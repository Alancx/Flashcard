<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class OrdersController extends BaseController{

  ///////未完成的订单/////////////////
  public function Getnotendproinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $sqlStr=" SELECT a.OrderId,CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Status,a.Count,a.PayName,case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,a.Freight,b.MemberId, (CASE WHEN a.MemberId='".$shopinfo['StoreId']."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in ('2','3','5') AND  a.Count>0 AND a.PayName='T' AND a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."' ORDER BY a.CreateDate DESC";

      $data=$this->BM()->query($sqlStr);
      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $orderlist[$key]=$value;
      }
      if ($orderlist) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $orderlist), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'nodataError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ///////////所有订单信息////////////////
  public function GetAllOrdersinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      //退款中
      $wherearray='';
      $wherearray['Status']=array('eq','5');
      $wherearray['Count']=array('gt',0);
      $wherearray['token']=array('eq',$shopinfo['Token']);
      $wherearray['stoken']=array('eq',$shopinfo['Stoken']);
      $tkzordercont=$this->BM('order')->where($wherearray)->count();
      $pagedata['tkzcount']=$tkzordercont;
      //已完成
      $wherearray='';
      $wherearray['Status']=array('in','4,10');
      $wherearray['Count']=array('gt',0);
      $wherearray['token']=array('eq',$shopinfo['Token']);
      $wherearray['stoken']=array('eq',$shopinfo['Stoken']);
      // $wherearray['CONVERT(VARCHAR(10),GetDate,120)']=array('eq',date('Y-m-d',time()));
      $ywcordercont=$this->BM('order')->where($wherearray)->count();
      $pagedata['ywccount']=$ywcordercont;
      /////待付
      $wherearray='';
      $wherearray['Status']=array('eq','1');
      $wherearray['Count']=array('gt',0);
      $wherearray['token']=array('eq',$shopinfo['Token']);
      $wherearray['stoken']=array('eq',$shopinfo['Stoken']);
      $dfordercont=$this->BM('order')->where($wherearray)->count();
      $pagedata['dfcount']=$dfordercont;
      //待发货
      $wherearray='';
      $wherearray['Status']=array('eq','2');
      $wherearray['Count']=array('gt',0);
      $wherearray['token']=array('eq',$shopinfo['Token']);
      $wherearray['stoken']=array('eq',$shopinfo['Stoken']);
      $dfhordercont=$this->BM('order')->where($wherearray)->count();
      $pagedata['dfhcount']=$dfhordercont;
      //待提货
      $wherearray='';
      $wherearray['Status']=array('eq','2');
      $wherearray['RecevingPost']=array('eq','ZT');
      $wherearray['Count']=array('gt',0);
      $wherearray['token']=array('eq',$shopinfo['Token']);
      $wherearray['stoken']=array('eq',$shopinfo['Stoken']);
      $dthordercont=$this->BM('order')->where($wherearray)->count();
      $pagedata['dthcount']=$dthordercont;
      //已发货
      $wherearray='';
      $wherearray['Status']=array('eq','3');
      $wherearray['Count']=array('gt',0);
      $wherearray['token']=array('eq',$shopinfo['Token']);
      $wherearray['stoken']=array('eq',$shopinfo['Stoken']);
      $yfhordercont=$this->BM('order')->where($wherearray)->count();
      $pagedata['yfhcount']=$yfhordercont;
      //////已完成的订单///////////////
      $dataorder='';
      $sqlStr=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
      CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
      case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$shopinfo['StoreId']."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('4','10') AND  a.Count>0 AND
      a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."') c WHERE RowNumber>(10*0)";
      $data=$this->BM()->query($sqlStr);
      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $dataorder[$key]=$value;
      }
      $pagedata['ywcorder']=$dataorder;
      //////待付款的订单///////////////
      $dataorder='';
      $sqlStr=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
      CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
      case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$shopinfo['StoreId']."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('1') AND  a.Count>0 AND
      a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."') c WHERE RowNumber>(10*0)";
      $data=$this->BM()->query($sqlStr);
      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $dataorder[$key]=$value;
      }
      $pagedata['dforder']=$dataorder;
      //////待发货的订单///////////////
      $dataorder='';
      $sqlStr=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
      CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
      case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$shopinfo['StoreId']."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('2') AND  a.Count>0 AND
      a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."') c WHERE RowNumber>(10*0)";
      $data=$this->BM()->query($sqlStr);
      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $dataorder[$key]=$value;
      }
      $pagedata['dfhorder']=$dataorder;
      //////待提货的订单///////////////
      $dataorder='';
      $sqlStr=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
      CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
      case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$shopinfo['StoreId']."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('2')  AND a.RecevingPost='ZT' AND a.Count>0 AND
      a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."') c WHERE RowNumber>(10*0)";
      $data=$this->BM()->query($sqlStr);
      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $dataorder[$key]=$value;
      }
      $pagedata['dthorder']=$dataorder;
      //////已发货的订单///////////////
      $dataorder='';
      $sqlStr=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
      CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
      case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$shopinfo['StoreId']."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('3') AND  a.Count>0 AND
      a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."') c WHERE RowNumber>(10*0)";
      $data=$this->BM()->query($sqlStr);
      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $dataorder[$key]=$value;
      }
      $pagedata['yfhorder']=$dataorder;

      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  /////////获得退款订单信息///////////////////
  function GetTkOrdersinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $sqlStr="SELECT a.OrderId,
      CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
      case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId, b.MemberName,a.SceneMember,a.IsRcheck from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='5' AND  a.Count>0 AND
      a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."' AND a.MemberId!='".$shopinfo['StoreId']."' ORDER BY a.IsRcheck ";
      $data=$this->BM()->query($sqlStr);

      foreach ($data as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
        FROM RS_OrderList a
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $dataorder[$key]=$value;
      }
      if ($dataorder) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'queryError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }

  //////////退款处理同意或者拒绝///////////////////
  public function SetTkOrdersinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $oid=$info['oid'];
      $otype=$info['ltype'];
      $res=$this->BM('order')->where(array('OrderId'=>$oid))->save(array('IsRcheck'=>$otype));
      if ($res) {
        $sinfo['oid']=$oid;
        $sinfo['type']=$otype;
        $this->ajaxReturn(array('status' => 'true', 'info' => $sinfo), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'flase', 'info' => 'saveerror'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }




}?>
