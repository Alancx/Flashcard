<?php
namespace Home\Controller;
class DistributionController extends BaseController {
    public $DisOpenId;
    public $Oss;
    public function _initialize()
    {
      parent::_initialize();
      import('Vendor.alioss.Oss');
      $this->Oss=new \Oss();
      $this->Delivery['DisID']=session('DisID');
      $this->Delivery['DisName']=session('DisName');
      $this->DisOpenId=$this->webParam['openid'];
    }
    ////////配送员个人中心///////////////////
    public function Index(){
      $DisInfo=$this->BM('distribution')->where(array('OpenId'=>$this->DisOpenId))->find();
      if (!$DisInfo) {
        $this->redirect('Distribution/Distribution');
      } else {
        if (($DisInfo['IsReceving']=='1')&&($DisInfo['IsBoss']=='1')) {
          $this->BM('distribution')->where(array('OpenId'=>$this->DisOpenId))->save(array('IsReceving'=>'0'));
          $DisInfo['IsReceving']='0';
        }
        //////////配送员可提金额//////////
        $getmoney=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','IsCuted'=>'0','OpenId'=>$this->DisOpenId))->SUM('PsGet');
        $this->assign('getmoney',$getmoney);
        $sqlStr="SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY do.GetDate desc) AS RowNumber, o.OrderId,o.RecevingName,o.RecevingPhone,(o.RecevingProvince+o.RecevingCity+o.RecevingArea+o.RecevingAddress) AS addr,do.IsSuccess,do.PsGet AS Price,do.Status FROM RS_DistributionForOrder do LEFT JOIN RS_Order o ON do.OrderId = o.OrderId WHERE do.IsDelete='0' AND do.IsSuccess='0' AND do.OpenId='".$this->DisOpenId."'  ) as c";

        $disingorder=$this->BM()->query($sqlStr);
        if ($disingorder) {
          foreach ($disingorder as $key => $value) {
            $sqlStr="SELECT (p.ProName+'('+pl.ProSpec1+')×'+convert(varchar(20),ol.[Count])) AS product FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId = p.ProId LEFT JOIN RS_ProductList pl ON ol.ProIdCard = pl.ProIdCard WHERE ol.OrderId='".$value['OrderId']."' ";
            $olist=$this->BM()->query($sqlStr);
            $disingorder[$key]['prolist']=$olist;
          }
          $this->assign('disingorder',$disingorder);
        } else {
          $this->assign('disingorder','NULLORDERINFO');
        }
        $this->assign('disinfo',$DisInfo);
        $this->assign('Title','配送员中心');
        $this->display();
      }
    }
///////////加载更多订单信息///////////////////////
public function getmoreorder(){
  if (IS_POST) {
    $type=$_POST['type'];
    $page=$_POST['page'];
    if ($type=='ordering') {
      $sqlStr="SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY do.GetDate desc) AS RowNumber, o.OrderId,o.RecevingName,o.RecevingPhone,(o.RecevingProvince+o.RecevingCity+o.RecevingArea+o.RecevingAddress) AS addr,do.IsSuccess,do.PsGet AS Price,do.Status FROM RS_DistributionForOrder do LEFT JOIN RS_Order o ON do.OrderId = o.OrderId WHERE do.IsDelete='0' AND do.IsSuccess='0' AND do.OpenId='".$this->DisOpenId."'  ) as c WHERE RowNumber>(20*".$page.")";
      $orderinfo=$this->BM()->query($sqlStr);
    } else {
      $sqlStr="SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY do.GetDate desc) AS RowNumber, o.OrderId,o.RecevingName,o.RecevingPhone,(o.RecevingProvince+o.RecevingCity+o.RecevingArea+o.RecevingAddress) AS addr,do.IsSuccess,do.PsGet AS Price,do.Status FROM RS_DistributionForOrder do LEFT JOIN RS_Order o ON do.OrderId = o.OrderId WHERE do.IsDelete='0' AND do.IsSuccess='1' AND do.OpenId='".$this->DisOpenId."'  ) as c WHERE RowNumber>(20*".$page.")";
      $orderinfo=$this->BM()->query($sqlStr);
    }
    if ($orderinfo) {
      foreach ($orderinfo as $key => $value) {
        $sqlStr="SELECT (p.ProName+'('+pl.ProSpec1+')×'+convert(varchar(20),ol.[Count])) AS product FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId = p.ProId LEFT JOIN RS_ProductList pl ON ol.ProIdCard = pl.ProIdCard WHERE ol.OrderId='".$value['OrderId']."' ";
        $olist=$this->BM()->query($sqlStr);
        $orderinfo[$key]['prolist']=$olist;
      }
      $this->ajaxReturn(array('status' => 'true','datainfo'=>$orderinfo), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false','datainfo'=>'nullorder'), 'JSON');
    }
  } else {
    $this->ajaxReturn(array('status' => 'false','datainfo'=>'postError'), 'JSON');
  }
}
/////////配送员注册页面///////////////////
public function Distribution(){
  $this->assign('Title','配送员注册');
  $this->display();
}
////////发送短信验证码/////////
public function sendphonecode(){
  $tel=$_POST['tel'];
  $str='0123456789';
  $psphonecode='';
  if ($this->BM('Distribution')->where(array('Phone'=>$tel))->find())
  {
    $this->ajaxReturn(array('status' => 'false','datainfo'=>'phonehaserror'), 'JSON');
    return;
  }
  for ($i=0; $i < 4; $i++) {
    $psphonecode.=substr(str_shuffle($str), mt_rand(0,9),1);
  }
  session('psphonecode',md5($psphonecode));
  $content='验证码：'.$psphonecode.'，您正在进行手机绑定，请在120秒内填写，如非本人操作，请忽略本短信。';
  $data['mobiles']=$tel;
  $data['content']=C('SMS_SIGN').$content;
  $res=$this->SendMessage($data);
  parse_str($res,$Atr);
  if ($Atr['result']=='0') {
    $this->ajaxReturn(array('status' => 'true','datainfo'=>$psphonecode), 'JSON');
  }else{
    $this->ajaxReturn(array('status' => 'false'), 'JSON');
  }
}
///////////上传头像//////////////////////
public function userimage(){
  if (IS_POST) {

    $file_name=uniqid('distribution');
    $ext=explode('/', $_FILES['iptxtp']['type'])[1];
    $res=$this->Oss->uploadFile($_FILES['iptxtp']['tmp_name'],$file_name.'.'.$ext,false);
    if ($res == false) {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
    }else{
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $res), 'JSON');
    }
    // $upload=new \Think\Upload();
    // $upload->maxSize=3145728;
    // $upload->savePath='./userH/';
    // $upload->exts=array('jpg','png','jpeg');
    // $info=$upload->uploadOne($_FILES['iptxtp']);
    // if (!$info) {
    //   $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
    // }else{
    //   $ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
    //   $image = new \Think\Image();
    //   $image->open('.'.$ImgUrls);
    //   $image->thumb(200,200)->save('./Upload/'.$info['savepath'].$info['savename']);
    //   $this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
    // }
  }
}
////////////////////上传身份证件/////////////
public function useridcard(){
  if (IS_POST) {

    $file_name=uniqid('distribution');
    $ext=explode('/', $_FILES['idcards']['type'])[1];
    $res=$this->Oss->uploadFile($_FILES['idcards']['tmp_name'],$file_name.'.'.$ext,false);
    if ($res == false) {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
    }else{
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $res), 'JSON');
    }



    // $upload=new \Think\Upload();
    // $upload->maxSize=3145728;
    // $upload->savePath='./userIdCard/';
    // $upload->exts=array('jpg','png','jpeg');
    // $info=$upload->uploadOne($_FILES['idcards']);
    // // var_dump($info);exit();
    // if (!$info) {
    //   $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'scError'), 'JSON');
    // }else{
    //   $ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
    //   $this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
    // }
  }
}
////////////保存配送员信息///////////
public function savedistribution(){
  if (IS_POST) {
    $phonecode=$_POST['PhoneCode'];
    if (md5($phonecode)==session('psphonecode')) {
      $psdata['TrueName']=$_POST['TrueName'];
      $psdata['IdCard']=$_POST['IdCard'];
      $psdata['IdImg']=$_POST['IdImg'];
      $psdata['Phone']=$_POST['Phone'];
      $psdata['OpenId']=$this->DisOpenId;
      $psdata['HeadImg']=$_POST['HeadImg'];
      $psdata['MemberId']=$this->webParam['uid'];
      $res=$this->BM('distribution')->add($psdata);
      if ($res) {
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError'), 'JSON');
    }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'codeError'), 'JSON');
    }
  } else {
    $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
  }
}
/////////添加门店列表页面///////////////////////
public function disaddshop(){
  $sqlStr="SELECT id,storename,Slogo,tel,(province+city+area+addr) AS addr,stoken FROM RS_Store WHERE id NOT IN (SELECT StoreId FROM RS_DistributionForStore WHERE OpenId='".$this->DisOpenId."') AND IsCheck='1' AND stoken!='0'";
  $shoplist=$this->BM()->query($sqlStr);
  $this->assign('shoplist',$shoplist);
    $sqlStr="SELECT ds.StoreId,ds.Status,s.storename,s.Slogo FROM RS_DistributionForStore ds LEFT JOIN RS_Store s ON ds.StoreId=s.id WHERE ds.OpenId='".$this->DisOpenId."' AND s.IsCheck='1'";
  $shophaslist=$this->BM()->query($sqlStr);
  $this->assign('hasshoplist',$shophaslist);
  $this->assign('Title','门店选择');
  $this->display();
}
/////////保存配送员选择的门店列表/////////////////////
public function saveshoplist(){
  if (IS_POST) {
    $shoplist=json_decode($_POST['sidlist'],true);
    $this->BM()->startTrans();
    $rec=true;
    foreach ($shoplist as $key => $value) {
      $savedata['StoreId']=$key;
      $savedata['OpenId']=$this->DisOpenId;
      $savedata['Status']='0';
      $savedata['stoken']=$value;
      if (!$this->BM('distributionforstore')->where(array('StoreId'=>$key,'OpenId'=>$this->DisOpenId))->find()) {
        $res=$this->BM('distributionforstore')->add($savedata);
        if (!$res) {
          $rec=false;
          break;
        }
      }
    }
    if ($rec) {
      $this->BM()->commit();
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
    } else {
      $this->BM()->rollback();
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError'), 'JSON');
    }
  } else {
    $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
  }
}

///////////配送员订单信息/////////////////////
public function disorder(){
  $sqlStr="SELECT o.OrderId,o.RecevingName,o.RecevingPhone,(o.RecevingProvince+o.RecevingCity+o.RecevingArea+o.RecevingAddress) AS addr,do.IsSuccess,o.Price FROM RS_DistributionForOrder do LEFT JOIN RS_Order o ON do.OrderId = o.OrderId WHERE do.IsDelete='0' AND do.OpenId='".$this->DisOpenId."' ORDER BY do.GetDate desc";
  $disorderinfo=$this->BM()->query($sqlStr);
  $this->assign('orderlist',$disorderinfo);
  $this->assign('Title','配送员订单');
  $this->display();
}
///////配送员完成订单配送处理///////////////////
public function savesendorder(){
  if (IS_POST) {
    $oid=$_POST['oid'];
    $type=$_POST['type'];
    if ($type=='0') {
      $oinfo=$this->BM()->table("RS_Order")->where("OrderId='%s'",$oid)->find();
  		$minfo=$this->BM()->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->find();
      $verify=$this->BM()->table('RS_DistributionForOrder')->where(array('OrderId'=>$oid,"IsDelete"=>'0'))->getField('Verify');
      $sinfo=$this->BM()->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->find();
      if ($oinfo['Status']=='2' || $oinfo['Status']=='3') {
        $res=true;
        $whres=true;
        $uppro=true;
        $this->BM()->startTrans();
        $res=$this->BM()->table('RS_DistributionForOrder')->where(array('OrderId'=>$oid,"IsDelete"=>'0'))->save(array("Status"=>'1'));
        if ($oinfo['Status']=='2'){
          $tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8).'_'.$sinfo['id'];
          $whres=$this->BM()->execute("UPDATE wh SET StockCount=StockCount-ol.Count,SalesCount=SalesCount+ol.Count,LastUpdateDate=GetDate() FROM {$tb_name} wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}'");
                  // 更新商品销量 和 数量字段
          $uppro=$this->BM()->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),SalesCount=SalesCount+(SELECT SUM(Count) FROM RS_OrderList WHERE OrderId=('".$oid."') AND a.ProId=RS_OrderList.ProId) FROM RS_Product a, RS_OrderList b WHERE b.ProId=a.ProId  AND b.OrderId=('".$oid."')");
        }


        if ($res && $whres && $uppro) {
          $this->BM()->commit();
          $smInfo=array(
              'touser'=>$sinfo['MsgRecever'], //必填
              'template_id'=>'DK4fXSIlocmLr552h1cTe4a4h8W4tNBx8F3M7p3ZIGM', //必填
              'first'=>array('value'=>'配送员已确认提货',color=>'#000000'), //必填
              'content'=>array(
                0=>array('value'=>$oid,'color'=>'#000000'),
                1=>array('value'=>$sinfo['storename'],'color'=>'#000000'),
                2=>array('value'=>date('Y-m-d H:i:s'),'color'=>'#000000'),
              ),  //必填
              'remark'=>array('value'=>'等待送达','color'=>'#000000'),
            );
          $ressm=$this->sendWxMessage($smInfo);



          $msg['mobiles']=$oinfo['RecevingPhone'];
  				$msg['content']='【'.$sinfo['storename'].'】您的订单：'.$oid.'您的订单配送员已成功提货,验证码:'.$verify.'包裹将很快送达您手中';
  				$this->SendMessage($msg);
          $this->ajaxReturn(array('status' => 'true', 'datainfo' => $oid), 'JSON');
        } else {
          $this->BM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveerror'), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'hadsuccess'), 'JSON');
      }
    } else {
      $data['IsSuccess']='1';
      $data['Status']='2';
  		$data['OverDate']=date('Y-m-d H:i:s',time());
  		$oinfo=$this->BM()->table("RS_Order")->where("OrderId='%s'",$oid)->find();
  		$minfo=$this->BM()->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->find();
      $verify=$this->BM()->table('RS_DistributionForOrder')->where(array('OrderId'=>$oid,'IsDelete'=>'0'))->getField('Verify');
  		if ($verify!=$_POST['code']) {
  			$this->ajaxReturn(array('status' => 'false', 'datainfo' => 'codeError'), 'JSON');
        return;
  		}
  		if ($oinfo['Status']=='2' || $oinfo['Status']=='3') {
  			$this->BM()->startTrans();
  			$ds=$ores=$mres=true;
  			$ds=$this->BM()->table('RS_DistributionForOrder')->where(array('OrderId'=>$oid,'IsDelete'=>'0'))->setField($data);

  			$ores=$this->BM()->table('RS_Order')->where("OrderId='%s'",$oid)->setField(array('Status'=>4,'GetDate'=>date('Y-m-d H:i:s',time())));

  			$mres=$this->BM()->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->setField("LastBuyTime",date('Y-m-d H:i:s',time()));
  			$nowTime = date("Y-m-d H:i:s", time());
  	        $ProIdCards=$this->BM()->table('RS_OrderList')->where("OrderId='%s'",$order_no)->getField('ProIdCard',true);
  	        $Counts=$this->BM()->table('RS_OrderList')->where("OrderId='%s'",$order_no)->getField('Count',true);
  	        $whres=true;
  	        $uppro=true;

  	        $sinfo=$this->BM()->table('RS_Store')->where("stoken='%s'",$oinfo['stoken'])->find();
  	        // if ($oinfo['Status']=='2') {
  		        // $tb_name=C('CKname').'.dbo.tb_wh'.substr($oinfo['token'], -8,8).'_'.$sinfo['id'];
  		        // $whres=$this->BM()->execute("UPDATE wh SET StockCount=StockCount-ol.Count,SalesCount=SalesCount+ol.Count,LastUpdateDate=GetDate() FROM {$tb_name} wh LEFT JOIN RS_OrderList ol ON wh.ProIdCard=ol.ProIdCard WHERE ol.OrderId='{$oid}'");
  		        //         // 更新商品销量 和 数量字段
  		        // $uppro=$this->BM()->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),SalesCount=SalesCount+(SELECT SUM(Count) FROM RS_OrderList WHERE OrderId=('".$oid."') AND a.ProId=RS_OrderList.ProId) FROM RS_Product a, RS_OrderList b WHERE b.ProId=a.ProId  AND b.OrderId=('".$oid."')");
  	        // }
            $this->LOGS('配送处理--->>>ds'.$ds.'__ores'.$ores.'__mres'.$mres.'__whres'.$whres.'__uppro'.$uppro);
  			if ($ds && $ores && $mres && $whres && $uppro) {
  				$this->BM()->commit();
  				$msg['mobiles']=$minfo['Phone'];
  				$msg['content']='【'.$sinfo['storename'].'】您的订单：'.$oid.'已完成配送并确认收货';
  				$this->SendMessage($msg);
          $msg['mobiles']=$sinfo['tel'];
  				$msg['content']='【'.$sinfo['storename'].'】您的订单：'.$oid.'已完成配送并确认收货';
  				$this->SendMessage($msg);
          $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
  			}else{

  				$this->BM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError'), 'JSON');
  			}
  		}else{
  			$this->ajaxReturn(array('status' => 'true', 'datainfo' => 'hadsuccess'), 'JSON');
  		}
    }
  } else {
    $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
  }
}
///////修改配送员是否接受订单信息////////////////////////
public function updatedistype(){
  if (IS_POST) {
    $stype=$_POST['type'];
    if ($stype=='0') {
      $sqlStr="UPDATE RS_Distribution SET IsReceving='1' WHERE OpenId='".$this->DisOpenId."'";
    } else {
      $sqlStr="UPDATE RS_Distribution SET IsReceving='0' WHERE OpenId='".$this->DisOpenId."'";
    }
    $res=$this->BM()->execute($sqlStr);
    if ($res) {
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'updateError'), 'JSON');
    }
  } else {
    $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
  }
}
public function disgetcashlist(){
  // $pagedata['alltotalmoney']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','IsCuted'=>'0','OpenId'=>$this->DisOpenId))->SUM('PsGet');
  //////////配送员配送订单数//////////
  $pagedata['alltotalorder']=$this->BM('distributionfororder')->where(array('IsDelete'=>'0','OpenId'=>$this->DisOpenId))->count();
  //////////配送员收益金额//////////
  $pagedata['alltotalmoney']=$this->BM('distributionfororder')->where(array('IsDelete'=>'0','OpenId'=>$this->DisOpenId))->SUM('PsGet');
  ////////配送员已提现金额//////////
  $pagedata['hadgetmoney']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','IsCuted'=>'3','OpenId'=>$this->DisOpenId))->SUM('PsGet');
  ////////配送员提现中金额//////////
  $pagedata['hasinggetmoney']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','OpenId'=>$this->DisOpenId))->where("IsCuted='1' OR IsCuted='2'")->SUM('PsGet');
  ////////未提现金额/////////
  $pagedata['getmoney']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','IsCuted'=>'0','OpenId'=>$this->DisOpenId))->SUM('PsGet');
  /////////未提现订单////////////
  $pagedata['getorder']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','IsCuted'=>'0','OpenId'=>$this->DisOpenId))->field('OrderId,PsGet,IsCuted,CONVERT(varchar(19),GetDate,120)AS Gdate,CONVERT(varchar(19),OverDate,120)AS Odate,stoken')->select();
  // var_dump($this->BM()->getlastsql());exit();
  /////////提现中订单////////////
  $pagedata['gcashingorder']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','OpenId'=>$this->DisOpenId))->where("IsCuted='1' OR IsCuted='2'")->field('OrderId,PsGet,IsCuted,CONVERT(varchar(19),GetDate,120)AS Gdate,CONVERT(varchar(19),OverDate,120)AS Odate,stoken')->select();
  /////////已提现订单////////////
  $pagedata['gcashendorder']=$this->BM('distributionfororder')->where(array('IsSuccess'=>'1','IsDelete'=>'0','Status'=>'2','IsCuted'=>'3','OpenId'=>$this->DisOpenId))->field('OrderId,PsGet,IsCuted,CONVERT(varchar(19),GetDate,120)AS Gdate,CONVERT(varchar(19),OverDate,120)AS Odate,stoken')->select();

  $pagedata['Title']='配送员提现';
  $this->assign($pagedata);
  $this->display();
}
//////////配送员提现提交////////
public function savecashorder(){
  if (IS_POST) {
    $orderinfo=json_decode($_POST['data'],true);
    $this->BM()->startTrans();
    $res=true;
    foreach ($orderinfo as $key => $value) {
      $olist="'".implode("','",explode(',',$value['olist']))."'";
      $cashdata['Money']=$value['money'];
      $cashdata['CreateDate']=date('Y-m-d H:i:s',time());
      if ($value['money']==0) {
        $cashdata['Status']='2';
        $orderdata['IsCuted']='3';
      } else {
        $cashdata['Status']='0';
        $orderdata['IsCuted']='1';
      }
      $cashdata['token']=$this->webParam['token'];
      $cashdata['stoken']=$key;
      $cashdata['OpenId']=$this->DisOpenId;
      $cashdata['OrderList']=$value['olist'];
      if (!$this->BM('distributioncashdetail')->add($cashdata)) {
        $res=false;
        break;
      }
      if (!$this->BM('distributionfororder')->where("OrderId IN (".$olist.")")->save($orderdata)) {
        $res=false;
        break;
      }
    }
    if ($res) {
      $this->BM()->commit();
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
    } else {
      $this->BM()->rollback();
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'saveError'), 'JSON');
    }
  } else {
    $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
  }
}

/////////发送微信模板消息/////////
	public function sendWxMessage($info)
	{
		import("Vendor.Wechat.WXTemplate");

		$wxPInfo=$this->UM('wxpayset')->where(array('token'=>$this->token))->find();
		$wxchatinfo['appid']=$wxPInfo['appid'];
		$wxchatinfo['appsecert']=$wxPInfo['appsecret'];
		$sendwxchat=new \WXTemplate($wxchatinfo);
		$res=$sendwxchat->sendTemplate($info);
    $this->LOGS('微信消息---结果'.$res);
		return $res;

	}




}?>
