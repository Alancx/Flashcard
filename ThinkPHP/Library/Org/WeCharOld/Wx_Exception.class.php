<?php
/**
 * 
 * 微信支付API异常类
 * @author widyhu
 *
 */
namespace Org\WeChar;
class Wx_Exception extends \Exception {

	public $eData=array();

	public function __construct ($res,$no)
	{
		$eData['no']=$no;
		$eData['res']=$res;
	}
}
