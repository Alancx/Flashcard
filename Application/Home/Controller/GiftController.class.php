<?php
namespace Home\Controller;
class GiftController extends BaseController {

	public function _initialize()
    {
        parent::_initialize();
    }

    //网站前台首页
    public function CreateOrder()
    {
            if(empty($this->webParam['uid'])||$this->webParam['uid']=="NULLVALUE")
            {
                $this->redirect('Account/Login');
                return false;
            }

            //cid/bglls54582/pic/pro7007360798_1038.html
            //$data=$_GET["OrderInfo"];

            //$data='{"Goods":{'.$data.'}}';

            //$orderInfoArray=json_decode($data,true);

            // $gidList=array();
            // foreach ($orderInfoArray["Goods"] as $key => $value) {
            //     array_push($gidList,$key);
            // }

            // $gInfo=$this->BM()->table(C('DB_BASE')['DB_PREFIX']."Product p")->join(C('DB_BASE')['DB_PREFIX']."ProductList pl ON p.ProId=pl.ProId","LEFT")->where(array('pl.ProIdCard'=>array('in',implode($gidList, ',')),'pl.IsUseScore'=>1))->field("p.ProName,p.ProId,pl.ProIdCard,pl.Score AS Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,0 AS nums")->select();

            $data=$_GET;

            $gInfo=$this->BM()->query("SELECT p.ProName,p.ProId,pl.ProIdCard,pl.Price AS Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,mgp.CouponCount AS nums FROM RS_MemberGiftPackage mgp LEFT JOIN RS_ProductList pl ON mgp.content=pl.ProIdCard LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE mgp.content='".$data['pic']."' AND mgp.MemberId='".$this->webParam['uid']."' AND mgp.CouponCount>0 AND mgp.CouponId='".$data['cid']."' AND mgp.token=p.token AND mgp.token =pl.token AND mgp.token='".$this->webParam['token']."'");


            $dAdd=$this->BM('orderrecevingaddress')->where(array('MemberId'=>$this->webParam['uid'],'IsDefault'=>true,'token'=>$this->webParam['token']))->find();



            $orderArray['OrderId']="LB".date("YmdHis",time()).rand(1000,9999);


            $weight=0;
            $totlePrice=0;

            $orderArray['Goods']=array();

            $goodsMaxPrice=0;

            foreach ($gInfo as $key => $value)
            {
                if ($gInfo[$key]['nums']==0)
                {
                    $gInfo[$key]['nums']=$orderInfoArray['Goods'][$value['ProIdCard']]['nums'];

                    $value['nums']=$orderInfoArray['Goods'][$value['ProIdCard']]['nums'];
                }

                $orderArray['Goods'][$value['ProIdCard']]=$value;

                $totlePrice+=($gInfo[$key]['Price']*$gInfo[$key]['nums']);
            }


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

        $storeList=$this->BM('store')->where(array('token'=>$this->webParam['token']))->select();

        if ($storeList)
        {
            $this->assign('hadStore','inline');
            $this->assign('storeList',$storeList);
        }
        else
        {
            $this->assign('hadStore','none');
            $this->assign('storeList',$storeList);
        }

        $addrs=$this->BM('orderrecevingaddress')->where("MemberId='%s' and token='%s'",array($_COOKIE['user_UserID'],$this->webParam['token']))->select(); //获取所有地址
        $this->assign('addrs',$addrs);

        $freightTemp=$this->BM()->query("SELECT a.Opiece,a.Oadd,a.Tpiece,a.Tadd,a.FirstWeight,a.AddWeight,b.Price,b.Area from RS_Freight a LEFT JOIN RS_Freight_Area b ON a.ID=b.FreightID WHERE a.token='".$this->webParam['token']."' and a.Blong=0 and a.IsSet=1");

        $this->assign('fjson',json_encode($freightTemp));

        $this->assign('cid',$data['cid']);
        $this->assign('pic',$data['pic']);
        $this->assign('orderid',$orderArray['OrderId']);
        $this->assign('totlePrice',$totlePrice);
        $this->assign('gInfo',$gInfo);
		$this->assign('Title','大礼包订单');
        $this->assign('searchSign',0);
        $this->assign('footerSign',8);
        $this->display();
    }

    public function pay()
    {
        if(empty($this->webParam['uid'])||$this->webParam['uid']=="NULLVALUE")
        {
            $this->ajaxReturn(array('status'=>'false','info'=>'notLogin'),'JSON');
            return false;
        }

        $data=$_POST;
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
            else
            {
                    $addInfo=$this->BM('store')->where(array('id'=>$data["addid"]))->find();

                    $orderData["RecevingName"]=$addInfo["id"];
                    $orderData["RecevingArea"]=$addInfo["area"];
                    $orderData["RecevingCity"]=$addInfo["city"];
                    $orderData["RecevingProvince"]=$addInfo["province"];
                    $orderData["RecevingAddress"]=$addInfo["addr"];
                    $orderData["RecevingPost"]='ZT';
                    $orderData["RecevingPhone"]=$addInfo["tel"].'';
            }

                if ($this->BM('giftorder')->where(array('OrderId'=>$data["orderid"]))->find())
                {
                    $this->ajaxReturn(array('status'=>'false','info'=>'hadOrder'),'JSON');
                    return;
                }
                else
                {

                    $orderData["OrderId"]=$data["orderid"];
                    $orderData["MemberId"]=$this->webParam['uid'];
                    $orderData["Count"]=0;

                    //$orderInfoList=json_decode($orderInfoStr,true);

                    // $gidList=array();
                    // foreach ($orderInfoList["Goods"] as $key => $value) {
                    //     array_push($gidList,$key);
                    // }

                    $gInfo=$this->BM()->query("SELECT p.ProName,p.ProId,pl.ProIdCard,0 AS Price,p.PageUrl,p.ProLogoImg,p.Cut,p.Cut2,p.Cut3,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.ProSpec4,pl.ProSpec5,p.Weight,mgp.CouponCount AS nums FROM RS_MemberGiftPackage mgp LEFT JOIN RS_ProductList pl ON mgp.content=pl.ProIdCard LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE mgp.content='".$data['pic']."' AND mgp.MemberId='".$this->webParam['uid']."' AND mgp.CouponCount>0 AND mgp.CouponId='".$data['cid']."' AND mgp.token=p.token AND mgp.token =pl.token AND mgp.token='".$this->webParam['token']."'");

                    $goodListArray=array();

                    foreach ($gInfo as $key => $value)
                    {

                        //$cInfo[$key]['nums']=(int)$orderInfoList['Goods'][$value['ProIdCard']]['nums'];
                        //$value["nums"]=(int)$cInfo[$key]['nums'];

                        $goodList["OrderId"]=$orderData["OrderId"];
                        $goodList["ProId"]=$value["ProId"];
                        $goodList["ProIdCard"]=$value["ProIdCard"];
                        $goodList["Price"]=(float)$value["Price"];

                        $goodList["Count"]=(int)$value["nums"];
                        $goodList["Money"]=(float)$value["Price"]*$value["nums"];

                        $goodList["Cut"]=$value["Cut"];
                        $goodList["Cut2"]=$value["Cut2"];
                        $goodList["Cut3"]=$value["Cut3"];
                        $goodList["Spec"]=$value["ProSpec1"]."_".$value["ProSpec2"]."_".$value["ProSpec3"]."_".$value["ProSpec4"]."_".$value["ProSpec5"];
                        $goodList["IsDelete"]=false;
                        $goodList["LastUpdateDate"]=date("Y-m-d H:i:s",time());
                        $goodList["IsEvaluation"]=0;


                        $res=$this->BM("giftorderlist")->add($goodList);

                        $orderData["Price"]=((float)$orderData["Price"]+$goodList["Money"]);
                        $orderData["Count"]=($orderData["Count"]+$value["nums"]);

                        $goodList=array();

                        if(!$res)
                        {
                            $this->BM()->rollback();
                            $this->ajaxReturn(array('status'=>'false','info'=>'saveError_orderlist'),'JSON');
                            return;
                        }

                    }


                    $orderData["Freight"]=A("Public")->getFreight($orderData["Province"],$weight,0);
                    $orderData["Price"]=$orderData["Freight"];
                    $orderData["Coupon"]=0;
                    $orderData["CouponListId"]=$data['cid'];
                    $orderData["PayName"]='LB';
                    $orderData["IsEvaluation"]=0;

                    if ($orderData["Price"]>0) {
                        $orderData["Status"]=1;
                    }
                    else
                    {
                        $orderData["Status"]=2;
                    }

                    if ($orderData["RecevingPost"]=='ZT') {
                        $orderData["Status"]=1;
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
                    $orderData["token"]=$this->webParam['token'];

                    $res=$this->BM("giftorder")->add($orderData);
                    if($res)
                    {

                        //$res=$this->BM('membergiftpackage')->where(array('CouponId'=>$data['cid'],'content'=>$data['pic'],'token'=>$this->webParam['token']))->setField(array('UseDate'=>$this->nowTimeParam['datetime'],'cOpenid'=>$this->webParam['openid'],'CouponCount'=>0));
    
                            if ($res) {

                                $this->BM()->commit();
                                $this->ajaxReturn(array('status'=>'true','info'=>'Success','payStatus'=>$orderData["Status"]),'JSON');

                            }
                            else
                            {
                                $this->BM()->rollback();
                                $this->ajaxReturn(array('status'=>'false','info'=>'MemberGiftError','JSON'));
                            }

                    }
                    else
                    {
                        $this->BM()->rollback();
                        $this->ajaxReturn(array('status'=>'false','info'=>$this->BM("giftorder")->_sql()),'JSON');
                    }
                }
        }
    }
}
