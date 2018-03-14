<?php
namespace Seller\Controller;
use Think\Controller;
/**
* 微信支付相关
*/
header('content-type:text/html;charset=utf-8');
class WxpayController extends CommonController
{
	public function _intialize(){
		parent::_initialize();
	}
	/**
		 * 微信退款处理
		 */
	public function refund(){

		$temp_out_trade_no=$_GET['out_trade_no'];
		$pid=M()->table('RS_Order')->where("OrderId='%s'",$_GET['out_trade_no'])->find();
		//$out_trade_no=$_GET['out_trade_no'].$pid['extStr'];
		$out_trade_no=$_GET['out_trade_no'];
		$price=$_GET['price'];
		if (IS_POST) {
			if (!isset($_POST["out_trade_no"]) || !isset($_POST["refund_fee"]))
			{
				$out_trade_no = " ";
				$refund_fee = "1";
			}
			else
			{
				$payData=$this->MSL('wxpayset')->where(array('token'=>$this->token))->find();

				$wxData=new \Org\WeChar\Wx_Data();
				$wxData->token=$this->token;
				$wxData->values=array(
					'appid'=>$payData['appid'],
					'mch_id'=>$payData['mchid'],
					'nonce_str'=>$wxData->createNonceStr('16'),
					'transaction_id'=>$pid['TransactionId'],
					'out_refund_no'=>"TK".date("YmdHis",time()).rand(1000,9999),
					'total_fee'=>$pid['Price']*100,
					'refund_fee'=>$pid['Price']*100,
					'op_user_id'=>$payData['mchid'],
				);

				$wxData->values['sign']=$wxData->SetSign($payData['apikey']);
				$wxPayAPI= new \Org\WeChar\WxPay\WxPay_Api();

 				$wxData->FromXml($wxPayAPI->refund($wxData));

// <xml>
// <return_code><!--[CDATA[SUCCESS]]--></return_code>
// <return_msg><!--[CDATA[OK]]--></return_msg>
// <appid><!--[CDATA[wx94e1186193685a40]]--></appid>
// <mch_id><!--[CDATA[1344511601]]--></mch_id>
// <nonce_str><!--[CDATA[fi1xULnzHSaOwB6X]]--></nonce_str>
// <sign><!--[CDATA[30C23EB964D5331222549FB6B2A6C2CA]]--></sign>
// <result_code><!--[CDATA[SUCCESS]]--></result_code>
// <transaction_id><!--[CDATA[4010032001201605206081222140]]--></transaction_id>
// <out_trade_no><!--[CDATA[E201605201846444774]]--></out_trade_no>
// <out_refund_no><!--[CDATA[TK201605211517483606]]--></out_refund_no>
// <refund_id><!--[CDATA[2010032001201605210248089223]]--></refund_id>
// <refund_channel><!--[CDATA[]]--></refund_channel>
// <refund_fee>10</refund_fee>
// <coupon_refund_fee>0</coupon_refund_fee>
// <total_fee>10</total_fee>
// <cash_fee>10</cash_fee>
// <coupon_refund_count>0</coupon_refund_count>
// <cash_refund_fee>10</cash_refund_fee>
// </xml>

 				$coupon=M()->table('RS_Order')->where("OrderId='%s' and token='%s'",array($_POST['out_trade_no'],$this->token))->find();
 				$orderlistcount=M()->table('RS_OrderList')->where("OrderId='%s' and token='%s'",array($_POST['out_trade_no'],$this->token))->field("Count,Price")->select();
		        $tb_name='tb_wh'.substr($this->token, -8,8);
 				if ($wxData->result['return_code']=='SUCCESS') {
 					M()->startTrans();
 					$res=M()->table('RS_Order')->where("OrderId='%s' and token='%s'",array($temp_out_trade_no,$this->token))->setField('Status',8);
 					// if (!) {
 						// var_dump(M()->getlastsql());
						// exit('数据库处理失败');
 					// }
 					// echo M()->getlastsql();exit();
 					$res2=true;
					if (!empty($coupon['getcoupon'])) {
						$usercoupons=M()->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s' and token='%s'",array($coupon['MemberId'],$coupon['getcoupon'],$this->token))->find();
						if ($usercoupons['CouponCount']>0) {
							$res2=M()->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s' and token='%s'",array($coupon['MemberId'],$coupon['getcoupon'],$this->token))->setDec('CouponCount');
						}
					}
					if ($res && $res2) {
						foreach ($orderlistcount as $ol) {
                            $this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount+".$ol['Count'].",VirtualCount=VirtualCount+".$ol['Count'].",SalesCount=SalesCount-".$ol['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$ol['ProIdCard']."'");
						}
						M()->commit();
					}else{
						M()->rollback();
						exit('数据库处理失败');
					}
 				}
 				else
 				{
 					exit('退款失败');
 				}

			    //$out_trade_no = $_POST["out_trade_no"];
			    //$refund_fee = $_POST["refund_fee"]*100;
				//商户退款单号，商户自定义，此处仅作举例
				//$out_refund_no = "$out_trade_no"."$time_stamp";
				//总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
				//$total_fee = $_POST['total_fee']*100;

				//使用退款接口
				//$refund = new \Refund_pub();
				//设置必填参数
				//appid已填,商户无需重复填写
				//mch_id已填,商户无需重复填写
				//noncestr已填,商户无需重复填写
				//sign已填,商户无需重复填写
				// $refund->setParameter("out_trade_no","$out_trade_no");//商户订单号
				// $refund->setParameter("out_refund_no","$out_refund_no");//商户退款单号
				// $refund->setParameter("total_fee","$total_fee");//总金额
				// $refund->setParameter("refund_fee","$refund_fee");//退款金额
				// $refund->setParameter("op_user_id",\WxPayConf_pub::MCHID);//操作员
				//非必填参数，商户可根据实际情况选填
				//$refund->setParameter("sub_mch_id","XXXX");//子商户号
				//$refund->setParameter("device_info","XXXX");//设备号
				//$refund->setParameter("transaction_id","XXXX");//微信订单号

				//调用结果
				//$refundResult = $refund->getResult();
				//var_dump($refundResult);
				// var_dump($refundResult);exit();
				//商户根据实际情况设置相应的处理流程,此处仅作举例
				// file_put_contents('logs.txt', $refundResult);
				// if ($refundResult['result_code']=='SUCCESS') {
				// 	//数据库操作

				// }

				$this->assign('refundResult',$wxData->result);
				$this->display();
			}
		}
		else
		{
			$this->assign(array('out_trade_no'=>$out_trade_no,'refund_fee'=>$price));
			$this->display();
		}

	}



}

 ?>
