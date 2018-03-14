<?php
namespace Sellermobile\Controller;
use Think\Controller;
use Org\WeChar\Wx_Api;
use Org\WeChar\Wx_JSSDK;

class PublicController extends BaseController{
	public function login(){
		if ($_GET['type']=='1') {
			session_unset();
		}
		$openid=$_GET['openid'];
		if (strlen($openid)==28) {
			session('openid',$openid);
			if (session('is_login')==1 && session('userinfo')!=false) {
				$this->redirect('Index/Index');
			} else {
				$this->assign('Title','登录');
				$this->display();
			}
		} else {
			$this->getuseropenid();
		}
	}
	public function logining(){
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		if ($userinfo=$this->UM('user')->where("userName='%s' and password='%s'",array($username,$password))->find()) {
			$uinfo['ID']=$userinfo['id'];
			$uinfo['userName']=$userinfo['userName'];
			if($userinfo['IsLogin']==1){
				$shopinfo=$this->BM('store')->where("token='%s' and stoken='%s'",array($userinfo['token'],$userinfo['stoken']))->find();
				if (empty($shopinfo['openid'])) {
					session('useropenid',session('openid'));
					$this->BM('store')->where("token='%s' and stoken='%s'",array($userinfo['token'],$userinfo['stoken']))->save(array('openid'=>session('openid'),'MsgRecever'=>session('openid')));
				} else {
					session('useropenid',$shopinfo['openid']);
				}
				session('HeadImgUrl',$userinfo['HeadImgUrl']);
				session('token',$userinfo['token']);
				session('stoken',$userinfo['stoken']);
				session('department',$shopinfo['id']);
				session('is_login',1);
				session('userinfo',$uinfo);
				$this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
			} else{
				$this->ajaxReturn(array('status' => 'flase', 'info' => 'error-1'), 'JSON');
			}
		}else{
			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error-2'), 'JSON');
		}
	}


	/////////////获取openid//////////////
	public function getuseropenid(){
		if(empty($_GET['code']))
		{
			$redirect_url='http://'.$_SERVER['HTTP_HOST'].U('Sellermobile/Public/getuseropenid');
			$wxURL='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('WXAPPID').'&redirect_uri='.urlencode($redirect_url).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
			header("location:".$wxURL."");
		} else {
			$code=$_GET['code'];
			$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
			$uinfo=json_decode($resinfo,true);
			$this->redirect('Public/login',array('openid'=>$uinfo['openid']));
		}
	}

	public function getUserQrcode()
	{
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$qrcodeImg='<img src="'.\QRcode::png('http://'.$_SERVER['HTTP_HOST'].U('Home/Index/Index',array('stoken'=>session('stoken'),'once'=>'1','inred'=>'true')),false,'L',4,'2').'"/>';
		echo $qrcodeImg;
	}

	public function getCashQrcode()
	{
			$getCashCode=$this->BM('store')->WHERE("token='%s' and stoken='%s'",array(session('token'),session('stoken')))->getField('CashCode');
			if ($getCashCode) {
				ob_clean();
				vendor('PHPQR.phpqrcode');
				$qrcodeImg='<img src="'.\QRcode::png('GETCASH'.$getCashCode,false,'L',4,'2').'"/>';
				echo $qrcodeImg;
			} else {
				$str='0123456789';
				$phonecode='';
				for ($i=0; $i < 5; $i++) {
					$getcashcodes.=substr(str_shuffle($str), mt_rand(0,9),1);
				}
				$getcashcodes=md5($getcashcodes);
				if ($this->BM('store')->WHERE("token='%s' and stoken='%s'",array(session('token'),session('stoken')))->save(array('CashCode'=>$getcashcodes))) {
					ob_clean();
					vendor('PHPQR.phpqrcode');
					$qrcodeImg='<img src="'.\QRcode::png('GETCASH'.$getcashcodes,false,'L',4,'2').'"/>';
					echo $qrcodeImg;
				}
			}
	}


	/*
	座位二维码生成
	*/
	public function getQr() {
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// 获取ID值
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('Home/Table/Singlepoint',array('tableid'=>$_GET['id'],'stoken'=>session('stoken'),'once'=>1,'inred'=>'true'));
		$level = 'L';
		$size = 4;
		echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";

	}
  // 消息接收人二维码
	public function getmessageQrcode(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// 获取ID值
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('Seller/Base/WXinfo',array('stoken'=>session('stoken')));
		$level = 'L';
		$size = 4;
		echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";
	}

}

?>
