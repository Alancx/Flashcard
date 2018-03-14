<?php
namespace Seller\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class StoresController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
		if (IS_POST) {
			$sid=$_POST['sid'];
			if ($_POST['endtime']) {
				$where=" AND stoken='".$this->stoken."' AND Mercut=1 AND PayDate BETWEEN '".$_POST['strtime']."' AND '".$_POST['endtime']."'";
			}else{
				$where=" AND stoken='".$this->stoken."' AND Mercut=1";
			}
			$storeinfo=M()->table('RS_Store')->where("token='%s' and id='%s'",array($this->token,$sid))->find();
			// var_dump(M()->getlastsql());
			$Sorders=M()->query("SELECT SUM(Price-Freight) AS sallmoney,COUNT(OrderId) AS scount FROM RS_Order  WHERE Status in (4,10) AND PayName='XJ' AND Prostoken='".$storeinfo['stoken']."'".$where)[0];  //商户销售自家商品单据
			// echo M()->getlastsql();exit();
			$pagedata['cutinfo']=$Sorders;
			$pagedata['pageinfo']=array('sid'=>$sid);
			$pagedata['cutmoney']=$OnlineCut+$MonlineCut;
			$pagedata['stime']=date('Y-m-d H:i:s',$storeinfo['MercutDate']);
			$pagedata['storename']=$storeinfo['storename'];
			$pagedata['endtime']=$_POST['endtime'];
			// $pagedata['stime']=
			if ($_POST['type']=='cuted') {
				$Info['token']=$this->token;
				$Info['storeid']=$sid;
				$Info['allmoney']=$Sorders['sallmoney'];
				$Info['CreateDate']=date('Y-m-d H:i:s',time());
				$Info['stoken']=$this->stoken;
				//更新时间
				if ($_POST['endtime']) {
					$time=strtotime($_POST['endtime']);
					// $tempInfo=array('MercutDate',strtotime($_POST['endtime']));
					$newWhere=" AND PayDate BETWEEN '".$_POST['strtime']."' and '".$_POST['endtime']."'";
				}else{
					$time=time();
					// $tempInfo=array('MercutDate',time());
					$mewWhere="";
				}
				M()->startTrans();
				$sres=M()->table('RS_Store')->where('id=%d',$sid)->setField('MercutDate',$time);
				$ires=M()->table('RS_ToStoreinfo')->add($Info);
				$ores=M()->execute("UPDATE RS_Order SET Mercut=2 WHERE stoken='".$this->stoken."' AND Prostoken='".$storeinfo['stoken']."' AND Status in(4,10)".$newWhere);
				if ($sres && $ires && $ores) {
					M()->commit();
					echo json_encode('success');exit();
				}else{
					$this->LOGS("sres=$sres && ires=$ires && ores=$ores_____".M()->getlastsql());
					M()->rollback();
					echo json_encode('error');exit();
				}
			}
		}else{
			$pagedata['pageinfo']=false;
		}
		$stores=M()->table('RS_Store')->where("token='%s' and IsCheck='%s' and stoken<>'%s'",array($this->token,'1',$this->stoken))->select();
		foreach ($stores as &$st) {
			$st['ctime']=date('Y-m-d H:i:s',$st['MercutDate']);
		}
		$pagedata['jsondata']=json_encode($stores);
		$pagedata['stores']=$stores;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 查看明细
	 */
	public function getmore(){
		$sid=$_POST['sid'];
		$storeinfo=M()->table('RS_Store')->where('id=%d',$sid)->find();
		if ($_POST['etime']) {
			$where=" AND stoken='".$this->stoken."' AND Mercut=1 AND PayDate BETWEEN '".$_POST['stime']."' AND '".$_POST['etime']."'";
		}else{
			$where=" AND stoken='".$this->stoken."' AND Mercut=1";
		}
		$Sorders=M()->query("SELECT OrderId,Price,Freight,MemberId,RecevingName,Count,CONVERT(varchar(100),PayDate,120) as PayDate FROM RS_Order  WHERE Status in (4,10) AND PayName='XJ' AND Prostoken='".$storeinfo['stoken']."'".$where);  //商户销售自家商品单据
		// echo M()->getlastsql();exit();
		if (count($Sorders)>0) {
			echo json_encode(array('status'=>'success','data'=>$Sorders));
		}else{
			echo json_encode(array('status'=>'error','info'=>'没有订单.'));
		}
	}
	//发送短信验证码
	public function sendCode() {
	    if(IS_POST) {
            $makeCode = '';
            //获取6位随机数
            for($i=0;$i<6;$i++) {
                $makeCode .= rand(0,9);
            }
            $messageData = array();
            $messageData['mobiles'] = trim($_POST['phone']);
            $messageData['content'] = " 验证码: ".$makeCode." 如非本人操作,请忽略此条短信";
            //调用短信接口类
            $this->SendMessage($messageData);
            $msg['status'] = 'success';
            $msg['info'] = '验证码已发送';
            $msg['code'] = $makeCode;
            $this->ajaxReturn($msg);
        }
    }
    /**
	 * 商户信息设置
	 */
	public function sinfoset(){
		if (IS_POST) {
			$db=array();
			$db['BankName']=$_POST['BankName'];
			$db['IdCard']=$_POST['IdCard'];
			$db['IdName']=$_POST['IdName'];
			$db['tel'] = $_POST['phone'];
			$db['stoken']=$this->stoken;
			$db['IsCheck']='1';
			//ajax传递过来的验证码,需要和生成的验证码进行比对
			if (M()->table('RS_MerchantBank')->where("stoken='%s'",$this->stoken)->find()) {
				if (M()->table('RS_MerchantBank')->where("stoken='%s'",$this->stoken)->save($db)) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}else{
				if (M()->table('RS_MerchantBank')->add($db)) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}
		}else{
			$binfo=M()->table('RS_MerchantBank')->where("stoken='%s'",$this->stoken)->find();
			$pagedata['binfo']=$binfo;
			$this->assign($pagedata);
			$this->display();
		}
	}
	/**
	 * 商户信息设置
	 */
	/*public function sinfoset(){
		if (IS_POST) {
			$db=array();
			$db['BankName']=$_POST['BankName'];
			$db['IdCard']=$_POST['IdCard'];
			$db['IdName']=$_POST['IdName'];
			$db['stoken']=$this->stoken;
			$db['IsCheck']='1';
			if (M()->table('RS_MerchantBank')->where("stoken='%s'",$this->stoken)->find()) {
				if (M()->table('RS_MerchantBank')->where("stoken='%s'",$this->stoken)->save($db)) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}else{
				if (M()->table('RS_MerchantBank')->add($db)) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}
		}else{
			$binfo=M()->table('RS_MerchantBank')->where("stoken='%s'",$this->stoken)->find();
			$pagedata['binfo']=$binfo;
			$this->assign($pagedata);
			$this->display();
		}
	}*/

	/**
	 * 发验证码
	 */
	public function sendmsg(){
		$type=$_POST['type'];
		$str='0123456789';
		switch ($type) {
			case 'bankverify':
				$code=substr(str_shuffle($str), -6,6);
				$msg['content']='您的验证码为：'.$code;
				$msg['mobiles']=$_POST['tel'];
				break;
			
			default:
				# code...
				break;
		}
		$this->SendMessage($msg);
		$res['status']='success';
		$res['info']=$code;
		echo json_encode($res);
	}

	/**
	 * 保存银行卡信息
	 */
	public function savebanks(){
		$data=$_POST;
		unset($data['isold']);
		if ($_POST['isold']=='1') {
			//更新信息
			if (M()->table('RS_MerchantBank')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->save($data)) {
				$msg['status']='success';

			}else{
				$msg['status']='error';
				$this->LOGS('银行卡信息更新失败--->>>'.M()->getlastsql());
			}
		}else{
			$data['token']=$this->token;
			$data['stoken']=$this->stoken;
			if (M()->table('RS_MerchantBank')->add($data)) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$this->LOGS('银行卡信息添加失败--->>>'.M()->getlastsql());
			}
		}
		echo json_encode($msg);
	}

	/**
	 * 修改基本信息
	 */
	public function changestinfo(){
		if (IS_POST) {
			$data=$_POST;
			$data['slang']=$_POST['lang']>0?$_POST['lang']:360+$_POST['lang']; //搜索经度
			if (M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->save($data)) {
				$this->success('修改成功');
			}else{
				$this->LOGS("门店信息修改失败--->>>".M()->getlastsql());
				$this->error('修改失败');
			}
		}else{
			$sinfo=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->find();
			$pagedata['sinfo']=$sinfo;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 配送费用设置 2017-05-14
	 */
	public function mercutinfo(){
		if (IS_POST) {
			$update=array();
			$update['PsPrice']=$_POST['PsPrice'];
			$update['PsGet']=$_POST['PsGet'];
			$update['MinPsGet']=$_POST['MinPsGet'];
			$update['MaxPsGet']=$_POST['MaxPsGet'];
			$update['PsgetType']=$_POST['PsgetType'];
			if (M()->table('RS_Store')->where("id=%d",$_POST['id'])->setField($update)) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='处理失败';
			}
			echo json_encode($msg);
		}else{
			$store=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
			// var_dump(M()->getlastsql());exit();
			$pagedata['store']=$store;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 账户提现  2017-05-14
	 */
	public function CashManager(){
		if (IS_POST) {
			$Param=$_POST;
		}else{
			$Param=$_GET;
		}
		unset($Param['v']);
		unset($Param['p']);
		$whereStr="token='{$this->token}' and stoken='{$this->stoken}'";
		if ($Param && count($Param)>0) {
			if ($Param['Status']) {
				$whereStr.=" and Status='{$Param['Status']}'";
			}
			if ($Param['StartDate'] && $Param['EndDate']) {
				$whereStr.=" and CreateDate BETWEEN '{$Param['StartDate']}' and '{$Param['EndDate']}'";
			}
		}
		$pagedata['Money']=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField("Money");
		$count=M()->table('RS_MerCutDetail')->where($whereStr)->count();
		// var_dump(M()->getlastsql());
		$page = new \Think\Page($count,20,$Param);
		$lists= M()->table('RS_MerCutDetail')->where($whereStr)->field("ID,Money,CONVERT(varchar(20),CreateDate,120) as CreateDate,IdCard,IdName,GetName,tel,CONVERT(varchar(20),ExecDate,120) as ExecDate,(CASE Status WHEN '0' THEN '待处理' WHEN '1' THEN '已处理' END) AS Status,Status as Astu")->limit($page->firstRow.','.$page->listRows)->order('CreateDate desc')->select();
		// var_dump(M()->getlastsql());
		$pagedata['Param']=$Param;
		$pagedata['lists']=$lists;
		$bkinfo=M()->table('RS_MerchantBank')->where("stoken='%s' and IsCheck='1'",$this->stoken)->find();
		if ($bkinfo) {
			$pagedata['hasbk']=1;
		}else{
			$pagedata['hasbk']=0;
		}
		$pagedata['page']=$page->show();
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 提现处理  2017-05-14
	 */
	public function getmoney(){
		if (IS_POST) {
			$IdInfo=M()->table('RS_MerchantBank')->where("stoken='%s' and IsCheck='1'",$this->stoken)->find();
			if ($IdInfo && $IdInfo['IdCard'] && $IdInfo['IdName'] && $IdInfo['BankName']) {
				$money=$_POST['money'];
				$RecoreData=array();
				$RecoreData['Money']=$money;
				$RecoreData['stoken']=$this->stoken;
				$RecoreData['IdCard']=$IdInfo['IdCard'];
				$RecoreData['IdName']=$IdInfo['BankName'];
				$RecoreData['IdType']=$IdInfo['IsOpen'];
				$RecoreData['GetName']=$IdInfo['IdName'];
				$RecoreData['tel']=$IdInfo['tel'];
				$RecoreData['token']=$this->token;
				M()->startTrans();
				$sres=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->setDec('Money',$money);
				$mres=M()->table('RS_MerCutDetail')->add($RecoreData);
				if ($sres && $mres) {
					M()->commit();
					$msg['status']='success';
					$RecoreData['Status']='待处理';
					$RecoreData['CreateDate']=date('Y-m-d H:i:s',time());
					$RecoreData['ID']=$mres;
					$filename = './Public/message.json';
	                $res = json_decode(file_get_contents($filename),true);
	                $messageData['mobiles'] = $res['withdraw'];
	                $messageData['content'] = "您有的新的商户提现申请,请及时处理";
	                $this->SendMessage($messageData);
					$msg['data']=$RecoreData;
				}else{
					M()->rollback();
					$msg['status']='error';
					$msg['info']='处理失败';
				}
			}else{
				$msg['status']='error';
				$msg['info']='银行账户信息不全，请完善银行账户信息后申请提现';
			}
			echo json_encode($msg);
		}
	}

	/**
	 * 取消申请  2017-05-14
	 */
	public function cancelMoney(){
		$ID=$_POST['ID'];
		$minfo=M()->table('RS_MerCutDetail')->where("ID=%d",$ID)->find();
		M()->startTrans();
		$mres=M()->table('RS_MerCutDetail')->where("ID=%d",$ID)->delete();
		$sres=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->setInc('Money',$minfo['Money']);

		if ($mres && $sres) {
			M()->commit();
			$msg['status']='success';
			$msg['Money']=M()->table("RS_Store")->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField("Money");
		}else{
			M()->rollback();
			$msg['status']='error';
			$msg['info']='处理失败';
		}
		echo json_encode($msg);
	}

	/**
	 * 账户金额变动记录   2017-05-14
	 */
	public function storemoney(){
		if (IS_POST) {
			
		}else{

		}
		$whereStr="token='{$this->token}' and stoken='{$this->stoken}'";
		$count=M()->table('RS_StoreMoneyManager')->where($whereStr)->count();
		$page = new \Think\Page($count,20);
		$lists= M()->table('RS_StoreMoneyManager')->where($whereStr)->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,CONVERT(float(53),ISNULL(Money,0),120) as Money,(CASE Type WHEN 'add' THEN '收入' WHEN 'less' THEN '支出' END) as Type,(CASE Useage WHEN 'XS' THEN '销售结算' WHEN 'YL' THEN '引流结算' WHEN 'JS' THEN '提现支出' WHEN 'PS' THEN '配送员提现' WHEN 'CKDIFF' THEN '缺货退款' END) as Useage,ID,Useage as Uname")->limit($page->firstRow.','.$page->listRows)->order("CreateDate desc")->select();
		// var_dump(M()->getlastsql());
		$pagedata['lists']=$lists;
		$pagedata['page']=$page->show();
		$pagedata['MoneyInfo']=M()->table('RS_Store')->where($whereStr)->find();
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 核销员添加管理
	 */
	public function cancel(){
		if (IS_POST) {
			
		}else{
			$count=M()->table('RS_Cancel')->where("stoken='%s'",$this->stoken)->count();
			$page = new \Think\Page($count,20);
			$lists=M()->table('RS_Cancel')->where("stoken='%s'",$this->stoken)->field("id,CONVERT(varchar(20),CreateDate,120) as CreateDate,username")->limit($page->firstRow.','.$page->listRows)->select();
			$pagedata['page']=$page->show();
			$pagedata['lists']=$lists;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 删除核销员
	 */
	public function delcancel(){
		$id=$_GET['id'];
		if (M()->table('RS_Cancel')->where('id=%d',$id)->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	/**
	 * 更新验证码
	 */
	public function getcancelverify(){
		$verify=$this->getStr(0,4,false);
		$time=date('Y-m-d H:i:s',time()+3600);
		if (M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->setField(array("verify"=>$verify,'verify_time'=>$time))) {
			$msg['status']='success';
			$msg['verify']=$verify;
		}else{
			$msg['status']='error';
		}
		// var_dump(M()->getlastsql());exit();
		// var_dump($_SERVER);
		echo json_encode($msg);
	}
	/**
	 * 二维码输出
	 */
	public function getCancelQr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$level="L";
		$size=4;
		$url='https://'.$_SERVER['HTTP_HOST'].U('Admin/Base/addCancel').'?type=1&stoken='.$this->stoken;
		\QRcode::png($url,false,$level,$size,'2');
	}

	/**
	 * getBankinfo
	 */
	public function getBankinfo(){
		$data=M()->table('RS_BankList')->where("Province='%s' and City='%s' and BankName='%s'",$_POST)->select();
		if ($data && count($data)>0) {
			$msg['status']='success';
			$msg['data']=$data;
		}else{
			$msg['status']='error';
		}
		echo json_encode($msg);
	}


	/**
	 * 资金变动明细
	 */
	public function showmlist(){
		$ID=$_POST['ID'];
		$info=M()->table('RS_StoreMoneyManager')->where("ID=%d",$ID)->find();
		$oids=unserialize(str_replace('\"', '"', $info['Ext']));
		if ($info['Useage']=='XS') {
			$where="OrderId IN ('".implode("','", $oids)."')";
		}else{
			$where="OrderId IN ('".implode("','", $oids)."') and CutMoney>0";
		}
		$oinfo=M()->table('RS_Order')->where($where)->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,OrderId,CONVERT(float(53),Price,120) as Price,CONVERT(float(53),CutMoney,120) as CutMoney,(CASE PayName WHEN 'T' THEN '微信支付' WHEN 'ALIPAY' THEN '支付宝' WHEN 'XX' THEN '线下支付' WHEN 'POSXJ' THEN 'POS端现金支付' ELSE '其他' END) AS PayName,(CASE DisMoney WHEN 0 THEN '0' ELSE '1' END) as IsDis")->select();
		if ($oinfo && count($oinfo)>0) {
			$msg['status']='success';
			$msg['data']=$oinfo;
		}else{
			$msg['status']='error';
			// $msg['info']=M()->getlastsql();
			$msg['info']='查询失败';
		}
		echo json_encode($msg);
	}

	public function ordercut(){
		$sinfo=M()->table('RS_Store')->where("stoken='%s'",$this->stoken)->field("CONVERT(varchar(20),FreeStime,120) as FreeStime,CONVERT(varchar(20),FreeEtime,120) as FreeEtime,CutNum,IsFreeCut,MercutDate")->find();
		if (IS_POST) {
			$whereStr="o.Status in (4,10) and o.Pstoken=1 and o.PayName in ('T','ALIPAY')";
			$stime='1970-01-01 00:00:01';
			$whereStr.=" and o.stoken='{$this->stoken}'";
			if ($_POST['endtime']) {
				$whereStr.=" and o.CreateDate BETWEEN '{$stime}' and '{$_POST['endtime']}'";
			}
			$spmoney=0;
			if ($sinfo['IsFreeCut']==1) {
				$money1=M()->query("SELECT SUM(o.Price) as cutmoney FROM RS_Order o WHERE {$whereStr} and o.DisMoney<>0 and o.CreateDate NOT BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}'")[0]['cutmoney'];
				// var_dump(M()->getlastsql());exit();
				if ($sinfo['FreeStime']<$_POST['endtime'] && $sinfo['FreeEtime']<$_POST['endtime']) {
					$spmoney=M()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
				}elseif ($sinfo['FreeStime']<$_POST['endtime'] && $sinfo['FreeEtime']>=$_POST['endtime']) {
					$spmoney=M()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$_POST['endtime']}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
				}
			}else{
				$money1=M()->query("SELECT SUM(o.Price) as cutmoney FROM RS_Order o WHERE {$whereStr} and o.DisMoney<>0")[0]['cutmoney'];
			}
			// $money2=M()->query("SELECT SUM(ol.Money) as money FROM RS_Order o LEFT JOIN RS_OrderList ol ON o.OrderId=ol.OrderId WHERE {$whereStr} AND ol.DisMoney=0");
			$money2=M()->query("SELECT SUM(o.Price) as money FROM RS_Order o WHERE {$whereStr} and o.DisMoney=0")[0]['money'];
			$Allmoney=0;
			$cut=0;
			if ($sinfo['CutNum'] && $sinfo['CutNum']>0) {
				$tmpmoney=floatval($money1)*(100-intval($sinfo['CutNum']))/100;
				$cut=floatval($money1)-floatval($tmpmoney);
				$Allmoney=floatval($money2)+floatval($tmpmoney);
			}else{
				$tmpmoney=floatval($money1);
				$Allmoney=floatval($money2)+floatval($tmpmoney);
			}
			$Allmoney=$Allmoney+floatval($spmoney);
			$Allmoney=round($Allmoney,2);
			$cut=round($cut,2);
			$info['Allmoney']=$Allmoney;
			$info['Lastcut']=$sinfo['MercutDate'];
			$info['cut']=$cut;
			$info['CutNum']=$sinfo['CutNum'];
			$pagedata['info']=$info;
			$data=array('strtime'=>$stime,'endtime'=>$_POST['endtime']);
			$pagedata['data']=$data;
			$bkinfo=M()->table('RS_MerchantBank')->where("stoken='%s' and IsCheck='1'",$this->stoken)->find();
			$pagedata['bkinfo']=$bkinfo;
		}else{

		}
		$pagedata['sinfo']=$sinfo;
		$this->assign($pagedata);
		$this->display();
	}




	/**
	 * 结算详情
	 */
	public function getdetail(){
		$whereStr="Status in (4,10) and Pstoken=1 and PayName IN ('T','ALIPAY') and stoken='{$this->stoken}'";
		$stime='1970-01-01 08:00:00';
		if ($_GET['etime']) {
			$whereStr.=" and CreateDate BETWEEN '{$stime}' and '{$_GET['etime']}'";
		}
		$count=M()->table('RS_Order')->where($whereStr)->count();
		$page=new \Think\Page($count,15,$_GET);
		$infolist=M()->table('RS_Order')->where($whereStr)->field("(CASE PayName WHEN 'T' THEN '微信支付' WHEN 'ALIPAY' THEN '支付宝' WHEN 'XJ' THEN '现金支付' END) as PayName,OrderId,CONVERT(float(50),Price,120) as Price,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE DisMoney WHEN 0 THEN '0' ELSE '1' END) as IsDis")->limit($page->firstRow.','.$page->listRows)->select();
		$pagedata['page']=$page->show();
		$pagedata['infolist']=$infolist;
		$this->assign($pagedata);
		$this->display();
	}


	public function cuted(){
		if (IS_POST) {
			$sinfo=M()->table('RS_Store')->where("stoken='%s'",$this->stoken)->field("CONVERT(varchar(20),FreeStime,120) as FreeStime,CONVERT(varchar(20),FreeEtime,120) as FreeEtime,CutNum,IsFreeCut,MercutDate,Invcode")->find();
			$stime='1970-01-01 00:00:01';
			$etime=$_POST['etime'];
			$whereStr="o.stoken='{$this->stoken}' and o.Status in (4,10) and o.Pstoken=1 and o.CreateDate BETWEEN '{$stime}' and '{$etime}'";
			$spwhere="stoken='{$this->stoken}' and Status in (4,10) and Pstoken=1 and CreateDate BETWEEN '{$stime}' and '{$etime}'";
			$spmoney=0;
			if ($sinfo['IsFreeCut']==1) {
				$cutmoney=M()->table('RS_Order o')->where($whereStr." and o.DisMoney<>0 and o.CreateDate NOT BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}'")->sum('o.Price');
				if ($sinfo['FreeStime']<$etime && $sinfo['FreeEtime']<$etime) {
					$spmoney=M()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
				}elseif ($sinfo['FreeStime']<$etime && $sinfo['FreeEtime']>=$etime) {
					$spmoney=M()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$etime}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
				}
			}else{
				$cutmoney=M()->table('RS_Order o')->where($whereStr.' and o.DisMoney<>0')->sum('o.Price');
			}
			$money=M()->table('RS_Order o')->where($whereStr.' and o.DisMoney=0')->sum('o.Price');
			$Allmoney=0;
			$cut=0;
			if ($sinfo['CutNum'] && $sinfo['CutNum']>0) {
				$tmpmoney=floatval($cutmoney*(100-intval($sinfo['CutNum']))/100);
				$Allmoney=floatval($money)+$tmpmoney;
				$cut=floatval($cutmoney*intval($sinfo['CutNum'])/100);
			}else{
				$Allmoney=floatval($money)+floatval($cutmoney);
			}
			$Allmoney=$Allmoney+floatval($spmoney);
			$tuier=false;
			if ($sinfo['Invcode']) {
				$tuier=M()->table('RS_Tuier')->where("Invcode='%s'",$sinfo['Invcode'])->find();
			}
			// var_dump($sinfo['Invcode']);
			// var_dump(M()->getlastsql());exit();
			$cut=round($cut,2);
			$Allmoney=round($Allmoney,2);
			$alloids=M()->table('RS_Order o')->where($whereStr)->getField("OrderId",true);
			$os=$ms=$mms=$ts=$tms=true;
			M()->startTrans();
			//更新订单
			$os=M()->table('RS_Order')->where($spwhere)->setField("Pstoken",'2');
			// $this->LOGS(M()->getlastsql());
			$now=strtotime($etime);
			$ms=M()->execute("UPDATE RS_Store SET Money=Money+{$Allmoney},TotalMoney=TotalMoney+{$Allmoney},MercutDate={$now} WHERE stoken='{$this->stoken}'");
			$mmdb=array();
			$mmdb['Money']=$Allmoney;
			$mmdb['Type']='add';
			$mmdb['Useage']='XS';
			$mmdb['token']=$this->token;
			$mmdb['stoken']=$this->stoken;
			$mmdb['Ext']=serialize($alloids);
			$mms=M()->table('RS_StoreMoneyManager')->add($mmdb);
			if ($tuier && count($tuier)>0 && $cut>0) {
				$tuiercut=M()->table('RS_LevelInfo')->where("LevelType='TUIER' and LevelLabel='{$tuier['Level']}'")->getField("LevelCut");
				if ($tuiercut && $tuiercut>0) {
					$tcut=floatval($cut*floatval($tuiercut)/100);
					//有推广人
					$ts=M()->execute("UPDATE RS_Tuier SET TotalMoney=TotalMoney+{$tcut},Money=Money+{$tcut} WHERE ID={$tuier['ID']}");
					$tmsdb=array();
					$tmsdb['TuierId']=$tuier['ID'];
					$tmsdb['TuierAccount']=$tuier['userName'];
					$tmsdb['Type']='add';
					$tmsdb['Money']=$tcut;
					$tmsdb['stoken']=$this->stoken;
					$tms=M()->table('RS_TuiMoneyManager')->add($tmsdb);					
				}
			}
			//更新金额
			//更新金额变动记录
			//更新推广人金额
			//更新推荐人金额变动记录
			if ($os && $ms && $mms && $ts && $tms) {
				M()->commit();
				$msg['status']='success';
			}else{
				M()->rollback();
				$msg['status']='error';
				$msg['info']='处理失败';
				$this->LOGS("os=$os && ms=$ms && mms=$mms && ts=$ts && tms=$tms");
			}
		}else{
			$msg['status']='error';
			$msg['info']='非法操作';
		}
		$this->ajaxReturn($msg);
	}



	/**
	 * 商户结算相关
	 */
	public function storecut(){
		if (IS_POST) {
			$type=$_POST['cutype'];
			$stoken=$_POST['sid'];
			$sinfo=M()->table('RS_Store')->where("stoken='%s'",$stoken)->find();
			if ($type=='1') {
				$whereStr="o.Status in (4,10) and o.Pstoken=1 and o.PayName in ('T','ALIPAY')";
				if ($_POST['sid']) {
					$whereStr.=" and o.stoken='{$stoken}'";
				}
				if ($_POST['strtime'] && $_POST['endtime']) {
					$whereStr.=" and o.CreateDate BETWEEN '{$_POST['strtime']}' and '{$_POST['endtime']}'";
				}
				$info=M()->table('RS_Store s')->join("LEFT JOIN RS_Order o ON s.stoken=o.stoken")->join("LEFT JOIN RS_MerchantBank mb ON mb.stoken=s.stoken")->where($whereStr)->field("s.storename,CONVERT(float(53),SUM(o.Price-o.TruepsMoney),120) as allmoney,s.tel,s.CutDate,mb.IdCard,s.stoken")->group("s.storename,s.tel,s.CutDate,mb.IdCard,s.stoken")->find();
				$allps=M()->table('RS_Order o')->where($whereStr." AND o.Freight<>o.TruepsMoney")->sum('o.TruepsMoney');
				$trueallps=floatval($allps)*floatval($sinfo['Fpsper'])/100;
				$this->LOGS($allps.'...'.$trueallps.'///'.$info['allmoney']);
				$allmoney=floatval($info['allmoney'])+$trueallps;
				$allmoney=floatval($allmoney)*(100-$sinfo['CutNum'])/100;
				$info['allmoney']=$allmoney;
			}
			if ($type=='2') {
				$whereStr="o.Status in (4,10) and o.IsCuted='1'";
				if ($_POST['sid']) {
					$whereStr.=" and o.SceneContent='{$stoken}'";
				}
				if ($_POST['strtime'] && $_POST['endtime']) {
					$whereStr.=" and o.CreateDate BETWEEN '{$_POST['strtime']}' and '{$_POST['endtime']}'";
				}
				$info=M()->table('RS_Store s')->join("LEFT JOIN RS_Order o ON s.stoken=o.SceneContent")->join("LEFT JOIN RS_MerchantBank mb ON mb.stoken=s.stoken")->where($whereStr)->field("s.storename,CONVERT(float(53),SUM(o.CutMoney),120) as allmoney,s.tel,s.CutDate,mb.IdCard,s.stoken")->group("s.storename,s.tel,s.CutDate,mb.IdCard,s.stoken")->find();
				$allmoney=floatval($info['alllmoney'])*(100-$sinfo['CutNum'])/100;
				$info['allmoney']=$allmoney;
			}
			$pagedata['info']=$info;
			$pagedata['data']=$_POST;
			$pagedata['cutype']=$type;
		}else{

		}
		$stores=M()->table('RS_Store')->where("IsCheck='1' and stoken<>'0'")->field("storename,stoken")->select();
		$pagedata['stores']=$stores;
		$this->assign($pagedata);
		$this->display();
	}



}
