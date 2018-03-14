<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends BaseController{
  public function _initialize(){
    parent::_initialize();
  }

  // 购物车数据修改
  public function updatecart(){
    if (IS_POST) {
      $pid = $_POST['pid'];
      $plid = $_POST['plid'];
      $nums = $_POST['nums'];
      $pspec = $_POST['pspec'];
      $cid = $_POST['cid'];
      $type = $_POST['type'];
      $numtype = $_POST['numtype'];
      $filename = session('memberId').".json";
      $cartinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
      if ($type == 'updatecart') {
        $keyinfo= $pid.'-'.$plid;
        if ($nums == '0') {
          unset($cartinfo[$keyinfo]);
        } else {
          $cartinfo[$keyinfo]['pid'] = $pid;
          $cartinfo[$keyinfo]['plid'] = $plid;
          $cartinfo[$keyinfo]['pspec'] = $pspec;
          $cartinfo[$keyinfo]['nums'] = $nums;
          $cartinfo[$keyinfo]['cid'] = $cid;
          $cartinfo[$keyinfo]['numtype'] = $numtype;
          $cartinfo[$keyinfo]['stoken'] = $this->stoken;
        }
        file_put_contents('Public/json/'.$filename,json_encode($cartinfo));
      } elseif ($type == 'cleancart') {
        foreach ($cartinfo as $key => $value) {
          if ($value['stoken'] == $this->stoken) {
            unset($cartinfo[$key]);
          }
        }
        file_put_contents('Public/json/'.$filename, json_encode($cartinfo));
      }

      $this->ajaxReturn(array('status'=>'true','info'=>'SUCCESS'),'JSON');
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }

  // 支付成功处理方法
  public function paysuccess($oid){
    $oid = $oid;
    // $oid = 'E201801091601422380';
    $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();

    $sdate = date("Y-m-d 00:00:00",time());
    $edate = date("Y-m-d 23:59:59",time());
    $whereArray['Status'] = array('NOT IN','1,9');
    $whereArray['CreateDate'] = array('ELT',$edate);
    $whereArray['CreateDate'] = array('EGT',$sdate);
    $whereArray['stoken'] = array('EQ',$oinfo['stoken']);
    $maxnum = M()->table('RS_Order')->where($whereArray)->count();
    $maxnum = $maxnum+1;
    if ($maxnum>=0 && $maxnum<=9) {
      $maxnum = '000'.$maxnum;
    } elseif ($maxnum>=10 && $maxnum<=99) {
      $maxnum = '00'.$maxnum;
    } else {
      $maxnum = '0'.$maxnum;
    }
    // $this->psuccesssendwxmessage($oid);
    $res = M()->table('RS_Order')->where(array('OrderId'=>$oid,'Status'=>'1'))->save(array('Status'=>'2','ShortOid'=>$maxnum));
    if ($res) {
      $this->setordereat($oid);
      $this->psuccesssendwxmessage($oid);
      return true;
    } else {
      return false;
    }
  }

  // 支付成功发送微信消息
  public function psuccesssendwxmessage($oid){
    $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
    $sinfo=M()->table('RS_Store')->where(array('stoken'=>$oinfo['stoken']))->find();
    $olinfo = M()->table('RS_OrderList')->where(array('OrderId'=>$oid))->select();
    $minfo = M()->table('RS_Member')->where(array('MemberId'=>$oinfo['MemberId']))->find();
    $pnameinfo='';
    foreach ($olinfo as $key => $value) {
      if ($pnameinfo =='') {
        $pnameinfo = $value['ProName'].'('.$value['Spec'].')';
      } else {
        $pnameinfo = $pnameinfo.';'.$value['ProName'].'('.$value['Spec'].')';
      }
    }
    if (!empty($sinfo['MsgRecever'])) {
      $smInfo=array(
          'touser'=>$sinfo['MsgRecever'], //必填
          'template_id'=>'-CZ3fb1hys-E3zHvjF88XiRmSW8MowBM-wXJKKIHmQU', //必填
          'first'=>array('value'=>'新订单通知',color=>'#000000'), //必填
          'content'=>array(
            0=>array('value'=>date("Y-m-d H:i:s",time()),'color'=>'#000000'),
            1=>array('value'=>$pnameinfo,'color'=>'#000000'),
            2=>array('value'=>$oinfo['ShortOid'],'color'=>'#000000'),
          ),  //必填
          'remark'=>array('value'=>'请及时处理','color'=>'#000000'),
        );
      $this->sendWxMessage($smInfo);
    }

    if (!empty($minfo['OpenId'])) {
      $smInfo=array(
          'touser'=>$minfo['OpenId'], //必填
          'template_id'=>'-CZ3fb1hys-E3zHvjF88XiRmSW8MowBM-wXJKKIHmQU', //必填
          'first'=>array('value'=>'新订单通知',color=>'#000000'), //必填
          'content'=>array(
            0=>array('value'=>date("Y-m-d H:i:s",time()),'color'=>'#000000'),
            1=>array('value'=>$pnameinfo,'color'=>'#000000'),
            2=>array('value'=>$oinfo['ShortOid'],'color'=>'#000000'),
          ),  //必填
          'remark'=>array('value'=>'请及时处理','color'=>'#000000'),
        );
      $this->sendWxMessage($smInfo);
    }
  }

  // 修改越吃越便宜
  public function setordereat($oid){
      $oid = $oid;
      $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
      $olinfo = M()->query("SELECT distinct ProId, SUM(Count) as Count FROM RS_OrderList WHERE OrderId ='".$oid."' GROUP BY ProId");
      $res= true;
      $ref= true;

      $rew= true;

      M()->startTrans();
      // $res = M()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('Status'=>'4'));
      foreach ($olinfo as $key => $value) {
        M()->table('RS_Product')->where(array('ProId'=>$value['ProId']))->setInc('SalesCount',$value['Count']);
        $pem = M()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
        if ($pem) {
          $red = true;
          $mpem = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->find();
          if ($mpem) {
            if ($mpem['Level'] >=$pem['Level'] ) {
              $red = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
            } else {
              $red=M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId']))->setInc('Level',1);
            }
          } else {
            $red = M()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['MemberId'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
          }
          if ($red === false) {
            $ref = false;
            break;
          }
        }
      }

      if (!empty($oinfo['SceneMember'])) {
        foreach ($olinfo as $key => $value) {
          $pem = M()->table('RS_Eatmore')->where(array('ProId'=>$value['ProId']))->find();
          if ($pem) {
            $red = true;
            $mpem = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->find();
            if ($mpem) {
              if ($mpem['Level'] >=$pem['Level'] ) {
                $red = M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->save(array('Level'=>1));
              } else {
                $red=M()->table('RS_MemberEatmore')->where(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId']))->setInc('Level',1);
              }
            } else {
              $red = M()->table('RS_MemberEatmore')->add(array('MemberId'=>$oinfo['SceneMember'],'ProId'=>$value['ProId'],'Level'=>2,'stoken'=>$oinfo['stoken']));
            }
            if ($red === false) {
              $rew = false;
              break;
            }
          }
        }
      }
      if ($res && $ref && $rew) {
        M()->commit();
      } else {
        M()->rollback();
      }
  }

  // 就餐码
  Public function geteatcode(){
    $oid = $_GET['oid'];
    $type = $_GET['type'];
    $stoken = $_GET['stoken'];
    ob_clean();
    vendor('PHPQR.phpqrcode');
    $qrcodeImg='<img src="'.\QRcode::png('http://'.$_SERVER['HTTP_HOST'].U('Seller/Base/getwxparam',array('oid'=>$oid,'type'=>$type,'stoken'=>$stoken)),false,'L',4,'2').'"/>';
    echo $qrcodeImg;
  }





}?>
