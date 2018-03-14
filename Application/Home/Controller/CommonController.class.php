<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
  public function errorpage(){
    $this->assign('info','通过扫码或者分享进入商城');
    $this->display();
  }





}?>
