<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
  }
  // 个人中心首页
  public function Index(){
    $mid = session('memberId');
    $mdata = M()->table('RS_Member')->where(array('MemberId'=>$mid))->find();
    $pagedata['minfo'] = $mdata;
    $sinfo = M()->table('RS_Store')->where(array('stoken'=>$this->stoken))->find();
    $pagedata['sinfo'] = $sinfo;
    $pagedata['Title']='我的';
    $this->assign($pagedata);
    $this->display();
  }
  // 我的订单
  public function Userorders(){
    $mid = session('memberId');
    $sqlStr="SELECT o.OrderId,o.stoken,o.Status,o.ShortOid,o.[Count],o.Price,ol.[Count] as pcount,ol.ProName,o.IsEvaluation,s.Slogo,s.storename,o.TableId FROM RS_Order o LEFT JOIN RS_OrderList ol ON o.OrderId = ol.OrderId LEFT JOIN RS_Store s ON o.stoken = s.stoken WHERE o.MemberId ='".$mid."' AND o.Status IN ('1','2','3','4','5','8') ORDER BY o.CreateDate DESC";
    $orderinfo = M()->query($sqlStr);
    // var_dump($orderinfo);exit();
    if ($orderinfo) {
      $orderArray = array();
      foreach ($orderinfo as $key => $value) {
        if (empty($orderArray[$value['OrderId']]) || $orderArray[$value['OrderId']] == '') {
          $orderArray[$value['OrderId']]['oid'] = $value['OrderId'];
          $orderArray[$value['OrderId']]['status'] = $value['Status'];
          $orderArray[$value['OrderId']]['count'] = $value['Count'];
          $orderArray[$value['OrderId']]['price'] = $value['Price'];
          $orderArray[$value['OrderId']]['sname'] = $value['storename'];
          $orderArray[$value['OrderId']]['slogo'] = $value['Slogo'];
          $orderArray[$value['OrderId']]['stoken'] = $value['stoken'];
          $orderArray[$value['OrderId']]['isevaluation'] = $value['IsEvaluation'];
          $orderArray[$value['OrderId']]['tableid'] = $value['TableId'];
          $orderArray[$value['OrderId']]['soid'] = $value['ShortOid'];
          $orderArray[$value['OrderId']]['plist'] = array();
          array_push($orderArray[$value['OrderId']]['plist'],array('pname'=>$value['ProName'],'pnums'=>$value['pcount']));
        } else {
          array_push($orderArray[$value['OrderId']]['plist'],array('pname'=>$value['ProName'],'pnums'=>$value['pcount']));
        }
      }
      $pagedata['orderinfo']=$orderArray;
    } else {
      $pagedata['orderinfo']=null;
    }
    $pagedata['Title']='订单';
    $this->assign($pagedata);
    $this->display();
  }
  // 我关注的店铺
  public function myshops(){
    $mid = session('memberId');
    $sqlStr = "SELECT s.Slogo,s.storename,mc.ID,s.city,s.area,s.addr,s.province,s.stoken FROM RS_MemberCollect mc LEFT JOIN RS_Store s ON mc.stoken = s.stoken WHERE mc.MemberId='".$mid."' AND mc.IsType='SHOP'";
    $shopinfo = M()->query($sqlStr);
    $pagedata['shopinfo']=$shopinfo;
    $pagedata['Title']='我的关注';
    $this->assign($pagedata);
    $this->display();
  }
  // 我收藏的商品
  public function mycollect(){
    $mid = session('memberId');
    $sqlStr = "SELECT p.ProName,p.ProLogoImg,p.Price,s.storename,mc.ID FROM RS_MemberCollect mc LEFT JOIN RS_Product p ON mc.ProId = p.ProId LEFT JOIN RS_Store s ON mc.stoken = s.stoken WHERE mc.MemberId='".$mid."' AND mc.IsType='PRO'";
    $proinfo = M()->query($sqlStr);
    $pagedata['proinfo']=$proinfo;
    $pagedata['Title']='我的收藏';
    $this->assign($pagedata);
    $this->display();
  }
  // 我的红包
  public function myredpack(){
    // 有效的红包

    $sqlStr = "SELECT mc.MemberId, mc.Status,mc.CouponCount,c.CouponId,c.Type,c.Rules,c.CouponName,c.ExpiredDate,CONVERT(varchar(19),c.ExpiredDate,120) AS edate,s.storename,s.Slogo FROM RS_MemberCoupon mc LEFT JOIN RS_Coupon c ON mc.CouponId = c.CouponId LEFT JOIN RS_Store s ON c.stoken = s.stoken WHERE mc.MemberId='".session('memberId')."' AND mc.Status ='0' AND c.ExpiredDate > GETDATE() AND mc.CouponCount>0 ORDER BY c.ExpiredDate ASC";
    $couinginfo = M()->query($sqlStr);

    if ($couinginfo && count($couinginfo)>0) {
			foreach ($couinginfo as $key => $value) {
				if ($value['Type']=='2') {
					$rulestemp = explode("/",$value['Rules']);
					$couinginfo[$key]['Rules'] = $rulestemp[1];
					$couinginfo[$key]['Totalrules'] = $rulestemp[0];
				}
			}
    }
    $pagedata['cinglist']=$couinginfo;

    //失效的红包

    $sqlStr = "SELECT mc.MemberId, mc.Status,mc.CouponCount,c.CouponId,c.Type,c.Rules,c.CouponName,c.ExpiredDate,CONVERT(varchar(19),c.ExpiredDate,120) AS edate,s.storename,s.Slogo FROM RS_MemberCoupon mc LEFT JOIN RS_Coupon c ON mc.CouponId = c.CouponId LEFT JOIN RS_Store s ON c.stoken = s.stoken WHERE mc.MemberId='".session('memberId')."' AND mc.Status ='0' AND c.ExpiredDate <= GETDATE() AND mc.CouponCount>0 ORDER BY c.ExpiredDate ASC";
    $couendinfo = M()->query($sqlStr);
    if ($couendinfo && count($couendinfo)>0) {
			foreach ($couendinfo as $key => $value) {
				if ($value['Type']=='2') {
					$rulestemp = explode("/",$value['Rules']);
					$couendinfo[$key]['Rules'] = $rulestemp[1];
					$couendinfo[$key]['Totalrules'] = $rulestemp[0];
				}
			}
    }
    $pagedata['cendlist']=$couendinfo;

    $pagedata['Title']='我的红包';
    $this->assign($pagedata);
    $this->display();
  }

  // 删除关注或者收藏
  public function delusercollect(){
    if (IS_POST) {
      $mcid = $_POST['mcid'];
      $res = M()->table('RS_MemberCollect')->where(array('ID'=>$mcid))->delete();
      if ($res) {
        $this->ajaxReturn(array('status'=>'true','info'=>$mcid),'JSON');
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'DELERROR'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }

  // 获取订单状态
  public function getorderstatus(){
    if (IS_POST) {
      $oid = $_POST['oid'];
      $res = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
      if ($res) {
        $this->ajaxReturn(array('status'=>'true','info'=>$res['Status']),'JSON');
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'DELERROR'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }

  // 获取订单状态
  public function setorderstatus(){
    if (IS_POST) {
      $oid = $_POST['oid'];
      $status = $_POST['status'];
      $res = M()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('Status'=>$status));
      if ($res) {
        $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'DELERROR'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }

  // 打开订单评价
  public function userevaluation(){
    $oid = $_GET['oid'];
    $stoken = $_GET['stoken'];
    // 门店信息
    $sinfo = M()->table('RS_Store')->where(array('stoken'=>$stoken))->find();
    $pagedata['sinfo']=$sinfo;
    // 商品信息
    $olinfo = M()->table('RS_OrderList')->where(array('OrderId'=>$oid))->field('OrderListId,OrderId,ProId,Money,Price,[Count],ProName,ProLogoImg')->select();
    $pagedata['olinfo']=$olinfo;
    $pagedata['oid']=$oid;
    $pagedata['Title']='订单评价';
    $this->assign($pagedata);
    $this->display();
  }

  // 提交订单评价
  public function setuserevaluation(){
    if (IS_POST) {
      $oid = $_POST['oid'];
      $shopevalinfo=$_POST['shopevalinfo'];
      $prosevalinfo=$_POST['prosevalinfo'];
      $res = true;
      $red = true;
      $ref = true;
      M()->startTrans();
      // 店铺评论信息保存
      if ($shopevalinfo) {
        $shopsavedata=array(
          'ProId'=>$shopevalinfo['stoken'],
          'OrderId'=>$oid,
          'MemberId'=>session('memberId'),
          'Content'=>$shopevalinfo['content'],
          'Label'=>'SHOPS',
          'ClassScore'=>$shopevalinfo['prostart'],
          'ServiceScore'=>$shopevalinfo['shoptart'],
          'stoken'=>$shopevalinfo['stoken'],
          'token'=>$this->token,
        );
        $res=M()->table('RS_ProductEvaluation')->add($shopsavedata);
      }
      // 商品评论信息保存
      if($prosevalinfo){
        foreach ($prosevalinfo as $key => $value) {
          $prosavedata=array(
            'ProId'=>$value['pid'],
            'OrderId'=>$oid,
            'MemberId'=>session('memberId'),
            'Content'=>$value['content'],
            'Label'=>'PROS',
            'ClassScore'=>$value['start'],
            'stoken'=>$value['stoken'],
            'token'=>$this->token,
          );
          $rez=M()->table('RS_ProductEvaluation')->add($prosavedata);
          $rex=M()->table('RS_OrderList')->where(array('OrderListId'=>$value['olid']))->save(array('IsEvaluation'=>'1'));
          if (!$rez || !$rex ) {
            $red=false;
            break;
          }
        }
      }
      $ref=M()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('IsEvaluation'=>'1'));
      if($res && $red && $ref){
        M()->commit();
        $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
      }else{
        M()->rollback();
        $this->ajaxReturn(array('status'=>'false','info'=>'saveError'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTError'),'JSON');
    }
  }

// 用户基本信息设置
public function setuserinfo(){
  $mid = session('memberId');
  $minfo = M()->table('RS_Member')->where(array('MemberId'=>$mid))->find();
  $pagedata['minfo']=$minfo;
  $pagedata['Title']='用户设置';
  $this->assign($pagedata);
  $this->display();
}

// 保存用户手机号
public function setuserphone(){
  if (IS_POST) {
  $phone = $_POST['phone'];
  $res= m()->table('RS_Member')->where(array('MemberId'=>session('memberId')))->save(array('Phone'=>$phone));
  if ($res) {
    $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
  } else {
    $this->ajaxReturn(array('status'=>'false','info'=>'saveError'),'JSON');
  }
  } else {
    $this->ajaxReturn(array('status'=>'false','info'=>'POSTError'),'JSON');
  }
}

// 确认完成订单
public function setorderend(){
  if(IS_POST){
    $oid = $_POST['oid'];
    $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
    // $olinfo = M()->table('RS_OrderList')->where(array('OrderId'=>$oid))->select();
    $olinfo = M()->query("SELECT distinct ProId, SUM(Count) as Count FROM RS_OrderList WHERE OrderId ='".$oid."' GROUP BY ProId");
    $res= true;
    $ref= true;

    $rew= true;

    M()->startTrans();
    $res = M()->table('RS_Order')->where(array('OrderId'=>$oid))->save(array('Status'=>'4'));
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
      // M()->rollback();
      $this->ajaxReturn(array('status'=>'true','info'=>'success'),'JSON');
    } else {
      M()->rollback();
      $this->ajaxReturn(array('status'=>'false','info'=>'updateError'),'JSON');
    }
  } else {
    $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
  }
}

public function Vernotes(){
  $pagedata['Title']='版本更新说明';
  $this->assign($pagedata);
  $this->display();
}












}?>
