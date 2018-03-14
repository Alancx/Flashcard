<?php
namespace Seller\Controller;
use Think\Controller;
class WarehouseController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
		$this->display();
	}

	public function add(){
		$Warehouse=M()->table('RS_WarehouseList')->order('Sort')->select();
		foreach ($Warehouse as &$w) {
			$cobj=$w['CreateDate'];
			foreach ($cobj as $k => $v) {
				if ($k=='date') {
					$w['CreateDate']=$v;
				}
			}
		}
		$this->assign(array('Warehouse'=>$Warehouse));
		$this->display();
	}

	public function savehouse(){
		$str="abcdefghijklmnopqrstuvwxyz0123456789";
		$card=substr(str_shuffle($str), mt_rand(0,30),4);
		$WarehouseCard=$card;
		if (M()->table('WarehouseList')->where("TableName='%s'","RS_Warehouse_".$WarehouseCard)->find()) {
			$WarehouseCard=substr(str_shuffle($str.$number), mt_rand(0,30),5);
		}
		$data['WarehouseCard']=$WarehouseCard;
		$data['WarehouseName']=$_POST['WarehouseName'];
		$data['TableName']="RS_Warehouse_".$WarehouseCard;
		if (strlen($_POST['Sort'])<2) {
			$data['Sort']="00".$_POST['Sort'];
		}elseif (strlen($_POST['Sort'])<3) {
			$data['Sort']="0".$_POST['Sort'];
		}else{
			$data['Sort']=$_POST['Sort'];
		}
            $procedure =<<<TBL
DECLARE	@return_value int
EXEC	@return_value = [dbo].[P_CreateWarehouse]
		@index = N'{$WarehouseCard}'

TBL;
		// var_dump($procedure);exit();
		$model=M();
		$model->startTrans();
		$wres=$model->table('RS_WarehouseList')->add($data);
		$cres=true;
		if (!is_array($model->setIsProc(true)->query($procedure))) {
			$cres=false;
		}
		if ($cres && $wres) {
			$model->commit();
			$this->success('添加成功');
		}else{
			var_dump($cres);
			var_dump($wres);exit();
			$model->rollback();
			$this->error('添加失败');
		}
	}


	public function supplier(){
		$this->assign('pagename','供应商添加');
		$this->display();
	}

	public function saveSupplier(){
		$data['Supplier']=$_POST['Supplier'];
		$data['Name']=$_POST['Name'];
		$data['Phone']=$_POST['Phone'];
		$data['Tel']=$_POST['Tel'];
		$data['Email']=$_POST['Email'];
		$data['Fax']=$_POST['Fax'];
		$data['Address']=$_POST['Address'];
		$data['Company']=$_POST['Company'];
		$data['Remarks']=$_POST['Remarks'];
		$data['token']=$this->token;
		if ($_POST['ID']) {
			if (M()->table('RS_Supplier')->where('ID=%d',$_POST['ID'])->save($data)) {
				$this->success('操作成功',U('Warehouse/suppliers'));
			}else{
				$this->error('操作失败');
			}
		}else{
			if (M()->table('RS_Supplier')->add($data)) {
				$this->success('操作成功',U('Warehouse/suppliers'));
			}else{
				// echo M()->getlastsql();exit;
				$this->error('操作失败');
			}
		}
	}

	public function suppliers(){
		$count=M()->table('RS_Supplier')->where("IsDelete=0 and token='".$this->token."'")->count();
		$page=new \Think\Page($count,20);
		$suppliers=M()->table('RS_Supplier')->where("IsDelete=0 and token='".$this->token."'")->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($suppliers as &$sup) {
			$cdate=$sup['CreateDate'];
			$ldate=$sup['LastLoginDate'];
			foreach ($cdate as $k => $v) {
				if ($k=='date') {
					$sup['CreateTime']=$v;
				}
			}
			foreach ($ldate as $lk => $lv) {
				if ($lk=='date') {
					$sup['LastUpdateTime']=$lv;
				}
			}
		}
		$this->assign(array('suppliers'=>$suppliers,'page'=>$page->show()));
		$this->display();
	}

	public function editSupplier(){
		$id=$_GET['sid'];
		$sinfo=M()->table('RS_Supplier')->where('ID=%d',$id)->find();
		$this->assign(array('sinfo'=>$sinfo,'pagename'=>'供应商修改'));
		$this->display('supplier');
	}

	public function delSupplier(){
		$id=$_GET['id'];
		// var_dump($id);exit();
		if (M()->table('RS_Supplier')->where('ID=%d',$id)->setField('IsDelete',1)) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	public function logistics(){
		$infos=M()->table('RS_Logistics')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		foreach ($infos as &$info) {
			$cobj=$info['CreateDate'];
			foreach ($cobj as $k => $v) {
				if ($k=='date') {
					$info['CreateDate']=$v;
				}
			}
		}
		$this->assign('infos',$infos);
		$this->display();
	}

	public function savewuliu(){
		// var_dump($_POST);
		$data['Name']=$_POST['Name'];
		$data['Number']=$_POST['Number'];
		$data['token']=$this->token;
		$data['stoken']=$this->stoken;
		if ($_POST['IsDefault']) {
			$data['IsDefault']=1;
		}else{
			$data['IsDefault']=0;
		}
		if (M()->table('RS_Logistics')->add($data)) {
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}

	public function changemoren(){
		$id=$_POST['id'];
		$statu=$_POST['statu'];
		if ($statu=='yes') {
			$res=M()->table('RS_Logistics')->where('ID!=%d',$id)->setField('IsDefault',0);
			// var_dump(M()->getlastsql());
			if (M()->table('RS_Logistics')->where('ID=%d',$id)->setField('IsDefault',1)) {
				echo "success";
			}else{
				echo "error";
			}
		}else{
			if (M()->table('RS_Logistics')->where('ID=%d',$id)->setField('IsDefault',0)) {
				echo "success";
			}else{
				echo "error";
			}
		}
	}

	public function searchSupp(){
		$Supplier=$_POST['Supplier'];
		$count=M()->table('RS_Supplier')->where("token='".$this->token."' and Supplier like '%".$Supplier."%'")->count();
		$page=new \Think\Page($count,20);
		$suppliers=M()->table('RS_Supplier')->where("token='".$this->token."' and Supplier like '%".$Supplier."%'")->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($suppliers as &$sup) {
			$cdate=$sup['CreateDate'];
			$ldate=$sup['LastLoginDate'];
			foreach ($cdate as $k => $v) {
				if ($k=='date') {
					$sup['CreateTime']=$v;
				}
			}
			foreach ($ldate as $lk => $lv) {
				if ($lk=='date') {
					$sup['LastUpdateTime']=$lv;
				}
			}
		}
		$this->assign(array('suppliers'=>$suppliers,'page'=>$page->show()));
		$this->display('suppliers');
	}


/**
	 * 收银台
	 */
	public function ScanPay(){
		$this->error('未知的功能，請重新分配權限',U('Auth/group'));
		$pros=M()->table('RS_Product')->where("token='%s' and stoken='%s' and IsScan<>'%s'",array($this->token,$this->stoken,'scan'))->getField('ProId,ProName');
		$cpro=M()->table('RS_Product')->where("token='%s' and stoken='%s' and IsScan='%s'",array($this->token,$this->stoken,'scan'))->select();
		$stores=M()->table('RS_Store')->where("token='%s' and stoken='%s' and IsCheck='%s'",array($this->token,$this->stoken,'1'))->select();
		$emps=$this->MSL('user')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		// echo "<pre>";
		// var_dump($pros);exit();
		// var_dump($cpro);exit();
		$pagedata['emps']=$emps;
		$pagedata['stores']=$stores;
		$pagedata['cpros']=$cpro;
		$pagedata['pros']=$pros;
		$pagedata['token']=$this->token;
		$this->assign($pagedata);
		define('FPAGE','SHOUYIN');
		$this->display();
	}

	/**
	 * 二维码展示
	 */
	public function showQr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$type=$_GET['type'];
		$sid=$_GET['sid'];
		$mid=$_GET['mid'];
		if ($type=='scanpay') {
			$url='http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/?token='.$this->token.'&sid='.$sid.'&mid='.$mid.'&stoken='.$this->stoken;
		}
		if ($type=='msg') {
			$url='http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/msg.html?token='.$this->token.'&sid='.$sid.'&mid='.$mid;
		}
		if ($type=='setrecever') {
			$url='http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/?token='.$this->token.'&sid='.$sid.'&setRecever=1';
		}
		// echo $url;
		// var_dump($url);
		$level="L";
		// $filename='./Uploads/1.png';
		$size=4;
		// \QRcode::png($url,$filename,$level,$size,'2');
		echo \QRcode::png($url,false,$level,$size,'2');

	}

	/**
	 * 收银台商品设置
	 */
	public function saveScanPro(){
		$pid=$_POST['ProId'];
		$type=$_POST['type'];
		if ($type=='add') {
			$info='scan';
		}else{
			$info='0';
		}
		if (M()->table('RS_Product')->where("token='%s' and ProId='%s' and stoken='%s'",array($this->token,$pid,$this->stoken))->setField('IsScan',$info)) {
			if ($type=='add') {
				$this->success('设置成功');
			}else{
				echo json_encode("success");
			}
		}else{
			if ($type=='add') {
				$this->error('设置失败');
			}else{
				echo json_encode('error');
			}
		}
	}






}











 ?>
