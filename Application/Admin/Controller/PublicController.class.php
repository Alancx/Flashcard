<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends BaseController{
	public function _initialize(){
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Iphone')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false) {
			$this->show('<h1>程序不支持手机/微信浏览器！请复制链接到电脑端浏览器访问</h1>');exit();
		}
	}
	public function login(){
		$this->display('login');
	}

	public function logining(){
		$tpeme=C('DEFAULT_THEME');
		$theme=$tpeme ? $tpeme.'/' : '';
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		if ($userinfo=$this->MSL('user')->where("userName='%s' and password='%s'",array($username,$password))->find()) 
		{

			if ($userinfo['IsLogin']=='1') 
			{
				if ($userinfo['stoken']=='0') {
					$uinfo['ID']=$userinfo['id'];
					$uinfo['userName']=$userinfo['userName'];
					$uinfo['TrueName']=$userinfo['TrueName'];
					$userShop=M()->table('RS_Store')->where("stoken='%s'",$userinfo['stoken'])->getField('id');
					$uinfo['userShop']=$userShop;
					$uinfo['IsServer']=$userinfo['IsServer'];
					$gid=$this->MSL('usergroup')->where('userId=%d',$userinfo['id'])->getField('GroupId');
					$Gname=$this->MSL('groupmanger')->where('GroupId=%d',$gid)->getField('GroupName');
					session('Gname',$Gname);
					session('stoken',$userinfo['stoken']);
					session('HeadImgUrl',$userinfo['HeadImgUrl']);
					session('token',$userinfo['token']);
					session('is_login',1);
					session('userinfo',$uinfo);
					session('IsServer',$userinfo['IsServer']);
					$file=dirname(__FILE__).'/../view/'.$theme.'Index/indexv'.$gid.'.html';
					if (!file_exists($file)) {

						if ($Gname!='超级管理组') {
							// var_dump($Gname);
							$this->error('您的账号暂无权限，请联系管理员分配权限之后登陆');
						}else{
							$gid='超级管理组';
						}
					}
					session('GroupId',$gid);
					$this->MSL('user')->where('id=%d',$userinfo['id'])->setField('LastLoginDate',time());
					$this->success('登录成功',U('Index/index'));
				}
				else
				{
					$this->error('你的账号无权登陆');
				}
			}
			else
			{
				$this->error('您的账号无权登陆');
			}
		}
		else
		{				
			$this->error('用户名或密码错误');
		}
	}

	public function logout(){
		session('is_login',FALSE);
		session('userinfo',FALSE);
		$this->success('成功退出',U('Public/login'));
	}

	/**
	 * F登陆
	 */
	public function godata(){
		$this->LOGS(json_encode($_REQUEST));
		if (IS_POST) {
			$data=$_POST;
			$url='https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			if ($this->checkParam($data,$url)) {
				if ($data['type']=='f') {
					//首次登陆处理
					if ($uinfo=$this->MSL('user')->where("userName='%s' and Password='%s' and stoken='%s' and IsLogin='%s'",array($data['account'],md5($data['password']),'0','1'))->find()) {
						if (count(unserialize($uinfo['AreaIds']))>0) {
							//登陆成功
							session('AreaIds',unserialize($uinfo['AreaIds']));
							$exptime=intval(strtotime('+7 days'));
							$preStr='u_'.$data['account'].'p_'.md5($data['password']).'exp_'.$exptime;
							$AppToken=md5($preStr);
							$app=array();
							$app['Token']=$uinfo['token'];
							$app['Stoken']=$uinfo['stoken'];
							$app['AppToken']=$AppToken;
							$app['ExpDate']=$exptime;
							if ($this->MSL('apptoken')->where("UserId='%s'",$uinfo['id'])->find()) {
								if ($this->MSL('apptoken')->where("UserId='%s'",$uinfo['id'])->save($app)) {
									$msg['status']='success';
									session('apphaslogo',true);
									
									$msg['app']=array('apptoken'=>"ato_".$AppToken,'exptime'=>$exptime);
									$msg['MyArea']=$this->getMyarea(unserialize($uinfo['AreaIds']));
								}else{
									$msg['status']='error';
									$msg['info']='鉴权失败';
								}
							}else{
								$app['UserId']=$uinfo['id'];
								if ($this->MSL('apptoken')->add($app)) {
									$msg['status']='success';
									session('apphaslogo',true);
									
									$msg['app']=array('apptoken'=>"ato_".$AppToken,'exptime'=>$exptime);
									$msg['MyArea']=$this->getMyarea(unserialize($uinfo['AreaIds']));
								}else{
									$msg['status']='error';
									$msg['info']='鉴权失败';
								}
							}
						}else{
							$msg['status']='error';
							$msg['info']='您的账号暂无权限';
						}
					}else{
						$msg=array('status'=>'error','info'=>'账号或密码错误');
					}
				}elseif ($data['type']=='s') {
					//次回验证处理
					$appinfo=$this->MSL('apptoken')->where("AppToken='%s'",substr($data['timestamp'], 13))->find();
					if ($appinfo && intval($appinfo['ExpDate'])>=intval(time())) {
						$uinfo=$this->MSL('user')->where("id=%d",$appinfo['UserId'])->find();
						if (count(unserialize($uinfo['AreaIds']))>0) {
							if ($data['account']==$uinfo['userName'] && md5($data['password']==$uinfo['Password'])) {
								$msg['status']='success';
								session('apphaslogo',true);
								session('AreaIds',unserialize($uinfo['AreaIds']));
								$msg['MyArea']=$this->getMyarea(unserialize($uinfo['AreaIds']));
							}else{
								$msg['status']='error';
								$msg['info']='relogin';//重新登陆
							}
						}else{
							$msg['status']='error';
							$msg['info']='您的账号暂无权限';
						}
					}else{
						$msg['status']='error';
						$msg['info']='登陆失效/请重新登陆';
					}
				}
			}else{
				$msg=array('status'=>'error','info'=>'验签失败');
			}		
		}else{
			$msg=array('status'=>'error','info'=>'error');
		}
		$this->LOGS('登陆记录'.json_encode($msg),true);
		echo json_encode($msg);
	}

	/**
	 * 验签
	 */
	public function checkParam($data,$url){
		$sign=$data['sign'];
		if ($data['type']=='f') {
			$paramStr=$url.'*facunm='.$data['account'].'*facpwd='.$data['password'].'**'.$data['timestamp'];
		}elseif ($data['type']=='s') {
			$paramStr=$url.'*facunm='.$data['account'].'*facpwd='.$data['password'].'**'.substr($data['timestamp'], 0,9).'***'.substr($data['timestamp'], 9);
		}
		if (md5($paramStr)==$sign) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 拿时间
	 */
	public function getservertime(){
		echo time();
	}

	/**
	 * 入库单查询
	 */
	// public function getmsg(){
	// 	$type=$_POST['type'];
	// 	if ($type=='warehouse') {
	// 		$data=M()->query("SELECT COUNT(pw.ID) as count ,s.storename FROM RS_ProductInWarehouse pw LEFT JOIN RS_Store s ON pw.stoken=s.stoken WHERE  pw.stoken<>'0' and pw.Type='4' and (pw.Status='2' OR pw.Status='-1')  GROUP BY s.storename");
	// 		if ($data && count($data)>0) {
	// 			$msg['status']='success';
	// 			$msg['title']='<h3>有新的入库单据等待处理</h3>';
	// 			$str='';
	// 			foreach ($data as $da) {
	// 				$str.="【".$da['storename']."】:".$da['count']."条入库申请单据 <br><br>";
	// 			}
	// 			$msg['info']=$str;
	// 		}else{
	// 			$msg['status']='error';
	// 		}
	// 	}
	// 	echo json_encode($msg);
	// }


	/**
	 * 手机端登陆成功获取权限内地址
	 */
	public function getMyarea($data=array()){
		$Areainfo=M()->table('RS_AreaList')->where("AreaId in ('".implode("','", $data)."')")->getField('Area',true);
		// $areas=file_get_contents('./Public/Admin/areas.txt');
		// $str=str_replace('|市辖县', '', $areas);
		// $str=explode('#', $str);
		// $Marea=array();
		// foreach ($str as $st) {
		// 	$tp=explode('$', $st);
		// 	if (in_array($tp[0], $Areainfo)) {
		// 		$Marea[]=implode('$', $tp);
		// 	}
		// }
		// $Marea=implode('#', $Marea);
		// $this->LOGS($Marea,true);
		$Areainfo=implode('_', $Areainfo);
		return $Areainfo;
	}

}










 ?>
