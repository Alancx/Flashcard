<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends BaseController
{
	public function _initialize()
	{
		parent::_initialize();
	}
  public function test(){
    $mid=session('memberId');
    $sqlStr="SELECT p.ProId,pc.ClassId,pc.ClassName,pc.ClassSort,p.ProName,p.ProLogoImg,p.Price,p.SalesCount,pl.ProIdCard,pl.ProSpec1,em.Level,em.Lv1,em.Lv2,em.Lv3,em.Lv4,em.Lv5,mem.Level AS ltype FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId = pl.ProId LEFT JOIN RS_ProductClass pc ON pc.ClassId=p.ClassType LEFT JOIN RS_Eatmore em ON p.stoken = em.stoken AND p.ProId = em.ProId LEFT JOIN RS_MemberEatmore mem ON p.ProId = mem.ProId AND p.stoken = mem.stoken AND mem.MemberId = '".$mid."' WHERE pc.IsVisible='1' and pc.stoken='".$this->stoken."' AND p.IsShelves='1' and p.stoken = '".$this->stoken."'";
    $allpcs=M()->query($sqlStr);
    $allpros=array();
		foreach ($allpcs as $key => $value) {
      if (array_key_exists($value['ProId'], $allpros)) {
        $plinfo = array(
					'ProIdCard'=>$value['ProIdCard'],
					'ProSpec'=>$value['ProSpec1'],
				);
        $allpros[$value['ProId']]['prolist'][]=$plinfo;
      } else {
        $plinfo = array(
					'ProIdCard'=>$value['ProIdCard'],
					'ProSpec'=>$value['ProSpec1'],
				);
        $allpros[$value['ProId']]=array(
          'ProId'=>$value['ProId'],
          'ClassId'=>$value['ClassId'],
          'ClassName'=>$value['ClassName'],
					'ProName'=>$value['ProName'],
					'ProLogoImg'=>$value['ProLogoImg'],
					'Price'=>$value['Price'],
					'SalesCount'=>$value['SalesCount'],
					'Level'=>$value['Level'],
          'Lv1'=>$value['Lv1'],
          'Lv2'=>$value['Lv2'],
          'Lv3'=>$value['Lv3'],
          'Lv4'=>$value['Lv4'],
          'Lv5'=>$value['Lv5'],
          'ltype'=>$value['ltype'],
					'prolist'=>array($plinfo)
				);
      }
    }

    var_dump($allpros);exit();
  }
}?>
