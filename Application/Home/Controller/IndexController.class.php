<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function Index(){
		$SID=$_GET['SID'];
		$res = A('Share')->inshare(array(
			'ID'=>$SID,
			'Inshare'=>session('memberId'),
		));
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

		$sharememberid = cookie('sharerID');
		if (!empty($sharememberid) && $sharememberid != session('memberId')) {
			$mid=$sharememberid;
		} else {
			$mid=session('memberId');
		}

		$sqlStr="SELECT p.ProId,pc.ClassId,pc.ClassName,pc.ClassSort,p.ProName,p.ProLogoImg,p.Price,p.SalesCount,p.NumType,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId = pl.ProId LEFT JOIN RS_ProductClass pc ON pc.ClassId=p.ClassType LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON p.ProId = ps.ProId  WHERE pc.IsVisible='1' and pc.stoken='".$this->stoken."' AND pl.IsDelete='0' AND p.IsShelves='1' and p.stoken = '".$this->stoken."' ORDER BY p.CreateDate DESC";
		// var_dump($sqlStr);exit();
		$allpcs=M()->query($sqlStr);
		$allprolist=array();
		foreach ($allpcs as $k => $val) {
			if (array_key_exists($val['ProId'], $allprolist)) {
				$plinfo = array(
					'ProIdCard'=>$val['ProIdCard'],
					'ProSpec'=>$val['ProSpec1'],
				);
				$allprolist[$val['ProId']]['prolist'][]=$plinfo;
			} else {
				$plinfo = array(
					'ProIdCard'=>$val['ProIdCard'],
					'ProSpec'=>$val['ProSpec1'],
				);
				$allprolist[$val['ProId']]=array(
					'ProId'=>$val['ProId'],
					'ClassId'=>$val['ClassId'],
					'ClassName'=>$val['ClassName'],
					'ClassSort'=>$val['ClassSort'],
					'ProName'=>$val['ProName'],
					'ProLogoImg'=>$val['ProLogoImg'],
					'Price'=>$val['Price'],
					'SalesCount'=>$val['SalesCount'],
					'Level'=>$val['Level'],
					'Lv1'=>$val['Lv1'],
					'Lv2'=>$val['Lv2'],
					'Lv3'=>$val['Lv3'],
					'Lv4'=>$val['Lv4'],
					'Lv5'=>$val['Lv5'],
					'ltype'=>$val['ltype'],
					'NumType'=>$val['NumType'],
					'sprice'=>$val['sprice'],
					'prolist'=>array($plinfo)
				);
			}
		}
		$allpros=array();
		foreach ($allprolist as $key => $value) {
			if (array_key_exists($value['ClassId'], $allpros)) {
				$pinfo = array(
					'ProId'=>$value['ProId'],
					'ProName'=>$value['ProName'],
					'ProLogoImg'=>$value['ProLogoImg'],
					'OldPrice'=>$value['Price'],
					'SalesCount'=>$value['SalesCount'],
					'Level'=>$value['Level'],
					'prolist'=>$value['prolist'],
					'plcount'=>count($value['prolist']),
					'NumType'=>$value['NumType'],
					'sprice'=>$value['sprice'],
				);
				$ltype = $value['ltype'];
				if ($value['Level'] !=null && $value['Level']!='' ) {
					if (($ltype == null) || ($value['ltype'] == '')) {
						$pinfo['Price'] = $value['Lv1'];
					} else {
						if ($ltype >= $value['Level']) {
							$pinfo['Price'] = $value['Lv'.$value['Level']];
						} else {
							$pinfo['Price'] = $value['Lv'.$ltype];
						}
					}
				} else {
					if ($value['sprice'] !=null && $value['sprice']!='') {
						$pinfo['Price'] = $value['sprice'];
					} else {
						$pinfo['Price'] = $value['Price'];
					}
				}
				$allpros[$value['ClassId']]['pros'][$value['ProId']]=$pinfo;
			}else{
				$pinfo = array(
					'ProId'=>$value['ProId'],
					'ProName'=>$value['ProName'],
					'ProLogoImg'=>$value['ProLogoImg'],
					'OldPrice'=>$value['Price'],
					'SalesCount'=>$value['SalesCount'],
					'Level'=>$value['Level'],
					'prolist'=>$value['prolist'],
					'plcount'=>count($value['prolist']),
					'NumType'=>$value['NumType'],
					'sprice'=>$value['sprice'],
				);
				$ltype = $value['ltype'];
				if ($value['Level'] !=null && $value['Level']!='' ) {
					if (($ltype == null) || ($value['ltype'] == '')) {
						$pinfo['Price'] = $value['Lv1'];
					} else {
						if ($ltype >= $value['Level']) {
							$pinfo['Price'] = $value['Lv'.$value['Level']];
						} else {
							$pinfo['Price'] = $value['Lv'.$ltype];
						}
					}
				} else {
					if ($value['sprice'] !=null && $value['sprice']!='') {
						$pinfo['Price'] = $value['sprice'];
					} else {
						$pinfo['Price'] = $value['Price'];
					}
				}
				$allpros[$value['ClassId']]=array(
					'ClassId'=>$value['ClassId'],
					'ClassName'=>$value['ClassName'],
					'ClassSort'=>$value['ClassSort'],
				);
				$allpros[$value['ClassId']]['pros'][$value['ProId']]=$pinfo;
			}
		}
		// var_dump($allpros);exit();
		// foreach ($allpros as $key => $value) {
		// 	$rating[$key] = $value['ClassSort'];
		// 	asort($allpros[$key]['pros']);
		// }
		asort($allpros);
		// array_multisort($rating, $allpros);
		//红包
		$sqlStr ="SELECT Rules,CouponId,Type FROM RS_Coupon WHERE stoken='{$this->stoken}' AND IsEnable=1 AND (Forshare='1' OR Forsharer ='1') AND ExpiredDate > '".date('Y-m-d H:i:s')."'";
		$coupon = M()->query($sqlStr);
		if ($coupon && count($coupon)>0) {
			foreach ($coupon as $key => $value) {
				if ($value['Type']=='2') {
					$rulestemp = explode("/",$value['Rules']);
					$coupon[$key]['Rules'] = $rulestemp[1];
					$coupon[$key]['Totalrules'] = $rulestemp[0];
				}
			}
			// var_dump($coupon);exit();
			$pagedata['coupon'] = $coupon;
		} else {
			$pagedata['coupon'] = null;
		}
		//店铺信息
		$sinfo=M()->table("RS_Store")->where("stoken='%s'",$this->stoken)->find();
		$simgs =unserialize(stripcslashes($sinfo['ShopImgs']));
		$pagedata['simgs'] = $simgs;

		// 是否关注此店
		$collinfo=M()->table("RS_MemberCollect")->where(array('token'=>$this->token,'stoken'=>$this->stoken,'IsType'=>'SHOP','MemberId'=>session('memberId')))->find();
		if ($collinfo) {
			$pagedata['colltype']='1';
		} else {
			$pagedata['colltype']='0';
		}
		//购物车信息
		$filename = session('memberId').".json";
		$cartinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
		if ($cartinfo && count($cartinfo) >0) {
			foreach ($cartinfo as $key => $value) {
				if ($value['stoken'] != $this->stoken) {
					unset($cartinfo[$key]);
				}
			}
			if ($cartinfo) {
				$pagedata['cartinfo']=json_encode($cartinfo);
			} else {
				$pagedata['cartinfo']=null;
			}

		} else {
			$pagedata['cartinfo']=null;
		}
    // 公告信息
		$gginfo =  file_get_contents('Public/json/'.$this->stoken.'gginfos.json');
		$gginfo = json_decode($gginfo,true);
		if ($gginfo) {
		 $this->assign('gginfo',$gginfo['gginfo']);
	 } else {
		 $this->assign('gginfo',null);
	 }
		// 商家评论信息
		$sqlStr="SELECT pe.*,CONVERT(varchar(10),pe.Date,120) AS cdate,m.MemberName,m.HeadImgUrl FROM RS_ProductEvaluation pe LEFT JOIN RS_Member m ON pe.MemberId = m.MemberId WHERE pe.token='".$this->token."' AND pe.stoken='".$this->stoken."' AND pe.Label='SHOPS'";
		$shopeval=M()->query($sqlStr);
		$pagedata['shopeval']=$shopeval;
		$pagedata['sevalnum']=count($shopeval);
    // 特色特价图片信息
		$allimginfo = json_decode(file_get_contents('Public/uploadImg.json'),true);
		$tsimg = M()->table('RS_HomeImg')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'Type'=>'1'))->find();
		if ($tsimg) {
			$pagedata['tsimg']=$tsimg['ImgPath'];
		} else {
			foreach ($allimginfo as $key => $value) {
				if ($value['default'] == '1') {
					$pagedata['tsimg']=$value['imgurl'];
				}
			}
		}
		if (!$pagedata['tsimg'] && count($allimginfo)>0) {
			$pagedata['tsimg'] = $allimginfo[0]['imgurl'];
		}
		$tjimg = M()->table('RS_HomeImg')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'Type'=>'2'))->find();
		if ($tjimg) {
			$pagedata['tjimg']=$tjimg['ImgPath'];
		} else {
			foreach ($allimginfo as $key => $value) {
				if ($value['default'] == '1') {
					$pagedata['tjimg']=$value['imgurl'];
				}
			}
		}
		if (!$pagedata['tjimg'] && count($allimginfo)>0) {
			$pagedata['tjimg'] = $allimginfo[0]['imgurl'];
		}
		// 特色特价名称显示
		$showactivename=json_decode(file_get_contents('./Public/'.$this->stoken.'showname.json'),true);
		$pagedata['showname'] = $showactivename;
		// 进店红包
		$inredinfo=cookie('inredinfo');
		if ($inredinfo !=null && cookie('inredinfofirst') == 'true') {
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
			cookie('inredinfofirst',null);
		} else {
			$redprice = null;
		}
		$pagedata['redprice']=$redprice;
		//分享信息处理
		$menuList = 'menuItem:share:appMessage,menuItem:share:timeline';
		$pagedata['menulist']=$menuList;
		$pagedata['shareType']='S';
		$pagedata['shareMemberId']=session('memberId');
		$pagedata['ShareId']=$this->stoken;
		//分享信息处理end
		$pagedata['sinfo']=$sinfo;
		// var_dump($allpros);exit();
		$pagedata['allpros']= $allpros;
		$pagedata['allproinfo']=json_encode($allpros);
		$pagedata['Title'] = '首页';
		$this->assign($pagedata);
		$this->display();
	}
	// 红包说明页面
	public function redenvel(){
		$couponId = $_GET['rid'];
		$whereStr = "stoken='{$this->stoken}' AND IsEnable=1 AND CouponId='{$couponId}'";
		$coupon = M()->table("RS_Coupon")->where($whereStr)->field("CONVERT(varchar(20),ExpiredDate,120) as ExpiredDate,Rules,Type")->find();
		if ($coupon['Type']=='2') {
			$rulestemp = explode("/",$coupon['Rules']);
			$coupon['Rules'] = $rulestemp[1];
			$coupon['Totalrules'] = $rulestemp[0];
		}
		$pagedata['coupon'] = $coupon;
		//店铺信息
		$sinfo=M()->table("RS_Store")->where("stoken='%s'",$this->stoken)->find();
		$pagedata['Title'] = $sinfo['storename'];
		$this->assign($pagedata);
		$this->display();
	}
	/**
	* 活动
	*/
	public function activity(){
		$type=$_GET['type'];
		// 活动商品信息
		$sharememberid = cookie('sharerID');
		if (!empty($sharememberid) && $sharememberid != session('memberId')) {
			$mid=$sharememberid;
		} else {
			$mid=session('memberId');
		}
		if ($type == 'TJ') {
			$sqlStr="SELECT p.ProName,p.ProLogoImg,p.Price,p.PriceRange,p.ProId,p.SalesCount,p.ClassType,p.NumType,pli.ProIdCard,pli.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice FROM RS_ProductOnsale ps LEFT JOIN RS_Product p ON p.ProId = ps.ProId LEFT JOIN RS_ProductList pli ON pli.ProId=p.ProId  LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' WHERE ps.stoken='".$this->stoken."' and p.stoken='".$this->stoken."' AND pli.IsDelete='0'";
			$pagedata['showtext'] ='特价';
		} elseif ($type=='TS') {
			$sqlStr="SELECT p.ProName,p.ProLogoImg,p.Price,p.PriceRange,p.ProId,p.SalesCount,p.ClassType,p.NumType,pli.ProIdCard,pli.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice FROM RS_ProductLabelList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId LEFT JOIN RS_ProductList pli ON pli.ProId=p.ProId  LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON p.ProId = ps.ProId WHERE pl.stoken='".$this->stoken."' and p.stoken='".$this->stoken."' and pl.LabelType='2' AND pl.ProLabel='2' AND pli.IsDelete='0'";
			$pagedata['showtext'] ='特色';
		} else {
			$sqlStr="SELECT p.ProName,p.ProLogoImg,p.Price,p.PriceRange,p.ProId,p.SalesCount,p.ClassType,p.NumType,pli.ProIdCard,pli.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice FROM RS_ProductLabelList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId LEFT JOIN RS_ProductList pli ON pli.ProId=p.ProId  LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON p.ProId = ps.ProId WHERE pl.stoken='".$this->stoken."' and p.stoken='".$this->stoken."' and pl.LabelType='1' AND pl.ProLabel='1' AND pli.IsDelete='0'";
			$pagedata['showtext'] ='活动';
		}
		$list=M()->query($sqlStr);
		$listarray= array();
		foreach ($list as $k => $val) {
			if (array_key_exists($val['ProId'], $listarray)) {
				$plinfo = array(
					'ProIdCard'=>$val['ProIdCard'],
					'ProSpec'=>$val['ProSpec1'],
				);
				$listarray[$val['ProId']]['prolist'][]=$plinfo;
			} else {
				$plinfo = array(
					'ProIdCard'=>$val['ProIdCard'],
					'ProSpec'=>$val['ProSpec1'],
				);
				$listarray[$val['ProId']]=array(
					'ProId'=>$val['ProId'],
					'ProName'=>$val['ProName'],
					'ClassId'=>$val['ClassType'],
					'ProLogoImg'=>$val['ProLogoImg'],
					'Price'=>$val['Price'],
					'SalesCount'=>$val['SalesCount'],
					'Level'=>$val['Level'],
					'Lv1'=>$val['Lv1'],
					'Lv2'=>$val['Lv2'],
					'Lv3'=>$val['Lv3'],
					'Lv4'=>$val['Lv4'],
					'Lv5'=>$val['Lv5'],
					'ltype'=>$val['ltype'],
					'NumType'=>$val['NumType'],
					'sprice'=>$val['sprice'],
					'prolist'=>array($plinfo)
				);
			}
		}
		foreach ($listarray as $key => $value) {
			$listarray[$key]['OldPrice'] = $value['Price'];
			$listarray[$key]['plcount'] =count($value['prolist']);
			$ltype = $value['ltype'];
			if ($value['Level'] !=null && $value['Level']!='' ) {
				if (($ltype == null) || ($value['ltype'] == '')) {
					$listarray[$key]['Price'] = $value['Lv1'];
				} else {
					if ($ltype >= $value['Level']) {
						$listarray[$key]['Price'] = $value['Lv'.$value['Level']];
					} else {
						$listarray[$key]['Price'] = $value['Lv'.$ltype];
					}
				}
			} else {
				if ($listarray[$key]['sprice']!=null && $listarray[$key]['sprice']!='') {
					$listarray[$key]['Price'] = $value['sprice'];
				} else {
					$listarray[$key]['Price'] = $value['Price'];
				}
			}
		};
		// var_dump($listarray);exit();
		$pagedata['list']=$listarray;
		$pagedata['allproinfo']=json_encode($listarray);
		//购物车信息
		$filename = session('memberId').".json";
		$cartinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
		// var_dump($cartinfo);exit();
		if ($cartinfo && count($cartinfo) >0) {
			foreach ($cartinfo as $key => $value) {
				if ($value['stoken'] == $this->stoken) {
					$sqlStr="SELECT p.ProName,p.ProLogoImg,p.Price,p.ClassType,p.ProId,p.NumType,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice from RS_Product p LEFT JOIN RS_ProductList pl ON pl.ProId = p.ProId LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON ps.ProId = p.ProId WHERE  p.stoken='".$this->stoken."' AND p.ProId ='".$value['pid']."' AND pl.IsDelete='0' AND pl.ProIdCard='".$value['plid']."'";
					// var_dump($sqlStr);exit();
					$pinfo = M()->query($sqlStr);
					$pinfo = $pinfo[0];
					if ($pinfo) {
						$ltype = $pinfo['ltype'];
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
		$allimginfo = json_decode(file_get_contents('Public/uploadImg.json'),true);
		$acimg = M()->table('RS_HomeImg')->where(array('token'=>$this->token,'stoken'=>$this->stoken,'Type'=>'3'))->find();
		if ($acimg) {
			$pagedata['acimg']=$acimg['ImgPath'];
		} else {
			foreach ($allimginfo as $key => $value) {
				if ($value['default'] == '1') {
					$pagedata['acimg']=$value['imgurl'];
				}
			}
		}
		if (!$pagedata['acimg'] && count($allimginfo)>0) {
			$pagedata['acimg'] = $allimginfo[0]['imgurl'];
		}
		$pagedata['Title'] ='活动';
		$this->assign($pagedata);
		$this->display();
	}

	// 关注门店信息
	public function setshopcollect(){
		if (IS_POST) {
			$ctype = $_POST['ctype'];
			if ($ctype == '1') {
				$savedata['MemberId'] = session('memberId');
				$savedata['ProId'] = '';
				$savedata['IsType'] = 'SHOP';
				$savedata['token'] = $this->token;
				$savedata['stoken'] = $this->stoken;
				$res = M()->table('RS_MemberCollect')->add($savedata);
			} else {
				$res = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'stoken'=>$this->stoken,'token'=>$this->token,'IsType'=>'SHOP'))->delete();
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





}











?>
