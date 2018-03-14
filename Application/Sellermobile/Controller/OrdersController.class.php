<?php
namespace Sellermobile\Controller;
use Think\Controller;
class OrdersController extends CommonController {
  ////////订单管理///////////////
  public function index(){
    $this->assign('Title','订单管理');
    $this->display();
  }
  ////////全部订单///////////////
  public function orders(){
    /////待付
    $wherearray='';
    $wherearray['Status']=array('eq','1');
    $wherearray['Count']=array('gt',0);
    $wherearray['token']=array('eq',$this->token);
    $wherearray['stoken']=array('eq',$this->stoken);
    $dfordercont=$this->BM('order')->where($wherearray)->count();
    $pagecountdata['dfcount']=$dfordercont;
    //待发货
    $wherearray='';
    $wherearray['Status']=array('in','2,3');
    $wherearray['Count']=array('gt',0);
    $wherearray['token']=array('eq',$this->token);
    $wherearray['stoken']=array('eq',$this->stoken);
    $dfhordercont=$this->BM('order')->where($wherearray)->count();
    $pagecountdata['dfhcount']=$dfhordercont;
    //已发货
    // $wherearray='';
    // $wherearray['Status']=array('eq','3');
    // $wherearray['Count']=array('gt',0);
    // $wherearray['token']=array('eq',$this->token);
    // $wherearray['stoken']=array('eq',$this->stoken);
    // $yfhordercont=$this->BM('order')->where($wherearray)->count();
    // $pagecountdata['yfhcount']=$yfhordercont;
    $pagecountdata['yfhcount']=0;
    //待提货
    // $wherearray='';
    // $wherearray['Status']=array('eq','2');
    // $wherearray['RecevingPost']=array('eq','ZT');
    // $wherearray['Count']=array('gt',0);
    // $wherearray['token']=array('eq',$this->token);
    // $wherearray['stoken']=array('eq',$this->stoken);
    // $dthordercont=$this->BM('order')->where($wherearray)->count();
    // $pagecountdata['dthcount']=$dthordercont;
    $pagecountdata['dthcount']=0;
    //退款中
    $wherearray='';
    $wherearray['Status']=array('eq','5');
    $wherearray['Count']=array('gt',0);
    $wherearray['token']=array('eq',$this->token);
    $wherearray['stoken']=array('eq',$this->stoken);
    $tkzordercont=$this->BM('order')->where($wherearray)->count();
    $pagecountdata['tkzcount']=$tkzordercont;
    //已完成
    $wherearray='';
    $wherearray['Status']=array('in','4,10');
    $wherearray['Count']=array('gt',0);
    $wherearray['token']=array('eq',$this->token);
    $wherearray['stoken']=array('eq',$this->stoken);
    // $wherearray['CONVERT(VARCHAR(10),GetDate,120)']=array('eq',date('Y-m-d',time()));
    $ywcordercont=$this->BM('order')->where($wherearray)->count();

    $pagecountdata['ywccount']=$ywcordercont;
    $this->assign($pagecountdata);
    $this->assign('Title','全部订单');
    $this->display();
  }
  ////////未处理的退款订单///////////////
  public function tkorders(){
    $sqlStr="SELECT a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.TableId,a.ShortOid,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId, b.MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='5' AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."' AND a.MemberId!='".$this->department."' AND IsRcheck='0' ORDER BY a.CreateDate DESC ";
    $data=$this->BM()->query($sqlStr);

    foreach ($data as $key => $value) {
      $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlStr);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    $this->assign('tkorders',$dataorder);
    $this->assign('Title','退款订单');
    $this->display();
  }
  //////////////////处理退款订单///////////////////
  public function settkorder(){
    $oid=$_POST['oid'];
    $vercode=$_POST['vercode'];
    $otype=$_POST['type'];
    $verify= new \Think\Verify();
    if ($otype=='1') {
      if(!$verify->check($vercode,'')){
        $this->ajaxReturn(array('status' => 'flase', 'info' => 'vererror'), 'JSON');
        exit();
      }
    }

    $res=$this->BM('order')->where(array('OrderId'=>$oid))->save(array('IsRcheck'=>$otype));
    if ($res) {
      if ($otype=='1') {
        $filename = './Public/message.json';
        $res = json_decode(file_get_contents($filename),true);
        $messageData['mobiles'] = $res['refund'];
        $messageData['content'] = "您有新的订单退款申请,请及时处理";
        $this->SendMessage($messageData);
      }
      $this->ajaxReturn(array('status' => 'true', 'info' => $oid), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'saveerror'), 'JSON');
    }
  }
  /////////未完成的订单///////////////////
  public function incompleteorders(){

    $sqlStr=" SELECT a.OrderId,CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Status,a.Count,a.PayName,case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,a.Freight,b.MemberId, (CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in ('2','3','5') AND  a.Count>0 AND a.PayName='T' AND a.token='".$this->token."' AND a.stoken='".$this->stoken."'";

    $data=$this->BM()->query($sqlStr);
    foreach ($data as $key => $value) {
      $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlStr);
      $value['prolist']=$oderpro;
      $orderlist[$key]=$value;
    }
    $this->assign('orderlist',$orderlist);
    $this->assign('Title','未完成订单明细');
    $this->display();
  }
  public function getmoreorder_1(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId, (CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='1' AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    // var_dump($sqlju);exit();
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    // var_dump($dataorder);exit();
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  public function getmoreorder_2(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('2','3') AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  public function getmoreorder_3(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='3' AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  public function getmoreorder_4(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='2' AND a.RecevingPost='ZT' AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  public function getmoreorder_5(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='5' AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  public function getmoreorder_6(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status in('4','10') AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  public function getmoreorder_7(){
    $page=$_POST['page'];
    $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber,a.TableId,a.ShortOid,a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId,(CASE WHEN a.MemberId='".$this->department."' THEN '门店自助结算' ELSE b.MemberName END) AS MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='10' AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
    $data=$this->BM()->query($sqlju);
    foreach ($data as $key => $value) {
      $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlju);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    if($dataorder){
      $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
    }
  }
  /////////////获得验证码////////////////////
  public function getverify(){
    $verify= new \Think\Verify();
    $verify->codeSet = '0123456789';
    $verify->fontSize = 40;
    $verify->length = 4;
    $verify->entry();
  }

  ////////未完成待完成订单///////////////
  public function noendorders(){
    $sqlStr="SELECT a.OrderId,
    CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.TableId,a.ShortOid,a.MessageByBuy,a.stoken,a.Count,a.PayName,
    case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Freight,b.MemberId, b.MemberName,a.SceneMember from RS_Order a
    LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE (a.status='2' or a.status='3' ) AND  a.Count>0 AND
    a.token='".$this->token."' AND a.stoken='".$this->stoken."' AND a.MemberId!='".$this->department."' AND IsRcheck='0' ORDER BY a.CreateDate DESC";
    $data=$this->BM()->query($sqlStr);

    foreach ($data as $key => $value) {
      $sqlStr="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Count,a.ProLogoImg,a.ProName,a.ProTitle,a.Spec
      FROM RS_OrderList a
      where a.OrderId='".$value['OrderId']."'";
      $oderpro=$this->BM()->query($sqlStr);
      $value['prolist']=$oderpro;
      $dataorder[$key]=$value;
    }
    $this->assign('orders',$dataorder);
    // var_dump($dataorder);exit();
    $this->assign('Title','待完成订单');
    $this->display();
  }
  public function setendorder(){
    if(IS_POST){
      $oid = $_POST['oid'];
      $oinfo = $this->BM()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
      $olinfo = $this->BM()->query("SELECT distinct ProId, SUM(Count) as Count FROM RS_OrderList WHERE OrderId ='".$oid."' GROUP BY ProId");
      $res= true;
      $ref= true;
      $rew= true;
      $this->BM()->startTrans();
      $res = $this->BM()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('Status'=>'4'));
      // foreach ($olinfo as $key => $value) {
      //   $this->BM()->table('RS_Product')->where(array('ProId'=>$value['ProId']))->setInc('SalesCount',$value['Count']);
      //   $pem = $this->BM()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
      //   if ($pem) {
      //     $red = true;
      //     $mpem = $this->BM()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->find();
      //     if ($mpem) {
      //       if ($mpem['Level'] >=$pem['Level'] ) {
      //         $red = $this->BM()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
      //       } else {
      //         $red=$this->BM()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->setInc('Level',1);
      //       }
      //     } else {
      //       $red = $this->BM()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
      //     }
      //     if ($red === false) {
      //       $ref = false;
      //       break;
      //     }
      //   }
      // }

      // if (!empty($oinfo['SceneMember'])) {
      //   foreach ($olinfo as $key => $value) {
      //     $pem = $this->BM()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
      //     if ($pem) {
      //       $red = true;
      //       $mpem = $this->BM()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->find();
      //       if ($mpem) {
      //         if ($mpem['Level'] >=$pem['Level'] ) {
      //           $red = $this->BM()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
      //         } else {
      //           $red=$this->BM()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->setInc('Level',1);
      //         }
      //       } else {
      //         $red = $this->BM()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
      //       }
      //       if ($red === false) {
      //         $rew = false;
      //         break;
      //       }
      //     }
      //   }
      // }
      if ($res && $ref && $rew) {
        $this->BM()->commit();
        $this->sendendwxmesg($oid);
        $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status'=>'false','info'=>'updateError'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }

  // 订单完成发送微信消息
  public function sendendwxmesg($oid){
    $oinfo = $this->BM()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
    $minfo=$this->BM()->table('RS_Member')->where(array('MemberId'=>$oinfo['MemberId']))->find();
    $olinfo = $this->BM()->table('RS_OrderList')->where(array('OrderId'=>$oid))->select();
    $pnameinfo='';
    foreach ($olinfo as $key => $value) {
      if ($pnameinfo =='') {
        $pnameinfo = $value['ProName'].'('.$value['Spec'].')';
      } else {
        $pnameinfo = $pnameinfo.';'.$value['ProName'].'('.$value['Spec'].')';
      }
    }
    if (!empty($minfo['OpenId'])) {
      $smInfo=array(
        'touser'=>$minfo['OpenId'], //必填
        'template_id'=>'-CZ3fb1hys-E3zHvjF88XiRmSW8MowBM-wXJKKIHmQU', //必填
        'first'=>array('value'=>'订单完成通知',color=>'#000000'), //必填
        'content'=>array(
          0=>array('value'=>date("Y-m-d H:i:s",time()),'color'=>'#000000'),
          1=>array('value'=>$pnameinfo,'color'=>'#000000'),
          2=>array('value'=>$oinfo['ShortOid'],'color'=>'#000000'),
        ),  //必填
        'remark'=>array('value'=>'欢迎下次光临!','color'=>'#000000'),
      );
      $this->sendWxMessage($smInfo);
    }
  }



}?>
