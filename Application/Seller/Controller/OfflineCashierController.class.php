<?php
namespace Seller\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class OfflineCashierController extends BaseController{
	public $model;
	public $apiurl;
	public $key;
	public $merchantNo;
	public $path;
	public function _initialize(){
		$this->model=M();
		$this->apiurl='http://api.tongxingpay.com/txpayApi/offLine';
		$this->key='d80efe63192b4b7ebf9a30d78075b8fa';
		$this->merchantNo='TX0001455';
		$this->path='./Public/Admin/tporder/';
	}
	/**
	 * 收银台
	 */
	public function index(){
		session('cashData',null);
		$cashData['token']=$_GET['token'];
		$cashData['stoken']=$_GET['stoken'];
		$cashData['storeid']=$_GET['sid'];
		session('cashData',$cashData);
		$this->assign($cashData);
		$this->display();
	}

	/**
	 * 处理页
	 */
	public function cashier(){
		session('tempOrder',null);
		$tempOrder=array();
		$cashData=session('cashData');
		if ($cashData['stoken'] && $cashData['token'] && $cashData['storeid']) {
			
		}else{
			exit('缺少参数');
		}
		$OrderId='E'.date('YmdHis',time()).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
		$tempOrder['OrderId']=$OrderId;
		$tempOrder['RecevingName']='门店自助结算';
		$tempOrder['RecevingCity']='门店自助结算';
		$tempOrder['RecevingArea']='门店自助结算';
		$tempOrder['RecevingAddress']='门店自助结算';
		$tempOrder['RecevingProvince']='门店自助结算';
		$tempOrder['RecevingPhone']='0000000000';
		$tempOrder['MemberId']=$cashData['storeid'];
		$tempOrder['Count']=0;
		$tempOrder['Coupon']=0;
		$tempOrder['PayName']='T';
		$tempOrder['Price']=0;
		$tempOrder['Status']=1;
		$tempOrder['CreateDate']=date('Y-m-d H:i:s',time());
		$tempOrder['LastUpdateDate']=date('Y-m-d H:i:s',time());
		$tempOrder['token']=$cashData['token'];
		$tempOrder['stoken']=$cashData['stoken'];
		session('tempOrder',$tempOrder);
		$filename=$OrderId.'.json';
		if (!is_dir($this->path)) {
			mkdir($this->path);
		}
		file_put_contents($this->path.$filename, json_encode($tempOrder));

		$this->assign("OrderId",$OrderId);
		$this->display();
	}

	/**
	 * 拉取商品信息
	 */
	public function getpro(){
		$isneworder=true;
		$OrderId=$_POST['OrderId'];
		if ($oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$OrderId)->find()) {
			$isneworder=false;
		}else{
			$filename=$this->path.$OrderId.'.json';
			$oinfofromdata=json_decode(file_get_contents($filename),true);

			// $oinfofromdata=$this->model->table('RS_Order')->where("OrderId='%s'",$OrderId)->find();
			if ($oinfofromdata) {
				$oinfo=$oinfofromdata;
			}else{
				$result['status']='error';
				$result['type']='refrePage';
				$result['info']='页面过期，即将刷新';
				echo json_encode($result);exit();
			}
		}
		$barcode=$_POST['barcode'];
		if (strlen($barcode)==18 && in_array(substr($barcode, 0,2), array(10,11,12,13,14,15))) {
			//微信付款码
			/**
			 * 新单->创建订单->付款
			 * 旧单->更新支付方式->付款
			 */
			if ($isneworder) {
				$oinfo['PayName']='T';
				file_put_contents($filename, json_encode($oinfo));
				$CreateParams['PayName']='T';
				$CreateParams['oid']=$OrderId;
				$CreateParams['auth_code']=$barcode;
				$this->getorderinfo($CreateParams);
			}else{
				$opdata['PayName']='T';
				$opdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				if ($this->model->table('RS_Order')->where("OrderId='%s'",$OrderId)->setField($opdata)) {
					$PayParams['oid']=$OrderId;
					$PayParams['auth_code']=$barcode;
					$this->WxScanPay($PayParams);
				}else{
					$result['status']='error';
					$result['info']='支付方式更新失败';
					echo json_encode($result);exit();
				}
			}
		}elseif (strlen($barcode)=='39' && substr($barcode, 0,7)=='GETCASH') {
			//现金支付
			if ($isneworder) {
				$oinfo['PayName']='POSXJ';
				file_put_contents($filename, json_encode($oinfo));
				$CreateParams['PayName']='POSXJ';
				$CreateParams['oid']=$OrderId;
				$CreateParams['auth_code']=$barcode;
				$this->getorderinfo($CreateParams);
			}else{
				$opdata['PayName']='POSXJ';
				$opdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				if ($this->model->table('RS_Order')->where("OrderId='%s'",$OrderId)->setField($opdata)) {
					$PayParams['oid']=$OrderId;
					$PayParams['auth_code']=$barcode;
					$this->PosXjPay($PayParams);
				}else{
					$result['status']='error';
					$result['info']='支付方式更新失败';
					echo json_encode($result);exit();
				}
			}
		}else{
			$result=array();
			if ($pro=$this->model->table('RS_ProductList pl')->join("LEFT JOIN RS_Product p ON pl.ProId=p.ProId")->where("pl.ProIdInputCard='%s' and IsDelete=%d",array($barcode,0))->field("p.ProName+'_'+pl.ProSpec1 as ProName,p.ProLogoImg,p.ProTitle,pl.ProIdInputCard as barcode,pl.Price as price,pl.ProIdCard,pl.ProId,pl.ProSpec1 as Spec")->find()) {
				$now=date('Y-m-d H:i:s',time());
				$spinfo=$this->model->table("RS_ProductOnSale")->where("token='{$oinfo['token']}' and ProIdCard='{$pro['ProIdCard']}' and etime>'{$now}' and stime<'{$now}'")->find();
				// $this->LOGS(M()->getlastsql());
				//处理后事
				$tempOrderList=$oinfo['OrderList'];
				$isneworderlist=true;
				if (array_key_exists($pro['ProIdCard'], $tempOrderList)) {
					$thisListInfo=$tempOrderList[$pro['ProIdCard']];				
					$isneworderlist=false;
					$updata=array();
					$OrderUpdata=array();
					$thisListInfo['Count']=intval($thisListInfo['Count'])+1;
					$olcoupon=0;
					if ($spinfo) {
						$thisListInfo['Money']=floatval($thisListInfo['Money'])+floatval($spinfo['sprice']);
						// $updata['Money']=floatval($tempOrderList['Money'])+floatval($spinfo['sprice']);
						$olcoupon=floatval($pro['price'])-floatval($spinfo['sprice']);
						$trueprice=$spinfo['sprice'];
						$oinfo['Coupon']+=$olcoupon;
						//计算订单优惠总额
					}else{
						$thisListInfo['Money']=floatval($thisListInfo['Money'])+floatval($pro['price']);
						// $updata['Money']=floatval($tempOrderList['Money'])+floatval($pro['price']);
						$trueprice=$pro['price'];
					}
					$oinfo['Count']=intval($oinfo['Count'])+1;
					$oinfo['Price']+=floatval($trueprice);
					$oinfo['OrderList'][$pro['ProIdCard']]=$thisListInfo;

					$pageOrder=array();
					$pageOrder['Count']=$oinfo['Count'];
					$pageOrder['Price']=$oinfo['Price'];
					$pageOrder['Coupon']=$oinfo['Coupon'];
					$pageOrder['OrderId']=$OrderId;
					$pageOrderList=array();
					$pageOrderList['ProIdCard']=$pro['ProIdCard'];
					$pageOrderList['Count']=$thisListInfo['Count'];
					$pageOrderList['Money']=$thisListInfo['Money'];
					$pageOrderList['isnew']=2;
					$pageOrderList['ProIdCard']=$pro['ProIdCard'];
					$pagedata['OrderInfo']=$pageOrder;
					$pagedata['OrderList']=$pageOrderList;
					$result['status']='success';
					$result['type']='proinfo';
					$result['data']=$pagedata;
					file_put_contents($filename, json_encode($oinfo));
				}else{
					//新的子单
					$OrderListDB['OrderId']=$OrderId;
					$OrderListDB['ProId']=$pro['ProId'];
					$OrderListDB['ProIdCard']=$pro['ProIdCard'];
					$OrderListDB['Spec']=$pro['Spec'];
					$OrderListDB['Count']=1;
					$OrderListDB['ProName']=$pro['ProName'];
					$OrderListDB['ProLogoImg']=$pro['ProLogoImg'];
					$OrderListDB['ProTitle']=$pro['ProTitle'];
					$olcoupon=0;
					if ($spinfo) {
						$OrderListDB['Price']=$spinfo['sprice'];
						$OrderListDB['Money']=$spinfo['sprice'];
						$olcoupon=floatval($pro['price'])-floatval($spinfo['sprice']);
					}else{
						$OrderListDB['Price']=$pro['price'];
						$OrderListDB['Money']=$pro['price'];
					}
					$OrderListDB['Spec']=$pro['Spec'];
					$oinfo['Count']+=1;
					$oinfo['Price']+=floatval($OrderListDB['Price']);
					$oinfo['Coupon']+=$olcoupon;
					$oinfo['OrderList'][$pro['ProIdCard']]=$OrderListDB;


					$pageOrder=array();
					$pageOrder['Count']=$oinfo['Count'];
					$pageOrder['Price']=$oinfo['Price'];
					$pageOrder['Coupon']=$oinfo['Coupon'];
					$pageOrder['OrderId']=$OrderId;
					$pageOrderList=array();
					$pageOrderList['ProIdCard']=$pro['ProIdCard'];
					$pageOrderList['Count']=1;
					$pageOrderList['Money']=$OrderListDB['Money'];
					$pageOrderList['isnew']=1;
					$pageOrderList['ProIdCard']=$pro['ProIdCard'];
					$pageOrderList['ProName']=$pro['ProName'];
					$pageOrderList['Barcode']=$barcode;
					$pageOrderList['Price']=$pro['price'];
					$pageOrderList['Coupon']=$olcoupon;
					$pagedata['OrderInfo']=$pageOrder;
					$pagedata['OrderList']=$pageOrderList;
					$result['status']='success';
					$result['data']=$pagedata;
					$result['type']='proinfo';
					file_put_contents($filename, json_encode($oinfo));
				}
			}else{
				$result['status']='error';
				$result['info']='未查找到该商品';
			}
			echo json_encode($result);
		}
		
	}

	/**
	 * 修改商品数量
	 */
	public function changnum(){
		$pid=$_POST['pid'];
		$oid=$_POST['oid'];
		$filename=$this->path.$oid.'.json';
		$oinfo=json_decode(file_get_contents($filename),true);
		$type=$_POST['type'];
		$thisListInfo=$oinfo['OrderList'][$pid];
		$Price=$this->model->table('RS_ProductList')->where("ProIdCard='%s'",$pid)->getField('Price');
		$now=date('Y-m-d H:i:s',time());
		$sprice=$this->model->table("RS_ProductOnSale")->where("token='{$oinfo['token']}' and ProIdCard='{$pid}' and etime>'{$now}' and stime<'{$now}'")->getField('sprice');
		$this->model->startTrans();
		$coupon=0;
		if ($sprice) {
			$trueprice=$sprice;
			$coupon=floatval($Price)-floatval($sprice);
		}else{
			$trueprice=$Price;
		}
		if ($type=='add') {
			$oinfo['Count']+=1;
			$oinfo['Price']+=floatval($trueprice);
			$oinfo['Coupon']+=floatval($coupon);
			$thisListInfo['Count']+=1;
			$thisListInfo['Money']+=floatval($trueprice);
		}elseif ($type=='less') {
			$oinfo['Count']-=1;
			$oinfo['Price']-=floatval($trueprice);
			$oinfo['Coupon']-=floatval($coupon);
			$thisListInfo['Count']-=1;
			$thisListInfo['Money']-=floatval($trueprice);
		}
		$oinfo['OrderList'][$pid]=$thisListInfo;
		file_put_contents($filename, json_encode($oinfo));
		
		$pagedata['OrderList']=$thisListInfo;
		$pagedata['OrderInfo']=$oinfo;
		$result['status']='success';
		$result['type']='proinfo';
		$result['data']=$pagedata;


		echo json_encode($result);
	}


	/**
	 * 删除商品
	 */
	public function delpro(){
		$oid=$_POST['oid'];
		$pid=$_POST['pid'];
		$filename=$this->path.$oid.'.json';
		$oinfo=json_decode(file_get_contents($filename),true);
		$olinfo=$oinfo['OrderList'];

		$thisListInfo=$olinfo[$pid];
		$oinfo['Count']-=$thisListInfo['Count'];
		$oinfo['Price']-=floatval($thisListInfo['Money']);
		//优惠结算
		$Price=$this->model->table('RS_OrderList')->where("ProIdCard='%s'",$pid)->getField('Price');
		$sprice=$this->model->table('RS_ProductOnSale')->where("ProIdCard='%s' and stoken='%s'",array($pid,$oinfo['stoken']))->getField('sprice');
		$coupon=0;
		if ($sprice) {
			$coupon+=floatval($Price-$sprice)*$thisListInfo['Count'];
		}
		$oinfo['Coupon']-=$coupon;
		unset($olinfo[$pid]);
		$oinfo['OrderList']=$olinfo;
		file_put_contents($filename, json_encode($oinfo));
		$OrderInfo=array('Price'=>$oinfo['Price'],'Count'=>$oinfo['Count']);
		$result['data']=$OrderInfo;
		$result['status']='success';
		$result['type']='proinfo';
		
		echo json_encode($result);
	}


	/**
	 * 获取订单信息
	 */
	public function getorderinfo($CreateParams){
		$oid=$CreateParams['oid'];
		$auth_code=$CreateParams['auth_code'];
		$filename=$this->path.$oid.'.json';
		$oinfo=json_decode(file_get_contents($filename),true);
		// var_dump($oinfo);
		$OrderList=$oinfo['OrderList'];
		unset($oinfo['OrderList']);
		$this->model->startTrans();
		$ores=$olres=true;
		$ores=$this->model->table('RS_Order')->add($oinfo);
		foreach ($OrderList as $ol) {
			if (!$this->model->table('RS_OrderList')->add($ol)) {
				$olres=false;
				break;
			}
		}
		if ($ores && $olres) {
			$this->model->commit();
			unlink($filename);
			$PayParams['oid']=$oid;
			$PayParams['auth_code']=$auth_code;
			if ($CreateParams['PayName']=='T') {
				$this->WxScanPay($PayParams);
			}else{
				$this->PosXjPay($PayParams);				
			}
		}else{
			$this->model->rollback();
			$result['status']='error';
			$result['info']='订单处理失败';
			echo json_encode($result);
		}
		// $oinfo=$this->model->table('RS_Order')->where("OrderId='%s' and stoken='%s'",array($oid,$oinfo['stoken']))->field('Count,Price,Coupon')->find();
		// if (!$oinfo) {
		// 	$result['status']='error';
		// 	$result['info']='获取订单信息失败';		
		// }else{
		// 	$oinfo['son']=$this->model->table('RS_OrderList ol')->join("LEFT JOIN RS_Product p ON ol.ProId=p.ProId")->where("ol.OrderId='%s' and ol.IsDelete=0",$oid)->field("p.ProName+'_'+CONVERT(varchar(120),ol.Spec,120) as ProName,ol.Price,ol.Count,ol.Money")->select();
		// 	// $this->LOGS(M()->getlastsql());
		// 	$result['status']='success';
		// 	$result['data']=$oinfo;
		// }
		// echo json_encode($result);

	}

	/**
	 * 获取用户信息
	 */
	public function getmember(){
		$minfo=$_POST['memberinfo'];
		$oid=$_POST['oid'];
		$openid=substr(str_replace('.html', '', $minfo), -28,28);
		$result=array();
		if ($uinfo=$this->model->table('RS_Member')->where("OpenId='%s'",$openid)->find()) {
			if (!$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find()) {
				$oinfo=session('tempOrder');
				$oinfo['MemberId']=$openid;
				$oinfo['OpenId']=$openid;
				session('tempOrder',$oinfo);
				$result['status']='success';
				$result['data']=$uinfo;
			}else{
				if ($this->model->table('RS_Order')->where("OrderId='%s'",$oid)->setField('MemberId',$openid)) {
					$result['status']='success';
					$result['data']=$uinfo;
				}else{
					$result['status']='error';
					$result['info']='订单信息更新失败';
				}

			}
		}else{
			$result['status']='error';
			$result['info']='未查找到此会员';
		}
		echo json_encode($result);
	}

	/**
	 * 现金支付
	 */
	public function PosXjPay($PayParams){
		$cashData=session('cashData');
		$oid=$PayParams['oid'];
		$auth_code=$PayParams['auth_code'];
		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$true_code=$this->model->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->getField('CashCode');
		if (str_replace('GETCASH', '', $auth_code)==$true_code) {
			$newcode=md5('GETCASHCODE'.mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).time());
			//积分处理？？
			//支付成功处理
			$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
			$money=floatval($oinfo['Price']);
			$pros=$this->model->query("SELECT p.ProName+'*'+CONVERT(varchar(20),ol.Count,120) as ProName FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ol.OrderId='{$oid}'");
			$pstr='';
			// $this->LOGS('商品信息--->'.M()->getlastsql().json_encode($pros));
			foreach ($pros as $ps) {
				$pstr.=$ps['ProName'].',';
			}
			$ostatus=$oinfo['Status'];
			if ($ostatus=='1') {
				$sinfo=$this->model->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->find();
				// $
				$CutNum=floatval($sinfo['CutNum'])/100;  //商户扣点
				$WXCUT=floatval(C('WXCUT'))/100;    //微信支付扣点
				$PayCom=floatval($money)*$WXCUT;
				$MerCom=0;
				if ($CutNum>0) {
					$MerCom=floatval($money)*$CutNum;
				}
				$OverCom=floatval($money)-floatval($PayCom)-floatval($MerCom);
				//订单更新信息
				$OrderUpdata['PayCom']=$PayCom;
				$OrderUpdata['MerCom']=$MerCom;
				$OrderUpdata['OverCom']=$OverCom;
				$OrderUpdata['PayDate']=date('Y-m-d H:i:s',time());
				$OrderUpdata['GetDate']=date('Y-m-d H:i:s',time());
				$OrderUpdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$OrderUpdata['Status']=10;

				if ($this->model->table('RS_Order')->where("OrderId='%s'",$oid)->setField($OrderUpdata)) {
					$this->model->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->setField('LastBuyTime',date('Y-m-d H:i:s',time()));
					$return['status']='success';
					$return['type']='paying';
					$tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8).'_'.$sinfo['id'];
					if ($sinfo['MsgRecever']) {
						$MsgData=array();
						$content=array();
						$MsgData['touser']=$sinfo['MsgRecever'];
						$MsgData['template_id']='o3GHT9fgn7xto8dcLMpV2TIdzLUdcpb0uaQvUuvMdN8';
						$MsgData['first']=array('value'=>'POS收银有新订单~','color'=>'#000000');
						$MsgData['remark']=array('value'=>'登陆商户管理平台可查看详情','color'=>'#000000');
						$content[]=array('value'=>$sinfo['storename'],'color'=>'#000000');
						$content[]=array('value'=>$pstr,'color'=>'#000000');
						$content[]=array('value'=>date('Y-m-d H:i:s',time()),'color'=>'#000000');
						$content[]=array('value'=>number_format($money,2),'color'=>'#000000');
						$content[]=array('value'=>'已付款','color'=>'#000000');
						$MsgData['content']=$content;
						$wxinfo=$this->MSL()->table('tb_wxpayset')->where("token='%s'",$cashData['token'])->find();
						$wxinfo['appsecert']=$wxinfo['appsecret'];
						$this->LOGS(json_encode($wxinfo));
						import("Vendor.Wechat.WXTemplate");
						$tmp=new \WXTemplate($wxinfo);
						$this->LOGS("模板消息--->".json_encode($MsgData));
						$res=$tmp->sendTemplate($MsgData);
						$this->LOGS($res);
						//扣库存
					}
					$this->model->execute("UPDATE wh SET StockCount=StockCount-ol.Count,VirtualCount=VirtualCount-ol.Count,SalesCount=SalesCount+ol.Count,LastUpdateDate=GetDate() FROM $tb_name wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}'");
					// $this->LOGS('扣减库存--->>>'.M()->getlastsql());
					$this->model->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->setField('CashCode',$newcode);
				}else{
					$return['status']='error';
					$return['info']='支付成功，订单处理失败';
					$this->LOGS('支付结果--->>>现金支付成功，订单处理失败：'.$oid.'--->>>'.M()->getlastsql());
				}
			}else{
				$return['status']='error';
				$return['info']='支付成功，订单已处理';
				$this->LOGS('支付结果--->>>现金支付成功，订单已处理：'.$oid);
			}
			echo json_encode($return);
		}else{
			$result['status']='error';
			$result['info']='现金付款码错误/过期，请刷新后重试';
			echo json_encode($result);exit();
		}
	}

	/**
	 * 扫码支付 商户扫用户
	 */
	public function WxScanPay($PayParams){
		$cashData=session('cashData');
		$oid=$PayParams['oid'];
		$authCode=$PayParams['auth_code'];
		$money=$this->model->table('RS_Order')->where("OrderId='%s' and stoken='%s'",array($oid,$cashData['stoken']))->getField('Price');
		$ScanParam=array();
		$ScanParam['service']='offPayGate';
		$ScanParam['merchantNo']=$this->merchantNo;
		$ScanParam['version']='V1.0';
		$ScanParam['payChannelCode']='TX_WXZF';
		$ScanParam['orderNo']=$oid;
		$ScanParam['orderAmount']=floatval($money)*100;
		// $ScanParam['orderAmount']=1;
		$ScanParam['curCode']='CNY';
		$ScanParam['orderTime']=date('YmdHis',time());
		$ScanParam['authCode']=$authCode;
		$ScanParam['payerIp']=$_SERVER['REMOTE_ADDR'];
		$sign=$this->lvs_sign($ScanParam);
		$ScanParam['sign']=$sign;
		$this->LOGS('商户扫码收款请求信息--->>>'.json_encode($ScanParam));
		$result=$this->postXmlCurl($ScanParam,$this->apiurl);
		$this->LOGS('商户扫码收款响应信息--->>>'.$result);
		$result=json_decode($result,true);
		$return=array();
		if ($result['dealCode']=='10000') {
			//积分处理？？
			//支付成功处理
			$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
			$pros=$this->model->query("SELECT p.ProName+'*'+CONVERT(varchar(20),ol.Count,120) as ProName FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ol.OrderId='{$oid}'");
			$pstr='';
			// $this->LOGS('商品信息--->'.M()->getlastsql().json_encode($pros));
			foreach ($pros as $ps) {
				$pstr.=$ps['ProName'].',';
			}
			$ostatus=$oinfo['Status'];
			if ($ostatus=='1') {
				$sinfo=$this->model->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->find();
				// $
				$CutNum=floatval($sinfo['CutNum'])/100;  //商户扣点
				$WXCUT=floatval(C('WXCUT'))/100;    //微信支付扣点
				$PayCom=floatval($money)*$WXCUT;
				$MerCom=0;
				if ($CutNum>0) {
					$MerCom=floatval($money)*$CutNum;
				}
				$OverCom=floatval($money)-floatval($PayCom)-floatval($MerCom);
				//订单更新信息
				$OrderUpdata['PayCom']=$PayCom;
				$OrderUpdata['MerCom']=$MerCom;
				$OrderUpdata['OverCom']=$OverCom;
				$OrderUpdata['PayDate']=date('Y-m-d H:i:s',time());
				$OrderUpdata['GetDate']=date('Y-m-d H:i:s',time());
				$OrderUpdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$OrderUpdata['Status']=10;

				if ($this->model->table('RS_Order')->where("OrderId='%s'",$oid)->setField($OrderUpdata)) {
					$this->model->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->setField('LastBuyTime',date('Y-m-d H:i:s',time()));
					$return['status']='success';
					$return['type']='paying';
					$tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8).'_'.$sinfo['id'];
					if ($sinfo['MsgRecever']) {
						$MsgData=array();
						$content=array();
						$MsgData['touser']=$sinfo['MsgRecever'];
						$MsgData['template_id']='o3GHT9fgn7xto8dcLMpV2TIdzLUdcpb0uaQvUuvMdN8';
						$MsgData['first']=array('value'=>'POS收银有新订单~','color'=>'#000000');
						$MsgData['remark']=array('value'=>'登陆商户管理平台可查看详情','color'=>'#000000');
						$content[]=array('value'=>$sinfo['storename'],'color'=>'#000000');
						$content[]=array('value'=>$pstr,'color'=>'#000000');
						$content[]=array('value'=>date('Y-m-d H:i:s',time()),'color'=>'#000000');
						$content[]=array('value'=>$money,'color'=>'#000000');
						$content[]=array('value'=>'已付款','color'=>'#000000');
						$MsgData['content']=$content;
						$wxinfo=$this->MSL()->table('tb_wxpayset')->where("token='%s'",$cashData['token'])->find();
						$wxinfo['appsecert']=$wxinfo['appsecret'];
						$this->LOGS(json_encode($wxinfo));
						import("Vendor.Wechat.WXTemplate");
						$tmp=new \WXTemplate($wxinfo);
						$this->LOGS("模板消息--->".json_encode($MsgData));
						$res=$tmp->sendTemplate($MsgData);
						$this->LOGS($res);
						//扣库存
					}
					$this->model->execute("UPDATE wh SET StockCount=StockCount-ol.Count,VirtualCount=VirtualCount-ol.Count,SalesCount=SalesCount+ol.Count,LastUpdateDate=GetDate() FROM $tb_name wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}'");
					// $this->LOGS('扣减库存--->>>'.M()->getlastsql());
				}else{
					$return['status']='error';
					$return['info']='支付成功，订单处理失败';
					$this->LOGS('支付结果--->>>支付成功，订单处理失败：'.$oid);
				}
			}else{
				$return['status']='error';
				$return['info']='支付成功，订单已处理';
				$this->LOGS('支付结果--->>>支付成功，订单已处理：'.$oid);
			}
			echo json_encode($return);
		}elseif ($result['dealCode']=='10001') {
			//支付中，查询支付结果
			$this->QueryPayResult($oid);
		}else{
			//支付失败
			$return['status']='error';
			$return['info']='支付失败:(';
			$this->LOGS('支付结果--->>>支付失败：'.$oid);
			echo json_encode($return);
		}
	}

	/**
	 * 用户扫商户
	 */
	public function UserScanPay(){
		$cashData=session('cashData');
		$oid=$_POST['oid'];
		$authCode=$_POST['authCode'];
		$money=$this->model->table('RS_Order')->where("OrderId='%s' and stoken='%s'",array($oid,$cashData['stoken']))->getField('Price');
		$ScanParam=array();
		$ScanParam['service']='getCodeUrl';
		$ScanParam['merchantNo']=$this->merchantNo;
		$ScanParam['version']='V1.0';
		$ScanParam['bgUrl']=$_SERVER['HOST_NAME'].'/Seller/OfflineCashier/notify';
		$ScanParam['payChannelCode']='TX_WXZF';
		$ScanParam['orderNo']=$oid;
		$ScanParam['orderAmount']=floatval($money)*100;
		$ScanParam['curCode']='CNY';
		$ScanParam['orderTime']=date('YmdHis',time());
		$sign=$this->lvs_sign($ScanParam);
		$ScanParam['sign']=$sign;
		$this->LOGS('用户扫码支付请求信息--->>>'.json_encode($ScanParam));
		$result=$this->postXmlCurl($ScanParam,$this->apiurl);
		$this->LOGS('用户扫码支付响应信息--->>>'.$result);
		$result=json_decode($result,true);
		if ($result['dealCode']=='10000') {
			//二维码获取成功
			$codeUrl=$result['codeUrl'];//支付二维码
			return true;
		}else{
			//支付失败
		}

	}

	/**
	 * 支付结果查询
	 */
	public function QueryPayResult($oid){
		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$param=array();
		$param['service']='offSearchOrder';
		$param['merchantNo']=$this->merchantNo;
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$sign=$this->lvs_sign($param);
		$param['sign']=$sign;
		$res=$this->postXmlCurl($param,$this->apiurl);
		$result=json_decode($res,true);
		$return=array();
		if ($result['dealCode']=='10000') {
			//积分处理？？
			//支付成功处理
			$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$result['orderNo'])->find();
			$pros=$this->model->query("SELECT p.ProName+'*'+CONVERT(varchar(20),ol.Count,120) as ProName FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ol.OrderId='{$oid}'");
			$pstr='';
			// $this->LOGS('商品信息--->'.M()->getlastsql().json_encode($pros));
			foreach ($pros as $ps) {
				$pstr.=$ps['ProName'].',';
			}
			$ostatus=$oinfo['Status'];
			if ($ostatus=='1') {
				if ($this->model->table('RS_Order')->where("OrderId='%s'",$result['orderNo'])->setField('Status','10')) {
					$this->model->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->setField('LastBuyTime',date('Y-m-d H:i:s',time()));
					$return['status']='success';
					$sinfo=$this->model->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->find();
					$tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8).'_'.$sinfo['id'];
					if ($sinfo['MsgRecever']) {
						$MsgData=array();
						$content=array();
						$MsgData['touser']=$sinfo['MsgRecever'];
						$MsgData['template_id']='o3GHT9fgn7xto8dcLMpV2TIdzLUdcpb0uaQvUuvMdN8';
						$MsgData['first']=array('value'=>'POS收银有新订单~','color'=>'#000000');
						$MsgData['remark']=array('value'=>'登陆商户管理平台可查看详情','color'=>'#000000');
						$content[]=array('value'=>$sinfo['storename'],'color'=>'#000000');
						$content[]=array('value'=>$pstr,'color'=>'#000000');
						$content[]=array('value'=>date('Y-m-d H:i:s',time()),'color'=>'#000000');
						$content[]=array('value'=>$money,'color'=>'#000000');
						$content[]=array('value'=>'已付款','color'=>'#000000');
						$MsgData['content']=$content;
						$wxinfo=$this->MSL()->table('tb_wxpayset')->where("token='%s'",$cashData['token'])->find();
						$wxinfo['appsecert']=$wxinfo['appsecret'];
						$this->LOGS(json_encode($wxinfo));
						import("Vendor.Wechat.WXTemplate");
						$tmp=new \WXTemplate($wxinfo);
						$this->LOGS("模板消息--->".json_encode($MsgData));
						$res=$tmp->sendTemplate($MsgData);
						$this->LOGS($res);
						//扣库存
					}
					$this->model->execute("UPDATE wh SET StockCount=StockCount-ol.Count,VirtualCount=VirtualCount-ol.Count,SalesCount=SalesCount+ol.Count,LastUpdateDate=GetDate() FROM $tb_name wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}'");
					// $this->LOGS('扣减库存--->>>'.M()->getlastsql());
				}else{
					$return['status']='error';
					$return['info']='支付成功，订单处理失败';
					$this->LOGS('支付结果--->>>支付成功，订单处理失败：'.$oid);
				}
			}else{
				$return['status']='error';
				$return['info']='支付成功，订单已处理';
				$this->LOGS('支付结果--->>>支付成功，订单已处理：'.$oid);
			}
			echo json_encode($return);
		}elseif ($result['orderPayStatus']=='0') {
			sleep(2); //睡两秒再请求
			$this->QueryPayResult($oid);
		}elseif ($result['orderPayStatus']=='2') {
			$this->LOGS('同兴支付失败--->>>'.$res);
			$return['status']='error';
			$return['info']='支付失败';
			echo json_encode($return);
		}elseif ($result['orderPayStatus']=='6') {
			$this->LOGS('同兴支付：订单未支付--->>>'.$res);
			$return['status']='error';
			$return['info']='订单未支付';
			echo json_encode($return);
		}elseif ($result['orderPayStatus']=='7') {
			$this->LOGS('同兴支付：订单超时--->>>'.$res);
			$return['status']='error';
			$return['info']='订单超时';
			echo json_encode($return);
		}elseif ($result['orderPayStatus']=='8') {
			$this->LOGS('同行支付：订单已撤销--->>>'.$res);
			$return['status']='error';
			$return['info']='订单已撤销';
			echo json_encode($return);
		}
	}





	/**
	 * 签名
	 */
	private function lvs_sign($data){
		ksort($data);
		$str=http_build_query($data);
		$str=$str.$this->key;
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

    /**
     * 支付结果通知处理
     */
    public function notify(){
    	$data=$_POST;
    	$this->LOGS($_POST);
    	$data=json_decode($data);
    	if ($data['dealCode']=='10000') {
    		//处理订单支付结果
    	}
    }





}
