<?php
namespace Adminmobile\Controller;
use Think\Controller;

class PublicController extends BaseController{
	public function login(){
		if (session('is_login')==1 && session('userinfo')!=false) {
			$this->redirect('Index/index');
		}
		$this->assign('Title','登录');
		$this->display();
	}
	public function logining(){
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		if ($userinfo=$this->UM('user')->where("userName='%s' and password='%s'",array($username,$password))->find()) {
				$uinfo['ID']=$userinfo['id'];
				$uinfo['userName']=$userinfo['userName'];
				if($userinfo['IsLogin']==1){
				session('HeadImgUrl',$userinfo['HeadImgUrl']);
				session('token',$userinfo['token']);
				session('stoken',$userinfo['stoken']);
				session('department',$userinfo['DepartmentName']);
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
	public function getUserQrcode()
	{
        ob_clean();
        vendor('PHPQR.phpqrcode');
        // $qrcodeImg='<img src="'.\QRcode::png('http://'.$_SERVER['HTTP_HOST'].U('Home/Account/Register',array('uid'=>session('userinfo')['userName'])),false,'L',4,'2').'"/>';
				$qrcodeImg='<img src="'.\QRcode::png('http://'.$_SERVER['HTTP_HOST'].U('Home/Index/Index',array('uid'=>session('userinfo')['userName'])),false,'L',4,'2').'"/>';
				echo $qrcodeImg;
	}

}

?>
