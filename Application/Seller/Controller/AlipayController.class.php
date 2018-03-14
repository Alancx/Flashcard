<?php
namespace Seller\Controller;
use Think\Controller;
/**
*
*/
class AlipayController extends Controller
{

	public function _initialize(){
		vendor('Alipay.Md5function');
		vendor('Alipay.Corefunction');
		vendor('Alipay.Notify');
		vendor('Alipay.Submit');
	}


	public function refund(){
		/* *
		 * 功能：即时到账批量退款有密接口接入页
		 * 版本：3.3
		 * 修改日期：2012-07-23
		 */

		$alipay_config=C('alipay_config');

		/**************************请求参数**************************/

		        //服务器异步通知页面路径
		        $notify_url = C('alipay')['notify_url'];
		        //需http://格式的完整路径，不允许加?id=123这类自定义参数

		        //卖家支付宝帐户
		        $seller_email = $alipay_config['seller_email'];
		        //必填

		        //退款当天日期
		        $refund_date = date('Y-m-d H:i:s',time());
		        //必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13

		        //批次号
		        $batch_no = date('YmdHis',time()).str_shuffle(substr(time(), 3));
		        //必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001

		        //退款笔数
		        $batch_num = 1;
		        //必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）

		        //退款详细数据
		        $trade_no=$_GET['trade_no'];
		        $price=intval($_GET['price']);
		        $detail_data = $trade_no."^".$price."^协商退款";
		        //必填，具体格式请参见接口技术文档


		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "refund_fastpay_by_platform_pwd",
				"partner" => trim($alipay_config['partner']),
				"notify_url"	=> $notify_url,
				"seller_email"	=> $alipay_config['seller_email'],
				"refund_date"	=> $refund_date,
				"batch_no"	=> $batch_no,
				"batch_num"	=> $batch_num,
				"detail_data"	=> $detail_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		// var_dump($parameter);exit();
		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
	}


	//异步处理
	public function notify_url(){
		$alipay_config=C('alipay_config');
		$alipayNotify = new \AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

			//批次号

			$batch_no = $_POST['batch_no'];

			//批量退款数据中转账成功的笔数

			$success_num = $_POST['success_num'];

			//批量退款数据中的详细信息
			$result_details = $_POST['result_details'];
			$result=explode('^', $result_details);
			if (M()->table('RS_Order')->where('TransactionId="%s"',$result[0])->setField('status',8)) {
				echo "success";
			}else{
				echo "fail";
			}
			//判断是否在商户网站中已经做过了这次通知返回的处理
				//如果没有做过处理，那么执行商户的业务程序
				//如果有做过处理，那么不执行商户的业务程序
			file_put_contents('alirefund.txt', json_encode($_POST),FILE_APPEND);

			// echo "success";		//请不要修改或删除

			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
		    //验证失败
		    echo "fail";

		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}


}

 ?>
