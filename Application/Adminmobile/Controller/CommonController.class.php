<?php
namespace Adminmobile\Controller;
use Think\Controller;
class CommonController extends BaseController{
	public $token;
	public $stoken;
	public $department;
	public $REALPATH;
	public function _initialize(){
		$this->token=session('token');
		$this->stoken=session('stoken');
		$this->department=session('department');
		$this->REALPATH=explode('/Home/Application/',str_replace('\\','/',realpath(dirname(__FILE__).'/')))[0].'/Home/Web';
		if (session('is_login')!=1 || session('userinfo')==false) {
			$this->redirect('Public/login');
		}
	}
}
?>
