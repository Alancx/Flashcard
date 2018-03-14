<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class UserController extends BaseController{

  public function GetBankinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $bankinfo=$this->BM('merchantbank')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->find();
      if ($bankinfo) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $bankinfo), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'nobankError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }

  public function getzhBankinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $province=mb_substr($info['province'],0,2,'utf-8');
      $city=mb_substr($info['city'],0,2,'utf-8');
      $bankname=$info['bankname'];
      $sqlStr="SELECT BankOfArea,BankNumber FROM RS_BankList WHERE Province LIKE '%".$province."%' AND City LIKE '%".$city."%' AND BankName='".$bankname."'";
      $zhbankinfo=$this->BM()->query($sqlStr);
      if ($zhbankinfo) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $zhbankinfo), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'getfalse'), 'JSON');
      }
    }else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  public function GetBcodeinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $phoneno=$info['phoneno'];
      $str='0123456789';
      $vcode='';
      for ($i=0; $i < 4; $i++) {
        $vcode.=substr(str_shuffle($str), mt_rand(0,9),1);
      }
      $content='验证码：'.$vcode.'，您正在进行添加银行卡，请在120秒内填写，如非本人操作，请忽略本短信。';
      $data['mobiles']=$phoneno;
      $data['content']=$content;
      $res=$this->SendMessage($data);
      parse_str($res,$Atr);
      if ($Atr['result']=='0') {
        $this->ajaxReturn(array('status' => 'true','info'=>$phoneno.'&&'.$vcode), 'JSON');
      }else{
        $this->ajaxReturn(array('status' => 'false','info'=>'sendError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }

  }
  //保存商户银行卡信息//////
  public function SaveBankinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $tempdata=json_decode($info['savedata'],true);
      $saveType=$tempdata['saveType'];
      $savedata['IdType']=$tempdata['Bbankcode'];
      $savedata['IdCard']=$tempdata['Bbankno'];
      $savedata['IdName']=$tempdata['Bname'];
      $savedata['BankName']=$tempdata['Bbank'];
      $savedata['tel']=$tempdata['Bphone'];
      $savedata['ZBankName']=$tempdata['Bzbank'];
      $savedata['ZBankNo']=$tempdata['Bzbankcode'];
      $savedata['Province']=$tempdata['province'];
      $savedata['City']=$tempdata['city'];
      $savedata['token']=$shopinfo['Token'];
      $savedata['stoken']=$shopinfo['Stoken'];
      if ($saveType == 'add') {
        $res=$this->BM('merchantbank')->add($savedata);
      } else {
        $res=$this->BM('merchantbank')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->save($savedata);
      }
      if ($res) {
        $this->ajaxReturn(array('status' => 'true','info' => $res), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false','info' => 'saveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  /////获得结算统计数据////////////////
  public function GetJsinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $MoneyInfo=$this->BM()->table('RS_Store')->where("token='%s' and stoken='%s'",array($shopinfo['Token'],$shopinfo['Stoken']))->field("CONVERT(float(53),ISNULL(Money,0),120) as Money,CONVERT(float(53),ISNULL(TotalMoney,0),120) as TotalMoney")->find();
      $pagedata['MoneyInfo']=$MoneyInfo;
      $cutlist=$this->BM()->table('RS_StoreMoneyManager')->where("token='%s' and stoken='%s'",array($shopinfo['Token'],$shopinfo['Stoken']))->field("ID,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE Type WHEN 'add' THEN '+' WHEN 'less' THEN '-' END) as Type,CONVERT(float(53),Money,120) as Money,CONVERT(float(53),TmpMoney,120) as TmpMoney,(CASE Useage WHEN 'XS' THEN '销售结算' WHEN 'YL' THEN '引流结算' WHEN 'JS' THEN '账户提现' WHEN 'PS' THEN '配送提现' END) as Useage")->order("CreateDate desc")->select();
      $pagedata['cutlist']=$cutlist;
      $sqlist=$this->BM()->table('RS_MerCutDetail')->where("token='%s' and stoken='%s'",array($shopinfo['Token'],$shopinfo['Stoken']))->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,CONVERT(float(53),Money,120) as Money,Status AS Stype,(CASE Status WHEN '0' THEN '待处理' WHEN '1' THEN '已处理' END) AS Status")->order("CreateDate desc")->select();
      $pagedata['sqlist']=$sqlist;
      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////提现申请///////////////
  public function SendtxMoneyinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $IdInfo=$this->BM()->table("RS_MerchantBank")->where("token='%s' and stoken='%s' and IsCheck='1'",array($shopinfo['Token'],$shopinfo['Stoken']))->find();
      if ($IdInfo) {
        $DB=array();
        $DB['Money']=$info['money'];
        $DB['IdCard']=$IdInfo['IdCard'];
        $DB['IdName']=$IdInfo['BankName'];
        $DB['IdType']=$IdInfo['IsOpen'];
        $DB['GetName']=$IdInfo['IdName'];
        $DB['tel']=$IdInfo['tel'];
        $DB['CutType']='GETMONEY';
        $DB['token']=$shopinfo['Token'];
        $DB['stoken']=$shopinfo['Stoken'];

        $this->BM()->startTrans();
        $res=$this->BM()->table('RS_MerCutDetail')->add($DB);
        $sres=$this->BM()->table('RS_Store')->where("token='%s' and stoken='%s'",array($shopinfo['Token'],$shopinfo['Stoken']))->setDec('Money',$info['money']);
        if ($res && $sres) {
          $this->BM()->commit();
          $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
        }else{
          $this->BM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'info' => 'saveError'), 'JSON');
        }

      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'nobankError'), 'JSON');
      }
      $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////获得配送员信息/////////////////
  public function GetDistributioninfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $psping=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=0 and stoken='".$shopinfo['Stoken']."'")->field("ds.ID,db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
      $pspend=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=1 and stoken='".$shopinfo['Stoken']."'")->field("db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
      $pagedata['pspend']=$pspend;
      $pagedata['psping']=$psping;
      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////审核配送员/////////////
  public function SetDistributioninfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $id = $info['psid'];
      $type = $info['ltype'];
      if ($type=='gook') {
        $res = $this->BM()->table('RS_DistributionForStore')->where("ID=%d",$id)->setField("Status",1);
      } elseif ($type=='ref') {
        $res = $this->BM()->table('RS_DistributionForStore')->where("ID=%d",$id)->setField("Status",2);
      } else {
        $res = false;
      }
      if ($res) {
        $psping=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=0 and stoken='".$shopinfo['Stoken']."'")->field("ds.ID,db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
        $pspend=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=1 and stoken='".$shopinfo['Stoken']."'")->field("db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
        $pagedata['pspend']=$pspend;
        $pagedata['psping']=$psping;
        $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'saveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////配送员提现记录信息///////
  public function GetDisCashinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$shopinfo['Token']."' AND dc.stoken='".$shopinfo['Stoken']."' AND dc.Status='0' ORDER BY dc.ID DESC";
      $pagedata['dshdata']=$this->BM()->query($sqlStr);//待审核

      $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$shopinfo['Token']."' AND dc.stoken='".$shopinfo['Stoken']."' AND dc.Status='1' ORDER BY dc.ID DESC";
      $pagedata['yshdata']=$this->BM()->query($sqlStr);//已审核

      $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$shopinfo['Token']."' AND dc.stoken='".$shopinfo['Stoken']."' AND dc.Status='2' ORDER BY dc.ID DESC";
      $pagedata['ywcdata']=$this->BM()->query($sqlStr);//已完成

      $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$shopinfo['Token']."' AND dc.stoken='".$shopinfo['Stoken']."' AND dc.Status='3' ORDER BY dc.ID DESC";
      $pagedata['yjjdata']=$this->BM()->query($sqlStr);//已拒绝

      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  /////提现审核处理///////
  public function SetDisCashinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $type=$info['ltype'];
      $id=$_POST['tid'];
      if ($type=='pass') {
        $Status=1;
        $IsCuted=2;
      } elseif ($type=='refund') {
        $Status=3;
        $IsCuted=0;
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'notypeError'), 'JSON');
        exit();
      }
      $OrderIds=$this->BM()->table('RS_DistributionCashDetail')->where("ID=%d",$id)->getField("OrderList");
      $this->BM()->startTrans();
      $dc=$this->BM()->table('RS_DistributionCashDetail')->where("ID=%d",$id)->setField('Status',$Status);
      $do=$this->BM()->table('RS_DistributionForOrder')->where("OrderId in ('".str_replace(',', "','", $OrderIds)."')")->setField('IsCuted',$IsCuted);
      if ($do && $dc) {
        $this->BM()->commit();
        $this->GetDisCashinfo();
        $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
      }else{
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'info' => 'saveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////获得配送订单信息//////////////
  public function GetDisOrderinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $psing=$this->BM()->table('RS_DistributionForOrder df')->join("LEFT JOIN RS_Distribution db ON df.OpenId=db.OpenId")->where("df.stoken='".$shopinfo['Stoken']."' and df.token='".$shopinfo['Token']."' and IsSuccess=0 and df.IsDelete=0")->field("df.OrderId,CONVERT(varchar(20),GetDate,102) as CDate,CONVERT(varchar(20),GetDate,108) as Ctime,db.TrueName,db.Phone,df.Status as status")->select();

      $psend=$this->BM()->table('RS_DistributionForOrder df')->join("LEFT JOIN RS_Distribution db ON df.OpenId=db.OpenId")->where("df.stoken='".$shopinfo['Stoken']."' and df.token='".$shopinfo['Token']."' and IsSuccess=1 and df.IsDelete=0")->field("df.OrderId,CONVERT(varchar(20),GetDate,120) as GetDate,CONVERT(varchar(20),OverDate,120) as OverDate,db.TrueName,db.Phone")->select();

      $pagedata['psing']=$psing;
      $pagedata['psend']=$psend;
      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////重新派单///////////////
  public function NewSendDisOrderinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $oid=$info['oid'];

      $psopenid=$this->BM('distributionfororder')->where(array('OrderId'=>$oid,'IsDelete'=>'0'))->find();
      $psopenid=$psopenid['OpenId'];
      $red=$this->BM('distributionfororder')->where(array('OrderId'=>$oid,'IsDelete'=>'0'))->save(array('IsDelete'=>'1'));
      if ($red) {
        $sqlStr="UPDATE RS_Distribution SET IsReceving='1' WHERE OpenId='".$psopenid."' AND IsBoss='0'";
        $this->BM()->execute($sqlStr);
        /////发送微信消息//////
        $orderInfo=$this->BM('order')->where(array('OrderId'=>$oid))->find();
        $storeInfo=$this->BM('store')->where(array('stoken'=>$orderInfo['stoken']))->find();
        $ssmInfo=$this->BM()->query("SELECT dfs.* FROM RS_DistributionForStore dfs LEFT JOIN RS_Distribution d ON dfs.OpenId=d.OpenId WHERE dfs.stoken='".$orderInfo['stoken']."' AND dfs.Status='1' AND d.IsReceving='0'");

        foreach ($ssmInfo as $key => $value){
          $smInfo=array(
            'touser'=>$value['OpenId'], //必填
            'template_id'=>'t3kQ8SlQC13-YZt5pxxKTubLCeLUAz6hd0YBZ2ksGJE', //必填
            'first'=>array('value'=>'您有一份新的订单('.'取货门店:'.$storeInfo['storename'].')',color=>'#000000'), //必填
            'remark'=>array('value'=>'点击下方详情抢单',color=>'#000000'), //必填
            'url'=>'https://'.$_SERVER['HTTP_HOST'].U('Admin/Base/getordersoon',array('openid'=>$value['OpenId'],'oid'=>$orderInfo['OrderId'])),
            'content'=>array(
              0=>array('value'=>$orderInfo['OrderId'],'color'=>'#000000'),
              1=>array('value'=>date('Y-m-d H:i:s'),'color'=>'#000000'),
              2=>array('value'=>$orderInfo['RecevingName'],'color'=>'#000000'),
              3=>array('value'=>$orderInfo['RecevingPhone'],'color'=>'#000000'),
              4=>array('value'=>$orderInfo['RecevingProvince'].$orderInfo['RecevingCity'].$orderInfo['RecevingArea'].$orderInfo['RecevingAddress'],'color'=>'#000000'),
              )  //必填
            );
            $ressm=$this->sendWxMessage($smInfo);
            $this->LOGS('重新派单发送微信消息'.$ressm);
          }

          $this->ajaxReturn(array('status' => 'true', 'info' => $oid), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'false', 'info' => 'sendnewError'), 'JSON');
        }

      }else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
      }
    }
    ////获得小店首页数据信息//////////////////
    public function GetmPageinfo($info){
      $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
      if ($shopinfo) {
        //平台设置小店的轮播图///
        $pagedata['homeimg']=$this->BM()->table('RS_HomeImg')->where("token='%s' and stoken='%s'",array($shopinfo['Token'],0))->find();
        ///小店自己设置的轮播图//
        $pagedata['lbdata']=$this->BM('homeimg')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken'],'IsShow'=>1))->order('ID')->select();
        //平台设置的小店的热卖商品
        $sqlStr="SELECT p.ProId,poh.[Position],(CASE WHEN (p.stoken='0' AND mp.Price IS NOT NULL) THEN mp.Price ELSE p.PriceRange
         END) AS PriceRange,p.ProName,p.ProTitle,p.ProLogoImg,p.Price FROM
         RS_Product p LEFT JOIN  RS_ProductOnHome poh ON poh.ProId = p.ProId
         LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
          WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token='".$shopinfo['Token']."' AND p.IsShelves=1 AND
        (p.stoken='".$shopinfo['Stoken']."' OR mp.stoken='".$shopinfo['Stoken']."' OR poh.stoken='0') AND
        poh.[Position]='SHOP_HOT' ORDER BY poh.[Position]";
        $prohot=$this->BM()->query($sqlStr);
        $pagedata['prohot']=$prohot[0];
        ///小店设置的热卖商品
        $sqlStr="SELECT p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,p.Price,poh.[Position],poh.ID,
        (CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_ProductOnHome poh
        LEFT JOIN RS_Product p ON p.ProId = poh.ProId LEFT JOIN
        (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
        WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp
        ON poh.ProId=mp.ProId
        WHERE poh.[Position] LIKE '%HOT%' AND poh.token='".$shopinfo['Token']."'
        AND poh.stoken='".$shopinfo['Stoken']."' ORDER BY poh.[Position]";
        $selprohot=$this->BM()->query($sqlStr);
        $pagedata['selhotinfo']=$selprohot[0];
        /////热卖商品//////////
        $sqlStr="SELECT p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,(CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros WHERE token = '".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token ='".$shopinfo['Token']."' AND (p.stoken='".$shopinfo['Stoken']."' OR mp.stoken='".$shopinfo['Stoken']."') AND p.IsShelves='1'";
        $pagedata['hotproinfo'] = $this->BM()->query($sqlStr);

        //////底部商品信息//////
        $sqlStr="SELECT * FROM(SELECT ROW_NUMBER()OVER (ORDER BY p.ProId ) AS num, p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,(CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
  			WHERE token = '".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token ='".$shopinfo['Token']."' AND (p.stoken='".$shopinfo['Stoken']."' OR mp.stoken='".$shopinfo['Stoken']."') AND p.IsShelves='1') proinfo WHERE num BETWEEN 0 AND 4";
        $pagedata['bottomproinfo'] = $this->BM()->query($sqlStr);

        $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
      }
    }
    /////轮播图保存///////
    public function SaveLbImginfo($info){
      $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
      if ($shopinfo) {
        $lbdata=json_decode($info['lbinfo'],true);
        $homelbdata['ImgPath']=$lbdata['imgurl'];
        $homelbdata['ImgUrl']=$lbdata['imghref'];
        $homelbdata['Sort']=$lbdata['sort'];
        $homelbdata['IsShow']=1;
        $homelbdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
        $homelbdata['token']=$shopinfo['Token'];
        $homelbdata['stoken']=$shopinfo['Stoken'];
        if ($lbdata['ltype']=='add') {
          $res=$this->BM('homeimg')->add($homelbdata);
        } else {
          $res=$this->BM('homeimg')->where(array('ID'=>$lbdata['ltype']))->save($homelbdata);
        }
        if ($res) {
          $lbdata['ID']=$res;
          $this->ajaxReturn(array('status' => 'true', 'info' => $lbdata), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'false', 'info' => 'saveError'), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
      }
    }
    ///删除轮播图信息////////////
    public function DelLbImginfo($info){
      $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
      if ($shopinfo) {
        $hid = $info['lbid'];
        $res=$this->BM('homeimg')->where(array('ID'=>$hid))->delete();
        if ($res) {
          $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'false', 'info' => 'delError'), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
      }
    }
    /////保存选择的首页商品///////
    public function SaveHotProinfo($info){
      $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
      if ($shopinfo) {
        $pid = $info['pid'];
        $type = $info['ltype'];
        $hotprodata['ProId']=$pid;
        $hotprodata['token']=$shopinfo['Token'];
        $hotprodata['stoken']=$shopinfo['Stoken'];
        $red= $this->BM('productlabellist')->where($hotprodata)->find();
        if (!$red) {
          $hotdata=$hotprodata;
          $hotdata['ProLabel']='1';
          $hotdata['LabelType']='0';
          $this->BM('productlabellist')->add($hotdata);
        }

        if ($type == 'add') {
          $hotprodata['Position']='HOT1';
          $res=$this->BM('productonhome')->add($hotprodata);
        } else {
          $res=$this->BM('productonhome')->where(array('ID'=>$type))->save($hotprodata);
        }
        if ($res) {
          $this->ajaxReturn(array('status' => 'true', 'info' => $res), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'false', 'info' => 'saveError'), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
      }
    }
    /////获得店铺二维码//////////
    public function GetShopCodeinfo(){
      $atoken = $_GET['apptoken'];
      $shopinfo=$this->UM('apptoken')->where("Apptoken='".$atoken."' AND Stoken!='0'")->find();
        ob_clean();
    		Vendor('PHPQR.phpqrcode');
        $data='https://'.$_SERVER['HTTP_HOST'].U('Home/Index/Index',array('stoken'=>$shopinfo['Stoken']));
        \QRcode::png($data,false,'L',4,'2');

    }







  }?>
