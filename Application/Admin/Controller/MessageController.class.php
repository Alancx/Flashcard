<?php
namespace Admin\Controller;
use Think\Controller;
class MessageController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}
	public function getMessage() {
		if(IS_POST) {
			//提现
			$withdraw = $_POST['withdraw'];
			//退款
			$refund = $_POST['refund'];
			//json文件名
			$filename = './Public/message.json';
			$arr = array();
			//提现人信息
			$arr['withdraw'] = $withdraw;
			//退款人信息
			$arr['refund'] = $refund;
			$data = json_encode($arr);
			file_put_contents($filename, $data);
			$this->success('保存成功');
		}else {
			$filename = './Public/message.json';
			$data = json_decode(file_get_contents($filename),true);
			$withdraw = $data['withdraw'];
			$refund = $data['refund'];
			$pagedata['withdraw'] = $withdraw;
			$pagedata['refund'] = $refund;
			$this->assign($pagedata);
			$this->display();
		}

	}




}

