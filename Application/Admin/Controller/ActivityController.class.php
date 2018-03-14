<?php 	
namespace Admin\Controller;
use Think\Controller;
/**
* 
*/
class ActivityController extends CommonController
{
	
	function _initialize()
	{
		parent::_initialize();
	}

	public function shareredpaper(){
		if (IS_POST) {
			$type=$_POST['type'];
			$cid=$_POST['cid'];
			if ($type=='proshare') {
				M()->table('RS_Coupon')->where("Forproshare='6'")->setField('Forproshare','0');
				if (M()->table('RS_Coupon')->where("CouponId='%s'",$cid)->setField('Forproshare','1')) {
					$msg['status']='success';
				}else{
					$msg['status']='error';
					$msg['info']='处理失败';	
				}
			}elseif ($type=='storeshare') {
				M()->table('RS_Coupon')->where("Forstoreshare='6'")->setField('Forstoreshare','0');
				if (M()->table('RS_Coupon')->where("CouponId='%s'",$cid)->setField('Forstoreshare','1')) {
					$msg['status']='success';
				}else{
					$msg['status']='error';
					$msg['info']='处理失败';	
				}
			}elseif ($type='del') {
				if ($_POST['s']=='proshare') {
					$name='Forproshare';
				}else{
					$name='Forstoreshare';
				}
				M()->table('RS_Coupon')->where('1=1')->setField($name,'0');
				$msg['info']=M()->getlastsql();
				$msg['status']='success';
			}
			$this->ajaxReturn($msg);
		}else{
			$proshare=M()->table("RS_Coupon")->where("Forproshare='1'")->find();
			$storeshare=M()->table("RS_Coupon")->where("Forstoreshare='1'")->find();
			$pagedata['proshare']=$proshare;
			$pagedata['storeshare']=$storeshare;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 获取现金抵扣券
	 */
	public function getcoupons(){
		$type=$_POST['type'];
		if ($type=='share') {
			$list=M()->table('RS_Coupon')->where("Type='0'")->field("CouponName,CouponId,Rules,CONVERT(varchar(20),StartDate,120) as StartDate,CONVERT(varchar(20),ExpiredDate,120) as ExpiredDate")->select();
			if ($list && count($list)>0) {
				$msg['status']='success';
				$msg['data']=$list;
			}else{
				$msg['status']='error';
				$msg['info']='暂无可用优惠券';
			}
		}
		$this->ajaxReturn($msg);
	}
}








 ?>