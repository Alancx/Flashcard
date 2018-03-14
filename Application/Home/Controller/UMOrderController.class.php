<?php
namespace Home\Controller;
use Think\Controller;
class UMOrderController extends BaseController 
{
    public function _initialize()
    {
      parent::_initialize();
    }

    public function Index()
    {
    	

    	$this->display();
    }
   
}