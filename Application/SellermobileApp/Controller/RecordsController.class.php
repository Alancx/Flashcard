<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class RecordsController extends BaseController{

  //////////数据统计基本信息/////////////////////
  public function GetRecordsinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $today=date("Y-m-d 23:59:59",strtotime('-1 day'));
      $startday=date("Y-m-d 00:00:00",strtotime('-1 day'));
      $sqlStr="SELECT COUNT(*) AS allorder,COUNT((CASE WHEN Status='1' THEN '1' ELSE NULL END)) AS dforder,COUNT((CASE WHEN Status IN('3','4','10') THEN '1' ELSE NULL END)) AS yforder FROM RS_Order WHERE status in ('1','2','3','4','5','10') AND Count>0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND CreateDate BETWEEN '".$startday."' AND '".$today."'";
      $pagedata['orderinfo']=$this->BM()->query($sqlStr);
      $sqlStr="SELECT COUNT(distinct MemberId) AS mnum FROM RS_Order WHERE status in ('2','3','4','5','10') AND Count>0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND CreateDate BETWEEN '".$startday."' AND '".$today."'";
      $pagedata['fkmcount']=$this->BM()->query($sqlStr);
      $sqlStr="SELECT sum(Price-Freight) AS allmoney FROM RS_Order WHERE status in ('2','3','4','5','10') AND Count>0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND CreateDate BETWEEN '".$startday."' AND '".$today."'";
      $pagedata['allmoney']=$this->BM()->query($sqlStr);
      ////////////////////////////////////////////////////////
      $stoday=date("Y-m-d 23:59:59",strtotime('0 day'));
      $sstartday=date("Y-m-d 00:00:00",strtotime('-6 day'));
      $pagedata['sallmoney']=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$sstartday."' and '".$stoday."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->sum('Price-Freight');
      $pagedata['smaxmoney']=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$sstartday."' and '".$stoday."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->group("CONVERT(varchar(10),PayDate,20)")->order('maxmoney desc')->getField('sum(Price-Freight) AS maxmoney');

      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////////////昨日订单数据////////////////////////
  function GetYesOrderinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $today=date("Y-m-d 23:59:59",strtotime('-1 day'));
      $startday=date("Y-m-d 00:00:00",strtotime('-1 day'));

      $sqlStr="SELECT COUNT(*) AS allorder,COUNT((CASE WHEN Status='1' THEN '1' ELSE NULL END)) AS dforder,COUNT((CASE WHEN Status IN('3','4','10') THEN '1' ELSE NULL END)) AS yforder FROM RS_Order WHERE status in ('1','2','3','4','5','10') AND Count>0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND CreateDate BETWEEN '".$startday."' AND '".$today."'";
      $pagedata['orderinfo']=$this->BM()->query($sqlStr);
      $sqlStr="SELECT COUNT(distinct MemberId) AS mnum FROM RS_Order WHERE status in ('2','3','4','5','10') AND Count>0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND CreateDate BETWEEN '".$startday."' AND '".$today."'";
      $pagedata['fkmcount']=$this->BM()->query($sqlStr);
      $sqlStr="SELECT sum(Price-Freight) AS allmoney FROM RS_Order WHERE status in ('2','3','4','5','10') AND Count>0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND CreateDate BETWEEN '".$startday."' AND '".$today."'";
      $pagedata['allmoney']=$this->BM()->query($sqlStr);
      ///////////////////////////////////////////////////////////////////////
      $temp_data=$this->BM()->table('RS_Order')->where("token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."' and Status in (2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '$today'")->group("CONVERT(varchar(13),PayDate,20)")->getField("CONVERT(varchar(13),PayDate,20) as PayDate,COUNT(OrderId) as ocount");
      $day=date("Y-m-d 00:00:00",strtotime('-1 day'));
      $order_data=array();
      $order_day=array();
      for ($i=0; $i <= 24; $i++) {
        $keydate=date("Y-m-d H",strtotime($day));
        if (array_key_exists($keydate, $temp_data)) {
          $order_data[]=$temp_data[$keydate]['ocount'];
        }else{
          $order_data[]=0;
        }
        $order_day[]=$day;
        $day=date("Y-m-d H:i:s",strtotime("$day +1 hours"));
      }
      $pagedata['oday']=$order_day;
      $pagedata['odata']=$order_data;
      //////////////////////////////////////////////////////////////

      $sqlStr=" SELECT a.OrderId,CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Status,a.Count,a.PayName,case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,a.Freight,b.MemberId, (CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in ('1','2','3','4','5','10') AND  a.Count>0  AND a.token='".$shopinfo['Token']."' AND a.stoken='".$shopinfo['Stoken']."' AND a.CreateDate BETWEEN '".$startday."' AND '".$today."' ";
      $sdorder=$this->BM()->query($sqlStr);
      foreach ($sdorder as $key => $value) {
        $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
        a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
        FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
        where a.OrderId='".$value['OrderId']."'";
        $oderpro=$this->BM()->query($sqlStr);
        $value['prolist']=$oderpro;
        $orderlist[$key]=$value;
      }
      $pagedata['olist']=$orderlist;

      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
////////七日订单数据/////////////////////
public function GetSevOrderinfo($info){
  $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
  if ($shopinfo) {
    $stoday=date("Y-m-d 23:59:59",strtotime('0 day'));
    $sstartday=date("Y-m-d 00:00:00",strtotime('-6 day'));
    $pagedata['sallmoney']=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$sstartday."' and '".$stoday."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->sum('Price-Freight');
    $pagedata['smaxmoney']=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$sstartday."' and '".$stoday."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->group("CONVERT(varchar(10),PayDate,20)")->order('maxmoney desc')->getField('sum(Price-Freight) AS maxmoney');

    $temp_data=$this->BM()->table('RS_Order')->where("token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."' and Status in (2,3,4,5,10) and PayDate BETWEEN '".$sstartday."' and '$stoday'")->group("CONVERT(varchar(10),PayDate,20)")->getField("CONVERT(varchar(10),PayDate,20) as PayDate,CONVERT(float(50),SUM(Price),20) as money,COUNT(OrderId) as count");
    $table_data=array();
    for ($i=6; $i >= 0; $i--) {
      $nowtime=date('Y-m-d',strtotime('-'.$i.' days'));
      if (array_key_exists($nowtime, $temp_data)) {
        $table_data[]=$temp_data[$nowtime];
      }else{
        $table_data[]=array('PayDate'=>$nowtime,'money'=>0,'count'=>0);
      }
    }
    $pagedata['sevdata']=$table_data;
    $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');

  } else {
    $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
  }
}



}?>
