<?php
namespace Seller\Controller;
use Think\Controller;
class ExtendController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function scene(){
		$count=M()->table('RS_Scene')->where("token='%s'",$this->token)->count();
		$page= new \Think\Page($count,15);
		$scenes=M()->table('RS_Scene')->where("token='%s'",$this->token)->order('Sort')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($scenes as &$ss) {
			$ss['storename']=M()->table('RS_Store')->where("id=%d",$ss['storeid'])->getField('storename');
		}
		$storelist=M()->table('RS_Store')->where("token='%s'",$this->token)->select();
		$this->assign(array('scenes'=>$scenes,'jsonData'=>json_encode($scenes),'page'=>$page->show(),'storelist'=>$storelist));
		$this->display();
	}

	public function search(){
		$key=$_GET['keyword'];
		$count=M()->table('RS_Scene')->where("token='".$this->token."' and SceneName like '%s'","%".$key."%")->count();
		$page= new \Think\Page($count,10);
		$scenes=M()->table('RS_Scene')->order('Sort')->where("token='".$this->token."' and SceneName like '%s'","%".$key."%")->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($scenes as &$ss) {
			$ss['storename']=M()->table('RS_Store')->where("id=%d",$ss['storeid'])->getField('storename');
		}
		$this->assign(array('scenes'=>$scenes,'jsonData'=>json_encode($scenes),'page'=>$page->show()));
		$this->display('scene');
	}

	public function saveScene(){
		// var_dump($_POST);exit();
		if (IS_POST) {
			$data['SceneName']=$_POST['SceneName'];
			// var_dump(strlen($_POST['Sort']));
			// if (strlen($_POST['Sort'])==3) {
			// 	$data['Sort']=$_POST['Sort'];
			// }elseif (strlen($_POST['Sort'])==2) {
			// 	$data['Sort']='0'.$_POST['Sort'];
			// }elseif (strlen($_POST['Sort']==1)) {
			// 	echo "111";
			// 	$data['Sort']='00'.$_POST['Sort'];
			// }
			switch (strlen($_POST['Sort'])) {
				case 1:
					$data['Sort']='00'.$_POST['Sort'];
					break;
				case 2:
					$data['Sort']='0'.$_POST['Sort'];
					break;
				default:
					$data['Sort']=$_POST['Sort'];
					break;
			}
			$data['IsShow']=$_POST['IsShow'];
			$data['storeid']=$_POST['storeid'];
			$data['token']=$this->token;
			if ($_POST['ID']) {
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				if (M()->table('RS_Scene')->where('ID=%d',$_POST['ID'])->save($data)) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}else{
				if (M()->table('RS_Scene')->add($data)) {
					$this->success('添加成功');
				}else{
					echo M()->getlastSql();exit();
					$this->error('添加失败');
				}
			}
		}
	}
	public function delScene(){
		$id=$_GET['id'];
		if (M()->table('RS_Scene')->where('ID=%d',$id)->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	public function changeshow(){
		$statu=$_POST['statu'];
		$id=$_POST['id'];
		if (M()->table('RS_Scene')->where('ID=%d',$id)->setField('IsShow',$statu)) {
			echo "success";
		}
	}





}






 ?>
