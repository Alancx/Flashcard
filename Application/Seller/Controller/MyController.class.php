<?php
namespace Seller\Controller;
use Think\Controller;
class MyController extends CommonController{
	public $myInfo;
	public $tempsession;
	public function _initialize(){
		parent::_initialize();
		$tempsession=session('userinfo');
		$id=$tempsession['ID'];
		$info=$this->MSL('user')->where('id=%d',$id)->find();
		// var_dump($info);
		$this->myInfo=$info;
	}

	public function modpass(){
		$this->assign('info',$this->myInfo);
		$this->display();
	}

	public function head(){
		$this->display();
	}

	public function editImg(){
		$img=$_POST['img'];
		$id=$_POST['uid'];
		if ($this->MSL('user')->where("id=%d",$id)->setField('HeadImgUrl',$img)) {
			session('HeadImgUrl',$img);
			echo "success";
		}else{
			echo "error";
		}
	}

	public function checkpass(){
		$pass=$_POST['password'];
		$password=md5($pass);
		if ($password==$this->myInfo['Password']) {
			echo "success";
		}else{
			echo "error";
		}
	}

	public function savepass(){
		$password=md5($_POST['Password']);
		if ($this->MSL('user')->where('id=%d',trim($this->myInfo['id']))->setField('Password',$password)) {
			$this->success('修改成功，请重新登陆',U('Public/logout'));
		}else{
			// var_dump($tempsession);
			// var_dump(M()->getlastSql());exit();
			$this->error('修改失败');
		}
	}
}










 ?>
