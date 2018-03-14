<?php
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class StoreController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function add(){
		// var_dump($_POST);exit;
		if (IS_POST) {
			// var_dump($this->token);
			$data['addr']=$_POST['addr'];
			$data['storename']=$_POST['StoreName'];
			$data['tel']=$_POST['tel'];
			$data['token']=$this->token;
			$data['lang']=$_POST['lang']; //经度
			$data['lat']=$_POST['lat']; //纬度
			$data['slang']=$_POST['lang']>0?$_POST['lang']:360+$_POST['lang']; //搜索经度
			$data['stoken']=0;
			if ($_POST['id']) {
				if ($_POST['ischangeaddr']=='1') {
					$data['province']=$_POST['province'];
					$data['city']=$_POST['city'];
					$data['area']=$_POST['county'];
				}
				// var_dump($_POST);exit;
				if (M()->table('RS_Store')->where('id=%d',$_POST['id'])->save($data)) {
					$this->success('操作成功',U('Store/index'));
				}else{
					$this->error('操作失败');
				}
			}else{
				$data['province']=$_POST['province'];
				$data['city']=$_POST['city'];
				$data['area']=$_POST['county'];
				M()->startTrans();
				$res=M()->table('RS_Store')->add($data);
				// echo M()->getlastSql();
				$tbName=substr($this->token,-8,8).'_'.$res;
				$tempStr=$this->warehouseSQLString($tbName);
				$houseRes=$this->SH('temp')->db()->query($tempStr);
				$hres=true;
				if ($houseRes===false) {
					$hres=false;
				}
				// var_dump($res,$hres);exit;
				if ($res && $hres) {
					M()->commit();
					$this->success('操作成功',U('Store/index'));
				}else{
					M()->rollback();
					$this->error('操作失败');
				}
			}
		}else{
			$this->assign('store',$store['id']=null);
			define('FPAGE','MENDIAN');
			
			
			$this->display();
		}
	}

	public function index(){
		$stores=M()->table('RS_Store')->where("token='%s'",$this->token)->select();
		foreach ($stores as &$st) {
			$st['ordernums']=M()->table('RS_Order')->where("RecevingPost='%s' and RecevingName='%s' and (ZTname<>'' or XJname<>'')",array('ZT',$st['id']))->count();
			// echo M()->getlastSql();exit();
			$st['moneys']=M()->table('RS_Order')->where("RecevingPost='%s' and RecevingName='%s' and (ZTname<>'' or XJname<>'')",array('ZT',$st['id']))->sum('Price');
		}
		$this->assign('stores',$stores);
			define('FPAGE','MENDIAN');
		$this->display();
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
					$where="XJname='".$openid."' and stoken='".$tempData['stoken']."' and token='".$this->token."' AND XJscantime BETWEEN '".$stime."' AND '".$etime."'";
				};
				if ($CanType=='get') {
					$where="ZTname='".$openid."' and stoken='".$tempData['stoken']."' and token='".$this->token."' AND Cantime BETWEEN '".$stime."' AND '".$etime."'";
				}
			// }
			$count=M()->table('RS_Order')->where($where)->count();
			$page=new \Think\Page($count,15);
			$lists=M()->table('RS_Order')->where($where)->limit($page->firstRow.','.$page->listRows)->order('CreateDate desc')->select();
			// $this->LOGS(M()->getlastsql());
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

	/**
	 * 申请开店
	 */
	public function register(){
		$this->assign($pagedata);
		$this->display();
	}



	//创建仓库sql

	public function warehouseSQLString($token)
	{
		return "
CREATE TABLE [dbo].[tb_wh".$token."](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[ProId] [varchar](50) NOT NULL,
	[ProIdCard] [varchar](50) NULL,
	[StockCount] [float] NOT NULL,
	[LimitCount] [float] NOT NULL,
	[VirtualCount] [float] NOT NULL,
	[InCount] [float] NOT NULL,
	[SalesCount] [float] NOT NULL,
	[OutCount] [float] NOT NULL,
	[ReturnCount] [float] NOT NULL,
	[IsDelete] [int] NULL,
	[CreateDate] [datetime2](7) NOT NULL,
	[LastUpdateDate] [datetime2](7) NOT NULL,
 CONSTRAINT [PK_tb_wh".$token."] PRIMARY KEY CLUSTERED
([ID] ASC)
WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
";
	}

	/**
	 * 收银台二维码
	 */
	public function getcashierqr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		$url='http://'.$_SERVER['HTTP_HOST'].U('Seller/OfflineCashier/Index',array('token'=>$this->token,'stoken'=>$this->stoken,'sid'=>$_GET['id']));
		$level="L";
		$size=4;
		\QRcode::png($url,$filename,$level,$size,'2');
	}



}
