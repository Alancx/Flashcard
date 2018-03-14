<?php
namespace Sellermobile\Controller;
use Think\Controller;
class UserController extends CommonController {
  public function Index(){
    $shopdata=$this->BM('Store')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'IsCheck'=>'1'))->find();
    $this->assign('shopinfo',$shopdata);
    $this->assign('footerSign',1);
    $this->assign('Title','个人中心');
    $this->display();
  }
///////银行卡管理//////
  public function bankcards(){
    $bankinfo=$this->BM('merchantbank')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
    if ($bankinfo) {
      $this->assign('type','update');
    } else {
      $this->assign('type','add');
    }
    $this->assign('bankinfo',$bankinfo);
    $this->assign('Title','银行卡管理');
    $this->display();
  }

  ////////发送短信验证码/////////
  public function sendmsmcode(){
    $tel=$_POST['tel'];
    $str='0123456789';
    $vcode='';
    for ($i=0; $i < 4; $i++) {
      $vcode.=substr(str_shuffle($str), mt_rand(0,9),1);
    }
    session('smsbcode',md5($vcode));
    $content='验证码：'.$vcode.'，您正在进行添加银行卡，请在120秒内填写，如非本人操作，请忽略本短信。';
    $data['mobiles']=$tel;
    $data['content']=$content;
    $res=$this->SendMessage($data);
    parse_str($res,$Atr);
    if ($Atr['result']=='0') {
      $this->ajaxReturn(array('status' => 'true'), 'JSON');
    }else{
      $this->ajaxReturn(array('status' => 'false'), 'JSON');
    }
  }
  ///////保存银行卡信息//////
  public function savebanks(){
    if (IS_POST) {
      $type=$_POST['type'];
      $smsbcode=$_POST['smsbcode'];
      if (md5($smsbcode)==session('smsbcode')) {
        $savedata['IdType']=$_POST['IdType'];
        $savedata['IdCard']=$_POST['IdCard'];
        $savedata['IdName']=$_POST['IdName'];
        $savedata['BankName']=$_POST['BankName'];
        $savedata['tel']=$_POST['tel'];
        $savedata['token']=$this->token;
        $savedata['stoken']=$this->stoken;
        if ($type=='add') {
          $res=$this->BM('merchantbank')->add($savedata);
        } else {
          $res=$this->BM('merchantbank')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->save($savedata);
        }
        if ($res) {
          $this->ajaxReturn(array('status' => 'true','datainfo' => 'SUCCESS'), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'false','datainfo' => 'saveError'), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'codeError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false','datainfo' => 'postError'), 'JSON');
    }
  }
  ////////店铺二维码名片//////////////
  public function shopqrcode(){
    array_push($this->wxJSSDKConfigArray['jsApiList'],'onMenuShareAppMessage');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'onMenuShareTimeline');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'showMenuItems');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'getLocation');
    $shopdata=$this->BM('Store')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'IsCheck'=>'1'))->find();
    if ($shopdata['Slogo']) {
      $sharimg=$shopdata['Slogo'];
    } else {
      $sharimg='/public/Sellermobile/icon/add_factory.png';
    }
    $showlist='wx.showMenuItems({menuList: ["menuItem:share:appMessage","menuItem:share:timeline"]});';
    $this->assign('showlist',$showlist);
    $this->assign('wxJSSDKConfigStr',json_encode($this->wxJSSDKConfigArray));
    $this->assign('shopname',$shopdata['storename']);
    $this->assign('shopdesc',$shopdata['Descinfo']);
    $this->assign('shareImg','http://'.$_SERVER['HTTP_HOST'].$sharimg);
		$this->assign('shareUrl','http://'.$_SERVER['HTTP_HOST'].U('Home/Index/Index',array('stoken'=>$this->stoken)));
    $this->assign('Title','店铺二维码');
    $this->display();
  }

  /////////配送员管理////////////////
  public function distributionlist(){
    $this->assign('Title','配送员管理');
    $this->display();
  }
  /**
   * 配送信息
   */
  public function AboutPs(){
    $pagedata['pagesize']=$pagesize=10;
    if (IS_POST) {
      $pagestart=$_POST['page'];
      if ($_POST['type']=='getpsing') {
        $psing=$this->BM()->table('RS_DistributionForOrder df')->join("LEFT JOIN RS_Distribution db ON df.OpenId=db.OpenId")->where("df.stoken='{$this->stoken}' and df.token='{$this->token}' and IsSuccess=0 and df.IsDelete=0")->limit($pagestart.','.$pagesize)->field("df.OrderId,CONVERT(varchar(20),GetDate,120) as GetDate,db.TrueName+'/'+db.Phone as Psinfo,df.Status as status")->select();
        if ($psing) {
          $msg['status']='success';
          $msg['data']=$psing;
          if(count($psing)==$pagesize){
            $msg['info']='ok';
          }else{
            $msg['info']='nomore';
          }
        }else{
          $msg['status']='error';
          $msg['info']='没有更多了';
        }
      }elseif ($_POST['type']=='getpsend') {
        $psend=$this->BM()->table('RS_DistributionForOrder df')->join("LEFT JOIN RS_Distribution db ON df.OpenId=db.OpenId")->where("df.stoken='{$this->stoken}' and df.token='{$this->token}' and IsSuccess=1 and df.IsDelete=0")->limit($pagestart.','.$pagesize)->field("df.OrderId,CONVERT(varchar(20),GetDate,120) as GetDate,CONVERT(varchar(20),OverDate,120) as OverDate,db.TrueName+'/'+db.Phone as Psinfo")->select();
        // var_dump($psend);
        if ($psend) {
          $msg['status']='success';
          $msg['data']=$psend;
          if(count($psend)==$pagesize){
            $msg['info']='ok';
          }else{
            $msg['info']='nomore';
          }
        }else{
          $msg['status']='error';
          $msg['info']='没有更多了';
        }
      }elseif ($_POST['type']=='gook') {
        $id=$_POST['id'];
        if ($this->BM()->table('RS_DistributionForStore')->where("ID=%d",$id)->setField("Status",1)) {
          $msg['status']='success';
        }else{
          $msg['status']='error';
        }
      }elseif ($_POST['type']=='ref') {
        $id=$_POST['id'];
        if ($this->BM()->table('RS_DistributionForStore')->where("ID=%d",$id)->setField("Status",2)) {
          $msg['status']='success';
        }else{
          $msg['status']='error';
        }
      }elseif ($_POST['type']=='getpsping') {
        $psping=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=0 and stoken='{$this->stoken}'")->limit($pagestart.','.$pagesize)->field("ds.ID,db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
        if ($psping) {
          $msg['status']='success';
          $msg['data']=$psping;
          if (count($psping)==$pagesize) {
              $msg['info']='ok';
          }else{
              $msg['info']='nomore';
          }
        }else{
          $msg['status']='error';
          $msg['info']='没有更多了';
        }
      }elseif ($_POST['type']=='getpspend') {
        $pspend=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=1 and stoken='{$this->stoken}'")->limit($pagestart.','.$pagesize)->field("db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
        if ($pspend) {
          $msg['status']='success';
          $msg['data']=$pspend;
          if (count($pspend)==$pagesize) {
            $msg['info']='ok';
          }else{
            $msg['info']='nomore';
          }
        }else{
          $msg['status']='error';
          $msg['info']='没有更多了';
        }
      }
      echo json_encode($msg);
    }else{
      $psing=$this->BM()->table('RS_DistributionForOrder df')->join("LEFT JOIN RS_Distribution db ON df.OpenId=db.OpenId")->where("df.stoken='{$this->stoken}' and df.token='{$this->token}' and IsSuccess=0 and df.IsDelete=0")->limit('0,'.$pagesize)->field("df.OrderId,CONVERT(varchar(20),GetDate,120) as GetDate,db.TrueName+'/'+db.Phone as Psinfo,df.Status as status")->select();
      $psend=$this->BM()->table('RS_DistributionForOrder df')->join("LEFT JOIN RS_Distribution db ON df.OpenId=db.OpenId")->where("df.stoken='{$this->stoken}' and df.token='{$this->token}' and IsSuccess=1 and df.IsDelete=0")->limit('0,'.$pagesize)->field("df.OrderId,CONVERT(varchar(20),GetDate,120) as GetDate,CONVERT(varchar(20),OverDate,120) as OverDate,db.TrueName+'/'+db.Phone as Psinfo")->select();
      // var_dump($psing);
      $pagedata['psing']=$psing;
      $pagedata['psend']=$psend;
      //待审核人员
      $psping=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=0 and stoken='{$this->stoken}'")->limit('0,'.$pagesize)->field("ds.ID,db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
      $pspend=$this->BM()->table('RS_DistributionForStore ds')->join("LEFT JOIN RS_Distribution  db ON ds.OpenId=db.OpenId")->where("ds.Status=1 and stoken='{$this->stoken}'")->limit('0,'.$pagesize)->field("db.Phone,db.TrueName,db.IdCard,db.IdImg,CONVERT(varchar(20),ds.AskDate,120) as AskDate,db.HeadImg")->select();
      $pagedata['pspend']=$pspend;
      $pagedata['psping']=$psping;
      $this->assign($pagedata);
      $this->display();
    }
  }
  /////////删除配送消息从新派单///
  public function delPs(){
    $oid=$_POST['oid'];

    $psopenid=$this->BM('distributionfororder')->where(array('OrderId'=>$oid,'IsDelete'=>'0'))->find();
    $psopenid=$psopenid['OpenId'];
    $red=$this->BM('distributionfororder')->where(array('OrderId'=>$oid,'IsDelete'=>'0'))->save(array('IsDelete'=>'1'));
    $red=true;
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
      }

      $this->ajaxReturn(array('status'=>'true','info'=>'true'));
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'false'));
    }
  }
  /////////配送员提现申请/////////////////
  public function districash(){
    $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$this->token."' AND dc.stoken='".$this->stoken."' AND dc.Status='0' ORDER BY dc.ID DESC";
    $pagedata['dshdata']=$this->BM()->query($sqlStr);//待审核

    $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$this->token."' AND dc.stoken='".$this->stoken."' AND dc.Status='1' ORDER BY dc.ID DESC";
    $pagedata['yshdata']=$this->BM()->query($sqlStr);//已审核

    $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$this->token."' AND dc.stoken='".$this->stoken."' AND dc.Status='2' ORDER BY dc.ID DESC";
    $pagedata['ywcdata']=$this->BM()->query($sqlStr);//已完成

    $sqlStr="SELECT CONVERT(float(53),dc.Money,120) as Money,CONVERT(varchar(20),dc.CreateDate,102) as CDate,CONVERT(varchar(20),dc.CreateDate,108) as Ctime,dc.Status,d.TrueName,dc.ID FROM RS_DistributionCashDetail dc LEFT JOIN RS_Distribution d ON dc.OpenId = d.OpenId WHERE dc.Money>0 AND dc.token='".$this->token."' AND dc.stoken='".$this->stoken."' AND dc.Status='3' ORDER BY dc.ID DESC";
    $pagedata['yjjdata']=$this->BM()->query($sqlStr);//已拒绝

    $this->assign($pagedata);
    $this->assign('Title','配送员提现申请');
    $this->display();
  }
  /**
   * 同意/拒绝申请
   */
  public function setpscheck(){
    $type=$_POST['type'];
    $id=$_POST['id'];
    if ($type=='pass') {
      $Status=1;
      $IsCuted=2;
    }
    if ($type=='refund') {
      $Status=3;
      $IsCuted=0;
    }
    $OrderIds=$this->BM()->table('RS_DistributionCashDetail')->where("ID=%d",$id)->getField("OrderList");
    $this->BM()->startTrans();
    $dc=$this->BM()->table('RS_DistributionCashDetail')->where("ID=%d",$id)->setField('Status',$Status);
    $do=$this->BM()->table('RS_DistributionForOrder')->where("OrderId in ('".str_replace(',', "','", $OrderIds)."')")->setField('IsCuted',$IsCuted);
    if ($do && $dc) {
      $this->BM()->commit();
      $this->ajaxReturn(array('status'=>'true','info'=>'sure'));
    }else{
      $this->BM()->rollback();
      $this->LOGS("配送提现申请审核失败 do=$do...dc=$dc--->>>".$this->BM()->getlastsql());
      $this->ajaxReturn(array('status'=>'false','info'=>'ERROR'));
    }
  }
//////////小店主页设置//////////////////
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
        $res=$this->BM('homeimg')->add($homelbdata);
      } else {
        $res=$this->BM('homeimg')->where(array('ID'=>$homedata['hid']))->save($homelbdata);
      }
    } elseif ($homedata['stype']=='1') {
      $hotprodata['ProId']=$homedata['hpid'];
      $hotprodata['token']=$this->token;
      $hotprodata['stoken']=$this->stoken;
      $red= $this->BM('productlabellist')->where($hotprodata)->find();

      if (!$red) {
        $hotdata=$hotprodata;
        $hotdata['ProLabel']='1';
        $hotdata['LabelType']='0';
        $this->BM('productlabellist')->add($hotdata);
      }

      if ($homedata['type']=='add') {
        $hotprodata['Position']='HOT'.$homedata['hcount'];
        $res=$this->BM('productonhome')->add($hotprodata);
      } else {
        $res=$this->BM('productonhome')->where(array('ID'=>$homedata['hid']))->save($hotprodata);
      }
    }elseif ($homedata['stype']=='2') {
      $newprodata['ProId']=$homedata['hpid'];
      $newprodata['token']=$this->token;
      $newprodata['stoken']=$this->stoken;
      if ($homedata['type']=='add') {
        $newprodata['Position']='NEW'.$homedata['hcount'];
        $res=$this->BM('productonhome')->add($newprodata);
      } else {
        $res=$this->BM('productonhome')->where(array('ID'=>$homedata['hid']))->save($newprodata);
      }
    }

    if ($res) {
      $this->ajaxReturn(array('status'=>'true','info'=>$res));
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'false'));
    }
  } else {
    //平台设置的小店的热卖商品
    $sqlStr="SELECT p.ProId,poh.[Position],(CASE WHEN (p.stoken='0' AND mp.Price IS NOT NULL) THEN mp.Price ELSE p.PriceRange
     END) AS PriceRange,p.ProName,p.ProTitle,p.ProLogoImg,p.Price FROM
     RS_Product p LEFT JOIN  RS_ProductOnHome poh ON poh.ProId = p.ProId
     LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
      WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON
      p.ProId = mp.ProId WHERE p.token='".$this->token."' AND p.IsShelves=1 AND
    (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."' OR poh.stoken='0') AND
    poh.[Position]='SHOP_HOT' ORDER BY poh.[Position]";
    $prohot=$this->BM()->query($sqlStr);
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
    $pronew=$this->BM()->query($sqlStr);
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
    $pagedata['selhotinfo']=$this->BM()->query($sqlStr);
    /////小店设置的新品商品
    $sqlStr="SELECT p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,p.Price,poh.[Position],poh.ID,
    (CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_ProductOnHome poh
    LEFT JOIN RS_Product p ON p.ProId = poh.ProId LEFT JOIN
    (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros
    WHERE token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp
    ON poh.ProId=mp.ProId
    WHERE poh.[Position] LIKE '%NEW%' AND poh.token='".$this->token."'
    AND poh.stoken='".$this->stoken."' ORDER BY poh.[Position]";
    $pagedata['selnewinfo']=$this->BM()->query($sqlStr);
   /////////////////////////////////////////////////
   ///小店基本信息
   $pagedata['shopinfo']=$this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
   ///平台设置的小店的轮播图
   $pagedata['homeimg']=$this->BM()->table('RS_HomeImg')->where("token='%s' and stoken='%s'",array($this->token,0))->find();
   ///小店自己设置的轮播图
   $pagedata['lbdata']=$this->BM('homeimg')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'IsShow'=>1))->order('ID')->select();
   /////热卖商品//////////
   $sqlStr="SELECT p.ProId,p.ProName,p.ProLogoImg,(CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros WHERE token = '".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token ='".$this->token."' AND (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."') AND p.IsShelves='1'";
   $this->assign('hotproinfo',$this->BM()->query($sqlStr));
   /////新品商品//////////
   $sqlStr="SELECT p.ProId,p.ProName,p.ProLogoImg,
   (CASE WHEN mp.Price IS NULL THEN p.PriceRange ELSE mp.Price END) AS PriceRange
   FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM
   RS_MerPros WHERE token = '".$this->token."' AND stoken='".$this->stoken."' GROUP BY ProId,token,stoken) mp
   ON p.ProId = mp.ProId LEFT JOIN RS_ProductLabelList pll ON p.ProId = pll.ProId
   WHERE p.token ='".$this->token."' AND (p.stoken='".$this->stoken."' OR mp.stoken='".$this->stoken."')
   AND pll.ProLabel=2 AND pll.stoken='".$this->stoken."' AND pll.token='".$this->token."' AND p.IsShelves='1' ";
   $this->assign('newproinfo',$this->BM()->query($sqlStr));
  ///////////////////////////////////////////////
    $pagedata['Title']='店铺首页设置';
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
			$res=$this->BM('homeimg')->where(array('ID'=>$hid))->delete();
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
////////////////////轮播图图片/////////////
public function lbimage(){
  if (IS_POST) {
    $upload=new \Think\Upload();
    $upload->maxSize=10485760;
    $upload->savePath='./Home/';
    $upload->exts=array('jpg','png','jpeg');
    $info=$upload->uploadOne($_FILES['selimg']);
    if (!$info) {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
    }else{
      $ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
    }
  }
}

public function shopsetinfo(){
  $shopimgs = $this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->find();
  $shopimgs = $shopimgs['ShopImgs'];
  $shopimgs = unserialize(stripcslashes($shopimgs));
  $pagedata['shopimgs'] =$shopimgs;
  $pagedata['Title'] ='店铺展示图';
  $this->assign($pagedata);
  $this->display();
}

public function shopimage(){
  if (IS_POST) {
    $upload=new \Think\Upload();
    $upload->maxSize=10485760;
    $upload->savePath='./Uoloads/';
    $upload->exts=array('jpg','png','jpeg');
    $info=$upload->uploadOne($_FILES['selimg']);
    if (!$info) {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
    }else{
      $ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
    }
  }
}

public function shopimagesave(){
  if (IS_POST) {
    $simglist =json_decode($_POST['shopimgs'],true);
    // var_dump();exit()
    $res = $this->BM('store')->where(array('token'=>$this->token,'stoken'=>$this->stoken))->save(array('ShopImgs'=>serialize($simglist)));
    if ($res) {
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError'), 'JSON');
    }
  } else {
    $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
  }
}

public function settable(){
  if (IS_POST) {
    $tid = $_POST['tid'];
    $tname = $_POST['tname'];
    $type = $_POST['type'];
    if ($type == '1') {
      if ($tid =='add') {
        $savedata['Tname'] = $tname;
        $savedata['stoken'] = $this->stoken;
        $res = $this->BM('tableinfo')->add($savedata);
      } else {
        $res = $this->BM('tableinfo')->where(array('ID'=>$tid))->save(array('Tname'=>$tname));
      }
    } else {
      $res = $this->BM('tableinfo')->where(array('ID'=>$tid))->delete();
    }
    if ($res) {
      $tinfo = $this->BM('tableinfo')->where(array('stoken'=>$this->stoken))->order('Tname asc')->select();
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $tinfo), 'JSON');
    } else {
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'Error'), 'JSON');
    }
  } else {
    $tinfo = $this->BM('tableinfo')->where(array('stoken'=>$this->stoken))->order('Tname asc')->select();
    $pagedata['tinfo'] =$tinfo;
    $pagedata['Title'] ='桌号设置';
    $this->assign($pagedata);
    $this->display();
  }
}
public function logos(){
  $list=$this->BM('store')->where(array('stoken'=>$this->stoken))->find();
  $this->assign('list',$list);
  $this->assign('Title','基本信息');
  $this->display('User/logo');
}
public function logo(){
    if (IS_POST) {
        $upload=new \Think\Upload();
        $upload->maxSize=10485760;
        $upload->savePath='./Upload/';
        $upload->exts=array('jpg','png','jpeg');
        $info=$upload->uploadOne($_FILES['files']);
        if (!$info) {
          $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
        }else{
          $ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
          $data['Slogo']=$ImgUrls;
          $list=$this->BM('store')->where(array('stoken'=>$this->stoken))->save($data);
          $this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
        }
    }
}

public function Vernotes(){
  $this->assign('Title','版本信息');
  $this->display();
}


public function setremark(){
  if (IS_POST) {
    $tid = $_POST['tid'];
    $tname = $_POST['tname'];
    $type = $_POST['type'];
    if ($type == '1') {
      if ($tid =='add') {
        $savedata['content'] = $tname;
        $savedata['stoken'] = $this->stoken;
        $savedata['token'] = $this->token;
        $savedata['type'] = '2';
        $res = $this->BM('defaulteval')->add($savedata);
      } else {
        $res = $this->BM('defaulteval')->where(array('ID'=>$tid))->save(array('content'=>$tname));
      }
    } else {
      $res = $this->BM('defaulteval')->where(array('ID'=>$tid))->delete();
    }
    if ($res) {
      $rinfo = $this->BM('defaulteval')->where(array('stoken'=>$this->stoken,'type'=>'2'))->select();
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $rinfo), 'JSON');
    } else {
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'Error'), 'JSON');
    }
  } else {
    $rinfo = $this->BM('defaulteval')->where(array('stoken'=>$this->stoken,'type'=>'2'))->select();
    $pagedata['rinfo'] =$rinfo;
    $pagedata['Title'] ='备注设置';
    $this->assign($pagedata);
    $this->display();
  }
}

public function shopsetmessage(){
  // var_dump($this->stoken);exit();
  $shopinfo = $this->BM('store')->where(array('stoken'=>$this->stoken))->find();
  // var_dump($shopinfo);exit();
  $pagedata['shopinfo'] = $shopinfo;
  $this->assign($pagedata);
  $this->display();
}



}
?>
