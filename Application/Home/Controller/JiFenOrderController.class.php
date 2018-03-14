<?php
namespace Home\Controller;
class JiFenOrderController extends BaseController {

	public function _initialize()
    {
        parent::_initialize();
    }

    ///////////////////////////////////////////////////////////////////////
    //购物车

    /////////////////////////////////////////////////////////////////////////////////

    public function CreateOrder()
    {

        $selectMid="0";

        $isOldOrder="0";
        $isGetMyself="0";
        $myCouponId="NONE";

        $isGroupDiscount=false;


        $oStoken='0';

        $nowStoken="0";

        if ($_GET['oid'])
        {

        }
        else
        {
            $GoodsStr=$_GET["GoodsInfo"];

            $GoodsArray=array('type'=>$_GET['type'],'Goods'=>array());

            $tempBuyArray=array();

            $gidList=array();


            if ($_GET['type']=='C')
            {
                //购物车
                $tempCart=$this->readCart('all');

                $tempBuyArray=explode(',', $GoodsStr);

                foreach ($tempBuyArray as $key => $value)
                {
                    if ($value)
                    {
                        $GoodsArray['Goods'][$value]=$tempCart['Goods'][$value];
                        array_push($gidList,$value);
                    }
                }

            }
            else
            {
                //直接购买
                $tempBuyArray=json_decode($GoodsStr,true);

                $GoodsArray['Goods']=$tempBuyArray;

                $gidList=array_keys($GoodsArray['Goods']);

            }


            $myCouponId="NONE";

            $hadGroup='0';

            sort($gidList);

            $gInfo=array();

            $sqlStr=A('Public')->getProsInfoSQL('p.ProId,p.ProLogoImg,p.ProName,p.ProTitle,p.Weight,pl.Price AS oPrice,pl.ProIdCard,pl.ProSpec1,p.Score,0 AS nums',$this->webParam['token'],$nowStoken," pl.ProIdCard IN ('".implode($gidList, '\',\'')."')");

            $gInfo=$this->BM()->query($sqlStr);

            $isGroupDiscount=false;

            $dAdd=$this->BM()->query("SELECT  * FROM RS_OrderRecevingAddress WHERE MemberId='".$this->webParam['uid']."' AND token='".$this->webParam['token']."' ORDER BY IsDefault DESC");

            $orderArray['OrderId']="E".date("YmdHis",time()).rand(1000,9999);
        }




            $addrs=$dAdd;
            $this->assign('addrs',$addrs);

            $dAdd=$dAdd[0];

            $weight=0;
            $totlePrice=0;

            $tempPrice=0;

            $orderArray['Goods']=array();

            $hasHuiJiGoods=false;


            $huiJiMoney=0;
            $ShopMoney=0;

            $tempGoodsMoney=0;

            foreach ($gInfo as $key => $value)
            {
                $tempPrice=$value['Score'];

                $gInfo[$key]['oldPrice']=$value['oPrice'];
                $gInfo[$key]['Price']=$tempPrice;

                if ($gInfo[$key]['nums']==0)
                {
                    $gInfo[$key]['nums']=$GoodsArray['Goods'][$value['ProIdCard']]['nums'];

                    $value['nums']=$GoodsArray['Goods'][$value['ProIdCard']]['nums'];
                }

                $orderArray['Goods'][$value['ProIdCard']]=$value;

                $tempGoodsMoney=($gInfo[$key]['Price']*$gInfo[$key]['nums']);

                $weight+=$value["Weight"]*$value['nums'];
                $totlePrice+=$tempGoodsMoney;

            }

            //isstore=1 只有配送
            //isstore=0 只快递


            $psType='KD';

            $psType='KD';

            $this->assign('isstore','0');

            if(empty($dAdd))
            {
                $this->assign('freight','0');
                $this->assign('hasAdd','0');
            }
            else
            {
                $this->assign('freight',A("Public")->getFreight($dAdd["Province"],$weight,0));
                $this->assign('hasAdd','1');
                $this->assign('add',$dAdd);
            }

            $this->assign('weight',$weight);

            $this->assign('wawasJCB',$psType);

            $lessprice=0;

            $this->assign('hadCoupon','0');

            $storeInfo=$this->BM('store')->where(array('token'=>$this->webParam['token'],'stoken'=>$nowStoken))->find();

            $psMoney=0;

            if ($storeInfo)
            {
                if ($totlePrice<$storeInfo['PsPrice'])
                {
                    if ($storeInfo['PsGet'])
                    {
                        $psMoney=$storeInfo['PsGet'];
                    }
                }
            }

            //配送费
            $this->assign('psMoney',$psMoney);

            $freightTemp=$this->BM()->query("SELECT a.Opiece,a.Oadd,a.Tpiece,a.Tadd,a.FirstWeight,a.AddWeight,b.Price,b.Area from RS_Freight a LEFT JOIN RS_Freight_Area b ON a.ID=b.FreightID WHERE a.token='".$this->webParam['token']."' and a.Blong=0 and a.IsSet=1");

            //写入订单缓存
            $this->saveFile($this->getUserFile($this->webParam['uid']).'order.json',json_encode($GoodsArray));

            $this->assign('payurl','https://'.$this->webParam['host'].U('Payment/Index'));

            $this->assign('hjMoney',$huiJiMoney);
            $this->assign('sMoney',$ShopMoney);

            $this->assign('fjson',json_encode($freightTemp));
            $this->assign('lessprice',$lessprice);
            $this->assign('isOldOrder',$isOldOrder);
            $this->assign('selectMid',$selectMid);
            $this->assign('isGetMyself',$isGetMyself);
            $this->assign('myCouponId',$myCouponId);
            $this->assign('orderid',$orderArray['OrderId']);
            $this->assign('totlePrice',$totlePrice);
            $this->assign('weight',$weight);
            $this->assign('gInfo',$gInfo);
            $this->assign('zpInfo',$zpInfo);
    		$this->assign('Title','确认订单');
            $this->assign('searchSign',0);
            $this->assign('footerSign',3);
            $this->display();
    }


    public function CreateNewOrder()
    {
        if(empty($this->webParam['uid'])||$this->webParam['uid']=="NULLVALUE")
        {
            $this->ajaxReturn(array('status'=>'false','info'=>'notLogin'),'JSON');
            return false;
        }

        $data=$_POST;


        $oIsOther='0';

        $nowStoken="0";

        if (empty($data))
        {
            $this->ajaxReturn(array('status'=>'false','info'=>'dataIsNull'),'JSON');
        }
        else
        {
            $this->BM()->startTrans();

            $orderData=array();


        
        
            if ($data["sendType"]=="KD")
            {
                    $addInfo=$this->BM("orderrecevingaddress")->where(array('ReceivingId'=>$data["addid"]))->find();
                    $orderData["RecevingName"]=$addInfo["Name"];
                    $orderData["RecevingArea"]=$addInfo["Area"];
                    $orderData["RecevingCity"]=$addInfo["City"];
                    $orderData["RecevingProvince"]=$addInfo["Province"];
                    $orderData["RecevingAddress"]=$addInfo["Address"];
                    $orderData["RecevingPost"]=$addInfo["Post"];
                    $orderData["RecevingPhone"]=$addInfo["Phone"];

            }


            if ($data['oldOrder']=="1")
            {
                
            }
            else
            {
                //不是老单子
                if ($this->BM('order')->where(array('OrderId'=>$data["orderid"]))->find())
                {
                    $this->ajaxReturn(array('status'=>'false','info'=>'hadOrder'),'JSON');
                    return;
                }
                else
                {

                    $orderInfoStr=$this->readFile($this->getUserFile($this->webParam['uid']).'order.json');

                    $orderData["OrderId"]=$data["orderid"];
                    $orderData["MemberId"]=$this->webParam['uid'];


                    $orderData["Count"]=0;

                    $orderData["SceneMember"]="";
                    $orderData["SceneId"]="";
                    $orderData["SceneContent"]="";

                    $orderInfoList=json_decode($orderInfoStr,true);

                    $gidList=array();
                    
                    foreach ($orderInfoList["Goods"] as $key => $value) {
                        array_push($gidList,$key);
                    }

                    $hadGroup='0';

                    sort($gidList);

                    $isGroupDiscount=false;

                    $cInfo=array();

                    $sqlStr=A('Public')->getProsInfoSQL('p.ProId,p.ProLogoImg,p.ProName,p.ProTitle,p.Weight,pl.Price AS oPrice,pl.ProIdCard,pl.ProSpec1,p.Score,0 AS nums',$this->webParam['token'],$nowStoken," pl.ProIdCard IN ('".implode($gidList, '\',\'')."')");

                    $cInfo=$this->BM()->query($sqlStr);

                    //处理方法

                    $goodListArray=array();

                    $maxGoodsPrice=0;
                    $maxGoodsId='';

                    $orderGroupId=array();

                    $stokenvar=$this->webParam['stoken'];

                    $WeightVar=0;

                    $hCount=0;
                    $hMoney=0;

                    foreach ($cInfo as $key => $value)
                    {

                        $cInfo[$key]['nums']=(int)$orderInfoList['Goods'][$value['ProIdCard']]['nums'];
                        $value["nums"]=(int)$cInfo[$key]['nums'];

                        $goodList["OrderId"]=$orderData["OrderId"];
                        $goodList["ProId"]=$value["ProId"];
                        $goodList["ProIdCard"]=$value["ProIdCard"];


                        $goodList["Price"]=$value['Score'];

                        $goodList["Count"]=(int)$value["nums"];

                        $goodList["Money"]=(float)$goodList["Price"]*$value["nums"];

                        $goodList["Spec"]=$value["ProSpec1"];
                        $goodList["IsDelete"]=false;
                        $goodList["LastUpdateDate"]=date("Y-m-d H:i:s",time());
                        $goodList["IsEvaluation"]=0;
                        $goodList["Iszp"]='0';

                        $goodList["ProName"]=$value['ProName'];
                        $goodList["ProLogoImg"]=$value['ProLogoImg'];
                        $goodList["ProTitle"]=$value['ProTitle'];

                        $res=$this->BM("scoreorderlist")->add($goodList);

                        $orderData["Price"]=((float)$orderData["Price"]+$goodList["Money"]);
                        $orderData["Count"]=($orderData["Count"]+$value["nums"]);

                        $WeightVar+=($value['Weight']*$value["nums"]);

                        $goodList=array();

                        if(!$res)
                        {
                            $this->BM()->rollback();
                            $this->ajaxReturn(array('status'=>'false','info'=>'saveError_orderlist'),'JSON');
                            return;
                        }

                    }


                    // if ($data['sendType']=='KD') 
                    // {
                    //     $orderData["Freight"]=A("Public")->getFreight($orderData["RecevingProvince"],$WeightVar,0);
                    // }
                    // else if ($data['sendType']=='PS') 
                    // {
                    //     $storeInfo=$this->BM('store')->where(array('stoken'=>$nowStoken))->find();
                    //     if ($storeInfo) 
                    //     {
                    //         if ($orderData["Price"]<$storeInfo['PsPrice']) 
                    //         {
                    //             if ($storeInfo['PsGet']) 
                    //             {
                    //                 $orderData["Freight"]=$storeInfo['PsGet'];
                    //             }
                    //         }
                    //         else
                    //         {
                    //             $orderData["Freight"]=0;
                    //         }
                    //     }
                    // }
                    // else
                    // {
                    //     $orderData["Freight"]=0;
                    // }   
                    $orderData["Freight"]=0;

                    $orderData["Price"]=floatval($orderData["Price"]+$orderData["Freight"]-$orderData["Coupon"]);

                    $orderData["PayName"]='JF';


                    $orderData["IsEvaluation"]=0;
                    $orderData["Status"]=2;
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
                    $orderData["token"]=$this->webParam['token'];

                    $orderData['OpenId']=$this->webParam['openid'];

                    $uinfo=$this->BM('member')->where(array('token'=>$this->webParam['token'],'MemberId'=>$this->webParam['uid']))->find();

                    if ($uinfo['Integral']<$orderData['Price']) 
                    {
                        $this->BM()->rollback();
                        $this->ajaxReturn(array('status'=>'false','info'=>'userScore','JSON'));
                    }
                    else
                    {

                        $res=$this->BM("scoreorder")->add($orderData);

                        if($res)
                        {

                            $intData = array(
                                'MemberId'      => $this->webParam['uid'],
                                'Type'          => 'xf',
                                'Remarks'       => $orderData["OrderId"],
                                'Status'        => 0,
                                'CreateDate'    => date("Y-m-d",time()),
                                'Integral'      => 0-$orderData['Price'],
                                'token'=>$this->webParam['token']
                            );
                            
                            $res=$this->BM("integraldetail")->add($intData);

                            if ($res) 
                            {
                               
                                $res=$this->BM('member')->where(array('MemberId'=>$this->webParam['uid']))->setField(array('Integral'=>$uinfo['Integral']-$orderData['Price']));


                                if ($res) 
                                {

                                    $res=false;

                                    if ($orderInfoList['type']=='C') 
                                    {

                                        $res=$this->delCarts($gidList);

                                        if ($res) 
                                        {
                                            $res=$this->saveFile($this->getUserFile($this->webParam['uid']).'order.json',json_encode(array()));
                                        }
                                        else
                                        {
                                            $res=false;
                                        }
                                    }
                                    else
                                    {
                                        $res=$this->saveFile($this->getUserFile($this->webParam['uid']).'order.json',json_encode(array()));
                                    }

                                    if ($res!==false)
                                    {
                                        $this->BM()->commit();
                                        $this->ajaxReturn(array('status'=>'true','money'=>floatval($orderData['Price']),'info'=>'Success'),'JSON');
                                    }
                                    else
                                    {
                                        $this->BM()->rollback();
                                        $this->ajaxReturn(array('status'=>'false','info'=>'DelCartError'),'JSON');
                                    }
                                    //cookie("user_Cart","NULLVALUE");
                                    $this->BM()->commit();
                                    //$wxpayData=new \Org\WeChar\Wx_Data();
                                    $this->ajaxReturn(array('status'=>'true','info'=>'Success'),'JSON');
                                }
                                else
                                {
                                    $this->BM()->rollback();
                                    $this->ajaxReturn(array('status'=>'false','info'=>'CouponError'),'JSON');
                                }
                            }
                            else
                            {
                                $this->BM()->rollback();
                                $this->ajaxReturn(array('status'=>'false','info'=>'scoreerror'),'JSON');
                            }


                        }
                        else
                        {
                            $this->BM()->rollback();
                            $this->ajaxReturn(array('status'=>'false','info'=>$this->BM("order")->_sql()),'JSON');
                        }
                    }

                }
            }
        }
    }



}
