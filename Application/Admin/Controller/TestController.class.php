<?php
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class TestController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function pj(){
		if (IS_POST) {
			$data['ProId']=$_POST['ProId'];
			$data['MemberId']=$_POST['MemberId'];
			$data['Class']=$_POST['Class'];
			$data['ClassScore']=$_POST['ClassScore'];
			$data['ServiceScore']=$_POST['ServiceScore'];
			$data['LogisticsScore']=$_POST['LogisticsScore'];
			$data['Content']=$_POST['Content'];
			$data['OrderId']=$_POST['OrderId'];
			$data['IsDelete']=0;
			$data['Label']='';
			$data['Integral']=0;
			$data['Image']='';
			if (M()->table('RS_ProductEvaluation')->add($data)) {
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}else{
			$pinfos=M()->table('RS_Product')->select();
			$this->assign('pinfos',$pinfos);
			$this->display();
		}
	}

	public function seng(){
		import("Vendor.Wechat.WXTemplate");
		$wxinfo=array('appid'=>'wxed2f2ef5e18e5423','appsecert'=>'b75711e17f2bcd266923254e85cb4206');
		$msg=new \WXTemplate($wxinfo);
		// var_dump($msg);
		$data=array(
			'touser'=>'o1e6cv18DEL1F87j8qqC5LFZ33I4',
			'template_id'=>'-R-s_sG19MBStzHyWqASJ1M4_WlDvXOG7mqsfCdcfeA',
			'first'=>array('value'=>'first','color'=>'#000000'),
			'remark'=>array('value'=>'remark','color'=>'#000000'),
			'content'=>array(
				'keyword1'=>array('value'=>'21111','color'=>'#000000'),
				'keyword2'=>array('value'=>'22222','color'=>'#000000'),
				'keyword3'=>array('value'=>'23333','color'=>'#000000')
				)
			);
		$res=$msg->sendTemplate($data);
		var_dump($res);
	}

	/**
	 * 代付接口测试
	 */
	public function paying(){
		$Param=array();
		$Param['service']='batchPay';
		$Param['merchantNo']='TX0001455';
		$Param['version']='V1.0';
		$Param['bgUrl']='https://www.huijistore.com/Admin/Base/bank_notify.html';
		$Param['batchNo']=uniqid('PAYING');
		$Param['totalNum']=1;
		$Param['totalAmount']=100;
		$Param['orderTime']=date('YmdHis',time());
		$Param['isRepay']=0;
		$Param['sign']=$this->sign($Param);
		$contents=array();
		$content=array();
		$content['orderNo']=uniqid('PAYONE');
		$content['curCode']='CNY';
		$content['orderAmount']=100;
		$content['accountName']='中信银行';
		$content['accountNumber']='6217711109904753';
		$content['accountType']=1;
		$content['bankCode']='CCB';
		$content['branchBankName']='中信银行郑州东明路支行';
		$content['province']='河南省';
		$content['city']='郑州市';
		$content['feeType']=1;
		$content['isUrgent']=0;
		$contents[]=$content;
		$Param['content']=json_encode($contents,JSON_UNESCAPED_UNICODE);
		echo "<pre>";
		var_dump($Param);

		// $Param['content']={'orderNo':'".uniqid('PAYONE')."','curCode':'CNY','orderAmount':100,'accountName':'中信银行','accountNumber':'6217711109904753','accountType':'1','bankCode':'CCB','branchBankName':'中信银行郑州东明路支行','feeType':'1','isUrgent':'0'};
		$this->LOGS('银行请求信息--->>>'.json_encode($Param,JSON_UNESCAPED_UNICODE));
		$res=$this->postXmlCurl($Param,'http://api.tongxingpay.com/txpayApi/bank');
		// $res=$this->postXmlCurl($Param,'http://v.lleaves.com');
		// $res=$this->postXmlCurl($Param,'http://182.147.243.126:8080/txpayApi/bank');
		var_dump($res);
	}
	public function notify(){
		$this->LOGS('--->>>银行反馈信息'.json_encode($_POST));
	}
	public function sign($data){
		ksort($data);
        $str=urldecode(http_build_query($data));
        $str=$str.'d80efe63192b4b7ebf9a30d78075b8fa';
        var_dump('签名字符串'.$str);
        echo "<hr>";
        // $this->LOGS($str);
        $sign=strtoupper(md5($str));
        var_dump('签名结果'.$sign);
        echo "<hr>";
        return $sign;
	}

	/**
	 * 扫码支付测试
	 */
	public function scan(){
            $oid='RKPAYING'.date('YmdHis',time()).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
            $money=$wareinfo['Money'];
            $ScanParam=array();
            $ScanParam['service']='getCodeUrl';
            $ScanParam['merchantNo']='TX0001455';
            $ScanParam['version']='V1.0';
            $ScanParam['bgUrl']='https://'.$_SERVER['HTTP_HOST'].'/Admin/Base/Invonotify';
            $ScanParam['payChannelCode']='TX_WXZF';
            $ScanParam['orderNo']=$oid;
            // $ScanParam['ext1']=$cid;
            $ScanParam['orderAmount']=1;
            $ScanParam['productName']='test';
            // if (floatval($money)*100<=0) {
            //     $ScanParam['orderAmount']=1;
            // }else{
            //     $ScanParam['orderAmount']=floatval($money)*100;
            // }
            $ScanParam['curCode']='CNY';
            $ScanParam['orderTime']=date('YmdHis',time());
            $sign=$this->sign($ScanParam);
            $ScanParam['sign']=$sign;
            $this->LOGS('用户扫码支付请求信息--->>>'.json_encode($ScanParam));
            var_dump('请求数据'.json_encode($ScanParam));
            echo "<hr>";
            $result=$this->postXmlCurl($ScanParam,'http://182.147.243.126:8080/txpayApi/offLine');
            var_dump('请求结果'.$result['info']);
            $this->LOGS('用户扫码支付响应信息--->>>'.$result);
            $result=json_decode($result['info'],true);
            if ($result['dealCode']=='10000') {
                //二维码获取成功
                $codeUrl=$result['codeUrl'];//支付二维码
                $msg['status']='success';
                $msg['code']=$codeUrl;
            }else{
                //支付失败
                $msg['status']='error';
                $msg['info']=$result;
            }
            $this->assign($msg);
            $this->display();

	}

	/**
	 * code
	 */
	public function showcode(){
        ob_clean();
        vendor('PHPQR.phpqrcode');
        $code=$_GET['code'];
        $level="L";
        // $filename='./Uploads/1.png';
        $size=4;
        // \QRcode::png($url,$filename,$level,$size,'2');
        \QRcode::png($code,false,$level,$size,'2');
	}

	public function txrefund(){
		$oid='SSCAN5940b03891600';
		$refundNo='TXTK'.substr($oid, 1);
		// $this->model->table('RS_Order')->where("OrderId='%s'",$oid)->setField('BackNo',$refundNo);
		$param=array();
		$param['service']='offRefundOrder';
		$param['merchantNo']='TX0001455';
		$param['refundNo']=$refundNo;
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$param['curCode']='CNY';
		$param['refundAmount']=1;
		// $param['refundAmount']=1;
		$param['refundTime']=date('YmdHis');
		$param['refundDesc']='justrefund';
		$sign=$this->sign($param);
		$param['sign']=$sign;
		$this->LOGS('退款信息--->>>'.json_encode($param));
		$res=$this->postXmlCurl($param,'http://182.147.243.126:8080/txpayApi/offLine');
		$result=json_decode($res,true);
		var_dump($res['info']);exit();
		if ($result['dealCode']=='10000') {
			return true;
		}

	}

	public function queryrefund(){
		$oid='SSCAN5940b03891600';
		$refundNo='TXTKSCAN5940b03891600';
		$param=array();
		$param['service']='offSearchRefund';
		$param['merchantNo']='TX0001455';
		$param['refundNo']=$refundNo;
		$param['orderNo']=$oid;
		$param['version']='V1.0';
		$sign=$this->sign($param);
		$param['sign']=$sign;
		$res=$this->postXmlCurl($param,'http://182.147.243.126:8080/txpayApi/offLine');
		$result=json_decode($res,true);
		var_dump($res['info']);exit();
		if ($result['dealCode']=='10000') {
			if ($result['refundStatus']=='4') {
				return true;
			}elseif ($result['refundStatus']=='3') {
				sleep(2); //睡两秒再请求
				$this->queryrefund($oid);
			}elseif ($result['refundStatus']=='5') {
				$this->LOGS('同兴支付退款失败--->>>'.$res);
				return $result['dealMsg'];
			}
		}

	}


	/**
	 * 刷卡支付测试
	 */
	public function csn(){
		if (IS_POST) {
			$ScanParam=array();
			$ScanParam['service']='offPayGate';
			$ScanParam['merchantNo']='TX0001455';
			$ScanParam['version']='V1.0';
			$ScanParam['payChannelCode']='TX_WXZF';
			$ScanParam['orderNo']=uniqid('SSCAN');
			$ScanParam['orderAmount']=1;
			// $ScanParam['orderAmount']=1;
			$ScanParam['curCode']='CNY';
			$ScanParam['orderTime']=date('YmdHis',time());
			$ScanParam['authCode']=$_POST['auth_code'];
			$ScanParam['productName']='test';
			// var_dump($_POST['auth_code']);exit();
			$ScanParam['payerIp']=$_SERVER['REMOTE_ADDR'];
			$sign=$this->sign($ScanParam);
			$ScanParam['sign']=$sign;
			$this->LOGS('商户扫码收款请求信息--->>>'.json_encode($ScanParam));
			$result=$this->postXmlCurl($ScanParam,'http://182.147.243.126:8080/txpayApi/offLine');
			$this->LOGS('商户扫码收款响应信息--->>>'.$result);
			var_dump($result['info']);exit();
			$result=json_decode($result,true);			
		}else{
			$this->display();
		}
	}

	public function payother(){
		vendor("tbsdk.AopSdk");
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2017061407488573';
        // $aop->rsaPrivateKeyFilePath = 'Upload/cert/'.$this->token.'/alipay_rsa_private_key.pem';
        // $aop->alipayPublicKey='Upload/cert/'.$this->token.'/alipay_rsa_public_key.pem';
        $aop->rsaPrivateKey = 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCGPtT8SQ6kJNPcvQHLsMZRK50fKjR4HMEUIfhSPljk0BITcxki2j7u6rF+tL25p7ugfbeaNto/H4gm+82OWGK1nz79XNkqB9UrExbD/TdrC/+bEQOYcx+p2ZZw/RC1ybKz3D+XIDNHTy0oF4sJtPRdlBDpsnjbvxKW53QgfdiWCqtqtG3GhzhFmOULcqzVAZNnLGIMk283anLnCl/b9Ze3jftFBfot60L2Gft2NXgotLNXEqZdgtq6XFwEey2Km40r1whM4ajwjt889Kcw7oLELmcIVMFa8EmeDdiam/33nchg/GRiLYvOz0MYw86aRX9PO/dodXNcRdei6yMDi2x3AgMBAAECggEAS5ofAGV0ZWSIHAwlcrz/Mr9JIT/3hJ1M7zWiT07laRb01xc+1K3sO0jY5O6M2n1n4R5rw+GYT2xGlQ3B2aRVEsu4AZ/EfTMkDboWXBHCn/qF3KJcUAKlllX/r2oVews4JKqo9UchGcuxNp7rom/SHtFAJRxpi/ezVGDMuW+mBfjzNfVISnYANC8A0zH9tKh5dRU8Wzebc0yCXG9MdWFfcEZ8fNa/9ZbLQNZwWaIUFsetR2bUIX7XyzmbV/JFyKFBiWKidT5IlwCFR5q49loLyZuI+Q52lV5S68/EQLpG00bS2NXvQ75zWNv1sPfa4Bkn4DicqOoIFA+eemfEZAOOIQKBgQDDA2muj88TxbCrfElp5Ph0oJ9+zoRBkWK888juxy5SgU4Yy1qMJ5peU5K9SiutUs4+lUyiQuFuBjRmSIfg8oMKLJs1IHBNxY67aHOIsdDBVTEBguclvvCb1XFmqTqela+SL9XR7CImAVpze2c+0Owwn5v0u0M/nR3Q6O7z0zlhrQKBgQCwOmXPZAKsi1fq+JlfFIE2Ueu8l0lQCCKx3X4HpZCpxl6l8OvnILW2HBDgOmSk5xNm6zIOltm8wq9At5xrLSWQfYa1db0yg1wOWtgRJNeL+NR7qEeEVTAlkf2wjyWO5fvR0G1nTnf/H9XCykwVGZYRf9+DCl8rWnIA8Qrs0zWzMwKBgCV9Z3i2jtG3RJKDDz37VcReCKuBGi3cvEWk/DDjO8WCtDfSCDM/fc66dFBNjP4CGEIxw4zCHMJhEPvE39Jf7M8s6h2Zgd2BqEHg/6z8uiwgq44l0zgPcAQVUXqx9+H61sjcx1dW9O2nfvMKezu5QF7MoFe5FGGLW+sIjL91EOf1AoGAD07zWyGn7c8o8vtnb0/7rXlOThKiRrZ+NQ81jHqAZ48Y17dm9qvrvQcRHDlWVtDP6afSsFvATFppGOkaSGEimzucQRUaO1IX5BNWI58crkcORjOnCsLPrOPSssysiY5G4sIFTu0NFXdxfTtPgqG3XIvMIbj77Wss7hICTfJG/usCgYBVc1QfanIIFyhFtWygoV6H3OwZaYyOqO0RW0zx+v7RHATTrNxTuQBwdS9LZ4H2EhIEQS034PFXSPh5bTTeZWEad4TRqplfvwAhN2hp+RlzwmrrUGyXzEhxUpW5nMzDhrBSWCQiS8S0BlgWjSuuSl8jFnNqVnMGtFIL5MetOqz8rw==';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhj7U/EkOpCTT3L0By7DGUSudHyo0eBzBFCH4Uj5Y5NASE3MZIto+7uqxfrS9uae7oH23mjbaPx+IJvvNjlhitZ8+/VzZKgfVKxMWw/03awv/mxEDmHMfqdmWcP0Qtcmys9w/lyAzR08tKBeLCbT0XZQQ6bJ4278Slud0IH3YlgqrarRtxoc4RZjlC3Ks1QGTZyxiDJNvN2py5wpf2/WXt437RQX6LetC9hn7djV4KLSzVxKmXYLaulxcBHstipuNK9cITOGo8I7fPPSnMO6CxC5nCFTBWvBJng3Ympv9953IYPxkYi2Lzs9DGMPOmkV/Tzv3aHVzXEXXousjA4tsdwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayFundTransToaccountTransferRequest();
        $OrderId=uniqid('PAYOTHER');
        // $request->setBizContent(json_encode(array('out_trade_no'=>$oinfo['Payorderid'],'refund_amount'=>floatval($oinfo['Price']))));
        $request->setBizContent(json_encode(array('out_biz_no'=>$OrderId,'payee_type'=>'ALIPAY_LOGONID','payee_account'=>'15239442258','amount'=>'0.2')));
        $result = $aop->execute ( $request); 
        var_dump($result);exit();
        $this->LOGS(json_encode($result));
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $info=json_decode(json_encode($result->$responseNode),true);
        $code=$info['code'];
        $status=$info['fund_change'];
        if ($code==10000 && $status=='Y') {
            $this->model->commit();
            $data['mobiles']=$this->model->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->getField('Phone');
            $data['content']='您的订单：'.$oinfo['OrderId'].',已完成退款，请注意查询对应账户金额';
            $this->SendMessage($data);
            $this->success('退款成功');
        }else{
            $this->model->rollback();
            $this->error('退款失败');
        }
	}


	public function s(){
		$id=$_GET['id'];
		$tbs=$this->getCK('all');
		// var_dump($tbs);exit();
		foreach ($tbs as $tb) {
			if (!$this->SH($tb)->where("ProIdCard='%s'",$id)->setField("IsDelete",1)) {
				// $wres=false;
				// break;
			}
			echo "<hr>";
			echo $this->SH()->getlastsql();
		}
		var_dump($wres);
	}

	/**
	 * 获取当前商户所有仓库表名
	 */
	 public function getCK($type='main'){
		 if ($type!='main') { //获取全部仓库表名--备用
			 $tempAry=array();
			 $tempAry[]='wh'.substr($this->token,-8,8);
			 $StoreIds=M()->table('RS_Store')->where("token='%s'",$this->token)->getField('id',true);
		 		foreach ($StoreIds as $st) {
		 			$tempAry[]='wh'.substr($this->token,-8,8).'_'.$st;
		 		}
				return $tempAry;
		}else{ //返回主仓库表名
			return 'wh'.substr($this->token,-8,8);
		}
	 }





}












?>
