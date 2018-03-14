<?php
namespace Seller\Controller;
use Think\Controller;
class PublicController extends BaseController{
	public function _initialize(){
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'IPhone')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false) {
			$this->show('<h1>程序不支持手机/微信浏览器！请复制链接到电脑端浏览器访问</h1>');exit();
		}
	}
	public function login(){
		$this->display();
	}

	public function logining(){
		$tpeme=C('DEFAULT_THEME');
		$theme=$tpeme ? $tpeme.'/' : '';
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		if ($userinfo=$this->MSL('user')->where("userName='%s' and password='%s'",array($username,$password))->find()) {
			if ($userinfo['IsLogin']=='1') {
				if ($userinfo['stoken'] || $userinfo['stoken']!='0') {
					$uinfo['ID']=$userinfo['id'];
					$uinfo['userName']=$userinfo['userName'];
					$uinfo['TrueName']=$userinfo['TrueName'];
					$userShop=M()->table('RS_Store')->where("stoken='%s'",$userinfo['stoken'])->getField('id');
					$uinfo['userShop']=$userShop;
					$gid=$this->MSL('usergroup')->where('userId=%d',$userinfo['id'])->getField('GroupId');
					$Gname=$this->MSL('groupmanger')->where('GroupId=%d',$gid)->getField('GroupName');
					$Sname=M()->table('RS_Store')->where("stoken='%s'",$userinfo['stoken'])->getField('storename');
					session('Sname',$Sname);
					session('Gname',$Gname);
					session('HeadImgUrl',$userinfo['HeadImgUrl']);
					session('token',$userinfo['token']);
					session('stoken',$userinfo['stoken']);
					session('is_login',1);
					session('userinfo',$uinfo);
					$file=dirname(__FILE__).'/../view/'.$theme.'Index/indexv'.$gid.'.html';
					if (!file_exists($file)) {

						if ($Gname!='超级管理组') {
							// var_dump($Gname);
							$this->error('您的账号暂无权限，请联系管理员分配权限之后登陆');
						}else{
							$gid='超级管理组';
						}
					}
					session('GroupId',$gid);
					$this->MSL('user')->where('id=%d',$userinfo['id'])->setField('LastLoginDate',time());
					$this->success('登录成功',U('Index/index'));
				}else{
					$this->error('非法用户');
				}
			}else{
				$this->error('您的账号无权登陆');
			}
		}else{
			$this->error('用户名或密码错误');
		}
	}

	public function logout(){
		session('is_login',FALSE);
		session('userinfo',FALSE);
		$this->success('成功退出',U('Public/login'));
	}
	



	
}










 ?>
