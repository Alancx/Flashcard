<?php
namespace Home\Controller;
class AccountController extends BaseController 
{
	public function Register()
	{

	}

  	///关注公众号/////
	public function gzggh()
	{
		$posturl= array(
			'http' => array(
				'method' => 'POST',
				 'content' =>'{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "100"}}}',
			),
		);
		$acctoken=$this->get_access_token();
		$GZurl=file_get_contents("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$acctoken."",false,stream_context_create($posturl));
		$GZurl=json_decode($GZurl,true);
		$this->isgzgz="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$GZurl['ticket']."";
		$this->assign('qdgzurl',$this->isgzgz);
		$this->assign('searchSign',0);
		$this->assign('footerSign',0);
		$this->assign('Title','关注公众号');
		$this->display();
	}

	//登录
	public function Login()
	{

	}

    //统一处理扫码事件
    public function QrCodeHandle()
    {
    	//URL格式
    	//http://www.xxx.com:8082/index.php/Home/Account/QrCodeHandle/+参数
    	$type=$_GET['tp'];
    	switch ($type) {
    		case '1':
    			//自提货
    			$this->ztGoods($_GET);
    			break;
    		case '2':
    			//现金支付
    			$this->xjPay($_GET);
    			break;
    		case '3':
    			//商品扫码添加
    			$this->cartByQrode($_GET);
    			break;
    		case '4':
    			//核销员添加
    			$this->addCheckUser($_GET);
    			break;
    		case '5':
    			//优惠券获取
    			$this->getCoupon($_GET);
    			break;
    		case '6':
    			//优惠券获取
    			$this->tkByQrode($_GET);
    			break;
    		case '7':
    			//扫码核销积分购买
    			$this->ztjfGoods($_GET);
    				break;
    		case '8':
    			//大礼包提取物品
    			$this->userGift($_GET);
    				break;
    		default:
    			$this->AlertPage('未授权的二维码');
    			break;
    	}
    }

    public function getCoupon($data)
    {
    	$this->redirect('User/getCoupon');
    }

    public function testx()
    {
    	$mInfo=$this->BM('cancel')->where(array('openid'=>'onsPjv_pgLLi7aiZyoyZycp7BZg0','token'=>$this->webParam['token']))->find();
		$xxxx=json_decode(stripslashes($mInfo['type']),true);
    	var_dump($xxxx);
    }

    public function ztGoods($data)
    {


		$whereOrder['o.OrderId']=$data['oid'];
    	$whereOrder['o.token']=$this->webParam['token'];
    	$whereOrder['o.Status']=2;
    	//$whereOrder['o.RecevingName']=$mInfo['storeid'];

		$ol=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."Order o")->join(C('DB_BASE')['DB_PREFIX']."OrderList ol ON o.OrderId=ol.OrderId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=ol.ProId","LEFT")->where($whereOrder)->field("o.OrderId AS oid,o.RecevingName AS rname,o.RecevingPost AS stype,o.Count AS acount,o.Price AS aprice,o.Freight AS freight,o.PayName AS ptype,o.IsEvaluation AS isevaluation,o.Status AS status,o.CreateDate AS cdate,ol.ProIdCard AS plid,ol.Price AS plprice,ol.Count AS plcount,ol.Money AS plmoney,ol.Spec AS spec,p.ProName AS pname,p.ProLogoImg AS pimg,o.RecevingName AS thstoreid")->order("cdate desc,oid desc")->select();

		if ($ol) {
			$mInfo=$this->BM('cancel')->where(array('openid'=>$this->webParam['openid'],'token'=>$this->webParam['token'],'storeid'=>$ol[0]['thstoreid']))->find();
			if ($mInfo) {
				$checktype=json_decode(stripslashes($mInfo['type']),true);

				if (in_array('TH',$checktype)) {
		    		$this->assign('ol',$ol);
		    		$this->assign('mid',$mInfo['storeid']);
		    		$this->assign('backpageurl',$this->webParam['backpageurl']);
		    		$this->assign('Title','商品自提');
		    		$this->display('Order/ztOrder');
				}
				else
				{
					$this->AlertPage('无提货核销操作权限');
				}
			}
			else
			{
				$this->AlertPage('您不是认证过的审核员');
			}
		}
		else
		{
			$this->AlertPage('未找到订单');
		}

    }

    //现金支付
    public function xjPay()
    {

		$whereOrder['o.OrderId']=$_GET['id'];
    	$whereOrder['o.token']=$this->webParam['token'];
    	$whereOrder['o.Status']=1;
    	//$whereOrder['o.xjstore']=$mInfo['storeid'];

    	$ol=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."Order o")->join(C('DB_BASE')['DB_PREFIX']."OrderList ol ON o.OrderId=ol.OrderId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=ol.ProId","LEFT")->where($whereOrder)->field("o.OrderId AS oid,o.RecevingName AS rname,o.RecevingPost AS stype,o.Count AS acount,o.Price AS aprice,o.Freight AS freight,o.PayName AS ptype,o.IsEvaluation AS isevaluation,o.Status AS status,o.CreateDate AS cdate,ol.ProIdCard AS plid,ol.Price AS plprice,ol.Count AS plcount,ol.Money AS plmoney,ol.Spec AS spec,p.ProName AS pname,p.ProLogoImg AS pimg,o.xjstore AS xjstore")->order("cdate desc,oid desc")->select();

    	if ($ol) {
    		$mInfo=$this->BM('cancel')->where(array('openid'=>$this->webParam['openid'],'token'=>$this->webParam['token'],'stoken'=>$ol[0]['xjstore']))->find();

    		if ($mInfo) {
    			$checktype=json_decode(stripslashes($mInfo['type']),true);
    			if (in_array('XJ',$checktype)){
		    		$this->assign('ol',$ol);
		    		$this->assign('mid',$mInfo['storeid']);
		    		$this->assign('backpageurl',$this->webParam['backpageurl']);
		    		$this->assign('Title','订单现金支付');
		    		$this->display('Payment/xjPay');
    			}
    			else
    			{
    				$this->AlertPage('无现金核销操作权限');
    			}
    		}
    		else
    		{
    			$this->AlertPage('您不是认证过的审核员');
    		}
    	}
    	else
    	{
    		$this->AlertPage('未找到订单');
    	}

    }

    public function xjPayPost()
    {
    	$data=$_POST;

    	$orderStatus=$this->BM('order')->where(array('OrderId'=>$data['oid']))->find();

    	if ($orderStatus['Status']!='1')
    	{
            $this->AlertPage('该订单已核销，请勿重复操作',2,'错误',false,$_POST['backPage']);
    		return;
    	}
      else
      {

          $nowtime=$this->nowTimeParam['datetime'];

          $publicClass=A('Public');

          $res=$publicClass->paySuccess($data['oid'],'XJ',array('Status'=>4,'GetDate'=>$nowtime));

          if ($res['res']) 
          {
              if ($res['code']=='0') 
              {
                  $this->AlertPage('交易成功');
              }
              else
              {

              }
          }
          else
          {
              $this->AlertPage('核销失败',2,'错误',false,$_POST['backPage']);
          }

      }

        
   //  	if ($data['isSend']=="0")
   //  	{
   //  		//收款不发货
   //  		$orderData["Status"]=2;
			// $orderData['LastUpdateDate']=$nowtime;
			// $orderData['PayDate']=$nowtime;
			// $orderData['Cantime']=$nowtime;
			// $orderData['CanType']='pay';
			// $orderData['XJname']=$this->webParam['openid'];
			// $orderData['XJscantime']=$nowtime;
			// $orderMaps=array("OrderId"=>$data['oid']);
			// // 更新订单主表状态


			// $this->BM()->startTrans();

			// $ordermaps=$this->BM('order')->where($orderMaps)->setField($orderData);


			// if ($ordermaps) 
			// {
			// 	$orderlist=$this->BM('orderlist')->where(array('OrderId'=>$data['oid']))->select();

   //              // 获取订单的三级提成信息
   //              foreach($orderlist as $key => $value)
   //              {

   //                  if ($this->BM('productlist')->where(array('ProIdCard'=>$value['ProIdCard']))->setDec('Count',$value['Count'])) 
   //                  {
   //                  	$ordermaps=true;
   //                  }
   //                  else
   //                  {
			// 			$ordermaps=false;
			// 			break;
   //                  }

   //              }
			// }
			// else
			// {
				
			// }


			// if ($ordermaps) 
			// {

			// 	$this->BM()->commit();
			// 	if ($orderStatus['RecevingPost']=='ZT') 
			// 	{
			// 		$this->AlertPage('付款成功，请到指定门店提取商品');
			// 	}
			// 	else
			// 	{
			// 		$this->AlertPage('付款成功，商品近日将通过物流发送');
			// 	}
			// }
			// else
			// {
			// 	$this->BM()->rollback();
			// 	$this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
			// }


   //  	}
   //  	else
   //  	{
			// $addInfo=$this->BM('store')->where(array('id'=>$data["mid"]))->find();
   //  		//收款并发货


			// 	// 确认收货
   //              $orderWhereStr=array("OrderId"=>$data['oid']);
   //              //$orderWhereStr['Status']=3;

   //              $ordermaps=$this->BM('order')->where($orderWhereStr)->find();

   //              if ($ordermaps) {
   //                  # code...
   //              }
   //              else
   //              {
   //                  $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                  return;
   //              }

			// 	$stokenvar=$ordermaps['stoken'];


			// 	$storeinfo=$this->BM('store')->where(array('token'=>$this->webParam['token'],'stoken'=>$stokenvar))->find();

			// 	if ($storeinfo) {
			// 		$stokenvar='wh'.substr($this->webParam['token'],-8).'_'.$store['id'];
			// 	}
			// 	else
			// 	{
			// 		$stokenvar='wh'.substr($this->webParam['token'],-8);
			// 	}

			// 	$this->BM()->startTrans();

   //              // 获取订单子表
   //              $orderlist=$this->BM('orderlist')->where(array('OrderId'=>$data['oid']))->select();

   //              $moneys = array(0,0,0);
			// 	$tgMoney=0;
   //              // 获取订单的三级提成信息
   //              foreach($orderlist as $key => $value)
   //              {
   //                  $moneys[0]+=floatval($value['Money'])*floatval($value['Cut'])/100;
   //                  $moneys[1]+=floatval($value['Money'])*floatval($value['Cut2'])/100;
   //                  $moneys[2]+=floatval($value['Money'])*floatval($value['Cut3'])/100;
   //                  $tgMoney+=floatval($value['ExtendCut']);

   //                  if ($this->BM('productlist')->where(array('ProIdCard'=>$value['ProIdCard']))->setDec('Count',$value['Count'])) {
   //                  	//var_dump('expression');
   //                  }
   //                  else
   //                  {
   //                  	$this->BM()->rollback();
   //                  	$this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                  }

   //              }

   //              // 计算收益提成等级 1,2,3
   //              // $tichengGrade=C('tichengGrade');

   //              $mem=$this->BM('member')->where(array("MemberId"=>$ordermaps['MemberId'],'token'=>$this->webParam['token']))->find();

   //              $memberData['OrderMoney']=floatval($mem['OrderMoney'])+floatval($ordermaps['Price'])-floatval($ordermaps['Freight']);

   //              $memberData['Cut']=floatval($mem['Cut'])+$moneys[0];
   //              $memberData['Cut2']=floatval($mem['Cut2'])+$moneys[1];
   //              $memberData['Cut3']=floatval($mem['Cut3'])+$moneys[2];
   //              $memberData['LastUpdateDate']=$nowtime;

                
   //              $res=true;

   //              // 上1级推广人 金额设置
   //              if(!empty($mem['SceneMember']))
   //              {

   //                  $res=$this->BM()->execute("update rs_member set cutmoney=cutmoney+{$moneys[0]},cuttotalmoney=cuttotalmoney+{$moneys[0]},LastUpdateDate='{$nowtime}' where memberid='{$mem["SceneMember"]}'");

   //                  if ($res)
   //                  {
   //                      $comm1["MemberId"]=$ordermaps['MemberId'];
   //                      $comm1["OrderId"]=$data['oid'];
   //                      $comm1["CreateDate"]=$nowtime;
   //                      $comm1["Type"]="U";
   //                      $comm1["Remarks"]="";
   //                      $comm1["FromMemberId"]=$mem['SceneMember'];
   //                      $comm1["FromMemberGrade"]=1;
   //                      $comm1["Money"]=$moneys[0];
   //                      $comm1['token']=$this->webParam['token'];
   //                      $res=$this->BM('membercommission')->add($comm1);
   //                      if ($res) {

   //                      }
   //                      else
   //                      {
   //                          $this->BM()->rollback();
   //                          $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                      }
   //                  }
   //                  else
   //                  {
   //                      $this->BM()->rollback();
   //                      $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                  }
   //              }
   //              // 上2级推广人 金额设置
   //              if(!empty($mem['TcMember2']))
   //              {
   //                  $res=$this->BM()->execute("update rs_member set cutmoney=cutmoney+{$moneys[1]},cuttotalmoney=cuttotalmoney+{$moneys[1]},LastUpdateDate='{$nowtime}' where memberid='{$mem["TcMember2"]}'");
   //                  if ($res)
   //                  {
   //                      $comm2["MemberId"]=$ordermaps['MemberId'];
   //                      $comm2["OrderId"]=$data['oid'];
   //                      $comm2["CreateDate"]=$nowtime;
   //                      $comm2["Type"]="U";
   //                      $comm2["Remarks"]="";
   //                      $comm2["FromMemberId"]=$mem['TcMember2'];
   //                      $comm2["FromMemberGrade"]=2;
   //                      $comm2["Money"]=$moneys[1];
   //                      $comm2['token']=$this->webParam['token'];
   //                      $res=$this->BM('membercommission')->add($comm2);
   //                      if ($res) {
   //                          # code...
   //                      }
   //                      else
   //                      {
   //                          $this->BM()->rollback();
   //                          $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                      }
   //                  }
   //                  else
   //                  {
   //                      $this->BM()->rollback();
   //                      $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                  }

   //              }
   //              // 上3级推广人 金额设置
   //              if(!empty($mem['TcMember3']))
   //              {
   //                  $res=$this->BM()->execute("update rs_member set cutmoney=cutmoney+{$moneys[2]},cuttotalmoney=cuttotalmoney+{$moneys[2]},LastUpdateDate='{$nowtime}' where memberid='{$mem["TcMember3"]}'");
   //                  if ($res) {
   //                      $comm3["MemberId"]=$ordermaps['MemberId'];
   //                      $comm3["OrderId"]=$data['oid'];
   //                      $comm3["CreateDate"]=$nowtime;
   //                      $comm3["Type"]="";
   //                      $comm3["Remarks"]="U";
   //                      $comm3["FromMemberId"]=$mem['TcMember3'];
   //                      $comm3["FromMemberGrade"]=3;
   //                      $comm3["Money"]=$moneys[2];
   //                      $comm3['token']=$this->webParam['token'];
   //                      $res=$this->BM('membercommission')->add($comm3);
   //                      if ($res) {
   //                          # code...
   //                      }
   //                      else
   //                      {
   //                          $this->BM()->rollback();
   //                          $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                      }
   //                  }
   //                  else
   //                  {
   //                      $this->BM()->rollback();
   //                      $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                  }
   //              }

			// 	if ($ordermaps['CouponListId']!='0')
			// 	{
			// 		$res=$this->BM('membercoupon')->where(array('CouponId'=>$ordermaps['CouponListId'],'MemberId'=>$ordermaps['MemberId'],'token'=>$this->webParam['token']))->setDec('CouponCount',1);
			// 	}

   //              if ($res)
   //              {
   //                  $res=$this->BM('member')->where(array("MemberId"=>$ordermaps['MemberId'],'token'=>$this->webParam['token']))->setField($memberData);

   //                  if($res)
   //                  {
   //                      // 更新订单主表状态
   //                      $orderData["Status"]=4;
			// 			$orderData['PayDate']=$nowtime;
			// 			$orderData['GetDate']=$nowtime;
			// 			$orderData['LastUpdateDate']=$nowtime;
			// 			$orderData['Cantime']=$nowtime;
			// 			$orderData['CanType']='get';
			// 			$orderData['ZTname']=$this->webParam['openid'];
			//             $orderData["RecevingName"]=$addInfo["id"];
			//             $orderData["RecevingArea"]=$addInfo["area"];
			//             $orderData["RecevingCity"]=$addInfo["city"];
			//             $orderData["RecevingProvince"]=$addInfo["province"];
			//             $orderData["RecevingAddress"]=$addInfo["addr"];
			//             $orderData["RecevingPost"]='ZT';
			//             $orderData["RecevingPhone"]=$addInfo["tel"].'';
   //                      $res=$this->BM('order')->where($orderWhereStr)->setField($orderData);

   //                      if ($res)
   //                      {
   //                          $this->BM()->commit();
   //                           $this->AlertPage('付款并提货成功',1);
   //                      }
   //                      else
   //                      {
   //                          $this->BM()->rollback();
   //                          $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                      }
   //                  }
   //                  else
   //                  {
   //                      $this-BM()->rollback();
   //                      $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //                  }
   //              }
   //              else
   //              {
   //                  $this->BM()->rollback();
   //                  $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
   //              }

   //  	}
    }

    //扫码加入购物车  tp=3
    public function cartByQrode($data)
    {
    	//URL格式
    	//http://www.xxx.com:8082/index.php/Home/Account/QrCodeHandle/tp/3/gid/pro5884326833_1045/zp/1.html
    	//zp 赠品标识
    	$zpSign='0';

    	$gInfowhereArray=array('ProIdCard'=>$data['gid']);

    	if ($data['zp']=='1')
    	{
    		$zpSign='1';
    		$gInfowhereArray['Iszp']='1';
    	}

        $gInfo=$this->BM('productlist')->where($gInfowhereArray)->find();

        if ($gInfo)
        {
        	$GoodsInfo['id']=$gInfo['ProId'];
        	$GoodsInfo['attr']=str_replace($gInfo['ProId'],'',$gInfo['ProIdCard']);
        	$GoodsInfo['nums']=1;
        	$GoodsInfo['zp']=$zpSign;
        	//$CartStr=cookie('user_Cart');

        	//$CartInfo=$this->BM('cart')->where(array('token'=>$this->webParam['token'],'openid'=>$this->webParam['openid']))->find();
            $CartStr=$this->readCart('all');
            $CartCookieArray=array();

            if(empty($CartStr)||$CartStr=='NULLVALUE'||$CartStr=='NULL')
            {

            	if ($zpSign=='1') {
            		$CartCookieArray['zpGoods']=
	            	array(
	            		$GoodsInfo['id'].$GoodsInfo['attr']=>
		            		array(
			            		'id'=>$GoodsInfo['id'],
			            		'attr'=>$GoodsInfo['attr'],
			            		'nums'=>$GoodsInfo['nums'],
			            		'zp'=>$GoodsInfo['zp'],
	            			)
		            	);
            	}
            	else
            	{
	            	$CartCookieArray['Goods']=
	            	array(
	            		$GoodsInfo['id'].$GoodsInfo['attr']=>
		            		array(
			            		'id'=>$GoodsInfo['id'],
			            		'attr'=>$GoodsInfo['attr'],
			            		'nums'=>$GoodsInfo['nums'],
			            		'zp'=>$GoodsInfo['zp'],
	            			)
		            	);
            	}

                //$CartCookieStr='{"Goods":{"'.$GoodsInfo['id'].$GoodsInfo['attr'].'":{"id":"'.$GoodsInfo['id'].'","attr":"'.$GoodsInfo['attr'].'","nums":'.$GoodsInfo['nums'].'}}}';
            }
            else
            {
                $CartCookieArray=json_decode($CartStr,true);

                $AddData['id']=$GoodsInfo['id'];
                $AddData['attr']=$GoodsInfo['attr'];
                $AddData['nums']=$GoodsInfo['nums'];
                $AddData['zp']=$GoodsInfo['zp'];

                if ($zpSign=='1')
                {
	                if (count($CartCookieArray['zpGoods'])>0)
	                {
	                    if (empty($CartCookieArray['zpGoods'][$AddData["id"].$AddData['attr']]))
	                    {
	                    	$CartCookieArray['zpGoods'][$AddData["id"].$AddData['attr']]=$AddData;
	                        //$CartCookieStr.='"'.$AddData['id'].$AddData['attr'].'":{"id":"'.$AddData['id'].'","attr":"'.$AddData['attr'].'","nums":'.$AddData['nums'].'},';
	                    }
	                    else
	                    {
	                    	$CartCookieArray['zpGoods'][$AddData["id"].$AddData['attr']]['nums']=($CartCookieArray['zpGoods'][$AddData["id"].$AddData['attr']]['nums']+$AddData["nums"]);
	                        //$CartCookieStr.='"'.$AddData['id'].$AddData['attr'].'":{"id":"'.$AddData['id'].'","attr":"'.$AddData['attr'].'","nums":'.($CartCookieArray['Goods'][$AddData["id"].$AddData['attr']]['nums']+$AddData["nums"]).'},';
	                    }
	                }
	                else
	                {
		            	$CartCookieArray['zpGoods']=array(
		            		$AddData['id'].$AddData['attr']=>
			            		array(
				            		'id'=>$AddData['id'],
				            		'attr'=>$AddData['attr'],
				            		'nums'=>$AddData['nums'],
				            		'zp'=>$AddData['zp'],
		            			)
			            	);
	                    //$CartCookieStr.='"'.$AddData['id'].$AddData['attr'].'":{"id":"'.$AddData['id'].'","attr":"'.$AddData['attr'].'","nums":'.$AddData['nums'].'},';
	                }
                }
                else
                {
	                if (count($CartCookieArray['Goods'])>0)
	                {
	                    if (empty($CartCookieArray['Goods'][$AddData["id"].$AddData['attr']]))
	                    {
	                    	$CartCookieArray['Goods'][$AddData["id"].$AddData['attr']]=$AddData;
	                        //$CartCookieStr.='"'.$AddData['id'].$AddData['attr'].'":{"id":"'.$AddData['id'].'","attr":"'.$AddData['attr'].'","nums":'.$AddData['nums'].'},';


	                    }
	                    else
	                    {
	                    	$CartCookieArray['Goods'][$AddData["id"].$AddData['attr']]['nums']=($CartCookieArray['Goods'][$AddData["id"].$AddData['attr']]['nums']+$AddData["nums"]);
	                        //$CartCookieStr.='"'.$AddData['id'].$AddData['attr'].'":{"id":"'.$AddData['id'].'","attr":"'.$AddData['attr'].'","nums":'.($CartCookieArray['Goods'][$AddData["id"].$AddData['attr']]['nums']+$AddData["nums"]).'},';
	                    }
	                }
	                else
	                {
		            	$CartCookieArray['Goods']=array(
		            		$AddData['id'].$AddData['attr']=>
			            		array(
				            		'id'=>$AddData['id'],
				            		'attr'=>$AddData['attr'],
				            		'nums'=>$AddData['nums'],
				            		'zp'=>$AddData['zp'],
		            			)
			            	);
	                    //$CartCookieStr.='"'.$AddData['id'].$AddData['attr'].'":{"id":"'.$AddData['id'].'","attr":"'.$AddData['attr'].'","nums":'.$AddData['nums'].'},';
	                }
                }




                //$CartCookieStr='{"Goods":{'.substr($CartCookieStr,0,-1).'}}';
            }

                if ($this->saveCart($CartCookieArray)) {
                    $this->AlertPage("添加到购物车成功",1,"消息",true,'Home/Order/Cart');
                }
                else
                {
                    $this->AlertPage("添加到购物车失败",1,"失败",true,'Home/Order/Cart');
                }
            //$this->saveCooies('Cart',$CartCookieStr);



            //$this->AlertPage("添加到购物车成功","消息",true,'Home/Order/Cart');
        }
        else
        {
        	$this->AlertPage("未查找到商品");
        }
    }

    //添加核销员 tp=4
    public function addCheckUser($data=null)
    {
	    	$mInfo=$this->BM('store')->where(array('id'=>$data['id'],'token'=>$this->webParam['token']))->find();
	    	if($mInfo)
	    	{
				$this->assign('type',$data['type']);
	    		$this->assign('backpageurl',$this->webParam['backpageurl']);
	    		$this->assign('minfo',$mInfo);
	    		$this->assign('Title','添加核销员');
	    		$this->display('Account/addCheckPeople');
	    	}
	    	else
	    	{
	    		$this->AlertPage('门店不存在,请联系总部确认');
	    	}
    }

    public function tkByQrode($data)
    {
    	$mInfo=$this->BM('cancel')->where(array('openid'=>$this->webParam['openid'],'token'=>$this->webParam['token']))->find();
    	if ($mInfo)
    	{
	    	$whereOrder="o.OrderId='".$data['oid']."' AND o.token='".$this->webParam['token']."' AND ((o.Status=4) OR (o.Status=2 AND o.PayName='XJ'))";
	    	$ol=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."Order o")->join(C('DB_BASE')['DB_PREFIX']."OrderList ol ON o.OrderId=ol.OrderId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=ol.ProId","LEFT")->where($whereOrder)->field("o.OrderId AS oid,o.RecevingName AS rname,o.RecevingPost AS stype,o.Count AS acount,o.Price AS aprice,o.Freight AS freight,o.PayName AS ptype,o.IsEvaluation AS isevaluation,o.Status AS status,o.CreateDate AS cdate,ol.ProIdCard AS plid,ol.Price AS plprice,ol.Count AS plcount,ol.Money AS plmoney,ol.Spec AS spec,p.ProName AS pname,p.ProLogoImg AS pimg")->order("cdate desc,oid desc")->select();

	    	if ($ol) {
	    		$this->assign('ol',$ol);
	    		$this->assign('mid',$mInfo['storeid']);
	    		$this->assign('backpageurl',$this->webParam['backpageurl']);
	    		$this->assign('Title','订单退款');
	    		$this->display('Order/tkOrder');
	    	}
	    	else
	    	{
	    		$this->AlertPage('未找到订单');
	    	}
    	}
    	else
    	{
    		$this->AlertPage('您不是认证过的审核员');
    	}
    }

    public function tkPost()
    {
//$tcInfo=$this->BM('membercommission')->
    	$data=$_POST;
    	$nowTime=$this->nowTimeParam['datetime'];



    	$whereOrder="OrderId='".$data['oid']."' AND token='".$this->webParam['token']."' AND ((Status=4 AND GETDATE()<=DATEADD(DAY,".$this->webParam['tkinterval'].",LastUpdateDate)) OR (Status=2 AND PayName='XJ'))";

    	$orderInfo=$this->BM('order')->where($whereOrder)->find();




		$stokenvar=$orderInfo['stoken'];


		$storeinfo=$this->BM('store')->where(array('token'=>$this->webParam['token'],'stoken'=>$stokenvar))->find();

		if ($stokenvar) {
			$stokenvar='wh'.substr($this->webParam['token'],-8).'_'.$store['id'];
		}
		else
		{
			$stokenvar='wh'.substr($this->webParam['token'],-8);
		}

		$this->WM()->startTrans();

        // 获取订单子表
        $orderlist=$this->BM('orderlist')->where(array('OrderId'=>$orderInfo['OrderId']))->select();


        foreach($orderlist as $key => $value)
        {
            if ($this->WM($stokenvar)->where(array('ProIdCard'=>$value['ProIdCard']))->setInc('StockCount',$value['Count'])) {
            	# code...
            }
            else
            {
            	$this->WM()->rollback();
            	$this->AlertPage('删除提成信息出错',2,'错误',false,$_POST['backPage']);
            }
        }





    	if ($orderInfo)
    	{
    		$this->BM()->startTrans();
    		$res=true;
	    	$tcInfo=$this->BM('membercommission')->where(array('OrderId'=>$data['oid'],'token'=>$this->webParam['token']))->select();
	    	if ($tcInfo)
	    	{
	    		foreach ($tcInfo as $key => $value)
	    		{

					$res=$this->BM()->execute("update rs_member set cutmoney=cutmoney-".$value['Money'].",cuttotalmoney=cuttotalmoney-".$value['Money'].",LastUpdateDate='".$nowtime."' where memberid='".$value['FromMemberId']."' AND token='".$this->webParam['token']."'");
					if ($res) {
						# code...
					}
					else
					{
						$this->BM()->rollback();
						$this->WM()->rollback();
						$this->AlertPage('删除提成信息出错',2,'错误',false,$_POST['backPage']);
					}
	    		}
	    		$res=$this->BM('membercommission')->where(array('OrderId'=>$data['oid'],'token'=>$this->webParam['token']))->setField(array('Type'=>'TK'));

	    	}

	    	$rewordCoupon=$this->BM('membercoupon')->where(array('MemberId'=>$orderInfo['MemberId'],'CouponId'=>$orderInfo['getcoupon'],'token'=>$this->webParam['token']))->find();

	    	if ($rewordCoupon) {
	    		if ($rewordCoupon['CouponCount']>0) {
	    			$res=$this->BM('membercoupon')->where(array('MemberId'=>$orderInfo['MemberId'],'CouponId'=>$orderInfo['getcoupon'],'token'=>$this->webParam['token']))->setDec('CouponCount',1);
	    		}
	    	}

    		if ($res)
    		{
    			$dt = array(
		            'BackMoneyOkDate' =>$nowTime,
		            'Status'=>8,
		            'LastUpdateDate'=>$nowTime
		        );
    			if ($this->BM("order")->where($whereOrder)->save($dt))
    			{
    				$this->BM()->commit();
    				$this->WM()->commit();
				    $this->AlertPage('退款成功',1);
				}
				else
				{
					$this->BM()->rollback();
					$this->WM()->rollback();
				    $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
				}
    		}
    		else
    		{
    			$this->BM()->rollback();
    			$this->WM()->rollback();
				$this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
    		}
    	}
    	else
    	{
    		$this->AlertPage('找不到订单',2,'错误',false,$_POST['backPage']);
    	}
    	         //    $comm3["MemberId"]=$this->webParam['uid'];
                 //    $comm3["OrderId"]=$orderid;
                 //    $comm3["CreateDate"]=$nowtime;
                 //    $comm3["Type"]="TK";
                 //    $comm3["Remarks"]="";
                 //    $comm3["FromMemberId"]=$mem['TcMember3'];
                 //    $comm3["FromMemberGrade"]=3;
                 //    $comm3["Money"]=$moneys[2];tkInterval
                 //    $comm3['token']=$this->webParam['token'];

    }

    public function ztjfGoods($data)
    {



		$whereOrder['o.OrderId']=$data['oid'];
    	$whereOrder['o.token']=$this->webParam['token'];
    	$whereOrder['o.Status']=2;
    	//$whereOrder['o.RecevingName']=$mInfo['storeid'];

		$ol=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."ScoreOrder o")->join(C('DB_BASE')['DB_PREFIX']."ScoreOrderList ol ON o.OrderId=ol.OrderId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=ol.ProId","LEFT")->where($whereOrder)->field("o.OrderId AS oid,o.RecevingName AS rname,o.RecevingPost AS stype,o.Count AS acount,o.Price AS aprice,o.Freight AS freight,o.PayName AS ptype,o.IsEvaluation AS isevaluation,o.Status AS status,o.CreateDate AS cdate,ol.ProIdCard AS plid,ol.Price AS plprice,ol.Count AS plcount,ol.Money AS plmoney,ol.Spec AS spec,p.ProName AS pname,p.ProLogoImg AS pimg,o.RecevingName AS ztstoreid")->order("cdate desc,oid desc")->select();

		if ($ol) {
			$mInfo=$this->BM('cancel')->where(array('openid'=>$this->webParam['openid'],'token'=>$this->webParam['token'],'storeid'=>$ol[0]['ztstoreid']))->find();
			if ($mInfo) {
				$checktype=json_decode(stripslashes($mInfo['type']),true);
				if (in_array('TH',$checktype))
				{
		    		$this->assign('ol',$ol);
		    		$this->assign('mid',$mInfo['storeid']);
		    		$this->assign('backpageurl',$this->webParam['backpageurl']);
		    		$this->assign('Title','积分商品自提');
		    		$this->display('Score/ztOrder');
				}
				else
				{
					$this->AlertPage('无提货核销操作权限');
				}
			}
			else
			{
				$this->AlertPage('您不是认证过的审核员');
			}
		}
		else
		{
			$this->AlertPage('未找到订单');
		}
    }

    public function jfPayPost()
    {
    	$data=$_POST;

    	$orderStatus=$this->BM('scoreorder')->where(array('OrderId'=>$data['oid']))->find();
    	if ($orderStatus['Status']!=$data['reStatus'])
    	{
    		$this->AlertPage('该订单已核销，请勿重复操作');
    		return;
    	}

    	$nowtime=$this->nowTimeParam['datetime'];

		$addInfo=$this->BM('store')->where(array('id'=>$data["mid"]))->find();
    	//收款并发货

		// 确认收货
        $orderWhereStr=array("OrderId"=>$data['oid']);

        $ordermaps=$this->BM('scoreorder')->where($orderWhereStr)->find();

        if ($ordermaps) {
            # code...
        }
        else
        {
            $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
            return;
        }

        // 更新订单主表状态
        $orderData["Status"]=4;
		$orderData['PayDate']=$nowtime;
		$orderData['GetDate']=$nowtime;
		$orderData['LastUpdateDate']=$nowtime;
		$orderData['Cantime']=$nowtime;
		$orderData['CanType']='get';
		$orderData['ZTname']=$this->webParam['openid'];
        $orderData["RecevingName"]=$addInfo["id"];
        $orderData["RecevingArea"]=$addInfo["area"];
        $orderData["RecevingCity"]=$addInfo["city"];
        $orderData["RecevingProvince"]=$addInfo["province"];
        $orderData["RecevingAddress"]=$addInfo["addr"];
        $orderData["RecevingPost"]='ZT';
        $orderData["RecevingPhone"]=$addInfo["tel"].'';
        $res=$this->BM('scoreorder')->where($orderWhereStr)->setField($orderData);

        if ($res)
        {
             $this->AlertPage('提货成功',1);
        }
        else
        {
            $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
        }
    }

    public function userGift($data)
    {

		$whereOrder['o.OrderId']=$data['id'];
    	$whereOrder['o.token']=$this->webParam['token'];
    	$whereOrder['o.Status']=1;
    	//$whereOrder['o.RecevingName']=$mInfo['storeid'];

		$ginfo=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."GiftOrder o")->join(C('DB_BASE')['DB_PREFIX']."GiftOrderList ol ON o.OrderId=ol.OrderId","LEFT")->join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=ol.ProId","LEFT")->where($whereOrder)->field("o.OrderId AS oid,o.RecevingName AS rname,o.RecevingPost AS stype,o.Count AS acount,o.Price AS aprice,o.Freight AS freight,o.PayName AS ptype,o.IsEvaluation AS isevaluation,o.Status AS status,o.CreateDate AS cdate,ol.ProIdCard AS plid,ol.Price AS plprice,ol.Count AS plcount,ol.Money AS plmoney,ol.Spec AS spec,p.ProName AS pname,p.ProLogoImg AS pimg,o.RecevingName AS thstoreid")->order("cdate desc,oid desc")->select();

		if ($ginfo) {

			$mInfo=$this->BM('cancel')->where(array('openid'=>$this->webParam['openid'],'token'=>$this->webParam['token'],'storeid'=>$ginfo[0]['thstoreid']))->find();
			if ($mInfo) {
				$checktype=json_decode(stripslashes($mInfo['type']),true);

				if (in_array('TH',$checktype))
				{
					$this->assign('ginfo',$ginfo);
					$this->assign('oid',$data['id']);
					$this->assign('backpageurl',$this->webParam['backpageurl']);
					$this->display('Order/giftOrder');
				}
				else
				{
					$this->AlertPage('无提货核销操作权限');
				}
			}
			else
			{
				$this->AlertPage('您不是认证过的审核员');
			}

		}
		else
		{
			$this->AlertPage('未找到礼包商品');
		}

    }

    public function userGiftPost()
    {
		// 确认收货
		$data=$_POST;
		$nowtime=$this->nowTimeParam['datetime'];

        $orderWhereStr=array("OrderId"=>$data['oid'],'Status'=>1);

        $ordermaps=$this->BM('giftorder')->where($orderWhereStr)->find();

        if ($ordermaps) {
            # code...
        }
        else
        {
            $this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
            return;
        }

        // 更新订单主表状态
        $orderData["Status"]=4;
		$orderData['PayDate']=$nowtime;
		$orderData['GetDate']=$nowtime;
		$orderData['LastUpdateDate']=$nowtime;
		$orderData['Cantime']=$nowtime;
		$orderData['CanType']='get';
		$orderData['ZTname']=$this->webParam['openid'];

        $res=$this->BM('giftorder')->where($orderWhereStr)->setField($orderData);
    	if ($res) {
    		$this->AlertPage('核销成功',1);
    	}
    	else
    	{
    		$this->AlertPage('核销失败请重试',2,'错误',false,$_POST['backPage']);
    	}
    }





    public function saveCheckPeople()
    {

    	$minfo=$this->BM('store')->where(array('id'=>$_POST['mid'],'token'=>$this->webParam['token'],'verify'=>$_POST['pwd']))->find();
    	if ($minfo) {


    		$info=array(
	    			'openid'=>$this->webParam['openid'],
	    			'storeid'=>$minfo['id'],
	    			'token'=>$this->webParam['token']
    			);

    		$memberCancel=$this->BM('cancel')->where($info)->find();

    		$res=false;
    		if ($memberCancel) {
				$oldCheckType=json_decode(stripslashes($memberCancel['type']),true);
				if (!in_array($_POST['type'], $oldCheckType)) {  //避免重复添加...
					array_push($oldCheckType,$_POST['type']);
				}

				$res=$this->BM('cancel')->where($info)->setField(array('type'=>json_encode($oldCheckType)));
    		}
    		else
    		{
    			$info['type']=json_encode(array($_POST['type']));
    			$info['username']=$_POST['name'];
    			$res=$this->BM('cancel')->add($info);
    		}

    		if ($res)
    		{
    			$this->AlertPage('添加核销员成功');
    		}
    		else
    		{
    			$this->AlertPage('添加核销员失败',2,'错误',false,$_POST['backPage']);
    		}
    	}
    	else
    	{
    		//失败跳转回去
			$this->AlertPage('添加核销员失败',2,'错误',false,$_POST['backPage']);
    	}

    }

		/////判断是否关注微信公众号///////
		public function iswxgzh(){
			if ($this->webParam['openid']!='NULLVALUE'&&!empty($this->webParam['openid'])) {
				if ($_GET['code']) {
					$acctoken=$this->get_access_token();
					$user=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$acctoken."&openid=".$this->webParam['openid']."&lang=zh_CN");
					$user=json_decode($user,true);
					// var_dump($user['subscribe']);exit;
					if ($user['subscribe']=='1') {
						// return 'true';
						$this->isgzgz='true';
					} else {
						// $posturl= array(
						// 	'http' => array(
						// 		'method' => 'POST',
						// 		 'content' =>'{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "100"}}}',
						// 	),
						// );
						// // $acctoken=$this->get_access_token();
						// $GZurl=file_get_contents("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$acctoken."",false,stream_context_create($posturl));
						// $GZurl=json_decode($GZurl,true);
						//   // return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$GZurl['ticket']."";
						// 	// $this->isgzgz="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$GZurl['ticket']."";
							$this->isgzgz='false';
					}

				} else{
					$redirect_uri="http://home.58ate.com/index.php/Home/Account/Register";
					header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->wxParam['appid']."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect");
				}
			} else {
				// return 'eror';
				$this->isgzgz='erro';
			}
		}
		/////获得access_token///////
		public	function get_access_token(){
			$data=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->wxParam['appid']."&secret=".$this->wxParam['appsecret']." ");
			$content=json_decode($data,true);
			$token=$content['access_token'];
			return $token;
		}
//	public function loginScoreAdd()
//	{
//		$loginScore=M('Scoreset')->where(array('type'=>'login','switchs'=>2))->find();
//		if($loginScore)
//		{
//			//存在登录积分
//
//		}
//	}
}
