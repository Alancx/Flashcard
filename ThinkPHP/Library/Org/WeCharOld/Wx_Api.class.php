<?php
namespace Org\WeChar;
/**
*  微信基础功能Api
*/
use Org\Net\Http;
class Wx_Api
{

	function __construct()
	{
		# code...
	}

	public function getOpenId($WxParam)
	{
		//return print_r($_GET).
		//return ($WxParam['SITE_URL'].substr(U($Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME,$_GET),1));

		if(empty($_GET['code']))
		{
			$wxURL='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$WxParam['wx_appid'].'&redirect_uri='.urlencode($WxParam['site_url']).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
			redirect($wxURL);
		}
		else
		{
			// var_dump($_GET);
			if ($_GET['subscribe']=='0') 
			{
				$wxGetOpenIdUrl='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$WxParam['wx_appid'].'&secret='.$WxParam['wx_appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code';
				$returnRes=$this->httpGet($wxGetOpenIdUrl);
				$getParams=json_decode($returnRes,true);
				$userurl='https://api.weixin.qq.com/sns/userinfo?access_token='.$getParams['access_token'].'&openid='.$getParams['openid'].'&lang=zh_CN';
				$returnRes=$this->httpGet($userurl);
				$getParams=json_decode($returnRes,true);
				$getParams['subscribe']='0';
				$userinfo=$getParams;
			} 
			else 
			{
				$wxGetOpenIdUrl='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$WxParam['wx_appid'].'&secret='.$WxParam['wx_appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code';
				$returnRes=$this->httpGet($wxGetOpenIdUrl);
				$getParams=json_decode($returnRes,true);
				//////////////
				$get_access_token_url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$WxParam['wx_appid'].'&secret='.$WxParam['wx_appsecret'].'';
				$returnRes=$this->httpGet($get_access_token_url);
				$getasstoken=json_decode($returnRes,true);

				$wxgetuserinfourl='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$getasstoken['access_token'].'&openid='.$getParams['openid'].'&lang=zh_CN';
				$returnRes=$this->httpGet($wxgetuserinfourl);
				$getParams=json_decode($returnRes,true);
				if ($getParams['subscribe']=='0') {
					$_GET['subscribe']='0';
					$wxurl='https://'.$this->webParam['host'].U($Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME,$_GET);
					$wxURL='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$WxParam['wx_appid'].'&redirect_uri='.urlencode($wxurl).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
					redirect($wxURL);
				} else {
					$userinfo=$getParams;
				}
			}
			////////////////////////////////////
			if(!empty($userinfo['openid']))
			{
				$info['openid']=$userinfo['openid'];
				$info['nickname']=$userinfo['nickname'];
				$info['subscribe']=$userinfo['subscribe'];
				$info['sex']=$userinfo['sex'];
				$info['city']=$userinfo['city'];
				$info['province']=$userinfo['province'];
				$info['headimgurl']=$userinfo['headimgurl'];
				$info['unionid']=$userinfo['unionid'];
				return $info;
			}
			else
			{
				return $info['openid']='ERROR';
			}
		}
	}


}
