<?php 
/**
* 微信模板消息发送
*/
class WXTemplate
{
	
	public $appid;
	public $appsecert;
	public $apiurl;
	function __construct($Param)
	{
		//验证微信参数
		if (empty($Param['appid']) || empty($Param['appsecert'])) {
			return 'error:noParam!';
		}else{
			$this->appid=$Param['appid'];
			$this->appsecert=$Param['appsecert'];
		}
		$this->apiurl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";
	}

	public function sendTemplate($data){
		$cing=$this->checkParam($data);
		if ($cing['status']=='success') {
			$Template=array();
			$TemplateData=array();
			$Template['touser']=$data['touser'];
			$Template['template_id']=$data['template_id'];
			if ($data['url']) {
				$Template['url']=$data['url'];
			}
			$content=$data['content'];
			$TemplateData['first']=$data['first'];
			$TemplateData['remark']=$data['remark'];
			$i=1;
			foreach ($content as $con) {
				$TemplateData['keyword'.$i]=$con;
				$i++;
			}
			$Template['data']=$TemplateData;
			$access_token=$this->access_token();
			$url=$this->apiurl.$access_token;
			$res=$this->PostCurl(json_encode($Template),$url);
			return $res;
		}else{
			return $cing['info'];
		}
	}

	/**
	 * 验证参数
	 */
	private function checkParam($data){
		if ($this->appsecert && $this->appid) {
			if (empty($data['touser']) || empty($data['template_id']) || empty($data['first']) || empty($data['remark']) || empty($data['content']) || count($data['content'])<=0) {
				$lostParam='';
				if (empty($data['touser'])) {
					$lostParam.='、touser';
				}
				if (empty($data['template_id'])) {
					$lostParam.='、template_id';
				}
				if (empty($data['first'])) {
					$lostParam.='、first';
				}
				if (empty($data['remark'])) {
					$lostParam.='、remark';
				}
				if (empty($data['content'])) {
					$lostParam.='、content';
				}
				$msg['status']='error';
				$msg['info']='缺少消息参数('.$lostParam.')';
			}else{
				$msg['status']='success';
			}
		}else{
			$msg['status']='error';
			$msg['info']='缺少必要参数(appid,appsecert)';
		}
		return $msg;
	}

	/**
	 * 获取access_token
	 */
	private function access_token(){
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecert;
	    $data=file_get_contents($url);
	    $content=json_decode($data,true);
	    $token=$content['access_token'];
	    return $token;
	}




	private function PostCurl($xml, $url, $second = 30)
    {       
        // var_dump($xml,$url);exit();
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        //如果有配置代理这里就设置代理
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        // var_dump($data);exit();
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            if ($error!='0') {
	            $res['status']=false;
	            $res['info']="curl出错，错误码:$error";
            }else{
            	$res='通讯成功，对方无响应';
            }
            return $res;
        }
    }
}





 ?>