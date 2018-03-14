<?php
namespace Sellermobile\Controller;
use Think\Controller;
class PaymentController extends CommonController {
  public function getpayID($oid,$totalprice){
    $url="http://api.tongxingpay.com/txpayApi/offLine";
    $nowTimeStr=date('YmdHis',time());
    $rkid='RK'.date('ymdHis',time()).time();
    $data=array(
        'service'=>'publicPay',
        'merchantNo'=>'TX0001455',
        'bgUrl'=>"https://".$_SERVER['HTTP_HOST'].U('Payment/TXNotify'),
        'version'=>'V1.0',
        'payChannelCode'=>'TX_WXZF',
        'orderNo'=>$rkid,
        'orderAmount'=>$totalprice*100,
        'curCode'=>'CNY',
        'orderTime'=>$nowTimeStr,
        'productName'=>'采购订单-支付',
        'openid'=>$this->useropenid,
        'ext1'=>$oid,
        );
        $key='d80efe63192b4b7ebf9a30d78075b8fa';
        $data['sign']=$this->makesignstr($data,$key);//////签名;

    $res=$this->HTTPPOST($url,$data);
    $resArray=json_decode($res,true);
    if ($resArray['dealCode']=='10000') {
      $payData=array(
              "appId"=>$resArray['appId'],     //公众号名称，由商户传入
              "timeStamp"=>$resArray['timeStamp'],         //时间戳，自1970年以来的秒数
              "nonceStr"=>$resArray['nonceStr'], //随机串
              "package"=>"prepay_id=".$resArray['prepayId'],
              "signType"=>$resArray['signType'],         //微信签名方式：
              "paySign"=>$resArray['paySign'],
          );
      return $payData;
    } else {
      return false;
    }
  }
  ////////生成微信支付的随机字符串/////////
  public function makestr(){
    $strlist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str="";
    for ($i=0; $i <32 ; $i++) {
      $str.=$strlist[mt_rand(0,strlen($strlist)-1)];
    }
    return $str;
  }
  ////////生成微信支付的签名/////////
  public function makesignstr($values,$key){
    ksort($values);
    $str = "";
    foreach ($values as $k => $v)
    {
      if($k != "sign" && $v != "" && !is_array($v)){
        $str .= $k . "=" . $v . "&";
      }
    }
    $str = trim($str, "&");
    return strtoupper(md5($str.$key));
  }

  public function ToXml($data)
	{

    	$xml = "<xml>";
    	foreach ($data as $key=>$val)
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
///////////同心获取预支付ID的通知地址//////////////////
public function TXNotify(){
  $this->LOGS('同心支付返回结果');
  $postData=$_POST;
  $orderTrueId=$postData['orderNo'];
  $returnorder=$postData['txOrderNo'];
  $returnbankorder=$postData['bankOrderNo'];
  $ext1=$postData['ext1'];
  $this->LOGS($ext1);
  try {
    $res=$this->BM('productinwarehouse')->where(array('InWarehouseId'=>$ext1))->save(array('IsPay'=>'1','OrderId'=>$orderTrueId,'txOrderNo'=>$returnorder,'bankOrderNo'=>$returnbankorder));
    $this->LOGS(json_encode($res));
  } catch (Exception $e) {
    $res=false;
    $this->LOGS('支付成功修改数据库失败');
  }

  if ($res) {
    $key='d80efe63192b4b7ebf9a30d78075b8fa';
    $returnData=array(
      'merchantNo'=>'TX0001455',
      'dealResult'=>'SUCCESS'
    );

    $signStr=$this->makesignstr($returnData,$key);

    $returnData['sign']=$signStr;

    $this->ajaxReturn($returnData,'JSON');
  }

}



}?>
