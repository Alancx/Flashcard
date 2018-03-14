<?php
namespace Home\Controller;
use Think\Controller;
class TableController extends BaseController
{
	public function _initialize()
	{
		parent::_initialize();
	}

// 通过扫桌面二维码进入店铺
Public function Singlepoint(){
  $tableid = $_GET['tableid'];
  cookie('tableID',$tableid);

	if(empty($_GET['oid'])){
		$oid = "E".date("YmdHis",time()).rand(10000,99999);
	} else {
		$oid = $GET['oid'];
	}


  // 商品信息
$mid=session('memberId');
  $sqlStr="SELECT p.ProId,pc.ClassId,pc.ClassName,pc.ClassSort,p.ProName,p.ProContent,p.ProLogoImg,p.Price,p.NumType,p.SalesCount,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype,ps.sprice FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId = pl.ProId LEFT JOIN RS_ProductClass pc ON pc.ClassId=p.ClassType LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' LEFT JOIN RS_ProductOnsale ps ON p.ProId = ps.ProId WHERE pc.IsVisible='1' and pc.stoken='".$this->stoken."' AND pl.IsDelete='0' AND p.IsShelves='1' and p.stoken = '".$this->stoken."' ORDER BY p.CreateDate DESC";
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
				'ProContent'=>$val['ProContent'],
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
				'ProContent'=>$value['ProContent'],
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
				'ProContent'=>$value['ProContent'],
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


	asort($allpros);

  // 获取当前购物车商品
  // $filename = $oid.".json";
	$filename = session('memberId').".json";
  $cartinfo = json_decode(file_get_contents('Public/json/'.$filename),true);
  if ($cartinfo && count($cartinfo) >0) {
    foreach ($cartinfo as $key => $value) {
      if ($value['stoken'] == $this->stoken) {
        unset($cartinfo[$key]);
      }
    }
    file_put_contents('Public/json/'.$filename, json_encode($cartinfo));
  }

	//店铺信息
	$sinfo=M()->table("RS_Store")->where("stoken='%s'",$this->stoken)->find();

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

  $pagedata['cartinfo']=null;
  $pagedata['allpros']=$allpros;
	$pagedata['allproinfo']=json_encode($allpros);
	$pagedata['sinfo']=$sinfo;
	$pagedata['tid']=$tableid;//桌号信息
	$pagedata['stoken'] = $this->stoken;
	$pagedata['oid'] = $oid;
  $pagedata['Title'] = '点菜';
  $this->assign($pagedata);
  $this->display();
}







}?>
