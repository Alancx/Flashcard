<?php
namespace Home\Controller;
class OrderController extends BaseController {

	public function _initialize()
    {
        parent::_initialize();
    }

    ///////////////////////////////////////////////////////////////////////
    //购物车


    //购物车
    public function Cart()
    {
        $this->display();

    }


    //删除购物车内商品
    public function DelCart()
    {
        $postData=$_POST;

        if(empty($postData['id'])||empty($postData['attr']))
        {
            $this->ajaxReturn(array('status'=>'false','info'=>'dataIsNull'),'JSON');
        }
        else
        {

            $res=$this->delCarts(array($postData['id'].$postData['attr']));

            if($res)
            {
                $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
            }
            else
            {
                $this->ajaxReturn(array('status'=>'false','info'=>'Error'),'JSON');
            }
        }
    }

    //编辑购物车内商品
    public function EditCart()
    {
        $postData=$_POST;

        if(empty($postData['id'])||empty($postData['attr'])||empty($postData['nums']))
        {
            $this->ajaxReturn(array('status'=>'false','info'=>'dataIsNull'),'JSON');
        }
        else
        {
            $CartArray=$this->readCart('all');

            if ($CartArray['Goods'][$postData['id'].$postData['attr']])
            {
                $CartArray['Goods'][$postData['id'].$postData['attr']]['nums']= $CartArray['Goods'][$postData['id'].$postData['attr']]['nums']+$postData['nums'];
            }


            if ($this->saveCart($CartArray['Goods']))
            {
                $this->ajaxReturn(array('status'=>'true','info'=>'Success'),'JSON');
            }
            else
            {
                $this->ajaxReturn(array('status'=>'false','info'=>'Error'),'JSON');
            }
        }
    }

    /////////////////////////////////////////////////////////////////////////////////

    public function CreateOrder()
    {

        $selectMid="0";

        $isOldOrder="0";
        $isGetMyself="0";
        $myCouponId="NONE";

        $isGroupDiscount=false;


        $oStoken=$_GET['oStoken'];

        $nowStoken="";


        if ($_GET['type']=='C')
        {
            $nowStoken=$this->webParam['stoken'];
            $this->assign('isOther','0');
        }
        else
        {

            if ($oStoken=='-1') 
            {
                $nowStoken=$this->webParam['stoken'];
                $this->assign('isOther','0');
            }
            else
            {
                if ($oStoken=="0") 
                {
                    $nowStoken="0";
                }
                else
                {
                    $ostoreInfo=$this->WM('store')->where(array('id'=>$oStoken))->find();
                    $nowStoken=$ostoreInfo['stoken']; 
                }

                $this->assign('isOther','1');
            }

        }

        $this->assign('oStoken',$nowStoken);


        if ($_GET['oid'])
        {
            $isOldOrder=1;

            $gInfo=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."Order o")->
						join(C('DB_BASE')['DB_PREFIX']."OrderList ol ON o.OrderId=ol.OrderId","LEFT")->
						join(C('DB_BASE')['DB_PREFIX']."ProductList pl ON pl.ProIdCard=ol.ProIdCard","LEFT")->
						join(C('DB_BASE')['DB_PREFIX']."Product p ON p.ProId=pl.ProId","LEFT")->
						join(C('DB_BASE')['DB_PREFIX']."ProductOnsale pos ON p.ProId=pos.ProId AND (GETDATE() BETWEEN pos.stime AND pos.etime) AND pos.Ison=1","LEFT")->
						where(array('o.OrderId'=>$_GET['oid'],'Status'=>1,'ol.Iszp'=>'0','pl.Iszp'=>'0'))->
						field('o.OrderId,o.RecevingName,o.RecevingPhone,o.RecevingProvince,o.RecevingCity,o.RecevingArea,o.RecevingAddress,o.RecevingPost,o.CouponListId,p.ProName,p.ProId,p.stoken,pl.ProIdCard,pl.Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,pos.sprice,pos.Ison,ol.Count AS nums')->select();


            $myCouponId="NONE";

            if ($gInfo[0]['RecevingPost']=='ZT')
            {
                $selectMid=$gInfo[0]['RecevingName'];
                $isGetMyself="1";
            }
            else
            {
                $dAdd=$this->BM('orderrecevingaddress')->where(array('MemberId'=>$this->webParam['uid'],'Name'=>$gInfo[0]['RecevingName'],'Phone'=>$gInfo[0]['RecevingPhone'],'Province'=>$gInfo[0]['RecevingProvince'],'City'=>$gInfo[0]['RecevingCity'],'Area'=>$gInfo[0]['RecevingArea'],'Address'=>$gInfo[0]['RecevingAddress'],'Post'=>$gInfo[0]['RecevingPost'],'token'=>$this->webParam['token']))->find();
            }

            $orderArray['OrderId']=$gInfo[0]['OrderId'];

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

            $sqlStr=A('Public')->getProsInfoSQL('p.ProId,p.ProLogoImg,p.ProName,p.ProTitle,p.Weight,pl.Price AS oPrice,pl.ProIdCard,pl.ProSpec1,0 AS nums',$this->webParam['token'],$nowStoken," pl.ProIdCard IN ('".implode($gidList, '\',\'')."')");



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

                if ($value['sprice'])
                {
                    $tempPrice=$value['sprice'];
                }
                else
                {
                    $tempPrice=$value['tPrice'];
                }




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


                if ($value['stoken']=="0") 
                {
                    $hasHuiJiGoods=true;
                    $huiJiMoney+=$tempGoodsMoney;
                }
                else
                {
                    $ShopMoney+=$tempGoodsMoney;
                }


            }

            //isstore=1 只有配送
            //isstore=0 只快递


            $psType='KD';

            if ($nowStoken=='0')
            {

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

            }
            else
            {
                $psType='PS';

                $this->assign('isstore','1');


                if(empty($dAdd))
                {
                    $this->assign('hasAdd','0');
                }
                else
                {
                    $this->assign('hasAdd','1');
                    $this->assign('add',$dAdd);
                }

                $this->assign('freight','0');
            }

            $this->assign('weight',$weight);

            $this->assign('wawasJCB',$psType);

            $lessprice=0;

            $this->assign('hadCoupon','0');

            $storeInfo=$this->BM('store')->where(array('token'=>$this->webParam['token'],'stoken'=>$nowStoken))->find();

            $psMoney=0;

            if($storeInfo)
            {
                if ($storeInfo['PsgetType']=='0') 
                {

                    if ($storeInfo['PsPrice']>$totlePrice) 
                    {
                        $psMoney=$totlePrice*($storeInfo['PsGet']/100);

                        if ($psMoney<$storeInfo['MinPsGet']) 
                        {
                            $psMoney=$storeInfo['MinPsGet'];
                        }

                        if ($psMoney>$storeInfo['MaxPsGet']) 
                        {
                            $psMoney=$storeInfo['MaxPsGet'];
                        }

                    }
                    else
                    {
                        $psMoney=0;
                    }

                }
                else
                {

                    if ($totlePrice<$storeInfo['PsPrice'])
                    {
                        if ($storeInfo['PsGet'])
                        {
                            $psMoney=$storeInfo['PsGet'];
                        }
                    }
                    else
                    {
                        $psMoney=0;
                    }
                    
                }
            }
            else
            {
                $psMoney=0;
            }

            //配送费
            $this->assign('psMoney',$psMoney);

            //$addrs=$this->BM('orderrecevingaddress')->where(array('MemberId'=>$this->webParam['uid'],'token'=>$this->webParam['token']))->select(); //获取所有地址

            $stokenCoupon=$nowStoken;

            if ($hasHuiJiGoods) 
            {
                $stokenCoupon="0";
            }

            $couponList=$this->BM()->query("SELECT mc.CouponId,c.CouponName,c.Rules,c.Type,c.stoken FROM RS_MemberCoupon mc LEFT JOIN RS_Coupon c ON mc.CouponId=c.CouponId WHERE mc.Status=0 AND (GETDATE() BETWEEN c.StartDate AND c.ExpiredDate) AND c.IsEnable=1 AND mc.OpenId='".$this->webParam['openid']."' AND (c.stoken='".$stokenCoupon."' OR c.stoken='".$nowStoken."')");

            $this->assign('couponList',$couponList);

            if (count($couponList)>0) 
            {
                $this->assign('hasCoupon',true);
            }
            else
            {
                $this->assign('hasCoupon',false);
            }

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


}
