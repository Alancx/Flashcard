<?php
namespace Sellermobile\Controller;
use Think\Controller;
class RecordController extends CommonController {
  public function record(){
    $today=date("Y-m-d 23:59:59",strtotime('0 day'));
    $startday=date("Y-m-d 00:00:00",strtotime('-6 day'));
    $ystartday=date("Y-m-d 00:00:00",strtotime('-1 day'));
    $yendday=date("Y-m-d 23:59:59",strtotime('-1 day'));
    $sdrevenue=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $orderyd=$this->BM('Order')->where("Status in(1,2,3,4,5,10) and PayDate BETWEEN '".$ystartday."' and '".$yendday."' and token='".$this->token."' and stoken='".$this->stoken."' and count>0 ")->count();
    $ymcount=$this->BM('Member')->where("token='".$this->token."' and stoken='".$this->stoken."' and RegisterDate BETWEEN '".$ystartday."' and '".$yendday."'")->count();
    $this->assign('sdrevenue',$sdrevenue);
    $this->assign('orderyd',$orderyd);
    $this->assign('ymcount',$ymcount);
    $this->assign('Title','数据统计');
    $this->display();
  }
  public function revenue(){
    $today=date("Y-m-d 23:59:59",strtotime('0 day'));
    $startday=date("Y-m-d 00:00:00",strtotime('-6 day'));
    $sdrevenue=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $maxev=$this->BM()->query("SELECT max(dsum) as dsum FROM (SELECT SUM(Price-Freight) AS dsum FROM RS_Order where (Status in(2,3,4,5,10)
    AND PayDate BETWEEN '".$startday."' AND '".$today."' AND token='".$this->token."' AND stoken='".$this->stoken."')
    GROUP BY convert(varchar(10),PayDate,120)) as daysum");
    $dataday="[";
    $daymon="[";
    for ($i=6; $i >=0 ; $i--) {
      $day=date("Y-m-d",strtotime('-'.$i.' day'));
      $evday=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-'.$i.' day'))."' and '".date('Y-m-d 23:59:59',strtotime('-'.$i.' day'))."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
      $evday=is_null($evday)?0:$evday;
      if ($i==6){
        $dataday=$dataday."'$day'";
        $daymon=$daymon.$evday;
      } else{
        $dataday=$dataday.","."'$day'";
        $daymon=$daymon.",".$evday;
      }
    }
    $dataday=$dataday."]";
    $daymon=$daymon."]";
    $this->assign('dataday',$dataday);
    $this->assign('daymon',$daymon);
    $this->assign('maxev',$maxev[0]['dsum']);
    $this->assign('sdrevenue',$sdrevenue);
    $this->assign('Title','店铺营收');
    $now=date('Y-m-d 23:59:59',time());
    $aweek=date('Y-m-d 00:00:00',strtotime('-7 days'));
    // $temp_data=$this->BM()->query("SELECT CONVERT(varchar(10),PayDate,20) as PayDate,SUM(Price) as money,COUNT(OrderId) as count FROM RS_Order WHERE token='{$this->token}' and stoken='{$this->stoken}' and Status in (2,3,4,5,10) and PayDate BETWEEN '{$aweek}' and '$now' GROUP BY CONVERT(varchar(10),PayDate,20)");
    $temp_data=$this->BM()->table('RS_Order')->where("token='{$this->token}' and stoken='{$this->stoken}' and Status in (2,3,4,5,10) and PayDate BETWEEN '{$aweek}' and '$now'")->group("CONVERT(varchar(10),PayDate,20)")->getField("CONVERT(varchar(10),PayDate,20) as PayDate,CONVERT(float(50),SUM(Price),20) as money,COUNT(OrderId) as count");
    // var_dump($this->BM()->getlastsql());
    // var_dump($temp_data);
    $table_data=array();
    for ($i=0; $i < 7; $i++) {
      $nowtime=date('Y-m-d',strtotime('-'.$i.' days'));
      if (array_key_exists($nowtime, $temp_data)) {
        $table_data[]=$temp_data[$nowtime];
      }else{
        $table_data[]=array('PayDate'=>$nowtime,'money'=>0,'count'=>0);
      }

    }
    // var_dump($table_data);
    $this->assign('table_data',$table_data);
    $this->display();
  }
  public function reorder(){
    $getday=$_GET['day'];
    $today=date("Y-m-d 23:59:59",strtotime($getday.' day'));
    $startday=date("Y-m-d 00:00:00",strtotime($getday.' day'));
    // $today=date("2017-03-13 23:59:59",strtotime('0 day'));
    // $startday=date("2017-03-13 00:00:00",strtotime('0 day'));

    $sqlStr=" SELECT a.OrderId,CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Status,a.Count,a.PayName,case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,a.Freight,b.MemberId, (CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in ('1','2','3','4','5','10') AND  a.Count>0  AND a.token='".$this->token."' AND a.stoken='".$this->stoken."' AND a.CreateDate BETWEEN '".$startday."' AND '".$today."' ";

    // $sdorder=$this->BM('Order')->where(" Status in(1,2,3,4,5,10) and CreateDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->select();
    $sdorder=$this->BM()->query($sqlStr);
    $sdordercount=count($sdorder);//////所有订单
    $sdorderpcount=0;////////未付款订单
    $sdorderfcount=0;///////发货订单
    $sdorderypcount=$this->BM()->query("SELECT count(MemberId) as mnum FROM (SELECT MemberId FROM RS_Order
      where status in(2,3,4,5,10) and CreateDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."' GROUP BY MemberId ) as c");
    $sdorderpmcount=0;
    foreach ($sdorder as $order) {
      if($order['Status']=='1'){
        $sdorderpcount++;
      }
      if (($order['Status']=='3')||($order['Status']=='4')) {
        $sdorderfcount++;
      }
      if (($order['Status']=='2')||($order['Status']=='3')||($order['Status']=='4')||($order['Status']=='5')||($order['Status']=='10')) {
        // $sdorderypcount++;
        $sdorderpmcount=$sdorderpmcount+$order['Price'];
      }
    }
    $dataday="[";
    $daymon="[";
    $day=date("Y-m-d 00:00:00",strtotime($getday.' day'));
    // $day=date("2017-03-13 00:00:00",strtotime('0 day'));
    for ($i=0; $i <=24 ; $i++) {
      $evday=$this->BM('Order')->where("CreateDate BETWEEN '".date('Y-m-d H:00:00',strtotime("$day"))."' and '".date('Y-m-d H:59:59',strtotime("$day"))."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
      $evday=is_null($evday)?0:$evday;
      if ($i==0){
        $dataday=$dataday."'$day'";
        $daymon=$daymon.$evday;
      } else{
        $dataday=$dataday.","."'$day'";
        $daymon=$daymon.",".$evday;
      }
      $day=date("Y-m-d H:i:s",strtotime("$day +1 hours"));
    }
    $dataday=$dataday."]";
    $daymon=$daymon."]";
    $this->assign('getday',$getday);
    $this->assign('dataday',$dataday);
    $this->assign('daymon',$daymon);
    $this->assign('sdordercount',$sdordercount);
    $this->assign('sdorderpcount',$sdorderpcount);
    $this->assign('sdorderfcount',$sdorderfcount);
    $this->assign('sdorderypcount',$sdorderypcount[0]['mnum']);
    $this->assign('sdorderpmcount',$sdorderpmcount);
    $this->assign('Title',date("m-d",strtotime($getday.' day')).'--订单');

    foreach ($sdorder as $key => $value) {
      $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
      FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlStr);
      $value['prolist']=$oderpro;
      $orderlist[$key]=$value;
    }
    $this->assign('orderlist',$orderlist);
    $this->display();
  }
  /////加载某一天的营业收入////////////////
  public function seldayven(){
    $page=$_POST['spage'];
    $sday=$_POST['sday'];
    $sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY PayDate) AS RowNumber, OrderId,
     case when left(Price,1)='.' then '0'+cast(Price as varchar) else cast(Price as varchar) end as Price,
    CONVERT(varchar(100), PayDate, 120) AS PayDate
     FROM  RS_Order WHERE stoken='".$this->stoken."' AND token='".$this->token."'
     AND PayDate like '%".$sday."%' AND Status in(2,3,4,5,10)) as c WHERE RowNumber>(20*".$page.")";
     $dataven=$this->BM()->query($sqlju);
     if($dataven){
   			$this->ajaxReturn(array('status' => 'true', 'info' => $dataven), 'JSON');
   		} else{
   			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
   		}
  }
  /////加载某一天订单////////////////
  public function otherdayorders(){
    $page=$_POST['spage'];
    $selday=$_POST['day'];
    $sday=date("Y-m-d",strtotime($selday.' day'));
    //  var_dump($sday);exit();
    $sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY PayDate) AS RowNumber, OrderId,Count,Freight,PayName,
     case when left(Price,1)='.' then '0'+cast(Price as varchar) else cast(Price as varchar) end as Price,
    CONVERT(varchar(100), CreateDate, 120) AS CreateDate
     FROM  RS_Order WHERE stoken='".$this->stoken."' AND token='".$this->token."'
     AND CreateDate like '%".$sday."%' AND Status in(2,3,4,5,10)) as c WHERE RowNumber>(20*".$page.")";

    //  var_dump($sqlju);exit();
     $data=$this->BM()->query($sqlju);
     foreach ($data as $key => $value) {
       $sqlju="SELECT  CONVERT(float(50),a.Price,50) AS Price,a.Count,b.ProLogoImg,b.ProName,b.ProTitle
       FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
       where a.OrderId='".$value['OrderId']."'";
       $oderpro=$this->BM()->query($sqlju);
       $value['prolist']=$oderpro;
       $dataorder[$key]=$value;
     }
     if($dataorder){
   			$this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
   		} else{
   			$this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
   		}
  }
/////////新增会员/////////////////
  public function newmember(){
    $ystartday=date("Y-m-d 00:00:00",strtotime('-50 day'));
    $yendday=date("Y-m-d 23:59:59",strtotime('-1 day'));
    $ymember=$this->BM('Member')->where("token='".$this->token."' and stoken='".$this->stoken."' and RegisterDate BETWEEN '".$ystartday."' and '".$yendday."'")->
    field('MemberId,MemberName,CONVERT(varchar(100), RegisterDate, 120) AS RegisterDate')->select();
    $this->assign('ymember',$ymember);
    $this->assign('Title','新增客户');
    $this->display();
  }
  /////////////今日收入明细/////////////////////////
  public function todaydetails(){
    $Ordertoday=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',time())."' and '".date('Y-m-d 23:59:59',time())."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $this->assign('countmoney',$Ordertoday);
    $this->assign('Title','今日收入');
    $this->display();
  }



}?>
