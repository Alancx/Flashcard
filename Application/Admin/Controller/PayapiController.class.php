<?php 
namespace Admin\Controller;
use Think\Controller;
class PayapiController extends BaseController
{
	public $model;
	public $token;
	public $stoken;
	public $apiurl;
	public $merchantNo;
	public $key;
	public $Method;
	public $bgUrl;
	function _initialize()
	{
		$this->model=M();
		$this->apiurl="http://api.tongxingpay.com/txpayApi/offLine";
		$this->merchantNo=C('merchantNo');
		$this->key=C('TXkey');
		$this->bgUrl="https://www.huijistore.com/Admin/Payapi/refunNotify";
	}


	/**
	 * 退款接口
	 */
	public function txrefund($oid){
		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$tmpoid=$oid;
		if (substr($oid, 0,1)=='E') {
			$oid=$oid.$oinfo['extStr'];
		}else{
			$oid=$oid;
		}
		$refundNo='OrderTk'.substr($oid, 1).mt_rand(100,999);
		$this->model->table('RS_Order')->where("OrderId='%s'",$tmpoid)->setField('BackNo',$refundNo);
		$param=array();
		$param['service']='offRefundOrder';
		$param['merchantNo']=$this->merchantNo;
		$param['refundNo']=$refundNo;
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$param['curCode']='CNY';
		$param['refundAmount']=floatval($oinfo['Price'])*100;
		$param['bgUrl']=$this->bgUrl;
		$param['refundTime']=date('YmdHis');
		$param['refundDesc']='justrefund';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$this->LOGS('退款信息--->>>'.json_encode($param));
		$res=$this->postXmlCurl($param,$this->apiurl);
		$this->LOGS('退款反馈信息--->>>'.$res);
		$this->LOGS('走过请求了');
		$result=json_decode($res,true);
		if ($result['dealCode']=='10000') {
			return true;
		}elseif ($result['dealCode']=='10001') {
			return 'loading';
			// $this->queryrefund($oid,$refundNo);
		}else{
			$this->LOGS('同兴支付退款失败--->>>'.$res);
			return $result['dealMsg'];
		}

	}

	/**
	 * 未成团退款处理
	 */
	public function grouprefund($data){
		$refundNo='GroupTk'.substr($data['OrderNo'], 4);
		$this->model->table('RS_GroupBuyerList')->where("ID=%d",$data['ID'])->setField('BackNo',$refundNo);
		$param=array();
		$param['service']='offRefundOrder';
		$param['merchantNo']=$this->merchantNo;
		$param['refundNo']=$refundNo;
		$param['orderNo']=$data['OrderNo'];
		$param['version']='V1.0';
		$param['curCode']='CNY';
		$param['refundAmount']=floatval($data['Money'])*100;
		$param['bgUrl']=$this->bgUrl;
		$param['refundTime']=date('YmdHis');
		$param['refundDesc']='GroupRefund';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$this->LOGS('退款信息--->>>'.json_encode($param));
		$res=$this->postXmlCurl($param,$this->apiurl);
		$result=json_decode($res,true);
		if ($result['dealCode']=='10000') {
			return true;
		}elseif ($result['dealCode']=='10001') {
			return 'loading';
			// $this->queryrefund($data['OrderNo'],$refundNo);
		}else{
			$this->LOGS('同兴支付退款失败--->>>'.$res);
			return $result['dealMsg'];
		}

	}

	/**
	 * 入庫單退款处理
	 */
	public function wkrefund($data){
		$refundNo='RkTk'.substr($data['OrderId'], 7);
		$this->model->table('RS_ProductInWarehouse')->where("OrderId='%s'",$data['OrderId'])->setField('BackNo',$refundNo);
		$param=array();
		$param['service']='offRefundOrder';
		$param['merchantNo']=$this->merchantNo;
		$param['refundNo']=$refundNo;
		$param['orderNo']=$data['OrderId'];
		$param['version']='V1.0';
		$param['curCode']='CNY';
		$param['refundAmount']=floatval($data['Money'])*100;
		$param['bgUrl']=$this->bgUrl;
		$param['refundTime']=date('YmdHis');
		$param['refundDesc']='wkrefund';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$this->LOGS('退款信息--->>>'.json_encode($param));
		$res=$this->postXmlCurl($param,$this->apiurl);
		$result=json_decode($res,true);
		if ($result['dealCode']=='10000') {
			return true;
		}elseif ($result['dealCode']=='10001') {
			return 'loading';
			// $this->queryrefund($data['OrderId'],$refundNo);
		}else{
			$this->LOGS('同兴支付退款失败--->>>'.$res);
			return $result['dealMsg'];
		}

	}

	/**
	 * 查询退款结果
	 */
	public function queryrefund($oid,$BackNo){
		// $oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$param=array();
		$param['service']='offSearchRefund';
		$param['merchantNo']=$this->merchantNo;
		$param['refundNo']=$BackNo;
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$this->LOGS('退款查询发送信息--->>>'.json_encode($param));
		$res=$this->postXmlCurl($param,$this->apiurl);
		$result=json_decode($res,true);
		$this->LOGS('退款查询结果--->>>'.$res);
		if ($result['dealCode']=='10000') {
			if ($result['refundStatus']=='4') {
				return true;
			}elseif ($result['refundStatus']=='3') {
				sleep(2); //睡两秒再请求
				$this->queryrefund($oid,$BackNo);
			}elseif ($result['refundStatus']=='5') {
				$this->LOGS('同兴支付退款失败--->>>'.$res);
				return $result['dealMsg'];
			}
		}

	}

	/**
	 * 同兴代付
	 */
	public function payother($data){
		$url="http://api.tongxingpay.com/txpayApi/bank";
		$Param=array();
		$Param['service']='batchPay';
		$Param['merchantNo']=$this->merchantNo;
		$Param['version']='V1.0';
		$Param['bgUrl']='https://www.huijistore.com/Admin/Payapi/payother_notify.html';
		$Param['batchNo']=$data['batchNo'];
		$Param['totalNum']=1;
		$Param['totalAmount']=$data['totalAmount'];//传值确定
		$Param['orderTime']=date('YmdHis',time());
		$Param['isRepay']=0;
		$Param['sign']=$this->lvs_sign($Param);
		$Param['content']=$data['content'];
		$this->LOGS('代付接口银行请求信息--->>>'.json_encode($Param,JSON_UNESCAPED_UNICODE));
		$res=$this->postXmlCurl($Param,$url);
		//直接处理？？？
		$this->LOGS($res);
		$result=json_decode($res,true);
		return $result;
	}


	/**
	 * 签名
	 */
	private function lvs_sign($data){
		ksort($data);
		$str=urldecode(http_build_query($data));
		$str=$str.$this->key;
		$this->LOGS($str);
		$sign=md5($str);
		$this->LOGS($sign);
		return $sign;
	}


	/**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    public function postXmlCurl($xml, $url, $second = 30)
    {       
        // var_dump($xml);exit();
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        //如果有配置代理这里就设置代理
        // if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
        //  && WxPayConfig::CURL_PROXY_PORT != 0){
        //  curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
        //  curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
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
            return $res;
        }
    }

    /**
     * 处理反馈
     */
    public function payother_notify(){
    	$data=$_POST;
    	$this->LOGS('代付接口反馈信息---->>>>'.json_encode($data,JSON_UNESCAPED_UNICODE),true);
    	if ($data['dealCode']=='10000') {
    		$batchNo=$data['batchNo'];
    		$cutinfo=M()->table('RS_MerCutDetail')->where("Payid='%s'",$batchNo)->find();
			$MoneyInfo=array();
			$MoneyInfo['Money']=$cutinfo['Money'];
			$MoneyInfo['Type']='less';
			$MoneyInfo['Useage']='JS';
			$MoneyInfo['token']=$this->token;
			$MoneyInfo['stoken']=$cutinfo['stoken'];
			$update=array();
			$update['Status']='1';
			$update['ExecDate']=date('Y-m-d H:i:s',time());
			
    	}else{
    		//打款失败
    	}
    }

    /**
     * 生成一个单号
     */
    public function getOid($type){
    	if ($type) {
    		$str=strtoupper($type);
    		$str.=date('YmdHis',time());
    		$str.=substr(str_shuffle(md5(uniqid())), -6,6);
    		return $str;
    	}else{
    		return false;
    	}
    }

    /**
     * 退款成功通知
     */
    public function refunNotify(){
    	$data=$_POST;
    	$this->LOGS('退款接口反馈信息---->>>>'.json_encode($data,JSON_UNESCAPED_UNICODE),true);
    	$oid=$data['orderNo'];
    	$refundNo=$data['refundNo'];
    	if ($data['refundStatus']=='4') {
	    	if (substr($refundNo, 0,7)=='OrderTk') {
	    		$oid=strrev(substr(strrev($oid), 6));
	    		//普通订单处理
	    		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
	    		// var_dump(M()->getlastsql());
		        $tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8);
		        if ($oinfo['stoken']!='0') {
		            $sid=$this->model->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->getField('id');
		            $tb_name=$tb_name.'_'.$sid;
		        }
		        // var_dump($oinfo);
		        $this->model->startTrans();
		        $memberScore=$srecord=$myCps=$myCp=$lesscash=$less=$orderres=$shres=true;
		        $myscore=M()->table('RS_IntegralDetail')->where("token='%s' and MemberId='%s' and Remarks='%s' and Type='%s'",array($oinfo['token'],$oinfo['MemberId'],$oid,'cons'))->getField('Integral'); //查询积分信息
		        // var_dump(M()->getlastsql());
		        // var_dump($myscore);exit();
		        $shres=$this->model->execute("UPDATE wh SET StockCount=StockCount+ol.Count,VirtualCount=VirtualCount+ol.Count,SalesCount=SalesCount-ol.Count FROM {$tb_name} wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}' and ol.IsDelete=0");
		        // var_dump(M()->getlastsql());
		        if ($myscore) {
		            $memberScore=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($oinfo['token'],$oinfo['MemberId']))->setDec('Integral',$myscore); //扣减对应积分
		            $tempSDB['MemberId']=$oinfo['MemberId'];
		            $tempSDB['Integral']=-floatval($myscore);
		            $tempSDB['Type']='constk';
		            $tempSDB['Remarks']=$oid;
		            $tempSDB['token']=$oinfo['token'];
		            $srecord=M()->table('RS_IntegralDetail')->add($tempSDB);  //插入积分扣减记录
		        }
		        if ($oinfo['Status']!=8) {
		            $orderres=$this->model->table('RS_Order')->where("OrderId='%s' and token='%s'",array($oid,$oinfo['token']))->setField(array('Status'=>8,'BackMoneyDate'=>date('Y-m-d H:i:s',time())));
		        }else{
		            $this->model->rollback();
		            $this->LOGS('该订单已处理，请勿重复处理');

		        }
		        if ($memberScore && $srecord && $myCps && $myCp && $lesscash && $less && $orderres && $shres) {
		        	//处理反馈
		        	$this->LOGS('订单退款处理成功');
		        	$this->model->commit();
		        	$Param=array();
		        	$Param['merchantNo']=$this->merchantNo;
		        	$Param['dealResult']='SUCCESS';
		        	$sign=$this->lvs_sign($Param);
		        	$Param['sign']=$sign;
		        	$this->LOGS(json_encode($Param));
		        	$this->postXmlCurl($Param,$this->apiurl);
		        }else{
		        	$this->model->rollback();
		        	$this->LOGS('订单退款处理失败'."$memberScore && $srecord && $myCps && $myCp && $lesscash && $less && $orderres && $shres");
		        }
	    	}elseif (substr($refundNo, 0,7)=='GroupTk') {
	    		//团购单处理
	    		if ($this->model->table('RS_GroupBuyerList')->where("BackNo='%s'",$refundNo)->setField('Status','1')) {
		        	$Param=array();
		        	$Param['merchantNo']=$this->merchantNo;
		        	$Param['dealResult']='SUCCESS';
		        	$sign=$this->lvs_sign($Param);
		        	$Param['sign']=$sign;
		        	$this->postXmlCurl($Param,$this->apiurl);
	    		}else{
	    			$this->LOGS('团购退款处理失败--->>>'.M()->getlastsql());
	    		}
	    	}elseif (substr($refundNo, 0,4)=='RkTk') {
	    		//入库单处理
	    		if ($this->model->table('RS_ProductInWarehouse')->where("OrderId='%s'",$oid)->setField('Status',4)) {
		        	$Param=array();
		        	$Param['merchantNo']=$this->merchantNo;
		        	$Param['dealResult']='SUCCESS';
		        	$sign=$this->lvs_sign($Param);
		        	$Param['sign']=$sign;
		        	$this->postXmlCurl($Param,$this->apiurl);
	    		}else{
	    			$this->LOGS("拒绝入库申请退款处理失败--->>>".M()->getlastsql());
	    		}
	    	}
    	}
    	// echo "over";
    }

}














 ?>