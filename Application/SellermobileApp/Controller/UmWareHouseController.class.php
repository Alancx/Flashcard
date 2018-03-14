<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class UmWareHouseController extends BaseController{

  ////////库存查询/////////
  public function StockQueryproinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $wh= 'tb_wh'.substr($shopinfo['Token'], -8,8).'_'.$shopinfo['StoreId'];
      $sqlStr="SELECT a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.Price,CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  (SELECT p.ProId,p.IsShelves,p.ProName,p.ProTitle,p.ProLogoImg,p.CreateDate,(CASE WHEN p.stoken='".$shopinfo['Stoken']."' THEN p.PriceRange ELSE mp.Price END) AS Price FROM ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM ".C('DB_BASE')['DB_NAME'].".dbo.RS_MerPros WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token='".$shopinfo['Token']."' AND (p.stoken='".$shopinfo['Stoken']."' OR mp.stoken='".$shopinfo['Stoken']."')) a LEFT JOIN (SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON a.ProId = b.ProId ORDER BY a.CreateDate";
      $prolist=$this->BM()->query($sqlStr);
      if ($prolist) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $prolist), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'queryError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////订货申请单///////
  public function GetSqOrderinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $type=$info['ltype'];
      $wh= 'tb_wh'.substr($shopinfo['Token'], -8,8);//////主仓库名称
      if ($type=='add') {
        $uinfo=$this->UM('user')->where(array('stoken'=>$shopinfo['Stoken'],'token'=>$shopinfo['Token'],'id'=>$shopinfo['UserId']))->find();
        $pagedata['uinfo'] = $uinfo;
        $sinfo=$this->BM('store')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->find();
        $pagedata['sinfo'] = $sinfo;
        $pagedata['nowdate'] = date('Y-m-d H:i',time());
      } else {
        $whid=$info['ltype'];
        $sqlStr="SELECT *,CONVERT(varchar(16), Date, 120) AS oDate FROM RS_ProductInWarehouse WHERE InWarehouseId='".$whid."' ";
        $warhouse = $this->BM()->query($sqlStr);
        $pagedata['warhouse']=$warhouse[0];
        $sqlStr="SELECT p.ProName,p.ProLogoImg,p.ClassType,p.ClassName,pl.ProIdCard,pl.ProSpec1,(case when mp.Price IS NULL THEN '0.00' else mp.Price end) as Price,pwl.Price AS cosp,pwl.Count FROM RS_ProductInWarehouseList pwl LEFT JOIN RS_Product p ON pwl.ProId = p.ProId LEFT JOIN RS_ProductList pl ON pwl.ProIdCard = pl.ProIdCard LEFT JOIN RS_MerPros mp ON mp.ProIdCard= pl.ProIdCard AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' WHERE pwl.InWarehouseId='".$whid."'";
        $pagedata['warhouselist'] = $this->BM()->query($sqlStr);
      }

      $sqlStr="SELECT p.ProId,p.ProName,pl.ProIdCard,pl.ProSpec1,p.ProLogoImg,(CASE WHEN pl.CosPrice IS NULL THEN 0 ELSE pl.CosPrice END) AS CosPrice,mp.Price,pc.ClassId,pc.ClassName,pwh.StockCount FROM ".C('DB_BASE')['DB_NAME'].".dbo.RS_MerPros mp LEFT JOIN ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product p ON mp.ProId=p.ProId LEFT JOIN ".C('DB_BASE')['DB_NAME'].".dbo.RS_ProductList pl ON mp.ProIdCard = pl.ProIdCard LEFT JOIN ".C('DB_BASE')['DB_NAME'].".dbo.RS_ProductClass pc ON p.ClassType = pc.ClassId LEFT JOIN ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." pwh ON mp.ProIdCard = pwh.ProIdCard WHERE mp.token='".$shopinfo['Token']."' AND mp.stoken='".$shopinfo['Stoken']."' AND pl.ProIdCard IS NOT NULL";
      $cinfo=$this->BM()->query($sqlStr);
      $pagedata['cinfo'] = $cinfo;

      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  /////保存订货申请单/////
  public function SaveSqOrderinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $data=$info;
      $totalprice=$data['tp'];
      $saveType=$data['ltype'];
      $this->BM()->startTrans();

      $nowDate=date('Y-m-d H:i:s',time());

      if ($saveType=='add') {
        $dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
      } else {
        $dataInWarehouse['InWarehouseId']=$saveType;
      }

      $dataInWarehouse['InWarehouseNumber']=$data['mt']['id'];
      $dataInWarehouse['Count']=0;
      $dataInWarehouse['Money']=$totalprice;
      $dataInWarehouse['Status']=$data['mt']['istatus'];
      $dataInWarehouse['Date']=$data['mt']['idate'];
      $dataInWarehouse['InputId']=$data['mt']['ipid'];
      $dataInWarehouse['InputName']=$data['mt']['ipname'];
      $dataInWarehouse['HandleId']='';
      $dataInWarehouse['HandleName']='';
      $dataInWarehouse['Type']=$data['mt']['itype'];
      $dataInWarehouse['Remarks']=$data['mt']['remarks'];
      $dataInWarehouse['InStorehouseId']=$data['mt']['whid'];
      $dataInWarehouse['InStorehouseName']=$data['mt']['whname'];
      $dataInWarehouse['CreateDate']=$nowDate;
      $dataInWarehouse['LastUpdateDate']=$nowDate;
      $dataInWarehouse['token']=$shopinfo['Token'];
      $dataInWarehouse['stoken']=$shopinfo['Stoken'];
      $dataInWarehouse['IsPay']='0';


      $res=false;
      if($saveType!='add'){
        $red=$this->BM('productinwarehouselist')->where(array('InWarehouseId'=>$saveType))->delete();
        if ($red===false){
          $this->BM()->rollback();
          $this->ajaxReturn(array('status'=>'false','info'=>'saveError_1'),'JSON');
        }
      }

      foreach ($data['st'] as $key => $value){
        $dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
        $dataInWarehouseList['ProId']=explode('_', $key)[0];
        $dataInWarehouseList['ProIdCard']=$key;
        $dataInWarehouseList['ClassId']=$value['cid'];
        $dataInWarehouseList['Price']=$value['price'];
        $dataInWarehouseList['Count']=$value['nums'];
        $dataInWarehouseList['Money']=$value['price']*$value['nums'];

        $dataInWarehouse['Count']+=$value['nums'];

        $dataInWarehouseList['IsMark']=0;
        $dataInWarehouseList['Remarks']="";
        $dataInWarehouseList['Supplier']="";
        $dataInWarehouseList['CreateDate']=$nowDate;
        $dataInWarehouseList['LastUpdateDate']=$nowDate;
        $dataInWarehouseList['token']=$shopinfo['Token'];

        $res=$this->BM('productinwarehouselist')->add($dataInWarehouseList);

        if ($res){
          $res=true;
        } else {
          $this->BM()->rollback();
          $this->ajaxReturn(array('status'=>'false','info'=>'saveError_2'),'JSON');
        }
      }
      if ($saveType=='add') {
        $res=$this->BM('productinwarehouse')->add($dataInWarehouse);
      } else {
        $dataInWarehouseU['Money']=$totalprice;
        $dataInWarehouseU['Count']=$dataInWarehouse['Count'];
        $dataInWarehouseU['LastUpdateDate']=$nowDate;
        $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$saveType))->save($dataInWarehouseU);
      }


      if ($res){
        $this->BM()->commit();
        $oid=$dataInWarehouse['InWarehouseId'];
        $this->ajaxReturn(array('status'=>'true','info'=>$oid),'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status'=>'false','info'=>'saveError_3'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  /////订货申请单列表/////
  public function GetSqorderListinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $wh= 'wh'.substr($shopinfo['Token'], -8,8).'_'.$shopinfo['StoreId'];///当前门店id
      $sqlStr="SELECT * FROM RS_ProductInWarehouse WHERE  Type='4' AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND InStorehouseId='".$wh."' ORDER BY Date DESC";
      $warhouselist=$this->BM()->query($sqlStr);
      if ($warhouselist) {
        $this->ajaxReturn(array('status'=>'true','info'=>$warhouselist),'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'nodateError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////////提交订货申请单//////
  public function SendSqorderListinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $oid=$info['whid'];
      $paytype=$info['paytype'];
      if ($paytype=='0'){
        $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$oid))->save(array('Status'=>'-1'));
      } else {
        $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$oid))->save(array('Status'=>'2'));
      }
      if ($res) {
        $pagedata['whid']=$oid;
        $pagedata['paytype']=$paytype;
        $this->ajaxReturn(array('status'=>'true','info'=>$pagedata),'JSON');
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'sendError'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////刪除訂貨申請單///////
  public function DelSqOrderListinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $whid=$info['whid'];
      $this->BM()->startTrans();
      $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$whid))->delete();
      $red=$this->BM('productinwarehouselist')->where(array('InWarehouseId'=>$whid))->delete();
      if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status'=>'true','info'=>$whid),'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status'=>'false','info'=>'delError'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////查看訂貨申請閑情/////////////
  public function LGetSqOrderinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $whid=$info['ltype'];
      $sqlStr="SELECT *,CONVERT(varchar(16), Date, 120) AS oDate FROM RS_ProductInWarehouse WHERE InWarehouseId='".$whid."' ";
      $warhouse = $this->BM()->query($sqlStr);
      $pagedata['warhouse']=$warhouse[0];
      $sqlStr="SELECT p.ProName,p.ProLogoImg,p.ClassType,p.ClassName,pl.ProIdCard,pl.ProSpec1,(case when mp.Price IS NULL THEN '0.00' else mp.Price end) as Price,pwl.Price AS cosp,pwl.Count FROM RS_ProductInWarehouseList pwl LEFT JOIN RS_Product p ON pwl.ProId = p.ProId LEFT JOIN RS_ProductList pl ON pwl.ProIdCard = pl.ProIdCard LEFT JOIN RS_MerPros mp ON mp.ProIdCard= pl.ProIdCard AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' WHERE pwl.InWarehouseId='".$whid."'";
      $pagedata['warhouselist'] = $this->BM()->query($sqlStr);
      $this->ajaxReturn(array('status'=>'true','info'=>$pagedata),'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////入库单数据///////////
  public function GetInwarehouseinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      /////订货申请单/////
      $wid='wh'.substr($shopinfo['Token'],-8).'_'.$shopinfo['StoreId'];
      $rkOrder=$this->BM('productinwarehouse')->where(array('InStorehouseId'=>$wid,'Status'=>0,'Type'=>5))->select();
      $pagedata['dhorder'] = $rkOrder;

      /////采购入库单据//////
      $pagedata['cgwhid'] = 'RK'.date('ymdHis',time()).time();
      $pagedata['nowtime'] = date('Y-m-d H:i:s',time());
      $sqlStr="SELECT p.ProId,p.ProName,p.ProLogoImg,pl.ProIdCard,pl.ProSpec1,pl.Price,pc.ClassId,pc.ClassName FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId = pl.ProId LEFT JOIN RS_ProductClass pc ON p.ClassType = pc.ClassId  WHERE p.token ='".$shopinfo['Token']."' AND p.stoken = '".$shopinfo['Stoken']."' AND pl.IsDelete = '0'";
      $cinfo=$this->BM()->query($sqlStr);
      $pagedata['cinfo']=$cinfo;



      /////////公用数据///////
      $uinfo=$this->UM('user')->where(array('stoken'=>$shopinfo['Stoken'],'token'=>$shopinfo['Token'],'id'=>$shopinfo['UserId']))->find();
      $pagedata['uinfo'] = $uinfo;
      $sinfo=$this->BM('store')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->find();
      $pagedata['sinfo'] = $sinfo;

      $this->ajaxReturn(array('status' => 'true' , 'info' => $pagedata),'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////獲得單個訂貨單數據/////
  public function GetInwhOneinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $whid = $info['iwhid'];
      $sqlStr="SELECT *,CONVERT(varchar(16), Date, 120) AS oDate FROM RS_ProductInWarehouse WHERE InWarehouseId='".$whid."' ";
      $warhouse = $this->BM()->query($sqlStr);
      $pagedata['warhouse']=$warhouse[0];
      $sqlStr="SELECT p.ProName,p.ProLogoImg,p.ClassType,p.ClassName,pl.ProIdCard,pl.ProSpec1,(case when mp.Price IS NULL THEN '0.00' else mp.Price end) as Price,pwl.Price AS cosp,pwl.Count FROM RS_ProductInWarehouseList pwl LEFT JOIN RS_Product p ON pwl.ProId = p.ProId LEFT JOIN RS_ProductList pl ON pwl.ProIdCard = pl.ProIdCard LEFT JOIN RS_MerPros mp ON mp.ProIdCard= pl.ProIdCard AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' WHERE pwl.InWarehouseId='".$whid."'";
      $pagedata['warhouselist'] = $this->BM()->query($sqlStr);
      $pagedata['nowtime'] = date('Y-m-d H:i:s',time());
      $this->ajaxReturn(array('status'=>'true','info'=>$pagedata),'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////保存訂貨入庫////////////////
  public function SenddhInwarehouseinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $data=$info;
      $this->BM()->startTrans();
      $this->WM()->startTrans();
      if ($data['mt']['itype']=='4'){
          $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$data['mt']['id']))->setField(array('Status'=>'2'));

          if ($res){
            $proList=$this->BM('productinwarehouselist')->where(array('InWarehouseId'=>$data['mt']['id']))->select();

            foreach ($proList as $key => $value){
              $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount+".$value['Count'].",[VirtualCount]=VirtualCount+".$value['Count']." WHERE [ProIdCard] = '".$value['ProIdCard']."'");

              if ($res!==false){
                $res=true;
              } else {
                $res=false;
              }
            }

            if ($res){
              $this->WM()->commit();
              $this->BM()->commit();
              $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
            }else{
              $this->WM()->rollback();
              $this->BM()->rollback();
              $this->ajaxReturn(array('status'=>'false','info'=>'saveError'),'JSON');
            }

          }else{
            $this->WM()->rollback();
            $this->BM()->rollback();
            $this->ajaxReturn(array('status'=>'false','info'=>'saveError'),'JSON');
          }
      } else {
        $nowDate=date('Y-m-d H:i:s',time());

        $dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
        $dataInWarehouse['InWarehouseNumber']=$data['mt']['id'];
        $dataInWarehouse['Count']=0;
        $dataInWarehouse['Money']=0;
        $dataInWarehouse['Status']=1;
        $dataInWarehouse['Date']=$data['mt']['idate'];
        $dataInWarehouse['InputId']=$data['mt']['ipid'];
        $dataInWarehouse['InputName']=$data['mt']['ipname'];
        $dataInWarehouse['HandleId']='';
        $dataInWarehouse['HandleName']='';
        $dataInWarehouse['Type']=$data['mt']['itype'];
        $dataInWarehouse['Remarks']=$data['mt']['remarks'];
        $dataInWarehouse['InStorehouseId']=$data['mt']['whid'];
        $dataInWarehouse['InStorehouseName']=$data['mt']['whname'];
        $dataInWarehouse['CreateDate']=$nowDate;
        $dataInWarehouse['LastUpdateDate']=$nowDate;
        $dataInWarehouse['token']=$shopinfo['Token'];


        $res=false;

        foreach ($data['st'] as $key => $value){
          $dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
          $dataInWarehouseList['ProId']=explode('_', $key)[0];
          $dataInWarehouseList['ProIdCard']=$key;
          $dataInWarehouseList['ClassId']=$value['cid'];
          $dataInWarehouseList['Price']=$value['price'];
          $dataInWarehouseList['Count']=$value['nums'];
          $dataInWarehouseList['Money']=$value['price']*$value['nums'];

          $dataInWarehouse['Count']+=$value['nums'];
          $dataInWarehouse['Money']+=$dataInWarehouseList['Money'];

          $dataInWarehouseList['IsMark']=0;
          $dataInWarehouseList['Remarks']="";
          $dataInWarehouseList['Supplier']="";
          $dataInWarehouseList['CreateDate']=$nowDate;
          $dataInWarehouseList['LastUpdateDate']=$nowDate;
          $dataInWarehouseList['token']=$shopinfo['Token'];

          $res=$this->BM('productinwarehouselist')->add($dataInWarehouseList);

          if ($res){

            $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount+".$value['nums'].",[VirtualCount]=VirtualCount+".$value['nums']." WHERE [ProIdCard] = '$key'");

            if ($res===false) {
              $this->BM()->rollback();
              $this->WM()->rollback();
              $this->ajaxReturn(array('status'=>'false','info'=>'0'),'JSON');
            }
          }else{
            $this->BM()->rollback();
            $this->WM()->rollback();
            $this->ajaxReturn(array('status'=>'false','info'=>'1'),'JSON');
          }
        }

        $res=$this->BM('productinwarehouse')->add($dataInWarehouse);

        if ($res){
          $this->BM()->commit();
          $this->WM()->commit();
          $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
        }else{
          $this->BM()->rollback();
          $this->WM()->rollback();
          $this->ajaxReturn(array('status'=>'false','info'=>'2'),'JSON');
        }
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }



}?>
