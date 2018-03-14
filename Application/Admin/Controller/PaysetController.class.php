<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 支付配置类--20160326--leaves
 */
class PaysetController extends CommonController
{

  function _initialize()
  {
    parent::_initialize();
  }

  public function index(){
    $config=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find();
    $this->assign('config',$config);
    $this->display();
  }

  /**
   * 保存微信支付配置内容
   */

   public function wxpaysave(){
    //  echo "<pre>";
    //  var_dump($_POST);exit;
    if (IS_POST) {
      $data['appid']=$_POST['APPID'];
      $data['appsecret']=$_POST['APPSECRET'];
      $data['mchid']=$_POST['mchid'];
      $data['apikey']=$_POST['api'];
      $data['apiclient_cert']=$_POST['apiclient_cert'];
      $data['apiclient_key']=$_POST['apiclient_key'];
      $data['createtime']=time();
      $data['token']=$this->token;
      if ($_POST['ID']) {
        if ($this->MSL('wxpayset')->where("token='%s'",$this->token)->save($data)) {
          $this->success('设置成功');
        }else{
          $this->error('设置失败');
        }
      }else{
        if ($this->MSL('wxpayset')->add($data)) {
          $this->success('设置成功');
        }else{
          $this->error('设置失败');
        }
      }
    }else{
      $this->error('非法操作');
    }
   }

   /**
    * 微信转账
    */
   public function payother(){
    $this->display();
   }

   /**
    * 
    */
   public function openidqr(){
    ob_clean();
    vendor('PHPQR.phpqrcode');
      $url='http://'.$_SERVER['HTTP_HOST'].U('Admin/Base/checkStore');
    $level="L";
    // $filename='./Uploads/1.png';
    $size=4;
    \QRcode::png($url,$filename,$level,$size,'2');
   }

   /**
    * 转账
    */
   public function givemoney(){
    // var_dump($_POST);
    $money=floatval($_POST['money'])*100;
    $openid=$_POST['openid'];
    $dir=dirname(__FILE__);
      $merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();//获取商户基本信息
      $wxinfo=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find(); //获取商户微信支付信息
      $apiclient_cert="E:/APPS/Hmall/Home/Web/Public/Admin/apiclient_cert.pem";
      $apiclient_key="E:/APPS/Hmall/Home/Web/Public/Admin/apiclient_key.pem";
      // var_dump($apiclient_cert);exit();
        //用户openid存在-->处理业务
      $mch_billno=$wxinfo['mchid'].date('Ymd',time()).substr(time(), 5).substr(microtime(), 2,5);
      $mch_id="1417749402";
      $wxappid="wx1ade5f9a94f7e0b1";
      $send_name=$merchant['storeName'];
      $re_openid=$getinfo['OpenId'];
      $total_amount=floatval($getinfo['Money'])*100;
      $total_num=1;
      $client_ip=$_SERVER['SERVER_ADDR'];
      $nonce_str=md5($mch_billno);
        //参数集合
      $param=array();
      $param['mch_appid']='wx1ade5f9a94f7e0b1';
      $param['mchid']=$mch_id;
      $param['nonce_str']=md5($mch_billno);
      $param['partner_trade_no']=$mch_billno;
      $param['openid']=$openid;
      $param['check_name']='NO_CHECK';
      $param['amount']=$money;
      $param['desc']='企业转账';
      $param['spbill_create_ip']=$_SERVER['SERVER_ADDR'];
      ksort($param);
      $strA=http_build_query($param);
      // var_dump($strA);exit();
        // 排列数据
      // $strA="act_name=提现红包&client_ip=".$client_ip."&mch_billno=".$mch_billno."&mch_id=".$mch_id."&nonce_str=".$nonce_str."&re_openid=".$re_openid."&remark=提现红包&send_name=".$send_name."&total_amount=".$total_amount."&total_num=".$total_num."&wishing=提现红包&wxappid=".$wxappid;
      $key="shunliankeji13607661269shunliany";
        //
      $stringSignTemp=$strA."&key=".$key;
      $sign=strtoupper(md5($stringSignTemp));
      $param['sign']=$sign;
      $xmls=$this->ToXml($param);
        //生成签名
      // $str="<xml><sign><![CDATA[%s]]></sign> <mch_billno><![CDATA[%s]]></mch_billno> <mch_id><![CDATA[%s]]></mch_id> <wxappid><![CDATA[%s]]></wxappid> <send_name><![CDATA[%s]]></send_name> <re_openid><![CDATA[%s]]></re_openid> <total_amount><![CDATA[%s]]></total_amount> <total_num><![CDATA[%s]]></total_num> <wishing><![CDATA[提现红包]]></wishing> <client_ip><![CDATA[%s]]></client_ip> <act_name><![CDATA[提现红包]]></act_name> <remark><![CDATA[提现红包]]></remark> <nonce_str><![CDATA[%s]]></nonce_str> </xml>";
      // $data=sprintf($str,$sign,$mch_billno,$mch_id,$wxappid,$send_name,$re_openid,$total_amount,$total_num,$client_ip,$nonce_str);
      $url="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        //提交红包请求
      $res=$this->https_post($url,$xmls,$apiclient_cert,$apiclient_key);
      // $res=simplexml_load_string($res);
      $this->LOGS($res);
      // var_dump(json_decode(json_encode($res),true));exit();
      $this->LOGS(json_encode($res));
      // var_dump($res);exit();
        // file_put_contents('111.txt', $res);

        // $payData=$this->MSL('wxpayset')->where(array('token'=>$this->token))->find();
        //
        // $wxData=new \Org\WeChar\Wx_Data();
        // $wxData->token=$this->token;
        // $wxData->values=array(
        //  'mch_appid'=>$payData['appid'],
        //  'mchid'=>$payData['mchid'],
        //  'nonce_str'=>$wxData->createNonceStr('32'),
        //  'partner_trade_no'=>"TX".date("YmdHis",time()).rand(1000,9999),
        //  'openid'=>$getinfo['OpenId'],
        //  'check_name'=>'OPTION_CHECK',
        //  're_user_name '=>'黎明',
        //  'amount'=>$getinfo['Money']*100,
        //  'desc'=>'用户提现',
        //  'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
        //  // 'refund_fee'=>$pid['Price']*100,
        //  // 'op_user_id'=>$payData['mchid'],
        // );
        //
        // $wxData->values['sign']=$wxData->SetSign($payData['apikey']);
        // file_put_contents('1111.txt',json_encode($wxData->values));
        // $wxPayAPI= new \Org\WeChar\WxPay\WxPay_Api();
        //
      //  $xml=$wxData->FromXml($wxPayAPI->refund($wxData));
        //
        //
      $xml=simplexml_load_string($res);
        file_put_contents('1.txt',$xml);

        // $result=json_decode(json_encode($xml),true);
      if ($xml->return_code=='SUCCESS') {
        if ($xml->result_code=='SUCCESS') {
          $remarks="发放成功--> 红包金额:".($xml->total_amount/100)." --发放时间:".$xml->send_time." --红包微信单号:".$xml->send_listid;
          $this->LOGS($remarks);
          $this->success('转账成功');
        }else{
          $remarks="发放失败-->错误代码:".$xml->err_code." --错误描述:".$xml->err_code_des;
          $this->LOGS($remarks);
          $this->success('转账失败');
        }
      }else{
        $remarks="发放失败-->错误代码:".$xml->return_code." --错误描述:".$xml->return_msg;
        $this->LOGS($remarks);
        $this->success('转账失败');
      }

    }

    public function ToXml($param)
    {
      if(!is_array($param) 
        || count($param) <= 0)
      {
        return false;
      }
      
      $xml = "<xml>";
      foreach ($param as $key=>$val)
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

    public function uploadImg() {
      $this->display();
    }


    /**
     * curl_post调用
     */
  public function https_post($url,$data,$apiclient_cert,$apiclient_key)
  {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($curl,CURLOPT_SSLCERTTYPE,'PEM');
      curl_setopt($curl,CURLOPT_SSLCERT,$apiclient_cert);
      //默认格式为PEM，可以注释
      curl_setopt($curl,CURLOPT_SSLKEYTYPE,'PEM');
      curl_setopt($curl,CURLOPT_SSLKEY,$apiclient_key);
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $result = curl_exec($curl);
      if (curl_errno($curl)) {
         return 'Errno'.curl_error($curl);
      }
      curl_close($curl);
      return $result;
  }




}














 ?>
