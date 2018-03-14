<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
	public $token;
	public $stoken;
	public $REALPATH;
	public function _initialize(){
		// $this->();
		$this->assign('PICURL',C('PICURL'));
		$this->REALPATH=explode('/Home/Application/',str_replace('\\','/',realpath(dirname(__FILE__).'/')))[0].'/Home/Web';
		$this->token=session('token');
		$this->stoken=session('stoken');
		// if ("http://".$_SERVER['HTTP_HOST']==C('WEBURL')) {
		// 	header("location:".C('WEBURL').":81");
		// }
		// var_dump($_SESSION);
		$gid=session('GroupId');
		if ($gid=='超级管理组') {
			$headername="Common:default";
		}else{
			$headername="Common:".$gid;
		}
		$this->assign(array('headername'=>$headername));
		if (session('is_login')!=1 || session('userinfo')==false) {
			$this->error('请登录...',U('Public/login'));
		}

	}

	function _empty(){
		header("HTTP/1.0 404 NotFound");
		$this->display('Common:404');
	}


	public $cookie_abcd9_com,$baidu_content;
	public function baidu_post($post_url,$param)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$post_url); //设定远程抓取网址
		curl_setopt($ch, CURLOPT_POST, 1); //设置为POST提交模式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param); //提交参数
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_abcd9_com);
		//把返回的cookie保存到$this->cookie_abcd9_com文件中
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_abcd9_com);
		//读取cookie
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//返回获取的输出文本流，而不自动显示
		$this->baidu_content = curl_exec($ch);
		curl_close($ch);
	}


	public function MSL($tb){
		return M($tb,C('MYSQL')['DB_PREFIX'],'MYSQL');
	}

	public function SH($tb){
		return M($tb,C('SQLHOUSE')['DB_PREFIX'],'SQLHOUSE');
	}





	 public function saveCooies($name,$value,$profix='user')
	 {
	 	$tempValue=cookie($name);
	 	$this->cookies[$name]=$value;
	 	if ($tempValue) {
	 		cookie($profix.'_'.$name,$value);
	 	}
	 	else
	 	{
	 		cookie($name,$value,array('expire'=>86400,'prefix'=>$profix.'_'));
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
		// var_dump($logfile);exit;
		$content='操作定位:'.$controller.'/'.$function.'::::'.$desc.'::::::操作日期:::::::'.date('Y-m-d H:i:s',time()).PHP_EOL;
		file_put_contents($logfile,$content,FILE_APPEND);
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
		return $String;
	}

	/**
	 * 组合优惠匹配方法
	 * $ProIdCards 订单中所有ProIdCards合集、数组
	 * $allDiscount 所有优惠组合信息中的ProIdCards字段值、数组，使用前需反序列化优惠ID为key值
	 */
	public function getDiscount($ProIdCards){
		// var_dump($ProIdCards,$allDiscount);
		$allDiscount=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->order('SDate desc')->getField('GroupId,ProIdCards,CONVERT(varchar(100),SDate,120) as SDate');
		// $allDiscount=unserialize(stripslashes($allDiscount))
		foreach ($allDiscount as $key=>&$discount) {
			$tempPids=array();
			$tempInfos=array();
			// var_dump($discount['ProIdCards']);
			$infos=unserialize(stripslashes($discount['ProIdCards']));
			// var_dump($infos);
			foreach ($infos as $ik => $iv) {
				$tempPids[]=$ik;
				// $tempInfos[]=$iv;
			}
			$discount['ProIdCards']=$tempPids;
			$discount['infos']=$infos;
			$tempY['count']=count($infos);
			$tempY['time']=strtotime($discount['SDate']);
			$tempY['gid']=$key;
			$tempAY[]=$tempY;
		}
		$newDiscount=$this->bubbleSort($tempAY);  //根据组合数量排序->开始时间排序

		// echo "<pre>";
		// var_dump($allDiscount);
		foreach ($newDiscount as $nk => $nv) {
			$newDiscount[$nk]['GroupId']=$allDiscount[$nk]['GroupId'];
			$newDiscount[$nk]['ProIdCards']=$allDiscount[$nk]['ProIdCards'];
			$newDiscount[$nk]['infos']=$allDiscount[$nk]['infos'];
		}
		//拼接排序后数组
		// var_dump($newDiscount);exit();
		$res=false;
		$OrderInfo=array();
		$OrderInfo['discount']=0;
		$OrderInfo['money']=0;
		foreach ($newDiscount as &$discount) {
			// echo "pids";
			// var_dump($discount['count'],$discount['time']);
			// var_dump($discount);
			// echo "<br>";
			// $tempAry=unserialize(stripslashes($discount['ProIdCards']));
			$tempAry=$discount['ProIdCards'];
			// echo "zh";
			// var_dump($tempAry);
			$result=array_intersect($ProIdCards, $tempAry);//获取交集
			// var_dump($result);
			if (count($result)>1) {  //交集数量大于1则匹配到优惠
				// var_dump();
				$tempDis=$discount['infos'];
				// echo "yes";
				$res=true;
				//查询出优惠信息
				foreach ($result as &$pid) {
					$sprice=0;
					$sprice=$tempDis[$pid]['Price'];
					// var_dump($pid);
					// var_dump($tempDis[$pid]);
					$price=M()->table('RS_ProductList')->where("ProIdCard='%s' and token='%s'",array($pid,$this->token))->getField('Price');
					$OrderInfo['goods'][$pid]['Price']=$sprice;
					$OrderInfo['goods'][$pid]['oldPrice']=$price;
					$discount=floatval($price)-floatval($sprice);
					$OrderInfo['goods'][$pid]['discount']=$discount;
					$OrderInfo['discount']+=$discount;
					$OrderInfo['money']+=$sprice;
				}
				$ProIdCards=array_diff($ProIdCards, $result); //删除已匹配的ID
			}
			if (count($ProIdCards)<=1) {
				break;
			}
		}
		if (count($ProIdCards)>0) {
			foreach ($ProIdCards as $proid) {
				//未匹配到组合的商品享受限时特价？使用优惠券？订单慢减？
				$price=M()->table('RS_ProductList')->where("token='%s' and ProIdCard='%s'",array($this->token,$proid))->getField('Price');
				$OrderInfo['goods'][$proid]['Price']=$price;
				$OrderInfo['goods'][$proid]['oldPrice']=$price;
				$OrderInfo['goods'][$proid]['discount']=0;
				$OrderInfo['discount']+=0;
				$OrderInfo['money']+=$price;
			}
		}
		// var_dump($OrderInfo);exit();
		if ($res) {
			return $OrderInfo;
		}else{
			return $res;
		}
	}
	/**
	 * 冒泡排序
	 */
	public function bubbleSort($numbers) {
    $cnt = count($numbers);
    for ($i = 0; $i < $cnt; $i++) {
        for ($j = 0; $j < $cnt - $i - 1; $j++) {
            if ($numbers[$j]['count'] < $numbers[$j + 1]['count']) {
                $temp = $numbers[$j];
                $numbers[$j] = $numbers[$j + 1];
                $numbers[$j + 1] = $temp;
            }elseif ($numbers[$j]['count']==$numbers[$j + 1]['count']) {
            	if ($numbers[$j]['time']<$numbers[$j + 1]['time']) {
	                $temp = $numbers[$j];
	                $numbers[$j] = $numbers[$j + 1];
	                $numbers[$j + 1] = $temp;
            	}
            }
        }
    }
    $newAry=array();
    foreach ($numbers as $va) {
    	$newAry[$va['gid']]=$va;
    }

    return $newAry;
	}



	/**
	 * 获取所有仓库表名
	 */
	public function getAllWare($prefix=''){
		$main=$prefix.'wh'.substr($this->token, -8,8);
		$stores=M()->table('RS_Store')->where("token='%s' and IsCheck='%s' ",array($this->token,'1'))->getField('id',true);
		$tempAry=array();
		foreach ($stores as $st) {
			$tempAry[]=$main.'_'.$st;
		}
		$tempAry[]=$main;
		return $tempAry;
	}

	/**
	 * 发送模板消息
	 */
	public function sendMsg($type,$data){
		$MemberId=M()->table('RS_Order')->where("token='%s' and OrderId='%s'",array($this->token,$data['OrderId']))->getField('MemberId');
		$openid=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$MemberId))->getField('OpenId');
		$storename=$this->MSL('merchant')->where("token='%s'",$this->token)->getField('storeName');
		$access_token=$this->get_access_token();
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
		$msginfo=M()->table('RS_TemplateMsg')->where("token='%s' and TemplateType='%s'",array($this->token,'ordersend'))->find();
		$this->LOGS(json_encode($msginfo));
		$color=$msginfo['Color']?$msginfo['Color']:'#000000';
		$template_id=$msginfo['TemplateId'];
		$MsgData=array();
		$MsgData['touser']=$openid;
		$MsgData['template_id']=$template_id;
		if ($type=='ordersend') {
			$sdata=array();
			$sdata['first']=array('value'=>'您在<'.$storename.'>购买的商品已发货','color'=>$color);
			$sdata['keyword1']=array('value'=>$data['OrderId'],'color'=>$color);
			$sdata['keyword2']=array('value'=>$data['sendname'],'color'=>$color);
			$sdata['keyword3']=array('value'=>$data['sendnumber'],'color'=>$color);
			$sdata['keyword4']=array('value'=>date('Y-m-d H:i:s',time()),'color'=>$color);
			$sdata['remark']=array('value'=>'欢迎再次光临'.$storename,'color'=>$color);
			$MsgData['data']=$sdata;

		}elseif ($type=='payed') {
			//支付消息  
		}elseif ($type=='createorder') {
			//下单通知
		}elseif ($type=='disprice') {
			//补差价通知
			$sdata=array();
			$sdata['first']=array('value'=>'您在<'.$storename.'>的订单需补差价'.$data['money'].'元','color'=>$color);
			$sdata['keyword1']=array('value'=>$data['pronames'],'color'=>$color);
			$sdata['keyword2']=array('value'=>$data['OrderId'],'color'=>$color);
			$sdata['keyword3']=array('value'=>'需补差价'.$data['money'].'元','color'=>$color);
			$sdata['keyword4']=array('value'=>date('Y-m-d H:i:s',time()),'color'=>$color);
			$sdata['remark']=array('value'=>'点击操作','color'=>$color);
			$MsgData['data']=$sdata;
		}
		$this->LOGS(json_encode($MsgData));
		$res=$this->postXmlCurl(json_encode($MsgData),$url);
		$this->LOGS($res['info']);
	}

	/**
	 * 获取微信access_token
	 */
	public function get_access_token(){
		$wxinfo=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find();
		$appid=$wxinfo['appid'];
		$appsecret=$wxinfo['appsecret'];
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$result=json_decode(file_get_contents($url),true);
		return $result['access_token'];
	}

	/**
	 * curl
	 */
	public function postXmlCurl($xml, $url, $useCert = false, $second = 30)
	{		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		curl_setopt($ch,CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		if($useCert == true){
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
		}
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		// var_dump($data);exit();
		if($data){
			curl_close($ch);
			$res['status']=true;
			$res['info']=$data;
			// $this->LOGS($data);
			return $res;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			$res['status']=false;
			$res['info']="curl出错，错误码:$error";
			$this->LOGS(json_encode($res));
			return $res;
		}
	}



	//短信发送-基于腾信公司接口
	public function SendMessage($messageData)
	{

		$timeStamp=date('Y-m-d H:i:s',time());

		$SMData=array(
			'username'=>C('SMS_USERNAME'),
			'password'=>md5(C('SMS_PASSWORD').$timeStamp),
			'mobiles'=>$messageData['mobiles'],
			'content'=>C('SMS_SIGN').$messageData['content'],
			'f'=>'1',
			'timestamp'=>$timeStamp,
		);

		$SendURL=C('SMS_SENDURL').http_build_query($SMData);
		// file_put_contents('temp.json', $SendURL,FILE_APPEND);
		$res=$this->httpGet($SendURL);
		$this->LOGS($SendURL.'---结果'.$res);
		// file_put_contents('temp.json', $res,FILE_APPEND);
		$resArray=json_decode($res,true);
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
		//$res=curl_errno($curl);
		curl_close($curl);

		return $res;
	}



}


 ?>
