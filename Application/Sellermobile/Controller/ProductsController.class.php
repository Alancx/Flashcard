<?php
namespace Sellermobile\Controller;
use Think\Controller;
class ProductsController extends CommonController {
  //商品管理列表
	public function prolist(){
		///////////获得选买的工厂商品信息///////////
    $sqlStr="SELECT p.ProId,p.ProTitle,p.ProName,p.ClassType,CAST(p.ProLogoImg AS varchar(MAX)) AS ProLogoImg,MIN(mp.Price) AS Price FROM RS_MerPros mp LEFT JOIN RS_Product p ON mp.ProId = p.ProId WHERE mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' GROUP BY p.ProId,p.ProTitle,p.ProName,p.ClassType,CAST(p.ProLogoImg AS varchar(MAX))";
		// var_dump($sqlStr);exit();
    $facpro=$this->BM()->query($sqlStr);
		foreach ($facpro as $key => $value) {
			$sqlStr="SELECT pl.ProId,pl.ProSpec1,pl.ProIdCard,pl.CosPrice,mp.Price FROM RS_ProductList pl LEFT JOIN RS_MerPros mp ON pl.ProId=mp.ProId AND pl.ProIdCard = mp.ProIdCard WHERE pl.ProId='".$value['ProId']."' AND pl.IsDelete=0 AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' ";
			$attrdata=$this->BM()->query($sqlStr);
			$facpro[$key]['attrlist']=$attrdata;
		}
    if ($facpro) {
      // $this->assign('facpro',$facpro);
			$this->assign('facpro','NULLFACPRO');
    } else {
      $this->assign('facpro','NULLFACPRO');
    }
		/////////////获得自营商品/////////////////////////
		// $wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
		// $sqlStr=" SELECT a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.PriceRange,CASE when (a.SalesCount) is NULL then 0 ELSE a.SalesCount END SCount,CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product a LEFT JOIN (SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON a.ProId = b.ProId WHERE a.stoken='".$this->stoken."' AND a.token='".$this->token."' ORDER BY a.CreateDate";
		$sqlStr="SELECT * FROM RS_Product  WHERE stoken='".$this->stoken."' AND token='".$this->token."' ORDER BY CreateDate DESC";
		// var_dump($sqlStr);exit();
		$selfpro=$this->BM()->query($sqlStr);
		foreach ($selfpro as $key => $value) {
			$sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$this->token."' AND stoken='".$this->stoken."' AND ProId='".$value['ProId']."'";
			$proattr=$this->BM()->query($sqlStr);
			$selfpro[$key]['attrlist']=$proattr;
		}
		if ($selfpro) {
			$this->assign('selfpro',$selfpro);
		} else {
			$this->assign('selfpro','NULLSELFPRO');
		}
		//////////获得本店所有商品以及分类信息/////////////////////

		$sqlStr="SELECT p.ProId,p.Price AS PriceRange,(CASE WHEN p.stoken='0' THEN '1' ELSE '2' END) AS ptype,p.IsShelves,p.ProLogoImg,pc.ClassName,pc.ClassId AS ClassType,p.ProName,p.ProTitle FROM RS_Product p LEFT JOIN RS_ProductClass pc ON p.ClassType = pc.ClassId WHERE p.token='".$this->token."' AND p.stoken='".$this->stoken."' ORDER BY p.CreateDate DESC";

		$allpro=$this->BM()->query($sqlStr);
		if ($allpro) {
			$this->assign('allpro',$allpro);
		} else {
			$this->assign('allpro','NULLALLPRO');
		}

		$this->assign('Title','商品管理');
    $this->assign('footerSign',1);
		$this->display();
	}
  ///////平台商品列表///////////////////
  public function Factorypro(){
		if (IS_POST) {
			$serchtext=$_POST['textinfo'];
			$sqlStr="SELECT * FROM RS_Product WHERE IsShelves=1 AND token='".$this->token."' AND stoken='0' AND ProId NOT IN (SELECT ProId FROM RS_MerPros WHERE token='".$this->token."' AND stoken='".$this->stoken."') AND ProName LIKE '%".$serchtext."%' ORDER BY ID DESC ";
	    $prodata=$this->BM()->query($sqlStr);

			foreach ($prodata as $key => $value) {
				$sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$this->token."' AND stoken='0' AND ProId='".$value['ProId']."'";
				$proattr=$this->BM()->query($sqlStr);
				$prodata[$key]['attrlist']=$proattr;
			}

			if ($prodata) {
	      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $prodata), 'JSON');
	    } else {
	      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'NULLDATA'), 'JSON');
	    }

		} else {
			$sqlStr="SELECT * FROM RS_Product WHERE IsShelves=1 AND token='".$this->token."' AND stoken='0' AND ProId NOT IN (SELECT ProId FROM RS_MerPros WHERE token='".$this->token."' AND stoken='".$this->stoken."') ORDER BY ID DESC ";
	    $prodata=$this->BM()->query($sqlStr);

			foreach ($prodata as $key => $value) {
				$sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$this->token."' AND stoken='0' AND ProId='".$value['ProId']."'";
				$proattr=$this->BM()->query($sqlStr);
				$prodata[$key]['attrlist']=$proattr;
			}
	    if ($prodata) {
	      $this->assign('prodata',$prodata);
	    } else {
	      $this->assign('prodata','NULLPRO');
	    }
	    $this->assign('Title','工厂商品');
	    $this->display();
		}
  }
  //////添加平台商品列表////////////////
	public function Factoryproadd(){
		$proid=$_GET['proid'];
		$type=$_GET['type'];
		if (empty($type)){
			$sqlStr="SELECT * FROM RS_Product WHERE IsShelves=1 AND token='".$this->token."' AND stoken='0' AND ProId='".$proid."'";
			$prodata=$this->BM()->query($sqlStr);
			$sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$this->token."' AND stoken='0' AND ProId='".$proid."'";
			$prolist=$this->BM()->query($sqlStr);
			$prodata[0]['prolist']=$prolist;
			$this->assign('factype','A');
			$this->assign('proinfo',$prodata[0]);
			$this->assign('Title','添加平台商品');
		} else {
			$sqlStr="SELECT * FROM RS_Product WHERE token='".$this->token."' AND stoken='0' AND ProId='".$proid."'";
			$prodata=$this->BM()->query($sqlStr);
			$sqlStr="SELECT mp.Price AS setprice,pl.* FROM RS_MerPros mp RIGHT JOIN RS_ProductList pl ON mp.ProId = pl.ProId AND mp.ProIdCard = pl.ProIdCard WHERE mp.ProId='".$proid."' AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' ";
			$prolist=$this->BM()->query($sqlStr);
			$prodata[0]['prolist']=$prolist;
			$this->assign('factype','U');
			$this->assign('proinfo',$prodata[0]);
			$this->assign('Title','修改平台商品');
		}
		$this->display();
	}
	///////批量保存选择的平台商品/////////////////////////
	public function Factoryprosave(){
		if (IS_POST) {
			$prodata=$_POST['prolist'];
			$prolist=json_decode($prodata,true);
			$nowDate=date('Y-m-d H:i:s',time());
			$res=true;
			$red=true;
			$wh= 'wh'.substr($this->token, -8,8);//////主仓库名称
			$uinfo=$this->UM('user')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'id'=>session('userinfo')['ID']))->find();
			$sinfo=$this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
			// var_dump($prolist);exit();
			////////订货申请单主表信息//////
			$dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
			$dataInWarehouse['InWarehouseNumber']='';
	    $dataInWarehouse['Count']=0;
	    $dataInWarehouse['Money']=0.00;
	    $dataInWarehouse['Status']=0;
	    $dataInWarehouse['Date']=$nowDate;
	    $dataInWarehouse['InputId']=$uinfo['id'];
	    $dataInWarehouse['InputName']=$uinfo['TrueName'];
	    $dataInWarehouse['HandleId']='';
	    $dataInWarehouse['HandleName']='';
	    $dataInWarehouse['Type']='4';
	    $dataInWarehouse['Remarks']='';
	    $dataInWarehouse['InStorehouseId']=$wh.'_'.$sinfo['id'];
	    $dataInWarehouse['InStorehouseName']=$sinfo['storename'];
	    $dataInWarehouse['CreateDate']=$nowDate;
	    $dataInWarehouse['LastUpdateDate']=$nowDate;
	    $dataInWarehouse['token']=$this->token;
	    $dataInWarehouse['stoken']=$this->stoken;
	    $dataInWarehouse['IsPay']='0';
			$this->BM()->startTrans();
			foreach ($prolist as $proitem) {
				///////添加选卖商品/////////
				$savedata['ProId']=$proitem['pid'];
				$savedata['ProIdCard']=$proitem['pcid'];
				$savedata['token']=$this->token;
				$savedata['stoken']=$this->stoken;
				$savedata['Price']=$proitem['price'];
				if(!$this->BM('merpros')->add($savedata)){
					$res=false;
					break;
				}
				////////订货申请单字表////////
				if ($prolist['num']!='0') {
					$dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
		      $dataInWarehouseList['ProId']=$proitem['pid'];
		      $dataInWarehouseList['ProIdCard']=$proitem['pcid'];
		      $dataInWarehouseList['ClassId']=$proitem['cid'];
		      $dataInWarehouseList['Price']=$proitem['cprice'];
		      $dataInWarehouseList['Count']=$proitem['num'];
		      $dataInWarehouseList['Money']=$proitem['cprice']*$proitem['num'];
		      $dataInWarehouseList['IsMark']=0;
		      $dataInWarehouseList['Remarks']="";
		      $dataInWarehouseList['Supplier']="";
		      $dataInWarehouseList['CreateDate']=$nowDate;
		      $dataInWarehouseList['LastUpdateDate']=$nowDate;
		      $dataInWarehouseList['token']=$this->token;

					$dataInWarehouse['Count']+=$proitem['num'];
					$dataInWarehouse['Money']+= $dataInWarehouseList['Money'];
					if(!$this->BM('productinwarehouselist')->add($dataInWarehouseList)){
						$red=false;
						break;
					}
				}
			}
			if ($red) {
				if ($dataInWarehouse['Count']>0) {
					$red=$this->BM('productinwarehouse')->add($dataInWarehouse);
				}
			}

			if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'SaveError'), 'JSON');
      }
		} else {
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
	////////批量保存编辑自营商品的价格///////////////////
	public function selfproeditsave(){
		if (IS_POST) {
			$prodata=$_POST['prolist'];
			$prolist=json_decode($prodata,true);
			$nowDate=date('Y-m-d H:i:s',time());
			$res=true;
			$red=true;
			$this->BM()->startTrans();
			foreach ($prolist as $key => $value) {
				///////获得商品最低价格////////
				if (empty($minprice[$value['pid']])) {
					$minprice[$value['pid']]=$value['price'];
				} else {
					if (floatval($minprice[$value['pid']])>floatval($value['price'])) {
						$minprice[$value['pid']]=$value['price'];
					}
				}
				////////修改产品字表价格///////
				$savedata['Price']=$value['price'];
				$wheredata['ProId']=$value['pid'];
				$wheredata['ProIdCard']=$value['pcid'];
				$wheredata['token']=$this->token;
				$wheredata['stoken']=$this->stoken;
				if (!$this->BM('Productlist')->where($wheredata)->save($savedata)) {
					$red=false;
					break;
				}
			}
			//////修改产品主表信息////////
			foreach ($minprice as $k => $val) {
				$savesdata['PriceRange']=$val;
				$wheresdata['ProId']=$k;
				$wheresdata['token']=$this->token;
				$wheresdata['stoken']=$this->stoken;
				if (!$this->BM('Product')->where($wheresdata)->save($savesdata)) {
					$res=false;
					break;
				}
			}
			if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'SaveError'), 'JSON');
      }
		} else {
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
	///////批量保存编辑修改的平台商品/////////////////////////
	public function Factoryproeditsave(){
		if (IS_POST) {
			$prodata=$_POST['prolist'];
			$prolist=json_decode($prodata,true);
			$nowDate=date('Y-m-d H:i:s',time());
			$res=true;
			$red=true;
			$wh= 'wh'.substr($this->token, -8,8);//////主仓库名称
			$uinfo=$this->UM('user')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'id'=>session('userinfo')['ID']))->find();
			$sinfo=$this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
			// var_dump($prolist);exit();
			////////订货申请单主表信息//////
			$dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
			$dataInWarehouse['InWarehouseNumber']='';
	    $dataInWarehouse['Count']=0;
	    $dataInWarehouse['Money']=0.00;
	    $dataInWarehouse['Status']=0;
	    $dataInWarehouse['Date']=$nowDate;
	    $dataInWarehouse['InputId']=$uinfo['id'];
	    $dataInWarehouse['InputName']=$uinfo['TrueName'];
	    $dataInWarehouse['HandleId']='';
	    $dataInWarehouse['HandleName']='';
	    $dataInWarehouse['Type']='4';
	    $dataInWarehouse['Remarks']='';
	    $dataInWarehouse['InStorehouseId']=$wh.'_'.$sinfo['id'];
	    $dataInWarehouse['InStorehouseName']=$sinfo['storename'];
	    $dataInWarehouse['CreateDate']=$nowDate;
	    $dataInWarehouse['LastUpdateDate']=$nowDate;
	    $dataInWarehouse['token']=$this->token;
	    $dataInWarehouse['stoken']=$this->stoken;
	    $dataInWarehouse['IsPay']='0';
			$this->BM()->startTrans();
			foreach ($prolist as $proitem) {
				////////修改选卖商品的销售价格////////
				$wherestr['ProId']=$proitem['pid'];;
				$wherestr['ProIdCard']=$proitem['pcid'];
				$wherestr['token']=$this->token;
				$wherestr['stoken']=$this->stoken;
				$savedata['Price']=$proitem['price'];
				if(!$this->BM('merpros')->where($wherestr)->save($savedata)){
					$res=false;
					break;
				}

				////////订货申请单字表////////
				if ($prolist['num']!='0') {
					$dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
		      $dataInWarehouseList['ProId']=$proitem['pid'];
		      $dataInWarehouseList['ProIdCard']=$proitem['pcid'];
		      $dataInWarehouseList['ClassId']=$proitem['cid'];
		      $dataInWarehouseList['Price']=$proitem['cprice'];
		      $dataInWarehouseList['Count']=$proitem['num'];
		      $dataInWarehouseList['Money']=$proitem['cprice']*$proitem['num'];
		      $dataInWarehouseList['IsMark']=0;
		      $dataInWarehouseList['Remarks']="";
		      $dataInWarehouseList['Supplier']="";
		      $dataInWarehouseList['CreateDate']=$nowDate;
		      $dataInWarehouseList['LastUpdateDate']=$nowDate;
		      $dataInWarehouseList['token']=$this->token;

					$dataInWarehouse['Count']+=$proitem['num'];
					$dataInWarehouse['Money']+= $dataInWarehouseList['Money'];
					if(!$this->BM('productinwarehouselist')->add($dataInWarehouseList)){
						$red=false;
						break;
					}
				}
			}
			if ($red) {
				if ($dataInWarehouse['Count']>0) {
					$red=$this->BM('productinwarehouse')->add($dataInWarehouse);
				}
			}

			if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'SaveError'), 'JSON');
      }
		} else {
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
  ///////保存选择的平台商品/////////////
  public function savefactorypro(){
    if (IS_POST) {
      $proid=$_POST['proid'];
      $attrdata=$_POST['attrdata'];
			$type=$_POST['type'];
      $res=true;
      $this->BM()->startTrans();
			if ($type=='U') {
				foreach ($attrdata as $attritem) {
					$wherestr['ProId']=$proid;
					$wherestr['ProIdCard']=$attritem['proidcode'];
					$wherestr['token']=$this->token;
					$wherestr['stoken']=$this->stoken;
					$savedata['Price']=$attritem['setprice'];
					if(!$this->BM('merpros')->where($wherestr)->save($savedata)){
						$res=false;
						break;
					}
				}
			} else {
				foreach ($attrdata as $attritem) {
					$savedata['ProId']=$proid;
					$savedata['ProIdCard']=$attritem['proidcode'];
					$savedata['token']=$this->token;
					$savedata['stoken']=$this->stoken;
					$savedata['Price']=$attritem['setprice'];
					if(!$this->BM('merpros')->add($savedata)){
						$res=false;
						break;
					}
				}
			}
      if ($res) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
      } else {
        $this->rollback();
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'SaveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
    }
  }
  //添加或者编辑自营商品
	public function proedit(){
		// $proData['ProContent']=htmlspecialchars($_POST['ProContent']);
		$sqlju="SELECT ClassId,ClassName,ParentClassId, ClassGrade,ClassSort FROM RS_ProductClass WHERE IsVisible=1
		AND token='".$this->token."' AND stoken ='".$this->stoken."'";
		$classdata=$this->BM()->query($sqlju);
		if(IS_GET){
			$proid=$_GET['proid'];
			if($proid!=''){
				$titlename='商品修改';
				$Stype='proupdate';
				$prodata=$this->BM('Product')->where("ProId='%s'",array($proid))->find();
				$prolist=$this->BM('Productlist')->where("ProId='%s' and IsDelete=%d",array($proid,0))->select();
				$this->assign('prolist',$prolist);
				$this->assign('proid',$proid);
				$this->assign('Simgdata',json_encode(unserialize(stripcslashes($prodata['ProImgs']))));
				if($prodata){
					// var_dump($prodata['ProContent']);exit();
					$this->assign('Pdata',$prodata);//商品主表信息
				}
			} else{
				$titlename='商品添加';
				$Stype='proadd';
			}
		}
		$this->assign('Pclass',$classdata);//商品分类
		$this->assign('Cclass',json_encode($classdata));//商品分类
		$this->assign('Title',$titlename);
		$this->assign('Stype',$Stype);
		$this->display();
	}
  ////////////////////商品图片/////////////
	public function proimage(){
		if (IS_POST) {
			$upload=new \Think\Upload();
			$upload->maxSize=10485760;
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
			// $wh= 'tb_wh'.substr($this->token, -8,8);//主仓库表名
			// $dwh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;//当前仓库库表名
			$protype=$_POST['Ptype'];
			$Pdata['ProLogoImg']=$_POST['ProLogoImg'];
			$Pdata['ProName']=htmlspecialchars($_POST['ProName']);
			$Pdata['ProShowImg']=json_decode($_POST['ProShowImg'],true);
			$Pdata['ClassType']=$_POST['ClassType'];
			$Pdata['ClassName']=$_POST['ClassName'];
			$Pdata['ProAttrList']=json_decode($_POST['ProAttrList'],true);
			$Pdata['ProTitle']=htmlspecialchars($_POST['ProTitle']);
			$Pdata['ProSubtitle']=htmlspecialchars($_POST['ProSubtitle']);
			$Pdata['Price'] = $_POST['Price'];
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
			$Pdata['NumType']=$_POST['NumType'];
			$Pdata['ProContent']=$_POST['ProContent'];
			$delpraoattr=json_decode($_POST['Delproattr'],true);
			if($protype=='proadd'){
				$ProId="pro".substr(time(), 3).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
				//////主表数据//////////
				$prodata['ProId']=$ProId;
				$prodata['ProNumber']=$Pdata['ProNumber'];
				$prodata['ProName']=$Pdata['ProName'];
				$prodata['ProTitle']=$Pdata['ProTitle'];
				$prodata['ProSubtitle']=$Pdata['ProSubtitle'];
				$prodata['PriceRange']=$Pdata['Price'];
				$prodata['Price']=$Pdata['PriceRange'];
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
				$prodata['NumType']=$Pdata['NumType'];
				$prodata['ProContent']=htmlspecialchars($Pdata['ProContent']);
				$prodata['ExtendCut']=$Pdata['ExtendCut'];
				$prodata['stoken']=$this->stoken;
				$prodata['ProImgs']=serialize($Pdata['ProShowImg']);
				///////新品数据//////////
				// $newprodata['ProId']=$ProId;
				// $newprodata['ProLabel']='2';
				// $newprodata['LabelType']=0;
				// $newprodata['token']=$this->token;
				// $newprodata['stoken']=$this->stoken;
				//////end主表数据///////////////
				//////子表数据 和 仓库数据/////////////////
				foreach ($Pdata['ProAttrList'] as $key => $value) {
					$proldata['ProId']=$ProId;
					$proldata['ProIdInputCard']='';
					$proldata['ProIdCard']=$ProId.'_0'.($key+1);
					$proldata['ProSpec1']=$value['attr'];
					// $proldata['OldPrice']=$Pdata['Price'];
					$proldata['Price']='0';
					$proldata['EmpCut']=$Pdata['EmpCut'];
					$proldata['token']=$this->token;
					$proldata['Iszp']=$Pdata['Iszp'];
					$proldata['IsUseScore']=$Pdata['IsUseScore'];
					$proldata['Score']=$Pdata['Score'];
					$proldata['stoken']=$this->stoken;
					$proldata['Count']='0';
					$proldata['InputCode']='';
					$proldatas[$proldata['ProIdCard']]=$proldata;
					//////////////////////////////
					// $ckdata['ProId']=$ProId;
					// $ckdata['ProIdCard']=$ProId.'_0'.($key+1);
					// $ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
					// $ckdata['StockCount']=0;
					// $ckdata['LimitCount']=0;
					// $ckdata['VirtualCount']=0;
					// $ckdata['InCount']=0;
					// $ckdata['SalesCount']=0;
					// $ckdata['OutCount']=0;
					// $ckdata['ReturnCount']=0;
					// $ckdata['IsDelete']=0;
					// $ckdata['CreateDate']=date('Y-m-d H:i:s',time());
					// $cdata[$key]=$ckdata;
					/////////当前仓库数据///////////
					// $dckdata['ProId']=$ProId;
					// $dckdata['ProIdCard']=$ProId.'_0'.($key+1);
					// $dckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
					// $dckdata['StockCount']=$value['count'];
					// $dckdata['LimitCount']=0;
					// $dckdata['VirtualCount']=$value['count'];
					// $dckdata['InCount']=$value['count'];
					// $dckdata['SalesCount']=0;
					// $dckdata['OutCount']=0;
					// $dckdata['ReturnCount']=0;
					// $dckdata['IsDelete']=0;
					// $dckdata['CreateDate']=date('Y-m-d H:i:s',time());
					// $dcdata[$key]=$dckdata;
				}
				//////end子表数据 和 仓库数据//////////////
				//////展示图片/////////////////
				foreach ($Pdata['ProShowImg'] as $key => $img) {
					$imgdata[$key]=$img;
				}
				//////end展示图片/////////////////
				////////json数据//////////////////
				$json=array();
				$json=$prodata;
				$json['ProductList']=$proldatas;
				$json['imgs']=$imgdata;
				////////endjson数据//////////////////
				///////获得仓库表名/////////////////
				// $whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
				///////end获得仓库表名/////////////////
				$this->BM()->startTrans();
				// $this->WNM()->startTrans();
				$prores=$this->BM('Product')->add($prodata);
				$prolres=true;
				// $newpro=$this->BM('productlabellist')->add($newprodata);
				foreach ($proldatas as $value) {
					if (!$this->BM('Productlist')->add($value)) {
						$prolres=false;
						break;
					}
				}
				// $whprores=true;
				// foreach ($whlist as $whv) {
				// 	if(!$whprores){
				// 		break;
				// 	}
				// 	if ($whv['name']==$dwh) {
				// 		foreach ($dcdata as $dcv) {
				// 			if(!$this->WNM($dwh)->add($dcv)){
				// 				$whprores=false;
				// 				break;
				// 			}
				// 		}
				// 	} else {
				// 		foreach ($cdata as $cv) {
				// 			if(!$this->WNM($whv['name'])->add($cv)){
				// 				$whprores=false;
				// 				break;
				// 			}
				// 		}
				// 	}
				// }
				if($prores && $prolres){
					// file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
					$this->BM()->commit();
					// $this->WNM()->commit();
					$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
				} else{
					$this->BM()->rollback();
					// $this->WNM()->rollback();
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'bcError'), 'JSON');
				}
			} else{
				$ProId=$_POST['Ptype'];
				$proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$ProId.'.json'),true);
				//////主表数据//////////
				$prodata['ProId']=$ProId;
				$prodata['ProNumber']=$Pdata['ProNumber'];
				$prodata['ProName']=$Pdata['ProName'];
				$prodata['ProTitle']=$Pdata['ProTitle'];
				$prodata['ProSubtitle']=$Pdata['ProSubtitle'];
				$prodata['PriceRange']=$Pdata['Price'];
				$prodata['Price']=$Pdata['PriceRange'];
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
				$prodata['NumType']=$Pdata['NumType'];
				$prodata['ProContent']=htmlspecialchars($Pdata['ProContent']);
				$prodata['ExtendCut']=$Pdata['ExtendCut'];
				$prodata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$prodata['token']=$this->token;
				$prodata['stoken']=$this->stoken;
				$prodata['ProImgs']=serialize($Pdata['ProShowImg']);
				//////end主表数据///////////////

				$proattcount=$this->BM('Productlist')->
				where("ProId='%s' and token='%s' and stoken='%s' ",array($ProId,$this->token,$this->stoken))->field('ProIdCard')->Count();
				//////子表修改和新增数据 和 仓库/////////////////
				foreach ($Pdata['ProAttrList'] as $key => $value) {
					if ($value['ptype']=='add') {
						$proattcount=$proattcount+1;
						$proldata['ProId']=$ProId;
						$proldata['ProIdInputCard']='';
						$proldata['ProIdCard']=$ProId.'_0'.$proattcount;
						$proldata['ProSpec1']=$value['attr'];
						// $proldata['OldPrice']=$Pdata['Price'];
						$proldata['Price']='0';
						$proldata['EmpCut']=$Pdata['EmpCut'];
						$proldata['token']=$this->token;
						$proldata['Iszp']=$Pdata['Iszp'];
						$proldata['IsUseScore']=$Pdata['IsUseScore'];
						$proldata['Score']=$Pdata['Score'];
						$proldata['stoken']=$this->stoken;
						$proldata['Count']='';
						$proldata['InputCode']='';
						$proldatas[$proldata['ProIdCard']]=$proldata;///新增字表数据
						/////
						//////////////////////////////
						// $ckdata['ProId']=$ProId;
						// $ckdata['ProIdCard']=$ProId.'_0'.$proattcount;
						// $ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
						// $ckdata['StockCount']=0;
						// $ckdata['LimitCount']=0;
						// $ckdata['VirtualCount']=0;
						// $ckdata['InCount']=0;
						// $ckdata['SalesCount']=0;
						// $ckdata['OutCount']=0;
						// $ckdata['ReturnCount']=0;
						// $ckdata['IsDelete']=0;
						// $ckdata['CreateDate']=date('Y-m-d H:i:s',time());
						// $cdata[]=$ckdata;
						/////////当前仓库数据///////////
						// $dckdata['ProId']=$ProId;
						// $dckdata['ProIdCard']=$ProId.'_0'.$proattcount;
						// $dckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
						// $dckdata['StockCount']=$value['count'];
						// $dckdata['LimitCount']=0;
						// $dckdata['VirtualCount']=$value['count'];
						// $dckdata['InCount']=$value['count'];
						// $dckdata['SalesCount']=0;
						// $dckdata['OutCount']=0;
						// $dckdata['ReturnCount']=0;
						// $dckdata['IsDelete']=0;
						// $dckdata['CreateDate']=date('Y-m-d H:i:s',time());
						// $dcdata[]=$dckdata;
					} else {
						$proldatax['ProId']=$ProId;
						$proldatax['ProIdInputCard']='';
						$proldatax['ProIdCard']=$value['ptype'];
						$proldatax['ProSpec1']=$value['attr'];
						// $proldatax['OldPrice']=$Pdata['Price'];
						$proldatax['Price']='0';
						$proldatax['EmpCut']=$Pdata['EmpCut'];
						$proldatax['token']=$this->token;
						$proldatax['Iszp']=$Pdata['Iszp'];
						$proldatax['IsUseScore']=$Pdata['IsUseScore'];
						$proldatax['Score']=$Pdata['Score'];
						$proldatax['stoken']=$this->stoken;
						$proldatax['Count']='0';
						$proldatax['InputCode']=$value['inputcode'];
						$proldatax['LastUpdateDate']=date('Y-m-d H:i:s',time());
						$proldatau[$proldatax['ProIdCard']]=$proldatax;///修改字表数据
					}
				}
				//////end子表数据 和 仓库//////////////
				//需要删除的子表和仓库信息///////////////
				foreach ($delpraoattr as $key => $value) {
					$proldatad[]=$value;///删除的子表信息
					// $cdatad[]=$value;//需要删除的仓库信息
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
				////////endjson数据//////////////////
				///////获得仓库表名/////////////////
				// $whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
				///////end获得仓库表名/////////////////
				$this->BM()->startTrans();
				// $this->WNM()->startTrans();
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
				// foreach ($whlist as $whv) {
				// 	if(!$whprores){
				// 		break;
				// 	}
				// 	if ($whv['name']==$dwh) {
				// 		foreach ($dcdata as $dcv) {
				// 			if(!$this->WNM($dwh)->add($dcv)){
				// 				$whprores=false;
				// 				break;
				// 			}
				// 		}
				// 	} else {
				// 		foreach ($cdata as $cv) {
				// 			if(!$this->WNM($whv['name'])->add($cv)){
				// 				$whprores=false;
				// 				break;
				// 			}
				// 		}
				// 	}
				// }

				$whdprores=true;
				// foreach ($whlist as $whv) {
				// 	if(!$whdprores){
				// 		break;
				// 	}
				// 	foreach ($cdatad as $cv) {
				// 		$delete['IsDelete']=1;
				// 		if(!$this->WNM($whv['name'])->where("ProIdCard='%s'",$cv)->save($delete)){
				// 			$whdprores=false;
				// 			break;
				// 		}
				// 	}
				// }
				if($prolres && $prolures && $proldres && $whprores && $whdprores){
					// file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
					$this->BM()->commit();
					// $this->WNM()->commit();
					$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
				} else{
					$this->BM()->rollback();
					// $this->WNM()->rollback();
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'xgError'), 'JSON');
				}
			}
		} else{
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
	// public function prosave(){
	// 	if(IS_POST){
	// 		$wh= 'tb_wh'.substr($this->token, -8,8);//主仓库表名
	// 		$protype=$_POST['Ptype'];
	// 		$Pdata['ProLogoImg']=$_POST['ProLogoImg'];
	// 		$Pdata['ProName']=htmlspecialchars($_POST['ProName']);
	// 		$Pdata['ProShowImg']=json_decode($_POST['ProShowImg'],true);
	// 		$Pdata['ClassType']=$_POST['ClassType'];
	// 		$Pdata['ClassName']=$_POST['ClassName'];
	// 		$Pdata['ProAttrList']=json_decode($_POST['ProAttrList'],true);
	// 		$Pdata['ProTitle']=htmlspecialchars($_POST['ProTitle']);
	// 		$Pdata['ProSubtitle']=htmlspecialchars($_POST['ProSubtitle']);
	// 		$Pdata['Price']=$_POST['Price'];
	// 		$Pdata['PriceRange']=$_POST['PriceRange'];
	// 		$Pdata['ProNumber']=trim($_POST['ProNumber']);
	// 		$Pdata['Barcode']=trim($_POST['Barcode']);
	// 		$Pdata['Weight']=$_POST['Weight'];
	// 		$Pdata['Remarks']=htmlspecialchars($_POST['Remarks']);
	// 		$Pdata['KeyWord']=htmlspecialchars($_POST['KeyWord']);
	// 		$Pdata['EmpCut']=$_POST['EmpCut'];
	// 		$Pdata['Cut']=$_POST['Cut'];
	// 		$Pdata['ExtendCut']=$_POST['ExtendCut'];
	// 		$Pdata['IsUseConpon']=$_POST['IsUseConpon'];
	// 		$Pdata['Iszp']=$_POST['Iszp'];
	// 		$Pdata['IsUseScore']=$_POST['IsUseScore'];
	// 		$Pdata['Score']=$_POST['Score'];
	// 		$delpraoattr=json_decode($_POST['Delproattr'],true);
	// 		if($protype=='proadd'){
	// 			$ProId="pro".substr(time(), 3).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
	// 			//////主表数据//////////
	// 			$prodata['ProId']=$ProId;
	// 			$prodata['ProNumber']=$Pdata['ProNumber'];
	// 			$prodata['ProName']=$Pdata['ProName'];
	// 			$prodata['ProTitle']=$Pdata['ProTitle'];
	// 			$prodata['ProSubtitle']=$Pdata['ProSubtitle'];
	// 			$prodata['Price']=$Pdata['Price'];
	// 			$prodata['PriceRange']=$Pdata['PriceRange'];
	// 			$prodata['IsShelves']=1;
	// 			$prodata['Remarks']=$Pdata['Remarks'];
	// 			$prodata['KeyWord']=$Pdata['KeyWord'];
	// 			$prodata['ClassType']=$Pdata['ClassType'];
	// 			$prodata['ClassName']=$Pdata['ClassName'];
	// 			$prodata['SalesCount']=0;
	// 			$prodata['AttributeCount']=0;
	// 			$prodata['BrowseCount']=0;
	// 			$prodata['ProLogoImg']=$Pdata['ProLogoImg'];
	// 			$prodata['Cut']=$Pdata['Cut'];
	// 			$prodata['Cut2']=0;
	// 			$prodata['Cut3']=0;
	// 			$prodata['IsUseConpon']=$Pdata['IsUseConpon'];
	// 			$prodata['Barcode']=$Pdata['Barcode'];
	// 			$prodata['Weight']=$Pdata['Weight'];
	// 			$prodata['EmpCut']=$Pdata['EmpCut'];
	// 			$prodata['token']=$this->token;
	// 			$prodata['Iszp']=$Pdata['Iszp'];
	// 			$prodata['IsUseScore']=$Pdata['IsUseScore'];
	// 			$prodata['Score']=$Pdata['Score'];
	// 			$prodata['ExtendCut']=$Pdata['ExtendCut'];
	// 			$prodata['stoken']=$this->stoken;
	// 			///////新品数据//////////
	// 			$newprodata['ProId']=$ProId;
	// 			$newprodata['ProLabel']='2';
	// 			$newprodata['LabelType']=0;
	// 			$prodata['token']=$this->token;
	// 			$prodata['stoken']=$this->stoken;
	// 			//////end主表数据///////////////
	// 			//////子表数据 和 仓库数据/////////////////
	// 			foreach ($Pdata['ProAttrList'] as $key => $value) {
	// 				$proldata['ProId']=$ProId;
	// 				$proldata['ProIdInputCard']=$value['code'];
	// 				$proldata['ProIdCard']=$ProId.'_0'.($key+1);
	// 				$proldata['ProSpec1']=$value['attr'];
	// 				$proldata['OldPrice']=$Pdata['Price'];
	// 				$proldata['Price']=$value['price'];
	// 				$proldata['EmpCut']=$Pdata['EmpCut'];
	// 				$proldata['token']=$this->token;
	// 				$proldata['Iszp']=$Pdata['Iszp'];
	// 				$proldata['IsUseScore']=$Pdata['IsUseScore'];
	// 				$proldata['Score']=$Pdata['Score'];
	// 				$proldata['stoken']=$this->stoken;
	// 				$proldata['Count']=$value['count'];
	// 				$proldata['InputCode']=$value['inputcode'];
	// 				$proldatas[$proldata['ProIdCard']]=$proldata;
	// 				//////////////////////////////
	// 				$ckdata['ProId']=$ProId;
	// 				$ckdata['ProIdCard']=$ProId.'_0'.($key+1);
	// 				$ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
	// 				$ckdata['StockCount']=0;
	// 				$ckdata['LimitCount']=0;
	// 				$ckdata['VirtualCount']=0;
	// 				$ckdata['InCount']=0;
	// 				$ckdata['SalesCount']=0;
	// 				$ckdata['OutCount']=0;
	// 				$ckdata['ReturnCount']=0;
	// 				$ckdata['IsDelete']=0;
	// 				$ckdata['CreateDate']=date('Y-m-d H:i:s',time());
	// 				$cdata[$key]=$ckdata;
	// 			}
	// 			//////end子表数据 和 仓库数据//////////////
	// 			//////展示图片/////////////////
	// 			foreach ($Pdata['ProShowImg'] as $key => $img) {
	// 				$imgdata[$key]=$img;
	// 			}
	// 			//////end展示图片/////////////////
	// 			////////json数据//////////////////
	// 			$json=array();
	// 			$json=$prodata;
	// 			$json['ProductList']=$proldatas;
	// 			$json['imgs']=$imgdata;
	// 			////////endjson数据//////////////////
	// 			///////获得仓库表名/////////////////
	// 			$whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
	// 			///////end获得仓库表名/////////////////
	// 			$this->BM()->startTrans();
	// 			$this->WNM()->startTrans();
	// 			$prores=$this->BM('Product')->add($prodata);
	// 			$prolres=true;
	// 			$newpro=$this->BM('productlabellist')->add($newprodata);
	// 			foreach ($proldatas as $value) {
	// 				if (!$this->BM('Productlist')->add($value)) {
	// 					$prolres=false;
	// 					break;
	// 				}
	// 			}
	// 			$whprores=true;
	// 			foreach ($whlist as $whv) {
	// 				if(!$whprores){
	// 					break;
	// 				}
	// 				foreach ($cdata as $cv) {
	// 					if(!$this->WNM($whv['name'])->add($cv)){
	// 						$whprores=false;
	// 						break;
	// 					}
	// 				}
	// 			}
	// 			if($prores && $prolres && $whprores && $newpro){
	// 				file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
	// 				$this->BM()->commit();
	// 				$this->WNM()->commit();
	// 				$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
	// 			} else{
	// 				$this->BM()->rollback();
	// 				$this->WNM()->rollback();
	// 				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'bcError'), 'JSON');
	// 			}
	// 		} else{
	// 			$ProId=$_POST['Ptype'];
	// 			$proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$ProId.'.json'),true);
	// 			//////主表数据//////////
	// 			$prodata['ProId']=$ProId;
	// 			$prodata['ProNumber']=$Pdata['ProNumber'];
	// 			$prodata['ProName']=$Pdata['ProName'];
	// 			$prodata['ProTitle']=$Pdata['ProTitle'];
	// 			$prodata['ProSubtitle']=$Pdata['ProSubtitle'];
	// 			$prodata['Price']=$Pdata['Price'];
	// 			$prodata['PriceRange']=$Pdata['PriceRange'];
	// 			$prodata['Remarks']=$Pdata['Remarks'];
	// 			$prodata['KeyWord']=$Pdata['KeyWord'];
	// 			$prodata['ClassType']=$Pdata['ClassType'];
	// 			$prodata['ClassName']=$Pdata['ClassName'];
	// 			$prodata['ProLogoImg']=$Pdata['ProLogoImg'];
	// 			$prodata['Cut']=$Pdata['Cut'];
	// 			$prodata['IsUseConpon']=$Pdata['IsUseConpon'];
	// 			$prodata['Barcode']=$Pdata['Barcode'];
	// 			$prodata['Weight']=$Pdata['Weight'];
	// 			$prodata['EmpCut']=$Pdata['EmpCut'];
	// 			$prodata['Iszp']=$Pdata['Iszp'];
	// 			$prodata['IsUseScore']=$Pdata['IsUseScore'];
	// 			$prodata['Score']=$Pdata['Score'];
	// 			$prodata['ExtendCut']=$Pdata['ExtendCut'];
	// 			$prodata['LastUpdateDate']=date('Y-m-d H:i:s',time());
	// 			$prodata['token']=$this->token;
	// 			$prodata['stoken']=$this->stoken;
	// 			//////end主表数据///////////////
	//
	// 			$proattcount=$this->BM('Productlist')->
	// 			where("ProId='%s' and token='%s' and stoken='%s' ",array($ProId,$this->token,$this->stoken))->field('ProIdCard')->Count();
	// 			//////子表修改和新增数据 和 仓库/////////////////
	// 			foreach ($Pdata['ProAttrList'] as $key => $value) {
	// 				if ($value['ptype']=='add') {
	// 					$proattcount=$proattcount+1;
	// 					$proldata['ProId']=$ProId;
	// 					$proldata['ProIdInputCard']=$value['code'];
	// 					$proldata['ProIdCard']=$ProId.'_0'.$proattcount;
	// 					$proldata['ProSpec1']=$value['attr'];
	// 					$proldata['OldPrice']=$Pdata['Price'];
	// 					$proldata['Price']=$value['price'];
	// 					$proldata['EmpCut']=$Pdata['EmpCut'];
	// 					$proldata['token']=$this->token;
	// 					$proldata['Iszp']=$Pdata['Iszp'];
	// 					$proldata['IsUseScore']=$Pdata['IsUseScore'];
	// 					$proldata['Score']=$Pdata['Score'];
	// 					$proldata['stoken']=$this->stoken;
	// 					$proldata['Count']=$value['count'];
	// 					$proldata['InputCode']=$value['inputcode'];
	// 					$proldatas[$proldata['ProIdCard']]=$proldata;///新增字表数据
	// 					/////
	// 					//////////////////////////////
	// 					$ckdata['ProId']=$ProId;
	// 					$ckdata['ProIdCard']=$ProId.'_0'.$proattcount;
	// 					$ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
	// 					$ckdata['StockCount']=0;
	// 					$ckdata['LimitCount']=0;
	// 					$ckdata['VirtualCount']=0;
	// 					$ckdata['InCount']=0;
	// 					$ckdata['SalesCount']=0;
	// 					$ckdata['OutCount']=0;
	// 					$ckdata['ReturnCount']=0;
	// 					$ckdata['IsDelete']=0;
	// 					$ckdata['CreateDate']=date('Y-m-d H:i:s',time());
	// 					$cdata[]=$ckdata;
	// 				} else {
	// 					$proldatax['ProId']=$ProId;
	// 					$proldatax['ProIdInputCard']=$value['code'];
	// 					$proldatax['ProIdCard']=$value['ptype'];
	// 					$proldatax['ProSpec1']=$value['attr'];
	// 					$proldatax['OldPrice']=$Pdata['Price'];
	// 					$proldatax['Price']=$value['price'];
	// 					$proldatax['EmpCut']=$Pdata['EmpCut'];
	// 					$proldatax['token']=$this->token;
	// 					$proldatax['Iszp']=$Pdata['Iszp'];
	// 					$proldatax['IsUseScore']=$Pdata['IsUseScore'];
	// 					$proldatax['Score']=$Pdata['Score'];
	// 					$proldatax['stoken']=$this->stoken;
	// 					$proldatax['Count']=$value['count'];
	// 					$proldatax['InputCode']=$value['inputcode'];
	// 					$proldatax['LastUpdateDate']=date('Y-m-d H:i:s',time());
	// 					$proldatau[$proldatax['ProIdCard']]=$proldatax;///修改字表数据
	// 				}
	// 			}
	// 			//////end子表数据 和 仓库//////////////
	// 			//需要删除的子表和仓库信息///////////////
	// 			foreach ($delpraoattr as $key => $value) {
	// 				$proldatad[]=$value;///删除的子表信息
	// 				$cdatad[]=$value;//需要删除的仓库信息
	// 			}
	// 			///end需要删除的子表和仓库信息///////////////
	// 			//////展示图片/////////////////
	// 			foreach ($Pdata['ProShowImg'] as $key => $img) {
	// 				$imgdata[]=$img;
	// 			}
	// 			//////end展示图片/////////////////
	// 			////////json数据//////////////////
	// 			$json=array();
	// 			$json=$prodata;
	// 			if($proldatau==null){
	// 				$json['ProductList']=$proldatas;
	// 			} else if ($proldatas==null) {
	// 				$json['ProductList']=$proldatau;
	// 			} else{
	// 				$json['ProductList']=array_merge($proldatau,$proldatas);
	//
	// 			}
	// 			$json['ProContent']=$proinfo['ProContent'];
	// 			$json['imgs']=$imgdata;
	// 			////////endjson数据//////////////////
	// 			///////获得仓库表名/////////////////
	// 			$whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
	// 			///////end获得仓库表名/////////////////
	// 			$this->BM()->startTrans();
	// 			$this->WNM()->startTrans();
	// 			$prores=$this->BM('Product')->where("ProId='%s'",$prodata['ProId'])->save($prodata);
	// 			$prolres=true;
	// 			foreach ($proldatas as $value) {
	// 				if (!$this->BM('Productlist')->add($value)) {
	// 					$prolres=false;
	// 					break;
	// 				}
	// 			}
	// 			$prolures=true;
	// 			foreach ($proldatau as $value) {
	// 				if (!$this->BM('Productlist')->where("ProIdCard='%s'",$value['ProIdCard'])->save($value)) {
	// 					$prolures=false;
	// 					break;
	// 				}
	// 			}
	// 			$proldres=true;
	// 			foreach ($proldatad as $value) {
	// 				if (!$this->BM('Productlist')->where("ProIdCard='%s'",$value)->save(array('IsDelete'=>1))) {
	// 					$proldres=false;
	// 					break;
	// 				}
	// 			}
	// 			$whprores=true;
	// 			foreach ($whlist as $whv) {
	// 				if(!$whprores){
	// 					break;
	// 				}
	// 				foreach ($cdata as $cv) {
	// 					if(!$this->WNM($whv['name'])->add($cv)){
	// 						$whprores=false;
	// 						break;
	// 					}
	// 				}
	// 			}
	// 			$whdprores=true;
	// 			foreach ($whlist as $whv) {
	// 				if(!$whdprores){
	// 					break;
	// 				}
	// 				foreach ($cdatad as $cv) {
	// 					$delete['IsDelete']=1;
	// 					if(!$this->WNM($whv['name'])->where("ProIdCard='%s'",$cv)->save($delete)){
	// 						$whdprores=false;
	// 						break;
	// 					}
	// 				}
	// 			}
	// 			if($prolres && $prolures && $proldres && $whprores && $whdprores){
	// 				file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
	// 				$this->BM()->commit();
	// 				$this->WNM()->commit();
	// 				$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data-proid'=>$ProId), 'JSON');
	// 			} else{
	// 				$this->BM()->rollback();
	// 				$this->WNM()->rollback();
	// 				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'xgError'), 'JSON');
	// 			}
	// 		}
	// 	} else{
	// 		$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
	// 	}
	// }
  ///删除商品信息//////////////
  public function prodelete(){
    if(IS_POST){
      $wh= 'tb_wh'.substr($this->token, -8,8);//主仓库表名
      $ProIdlist=json_decode($_POST['ProId'],true);
      $type=$_POST['type'];
      if ($type=='1') {
				$dres=true;
				$this->BM()->startTrans();
				foreach ($ProIdlist as $ProId) {
					if(!$this->BM('Merpros')->where("ProId='%s'",$ProId)->delete()){
						$dres=false;
						break;
					}
				}
        if ($dres) {
					$this->BM()->commit();
          $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data'=>$ProIdlist), 'JSON');
        } else {
					$this->BM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'delproError'), 'JSON');
        }
      } else {
				// $whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
				$delres=true;
				$this->BM()->startTrans();
				// $this->WNM()->startTrans();
				foreach ($ProIdlist as $ProId) {
					$pres=$this->BM('Product')->where("ProId='%s'",$ProId)->delete();
					$plres=$this->BM('Productlist')->where("ProId='%s'",$ProId)->delete();
					$whdprores=true;
					// foreach ($whlist as $whv) {
					// 	$delete['IsDelete']=1;
					// 	if($this->WNM($whv['name'])->where("ProId='%s'",$ProId)->save($delete)===false){
					// 		$whdprores=false;
					// 		break;
					// 	}
					// }

					if($pres==false || $plres==false || $whdprores==false){
						$delres=false;
						break;
					}
				}
				if($delres){
					$this->BM()->commit();
					// $this->WNM()->commit();
					$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data'=>$ProIdlist), 'JSON');
				} else{
					$this->BM()->rollback();
					// $this->WNM()->rollback();
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'delproError'), 'JSON');
				}
      }
    } else{
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
    }
  }
	///修改商品上架状态//////////////
	public function setshelve(){
		if(IS_POST){
			$ProIdlist=json_decode($_POST['ProId'],true);
			$sres=true;
			$this->BM()->startTrans();
			foreach ($ProIdlist as $Pro) {
				$proarray=explode('|',$Pro);
				if($proarray[1]=='0'){
					$pro['IsShelves']='1';
				} else{
					$pro['IsShelves']='0';
				}
				if (!$this->BM('Product')->where("ProId='%s'",$proarray[0])->save($pro)) {
					$sres=false;
					break;
				}
			}
			if ($sres) {
				$this->BM()->commit();
				$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'true','data'=>$ProIdlist), 'JSON');
			} else {
				$this->BM()->rollback();
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'shelveError'), 'JSON');
			}
		} else{
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}
  // 分类设置信息，保存修改删除
	public function classedit(){
		if (IS_POST) {
			$cid = $_POST['cid'];
			$cname = $_POST['classname'];
			$csort = $_POST['classsort'];
			$type = $_POST['type'];
			if ($type=='1') {
				if ($cid=='add') {
					$cadata['ClassName']=$cname;
					$cadata['ParentClassId']='0';
					$cadata['RootId']='0';
					$cadata['ClassSort']=$csort;
					$cadata['ClassGrade']='1';
					$cadata['IsVisible']='1';
					$cadata['token']=$this->token;
					$cadata['stoken']=$this->stoken;
					$res= $this->BM('productclass')->add($cadata);
				} else {
					$cpadata['ClassName']=$cname;
					$cpadata['ClassSort']=$csort;
					$res= $this->BM('productclass')->where(array('ClassId'=>$cid))->save($cpadata);
				}
			} else {
				$plist = $this->BM('product')->where(array('ClassType'=>$cid))->select();
				if ($plist) {
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'delhasproError'), 'JSON');
					exit();
				} else {
					$res = $this->BM('productclass')->where(array('ClassId'=>$cid))->delete();
				}
			}
			if ($res) {
				$sqlStr="SELECT ClassId,ClassName,ParentClassId, ClassGrade,ClassSort FROM RS_ProductClass WHERE IsVisible=1
				AND token='".$this->token."' AND stoken ='".$this->stoken."' AND ClassGrade='1'";
				$classinfo=$this->BM()->query($sqlStr);
				$this->ajaxReturn(array('status' => 'true', 'datainfo' => $classinfo), 'JSON');
			} else {
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'Error'), 'JSON');
			}
		} else {
			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'PostError'), 'JSON');
		}
	}

}
?>
