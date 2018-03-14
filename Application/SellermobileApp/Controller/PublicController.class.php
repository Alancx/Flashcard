<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class PublicController extends BaseController{

	//////////数据请求入口//////////////////
	public function UserInfo(){
		if (IS_GET) {
			$type=$_GET['type'];
			switch ($type) {
				case 'gettime':   //获得服务器时间/////
				$this->GetTime();
				break;
				default:
				# code...
				break;
			}
		} elseif (IS_POST) {
			$type=$_POST['type'];
			$this->LOGS('---方法:'.$type.'---数据:'.json_encode($_POST));
			if ($type=='logining') {
				$this->Login($_POST);
			} else {
				if ($this->sign==md5($this->activeurl.$this->timetemp.$this->apptokentemp)) {
					switch ($type) {
						case 'getshopinfo':
						A('Index')->Index();
						break;
						case 'getfaceproinfo':
						A('Products')->Factorypro($_POST);
						break;
						case 'savefaceproinfo':
						A('Products')->Factoryprosave($_POST);
						break;
						case 'getclasstype':
						A('Products')->GetClassInfo();
						break;
						case 'saveselfproinfo':
						A('Products')->Selfprosave($_POST);
						break;
						case 'gethasfaceproinfo':
						A('Products')->HadFactorypro($_POST);
						break;
						case 'delproinfo':
						A('Products')->Deletepro($_POST);
						break;
						case 'saveeditfaceproinfo':
						A('Products')->Factoryproeditsave($_POST);
						break;
						case 'saveeditselfproinfo':
						A('Products')->Selfproeditsave($_POST);
						break;
						case 'getfactproinfo':
						A('Products')->GetFactProinfo($_POST);
						break;
						case 'savefactproinfo':
						A('Products')->SaveFactProinfo($_POST);
						break;
						case 'getselfproinfo':
						A('Products')->GetSelfProinfo($_POST);
						break;
						case 'getselfprooneinfo':
						A('Products')->GetSelfProOneinfo($_POST);
						break;
						case 'shelveproinfo':
						A('Products')->ShelveProinfo($_POST);
						break;
						case 'getallclassproinfo':
						A('Products')->GetAllClassProinfo($_POST);
						break;
						case 'searchallpro':
						A('Products')->SearchallProinfo($_POST);
						break;



						case 'getnotendproinfo':
						A('Orders')->Getnotendproinfo($_POST);
						break;
						case 'stockqueryproinfo':
						A('UmWareHouse')->StockQueryproinfo($_POST);
						break;
						case 'getallordersinfo':
						A('Orders')->GetAllOrdersinfo($_POST);
						break;
						case 'gettkordersinfo':
						A('Orders')->GetTkOrdersinfo($_POST);
						break;
						case 'settkordersinfo':
						A('Orders')->SetTkOrdersinfo($_POST);
						break;
						case 'getrecordsinfo':
						A('Records')->GetRecordsinfo($_POST);
						break;
						case 'getyesorderinfo':
						A('Records')->GetYesOrderinfo($_POST);
						break;
						case 'getsevorderinfo':
						A('Records')->GetSevOrderinfo($_POST);
						break;
						case 'getzhbankinfo':
						A('User')->getzhBankinfo($_POST);
						break;
						case 'getbcodeinfo':
						A('User')->GetBcodeinfo($_POST);
						break;
						case 'savebankinfo':
						A('User')->SaveBankinfo($_POST);
						break;
						case 'getbankinfo':
						A('User')->GetBankinfo($_POST);
						break;
						case 'getjsinfo':
						A('User')->GetJsinfo($_POST);
						break;
						case 'sendtxmoneyinfo':
						A('User')->SendtxMoneyinfo($_POST);
						break;
						case 'getdistributioninfo':
						A('User')->GetDistributioninfo($_POST);
						break;
						case 'setdistributioninfo':
						A('User')->SetDistributioninfo($_POST);
						break;
						case 'getdiscashinfo':
						A('User')->GetDisCashinfo($_POST);
						break;
						case 'setdiscashinfo':
						A('User')->SetDisCashinfo($_POST);
						break;
						case 'getdisorderinfo':
						A('User')->GetDisOrderinfo($_POST);
						break;
						case 'newsenddisorderinfo':
						A('User')->NewSendDisOrderinfo($_POST);
						break;
						case 'getmpageinfo':
						A('User')->GetmPageinfo($_POST);
						break;
						case 'savelbimginfo':
						A('User')->SaveLbImginfo($_POST);
						break;
						case 'dellbimginfo':
						A('User')->DelLbImginfo($_POST);
						break;
						case 'savehotproinfo':
						A('User')->SaveHotProinfo($_POST);
						break;
						case 'getshopcodeinfo':
						A('User')->GetShopCodeinfo($_POST);
						break;
						case 'getsqorderinfo':
						A('UmWareHouse')->GetSqOrderinfo($_POST);
						break;
						case 'savesqorderinfo':
						A('UmWareHouse')->SaveSqOrderinfo($_POST);
						break;
						case 'getsqorderlistinfo':
						A('UmWareHouse')->GetSqorderListinfo($_POST);
						break;
						case 'sendsqorderlistinfo':
						A('UmWareHouse')->SendSqorderListinfo($_POST);
						break;
						case 'delsqorderlistinfo':
						A('UmWareHouse')->DelSqOrderListinfo($_POST);
						break;
						case 'lgetsqorderinfo':
						A('UmWareHouse')->LGetSqOrderinfo($_POST);
						break;
						case 'getinwarehouseinfo':
						A('UmWareHouse')->GetInwarehouseinfo($_POST);
						break;
						case 'getinwhoneinfo':
						A('UmWareHouse')->GetInwhOneinfo($_POST);
						break;
						case 'senddhinwarehouseinfo':
						A('UmWareHouse')->SenddhInwarehouseinfo($_POST);
						break;

						default:
						$this->ajaxReturn(array('status' => 'false', 'info' => 'NoWayError'), 'JSON');
						break;
					}
				} else {
					$this->ajaxReturn(array('status' => 'false', 'info' => 'signError'), 'JSON');
				}
			}
		} else {
			$this->ajaxReturn(array('status' => 'false', 'info' => 'requestError'), 'JSON');
		}
	}

	public function GetTime(){
		echo time();
	}
	public function Login($info){
		$useraccout=$info['Uaccout'];
		$password=$info['Upassword'];
		$timetemp=$info['timetemp'];
		$tokentemp=$info['apptoken'];
		$type=$info['ltype'];
		$sign=$info['sign'];
		$activeurl='https://'.$_SERVER['HTTP_HOST'].__ACTION__;
		if (md5($activeurl.$timetemp.$tokentemp.$useraccout.$password.$type)==$sign) {
			$userinfo=$this->UM('user')->where("userName='".$useraccout."' AND password='".md5($password)."' AND IsAdmin='1' AND stoken!='0'")->find();
			if ($userinfo) {
				$shopid=$this->BM('store')->where(array('token'=>$userinfo['token'],'stoken'=>$userinfo['stoken'],'IsCheck'=>'1'))->getField('id');
				$this->LOGS('---方法:'.$type.'---数据:'.$this->BM()->getlastsql());
				$apptokeninfo=$this->UM('apptoken')->where("UserId=".$userinfo['id']." AND Stoken!='0'")->find();
				$res=false;
				if ($type=='1') {
					$apptoken=$this->getStr(16,16,true);
					$exptime=strtotime('+7 day',time());
					if ($apptokeninfo) {
						$res=$this->UM('apptoken')->where("UserId=".$userinfo['id']." AND Stoken!='0'")->save(array('AppToken'=>$apptoken,'StoreId'=>$shopid,'ExpDate'=>$exptime));
					} else {
						$res=$this->UM('apptoken')->add(array('UserId'=>$userinfo['id'],'Token'=>$userinfo['token'],'Stoken'=>$userinfo['stoken'],'StoreId'=>$shopid,'AppToken'=>$apptoken,'ExpDate'=>$exptime));
					}
				} elseif ($type=='0') {
					if (substr($tokentemp,0,32)==$apptokeninfo['AppToken']) {
						$res=true;
						$apptoken=$apptokeninfo['AppToken'];
						$exptime=$apptokeninfo['ExpDate'];
					}
				}
				if ($res) {
					$shopinfo=$this->BM('store')->where(array('token'=>$userinfo['token'],'stoken'=>$userinfo['stoken'],'IsCheck'=>'1'))->field('id,storename,Slogo,DescInfo')->find();
					$info['apptoken']=$apptoken.time().$exptime;
					$info['exptime']=$exptime;
					$info['shopinfo']=$shopinfo;
					$this->ajaxReturn(array('status' => 'true', 'info' => $info), 'JSON');
				} else {
					$this->ajaxReturn(array('status' => 'false', 'info' => 'userError'), 'JSON');
				}
			}else {
				$this->ajaxReturn(array('status' => 'false', 'info' => 'userError'), 'JSON');
			}
		} else {
			$this->ajaxReturn(array('status' => 'false', 'info' => 'signError'), 'JSON');
		}
	}

	//////////////门店二维码?待修改//////////////////////
	public function getUserQrcode()
	{
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$qrcodeImg='<img src="'.\QRcode::png('http://'.$_SERVER['HTTP_HOST'].U('Home/Index/Index',array('stoken'=>session('stoken'))),false,'L',4,'2').'"/>';
		echo $qrcodeImg;
	}

	///////现金付款码？待修改///////////
	public function getCashQrcode()
	{
		$getCashCode=$this->BM('store')->WHERE("token='%s' and stoken='%s'",array(session('token'),session('stoken')))->getField('CashCode');
		if ($getCashCode) {
			ob_clean();
			vendor('PHPQR.phpqrcode');
			$qrcodeImg='<img src="'.\QRcode::png('GETCASH'.$getCashCode,false,'L',4,'2').'"/>';
			echo $qrcodeImg;
		} else {
			$str='0123456789';
			$phonecode='';
			for ($i=0; $i < 5; $i++) {
				$getcashcodes.=substr(str_shuffle($str), mt_rand(0,9),1);
			}
			$getcashcodes=md5($getcashcodes);
			if ($this->BM('store')->WHERE("token='%s' and stoken='%s'",array(session('token'),session('stoken')))->save(array('CashCode'=>$getcashcodes))) {
				ob_clean();
				vendor('PHPQR.phpqrcode');
				$qrcodeImg='<img src="'.\QRcode::png('GETCASH'.$getcashcodes,false,'L',4,'2').'"/>';
				echo $qrcodeImg;
			}
		}
	}





}?>
