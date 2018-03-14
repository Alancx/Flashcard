<?php
namespace Home\Controller;
use Think\Controller;
class LBSController extends BaseController {
    // public function _initialize(){
    //   parent::_initialize();
    // }

    public function myaddr(){
        $this->assign('Title','附近门店');
        $this->assign('searchSign', 0);
        $this->assign('footerSign', 1);
        $this->display();
    }


    public function getRange()
    {
      echo "false";
    }


    //经纬度范围计算
 	 /**
 	  * $lat //经度
 		* $lot //纬度
 		* $long //范围半径 长度m
 		* $
 	  */
    public function getRanges($lat='',$lot='',$long='')
   	 {
       $lat=$_POST['slng'];
       $lot=$_POST['slat'];
       $long=$_POST['slong'];
       $city=$_POST['city'];
      //  echo "<pre>";
      //  var_dump($_POST);
   		 $pi=M_PI;
   		 $radius=63710040;//地球半径
   		 $pov=asin($long/$radius)*180/$pi; //计算纬度增加角度
   		 $maxlot=$lot+(2*$pov);
   		 $views['maxlot']=$maxlot>0?$maxlot:360+$minlot;
   		 $minlot=$lot-(2*$pov);
   		 $views['minlot']=$minlot>0?$minlot:360+$minlot; //纬度查询范围
   		 $views['maxlat']=$lat+(2*$pov);
   		 $views['minlat']=$lat-(2*$pov);
       ////  $sql="SELECT * FROM RS_Store WHERE (lat BETWEEN ".$views['minlot']." AND ".$views['maxlot'].") AND (slang BETWEEN ".$views['minlat']." AND ".$views['maxlat'].") AND token= ". $this->webParam['token'] ."";

      //  var_dump($views);exit;
      //  $sql="SELECT * FROM RS_Store WHERE (lat BETWEEN ".$views['minlot']." AND ".$views['maxlot'].") AND (slang BETWEEN ".$views['minlat']." AND ".$views['maxlat'].")";
        // $sql="SELECT * FROM RS_Store";
        $sql= "SELECT * FROM RS_Store WHERE city='".$city."' AND token= '". $this->webParam['token'] ."' AND IsCheck='1'";

       $merchants=$this->BM('Store')->query($sql);
       echo json_encode($merchants);
   	 }

 	 public function getRangeSql($views){
 		 $sql="SELECT * FROM RS_Store WHERE (slang BETWEEN ".$views['maxlot']." AND ".$views['minlot'].") AND (lat BETWEEN ".$views['minlat']." AND ".$views['maxlat'].") AND IsCheck='1'";
 		 return $sql;
 	 }


   ///////////获取当前位置最近门店///////////////
   public function getchildshop()
   {
     $slat=$_POST['slat'];
     $slng=$_POST['slng'];
     $scity=$_POST['scity'];

     $sqlStr= "SELECT * FROM RS_Store WHERE city='".$scity."' AND token= '". $this->webParam['token'] ."' AND IsCheck='1'";

     $shoplist=$this->BM()->query($sqlStr);

     $arraylong=array();

     if ($shoplist)
     {

       foreach ($shoplist as $key => $value)
       {
          $arraylong[$key]=$this->getpointline($slat,$slng,$value['lat'],$value['lang']);
       }

       $long=min($arraylong);

       if ($long<=5000)
       {
        if ($this->webParam['stoken']!=($shoplist[array_search($long,$arraylong)]['stoken']))
        {

          $shopdata=array(
            'stoken'=>$shoplist[array_search($long,$arraylong)]['stoken'],
            'suid'=>$shoplist[array_search($long,$arraylong)]['MemberId'],
            'wid'=>$shoplist[array_search($long,$arraylong)]['id'],
            );

          $this->changeShop($shopdata);

          $this->ajaxReturn(array('status'=>'true','info'=>'success'));
        }
        else
        {
          $this->ajaxReturn(array('status'=>'false','info'=>'sameshop'));
        }
       }
       else
       {

          $shopdata=array(
            'stoken'=>'0',
            'suid'=>'0',
            'wid'=>'-1',
            );

          $this->changeShop($shopdata);

          $this->ajaxReturn(array('status'=>'false','info'=>'noShop'));

       }
     }
     else
     {

        $shopdata=array(
          'stoken'=>'0',
          'suid'=>'0',
          'wid'=>'-1',
          );

        $this->changeShop($shopdata);

        $this->ajaxReturn(array('status'=>'false','info'=>'noShops'));

     }
   }

   //////////判断地图两点之间的距离////////////
   public function getpointline($slat,$slng,$elat,$elng){
     $pi=M_PI;
     $radius=63710040;//地球半径
     $radLat1 = $slat * ($pi / 180);
     $radLat2 = $elat * ($pi / 180);
     $a = $radLat1 - $radLat2;
     $b = ($slng * ($pi / 180)) - ($elng * ($pi / 180));
     $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
     $s = $s * $radius;
     $s = round($s);
     return $s;
   }
   ///////////门店搜索/////////
   public function seachshop(){
     if (IS_GET) {
       $shopname=$_GET['shopname'];
       $sqlStr="SELECT * FROM RS_Store WHERE token= '". $this->webParam['token'] ."' AND storename LIKE '%".$shopname."%' AND IsCheck='1'";
       $shoplist=$this->BM()->query($sqlStr);
       if ($shoplist) {
         $this->assign('watermark','false');
       } else {
         $this->assign('watermark','true');
       }
       $this->assign('shoplist',$shoplist);
       $this->assign('sname',$shopname);
       $this->assign('Title','门店搜索');
       $this->display();
     } else {
       $shopname=$_POST['shopname'];
       $sqlStr="SELECT * FROM RS_Store WHERE token= '". $this->webParam['token'] ."' AND storename LIKE '%".$shopname."%'";
       $shoplist=$this->BM()->query($sqlStr);
       if ($shoplist) {
         $this->ajaxReturn(array('status'=>'true','info'=>$shoplist));
       } else {
         $this->ajaxReturn(array('status'=>'false','info'=>'noShops'));
       }
     }
   }

}
