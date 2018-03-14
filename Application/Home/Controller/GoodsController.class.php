<?php
namespace Home\Controller;
use Think\Controller;
class GoodsController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
  }
  // 商品详情
  Public function goods(){
    $SID=$_GET['SID'];
    $res = A('Share')->inshare(array(
      'ID'=>$SID,
      'Inshare'=>session('memberId'),
    ));
    // var_dump($res);exit();
    if ($res['status'] == 'success') {
      if ($res['errinfo']=='s') {
        $pagedata['coninfo']='1';
      } else{
        $couponId = $res['CouponId'];
        if ($couponId != '' && $couponId != null) {
          $couponinfo = M()->table('RS_Coupon')->where(array('CouponId'=>$couponId,'stoken'=>$this->stoken))->find();
          if ($couponinfo) {
            $pagedata['coninfo']=json_encode($couponinfo);
          } else {
            $pagedata['coninfo']='1';
          }
        } else {
          $pagedata['coninfo']='1';
        }
      }
    } else {
      $pagedata['coninfo']='1';
    }



    $MemberId = session('memberId');
    $ProId=$_GET['pid'];

    $sharememberid = cookie('sharerID');
    if (!empty($sharememberid) && $sharememberid != session('memberId')) {
      $mid=$sharememberid;
    } else {
      $mid=session('memberId');
    }

    $sqlStr="SELECT p.ProId,p.ProName,p.ProTitle,p.ProSubtitle,p.Price,p.PriceRange,p.ClassType,p.ProContent,p.NumType,p.ProLogoImg,p.SalesCount,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice from RS_Product p LEFT JOIN RS_ProductList pl ON pl.ProId = p.ProId LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON ps.ProId = p.ProId WHERE  p.stoken='".$this->stoken."' AND p.ProId ='".$ProId."' AND pl.IsDelete='0'";
    // var_dump($sqlStr);exit();
    $pinfo = M()->query($sqlStr);
    $plinfo = $pinfo;
    $plarray = array();
    foreach ($plinfo as $k => $val) {
      $plarray[$k] = array(
        'ProIdCard'=>$val['ProIdCard'],
        'ProSpec'=>$val['ProSpec1'],
      );
    };
    $pinfo = $pinfo[0];
    $pinfo['OldPrice'] = $pinfo['Price'];
    $ltype = $pinfo['ltype'];
    if ($pinfo['Level'] !=null && $pinfo['Level']!='' ) {
      if (($ltype != null) && ($pinfo['ltype'] != '')) {
        if ($ltype >= $pinfo['Level']) {
          $pinfo['Price'] = $pinfo['Lv'.$value['Level']];
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
    $pinfo['plcount'] = count($plarray);
    // var_dump($pinfo);exit();
    $pagedata['pinfo'] = $pinfo;
    $pagedata['plinfo'] = json_encode($plarray);
    $sinfo=M()->table("RS_Store")->where("stoken='%s'",$this->stoken)->find();
    $pagedata['sinfo'] = $sinfo;
    //购物车信息
    $filename = session('memberId').".json";
		$cartinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
		if ($cartinfo && count($cartinfo) >0) {
			foreach ($cartinfo as $key => $value) {
				if ($value['stoken'] == $this->stoken) {
					$sqlStr="SELECT p.ProName,p.ProLogoImg,p.Price,p.ClassType,p.ProId,p.NumType,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice from RS_Product p LEFT JOIN RS_ProductList pl ON pl.ProId = p.ProId LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON ps.ProId = p.ProId WHERE  p.stoken='".$this->stoken."' AND p.ProId ='".$value['pid']."' AND pl.IsDelete='0' AND pl.ProIdCard='".$value['plid']."'";
					$pinfo = M()->query($sqlStr);
					$pinfo = $pinfo[0];
					if ($pinfo) {
						$ltype = $pinfo['ltype'];
            if ($pinfo['Level'] !=null && $pinfo['Level']!='' ) {
              if (($ltype != null) && ($pinfo['ltype'] != '')) {
  	            if ($ltype >= $pinfo['Level']) {
  	    					$pinfo['Price'] = $pinfo['Lv'.$value['Level']];
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
				}
			}
			if ($cartArray) {
				$pagedata['cartinfo'] = json_encode($cartArray);
			} else {
				$pagedata['cartinfo'] = null;
			}
		} else {
			$pagedata['cartinfo']=null;
		}
    // 是否收藏
    $collinfo=M()->table("RS_MemberCollect")->where(array('MemberId'=>session('memberId'),'token'=>$this->token,'stoken'=>$this->stoken,'IsType'=>'PRO','ProId'=>$ProId))->find();
    if ($collinfo) {
      $pagedata['colltype']='1';
    } else {
      $pagedata['colltype']='0';
    }
    // 关注门店人数
    $shopcollnums = M()->table("RS_MemberCollect")->where(array('token'=>$this->token,'stoken'=>$this->stoken,'IsType'=>'SHOP'))->count();
    $pagedata['scnums']=$shopcollnums;
    // 商品评论信息
    $sqlStr="SELECT pe.*,CONVERT(varchar(10),pe.Date,120) AS cdate,m.MemberName,m.HeadImgUrl FROM RS_ProductEvaluation pe LEFT JOIN RS_Member m ON pe.MemberId = m.MemberId WHERE pe.token='".$this->token."' AND pe.stoken='".$this->stoken."' AND pe.Label='PROS'";
    $proeval=M()->query($sqlStr);
    $pagedata['proeval']=$proeval;
    $pagedata['pevalnum']=count($proeval);

    //分享信息处理
    $menuList = 'menuItem:share:appMessage,menuItem:share:timeline';
    $pagedata['menulist']=$menuList;
    $pagedata['shareType']='P';
    $pagedata['shareMemberId']=session('memberId');
    $pagedata['ShareId']=$ProId;
    //分享信息处理end

    $pagedata['Title'] = $pinfo['ProName'];
    $this->assign($pagedata);
    $this->display();
  }

  // 关注门店信息
  public function setprocollect(){
    if (IS_POST) {
      $ctype = $_POST['ctype'];
        $pid = $_POST['pid'];
      if ($ctype == '1') {
        $savedata['MemberId'] = session('memberId');
        $savedata['ProId'] = $pid;
        $savedata['IsType'] = 'PRO';
        $savedata['token'] = $this->token;
        $savedata['stoken'] = $this->stoken;
        $res = M()->table('RS_MemberCollect')->add($savedata);
      } else {
        $res = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'stoken'=>$this->stoken,'token'=>$this->token,'IsType'=>'PRO','ProId'=>$pid))->delete();
      }
      if ($res) {
        $this->ajaxReturn(array('status'=>'true','info'=>$ctype),'JSON');
      } else {
        $this->ajaxReturn(array('status'=>'false','info'=>'UPDATEERROR'),'JSON');
      }
    } else{
      $this->ajaxReturn(array('status'=>'false','info'=>'POSTERROR'),'JSON');
    }
  }




}?>
