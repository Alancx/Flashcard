<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class IndexController extends BaseController{
  public function Index(){
      $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
      if ($shopinfo) {
        ///////三十天的营业收入/////////
        $today=date("Y-m-d 23:59:59",strtotime('0 day'));
        $startday=date("Y-m-d 00:00:00",strtotime('-29 day'));
        $allprice=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '".$today."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->sum('Price-Freight');
        $pagedata['avgprice']=is_null($allprice)?'0.00':number_format($allprice,2);
        /////////计算每天的营业收入///////
        $dataday=array();
        $daymon=array();
        for ($i=29; $i >=0 ; $i--) {
          $day=date("Y-m-d",strtotime('-'.$i.' day'));
          $evday=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-'.$i.' day'))."' and '".date('Y-m-d 23:59:59',strtotime('-'.$i.' day'))."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->sum('Price-Freight');
          $evday=is_null($evday)?'0.00':number_format($evday,2);
          array_push($dataday,$day);
          array_push($daymon,$evday);
        }
        $pagedata['dataday']=$dataday;
        $pagedata['daymon']=$daymon;
        ////////今日订单和今日收入/////////////////////
        $otoday=date("Y-m-d 23:59:59",strtotime('0 day'));
        $ostartday=date("Y-m-d 00:00:00",strtotime('0 day'));

        $todayprice=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$ostartday."' and '".$otoday."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->sum('Price-Freight');
        $pagedata['todayprice']=is_null($todayprice)?'0.00':number_format($todayprice,2);

        $todayorder=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$ostartday."' and '".$otoday."' and token='".$shopinfo['Token']."' and stoken='".$shopinfo['Stoken']."'")->count();
        $pagedata['todayorder']=is_null($todayorder)?'0':$todayorder;
        //////////营业收入现金和微信///////////
        $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName IN ('XJ','POSXJ') AND Status in ('4','10') AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' ";
        $cashmoney=$this->BM()->query($sqlStr);
        $pagedata['cashmoney']=is_null($cashmoney[0]['money'])?'0.00':number_format($cashmoney[0]['money'],2);

        $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('2','3','4','5','10') AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' ";
        $wxmoney=$this->BM()->query($sqlStr);
        $pagedata['wxmoney']=is_null($wxmoney[0]['money'])?'0.00':number_format($wxmoney[0]['money'],2);

        $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('2','3','5') AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' ";
        $wxhasnomoney=$this->BM()->query($sqlStr);
        $pagedata['wxhasnomoney']=is_null($wxhasnomoney[0]['money'])?'0.00':number_format($wxhasnomoney[0]['money'],2);

        $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('4','10') AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' ";
        $wxhasmoney=$this->BM()->query($sqlStr);
        $pagedata['wxhasmoney']=is_null($wxhasmoney[0]['money'])?'0.00':number_format($wxhasmoney[0]['money'],2);

        $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('4','10') AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND Pstoken='1' ";
        $wxnojsmoney=$this->BM()->query($sqlStr);
        $pagedata['wxnojsmoney']=is_null($wxnojsmoney[0]['money'])?'0.00':number_format($wxnojsmoney[0]['money'],2);

        $sqlStr="SELECT SUM(Price-Freight) AS money FROM RS_Order where PayName ='T' AND Status in ('4','10') AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND Pstoken='2' ";
        $wxhasjsmoney=$this->BM()->query($sqlStr);
        $pagedata['wxhasjsmoney']=is_null($wxhasjsmoney[0]['money'])?'0.00':number_format($wxhasjsmoney[0]['money'],2);
        $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
      }
  }
}
?>
