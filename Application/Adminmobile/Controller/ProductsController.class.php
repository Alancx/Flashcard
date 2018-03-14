<?php
namespace Adminmobile\Controller;
use Think\Controller;
class ProductsController extends CommonController {
	//商品管理
	public function prolist(){
		$wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
		$sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY a.ProId) AS RowNumber, a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.PriceRange,
		CASE when (a.SalesCount) is NULL then 0 ELSE a.SalesCount END SCount,
		CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product a LEFT JOIN
		(SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON
		a.ProId = b.ProId WHERE a.stoken='".$this->stoken."' AND a.token='".$this->token."' AND a.IsShelves=1) as c";
		//var_dump($sqlju);exit;
		$prodata=$this->BM()->query($sqlju);
		$this->assign('prodatas',$prodata);
		$this->assign('Title','商品管理');
		$this->display();
	}
	//加载出售中商品信息
	public function getmorepro_1(){
		$page=$_POST['page'];
		$wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
		$sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY a.ProId) AS RowNumber, a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.PriceRange,
		CASE when (a.SalesCount) is NULL then 0 ELSE a.SalesCount END SCount,
		CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product a LEFT JOIN
		(SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON
		a.ProId = b.ProId WHERE a.stoken='".$this->stoken."' AND a.token='".$this->token."' AND a.IsShelves=1) as c WHERE RowNumber>(20*".$page.")";
		$prodata=$this->BM()->query($sqlju);
		if($prodata){
			$this->ajaxReturn(array('status' => 'true', 'info' => $prodata), 'JSON');
		} else{
			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
		}
	}
	//加载售馨的商品信息
	public function getmorepro_2(){
		$page=$_POST['page'];
		$wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
		$sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY a.ProId) AS RowNumber, a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.PriceRange,
		CASE when (a.SalesCount) is NULL then 0 ELSE a.SalesCount END SCount,
		CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product a LEFT JOIN
		(SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON
		a.ProId = b.ProId WHERE a.stoken='".$this->stoken."' AND a.token='".$this->token."' AND (b.PCount<=0 OR b.PCount is NULL)) as c WHERE RowNumber>(20*".$page.")";
		$prodata=$this->BM()->query($sqlju);
		if($prodata){
			$this->ajaxReturn(array('status' => 'true', 'info' => $prodata), 'JSON');
		} else{
			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
		}
	}
	//加载仓库中商品信息
	public function getmorepro_3(){
		$page=$_POST['page'];
		$wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
		$sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY a.ProId) AS RowNumber, a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.PriceRange,
		CASE when (a.SalesCount) is NULL then 0 ELSE a.SalesCount END SCount,
		CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product a LEFT JOIN
		(SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON
		a.ProId = b.ProId WHERE a.stoken='".$this->stoken."' AND a.token='".$this->token."' AND b.PCount>0) as c WHERE RowNumber>(20*".$page.")";
		$prodata=$this->BM()->query($sqlju);
		if($prodata){
			$this->ajaxReturn(array('status' => 'true', 'info' => $prodata), 'JSON');
		} else{
			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
		}
	}
	//加载最新商品信息
	public function getmorepro_4(){
		$page=$_POST['page'];
		$wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
		$sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY a.ProId) AS RowNumber, a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.PriceRange,
		CASE when (a.SalesCount) is NULL then 0 ELSE a.SalesCount END SCount,
		CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product a LEFT JOIN
		(SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON
		a.ProId = b.ProId WHERE a.stoken='".$this->stoken."' AND a.token='".$this->token."' AND DATEADD(DAY, 15, a.CreateDate)>GETDATE()) as c WHERE RowNumber>(20*".$page.")";
		$prodata=$this->BM()->query($sqlju);
		if($prodata){
			$this->ajaxReturn(array('status' => 'true', 'info' => $prodata), 'JSON');
		} else{
			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
		}
	}
	//添加或者编辑商品
	public function proedit(){
		$sqlju="SELECT ClassId,ClassName,ParentClassId, ClassGrade,ClassSort FROM RS_ProductClass WHERE IsVisible=1
		AND token='".$this->token."'";
		$classdata=$this->BM()->query($sqlju);
		$attrdatas=$this->BM('Productattribute')->where("IsEnable='%s' and token='%s'",array('1',$this->token))->select();
		foreach ($attrdatas as &$attrdata) {
			$attrdata['values']=$this->BM('Productattributevalue')->where('AttributeId=%d',$attrdata['AttributeId'])->select();
		}
		if(IS_GET){
			$proid=$_GET['proid'];
			if($proid!=''){
				$titlename='商品修改';
				$Stype='proupdate';
				$prodata=$this->BM('Product')->where("ProId='%s'",array($proid))->select();
				if (file_exists($this->REALPATH.C('STATICPATH').$proid.'.json')) {
					$proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$proid.'.json'),true);
					//var_dump($proinfo['imgs']);exit;
					$this->assign('Simgdata',json_encode($proinfo['imgs']));
					$this->assign('Attrsdata',json_encode($proinfo['attrs']));
					$this->assign('proid',$proid);
				}
				if($prodata){
					$this->assign('Pdata',$prodata[0]);//商品主表信息
				}
			} else{
				$titlename='商品添加';
				$Stype='proadd';
			}
		}
		$this->assign('Pclass',$classdata);//商品分类
		$this->assign('Cclass',json_encode($classdata));//商品分类
		$this->assign('Attrs',$attrdatas);//商品属性
		$this->assign('Title',$titlename);
		$this->assign('Stype',$Stype);
		$this->display();
	}

	////////////////////商品图片/////////////
	public function proimage(){
		//var_dump($_FILES['selhimg']);
		if (IS_POST) {
			$upload=new \Think\Upload();
			$upload->maxSize=3145728;
			$upload->savePath='./Uoloads/';
			$upload->exts=array('jpg','png','jpeg');
			$info=$upload->uploadOne($_FILES['selimg']);
			if (!$info) {
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
			}else{
				$ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
				$this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
			}
		}
	}
	//////////////////商品保存////////////////
	public function prosave(){
		if(IS_POST){
			$wh= 'tb_wh'.substr($this->token, -8,8);//主仓库表名
			$protype=$_POST['Ptype'];
			$Pdata['ProLogoImg']=$_POST['ProLogoImg'];
			$Pdata['ProName']=htmlspecialchars($_POST['ProName']);
			$Pdata['ProShowImg']=json_decode($_POST['ProShowImg'],true);
			$Pdata['ClassType']=$_POST['ClassType'];
			$Pdata['ClassName']=$_POST['ClassName'];
			$Pdata['ProAttrs']=json_decode($_POST['ProAttrs'],true);
			$Pdata['ProAttrList']=json_decode($_POST['ProAttrList'],true);
			$Pdata['ProTitle']=htmlspecialchars($_POST['ProTitle']);
			$Pdata['ProSubtitle']=htmlspecialchars($_POST['ProSubtitle']);
			$Pdata['Price']=$_POST['Price'];
			$Pdata['PriceRange']=$_POST['PriceRange'];
			$Pdata['ProNumber']=trim($_POST['ProNumber']);
			$Pdata['Barcode']=trim($_POST['Barcode']);
			$Pdata['Weight']=$_POST['Weight'];
			$Pdata['Remarks']=htmlspecialchars($_POST['Remarks']);
			$Pdata['KeyWord']=htmlspecialchars($_POST['KeyWord']);
			$Pdata['EmpCut']=$_POST['EmpCut'];
			$Pdata['Cut']=$_POST['Cut'];
			$Pdata['ExtendCut']=$_POST['ExtendCut'];
			$Pdata['IsUseConpon']=$_POST['IsUseConpon'];
			$Pdata['Iszp']=$_POST['Iszp'];
			$Pdata['IsUseScore']=$_POST['IsUseScore'];
			$Pdata['Score']=$_POST['Score'];
			if($protype=='proadd'){
				$ProId="pro".substr(time(), 3).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
				//////主表数据//////////
				$prodata['ProId']=$ProId;
				$prodata['ProNumber']=$Pdata['ProNumber'];
				$prodata['ProName']=$Pdata['ProName'];
				$prodata['ProTitle']=$Pdata['ProTitle'];
				$prodata['ProSubtitle']=$Pdata['ProSubtitle'];
				$prodata['Price']=$Pdata['Price'];
				$prodata['PriceRange']=$Pdata['PriceRange'];
				$prodata['IsShelves']=1;
				$prodata['Remarks']=$Pdata['Remarks'];
				$prodata['KeyWord']=$Pdata['KeyWord'];
				$prodata['ClassType']=$Pdata['ClassType'];
				$prodata['ClassName']=$Pdata['ClassName'];
				$prodata['SalesCount']=0;
				$prodata['AttributeCount']=0;
				$prodata['BrowseCount']=0;
				$prodata['ProLogoImg']=$Pdata['ProLogoImg'];
				$prodata['Cut']=$Pdata['Cut'];
				$prodata['Cut2']=0;
				$prodata['Cut3']=0;
				$prodata['IsUseConpon']=$Pdata['IsUseConpon'];
				$prodata['Barcode']=$Pdata['Barcode'];
				$prodata['Weight']=$Pdata['Weight'];
				$prodata['EmpCut']=$Pdata['EmpCut'];
				$prodata['token']=$this->token;
				$prodata['Iszp']=$Pdata['Iszp'];
				$prodata['IsUseScore']=$Pdata['IsUseScore'];
				$prodata['Score']=$Pdata['Score'];
				$prodata['ExtendCut']=$Pdata['ExtendCut'];
				$prodata['stoken']=$this->stoken;
				//////end主表数据///////////////
				//////子表数据/////////////////
				foreach ($Pdata['ProAttrs'] as $key => $value) {
					$str=explode('|', $value);
					$proldata['ProId']=$ProId;
					$proldata['ProIdInputCard']='';
					$proldata['ProIdCard']=$ProId.$str[0];
					$proldata['ProSpec1']=$str[1];
					$proldata['ProSpec2']=empty($str[2])?' ':$str[2];
					$proldata['ProSpec3']=empty($str[3])?' ':$str[3];
					$proldata['ProSpec4']=empty($str[4])?' ':$str[4];
					$proldata['ProSpec5']=empty($str[5])?' ':$str[5];
					$proldata['OldPrice']=$Pdata['Price'];
					$proldata['Price']=$Pdata['PriceRange'];
					$proldata['Price']=$Pdata['PriceRange'];
					$proldata['EmpCut']=$Pdata['EmpCut'];
					$proldata['token']=$this->token;
					$proldata['Iszp']=$Pdata['Iszp'];
					$proldata['IsUseScore']=$Pdata['IsUseScore'];
					$proldata['Score']=$Pdata['Score'];
					$proldata['stoken']=$this->stoken;
					$proldatas[$ProId.$str[0]]=$proldata;
				}
				//////end子表数据//////////////
				//////仓库数据////////////////
				foreach ($Pdata['ProAttrs'] as $key => $value) {
					$ckdata['ProId']=$ProId;
					$temp=explode('|', $value);
					$ckdata['ProIdCard']=$ProId.$temp[0];
					$ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
					$ckdata['StockCount']=0;
					$ckdata['LimitCount']=0;
					$ckdata['VirtualCount']=0;
					$ckdata['InCount']=0;
					$ckdata['SalesCount']=0;
					$ckdata['OutCount']=0;
					$ckdata['ReturnCount']=0;
					$ckdata['IsDelete']=0;
					$ckdata['CreateDate']=date('Y-m-d H:i:s',time());
					$cdata[]=$ckdata;
				}
				//////end仓库数据////////////////
				//////展示图片/////////////////
				foreach ($Pdata['ProShowImg'] as $key => $img) {
					$imgdata[]=$img;
				}
				//////end展示图片/////////////////
				////////json数据//////////////////
				$json=array();
				$json=$prodata;
				$json['ProductList']=$proldatas;
				$json['imgs']=$imgdata;
				$json['attrs']=$Pdata['ProAttrList'];
				////////endjson数据//////////////////
				///////获得仓库表名/////////////////
				$whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
				///////end获得仓库表名/////////////////

				$this->BM()->startTrans();
				$this->WNM()->startTrans();
				$prores=$this->BM('Product')->add($prodata);
				$prolres=true;
				foreach ($proldatas as $value) {
					if (!$this->BM('Productlist')->add($value)) {
						$prolres=false;
						break;
					}
				}
				$whprores=true;
				foreach ($whlist as $whv) {
					if(!$whprores){
						break;
					}
					foreach ($cdata as $cv) {
						if(!$this->WNM($whv['name'])->add($cv)){
							$whprores=false;
							break;
						}
					}
				}
				if($prores && $prolres && $whprores){
					file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
					$this->BM()->commit();
					$this->WNM()->commit();
					$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
				} else{
					$this->BM()->rollback();
					$this->WNM()->rollback();
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'bcError'), 'JSON');
				}
			} else{
				$ProId=$_POST['Proid'];
				$proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$ProId.'.json'),true);
				//////主表数据//////////
				$prodata['ProId']=$ProId;
				$prodata['ProNumber']=$Pdata['ProNumber'];
				$prodata['ProName']=$Pdata['ProName'];
				$prodata['ProTitle']=$Pdata['ProTitle'];
				$prodata['ProSubtitle']=$Pdata['ProSubtitle'];
				$prodata['Price']=$Pdata['Price'];
				$prodata['PriceRange']=$Pdata['PriceRange'];
				$prodata['Remarks']=$Pdata['Remarks'];
				$prodata['KeyWord']=$Pdata['KeyWord'];
				$prodata['ClassType']=$Pdata['ClassType'];
				$prodata['ClassName']=$Pdata['ClassName'];
				$prodata['ProLogoImg']=$Pdata['ProLogoImg'];
				$prodata['Cut']=$Pdata['Cut'];
				$prodata['IsUseConpon']=$Pdata['IsUseConpon'];
				$prodata['Barcode']=$Pdata['Barcode'];
				$prodata['Weight']=$Pdata['Weight'];
				$prodata['EmpCut']=$Pdata['EmpCut'];
				$prodata['Iszp']=$Pdata['Iszp'];
				$prodata['IsUseScore']=$Pdata['IsUseScore'];
				$prodata['Score']=$Pdata['Score'];
				$prodata['ExtendCut']=$Pdata['ExtendCut'];
				$prodata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$prodata['token']=$this->token;
				$prodata['stoken']=$this->stoken;
				//////end主表数据///////////////
				$pdatalist=$this->BM('Productlist')->
				where("ProId='%s' and token='%s' and stoken='%s' ",array($ProId,$this->token,$this->stoken))->field('ProIdCard')->select();
				foreach ($pdatalist as $key => $value) {
					$pdlist[]=$value['ProIdCard'];//老的子表商品属性编号
				}
				//////子表修改和新增数据/////////////////
				foreach ($Pdata['ProAttrs'] as $key => $value) {
					$str=explode('|', $value);
					$proldata['ProId']=$ProId;
					$proldata['ProIdCard']=$ProId.$str[0];
					$proldata['ProSpec1']=$str[1];
					$proldata['ProSpec2']=empty($str[2])?' ':$str[2];
					$proldata['ProSpec3']=empty($str[3])?' ':$str[3];
					$proldata['ProSpec4']=empty($str[4])?' ':$str[4];
					$proldata['ProSpec5']=empty($str[5])?' ':$str[5];
					$proldata['OldPrice']=$Pdata['Price'];
					$proldata['Price']=$Pdata['PriceRange'];
					$proldata['EmpCut']=$Pdata['EmpCut'];
					$proldata['token']=$this->token;
					$proldata['Iszp']=$Pdata['Iszp'];
					$proldata['IsUseScore']=$Pdata['IsUseScore'];
					$proldata['Score']=$Pdata['Score'];
					$proldata['stoken']=$this->stoken;
					if(in_array($proldata['ProIdCard'],$pdlist)){
						$proldata['LastUpdateDate']=date('Y-m-d H:i:s',time());
						$proldatau[$ProId.$str[0]]=$proldata;///修改字表数据
					} else{
						$proldata['ProIdInputCard']='';
						$proldatas[$ProId.$str[0]]=$proldata;///新增字表数据
					}
					$pdlistnow[]=$proldata['ProIdCard'];//新的子表商品属性编号
				}
				//////end子表数据//////////////
				//////仓库数据////////////////
				foreach ($Pdata['ProAttrs'] as $key => $value) {
					$ckdata['ProId']=$ProId;
					$temp=explode('|', $value);
					$ckdata['ProIdCard']=$ProId.$temp[0];
					$ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
					$ckdata['StockCount']=0;
					$ckdata['LimitCount']=0;
					$ckdata['VirtualCount']=0;
					$ckdata['InCount']=0;
					$ckdata['SalesCount']=0;
					$ckdata['OutCount']=0;
					$ckdata['ReturnCount']=0;
					$ckdata['IsDelete']=0;
					$ckdata['CreateDate']=date('Y-m-d H:i:s',time());
					if(!in_array($ckdata['ProIdCard'],$pdlist)){
						$cdata[]=$ckdata;
					}
				}
				//////end仓库数据////////////////
				//需要删除的子表和仓库信息///////////////
				foreach ($pdlist as $key => $value) {
					if(!in_array($value,$pdlistnow)){
						$proldatad[$value]=$value;///删除的子表信息
						$cdatad[]=$value;//需要删除的仓库信息
					}
				}
				///end需要删除的子表和仓库信息///////////////
				//////展示图片/////////////////
				foreach ($Pdata['ProShowImg'] as $key => $img) {
					$imgdata[]=$img;
				}
				//////end展示图片/////////////////
				////////json数据//////////////////
				$json=array();
				$json=$prodata;
				if($proldatau==null){
					$json['ProductList']=$proldatas;
				} else if ($proldatas==null) {
					$json['ProductList']=$proldatau;
				} else{
					$json['ProductList']=array_merge($proldatau,$proldatas);

				}
				$json['ProContent']=$proinfo['ProContent'];
				$json['imgs']=$imgdata;
				$json['attrs']=$Pdata['ProAttrList'];
				////////endjson数据//////////////////
				///////获得仓库表名/////////////////
				$whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
				///////end获得仓库表名/////////////////
				$this->BM()->startTrans();
				$this->WNM()->startTrans();
				$prores=$this->BM('Product')->where("ProId='%s'",$prodata['ProId'])->save($prodata);
				$prolres=true;
				foreach ($proldatas as $value) {
					if (!$this->BM('Productlist')->add($value)) {
						$prolres=false;
						break;
					}
				}
				$prolures=true;
				foreach ($proldatau as $value) {
					if (!$this->BM('Productlist')->where("ProIdCard='%s'",$value['ProIdCard'])->save($value)) {
						$prolures=false;
						break;
					}
				}
				$proldres=true;
				foreach ($proldatad as $value) {
					if (!$this->BM('Productlist')->where("ProIdCard='%s'",$value)->save(array('IsDelete'=>1))) {
						$proldres=false;
						break;
					}
				}
				$whprores=true;
				foreach ($whlist as $whv) {
					if(!$whprores){
						break;
					}
					foreach ($cdata as $cv) {
						if(!$this->WNM($whv['name'])->add($cv)){
							$whprores=false;
							break;
						}
					}
				}
				$whdprores=true;
				foreach ($whlist as $whv) {
					if(!$whdprores){
						break;
					}
					foreach ($cdatad as $cv) {
						$delete['IsDelete']=1;
						if(!$this->WNM($whv['name'])->where("ProIdCard='%s'",$cv)->save($delete)){
							$whdprores=false;
							break;
						}
					}
				}
				if($prolres && $prolures && $proldres && $whprores && $whdprores){
					file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
					$this->BM()->commit();
					$this->WNM()->commit();
					$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
				} else{
					$this->BM()->rollback();
					$this->WNM()->rollback();
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'xgError'), 'JSON');
				}
			}
		} else{
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
	///修改商品上架状态//////////////
	public function setshelve(){
		if(IS_POST){
			$ProId=$_POST['ProId'];
			if($_POST['IsShelves']=='0'){
				$pro['IsShelves']='1';
			} else{
				$pro['IsShelves']='0';
			}
			$res=$this->BM('Product')->where("ProId='%s'",$ProId)->save($pro);
			if($res!=false){
				$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
			} else{
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'shelveError'), 'JSON');
			}
		} else{
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
	///修改商品上架状态//////////////
	public function prodelete(){
		if(IS_POST){
			$wh= 'tb_wh'.substr($this->token, -8,8);//主仓库表名
			$ProId=$_POST['ProId'];
			$whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
			$this->BM()->startTrans();
			$this->WNM()->startTrans();
			$pres=$this->BM('Product')->where("ProId='%s'",$ProId)->delete();
			$plres=$this->BM('Productlist')->where("ProId='%s'",$ProId)->delete();
			$whdprores=true;
			foreach ($whlist as $whv) {
					$delete['IsDelete']=1;
					if(!$this->WNM($whv['name'])->where("ProId='%s'",$ProId)->save($delete)){
						$whdprores=false;
						break;
				}
			}

			if($pres && $plres && $whdprores){
				$this->BM()->commit();
				$this->WNM()->commit();
				$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
			} else{
				$this->BM()->rollback();
				$this->WNM()->rollback();
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'delproError'), 'JSON');
			}
		} else{
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}





}?>
