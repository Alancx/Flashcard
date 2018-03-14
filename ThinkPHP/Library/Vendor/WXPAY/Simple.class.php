<?php 

class MicroPay
{
	public $appid;
	public $appsecret;
	public $mchid;
	public $paykey;
	public $payParms=array();
	public $queryParms=array();
	public $jsapi=array();
	public function __construct($params){
		$this->appid=$params['appid'];
		$this->appsecret=$params['appsecret'];
		$this->mchid=$params['mchid'];
		$this->paykey=$params['apikey'];
	}
	/**
	 * 
	 * 提交刷卡支付，并且确认结果，接口比较慢
	 * @param WxPayMicroPay $microPayInput
	 * @throws WxpayException
	 * @return 返回查询接口的结果
	 */
	public function pay($microPayInput)
	{
		//①、提交被扫支付
		$result = $this->micropay($microPayInput, 5);
		if ($result) {
			if ($result->return_code=='SUCCESS') {
				if ($result->result_code=='SUCCESS') {
					$rinfo['status']=true;
				}else{
					if ($result->err_code=='SYSTEMERROR'  || $result->err_code=='BANKERROR' || $result->err_code=='USERPAYING') {
						$this->LOGS($result->err_code);
						$queryTimes = 10;
						for ($i=0; $i < 10; $i++) { 
							$succResult = 0;
							$queryResult = $this->query($microPayInput->out_trade_no, $succResult);
							//如果需要等待1s后继续
							if($succResult == 2){
								$this->LOGS('查询次数---'.$queryTimes.'---'.date('H:i:s',time()));
								sleep(2);
								continue;
							} else if($succResult == 1){//查询成功
								$this->LOGS("$queryResult");
								$rinfo['status']=true;
								return $rinfo;
								// return true;
							} else {//订单交易失败
								// return false;
								$rinfo['status']=false;
								$rinfo['info']='查询失败';
								return $rinfo;
							}
						}
						// while($queryTimes > 0)
						// {
						// 	$succResult = 0;
						// 	$queryResult = $this->query($microPayInput->OrderId, $succResult);
						// 	//如果需要等待1s后继续
						// 	if($succResult == 2){
						// 		$this->LOGS('查询次数---'.$queryTimes.'---'.date('H:i:s',time()));
						// 		sleep(2);
						// 		continue;
						// 	} else if($succResult == 1){//查询成功
						// 		$this->LOGS("$queryResult");
						// 		$rinfo['status']=true;
						// 		return $rinfo;
						// 		// return true;
						// 	} else {//订单交易失败
						// 		// return false;
						// 		$rinfo['status']=false;
						// 		$rinfo['info']='查询失败';
						// 		return $rinfo;
						// 	}
						// 	$queryTimes--;
						// }
					}else{
						$this->LOGS('刷卡支付失败、err_code:'.$result->err_code.'---err_code_des:'.$result->err_code_des);
						$rinfo['status']=false;
						$rinfo['info']='err_code:'.$result->err_code.'---err_code_des:'.$result->err_code_des;
						// return false;
					}
				}
			}else{
				$this->LOGS('通信失败');
				$rinfo['status']=false;
				$rinfo['info']='通信失败';
				// return false;
			}
		}else{
			$this->LOGS('curl出错');
			$rinfo['status']=false;
			$rinfo['info']='通信出错';
			// return false;
		}
		return $rinfo;
	}
	
	/**
	 * 
	 * 查询订单情况
	 * @param string $out_trade_no  商户订单号
	 * @param int $succCode         查询订单结果
	 * @return 0 订单不成功，1表示订单成功，2表示继续等待
	 */
	public function query($out_trade_no, &$succCode)
	{
		// var_dump($out_trade_no);exit();
		$result = $this->orderQuery($out_trade_no);
		if($result->return_code == "SUCCESS" 
			&& $result->result_code == "SUCCESS")
		{
			//支付成功
			if($result->trade_state == "SUCCESS"){
				$succCode = 1;
			   	return $result;
			}
			//用户支付中
			else if($result->trade_state == "USERPAYING"){
				$succCode = 2;
				return false;
			}
		}
		
		//如果返回错误码为“此交易订单号不存在”则直接认定失败
		if($result->err_code == "ORDERNOTEXIST")
		{
			$succCode = 0;
		} else{
			//如果是系统错误，则后续继续
			$succCode = 2;
		}
		return false;
	}
	
	/**
	 * 
	 * 撤销订单，如果失败会重复调用10次
	 * @param string $out_trade_no
	 * @param 调用深度 $depth
	 */
	public function cancel($out_trade_no, $depth = 0)
	{
		if($depth > 10){
			return false;
		}
		
		$clostOrder = new WxPayReverse();
		$clostOrder->SetOut_trade_no($out_trade_no);
		$result = WxPayApi::reverse($clostOrder);
		
		//接口调用失败
		if($result["return_code"] != "SUCCESS"){
			return false;
		}
		
		//如果结果为success且不需要重新调用撤销，则表示撤销成功
		if($result["result_code"] != "SUCCESS" 
			&& $result["recall"] == "N"){
			return true;
		} else if($result["recall"] == "Y") {
			return $this->cancel($out_trade_no, ++$depth);
		}
		return false;
	}


	public function micropay($inputObj, $timeOut = 10)
	{
		$url = "https://api.mch.weixin.qq.com/pay/micropay";
		$this->payParms=array();
		//检测必填参数
		// if(!$inputObj->IsBodySet()) {
		// 	$result['errinfo']="提交被扫支付API接口中，缺少必填参数body！";
		// } else if(!$inputObj->IsOut_trade_noSet()) {
		// 	$result['errinfo']="提交被扫支付API接口中，缺少必填参数out_trade_no！";
		// } else if(!$inputObj->IsTotal_feeSet()) {
		// 	$result['errinfo']="提交被扫支付API接口中，缺少必填参数total_fee！";
		// } else if(!$inputObj->IsAuth_codeSet()) {
		// 	$result['errinfo']="提交被扫支付API接口中，缺少必填参数auth_code！";
		// }
		// var_dump($inputObj);exit();
		$this->payParms['appid']=$this->appid;
		$this->payParms['mch_id']=$this->mchid;
		$this->payParms['nonce_str']=$this->getNonceStr();
		$this->payParms['body']=$inputObj->body;
		$this->payParms['out_trade_no']=$inputObj->out_trade_no;
		$this->payParms['total_fee']=$inputObj->total_fee;
		$this->payParms['spbill_create_ip']=$_SERVER['REMOTE_ADDR'];
		$this->payParms['auth_code']=$inputObj->auth_code;
		$this->payParms['sign']=$this->MakeSign($this->paykey);
		$xml = $this->ToXml();
		
		$startTimeStamp = $this->getMillisecond();//请求开始时间
		$response = $this->postXmlCurl($xml, $url, false);
		if ($response['status']==false) {
			return false;
		}else{
			$result=simplexml_load_string($response);
			return $result;
		}
	}






	/**
	 * 
	 * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 * @param WxPayOrderQuery $inputObj
	 * @param int $timeOut
	 * @throws WxPayException
	 * @return 成功时返回，其他抛异常
	 */
	public function orderQuery($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/pay/orderquery";
		$this->payParms=array();
		//检测必填参数
		$this->payParms['appid']=$this->appid;
		$this->payParms['mch_id']=$this->mchid;
		$this->payParms['out_trade_no']=$inputObj;
		$this->payParms['nonce_str']=$this->getNonceStr();
		$this->payParms['sign']=$this->MakeSign($this->paykey);
		
		$xml = $this->ToXml();
		
		$startTimeStamp = $this->getMillisecond();//请求开始时间
		$response = $this->postXmlCurl($xml, $url, false, $timeOut);
		$result=simplexml_load_string($response);
		
		return $result;
	}

	public function refund($inputObj,$timeOut=10){
		$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
		$this->payParms=array();
		//检测必填参数
		$this->payParms['appid']=$this->appid;
		$this->payParms['mch_id']=$this->mchid;
		$this->payParms['out_trade_no']=$inputObj['Orderid'];
		$this->payParms['out_refund_no']=$inputObj['Refundid'];
		$this->payParms['total_fee']=$inputObj['total_fee'];
		$this->payParms['refund_fee']=$inputObj['refund_fee'];
		$this->payParms['op_user_id']=$inputObj['op_user_id'];
		$this->payParms['nonce_str']=$this->getNonceStr();
		$this->payParms['sign']=$this->MakeSign($this->paykey);
		
		$xml = $this->ToXml();
		
		$startTimeStamp = $this->getMillisecond();//请求开始时间
		$response = $this->postXmlCurl($xml, $url, true, $timeOut);
		$result=$this->xmlToArray($response);
		
		return $result;
	}	




	/**
	 * 
	 * 获取jsapi支付的参数
	 * @param array $UnifiedOrderResult 统一支付接口返回的数据
	 * @throws WxPayException
	 * 
	 * @return json数据，可直接填入js函数作为参数
	 */
	public function GetJsApiParameters($UnifiedOrderResult)
	{
		// var_dump($UnifiedOrderResult);exit();
		if(!array_key_exists("appid", $UnifiedOrderResult)
		|| !array_key_exists("prepay_id", $UnifiedOrderResult)
		|| $UnifiedOrderResult['prepay_id'] == "")
		{
			exit('js支付参数错误');
		}
		$this->jsapi = array();
		$this->jsapi['appId']=$UnifiedOrderResult['appid'];
		$this->jsapi['timeStamp']=time();
		$this->jsapi['nonceStr']=$this->getNonceStr();
		$this->jsapi['package']='prepay_id='.$UnifiedOrderResult['prepay_id'];
		$this->jsapi['signType']='MD5';
		$this->jsapi['paySign']=$this->MakeSign($this->paykey,'jsapi');
		$parameters = json_encode($this->jsapi);
		return $parameters;
	}

	/**
	 * 生成签名
	 */
	public function MakeSign($key,$type='payParms')
	{
		//签名步骤一：按字典序排序参数
		if ($type=='payParms') {
			$Parms=$this->payParms;
		}elseif ($type=='jsapi') {
			$Parms=$this->jsapi;
		}
		ksort($Parms);
		$string = $this->ToUrlParams($Parms);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".$key;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}


	/**
	 * 
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return 产生的随机字符串
	 */
	public function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

	/**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams($Parms)
	{
		$buff = "";
		foreach ($Parms as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}


	public function ToXml()
	{
		if(!is_array($this->payParms) 
			|| count($this->payParms) <= 0)
		{
    		return false;
    	}
    	
    	$xml = "<xml>";
    	foreach ($this->payParms as $key=>$val)
    	{
    		if (is_numeric($val)){
    			$xml.="<".$key.">".$val."</".$key.">";
    		}else{
    			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    		}
        }
        $xml.="</xml>";
        return $xml; 
	}

	/**
	 * 获取毫秒级别的时间戳
	 */
	private static function getMillisecond()
	{
		//获取毫秒的时间戳
		$time = explode ( " ", microtime () );
		$time = $time[1] . ($time[0] * 1000);
		$time2 = explode( ".", $time );
		$time = $time2[0];
		return $time;
	}
    // file_get_contents('http://')


	/**
	 * 以post方式提交xml到对应的接口url
	 * 
	 * @param string $xml  需要post的xml数据
	 * @param string $url  url
	 * @param bool $useCert 是否需要证书，默认不需要
	 * @param int $second   url执行超时时间，默认30s
	 * @throws WxPayException
	 */
	public function postXmlCurl($xml, $url, $useCert = false, $second = 30)
	{		
		// var_dump($xml);exit();
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		//如果有配置代理这里就设置代理
		// if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
		// 	&& WxPayConfig::CURL_PROXY_PORT != 0){
		// 	curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
		// 	curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
		// }
		curl_setopt($ch,CURLOPT_URL, $url);
		// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
		// curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
		if($useCert == true){
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			$path=dirname(__FILE__);
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, $path.'\apiclient_cert.pem');
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, $path.'\apiclient_key.pem');
		}
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		// var_dump($data);exit();
		if($data){
			curl_close($ch);
			$this->LOGS($data);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			$res['status']=false;
			$res['info']="curl出错，错误码:$error";
			$this->LOGS(json_encode($res));
			return $res;
		}
	}

	/**
	 * 用户操作记录log
	 */
	public function LOGS($desc='',$controller=CONTROLLER_NAME,$function=ACTION_NAME){
		$filename=date('Y-m-d',time());
		$logfile=str_replace('\\','/',strrev(substr(strrev(dirname(__FILE__)),5))).'logs/';
		if (!is_dir($logfile)) {
			mkdir($logfile,777);
		}
		$logfile=$logfile.$filename.'.txt';
		// var_dump($logfile);exit;
		$content='操作定位:'.$controller.'/'.$function.'::::'.$desc.'::::::操作日期:::::::'.date('Y-m-d H:i:s',time()).PHP_EOL;
		file_put_contents($logfile,$content,FILE_APPEND);
	}



	/**
	 * 
	 * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 * @param WxPayUnifiedOrder $inputObj
	 * @param int $timeOut
	 * @throws WxPayException
	 * @return 成功时返回，其他抛异常
	 */
	public function unifiedOrder($inputObj, $timeOut = 6)
	{
		// $this->LOGS(json_encode($inputObj));exit();
		$this->payParms=array();
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		// var_dump($inputObj);exit();
		//异步通知url未设置，则使用配置文件中的url
		$this->payParms['notify_url']=$inputObj->notify_url;//异步通知url
		$this->payParms['appid']=$this->appid;
		$this->payParms['mch_id']=$this->mchid;
		$this->payParms['nonce_str']=$this->getNonceStr();
		$this->payParms['body']=$inputObj->body;
		$this->payParms['out_trade_no']=$inputObj->out_trade_no;
		$this->payParms['total_fee']=$inputObj->total_fee;
		$this->payParms['spbill_create_ip']=$_SERVER['REMOTE_ADDR'];
		// $this->payParms['attach']=$this->attach;
		$this->payParms['trade_type']='JSAPI';
		$this->payParms['openid']=$inputObj->openid;
		$this->payParms['sign']=$this->MakeSign($this->paykey);
		$xml = $this->ToXml();
		// var_dump($xml);
		$this->LOGS(json_encode($xml));
		// file_put_contents(json_encode($xml), '1111');
		
		$startTimeStamp = $this->getMillisecond();//请求开始时间
		$response = $this->postXmlCurl($xml, $url, false);
		return $response;
	}

	/**
	 * 
	 * 获取地址js参数
	 * 
	 * @return 获取共享收货地址js函数需要的参数，json格式可以直接做参数使用
	 */
	public function GetEditAddressParameters($access_token)
	{	
		$data["appid"] = $this->appid;
		$data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$time = time();
		$data["timestamp"] = "$time";
		$data["noncestr"] = "1234568";
		$data["accesstoken"] = $access_token;
		ksort($data);
		$params = $this->ToUrlParams($data);
		$addrSign = sha1($params);
		
		$afterData = array(
			"addrSign" => $addrSign,
			"signType" => "sha1",
			"scope" => "jsapi_address",
			"appId" => $this->appid,
			"timeStamp" => $data["timestamp"],
			"nonceStr" => $data["noncestr"]
		);
		$parameters = json_encode($afterData);
		return $parameters;
	}

	public function xmlToArray($xml)
	{		
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}




}









 ?>