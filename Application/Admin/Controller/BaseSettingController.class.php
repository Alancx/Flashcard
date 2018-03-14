<?php
namespace Admin\Controller;
use Think\Controller;
class BaseSettingController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function home(){
		// $count=M()->table('RS_HomeImg')->where("token='%s'",$this->token)->count();
		// $page= new \Think\Page($count,5);
		// $imgs=M()->table('RS_HomeImg')->where("token='%s'",$this->token)->order('Sort')->limit($page->firstRow.','.$page->listRows)->select();
		// $this->assign(array('imgs'=>$imgs,'jsondata'=>json_encode($imgs),'page'=>$page->show()));
		// define('FPAGE', 'BASE');
		//平台设置的小店的热卖商品
	$sqlStr="SELECT p.ProId,poh.[Position],(CASE WHEN (p.stoken='0' AND mp.Price IS NOT NULL) THEN mp.Price ELSE p.PriceRange
	 END) AS PriceRange,p.ProName,p.ProTitle,p.ProLogoImg,p.Price FROM
	 RS_Product p LEFT JOIN  RS_ProductOnHome poh ON poh.ProId = p.ProId
	 LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
		WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON
		p.ProId = mp.ProId WHERE p.token='".$this->token."' AND p.IsShelves=1 AND
	(p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."' OR poh.stoken='0') AND
	poh.[Position]='SHOP_HOT' ORDER BY poh.[Position]";
	$prohot=M()->query($sqlStr);
	$pagedata['prohot']=$prohot[0];
	////平台设置小店的新品
    $sqlStr="SELECT p.ProId,poh.[Position],(CASE WHEN (p.stoken='0' AND mp.Price IS NOT NULL) THEN mp.Price ELSE p.PriceRange
     END) AS PriceRange,p.ProName,p.ProTitle,p.ProLogoImg,p.Price FROM
     RS_Product p LEFT JOIN  RS_ProductOnHome poh ON poh.ProId = p.ProId
     LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
      WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON
      p.ProId = mp.ProId WHERE p.token='".$this->token."' AND p.IsShelves=1 AND
    (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."' OR poh.stoken='0') AND
    poh.[Position]='SHOP_NEW' ORDER BY poh.[Position]";
    $pronew=M()->query($sqlStr);
    $pagedata['pronew']=$pronew[0];
		///平台设置的小店的轮播图
   $pagedata['homeimg']=M()->table('RS_HomeImg')->where("token='%s' and stoken='%s'",array($this->token,0))->find();
	 $proinfo=M()->table('RS_Product')->where(array('IsShelves'=>1,'token'=>$this->token,'stoken'=>$this->stoken))->select();
		$pagedata['proinfo']=$proinfo;
	 $this->assign($pagedata);
		$this->display('BaseSetting/homeset');
	}
  public function homesave(){
		if (IS_POST) {
			$data=$_POST;
			if ($data['type']=='0') {
				M()->table('RS_HomeImg')->where("token='%s' and stoken='%s'",array($this->token,0))->delete();
				$savedata['ImgPath']=$data['imgurl'];
				$savedata['ImgUrl']=$data['imghref'];
				$savedata['Sort']='LB1';
				$savedata['token']=$this->token;
				$savedata['stoken']='0';
				if (M()->table('RS_HomeImg')->add($savedata)) {
					$this->ajaxReturn(array('status'=>'true','info'=>'true'));
				} else {
					$this->ajaxReturn(array('status'=>'error','info'=>'saveerror'));
				}
			} elseif ($data['type']=='1') {
				M()->table('RS_ProductOnHome')->where("token='%s' and stoken='%s' and Position='%s'",array($this->token,0,'SHOP_HOT'))->delete();
				$savedata['ProId']=$data['pid'];
				$savedata['Position']='SHOP_HOT';
				$savedata['token']=$this->token;
				$savedata['stoken']='0';
				if (M()->table('RS_ProductOnHome')->add($savedata)) {
					$this->ajaxReturn(array('status'=>'true','info'=>'true'));
				} else {
					$this->ajaxReturn(array('status'=>'error','info'=>'saveerror'));
				}
			} elseif ($data['type']=='2') {
				M()->table('RS_ProductOnHome')->where("token='%s' and stoken='%s' and Position='%s'",array($this->token,0,'SHOP_NEW'))->delete();
				$savedata['ProId']=$data['pid'];
				$savedata['Position']='SHOP_NEW';
				$savedata['token']=$this->token;
				$savedata['stoken']='0';
				if (M()->table('RS_ProductOnHome')->add($savedata)) {
					$this->ajaxReturn(array('status'=>'true','info'=>'true'));
				} else {
					$this->ajaxReturn(array('status'=>'error','info'=>'saveerror'));
				}
			}
		} else {
			$this->ajaxReturn(array('status'=>'error','info'=>'nopost'));
		}
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
		define('FPAGE', 'BASE');
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
			define('FPAGE', 'BASE');
			$this->display();
		}
	}


	public function editYF(){
		$id=$_GET['id'];
		$info=M()->table('RS_Freight')->where('ID=%d',$id)->find();
		$area=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($id,'1'))->getField('Area',true);
		$area1=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($id,'2'))->getField('Area',true);
		$this->assign(array('info'=>$info,'area'=>C('AREA'),'area1'=>$area,'area2'=>$area1));
		$logistics=M()->table('RS_Logistics')->where("token='%s'",$this->token)->select();
		$this->assign('logs',$logistics);
		define('FPAGE', 'BASE');
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
			define('FPAGE', 'BASE');
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
		$top=M()->query("SELECT ph.ProId,p.ProName,p.ProLogoImg FROM RS_ProductOnHome ph LEFT JOIN RS_Product p ON ph.ProId=p.ProId WHERE ph.token='".$this->token."' and ph.Position='1'");
		// $top=M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'1'))->find();
		$lefts=M()->query("SELECT TOP 2 ph.*,p.ProName FROM RS_ProductOnHome ph LEFT JOIN RS_Product p ON ph.ProId=p.ProId WHERE ph.token='".$this->token."' and ph.Position='2'");
		// $lefts=M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'2'))->select();
		$pinfos=M()->query("SELECT ProName,ProId FROM RS_Product WHERE ProId NOT IN (SELECT ProId FROM RS_ProductOnHome WHERE Position='1')");
		$pagedata['pinfos']=$pinfos;
		$pagedata['top']=$top;
		$pagedata['lefts']=$lefts;
		$pagedata['time']=$time;
		$pagedata['evals']=$evals;
		$this->assign($pagedata);
		define('FPAGE', 'BASE');
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
		}elseif ($_POST['type']=='cut') {
			if ($this->MSL('merchant')->where("token='%s'",$this->token)->setField('Cut',$days)) {
				echo json_encode('success');
			}else{
				echo json_encode('error');
			}
		}elseif ($_POST['type']=='mcut') {
			if ($this->MSL('merchant')->where("token='%s'",$this->token)->setField('Mcut',$days)) {
				echo json_encode('success');
			}else{
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
	 		if (M()->table('RS_ProductOnHome')->add(array('token'=>$this->token,'ProId'=>$_POST['ProId'],'Position'=>'1'))) {
	 			$this->success('添加成功');
	 		}else{
	 			$this->LOGS(M()->getlastsql());
	 			$this->error('添加失败');
	 		}
	 		// if (M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'1'))->find()) {
	 		// 	if (M()->table('RS_ProductOnHome')->where("token='%s' and Position='%s'",array($this->token,'1'))->setField('ProId',$_POST['ProId'])) {
	 		// 		$this->success('保存成功');
	 		// 	}else{
	 		// 		$this->error('保存失败1');
	 		// 	}
	 		// }else{
	 		// 	if (M()->table('RS_ProductOnHome')->add(array('ProId'=>$_POST['ProId'],'Position'=>'1','token'=>$this->token))) {
	 		// 		$this->success('保存成功');
	 		// 	}else{
	 		// 		// echo M()->getlastsql();exit();
	 		// 		$this->error('保存失败2');
	 		// 	}
	 		// }
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


	 /**
	  * 模板消息配置
	  */
	 public function modelmsg(){
	 	if (IS_POST) {
	 		// var_dump($_POST);exit();
	 		$data['TemplateId']=$_POST['TemplateId'];
	 		$data['TemplateType']=$_POST['TemplateType'];
	 		$data['Color']=$_POST['Color'];
	 		$data['token']=$this->token;
	 		if (M()->table('RS_TemplateMsg')->where("token='%s' and TemplateType='%s'",array($this->token,$_POST['TemplateType']))->find()) {
	 			$sData['TemplateId']=$_POST['TemplateId'];
	 			$sData['Color']=$_POST['Color'];
	 			if (M()->table('RS_TemplateMsg')->where("token='%s' and TemplateType='%s'",array($this->token,$_POST['TemplateType']))->setField($sData)) {
	 				$this->success('处理成功');
	 			}else{
	 				$this->error('处理失败');
	 			}
	 		}else{
	 			// var_dump(M()->getlastsql());exit();
	 			if (M()->table('RS_TemplateMsg')->add($data)) {
	 				$this->success('添加成功');
	 			}else{
	 				$this->LOGS(M()->getlastsql());
	 				$this->error('添加失败');
	 			}
	 		}
	 	}else{
	 		$msginfos=M()->table('RS_TemplateMsg')->where("token='%s'",$this->token)->select();
	 		$pagedata['msginfos']=$msginfos;
	 		// var_dump(json_encode($msginfos));exit();
	 		$pagedata['msgs']=json_encode($msginfos);
	 		// echo "<pre>";
	 		// var_dump($pagedata);
	 		$this->assign($pagedata);
			define('FPAGE', 'BASE');
	 		$this->display();
	 	}
	 }
	 public function pageset(){
		if (IS_POST) {

			if (file_put_contents('./Public/pagesethome/pageconfig.json', $_POST["homedata"])) {
				// 	$this->success('处理完成');
				$this->ajaxReturn(array('status'=>'true','info'=>'true'));
			}else{
				// 	$this->error('处理失败');
				$this->ajaxReturn(array('status'=>'false','info'=>'false'));
			}
		}else{
      /// 读取保存的json 文件数据
			$info=json_decode(file_get_contents('./Public/pagesethome/pageconfig.json'),true);
			$pagedata['info']=$info;
			//////商品分类数据/////////
			$classinfo=M()->query("SELECT * FROM [RS_ProductClass] WHERE [ClassGrade] = '2' AND [IsVisible] = '1' AND [token] = '".$this->token."' AND ParentClassId IN(SELECT ClassId FROM [RS_ProductClass] WHERE [ClassGrade] = '1' AND [IsVisible] = '1' AND [token] = '".$this->token."') ORDER BY ClassSort");
			$pagedata['classinfo']=$classinfo;
			///////平台总店商品信息///////////////
			$proinfo=M()->table('RS_Product')->where(array('IsShelves'=>1,'token'=>$this->token,'stoken'=>$this->stoken))->select();
			$pagedata['proinfo']=$proinfo;
			$this->assign($pagedata);
			$this->display('BaseSetting/mallpageset');
			// $this->display();
		}
	 }

	 /**
	  * 删除首页商品
	  */
	 public function delonhome(){
	 	$pid=$_POST['pid'];
	 	if (M()->table('RS_ProductOnHome')->where("token='%s' and ProId='%s'",array($this->token,$pid))->delete()) {
	 		echo json_encode(array('status'=>'success'));
	 	}else{
	 		echo json_encode(array('status'=>'error'));
	 	}
	 }

	 public function GGinfo(){
		 $gginfo =  file_get_contents('Public/json/gginfos.json');
		 $gginfo = json_decode($gginfo,true);
		 if ($gginfo) {
		 	$this->assign('gginfo',$gginfo['gginfo']);
		} else {
			$this->assign('gginfo',null);
		}
		 $this->display();
	 }
	 public function savegginfo(){
		 $info['gginfo']=$_POST['gginfo'];
		 $res =  file_put_contents('Public/json/gginfos.json',json_encode($info));
		 if ($res) {
		 	$this->success('保存成功');
		} else {
			$this->error('保存失败');
		}
	 }

























	}









	?>
