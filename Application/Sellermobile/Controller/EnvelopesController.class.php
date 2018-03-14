<?php
namespace Sellermobile\Controller;
use Think\Controller;
class EnvelopesController extends CommonController{
	public function index(){
		$result=$this->BM()->query("SELECT CONVERT(VARCHAR(19), StartDate, 120) as StartDate,CONVERT(VARCHAR(19), ExpiredDate, 120) as ExpiredDate,  CouponId, CouponName,Rules,Type FROM RS_Coupon WHERE stoken='$this->stoken' AND token='$this->token' AND Forsharer=1 AND IsEnable=1");
		foreach ($result as $key => $value) {
			if ($value['Type'] == '2') {
				$rulestemp = explode("/",$value['Rules']);
				$result[$key]['Rules'] = $rulestemp[1];
				$result[$key]['Totalrules'] = $rulestemp[0];
				$result[$key]['CouponName'] = '满减券(满'.$rulestemp[0].'元可用)';
			}
		}
		$row=$this->BM()->query("SELECT CONVERT(VARCHAR(19), StartDate, 120) as StartDate,CONVERT(VARCHAR(19), ExpiredDate, 120) as ExpiredDate,  CouponId, CouponName,Rules,Type FROM RS_Coupon WHERE stoken='$this->stoken' AND token='$this->token' AND Forshare=1 AND IsEnable=1");
		foreach ($row as $key => $value) {
			if ($value['Type'] == '2') {
				$rulestemp = explode("/",$value['Rules']);
				$row[$key]['Rules'] = $rulestemp[1];
				$row[$key]['Totalrules'] = $rulestemp[0];
				$row[$key]['CouponName'] = '满减券(满'.$rulestemp[0].'元可用)';
			}
		}
		// var_dump($result);
		// var_dump($row);
		$list=$this->BM()->query("SELECT CONVERT(VARCHAR(19), StartDate, 120) as StartDate,CONVERT(VARCHAR(19), ExpiredDate, 120) as ExpiredDate,  CouponId, CouponName,Rules,Type FROM RS_Coupon WHERE stoken='$this->stoken' AND token='$this->token' AND IsEnable=1");
		foreach ($list as $key => $value) {
			if ($value['Type'] == '2') {
				$rulestemp = explode("/",$value['Rules']);
				$list[$key]['Rules'] = $rulestemp[1];
				$list[$key]['Totalrules'] = $rulestemp[0];
				$list[$key]['CouponName'] = '满减券(满'.$rulestemp[0].'元可用)';
			}
		}

		$filename = $this->stoken.'inredinfo.json';
		$tempinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
		$this->assign('inredprice',$tempinfo['price']);
		$this->assign('row',$row[0]);
		$this->assign('result',$result[0]);
		$this->assign('list',$list);
		$this->assign('Title','红包列表');
		$this->display('Envelopes/index');
	}
	public function add(){
		$id=$_GET['id'];
		if($id=='add'){
			$type='add';
			$this->assign('type',$type);
			$this->assign('Title','红包设置');
			$this->display('Envelopes/setup');
		}else{
			$list=$this->BM()->query("SELECT CONVERT(VARCHAR(19), StartDate, 120) as StartDate,CONVERT(VARCHAR(19), ExpiredDate, 120) as ExpiredDate, Count, CouponName,Rules,Type FROM RS_Coupon WHERE CouponId='{$id}'");

			foreach ($list as $key => $value) {
				if ($value['Type'] == '2') {
					$rulestemp = explode("/",$value['Rules']);
					$list[$key]['Rules'] = $rulestemp[1];
					$list[$key]['Totalrules'] = $rulestemp[0];					
				}
			}


			$type=$id;
			$this->assign('type',$type);
			$this->assign('list',$list[0]);
			$this->assign('Title','红包设置');
			$this->display('Envelopes/setup');
		}

	}
	public function doadd(){
		$type=$_POST['type'];
		$data['Count']=$_POST['number'];
		$data['AfterCount']=$_POST['number'];
		$data['Rules']=$_POST['totalmoney'].'/'.$_POST['money'];
		$data['ExpiredDate']=$_POST['end'];
		$data['StartDate']=$_POST['start'];
		if($type=='add'){
			$str='abcdefghijklmnopqrstuvwxyz';
			$rndstr;	//用来存放生成的随机字符串
			for($i=0;$i<5;$i++)
			{
				$rndcode=rand(0,25);
				$rndstr.=$str[$rndcode];
			}
			$data['CouponId']=$rndstr.rand(10000,99999);
			$data['Type']=$_POST['name'];
			if($_POST['name']=='2'){
				$_POST['name']='满减券';
			}
			$data['CouponName']=$_POST['name'];
			$data['CreateDate']=date('Y-m-d H:i:s',time());
			$data['UserCount']='1';
			$data['stoken']=$this->stoken;
			$data['token']=$this->token;
			$list=$this->BM('Coupon')->add($data);
			if($list){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
			}
		}else{
			$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
			$row=$this->BM('Coupon')->where(array('CouponId'=>$type))->save($data);
			if($row){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
			}
		}
	}
	public function deleta(){
		$id=$_POST['id'];
		$data['IsEnable']=0;
		$row=$this->BM('Coupon')->where(array('CouponId'=>$id))->save($data);
		if($row){
			$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
		}else{
			$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
		}
	}
	public function updata(){
		$id=$_POST['id'];
		$data['Forsharer']=2;
		$row=$this->BM('Coupon')->where(array('CouponId'=>$id))->save($data);
		if($row){
			$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
		}else{
			$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
		}
	}
	public function update(){
		$id=$_POST['id'];
		$data['Forshare']=2;
		$row=$this->BM('Coupon')->where(array('CouponId'=>$id))->save($data);
		if($row){
			$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
		}else{
			$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
		}
	}
	public function up(){
		$this->BM()->startTrans();
		$id=$_POST['id'];
		$date['Forsharer']=0;
		$data['Forsharer']=1;
		$list=$this->BM('Coupon')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'Forsharer'=>1))->save($date);
		// var_dump($this->BM()->getlastsql());
		$row=$this->BM('Coupon')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'CouponId'=>$id))->save($data);
		// var_dump($this->BM()->getlastsql());exit;
		if($list!==false && $row){
			$this->BM()->commit();
			$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
		}else{
			$this->BM()->rollback();
			$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
		}
	}
	public function data(){
		$this->BM()->startTrans();
		$id=$_POST['id'];
		$date['Forshare']=0;
		$data['Forshare']=1;
		$list=$this->BM('Coupon')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'Forshare'=>1))->save($date);
		 // var_dump($this->BM()->getlastsql());
		$row=$this->BM('Coupon')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'CouponId'=>$id))->save($data);
		// var_dump($this->BM()->getlastsql());exit;
		if($list!==false && $row){
			$this->BM()->commit();
			$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
		}else{
			$this->BM()->rollback();
			$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
		}
	}
	// 保存进店红包
	public function saveinred(){
		$filename = $this->stoken.'inredinfo.json';
		$saveinredinfo['price'] = $_POST['redprice'];
		file_put_contents('Public/json/'.$filename, json_encode($saveinredinfo));
		$this->ajaxReturn(array('status' => 'true', 'info' =>'SUCCESS'), 'JSON');
	}
}

?>
