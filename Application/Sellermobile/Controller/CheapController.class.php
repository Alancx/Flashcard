<?php
namespace Sellermobile\Controller;
use Think\Controller;
class CheapController extends CommonController{
	public function index(){
		$list=$this->BM()->query("SELECT pd.ProId,pd.ProLogoImg,pd.ProName,pd.Price,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,em.ProId FROM RS_Product pd LEFT JOIN RS_Eatmore em ON em.ProId=pd.ProId WHERE em.stoken='{$this->stoken}'");
		// var_dump($list);
		$this->assign('list',$list);
		$this->assign('Title','多吃优惠');
		$this->display('Cheap/index');
	}
	public function delete(){
		// $this->BM()->startTrans();
		$ProId=$_POST['ProId'];
		$row=$this->BM('Eatmore')->where(array('ProId'=>$ProId))->delete();
		if($row){
			// $this->BM()->rollback();
			$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
		}else{
			// $this->BM()->rollback();
			$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
		}
	}
	public function edit(){
		$ProId=$_GET['ProId'];
		if($ProId=='add'){
			$type='add';
			$result=$this->BM()->query("SELECT * FROM RS_Product WHERE stoken='{$this->stoken}' AND ProId NOT IN (SELECT ProId FROM RS_Eatmore WHERE stoken='{$this->stoken}'  UNION SELECT ProId FROM RS_ProductOnsale WHERE stoken='{$this->stoken}')");
			// var_dump($result);exit;
			$this->assign('result',$result);
			$this->assign('type',$type);
			$this->assign('Title','多吃优惠设置');
			$this->display('Cheap/cheap');
		}else{
			$type=$ProId;
			$list=$this->BM()->query("SELECT pd.ProId,pd.ProLogoImg,pd.ProName,pd.Price,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,em.ProId FROM RS_Product pd LEFT JOIN RS_Eatmore em ON em.ProId=pd.ProId WHERE em.stoken='{$this->stoken}' AND em.ProId='{$ProId}'");			
			$this->assign('list',$list[0]);
			$this->assign('type',$type);
			$this->assign('Title','多吃优惠设置');
			$this->display('Cheap/cheap');
		}
	}
	public function updata(){
		// var_dump($_POST['Level']);exit;
		$ProId=$_POST['ProId'];
		foreach ($_POST['Levels'] as $key => $value) {
			$Lv[$key]=$value;
		}
		$Lv['Level']=$_POST['Level'];
		// var_dump($Lv);exit;
		if($ProId=='add'){
			$time=date('Y-m-d H:i:s',time());
			$Lv['CreateDate']=$time;
			$Lv['ProId']=$_POST['Proid'];
			$Lv['stoken']=$this->stoken;
			$list=$this->BM('Eatmore')->add($Lv);
			// var_dump($this->BM()->getlastsql());exit;
			if($list){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
			}
		}else{
			$time=date('Y-m-d H:i:s',time());
			$Lv['LastUpdate']=$time;
			$row=$this->BM('Eatmore')->where('ProId='."'$ProId'")->save($Lv);
			// var_dump($this->BM()->getlastsql());exit;
			if($row){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
			}
		}
	}
}

?>
