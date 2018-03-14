<?php
namespace Admin\Controller;
use Think\Controller;
/**
*
*/
class WriteinfoController extends BaseController
{
	public $token='rhbnja145862596121';
	function _initialize()
	{

	}


	public function writepeople(){
		if (IS_POST) {
			$data['TrueName']=$_POST['TrueName'];
			$data['Password']=md5($_POST['Password']);
			$data['Invcode']=strtoupper(substr(md5(uniqid('INV')), -20,20));
			$data['Invcoded']=$_POST['Invcode'];
			if (M()->table('RS_Tuier')->where("Account='%s'",$_POST['userName'])->find()) {
				// if (M()->table('RS_Tuier')->where("Account='%s'",$_POST['userName'])->save($data)) {
				// 	$this->success('注册成功',U('Base/tuierlogin'));
				// }else{
				// 	$this->error('注册失败');
				// }
				$this->error('账号已存在');
			}else{
				$data['CreateDate']=time();
				$data['Account']=$_POST['userName'];
				if (M()->table('RS_Tuier')->add($data)) {
					$this->success('注册成功',U('Base/tuierlogin'));
				}else{
					$this->error('注册失败');
				}
			}
		}else{
			$pagedata['Invcode']=$_GET['Invcode'];
			$this->assign($pagedata);
			$this->display();
		}
	}



	public function writestore(){
		if (IS_POST) {
			$tel = $_POST['tel'];
			$red = M()->table('RS_Store')->where(array('tel'=>$tel))->find();
			if ($red) {
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'phoneError'), 'JSON');
				exit();
			} else {
				$savedata=array(
					'TrueName'=>$_POST['truename'],
					'tel'=>$_POST['tel'],
					'storename'=>$_POST['shopname'],
					'province'=>$_POST['province'],
					'city'=>$_POST['city'],
					'area'=>$_POST['area'],
					'addr'=>$_POST['addr'],
					'CreateDate'=>date('Y-m-d H:i:s', time()),
					'IdCard'=>$_POST['idcardno'],
					'IdInfo'=>$_POST['idInfo'],
					'IsCheck'=>'0',
					'lang'=>$_POST['lat'],
					'lat'=>$_POST['lang'],
					'slang'=>$_POST['lat'],
					'slang'=>$_POST['lat'],
					'Invcode'=>$_POST['Invcode'],
					'openid'=>$_POST['openid'],
					'MsgRecever'=>$_POST['openid'],
					'MsgReceverName'=>$_POST['nickname'],
					'token' =>$this->token,
				);

				$res = M()->table('RS_Store')->add($savedata);
				if ($res) {
					$data=array('statu'=>'1','id'=>$res);
					$ref = $this->checking($data);
					if($ref=='true'){
						$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
					}else{
						$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError2'), 'JSON');
					}
				} else {
					$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError'), 'JSON');
				}
			}
		}else{

			$Invcode= $_GET['Invcode'];
			$pagedata['openid']=$_GET['openid'];
			$pagedata['nickname']=$_GET['nickname'];
			$pagedata['subscribe'] =$_GET['subscribe'];
			$pagedata['Invcode']=$Invcode;
			$this->assign($pagedata);
			$this->display();
		}
	}

	////////////////////上传身份证件/////////////
	public function useridcard(){
		//var_dump($_FILES['iptxtp']);
		if (IS_POST) {
			$upload=new \Think\Upload();
			$upload->maxSize=3145728;
			$upload->savePath='./userIdCard/';
			$upload->exts=array('jpg','png','jpeg');
			$info=$upload->uploadOne($_FILES['iptxtp']);
			if (!$info) {
				$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
			}else{
				$ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
				$this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
			}
		}
	}


	public function checking($data){
		$tempData=$data;
		if ($tempData['statu']=='2') {

		}elseif ($tempData['statu']=='1') {
			$sinfo=M()->table('RS_Store')->where("token='%s' and id=%d",array($this->token,$tempData['id']))->find();
			// if ($sinfo['openid']) {
			// 	$minfo=M()->table('RS_Member')->where("token='%s' and OpenId='%s'",array($this->token,$sinfo['openid']))->find();
			// }

			//添加仓库
			$stoken=$this->getStr(5,4);

			M()->startTrans();
			$this->MSL()->startTrans();
			//添加用户
			$member=true;
			// if (!$minfo['OpenId'] && $sinfo['openid'] ) {
			// 		$MDB['MemberId']=$sinfo['tel'];
			// 		$MDB['OpenId']=$sinfo['openid'];
			// 		$MDB['MemberPwd']=md5(substr($sinfo['tel'], 7,4));
			// 		$MDB['MemberName']=$sinfo['TrueName'];
			// 		$MDB['token']=$this->token;
			// 		$MDB['stoken']=$stoken;
			// 		$member=M()->table('RS_Member')->add($MDB);
			// 	}else{
			// 		if ($sinfo['openid']) {
			// 			$member=M()->table('RS_Member')->where("token='%s' and OpenId='%s'",array($this->token,$sinfo['openid']))->setField('stoken',$stoken);
			// 		}
			// 	}
			$tempUs['userName']=$sinfo['tel'];
			$tempUs['Password']=md5(substr($sinfo['tel'], -4,4));
			$tempUs['token']=$this->token;
			$tempUs['stoken']=$stoken;
			$tempUs['TrueName']=$sinfo['TrueName'];
			$tempUs['IsLogin']=1;
			$tempUs['Sex']=0;
			$tempUs['InputId']='store';
			$tempUs['InputName']='store';
			$tempUs['CreateDate']=time();
			$tempUs['LastUpdateDate']=time();
			$tempUs['IsAdmin']=1;
			$tempUs['DepartmentName']=$tempData['id'];

			$tempGr['GroupName']='超级管理组';
			$tempGr['InputId']='admin';
			$tempGr['InputName']='admin';
			$tempGr['CreateDate']=time();
			$tempGr['LastUpdateDate']=time();
			$tempGr['stoken']=$stoken;
			$tempGr['token']=$this->token;

			$tempGu['InputId']='admin';
			$tempGu['InputName']='admin';
			$tempGu['token']=$this->token;
			$tempGu['CreateDate']=time();
			$tempGu['LastUpdateDate']=time();
			$tempGu['stoken']=$stoken;

			//添加用户组
			$tempDB['IsCheck']='1';
			$tempDB['Checkmark']='通过审核';
			$tempDB['stoken']=$stoken;
			$tempDB['MsgRecever']=$sinfo['openid'];
			$stres=M()->table('RS_Store')->where('id=%d',$tempData['id'])->setField($tempDB);
			$useres=true;
			if (!$resdf=$this->MSL('user')->where("userName='%s'",$tempUs['userName'])->find()) {
				$useres=$this->MSL('user')->add($tempUs);
				$tempGu['userId']=$useres;
			}else{
				$update=array();
				$update['stoken']=$stoken;
				$update['token']=$sinfo['token'];
				$update['IsAdmin']=1;
				$update['IsLogin']=1;
				$useres=$this->MSL('user')->where("userName='%s'",$tempUs['userName'])->setField($update);
				$tempGu['userId']=$resdf['id'];
			}
			$this->LOGS($this->MSL()->getlastSql());
			$gres=$this->MSL('groupmanger')->add($tempGr);
			$tempGu['GroupId']=$gres;

			$gures=$this->MSL('usergroup')->add($tempGu);
			// var_dump($houseRes);exit();

			//补充配送员信息
			$oldps=M()->table('RS_Distribution')->where("OpenId='%s'",$sinfo['openid'])->find();
			$PsStore=array();
			$PsStore['StoreId']=$sinfo['id'];
			$PsStore['OpenId']=$sinfo['openid'];
			$PsStore['Status']='1';
			$PsStore['AskDate']=date('Y-m-d H:i:s',time());
			$PsStore['stoken']=$stoken;
			$PsStore['MemberId']=$sinfo['MemberId'];
			if ($oldps) {
				//绑定门店//更新配送员信息
				$update=array();
				$update['IsBoss']='1';
				$update['IsReceving']='0';
				$dbs=M()->table('RS_Distribution')->where("OpenId='%s'",$sinfo['openid'])->setField($update);
				$dss=M()->table('RS_DistributionForStore')->add($PsStore);
			}else{
				//新注册配送员并绑定门店
				$update=array();
				$update['TrueName']=$sinfo['TrueName'];
				$update['IdCard']=$sinfo['IdCard'];
				$update['IdImg']=$sinfo['IdInfo'];
				$update['Phone']=$sinfo['tel'];
				$update['OpenId']=$sinfo['openid'];
				$update['HeadImg']=$sinfo['Slogo'];
				$update['MemberId']=$sinfo['MemberId'];
				$update['IsReceving']='0';
				$update['IsBoss']='1';
				// $dbs=M()->table('RS_Distribution')->add($update);
				// var_dump(M()->getlastSql());
				// $dss=M()->table('RS_DistributionForStore')->add($PsStore);
			}
			if ($stres && $useres && $gres && $gures && $member) {
				// $this->SH()->commit();
				$this->MSL()->commit();
				M()->commit();
				$SMS['content']="【光盘客】您的开店申请已通过审核，PC端管理地址".$_SERVER['HTTP_HOST']."/Seller 。 登陆账号：".$sinfo['tel']." 密码：".substr($sinfo['tel'], -4,4)." 。 ".$str;
				$SMS['mobiles']=$sinfo['tel'];
				$this->SendMessage($SMS);
				return 'true';
				//短信发送预留
			}else{
				// $this->SH()->rollback();
				$this->MSL()->rollback();
				M()->rollback();
				$this->LOGS('仓库添加：'.$houseRes.'__ 门店更新：'.$stres.'__ 用户添加：'.$useres.'__ 分组添加：'.$gres.'__ 用户组添加：'.$gures.' __ 用户添加：'.$member.'__仓库数据:'.$adddata.'__配送员补充dbs='.$dbs.'_dss='.$dss);
				return 'false';
				// $this->error('操作失败');
			}
		}
	}



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




	public function getwxparam(){
		// var_dump($_GET);exit();
		if ($_GET['code']) {
			$code=$_GET['code'];
			$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')."&code=".$code."&grant_type=authorization_code");
			$uinfo=json_decode($resinfo,true);

			$token=$this->get_access_token();

		$get_access_token_url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C('WXAPPID').'&secret='.C('WXAPPSECRET').'';
		$getasstoken=json_decode(file_get_contents($get_access_token_url),true);

		$user=json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$getasstoken['access_token']."&openid=".$uinfo['openid']."&lang=zh_CN"),true);
		if ($_GET['type']=='writestore') {

			$pagedata['openid']=$user['openid'];
			$pagedata['nickname']=$user['nickname'];
			$pagedata['subscribe']=$user['subscribe'];
			$pagedata['Invcode']=$_GET['Invcode'];

			//传值跳转；
			$this->redirect(str_replace('.html', '', "/Admin/Writeinfo/writestore?".http_build_query($pagedata)));
		}
		}else{
			$redirect_uri=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			// var_dump(C('WXAPPID'));exit();
		header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WXAPPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
		}
	}



}










 ?>
