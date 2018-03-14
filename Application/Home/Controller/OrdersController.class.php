<?php
namespace Home\Controller;
use Think\Controller;
class OrdersController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
  }

  Public function submitorder(){
    // 购物车信息
    $sharememberid = cookie('sharerID');
    if (!empty($sharememberid) && $sharememberid != session('memberId')) {
      $mid=$sharememberid;
    } else {
      $mid=session('memberId');
    }
    $filename = session('memberId').".json";
    $cartjson = file_get_contents('public/json/'.$filename);
    $cartsdata = json_decode($cartjson,true);
    $totalprice = 0;
    foreach ($cartsdata as $key => $value) {
      if ($value['stoken'] == $this->stoken) {
        $sqlStr="SELECT p.ProName,p.ProTitle,p.ProLogoImg,p.Price,p.ProId,p.ClassType,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice from RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId = pl.ProId LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON ps.ProId = p.ProId WHERE  p.stoken='".$this->stoken."' AND p.ProId ='".$value['pid']."' AND pl.ProIdCard='".$value['plid']."' AND pl.IsDelete='0' ";
        // var_dump($sqlStr);exit();
        $pinfo = M()->query($sqlStr);
        $pinfo = $pinfo[0];
        if ($pinfo) {
          $ltype = $pinfo['ltype'];
          $pinfo['OldPrice'] = $pinfo['Price'];
          if ($pinfo['Level'] !=null && $pinfo['Level']!='' ) {
            if (($ltype != null) && ($pinfo['ltype'] != '')) {
              if ($ltype >= $pinfo['Level']) {
                $pinfo['Price'] = $pinfo['Lv'.$pinfo['Level']];
              } else {
                $pinfo['Price'] = $pinfo['Lv'.$ltype];
              }
            } else {
              $pinfo['Price'] = $pinfo['Lv1'];
            }
          } else {
            if ($pinfo['sprice']!=null && $pinfo['sprice']!='') {
              $pinfo['Price'] = $pinfo['sprice'];
            }
          }
          $pinfo['snums'] = $value['nums'];
          $cartArray[$key] = $pinfo;
        }
        // var_dump($cartArray[$key]['Price']);
        $totalprice = $totalprice+($cartArray[$key]['Price'] * $cartArray[$key]['snums']);
      }
    }
    if ($cartArray) {
      $pagedata['cartinfo'] = json_encode($cartArray);
    } else {
      $pagedata['cartinfo'] = null;
    }
    // var_dump($totalprice);exit();
    $sinfo = M()->table("RS_Store")->where("stoken='%s'",$this->stoken)->find();	//获取店面信息
    $pagedata['sinfo'] = $sinfo;
    $pagedata['orderid'] = "E".date("YmdHis",time()).rand(1000,9999);
    // 可用红包信息

    $sqlStr = "SELECT mc.ID,mc.MemberId, mc.Status,c.CouponId,c.Type,c.Rules,c.CouponName,c.ExpiredDate,CONVERT(varchar(19),c.ExpiredDate,120) AS edate FROM RS_MemberCoupon mc LEFT JOIN RS_Coupon c ON mc.CouponId = c.CouponId WHERE mc.MemberId='".session('memberId')."' AND mc.Status ='0' AND c.stoken='".$this->stoken."' AND c.ExpiredDate > GETDATE() AND mc.CouponCount>0 ORDER BY c.ExpiredDate ASC";

    $couinginfo = M()->query($sqlStr);
    if ($couinginfo && count($couinginfo)>0) {
			foreach ($couinginfo as $key => $value) {
				if ($value['Type']=='2') {
					$rulestemp = explode("/",$value['Rules']);
					$couinginfo[$key]['Rules'] = $rulestemp[1];
					$couinginfo[$key]['Totalrules'] = $rulestemp[0];
          if ($rulestemp[0] >= $totalprice) {
            unset($couinginfo[$key]);
          }
				}
			}
		}
    $pagedata['cinglist']=$couinginfo;
    $pagedata['cingcount']=count($couinginfo);

    // 备注固定话术
    $pagedata['hualist']=M()->table('RS_DefaultEval')->where(array('stoken'=>$this->stoken,'type'=>'2'))->select();

    // 进店红包
		$inredinfo=cookie('inredinfo');
		if ($inredinfo !=null) {
			$filename = $this->stoken.'inredinfo.json';
			$tempinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
			if ($tempinfo!=null) {
				$tempredprice = $tempinfo['price'];
				if ($tempredprice>0) {
					$redprice = $tempredprice;
				} else {
					$redprice = null;
				}
			} else {
				$redprice = null;
			}
		} else {
			$redprice = null;
		}
    // var_dump($redprice);exit();
		$pagedata['redprice']=$redprice;

    $pagedata['Title']='提交订单';
    $this->assign($pagedata);
    $this->display();
  }

  Public function orderinfos(){
    $oid = $_GET['oid'];
    $oinfo =M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
    $pagedata['oinfo'] = $oinfo;
    $olinfo =M()->table('RS_OrderList')->where(array('OrderId'=>$oid))->select();
    $pagedata['olinfo'] =json_encode($olinfo);
    $sinfo = M()->table("RS_Store")->where("stoken='%s'",$oinfo['stoken'])->find();	//获取店面信息
    $pagedata['sinfo'] = $sinfo;
    $pagedata['orderid'] = $oid;
    if($oinfo['Status']=='4'){
      //分享信息处理
      // $menuList = '"menuItem:share:appMessage","menuItem:share:timeline"';
      // $pagedata['menulist']=$menuList;
      // $pagedata['shareType']='O';
      // $pagedata['shareMemberId']=session('memberId');
      // $pagedata['ShareId']=$oid;
      //分享信息处理end
    }
    $pagedata['Title']='订单详情';
    $this->assign($pagedata);
    $this->display();
  }



  public function createorder(){
    if (IS_POST) {
      $prosinfo = $_POST['prosinfo'];
      $prosinfo = json_decode($prosinfo,true);
      $redid = $_POST['redid'];
      $redprice = $_POST['redpackprice'];
      $inredprice = $_POST['inredprice'];
      $oid = $_POST['orderid'];
      $eatingnum = $_POST['eatingnum'];
      $remarkcontent = $_POST['remarkcontent'];
      $ods = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
      $mem = M()->table('RS_Member')->where(array('MemberId'=>session('memberId')))->find();
      if (!$ods) {
        $res = true;
        $red = true;
        $rec = true;
        $pcount=0;
        $ptotalprice =0;
        $ototalprice=0;
        M()->startTrans();
        foreach ($prosinfo as $key => $value) {
          $pcount = $pcount + $value['snums'];
          $ptotalprice = $ptotalprice + ($value['Price'] * $value['snums']);
          $ototalprice = $ototalprice + ($value['OldPrice'] * $value['snums']);
          $olinfo['OrderId']= $oid;
          $olinfo['ProId']= $value['ProId'];
          $olinfo['ProIdCard']= $value['ProIdCard'];
          $olinfo['Price']= $value['Price'];
          $olinfo['Count']= $value['snums'];
          $olinfo['Money']= $value['Price'] * $value['snums'];
          $olinfo['DisMoney']=($value['OldPrice'] - $value['Price']) * $value['snums'];
          $olinfo['Spec']= $value['ProSpec1'];
          $olinfo['Remarks']= '';
          $olinfo['stoken']= $this->stoken;
          $olinfo['ProName']= $value['ProName'];
          $olinfo['ProLogoImg']= $value['ProLogoImg'];
          $olinfo['ProTitle']= $value['ProTitle'];
          $saveolinfo = M()->table('RS_OrderList')->add($olinfo);
          if(!$olinfo){
            $red  = false;
            break;
          }
        }
        $oinfo['OrderId']= $oid;
        $oinfo['MemberId']= $mem['MemberId'];
        $oinfo['RecevingName']= $mem['MemberName'];
        $oinfo['RecevingProvince']= 'NODATA';
        $oinfo['RecevingCity']= 'NODATA';
        $oinfo['RecevingArea']= 'NODATA';
        $oinfo['RecevingAddress']= 'NODATA';
        $oinfo['RecevingPost']= 'NODATA';
        $oinfo['RecevingPhone']= $mem['MemberId'];;
        $oinfo['Count']= $pcount;
        if (!empty($redid)) {
          $oinfo['Coupon']= $redprice;
          $oinfo['CouponListId']= $redid;
          $oinfo['Price']= $ptotalprice - $redprice;
        } else {
          $oinfo['Price']= $ptotalprice;
        }
        if ($inredprice!='' || $inredprice != null) {
          $oinfo['Price'] = $oinfo['Price'] - $inredprice;
          $oinfo['InredPrice'] = $inredprice;
        }
        if ( $oinfo['Price'] <=0 ) {
          $oinfo['Price']= '0';
        }
        $oinfo['DisMoney']=$ototalprice - $oinfo['Price'];
        $sharememberid = cookie('sharerID');
        if (!empty($sharememberid) && $sharememberid != session('memberId')) {
          $oinfo['SceneMember']= $sharememberid;
        }
        $tableid = cookie('tableID');
        if (!empty($tableid)) {
          $oinfo['TableId']= $tableid;
        }
        $oinfo['EatingNums']= $eatingnum;
        $oinfo['MessageByBuy']= $remarkcontent;
        $oinfo['Status']= '1';
        $oinfo['PayName']= 'T';
        $oinfo['OpenId']= session('openid');
        $oinfo['stoken']= $this->stoken;
        $oinfo['token']= $this->token;
        $oinfo['CreateDate']= date("Y-m-d H:i:s",time());
        $oinfo['LastUpdateDate']= date("Y-m-d H:i:s",time());
        $res = M()->table('RS_Order')->add($oinfo);
        // 红包信息
        if (!empty($redid)) {
          $rec =M()->table('RS_MemberCoupon')->where(array('MemberId'=>session('memberId'),'CouponId'=>$redid))->setDec('CouponCount',1);
        }
        if ($res && $red && $rec) {
          M()->commit();
          $filename = session('memberId').".json";
          $cartinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
          foreach ($cartinfo as $key => $value) {
            if ($value['stoken'] == $this->stoken) {
              unset($cartinfo[$key]);
            }
          }
          file_put_contents('Public/json/'.$filename, json_encode($cartinfo));
          cookie('tableID',null);
          cookie('inredinfo',null);
          $this->ajaxReturn(array('status'=>'true','info'=>'SUCCESS'),'JSON');
        } else {
          M()->rollback();
          $this->ajaxReturn(array('status'=>'false','info'=>'SAVEERROR'),'JSON');
        }
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'ORDERHASD'),'JSON');
      }
    } else {
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }

  //分享的订单页面
  public function shareorderpage(){
    $shopstoken = $_GET['stoken'];
    $SID = $_GET['SID'];
    $sinfo = M()->table('RS_Store')->where(array('stoken'=>$shopstoken))->find();
    $pagedata['sinfo']=$sinfo;
    $meminfo= M()->table('RS_Member')->where(array('MemberId'=>session('memberId')))->find();
    $getcoupondata=array(
      'ID'=>$SID,
      'Inshare'=>session('memberId'),
    );
    $res = A('Share')->inshare($getcoupondata);
    // var_dump($res);exit();
    if ($res['status'] == 'success') {
      $couponId = $res['CouponId'];
      if ($couponId != '' && $couponId != null) {
        $couponinfo = M()->table('RS_Coupon')->where(array('CouponId'=>$couponId,'stoken'=>$shopstoken))->find();
        if ($couponinfo) {
          $pagedata['coninfo']=$couponinfo;
        } else {
          $pagedata['coninfo']='1';
        }
      } else {
        $pagedata['coninfo']='1';
      }
    } else {
      $pagedata['coninfo']='1';
    }
    $pagedata['SID']=$SID;
    $pagedata['shareorder']='true';
    $pagedata['minfo']=$meminfo;
    $pagedata['Title']='分享红包';
    $this->assign($pagedata);
    $this->display();

  }

  // 支付成功分享订单信息
  public function shareorderpages(){
    $oid=$_GET['oid'];
    $oinfo = M()->table('RS_Order')->where(array('OrderId'=>$oid))->find();
    if ($oinfo) {
      // if ($oinfo['DisMoney']>0 || $oinfo['DisMoney']<0) {
        $couponinfo = M()->table('RS_Coupon')->where(array('Forsharer'=>'1','stoken'=>$oinfo['stoken']))->find();
        if ($couponinfo) {
          if ($couponinfo['Type']=='2') {
            $rulestemp = explode("/",$couponinfo['Rules']);
            $couponinfo['Rules'] = $rulestemp[1];
            $couponinfo['Totalrules'] = $rulestemp[0];
          }
        }
        $pagedata['couponinfo']=$couponinfo;
        // if ($couponinfo) {
          // $sqlStr="SELECT DISTINCT ol.ProId,ol.ProName,ol.ProTitle,ol.ProLogoImg,ol.Price,p.NumType FROM RS_Product p LEFT JOIN RS_OrderList ol ON ol.ProId = p.ProId WHERE OrderId ='".$oid."'";
          $sqlStr = "SELECT p.ProId,p.ProName,p.ProTitle,p.ProLogoImg,p.Price,p.NumType FROM RS_Eatmore em LEFT JOIN RS_Product p ON em.ProId = p.ProId WHERE em.stoken='".$oinfo['stoken']."' AND p.ProId !='' AND p.IsShelves ='1'";
          $tempol=M()->query($sqlStr);
          if ($tempol) {
            $olinfo = $tempol;
          } else {
            $sqlStr = "SELECT ProId,ProName,ProTitle,ProLogoImg,Price,NumType FROM RS_Product WHERE stoken = 'nPyEo49507333966' AND IsShelves ='1'";
            $olinfo=M()->query($sqlStr);
          }
          if ($olinfo) {
            $shopinfo = M()->table('RS_Store')->where(array('stoken'=>$oinfo['stoken']))->find();
            $this->assign('shopstoken',$oinfo['stoken']);
            $pagedata['oid'] = $oid;
            $pagedata['olinfo'] = $olinfo;
            $pagedata['shopinfo'] = $shopinfo;

            //分享信息处理
            $menuList = 'menuItem:share:appMessage,menuItem:share:timeline';
            $pagedata['menulist']=$menuList;
            $pagedata['shareType']='O';
            $pagedata['shareMemberId']=session('memberId');
            $pagedata['ShareId']=$oid;
            //分享信息处理end

            $pagedata['Title']='订单分享';
            $this->assign($pagedata);
            $this->display();
          } else {
            $this->redirect('User/Userorders');
          }
        // } else {
        //   $this->redirect('User/Userorders');
        // }
      // } else {
      //   $this->redirect('User/Userorders');
      // }
    } else {
      $this->redirect('User/Userorders');
    }
  }


}?>
