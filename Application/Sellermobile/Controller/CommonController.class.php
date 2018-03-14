<?php
namespace Sellermobile\Controller;
use Think\Controller;
use Org\WeChar\Wx_JSSDK;
class CommonController extends BaseController{
	public $token;
	public $stoken;
	public $department;
	public $REALPATH;
	public $wxJSSDKConfigArray;/////分享使用///////
	public function _initialize(){
		$this->token=session('token');
		$this->stoken=session('stoken');
		$this->useropenid=session('useropenid');
		$this->department=session('department');
		$this->REALPATH=explode('/Home/Application/',str_replace('\\','/',realpath(dirname(__FILE__).'/')))[0].'/Home/Web';
		////////分享//////////////
		$jssdkObj=new \Org\WeChar\Wx_JSSDK(C('WXAPPID'),C('WXAPPSECRET'),$this->REALPATH);
		$this->wxJSSDKParam=$jssdkObj->getSignPackage();
		$this->wxJSSDKConfigArray=array(
			'debug'=>false,
			'appId'=>$this->wxJSSDKParam['appId'],
			'timestamp'=>$this->wxJSSDKParam['timestamp'],
			'nonceStr'=>$this->wxJSSDKParam['nonceStr'],
			'signature'=>$this->wxJSSDKParam['signature'],
			'jsApiList'=>array('scanQRCode','hideAllNonBaseMenuItem')
		);
		$this->assign('wxJSSDKConfigStr',json_encode($this->wxJSSDKConfigArray));
		$geturl=$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME;
		if(!in_array($geturl,C('TXNOTICE'))){
			if (session('is_login')!=1 || session('userinfo')==false) {
				$this->redirect('Public/login');
			}
		}
	}
/////////发送微信模板消息/////////
	public function sendWxMessage($info)
	{
		import("Vendor.Wechat.WXTemplate");		
		$wxchatinfo['appid']=C('WXAPPID');
		$wxchatinfo['appsecert']=C('WXAPPSECRET');
		$sendwxchat=new \WXTemplate($wxchatinfo);
		$res=$sendwxchat->sendTemplate($info);
		$this->LOGS('微信消息---结果'.$res);
		return $res;

	}
}
?>
