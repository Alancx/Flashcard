<?php
namespace Home\Controller;
use Think\Controller;
use Org\WeChar\WxPay\WxPay_Api;
use Org\WeChar\WxPay\WxPayData\WxPay_Data;
class PaymentController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
  }

  public function Index(){
    $oid = $_GET['oid'];
    $orderinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid,'Status'=>'1'))->find();
    if ($orderinfo) {
      if($orderinfo['PayName']=='T'){
        if (session('openid')!='' && session('openid') !=null) {
          $extStr='FJ'.rand(1000,9999);
          $res=M()->table('RS_Order')->where(array('OrderId'=>$oid))->setField(array('extStr'=>$extStr));
          if ($res) {
            $appid=C('WX_OPEN_APPID');
            $mchid=C('WX_PAY_MCHID');
            $apikey=C('WX_PAY_KEY');
            // $appid='wx3dd28eb27ded279b';
            // $mchid='1394550102';
            // $apikey='henanshunlianruanjian66666666666';
            $sendData=array(
              'appid'=>$appid,
              'mch_id'=>$mchid,
              'out_trade_no'=>$orderinfo['OrderId'].$extStr,
              'body'=>'光盘客-订单付款',
              'total_fee'=>1,
              // 'total_fee'=>$orderinfo['Price']*100,
              'trade_type'=>'JSAPI',
              'notify_url'=>'http://'.$_SERVER['HTTP_HOST'].U('Payment/WXPayNotify'),
                'nonce_str'=>$this->createNonceStr('16'),
                'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
                'openid'=>session('openid'),
              );
              $wxpayData=new WxPay_Data();
              $wxpayData->SetAllValues($sendData);
              $wxpayData->SetSign($apikey);
              $wxPayAPI= new WxPay_Api();
              $red=$wxPayAPI->unifiedOrder($wxpayData);
              if($red['res']){
                $response = $wxpayData->FromXml($red['data']);
                if ($response['return_code']=='SUCCESS'){
                  $jsapipay['appId']=$response['appid'];
                  $jsapipay['package']='prepay_id='.$response['prepay_id'];
                  $jsapipay['timeStamp']= strval(time());
                  $jsapipay['nonceStr']=$response['nonce_str'];
                  $jsapipay['signType']='MD5';
                  $wxpayData->SetAllValues($jsapipay);
                  $jsapipay['paySign']=$wxpayData->SetSign($apikey);
                  $this->assign('wxPayData',json_encode($jsapipay));
                  $pagedata['paystatus']='true';
                }else{
                  $pagedata['paystatus']='false';
                }
              } else {
                $pagedata['paystatus']='false';
              }
            } else {
              $pagedata['paystatus']='false';
            }
          }
        }
        $pagedata['oid']=$orderinfo['OrderId'];
        $pagedata['total']=$orderinfo['Price'];
      } else {
        $pagedata['oid']=null;
        $pagedata['total']='0.00';
      }
      $pagedata['Title'] ='支付';
      $this->assign($pagedata);
      $this->display();
    }


    public function WXPayNotify(){
      $msg=$GLOBALS["HTTP_RAW_POST_DATA"];
      $getdata=json_decode(json_encode(simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
      $this->LOGS('商城微信支付反馈信息--->>>'.json_encode($getdata));
      $preArt=$getdata;
      unset($preArt['sign']);
      $returndate=json_decode($getdata['attach'],true);
      $key=C('WX_PAY_KEY');
      $sign=$this->lvs_sign($preArt,$key);
      if ($sign==$getdata['sign']) {
        $this->LOGS('微信支付验签成功');
        if ($getdata['result_code']=='SUCCESS') {
          $orderTrueId=explode('FJ',$getdata['out_trade_no'])[0];
          $res= A('Public')->paysuccess($orderTrueId);
          if ($res){
            echo "<xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
          } else {
            $this->LOGS('商品状态已变更(支付冲突1)--->>>'.json_encode($res));
          }
        }else{
          $this->LOGS('商品状态已变更(支付冲突2)--->>>');
        }
      } else {
        $this->LOGS('微信支付验签失败>>>>>'.$sign.'>>>>>'.$getdata['sign']);
      }
    }

    private function lvs_sign($data,$key){
      ksort($data);
      $str='';
      foreach ($data as $dk => $value) {
        $str.=$dk."=".$value."&";
      }
      // $str=trim($str,'&');
      // var_dump($str);exit();
      // $str=http_build_query($data);
      $str=$str.'key='.$key;
      // var_dump($str);exit();
      $this->LOGS($str);
      $sign=strtoupper(md5($str));
      $this->LOGS($sign);
      return $sign;
    }


    public function payordersuccess(){
      if (IS_POST) {
        $oid = $_POST['oid'];
        $res= A('Public')->paysuccess($oid);
        if ($res) {
          $this->ajaxReturn(array('status'=>'true','info'=>'SUCCESS'),'JSON');
        } else {
          $this->ajaxReturn(array('status'=>'false','info'=>'PAYERROR'),'JSON');
        }
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
      }
    }





  }?>
