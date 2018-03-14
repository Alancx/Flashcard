<?php
 namespace Org\AliExp;

// use Org\AliExp\Util;
 use Org\AliExp\Http\HttpRequest;
 use Org\AliExp\Constant\HttpMethod;
 use Org\AliExp\Constant\HttpHeader;
 use Org\AliExp\Constant\ContentType;
 use Org\AliExp\Constant\SystemHeader;
 use Org\AliExp\Http\HttpClient;

class AliExp_Api
{

	protected  $appKey;
  protected  $appSecret;
	protected  $host;
	protected  $path;
	protected  $querys = array();


	function  __construct($appKey, $appSecret, $url  )
	{
		$this->appKey = $appKey;
		$this->appSecret = $appSecret;
		$ind= strpos($url,"/",8) ;//目的是截取path
		$this->path=substr($url,$ind);
		$this->host=substr($url,0,$ind);
	}

	public function addTextPara($key, $value)
	{
		$this->querys[$key] = $value;
		return $this;
	}

	/**
	*method=GET请求示例
	*/
    public function get() {
      //  var_dump(__DIR__);exit;
    //	echo ($this->host."\r\n");
    //	echo ($this->path."\r\n");
    	$request= new HttpRequest($this->host,$this->path, HttpMethod::GET, $this->appKey, $this->appSecret);
    	foreach ($this->querys as $itemKey => $itemValue) {
        // o ($itemValue."\r\n");
			 	$request->setQuery($itemKey, $itemValue);
		}

		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$response = HttpClient::execute($request);
		//echo ("server return is:\r\n" );
	//	echo($response->getContent()."\r\n" );

		$result = json_decode($response->getBody()."\r\n");
      //  echo ("current temperatur is:\r\n");
      //  echo($result->showapi_res_body->now->temperature."\r\n" );
        return  $result;
	}

	/**
	*method=POST且是表单提交，请求示例
	*/
	public function post() {
		$request =  new HttpRequest($this->host,$this->path, HttpMethod::POST, $this->appKey, $this->appSecret);
		foreach ($this->querys as $itemKey => $itemValue) {
    			// echo ("aaaaaaaaaaa:\r\n" );
    			// echo ($itemKey."\r\n");
        // o ($itemValue."\r\n");
			 	$request->setQuery($itemKey, $itemValue);
		}

		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_FORM);
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$response = HttpClient::execute($request);
		// echo ("server return is:\r\n" );
		// echo($response->getBody()."\r\n" );

		$result = json_decode($response->getBody()."\r\n");
        // echo ("current temperatur is:\r\n");
        // echo($result->showapi_res_code."\r\n" );
        return  $result;
	}




}
