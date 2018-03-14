<?php
namespace Admin\Controller;
use Think\Controller;
// header('content-type:text/html;charset=utf-8');
class StorersController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}


	/**
	 * 申请开店
	 */
	public function register(){
		if (IS_POST) {
		// var_dump($_POST);exit;
			// var_dump($this->token);
			$data['addr']=$_POST['addr'];
			$data['storename']=$_POST['storename'];
			$data['tel']=$_POST['tel'];
			$data['IdCard']=$_POST['IdCard'];
			$data['IdInfo']=$_POST['IdInfo'];
			$data['IsCheck']='0';
			$data['TrueName']=$_POST['TrueName'];
			$data['token']=$this->token;
			$data['CreateDate']=date('Y-m-d H:i:s',time());
			$data['lang']=$_POST['lang']; //经度
			$data['lat']=$_POST['lat']; //纬度
			$data['Invcode']=$_POST['Invcode'];
			$data['slang']=$_POST['lang']>0?$_POST['lang']:360+$_POST['lang']; //搜索经度
			if (M()->table('RS_Store')->where("tel='%s'",$_['tel'])->find()) {
				$this->error('该手机号已被占用');exit();
			}
			if ($_POST['id']) {
				if ($_POST['ischange']=='1') {
					$data['province']=$_POST['province'];
					$data['city']=$_POST['city'];
					$data['area']=$_POST['county'];
				}
				// var_dump($_POST);exit;
				if (M()->table('RS_Store')->where('id=%d',$_POST['id'])->save($data)) {
					$this->success('操作成功',U('Store/index'));
				}else{
					$this->LOGS(M()->getlastsql());
					$this->error('操作失败');
				}
			}else{
				$data['province']=$_POST['province'];
				$data['city']=$_POST['city'];
				$data['area']=$_POST['county'];
				M()->startTrans();
				$res=M()->table('RS_Store')->add($data);
				// echo M()->getlastSql();
				// $tbName=substr($this->token,-8,8).'_'.$res;
				// $tempStr=$this->warehouseSQLString($tbName);
				// $houseRes=$this->SH('temp')->db()->query($tempStr);
				$hres=true;
				// if ($houseRes===false) {
				// 	$hres=false;
				// }
				// var_dump($res,$hres);exit;
				if ($res && $hres) {
					M()->commit();
					$this->success('操作成功',U('Storers/checking')."?statu=1&id=".$res);
				}else{
					M()->rollback();
					$this->LOGS(M()->getlastsql());
					$this->error('操作失败');
				}
			}
		}else{
			define('FPAGE','SHANGHU');
			
			$this->assign('store',$store['id']=null);
			$this->display();
		}
	}

	public function checks(){
		$stores=M()->table('RS_Store')->where("token='%s' and IsCheck<>'%s'",array($this->token,''))->field("CONVERT(varchar(100),CreateDate,120) as CreateDate,storename,IdCard,IdInfo,TrueName,province,city,area,addr,tel,id,IsCheck,Checkmark,Remarks,openid")->select();
		$this->assign('stores',$stores);
			define('FPAGE','SHANGHU');
		$this->display();
	}

	/**
	 * 二维码
	 */
	public function showqr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// require 'E://web/webstore/Lib/ThinkPHP/Library/Vendor/PHPQR/phpqrcode.php';
		//$url=C('WEBURL')."/Product/".$_GET['pid'].".html?sid=".$_GET['sid'];
		$url='http://'.$_SERVER['HTTP_HOST'].U('Admin/Base/checkStore',array('id'=>$_GET['id']));
		$level="L";
		// $filename='./Uploads/1.png';
		$size=4;
		\QRcode::png($url,$filename,$level,$size,'2');
	}

	/**
	 * 手机号验证
	 */
	public function checktel(){
		$tel=$_POST['tel'];
		if (M()->table('RS_Store')->where("tel='%s'",$tel)->find()) {
			echo json_encode(array('status'=>'error'));
		}else{
			echo json_encode(array('status'=>'success'));
		}
	}

	/**
	 * 审核操作相关
	 */
	public function checking(){
		$tempData=$_GET;
		if ($tempData['statu']=='2') {
			$tempDB['Checkmark']=$tempData['Brmk'];
			$tempDB['IsCheck']='2';
			$tempDB['Remarks']=$tempData['Remarks'];
			$tel=M()->table('RS_Store')->where('id=%d',$tempData['id'])->getField('tel');
			if (M()->table('RS_Store')->where('id=%d',$tempData['id'])->setField($tempDB)) {
				$this->success('操作成功');
				$SMS['content']="您的开店申请已被驳回，原因：".$tempData['Brmk'].$tempData['Remarks'];
				$SMS['mobiles']=$tel;
				//发送短信通知预留
				$this->SendMessage($SMS);
			}else{
				// echo M()->getlastsql();exit();
				$this->error('操作失败');
			}
		}elseif ($tempData['statu']=='1') {
			$sinfo=M()->table('RS_Store')->where("token='%s' and id=%d",array($this->token,$tempData['id']))->find();
			if ($sinfo['openid']) {
				$minfo=M()->table('RS_Member')->where("token='%s' and OpenId='%s'",array($this->token,$sinfo['openid']))->find();
			}
			//添加仓库
			$stoken=$this->getStr(5,4);
			M()->startTrans();
			// $this->SH()->startTrans();
			$this->MSL()->startTrans();
			// $tbName=substr($this->token,-8,8).'_'.$tempData['id'];
			// $tempStr=$this->warehouseSQLString($tbName);
			// $houseRes=$this->SH()->db()->query($tempStr);
			// $adddata=$this->SH()->query("update tb_wh".$tbName." SET StockCount=0,LimitCount=0,VirtualCount=0,InCount=0,SalesCount=0,OutCount=0,ReturnCount=0 ");
		//	var_dump($houseRes);
		//	var_dump($this->SH()->getlastsql());exit;
		//	var_dump($adddata);exit;
			//添加用户
			$member=true;
			if (!$minfo['OpenId'] && $sinfo['openid'] ) {
					$MDB['MemberId']=$sinfo['tel'];
					$MDB['OpenId']=$sinfo['openid'];
					$MDB['MemberPwd']=md5(substr($sinfo['tel'], 7,4));
					$MDB['MemberName']=$sinfo['TrueName'];
					$MDB['token']=$this->token;
					$MDB['stoken']=$stoken;
					$member=M()->table('RS_Member')->add($MDB);
				}else{
					if ($sinfo['openid']) {
						$member=M()->table('RS_Member')->where("token='%s' and OpenId='%s'",array($this->token,$sinfo['openid']))->setField('stoken',$stoken);
					}
				}
			$tempUs['userName']=$sinfo['tel'];
			$tempUs['Password']=md5(substr($sinfo['tel'], -4,4));
			$tempUs['token']=$this->token;
			$tempUs['stoken']=$stoken;
			$tempUs['TrueName']=$sinfo['TrueName'];
			// var_dump(substr($sinfo['tel'], 7,4));exit();
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
			if (!$this->MSL('user')->where("userName='%s'",$tempUs['userName'])->find()) {
				$useres=$this->MSL('user')->add($tempUs);
			}else{
				$update=array();
				$update['stoken']=$stoken;
				$update['token']=$sinfo['token'];
				$update['IsAdmin']=1;
				$useres=$this->MSL('user')->where("userName='%s'",$tempUs['userName'])->setField($update);
			}
			$this->LOGS($this->MSL()->getlastSql());
			$gres=$this->MSL('groupmanger')->add($tempGr);
			$tempGu['GroupId']=$gres;
			$tempGu['userId']=$useres;
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
				$dbs=M()->table('RS_Distribution')->add($update);
				// var_dump(M()->getlastSql());
				$dss=M()->table('RS_DistributionForStore')->add($PsStore);
			}
			if ($stres && $useres && $gres && $gures && $member && $dbs && $dss) {
				$this->SH()->commit();
				$this->MSL()->commit();
				M()->commit();
				$this->success('审核完成');
				$str='';
				if ($sinfo['openid']) {
					// $str='请联系管理员绑定您的微信账号';
				}
				$SMS['content']="您的开店申请已通过审核，PC端管理地址".$_SERVER['HTTP_HOST']."/Seller 。 登陆账号：".$sinfo['tel']." 密码：".substr($sinfo['tel'], -4,4)." 。 ".$str;
				$SMS['mobiles']=$sinfo['tel'];
				$this->SendMessage($SMS);
				//短信发送预留
			}else{
				$this->SH()->rollback();
				$this->MSL()->rollback();
				M()->rollback();
				// var_dump('仓库添加：'.$houseRes.'__ 门店更新：'.$stres.'__ 用户添加：'.$useres.'__ 分组添加：'.$gres.'__ 用户组添加：'.$gures.' __ 用户添加：'.$member.'__仓库数据:'.$adddata.'__配送员补充dbs='.$dbs.'_dss='.$dss);exit();
				$this->LOGS('仓库添加：'.$houseRes.'__ 门店更新：'.$stres.'__ 用户添加：'.$useres.'__ 分组添加：'.$gres.'__ 用户组添加：'.$gures.' __ 用户添加：'.$member.'__仓库数据:'.$adddata.'__配送员补充dbs='.$dbs.'_dss='.$dss);
				$this->error('操作失败');
			}
		}
	}

	public function del(){
		$id=$_GET['id'];
		M()->startTrans();
		$store=M()->table('RS_Store')->where('id=%d',$id)->delete();
		$cancel=M()->table('RS_Cancel')->where('storeid=%d',$id)->delete();
		// var_dump($store,$cancel);exit();
		if ($store) {
			M()->commit();
			$this->success('操作成功');
		}else{
			M()->rollback();
			$this->error('操作失败');
		}
	}

	public function edit(){
		$id=$_GET['id'];
		$store=M()->table('RS_Store')->where('id=%d',$id)->find();
		$this->assign('store',$store);
		$this->display();
	}

	/**
	 * 查看门店核销员
	 */

	 public function userlist(){
	 	 $type=$_GET['type'];
	 	 // var_dump($type);
		 $sid=$_GET['sid'];
		 if ($type=='TH') {
		 	$name='o.ZTname';
		 }else{
		 	$name='o.XJname';
		 }
		 // echo "<pre>";
		 $userlists=M()->query("SELECT c.type,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=".$name." WHERE c.storeid='".$sid."' GROUP BY c.username,c.id,c.openid,c.type");
		 $typelist=array();
		 foreach ($userlists as $user) {
		 	$typeAry=json_decode(stripslashes($user['type']),true);
		 		// var_dump($typeAry);
		 	if (in_array($type, $typeAry)) {
		 		$typelist[]=$user;
		 	}
		 }
		 // var_dump($typelist);exit();
		 $pagedata['type']=$type;
		 $pagedata['userlists']=$typelist;
		 $this->assign($pagedata);
			define('FPAGE','SHANGHU');
		 $this->display();
	 }
	 /**
	  * 删除核销员
	  */
	 public function delcancle(){
	 	$id=$_GET['id'];
	 	$type=$_GET['type'];
	 	$cancel=M()->table('RS_Cancel')->where('id=%d',$id)->find();
	 	$typeAry=json_decode(stripcslashes($cancel['type']),true);
	 	if (in_array($type, $typeAry)) {
	 		// unset($typeAry[array_search($type, $typeAry)]);
	 		array_splice($typeAry, array_search($type, $typeAry),1);
	 	}
	 	if (count($typeAry)<1) {
	 		if (M()->table('RS_Cancel')->where('id=%d',$id)->delete()) {
	 			$this->success('删除成功');
	 		}else{
	 			$this->error('删除失败');
	 		}
	 	}else{
	 		if (M()->table('RS_Cancel')->where('id=%d',$id)->setField('type',json_encode($typeAry))) {
	 			$this->success('删除成功');
	 		}else{
	 			$this->error('删除失败');
	 		}
	 	}
	 }

	 /**
	  * 查看核销详情
	  */
		public function show(){
			$tempData=$_GET;
			$stime=$tempData['stime'];
			$etime=$tempData['etime'];
			$CanType=$tempData['CanType'];
			$openid=$tempData['sid'];
			// if ($CanType=='all') {
			// 	$where="ZTname='".$openid."' OR XJname='".$openid."' and token='".$this->token."' AND XJscantime BETWEEN '".date('Y-m-01 00:00:00',time())."' AND '".date('Y-m-d H:i:s',time())."'";
			// }else{
				if ($CanType=='pay') {
					$where="XJname='".$openid."' and token='".$this->token."' AND XJscantime BETWEEN '".$stime."' AND '".$etime."'";
				};
				if ($CanType=='get') {
					$where="ZTname='".$openid."' and token='".$this->token."' AND Cantime BETWEEN '".$stime."' AND '".$etime."'";
				}
			// }
			$count=M()->table('RS_Order')->where($where)->count();
			$page=new \Think\Page($count,15);
			$lists=M()->table('RS_Order')->where($where)->limit($page->firstRow.','.$page->listRows)->order('CreateDate desc')->select();
			foreach ($lists as &$li) {
				if ($CanType=='pay') {
					$time=$li['XJscantime'];
				}else{
					$time=$li['Cantime'];
				}
				foreach ($time as $key => $value) {
					if ($key=='date') {
						$li['Cantime']=substr($value,0,19);
					}
				}
			}
			$pagedata['CanType']=$CanType;
			$pagedata['lists']=$lists;
			$pagedata['page']=$page->show();
			$pagedata['username']=M()->table('RS_Cancel')->where("openid='%s'",$openid)->getField('username');
			// var_dump($openid);
			// var_dump($pagedata);
			$this->assign($pagedata);
			$this->display();
		}

	/**
	 * 导出某一核销员核销记录
	 */
	public function showOut(){
			$tempData=$_GET;
			$stime=$tempData['stime'];
			$etime=$tempData['etime'];
			$CanType=$tempData['CanType'];
			$openid=$tempData['sid'];
			// if ($CanType=='all') {
			// 	$where="ZTname='".$openid."' OR XJname='".$openid."' and token='".$this->token."' AND XJscantime BETWEEN '".date('Y-m-01 00:00:00',time())."' AND '".date('Y-m-d H:i:s',time())."'";
			// }else{
				if ($CanType=='pay') {
					$where="XJname='".$openid."' and token='".$this->token."' AND XJscantime BETWEEN '".$stime."' AND '".$etime."'";
					$Ctime="XJscantime";
					$Cname="支付核销";
				};
				if ($CanType=='get') {
					$where="ZTname='".$openid."' and token='".$this->token."' AND Cantime BETWEEN '".$stime."' AND '".$etime."'";
					$Ctime="Cantime";
					$Cname="提货核销";
				}
			// }
			$lists=M()->table('RS_Order')->where($where)->order('CreateDate desc')->field("CreateDate,OrderId,Price,CONVERT(varchar(100), ".$Ctime.", 120) as ".$Ctime.",(CASE WHEN PayName='XJ' THEN '现金支付' ELSE (CASE WHEN PayName='T' THEN '微信支付' ELSE(CASE WHEN PayName='YE' THEN '余额支付' ELSE(CASE WHEN PayName='JL' THEN '奖励余额支付' ELSE '其他' END) END) END) END) as PayName")->select();
			// var_dump(M()->getlastsql());exit();
			foreach ($lists as &$li) {
				$li['CanType']=$Cname;
			}
			$count=M()->table('RS_Order')->where($where)->count();
			$allmoney=M()->table('RS_Order')->where($where)->sum('Price');
        $xlsName=date('Y-m-d').'——'.$openid.$tempData['CanType'];
        $xlsCell  = array(
            array('OrderId','订单号'),
            array('Price','核销金额'),
            array($Ctime,'核销时间'),
            array('PayName','支付方式'),
            array('CanType','核销类型'),
            );
        $LogisticsCom=M()->table('RS_Cancel')->where("token='%s' and openid='%s'",array($this->token,$openid))->getField('username');
        $file_title="总订单数：".$count."  总订单额：".$allmoney."  查询条件：核销员：".$LogisticsCom."  查询类型：".$Cname."  查询时间：".$tempData['stime'].'——'.$tempData['etime'];
        // var_dump($file_title);exit();
            // var_dump(M()->getlastsql());exit();
        exportExcel($xlsName,$xlsCell,$xlsData=$lists,$file_title);
	}



	//创建仓库sql

	public function warehouseSQLString($token)
	{
		return "
     SELECT * INTO [dbo].[tb_wh".$token."] FROM
		  [dbo].[tb_wh".substr($this->token,-8,8)."]
		";
// 		return "
// CREATE TABLE [dbo].[tb_wh".$token."](
// 	[ID] [int] IDENTITY(1,1) NOT NULL,
// 	[ProId] [varchar](50) NOT NULL,
// 	[ProIdCard] [varchar](50) NULL,
// 	[StockCount] [float] NOT NULL,
// 	[LimitCount] [float] NOT NULL,
// 	[VirtualCount] [float] NOT NULL,
// 	[InCount] [float] NOT NULL,
// 	[SalesCount] [float] NOT NULL,
// 	[OutCount] [float] NOT NULL,
// 	[ReturnCount] [float] NOT NULL,
// 	[IsDelete] [int] NULL,
// 	[CreateDate] [datetime2](7) NOT NULL,
// 	[LastUpdateDate] [datetime2](7) NOT NULL,
//  CONSTRAINT [PK_tb_wh".$token."] PRIMARY KEY CLUSTERED
// ([ID] ASC)
// WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
// ) ON [PRIMARY]
// ";
	}


	/**
	 * 商户销售信息统计
	 */
	public function mersales(){
		if (IS_POST) {
			$where="s.stoken<>'0' and o.Status in (2,3,4,10)";
			if ($_POST['stime'] && $_POST['etime']) {
				$where.=" and o.CreateDate BETWEEN '{$_POST['stime']}' and '{$_POST['etime']}'";
			}
			$saledata=M()->query("SELECT CONVERT(float(53),SUM(o.Price),120) as allmoney,s.storename,s.tel,s.TrueName,s.stoken FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE {$where} GROUP BY s.stoken,s.storename,s.stoken,s.tel,s.TrueName");
			$pagedata['data']=$_POST;
			foreach ($saledata as $k => $v) {
				$total += $v['allmoney']; 
			}
		}else{
			$saledata=M()->query("SELECT CONVERT(float(53),SUM(o.Price),120) as allmoney,s.tel,s.TrueName,s.storename,s.stoken FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE s.stoken<>'0' and o.Status in(2,3,4,10) 
GROUP BY s.stoken,s.storename,s.stoken,s.tel,s.TrueName");
			foreach ($saledata as $k => $v) {
				$total += $v['allmoney']; 
			}
		}
		$pagedata['total'] = $total;
		$pagedata['saledata']=$saledata;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 到处
	 */
	public function mersalesout(){
		$where="s.stoken<>'0' and o.Status in (2,3,4,10)";
		if ($_GET['stime'] && $_GET['etime']) {
			$where.=" and o.CreateDate BETWEEN '{$_GET['stime']}' and '{$_GET['etime']}'";
		}
		$xlsData=M()->query("SELECT SUM(o.Price) as allmoney,s.storename,s.stoken,s.tel,s.TrueName FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE {$where} GROUP BY s.stoken,s.storename,s.stoken,s.tel,s.TrueName");
        $xlsName=date('YmdHis',time()).'salesdata';
        $xlsCell  = array(
            array('storename','店铺名称'),
            array('allmoney','销售额'),
            array('tel','联系电话'),
            array('TrueName','联系人'),
            );
        exportExcel($xlsName,$xlsCell,$xlsData);
	}

	/**
	 * 商户银行卡信息查询
	 */
	public function mercards(){
		$pagesize=20;
		if (IS_POST) {
			$data=$_POST;
		}else{
			$data=$_GET;
		}
		unset($data['v']);
		unset($data['p']);
		if ($data) {
			$whereStr="mb.token='{$this->token}'";
			if ($data['storename']) {
				$whereStr.=" and s.storename LIKE '%{$data['storename']}%'";
			}
			$count = M()->table('RS_MerchantBank mb')->join("LEFT JOIN RS_Store s ON mb.stoken=s.stoken")->where($whereStr)->count();
			$page  = new \Think\Page($count,$pagesize,$data);
			$banks = M()->table('RS_MerchantBank mb')->join("LEFT JOIN RS_Store s ON mb.stoken=s.stoken")->where($whereStr)->field("s.storename,mb.IdCard,mb.IdName,mb.BankName,mb.tel")->limit($page->firstRow.','.$page->listRows)->select();
		}else{
			$count = M()->table('RS_MerchantBank')->where("token='%s'",$this->token)->count();
			$page  = new \Think\Page($count,$pagesize);
			$banks = M()->table('RS_MerchantBank mb')->join("LEFT JOIN RS_Store s ON mb.stoken=s.stoken")->where("mb.token='%s'",$this->token)->field("s.storename,mb.IdCard,mb.IdName,mb.BankName,mb.tel")->limit($page->firstRow.','.$page->listRows)->select();
		}
		$pagedata['data']=$data;
		$pagedata['banks']=$banks;
		$pagedata['page']=$page->show();
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 结算处理  2017-05-14
	 */
	public function cutrecord(){
		$pagesize=15;
		if (IS_POST) {
			$param=$_POST;
		}else{
			$param=$_GET;
		}
		unset($param['v']);
		unset($param['p']);
		$whereStr=" md.token='{$this->token}'";
		if ($param['sid']) {
			$whereStr.=" AND md.stoken='{$param['sid']}'";
		}
		if ($param['strtime'] && $param['endtime']) {
			$whereStr.=" AND md.CreateDate BETWEEN '{$param['strtime']}' and '{$param['endtime']}'";
		}
		if (isset($param['Status']) && $param['Status']!='all') {
			$whereStr.=" and md.Status='{$param['Status']}'";
		}
			
		if ($param['param']=='out') {
			//导出
			$lists=M()->table('RS_MerCutDetail md')->join("LEFT JOIN RS_Store s ON md.stoken=s.stoken")->where($whereStr)->field("md.Money,CONVERT(varchar(20),md.CreateDate,120) as CreateDate,'卡号:'+''+md.IdCard as IdCard,md.IdName,GetName,s.storename")->select();
	        $xlsName=date('YmdHis',time()).'cutrecord';
	        $xlsCell  = array(
	            array('storename','店铺名称'),
	            array('IdName','开户行'),
	            array('GetName','开户名'),
	            array('IdCard','开户账号'),
	            array('Money','结算金额'),
	            array('CreateDate','结算时间')
	            );
	        $file_title="结算申请记录";
	        exportExcel($xlsName,$xlsCell,$xlsData=$lists,$file_title);
		}else{
			$count=M()->table('RS_MerCutDetail md')->where($whereStr)->count();
			$page = new \Think\Page($count,$pagesize,$param);
			$lists=M()->table('RS_MerCutDetail md')->join("LEFT JOIN RS_Store s ON md.stoken=s.stoken")->where($whereStr)->field("md.ID,CONVERT(varchar(20),md.CreateDate,120) as CreateDate,md.Money,md.IdCard,md.IdName,md.GetName,md.tel,s.storename,md.Status")->order('CreateDate desc')->limit($page->firstRow.','.$page->listRows)->select();
			$pagedata['lists']=$lists;
			$pagedata['data']=$param;
			$pagedata['page']=$page->show();
		}
		$stores=M()->table('RS_Store')->where("token='%s' and IsCheck='%s'",array($this->token,'1'))->field("stoken,storename")->select();
		$pagedata['stores']=$stores;
		$this->assign($pagedata);
		$this->display();
	}



	

	/**
	 * 商户提现申请处理  2017-05-14
	 */
	public function recordcuted(){
		$exec=$_SESSION['admin']['userinfo'];
		if (IS_POST) {
			$id=$_POST['id'];
			$cutinfo=M()->table('RS_MerCutDetail')->where("ID=%d",$id)->find();
			//资金变动记录
			$batchNo=uniqid('PAYING');
			$MoneyInfo=array();
			$MoneyInfo['Money']=$cutinfo['Money'];
			$MoneyInfo['Type']='less';
			$MoneyInfo['Useage']='JS';
			$MoneyInfo['token']=$this->token;
			$MoneyInfo['stoken']=$cutinfo['stoken'];
			//更新数据
			$update=array();
			$update['ExecDate']=date('Y-m-d H:i:s',time());
			$update['Status']='1';
			$update['ExecId']=$exec['ID'];
			$update['ExecName']=$exec['userName'];
			$update['Payid']=$batchNo;
			//结算处理
			// $Bankinfo=M()->table('RS_MerchantBank')->where("stoken='%s'",$cutinfo['stoken'])->find();
			// $Param=array();
			// $Param['batchNo']=$batchNo;
			// $Param['totalAmount']=floatval($cutinfo['Money'])*100;
			// $contents=array();
			// $content=array();
			// $content['orderNo']=uniqid('PAYONE');
			// $content['curCode']='CNY';
			// $content['orderAmount']=floatval($cutinfo['Money'])*100;
			// $content['accountName']=$Bankinfo['IdName'];
			// $content['accountNumber']=$Bankinfo['IdCard'];
			// $content['accountType']=1;
			// $content['bankCode']=$Bankinfo['IdType'];
			// $content['bankName']=$Bankinfo['BankName'];
			// $content['branchBankName']=$Bankinfo['ZBankName'];
			// $content['province']=$Bankinfo['Province'];
			// $content['city']=$Bankinfo['City'];
			// $content['feeType']=1;
			// $content['isUrgent']=0;
			// $contents[]=$content;
			// $Param['content']=json_encode($contents,JSON_UNESCAPED_UNICODE);
			// $Pay=new PayapiController();
			// $res=$Pay->payother($Param);
			// $this->LOGS
			// if ($res['dealCode']=='10000') {
				M()->startTrans();
				$md=M()->table('RS_MerCutDetail')->where("ID=%d",$id)->setField($update);
				// var_dump(M()->getlastsql());exit();
				$sm=M()->table('RS_StoreMoneyManager')->add($MoneyInfo);
				// $sm=true;
				if ($md && $sm) {
					M()->commit();
					$msg['status']='success';
				}else{
					M()->rollback();
					$msg['status']='error';
					$msg['info']='处理失败';
					$this->LOGS("商户提现申请结算处理失败--->>>md=$md,sm=$sm .--->>>".M()->getlastsql);
				}				
			// }else{
			// 	$msg['status']='error';
			// 	$msg['info']='转账申请提交失败，请检查商户银行信息是否无误';
			// }
			// var_dump($res);exit();
			echo json_encode($msg);
		}
	}


	/**
	 * 商户结算相关
	 */
	public function storecut(){
		if (IS_POST) {
			$param=$_POST;
		}else{
			$param=$_GET;
		}
		unset($param['p']);
		unset($param['v']);
		$whereStr="sm.Type='add' and sm.Useage='XS'";
		if ($param['stoken']) {
			$whereStr.=" and sm.stoken='{$param['stoken']}'";
		}
		if ($param['stime'] && $param['etime']) {
			$whereStr.=" and sm.CreateDate BETWEEN '{$param['stime']}' and '{$param['etime']}'";
		}
		$count=M()->table('RS_StoreMoneyManager sm')->join("LEFT JOIN RS_Store s ON sm.stoken=s.stoken")->where($whereStr)->count();
		$page = new \Think\Page($count,10,$param);
		$data=M()->table('RS_StoreMoneyManager sm')->join("LEFT JOIN RS_Store s ON sm.stoken=s.stoken")->where($whereStr)->field("sm.Money,CONVERT(varchar(20),sm.CreateDate,120) as CreateDate,sm.stoken,s.storename,sm.Ext,s.tel,sm.ID")->limit($page->firstRow.','.$page->listRows)->select();
		// var_dump(M()->getlastsql());
		// foreach ($data as $key => $value) {
		// 	$data[$key]['Ext']=unserialize($value['Ext']);
		// }
		$stores=M()->table('RS_Store')->where("IsCheck='1'")->field("storename,stoken")->select();
		$pagedata['data']=$data;
		$pagedata['page']=$page->show();
		$pagedata['param']=$param;
		$pagedata['stores']=$stores;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 更新引流佣金  2017-05-14
	 */
	public function updateCut(){
		$tmpdata=M()->query("SELECT SUM(ol.Money*p.Cut/100) as Money,ol.OrderId FROM RS_Order o LEFT JOIN RS_OrderList ol ON o.OrderId=ol.OrderId LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE o.Status in (4,10) and ol.IsDelete='0' and o.IsCuted='-1' GROUP BY ol.OrderId");
		$i=0;
		foreach ($tmpdata as $tp) {
			$tpupdate=array();
			$tpupdate['IsCuted']='1';
			$tpupdate['CutMoney']=$tp['Money'];
			if (M()->table('RS_Order')->where("token='%s' and OrderId='%s'",array($this->token,$tp['OrderId']))->setField($tpupdate)) {
				$i++;
			}
		}
		echo json_encode(array('status'=>'success','num'=>$i));
	}

	/**
	 * 结算处理
	 */
	public function cuted(){
		$stoken=$_GET['stoken'];
		$stime=$_GET['stime'];
		$etime=$_GET['etime'];
		$type=$_GET['cutype'];
		$sinfo=M()->table("RS_Store")->where("stoken='%s'",$stoken)->find();
		if ($type=='1') {
			$whereStr="token='{$this->token}' and stoken='{$stoken}' and PayName in('T','ALIPAY') and Status in (4,10) and Pstoken='1'";
			if ($stime && $etime) {
				$whereStr.=" and CreateDate BETWEEN '{$stime}' and '{$etime}'";
			}
			$allmoney=M()->table('RS_Order')->where($whereStr)->sum("Price-TruepsMoney");
			$allps=M()->table('RS_Order')->where($whereStr." and Freight<>TruepsMoney")->sum('TruepsMoney');
			$OrderIds=M()->table('RS_Order')->where($whereStr)->getField("OrderId",true);
			$trueallps=floatval($allps)*floatval($sinfo['Fpsper'])/100;
			$allmoney=$allmoney+$trueallps;
			$allmoney=$allmoney*(100-$sinfo['CutNum'])/100;
		}
		if ($type=='2') {
			$whereStr=" token='{$this->token}' and SceneContent='{$stoken}' and Status in (4,10) and IsCuted='1'";
			if ($stime && $etime) {
				$whereStr.=" and CreateDate BETWEEN '{$stime}' and '{$etime}'";
			}
			$allmoney=M()->table('RS_Order')->where($whereStr)->sum("CutMoney");
			$OrderIds=M()->table('RS_Order')->where($whereStr)->getField("OrderId",true);
			$allmoney=$allmoney*(100-$sinfo['CutNum'])/100;
		}
		//变动记录数据
		$MoneyInfo=array();
		$MoneyInfo['Money']=$allmoney;
		$MoneyInfo['Type']='add';
		if ($type=='1') {
			$MoneyInfo['Useage']='XS';
		}elseif ($type=='2') {
			$MoneyInfo['Useage']='YL';
		}
		$MoneyInfo['token']=$this->token;
		$MoneyInfo['stoken']=$stoken;
		$MoneyInfo['Ext']=serialize($OrderIds);
		//开始事务
		M()->startTrans();
		//更新订单
		if ($type=='1') {
			$ores=M()->table('RS_Order')->where($whereStr)->setField("Pstoken",'2');
		}elseif ($type=='2') {
			$ores=M()->table('RS_Order')->where($whereStr)->setField("IsCuted",'2');
		}
		$sres=M()->execute("UPDATE RS_Store SET Money=Money+{$allmoney},TotalMoney=TotalMoney+{$allmoney} WHERE token='{$this->token}' and stoken='{$stoken}'");
		$smrs=M()->table('RS_StoreMoneyManager')->add($MoneyInfo);
		if ($ores && $sres && $smrs) {
			M()->commit();
			$this->success('处理成功');
		}else{
			$this->error('处理失败');
			$this->LOGS("商户结算处理失败ores=$ores...sres=$sres...smrs=$smrs...--->>>".M()->getlastsql());
		}
	}

	/**
	 * 结算详情
	 */
	public function getdetail(){
		$whereStr="Status in (4,10) and Pstoken=1 and PayName IN ('T','ALIPAY') ";
		if ($_GET['stoken']) {
			$whereStr.=" and stoken='{$_GET['stoken']}'";
		}
		if ($_GET['stime'] && $_GET['etime']) {
			$whereStr.=" and CreateDate BETWEEN '{$_GET['stime']}' and '{$_GET['etime']}'";
		}
		$count=M()->table('RS_Order')->where($whereStr)->count();
		$page=new \Think\Page($count,15,$_GET);
		$infolist=M()->table('RS_Order')->where($whereStr)->field("(CASE PayName WHEN 'T' THEN '微信支付' WHEN 'ALIPAY' THEN '支付宝' WHEN 'XJ' THEN '现金支付' END) as PayName,OrderId,CONVERT(float(50),Price,120) as Price")->limit($page->firstRow.','.$page->listRows)->select();
		$pagedata['page']=$page->show();
		$pagedata['infolist']=$infolist;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 引流明细
	 */
	public function getyl(){
		$whereStr="Status in (4,10) and IsCuted='1' and CutMoney>0";
		if ($_GET['stoken']) {
			$whereStr.=" and SceneContent='{$_GET['stoken']}'";
		}
		if ($_GET['stime'] && $_GET['etime']) {
			$whereStr.=" and CreateDate BETWEEN '{$_GET['stime']}' and '{$_GET['etime']}'";
		}
		$count=M()->table('RS_Order')->where($whereStr)->count();
		$page=new \Think\Page($count,15,$_GET);
		$list=M()->table('RS_Order')->where($whereStr)->field("OrderId,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE PayName WHEN 'T' THEN '微信支付' WHEN 'ALIPAY' THEN '支付宝' WHEN 'XJ' THEN '现金支付' END) as PayName,CONVERT(float(50),Price,120) as Price,CONVERT(float(50),CutMoney,120) as CutMoney")->limit($page->firstRow.','.$page->listRows)->select();
		$pagedata['page']=$page->show();
		$pagedata['infolist']=$list;
		$this->assign($pagedata);
		$this->display();
	}
	/**
	 * 商户扣点信息设置 2017-05-14
	 */
	public function mercutinfo(){
		if (IS_POST) {
			$id=$_POST['id'];
			$data=$_POST;
			unset($data['id']);
			if ($data['IsFreeCut']==0) {
				$data['FreeStime']=NULL;
				$data['FreeEtime']=NULL;
			}
			if (M()->table('RS_Store')->where("id=%d",$id)->setField($data)) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='处理失败';
			}
			echo json_encode($msg);
		}else{
			$pagedata['stores']=M()->table("RS_Store")->where("token='{$this->token}' and stoken<>'0' and IsCheck='1'")->field("id,storename,tel,province,city,area,addr,stoken,Fpsper,PsGet,CutNum,IsFreeCut,CONVERT(varchar(20),FreeStime,120) as FreeStime,CONVERT(varchar(20),FreeEtime,120) as FreeEtime")->select();
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 配送提现处理
	 */
	public function pscheck(){
		if (IS_POST) {
			$Param=$_POST;
		}else{
			$Param=$_GET;
		}
		unset($Param['v']);
		unset($Param['p']);
		$whereStr="mc.token='{$this->token}' and mc.Money>0";
		if ($Param['Status']) {
			$whereStr.=" and mc.Status='{$Param['Status']}'";
		}else{
			$whereStr.=" and mc.Status in(1,2)";
		}
		if ($Param['StartDate'] && $Param['EndDate']) {
			$whereStr.=" and mc.CreateDate BETWEEN '{$Param['StartDate']}' and '{$Param['EndDate']}'";
		}
		if ($Param['MemberName']) {
			$whereStr.=" and d.TrueName LIKE '%{$Param['MemberName']}%'";
		}
		$count=M()->table('RS_DistributionCashDetail mc')->join("LEFT JOIN RS_Distribution d ON mc.OpenId=d.OpenId")->where($whereStr)->count();
		$page = new \Think\Page($count,20,$Param);
		$lists=M()->table('RS_DistributionCashDetail mc')->join("LEFT JOIN RS_Distribution d ON mc.OpenId=d.OpenId")->where($whereStr)->field("mc.Money,mc.OpenId,mc.stoken,mc.ExecName,CONVERT(varchar(20),mc.ExecDate,120) as ExecDate,d.TrueName,CONVERT(varchar(20),CreateDate,120) as CreateDate,mc.Status")->order('CreateDate desc')->limit($page->firstRow.','.$page->listRows)->select();
		$pagedata['lists']=$lists;
		$pagedata['page']=$page->show();
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 账户金额变动记录
	 */
	public function MerMoneyInfo(){
		if (IS_POST) {
			
		}else{

		}
		$whereStr="token='{$this->token}' and stoken<>'0' and IsCheck='1'";
		$count=M()->table('RS_Store')->where($whereStr)->count();
		$page = new \Think\Page($count,20);
		$lists=M()->table('RS_Store')->where($whereStr)->field("id,stoken,storename,province,city,area,addr,TrueName,IdCard,CONVERT(varchar(20),CreateDate,120) as CreateDate,convert(float(53),Money,120) as Money,convert(float(53),TotalMoney,120) as TotalMoney")->limit($page->firstRow.','.$page->listRows)->select();
		$pagedata['lists']=$lists;
		$pagedata['page']=$page->show();
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 账户明细查询
	 */
	public function showmoneydetail(){
		$stoken=$_POST['stoken'];
		$type=$_POST['type'];
		$pagesize=$_POST['pagesize'];
		if ($type=='first') {
			$data=M()->table('RS_StoreMoneyManager')->where("token='{$this->token}' and stoken='{$stoken}'")->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,convert(float(53),Money,120) as Money,(CASE Useage WHEN 'XS' THEN '销售结算' WHEN 'YL' THEN '引流结算' WHEN 'JS' THEN '账户提现' WHEN 'PS' THEN '配送员提现' WHEN 'CKDIFF' THEN '缺货退款' END) AS Useage,(CASE Type WHEN 'add' THEN '收入' WHEN 'less' THEN '支出' END) as Type")->limit(0,$pagesize)->select();
		}
		if ($type=='second') {
			$pagenum=$_POST['pagenum'];
			$data=M()->table('RS_StoreMoneyManager')->where("token='{$this->token}' and stoken='{$stoken}'")->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,convert(float(53),Money,120) as Money,(CASE Useage WHEN 'XS' THEN '销售结算' WHEN 'YL' THEN '引流结算' WHEN 'JS' THEN '账户提现' WHEN 'PS' THEN '配送员提现' WHEN 'CKDIFF' THEN '缺货退款' END) AS Useage,(CASE Type WHEN 'add' THEN '收入' WHEN 'less' THEN '支出' END) as Type")->limit($pagenum,$pagesize)->select();
		}
		if ($data && count($data)>0) {
			$msg['status']='success';
			$msg['data']=$data;
			if (count($data)==$pagesize) {
				$msg['hasmore']='yes';
			}
		}else{
			$msg['status']='error';
		}
		echo json_encode($msg);
	}


	/**
	 * 参数验证
	 */
	public function checkparam(){
		$type=$_POST['type'];
		$key=$_POST['key'];
		if ($type=='invcode') {
			if ($this->MSL('user')->where("Invcode='%s'",$key)->find()) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
			}
		}elseif ($type=='idcard') {
			if (M()->table('RS_Store')->where("IdCard='%s'",$key)->find()) {
				$msg['status']='error';
			}else{
				$msg['status']='success';
			}
		}
		$this->ajaxReturn($msg);
	}


	/**
	 * 商户结算明细
	 */
	public function storecutdetail(){
		if (IS_POST) {
			$param=$_POST;
		}else{
			$param=$_GET;
		}
		unset($param['p']);
		unset($param['v']);
		$whereStr="sm.Type='add' and sm.Useage='XS'";
		if ($param['stoken']) {
			$whereStr.=" and sm.stoken='{$param['stoken']}'";
		}
		if ($param['stime'] && $param['etime']) {
			$whereStr.=" and sm.CreateDate BETWEEN '{$param['stime']}' and '{$param['etime']}'";
		}
		$count=M()->table('RS_StoreMoneyManager sm')->join("LEFT JOIN RS_Store s ON sm.stoken=s.stoken")->where($whereStr)->count();
		$page = new \Think\Page($count,10);
		$data=M()->table('RS_StoreMoneyManager sm')->join("LEFT JOIN RS_Store s ON sm.stoken=s.stoken")->where($whereStr)->field("sm.Money,CONVERT(varchar(20),sm.CreateDate,120) as CreateDate,sm.stoken,s.storename,sm.Ext,sm.tel")->limit($page->firstRow.','.$page->listRows)->select();
		var_dump(M()->getlastsql());
		// foreach ($data as $key => $value) {
		// 	$data[$key]['Ext']=unserialize($value['Ext']);
		// }
		$stores=M()->table('RS_Store')->field("storename,stoken")->select();
		$pagedata['data']=$data;
		$pagedata['page']=$page->show();
		$pagedata['param']=$param;
		$pagedata['store']=$stores;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 查看结算明细
	 */
	public function showcutdetail(){
		$id=$_POST['id'];
		$info=M()->table('RS_StoreMoneyManager')->where("ID=%d",$id)->getField("Ext");
		$oids=unserialize(stripslashes($info));
		$orders=M()->table('RS_Order')->where("OrderId IN ('".implode("','", $oids)."')")->field("OrderId,Price,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE DisMoney WHEN 0 THEN '0' ELSE '1' END) as Dis")->select();
		if ($orders && count($orders)>0) {
			$msg['status']='success';
			$msg['data']=$orders;
		}else{
			$msg['status']='error';
			$msg['info']='查询失败';
		}
		$this->ajaxReturn($msg);
	}







}
