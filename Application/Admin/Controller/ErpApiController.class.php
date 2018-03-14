<?php 
/**
* 
*/
class ErpApiController extends BaseController
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
		$data=json_decode($_POST,true);
		$type=$data['API_TYPE'];
		$this->token=$data['token'];
		$this->stoken=$data['stoken']?$data['stoken']:'0';
		$this->Method=$type;
		switch ($type) {
			case 'SyncGoods':
			//货品信息同步
				$this->SyncGoods($data);
				break;
			case 'SyncAllGoods':
				$this->SyncAllGoods($data);
				$this->Method='SyncGoods';
				break;
			case 'CreateNewOrder':
				$this->CreateNewOrder($data);
				$this->Method='NewOrder';
				break;
			default:
				# code...
				break;
		}
	}

	/**
	 * 同步商品信息（单）
	 */
	public function SyncGoods($data){
		$pid=$data['ProId'];
		$goodinfo=$this->model->table('RS_Product')->where("ProId='%s' and token='%s' and stoken='%s'",array($pid,$this->token,$this->stoken))->find();
		$goodsons=$this->model->table('RS_ProductList')->where("ProId='%s' and IsDelete=0")->select();
		$Gdata['GoodsNO']=$goodinfo['ProNumber'];
		$Gdata['GoodsName']=$goodinfo['ProName'];
		$Gdata['Weight']=$goodinfo['Weight'];
		$Gdata['Price']=$goodinfo['PriceRange']; //取哪个价格？？？
		$Gdata['Barcode']=$goodinfo['Barcode'];
		$Sku=array();
		foreach ($goodsons as $gson) {
			$sinfo=array();
			$sinfo['SkuCode']=$gson['SkuCode'];
			$sinfo['SkuName']=$gson['ProSpec1'].'/'.$gson['ProSpec2'].'/'.$gson['ProSpec3'];
			$sinfo['Prce']=$gson['Price'];
			$sinfo['Weight']=$gson['Weight'];
			$sinfo['Barcode']=$gson['Barcode'];
			$Sku[]=$sinfo;
		}
		// SkuList拼接完成
		$Gdata['SkuList']['Sku']=$Sku;
		$Tpdata=array();
		$Tpdata['GoodsList']['Goods']=$Gdata;
		$Tpdata=json_encode($Tpdata);
		$signStr=$this->erp_sign($Tpdata);
		$post_data=$this->create_post_data($Tpdata,$signStr);
		$result=$this->Post_Json_Data($this->apiurl,$post_data);
		$this->exec_result($result);
	}

	/**
	 * 同步商品信息（全部）
	 */
	public function SyncAllGoods($data){
		$allProinfos=$this->model->query("SELECT * FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId=pl.ProId WHERE p.token='{$this->token}' and p.stoken='{$this->stoken}' and pl.IsDelete=0");
		$GoodsInfos=array();
		foreach ($allProinfos as $pros) {
			$GoodsInfos[$pros['ProId']][$pros['ProIdCard']]=$pros;
		}
		$GoodsList=array();
		foreach ($GoodsInfos as $goods) {
			$OneGoods=array();
			foreach ($goods as $god) {
				$OneGoods['GoodsNO']=$god['ProNumber'];
				$OneGoods['GoodsName']=$god['ProName'];
				$OneGoods['Weight']=$god['Weight'];
				$OneGoods['Price']=$god['PriceRange'];
				$OneGoods['Barcode']=$god['Barcode'];
				$Sku=array();
				$Sku['SkuCode']=$god['SkuCode'];
				$Sku['SkuName']=$god['ProSpec1'].'/'.$god['ProSpec2'].'/'.$god['ProSpec3'];
				$Sku['Prce']=$god['plPrice'];
				$Sku['Weight']=$god['plWeight'];
				$Sku['Barcode']=$god['plBarcode'];
				$OneGoods['SkuList']['Sku'][]=$Sku;
			}
			$GoodsList[]=$OneGoods;
		}
		$Tpdata=array();
		$Tpdata['GoodsList']['Goods']=$GoodsList;
		$Tpdata=json_encode($Tpdata);
		$signStr=$this->erp_sign($Tpdata);
		$post_data=$this->create_post_data($Tpdata,$signStr);
		$result=$this->Post_Json_Data($this->apiurl,$post_data);
		$this->exec_result($result);
	}

	/**
	 * 新增订单
	 */
	public function CreateNewOrder($data){
		$oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$data['oid'])->find();
		$sons = $this->model->query("SELECT pl.ProIdInputCard,p.ProName,pl.Price,ol.Money,ol.Count,ol.Remark FROM RS_OrderList ol LEFT JOIN RS_Product p ON ol.ProId=p.ProId LEFT JOIN RS_ProductList pl ON ol.ProIdCard=pl.ProIdCard WHERE ol.OrderId='{$data['oid']}'");
		$NewODB=array();
		$NewODB['OutInFlag']='3'; //销售出库
		$NewODB['IF_OrderCode']=$data['oid']; //订单号
		$NewODB['WarehouseNO']='';//仓库编号
		$NewODB['Remark']=''; //备注内容
		$NewODB['TheCause']='';//出入库原因
	}

	/**
	 * 结果处理
	 */
	public function exec_result($result){
		$data=json_decode($result,true);
		if ($data['ResultCode']=='0') {
			$retrunMsg['status']='success';
		}else{
			$returnMsg['status']='error';
			$retrunMsg['info']="处理出错：$data['ResultCode']--->>>$data['ResultMsg']";
			$this->LOGS("Erp接口处理失败信息--->>>处理出错：$data['ResultCode']--->>>$data['ResultMsg']");
		}
	}

	/**
	 * 签名
	 */
	private function erp_sign($data){
		$signStr=md5($data.$this->key);
		$signStr=base64_encode($signStr);
		$signStr=urlencode($signStr);
		return $signStr;
	}

	/**
	 * 拼接url
	 */
	private function create_post_data($content,$signStr){
		$post_data="Method={$this->Method}&SellerID={$this->SellerID}&InterfaceID={$this->InterfaceID}&Sign={$signStr}&Content={$content}";
		return $post_data;
	}

	/**
	 * post 发送请求
	 */
	public function Post_Json_Data($url,$data){

	}


}














 ?>