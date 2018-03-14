<?php
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class BaseController extends Controller{

	function _empty(){
		header("HTTP/1.0 404 NotFound");
		$this->display('Common:404');
	}

	public function checkStore(){
		// exit();
		$id=$_GET['id'];
		if ($_GET['code']) {
			$code=$_GET['code'];
			// header("loaction:https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8d5a5c2f1538e6c0&secret=99f16188336fc16d7df795893e175576&code=".$code."&grant_type=authorization_code");
			$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
			$uinfo=json_decode($resinfo,true);
			if (M()->table("RS_Store")->where("ID=%d",$id)->setField("openid",$uinfo['openid'])) {
				echo "<script>alert('处理成功');window.location.href='http://".$_SERVER['HTTP_HOST']."'</script>";
			}
			// $token=$this->get_access_token();
			// $user=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$uinfo['openid']."&lang=zh_CN");
			// $user=json_decode($user,true);
			// echo "你好 <span style='color:red'><b>".$user['nickname']."</b></span> 我拿到了你的个人信息 <img src='".$user['headimgurl']."'>";

		}else{
			$redirect_uri='http://'.$_SERVER['HTTP_HOST'].U('Base/checkStore').'?id='.$id;
			header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
		}


	}

	public function addCancel(){
		if ($_GET['code']) {
			$code=$_GET['code'];
			$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
			$uinfo=json_decode($resinfo,true);
			$pagedata['openid']=$uinfo['openid'];
			$stoken=$_GET['stoken'];
			$store=M()->table("RS_Store")->where("stoken='%s'",$stoken)->find();
			$type=$_GET['type'];
			$pagedata['type']=$type;
			$pagedata['stoken']=$stoken;
			$pagedata['token']=$store['token'];
			$pagedata['verify']=$store['verify'];
			$pagedata['storeid']=$store['id'];
			$this->assign($pagedata);
			$this->display();
		}else{
			$redirect_uri='http://'.$_SERVER['HTTP_HOST'].U('Base/addCancel').'?stoken='.$_GET['stoken'].'&type='.$_GET['type'];
			header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
		}


	}
	public function saveCancel(){
		// var_dump($_POST);
		if ($_POST['verify']!=$_POST['vy']) {
			$this->error('验证码错误');
		}
		$DB['openid']=$_POST['openid'];
		$DB['storeid']=$_POST['storeid'];
		$DB['token']=$_POST['token'];
		$DB['stoken']=$_POST['stoken'];
		$DB['username']=$_POST['username'];
		if (M()->table('RS_Cancel')->where("openid='%s'",$_POST['openid'])->getField('type')) {
			$this->error('你已绑定核销员，无法重复绑定');
		}else{
			if ($_POST['type']=='1') {
				$DB['type']=json_encode(array('XJ'));
			}else{
				$DB['type']=json_encode(array('TH'));
			}
		}
		if (M()->table('RS_Cancel')->add($DB)) {
			$this->success('绑定成功');
		}else{
			$this->error('绑定失败');
		}
	}

	public function get_access_token(){
	    $data=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET'));
	    $content=json_decode($data,true);
	    $token=$content['access_token'];
	    return $token;
	}

	public function MSL($tb){
		return M($tb,C('MYSQL')['DB_PREFIX'],'MYSQL');
	}

	public function SRV($tb){
		return false;
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

	//短信发送-基于腾信公司接口
	public function SendMessage($messageData)
	{

		$timeStamp=date('Y-m-d H:i:s',time());

		$SMData=array(
			'username'=>C('SMS_USERNAME'),
			'password'=>md5(C('SMS_PASSWORD').$timeStamp),
			'mobiles'=>$messageData['mobiles'],
			'content'=>$messageData['content'],
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

	/**
	 * 收款码
	 */
	public function showCashCode(){

		if ($_GET['code'] || session('cashtoken') || $_GET['openid']) {
			$code=$_GET['code'];
			$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
			$uinfo=json_decode($resinfo,true);
			if (session('cashtoken')) {
				$openid=session('cashtoken');
			}elseif ($_GET['openid']) {
				$openid=$_GET['openid'];
			}else{
				$openid=$uinfo['openid'];
			}
			if (M()->table('RS_Store')->where("openid='%s' and IsCheck='1'",$openid)->find()) {
				$pagedata['openid']=$openid;
				$this->assign($pagedata);
				session('cashtoken',$openid);
				$this->display('CashCode');
			}else{
				echo "<script>alert('未找到您的店铺信息');window.location.href='https://".$_SERVER['HTTP_HOST']."'</script>";
				// $this->error('未找到店铺信息！',$_SERVER['HTTP_HOST']);
			}
		}else{
			$redirect_uri='http://'.$_SERVER['HTTP_HOST'].U('Base/showCashCode');
			header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
		}

	}

	/**
	 * 收款吗
	 */
	public function showCashCodes(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$openid=$_GET['openid'];
		$qr=M()->table('RS_Store')->where("openid='%s' and IsCheck='1'",$openid)->getField('CashCode');
		if ($qr) {

		}else{
			$qr=md5(uniqid());
			M()->table('RS_Store')->where("openid='%s' and IsCheck='1'",$openid)->setField('CashCode',$qr);
		}
		$url='GETCASH'.$qr;
		$level="L";
		// $filename='./Uploads/1.png';
		$size=4;
		\QRcode::png($url,false,$level,$size,'2');

	}


	/**
	 * 用户操作记录log
	 */
	public function LOGS($desc='',$sp=false,$controller=CONTROLLER_NAME,$function=ACTION_NAME){
		$filename=date('Y-m-d',time());
		$logfile=str_replace('\\','/',strrev(substr(strrev(dirname(__FILE__)),10))).'logs/';
		if (!is_dir($logfile)) {
			mkdir($logfile,777);
		}
		if ($sp==true) {
			$logfile=$logfile.$filename.'.log';
		}else{
			$logfile=$logfile.$filename.'.txt';
		}
		// var_dump($logfile);exit;
		$content='操作定位:'.$controller.'/'.$function.'::::'.$desc.'::::::操作日期:::::::'.date('Y-m-d H:i:s',time()).PHP_EOL;
		file_put_contents($logfile,$content,FILE_APPEND);
	}

	/**
	 * 抢单
	 */
	public function getordersoon(){
		$openid=$_GET['openid'];
		$oid=$_GET['oid'];
		$oinfo=M()->table('RS_Order')->where("OrderId='%s'",$oid)->field("OrderId,RecevingName,RecevingPhone,RecevingProvince+RecevingCity+RecevingArea+RecevingAddress as Addr,OpenId,stoken,token,Freight,TruepsMoney")->find();
		$sinfo=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($oinfo['token'],$oinfo['stoken']))->find();
		$sms_pre='【'.$sinfo['storename'].'】';
		$type='ding';
		if ($goinfo=M()->table('RS_DistributionForOrder')->where("OrderId='%s' and IsDelete='0'",$oid)->field("CONVERT(varchar(20),GetDate,120) as GetDate,OpenId,IsSuccess,Status,IsDelete")->find()) {
			//判断是否完成抢单
			// var_dump($goinfo,$openid);exit();
			if ($openid==$goinfo['OpenId']) {
				// $pagedata['goinfo']=$goinfo;
				if ($goinfo['IsSuccess'] && $goinfo['Status']=='2') {
					$type='end';
				}elseif ($goinfo['Status']=='1') {
					$type='geted';
				}elseif ($goinfo['Status']=='0') {
					$type='geting';
				}
			}else{
				$type='fail';
			}
		}else{
			if ($ops=M()->table('RS_DistributionForOrder')->where("OrderId='%s' and IsDelete='1'",$oid)->getField("OpenId",true)) {
				//判断是否在已删除订单里
				foreach ($ops as $os) {
					if ($openid==$os) {
						$type='fail';
						break;
					}
				}
			}
			if ($type=='ding') {
				//抢到
				$verify=substr(str_shuffle('0123456789'), mt_rand(0,5),4);
				$ODB['OrderId']=$oid;
				$ODB['OpenId']=$openid;
				$ODB['GetDate']=date('Y-m-d H:i:s',time());
				$MemberId=M()->table('RS_Member')->where("OpenId='%s'",$openid)->getField('MemberId');
				//配送费
				$ODB['MemberId']=$MemberId;
				// $ODB['PsGet']=$oinfo['Freight'];
				$ODB['PsGet']=$oinfo['TruepsMoney'];
				$ODB['token']=$oinfo['token'];
				$ODB['stoken']=$oinfo['stoken'];
				$ODB['Verify']=$verify;
				if (M()->table('RS_DistributionForOrder')->add($ODB)) {
					$oson=M()->query("SELECT p.ProName+'_'+pl.ProSpec1 as ProName,ol.Count FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId=p.ProId LEFT JOIN RS_ProductList pl ON ol.ProIdCard=pl.ProIdCard WHERE ol.OrderId='{$oid}'");
					$proinfo='';
					foreach ($oson as $son) {
						$proinfo.="===".$son['ProName']."*".$son['Count']."===";
					}
					$type='success';
					$info=M()->table('RS_Distribution')->where("OpenId='%s'",$openid)->find();
					$data['mobiles']=M()->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->getField("Phone");
					$data['content']=$sms_pre.date('Y-m-d H:i:00',time()) .'：您的订单已被'.$info['TrueName'].'抢单，联系电话：'.$info['Phone'].'，验证码：'.$verify.'，包裹将很快送达至您的手中';
					$Sdata['mobiles']=M()->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->getField("tel");
					$Sdata['content']=$sms_pre.'配送员：'.$info['TrueName'].'/'.$info['Phone'].'已抢单,订单号：'.$oid."[".$proinfo."],请及时配货";
					$this->SendMessage($data);
					$this->SendMessage($Sdata);
				}else{
					$type='fail';  //抢单失败
				}
			}
		}
		$pagedata['type']=$type;
		$pagedata['oinfo']=$oinfo;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 完成配送
	 */
	public function getover(){
		$oid=$_GET['oid'];
		$data['IsSuccess']='1';
		$data['OverDate']=date('Y-m-d H:i:s',time());
		$data['Status']='2';
		$oinfo=M()->table("RS_Order")->where("OrderId='%s'",$oid)->find();
		$sinfo=M()->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->find();
		$minfo=M()->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->find();
		$verify=$_GET['verify'];
		$tverify=M()->table('RS_DistributionForOrder')->where("OrderId='%s' and IsDelete='0'",$oid)->getField('Verify');
		if ($verify==$tverify && $oinfo['Status']=='2') {
			M()->startTrans();
			$ds=$ores=$mres=true;
			$ds=M()->table('RS_DistributionForOrder')->where("OrderId='%s' and IsDelete='0'",$oid)->setField($data);
			$ores=M()->table('RS_Order')->where("OrderId='%s'",$oid)->setField(array('Status'=>4,'GetDate'=>date('Y-m-d H:i:s',time())));
			$mres=M()->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->setField("LastBuyTime",date('Y-m-d H:i:s',time()));
			$nowTime = date("Y-m-d H:i:s", time());
	        // $ProIdCards=M()->table('RS_OrderList')->where("OrderId='%s'",$order_no)->getField('ProIdCard',true);
	        // $Counts=M()->table('RS_OrderList')->where("OrderId='%s'",$order_no)->getField('Count',true);

	        // $sid=M()->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->getField('id');
	        $sid=$sinfo['id'];
	        $tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8).'_'.$sid;
	        $whres=true;
	        // $whres=M()->execute("UPDATE wh SET StockCount=StockCount-ol.Count,SalesCount=SalesCount+ol.Count,LastUpdateDate=GetDate() FROM {$tb_name} wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}'");
	        // $this->LOGS(M()->getlastsql());

	                // 更新商品销量 和 数量字段
	        $uppro=M()->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),SalesCount=SalesCount+(SELECT SUM(Count) FROM RS_OrderList WHERE OrderId=('".$oid."') AND a.ProId=RS_OrderList.ProId) FROM RS_Product a, RS_OrderList b WHERE b.ProId=a.ProId  AND b.OrderId=('".$oid."')");
	                // 更新订单表状态
			if ($ds && $ores && $mres && $whres && $uppro) {
				M()->commit();
				$msg['mobiles']=$minfo['Phone'];
				$msg['content']='【'.$sinfo['storename'].'】您的订单：'.$oid.'已完成配送并确认收货';
				$msg1['mobiles']=$sinfo['tel'];
				$msg1['content']='【'.$sinfo['storename'].'】您的订单：'.$oid.'已完成配送并确认收货';
				$this->SendMessage($msg);
				$this->SendMessage($msg1);
				$this->success('处理成功');
			}else{
				$this->LOGS('配送处理--->>>ds'.$ds.'__ores'.$ores.'__mres'.$mres.'__whres'.$whres.'__uppro'.$uppro);
				M()->rollback();
				$this->error('处理失败');
			}
		}else{
			$this->error('验证码错误');
		}
	}


	/**
	 * 代付接口通知信息
	 */
	public function bank_notify(){
		$this->LOGS("代付接口通知信息--->>>".json_encode($_POST),true);
	}

	/**
	 * @DateTime    2017-12-16
	 * @description 推广登陆
	 */
	public function tuierlogin(){
		if (IS_POST) {
			if ($uinfo=M()->table("RS_Tuier")->where("Account='%s' and Password='%s'",array($_POST['username'],md5($_POST['password'])))->find()) {
				if ($uinfo['IsCheck']=='1') {
					$u=array();
					$u['id']=$uinfo['ID'];
					$u['name']=$uinfo['Account'];
					$u['Invcode']=$uinfo['Invcode'];
					$u['Invcoded']=$uinfo['Invcoded'];
					$u['Header']=$uinfo['HeadImgUrl'];
					$u['realname']=$uinfo['TrueName'];
					session('uinfo',$u);
					session('login',true);
					$this->success('登陆成功',U('Tuier/index'));
				}else{
					$this->error('账号未审核');
				}
			}else{
				$this->error('账号或密码错误');
			}
		}else{
			if (session('login') && session('uinfo')) {
				$this->success('登陆成功',U('Tuier/index'));
			}else {
				$this->assign($pagedata);
				$this->display('login');
			}
		}
	}

	/**
	 * @DateTime    2017-12-16
	 * @description 退出登录
	 */
	public function tuierout(){
		session('login',false);
		session('uinfo',false);
		$this->success('已退出',U('Base/tuierlogin'));
	}


}


 ?>
