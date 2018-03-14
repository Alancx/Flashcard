<?php
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;chatrset=utf-8');
/**
* 平台用户管理--leaves--20160318
*/
class SysUserController extends CommonController
{
	public $userModel;
	public function _initialize()
	{
		parent::_initialize();
		$this->userModel=M('user','tb_','MYSQL');
		// var_dump($this->userModel);exit();
	}
	/**
	 * 平台用户管理
	 */
	public function index(){
		$count=$this->userModel->count();
		$page=new \Think\Page($count,20);
		$users=$this->userModel->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign(array('users'=>$users,'page'=>$page->show()));
		$this->display();
	}

	/**
	 * 添加新用户
	 */
	public function addUser(){
		if (IS_POST) {
		    $tempStr='qwertyuioplkjhgfdsazxcvbnm';
		    $tempNums='1234567890';
		    $token=substr(str_shuffle($tempStr),mt_rand(0,19),6).time().substr(str_shuffle($tempNums),mt_rand(0,7),2);
			$tempData['userName']=$_POST['userName'];
			$tempData['password']=md5($_POST['password']);
			$tempData['userUrl']=$_POST['userUrl'];
			$tempData['token']=$token;
			$tempData['registerTime']=time();
			if ($this->userModel->add($tempData)) {
				$this->success('添加成功',U('SysUser/index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			$this->display();
		}
	}

	/**
	 * 删除用户
	 */
	public function delUser(){
		$token=$_GET['token'];
		if ($this->userModel->where('token="%s"',$token)->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}



 ?>
