<?php 
namespace Seller\Controller;
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
	function _initialize()
	{
		$this->model=M();
		$this->apiurl="http://api.tongxingpay.com/txpayApi/offLine";
		$this->merchantNo=C('merchantNo');
		$this->key=C('TXkey');
	}


	/**
	 * 退款接口
	 */
	public function txrefund($oid){
		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$oid=$oid.$oinfo['extStr'];
		$refundNo='TXTK'.substr($oid, 1);
		$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->setField('BackNo',$refundNo);
		$param=array();
		$param['service']='offRefundOrder';
		$param['merchantNo']=$this->merchantNo;
		$param['refundNo']=$refundNo;
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$param['curCode']='CNY';
		$param['refundAmount']=floatval($oinfo['Price'])*100;
		// $param['refundAmount']=1;
		$param['refundTime']=date('YmdHis');
		$param['refundDesc']='justrefund';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$this->LOGS('退款信息---<>>>'.json_encode($param));
		$res=$this->postXmlCurl($param,$this->apiurl);
		$result=json_decode($res,true);
		if ($result['dealCode']=='10000') {
			return true;
		}elseif ($result['dealCode']=='10001') {
			$this->queryrefund($oid);
		}else{
			$this->LOGS('同兴支付退款失败--->>>'.$res);
			return $result['dealMsg'];
		}

	}

	/**
	 * 查询退款结果
	 */
	public function queryrefund($oid){
		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$param=array();
		$param['service']='offSearchRefund';
		$param['merchantNo']=$this->merchantNo;
		$param['refundNo']=$oinfo['BackNo'];
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$res=$this->postXmlCurl($param,$this->apiurl);
		$result=json_decode($res,true);
		if ($result['dealCode']=='10000') {
			if ($result['refundStatus']=='4') {
				return true;
			}elseif ($result['refundStatus']=='3') {
				sleep(2); //睡两秒再请求
				$this->queryrefund($oid);
			}elseif ($result['refundStatus']=='5') {
				$this->LOGS('同兴支付退款失败--->>>'.$res);
				return $result['dealMsg'];
			}
		}

	}


	/**
	 * 签名
	 */
	private function lvs_sign($data){
		ksort($data);
		$str=http_build_query($data);
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

}














 ?>