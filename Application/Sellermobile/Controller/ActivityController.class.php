<?php
namespace Sellermobile\Controller;
use Think\Controller;
class ActivityController extends CommonController{
	public function index(){
		$img=json_decode(file_get_contents('./Public/uploadImg.json'),true);
		// var_dump($img);exit;
		//特色商品
		$list=$this->BM()->query("SELECT * FROM RS_Product WHERE stoken='{$this->stoken}' AND ProId  IN (SELECT ProId FROM RS_ProductLabelList WHERE LabelType=2 AND ProLabel=2)");
		//特价商品
		$rows=$this->BM()->query(" SELECT pd.ProId,pd.ProName,pd.Price,pd.ProLogoImg,po.sprice FROM RS_Product pd RIGHT JOIN RS_ProductOnsale po ON pd.ProId=po.ProId WHERE pd.stoken='{$this->stoken}'");
		//活动商品
		$result=$this->BM()->query("SELECT * FROM RS_Product WHERE stoken='{$this->stoken}' AND ProId  IN (SELECT ProId FROM RS_ProductLabelList WHERE LabelType=1 AND ProLabel=1)");
		//特色图片
		$characteristic=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'1','IsShow'=>'1'))->find();
		if($characteristic){
			$this->assign('img',$characteristic['ImgPath']);
		}else{
			foreach ($img as $key => $value) {
				if($value['default']=='1'){
					$characteristicimg=$value['imgurl'];
					break;
				}

			}
			if(!$characteristicimg){
				$characteristicimg=$img[0]['imgurl'];
			}
			$this->assign('img',$characteristicimg);
		}
		// var_dump($characteristicimg);exit();
		//特价图片
		$translate=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'2','IsShow'=>'1'))->find();
		// var_dump($this->BM()->getlastsql());
		// var_dump($translate);exit;
		if($translate){
			$this->assign('img2',$translate['ImgPath']);
		}else{
			foreach($img as $key => $value){
				if($value['default']=='1'){
					$translateimg=$value['imgurl'];
					break;
				}
			}
			if(!$translateimg){
				$translateimg=$img[0]['imgurl'];
			}
			$this->assign('img2',$translateimg);
		}
		//活动图片
		$activity=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'3','IsShow'=>'1'))->find();
		if($activity){
			$this->assign('img3',$activity['ImgPath']);
		}else{
			foreach ($img as $key => $value) {
				if($value['default']=='1'){
					$activityimg=$value['imgurl'];
					break;
				}
			}
			if(!$activityimg){
				$activityimg=$img[0]['imgurl'];
			}
			$this->assign('img3',$activityimg);
		}

		$showactivename=json_decode(file_get_contents('./Public/'.$this->stoken.'showname.json'),true);
		$this->assign('showname',$showactivename);

		$this->assign('allimg',$img);
		$this->assign('rows',$rows);
		$this->assign('result',$result);
		$this->assign('list',$list);
		$this->assign('Title','活动商品');
		$this->display('Activity/index');

	}
	//特色页面
	public function characteristic(){
		$list=$this->BM()->query("SELECT * FROM RS_Product WHERE stoken='{$this->stoken}' AND ProId NOT IN (SELECT ProId FROM RS_ProductLabelList WHERE LabelType=2 AND ProLabel=2)");
		$this->assign('Title','特色');
		$this->assign('list',$list);
		$this->display('Activity/characteristic');
	}
	//活动页面
	public function activity(){
		$list=$this->BM()->query("SELECT * FROM RS_Product WHERE stoken='{$this->stoken}' AND ProId NOT IN (SELECT ProId FROM RS_ProductLabelList WHERE LabelType=1 AND ProLabel=1)");
		$this->assign('Title','活动');
		$this->assign('list',$list);
		$this->display('Activity/activity');
	}
	//特价页面
	public function translate(){
		$rows=$this->BM()->query("SELECT * FROM RS_Product WHERE stoken='{$this->stoken}' AND ProId NOT IN (SELECT ProId FROM RS_Eatmore WHERE stoken='{$this->stoken}'  UNION SELECT ProId FROM RS_ProductOnsale WHERE stoken='{$this->stoken}')");
		$this->assign('Title','特价');
		$this->assign('rows',$rows);
		$this->display('Activity/translate');
	}
	public function doadd(){
		$type=$_POST['type'];
		if($type=='tese'){
			$id=$_POST['id'];
			$res=true;
			$this->BM()->startTrans();
			foreach ($id as $key => $value) {
				$data['ProId']=$value;
				$data['ProLabel']=2;
				$data['LabelType']=2;
				$data['stoken']=$this->stoken;
				$data['token']=$this->token;
				$row=$this->BM('productlabellist')->add($data);
				if(!$row){
					$res=false;
					break;
				}
			}
			if($res){
				$this->BM()->commit();
				$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
			}else{
				$this->BM()->rollback();
				$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
			}
		}elseif($type=='huodong'){
			$id=$_POST['id'];
			$res=true;
			$this->BM()->startTrans();
			foreach ($id as $key => $value) {
				$data['ProId']=$value;
				$data['ProLabel']=1;
				$data['LabelType']=1;
				$data['stoken']=$this->stoken;
				$data['token']=$this->token;
				$row=$this->BM('productlabellist')->add($data);
				if(!$row){
					$res=false;
					break;
				}
			}
			if($res){
				$this->BM()->commit();
				$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
			}else{
				$this->BM()->rollback();
				$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
			}
		}else{
			$plist=$_POST['plist'];
			$res=true;
			$this->BM()->startTrans();
			foreach ($plist as $key => $value) {
				$data['ProId']=$key;
				$data['sprice']=$value;
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$data['stoken']=$this->stoken;
				$row=$this->BM('productonsale')->add($data);
				// var_dump($this->BM()->getlastsql());exit;
				if(!$row){
					$res=false;
					break;
				}
			}
			if($res){
				$this->BM()->commit();
				$this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
			}else{
				$this->BM()->rollback();
				$this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');
			}
		}

	}
	public function updata(){
		$type=$_POST['type'];
		$id=$_POST['id'];
		if($type=='tese'){
			$row=$this->BM('productlabellist')->where(array('ProId'=>$id,'stoken'=>$this->stoken,'token'=>$this->token,'LabelType'=>2,'ProLabel'=>2))->delete();
			if($row){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
			}
		}elseif($type=='huodong'){
			$row=$this->BM('productlabellist')->where(array('ProId'=>$id,'stoken'=>$this->stoken,'token'=>$this->token,'LabelType'=>1,'ProLabel'=>1))->delete();
			 // var_dump($this->BM()->getlastsql());exit;
			if($row){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
			}
		}else{
			$row=$this->BM('productonsale')->where(array('ProId'=>$id,'stoken'=>$this->stoken))->delete();
			 // var_dump($this->BM()->getlastsql());exit;
			if($row){
				$this->ajaxReturn(array('status' => 'true', 'info' =>'删除成功'), 'JSON');
			}else{
				$this->ajaxReturn(array('status' => 'false', 'info' =>'删除失败'), 'JSON');
			}
		}

	}
	public function updataimg(){
		$type=$_POST['type'];
		if($type=='tese'){
			$row=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'1'))->find();
			if($row){
				$data['ImgPath']=$_POST['imgurl'];
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$list=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'1'))->save($data);
				if($list){
					$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
				}else{
					$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
				}
			}else{
				$data['ImgPath']=$_POST['imgurl'];
				$data['CreateDate']=date('Y-m-d H:i:s',time());
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$data['IsShow']=1;
				$data['token']=$this->token;
				$data['stoken']=$this->stoken;
				$data['Type']=1;
				$list=$this->BM('homeimg')->add($data);
				if($list){
					$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
				}else{
					$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
				}
			}
		}elseif($type=='tejia'){
			$row=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'2'))->find();
			if($row){
				$data['ImgPath']=$_POST['imgurl'];
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$list=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'2'))->save($data);
				if($list){
					$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
				}else{
					$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
				}
			}else{
				$data['ImgPath']=$_POST['imgurl'];
				$data['CreateDate']=date('Y-m-d H:i:s',time());
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$data['IsShow']=1;
				$data['token']=$this->token;
				$data['stoken']=$this->stoken;
				$data['Type']=2;
				$list=$this->BM('homeimg')->add($data);
				if($list){
					$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
				}else{
					$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
				}
			}
		}else{
			$row=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'3'))->find();
			if($row){
				$data['ImgPath']=$_POST['imgurl'];
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$list=$this->BM('homeimg')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'Type'=>'3'))->save($data);
				if($list){
					$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
				}else{
					$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
				}
			}else{
				$data['ImgPath']=$_POST['imgurl'];
				$data['CreateDate']=date('Y-m-d H:i:s',time());
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$data['IsShow']=1;
				$data['token']=$this->token;
				$data['stoken']=$this->stoken;
				$data['Type']=3;
				$list=$this->BM('homeimg')->add($data);
				if($list){
					$this->ajaxReturn(array('status' => 'true', 'info' =>'修改成功'), 'JSON');
				}else{
					$this->ajaxReturn(array('status' => 'false', 'info' =>'修改失败'), 'JSON');
				}
			}
		}
	}

	// 保存特色特价名称
	public function namesave(){
		$type = $_POST['type'];
		$name = $_POST['name'];
		$showactivename=json_decode(file_get_contents('./Public/'.$this->stoken.'showname.json'),true);
		if ($type == '0') {
			$showactivename['tsshowname'] = $name;
		} else {
			$showactivename['tjshowname'] = $name;
		}
		file_put_contents('./Public/'.$this->stoken.'showname.json', json_encode($showactivename));//生成json数据文件
		$this->ajaxReturn(array('status' => 'true', 'info' =>'保存成功'), 'JSON');
	}

}

?>
