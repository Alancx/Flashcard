<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * groupmanger  menugroup  menu  usergroup user(employee)均为mysql操作 使用MSL方法
 */
class AuthController extends CommonController{
	public function _initialize(){
		parent::_initialize();
		// var_dump($this->token);
	}

	public function group(){
		$count=$this->MSL('groupmanger')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
		$page= new \Think\Page($count,20);
		$groups=$this->MSL('groupmanger')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($groups as &$g) {
			$cobj=$g['CreateDate'];
			foreach ($cobj as $k => $v) {
				if ($k=='date') {
					$g['CreateDate']=$v;
				}
			}
		}
		$this->assign(array('groups'=>$groups,'jsondata'=>json_encode($groups),'page'=>$page->show()));
		define('FPAGE', 'YUANGONG');
		$this->display();
	}

	public function saveGroup(){
		$userinfo=session('userinfo');
		$data['GroupName']=$_POST['GroupName'];
		if ($_POST['Remarks']) {
			$data['Remarks']=$_POST['Remarks'];
		}
		$data['InputId']=$userinfo['ID'];
		$data['InputName']=$userinfo['userName'];
		$data['token']=$this->token;
		$data['stoken']=$this->stoken;
		if ($_POST['GroupId']) {
			if ($this->MSL('groupmanger')->where("GroupId=%d",$_POST['GroupId'])->save($data)) {
				$this->success('修改成功');
			}else{
				// echo M()->getlastsql();
				$this->error('修改失败');
			}
		}else{
			if ($this->MSL('groupmanger')->add($data)) {
				$this->success('添加成功');
			}else{
				// echo $this->MSL()->getlastsql();exit;
				$this->error('添加失败');
			}
		}
	}

	public function temp(){
		$data['ParentId']=$_POST['ParentId'];
		if ($_POST['ParentId']) {
			$data['Grade']=2;
		}else{
			$data['Grade']=1;
		}
		$data['MenuName']=$_POST['MenuName'];
		$data['MenuUrl']=$_POST['MenuUrl'];
		$data['Sort']=$_POST['Sort'];
		$data['IsEnable']=1;
		$data['RootId']=0;
		if (IS_POST) {
			if (M()->table('RS_Menu')->add($data)) {
				$this->success('ok');
			}else{
				// echo M()->getlastsql();exit();
				$this->error(':(');
			}
		}
		$datas=$this->MSL('menu')->where("Grade=1")->select();
		$this->assign('datas',$datas);
		$this->display();
	}

	public function distribute(){
		$roots=$this->MSL('menu')->where("Grade=1 and MenuType='user'")->select();
		$sons=$this->MSL('menu')->where("Grade=2 and MenuType='user'")->select();
		$ssons=$this->MSL('menu')->where("Grade=3 and MenuType='user'")->select();
		for ($i=0; $i < count($roots); $i++) {
			$temp=array();
			foreach ($sons as $s) {
				if ($roots[$i]['MenuId']==$s['ParentId']) {
					$tempSS=array();
					foreach ($ssons as $sso) {
						if ($s['MenuId']==$sso['ParentId']) {
							$tempSS[]=$sso;
						}
					}
					$s['sons']=$tempSS;
					$temp[]=$s;
				}
			}
			$roots[$i]['sons']=$temp;
		}
		$groups=$this->MSL('groupmanger')->select();
		if ($_GET['gid']) {
			$nodes=$this->MSL('menugroup')->where('GroupId=%d',$_GET['gid'])->getField('MenuId',true);
			$this->assign(array('nodes'=>$nodes,'GroupId'=>$_GET['gid'],'statu'=>'edit'));
		}
		$this->assign(array('menus'=>$roots,'groups'=>$groups));
		$this->assign('FPAGE','YUANGONG');
		$this->display();
	}

	public function saveDis(){
		$theme=C('DEFAULT_THEME')?C('DEFAULT_THEME').'/':'';
		$sinfo=session('userinfo');
		$GroupId=$_POST['GroupId'];
		$node=$_POST['son'];
		M()->startTrans();
		$statu=true;
		$res=true;
		if ($_POST['statu']=='edit') {
			$res=$this->MSL('menugroup')->where('GroupId=%d',$_POST['GroupId'])->delete();
		}
		foreach ($node as $no) {
			$temp=array('GroupId'=>$_POST['GroupId'],'MenuId'=>$no,'InputId'=>$sinfo['ID'],'InputName'=>$sinfo['userName']);
			if (!$this->MSL('menugroup')->add($temp)) {
				$statu=false;
				break;
			}
			$temp=array();
		}
		if ($statu) {
			$filename=dirname(__FILE__)."/../View/".$theme."Common/".$GroupId.".html";
			$newfile=dirname(__FILE__)."/../View/".$theme."Index/indexv".$GroupId.".html";
			$model=dirname(__FILE__)."/../View/".$theme."Common/header.html";
			$newmodel=dirname(__FILE__)."/../View/".$theme."Common/newmodel.html";
			$nodes=$this->MSL('menugroup')->where('GroupId=%d',$GroupId)->getField('MenuId',true);
			foreach ($nodes as $no) {
				$tempVar=$this->MSL('menu')->where("MenuId='%s'",$no)->find();
				if ($tempVar['Grade']=='1') {
					$root[]=$tempVar;
				}elseif($tempVar['Grade']=='2'){
					$sons[]=$tempVar;
				}else{
					$sson[]=$tempVar;
				}
			}
			for ($i=0; $i < count($root); $i++) {
				$temp=array();
				foreach ($sons as $s) {
					if ($root[$i]['MenuId']==$s['ParentId']) {
						$tempS=array();
						foreach ($sson as $sss) {
							if ($s['MenuId']==$sss['ParentId']) {
								$tempS[]=$sss;
							}
						}
						$s['sons']=$tempS;
						$temp[]=$s;
					}
				}
				$root[$i]['sons']=$temp;
			}
			$str='';
			$newStr='';
			foreach ($root as $son) {
				$url=$son['MenuUrl']?'{:U("'.$son['MenuUrl'].'")}':'###';
				$newStr.="<li <if condition=\"FPAGE eq '".$son['MenuUrl']."'\">class='active'</if>><a href=\"".$url."\"><i class=\"".$son['MenuIcon']."\"></i><span class=\"nav-label\">".$son['MenuName']."</span></a>";
				$str.="<li title='".$son['MenuName']."' <if condition=\"FPAGE eq '".$son['MenuUrl']."'\">class='active'</if>>
                        <a href=\"".$url."\"><i class=\"".$son['MenuIcon']."\"></i>&nbsp;&nbsp;<span class=\"nav-label\">".$son['MenuName']."</span> </a>";
                if ($son['sons']) {
                	$newStr.="<ul class=\"nav nav-second-level\">";
                	$str.="<ul class=\"nav nav-second-level\">";
                	foreach ($son['sons'] as $ss) {
	                	$surl=$ss['MenuUrl']?'{:U("'.$ss['MenuUrl'].'")}':'###';
	                	if ($surl=='###') {
	                		$cls='';
	                	}else{
	                		$cls='J_menuItem';
	                	}
                		$newStr.="<li><a class=\"".$cls."\" href=\"".$surl."\">".$ss['MenuName']."</a>";
                		$str.="<li><a href=\"".$surl."\">".$ss['MenuName']."</a>";
                		if ($ss['sons']) {
                			$newStr.="<ul class=\"nav nav-second-level\">";
                			$str.="<ul class=\"nav nav-second-level\">";
                			foreach ($ss['sons'] as $ssso) {
                				$ssurl=$ssso['MenuUrl']?'{:U("'.$ssso['MenuUrl'].'")}':'###';
                				$newStr.="<li><a class=\"J_menuItem\" href=\"".$ssurl."\">&nbsp;&nbsp;&nbsp;&nbsp;".$ssso['MenuName']."</a>";
                				$str.="<li><a href=\"".$ssurl."\">&nbsp;&nbsp;&nbsp;&nbsp;".$ssso['MenuName']."</a></li>";
                			}
                			$newStr.="</ul>";
                			$str.="</ul>";
                		}
                		$newStr.="</li>";
                		$str.="</li>";
                	}
                	$newStr.="</ul>";
                	$str.="</ul>";
                }
                $newStr.="</li>";
                $str.="</li>";
			}
			$strContent["{MenuContent}"]=$str;
			$baseModel=file_get_contents($model);
			file_put_contents($filename, strtr($baseModel, $strContent));
			$strContent["{MenuContent}"]=$newStr;
			$baseModel=file_get_contents($newmodel);
			file_put_contents($newfile, strtr($baseModel, $strContent));

			M()->commit();
			$this->success('保存成功',U('Auth/group'));
		}else{
			M()->rollback();
			$this->error('保存失败');
		}
	}

}










 ?>
