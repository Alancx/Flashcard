<?php
namespace Sellermobile\Controller;
use Think\Controller;
class IndexController extends CommonController {
  public function Index(){
    ////////三十天的营业收入////////////////////

    $today=date("Y-m-d 23:59:59",strtotime('0 day'));
    $startday=date("Y-m-d 00:00:00",strtotime('-29 day'));
    $allprice=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $this->assign('avgprice',$allprice);
    $dataday="[";
    $daymon="[";
    for ($i=29; $i >=0 ; $i--) {
      $day=date("Y-m-d",strtotime('-'.$i.' day'));
      $evday=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-'.$i.' day'))."' and '".date('Y-m-d 23:59:59',strtotime('-'.$i.' day'))."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
      $evday=is_null($evday)?0:$evday;
      if ($i==29){
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
    /////////////////////今日营业收入////////////////////
    $otoday=date("Y-m-d 23:59:59",strtotime('0 day'));
    $ostartday=date("Y-m-d 00:00:00",strtotime('0 day'));
    $todayprice=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$ostartday."' and '".$otoday."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $this->assign('todayprice',$todayprice);
    $todayorder=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$ostartday."' and '".$otoday."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
    $this->assign('todayorder',$todayorder);
    $odataday="[";
    $odaymon="[";
    $day=date("Y-m-d 00:00:00",strtotime('0 day'));
    for ($i=0; $i <=24 ; $i++) {
      $evday=$this->BM('Order')->where("CreateDate BETWEEN '".date('Y-m-d H:00:00',strtotime("$day"))."' and '".date('Y-m-d H:59:59',strtotime("$day"))."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
      $evday=is_null($evday)?0:$evday;
      if ($i==0){
        $odataday=$odataday."'$day'";
        $odaymon=$odaymon.$evday;
      } else{
        $odataday=$odataday.","."'$day'";
        $odaymon=$odaymon.",".$evday;
      }
      $day=date("Y-m-d H:i:s",strtotime("$day +1 hours"));
    }
    $odataday=$odataday."]";
    $odaymon=$odaymon."]";
    $this->assign('odataday',$odataday);
    $this->assign('odaymon',$odaymon);

    ////////////////营业收入现金和微信///////////////////////////
    $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName IN ('XJ','POSXJ') AND Status in ('4','10') AND token='".$this->token."' AND stoken='".$this->stoken."' ";
    $cashmoney=$this->BM()->query($sqlStr);
    $this->assign('cashmoney',$cashmoney[0]['money']);
    $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('2','3','4','5','10') AND token='".$this->token."' AND stoken='".$this->stoken."' ";
    // var_dump($sqlStr);exit();
    $wxmoney=$this->BM()->query($sqlStr);
    $this->assign('wxmoney',$wxmoney[0]['money']);

    $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('2','3','5') AND token='".$this->token."' AND stoken='".$this->stoken."' ";
    $wxhasnomoney=$this->BM()->query($sqlStr);
    $this->assign('wxhasnomoney',$wxhasnomoney[0]['money']);

    $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('4','10') AND token='".$this->token."' AND stoken='".$this->stoken."' ";
    $wxhasmoney=$this->BM()->query($sqlStr);
    $this->assign('wxhasmoney',$wxhasmoney[0]['money']);

    $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('4','10') AND token='".$this->token."' AND stoken='".$this->stoken."' AND Pstoken='1' ";
    $wxnojsmoney=$this->BM()->query($sqlStr);
    $this->assign('wxnojsmoney',$wxnojsmoney[0]['money']);

    $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('4','10') AND token='".$this->token."' AND stoken='".$this->stoken."' AND Pstoken='2' ";
    $wxhasjsmoney=$this->BM()->query($sqlStr);
    $this->assign('wxhasjsmoney',$wxhasjsmoney[0]['money']);

    //////////////////////////////////////////////

    $this->assign('footerSign',1);
    $this->assign('Title','主页');
    $this->display();
  }
}
?>
