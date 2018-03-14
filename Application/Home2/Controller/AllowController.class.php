<?php
namespace Home2\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class AllowController extends Controller {
    private  $appid="wx3dd28eb27ded279b";
    private  $appsecret="9b7c0e147107c71f367ebb92573f18d5";
    public function _initialize(){ 
        // session('openid',null);       
        if (empty(session('openid'))){
            $userinfo=$this->getcode();             
            $list=M('users')->where(array('openid'=>$userinfo['openid']))->find();
            if($list){
                session('openid',$list['openid']);
                session('name',$list['name']);
                session('pic',$list['pic']);
            }else{
                $data['name']=$userinfo['nickname'];
                $data['openid']=$userinfo['openid'];
                $data['pic']=$userinfo['headimgurl'];
                $data['post']='员工';
                $data['isadmin']='1';
                $mod=M('users')->add($data);
                session('openid',$userinfo['openid']);
                session('name',$userinfo['nickname']);
                session('pic',$userinfo['headimgurl']);    
            }            
        }
    } 
    public function getcode(){
     if (empty($_GET['code'])) {
            // var_dump(ACTION_NAME);exit();
            $redirect_uri="http://flash.shunliansoft.com/index.php/Home2/".CONTROLLER_NAME."/".ACTION_NAME;
            $redirect_uri=urlEncode($redirect_uri);
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header("location:$url");
        } else {            
            $code=$_GET['code'];
            // var_dump($code);
            $token="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
            $token=file_get_contents($token);
            $token=json_decode($token,true);
            // var_dump($token);exit;
            $app="https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$token['openid']}&lang=zh_CN";
            $app=file_get_contents($app);
            $app=json_decode($app,true);
            // var_dump($app);exit();
            // memberid
            return $app;        
        }                 
    }
}