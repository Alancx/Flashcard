<?php
namespace Seller\Controller;
use Think\Controller;
class UpimgController extends Controller{
	public $ImgUrl;
	public $Oss;
	public function _initialize(){
		import('Vendor.alioss.Oss');
		$this->Oss=new \Oss();
		$this->assign('PICURL',C('PICURL'));
	}
	public function index(){
		if (IS_POST) {
			// $file_name=uniqid('logo');
			// $ext=explode('/', $_FILES['img']['type'])[1];
			// $res=$this->Oss->uploadFile($_FILES['img']['tmp_name'],$file_name.'.'.$ext,false);
			// $this->assign('imgurl',$res);
			// $this->display();			
			$upload=new \Think\Upload();
			$upload->maxSize=3145728;
			$upload->savePath='./Uoloads/';
			$upload->exts=array('jpg','png','jpeg','gif');
			// $upload->thumb=true;
			// $upload->thumbMaxWidth='200';
			// $upload->thumbMaxHeight='200';
			// $upload->thumbRemoveOrigin=false;
			// $upload->
			$info=$upload->uploadOne($_FILES['img']);
			if (!$info) {
				$this->error($upload->getError());
			}else{
				// if ($_POST['tumb']=='true') {
				// 	$img=new \Think\Image();
				// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
				// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
				// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
				// }
				$ImgUrl='/Upload'.substr($info['savepath'],1).$info['savename'];
				// $img=$img=substr($upload->savePath.$info['savename'],0);
				// var_dump($info);
				// var_dump($img);
				// var_dump($ImgUrl);
				$image = new \Think\Image();
				$image->open('.'.$ImgUrl);
				$image->thumb(200,200)->save('./Upload/'.$info['savepath'].'thumb_'.$info['savename']);
				$this->assign('imgurl',$ImgUrl);
				$this->display();
			}
			// var_dump($_FILES);
		}else{
			// $this->assign('imgurl','0');
			// var_dump($this->ImgUrl);
			$this->display();
		}
	}

	public function editpro(){
		if (IS_POST) {
			// $file_name=uniqid('pro');
			// $ext=explode('/', $_FILES['img']['type'])[1];
			// $res=$this->Oss->uploadFile($_FILES['img']['tmp_name'],$file_name.'.'.$ext,false);
			// $this->assign('imgurl',$res);
			// $this->display();
			$upload=new \Think\Upload();
			$upload->maxSize=3145728;
			$upload->savePath='./Uoloads/';
			$upload->exts=array('jpg','png','jpeg','gif');
			$info=$upload->uploadOne($_FILES['img']);
			if (!$info) {
				$this->error($upload->getError());
			}else{
				// if ($_POST['tumb']=='true') {
				// 	$img=new \Think\Image();
				// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
				// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
				// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
				// }
				$ImgUrl="/Upload".substr($info['savepath'], 1).$info['savename'];
				if ($_POST['logo']=='ProLogoImg') {
					$image = new \Think\Image();
					$image->open('.'.$ImgUrl);
					$image->thumb(200,200)->save('./Upload/'.$info['savepath'].'thumb_'.$info['savename']);
				}
				$this->assign('imgurl',$ImgUrl);
				$this->display();
			}
			// // var_dump($_FILES);
		}else{
			// $this->assign('imgurl',$this->ImgUrl);
			// var_dump($this->ImgUrl);
			$this->display();
		}
	}


	public function saveimg(){
		// var_dump($_FILES);exit();
		// $file_name=uniqid('logo');
		// $ext=explode('/', $_FILES['file']['type'])[1];
		// $res=$this->Oss->uploadFile($_FILES['file']['tmp_name'],$file_name.'.'.$ext,false);
		// echo json_encode($res);
			$upload=new \Think\Upload();
			$upload->maxSize=3145728;
			$upload->savePath='./Uoloads/';
			$upload->exts=array('jpg','png','jpeg','gif');
			$info=$upload->upload();
			if (!$info) {
				$this->error($upload->getError());
			}else{
				$img="/Upload".substr($info['file']['savepath'], 1).$info['file']['savename'];
				echo json_encode($img);
			}
	}


	public function home(){
		if (IS_POST) {
			$file_name=uniqid('logo');
			$ext=explode('/', $_FILES['img']['type'])[1];
			$res=$this->Oss->uploadFile($_FILES['img']['tmp_name'],$file_name.'.'.$ext,false);
			$this->assign('imgurl',$res);
			$this->display();
			// $upload=new \Think\Upload();
			// $upload->maxSize=3145728;
			// $upload->savePath='./Home/';
			// $upload->exts=array('jpg','png','jpeg','gif');
			// $info=$upload->uploadOne($_FILES['img']);
			// if (!$info) {
			// 	$this->error($upload->getError());
			// }else{
			// 	// if ($_POST['tumb']=='true') {
			// 	// 	$img=new \Think\Image();
			// 	// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
			// 	// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// }
			// 	$ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
			// 	$this->assign('imgurl',$ImgUrls);
			// 	$this->display();
			// }
			// var_dump($_FILES);
		}else{
			// $this->assign('imgurl',$this->ImgUrl);
			// var_dump($this->ImgUrl);
			$this->display();
		}
	}
	public function spceil(){
		if (IS_POST) {
			$file_name=uniqid('spceil');
			$ext=explode('/', $_FILES['img']['type'])[1];
			$res=$this->Oss->uploadFile($_FILES['img']['tmp_name'],$file_name.'.'.$ext,false);
			$this->assign('imgurl',$res);
			$this->display();			
			// $upload=new \Think\Upload();
			// $upload->maxSize=3145728;
			// $upload->savePath='./spceil/';
			// $upload->exts=array('jpg','png','jpeg','gif');
			// $info=$upload->uploadOne($_FILES['img']);
			// if (!$info) {
			// 	$this->error($upload->getError());
			// }else{
			// 	// if ($_POST['tumb']=='true') {
			// 	// 	$img=new \Think\Image();
			// 	// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
			// 	// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// }
			// 	$ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
			// 	// var_dump($ImgUrls);exit();
			// 	// $image=new \Think\Image();
			// 	// $image->open('.'.$ImgUrls);
			// 	// $image->thumb(640,640)->save('./Upload/'.$info['savepath'].$info['savename']);
			// 	$this->assign('imgurl',$ImgUrls);
			// 	$this->display();
			// }
			// var_dump($_FILES);
		}else{
			// $this->assign('imgurl',$this->ImgUrl);
			// var_dump($this->ImgUrl);
			$this->display();
		}
	}
	public function classimg(){
		if (IS_POST) {
			$file_name=uniqid('class');
			$ext=explode('/', $_FILES['img']['type'])[1];
			$res=$this->Oss->uploadFile($_FILES['img']['tmp_name'],$file_name.'.'.$ext,false);
			$this->assign('imgurl',$res);
			$this->display();			
			// $upload=new \Think\Upload();
			// $upload->maxSize=3145728;
			// $upload->savePath='./Class/';
			// $upload->exts=array('jpg','png','jpeg','gif');
			// $info=$upload->uploadOne($_FILES['img']);
			// if (!$info) {
			// 	$this->error($upload->getError());
			// }else{
			// 	// if ($_POST['tumb']=='true') {
			// 	// 	$img=new \Think\Image();
			// 	// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
			// 	// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// }
			// 	$ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
			// 	$image = new \Think\Image();
			// 	$image->open('.'.$ImgUrls);
			// 	$image->thumb(100,100)->save('./Upload/'.$info['savepath'].'thumb_'.$info['savename']);
			// 	$this->assign('imgurl',$ImgUrls);
			// 	$this->display();
			// }
			// var_dump($_FILES);
		}else{
			// $this->assign('imgurl',$this->ImgUrl);
			// var_dump($this->ImgUrl);
			$this->display();
		}
	}

	/**
	 * 身份证验证照片
	 */
	public function idinfo(){
		if (IS_POST) {
			$file_name=uniqid('idinfo');
			$ext=explode('/', $_FILES['img']['type'])[1];
			$res=$this->Oss->uploadFile($_FILES['img']['tmp_name'],$file_name.'.'.$ext,false);
			$this->assign('imgurl',$res);
			$this->display();			
			// $upload=new \Think\Upload();
			// $upload->maxSize=9145728;
			// $upload->savePath='./IdCards/';
			// $upload->exts=array('jpg','png','jpeg','gif');
			// $info=$upload->uploadOne($_FILES['img']);
			// // var_dump($info);exit();
			// if (!$info) {
			// 	$this->error($upload->getError());
			// }else{
			// 	// if ($_POST['tumb']=='true') {
			// 	// 	$img=new \Think\Image();
			// 	// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
			// 	// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	// }
			// 	$ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
			// 	// $image = new \Think\Image();
			// 	// $image->open('.'.$ImgUrls);
			// 	// $image->thumb(100,100)->save('./Upload/'.$info['savepath'].'thumb_'.$info['savename']);
			// 	$this->assign('imgurl',$ImgUrls);
			// 	$this->display();
			// }
			// var_dump($_FILES);
		}else{
			// $this->assign('imgurl',$this->ImgUrl);
			// var_dump($this->ImgUrl);
			// echo "string";
			$this->display();
		}
	}
	public function Saveprologo(){
		$upload=new \Think\Upload();
		$upload->maxSize=3145728;
		$upload->savePath='/Prologoimg/';
		$upload->exts=array('jpg','png','jpeg','gif');		
		$info=$upload->uploadOne($_FILES['imgs']);
		if (!$info) {
			$msg['status']='error';
			$msg['info']=$upload->getError();			
		}else{
			$img='/Upload'.$info['savepath'].$info['savename'];
			$msg['status']='success';
			$msg['img']=C('PICURL').$img;
		}
		echo json_encode($msg);
	}



}





 ?>
