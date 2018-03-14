<?php
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class CashierController extends CommonController{
	public function _initialize(){
		parent::_initialize();
	}

	public function Index()
	{
		define('FPAGE','SHOUYIN');
		$this->display();
	}

	public function Cashier()
	{
		//$url='http://'.$_SERVER['HTTP_HOST'];
				define('FPAGE','SHOUYIN');

		//ob_clean();
		//vendor('PHPQR.phpqrcode');
		$url='http://'.$_SERVER['HTTP_HOST'];
		//.U('Home/Account/QrCodeHandle',array('tp'=>'3','gid'=>$_GET['pid'],'sid'=>$_GET['sid']));
		//$level="L";
		//$size=4;
		//$qrcodeStr="<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";
		//\QRcode::png($url,false,$level,$size,'2');
		//$this->assign('qrcodeStr','\''.$qrcodeStr.'\'');
		$this->assign('qrcodeUrl',$url);
		$this->assign('orderId',"E".date("YmdHis",time()).rand(1000,9999));
		$this->display();
	}

	public function getGoodsPrice()
	{

		$data=$_POST;

		$tempGid=parse_url($data['pid']);
		$tempGid=$tempGid['path'];
		$tempGid=explode('/', $tempGid);

// array(3) {
//   ["scheme"]=>
//   string(4) "http"
//   ["host"]=>
//   string(12) "wx.58ate.com"
//   ["path"]=>
//   string(64) "/Home/Account/QrCodeHandle/tp/3/gid/pro0516501471_39/sid/11.html"
// }

//http://wx.58ate.com/Home/Account/QrCodeHandle/tp/3/gid/pro0515775329_38/sid/11.html


		$zpSign=0;

		if (count($tempGid)==1)
		{
			$data['pid']=$tempGid[0];
		}
		else
		{
			$tempGidIndex=array_search('gid',$tempGid);
			$data['pid']=$tempGid[$tempGidIndex+1];
		}

		$tempZPIndex=array_search('zp',$tempGid);

		$gInfoCondition="";
		if ($tempZPIndex)
		{
			$gInfoCondition="(pl.ProIdCard='".$data['pid']."' OR pl.ProIdInputCard='".$data['pid']."') AND pl.Iszp='1'";
			$zpSign=str_replace('.html','',$tempGid[$tempZPIndex+1]);
		}
		else
		{
			$gInfoCondition="(pl.ProIdCard='".$data['pid']."' OR pl.ProIdInputCard='".$data['pid']."')";
			$zpSign=0;
		}

		$gInfo=M()->table(C('DB_BASE')['DB_PREFIX']."product p")->join(C('DB_BASE')['DB_PREFIX']."productlist pl ON p.ProId=pl.ProId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."productonsale pos ON pos.ProId=pl.ProIdCard AND pos.Ison=1 AND (GETDATE() BETWEEN pos.stime AND pos.etime)","LEFT")->where($gInfoCondition)->field("pl.ProIdCard AS pid,p.ProName AS pname,pl.Price AS price,pos.sprice AS sprice,p.IsUseScore,p.Score")->find();
		if ($gInfo)
		{

			if ($zpSign==1) {
				$gInfo['sprice']=0;
				$gInfo['price']=0;
			}
			else
			{
				if (!$gInfo['sprice']) {
					$gInfo['sprice']=0;
				}
				else
				{
					$gInfo['sprice']=$gInfo['price']-$gInfo['sprice'];
					$gInfo['price']=$gInfo['price']-$gInfo['sprice'];
				}
			}
			if ($gInfo['IsUseScore']==1 && $gInfo['Score']>0) {
				$gInfo['score']=$gInfo['Score'];
				$gInfo['sprice']=0;
				$gInfo['price']=0;
			}else{
				$gInfo['score']=0;
			}
			$gInfo['num']=1;
		// file_put_contents('2.txt', json_encode($gInfo));

			$gInfoArray=cookie("sy_GoodsList");
			$gInfoArray=json_decode($gInfoArray,true);

// array(

// 	'编号'=>array(
// 		'oid'=>'编号',
// 		'nums'=>'1',
// 		'money'=>'9',
// 		'discount'=>'1',

// 		'goods'=>array(
//			'pid'=>array(
// 				'pid'=>'',
// 				'pname'=>'',
// 				'price'=>'',
// 				'sprice'=>'',
//				'num'=>'0',
//				'money'=>'10',
//				),
// 			),
// 		),

// 		'zpgoods'=>array(
//			'pid'=>array(
// 				'pid'=>'',
// 				'pname'=>'',
// 				'price'=>'',
// 				'sprice'=>'',
//				'num'=>'0',
//				'money'=>'10',
//				),
// 			),
// 		),

// 	);
			//$gInfoArray['Goods']=array('"'.$gInfo['pid'].'"'=>$gInfo);
			$nowOrderArray=$gInfoArray[$data['oid']];
			$nowOrderArray['oid']=$data['oid'];
			$nowOrderArray['nums']=$nowOrderArray['nums']+$gInfo['num'];
			$nowOrderArray['money']=$nowOrderArray['money']+$gInfo['price'];
			$nowOrderArray['allscore']=$nowOrderArray['allscore']+$gInfo['score'];
			$nowOrderArray['discount']=$nowOrderArray['discount']+($gInfo['sprice']*$gInfo['num']);
			$nowOrderArray['memberid']=$data['memberid'];
			if ($nowOrderArray['type']) {
				$nowOrderArray['type']=$nowOrderArray['type'];
			}else{
				$nowOrderArray['type']='general';
			}


			$gpid='';
			if ($zpSign==1) {
				$nowGoodsArray=$nowOrderArray['zpgoods'][$gInfo['pid']];
				$nowGoodsArray['pid']=$gInfo['pid'];
				$nowGoodsArray['pname']=$gInfo['pname'];
				$nowGoodsArray['price']=$gInfo['price'];
				$nowGoodsArray['sprice']=$gInfo['sprice'];
				$nowGoodsArray['num']=$nowGoodsArray['num']+$gInfo['num'];
				$nowGoodsArray['money']=$nowGoodsArray['price']*$nowGoodsArray['num'];

				$nowOrderArray['zpgoods'][$gInfo['pid']]=$nowGoodsArray;
				// $gInfoArray[$data['oid']]=$nowOrderArray;

				$gpid='xzp'.$nowGoodsArray['pid'];
			}
			else
			{
				if ($nowOrderArray['type']=='general' && $gInfo['score']==0) {
					$nowOrderArray['type']='justpay';
				}else{
					if ($nowOrderArray['type']=='score' && $gInfo['score']==0) {
						$this->ajaxReturn(array('status'=>'true','data'=>array('ordertype'=>'userscore')));
					}
				}
				$nowGoodsArray=$nowOrderArray['goods'][$gInfo['pid']];
				$nowGoodsArray['pid']=$gInfo['pid'];
				$nowGoodsArray['pname']=$gInfo['pname'];
				$nowGoodsArray['price']=$gInfo['price'];
				$nowGoodsArray['score']=$gInfo['score'];
				$nowGoodsArray['sprice']=$gInfo['sprice'];
				$nowGoodsArray['num']=$nowGoodsArray['num']+$gInfo['num'];
				$nowGoodsArray['money']=$nowGoodsArray['price']*$nowGoodsArray['num'];
				$nowGoodsArray['allscore']=$nowGoodsArray['allscore']+$gInfo['score'];

				$nowOrderArray['goods'][$gInfo['pid']]=$nowGoodsArray;

				$gpid=$nowGoodsArray['pid'];
			}







			$ProIdCards=array();
			foreach ($nowOrderArray['goods'] as $key => $value) {
				$ProIdCards[]=$key;
			}
			// sort($ProIdCards);
			// $newPram=serialize($ProIdCards);
			// // var_dump($newPram);exit();
	 		// $alldiscount=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->order('CreateDate desc')->getField('GroupId,ProIdCards,CreateDate');
			$Groupdiscountinfo=$this->getDiscount($ProIdCards); //遍历所有订单号，取出组合价格并计算出当前订单价格与优惠
			// var_dump($Groupdiscountinfo);exit();
			if ($Groupdiscountinfo) {
				// 计算优惠规则
				$nowOrderArray['type']='groupdiscount';
				$proinfos=$Groupdiscountinfo['goods'];
				$tempDisucount=0;
				$tempMoney=0;
				foreach ($nowOrderArray['goods'] as $key => $value) {
						$nowOrderArray['goods'][$key]['sprice']=$proinfos[$key]['Price'];
						$nowOrderArray['goods'][$key]['money']=$proinfos[$key]['Price']*$nowOrderArray['goods'][$key]['num'];
				}
				$nowOrderArray['discount']=$Groupdiscountinfo['discount'];
				$nowOrderArray['money']=$Groupdiscountinfo['money'];
			}
			$gInfoArray[$data['oid']]=$nowOrderArray;



			$gInfo=array(
				'pid'=>$gpid,
				'pname'=>strlen($nowGoodsArray['pname'])>12?substr($nowGoodsArray['pname'], 0,12).'...':$nowGoodsArray['pname'],
				'price'=>$nowGoodsArray['price'],
				'sprice'=>$nowGoodsArray['sprice'],
				'num'=>$nowGoodsArray['num'],
				'money'=>$nowGoodsArray['money'],
				'allscore'=>$nowGoodsArray['allscore'],
				'zp'=>$zpSign,
				'score'=>$nowGoodsArray['score'],
				'oInfo'=>array(
					'count'=>$nowOrderArray['nums'],
					'money'=>$nowOrderArray['money'],
					'discount'=>$nowOrderArray['discount'],
					'score'=>$nowOrderArray['allscore'],
					),
			);
			// var_dump($nowOrderArray);exit();
			if ($nowOrderArray['allscore']>0) {
				if ($nowOrderArray['type']=='general' || $nowOrderArray['type']=='score') {
					$tempType='score';
					if ($data['memberid']) {
						$memberinfo=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$data['memberid']))->getField('Integral');
						if ($memberinfo>0 && $memberinfo>=$nowOrderArray['allscore']) {
							$gInfoArray[$data['oid']]['type']=$tempType;
							$this->saveCooies('GoodsList',json_encode($gInfoArray),'sy');
						}else{
							$gInfo='';
							$gInfo['canusescore']='noscore';
						}
					}else{
						$gInfo='';
						$gInfo['canusescore']='no';
					}
				}else{
					// var_dump($gInfoArray[$data['oid']]);exit();
					$gInfo='';
					$gInfo['ordertype']='cantscore';
					// $this->saveCooies('GoodsList',json_encode($gInfoArray),'sy');
				}
			}else{
				$this->saveCooies('GoodsList',json_encode($gInfoArray),'sy');
			}
			// $this->saveCooies('GoodsList',json_encode($gInfoArray),'sy');
			if ($nowOrderArray['type']=='groupdiscount') {
				$this->ajaxReturn(array('status'=>'true','info'=>$gInfoArray,'ordertype'=>'discount','orderdata'=>$nowOrderArray,'data'=>$gInfo,'promotion'=>$this->getPromotion($nowOrderArray['money'])),'JSON');
			}else{
				$this->ajaxReturn(array('status'=>'true','info'=>$gInfoArray,'data'=>$gInfo,'promotion'=>$this->getPromotion($nowOrderArray['money'])),'JSON');
			}
		}
		else
		{
			$this->ajaxReturn(array('status'=>'false','info'=>'CantFindGoods','data'=>M()->_sql()),'JSON');
		}
	}

	public function delGoods()
	{
		$data=$_POST;
		$orderInfoArray=cookie("sy_GoodsList");
		$orderInfoArray=json_decode($orderInfoArray,true);

		//$orderInfoArray[$data['oid']]['goods'][$data['gid']]
		$gid='';
		if (substr($data['gid'], 0,3)=='xzp')
		{
			$gid=substr($data['gid'],3);
			$nowGoodsInfo=$orderInfoArray[$data['oid']]['zpgoods'][$gid];
			$orderInfoArray[$data['oid']]['money']=$orderInfoArray[$data['oid']]['money']-$nowGoodsInfo['money'];
			$orderInfoArray[$data['oid']]['nums']=$orderInfoArray[$data['oid']]['nums']-$nowGoodsInfo['num'];
			$orderInfoArray[$data['oid']]['discount']=$orderInfoArray[$data['oid']]['discount']-($nowGoodsInfo['sprice']*$nowGoodsInfo['num']);

			unset($orderInfoArray[$data['oid']]['zpgoods'][$gid]);
		}
		else
		{
			$gid=$data['gid'];
			$nowGoodsInfo=$orderInfoArray[$data['oid']]['goods'][$gid];
			$orderInfoArray[$data['oid']]['money']=$orderInfoArray[$data['oid']]['money']-$nowGoodsInfo['money'];
			$orderInfoArray[$data['oid']]['nums']=$orderInfoArray[$data['oid']]['nums']-$nowGoodsInfo['num'];
			$orderInfoArray[$data['oid']]['discount']=$orderInfoArray[$data['oid']]['discount']-($nowGoodsInfo['sprice']*$nowGoodsInfo['num']);
			$orderInfoArray[$data['oid']]['allscore']=$orderInfoArray[$data['oid']]['allscore']-$nowGoodsInfo['allscore'];

			unset($orderInfoArray[$data['oid']]['goods'][$gid]);
		}

			if ($orderInfoArray[$data['oid']]['type']=='groupdiscount') {
			$nowOrderInfo=$orderInfoArray[$data['oid']];
			$ProIdCards=array();
			foreach ($orderInfoArray[$data['oid']]['goods'] as $key => $value) {
				$ProIdCards[]=$key;
			}
			sort($ProIdCards);
	 		// $alldiscount=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->order('CreateDate desc')->getField('GroupId,ProIdCards,CreateDate');
			$Groupdiscountinfo=$this->getDiscount($ProIdCards); //遍历所有订单号，取出组合价格并计算出当前订单价格与优惠

			// $newPram=serialize($ProIdCards);
			// var_dump($newPram);exit();
			if ($Groupdiscountinfo) {
				// 计算优惠规则
				$nowOrderInfo['type']='groupdiscount';
				$nowOrderInfo['GroupId']=$Groupdiscount['GroupId'];
				$proinfos=$Groupdiscountinfo['goods'];
				$tempDisucount=0;
				$tempMoney=0;
				foreach ($nowOrderInfo['goods'] as $key => $value) {
					$nowOrderInfo['goods'][$key]['sprice']=$proinfos[$key]['Price'];
					$nowOrderInfo['goods'][$key]['money']=$proinfos[$key]['Price']*$nowOrderInfo['goods'][$key]['num'];
				}
				$nowOrderInfo['discount']=$Groupdiscountinfo['discount'];
				$nowOrderInfo['money']=$Groupdiscountinfo['money'];
				$orderInfoArray[$data['oid']]=$nowOrderInfo;
				$dtype='change';
			}else{
				$nowOrderInfo['type']='none';
				$proinfo=M()->table(C('DB_BASE')['DB_PREFIX']."ProductList pl")->join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=pl.ProId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."ProductOnsale pos ON p.ProId=pos.ProId","LEFT")->where(array('pl.ProIdCard'=>array('in',implode($ProIdCards, ','))))->field("p.ProId,pl.ProIdCard,pl.Price,pos.sprice,0 AS nums,pl.IsUseScore,pl.Score")->select();
				$tempMoney=0;
				$tempDisucount=0;
				foreach ($nowOrderInfo['goods'] as $key => $value) {
					foreach ($proinfo as $pv) {
						if ($key==$pv['ProIdCard']) {
							$nowOrderInfo['goods'][$key]['price']=$pv['Price'];
							$nowOrderInfo['goods'][$key]['sprice']=$pv['sprice'];
							if (empty($pv['sprice']) && $pv['sprice']==0) {
								$tempMoney+=$nowOrderInfo['goods'][$key]['num']*(floatval($pv['Price']));
								$tempDisucount+=0;
							}else{
								$tempMoney+=$nowOrderInfo['goods'][$key]['num']*(floatval($pv['sprice']));
								$tempDisucount+=(floatval($pv['Price'])-floatval($pv['sprice']))*$nowOrderInfo['goods'][$key]['num'];
							}
						}
					}
				}
				$nowOrderInfo['discount']=$tempDisucount;
				$nowOrderInfo['money']=$tempMoney;
				$orderInfoArray[$data['oid']]=$nowOrderInfo;
				$dtype='change';
			}
		}
		if (count($orderInfoArray[$data['oid']]['goods'])<1) {
			$orderInfoArray[$data['oid']]['money']=0;
			$orderInfoArray[$data['oid']]['nums']=0;
		}



		$dtype=$dtype=='change'?$dtype:'none';








		$this->saveCooies('GoodsList',json_encode($orderInfoArray),'sy');
		$tempGood=array();
		foreach ($orderInfoArray[$data['oid']]['goods'] as $v) {
			$tempGood[]=$v;
		}
		// var_dump($tempGood);
		$this->ajaxReturn(array('status'=>'true','info'=>'success','dtype'=>$dtype,'data'=>$orderInfoArray[$data['oid']],'orderdata'=>$tempGood[0],'promotion'=>$this->getPromotion($orderInfoArray[$data['oid']]['money'])),'JSON');
	}

	public function changeNum()
	{
		$data=$_POST;
		$orderInfoArray=cookie("sy_GoodsList");
		$orderInfoArray=json_decode($orderInfoArray,true);
		$thisOrder=$orderInfoArray[$data['oid']];
		$thisGood=$thisOrder['goods'][$data['gid']];
		$proinfo=M()->table('RS_ProductList')->where("token='%s' and ProIdCard='%s'",array($this->token,$data['gid']))->find();
		// $groupinfo=M()->table('RS_Groupdiscountlist')->where("GroupId='%s' and token='%s' and ProIdCard='%s'",array($thisOrder['GroupId'],$this->token,$data['gid']))->find(); //组合优惠价格
		if ($thisOrder['type']=='groupdiscount') {
			$onums=$thisOrder['nums']-$thisGood['num'];
			$omoney=$thisOrder['money']-$thisGood['money'];
			$odiscount=$thisOrder['discount']-($thisGood['num']*(floatval($thisGood['price'])-floatval($thisGood['sprice'])));
			$thisOrder['nums']=$onums+$data['num'];
			$thisOrder['money']=$omoney+($data['num']*floatval($thisGood['price']));
			$thisOrder['discount']=$odiscount+($data['num']*(floatval($proinfo['Price'])-floatval($thisGood['price'])));
			// $thisOrder['nums']=$thisOrder['nums']-$thisGood['num']+$data['num'];
			// $thisOrder['money']=$thisOrder['money']-$thisGood['money']+($data['num']*floatval($groupinfo['Price']));
			// $thisOrder['discount']=$thisOrder['discount']-($thisGood['num']*($thisGood['price']-$thisOrder['goods']['gid']['sprice']))+($data['num']*(floatval($proinfo['Price'])-floatval($groupinfo['Price'])));
			$thisGood['money']=$data['num']*floatval($thisGood['price']);
			$thisGood['num']=$data['num'];
			$thisGood['discount']=$data['num']*(floatval($proinfo['Price'])-floatval($thisGood['price']));
			$thisOrder['goods'][$data['gid']]=$thisGood;
			$orderInfoArray[$data['oid']]=$thisOrder;
			$nowGoodsInfo=$thisGood;
			// var_dump($orderInfoArray);exit();
		}else{
			$gid='';
			if (substr($data['gid'], 0,3)=='xzp')
			{
				$gid=substr($data['gid'],3);
				$nowGoodsInfo=$orderInfoArray[$data['oid']]['zpgoods'][$gid];
				$orderInfoArray[$data['oid']]['money']=$orderInfoArray[$data['oid']]['money']-$nowGoodsInfo['money'];
				$orderInfoArray[$data['oid']]['nums']=$orderInfoArray[$data['oid']]['nums']-$nowGoodsInfo['num'];
				$orderInfoArray[$data['oid']]['discount']=$orderInfoArray[$data['oid']]['discount']-($nowGoodsInfo['sprice']*$nowGoodsInfo['num']);

		// money: 1
		// num: 1
		// pid: "pro0516656563_39"
		// pname: "蜂之巢洋槐蜜"
		// price: "1.00"
		// sprice: 0
				$nowGoodsInfo['num']=$data['num'];
				$nowGoodsInfo['money']=$data['num']*$nowGoodsInfo['price'];

				$orderInfoArray[$data['oid']]['money']=$orderInfoArray[$data['oid']]['money']+$nowGoodsInfo['money'];
				$orderInfoArray[$data['oid']]['nums']=$orderInfoArray[$data['oid']]['nums']+$nowGoodsInfo['num'];
				$orderInfoArray[$data['oid']]['discount']=$orderInfoArray[$data['oid']]['discount']+($nowGoodsInfo['sprice']*$nowGoodsInfo['num']);

				$orderInfoArray[$data['oid']]['zpgoods'][$gid]=$nowGoodsInfo;

			}
			else
			{
				$gid=$data['gid'];
				$nowGoodsInfo=$orderInfoArray[$data['oid']]['goods'][$gid];
				$orderInfoArray[$data['oid']]['money']=$orderInfoArray[$data['oid']]['money']-$nowGoodsInfo['money'];
				$orderInfoArray[$data['oid']]['nums']=$orderInfoArray[$data['oid']]['nums']-$nowGoodsInfo['num'];
				$orderInfoArray[$data['oid']]['discount']=$orderInfoArray[$data['oid']]['discount']-($nowGoodsInfo['sprice']*$nowGoodsInfo['num']);

		// money: 1
		// num: 1
		// pid: "pro0516656563_39"
		// pname: "蜂之巢洋槐蜜"
		// price: "1.00"
		// sprice: 0
				$nowGoodsInfo['num']=$data['num'];
				$nowGoodsInfo['money']=$data['num']*$nowGoodsInfo['price'];

				$orderInfoArray[$data['oid']]['money']=$orderInfoArray[$data['oid']]['money']+$nowGoodsInfo['money'];
				$orderInfoArray[$data['oid']]['nums']=$orderInfoArray[$data['oid']]['nums']+$nowGoodsInfo['num'];
				$orderInfoArray[$data['oid']]['discount']=$orderInfoArray[$data['oid']]['discount']+($nowGoodsInfo['sprice']*$nowGoodsInfo['num']);

				$orderInfoArray[$data['oid']]['goods'][$gid]=$nowGoodsInfo;

			}
		}

		//$orderInfoArray[$data['oid']]['goods'][$data['gid']]


		//$orderInfoArray[$data['oid']]['goods'][$data['gid']]


		$this->saveCooies('GoodsList',json_encode($orderInfoArray),'sy');

		$this->ajaxReturn(array('status'=>'true','info'=>'success','data'=>$orderInfoArray[$data['oid']],'goodsInfo'=>$nowGoodsInfo,'promotion'=>$this->getPromotion($orderInfoArray[$data['oid']]['money'])),'JSON');
	}

	public function storeCashier()
	{
			$data=$_POST;

			$casherInfo=session('userinfo');
			// var_dump($casherInfo);exit();
			$orderInfoStr=cookie("sy_GoodsList");
            $tb_name='tb_wh'.substr($this->token, -8,8); //仓库表名

	        $addInfo=M('store')->where(array('id'=>$casherInfo['userShop'],'token'=>$this->token))->find();
	        // var_dump($_SESSION);
            $orderData["RecevingName"]=$addInfo["id"].'';
            $orderData["RecevingArea"]=$addInfo["area"].'';
            $orderData["RecevingCity"]=$addInfo["city"].'';
            $orderData["RecevingProvince"]=$addInfo["province"].'';
            $orderData["RecevingAddress"]=$addInfo["addr"].'';
            $orderData["RecevingPost"]='ZT';
            $orderData["RecevingPhone"]=$addInfo["tel"].'';

    		$orderData["OrderId"]=$data["oid"];
    		// $orderData["MemberId"]=$data['memberId'];
    		$orderData["Freight"]=0;
    		$orderData["Count"]=0;
    		$orderData['Posname']=$casherInfo['userName'];
			$orderData["SceneMember"]="STOREID";
			$orderData["SceneId"]='STOREID';
			$orderData["SceneContent"]="STOREID";


			/**
			 * 积分订单信息
			 */
            $sorderData["RecevingName"]=$addInfo["id"].'';
            $sorderData["RecevingArea"]=$addInfo["area"].'';
            $sorderData["RecevingCity"]=$addInfo["city"].'';
            $sorderData["RecevingProvince"]=$addInfo["province"].'';
            $sorderData["RecevingAddress"]=$addInfo["addr"].'';
            $sorderData["RecevingPost"]='ZT';
            $sorderData["RecevingPhone"]=$addInfo["tel"].'';

    		$sorderData["OrderId"]=$data["oid"];
    		// $sorderData["MemberId"]=$data['memberId'];
    		$sorderData["Freight"]=0;
    		$sorderData["Count"]=0;
    		$sorderData['Price']=0;

			$sorderData["SceneMember"]="STOREID";
			$sorderData["SceneId"]='STOREID';
			$sorderData["SceneContent"]="STOREID";
			// $sorderData['Posname']=$casherInfo['userName'];
    		//$orderData["Count"]=$data["orderid"];
    		// $SceneStr=cookie('user_Scene');
    		// if(!(empty($SceneStr)||$SceneStr=='NULLVALUE'))
    		// {
      //           //有场景
      //           $SceneArray=json_decode($SceneStr,true);
      //           foreach($SceneArray as $key => $item)
      //           {

      //               switch ($key)
      //               {
      //                  case 'uid':
      //                       $orderData['SceneMember']= $item;
      //                       break;
      //                  case 'sid':
      //                       $orderData['SceneId']=$item;
      //                       break;
      //                  case 'ct':
      //                       $orderData['SceneContent']=$item;
      //                       break;
      //                  default:
      //                       break;
      //               }
      //           }
    		// }
    		// else
    		// {
    		// 	$orderData["SceneMember"]="";
    		// 	$orderData["SceneId"]="";
    		// 	$orderData["SceneContent"]="";
    		// }
			// var_dump($_COOKIE);
            $orderInfoListArray=json_decode($orderInfoStr,true);

            $orderInfoList=$orderInfoListArray[$data['oid']];
            $sorderData['MemberId']=$orderInfoList['memberid'];
            $orderData['MemberId']=$orderInfoList['memberid'];
            $CouponId=$data['OrderCouponId']?$data['OrderCouponId']:false;
            $msg='优惠信息：';
            /**
             * 优先判断组合优惠
             */
            if ($orderInfoList['type']=='groupdiscount') {
            	$msg.='组合优惠';
            	$orderData['Discounttype']='group';
	            $gidList=array();
	            foreach ($orderInfoList["goods"] as $key => $value) {
	                array_push($gidList,$key);
	            }

	            $oldInfo=M()->table(C('DB_BASE')['DB_PREFIX']."Product p")->join(C('DB_BASE')['DB_PREFIX']."ProductList pl ON p.ProId=pl.ProId","LEFT")->where(array('pl.ProIdCard'=>array('in',implode($gidList, ','))))->field("p.ProName,p.ProId,pl.ProIdCard,pl.Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,0 AS nums,pl.IsUseScore,pl.Score")->select();
	            // var_dump(M()->getlastsql());


	            $TicketArray=array(
	            	'orderid'=>$data['oid'],
	            	'memberid'=>$orderInfoList['memberid'],
	            	'count'=>0,
	            	'money'=>0,
	            	'oldmoney'=>0,
	            	'offmoney'=>0,
	            	'allscore'=>0,
	            	'casher'=>$casherInfo['TrueName'],
	            	'company'=>'',
	            	'shop'=>$addInfo['storename'],
	            	'phone'=>$addInfo['tel'],
	            	'goods'=>array(),
	            	'date'=>date('Y-m-d H:i:s',time()),
	            	);


	            $CutMoneys = array(0,0,0);
	            //处理方法
		 		// $alldiscount=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->order('CreateDate desc')->getField('GroupId,ProIdCards,CreateDate'); //获取所有组合优惠  //后期可优化至方法中查询
	            $Groupdiscountinfo=$this->getDiscount($gidList);
	            $proinfos=$Groupdiscountinfo['goods'];
	    		foreach ($oldInfo as $key => $value)
	            {

	                $oldInfo[$key]['nums']=(int)$orderInfoList['goods'][$value['ProIdCard']]['num'];
	                $value["nums"]=(int)$oldInfo[$key]['nums'];

	    			$goodList["OrderId"]=$orderData["OrderId"];
	    			$goodList["ProId"]=$value["ProId"];
	    			$goodList["ProIdCard"]=$value["ProIdCard"];
	    			$goodList["Price"]=(float)$proinfos[$value['ProIdCard']]["Price"];

	    			$goodList["Count"]=(int)$value["nums"];



	   				$TicketArray['goods'][$value['ProIdCard']]=array(
	    				'name'=>$value['ProName'],
	    				'price'=>0,
	    				'count'=>$goodList['Count'],
	    				'money'=>0,
	    				);


                    $goodList["Money"]=(float)$proinfos[$value['ProIdCard']]["Price"]*$value["nums"];
                    $TicketArray['offmoney']=$TicketArray['offmoney']+((float)$proinfos[$value['ProIdCard']]["oldPrice"]-(float)$proinfos[$value['ProIdCard']]["Price"])*$value["nums"];
                    $TicketArray['goods'][$value['ProIdCard']]['price']=$proinfos[$value['ProIdCard']]["Price"];
	                $TicketArray['oldmoney']=$TicketArray['oldmoney']+(float)$proinfos[$value['ProIdCard']]["oldPrice"]*$value["nums"];
	                $TicketArray['goods'][$value['ProIdCard']]['money']=$goodList["Money"];

	                //提成信息
	                $CutMoneys[0]+=floatval($goodList['Money'])*floatval($value['Cut'])/100;
				    $CutMoneys[1]+=floatval($goodList['Money'])*floatval($value['Cut2'])/100;
				    $CutMoneys[2]+=floatval($goodList['Money'])*floatval($value['Cut3'])/100;


	    			$goodList["Cut"]=$value["Cut"];
	    			$goodList["Cut2"]=$value["Cut2"];
	    			$goodList["Cut3"]=$value["Cut3"];
	    			$goodList["Spec"]=$value["ProSpec1"]."_".$value["ProSpec2"]."_".$value["ProSpec3"]."_".$value["ProSpec4"]."_".$value["ProSpec5"];
	    			$goodList["IsDelete"]=false;
	    			$goodList["LastUpdateDate"]=date("Y-m-d H:i:s",time());
	    			$goodList["IsEvaluation"]=0;
	    			$goodList['Iszp']=0;

	    			$res=M("orderlist")->add($goodList);
	    			$res1=M("product")->where("token='%s' and ProId='%s'",array($this->token,$goodList['ProId']))->setInc('SalesCount',$goodList['Count']); //增加销量
	    			$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount-".$goodList['Count'].",VirtualCount=VirtualCount-".$goodList['Count'].",SalesCount=SalesCount+".$goodList['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$goodList['ProIdCard']."'");
	                $orderData["Price"]=((float)$orderData["Price"]+$goodList["Money"]);
	                $orderData["Count"]=($orderData["Count"]+$value["nums"]);

	                $goodList=array();
	    			if(!$res || !$res1)
	    			{
	    				M()->rollback();
	    				$this->ajaxReturn(array('status'=>'false','info'=>'saveError_orderlist'),'JSON');
	                    return;
	    			}

	    		}
            }else{
	            // var_dump($orderInfoList);exit();
	            $sorderData['MemberId']=$orderInfoList['memberid'];
	            $orderData['MemberId']=$orderInfoList['memberid'];
	            $gidList=array();
	            foreach ($orderInfoList["goods"] as $key => $value) {
	                array_push($gidList,$key);
	            }

	            $oldInfo=M()->table(C('DB_BASE')['DB_PREFIX']."Product p")->join(C('DB_BASE')['DB_PREFIX']."ProductList pl ON p.ProId=pl.ProId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."ProductOnsale pos ON p.ProId=pos.ProId","LEFT")->where(array('pl.ProIdCard'=>array('in',implode($gidList, ','))))->field("p.ProName,p.ProId,pl.ProIdCard,pl.Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,pos.sprice,0 AS nums,pl.IsUseScore,pl.Score,pos.Ison,CONVERT(varchar(100),pos.stime,120) as stime,CONVERT(varchar(100),pos.etime,120) as etime")->select();
	            // var_dump(M()->getlastsql());
	            // var_dump($oldInfo);
	            // $cInfo=array();
	            // $sInfo=array();
	            foreach ($oldInfo as $oinfo) {
	            	if ($oinfo['IsUseScore']==1) {
	            		$sInfo[]=$oinfo;
	            	}else{
	            		// var_dump($oinfo);
	            		if ($oinfo['Ison']==1 && strtotime($oinfo['stime'])<time() && strtotime($oinfo['etime'])>time()) {
	            			// echo "123";
	            			// 特价信息
	            			$oinfo['sprice']=$oinfo['sprice'];
	            		}else{
	            			$oinfo['sprice']=0;
	            		}
	            		$cInfo[]=$oinfo;
	            	}
	            }

	            // var_dump($cInfo);exit();
	            // $sInfo=M()->table(C('DB_BASE')['DB_PREFIX']."Product p")->join(C('DB_BASE')['DB_PREFIX']."ProductList pl ON p.ProId=pl.ProId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."ProductOnsale pos ON p.ProId=pos.ProId","LEFT")->where(array('pl.ProIdCard'=>array('in',implode($gidList, ',')),'pl.IsUseScore'=>'1'))->field("p.ProName,p.ProId,pl.Score,pl.ProIdCard,pl.Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,pos.sprice,0 AS nums")->select();
	            $gidList=array();
	            foreach ($orderInfoList["zpgoods"] as $key => $value) {
	                array_push($gidList,$key);
	            }

	            $zpInfo=M()->table(C('DB_BASE')['DB_PREFIX']."Product p")->join(C('DB_BASE')['DB_PREFIX']."ProductList pl ON p.ProId=pl.ProId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."ProductOnsale pos ON p.ProId=pos.ProId","LEFT")->where(array('pl.ProIdCard'=>array('in',implode($gidList, ','))))->field("p.ProName,p.ProId,pl.ProIdCard,pl.Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,pos.sprice,0 AS nums")->select();





	            $TicketArray=array(
	            	'orderid'=>$data['oid'],
	            	'memberid'=>$orderInfoList['memberid'],
	            	'count'=>0,
	            	'money'=>0,
	            	'oldmoney'=>0,
	            	'offmoney'=>0,
	            	'allscore'=>0,
	            	'casher'=>$casherInfo['TrueName'],
	            	'company'=>'',
	            	'shop'=>$addInfo['storename'],
	            	'phone'=>$addInfo['tel'],
	            	'goods'=>array(),
	            	'date'=>date('Y-m-d H:i:s',time()),
	            	);


	            $CutMoneys = array(0,0,0);
	            // var_dump($cInfo);
	            //处理方法
	            if (count($cInfo)>0) {
		    		foreach ($cInfo as $key => $value)
		            {

		                $cInfo[$key]['nums']=(int)$orderInfoList['goods'][$value['ProIdCard']]['num'];
		                $value["nums"]=(int)$cInfo[$key]['nums'];

		    			$goodList["OrderId"]=$orderData["OrderId"];
		    			$goodList["ProId"]=$value["ProId"];
		    			$goodList["ProIdCard"]=$value["ProIdCard"];
		    			$goodList["Price"]=(float)$value["Price"];

		    			$goodList["Count"]=(int)$value["nums"];



		   				$TicketArray['goods'][$value['ProIdCard']]=array(
		    				'name'=>$value['ProName'],
		    				'price'=>0,
		    				'count'=>$goodList['Count'],
		    				'money'=>0,
		    				);


		                if (empty($value['sprice'])&&$value['sprice']==0) {
		                    $goodList["Money"]=(float)$value["Price"]*$value["nums"];
		                    $TicketArray['goods'][$value['ProIdCard']]['price']=$value["Price"];
		                }
		                else
		                {
		                	$msg.='限时特价商品:'.$value['ProName'].'￥：'.$value['sprice'].'。';
		                    $goodList["Money"]=(float)$value["sprice"]*$value["nums"];
		                    $TicketArray['offmoney']=$TicketArray['offmoney']+((float)$value['price']-(float)$value['sprice'])*$value["nums"];
		                    $TicketArray['goods'][$value['ProIdCard']]['price']=$value["sprice"];
		                }

		                $TicketArray['oldmoney']=$TicketArray['oldmoney']+(float)$value["Price"]*$value["nums"];
		                $TicketArray['goods'][$value['ProIdCard']]['money']=$goodList["Money"];

		                //提成信息
		                $CutMoneys[0]+=floatval($goodList['Money'])*floatval($value['Cut'])/100;
					    $CutMoneys[1]+=floatval($goodList['Money'])*floatval($value['Cut2'])/100;
					    $CutMoneys[2]+=floatval($goodList['Money'])*floatval($value['Cut3'])/100;
					    // var_dump($value['Price'],$value['Cut']);

		    			$goodList["Cut"]=$value["Cut"];
		    			$goodList["Cut2"]=$value["Cut2"];
		    			$goodList["Cut3"]=$value["Cut3"];
		    			$goodList["Spec"]=$value["ProSpec1"]."_".$value["ProSpec2"]."_".$value["ProSpec3"]."_".$value["ProSpec4"]."_".$value["ProSpec5"];
		    			$goodList["IsDelete"]=false;
		    			$goodList["LastUpdateDate"]=date("Y-m-d H:i:s",time());
		    			$goodList["IsEvaluation"]=0;
		    			$goodList['Iszp']=0;

		    			$res=M("orderlist")->add($goodList);
		    			$res1=M("product")->where("token='%s' and ProId='%s'",array($this->token,$goodList['ProId']))->setInc('SalesCount',$goodList['Count']); //增加销量
		    			$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount-".$goodList['Count'].",VirtualCount=VirtualCount-".$goodList['Count'].",SalesCount=SalesCount+".$goodList['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$goodList['ProIdCard']."'");
		                $orderData["Price"]=((float)$orderData["Price"]+$goodList["Money"]);
		                $orderData["Count"]=($orderData["Count"]+$value["nums"]);

		                $goodList=array();
		    			if(!$res || !$res1)
		    			{
		    				M()->rollback();
		    				$this->ajaxReturn(array('status'=>'false','info'=>'saveError_orderlist'),'JSON');
		                    return;
		    			}

		    		}
	            }
	    		if (count($sInfo)>0) {
	    			// var_dump($sInfo);exit();
		    		foreach ($sInfo as $key => $value)
		            {

		                $sInfo[$key]['nums']=(int)$orderInfoList['goods'][$value['ProIdCard']]['num'];
		                $value["nums"]=(int)$sInfo[$key]['nums'];

		    			$goodList["OrderId"]=$orderData["OrderId"];
		    			$goodList["ProId"]=$value["ProId"];
		    			$goodList["ProIdCard"]=$value["ProIdCard"];
		    			$goodList["Price"]=(float)$value["Score"];

		    			$goodList["Count"]=(int)$value["nums"];



		   				$TicketArray['goods'][$value['ProIdCard']]=array(
		    				'name'=>$value['ProName'],
		    				'price'=>0,
		    				'count'=>$goodList['Count'],
		    				'money'=>0,
		    				);

		   				$goodList['Money']=floatval($value['Score'])*$value['nums'];
		   				$TicketArray['goods'][$value['ProIdCard']]['price']=$value["Score"].'积分';
		                // if (empty($value['sprice'])&&$value['sprice']==0) {
		                //     $goodList["Money"]=(float)$value["Price"]*$value["nums"];
		                //     $TicketArray['goods'][$value['ProIdCard']]['price']=$value["Price"];
		                // }
		                // else
		                // {
		                //     $goodList["Money"]=(float)$value["sprice"]*$value["nums"];
		                //     $TicketArray['offmoney']=$TicketArray['offmoney']+((float)$value['price']-(float)$value['sprice'])*$value["nums"];
		                //     $TicketArray['goods'][$value['ProIdCard']]['price']=$value["sprice"];
		                // }

		                // $TicketArray['oldmoney']=$TicketArray['oldmoney']+(float)$value["Score"]*$value["nums"];
		                $TicketArray['allscore']=$TicketArray['allscore']+(float)$value['Score']*$value['nums'];
		                $TicketArray['goods'][$value['ProIdCard']]['money']=$goodList["Money"].'积分';

		                //提成信息


		    			$goodList["Cut"]=0;
		    			$goodList["Cut2"]=0;
		    			$goodList["Cut3"]=0;
		    			$goodList["Spec"]=$value["ProSpec1"]."_".$value["ProSpec2"]."_".$value["ProSpec3"]."_".$value["ProSpec4"]."_".$value["ProSpec5"];
		    			$goodList["IsDelete"]=false;
		    			$goodList["LastUpdateDate"]=date("Y-m-d H:i:s",time());
		    			$goodList["IsEvaluation"]=0;
		    			$goodList['Iszp']=0;

		    			$res=M("scoreorderlist")->add($goodList);
		    			$res1=M("product")->where("token='%s' and ProId='%s'",array($this->token,$goodList['ProId']))->setInc('SalesCount',$goodList['Count']); //增加销量
		    			$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount-".$goodList['Count'].",VirtualCount=VirtualCount-".$goodList['Count'].",SalesCount=SalesCount+".$goodList['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$goodList['ProIdCard']."'");
		                $sorderData["Price"]=((float)$sorderData["Price"]+$goodList["Money"]);
		                $sorderData["Count"]=($sorderData["Count"]+$value["nums"]);

		                $goodList=array();
		    			if(!$res || !$res1)
		    			{
		    				M()->rollback();
		    				$this->ajaxReturn(array('status'=>'false','info'=>'saveError_scoreorderlist','sql'=>M('scoreorderlist')->getlastsql()),'JSON');
		                    return;
		    			}

		    		}
	    		}

	    		foreach ($zpInfo as $key => $value)
	            {

	                $zpInfo[$key]['nums']=(int)$orderInfoList['zpgoods'][$value['ProIdCard']]['num'];
	                $value["nums"]=(int)$zpInfo[$key]['nums'];

	    			$goodList["OrderId"]=$orderData["OrderId"];
	    			$goodList["ProId"]=$value["ProId"];
	    			$goodList["ProIdCard"]=$value["ProIdCard"];
	    			$goodList["Price"]=0;
	    			$goodList["Money"]=0;
	    			$goodList["Count"]=(int)$value["nums"];



	   				// $TicketArray['goods'][$value['ProIdCard']]=array(
	    			// 	'name'=>$value['ProName'],
	    			// 	'price'=>0,
	    			// 	'count'=>$goodList['Count'],
	    			// 	'money'=>0,
	    			// 	);


	                // if (empty($value['sprice'])&&$value['sprice']==0) {
	                //     $goodList["Money"]=(float)$value["Price"]*$value["nums"];
	                //     //$TicketArray['goods'][$value['ProIdCard']]['price']=$value["Price"];
	                // }
	                // else
	                // {
	                //     $goodList["Money"]=(float)$value["sprice"]*$value["nums"];
	                //     //$TicketArray['offmoney']=$TicketArray['offmoney']+((float)$value['price']-(float)$value['sprice'])*$value["nums"];
	                //     //$TicketArray['goods'][$value['ProIdCard']]['price']=$value["sprice"];
	                // }

	                //$TicketArray['oldmoney']=$TicketArray['oldmoney']+(float)$value["Price"]*$value["nums"];
	                //$TicketArray['goods'][$value['ProIdCard']]['money']=$goodList["Money"];

	                //提成信息
	       //          $CutMoneys[0]+=floatval($value['Money'])*floatval($value['Cut'])/100;
				    // $CutMoneys[1]+=floatval($value['Money'])*floatval($value['Cut2'])/100;
				    // $CutMoneys[2]+=floatval($value['Money'])*floatval($value['Cut3'])/100;


	    			$goodList["Cut"]=$value["Cut"];
	    			$goodList["Cut2"]=$value["Cut2"];
	    			$goodList["Cut3"]=$value["Cut3"];
	    			$goodList["Spec"]=$value["ProSpec1"]."_".$value["ProSpec2"]."_".$value["ProSpec3"]."_".$value["ProSpec4"]."_".$value["ProSpec5"];
	    			$goodList["IsDelete"]=false;
	    			$goodList["LastUpdateDate"]=date("Y-m-d H:i:s",time());
	    			$goodList["IsEvaluation"]=0;
	    			$goodList['Iszp']=1;

	    			$res=M("orderlist")->add($goodList);
	    			$res1=M("product")->where("token='%s' and ProId='%s'",array($this->token,$goodList['ProId']))->setInc('SalesCount',$goodList['Count']); //增加销量
	    			$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount-".$goodList['Count'].",VirtualCount=VirtualCount-".$goodList['Count'].",SalesCount=SalesCount+".$goodList['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$goodList['ProIdCard']."'");
	                //$orderData["Price"]=((float)$orderData["Price"]+$goodList["Money"]);
	                $orderData["Count"]=($orderData["Count"]+$value["nums"]);

	                $goodList=array();
	    			if(!$res || !$res1)
	    			{
	    				M()->rollback();
	    				$this->ajaxReturn(array('status'=>'false','info'=>'saveError_orderlist'),'JSON');
	                    return;
	    			}

	    		}
            }








    		$TicketArray['count']=$orderData["Count"]+$sorderData['Count'];
    		$TicketArray['money']=$orderData["Price"];


    		//$data['memberId']

    		$getPromotion=$this->getPromotion($orderData["Price"]);
//
    		if ($orderInfoList['memberid']) {
    			if ($getPromotion['p2m']>0) {
	    			$couponPromotion=M('coupon')->where(array('CouponId'=>$getPromotion['p2']['CouponId']))->find();
	    			if ($couponPromotion) {
	    				$orderData['getcoupon']=$getPromotion['p2']['CouponId'];
	    				$MemberCoupon=M('membercoupon')->where(array('token'=>$this->token,'MemberId'=>$orderInfoList['memberid'],'CouponId'=>$getPromotion['p2']['CouponId']))->find();
	    				$resp=0;
	    				if ($MemberCoupon) {
	    					$resp=M('membercoupon')->where(array('ID'=>$MemberCoupon['ID']))->setInc('CouponCount');
	    				}
	    				else
	    				{
	    					$resp=M('membercoupon')->where(array('ID'=>$MemberCoupon['ID']))->add(
	    						array(
	    							'MemberId'=>$orderInfoList['memberid'],
	    							'CouponId'=>$getPromotion['p2']['CouponId'],
	    							'GetTime'=>date('Y-m-d H:i:s',time()),
	    							'CouponCount'=>1,
	    							'LastUpdateTime'=>date('Y-m-d H:i:s',time()),
	    							'token'=>$this->token,
	    							)
	    						);
	    				}
	    				if (!$resp) {
	    					M()->rollback();
		    				$this->ajaxReturn(array('status'=>'false','info'=>'saveError_addCoupon'),'JSON');
		                    return;
	    				}
	    			}
	    			else
	    			{

	    			}
    			}
    		}
    		else
    		{

    		}
    		$orderData["CouponListId"]="0";
            	// var_dump($TicketArray);exit();
    		//组合优惠>订单慢减+限时特价>优惠券使用
    		// var_dump($getPromotion);exit();
            if ($orderInfoList['type']=='groupdiscount') {
            	$TicketArray['offmoney']=$orderInfoList['discount'];
            	$TicketArray['money']=$orderInfoList['money'];
            	$orderData['Price']=$orderInfoList['money'];
            	$orderData['Coupon']=$orderInfoList['discount'];
    		}else{
    			if ($getPromotion['p1m']>0 || $getPromotion['p2m']>0) {
    				$msg.='订单满减';
    			}
	    		$TicketArray['offmoney']=$TicketArray['offmoney']+$getPromotion['p1m'];
	    		$TicketArray['money']=$TicketArray['money']-$getPromotion['p1m'];
	    		$orderData["Price"]=($orderData["Price"]+$orderData["Freight"]-$getPromotion['p1m']);
	    		$orderData["Coupon"]=$getPromotion['p1m'];
	    		// var_dump($TicketArray['offmoney']);
	    		if ($CouponId) {
	    			// echo "string";
	    			$orderData['CouponListId']=$CouponId;
		    		$couponInfo=$this->getCouponInfo($orderData['Price'],$CouponId,$orderInfoList['memberid']);
		    		if ($couponInfo) {
			    		$orderData['Price']=$couponInfo['Price'];
			    		$orderData['Coupon']=$orderData['Coupon']+$couponInfo['Coupon'];
			    		$TicketArray['offmoney']=$TicketArray['offmoney']+$couponInfo['Coupon'];
			    		$TicketArray['money']=$couponInfo['Price'];
			    		if (!M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$orderInfoList['memberid'],$CouponId))->setDec('CouponCount')) {
			    			M()->rollback();
		    				$this->ajaxReturn(array('status'=>'false','info'=>'lessCouponError'),'JSON');
			    		}
		    		}
	    		}

    		}


    		// var_dump($TicketArray);exit();
    		//$orderData["Price"]=($orderData["Price"]+$orderData["Freight"]);
    		//$orderData["TotlePrice"]=$orderData["Price"];
    		//$orderData["Coupon"]=0;

    		$orderData["PayName"]=$data['PayType'];

    		$orderData["IsEvaluation"]=0;
    		if ($data['PayType']=='T') {
    			$orderData['Status']=1;
    		}else{
	    		$orderData["Status"]=4;
    		}
    		$orderData["MessageBySeller"]="";
    		$orderData["MessageByBuy"]="";
    		$orderData["NewStatusId"]=0;
    		$orderData["OutWarehouseId"]="";
    		$orderData["Logistics"]="";
    		$orderData["LogisticsId"]="";

    		$orderData["CreateDate"]=date("Y-m-d H:i:s",time());
    		$orderData["PayDate"]=date("Y-m-d H:i:s",time());
    		$orderData["GetDate"]=date("Y-m-d H:i:s",time());
    		$orderData["BackMoneyDate"]=date("Y-m-d H:i:s",time());

    		$orderData["ValidDate"]=date('Y-m-d H:i:s',strtotime('+1 day'));

    		$orderData["LastUpdateDate"]=date("Y-m-d H:i:s",time());
    		$orderData["IsLogisticsDown"]=0;
    		$orderData["TransactionId"]="";
    		$orderData["BackMoneyReason"]="";
            $orderData["token"]=$this->token;

            /**
             * 积分订单信息
             */
    		$sorderData["CouponListId"]="0";

    		$sorderData["PayName"]=$data['PayType'];

    		$sorderData["IsEvaluation"]=0;
    		$sorderData["Status"]=4;
    		$sorderData["MessageBySeller"]="";
    		$sorderData["MessageByBuy"]="";
    		$sorderData["NewStatusId"]=0;
    		$sorderData["OutWarehouseId"]="";
    		$sorderData["Logistics"]="";
    		$sorderData["LogisticsId"]="";

    		$sorderData["CreateDate"]=date("Y-m-d H:i:s",time());
    		$sorderData["PayDate"]=date("Y-m-d H:i:s",time());
    		$sorderData["GetDate"]=date("Y-m-d H:i:s",time());
    		$sorderData["BackMoneyDate"]=date("Y-m-d H:i:s",time());

    		$sorderData["ValidDate"]=date('Y-m-d H:i:s',strtotime('+1 day'));

    		$sorderData["LastUpdateDate"]=date("Y-m-d H:i:s",time());
    		$sorderData["IsLogisticsDown"]=0;
    		$sorderData["TransactionId"]="";
    		$sorderData["BackMoneyReason"]="";
            $sorderData["token"]=$this->token;
			// $tichengGrade=C('tichengGrade');
			//$ordermaps=M('order')->where($orderMaps)->find();
			$mem=M('member')->where("token='%s' and MemberId='%s'",array($this->token,$orderInfoList['memberid']))->find();

			$memberData['OrderMoney']=floatval($mem['OrderMoney'])+floatval($orderData['Price']);

			$memberData['Cut']=floatval($mem['Cut'])+$CutMoneys[0];
			$memberData['Cut2']=floatval($mem['Cut2'])+$CutMoneys[1];
			$memberData['Cut3']=floatval($mem['Cut3'])+$CutMoneys[2];
			$memberData['LastUpdateDate']=$nowtime;
			// var_dump($CutMoneys);
			// M()->rollback();exit();

			// 上1级推广人 金额设置
			if(!empty($mem['SceneMember'])){

			    M()->execute("update rs_member set cutmoney=cutmoney+{$CutMoneys[0]},cuttotalmoney=cuttotalmoney+{$CutMoneys[0]},LastUpdateDate='{$nowtime}' where memberid='{$mem["SceneMember"]}'");

			    $comm1["MemberId"]=$orderInfoList['memberid'];
			    $comm1["OrderId"]=$data['oid'];
			    $comm1["CreateDate"]=date('Y-m-d H:i:s',time());
			    $comm1["Type"]="TQ";
			    $comm1["Remarks"]="POS收银结算";
			    $comm1["FromMemberId"]=$mem['SceneMember'];
			    $comm1["FromMemberGrade"]=1;
			    $comm1["Money"]=$CutMoneys[0];
			    $comm1['token']=$this->token;
			    M('membercommission')->add($comm1);
			}
			// 上2级推广人 金额设置
			if(!empty($mem['TcMember2'])){
			    M()->execute("update rs_member set cutmoney=cutmoney+{$CutMoneys[1]},cuttotalmoney=cuttotalmoney+{$CutMoneys[1]},LastUpdateDate='{$nowtime}' where memberid='{$mem["TcMember2"]}'");

			    $comm2["MemberId"]=$orderInfoList['memberid'];
			    $comm2["OrderId"]=$data['oid'];
			    $comm2["CreateDate"]=date('Y-m-d H:i:s',time());
			    $comm2["Type"]="TQ";
			    $comm2["Remarks"]="POS收银结算";
			    $comm2["FromMemberId"]=$mem['TcMember2'];
			    $comm2["FromMemberGrade"]=2;
			    $comm2["Money"]=$CutMoneys[1];
			    $comm2['token']=$this->token;
			    M('membercommission')->add($comm2);
			}
			// 上3级推广人 金额设置
			if(!empty($mem['TcMember3'])){
			    M()->execute("update rs_member set cutmoney=cutmoney+{$CutMoneys[2]},cuttotalmoney=cuttotalmoney+{$CutMoneys[2]},LastUpdateDate='{$nowtime}' where memberid='{$mem["TcMember3"]}'");

			    $comm3["MemberId"]=$orderInfoList['memberid'];
			    $comm3["OrderId"]=$data['oid'];
			    $comm3["CreateDate"]=$nowtime;
			    $comm3["Type"]="";
			    $comm3["Remarks"]="";
			    $comm3["FromMemberId"]=$mem['TcMember3'];
			    $comm3["FromMemberGrade"]=3;
			    $comm3["Money"]=$CutMoneys[2];
			    $comm3['token']=$this->token;
			    M('membercommission')->add($comm3);
			}

//$orderData["Price"]

			//$couponInfo=M('discount')->where(array('token'=>$this->token))->select();


            // $offCoupon=M()->table(C('DB_BASE')['DB_PREFIX'].'MemberCoupon mc')->join(C('DB_BASE')['DB_PREFIX'].'Coupon c ON c.CouponId=mc.CouponId','LEFT')->where(array('mc.MemberId'=>cookie('user_UserID'),'mc.token'=>$this->token,'mc.CouponCount'=>array('GT',0),'mc.CouponId'=>$_POST['cid'],'c.IsEnable'=>1,'c.token'=>$this->token,'c.ExpiredDate'=>array('EGT',date('Y-m-d H:i:s',time()))))->field('c.CouponId AS cid,c.CouponName AS cname,c.Rules AS rules,c.Type AS tp')->find();
            // //NONE是没有使用 配合查询为空 以后做安全性校验
            // if ($offCoupon)
            // {
            //     //M('membercoupon')->where(array('CouponId'=>$_POST['cid'],'MemberId'=>cookie('user_UserID'),'token'=>$this->token))->setDec('CouponCount',1)  确认订单再校验
            //     if (true)
            //     {

            //         $orderData["CouponListId"]=$offCoupon['cid'];
            //         switch ($offCoupon['tp']) {
            //             case 0:
            //                 //现金抵扣
            //                 $orderData["Coupon"]=$offCoupon['rules'];
            //                 $orderData["Price"]=$orderData["Price"]-$orderData["Coupon"];
            //                 if ($orderData['Price']<0) {
            //                     $orderData["Coupon"]=$orderData['Price']+$orderData["Coupon"];
            //                     $orderData['Price']=0;
            //                 }
            //                 //$orderData["Price"]+$orderData["Freight"]
            //                 break;

            //             case 1:
            //                 //折扣券
            //                 $orderData["Coupon"]=($orderData["Price"]-$orderData["Freight"])*(1-$offCoupon['rules']);
            //                 $orderData["Price"]=($orderData["Price"]-$orderData["Freight"])*$offCoupon['rules'];
            //                 break;

            //             case 2:
            //                 //满减券
            //                 $tempTypeTwoCoupon=explode('/',$offCoupon['rules']);
            //                 if ($tempTypeTwoCoupon[0]<($orderData["Price"]-$orderData["Freight"])) {
            //                     $orderData["Coupon"]=$tempTypeTwoCoupon[1];
            //                     $orderData["Price"]=$orderData["Price"]-$tempTypeTwoCoupon[1];
            //                     if ($orderData['Price']<0)
            //                     {
            //                         $orderData["Coupon"]=$orderData['Price']+$orderData["Coupon"];
            //                         $orderData['Price']=0;
            //                     }
            //                 }
            //                 else
            //                 {
            //                     //这里需要做安全性校验
            //                     M()->rollback();
            //                     $this->ajaxReturn(array('status'=>'false','info'=>($orderData["Price"])),'JSON');
            //                 }
            //                 break;

            //             case 3:
            //                 //余留给包邮卡
            //                 $orderData["Price"]=$orderData["Price"]-$orderData["Freight"];
            //                 $orderData["Coupon"]=$orderData['Freight'];
            //                 break;

            //             default:
            //                 break;
            //         }
            //     }
            //     else
            //     {
            //         M()->rollback();
            //         $this->ajaxReturn(array('status'=>'false','info'=>M()->_sql()),'JSON');
            //     }
            // }
            // else
            // {
            //     if ($_POST['cid']!='NONE') {
            //         //安全性校验
            //         M()->rollback();
            //         $this->ajaxReturn(array('status'=>'false','info'=>'CouponError.'),'JSON');
            //     }
            // }
			// var_dump($orderData);exit();
			$TicketArray['msg']=$msg;
			$TicketArray['oid']=$data['oid'];
			if (count($sInfo)>0) {
				$TicketArray['score']=$mem['Integral']-$sorderData['Price'];
				$sres=M('scoreorder')->add($sorderData);
				if ($sres) {
					if (!M('member')->where("token='%s' and MemberId='%s'",array($this->token,$orderInfoList['memberid']))->setDec('Integral',$sorderData['Price'])) {
						M()->rollback();
						$this->ajaxReturn(array('status'=>'false','saveError_memberInteral','JSON'));
					}
				}else{
					$this->ajaxReturn(array('status'=>'false','saveError_scoreorder','JSON'));
				}
			}else{
				$TicketArray['score']=$mem['Integral'];
				$TicketArray['userMoney']=$mem['Money'];
				$TicketArray['userVirtualMoney']=$mem['VirtualMoney'];
    			$res=M("order")->add($orderData);
	    		// var_dump($orderData);exit();
	    		if ($res) {
	    			if ($data['PayType']=='YE') {   //根据付款方式选择操作
	    				$res=M('member')->where("token='%s' and MemberId='%s'",array($this->token,$orderInfoList['memberid']))->setDec('Money',$orderData['Price']);
	    				$TicketArray['userMoney']=$mem['Money']-$orderData['Price'];
	    				$TicketArray['userVirtualMoney']=$mem['VirtualMoney'];
	    			}elseif ($data['PayType']=='JL') {
	    				$res=M('member')->where("token='%s' and MemberId='%s'",array($this->token,$orderInfoList['memberid']))->setDec('VirtualMoney',$orderData['Price']);
	    				$TicketArray['userVirtualMoney']=$mem['VirtualMoney']-$orderData['Price'];
	    				$TicketArray['userMoney']=$mem['Money'];
	    			}elseif($data['PayType']=='T'){
	    				$TicketArray['type']='wecode';
	    			}else{
	    				$res=true;
	    			}
	    			// echo M()->getlastsql();
	    		}
			}
			// $res=true;
			// if (count($cInfo)>0 || $orderInfoList['type']=='groupdiscount') {
			// }
    		if($res)
    		{
    			// unset($orderInfoListArray[$data['oid']]);
    			// cookie("sy_GoodsList",json_encode($orderInfoListArray));
    			cookie("sy_GoodsList",null);

    			M()->commit();
    			$this->ajaxReturn(array('status'=>'true','info'=>$orderInfoListArray,'ticket'=>$TicketArray,'data'=>array('nextOid'=>"E".date("YmdHis",time()).rand(1000,9999))),'JSON');
    		}
    		else
    		{
    			M()->rollback();
    			$this->ajaxReturn(array('status'=>'false','info'=>M("order")->_sql()),'JSON');
    		}


	}


	private function getPromotion($money)
	{
		$canUsePromotion=array(
			'p1'=>array(),
			'p2'=>array(),
			'p1m'=>0,
			'p2m'=>0,
			//'temp'=>'',
			);

		$tempMoney=0;
		$tempT2=array();

		$PromotionInfo=M('discount')->where(array('token'=>$this->token,'DiscountType'=>array('NEQ','2'),'Consume'=>array('ELT',$money),'stime'=>array('ELT',date('Y-m-d H:i:s',time())),'etime'=>array('EGT',date('Y-m-d H:i:s',time()))))->select();

		foreach ($PromotionInfo as $key => $value) {
			//$canUsePromotion['p1']=$value;
			if ($value['DiscountType']==0) {
				if ($canUsePromotion['p1']['Consume']<$value['Consume']) {
					$canUsePromotion['p1']=$value;
					$tempMoney=$money-$value['Discount'];
				}
			}
			else if ($value['DiscountType']==1) {
				$tempT2['T'.$value['id']]=$value;
			}
		}

		foreach ($tempT2 as $key => $value)
		{
			if ($tempMoney>=$value['Consume'])
			{
				$canUsePromotion['p2']=$value;
			}
		}

		if ($canUsePromotion['p1']['Discount']) {
			$canUsePromotion['p1m']=$canUsePromotion['p1']['Discount'];
		}

		if ($canUsePromotion['p2']['Discount']) {
			$canUsePromotion['p2m']=$canUsePromotion['p2']['Discount'];
		}

//$canUsePromotion['temp']=$tempMoney;
		return $canUsePromotion;
	}

	// public function shopQrcode()
	// {
	// 	ob_clean();
	// 	vendor('PHPQR.phpqrcode');
	// 	$url='http://'.$_SERVER['HTTP_HOST'];
	// 	//.U('Home/Account/QrCodeHandle',array('tp'=>'3','gid'=>$_GET['pid'],'sid'=>$_GET['sid']));
	// 	$level="L";
	// 	$size=4;
	// 	echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";;
	// }

	public function getMemberInfo()
	{
		$data=$_POST;
		$member=M('member')->where(array('MemberId'=>$data['uid'],'token'=>$this->token))->find();
		if ($member) {
			$gInfoArray=cookie("sy_GoodsList");
			$gInfoArray=json_decode($gInfoArray,true);
			$gInfoArray[$data['oid']]['memberid']=$member['MemberId'];
			$this->saveCooies('GoodsList',json_encode($gInfoArray),'sy');
			// $member['orderid']="E".date("YmdHis",time()).rand(1000,9999);
			$MemberCoupons=M()->query("SELECT mc.CouponId,c.CouponName,c.Rules,c.Type FROM RS_MemberCoupon mc LEFT JOIN RS_Coupon c ON mc.CouponId=c.CouponId WHERE mc.token='%s' AND mc.MemberId='%s' AND mc.CouponCount>%d AND c.CreateDate<'%s' AND c.ExpiredDate>'%s'",array($this->token,$data['uid'],0,date('Y-m-d H:i:s',time()),date('Y-m-d H:i:s',time())));
			// var_dump(M()->getlastsql());exit();
			$this->ajaxReturn(array('status'=>'true','info'=>'success','data'=>$member,'membercoupons'=>$MemberCoupons),'JSON');
		}
		else
		{
			$this->ajaxReturn(array('status'=>'false','info'=>'noUser'),'JSON');
		}
	}

	public function Order()
	{
		// var_dump($_SESSION['userinfo']);exit();
		$orderList=M('order')->where("SceneContent='STOREID' AND Posname='".session('userinfo')['userName']."' AND (PayDate BETWEEN '".date('Y-m-d',time())." 00:00:00' AND '".date('Y-m-d',time())." 23:59:59')")->field('OrderId,Count,Price,Coupon,CONVERT(varchar(20),PayDate,120) AS Date,Status')->order('Date ASC')->select();
		foreach ($orderList as &$order) {
			$order['sons']=M()->query("SELECT p.ProName,ol.Money,ol.Count,ol.Spec FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ol.OrderId='".$order['OrderId']."'");
		}
		// echo "<pre>";
		// var_dump($orderList);exit();
		// var_dump(M()->getlastsql());exit();
		$this->assign('olist',$orderList);
		$this->display();
	}

	/**
	 * 订单导出
	 */
	public function orderOut(){
            $xlsData=M('order')->where("SceneContent='STOREID' AND Posname='".session('userinfo')['userName']."' AND (PayDate BETWEEN '".date('Y-m-d',time())." 00:00:00' AND '".date('Y-m-d',time())." 23:59:59')")->field('OrderId,Count,Price,Coupon,CONVERT(varchar(20),PayDate,120) AS Date')->order('Date ASC')->select();

            $xlsName=session('userinfo')['userName']."_order_".date('ymdHm');
            $xlsCell = array(
                array('OrderId' , '订单号'),
                array('Count' , '数量'),
                array('Price' , '金额'),
                array('Coupon' ,'优惠金额'),
                array('Date' , '交易时间'),
            );
            exportExcel($xlsName,$xlsCell,$xlsData);
	}

	/**
	 * 计算订单总价及总数
	 */
	public function array_sumValue($array,$array_keys){
		$tempAry=array();
		foreach ($array_keys as $key) {
			foreach ($array as $value) {
				$tempAry[$key]+=$value[$key];
			}
		}
		return $tempAry;
	}

	/**
	 * 计算优惠券结算后
	 */
	public function getCouponInfo($Money,$CouponId,$MemberId){
		$Coupon=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->find();
		$CouponCount=M()->table('RS_MemberCoupon')->where("token='%s' and CouponId='%s' and MemberId='%s'",array($this->token,$CouponId,$MemberId))->getField('CouponCount');
		if (!$Coupon || $CouponCount<=0) {
			$this->LOGS('未查找到优惠券信息'.M()->getlastsql());
			return false;
			//日志记录
		}
		if ($Coupon['Type']=='0') {
			$data['Price']=$Money-$Coupon['Rules'];
			$data['Coupon']=$Coupon['Rules'];
		}elseif ($Coupon['Type']=='1') {
			$data['Price']=$newMoney=$Money*floatval($Coupon['Rules']);
			$data['Coupon']=$Money-$newMoney;
		}elseif ($Coupon['Type']=='2') {
			$tempR=explode('/', $Coupon['Rules']);
			$data['Price']=$Money-$tempR[1];
			$data['Coupon']=$tempR[1];
		}else{
			$this->LOGS('未知的优惠券类型');
			$data=false;
		}
		return $data;
	}

	/**
	 * 撤销订单操作
	 */
	public function revoke(){
		$OrderId=$_GET['oid'];
		$Oinfo=M()->table('RS_Order')->where("token='%s' and OrderId='%s'",array($this->token,$OrderId))->find();
		M()->startTrans();
		//撤销Status=11
		$ores=M()->table('RS_Order')->where("token='%s' and OrderId='%s'",array($this->token,$OrderId))->setField('Status','11');
		//非现金支付返还余额/奖励余额  并添加充值记录  备注订单返还，写入订单号
		$mymoney=$givemymoney=$memberScore=$srecord=$myCp=$myCps=$less=$lesscash=$olres=true;
		if ($Oinfo['PayName']=='YE') {
			$mymoney=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$Oinfo['MemberId']))->setInc('Money',floatval($Oinfo['Price'])); //返还余额
			$tempDB['MemberId']=$Oinfo['MemberId'];
			$tempDB['OrderId']=$OrderId;
			$tempDB['Money']=floatval($Oinfo['Price']);
			$tempDB['Date']=date('Y-m-d H:i:s',time());
			$tempDB['token']=$this->token;
			$givemymoney=M()->table('RS_MemberCash')->add($tempDB); //插入充值记录
		}elseif ($Oinfo['PayName']=='JL') {
			$mymoney=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$Oinfo['MemberId']))->setInc('VirtualMoney',floatval($Oinfo['Price'])); //返还奖励余额
			$tempDB['MemberId']=$Oinfo['MemberId'];
			$tempDB['OrderId']=$OrderId;
			$tempDB['Reward']=floatval($Oinfo['Price']);
			$tempDB['Date']=date('Y-m-d H:i:s',time());
			$tempDB['token']=$this->token;
			$givemymoney=M()->table('RS_MemberCash')->add($tempDB);  //插入充值记录
		}
		//有几分记录返还积分 做积分扣减记录
		$myscore=M()->table('RS_IntegralDetail')->where("token='%s' and MemberId='%s' and Remarks='%s' and Type='%s'",array($this->token,$Oinfo['MemberId'],$OrderId,'cons'))->getField('Integral'); //查询积分信息
		if ($myscore) {
			$memberScore=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$Oinfo['MemberId']))->setDec('Integral',$myscore); //扣减对应积分
			$tempSDB['MemberId']=$Oinfo['MemberId'];
			$tempSDB['Integral']=-floatval($myscore);
			$tempSDB['Type']='cons';
			$tempSDB['Remarks']=$OrderId;
			$tempSDB['token']=$this->token;
			$srecord=M()->table('RS_IntegralDetail')->add($tempSDB);  //插入积分扣减记录
		}
		//使用优惠券/赠送优惠券做对应返还 赠送优惠券已使用不返还
		if ($Oinfo['CouponListId']!='0') {
			$myCp=M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$Oinfo['MemberId'],$Oinfo['CouponListId']))->setInc('CouponCount',1); //返还使用的优惠券
			// var_dump(M()->getlastsql());M()->rollback();exit();
		}
		if ($Oinfo['getcoupon']) {
			if (M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$Oinfo['MemberId'],$Oinfo['getcoupon']))->getField('CouponCount')>0) {
				$myCps=M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$Oinfo['MemberId'],$Oinfo['getcoupon']))->setDec('CouponCount',1);
				//扣减赠送的优惠券
			}
		}
		//有对应提成记录返还提成
		$getcash=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$Oinfo['MemberId'],$OrderId))->find();
		if ($getcash) {
			if ($getcash['Type']=='U') {
				$less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$Oinfo['MemberId'],$OrderId))->delete();
			}elseif ($getcash['Type']=='TQ') {
				$less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$Oinfo['MemberId'],$OrderId))->delete();
				$lesscash=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$getcash['FromMemberId']))->setDec('CutMoney',$getcash['Money']);
			}else{
				$less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$Oinfo['MemberId'],$OrderId))->delete();
			}
		}
		//恢复库存
		$OrderList=M()->table('RS_OrderList')->where("token='%s' and OrderId='%s'",array($this->token,$OrderId))->select();
        $tb_name='tb_wh'.substr($this->token, -8,8); //仓库表名
		foreach ($OrderList as $list) {
			if (!$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount+".$list['Count'].",VirtualCount=VirtualCount+".$list['Count'].",SalesCount=SalesCount-".$list['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$list['ProIdCard']."'")) {
				$olres=false;
				break;
			}
		}
		//余额/奖励余额返还、充值记录写入，使用的优惠券返还
		//最终判断
		if ($mymoney && $givemymoney && $memberScore && $srecord && $myCp && $myCps && $less!==false && $lesscash && $olres) {
			//输出成功
			$this->success('撤销成功');
			M()->commit();
		}else{
			$this->LOGS('订单撤销失败:mymoney:'.$mymoney.'..givemymoney:'.$givemymoney.'..memberScore:'.$memberScore.'..srecord:'.$srecord.'..myCp:'.$myCp.'..myCps:'.$myCps.'..less:'.$less.'..lesscash:'.$lesscash.'..olres:'.$olres);
			//输出失败
			$this->error('撤销失败');
			M()->rollback();
		}
	}

	/**
	 * 微信刷卡支付
	 */
	public function wecode(){
		// var_dump($_POST);exit();
		import('Vendor.WXPAY.Simple');
		$auth_code=$_POST['auth_code'];
		$oid=$_POST['OrderId'];
		$oinfo=M()->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		if ($oinfo['Status']!='1') {
			$this->error('该订单已支付');
		}else{
			$wxpayinfo=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find();
			$storename=$this->MSL('merchant')->where("id=%d and token='%s'",array(session('userinfo')['userShop'],$this->token))->getField('storeName');
			$Sml = new \MicroPay($wxpayinfo);
			$input->out_trade_no=$oid;
			$input->auth_code=$auth_code;
			$input->total_fee=$oinfo['Price']*100;
			$input->body=$storename?$storename:'购买商品';
			$res=$Sml->pay($input);
			// var_dump($res);
			if ($res['status']==true) {
				if (M()->table('RS_Order')->where("OrderId='%s'",$oid)->setField('Status',4)) {
					$res['status']='true';
				}else{
					$res['status']='false';
					$res['info']='支付成功，订单处理失败';
				}
			}else{
				$res['status']='false';
				$res['info']=$res['info'];
			}
			echo json_encode($res);

		}
	}


}
