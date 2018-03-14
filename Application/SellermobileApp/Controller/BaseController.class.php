<?php
namespace SellermobileApp\Controller;
use Think\Controller;
class BaseController extends Controller{
	public $apptoken;   //用户apptoken 唯一标识
	public $apptokentemp; ////apptoken 加时间戳
  public $activeurl; /////请求地址//用于验证签名
	public $timetemp; //////请求的时间戳////
	public $sign;  /////签名/////  除了登陆签名，其他签名规则一样
	public $REALPATH;
	public function _initialize(){
		$this->REALPATH=explode('/Home/Application/',str_replace('\\','/',realpath(dirname(__FILE__).'/')))[0].'/Home/Web';
		$typetemp=is_null($_GET['type'])?$_POST['type']:$_GET['type'];
		if (!is_null($typetemp)) {
			if(!in_array($typetemp,C('TGFUNCTION'))){
				if (abs($_POST['timetemp']-time())>60) {
					$this->ajaxReturn(array('status' => 'false', 'info' => 'timedifError'), 'JSON');
				}
			}
		}

		if (IS_POST) {
			$this->apptokentemp=$_POST['apptoken'];
			$this->apptoken=substr($_POST['apptoken'],0,32);
			$this->activeurl='https://'.$_SERVER['HTTP_HOST'].__ACTION__;
			$this->timetemp=$_POST['timetemp'];
			$this->sign=$_POST['sign'];
		}
	}

	//基础数据库读取
	public function BM($tableName)
	{
		return M($tableName,C('DB_BASE')['DB_PREFIX'],'DB_BASE');
	}

	//用户数据库读取
	public function UM($tableName)
	{
		return M($tableName,C('DB_USER')['DB_PREFIX'],'DB_USER');
	}

	//仓库数据库读取
	public function WM($tableName)
	{
		return M($tableName,C('DB_USER')['DB_PREFIX'],'DB_WAREHOUSE');
	}
	//仓库数据库读取没有读取前缀
	public function WNM($tableName)
	{
		return M($tableName,'','DB_WAREHOUSE');
	}

	//短信发送-基于腾信公司接口
	public function SendMessage($messageData)
	{

		$timeStamp=date('Y-m-d H:i:s');

		$SMData=array(
			'username'=>C('SMS_USERNAME'),
			'password'=>md5(C('SMS_PASSWORD').$timeStamp),
			'mobiles'=>$messageData['mobiles'],
			'content'=>C('SMS_SIGN').$messageData['content'],
			'f'=>'1',
			'timestamp'=>$timeStamp,
		);

		$SendURL=C('SMS_SENDURL').http_build_query($SMData);
		$res=$this->HTTPGET($SendURL);
		$this->LOGS($SendURL.'---结果'.$res);
		// $resArray=json_decode($res,true);
		return $res;
	}


	/**
	* 用户操作记录log
	*/
	public function LOGS($desc='',$controller=CONTROLLER_NAME,$function=ACTION_NAME){
		$filename=date('Y-m-d',time());
		$logfile=str_replace('\\','/',strrev(substr(strrev(dirname(__FILE__)),10))).'logs/';
		if (!is_dir($logfile)) {
			mkdir($logfile,777);
		}
		$logfile=$logfile.$filename.'.txt';
		$content='操作定位:'.$controller.'/'.$function.'::::'.$desc.'::::::操作日期:::::::'.date('Y-m-d H:i:s',time()).PHP_EOL;
		file_put_contents($logfile,$content,FILE_APPEND);
	}
	/////////发送微信模板消息/////////
	public function sendWxMessage($info)
	{
		import("Vendor.Wechat.WXTemplate");

		// $wxPInfo=$this->UM('wxpayset')->where(array('token'=>$this->token))->find();
		$wxchatinfo['appid']=C('WXAPPID');
		$wxchatinfo['appsecert']=C('WXAPPSECRET');
		$sendwxchat=new \WXTemplate($wxchatinfo);
		$res=$sendwxchat->sendTemplate($info);
		return $res;

	}

	/**
	 * 获取随机字符串
	 * $str 字符串长度、$num数字长度 $showtime是否使用时间戳
	 */
	public function getStr($str,$num,$showtime=true){
		$s='qwertyuioplkjhgfdsazxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ';
		$n='1234567890';
		$ss="";
		for ($i=0; $i < $str; $i++) {
			$ss.=substr(str_shuffle($s), $i,1);
		}
		$nn="";
		for ($i=0; $i < $num; $i++) {
			$nn.=substr(str_shuffle($n), $i,1);
		}
		if ($showtime) {
			$String=$ss.substr(time(), 1,7).$nn;
		}else{
			$String=$ss.$nn;
		}
		return md5($String);
	}

	//POST请求
	public function HTTPPOST($url, $post_data = '', $timeout = 5)
	{
		$ch = curl_init();

		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_POST, 1);

		if($post_data != ''){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_HEADER, false);

		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}

	//GET请求
	public function httpGet($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}
}
?>
