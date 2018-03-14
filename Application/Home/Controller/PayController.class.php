<?php 
/**
* 
*/

namespace Home\Controller;
use Think\Controller;
class PayController extends BaseController
{
	public $stoken;
	public function _initialize()
	{
		$this->stoken='nPyEo49507333966';
	}
    //商品支付
	public function pay(){
        //获取用于支付的所有订单的总价钱
        $filename = 'message.json';
        $json = file_get_contents('public/json/'.$filename);//获取文件
        $data = json_decode($json,true);//将json格式的数据转换成数组
        $total = 0;
        //循环遍历出所有的商品及价格总数
        foreach ($data as $k => $v) {
            $eachPrice = $v['Num']*$v['Price'];
            $total += $eachPrice;
        }
        $total = $total - 1;
        //将总价钱传递到模板上
	    $this->assign('total',$total);
		$this->display();
	}









}











 ?>