<?php
namespace Seller\Controller;
use Think\Controller;
class BaseSettingController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function home(){
		$count=M()->table('RS_HomeImg')->where("token='%s'",$this->token)->count();
		$page= new \Think\Page($count,5);
		$imgs=M()->table('RS_HomeImg')->where("token='%s'",$this->token)->order('Sort')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign(array('imgs'=>$imgs,'jsondata'=>json_encode($imgs),'page'=>$page->show()));
		$this->display();
	}

	public function saveImg(){
		$data['ImgPath']=$_POST['ImgPath'];
		$data['Sort']=$_POST['Sort'];
		$data['ImgUrl']=$_POST['ImgUrl'];
		$data['IsShow']=$_POST['IsShow'];
		$data['token']=$this->token;
		// var_dump($data);
		if ($_POST['ID']) {
			$data['LastUpdateDate']=date("Y-m-d H:i:s",time());
			if (M()->table('RS_HomeImg')->where('ID=%d',$_POST['ID'])->save($data)) {
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else{
			if (M()->table('RS_HomeImg')->add($data)) {
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}
	}

	public function delImg(){
		$id=$_GET['id'];
		if (M()->table('RS_HomeImg')->where('ID=%d',$id)->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	public function show(){
		$statu=$_POST['statu'];
		$id=$_POST['id'];
		if ($statu=='true') {
			if (M()->table('RS_HomeImg')->where('ID=%d',$id)->setField('IsShow',1)) {
				echo "success";
			}else{
				echo "error";
			}
		}else{
			if (M()->table('RS_HomeImg')->where('ID=%d',$id)->setField('IsShow',0)) {
				echo "success";
			}else{
				echo "error";
			}
		}
	}

	public function yunfei(){
		$count=M()->table('RS_Freight')->where("Blong=%d and token='%s' and stoken='%s'",array(1,$this->token,$this->stoken))->count();
		$page= new \Think\Page($count,5);
		$yfs=M()->table('RS_Freight')->where("Blong=%d and token='%s' and stoken='%s'",array(1,$this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($yfs as &$yf) {
			$yf['LogisticsId']=M()->table('RS_Logistics')->where("ID=%d",$yf['LogisticsId'])->getField('Name');
			$yf['Area']=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($yf['ID'],'1'))->getField('Area',true);
			$yf['Area1']=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($yf['ID'],'2'))->getField('Area',true);
		}
		$this->assign('Freights',$yfs);
		$this->assign('page',$page->show());
		$this->display();
	}

	public function addYF(){
		if (IS_POST) {
			$data['Name']=$_POST['name'];
			$data['Opiece']=$_POST['Opiece'];
			$data['AddWeight']=$_POST['AddWeight'];
			$data['FirstWeight']=$_POST['FirstWeight'];
			$data['LogisticsId']=$_POST['LogisticsId'];
			$data['Blong']=1;
			$data['Oadd']=$_POST['Oadd'];
			$data['token']=$this->token;
			$data['stoken']=$this->stoken;
			if ($_POST['Tpiece']) {
				$data['Tpiece']=$_POST['Tpiece'];
			}else{
				$data['Tpiece']=0;
			}
			if ($_POST['Tadd']) {
				$data['Tadd']=$_POST['Tadd'];
			}else{
				$data['Tadd']=0;
			}

			$data['Remarks']=$_POST['Remarks'];

			$province=$_POST['province'];
			$province1=$_POST['province1'];
			if ($_POST['ID']) {
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$model=M();
				$model->startTrans();
				$Fid=$model->table('RS_Freight')->where('ID=%d',$_POST['ID'])->save($data);
				// echo $model->getlastsql();exit();
				$dres=$model->table('RS_Freight_Area')->where('FreightID=%d',$_POST['ID'])->delete();
				$p=true;
				$p1=true;
				foreach ($province as $pro) {
					$pd=array('Price'=>1,'FreightID'=>$_POST['ID'],'Area'=>$pro);
					if (!$model->table('RS_Freight_Area')->add($pd)) {
						$p=false;
						break;
					}
				}
				foreach ($province1 as $pro1) {
					$pd1=array('Price'=>2,'FreightID'=>$_POST['ID'],'Area'=>$pro1);
					if (!$model->table('RS_Freight_Area')->add($pd1)) {
						$p1=false;
						break;
					}
				}
				// var_dump($Fid,$p,$p1,$dres);exit();
				if ($Fid && $p && $p1) {
					$model->commit();
					$this->success('修改成功',U('BaseSetting/yunfei'));
				}else{
					$model->rollback();
					$this->error('修改失败');
				}
			}else{
				$model=M();
				$model->startTrans();
				$Fid=$model->table('RS_Freight')->add($data);
				// echo $model->getlastsql();exit();
				$p=true;
				$p1=true;
				foreach ($province as $pro) {
					$pd=array('Price'=>1,'FreightID'=>$Fid,'Area'=>$pro);
					if (!$model->table('RS_Freight_Area')->add($pd)) {
						$p=false;
						break;
					}
				}
				foreach ($province1 as $pro1) {
					$pd1=array('Price'=>2,'FreightID'=>$Fid,'Area'=>$pro1);
					if (!$model->table('RS_Freight_Area')->add($pd1)) {
						$p1=false;
						break;
					}
				}
				// var_dump($Fid,$p,$p1);exit();
				if ($Fid && $p && $p1) {
					$model->commit();
					$this->success('添加成功',U('BaseSetting/yunfei'));
				}else{
					$model->rollback();
					$this->error('添加失败');
				}
			}
		}else{
			$logistics=M()->table('RS_Logistics')->where("token='%s'",$this->token)->select();
			$this->assign('area',C('AREA'));
			$this->assign('logs',$logistics);
			$this->display();
		}
	}


	public function editYF(){
		$id=$_GET['id'];
		$info=M()->table('RS_Freight')->where('ID=%d',$id)->find();
		$area=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($id,'1'))->getField('Area',true);
		$area1=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($id,'2'))->getField('Area',true);
		$this->assign(array('info'=>$info,'area'=>C('AREA'),'area1'=>$area,'area2'=>$area1));
		$logistics=M()->table('RS_Logistics')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		$this->assign('logs',$logistics);
		$this->display('addYF');
	}
	/**
	 * 删除运费模板
	 */
	public function delYF(){
		$id=$_GET['id'];
		M()->startTrans();
		$delarea=M()->table('RS_Freight_Area')->where('FreightID=%d',$id)->delete();
		$delfre=M()->table('RS_Freight')->where('ID=%d',$id)->delete();
		if ($delarea && $delfre) {
			M()->commit();
			$this->success('删除成功');
		}else{
			M()->rollback();
			$this->error('删除失败');
		}
	}

	/**
	 * 积分相关设置
	 */
	public function score(){
		$model=M();
		if (IS_POST) {
			// var_dump($_POST);exit;
			$score=intval(trim($_POST['score']));
			if ($_POST['switchs']=='on') {
				$switchs=2;
			}else{
				$switchs=1;
			}
			$type=$_POST['type'];
			$adds=intval(trim($_POST['adds']));
			$days=intval(trim($_POST['days']));
			$scores=intval(trim($_POST['scores']));
			$id=$_POST['id'];
			if ($type=='sign') {
				if ($scores && $score && $days && $adds) {
					$data=array('score'=>$score,'type'=>$type,'adds'=>$adds,'scores'=>$scores,'days'=>$days,'switchs'=>$switchs,'token'=>$this->token);
					if ($id) {
						if ($model->table('RS_Scoreset')->where("ID=%d",$id)->save($data)) {
							$this->success('设置成功');
						}else{
							$this->error('设置失败');
						}
					}else{
						if ($model->table('RS_Scoreset')->add($data)) {
							$this->success('设置成功');
						}else{
							$this->error('设置失败');
						}
					}
				}else{
					$this->error('请填写完整数据');
				}
			}else{
				if ($score) {
					$data=array('score'=>$score,'type'=>$_POST['type'],'switchs'=>$switchs,'token'=>$this->token);
					if ($id) {
						if ($model->table("RS_Scoreset")->where("ID=%d",$id)->save($data)) {
							$this->success('设置成功');
						}else{
							$this->error('设置失败');
						}
					}else{
						if ($model->table("RS_Scoreset")->add($data)) {
							$this->success('设置成功');
						}else{
							$this->error('设置失败');
						}
					}
				}else{
					$this->error('请填写积分数');
				}
			}
		}else{
			$sign=$model->table('RS_Scoreset')->where("type='%s' and token='%s'",array('sign',$this->token))->find();
			$cons=$model->table('RS_Scoreset')->where("type='%s' and token='%s'",array('cons',$this->token))->find();
			$login=$model->table('RS_Scoreset')->where("type='%s' and token='%s'",array('login',$this->token))->find();
			$extends=$model->table('RS_Scoreset')->where("type='%s' and token='%s'",array('extends',$this->token))->find();
			// echo "<pre>";
			// var_dump($sign,$cons,$login,$extends);
			$this->assign(array('sign'=>$sign,'login'=>$login,'cons'=>$cons,'extends'=>$extends));
			$this->display();
		}
	}

	/**
	 * 设置默认运费模板
	 */
	public function setMr(){
		$fid=$_GET['fid'];
		M()->table('RS_Freight')->where("Blong=%d and token='%s'",array(1,$this->token))->setField('IsSet',0);
		if (M()->table('RS_Freight')->where('ID=%d',$fid)->setField('IsSet',1)) {
			$this->success('设置成功');
		}else{
			$this->error('设置失败');
		}
	}

	/**
	 * 提现/退款时间限制
	 */
	public function timeset(){
		$time=$this->MSL('merchant')->where("token='%s'",$this->token)->find();
		$evals=M()->table('RS_Defaulteval')->where("token='%s'",$this->token)->select();
		$pinfos=M()->table('RS_Product')->where("Token='%s' and IsShelves='%s'",array($this->token,'1'))->select();
		$this->assign('pinfos',$pinfos);
		$top=M()->query("SELECT TOP 1 ph.*,p.ProName FROM RS_ProductOnHome ph LEFT JOIN RS_Product p ON ph.ProId=p.ProId WHERE ph.token='".$this->token."' and ph.Position='1'");
		// $top=M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'1'))->find();
		$lefts=M()->query("SELECT TOP 2 ph.*,p.ProName FROM RS_ProductOnHome ph LEFT JOIN RS_Product p ON ph.ProId=p.ProId WHERE ph.token='".$this->token."' and ph.Position='2'");
		// $lefts=M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'2'))->select();
		$pagedata['top']=$top[0];
		$pagedata['lefts']=$lefts;
		$pagedata['time']=$time;
		$pagedata['evals']=$evals;
		$this->assign($pagedata);
		$this->display();
	}

	public function saveTime(){
		$days=$_POST['time'];
		if ($_POST['type']=='tx') {
			if ($this->MSL('merchant')->where("token='%s'",$this->token)->setField('TXtime',$days)) {
				echo json_encode('success');
			}else{
				echo json_encode('error');
			}
		}elseif ($_POST['type']=='storename') {
			if ($this->MSL('merchant')->where("token='%s'",$this->token)->setField('storeName',$days)) {
				echo json_encode('success');
			}else{
				echo json_encode('error');
			}
		}elseif ($_POST['type']=='sendaddr') {
			if ($this->MSL('merchant')->where("token='%s'",$this->token)->setField('sendAddr',$days)) {
				echo json_encode('success');
			}else{
				echo json_encode('error');
			}
		}elseif ($_POST['type']=='defaulteval') {
			if (M()->table('RS_Defaulteval')->add(array('content'=>$days,'token'=>$this->token))) {
				echo json_encode('success');
			}else{
				// echo M()->getlastsql();
				echo json_encode('error');
			}
		}else{
			if ($this->MSL('merchant')->where("token='%s'",$this->token)->setField('TKtime',$days)) {
				echo json_encode('success');
			}else{
				echo json_encode('error');
			}
		}
	}

	 /**
	  * 删除评价
	  */
	 public function delval(){
	 	$id=$_GET['id'];
	 	if (M()->table('RS_Defaulteval')->where("token='%s' and ID=%d",array($this->token,$id))->delete()) {
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

	 /**
	  * 2.0首页商品设置
	  */
	 public function productonhome(){
	 	$type=$_GET['type'];
	 	if ($type=='1') {
	 		if (M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'1'))->find()) {
	 			if (M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'1'))->setField('ProId',$_POST['ProId'])) {
	 				$this->success('保存成功');
	 			}else{
	 				$this->error('保存失败1');
	 			}
	 		}else{
	 			if (M()->table('RS_ProductOnHome')->add(array('ProId'=>$_POST['ProId'],'Position'=>'1','token'=>$this->token))) {
	 				$this->success('保存成功');
	 			}else{
	 				// echo M()->getlastsql();exit();
	 				$this->error('保存失败2');
	 			}
	 		}
	 	}else{
	 		$ProId=$_POST['ProId'];
	 		if (M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'2'))->find()) {
	 			M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'2'))->delete();
	 		}
	 		foreach ($ProId as $pid) {
	 			if (!M()->table('RS_ProductOnHome')->add(array('ProId'=>$pid,'Position'=>'2','token'=>$this->token))) {
	 				$this->error('保存失败3');
	 				break;
	 			}
	 		}
	 		$this->success('保存成功');
	 	}
	 }


	 public function defaultrmk(){
	 	if (IS_POST) {
	 		$db=array();
	 		$db['type']='2';
	 		$db['stoken']=$this->stoken;
	 		$db['content']=$_POST['content'];
	 		$db['token']=$this->token;
	 		if ($id=M()->table('RS_Defaulteval')->add($db)) {
	 			$msg['status']='success';
	 			$msg['id']=$id;
	 		}else{
	 			$msg['status']='error';
	 			$msg['info']='添加失败';
	 		}
	 		$this->ajaxReturn($msg);
	 	}else{
	 		$pagedata['rmks']=M()->table('RS_Defaulteval')->where("type='2' and stoken='%s'",$this->stoken)->select();
	 		$this->assign($pagedata);
	 		$this->display();
	 	}
	 }

	 public function deldefrmk(){
	 	$id=$_POST['id'];
	 	if (M()->table('RS_Defaulteval')->where("ID=%d",$id)->delete()) {
	 		$msg['status']='success';
	 	}else{
	 		$msg['status']='error';
	 		$msg['info']='处理失败';
	 	}
	 	$this->ajaxReturn($msg);
	 }


}









	?>
