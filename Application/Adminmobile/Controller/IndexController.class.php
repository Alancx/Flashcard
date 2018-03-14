<?php
namespace Adminmobile\Controller;
use Think\Controller;
class IndexController extends CommonController {
  public function index(){
    $today=date("Y-m-d 23:59:59",strtotime('0 day'));
    $startday=date("Y-m-d 00:00:00",strtotime('-29 day'));
    $sdrevenue=$this->BM('Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".$startday."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
    $this->assign('sdrevenue',$sdrevenue);
    $this->assign('footerSign',1);
    $this->assign('Title','主页');
    $this->display();
  }
}
?>
