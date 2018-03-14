<?php
namespace Org\WeChar;

class Wx_JSSDK {

  private $appId;
  private $appSecret;
  private $filePath;

  public function __construct($appId, $appSecret,$rootPath) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
    $this->filePath=$rootPath;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // ע�� URL һ��Ҫ��̬��ȡ������ hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // ���������˳��Ҫ���� key ֵ ASCII ����������
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {

  	$getData=false;
  	$ticket='';

    // jsapi_ticket Ӧ��ȫ�ִ洢����£����´�����д�뵽�ļ�����ʾ��
    $data = json_decode($this->get_json_file("jsapi_ticket_$this->appId.json"));


//$this->set_json_file("x_$this->appId.json", json_encode($data));


    if (empty($data)) {
    	$getData=true;
    }

    if ($data->expire_time < time()) 
    {
    	$getData=true;
    } 
    else 
    {
    	$ticket = $data->jsapi_ticket;
    }

//$this->set_json_file("xx_$this->appId.json", json_encode($getData));

    if ($getData) 
    {
		$accessToken = $this->getAccessToken();
		// �������ҵ�������� URL ��ȡ ticket
		// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
		$res = json_decode($this->httpGet($url));
//$this->set_json_file("xxxxxxxx_$this->appId.json", json_encode($res));
		$ticket = $res->ticket;

  		if ($ticket) 
  		{
  			$data->expire_time = time() + 7000;
  			$data->jsapi_ticket = $ticket;
  			$this->set_json_file("jsapi_ticket_$this->appId.json", json_encode($data));
  		}
    }

    return $ticket;
  }

  private function getAccessToken() {

  	$getData=false;
  	$access_token='';

    // access_token Ӧ��ȫ�ִ洢����£����´�����д�뵽�ļ�����ʾ��
    $data = json_decode($this->get_json_file("access_token_$this->appId.json"));
//$this->set_json_file("xxxx_$this->appId.json", json_encode($data));
	if (empty($data)) {
		$getData=true;
	}
	else
	{
		if ($data->expire_time < time()) 
	    {
	    	$getData=true;
	    } 
	    else 
	    {
	      $access_token = $data->access_token;
	    }
	}
//$this->set_json_file("xxxxx_$this->appId.json", json_encode($getData));
	if ($getData) {
		// �������ҵ��������URL��ȡaccess_token
		// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
		$res = json_decode($this->httpGet($url));
//$this->set_json_file("xxxxxx_$this->appId.json", json_encode($res));
		$access_token = $res->access_token;
		if ($access_token) 
		{
			$data->expire_time = time() + 7000;
			$data->access_token = $access_token;
			$this->set_json_file("access_token_$this->appId.json", json_encode($data));
		}
	}

    return $access_token;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // Ϊ��֤��������������΢�ŷ�����֮�����ݴ���İ�ȫ�ԣ�����΢�Žӿڲ���https��ʽ���ã�����ʹ������2�д����ssl��ȫУ�顣
    // ����ڲ�������д����ڴ˴���֤ʧ�ܣ��뵽 http://curl.haxx.se/ca/cacert.pem �����µ�֤���б��ļ���
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    //$res=curl_errno($curl);
    curl_close($curl);

    return $res;
  }

  private function get_json_file($filename) {
    return trim(file_get_contents($this->filePath.'/json/'.$filename));
  }
  private function set_json_file($filename, $content) {
    $fp = fopen($this->filePath.'/json/'.$filename, "w");
    fwrite($fp,$content);
    fclose($fp);
  }
}