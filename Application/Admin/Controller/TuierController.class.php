<?php
namespace Admin\Controller;
use Think\Controller;
/**
*
*/
class TuierController extends BaseController
{
	public $uinfo;
	function _initialize(){
		if (session('login') && session('uinfo')) {
			$this->uinfo=session('uinfo');
		}else{
			$this->error('登陆失效',U('Base/tuierlogin'));
		}
	}


	public function index(){
		if (IS_POST) {

		}else{
//			echo "<pre>";
//			var_dump($this->uinfo);die();
			$account = M()->table("RS_Tuier")->where('ID=%d',$this->uinfo['id'])->find();
			//统计微信登录人下面的代理人
			$peoplelist=M()->table("RS_Tuier")->where("Invcoded='%s'",$this->uinfo['Invcode'])->field("Account as userName,TrueName,HeadImgUrl,CreateDate,ID as id,Invcode")->select();
			
			$Count = array();
			foreach ($peoplelist as $key => $value) {
				$invcode = $value['Invcode'];
				$Count[] = $invcode;
			}
			// $stores = M()->query("SELECT Invcode,count(Invcode) as num FROM RS_Store WHERE Invcode in('".implode("','",$Count)."') group by Invcode");
			$stores = M()->table('RS_Store')->where("Invcode in('".implode("','",$Count)."') ")->group('Invcode')->getField("Invcode,count(*) as count");
			foreach ($peoplelist as $key => $value) {
				$value['scount']=$stores[$value['Invcode']];
				$peoplelist[$key]=$value;
			}
			
			$storelist=M()->table('RS_Store')->where("Invcode='%s'",$invcode)->field("storename,province,city,area,addr,tel,stoken,TrueName,CONVERT(varchar(20),CreateDate,120) as CreateDate,Slogo")->select();
			
			//微信登录人下面的店面数量
			$storelist=M()->table('RS_Store')->where("Invcode='%s'",$this->uinfo['Invcode'])->field("storename,province,city,area,addr,tel,stoken,TrueName,CONVERT(varchar(20),CreateDate,120) as CreateDate,Slogo")->select();

			//历史销售总额
			$all = M()->query("select RS.stoken,CONVERT(VARCHAR(20),ISNULL(SUM(RO.Price),0)) as total FROM RS_Store RS LEFT JOIN RS_Order RO ON RS.stoken = RO.stoken where Status in(4,10) GROUP BY RS.stoken");
			$newall=array();
			foreach ($all as $key => $value) {
				$newall[$value['stoken']]=$value;
			}
			//今日销售总额
			$todayStart = date('Y-m-d 00:00:01',time());
			$todayEnd = date('Y-m-d 23:59:59',time());
			$today = M()->query("select RS.stoken,CONVERT(VARCHAR(20),ISNULL(SUM(RO.Price),0)) as total FROM RS_Store RS LEFT JOIN RS_Order RO ON RS.stoken = RO.stoken AND RO.CreateDate BETWEEN '{$todayStart}' AND '{$todayEnd}' AND Status in(4,10) GROUP BY RS.stoken");
			$newtoday=array();
			foreach ($today as $key => $value) {
				$newtoday[$value['stoken']]=$value;
			}
			//当月销售总额
			$startTimes = date('Y-m-d 00:00:01',strtotime('- 29 days'));//当月时间
			$endTimes = date('Y-m-d',time());
			$month = M()->query("select RS.stoken,CONVERT(VARCHAR(20),ISNULL(SUM(RO.Price),0)) as total FROM RS_Store RS LEFT JOIN RS_Order RO ON RS.stoken = RO.stoken WHERE RO.CreateDate BETWEEN '{$startTimes}' AND '{$endTimes}' AND Status in(4,10) GROUP BY RS.stoken");
			$newmonth=array();
			foreach ($month as $key => $value) {
				$newmonth[$value['stoken']]=$value;
			}
			//佣金
			$myMoney = M()->query("select stoken,CONVERT(VARCHAR(20),ISNULL(SUM(Money),0)) as Money FROM RS_TuiMoneyManager WHERE TuierId='{$this->uinfo['id']}' AND Type='add' GROUP BY stoken");
			if(!empty($myMoney)) {
				foreach($myMoney as $k=>$v) {
					$sum += (float) round($v['Money'],2);
				}
			}else {
				$sum = '0.00';
			}
			$newMoney = array();
			foreach($myMoney as $mk=>$mv) {
				$newMoney[$mv['stoken']] = $mv; 
			}
			foreach ($storelist as $st => $sv) {
				$son=array();
				$son['all'] = round($newall[$sv['stoken']]['total'],2) ? round($newall[$sv['stoken']]['total'],2) : '0.00';
				$son['month'] = round($newmonth[$sv['stoken']]['total'],2) ? round($newmonth[$sv['stoken']]['total'],2) : '0.00';
				$son['today'] = round($newtoday[$sv['stoken']]['total'],2) ? round($newtoday[$sv['stoken']]['total'],2) : '0.00';
				$son['myMoney'] = round($newMoney[$sv['stoken']]['Money'],2) ? round($newMoney[$sv['stoken']]['Money'],2) : '0.00';
				$sv['son']=$son;
				$storelist[$st]=$sv;
			}
			$pagedata['account'] = $account ;
			$pagedata['peoplelist'] = $peoplelist;
			$pagedata['pcount']=count($pagedata['peoplelist']);//统计自己名下一共有多少个代理人
			$pagedata['storelist'] = $storelist;//统计一共多少家店
			$pagedata['scount']=count($pagedata['storelist']);
			$pagedata['sum'] = $sum;
  			$this->assign($pagedata);
			$this->display();
		}
	}

	public function tuinfo(){
		if (IS_POST) {

		}else{
			$pagedata['Invcode']=$this->uinfo['Invcode'];
			$this->assign($pagedata);
			$this->display();
		}
	}

	public function getuiqr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		if ($_GET['type']=='store') {
			$url='http://'.$_SERVER['HTTP_HOST'].U('Admin/Writeinfo/getwxparam',array('Invcode'=>$_GET['Invcode'],'type'=>'writestore'));
		}else{
			$url='http://'.$_SERVER['HTTP_HOST'].U('Admin/Writeinfo/writepeople',array('Invcode'=>$_GET['Invcode']));
		}
		$level="L";
		$size=4;
		\QRcode::png($url,$filename,$level,$size,'2');
	}
	//佣金提现
	public function getMoney() {

		//我的佣金总金额
		$myMoney = M()->table("RS_Tuier")->where('ID=%d',$this->uinfo['id'])->getField('Money');
		$myMoney = number_format($myMoney,2);	
		//银行账户信息
		$banks = M()->table("RS_TuierBankinfo")->where("TuierId='{$this->uinfo['id']}'")->find();

		if($banks){
			$type = true;//设置一个判断类型
		}else{
			$type = false;
		}
		//提现管理,判断是否是有银行卡信息,只有银行卡添加完成后才可进行提现操作
		if(IS_POST ){ 
			$money = $_POST['money'];
			$data['Money'] = $_POST['money'];
			$data['Type'] = 'less';
			// $data['TuierId'] = $_POST
			$data['TuierId'] = $this->uinfo['id'];
			$data['TuierAccount'] = $this->uinfo['name'];	
			if( $type && !empty($money)) {
				//添加数据
				M()->startTrans(); //添加之前开始sqlserver事务
				$tid = M()->table("RS_TuiMoneyManager")->add($data);
				//往提现信息记录表中添加提现记录数据(RS_TuierGetmoney)				
				$add['Money'] = $money;
				$add['TuierId'] = $this->uinfo['id'];
				$add['Status'] = 0;
				//拼接银行卡信息
				$add['BankName'] = $banks['BankName'];
				$add['BankId'] = $banks['BankId'];
				$add['IdName'] = $banks['IdName'];
				$add['Tid'] = $tid;
				$getMoney = M()->table("RS_TuierGetmoney")->add($add);
				//等待后台同意提现后才可提现,如果拒绝提现则现金返回
				// var_dump($updateMoney);
				//修改数据库中佣金剩余量
				//这应该修改mysql  user表对应的Money字段
				// $change = $this->MSL()->table('tb_user')->where();
				 // $newMoney = M()->query("update RS_TuiMoneyManager set Money = {$updateMoney} where Type='add'");//  这一步没用  这里面只是金额变动记录 只需要添加删除  不需要修改
				//异步请求佣金明细
				// $id='当前推广人的id';
				$id = $this->uinfo['id'];
				// $ures=$this->MSL()->table('tb_user')->where("id=%d",$id)->setDec('Money','变动金额');
				$ures=M()->table('RS_Tuier')->where("ID=%d",$id)->setDec('Money',$money);
				// var_dump($this->MSL()->getlastsql());
				if ($tid && $getMoney && $ures) {
					//确定三条语句都执行成功 
					M()->commit();
					$status = true;
					//这里补上成功返回的信息
				}else{
					// var_dump($tid,$getMoney,$ures);die();
					// var_dump(expression)
					M()->rollback();
					$status = false;
					//补上处理失败的返回数据
				}
				// var_dump($status);
				//事务没提交 查询不会有对应的数据  这里不用返回数据库数据,只需要判断处理结果是否成功,成功的话前台页面再去处理提现记录,因为前台已经知道申请提现的金额,其他信息都是固定的用户信息.所以处理完不需要再返回额外的数据
				if($status) {
					//事务提交成功后
					$msg['updateMoney'] = $updateMoney;
					$backMoney = M()->table("RS_TuierGetmoney")->where("Tid='{$tid}'")->find();
					/*$ajaxMoney = M()->query("SELECT stoken,Type,CONVERT(VARCHAR(20),CreateDate,120) as createdate,Money FROM RS_TuiMoneyManager WHERE TuierId='{$this->uinfo['id']}'");*/
					$ajaxMoney = M()->query("SELECT rm.TuierId,rm.Type,CONVERT(VARCHAR(20),rm.CreateDate,120) as createdate,rm.Money,rg.Status FROM RS_TuiMoneyManager rm LEFT JOIN RS_TuierGetmoney rg ON rg.Tid=rm.ID  WHERE rm.TuierId='{$this->uinfo['id']}'");
					$money = M()->table("RS_Tuier")->where("ID=%d",$this->uinfo['id'])->getField('Money');
					$msg['money'] = $money;
					$msg['ajaxMoney'] = $ajaxMoney;
					// $msg['updateMoney'] = $updateMoney;
					// $msg['lastMoney'] = $lastMoney;
					$msg['type'] = "success";
				}else {
					$msg['type'] = 'error';
				}
				
			}
			$msg['bankmsg'] = $type;
			echo  json_encode($msg);


		}else{
			//收益明细
			$detail = M()->query("SELECT rm.TuierId,rm.Type,CONVERT(VARCHAR(20),rm.CreateDate,120) as createdate,rm.Money,rg.Status FROM RS_TuiMoneyManager rm LEFT JOIN RS_TuierGetmoney rg ON rg.Tid=rm.ID  WHERE rm.TuierId='{$this->uinfo['id']}'");
			// $money = M()->table("RS_Tuier")->where("ID=%d",$this->uinfo['id'])->getField('Money')->select();
			$str = $banks['BankId']; 
			$banks['bankCard'] =  $this->strreplace($str);
			$pagedata['bank'] = $banks;
			$pagedata['type'] = $type;
			$pagedata['detail'] = $detail;
			$pagedata['sum'] = $myMoney;
			$this->assign($pagedata);
			$this->display();
		}
		
	}
	//替换银行卡中间位数为***
	public function strreplace($str, $startlen = 3, $endlen = 4) {  
	    $repstr = "";  
	    if (strlen($str) < ($startlen + $endlen+1)) {  
	        return $str;  
	    }  
	    $count = strlen($str) - $startlen - $endlen;  
	    for ($i = 0; $i < $count; $i++) {  
	        $repstr.="*";  
	    }  
	    $repstr = substr($repstr, 1,4);
	    return preg_replace('/(\d{' . $startlen . '})\d+(\d{' . $endlen . '})/', '${1}' . $repstr . '${2}', $str);  
	} 
	
	//添加银行卡信息
	public function bankMessage() {
		$id = $_GET['id'];
		if(IS_POST) {
			$data['BankName'] = $_POST['bank'];
			$data['IdName'] = $_POST['user'];
			$data['BankId'] = $_POST['bankCard'];
			$data['TuierId'] = $this->uinfo['id'];
			
			$bankmsg = M()->table("RS_TuierBankinfo")->find();
			
			$id = $_POST['id'];
			//查询判断是否存在银行卡信息,如果存在则修改数据
			if($bankmsg && $id){
				$result = M()->table('RS_TuierBankinfo')->where("ID='{$id}'")->save($data);
			}else {
			//如果不存在则添加数据
				$result = M()->table('RS_TuierBankinfo')->add($data);
			}
			if($result) {
				$msg['type'] = 'success';
			}else {
				$msg['type'] = 'error';
			}
			echo json_encode($msg);
		}else{
			$bankmsg = M()->table("RS_TuierBankinfo")->where("id=%d",$id)->find();
			$this->assign('bankmsg',$bankmsg);
			$this->display();
		}
	}
	
	//代理人管理
	public function agent() {
			$id = (int)$_GET['id'];
			/*echo "<pre>";
			var_dump($this->uinfo);
			exit();*/
			//代理人信息
			$peoplelist=M()->table("RS_Tuier")->where("ID=%d",$id)->field("Account as userName,TrueName,HeadImgUrl,CreateDate,Invcode")->find();
			// echo "<pre>";
			// var_dump($peoplelist);exit();
			$invcode = $peoplelist['Invcode'];
			$storelist=M()->table('RS_Store')->where("Invcode='%s'",$invcode)->field("storename,province,city,area,addr,tel,stoken,TrueName,CONVERT(varchar(20),CreateDate,120) as CreateDate,Slogo")->select();
			$pagedata['peoplelist']=$peoplelist;
			//历史销售总额
			$all = M()->query("select RS.stoken,CONVERT(VARCHAR(20),ISNULL(SUM(RO.Price),0)) as total FROM RS_Store RS LEFT JOIN RS_Order RO ON RS.stoken = RO.stoken where Status in(4,10) GROUP BY RS.stoken");
			$newall=array();
			foreach ($all as $key => $value) {
				$newall[$value['stoken']]=$value;
			}
			//今日销售总额
			$todayStart = date('Y-m-d 00:00:01',time());
			$todayEnd = date('Y-m-d 23:59:59',time());
			$today = M()->query("select RS.stoken,CONVERT(VARCHAR(20),ISNULL(SUM(RO.Price),0)) as total FROM RS_Store RS LEFT JOIN RS_Order RO ON RS.stoken = RO.stoken AND RO.CreateDate BETWEEN '{$todayStart}' AND '{$todayEnd}' AND Status in(4,10) GROUP BY RS.stoken");
			$newtoday=array();
			foreach ($today as $key => $value) {
				$newtoday[$value['stoken']]=$value;
			}
			//当月销售总额
			$startTimes = date('Y-m-d 00:00:01',strtotime('- 29 days'));//当月时间
			$endTimes = date('Y-m-d',time());
			$month = M()->query("select RS.stoken,CONVERT(VARCHAR(20),ISNULL(SUM(RO.Price),0)) as total FROM RS_Store RS LEFT JOIN RS_Order RO ON RS.stoken = RO.stoken WHERE RO.CreateDate BETWEEN '{$startTimes}' AND '{$endTimes}' AND Status in(4,10) GROUP BY RS.stoken");
			$newmonth=array();
			foreach ($month as $key => $value) {
				$newmonth[$value['stoken']]=$value;
			}
			//佣金
			$myMoney = M()->query("select stoken,CONVERT(VARCHAR(20),ISNULL(SUM(Money),0)) as Money FROM RS_TuiMoneyManager WHERE TuierId='{$this->uinfo['id']}' AND Type='add' GROUP BY stoken");
			
			$newMoney = array();
			foreach($myMoney as $mk=>$mv) {
				$newMoney[$mv['stoken']] = $mv; 
			}
			foreach ($storelist as $st => $sv) {
				$son=array();
				$son['all']=$newall[$sv['stoken']]['total'];
				$son['month']=$newmonth[$sv['stoken']]['total'];
				$son['today']=$newtoday[$sv['stoken']]['total'];
				$son['myMoney'] = $newMoney[$sv['stoken']]['Money'];
				$sv['son']=$son;
				$storelist[$st]=$sv;
			}
			$pagedata['storelist'] = $storelist;
			$pagedata['scount']=count($pagedata['storelist']);
			$this->assign($pagedata);
		$this->display();
	}
	

	public function rules(){

		$this->display();

	}
	
	
	
	
	
	
	
	
	
}










 ?>
