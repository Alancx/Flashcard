<?php
namespace Home\Controller;
use Think\Controller;
/**
 * @Author      leaves
 * @DateTime    2017-12-06
 * @description 分享相关数据处理
 * @version     1.0
 */
class ShareController extends BaseController
{
	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * @Author      [leaves]
	 * @DateTime    2017-12-11
	 * @description 分享成功的处理
	 * @version     1.0
	 * @param       int     $id 预分享的ID
	 * @return      array         处理结果
	 */
	public function shareout($id){
		$Shareinfo=M()->table("RS_Shares")->where("ID=%d",$id)->find();
		$Sharer=$Shareinfo['Sharer'];
		if ($Shareinfo['Status'] =='1') {
			$msg['status']='error';
			$msg['errinfo']="此红包已领取";
		} else {
			if ($Shareinfo['IsReward']=='1') {
				$Coupon=M()->table('RS_Coupon')->where("Forsharer='1' and stoken='%s'",$this->stoken)->find();
				M()->startTrans();
				$cres=$coures=$sres=true;
				$sdb=array();
				if ($Coupon && $Coupon['AfterCount']>0) {
					//领取红包
					if ($has=M()->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s'",array($Sharer,$Coupon['CouponId']))->find()) {
						$cres=M()->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s'",array($Sharer,$Coupon['CouponId']))->setInc("CouponCount",1);
					}else{
						$db=array();
						$db['MemberId']=$Shareinfo['Sharer'];
						$db['CouponId']=$Coupon['CouponId'];
						$db['GetTime']=date('Y-m-d H:i:s',time());
						$db['CouponCount']=1;
						$db['OpenId']=session('openid');
						$cres=M()->table('RS_MemberCoupon')->add($db);
					}
					// var_dump();exit();
					//减红包数量
					$coures=M()->table('RS_Coupon')->where("CouponId='%s'",$Coupon['CouponId'])->setDec('AfterCount',1);
					$sdb['CouponId']=$Coupon['CouponId'];
					$info='红包正常，其他错误';
					$msg['redpaper']='yes';
					$msg['red_rule']=$Coupon['Rules'];
					$msg['red_type']=$Coupon['Type'];
				}else{
					//红包不存在或者已领完
					$sdb['CouponId']='';
					$info='红包不存在或者已领完';
					$msg['redpaper']='no';
				}
				$sdb['Remark']=$info;
				$sdb['Status']='1';
				//保存分享信息
				$sres=M()->table('RS_Shares')->where('ID=%d',$id)->save($sdb);
				if ($cres && $coures && $sres) {
					M()->commit();
					$msg['status']='success';
					$msg['info']=$info;
				}else{
					M()->rollback();
					$msg['status']='error';
					$msg['info']=$info;
					$msg['errinfo']="处理失败--->>>cres=$cres ... coures=$coures ... sres=$sres";
				}
			}else{
				$sdb=array();
				$sdb['Status']='1';
				if (M()->table('RS_Shares')->where("ID=%d",$id)->save($sdb)) {
					$msg['status']='success';
				}else{
					$msg['status']='error';
					$msg['errinfo']="数据写入失败--->>>".M()->getlastsql();
				}
			}
		}
		return $msg;
	}


	/**
	 * @DateTime    2017-12-06
	 * @description 分享出去
	 * @param       array('Type'=>'分享类型','ShareId'=>'分享ID','Sharer'=>'分享人')
	 * @param       分享人是否可领红包
	 * @param       点进分享是否可领红包
	 * @return      array
	 */
	public function preshare($data,$hasredpaper=true,$inreward=true){
		$Type=$data['Type'];//分享类型
		$ShareId=$data['ShareId']; //分享ID  商品ID或者订单号    会员信息+分享ID 作唯一ID 或者用自动生成的ID作唯一ID
		$Sharer=$data['Sharer'];  //分享人
		if ($sinfo=M()->table('RS_Shares')->where("Sharer='%s' and ShareId='%s'",array($Sharer,$ShareId))->find()) {
			$msg['status']='success';
			$msg['ID']=$sinfo['ID'];
		}else{
			if ($hasredpaper) {
			//查分享红包配置
				M()->startTrans();
				$cres=$coures=$sres=true;
				$sdb=array();
				$sdb['CouponId']='';
				$sdb['Sharer']=$Sharer;
				$sdb['ShareId']=$ShareId;
				$sdb['Type']=$Type;
				$sdb['Inshare']='';
				$sdb['stoken']=$this->stoken;
				$sdb['IsReward']='1';
				if ($inreward) {
					$sdb['InReward']='1';
				}
			//保存分享信息
				$sres=M()->table('RS_Shares')->add($sdb);
				if ($cres && $coures && $sres) {
					M()->commit();
					$msg['status']='success';
					$msg['ID']=$sres;
					$msg['info']=$info;
				}else{
					M()->rollback();
					$msg['status']='error';
					$msg['info']=$info;
					$msg['errinfo']="处理失败--->>>cres=$cres ... coures=$coures ... sres=$sres";
				}
			}else{
			//没有分享奖励的
				$sdb=array();
				$sdb['Sharer']=$Sharer;
				$sdb['ShareId']=$ShareId;
				$sdb['Type']=$Type;
				$sdb['Inshare']='';
				$sdb['stoken']=$this->stoken;
				$sdb['IsReward']='0';
				$sdb['CouponId']='';
				if ($inreward) {
					$sdb['InReward']='1';
				}
				if ($sres=M()->table('RS_Shares')->add($sdb)) {
					$msg['status']='success';
					$msg['ID']=$sres;
				}else{
					$msg['status']='error';
					$msg['errinfo']="数据写入失败--->>>".M()->getlastsql();
				}
			}
		}
		return $msg;
	}



	/**
	 * @DateTime    2017-12-06
	 * @description 点击分享进来的处理
	 * @param       data=array('ShareId'=>'分享ID','Inshare'=>'点击分享链接的人')
	 * @return      array('status'=>'处理结果','info'=>'普通信息','errinfo'=>'错误信息');
	 */
	public function inshare($data){
		$ID=$data['ID']; //或者用自动生成的ID
		$Inshare=$data['Inshare']; //点击链接的人
		$Shareinfo=M()->table('RS_Shares')->where("ID=%d",$ID)->find();
		if ($Shareinfo) {
			if ($hasshareinfo=M()->table('RS_Shares')->where("Inshare='%s' and ShareId='%s'",array($Inshare,$Shareinfo['ShareId']))->find()) {				
				//不是第一次进来
				$msg['status']='success';
				$msg['CouponId']=$hasshareinfo['CouponId'];
				$msg['errinfo']='s'; //第二次进来

			}else{
				if ($Shareinfo['InReward']=='1') {
				//有奖励
				//拿配置
					$Coupon=M()->table('RS_Coupon')->where("Forshare='1' and stoken='%s'",$this->stoken)->find();
					// var_dump($Coupon);exit();
					M()->startTrans();
					$cres=$coures=$sres=true;
					$sdb=array();
					if ($Coupon && $Coupon['AfterCount']>0) {
					//处理
						if ($has=M()->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s'",array($Inshare,$Coupon['CouponId']))->find()) {
							$cres=M()->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s'",array($Inshare,$Coupon['CouponId']))->setInc('CouponCount',1);
						}else{
							$db=array();
							$db['MemberId']=$Inshare;
							$db['CouponId']=$Coupon['CouponId'];
							$db['GetTime']=date('Y-m-d H:i:s',time());
							$db['CouponCount']=1;
							$db['OpenId']=session('openid');
							$cres=M()->table('RS_MemberCoupon')->add($db);
						}
						$coures=M()->table('RS_Coupon')->where("CouponId='%s'",$Coupon['CouponId'])->setDec('AfterCount',1);
						$info='红包正常，其他错误';
						$sdb['CouponId']=$Coupon['CouponId'];
						$msg['CouponId']=$Coupon['CouponId'];

					}else{
						$sdb['CouponId']='';
						$msg['CouponId']='';
						$info='红包不存在或者已领完';
					}
					$sdb['Sharer']='';
					$sdb['Type']=$Shareinfo['Type'];
					$sdb['ShareId']=$Shareinfo['ShareId'];
					$sdb['Inshare']=$Inshare;
					$sdb['IsReward']=$Shareinfo['IsReward'];
					$sdb['InReward']='1';
					$sdb['stoken']=$this->stoken;
					$sres=M()->table('RS_Shares')->add($sdb);
					if ($cres && $coures && $sres) {
						M()->commit();
						$msg['status']='success';
						$msg['info']=$info;

					}else{
						M()->rollback();
						$msg['status']='error';
						$msg['info']=$info;
						$msg['errinfo']="处理失败--->>>cres=$cres ... coures=$coures ... sres=$sres";
					}
				}else{
				//无奖励
					$sdb=array();
					$sdb['Sharer']='';
					$sdb['Type']=$Shareinfo['Type'];
					$sdb['ShareId']=$Shareinfo['ShareId'];
					$sdb['Inshare']=$Inshare;
					$sdb['IsReward']=$Shareinfo['IsReward'];
					$sdb['InReward']='0';
					$sdb['stoken']=$this->stoken;
					$sdb['CouponId']='';
					if (M()->table('RS_Shares')->add($sdb)) {
						$msg['status']='success';
					}else{
						$msg['status']='error';
						$msg['errinfo']='处理失败--->>>'.M()->getlastsql();
					}
					$msg['info']='无奖励';
				}
			}
		}else{
			$msg['status']='error';
			$msg['errinfo']='分享不存在';
		}

		return $msg;
	}



















}











 ?>
