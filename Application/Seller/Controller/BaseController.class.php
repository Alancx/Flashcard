<?php
namespace Seller\Controller;
use Think\Controller;
use Org\WeChar\Wx_Api;

header('content-type:text/html;charset=utf-8');
class BaseController extends Controller{
 	function _empty(){
		header("HTTP/1.0 404 NotFound");
		$this->display('Common:404');
	}

	public function checkStore(){
		exit();
		$id=$_GET['id'];
		if ($_GET['code']) {
			$code=$_GET['code'];
			// header("loaction:https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8d5a5c2f1538e6c0&secret=99f16188336fc16d7df795893e175576&code=".$code."&grant_type=authorization_code");
			$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
			$uinfo=json_decode($resinfo,true);
			$token=$this->get_access_token();
			$user=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$uinfo['openid']."&lang=zh_CN");
			$user=json_decode($user,true);
			echo "你好 <span style='color:red'><b>".$user['nickname']."</b></span> 我拿到了你的个人信息 <img src='".$user['headimgurl']."'>";

		}else{
			$redirect_uri=C('ADMINURL').U('Base/checkStore').'?id='.$id;
			header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
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
	 * 同兴支付通知信息
	 */
	public function Invonotify(){
		$data=$_POST;
		$this->LOGS('交易信息--->>>'.json_encode($_POST));
		if ($data['dealCode']=='10000') {
			$cid=$data['ext1'];
			$update=array();
			$update['IsPay']='1';
			$update['OrderId']=$data['orderNo'];
			$update['txOrderNo']=$data['txOrderNo'];
			$update['bankOrderNo']=$data['bankOrderNo']?$data['bankOrderNo']:'';
			$update['LastUpdateDate']=date('Y-m-d H:i:s',time());
			$update['Status']='2';
			if (M()->table('RS_ProductInWarehouse')->where("InWarehouseId='%s' and Status='-1'",$cid)->save($update)) {
				$param=array();
				$param['merchantNo']='TX0001455';
				$param['dealResult']='SUCCESS';
				$sign=$this->lvs_sign($param);
				$param['sign']=$sign;
				$url='http://api.tongxingpay.com/txpayApi/offLine';
				$this->LOGS('反馈同兴信息--->>'.json_encode($param));
				$this->postXmlCurl($param,$url);
			}else{
				$this->LOGS(M()->getlastsql());
			}
		}
	}


	/**
     * 签名
     */
    private function lvs_sign($data){
        ksort($data);
        $str=urldecode(http_build_query($data));
        $str=$str.'d80efe63192b4b7ebf9a30d78075b8fa';
        // var_dump($str);exit();
        // $this->LOGS($str);
        $sign=strtoupper(md5($str));
        return $sign;
    }








    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    public function postXmlCurl($xml, $url, $second = 30)
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
            $this->LOGS($data);
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

    public function addCancel(){
    	$param=$_GET;
    	$this->assign($param);
    	$this->display();
   //  	if ($_GET['code']) {
   //  		$code=$_GET['code'];
   //  		$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
   //  		$uinfo=json_decode($resinfo,true);
   //  		$token=$this->get_access_token();
			// $user=json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$uinfo['openid']."&lang=zh_CN"),true);
			// if (M()->table('RS_Cancel')->where("openid='%s' and stoken='%s'",$uinfo['openid'],$_GET['stoken'])->find()) {
			// 	$this->show("<h1 style='text-align:center;font-size:100px;color:#FF0000;'>请勿重复绑定</h1>");exit();
			// }
			// $pagedata['openid']=$uinfo['openid'];
			// $pagedata['nickname']=$user['nickname'];
			// $info=M()->table('RS_Store')->where("stoken='%s'",$_GET['stoken'])->field("CONVERT(varchar(20),verify_time,120) as verify_time,verify")->find();
			// if (intval(time())>strtotime($info['verify_time'])) {
			// 	$this->show("<h1 style='text-align:center;font-size:100px;color:#FF0000;'>验证码已过期，请重试</h1>");exit();
			// }else{
			// 	$pagedata['verify']=$info['verify'];
			// }
			// $pagedata['stoken']=$_GET['stoken'];
			// $this->assign($pagedata);
			// // echo "<pre>";
			// // var_dump($pagedata);exit();

			// $this->display();
   //  	}else{
   //  		$redirect_uri=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			// header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
   //  	}
    }

    public function saveCancel(){
    	$param=$_POST;
    	if (M()->table('RS_Cancel')->where("openid='%s' and stoken='%s'",array($param['openid'],$param['stoken']))->find()) {
    		$msg['status']='error';
    		$msg['info']='请勿重复绑定';
    	}else{
	    	if (M()->table('RS_Cancel')->add($param)) {
	    		$msg['status']='success';
	    	}else{
	    		$msg['status']='error';
	    		$msg['info']='处理失败';
	    	}
    	}
    	$this->ajaxReturn($msg);
    }

    public function cancelorder(){
			// var_dump($_GET['oid']);exit();
    	$cid=$_GET['cid'];//核销员记录ID
    	$oid=$_GET['oid'];
			$sqlStr="SELECT * FROM RS_OrderList WHERE OrderId='".$oid."'";
    	$olist=M()->query($sqlStr);
			$sqlStr="SELECT * FROM RS_Order WHERE OrderId='".$oid."'";
			$oinfo = M()->query($sqlStr);
			// var_dump(M()->getlastsql());exit();
    	$oinfo=$oinfo[0];
    	foreach ($olist as $key => $value) {
    		$oinfo['sons'][]=$value;
    	}
    	$pagedata['minfo']=M()->table('RS_Member')->where("MemberId='%s'",$oinfo['MemberId'])->find();
    	$pagedata['oinfo']=$oinfo;
    	$this->assign($pagedata);
    	$this->display();
    }

    public function getwxparam(){
			// var_dump($_GET);exit();
    	if ($_GET['code']) {
    		$code=$_GET['code'];
    		$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
    		$uinfo=json_decode($resinfo,true);
    		$token=$this->get_access_token();
			$user=json_decode(file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$uinfo['access_token']."&openid=".$uinfo['openid']."&lang=zh_CN"),true);
			if ($_GET['type']=='addCancel') {
				if (M()->table('RS_Cancel')->where("openid='%s' and stoken='%s'",$uinfo['openid'],$_GET['stoken'])->find()) {
					$this->assign('info','请勿重复绑定')->display('error');exit();
				}
				$pagedata['openid']=$uinfo['openid'];
				$pagedata['nickname']=$user['nickname'];
				$info=M()->table('RS_Store')->where("stoken='%s'",$_GET['stoken'])->field("CONVERT(varchar(20),verify_time,120) as verify_time,verify")->find();
				if (intval(time())>strtotime($info['verify_time'])) {
					$this->assign('info','验证码已过期，请重试')->display('error');exit();
					// $this->show("<h1 style='text-align:center;font-size:100px;color:#FF0000;'>验证码已过期，请重试</h1>");exit();
				}else{
					$pagedata['verify']=$info['verify'];
				}
				$pagedata['stoken']=$_GET['stoken'];
				//传值跳转；
				$this->redirect(str_replace('.html', '', "/Seller/Base/addCancel?".http_build_query($pagedata)));
			}elseif ($_GET['type']=='cancelorder') {
				// var_dump($_GET['oid']);exit();
				if ($cinfo=M()->table('RS_Cancel')->where("stoken='%s' and openid='%s'",array($_GET['stoken'],$uinfo['openid']))->find()) {
					//查询订单信息并跳转
					$this->redirect('Seller/Base/cancelorder?cid='.$cinfo['id'].'&oid='.$_GET['oid']);
				}else{
					// $this->show("<h1 style='text-align:center;font-size:100px;color:#FF0000;'>请绑定核销员后操作!</h1>");
					$this->assign('info','请绑定核销员后操作！')->display('error');exit();
				}
			}
			// $this->assign($pagedata);
      //
			// $this->display();
    	}else{
    		$redirect_uri=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
    	}
    }

		public function setorderstatus(){
			if(IS_POST){
				$oid = $_POST['oid'];
				$oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
				// $olinfo = M()->table('RS_OrderList')->where(array('OrderId'=>$oid))->select();
        $olinfo = M()->query("SELECT distinct ProId, SUM(Count) as Count FROM RS_OrderList WHERE OrderId ='".$oid."' GROUP BY ProId");
				$res= true;
				$ref= true;

				$rew= true;

				M()->startTrans();
				$res = M()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('Status'=>'4'));
				foreach ($olinfo as $key => $value) {
					M()->table('RS_Product')->where(array('ProId'=>$value['ProId']))->setInc('SalesCount',$value['Count']);
					$pem = M()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
					if ($pem) {
						$red = true;
						$mpem = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->find();
						if ($mpem) {
							if ($mpem['Level'] >=$pem['Level'] ) {
								$red = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
							} else {
								$red=M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->setInc('Level',1);
							}
						} else {
							$red = M()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
						}
						if ($red === false) {
							$ref = false;
							break;
						}
					}
				}

				if (!empty($oinfo['SceneMember'])) {
					foreach ($olinfo as $key => $value) {
						$pem = M()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
						if ($pem) {
							$red = true;
							$mpem = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->find();
							if ($mpem) {
								if ($mpem['Level'] >=$pem['Level'] ) {
									$red = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
								} else {
									$red=M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->setInc('Level',1);
								}
							} else {
								$red = M()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
							}
							if ($red === false) {
								$rew = false;
								break;
							}
						}
					}
				}
				if ($res && $ref && $rew) {
					M()->commit();
					// M()->rollback();
					$this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
				} else {
					M()->rollback();
					$this->ajaxReturn(array('status'=>'false','info'=>'updateError'),'JSON');
				}
			} else {
				$this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
			}
		}














public function setendorder(){
  if(IS_POST){
    $oid = $_POST['oid'];
    $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
    $olinfo = M()->query("SELECT distinct ProId, SUM(Count) as Count FROM RS_OrderList WHERE OrderId ='".$oid."' GROUP BY ProId");
    $res= true;
    $ref= true;
    $rew= true;
    M()->startTrans();
    $now=date('Y-m-d H:i:s',time());
    $res = M()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('Status'=>'4','GetDate'=>$now));
    // foreach ($olinfo as $key => $value) {
    //   M()->table('RS_Product')->where(array('ProId'=>$value['ProId']))->setInc('SalesCount',$value['Count']);
    //   $pem = M()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
    //   if ($pem) {
    //     $red = true;
    //     $mpem = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->find();
    //     if ($mpem) {
    //       if ($mpem['Level'] >=$pem['Level'] ) {
    //         $red = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
    //       } else {
    //         $red=M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->setInc('Level',1);
    //       }
    //     } else {
    //       $red = M()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
    //     }
    //     if ($red === false) {
    //       $ref = false;
    //       break;
    //     }
    //   }
    // }

    // if (!empty($oinfo['SceneMember'])) {
    //   foreach ($olinfo as $key => $value) {
    //     $pem = M()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
    //     if ($pem) {
    //       $red = true;
    //       $mpem = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->find();
    //       if ($mpem) {
    //         if ($mpem['Level'] >=$pem['Level'] ) {
    //           $red = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
    //         } else {
    //           $red=M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->setInc('Level',1);
    //         }
    //       } else {
    //         $red = M()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
    //       }
    //       if ($red === false) {
    //         $rew = false;
    //         break;
    //       }
    //     }
    //   }
    // }
    if ($res && $ref && $rew) {
      M()->commit();
      $this->sendendwxmesg($oid);
      $this->ajaxReturn(array('status'=>'success'),'JSON');
    } else {
      M()->rollback();
      $this->ajaxReturn(array('status'=>'error','info'=>'处理失败'),'JSON');
    }
  } else {
    $this->ajaxReturn(array('status'=>'error','info'=>'非法请求'),'JSON');
  }
}

// 订单完成发送微信消息
public function sendendwxmesg($oid){
  $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
  $minfo=M()->table('RS_Member')->where(array('MemberId'=>$oinfo['MemberId']))->find();
  $olinfo = M()->table('RS_OrderList')->where(array('OrderId'=>$oid))->select();
  $pnameinfo='';
  foreach ($olinfo as $key => $value) {
    if ($pnameinfo =='') {
      $pnameinfo = $value['ProName'].'('.$value['Spec'].')';
    } else {
      $pnameinfo = $pnameinfo.';'.$value['ProName'].'('.$value['Spec'].')';
    }
  }
  if (!empty($minfo['OpenId'])) {
    $smInfo=array(
        'touser'=>$minfo['OpenId'], //必填
        'template_id'=>'-CZ3fb1hys-E3zHvjF88XiRmSW8MowBM-wXJKKIHmQU', //必填
        'first'=>array('value'=>'订单完成通知',color=>'#000000'), //必填
        'content'=>array(
          0=>array('value'=>date("Y-m-d H:i:s",time()),'color'=>'#000000'),
          1=>array('value'=>$pnameinfo,'color'=>'#000000'),
          2=>array('value'=>$oinfo['ShortOid'],'color'=>'#000000'),
        ),  //必填
        'remark'=>array('value'=>'欢迎下次光临!','color'=>'#000000'),
      );

    $this->sendWxMessage($smInfo);
  }
}

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


	public function WXinfo() {

	    $userinfo = $this->getUserData();
	    $gzxx = $userinfo['subscribe'];
	    // var_dump($gzxx);die();
	    if($gzxx == '1'){
	    	$stoken = $_GET['stoken'];
	    	$data = array('MsgRecever'=>$userinfo['openid'],'MsgReceverName'=>$userinfo['nickname']);
	    	$res = M()->table('RS_Store')->where("stoken='%s'",$stoken)->setField($data);
	    	if($res) {
	    		// $this->success(‘执行成功);
	    		$this->display('Base/subscribe');
	    	}


	    }else{
	    	//如果有配置代理这里就设置代理果未关注,则通过二维码关注
	 		$this->display();
	 	}
	}
	/*
  	*获取用户信息
  	*/
	public function getUserData() {
		$wxParam = array('wx_appid'=>trim(C('WXAPPID')),'wx_appsecret'=>trim(C('WXAPPSECRET')),'site_url'=>'http://'.$_SERVER['HTTP_HOST'].U($Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME,$_GET));
		$userinfo = Wx_Api::getOpenId($wxParam);
		return $userinfo;

	}


}


 ?>
