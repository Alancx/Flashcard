<?php 
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
/**
* F2.0app后端数据处理
*/
class PhoneController extends BaseController
{
	public $token;
	public $model;
	public $stokens;
	public $theme=array('#CCCCFF','#99CCFF','#40D29B','#BDCCD4','#FFCC99','#E2C033','#F9ABC5','#D9A6ED','#FF7C70','#2ECECE','#FBB03B','#66CC66','#3FA9F5','#E377F2,#CCCCFF','#99CCFF','#40D29B','#BDCCD4','#FFCC99','#E2C033','#F9ABC5','#D9A6ED','#FF7C70','#2ECECE','#FBB03B','#66CC66','#3FA9F5','#E377F2,#CCCCFF','#99CCFF','#40D29B','#BDCCD4','#FFCC99','#E2C033','#F9ABC5','#D9A6ED','#FF7C70','#2ECECE','#FBB03B','#66CC66','#3FA9F5','#E377F2,#CCCCFF','#99CCFF','#40D29B','#BDCCD4','#FFCC99','#E2C033','#F9ABC5','#D9A6ED','#FF7C70','#2ECECE','#FBB03B','#66CC66','#3FA9F5','#E377F2');
	function _initialize()
	{
		// $this->LOGS('init'.json_encode($_POST),true);
		$this->model=M();
		if (!session('apphaslogo')===true) {
			$msg=array('status'=>'error','info'=>'登陆已过期');
			$this->LOGS(json_encode($msg));
			echo json_encode($msg);exit();
		}else{
			// session('AreaIds',array(2,3,4));
			$AreaIds=session('AreaIds');
			$Areas=M()->table('RS_AreaList')->where("AreaId in (".implode(',', $AreaIds).")")->getField('Area',true);
			$stokens=M()->table('RS_Store')->where("province in('".implode("','", $Areas)."') and IsCheck='1' and stoken<>'0'")->getField("stoken",true);
			$this->stokens=$stokens;

			// $this->LOGS('----init----'.json_encode($this->stokens),'----',session('AreaIds'));
		}
	}

	/**
	 * app接口
	 */
	public function getsomething(){
		$this->LOGS('get'.json_encode($_POST));
		$type=$_POST['type'];
		$data=$_POST;
		switch ($type) {
			case 'gethomedata':
				$this->gethomedata($data);
				break;
			case 'gettodaydata':
				$this->gettodaydata($data);
				break;
			case 'dataofareas':
				$this->dataofareas($data);
				break;
			case 'getdataofsales':
				$this->getdataofsales($data);
				break;
			case 'linetypes':
				$this->linetypes($data);
				break;
			case 'fcsales':
				$this->fcsales($data);
				break;
			case 'dataofstores':
				$this->dataofstores($data);
				break;
			case 'dataofemps':
				$this->dataofemps($data);
				break;
			default:
				echo json_encode(array('status'=>'error','info'=>'say something'));
				break;
		}
	}

	/**
	 * 首页数据  2017-05-22
	 */
	public function gethomedata($data){
		// $data
		// echo json_encode(array('status'=>'success','info'=>json_encode($data)));
		// $data=array('data_type'=>'a','timetype'=>'d','time'=>'today','oftype'=>'sale');
		$timetype=$data['timetype'];   //时间类型
		$data_type=$data['data_type'];   //区域类型
		$time=$data['time'];   //时间
		$oftype=$data['oftype'];  //数据类型
		$timeline=array();  //折线图的时间线
		if ($timetype=='d') {
			if ($time=='today') {
				$time=date('Y-m-d H:i:s',time());
			}
			$StartDate=date('Y-m-d 00:00:01',strtotime($time));
			$EndDate=date('Y-m-d 23:59:59',strtotime($time));
			$queryTimeType='hh';
			$dataTimeLang=24;
			for ($i=0; $i < $dataTimeLang; $i++) { 
				$timeline[]=$i.':00';
			}
		}elseif ($timetype=='m') {
			$StartDate=date('Y-m-01 00:00:01',strtotime($time));
			$EndDate=date('Y-',strtotime($time)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($time))+1,00));
			$queryTimeType='dd';
			$dataTimeLang=intval(substr(date('Y-m-d',strtotime($EndDate)), -2,2))+1;
			$mo=substr(date('Y-m',strtotime($EndDate)), -2,2);
			for ($i=1; $i < $dataTimeLang; $i++) { 
				$timeline[]=$mo.'-'.$i;
			}
		}elseif ($timetype=='y') {
			$StartDate=$time.'-01-01 00:00:01';
			$EndDate=$time.'-12-31 23:59:59';
			$queryTimeType='mm';
			$dataTimeLang=13;
			for ($i=1; $i < $dataTimeLang; $i++) { 
				$timeline[]=$time.'-'.$i;
			}
		}elseif ($timetype=='w') {
			
		}
		$whereStr="and o.Status in (2,3,4,5,10) and o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}' and o.stoken in('".implode("','", $this->stokens)."')";
		$spStr='';  //特殊查询条件
		// $whereStr='1=1';
		$returndata=array();
		if ($data_type=='a') {
			//区域为主
			$datas=$this->model->query("SELECT CONVERT(float(50),ISNULL(a.allmoney, 0),120) AS allmoney,CONVERT(float(50),ISNULL(a.pcount, 0),120) as pcount,b.scount,b.AreaName,b.ID FROM (SELECT am.AreaName,SUM(o.FTYM) as allmoney,SUM(o.HFTY) as pcount FROM RS_AreaManager am LEFT JOIN RS_AreaList al ON am.ID=al.AreaId LEFT JOIN RS_Store s ON s.province=al.Area LEFT JOIN RS_Order o ON o.stoken=s.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY am.AreaName) a FULL JOIN (SELECT (SELECT COUNT(id) FROM RS_Store WHERE stoken<>'0' AND IsCheck='1' AND province in (SELECT Area FROM RS_AreaList WHERE AreaId=RS_AreaManager.ID)) as scount,ID,AreaName FROM RS_AreaManager WHERE ID in (".implode(',', session('AreaIds')).")) b ON a.AreaName=b.AreaName");
			$HourData=$this->model->query("SELECT CONVERT(float(50),ISNULL(a.allmoney, 0),120) AS allmoney,a.Hour,CONVERT(float(50),ISNULL(a.pcount, 0),120) as pcount,b.scount,b.AreaName,b.ID FROM (SELECT am.AreaName,SUM(o.FTYM) as allmoney,SUM(o.HFTY) as pcount,DATEPART({$queryTimeType},o.CreateDate) as Hour FROM RS_AreaManager am LEFT JOIN RS_AreaList al ON am.ID=al.AreaId LEFT JOIN RS_Store s ON s.province=al.Area LEFT JOIN RS_Order o ON o.stoken=s.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY am.AreaName,DATEPART({$queryTimeType},o.CreateDate)) a FULL JOIN (SELECT (SELECT COUNT(id) FROM RS_Store WHERE stoken<>'0' AND IsCheck='1' AND province in (SELECT Area FROM RS_AreaList WHERE AreaId=RS_AreaManager.ID)) as scount,ID,AreaName FROM RS_AreaManager WHERE ID in (".implode(',', session('AreaIds')).")) b ON a.AreaName=b.AreaName");
			// 
			// $this->LOGS(M()->getlastsql(),true);
		}elseif ($data_type=='p') {
			if ($data['aname']) {
				$whereStr.=" and am.AreaName='{$data['aname']}'";
			}
			$datas=$this->model->query("SELECT a.Area as AreaName,a.ID,a.AreaId,CONVERT(float(50),ISNULL(a.allmoney, 0),120) as allmoney,ISNULL(a.pcount, 0) as pcount FROM (SELECT al.ID,al.Area,al.AreaId,SUM(o.FTYM) as allmoney,SUM(o.HFTY) as pcount FROM RS_AreaList al LEFT JOIN RS_AreaManager am ON al.AreaId=am.ID LEFT JOIN RS_Store s ON al.Area=s.province LEFT JOIN RS_Order o ON o.stoken=s.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY al.AreaId,al.ID,al.Area) a ORDER BY a.allmoney desc ");
		// $this->LOGS('---'.M()->getlastsql(),true);
			$HourData=$this->model->query("SELECT a.Hour,a.Area as AreaName,a.ID,a.AreaId,CONVERT(float(50),ISNULL(a.allmoney, 0),120) as allmoney,ISNULL(a.pcount, 0) as pcount FROM (SELECT al.ID,al.AreaId,al.Area,SUM(o.FTYM) as allmoney,SUM(o.HFTY) as pcount,DATEPART({$queryTimeType},o.CreateDate) as Hour FROM RS_AreaList al LEFT JOIN RS_AreaManager am ON al.AreaId=am.ID LEFT JOIN RS_Store s ON al.Area=s.province LEFT JOIN RS_Order o ON o.stoken=s.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY al.AreaId,al.ID,al.Area,DATEPART({$queryTimeType},o.CreateDate)) a ORDER BY a.allmoney desc ");
			//省份为主
		}elseif ($data_type=='c') {
			//城市为主
			if ($data['pname']) {
				$whereStr.=" and s.province='{$data['pname']}'";
			}
			$datas=$this->model->query("SELECT a.city as AreaName,CONVERT(float(50),ISNULL(a.allmoney, 0),120) as allmoney,ISNULL(a.pcount, 0) as pcount FROM (SELECT s.city,SUM(o.FTYM) as allmoney,SUM(o.HFTY) as pcount FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY s.city) a ");
			$HourData=$this->model->query("SELECT s.city as AreaName,CONVERT(float(53),ISNULL(SUM(o.FTYM),0),120) as allmoney,CONVERT(float(53),SUM(o.HFTY),120) as pcount ,DATEPART({$queryTimeType},o.CreateDate) as Hour FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY s.city,DATEPART({$queryTimeType},o.CreateDate)");
		}elseif ($data_type=='s') {
			//店铺为主
			if ($data['cname']) {
				$whereStr.=" and s.city='{$data['cname']}'";
			}
			$datas=$this->model->query("SELECT s.stoken,CONVERT(float(50),ISNULL(SUM(o.FTYM), 0),120) as allmoney,ISNULL(SUM(o.HFTY), 0) as pcount,storename as AreaName FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY s.storename,s.stoken");
			$HourData=$this->model->query("SELECT DATEPART({$queryTimeType},o.CreateDate) as Hour,s.stoken,CONVERT(float(50),ISNULL(SUM(o.FTYM), 0),120) as allmoney,ISNULL(SUM(o.HFTY), 0) as pcount,storename as AreaName FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE s.stoken<>'0' and s.IsCheck='1' {$whereStr} GROUP BY s.storename,s.stoken,DATEPART({$queryTimeType},o.CreateDate)");
		}
		// echo "<pre>";
		//公共处理
		$piex=array();
		$piey=array();
		$tmpary_s=array();
		foreach ($datas as $da) {
			if ($oftype=='sale') {
				$name='销售额';
				$piex[]=$da['AreaName'];
				$piey[]=array('name'=>$da['AreaName'],'value'=>$da['allmoney']);
				$tmpary_s[]=$da['allmoney'];
			}elseif ($oftype=='count') {
				$name='销售量';
				$piex[]=$da['AreaName'];
				$piey[]=array('name'=>$da['AreaName'],'value'=>$da['pcount']);
				$tmpary_s[]=$da['pcount'];
			}
		}
		$returndata['piex']=$piex;
		$returndata['piey']=$piey;
		$returndata['type']=$name;







		$soBigData=array();
		$tmpary=array();
		foreach ($HourData as $ho) {
			$Aname=$ho['AreaName'];
			$tmpary[$Aname][]=$ho;
		}
		if ($oftype=='sale') {
			$tmptype='allmoney';
		}elseif ($oftype=='count') {
			$tmptype='pcount';
		}
		foreach ($tmpary as $tk => $ty) {
			$base_array=array();
			for ($i=0; $i < $dataTimeLang; $i++) { 
				$base_array[$i]=0;
			}
			$smallData=array();
			foreach ($ty as $ti) {
				if (array_key_exists($ti['Hour'], $base_array)) {
					$base_array[$ti['Hour']]=$ti[$tmptype];
				}
			}
			$smallData['name']=$tk;
			$smallData['type']='line';
			$smallData['smooth']=true;
			$smallData['symbol']='circle';
			$smallData['data']=array_values($base_array);
			$soBigData[]=$smallData;
		}
		$returndata['color']=array_slice($this->theme, 0,count($piex));
		$returndata['timeline']=$timeline;
		$returndata['series']=$soBigData;
		if ($data_type!='a') {
			if (max($tmpary_s)>0 && $returndata['piey']) {
				$returndata['status']='success';
			}else{
				$returndata['status']='error';
			}
		}else{
			$returndata['status']='success';
		}
		// $this->LOGS('---->>>>'.M()->getlastsql(),true);
		// $this->LOGS('反馈信息--->>>'.json_encode($returndata),true);
		echo json_encode($returndata);
	}


	/**
	 * 主页当日数据对比
	 */
	public function gettodaydata($data){
		if ($data['time_type']=='d') {
			$sto=date('Y-m-d 00:00:01',time());
			$eto=date('Y-m-d 23:59:59',time());
			$stt=date('Y-m-d 00:00:01',strtotime('-1 days'));
			$ett=date('Y-m-d 23:59:59',strtotime('-1 days'));
		}elseif ($data['time_type']=='m') {
			$mm=intval(substr(date('Y-m',time()),-2,2))-1; //当前月份
			$sto=date('Y-m-01 00:00:01',time());
			$eto=date('Y-',strtotime($sto)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($sto))+1,00));
			$stt=date('Y-'.$mm.'-01 00:00:01',time());
			$ett=date('Y-',strtotime($stt)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($stt))+1,00));
		}elseif ($data['time_type']=='y') {
			$yy=intval(substr(date('Y-m-d',time()), 0,4));
			$preyy=$yy-1;
			$sto=$yy.'-01-01 00:00:01';
			$eto=$yy.'-12-31 23:59:59';
			$stt=$preyy.'-01-01 00:00:01';
			$ett=$preyy.'-12-31 23:59:59';
		}
		$thisStr="CreateDate BETWEEN '{$sto}' and '{$eto}' and Status in(2,3,4,5,10) and stoken in ('".implode("','", $this->stokens)."')";
		$thatStr="CreateDate BETWEEN '{$stt}' and '{$ett}' and Status in(2,3,4,5,10) and stoken in ('".implode("','", $this->stokens)."')";
		$thisdata=$this->model->table('RS_Order')->where($thisStr)->field("SUM(FTYM) AS allmoney,SUM(HFTY) as pcount")->select();
		$thatdata=$this->model->table('RS_Order')->where($thatStr)->field("SUM(FTYM) AS allmoney,SUM(HFTY) as pcount")->select();
			// $this->LOGS($sto.'---'.$eto.'*********'.$stt.'---'.$ett,true);
		if ($thisdata && $thatdata) {
			$result=array();
			$result['today_money']=$thisdata[0]['allmoney']?$thisdata[0]['allmoney']:0;			
			$result['today_count']=$thisdata[0]['pcount']?$thisdata[0]['pcount']:0;			
			$diffmoney=(floatval($thisdata[0]['allmoney'])-floatval($thatdata[0]['allmoney']))/floatval($thatdata[0]['allmoney'])*100;
			$diffcount=(intval($thisdata[0]['pcount'])-intval($thatdata[0]['pcount']))/intval($thatdata[0]['pcount'])*100;
			$result['diffmoney']=round($diffmoney,2);
			$result['diffcount']=round($diffcount,2);
			$msg['status']='success';
			$msg['data']=$result;
		}else{
			$msg['status']='error';
		}
		// $this->LOGS(json_encode($msg),true);
		echo json_encode($msg);
	}


	/**
	 * 排行相关信息
	 */
	public function dataofareas($data){
		$type=$data['data_type'];
		$EndDate=date('Y-m-d H:00:00',time());
		$StartDate=date('Y-m-d H:00:00',strtotime('-30 days'));
		$whereStr="o.Status in(2,3,4,5,10) and o.stoken in('".implode("','", $this->stokens)."') and o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}'";
		$cxStr='';
		if ($type=='p') {
			$cxStr='s.province';
			//省份排行
		}elseif ($type=='a') {
			//区域
			$cxStr='am.AreaName';
		}elseif ($type=='c') {
			//城市
			$cxStr='s.city';
		}elseif ($type=='x') {
			//县级
			$cxStr='s.area';
		}
		if ($type=='a') {
			$datas=$this->model->query("SELECT am.AreaName,CONVERT(float(53),SUM(o.FTYM),120) as allmoney,ISNULL(SUM(o.HFTY),0) as pcount FROM RS_AreaManager am LEFT JOIN RS_AreaList al ON am.ID=al.AreaId LEFT JOIN RS_Store s ON al.Area=s.province LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE {$whereStr} GROUP BY am.AreaName ORDER BY allmoney asc");
		}else{
			$datas=$this->model->query("SELECT CONVERT(float(53),SUM(o.FTYM),120) as allmoney,ISNULL(SUM(o.HFTY),0) as pcount ,{$cxStr} as AreaName FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE {$whereStr} GROUP BY {$cxStr} ORDER BY allmoney asc");
		}
		// $this->LOGS("-------".M()->getlastsql(),true);
		if ($datas && count($datas)>0) {
			$pagedata['status']='success';
		}else{
			$pagedata['status']='error';
		}
		//处理图表数据
		$barx=array();
		$bary=array();
		$barz=array();
		foreach ($datas as $da) {
			$barx[]=$da['AreaName'];
			$bary[]=$da['allmoney'];
			$barz[]=$da['pcount'];
		}
		$pagedata['barx']=$barx;
		$pagedata['bary']=$bary;
		$pagedata['barz']=$barz;
		// $this->LOGS('首页排行信息反馈--->>>'.json_encode($pagedata),true);
		echo json_encode($pagedata);
	}

	/**
	 * 销售统计相关
	 */
	public function getdataofsales($data){
		$time=$data['time'];
		$time_type=$data['time_type'];
		$data_type=$data['data_type'];  //数据类型订单量、订单额
		$timeline=array();  //折线图的时间线
		if ($time_type=='d') {
			if ($time=='today') {
				$time=date('Y-m-d H:i:s',time());
			}
			$StartDate=date('Y-m-d 00:00:01',strtotime($time));
			$EndDate=date('Y-m-d 23:59:59',strtotime($time));
			$queryTimeType='hh';
			$dataTimeLang=24;
			for ($i=0; $i < $dataTimeLang; $i++) { 
				$timeline[]=$i.':00';
			}
		}elseif ($time_type=='m') {
			$StartDate=date('Y-m-01 00:00:01',strtotime($time));
			$EndDate=date('Y-',strtotime($time)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($time))+1,00));
			$queryTimeType='dd';
			$dataTimeLang=intval(substr(date('Y-m-d',strtotime($EndDate)), -2,2))+1;
			$mo=substr(date('Y-m',strtotime($EndDate)), -2,2);
			for ($i=1; $i < $dataTimeLang; $i++) { 
				$timeline[]=$mo.'-'.$i;
			}
		}elseif ($time_type=='y') {
			$StartDate=$time.'-01-01 00:00:01';
			$EndDate=$time.'-12-31 23:59:59';
			$queryTimeType='mm';
			$dataTimeLang=13;
			for ($i=1; $i < $dataTimeLang; $i++) { 
				$timeline[]=$time.'-'.$i;
			}
		}elseif ($time_type=='w') {
			
		}
		$whereStr=" o.Status in (2,3,4,5,10) and o.stoken in ('".implode("','", $this->stokens)."') and o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}'";
		if ($data['pname']) {
			$whereStr.=" and s.province='{$data['pname']}'";
		}
		if ($data['cname']) {
			$whereStr.=" and s.city='{$data['cname']}'";
		}
		$Mdata=$this->model->query("SELECT CONVERT(float(53),SUM(o.FTYM),120) as allmoney,ISNULL(SUM(o.HFTY),0) as pcount,DATEPART({$queryTimeType},o.CreateDate) as Hour FROM RS_Order o LEFT JOIN RS_Store s ON o.stoken=s.stoken WHERE $whereStr GROUP BY DATEPART({$queryTimeType},o.CreateDate)");
		// $this->LOGS(M()->getlastsql(),true);
		//主图表数据
		$base_array=array();
		for ($i=0; $i < $dataTimeLang; $i++) { 
			$base_array[$i]=0;
		}
		$spStr='';
		if ($data_type=='sales') {
			$spStr='allmoney';
		}elseif ($data_type=='count') {
			$spStr='pcount';
		}
		foreach ($Mdata as $da) {
			if (array_key_exists($da['Hour'], $base_array)) {
				$base_array[$da['Hour']]=$da[$spStr];
			}
		}
		$linex=$timeline;
		$liney=array_values($base_array);
		//饼图数据
		$pieData=$this->model->query("SELECT CONVERT(float(53),SUM(o.FTYM),120) as allmoney,ISNULL(SUM(o.HFTY),0) as pcount,(CASE o.PayName WHEN 'T' THEN '微信支付' WHEN 'XJ' THEN '现金支付' WHEN 'POSXJ' THEN 'POS端现金支付' ELSE '' END) as PayName FROM RS_Order o LEFT JOIN RS_Store s ON o.stoken=s.stoken WHERE $whereStr GROUP BY o.PayName");
		$piex=array();
		$piey=array();
		$i=0; //颜色下标
		foreach ($pieData as $pd) {
			$piex[]=$pd['PayName'];
			$piey[]=array('name'=>$pd['PayName'],'value'=>$pd[$spStr],'itemStyle'=>array('normal'=>array('color'=>$this->theme[$i])));
			$i++;
		}
		//表格数据
		$tableData=$this->model->query("SELECT CONVERT(float(53),ISNULL(SUM(o.FTYM), 0),120) as allmoney,ISNULL(SUM(o.HFTY),0) as pcount,s.storename,em.EmpId FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken LEFT JOIN RS_EmpOfStore em ON em.StoreId=s.id WHERE {$whereStr} GROUP BY s.storename,em.EmpId ORDER BY {$spStr} desc");
		$empnames=$this->MSL('user')->where("stoken='0'")->getField('id,TrueName');
		foreach ($tableData as &$tb) {
			$tb['EmpName']=$empnames[$tb['EmpId']];
		}
		$pagedata['linex']=$linex;  
		$pagedata['liney']=$liney;
		$pagedata['piex']=$piex;
		$pagedata['piey']=$piey;
		$pagedata['tableData']=$tableData;
		// $this->LOGS('--->>>销售统计反馈'.json_encode($pagedata),true);
		if ($Mdata || $pieData || $tableData) {
			$pagedata['status']='success';
			echo json_encode($pagedata);
		}else{
			echo json_encode(array('status'=>'error'));
		}
	}

	/**
	 * 渠道数据
	 */
	public function linetypes($data){
		// $this->LOGS()
		$time=$data['time'];
		$lingtype=$data['linetype'];
		$time_type=$data['time_type'];
		$data_type=$data['data_type']; //
		if ($time_type=='d') {
			if ($time=='today') {
				$time=date('Y-m-d H:i:s',time());
			}
			$StartDate=date('Y-m-d 00:00:01',strtotime($time));
			$EndDate=date('Y-m-d 23:59:59',strtotime($time));
		}elseif ($time_type=='m') {
			$StartDate=date('Y-m-01 00:00:01',strtotime($time));
			$EndDate=date('Y-',strtotime($time)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($time))+1,00));
		}elseif ($time_type=='y') {
			$StartDate=$time.'-01-01 00:00:01';
			$EndDate=$time.'-12-31 23:59:59';
		}elseif ($time_type=='w') {
			
		}
		$whereStr="o.Status in(2,3,4,5,10) and o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}' and o.stoken in ('".implode("','", $this->stokens)."')";
		if ($data['pname']) {
			$whereStr.=" and s.province='{$data['pname']}'";
		}
		if ($data['cname']) {
			$whereStr.=" and s.city='{$data['cname']}'";
		}
		$pieData=$this->model->query("SELECT SUM(a.allmoney) as allmoney,a.linetype FROM (SELECT CONVERT(float(53),ISNULL(SUM(o.FTYM),0),120) as allmoney,(CASE o.PayName WHEN 'T' THEN 'online' ELSE 'offline' END) as linetype FROM RS_Order o LEFT JOIN RS_Store s ON o.stoken=s.stoken WHERE {$whereStr} GROUP BY o.PayName) a GROUP BY a.linetype");
		$piex=array('线上销售','线下销售');
		$piey=array();
		$i=0;
		foreach ($pieData as $pd) {
			if ($pd['linetype']=='offline') {
				$piey[]=array('name'=>'线下销售','value'=>$pd['allmoney'],'itemStyle'=>array('normal'=>array('color'=>$this->theme[$i])));
			}elseif ($pd['linetype']=='online') {
				$piey[]=array('name'=>'线上销售','value'=>$pd['allmoney'],'itemStyle'=>array('normal'=>array('color'=>$this->theme[$i])));
			}
			$i++;
		}
		$table_data=$this->model->query("SELECT SUM(a.allmoney) as allmoney,a.storename,a.sid,a.EmpId,a.PayName FROM (SELECT CONVERT(float(53),ISNULL(SUM(o.FTYM),0),120) as allmoney,s.storename,s.id as sid,em.EmpId,(CASE o.PayName WHEN 'T' THEN 'online' ELSE 'offline' END) AS PayName FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken LEFT JOIN RS_EmpOfStore em ON s.id=em.StoreId WHERE {$whereStr} GROUP BY s.storename ,em.EmpId,o.PayName,s.id) a GROUP BY a.PayName,a.storename,a.EmpId,a.sid ORDER BY allmoney desc");
		// $this->LOGS('--------'.M()->getlastsql(),true);
		$empnames=$this->MSL('user')->where("stoken='0'")->getField('id,TrueName');
		$newtable=array();
		foreach ($table_data as &$tb) {
			if (!array_key_exists($tb['sid'], $newtable)) {
				$newtable[$tb['sid']]['offline']=0;
				$newtable[$tb['sid']]['online']=0;
			}
			$newtable[$tb['sid']]['EmpName']=$empnames[$tb['EmpId']];
			$newtable[$tb['sid']]['storename']=$tb['storename'];
			if ($tb['PayName']=='online') {
				$newtable[$tb['sid']]['online']=$tb['allmoney'];
			}elseif ($tb['PayName']=='offline') {
				$newtable[$tb['sid']]['offline']=$tb['allmoney'];
			}
		}
		if ($pieData || $table_data) {
			$pagedata['status']='success';
		}else{
			$pagedata['status']='error';
		}
		$pagedata['piex']=$piex;
		$pagedata['piey']=$piey;
		$pagedata['table_data']=$newtable;
		// $this->LOGS("--->>>渠道数据--->>>".json_encode($pagedata),true);
		echo json_encode($pagedata);
	}


	/**
	 * 平台销售
	 */
	public function fcsales($data){
		$time_type=$data['time_type'];
		$time=date('Y-m-d H:i:s',time()); 
		$desc=$data['desc'];
		if ($time_type=='d') {
			$StartDate=date('Y-m-d 00:00:01',strtotime($time));
			$EndDate=date('Y-m-d 23:59:59',strtotime($time));
		}elseif ($time_type=='m') {
			$StartDate=date('Y-m-01 00:00:01',strtotime($time));
			$EndDate=date('Y-',strtotime($time)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($time))+1,00));
		}elseif ($time_type=='y') {
			$StartDate=date('Y-01-01 00:00:01',strtotime($time));
			$EndDate=date('Y-12-31 23:59:59',strtotime($time));
		}elseif ($time_type=='w') {
			
		}
		$whereStr="o.Status in (2,3,4,5,10) and o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}' AND p.stoken='0' and o.stoken='0'";
		$datas=$this->model->query("SELECT TOP 50 p.ProName,CONVERT(float(53),ISNULL(SUM(ol.Money), 0),120) as money,ISNULL(SUM(ol.Count), 0) as count FROM RS_Product p LEFT JOIN RS_OrderList ol ON p.ProId=ol.ProId LEFT JOIN RS_Order o ON o.OrderId=ol.OrderId WHERE {$whereStr} GROUP BY p.ProName ORDER BY money {$desc}");
		$i=1;
		foreach ($datas as $key => $value) {
			$datas[$key]['RowNumber']=$i;
			$i++;
		}
		$datas=array_reverse($datas);
		$barx=array();
		$bary=array();
		$barz=array();
		foreach ($datas as $da) {
			$barx[]=$da['ProName'];
			$bary[]=$da['money'];
			$barz[]=$da['count'];
		}
		if ($datas) {
			$pagedata['status']='success';
			$pagedata['barx']=$barx;
			$pagedata['bary']=$bary;
			$pagedata['barz']=$barz;
			$pagedata['table_data']=array_reverse($datas);
		}else{
			$pagedata['status']='error';
		}
		// $this->LOGS('--->>平台销售--->>>'.json_encode($pagedata),true);
		echo json_encode($pagedata);
	}

	/**
	 * 门店销售排行
	 */
	public function dataofstores($data){
		$time_type=$data['time_type'];
		$time=date('Y-m-d H:i:s',time());
		$desc=$data['desc'];
		if ($time_type=='d') {
			$StartDate=date('Y-m-d 00:00:01',strtotime($time));
			$EndDate=date('Y-m-d 23:59:59',strtotime($time));
		}elseif ($time_type=='m') {
			$StartDate=date('Y-m-01 00:00:01',strtotime($time));
			$EndDate=date('Y-',strtotime($time)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($time))+1,00));
		}elseif ($time_type=='y') {
			$StartDate=date('Y-01-01 00:00:01',strtotime($time));
			$EndDate=date('Y-12-31 23:59:59',strtotime($time));
		}elseif ($time_type=='w') {
			
		}
		$whereStr="o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}' and o.Status in (2,3,4,5,10) and o.stoken in('".implode("','", $this->stokens)."')";
		if ($data['pname']) {
			$whereStr.=" and s.province='{$data['pname']}'";
		}
		if ($data['cname']) {
			$whereStr.=" and s.city='{$data['cname']}'";
		}
		$alldata=$this->model->query("SELECT TOP 50 em.EmpId,CONVERT(float(53),ISNULL(SUM(o.FTYM), 0)) as money , ISNULL(SUM(o.HFTY), 0) as count,s.storename FROM RS_Store s LEFT JOIN RS_Order o ON o.stoken=s.stoken LEFT JOIN RS_EmpOfStore em ON em.StoreId=s.id WHERE {$whereStr} GROUP BY s.storename,em.EmpId ORDER BY money {$desc}");
		$i=1;
		foreach ($alldata as $key => $value) {
			$alldata[$key]['RowNumber']=$i;
			$i++;
		}
		$alldata=array_reverse($alldata);
		$barx=array();
		$bary=array();
		$barz=array();
		$empnames=$this->MSL('user')->where("stoken='0'")->getField('id,TrueName');
		foreach ($alldata as &$da) {
			$barx[]=$da['storename'];
			$bary[]=$da['money'];
			$barz[]=$da['count'];
			$da['EmpName']=$empnames[$da['EmpId']];
		}
		if ($alldata) {
			$pagedata['status']='success';
			$pagedata['barx']=$barx;
			$pagedata['bary']=$bary;
			$pagedata['barz']=$barz;
			$pagedata['tableData']=array_reverse($alldata);
		}else{
			$pagedata['status']='error';
		}
		// $this->LOGS("--->>>门店销售排行--->>>".json_encode($pagedata),true);
		echo json_encode($pagedata);
	}


	/**
	 * 员工管理门店销售排行
	 */

	public function dataofemps($data){
		$time_type=$data['time_type'];
		$time=date('Y-m-d H:i:s',time());
		$desc=$data['desc'];
		if ($time_type=='d') {
			$StartDate=date('Y-m-d 00:00:01',strtotime($time));
			$EndDate=date('Y-m-d 23:59:59',strtotime($time));
		}elseif ($time_type=='m') {
			$StartDate=date('Y-m-01 00:00:01',strtotime($time));
			$EndDate=date('Y-',strtotime($time)).date('m-d 23:59:59',mktime(0,0,0,date('m',strtotime($time))+1,00));
		}elseif ($time_type=='y') {
			$StartDate=date('Y-01-01 00:00:01',strtotime($time));
			$EndDate=date('Y-12-31 23:59:59',strtotime($time));
		}elseif ($time_type=='w') {
			
		}
		$whereStr=" o.Status in (2,3,4,5,10) and o.CreateDate BETWEEN '{$StartDate}' and '{$EndDate}' and o.stoken in ('".implode("','", $this->stokens)."')";
		if ($data['pname']) {
			$whereStr.=" and s.province='{$data['pname']}'";
		}
		if ($data['cname']) {
			$whereStr.=" and s.city='{$data['cname']}'";
		}
		$alldata=$this->model->query("SELECT TOP 50 CONVERT(float(53),ISNULL(SUM(o.FTYM), 0),120) as money,ISNULL(SUM(o.HFTY), 0) as count,em.EmpId FROM RS_EmpOfStore em LEFT JOIN RS_Store s ON em.StoreId=s.id LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE {$whereStr} GROUP BY em.EmpId ORDER BY money {$desc}");
		$i=1;
		foreach ($alldata as $key => $value) {
			$alldata[$key]['RowNumber']=$i;
			$i++;
		}
		$alldata=array_reverse($alldata);
		$barx=array();
		$bary=array();
		$barz=array();
		$empnames=$this->MSL('user')->where("stoken='0'")->getField('id,TrueName');
		foreach ($alldata as &$ada) {
			$barx[]=$empnames[$ada['EmpId']];
			$bary[]=$ada['money'];
			$barz[]=$ada['count'];
			$ada['EmpName']=$empnames[$ada['EmpId']];
		}
		if ($alldata && count($alldata)>0) {
			$pagedata['status']='success';
			$pagedata['barx']=$barx;
			$pagedata['bary']=$bary;
			$pagedata['barz']=$barz;
			$pagedata['tableData']=array_reverse($alldata);
		}else{
			$pagedata['status']='error';
		}
		$this->LOGS('人员销售排行--->>>'.json_encode($pagedata));
		echo json_encode($pagedata);
	}





















}














 ?>