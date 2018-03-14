<?php
namespace Sellermobile\Controller;
use Think\Controller;
class UMWareHouseController extends CommonController{
  public function whmage()
  {
    $this->assign('Title','采购管理');
    $this->display();
  }

  public function inWarehouse()
  {
    $uinfo=$this->UM('user')->where(array('stoken'=>$this->stoken,'token'=>$this->token))->select();

    //$cinfo=$this->BM('productclass')->where(array('token'=>$this->token,'ClassGrade'=>2))->select();

    $cinfo=$this->BM()->query("SELECT pc.ClassId,pc.ClassName FROM RS_Product p LEFT JOIN RS_ProductClass pc ON p.ClassType=pc.ClassId WHERE p.stoken='".$this->stoken."' GROUP BY pc.ClassId,pc.ClassName");

    $sinfo=$this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
    //var_dump($this->BM()->getlastsql());exit;

    $wid='wh'.substr($sinfo['token'],-8).'_'.$sinfo['id'];

    $rkOrder=$this->BM('productinwarehouse')->where(array('InStorehouseId'=>$wid,'Status'=>0,'Type'=>5))->select();

    $this->assign('rkOrder',$rkOrder);

    $this->assign('uinfo',$uinfo);
    $this->assign('cinfo',$cinfo);
    $this->assign('sinfo',$sinfo);

    $this->assign('Title','商品入库单');
    $this->display();
  }

  public function addInWarehouse()
  {

    $data=$_POST;

    $this->BM()->startTrans();
    $this->WM()->startTrans();

    if ($data['mt']['itype']=='4')
    {
      //$data['mt']['id'];

      $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$data['mt']['id']))->setField(array('Status'=>'2'));


      if ($res)
      {
        $proList=$this->BM('productinwarehouselist')->where(array('InWarehouseId'=>$data['mt']['id']))->select();

        foreach ($proList as $key => $value)
        {
          $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount+".$value['Count'].",[VirtualCount]=VirtualCount+".$value['Count']." WHERE [ProIdCard] = '".$value['ProIdCard']."'");

          if ($res!==false)
          {
            $res=true;
          }
          else
          {
            $res=false;
          }
        }

        if ($res)
        {
          $this->WM()->commit();
          $this->BM()->commit();
          $this->ajaxReturn(array('status'=>true,'info'=>'success'),'JSON');
        }
        else
        {
          $this->WM()->rollback();
          $this->BM()->rollback();
          $this->ajaxReturn(array('status'=>false,'info'=>'01'),'JSON');
        }

      }
      else
      {
        $this->WM()->rollback();
        $this->BM()->rollback();
        $this->ajaxReturn(array('status'=>false,'info'=>'00'),'JSON');
      }

    }
    else
    {

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
      $dataInWarehouse['token']=$this->token;


      $res=false;

      foreach ($data['st'] as $key => $value)
      {
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
        $dataInWarehouseList['token']=$this->token;

        $res=$this->BM('productinwarehouselist')->add($dataInWarehouseList);

        if ($res)
        {

          $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount+".$value['nums'].",[VirtualCount]=VirtualCount+".$value['nums']." WHERE [ProIdCard] = '$key'");

          if ($res===false) {
            $this->BM()->rollback();
            $this->WM()->rollback();
            $this->ajaxReturn(array('status'=>false,'info'=>'0'),'JSON');
          }
        }
        else
        {
          $this->BM()->rollback();
          $this->WM()->rollback();
          $this->ajaxReturn(array('status'=>false,'info'=>'1'),'JSON');
        }
      }

      $res=$this->BM('productinwarehouse')->add($dataInWarehouse);

      if ($res)
      {
        $this->BM()->commit();
        $this->WM()->commit();
        $this->ajaxReturn(array('status'=>true,'info'=>'success'),'JSON');
      }
      else
      {
        $this->BM()->rollback();
        $this->WM()->rollback();
        $this->ajaxReturn(array('status'=>false,'info'=>'2'),'JSON');
      }
    }




  }

  public function getSQWarehouseInfo()
  {

    $data=$_POST['sqid'];

    $rkOrder=$this->BM()->query("SELECT p.ProName,pl.ProSpec1,piwl.Price,piwl.Count FROM RS_ProductInWarehouseList piwl LEFT JOIN RS_Product p ON piwl.ProId=p.ProId LEFT JOIN RS_ProductList pl ON piwl.ProIdCard=pl.ProIdCard WHERE piwl.InWarehouseId='".$data."'");

    if ($rkOrder)
    {
      $this->ajaxReturn(array('status'=>true,'data'=>$rkOrder),'JSON');
    }
    else
    {
      $this->ajaxReturn(array('status'=>false),'JSON');
    }

  }

  public function sqWarehouselist()
  {
    $whid=$_GET['whid'];
    if ($whid) {
      $this->BM('productinwarehouse')->where(array('InWarehouseId'=>$whid,'IsPay'=>'0'))->save(array('IsPay'=>'-1'));
    }
    $wh= 'wh'.substr($this->token, -8,8).'_'.$this->department;///当前门店id
    $sqlStr="SELECT * FROM RS_ProductInWarehouse WHERE  Type='4' AND token='".$this->token."' AND stoken='".$this->stoken."' AND InStorehouseId='".$wh."' ORDER BY Date DESC";
    // var_dump($sqlStr);exit();
    $warhouselist=$this->BM()->query($sqlStr);
    $this->assign('wahouselist',$warhouselist);
    $this->assign('Title','订货申请单列表');
    $this->display();
  }
  public function sqWarehouse()
  {
    $whid=$_GET['whid'];
    if ($whid) {
      $sqlStr="SELECT *,CONVERT(varchar(16), Date, 120) AS oDate FROM RS_ProductInWarehouse WHERE InWarehouseId='".$whid."' ";
      $warhouse=$this->BM()->query($sqlStr);
      $this->assign('warhouse',$warhouse[0]);
      $sqlStr="SELECT p.ProName,p.ProLogoImg,p.ClassType,p.ClassName,pl.ProIdCard,pl.ProSpec1,mp.Price,pwl.Price AS cosp,pwl.Count FROM RS_ProductInWarehouseList pwl LEFT JOIN RS_Product p ON pwl.ProId = p.ProId LEFT JOIN RS_ProductList pl ON pwl.ProIdCard = pl.ProIdCard LEFT JOIN RS_MerPros mp ON mp.ProIdCard= pl.ProIdCard AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' WHERE pwl.InWarehouseId='".$whid."'";
      $warhouselist=$this->BM()->query($sqlStr);
      $this->assign('warhouselist',$warhouselist);
      $this->assign('saveType',$whid);
    } else {
      $this->assign('saveType','0');
    }

    $wh= 'tb_wh'.substr($this->token, -8,8);//////主仓库名称
    $uinfo=$this->UM('user')->where(array('stoken'=>$this->stoken,'token'=>$this->token,'id'=>session('userinfo')['ID']))->find();

    $sqlStr="SELECT p.ProId,p.ProName,pl.ProIdCard,pl.ProSpec1,p.ProLogoImg,(CASE WHEN pl.CosPrice IS NULL THEN 0 ELSE pl.CosPrice END) AS CosPrice,mp.Price,pc.ClassId,pc.ClassName,pwh.StockCount FROM ".C('DB_BASE')['DB_NAME'].".dbo.RS_MerPros mp LEFT JOIN ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product p ON mp.ProId=p.ProId LEFT JOIN ".C('DB_BASE')['DB_NAME'].".dbo.RS_ProductList pl ON mp.ProIdCard = pl.ProIdCard LEFT JOIN ".C('DB_BASE')['DB_NAME'].".dbo.RS_ProductClass pc ON p.ClassType = pc.ClassId LEFT JOIN ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." pwh ON mp.ProIdCard = pwh.ProIdCard WHERE mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' AND pl.ProIdCard IS NOT NULL";
    $cinfo=$this->BM()->query($sqlStr);


    $sinfo=$this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();

    $this->assign('uinfo',$uinfo);
    $this->assign('cinfo',$cinfo);
    $this->assign('sinfo',$sinfo);

    $this->assign('Title','订货申请单');
    $this->display();
  }

  public function addSQWarehouse()
  {
    $data=$_POST;
    $totalprice=$data['tp'];
    $saveType=$data['saveType'];

    $this->BM()->startTrans();

    $nowDate=date('Y-m-d H:i:s',time());

    if ($saveType=='0') {
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
    $dataInWarehouse['token']=$this->token;
    $dataInWarehouse['stoken']=$this->stoken;
    $dataInWarehouse['IsPay']='0';


    $res=false;
    if($saveType!='0'){
      $red=$this->BM('productinwarehouselist')->where(array('InWarehouseId'=>$saveType))->delete();
      if ($red===false)
      {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status'=>false,'info'=>'1'),'JSON');
      }
    }

    foreach ($data['st'] as $key => $value)
    {
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
      $dataInWarehouseList['token']=$this->token;

      $res=$this->BM('productinwarehouselist')->add($dataInWarehouseList);

      if ($res)
      {
        $res=true;
      }
      else
      {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status'=>false,'info'=>'1'),'JSON');
      }
    }
    if ($saveType=='0') {
      $res=$this->BM('productinwarehouse')->add($dataInWarehouse);
    } else {
      $dataInWarehouseU['Money']=$totalprice;
      $dataInWarehouseU['Count']=$dataInWarehouse['Count'];
      $dataInWarehouseU['LastUpdateDate']=$nowDate;
      $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$saveType))->save($dataInWarehouseU);
    }


    if ($res)
    {
      $this->BM()->commit();
      $oid=$dataInWarehouse['InWarehouseId'];
      $this->ajaxReturn(array('status'=>true,'info'=>$oid),'JSON');
    }
    else
    {
      $this->BM()->rollback();
      $this->ajaxReturn(array('status'=>false,'info'=>'2'),'JSON');
    }
  }
//////////////删除订货申请单////////////////////////
public function delsqWarehouse(){
  $whid=$_POST['whid'];
  $this->BM()->startTrans();
  $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$whid))->delete();
  $red=$this->BM('productinwarehouselist')->where(array('InWarehouseId'=>$whid))->delete();
  if ($res && $red) {
    $this->BM()->commit();
    $this->ajaxReturn(array('status'=>'true','info'=>'true'),'JSON');
  } else {
    $this->BM()->rollback();
    $this->ajaxReturn(array('status'=>'false','info'=>'false'),'JSON');
  }
}
/////////////look订货申请单、、、、、、、、、、、、、
public function looksqWarehouse(){
  $whid=$_GET['whid'];
  $sqlStr="SELECT *,CONVERT(varchar(16), Date, 120) AS oDate FROM RS_ProductInWarehouse WHERE InWarehouseId='".$whid."' ";
  $warhouse=$this->BM()->query($sqlStr);
  $this->assign('warhouse',$warhouse[0]);
  $sqlStr="SELECT p.ProName,p.ProLogoImg,p.ClassType,p.ClassName,pl.ProIdCard,pl.ProSpec1,mp.Price,pwl.Price AS cosp,pwl.Count FROM RS_ProductInWarehouseList pwl LEFT JOIN RS_Product p ON pwl.ProId = p.ProId LEFT JOIN RS_ProductList pl ON pwl.ProIdCard = pl.ProIdCard LEFT JOIN RS_MerPros mp ON mp.ProIdCard= pl.ProIdCard AND mp.token='".$this->token."' AND mp.stoken='".$this->stoken."' WHERE pwl.InWarehouseId='".$whid."'";
  $warhouselist=$this->BM()->query($sqlStr);
  $this->assign('warhouselist',$warhouselist);
  $this->assign('Title','订货申请单详情');
  $this->display();
}
///////////////支付订货单申请///////////////////////////
  public function paysqWarehouse(){
    $oid=$_POST['whid'];
    $totalprice=$_POST['tprice'];
    $payArray=A('Payment')->getpayID($oid,$totalprice);
    if ($payArray==false) {
      $this->ajaxReturn(array('status'=>'false','info'=>'flase'),'JSON');
    } else {
      $this->ajaxReturn(array('status'=>'true','info'=>$payArray),'JSON');
    }
  }
  ///////////////提交订货单申请///////////////////////////
    public function sendsqWarehouse(){
      $oid=$_POST['whid'];
      $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$oid))->save(array('Status'=>'2'));
      if ($res) {
        $this->ajaxReturn(array('status'=>'true','info'=>'true'),'JSON');
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'false'),'JSON');
      }
    }
  public function outWarehouse()
  {

    $uinfo=$this->UM('user')->where(array('stoken'=>$this->stoken,'token'=>$this->token))->select();

    $cinfo=$this->BM()->query("SELECT * FROM(SELECT pc.ClassId,pc.ClassName FROM RS_MerPros mp LEFT JOIN RS_Product p ON mp.ProId=p.ProId AND p.stoken='0' LEFT JOIN RS_ProductClass pc ON p.ClassType=pc.ClassId WHERE mp.stoken='".$this->stoken."' GROUP BY pc.ClassId,pc.ClassName UNION ALL SELECT pc.ClassId,pc.ClassName FROM RS_Product p LEFT JOIN RS_ProductClass pc ON p.ClassType=pc.ClassId WHERE p.stoken='".$this->stoken."') temp GROUP BY temp.ClassId,temp.ClassName");

    $sinfo=$this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->select();


    $this->assign('uinfo',$uinfo);
    $this->assign('cinfo',$cinfo);
    $this->assign('sinfo',$sinfo);

    $this->assign('Title','商品出库单');
    $this->display();
  }


  public function addOutWarehouse()
  {
    $data=$_POST;

    $this->BM()->startTrans();
    $this->WM()->startTrans();

    $nowDate=date('Y-m-d H:i:s',time());

    $dataOutWarehouse['OutWarehouseId']='RK'.date('ymdHis',time()).time();
    $dataOutWarehouse['OutWarehouseNumber']=$data['mt']['id'];
    $dataOutWarehouse['Count']=0;
    $dataOutWarehouse['Money']=0;
    $dataOutWarehouse['Status']=1;
    $dataOutWarehouse['Date']=$data['mt']['idate'];
    $dataOutWarehouse['OutputId']=$data['mt']['ipid'];
    $dataOutWarehouse['OutputName']=$data['mt']['ipname'];
    $dataOutWarehouse['HandleId']='';
    $dataOutWarehouse['HandleName']='';
    $dataOutWarehouse['Type']=$data['mt']['itype'];
    $dataOutWarehouse['Remarks']=$data['mt']['remarks'];
    $dataOutWarehouse['OutStorehouseId']=$data['mt']['whid'];
    $dataOutWarehouse['OutStorehouseName']=$data['mt']['whname'];
    $dataOutWarehouse['CreateDate']=$nowDate;
    $dataOutWarehouse['LastUpdateDate']=$nowDate;
    $dataOutWarehouse['token']=$this->token;


    $res=false;

    foreach ($data['st'] as $key => $value) {
      $dataOutWarehouseList['OutWarehouseId']=$dataOutWarehouse['OutWarehouseId'];
      $dataOutWarehouseList['ProId']=explode('_', $key)[0];
      $dataOutWarehouseList['ProIdCard']=$key;
      $dataOutWarehouseList['ClassId']=$value['cid'];
      $dataOutWarehouseList['Price']=$value['price'];
      $dataOutWarehouseList['Count']=$value['nums'];
      $dataOutWarehouseList['Money']=$value['price']*$value['nums'];

      $dataOutWarehouse['Count']+=$value['nums'];
      $dataOutWarehouse['Money']+=$dataOutWarehouseList['Money'];

      $dataOutWarehouseList['IsMark']=0;
      $dataOutWarehouseList['Remarks']="";
      $dataOutWarehouseList['Supplier']="";
      $dataOutWarehouseList['CreateDate']=$nowDate;
      $dataOutWarehouseList['LastUpdateDate']=$nowDate;
      $dataOutWarehouseList['token']=$this->token;

      $res=$this->BM('productoutwarehouselist')->add($dataOutWarehouseList);

      if ($res)
      {

        $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount-".$value['nums'].",[VirtualCount]=VirtualCount-".$value['nums']." WHERE [ProIdCard] = '$key'");

        if ($res===false) {
          $this->BM()->rollback();
          $this->WM()->rollback();
          $this->ajaxReturn(array('status'=>false,'info'=>'0'),'JSON');
        }
      }
      else
      {
        $this->BM()->rollback();
        $this->WM()->rollback();
        $this->ajaxReturn(array('status'=>false,'info'=>'1'),'JSON');
      }
    }

    $res=$this->BM('productoutwarehouse')->add($dataOutWarehouse);

    if ($res)
    {
      $this->BM()->commit();
      $this->WM()->commit();
      $this->ajaxReturn(array('status'=>true,'info'=>'success'),'JSON');
    }
    else
    {
      $this->BM()->rollback();
      $this->WM()->rollback();
      $this->ajaxReturn(array('status'=>false,'info'=>'2'),'JSON');
    }


  }

  public function getProduct()
  {
    $data=$_POST;

    //$pinfo=$this->BM('product')->where(array('token'=>$this->token,'ClassType'=>$data['cid']))->field('ProId,ProName')->select();

    $pinfo=array();

    if ($data['type']=='dh')
    {
      $pinfo=$this->BM()->query("SELECT p.ProId,p.ProName FROM RS_MerPros mp LEFT JOIN RS_Product p ON mp.ProId = p.ProId AND p.stoken = '0' WHERE mp.stoken = '".$this->stoken."' AND p.ClassType='".$data['cid']."' GROUP BY p.ProId,p.ProName");
    }
    else if ($data['type']=='cg')
    {
      $pinfo=$this->BM()->query("SELECT ProId,ProName FROM RS_Product WHERE stoken='".$this->stoken."' AND ClassType='".$data['cid']."'");
    }
    else
    {
      $pinfo=$this->BM()->query("SELECT p.ProId,p.ProName FROM RS_MerPros mp LEFT JOIN RS_Product p ON mp.ProId = p.ProId AND p.stoken = '0' WHERE mp.stoken = '".$this->stoken."' AND p.ClassType='".$data['cid']."' UNION SELECT ProId,ProName FROM RS_Product WHERE stoken='".$this->stoken."' AND ClassType = '4'");
    }

    if ($pinfo)
    {
      # code...
    }
    else
    {
      $pinfo='-1';
    }
    $this->ajaxReturn(array('status'=>true,'info'=>'success','data'=>$pinfo),'JSON');
  }

  public function getProAttr()
  {
    $data=$_POST;

    $pinfo=$this->BM('productlist')->where(array('ProId'=>$data['pid']))->select();


    if ($pinfo) {

    }
    else
    {
      $pinfo='-1';
    }

    $this->ajaxReturn(array('status'=>true,'info'=>'success','data'=>$pinfo),'JSON');
  }



  public function readGoodInfo($GoodId)
  {
    $json_string = file_get_contents($this->REALPATH.C('STATICPATH').$GoodId.'.json');//读取json内容
    $goodsInfo=array();
    if ($json_string) {
      $goodsInfo=json_decode($json_string,true);
    }
    else
    {
      //如果没读取到文件 就读取数据库里的属性
    }
    return $goodsInfo;
  }
  /////////库存数量查询///////
  public function stockquery(){
    $wh= 'tb_wh'.substr($this->token, -8,8).'_'.$this->department;
    $sqlStr="SELECT a.ProId,a.IsShelves,a.ProName,a.ProTitle,a.ProLogoImg,a.Price,CASE WHEN (b.PCount) IS NULL THEN 0 ELSE b.PCount END PCount FROM  (SELECT p.ProId,p.IsShelves,p.ProName,p.ProTitle,p.ProLogoImg,p.CreateDate,(CASE WHEN p.stoken='".$this->stoken."' THEN p.PriceRange ELSE mp.Price END) AS Price FROM ".C('DB_BASE')['DB_NAME'].".dbo.RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM ".C('DB_BASE')['DB_NAME'].".dbo.RS_MerPros WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token='".$this->token."' AND (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."')) a LEFT JOIN (SELECT ProId,SUM(StockCount) AS PCount FROM ".C('DB_WAREHOUSE')['DB_NAME'].".dbo.".$wh." GROUP BY ProId) b ON a.ProId = b.ProId ORDER BY a.CreateDate";

    $prolist=$this->BM()->query($sqlStr);
    $this->assign('plist',$prolist);
    $this->assign('Title','库存查询');
    $this->display();
  }
}
