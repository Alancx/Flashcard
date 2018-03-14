<?php 
/**
* 
*/
class NewErpApiController extends BaseController
{
	public $model;
	public $token;
	private $key='lvs';
	function _initialize()
	{
		$this->model=M();
	}

	/**
	 * 初始化接口
	 */
	public function initing(){
		$param=$_POST;
		$res=$this->checkParam($param);
		if ($res['status']) {
			//回馈错误信息
			$MsgData['status']='FAIL';
			$MsgData['info']=$res['info'];
		}else{
			$Method=$_POST['Method'];
			switch ($Method) {
				case 'GetOrder':
					$this->GetOrder($param);
					break;
				default:
					# code...
					break;
			}
		}
	}

	/**
	 * 获取订单信息
	 */
	public function GetOrder($param){
		//支付时间/下单时间，时间段必选//订单标记是否同步到WDT
		/**
		 * 起始时间/页码/页面大小
		 */
		if ($param['PageSize'] && $param['PageNo'] && $param['StartTime'] && $param['EndTime'] && strtotime($param['StartTime'])<=strtotime($param['EndTime'])) {
			$PageSize=$param['PageSize'];
			$PageNo=$param['PageNo'];
			$StartTime=$param['StartTime'];
			$EndTime=$param['EndTime'];
			$count=$this->model->table('RS_Order')->where("token='%s' and stoken='0' and RecevingPost='KD' and IsApplyWDT='N' and PayData BETWEEN '%s' and '%s'",array($param['token'],$param['StartTime'],$param['EndTime']))->count();
			$pageStart=$pageStart*($PageNo-1);
			$pageEnd=$PageSize;
			$data=$this->model->table('RS_Order o')->join("LEFT JOIN RS_OrderList ol ON o.OrderId=ol.OrderId")->join("LEFT JOIN RS_Product p ON ol.ProId=p.ProId")->where("o.token='%s' and o.stoken='0' and o.RecevingPost='KD' and o.IsApplyWDT='N' and o.PayData BETWEEN '%s' and '%s'",array($param['token'],$param['StartTime'],$param['EndTime']))->field("o.OrderId as IF_OrderCode,o.Price as GoodsTotal,o.Coupon as FavourableTotal,o.Price as OrderPay,o.Freight as LogisticsPay,o.RecevingName as BuyerName,o.RecevingPhone as BuyerTel,o.RecevingProvince as BuyerProvince,o.RecevingCity as BuyerCity,o.RecevingArea as BuyerDistrict,o.RecevingAddr as BuyerAdr,ol.InputCode as Sku_Code,ol.Price as Sku_Price,ol.Count as Qty,ol.Money as Total,p.ProName+'_'ol.Spec as Sku_Name")->limit($pageStart.','.$pageEnd)->select();
			//处理数据？
		}else{
			$MsgData['status']='FAIL';
			$MsgData['info']='缺少查询参数-->PageSize/PageNo/StartTime/EndTime';
		}

	}

	/**
	 * 检查必要参数
	 */
	public function checkParam($param){
		//查找token
		//验证token+method
		if (!$param['sign']) {
			$Result['status']=true;
			$Result['info']='缺少必填参数-->sign';
			return $Result;exit();
		}
		if (empty($param['token']) || empty($param['Method']) || is_null($param['token']) || is_null($param['Method'])) {
			$Result['status']=true;
			$Result['info']='缺少必填参数-->token/Method';		
		}else{
			if (!$this->MSL()->table("tb_merchant")->where("token='%s'",$param['token'])->find()) {
				$Result['status']=true;
				$Result['info']='参数错误-->token';
			}else{
				$sign=strtoupper(md5($param['token'].$param['Method']));
				if ($sign==$param['sign']) {
					$Result['status']=false;
				}else{
					$Result['status']=true;
					$Result['info']='签名错误';
				}
			}
		}
		return $Result;
	}

























}














 ?>