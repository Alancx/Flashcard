<?php
namespace Adminmobile\Controller;
use Think\Controller;
class StaffController extends CommonController {

  public function staff(){
    $this->assign('Title','员工列表');
    $this->display();
  }








}?>
