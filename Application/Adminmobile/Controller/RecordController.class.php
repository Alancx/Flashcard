<?php
namespace Adminmobile\Controller;
use Think\Controller;
class RecordController extends CommonController {
  public function record(){
    $today=date("Y-m-d 23:59:59",strtotime('0 day'));
    $startday=date("Y-m-d 00:00:00",strtotime('-6 day'));
    $ystartday=date("Y-m-d 00:00:00",strtotime('-1 day'));
    $yendday=date("Y-m-d 23:59:59",strtotime('-1 day'));
    $sdrevenue=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $orderyd=$this->BM('Order')->where("Status in(1,2,3,4) and PayDate BETWEEN '".$ystartday."' and '".$yendday."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
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
    $this->display();
  }
  public function reorder(){
    $today=date("Y-m-d 23:59:59",strtotime('-1 day'));
    $startday=date("Y-m-d 00:00:00",strtotime('-1 day'));
    $sdorder=$this->BM('Order')->where(" Status in(1,2,3,4) and CreateDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->select();
    $sdordercount=count($sdorder);
    $sdorderpcount=0;
    $sdorderfcount=0;
    $sdorderypcount=$this->BM()->query("SELECT count(MemberId) as mnum FROM (SELECT MemberId FROM RS_Order
      where status in(1,2,3,4) and CreateDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."' GROUP BY MemberId ) as c");
    $sdorderpmcount=0;
    foreach ($sdorder as $order) {
      if($order['Status']=='1'){
        $sdorderpcount++;
      }
      if (($order['Status']=='3')||($order['Status']=='4')) {
        $sdorderfcount++;
      }
      if (($order['Status']=='2')||($order['Status']=='3')||($order['Status']=='4')) {
        // $sdorderypcount++;
        $sdorderpmcount=$sdorderpmcount+$order['Price'];
      }
    }
    $dataday="[";
    $daymon="[";
    $day=date("Y-m-d 00:00:00",strtotime('-1 day'));
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
    $this->assign('dataday',$dataday);
    $this->assign('daymon',$daymon);
    $this->assign('sdordercount',$sdordercount);
    $this->assign('sdorderpcount',$sdorderpcount);
    $this->assign('sdorderfcount',$sdorderfcount);
    $this->assign('sdorderypcount',$sdorderypcount[0]['mnum']);
    $this->assign('sdorderpmcount',$sdorderpmcount);
    $this->assign('sorder',$sdorder);
    $this->assign('Title','店铺订单');
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
  /////加载昨日订单////////////////
  public function yesorders(){
    $page=$_POST['spage'];
    $sday=date("Y-m-d",strtotime('-1 day'));
    $sqlju=" SELECT top 20 * FROM
		(SELECT ROW_NUMBER() OVER (ORDER BY PayDate) AS RowNumber, OrderId,Count,Freight,
     case when left(Price,1)='.' then '0'+cast(Price as varchar) else cast(Price as varchar) end as Price,
    CONVERT(varchar(100), CreateDate, 120) AS CreateDate
     FROM  RS_Order WHERE stoken='".$this->stoken."' AND token='".$this->token."'
     AND CreateDate like '%".$sday."%' AND Status in(1,2,3,4)) as c WHERE RowNumber>(20*".$page.")";
     $data=$this->BM()->query($sqlju);
     foreach ($data as $key => $value) {
       $sqlju="SELECT a.Price,a.Count,b.ProLogoImg,b.ProName,b.ProTitle
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



}?>
