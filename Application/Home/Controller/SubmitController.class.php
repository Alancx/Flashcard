<?php 
/**
* 
*/

namespace Home\Controller;
class SubmitController extends BaseController
{
	public $stoken;
	public function _initialize()
	{
	    parent::_initialize();
		$this->stoken='nPyEo49507333966';
	}

	/**
	 * 首页
	 */
	public function index()	{
		$filename = 'message.json';
		$json = file_get_contents('public/json/'.$filename);
		$data = json_decode($json,true);
		$total = 0;
		foreach ($data as $k => $v) {
			$eachPrice = $v['Num']*$v['Price'];
			$total += $eachPrice;
		}
		$price = json_decode($_POST['discounts']);
		$ProId = $_GET['pid'];
		$length = sizeof($data);//获取数组的长度
		// var_dump($length);
        $this->assign('length',$length);
		$this->assign('total',$total);//给模板分配值
        $this->assign('Cart',$data);
		$this->display('submit/SubmitOrder');
	}










}











 ?>