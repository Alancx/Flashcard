<?php
namespace Home\Controller;
header('content-type:text/html;charset=utf-8');
class GroupBuyController extends BaseController {
	public $token;
	public $stoken;
	public function _initialize(){
		parent::_initialize();
		$this->token=$this->webParam['token'];
		// $this->stoken=$this->webParam['stoken']?$this->webParam['stoken']:'0';
		$this->stoken='0';  //缓存问题，暂时只查平台
	}

	/**
	 * 团购商品列表
	 */
	public function Group(){
		if (IS_POST) {

		}else{
			$datalist=$this->BM()->query("SELECT p.ProName,p.ProLogoImg,gb.GroupId,(SELECT min(Price) FROM RS_GroupBuyList WHERE GroupId=gb.GroupId) as sprice FROM RS_GroupBuy gb LEFT JOIN RS_Product p ON gb.ProId=p.ProId WHERE gb.StartDate<GETDATE() AND gb.EndDate>GETDATE() AND gb.token='{$this->token}' and gb.stoken='{$this->stoken}' and gb.IsDelete='0' and gb.ProductNum >= gb.ProductSnum");
			// var_dump($this->BM()->getlastsql());exit();
			// $this->LOGS($this->BM()->getlastsql());
			if ($datalist && count($datalist)>0) {
				$pagedata['datalist']=$datalist;
				$this->assign($pagedata);
				$this->display();
			}else{
				$this->error('暂无团购商品');
			}
		}
	}

	/**
	 * 团购页
	 */
	public function GroupInfo(){
		if (IS_POST) {

		}else{
			$GroupId=$_GET['GroupId'];
			$GroupInfo=$this->BM()->query("SELECT gbl.PeopleNum,gbl.GroupListId,gbl.Price,gbl.BuyNum,gb.ProId,CONVERT(varchar(20),gb.EndDate,120) as EndDate,gb.ProId,gb.ProductNum,gb.ProductSnum,gb.IsFeright FROM RS_GroupBuyList gbl LEFT JOIN RS_GroupBuy gb ON gbl.GroupId=gb.GroupId WHERE gb.GroupId='{$GroupId}' and gb.IsDelete='0'");//团基本信息
			// var_dump($this->BM()->getlastsql());exit();
			if (!$GroupInfo) {
				$this->error('团购不存在');exit();
			}
			if ($GroupInfo[0]['ProductNum']<=$GroupInfo[0]['ProductSnum']) {
				$this->error('感谢参与，该商品已售罄！');exit();
			}
			$GroupNum=$this->BM()->query("SELECT COUNT(gbyl.ID) as num FROM RS_GroupBuyerList gbyl LEFT JOIN RS_GroupBuyer gby ON gbyl.GroupBuyerId=gby.GroupBuyerId WHERE gby.GroupId='{$GroupId}'")[0]['num'];
			$Groups=$this->BM()->query("SELECT TOP 3 m.HeadImgUrl,m.MemberName,gbl.PeopleNum,(CASE WHEN gby.LeaderId='{$this->webParam['openid']}' THEN '1' ELSE '0' END) AS IsMe,gbl.PeopleNum-gby.PeopleNum as DifPeople,gby.GroupBuyerId FROM RS_GroupBuyer gby LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId LEFT JOIN RS_Member m ON gby.LeaderId=m.OpenId WHERE gby.GroupId='{$GroupId}' and gby.Status='0' ORDER BY gby.CreateDate desc");
			$Ginfo=array();
			$tmpp=array();
			foreach ($GroupInfo as $key => $value) {
				$tmpp[]=$value['Price'];
				$gsinfo=array();
				$Ginfo['ProId']=$value['ProId'];
				$Ginfo['EndDate']=$value['EndDate'];
				$Ginfo['ProductNum']=$value['ProductNum'];
				$Ginfo['ProductSnum']=$value['ProductSnum'];
				$Ginfo['IsFeright']=$value['IsFeright'];
				$gsinfo['PeopleNum']=$value['PeopleNum'];
				$gsinfo['GroupListId']=$value['GroupListId'];
				$gsinfo['BuyNum']=$value['BuyNum'];
				$gsinfo['Price']=$value['Price'];
				$Ginfo['RuleList'][]=$gsinfo;
			}
			$Ginfo['price']=number_format(min($tmpp),2);
			$Ginfo['bprice']=number_format(max($tmpp),2);
			$file=$this->webParam['realpath'].'/HTML/'.$Ginfo['ProId'].'.json';
			$proinfo=json_decode(file_get_contents($file),true);
			$pagedata['Ginfo']=$Ginfo;
			$pagedata['Proinfo']=$proinfo;
			$pagedata['time']=intval(strtotime($Ginfo['EndDate']))-intval(time());
			// $pagedata['time']=4;
			// var_dump($proinfo);exit();
			//查评价信息
			// $plists=$this->BM()->table('RS_RS_ProductEvaluation')->where("ProId='%s' IsDelete='0'",$Ginfo['ProId'])->select();
			$plists=$this->BM()->query("SELECT pe.Content,m.MemberName,m.HeadImgUrl FROM RS_RS_ProductEvaluation pe LEFT JOIN RS_Member m ON pe.MemberId=m.MemberId WHERE pe.ProId='{$Ginfo['ProId']}' and pe.IsDelete='0'");

			$pagedata['plist']=$plist;

			$pagedata['Gnum']=$GroupNum;
			$pagedata['Groups']=$Groups;
			$myinfo=$this->BM()->table('RS_GroupBuyer')->where("LeaderId='%s' and GroupId='%s'",array($this->webParam['openid'],$GroupId))->count();
			$pagedata['myinfo']=$myinfo;
			$pagedata['gid']=$GroupId;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 *
	 */
	public function gofororder(){
		// echo "<pre>";
		// var_dump($this->webParam);exit();
		$glid=$_GET['glid'];
		$count=$_GET['count'];
		$type=$_GET['type'];
		$gyid=$_GET['gyid'];
		$info=$this->BM()->query("SELECT p.ProName,p.ProLogoImg,gbl.Price,pl.ProSpec1,gb.ProId,gb.IsFeright FROM RS_GroupBuyList gbl LEFT JOIN RS_GroupBuy gb ON gbl.GroupId=gb.GroupId LEFT JOIN RS_Product p ON gb.ProId=p.ProId LEFT JOIN RS_ProductList pl ON gb.ProIdCard=pl.ProIdCard WHERE gbl.GroupListId='{$glid}'")[0];
		// echo "<pre>";
		// var_dump($info);exit();
		$info['count']=$count;
		$info['Money']=floatval($info['Price'])*intval($info['count']);
		if ($info['IsFeright']=='1') {
			// $
		}

		//拿默认收货地址
		$addr=$this->BM()->table('RS_OrderRecevingAddress')->where("MemberId='%s'",$this->webParam['uid'])->order('IsDefault desc')->select();
		// echo "<pre>";
		// var_dump($this->webParam);exit();
		// var_dump($this->BM()->getlastsql());
		// var_dump($addr);exit();
		$pagedata['addr']=$addr?$addr:0;
		$pagedata['info']=$info;
		$pagedata['type']=$type;
		$pagedata['glid']=$glid;
		$pagedata['gyid']=$gyid;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 收获地址处理
	 */
	public function saveaddr(){
		$data=$_POST;
		$id=$data['rid'];
		$type=$data['type'];
		unset($data['rid']);
		unset($data['type']);
		if ($type=='new') {
			$data['token']=$this->webParam['token'];
			$data['MemberId']=$this->webParam['uid'];
			if ($rid=$this->BM()->table('RS_OrderRecevingAddress')->add($data)) {
				$msg['status']='success';
				$msg['rid']=$rid;
			}else{
				$msg['status']='error';
				$msg['info']='添加失败';
				$this->LOGS($this->BM()->getlastsql());
			}
		}else{
			if ($this->BM()->table('RS_OrderRecevingAddress')->where("ReceivingId=%d",$id)->save($data)) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='保存失败';
			}
		}
		echo json_encode($msg);
	}

	/**
	 * 获取收获地址数据
	 */
	public function getaddrdata(){
		$data=$this->BM()->table('RS_OrderRecevingAddress')->where("MemberId='%s'",$this->webParam['uid'])->field("Name,Province,Phone,City,Area,Address,IsDefault,ReceivingId")->select();
		if ($data && count($data)>0) {
			$msg['status']='success';
			$msg['data']=$data;
		}else{
			$msg['status']='error';
			$msg['info']='查询失败';
		}
		echo json_encode($msg);
	}

	/**
	 * 删除收货地址
	 */
	public function deleteaddr(){
		$rid=$_POST['rid'];
		if ($this->BM()->table('RS_OrderRecevingAddress')->where("ReceivingId=%d",$rid)->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
			$msg['info']='删除失败';
		}
		echo json_encode($msg);
	}

	/**
	 * 设置默认
	 */
	public function setmr(){
		$rid=$_POST['rid'];
		$this->BM()->startTrans();
		$ro=$this->BM()->table('RS_OrderRecevingAddress')->where("MemberId='%s'",$this->webParam['uid'])->setField("IsDefault",0);
		$rt=$this->BM()->table('RS_OrderRecevingAddress')->where("ReceivingId=%d",$rid)->setField('IsDefault',1);
		if ($ro && $rt) {
			$this->BM()->commit();
			$msg['status']='success';
		}else{
			$this->BM()->rollback();
			$msg['status']='error';
		}
		echo json_encode($msg);
	}

	/**
	 * 临时单处理
	 */
	public function tmpfororder(){
		$type=$_POST['type'];
		$count=$_POST['count'];
		$glid=$_POST['glid'];
		$rid=$_POST['rid'];
		$gyid=$_POST['gyid'];
		$addr=$this->BM()->table('RS_OrderRecevingAddress')->where("ReceivingId=%d",$rid)->find();
		$Ginfo=$this->BM()->table('RS_GroupBuy gb')->join("LEFT JOIN RS_GroupBuyList gbl ON gb.GroupId=gbl.GroupId")->where("gbl.GroupListId='%s'",$glid)->field("gb.GroupId,gbl.Price,gb.IsFeright,gbl.PeopleNum")->find();
		if ($type=='open') {
			$GroupBeied=$this->BM()->table('RS_GroupBuyer')->where("GroupId='%s'",$Ginfo['GroupId'])->getField("LeaderId",true);
			if (in_array($this->webParam['openid'], $GroupBeied)) {
				$msg['status']='error';
				$msg['info']='请勿重复开团';
			}else{
				$GroupBuyerId=uniqid('GBYID');
				//开团处理
				// $Buyer=array();
				// $BuyerList=array();
				// $Buyer['GroupId']=$Ginfo['GroupId'];
				// $Buyer['GroupListId']=$glid;
				// $Buyer['PeopleNum']=1;
				// $Buyer['LeaderId']=$this->webParam['openid'];
				// $Buyer['GroupBuyerId']=$GroupBuyerId;
				// $Buyer['token']=$this->webParam['token'];
				// $Buyer['stoken']=$this->webParam['stoken']?$this->webParam['stoken']:'0';
				// $Buyer['Num']=$count;
				$BuyerList['GroupBuyerId']=$GroupBuyerId;
				$BuyerList['OpenId']=$this->webParam['openid'];
				$BuyerList['Num']=$count;
				$BuyerList['Price']=$Ginfo['Price'];
				$BuyerList['Money']=floatval($Ginfo['Price'])*intval($count);
				$BuyerList['token']=$this->webParam['token'];
				$BuyerList['stoken']=$this->webParam['stoken']?$this->webParam['stoken']:'0';
				$BuyerList['Province']=$addr['Province'];
				$BuyerList['City']=$addr['City'];
				$BuyerList['Area']=$addr['Area'];
				$BuyerList['Address']=$addr['Address'];
				$BuyerList['Phone']=$addr['Phone'];
				$BuyerList['RecevingName']=$addr['Name'];
				$BuyerList['Freight']=$Ginfo['IsFeright']=='0'?'0':'1.1';//运费未计算
				$BuyerList['GroupListId']=$glid;
				$BuyerList['GroupId']=$Ginfo['GroupId'];
				$bys=$bus=true;
				$this->BM()->startTrans();
				// $bys=$this->BM()->table('RS_GroupBuyer')->add($Buyer);
				$bls=$this->BM()->table('RS_GroupBuyerListTemp')->add($BuyerList);
				// $bus=$this->BM()->table('RS_GroupBuy')->where("GroupId='%s'",$Ginfo['GroupId'])->setInc('ProductSnum',$count);
				if ($bys && $bls && $bus) {
					$this->BM()->commit();
					$msg['status']='success';
					$msg['ID']=$bls;
				}else{
					$this->BM()->rollback();
					$msg['status']='error';
					$msg['info']='处理失败';
					$this->LOGS("预处理__开团处理失败--->>>bys=$bys __ bls=$bls __ bus=$bus--->>>".$this->BM()->getlastsql());
				}
			}
		}elseif ($type=='join') {
			$GroupBuyer=$this->BM()->table('RS_GroupBuyer')->where("GroupBuyerId='%s'",$gyid)->find();
			$Gbylist=$this->BM()->table('RS_GroupBuyerList')->where("GroupBuyerId='%s'",$gyid)->getField('OpenId',true);
			if (in_array($this->webParam['openid'], $Gbylist)) {
				$msg['status']='error';
				$msg['info']='请勿重复参团';
			}else{
			// 参团处理
				$BuyerList=array();
				$BuyerList['GroupBuyerId']=$gyid;
				$BuyerList['OpenId']=$this->webParam['openid'];
				$BuyerList['Num']=$count;
				$BuyerList['Price']=$Ginfo['Price'];
				$BuyerList['Money']=floatval($Ginfo['Price'])*intval($count);
				$BuyerList['token']=$this->webParam['token'];
				$BuyerList['stoken']=$this->webParam['stoken']?$this->webParam['stoken']:'0';
				$BuyerList['Province']=$addr['Province'];
				$BuyerList['City']=$addr['City'];
				$BuyerList['Area']=$addr['Area'];
				$BuyerList['Address']=$addr['Address'];
				$BuyerList['Phone']=$addr['Phone'];
				$BuyerList['RecevingName']=$addr['Name'];
				$BuyerList['Freight']=$Ginfo['IsFeright']=='0'?'0':'1.1';//运费未计算
				$BuyerList['GroupListId']=$glid;
				$BuyerList['GroupId']=$Ginfo['GroupId'];
				// $Buyer=array();
				// $Buyer['Num']=intval($GroupBuyer['Num'])+intval($count);
				// $Buyer['PeopleNum']=intval($GroupBuyer['PeopleNum'])+1;
				// if ($Buyer['PeopleNum']==$Ginfo['PeopleNum']) {
				// 	//人数凑够团购完成
				// 	$Buyer['Status']='1';
				// }
				$bys=$bus=true;
				$this->BM()->startTrans();
				// $bys=$this->BM()->table('RS_GroupBuyer')->where("GroupBuyerId='%s'",$gyid)->setField($Buyer);
				$bls=$this->BM()->table('RS_GroupBuyerListTemp')->add($BuyerList);
				// $bus=$this->BM()->table('RS_GroupBuy')->where("GroupId='%s'",$Ginfo['GroupId'])->setInc("ProductSnum",$count);
				if ($bys && $bls && $bus) {
					$this->BM()->commit();
					$msg['status']='success';
					$msg['ID']=$bls;
				}else{
					$this->BM()->rollback();
					$msg['status']='error';
					$msg['info']='处理失败';
					$this->LOGS("预处理_参团处理失败--->>>bys=$bys __ bls=$bls __ bus=$bus--->>>".$this->BM()->getlastsql());
				}
			}

		}
		echo json_encode($msg);
	}

	/**
	 * 参团开团处理
	 */
	public function saveorder($data){
		$type=$data['ext2'];
		$info=$this->BM()->table('RS_GroupBuyerListTemp')->where("ID=%d",$data['ext1'])->find();
		if ($info) {
			$count=$info['Num'];
			$glid=$info['GroupListId'];
			$gyid=$info['GroupBuyerId'];
			$GroupId=$info['GroupId'];
			$Ginfo=$this->BM()->table('RS_GroupBuyList')->where("GroupListId='%s'",$glid)->find();
			if ($type=='open') {
				$GroupBeied=$this->BM()->table('RS_GroupBuyer')->where("GroupId='%s'",$GroupId)->getField("LeaderId",true);
				if (in_array($this->webParam['openid'], $GroupBeied)) {
					$msg['status']='error';
					$msg['info']='请勿重复开团';
				}else{
					// $GroupBuyerId=uniqid('GBYID');
					//开团处理
					$Buyer=array();
					$BuyerList=array();
					$Buyer['GroupId']=$GroupId;
					$Buyer['GroupListId']=$glid;
					$Buyer['PeopleNum']=1;
					$Buyer['LeaderId']=$info['OpenId'];
					$Buyer['GroupBuyerId']=$gyid;
					$Buyer['token']=$this->webParam['token'];
					$Buyer['stoken']=$this->webParam['stoken']?$this->webParam['stoken']:'0';
					$Buyer['Num']=$count;
					$BuyerList['GroupBuyerId']=$gyid;
					$BuyerList['OpenId']=$info['OpenId'];
					$BuyerList['Num']=$count;
					$BuyerList['Price']=$info['Price'];
					$BuyerList['Money']=$info['Money'];
					$BuyerList['token']=$this->webParam['token'];
					$BuyerList['stoken']=$this->webParam['stoken']?$this->webParam['stoken']:'0';
					$BuyerList['Province']=$info['Province'];
					$BuyerList['City']=$info['City'];
					$BuyerList['Area']=$info['Area'];
					$BuyerList['Address']=$info['Address'];
					$BuyerList['Phone']=$info['Phone'];
					$BuyerList['RecevingName']=$info['RecevingName'];
					$BuyerList['Freight']=$info['Freight'];
					$BuyerList['OrderNo']=$data['orderNo'];
					$BuyerList['txOrderNo']=$data['txOrderNo'];
					$this->BM()->startTrans();
					$bys=$this->BM()->table('RS_GroupBuyer')->add($Buyer);
					$bls=$this->BM()->table('RS_GroupBuyerList')->add($BuyerList);
					$bus=$this->BM()->table('RS_GroupBuy')->where("GroupId='%s'",$GroupId)->setInc('ProductSnum',$count);
					$xxx=$this->BM()->table('RS_GroupBuyerListTemp')->where("ID=%d",$data['ext1'])->delete();
					if ($bys && $bls && $bus) {
						$this->BM()->commit();
						$msg['status']='success';
					}else{
						$this->BM()->rollback();
						$msg['status']='error';
						$msg['info']='处理失败';
						$this->LOGS("开团处理失败--->>>bys=$bys __ bls=$bls __ bus=$bus--->>>".$this->BM()->getlastsql());
					}
				}
			}elseif ($type=='join') {
				$GroupBuyer=$this->BM()->table('RS_GroupBuyer')->where("GroupBuyerId='%s'",$gyid)->find();
				$Gbylist=$this->BM()->table('RS_GroupBuyerList')->where("GroupBuyerId='%s'",$gyid)->getField('OpenId',true);
				if (in_array($this->webParam['openid'], $Gbylist)) {
					$msg['status']='error';
					$msg['info']='请勿重复参团';
				}else{
				// 参团处理
					$BuyerList=array();
					$BuyerList['GroupBuyerId']=$gyid;
					$BuyerList['OpenId']=$info['OpenId'];
					$BuyerList['Num']=$count;
					$BuyerList['Price']=$info['Price'];
					$BuyerList['Money']=$info['Money'];
					$BuyerList['token']=$this->webParam['token'];
					$BuyerList['stoken']=$this->webParam['stoken']?$this->webParam['stoken']:'0';
					$BuyerList['Province']=$info['Province'];
					$BuyerList['City']=$info['City'];
					$BuyerList['Area']=$info['Area'];
					$BuyerList['Address']=$info['Address'];
					$BuyerList['Phone']=$info['Phone'];
					$BuyerList['RecevingName']=$info['RecevingName'];
					$BuyerList['Freight']=$info['Freight'];
					$BuyerList['OrderNo']=$data['orderNo'];
					$BuyerList['txOrderNo']=$data['txOrderNo'];
					$Buyer=array();
					$Buyer['Num']=intval($GroupBuyer['Num'])+intval($count);
					$Buyer['PeopleNum']=intval($GroupBuyer['PeopleNum'])+1;
					$result=true;
					$this->BM()->startTrans();
					$bls=$this->BM()->table('RS_GroupBuyerList')->add($BuyerList);
					if ($Buyer['PeopleNum']==$Ginfo['PeopleNum']) {
						//人数凑够团购完成
						$Buyer['Status']='1';
						//处理订单
						$infos=$this->BM()->query("SELECT gbyl.OpenId,gbyl.OrderNo,gbyl.Num,gbyl.Price,gbyl.Money,gbyl.Province,gbyl.City,gbyl.Area,gbyl.Address,gbyl.Phone,gbyl.RecevingName,gbyl.Freight,gbyl.txOrderNo,gbyl.token,gbyl.stoken,gbyl.GroupBuyerId,gb.ProId,gb.ProIdCard,p.ProName,p.ProTitle,p.ProLogoImg,pl.ProSpec1 as Spec,m.MemberId FROM RS_GroupBuyerList gbyl LEFT JOIN RS_GroupBuyer gby ON gbyl.GroupBuyerId=gby.GroupBuyerId LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId LEFT JOIN RS_Product p ON gb.ProId=p.ProId LEFT JOIN RS_ProductList pl ON gb.ProIdCard=pl.ProIdCard LEFT JOIN RS_Member m ON gbyl.OpenId=m.OpenId WHERE gbyl.GroupBuyerId='{$gyid}'");
						// $this->LOGS(json_encode($infos,JSON_UNESCAPED_UNICODE));
						foreach ($infos as $ins) {
							$Order=array();
							$Order['OrderId']=$ins['OrderNo'];
							$Order['MemberId']=$ins['MemberId'];
							$Order['OpenId']=$ins['OpenId'];
							$Order['RecevingName']=$ins['RecevingName'];
							$Order['RecevingProvince']=$ins['Province'];
							$Order['RecevingCity']=$ins['City'];
							$Order['RecevingArea']=$ins['Area'];
							$Order['RecevingAddress']=$ins['Address'];
							$Order['RecevingPost']="KD";
							$Order['RecevingPhone']=$ins['Phone'];
							$Order['Count']=$ins['Num'];
							$Order['Price']=floatval($ins['Money'])+floatval($ins['Freight']);
							$Order['Freight']=$ins['Freight'];
							$Order['PayName']='T';
							$Order['Status']=2;
							$Order['TransactionId']=$ins['txOrderNo'];
							$Order['token']=$ins['token'];
							$Order['stoken']=$ins['stoken'];
							$Order['txOrderNo']=$ins['txOrderNo'];
							$Order['IsGroup']='1';
							$Order['GroupBuyerId']=$ins['GroupBuyerId'];
							$OrderList=array();
							$OrderList['OrderId']=$ins['OrderNo'];
							$OrderList['ProId']=$ins['ProId'];
							$OrderList['ProIdCard']=$ins['ProIdCard'];
							$OrderList['Price']=$ins['Price'];
							$OrderList['Count']=$ins['Num'];
							$OrderList['Money']=$ins['Money'];
							$OrderList['Spec']=$ins['Spec'];
							$OrderList['ProName']=$ins['ProName'];
							$OrderList['ProLogoImg']=$ins['ProLogoImg'];
							$OrderList['ProTitle']=$ins['ProTitle'];
							$ors=$this->BM()->table('RS_Order')->add($Order);
							$ols=$this->BM()->table('RS_OrderList')->add($OrderList);
							if ($ors && $ols) {
								continue;
							}else{
								$this->LOGS("团订单处理失败ors=$ors ... ols=$ols".$this->BM()->getlastsql());
								$result=false;
								break;
							}
						}
					}
					$bys=$this->BM()->table('RS_GroupBuyer')->where("GroupBuyerId='%s'",$gyid)->setField($Buyer);
					$bus=$this->BM()->table('RS_GroupBuy')->where("GroupId='%s'",$GroupId)->setInc("ProductSnum",$count);
					$xxx=$this->BM()->table('RS_GroupBuyerListTemp')->where("ID=%d",$data['ext1'])->delete();
					if ($bys && $bls && $bus && $result) {
						$this->BM()->commit();
						$msg['status']='success';
					}else{
						$this->BM()->rollback();
						$msg['status']='error';
						$msg['info']='处理失败';
						$this->LOGS("参团处理失败--->>>bys=$bys __ bls=$bls __ bus=$bus __ result=$result--->>>".$this->BM()->getlastsql());
					}
				}

			}
		}else{
			$msg['status']='error';
			$msg['info']='团购单信息不存在';
		}
		return $msg;
	}


	/**
	 * 我的团
	 */
	public function MyGroup(){
		if (IS_POST) {

		}else{

			$data=$this->BM()->query("SELECT p.ProName,p.ProLogoImg,gbyl.Price,CONVERT(varchar(20),gbyl.CreateDate,120) as KTDate,gby.Status,gby.PeopleNum,gbl.PeopleNum as TpeopleNum,(CASE WHEN gbyl.OpenId=gby.LeaderId THEN '1' ELSE '0' END) AS IsLeader ,(CASE gby.Status WHEN '0' THEN '进行中' WHEN '1' THEN '已完成' WHEN '2' THEN '已过期' WHEN '3' THEN '已退款' END) AS Stname,(CASE WHEN gbyl.OpenId=gby.LeaderId THEN '1' ELSE '0' END ) AS IsMe,(CASE WHEN gb.EndDate<GETDATE() THEN '1' ELSE '0' END) AS IsOver,CONVERT(varchar(20),gb.EndDate,120) as EndDate,gb.GroupId FROM RS_GroupBuyerList gbyl LEFT JOIN RS_GroupBuyer gby ON gbyl.GroupBuyerId=gby.GroupBuyerId LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId LEFT JOIN RS_Product p ON gb.ProId=p.ProId WHERE gbyl.OpenId='{$this->webParam['openid']}' ORDER BY gby.CreateDate desc");
			$myGroup=array();
			$inGroup=array();
			foreach ($data as $da) {
				if ($da['IsMe']=='1') {
					$myGroup[]=$da;
				}else{
					$inGroup[]=$da;
				}
			}
			$pagedata['myGroup']=$myGroup;
			$pagedata['inGroup']=$inGroup;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 团列表
	 */
	public function GroupList(){
		if (IS_POST) {

		}else{
			$GroupId=$_GET['gid'];
			$data=$this->BM()->query("SELECT m.HeadImgUrl,m.MemberName,gbl.PeopleNum,(CASE WHEN gby.LeaderId='{$this->webParam['openid']}' THEN '1' ELSE '0' END) AS IsMe,gbl.PeopleNum-gby.PeopleNum as DifPeople,gby.GroupBuyerId FROM RS_GroupBuyer gby LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId LEFT JOIN RS_Member m ON gby.LeaderId=m.OpenId WHERE gby.GroupId='{$GroupId}' and gby.Status='0' ORDER BY gby.CreateDate desc");
			$pagedata['data']=$data;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 参团
	 */
	public function InGroup(){
		$GBYID=$_GET['gbyid'];
		$Ginfo=$this->BM()->query("SELECT p.ProName,p.ProLogoImg,p.PriceRange,gb.ProductNum-gb.ProductSnum as OffNum,CONVERT(varchar(20),gb.EndDate,120) as EndDate,gbl.PeopleNum,gbl.Price,gbl.BuyNum,gbl.PeopleNum,gbl.PeopleNum-gby.PeopleNum as DifPeople,gby.LeaderId,gby.GroupListId FROM RS_GroupBuyer gby LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId LEFT JOIN RS_Product p ON gb.ProId=p.ProId WHERE gby.GroupBuyerId='{$GBYID}'")[0];
		// var_dump($this->BM()->getlastsql());exit();
		$Gsinfo=$this->BM()->query("SELECT CONVERT(varchar(20),gbly.CreateDate,120) as CreateDate,m.MemberName,m.HeadImgUrl,(CASE WHEN gbly.OpenId=gby.LeaderId THEN '1' ELSE '0' END) AS IsLeader,gbly.OpenId FROM RS_GroupBuyerList gbly LEFT JOIN RS_GroupBuyer gby ON gbly.GroupBuyerId=gby.GroupBuyerId LEFT JOIN RS_Member m ON gbly.OpenId=m.OpenId WHERE gbly.GroupBuyerId='{$GBYID}'");
		$PeopleBox=array(); //
		foreach ($Gsinfo as $gs) {
			$PeopleBox[]=$gs['OpenId'];
		}
		$pagedata['time']=intval(strtotime($Ginfo['EndDate']))-intval(time());
		if ($Ginfo['LeaderId']==$this->webParam['openid']  || in_array($this->webParam['openid'], $PeopleBox)) {
			$pagedata['isme']='1';
		}else{
			$pagedata['isme']='0';
		}
		if ($Ginfo['OffNum']<=0) {
			$pagedata['isme']='1';
		}
		$Ginfo['list']=$Gsinfo;
		$pagedata['Ginfo']=$Ginfo;
		$pagedata['gyid']=$GBYID;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 支付反馈
	 */
	public function txNotify(){
		$this->LOGS('团购支付回执信息--->>>'.json_encode($_POST,JSON_UNESCAPED_UNICODE));
		$data=$_POST;
		if ($data['dealCode']=='10000') {
			//处理
			$res=$this->saveorder($data);
			if ($res['status']=='success') {
				//搞定
				$Param=array();
				$Param['merchantNo']=C('TX_merchant');
				$Param['dealResult']='SUCCESS';
		        $key=C('TX_key');
		        $signStr=A('Public')->MakeSign($data,$key);
		        $Param['sign']=$signStr;
		        $res=$this->HTTPPOST($apiURL,$data);
		        $this->LOGS('团购支付回执处理成功');
			}else{
				$this->LOGS('团购支付回执处理失败--->>>'.json_encode($res));
			}
		}
	}

	/**
	 * 运费计算
	 */
	public function mathOfferight(){
		$weight=$_POST['weight']; //重量
		$area=$_POST['area'];  //省份
		

	}
}
