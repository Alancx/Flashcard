<?php
namespace Admin2\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class AllowController extends Controller {
    private  $appid="wx3dd28eb27ded279b";
    private  $appsecret="9b7c0e147107c71f367ebb92573f18d5";
    public function _initialize(){ 
        // session('openid',null);   
        // var_dump(session('openid')); exit;   
        if (empty(session('openid'))){
            $userinfo=$this->getcode();             
            // var_dump($userinfo);exit();
            $list=M('users')->where(array('openid'=>$userinfo['openid'],'isadmin'=>'2'))->find();
            // var_dump($list);exit;
            if($list){
                session('openid',$list['openid']);
                session('name',$list['name']);
                session('pic',$list['pic']);
            }else{                
                $this->error('没有权限','/Admin2/Admin/index',1);
                
            }            
        } else {
            $list=M('users')->where(array('openid'=>session('openid'),'isadmin'=>'2'))->find();
            // var_dump($list);exit;
            if($list){
                session('openid',$list['openid']);
                session('name',$list['name']);
                session('pic',$list['pic']);
            }else{                
                $this->error('没有权限','/Admin2/Admin/index',1);
                
            }
        }
    }
    // public function postXmlCurl($xml, $useCert = false, $second = 30)
    // {   
    //     $time=date('Y:m:d H:i:s',time());
    //     $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
    //     $url=file_get_contents($url);
    //     $url=json_decode($url,true);
    //     $urll="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$url['access_token']}";
    //     // $date=array("touser"=>"",
    //     //     "template_id"=>'aVXDUPri1RrPm-NhTi7vjCUFerSZmV0i4LKIzIN79p8',
    //     //     'data'=>array('first'=>array('value'=>'管理员给你发布了一条任务','color'=>'#000000'),'keyword1'=>array('value'=>'哈哈','color'=>'#000000'),'keynote2'=>array('value'=>'哈哈','color'=>'#000000'),'keynote3'=>array('value'=>'{$time}','color'=>'#000000'),'keynote4'=>array('value'=>'{$time}','color'=>'#000000'),'remark'=>array('value'=>'哈哈','color'=>'#000000')));
    //     // $xml=json_encode($date);
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    //     curl_setopt($ch,CURLOPT_URL, $urll);
    //     // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
    //     curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    //     curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
    //     curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //     if($useCert == true){
    //         //设置证书
    //         //使用证书：cert 与 key 分别属于两个.pem文件
    //         curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
    //         curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
    //         curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
    //         curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
    //     }
    //     //post提交方式
    //     curl_setopt($ch, CURLOPT_POST, TRUE);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //     //运行curl
    //     $data = curl_exec($ch);
    //     //返回结果
    //     var_dump($data);exit();
    //     if($data){
    //         curl_close($ch);
    //         $res['status']=true;
    //         $res['info']=$data;
    //         // $this->LOGS($data);
    //         return $res;
    //     } else { 
    //         $error = curl_errno($ch);
    //         curl_close($ch);
    //         $res['status']=false;
    //         $res['info']="curl出错，错误码:$error";
    //         $this->LOGS(json_encode($res));
    //         return $res;
    //     }
    //     // var_dump($xml);
    // }
    public function getcode(){
     if (empty($_GET['code'])) {
            // var_dump(ACTION_NAME);exit();
            $redirect_uri="http://flash.shunliansoft.com/index.php/Admin2/".CONTROLLER_NAME."/".ACTION_NAME;
            $redirect_uri=urlEncode($redirect_uri);
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header("location:$url");
        } else {            
            $code=$_GET['code'];
            // var_dump($code);
            $token="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
            $token=file_get_contents($token);
            $token=json_decode($token,true);
            // var_dump($token);
            $app="https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$token['openid']}&lang=zh_CN";
            $app=file_get_contents($app);
            $app=json_decode($app,true);
            return $app;        
        }                 
    }
}