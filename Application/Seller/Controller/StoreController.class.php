<?php
namespace Seller\Controller;
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
			$data['Token']=$this->token;
			$data['lang']=$_POST['lang']; //经度
			$data['lat']=$_POST['lat']; //纬度
			$data['slang']=$_POST['lang']>0?$_POST['lang']:360+$_POST['lang']; //搜索经度
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
			$this->display();
		}
	}

	public function index(){
		$stores=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		foreach ($stores as &$st) {
			$st['ordernums']=M()->table('RS_Order')->where("RecevingPost='%s' and RecevingName='%s' and (ZTname<>'' or XJname<>'')",array('ZT',$st['id']))->count();
			$st['moneys']=M()->table('RS_Order')->where("RecevingPost='%s' and RecevingName='%s' and (ZTname<>'' or XJname<>'')",array('ZT',$st['id']))->sum('Price');
		}
		$this->assign('stores',$stores);
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
					$where="XJname='".$openid."' and stoken='".$this->stoken."' and token='".$this->token."' AND XJscantime BETWEEN '".$stime."' AND '".$etime."'";
				};
				if ($CanType=='get') {
					$where="ZTname='".$openid."' and stoken='".$this->stoken."' and token='".$this->token."' AND Cantime BETWEEN '".$stime."' AND '".$etime."'";
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
				$li['Price']=floatval($li['Price']);
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

//////////////////平台商户首页设置和保存/////////////////////
public function pageset(){
	if (IS_POST) {
		$homedata=$_POST;
		if ($homedata['stype']=='0') {
			$homelbdata['ImgPath']=$homedata['lbiurl'];
			$homelbdata['ImgUrl']=$homedata['lbhref'];
			$homelbdata['Sort']=$homedata['sort'];
			$homelbdata['IsShow']=1;
			$homelbdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
			$homelbdata['token']=$this->token;
			$homelbdata['stoken']=$this->stoken;
			if ($homedata['type']=='add') {
				$res=M('homeimg')->add($homelbdata);
			} else {
				$res=M('homeimg')->where(array('ID'=>$homedata['hid']))->save($homelbdata);
			}
		} elseif ($homedata['stype']=='1') {
			$hotprodata['ProId']=$homedata['hpid'];
			$hotprodata['token']=$this->token;
			$hotprodata['stoken']=$this->stoken;
			$red= M('productlabellist')->where($hotprodata)->where('ProLabel=1')->find();

      if (!$red) {
        $hotdata=$hotprodata;
        $hotdata['ProLabel']='1';
        $hotdata['LabelType']='0';
        M('productlabellist')->add($hotdata);
      }
			if ($homedata['type']=='add') {
				$hotprodata['Position']='HOT'.$homedata['hcount'];
				$res=M('productonhome')->add($hotprodata);
			} else {
				$res=M('productonhome')->where(array('ID'=>$homedata['hid']))->save($hotprodata);
			}
		}elseif ($homedata['stype']=='2') {
			$newprodata['ProId']=$homedata['hpid'];
			$newprodata['token']=$this->token;
			$newprodata['stoken']=$this->stoken;
			$newprodata['prohomeimg']=$homedata['homeimg'];
			if ($homedata['type']=='add') {
				$newprodata['Position']='NEW'.$homedata['hcount'];
				$res=M('productonhome')->add($newprodata);
			} else {
				$res=M('productonhome')->where(array('ID'=>$homedata['hid']))->save($newprodata);
			}
		}

		if ($res) {
			$this->ajaxReturn(array('status'=>'true','info'=>$res));
		} else {
			$this->ajaxReturn(array('status'=>'false','info'=>'false'));
		}
	} else {
		$sqlStr="SELECT p.ProId,poh.[Position],(CASE WHEN (p.stoken='0' AND mp.Price IS NOT NULL) THEN mp.Price ELSE p.PriceRange
	 END) AS PriceRange,p.ProName,p.ProTitle,p.ProLogoImg,p.Price FROM
	 RS_Product p LEFT JOIN  RS_ProductOnHome poh ON poh.ProId = p.ProId
	 LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
		WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON
		p.ProId = mp.ProId WHERE p.token='".$this->token."' AND p.IsShelves=1 AND
	(p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."' OR poh.stoken='0') AND
	poh.[Position]='SHOP_HOT' ORDER BY poh.[Position]";
	$prohot=M()->query($sqlStr);
	$pagedata['prohot']=$prohot[0];
	////平台设置小店的新品
	$sqlStr="SELECT p.ProId,poh.[Position],(CASE WHEN (p.stoken='0' AND mp.Price IS NOT NULL) THEN mp.Price ELSE p.PriceRange
	 END) AS PriceRange,p.ProName,p.ProTitle,p.ProLogoImg,p.Price FROM
	 RS_Product p LEFT JOIN  RS_ProductOnHome poh ON poh.ProId = p.ProId
	 LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
		WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON
		p.ProId = mp.ProId WHERE p.token='".$this->token."' AND p.IsShelves=1 AND
	(p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."' OR poh.stoken='0') AND
	poh.[Position]='SHOP_NEW' ORDER BY poh.[Position]";
	$pronew=M()->query($sqlStr);
	$pagedata['pronew']=$pronew[0];
	//////分类数据
	$info=json_decode(file_get_contents('./Public/pagesethome/pageconfig.json'),true);
	$this->assign('info',$info);
	//////////////////////////////////////////////////////
	///小店设置的热卖商品
	$sqlStr="SELECT p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,p.Price,poh.[Position],poh.ID,
	(CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_ProductOnHome poh
	LEFT JOIN RS_Product p ON p.ProId = poh.ProId LEFT JOIN
	(SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
	WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp
	ON poh.ProId=mp.ProId
	WHERE poh.[Position] LIKE '%HOT%' AND poh.token='".$this->token."'
	AND poh.stoken='".$this->stoken."' ORDER BY poh.[Position]";
	$pagedata['selhotinfo']=M()->query($sqlStr);
	/////小店设置的新品商品
	$sqlStr="SELECT p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,p.Price,poh.[Position],poh.ID,poh.prohomeimg,
	(CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_ProductOnHome poh
	LEFT JOIN RS_Product p ON p.ProId = poh.ProId LEFT JOIN
	(SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
	WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp
	ON poh.ProId=mp.ProId
	WHERE poh.[Position] LIKE '%NEW%' AND poh.token='".$this->token."'
	AND poh.stoken='".$this->stoken."' ORDER BY poh.[Position]";

	$pagedata['selnewinfo']=M()->query($sqlStr);

 /////////////////////////////////////////////////
 ///小店基本信息
 $pagedata['shopinfo']=M('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
 ///平台设置的小店的轮播图
 $pagedata['homeimg']=M()->table('RS_HomeImg')->where("token='%s' and stoken='%s'",array($this->token,0))->find();
 ///小店自己设置的轮播图
 $pagedata['lbdata']=M('homeimg')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'IsShow'=>1))->order('ID')->select();
 /////热卖商品//////////
 $sqlStr="SELECT p.ProId,p.ProName,p.ProLogoImg,(CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros WHERE token = '".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token ='".$this->token."' AND (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."') AND p.IsShelves='1'";
 $this->assign('hotproinfo',M()->query($sqlStr));
 /////新品商品//////////
 $sqlStr="SELECT p.ProId,p.ProName,p.ProLogoImg,
 (CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange
 FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM
 RS_MerPros WHERE token = '".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp
 ON p.ProId = mp.ProId LEFT JOIN RS_ProductLabelList pll ON p.ProId = pll.ProId
 WHERE p.token ='".$this->token."' AND (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."')
 AND pll.ProLabel=2 AND pll.stoken='".$this->stoken."' AND pll.token='".$this->token."' AND p.IsShelves='1' ";
 $this->assign('newproinfo',M()->query($sqlStr));
 $this->assign($pagedata);
		$this->display();
	}
}
//////////////////////删除首页配置///////////////////////
public function delonhome(){
	if (IS_POST) {
		$type=$_POST['type'];
		$hid=$_POST['hid'];
		if ($type=='0') {
			$res=M('homeimg')->where(array('ID'=>$hid))->delete();
		} else if ($type=='1') {
			$res=M('productonhome')->where(array('ID'=>$hid))->delete();
		}
		if ($res) {
			$this->ajaxReturn(array('status'=>'true','info'=>'success'));
		} else {
			$this->ajaxReturn(array('status'=>'false','info'=>'false'));
		}
	} else {
		$this->ajaxReturn(array('status'=>'false','info'=>'false'));
	}
}

	/**
	 * 配送员审核
	 */
	public function peisong(){
		if (IS_POST) {

		}else{
			$datalist=M()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution d ON ds.MemberId=d.MemberId OR ds.OpenId=d.OpenId")->where("ds.stoken='%s'",$this->stoken)->field("ds.Status,CONVERT(varchar(20),ds.AskDate,120) as AskDate,d.TrueName,d.IdCard,d.IdImg,d.Phone,d.HeadImg,ds.ID,d.OpenId")->select();
			// var_dump(M()->getlastSql());
			$pagedata['stores']=$datalist;
			$pagedata['openid']=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('openid');
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 审核相关操作
	 */
	public function changeStatu(){
		$type=$_POST['type'];
		$id=$_POST['id'];
		if ($type=='delete') {
			//删除
			if (M()->table('RS_DistributionForStore')->where("ID=%d",$id)->delete()) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='删除失败';
			}
		}elseif ($type=='success') {
			//通过审核
			if (M()->table('RS_DistributionForStore')->where("ID=%d",$id)->setField('Status','1')) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='处理失败';
			}
		}elseif ($type=='error') {
			//拒绝审核
			if (M()->table('RS_DistributionForStore')->where('ID=%d',$id)->setField('Status','2')) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='处理失败';
			}
		}else{
			$msg['status']='error';
			$msg['info']='非法操作';
		}
		echo json_encode($msg);
	}

	/**
	 * 配送订单 2017-05-14
	 */
	public function Psorder(){
		if (IS_POST) {
			$Param=$_POST;
		}else{
			$Param=$_GET;
		}
		unset($Param['p']);
		unset($Param['v']);
		$whereStr="do.token='{$this->token}' and do.stoken='{$this->stoken}' and do.IsDelete='0'";
		if ($Param && count($Param)) {
			if ($Param['OrderId']) {
				$whereStr.=" do.and OrderId LIKE '%{$Param['OrderId']}%'";
			}
			if ($Param['Psinfo']) {
				$whereStr.=" and (d.TrueName LIKE '%{$Param['Psinfo']}%' OR d.Phone LIKE '%{$Param['Psinfo']}%')";
			}
		}
		$count=M()->table('RS_DistributionForOrder do')->join("LEFT JOIN RS_Distribution d ON do.OpenId=d.OpenId")->where($whereStr)->count();
		$page = new \Think\Page($count,20);
		$lists=M()->table('RS_DistributionForOrder do')->join("LEFT JOIN RS_Distribution d ON do.OpenId=d.OpenId")->join("LEFT JOIN RS_Order o ON o.OrderId=do.OrderId")->where($whereStr)->field('do.OrderId,CONVERT(varchar(20),do.GetDate,120) as GetDate,o.RecevingProvince+o.RecevingCity+o.RecevingArea+o.RecevingAddress+o.RecevingName+o.RecevingPhone as Receving,d.TrueName+d.Phone as PS,do.Status,CONVERT(varchar(20),do.OverDate,120) as OverDate')->order("GetDate desc")->limit($page->firstRow.','.$page->listRows)->select();
		$pagedata['page']=$page->show();
		$pagedata['order']=$lists;
		$pagedata['param']=$Param;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 重新配送  2017-05-19
	 */
	public function resend(){
		$oid=$_POST['oid'];
		$doinfo=M()->table('RS_DistributionForOrder')->where("OrderId='%s' and IsDelete='0'",$oid)->find();
		$oinfo=M()->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$wxinfo=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find();
		$sinfo=M()->table('RS_Store')->where("stoken='%s'",$this->stoken)->find();
		M()->startTrans();
		$dores=M()->table('RS_DistributionForOrder')->where("OrderId='%s' and IsDelete='0'",$oid)->setField('IsDelete','1');
		$bosss=M()->table('RS_Distribution')->where("IsBoss='1'")->getField("OpenId",true);
		if (in_array($doinfo['OpenId'], $bosss)) {
			// echo "老板";exit();
			$dres=true;
		}else{
			$dres=M()->table('RS_Distribution')->where("OpenId='%s'",$doinfo['OpenId'])->setField('IsReceving','1');
		}
		// var_dump($bosss,$doinfo['OpenId']);exit();
		// if ($doinfo['OpenId']==$sinfo['openid']) {
		// 	$dres=true;
		// }else{
		// 	$dres=M()->table('RS_Distribution')->where("OpenId='%s'",$doinfo['OpenId'])->setField('IsReceving','1');
		// }
		// var_dump(M()->getlastsql());
		if ($dores && $dres) {
			$msgr['status']='success';
			M()->commit();
			//获取接收人
			import('vendor.Wechat.WXTemplate');
			$msgers=M()->query("SELECT  ds.OpenId FROM RS_DistributionForStore ds LEFT JOIN RS_Distribution d ON ds.OpenId=d.OpenId  WHERE  ds.stoken='{$this->stoken}' and d.IsReceving='0' and ds.Status='1' ");
			// var_dump($msgers);
			// $this->LOGS(M()->getlastsql().json_encode($msgers));
			// var_dump(M()->getlastSql());
			$wxparam=array();
			$wxparam['appid']=$wxinfo['appid'];
			$wxparam['appsecert']=$wxinfo['appsecret'];
			$WXMSG=new \WXTemplate($wxparam);
			foreach ($msgers as $msg) {
				$MSGDATA=array(
					'touser'=>$msg['OpenId'],
					'template_id'=>'t3kQ8SlQC13-YZt5pxxKTubLCeLUAz6hd0YBZ2ksGJE',
					'first'=>array('value'=>'您有一份新的订单('.'取货门店:'.$sinfo['storename'].')','color'=>'#000000'), //必填
                    'remark'=>array('value'=>'点击下方详情抢单','color'=>'#000000'), //必填
                    'url'=>'https://'.$_SERVER['HTTP_HOST'].U('Admin/Base/getordersoon',array('openid'=>$msg['OpenId'],'oid'=>$oid)),
                	'content'=>array(
                		0=>array('value'=>$oid,'color'=>'#000000'),
                		1=>array('value'=>date('Y-m-d H:i:s',time()),'color'=>'#000000'),
                		2=>array('value'=>$oinfo['RecevingName'],'color'=>'#000000'),
                		3=>array('value'=>$oinfo['RecevingPhone'],'color'=>'#000000'),
                		4=>array('value'=>$oinfo['RecevingProvince'].$oinfo['RecevingCity'].$oinfo['RecevingArea'].$oinfo['RecevingAddress'],'color'=>'#000000'),
            		)
            	);
                $res=$WXMSG->sendTemplate($MSGDATA);
                $this->LOGS('模板消息发送日志--->>>'.$res);
            }
		}else{
			M()->rollback();
			$this->LOGS('重新派送处理失败dores='.$dores.'...dres='.$dres);
			$msgr['status']='error';
		}
		echo json_encode($msgr);
	}

	/**
	 * 配送提现审核   2017-05-14
	 */
	public function pscheck(){
		if (IS_POST) {
			$Param=$_POST;
		}else{
			$Param=$_GET;
		}
		unset($Param['v']);
		unset($Param['p']);
		$whereStr="dc.token='{$this->token}' and dc.stoken='{$this->stoken}'";
		if ($Param && count($Param)>0) {
			if ($Param['MemberName']) {
				$whereStr.=" and d.TrueName LIKE '%{$Param['MemberName']}%'";
			}
			if ($Param['Status']) {
				$whereStr.=" and dc.Status='{$Param['Status']}'";
			}
			if ($Param['StartDate'] && $Param['EndDate']) {
				$whereStr.=" and dc.CreateDate BETWEEN '{$Param['StartDate']}' and '{$Param['EndDate']}'";
			}
		}
		$count=M()->table('RS_DistributionCashDetail dc')->join("LEFT JOIN RS_Distribution d ON dc.OpenId=d.OpenId")->where($whereStr)->count();
		$page = new \Think\Page($count,20,$Param);
		$lists=M()->table('RS_DistributionCashDetail dc')->join("LEFT JOIN  RS_Distribution d ON dc.OpenId=d.OpenId")->where($whereStr)->field("dc.ID,CONVERT(float(53),ISNULL(dc.Money,0),120) as Money,CONVERT(varchar(20),dc.CreateDate,120) as CreateDate,d.TrueName,(CASE dc.Status WHEN '0' THEN '待审核' WHEN '1' THEN '待付款' WHEN '2' THEN '已完成' WHEN '3' THEN '已拒绝' END) AS Stname,dc.Status")->limit($page->firstRow.','.$page->listRows)->order('CreateDate desc')->select();
		$pagedata['lists']=$lists;
		$pagedata['page']=$page->show();
		$pagedata['Param']=$Param;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 审核操作
	 */
	public function setpscheck(){
		if (IS_POST) {
			$type=$_POST['type'];
			$id=$_POST['id'];
			$info=M()->table('RS_DistributionCashDetail')->where("stoken='%s' and id='%s'",array($this->stoken,$id))->find();
			if ($type=='pass') {
				//通过审核
				M()->startTrans();
				//改状态
				$update=array();
				$update['Status']='1';
				$update['ExecId']=session('userinfo')['ID'];
				$update['ExecName']=session('userinfo')['TrueName'];
				$update['ExecDate']=date('Y-m-d H:i:s',time());
				$dcres=M()->table('RS_DistributionCashDetail')->where("stoken='%s' and id='%s'",array($this->stoken,$id))->setField($update);
				$dores=M()->table('RS_DistributionForOrder')->where("OpenId='{$info['OpenId']}' and OrderId in ('".str_replace(',', "','", $info['OrderList'])."')")->setField('IsCuted','2');
				if ($dcres && $dores) {
					M()->commit();
					$msg['status']='success';
				}else{
					M()->rollback();
					$msg['status']='error';
					$msg['info']='处理失败';
					$this->LOGS('配送员提现申请处理失败--->>>dcres='.$dcres.'---dores='.$dores);
				}
			}elseif ($type=='refund') {
				//拒绝审核
				M()->startTrans();
				$update=array();
				$update['Status']='3';
				$update['ExecId']=session('userinfo')['ID'];
				$update['ExecName']=session('userinfo')['TrueName'];
				$update['ExecDate']=date('Y-m-d H:i:s',time());
				$dcres=M()->table('RS_DistributionCashDetail')->where("stoken='%s' and id='%s'",array($this->stoken,$id))->setField($update);
				$dores=M()->table('RS_DistributionForOrder')->where("OpenId='{$info['OpenId']}' and OrderId in ('".str_replace(',', "','", $info['OrderList'])."')")->setField('IsCuted','0');
				if ($dcres && $dores) {
					M()->commit();
					$msg['status']='success';
				}else{
					M()->rollback();
					$msg['status']='error';
					$msg['info']='处理失败';
					$this->LOGS('配送员提现申请处理失败--->>>dcres='.$dcres.'---dores='.$dores);
				}
			}
			echo json_encode($msg);
		}
	}



}
