<?php
namespace Seller\Controller;
use Think\Controller;
class TestController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function pj(){
		if (IS_POST) {
			$data['ProId']=$_POST['ProId'];
			$data['MemberId']=$_POST['MemberId'];
			$data['Class']=$_POST['Class'];
			$data['ClassScore']=$_POST['ClassScore'];
			$data['ServiceScore']=$_POST['ServiceScore'];
			$data['LogisticsScore']=$_POST['LogisticsScore'];
			$data['Content']=$_POST['Content'];
			$data['OrderId']=$_POST['OrderId'];
			$data['IsDelete']=0;
			$data['Label']='';
			$data['Integral']=0;
			$data['Image']='';
			if (M()->table('RS_ProductEvaluation')->add($data)) {
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}else{
			$pinfos=M()->table('RS_Product')->select();
			$this->assign('pinfos',$pinfos);
			$this->display();
		}
	}





}












?>
