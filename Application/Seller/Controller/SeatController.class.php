<?php
namespace Seller\Controller;
use Think\Controller;
use Org\WeChar\Wx_Api;
class SeatController extends CommonController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {

		$stoken = $this->stoken;	//获取stoken的值
		$Tname = $_POST['Tname'];
		$data = array();
		$data['Tname'] = $Tname;
		$data['stoken'] = $stoken;
		if($stoken && $Tname) {
			$result = M()->table('RS_Tableinfo')->add($data);
		}
		$allpos = M()->query("SELECT ID,Tname,CONVERT(varchar(20),CreateDate,120) as CreateDate FROM RS_Tableinfo WHERE stoken='{$stoken}'");
		$this->assign('allpos',$allpos);
		$this->display();
	}
	/*
	删除座位号
	*/
	public function del() {
		$id = $_POST['id'];
		if($id) {
			$res = M('Tableinfo')->where('ID=%d',$id)->delete();
			if($res) {
				$msg['status'] = "success";
			}else {
				$msg['status'] = "error";
				$msg['info'] = "处理失败";
			}
		}
		echo json_encode($msg);
	}
	/*
	座位二维码生成
	*/
	public function getQr() {
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// 获取ID值
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('Home/Table/Singlepoint',array('tableid'=>$_GET['id'],'stoken'=>$this->stoken,'once'=>1,'inred'=>'true'));
		 // 纠错级别：L、M、Q、H
		$level = 'L';
		// 点的大小：1到10,用于手机端4就可以了
		$size = 4;
		// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
            //$path = "images/";
            // 生成的文件名
            //$fileName = $path.$size.'.png';
		// QRcode::png($data, false, $level, $size);
		echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";;

	}

	public function GGinfos(){
		$stoken = $this->stoken;	//获取stoken的值
		// var_dump($stoken);exit();
		$gginfo =  file_get_contents('Public/json/'.$stoken.'gginfos.json');
		$gginfo = json_decode($gginfo,true);
		if ($gginfo) {
		 $this->assign('gginfo',$gginfo['gginfo']);
	 } else {
		 $this->assign('gginfo',null);
	 }
		$this->display();
	}
	public function savegginfo(){
		$stoken = $this->stoken;	//获取stoken的值
		$info['gginfo']=$_POST['gginfo'];
		$res =  file_put_contents('Public/json/'.$stoken.'gginfos.json',json_encode($info));
		if ($res) {
		 $this->success('保存成功');
	 } else {
		 $this->error('保存失败');
	 }
	}

	public function getMsg() {
		// $info = M()->query("SELECT　MsgReceverName FROM RS_Store WHERE stoken='{$this->stoken}'");
		$info = M()->table('RS_Store')->where("stoken='%s'",$this->stoken)->find();
		if(empty($info['MsgReceverName'])) {
			$pagedata['msg'] = '暂无接收人';
		}else {
			$pagedata['msg'] = $info['MsgReceverName'];

		}
		$this->assign($pagedata);
		$this->display();
	}

	public function qrcode() {
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$url = "http://".$_SERVER['HTTP_HOST'].U('Seller/Base/WXinfo',array('stoken'=>$this->stoken));
		$level = 'L';
		$size = 6;
		\QRcode::png($url,false,$level,$size);

	}







}






















?>
