<?php
namespace Sellermobile\Controller;
use Think\Controller;
class BaseController extends Controller{

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
