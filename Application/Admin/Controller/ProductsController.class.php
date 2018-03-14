<?php

/*
商品相关操作类
*/
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class ProductsController extends CommonController{
	public $Class;
	public $Attribute;
	public $AttributeValue;
	public $Product;
	public $ProductImage;
	public function _initialize(){
		parent::_initialize();
		$this->Class=D('ProductClass');
		$this->Attribute=D('ProductAttribute');
		$this->AttributeValue=D('ProductAttributeValue');
		$this->Product=M('Product');
		$this->ProductImage=D('ProductImage');
	}

	/*商品列表*/
	public function index(){
		$count=$this->Product->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
		$page=new \Think\Page($count,15);
		$products=$this->Product->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->order('ID desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($products as &$pro) {
			$pro['img']=$pro['ProLogoImg'];
			if ($bq=M()->table('RS_ProductLabelList')->where("ProId='%s'",$pro['ProId'])->getField('ProLabel',true)) {
				if (in_array('1', $bq)) {
					$pro['bq1']='1';
				}
				if (in_array('2', $bq)) {
					$pro['bq2']='2';
				}
			}
		}
		$allClass=$this->Class->where("token='%s'",$this->token)->order('ClassSort')->select();
		$this->assign(array('products'=>$products,'page'=>$page->show(),'allClass'=>$allClass));
		define('FPAGE','SHANGPIN');
		
		$this->display();
	}

	/**
	 * 限时特价设置
	 */
	public function sprice(){
		if (IS_POST) {
			$DB['ProId']=explode('_', $_POST['pros'])[0];
			$DB['ProIdCard']=$_POST['pros'];
			$DB['sprice']=$_POST['Sprice'];
			$DB['stime']=$_POST['Stime'];
			$DB['etime']=$_POST['Etime'];
			$DB['Remarks']=$_POST['Remarks'];
			$DB['token']=$this->token;
			$DB['stoken']=$this->stoken;
			if ($_POST['ID']) {
				if (M()->table('RS_ProductOnsale')->where("ID=%d",$_POST['ID'])->save($DB)) {
					$this->success('保存成功');
				}else{
					$this->LOGS('商品限时特价更新失败--->>>'.M()->getlastsql());
					$this->error('保存失败');
				}
			}else{
				if (M()->table('RS_ProductOnsale')->add($DB)) {
					$this->success('保存成功');
				}else{
					$this->LOGS('商品限时特价保存失败--->>>'.M()->getlastsql());
					$this->error('保存失败');
				}
			}
		}else{
			$count=M()->table('RS_ProductOnsale')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
			$page = new \Think\Page($count,15);
			$pss  = M()->table('RS_ProductOnsale po')->join("LEFT JOIN RS_ProductList pl ON po.ProIdCard=pl.ProIdCard and pl.IsDelete=0")->join("LEFT JOIN RS_Product p ON po.ProId=p.ProId")->where("po.token='%s' and po.stoken='%s'",array($this->token,$this->stoken))->field("p.ProName+'_'+pl.ProSpec1+'_'+pl.ProSpec2+'_'+pl.ProSpec3 as ProName,CONVERT(float(50),pl.Price,120) as Price,CONVERT(float(50),po.sprice,120) as sprice,CONVERT(varchar(120),po.stime,120) as stime,CONVERT(varchar(120),po.etime,120) as etime,po.Remarks,po.ID,po.ProIdCard")->limit($page->firstRow.','.$page->listRows)->select();
			$pros=M()->query("SELECT p.ProName+'_'+pl.ProSpec1+'_'+pl.ProSpec2+'_'+pl.ProSpec3+'/'+CONVERT(varchar(120),pl.Price,120) as ProName,pl.ProIdCard FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE pl.IsDelete=0  and p.token='{$this->token}' and p.stoken='{$this->stoken}'");
			$hasPro=M()->table('RS_ProductOnsale')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('ProIdCard',true);
			$pagedata['hasPro']=json_encode($hasPro);
			$pagedata['pros']=$pros;
			$pagedata['prodata']=$pss;
			$pagedata['page']=$page->show();
			$pagedata['jsondata']=json_encode($pss);
			$this->assign($pagedata);
			$this->display();
		}
	}
	/**
	 * 删除限时特价
	 */
	public function delsprice(){
		$ID=$_POST['ID'];
		if (M()->table('RS_ProductOnsale')->where("ID=%d",$ID)->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
		}
		echo json_encode($msg);
	}
	/**
	 * 商品添加页面
	 */
	public function proadd(){
		if ($res=M()->table('RS_Freight')->where("Blong=%d and IsSet=%d and token='%s' and stoken='%s'",array(0,1,$this->token,$this->stoken))->find()) {
			$oclass=$this->Class->where("ParentClassId=%d and token='%s'",array(0,$this->token))->select();
			$allClass=$this->Class->getAll($this->token);
			$this->assign(array('oclass'=>$oclass,'allClass'=>$allClass));
			$freights=M()->table('RS_Freight')->where("Blong=%d and token='%s'",array(0,$this->token))->select();
			$Suppliers=M()->table('RS_Supplier')->where("token='%s' and IsDelete='%s'",array($this->token,'0'))->select();
			$this->assign('Suppliers',$Suppliers);
			$this->assign('freights',$freights);
			define('FPAGE','SHANGPIN');
			$this->display();
		}else{
			$this->error('请设置默认运费模板',U('Products/yunfei'));
		}

	}


	/**
	 * 保存添加数据
	 */
	public function savePro(){
		if (IS_POST) {
			$temp=min($_POST['prices']);
			$tempold=$_POST['oldprice']; //市场价
			//主表插入数据
			$ProId="pro".substr(time(), 3).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
			$Pdata['ProId']=$ProId;
			$Pdata['ProName']=htmlspecialchars($_POST['ProName']);
			$Pdata['ProTitle']=htmlspecialchars($_POST['ProTitle']);
			$Pdata['ProSubtitle']=htmlspecialchars($_POST['ProSubtitle']);
			$Pdata['Freight']=trim($_POST['Freight']);
			$Pdata['FreightRemarks']=htmlspecialchars($_POST['FreightRemarks']);
			$Pdata['Remarks']=htmlspecialchars($_POST['Remarks']);
			$Pdata['KeyWord']=htmlspecialchars($_POST['KeyWord']);
			$Pdata['IsUseConpon']=$_POST['IsUseConpon'];
			$Pdata['IsShelves']=1;
			if ($_POST['ClassType']) {
				$Pdata['ClassType']=$_POST['ClassType'];
			}else{
				$Pdata['ClassType']=$_POST['ClassType1'];
			}
			$Pdata['ClassName']=$this->Class->where('ClassId=%d',$_POST['ClassType'])->getField('ClassName');
			// $Pdata['ProContent']=htmlspecialchars($_POST['ProContent']);
			$Pdata['ProLogoImg']=$_POST['ProLogoImg'];
			$Pdata['Cut']=$_POST['Cut'];
			// $Pdata['Cut2']=$_POST['Cut2'];
			// $Pdata['Cut2']=0;
			$Pdata['PriceRange']=$temp;
			$Pdata['Price']=$tempold;
			// $Pdata['Cut3']=$_POST['Cut3'];
			$Pdata['Cut3']=0;
			$Pdata['ShelvesDate']=$_POST['ShelvesDate'];
			$Pdata['ProNumber']=trim($_POST['ProNumber']);
			$Pdata['Barcode']=trim($_POST['Barcode']);
			$Pdata['Weight']=intval($_POST['Weight']);
			$Pdata['EmpCut']=$_POST['EmpCut'];
			$Pdata['IsUseScore']=$_POST['IsUseScore'];
			$Pdata['Score']=$_POST['Score'];
			$Pdata['Iszp']=$_POST['Iszp'];
			$Pdata['ExtendCut']=$_POST['ExtendCut'];
			$Pdata['SupplierId']=$_POST['SupplierId'];
			$Pdata['token']=$this->token;
			$Pdata['stoken']=$this->stoken;
			//
			//子表插入数据
			$sonprice=$_POST['prices']; //售价
			$nums=$_POST['nums'];  //数量
			$barcodes=$_POST['barcodes'];  //条码
			$sonpids=$_POST['pids'];  //拼ProIdCard
			$specs=$_POST['specs'];  //属性名称
			$codes=$_POST['input_codes'];
			$cosprices=$_POST['input_cosprice'];
			foreach ($sonpids as $k=>$v) {
				$info['ProIdCard']=$ProId.'_'.$v;
				$info['ProSpec1']=$specs[$k];
				$info['ProSpec2']=' ';
				$info['ProSpec3']=' ';
				$info['ProSpec4']=' ';
				$info['ProSpec5']=' ';
				$info['ProIdInputCard']=$barcodes[$k];
				$info['Price']=$sonprice[$k];
				$info['ProId']=$ProId;
				$info['EmpCut']=$_POST['EmpCut'];
				$info['Iszp']=$_POST['Iszp'];
				$info['IsUseScore']=$_POST['IsUseScore'];
				$info['Score']=$_POST['Score'];
				$info['token']=$this->token;
				$info['Count']=$nums[$k];
				$info['InputCode']=$codes[$k];
				$info['CosPrice']=$cosprices[$k];
				$infos[$ProId.'_'.$v]=$info;
			}

			//
			//图片信息表数据
			$tempI=1;
			foreach ($_POST['imgs'] as $img) {
				$imgdata[]=array('ProImage'=>$img,'ProId'=>$ProId,'ProImageSort'=>$tempI);
				$tempI++;
			}
			//

			/**
			 * json数据拼装
			 */
			$json=array();
			$json['ProId']=$ProId;
			$json['ProName']=htmlspecialchars($_POST['ProName']);
			$json['ProTitle']=htmlspecialchars($_POST['ProTitle']);
			$json['ProSubtitle']=htmlspecialchars($_POST['ProSubtitle']);
			// $json['Freight']=trim($_POST['Freight']);
			$json['FreightRemarks']=htmlspecialchars($_POST['FreightRemarks']);
			$json['Remarks']=htmlspecialchars($_POST['Remarks']);
			$json['KeyWord']=htmlspecialchars($_POST['KeyWord']);
			$json['IsUseConpon']=$_POST['IsUseConpon'];
			$json['IsShelves']=1;
			if ($_POST['ClassType']) {
				$json['ClassType']=$_POST['ClassType'];
			}else{
				$json['ClassType']=$_POST['ClassType1'];
			}
			$json['ClassName']=$this->Class->where('ClassId=%d',$_POST['ClassType'])->getField('ClassName');
			$json['ProContent']=htmlspecialchars($_POST['ProContent']);
			$json['ProLogoImg']=$_POST['ProLogoImg'];
			$json['Cut']=$_POST['Cut'];
			$json['Cut2']=0;
			$json['PriceRange']=$temp;
			$json['Price']=$tempold;
			$json['Cut3']=0;
			$json['ShelvesDate']=$_POST['ShelvesDate'];
			$json['ProNumber']=trim($_POST['ProNumber']);
			$json['Barcode']=trim($_POST['Barcode']);
			$json['Weight']=intval($_POST['Weight']);
			$json['EmpCut']=$_POST['EmpCut'];
			$json['IsUseScore']=$_POST['IsUseScore'];
			$json['Score']=$_POST['Score'];
			$json['Iszp']=$_POST['Iszp'];
			$json['token']=$this->token;
			//主数据end
			//图片数据start
			$json['imgs']=$_POST['imgs'];
			//商品子表数据
			$json['ProductList']=$infos;
			//仓库表插入数据
			foreach ($infos as $key => $value) {
				$ckdata['ProId']=$ProId;
				$ckdata['ProIdCard']=$key;
				$ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$ckdata['StockCount']=0;
				$ckdata['LimitCount']=0;
				$ckdata['VirtualCount']=0;
				$ckdata['InCount']=0;
				$ckdata['SalesCount']=0;
				$ckdata['OutCount']=0;
				$ckdata['ReturnCount']=0;
				$ckdata['IsDelete']=0;
				$ckdata['CreateDate']=date('Y-m-d H:i:s',time());

				$cdata[]=$ckdata;
			}
			$model=M();
			//
			// $tbName=$this->getCK(); //获取主仓库表名
			$tbNames=$this->getAllWare(); //获取主仓库表名
			// var_dump($tbNames);exit();
			$model->startTrans();
			$prores=$model->table('Rs_Product')->add($Pdata);
			$infores=true;
			foreach ($infos as $sonInfo) {
				if (!$model->table('Rs_ProductList')->add($sonInfo)) {
					$infores=false;
						break;
				}
			}
			$this->SH()->startTrans();
			$cres=true;
			foreach ($tbNames as $tb) {
				if ($cres==false) {
					break;
				}
				foreach ($cdata as $ccdata) {
					if (!$this->SH($tb)->add($ccdata)) {
						$cres=false;
						break;
					}
				}
			}

			$data['ProId']=$ProId;
			$data['ProLabel']='2';
			$data['LabelType']=0;
			M()->table('RS_ProductLabelList')->add($data);

			// }
			//判断关联信息
			$impres=true;
			$onlyone=true;
			if ($prores && $infores  && $cres && $onlyone && $impres) {
				file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
				$this->SH()->commit();
				$model->commit();
				$this->success('添加成功',U('Products/index'));
			}else{
				$this->LOGS("prores=$prores ... infores=$infores  ... cres=$cres ... onlyone=$onlyone ... impres=$impres");
				$this->LOGS($this->SH()->getlastsql());
				$this->SH()->rollback();
				$model->rollback();
				if (!$onlyone) {
					$this->error('关联导入数据重复');
				}else{
					$this->error('添加失败');
				}
			}
		}else{
			exit('非法操作');
		}
	}

	/**
	 * 商品属性删除
	 */
	public function delatr(){
		$pid=$_POST['pid'];
		$tbs=$this->getCK();
		M()->startTrans();
		$this->SH()->startTrans();
		$pres=M()->table('RS_ProductList')->where("ProIdCard='%s'",$pid)->delete();
		$wres=true;
		foreach ($tbs as $tb) {
			if (!$this->SH($tb)->where("ProIdCard='%s'",$pid)->setField("IsDelete",1)) {
				$wres=false;
				break;
			}
		}
		if ($pres && $wres) {
			M()->commit();
			$this->SH()->commit();
			$msg['status']='success';
		}else{
			M()->rollback();
			$this->SH()->rollback();
			$msg['status']='error';
		}
		echo json_encode($msg);
	}

	/**
	 * 商品分类显示
	 */
	public function category(){
		$classes=$this->Class->where("token='%s'",$this->token)->order('ClassSort')->select();
		$dclass=$this->Class->where("ParentClassId=0 and token='".$this->token."'")->order('ClassSort')->select();
		foreach ($dclass as &$cls) {
			$sonAry=array();
			foreach ($classes as $scls) {
				if ($cls['ClassId']==$scls['ParentClassId']) {
					$sonAry[]=$scls;
				}
			}
			$cls['sons']=$sonAry;
		}
		$this->assign('dclass',$dclass);
		$this->assign('jsondata',json_encode($dclass));
		$this->assign(array('classes'=>$dclass));
		$allClass=$this->Class->where("token='%s'",$this->token)->select();
		foreach ($allClass as &$c) {
			if ($c['ParentClassId']) {
				$tempp=explode('-', $c['ClassSort']);
				$c['sort']=$tempp[1];
			}else{
				$c['sort']=$c['ClassSort'];
			}
		}
		$mInfo=$this->MSL('merchant')->where(array('token'=>$this->token))->find();
		$mInfo=$mInfo['classDisplay'];

		$pinfos=M()->table('RS_Product')->where("token='%s'",$this->token)->select();
		$this->assign('pinfos',$pinfos);
		$this->assign('classDisplay',$mInfo);
		$this->assign('AllClass',json_encode($allClass));
		define('FPAGE','SHANGPIN');
		$this->display();
	}
	/**
	 * 添加保存商品分类数据
	 */
	public function saveClass(){
		$id=$_POST['id'];
		$data['ClassName']=trim($_POST['ClassName']);
		$data['ParentClassId']=$_POST['ParentClassId'];
		$data['ImgPath']=$_POST['ImgPath'];
		if ($_POST['ParentClassId']) {
			$data['ClassGrade']=2;
		}else{
			$data['ClassGrade']=1;
		}
		$data['IsVisible']=$_POST['IsVisible'];
		$preID=$_POST['ParentSort'];
		if (strlen($_POST['ClassSort'])<2) {
			$sort="0".$_POST['ClassSort'];
		}else{
			$sort=$_POST['ClassSort'];
		}
		if ($_POST['ParentClassId']) {
			$data['ClassSort']=$preID."-".$sort;
		}else{
			$data['ClassSort']=$sort;
		}
		$data['Pid']=$_POST['Pid'];
		$data['ProImg']=$_POST['ProImg'];
		$data['token']=$this->token;
		if ($id) {
			if ($this->Class->where('ClassId=%d',$id)->save($data)) {
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}else{
			if ($this->Class->add($data)) {
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}
	}

	/**
	 * 设置商户首页分类显示类型
	 */
	public function saveClass2(){
		//

		if ($this->MSL('merchant')->where(array('token'=>$this->token))->save(array('classDisplay'=>$_POST['classDiaplay']))) {
				$this->success('修改成功');
		}
		else
		{
				$this->error('修改失败');
		}
	}


	/**
	 * 分类删除
	 */
	public function delClass(){
		$id=$_GET['id'];
		if ($id) {
			if ($this->Class->where('ParentClassId=%d',$id)->find()) {
				$this->error('该分类下有子分类，无法删除');
			}else{
				if ($this->Class->where('ClassId=%d',$id)->delete()) {
					$this->success('删除成功');
				}else{
					$this->error('删除失败');
				}
			}

		}else{
			echo "<script>alert('非法操作');location.href='".U('Products/category')."'</script>";
		}
	}
	/**
	 * ajax获取分类信息
	 */
	public function getClassInfo(){
		$id=$_POST['id'];
		$classInfo=$this->Class->where('ClassId=%d',$id)->find();
		if ($classInfo) {
			echo json_encode($classInfo);
		} else {
			echo "ERROR";
		}
	}
	/**
	 * 获取子分类信息
	 */
	public function getSonClass(){
		$id=$_POST['id'];
		$sonClass=$this->Class->where('ParentClassId=%d',$id)->order('ClassSort asc')->select();
		echo json_encode($sonClass);
	}
	/**
	 * 属性管理
	 */
	public function attributes(){
		$count=$this->Attribute->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
		$page= new \Think\Page($count,15);
		$attrs=$this->Attribute->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($attrs as &$attr) {
			$attr['values']=$this->AttributeValue->where('AttributeId=%d',$attr['AttributeId'])->select();
		}
		$this->assign(array('attrs'=>$attrs,'page'=>$page->show(),'attrjson'=>json_encode($attrs)));
		define('FPAGE','SHANGPIN');
		$this->display();
	}
	/**
	 * ajax删除属性值
	 */
	public function delAttrValue(){
		$id=$_POST['id'];
		if ($this->AttributeValue->where('AttributeValueId=%d',$id)->delete()) {
			echo "success";
		}else{
			echo "error";
		}
	}
	/**
	 * 删除属性
	 */
	public function delAttribute(){
		$id=$_GET['id'];
		if ($this->Attribute->where('AttributeId=%d',$id)->delete()) {
			if ($this->AttributeValue->where('AttributeId=%d',$id)->find()) {
				$this->AttributeValue->where('AttributeId=%d',$id)->delete();
			}
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	/**
	 * 保存属性数据
	 */
	public function saveAttrs(){
		$data['AttributeName']=$_POST['AttributeName'];
		$data['IsEnable']=$_POST['IsEnable'];
		$data['token']=$this->token;
		$data['stoken']=0;
		if ($_POST['AttributeId']) {
			$data['AttributeId']=$_POST['AttributeId'];
			if ($this->Attribute->save($data)) {
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}else{
			if ($this->Attribute->add($data)) {
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}
	}
	/**
	 * 商品编辑页面数据
	 */
	public function proedit(){
		$pid=$_GET['pid'];
		$Suppliers=M()->table('RS_Supplier')->where("token='%s'",$this->token)->select();
		$this->assign('Suppliers',$Suppliers);
		define('FPAGE','SHANGPIN');
		//判断json文件是否存在，选择读取方式
		if (file_exists($this->REALPATH.C('STATICPATH').$pid.'.json')) {
			$proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$pid.'.json'),true);
			// var_dump($proinfo);exit();
			$this->OldAttr=$proinfo['attrs'];
			$proinfo['ProductList']=M()->table('Rs_ProductList')->where("ProId='%s' and IsDelete=%d",array($pid,0))->field("CONVERT(float(50),Price,120) as Price,ProIdCard,ProSpec1,ProIdInputCard,Count,InputCode,CosPrice")->select();
			$prodata=M()->table('RS_Product')->where("ProId='%s'",$pid)->find();
			$proinfo['IsUseScore']=$prodata['IsUseScore'];
			$proinfo['Iszp']=$prodata['Iszp'];
			$proinfo['ExtendCut']=$prodata['ExtendCut'];
			$proinfo['SupplierId']=$prodata['SupplierId'];
			$this->assign('proinfo',$proinfo);
			$this->assign(array('attrcount'=>count($proinfo['attrs']),'atnames'=>json_encode(array_keys($proinfo['attrs'])),'ProductList'=>json_encode($proinfo['ProductList'])));
			$tempStr=array();
			foreach (array_keys($proinfo['attrs']) as $value) {
				$tempId=explode('_',$value)[1];
				$tempStr[$tempId]=$this->AttributeValue->where('AttributeId=%d',$tempId)->select();
			}
			$this->assign('tempStr',json_encode($tempStr));
			// var_dump($tempStr);
		}else{
			$proinfo=$this->Product->where("ProId='%s'",$pid)->find();
			$proinfo['ProductList']=M()->table('Rs_ProductList')->where("ProId='%s' and IsDelete=%d",array($pid,0))->field("CONVERT(float(50),Price,120) as Price,ProIdCard,ProSpec1,ProIdInputCard,Count")->select();
			$proinfo['imgs']=M()->table('Rs_ProductImage')->where("ProId='%s'",$pid)->order('ProImageSort asc')->getField('ProImage',true);
			$this->assign('proinfo',$proinfo);

		}

		$class=$this->Class->where('ClassId=%d',$proinfo['ClassType'])->find();
		$nowClass=$this->Class->where('ClassId=%d',$class['ParentClassId'])->getField('ClassName')."-->".$class['ClassName'];
		$oclass=$this->Class->where("ParentClassId=0 and token='".$this->token."'")->select();
		// var_dump(M()->getlastSql());
		$lastpl=M()->table('RS_ProductList')->where("ProId='%s'",$pid)->order('ID desc')->find()['ProIdCard'];
		// var_dump(M()->getlastsql());
		$spid=substr($lastpl, strpos($lastpl,'_')+1)+1;
		// var_dump($spid);
		$this->assign('spid',$spid);
		$allClass=$this->Class->getAll($this->token);
		$this->assign(array('oclass'=>$oclass,'allClass'=>$allClass));
		$this->assign('ProId',$pid);
		$this->assign(array('nowClass'=>$nowClass));
		$this->display();
	}
	/**
	 * 保存商品修改数据
	 * 程序待优化。。。
	 */
	public function saveEdit(){
		$ProId=$_POST['ProId'];

		$proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$ProId.'.json'),true);
		$oattr=$proinfo['attrs'];
		$oldlist=$proinfo['ProductList'];
		if ($_POST['IsAddAttr']) {
			$tempPri=min(array_merge($_POST['prices'],$_POST['new_prices']));
		}else{
			$tempPri=min($_POST['prices']);
		}
		$tempold=$_POST['oldprice'];

		//主表数据start
		$proData['ProId']=$_POST['ProId'];
		$proData['ProName']=trim($_POST['ProName']);
		$proData['ProTitle']=$_POST['ProTitle'];
		$proData['ProSubtitle']=$_POST['ProSubtitle'];
		$proData['IsShelves']=1;
		$proData['Freight']=$_POST['Freight'];
		$proData['FreightRemarks']=$_POST['FreightRemarks'];
		$proData['Remarks']=$_POST['Remarks'];
		$proData['KeyWord']=$_POST['KeyWord'];
		$proData['ClassType']=$_POST['ClassType'];
		$proData['ClassName']=$this->Class->where('ClassId=%d',$_POST['ClassType'])->getField('ClassName');
		$proData['IsUseConpon']=$_POST['IsUseConpon'];
		// $proData['ProContent']=htmlspecialchars($_POST['ProContent']);
		$proData['LastUpdateDate']=date('Y-m-d H:i:s',time());
		$proData['ProLogoImg']=$_POST['ProLogoImg'];
		$proData['Price']=$tempold;
		$proData['PriceRange']=$tempPri;
		$proData['Cut']=$_POST['Cut'];
		$proData['Cut2']=0;
		$proData['Cut3']=0;
		$proData['ShelvesDate']=$_POST['ShelvesDate'];
		$proData['ProNumber']=trim($_POST['ProNumber']);
		$proData['Barcode']=trim($_POST['Barcode']);
		$proData['Weight']=intval($_POST['Weight']);
		$proData['EmpCut']=$_POST['EmpCut'];
		$proData['IsUseScore']=$_POST['IsUseScore'];
		$proData['Score']=$_POST['Score'];
		$proData['Iszp']=$_POST['Iszp'];
		$proData['ExtendCut']=$_POST['ExtendCut'];
		$proData['SupplierId']=$_POST['SupplierId'];
		$proData['token']=$this->token;
		// 主表数据end

		//子表数据start
		$oldsonprices=$_POST['prices'];  //价格
		$oldsoncount=$_POST['nums'];  //数量
		$ProIdInputCard=$_POST['barcodes'];  //条码
		$ProIdCard=$_POST['ProIdCards'];  //编号
		$specs=$_POST['specs']; //属性
		$codes=$_POST['oldcodes'];
		$csprices=$_POST['oldcosprice'];
		foreach ($ProIdCard as $sk => $sv)
		{
			$son['Price']=$oldsonprices[$sk];
			$son['ProIdInputCard']=$ProIdInputCard[$sk];
			$son['ProIdCard']=$sv;
			$son['LastUpdateDate']=date('Y-m-d H:i:s',time());
			$son['ProId']=$_POST['ProId'];
			$son['EmpCut']=$_POST['EmpCut'];
			$son['ProSpec1']=$specs[$sk];
			$son['ProSpec2']=' ';
			$son['ProSpec3']=' ';
			$son['ProSpec4']=' ';
			$son['ProSpec5']=' ';
			$son['Iszp']=$_POST['Iszp'];
			$son['IsUseScore']=$_POST['IsUseScore'];
			$son['Score']=$_POST['Score'];
			$son['token']=$this->token;
			$son['Count']=$oldsoncount[$sk];
			$son['InputCode']=$codes[$sk];
			$son['CosPrice']=$csprices[$sk];
			$sonData[$sv]=$son;
		}
		//子表数据end
		if ($_POST['IsAddAttr'])
		{
			//子表新增数据start
			$newsonprices=$_POST['new_prices'];
			$nums=$_POST['new_barcodes'];
			$newsoncount=$_POST['new_nums'];
			$newpids=$_POST['new_pids'];
			$newspecs=$_POST['new_specs'];
			$newoces=$_POST['new_codes'];
			$newcosprices=$_POST['new_cosprice'];
			foreach ($newpids as $nk => $nv) {
				$newSon['ProIdCard']=$proidcard=$ProId.'_'.$nv;
				$newSon['ProSpec1']=$newspecs[$nk];
				$newSon['ProSpec2']=' ';
				$newSon['ProSpec3']=' ';
				$newSon['ProSpec4']=' ';
				$newSon['ProSpec5']=' ';
				$newSon['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$newSon['ProIdInputCard']=$nums[$nk];
				$newSon['Count']=$newsoncount[$nk];
				$newSon['Price']=$newsonprices[$nk];
				$newSon['ProId']=$_POST['ProId'];
				$newSon['EmpCut']=$_POST['EmpCut'];
				$newSon['Iszp']=$_POST['Iszp'];
				$newSon['IsUseScore']=$_POST['IsUseScore'];
				$newSon['Score']=$_POST['Score'];
				$newSon['token']=$this->token;
				$newSon['InputCode']=$newoces[$nk];
				$newSon['CosPrice']=$newcosprices[$nk];
				$newSonData[$proidcard]=$newSon;
			}
			//仓库数据
			foreach ($newSonData as $cnk => $cnv) {
				$cndata['ProId']=$_POST['ProId'];
				$cndata['ProIdCard']=$cnk;
				$cndata['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$cndata['StockCount']=0;
				$cndata['LimitCount']=0;
				$cndata['VirtualCount']=0;
				$cndata['InCount']=0;
				$cndata['SalesCount']=0;
				$cndata['OutCount']=0;
				$cndata['ReturnCount']=0;
				$cndata['IsDelete']=0;
				$cndata['CreateDate']=date('Y-m-d H:i:s',time());
				$cnsData[]=$cndata;
			}
		}
		//新增数据end

		//图片数据
		$imgdata[]=$_POST['mainImg'];
		if ($_POST['img1']) {
			$imgdata[]=$_POST['img1'];
		}
		if ($_POST['img2']) {
			$imgdata[]=$_POST['img2'];
		}
		if ($_POST['img3']) {
			$imgdata[]=$_POST['img3'];
		}
		if ($_POST['img4']) {
			$imgdata[]=$_POST['img4'];
		}
		if ($_POST['img5']) {
			$imgdata[]=$_POST['img5'];
		}

		//图片数据end
		//json数据拼装
		$jsons=array();
		$jsons['ProId']=$_POST['ProId'];
		$jsons['ProName']=trim($_POST['ProName']);
		$jsons['ProTitle']=$_POST['ProTitle'];
		$jsons['ProSubtitle']=$_POST['ProSubtitle'];
		$jsons['IsShelves']=1;
		$jsons['FreightRemarks']=$_POST['FreightRemarks'];
		$jsons['Remarks']=$_POST['Remarks'];
		$jsons['KeyWord']=$_POST['KeyWord'];
		$jsons['ClassType']=$_POST['ClassType'];
		$jsons['ClassName']=$this->Class->where('ClassId=%d',$_POST['ClassType'])->getField('ClassName');
		$jsons['IsUseConpon']=$_POST['IsUseConpon'];
		$jsons['ProContent']=htmlspecialchars($_POST['ProContent']);
		$jsons['LastUpdateDate']=date('Y-m-d H:i:s',time());
		$jsons['ProLogoImg']=$_POST['ProLogoImg'];
		$jsons['Cut']=$_POST['Cut'];
		$jsons['Cut2']=0;
		$jsons['PriceRange']=$tempPri;
		$jsons['Price']=$tempold;
		$jsons['Cut3']=0;
		$jsons['ShelvesDate']=$_POST['ShelvesDate'];
		$jsons['ProNumber']=trim($_POST['ProNumber']);
		$jsons['Barcode']=trim($_POST['Barcode']);
		$jsons['Weight']=intval($_POST['Weight']);
		$jsons['EmpCut']=$_POST['EmpCut'];
		$jsons['imgs']=$imgdata;
		$jsons['IsUseScore']=$_POST['IsUseScore'];
		$jsons['Score']=$_POST['Score'];
		$jsons['Iszp']=$_POST['Iszp'];
		$jsons['token']=$this->token;
		//执行添加
		$model=M();
		//获取仓库表名
		// $tbNames=$this->getCK();
		$tbNames=$this->getAllWare();
		//
		$model->startTrans();
		$prores=$model->table('RS_Product')->where("ProId='%s'",$proData['ProId'])->save($proData);
		$sonRes=$newSonRes=$ckRes=$simgRes=$newimp=true;
		foreach ($sonData as $sData) {
			if (!$model->table('RS_ProductList')->where("ProIdCard='%s'",$sData['ProIdCard'])->save($sData)) {
				$sonRes=false;
				break;
			}
		}
		if ($_POST['IsAddAttr']) {
			foreach ($newSonData as $nsData) {
				if (!$model->table('RS_ProductList')->add($nsData)) {
					$this->LOGS(M()->getlastsql());
					$newSonRes=false;
					break;
				}
			}
			$this->SH()->startTrans();
			foreach ($tbNames as $tb) {
				if ($ckRes==false) {
					break;
				}
				foreach ($cnsData as $cdata) {
					if (!$this->SH($tb)->add($cdata)) {
						// var_dump($this->SH()->getlastsql());exit();
						$ckRes=false;
						break;
					}
				}
			}
		}

		//是否处理导入数据
		$onlyone=true;
		if ($sonRes && $newSonRes && $prores && $ckRes && $simgRes && $newimp && $onlyone) {
			$this->SH()->commit();
			$model->commit();
			$file_sons=M()->table('RS_ProductList')->where("token='%s' and ProId='%s' and IsDelete=%d",array($this->token,$ProId,0))->select();
			$jsons['ProductList']=$file_sons;
			file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json',json_encode($jsons));  //生成json数据文件
			$this->success('修改成功...',U('Products/index'));
		}else{
			// var_dump($sonRes , $newSonRes , $prores , $ckRes , $simgRes , $newimp , $onlyone);exit();
			$this->LOGS("商品修改失败--->>>$sonRes && $newSonRes && $prores && $ckRes && $simgRes && $newimp && $onlyone");
			$this->SH()->rollback();
			$model->rollback();
			if (!$onlyone) {
				$this->error('关联导入数据重复');
			}else{
				$this->error('修改失败');
			}
		}


	}

	/**
	 * 删除商品
	 */
	public function deletePro(){
		$pid=$_GET['id'];
		$model=M();
		$tablenames=$this->getCK();
		$model->startTrans();
		$Pres=$model->table('RS_Product')->where("ProId='%s'",$pid)->delete();
		$PLres=$model->table('RS_ProductList')->where("ProId='%s'",$pid)->delete();
		$model->table('RS_MemberCollect')->where("ProId='%s' and token='%s'",array($pid,$this->token))->setField('IsDelete',1);
		$Wres=true;
		$this->SH()->startTrans();
		foreach ($tablenames as $tb) {
			if (!$this->SH($tb)->where("ProId='%s'",$pid)->setField('IsDelete',1)) {
				$Wres=false;
				break;
			}
		}
		if ($Pres && $Wres && $PLres) {
			$this->SH()->commit();
			$model->commit();
			$filename=$this->REALPATH.C('STATICPATH').$pid.".json";
			unlink($filename);
			$this->success('删除成功');
		}else{
			$this->SH()->rollback();
			$model->rollback();
			$this->error('删除失败');
		}
	}
	/**
	 * 商品检索
	 */
	public function search(){
		if (IS_POST) {
			$statu=$_POST['statu'];
			$tempData=$_POST;
		}else{
			$statu=$_GET['statu'];
			$tempData=$_GET;
		}
		if ($tempData['statu']=='time') {
			if ($tempData['IsShelves']==2) {
				$str="token='".$this->token."' and CreateDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
			}else{
				$str="token='".$this->token."' and IsShelves='".$tempData['IsShelves']."' AND  CreateDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
			}
			$count=$this->Product->where($str)->count();
			$page=new \Think\Page($count,25,$tempData);
			$products=$this->Product->where($str)->limit($page->firstRow.','.$page->listRows)->select();
			foreach ($products as &$pro) {
				$pro['img']=$pro['ProLogoImg'];
				if ($bq=M()->table('RS_ProductLabelList')->where("ProId='%s'",$pro['ProId'])->getField('ProLabel',true)) {
					if (in_array('1', $bq)) {
						$pro['bq1']='1';
					}
					if (in_array('2', $bq)) {
						$pro['bq2']='2';
					}
				}

			}
			$this->assign(array('products'=>$products,'page'=>$page->show()));
			$this->display('index');
		}
		if ($tempData['statu']=='key') {
			$where="token='".$this->token."'";
			if ($tempData['IsShelves']!=2) {
				$where.=" AND IsShelves='".$tempData['IsShelves']."'";
			}
			if ($tempData['ProName']) {
				$where.=" AND ProName like '%".$tempData['ProName']."%'";
			};
			if ($tempData['ProNumber']) {
				$where.=" AND ProNumber like '%".$tempData['ProNumber']."%'";
			};
			if ($tempData['ClassType']) {
				$where.=" AND ClassType=".$tempData['ClassType']."";
			};
			$count=$this->Product->where($where)->count();
			$page=new \Think\Page($count,20,$tempData);
			$products=$this->Product->where($where)->limit($page->firstRow.','.$page->listRows)->select();
			foreach ($products as &$pro) {
				$pro['img']=$pro['ProLogoImg'];
				if ($bq=M()->table('RS_ProductLabelList')->where("ProId='%s'",$pro['ProId'])->getField('ProLabel',true)) {
					if (in_array('1', $bq)) {
						$pro['bq1']='1';
					}
					if (in_array('2', $bq)) {
						$pro['bq2']='2';
					}
				}

			}
			if ($tempData['type']=='import') {
			  $stoken=$_GET['stoken']?$_GET['stoken']:0;
			  $products=$this->Product->where($where." and stoken='{$stoken}'")->field("ProNumber,ProName,Price,PriceRange,SalesCount,(CASE WHEN IsShelves=0 THEN '否' ELSE '是' END ) AS IsShelves,CONVERT(varchar(100), CreateDate, 120) as CreateDate,Barcode")->select();
              $xlsName="ProductList_".date('ymdHm');
              $xlsCell = array(
                  array('ProNumber' , '商品编号'),
                  array('ProName' , '商品名称'),
                  array('Price' , '价格'),
                  array('PriceRange','出售价格'),
                  array('SalesCount' , '销量'),
                  array('IsShelves' , '是否上架'),
                  array('CreateDate' , '上传时间'),
                  array('Barcode' , '条形码'),
              );
              exportExcel($xlsName,$xlsCell,$xlsData=$products);
			}
			$allClass=$this->Class->where("token='%s'",$this->token)->order('ClassSort')->select();
			$this->assign(array('products'=>$products,'page'=>$page->show(),'allClass'=>$allClass));
			$this->display('index');

		}
	}
	/**
	 * ajax热卖、新品操作
	 * LabelType=0
	 * Prolabel 新品=2 热卖=1
	 */
	public function showHome(){
		$pid=$_POST['pid'];
		$statu=$_POST['statu'];
		$type=$_POST['type'];
		if ($statu=='0') {
			if ($type=='hot') {
				if (M()->table('RS_ProductLabelList')->where("ProId='%s' and ProLabel='%s'",array($pid,'1'))->delete()) {
					echo "success";
				}else{
					echo "error";
				}
			}else{
				if (M()->table('RS_ProductLabelList')->where("ProId='%s' and ProLabel='%s'",array($pid,'2'))->delete()) {
					echo "success";
				}else{
					echo "error";
				}
			}
		}else{
			if ($type=='hot') {
				$data['ProId']=$pid;
				$data['ProLabel']='1';
				$data['LabelType']=0;
				if (M()->table('RS_ProductLabelList')->add($data)) {
					echo "success";
				}else{
					echo "error";
				}
			}else{
				$data['ProId']=$pid;
				$data['ProLabel']='2';
				$data['LabelType']=0;
				if (M()->table('RS_ProductLabelList')->add($data)) {
					echo "success";
				}else{
					echo "error";
				}
			}
		}
	}
	/**
	 * 上下架操作
	 */
	public function setUp(){
		$statu=$_POST['statu'];
		$pid=$_POST['pid'];
		if (M()->table('RS_Product')->where("ProId='%s'",$pid)->setField('IsShelves',$statu)) {
			echo "success";
		}else{
			echo "error";
		}
	}

	//删除属性
	public function delAttr(){
		$id=$_POST['id'];
		//获取仓库表名
		$tbName=$this->getCK();

		$model=M();
		$model->startTrans();
		$statu=true;
		$this->SH()->startTrans();
			if (!$this->SH($tbName)->where("ProIdCard='%s'",$id)->setField('IsDelete',1)) {
				$statu=false;
			}

		if ($model->table('RS_ProductList')->where("ProIdCard='%s'",$id)->setField('IsDelete',1)) {
			$res=true;
		}else{
			$res=false;
		}
		if ($statu && $res) {
			$this->SH()->commit();
			$model->commit();
			echo "success";

		}else{
			// var_dump('仓库结果:'.$statu.$this->SH()->getlastsql().'字表:'.$res.M()->getlastsql());
			$this->LOGS('仓库结果:'.$statu.$this->SH()->getlastsql().'字表:'.$res.M()->getlastsql());
			$this->SH()->rollback();
			$model->rollback();
			echo "error";

		}
	}
	/**
	 * 运费模板列表
	 */
	public function yunfei(){
		$count=M()->table('RS_Freight')->where("Blong=%d and token='%s' and stoken='%s'",array(0,$this->token,$this->stoken))->count();
		$page= new \Think\Page($count,5);
		$yfs=M()->table('RS_Freight')->where("Blong=%d and token='%s' and stoken='%s'",array(0,$this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($yfs as &$yf) {
			$yf['Area']=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($yf['ID'],'1'))->getField('Area',true);
			$yf['Area1']=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($yf['ID'],'2'))->getField('Area',true);
		}
		$this->assign('Freights',$yfs);
		$this->assign('page',$page->show());
		define('FPAGE','SHANGPIN');
		$this->display();
	}
	/**
	 * 添加运费模板数据
	 */
	public function addYF(){
		if (IS_POST) {
			$data['Name']=$_POST['name'];
			$data['Opiece']=$_POST['Opiece'];
			$data['AddWeight']=$_POST['AddWeight'];
			$data['FirstWeight']=$_POST['FirstWeight'];
			$data['Oadd']=$_POST['Oadd'];
			$data['token']=$this->token;
			$data['stoken']=$this->stoken;
			if ($_POST['Tpiece']) {
				$data['Tpiece']=$_POST['Tpiece'];
			}else{
				$data['Tpiece']=0;
			}
			if ($_POST['Tadd']) {
				$data['Tadd']=$_POST['Tadd'];
			}else{
				$data['Tadd']=0;
			}

			$data['Remarks']=$_POST['Remarks'];

			$province=$_POST['province'];
			$province1=$_POST['province1'];
			if ($_POST['ID']) {
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$model=M();
				$model->startTrans();
				$Fid=$model->table('RS_Freight')->where('ID=%d',$_POST['ID'])->save($data);
				// echo $model->getlastsql();exit();
				$dres=$model->table('RS_Freight_Area')->where('FreightID=%d',$_POST['ID'])->delete();
				$p=true;
				$p1=true;
				foreach ($province as $pro) {
					$pd=array('Price'=>1,'FreightID'=>$_POST['ID'],'Area'=>$pro);
					if (!$model->table('RS_Freight_Area')->add($pd)) {
						$p=false;
						break;
					}
				}
				foreach ($province1 as $pro1) {
					$pd1=array('Price'=>2,'FreightID'=>$_POST['ID'],'Area'=>$pro1);
					if (!$model->table('RS_Freight_Area')->add($pd1)) {
						$p1=false;
						break;
					}
				}
				// var_dump($Fid,$p,$p1,$dres);exit();
				if ($Fid && $p && $p1) {
					$model->commit();
					$this->success('修改成功',U('Products/yunfei'));
				}else{
					$model->rollback();
					$this->error('修改失败');
				}
			}else{
				$model=M();
				$model->startTrans();
				$Fid=$model->table('RS_Freight')->add($data);
				// echo $model->getlastsql();exit();
				$p=true;
				$p1=true;
				foreach ($province as $pro) {
					$pd=array('Price'=>1,'FreightID'=>$Fid,'Area'=>$pro);
					if (!$model->table('RS_Freight_Area')->add($pd)) {
						$p=false;
						break;
					}
				}
				foreach ($province1 as $pro1) {
					$pd1=array('Price'=>2,'FreightID'=>$Fid,'Area'=>$pro1);
					if (!$model->table('RS_Freight_Area')->add($pd1)) {
						$p1=false;
						break;
					}
				}
				// var_dump($Fid,$p,$p1);exit();
				if ($Fid && $p && $p1) {
					$model->commit();
					$this->success('添加成功',U('Products/yunfei'));
				}else{
					$model->rollback();
					$this->error('添加失败');
				}
			}
		}else{
			$this->assign('area',C('AREA'));
			define('FPAGE','SHANGPIN');
			$this->display();
		}
	}
	/**
	 * 编辑运费模板
	 */
	public function editYF(){
		$id=$_GET['id'];
		$info=M()->table('RS_Freight')->where('ID=%d',$id)->find();
		$area=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($id,'1'))->getField('Area',true);
		$area1=M()->table('RS_Freight_Area')->where("FreightID=%d and Price='%s'",array($id,'2'))->getField('Area',true);
		$this->assign(array('info'=>$info,'area'=>C('AREA'),'area1'=>$area,'area2'=>$area1));
		define('FPAGE','SHANGPIN');
		$this->display('addYF');
	}
	/**
	 * 删除运费模板
	 */
	public function delYF(){
		$id=$_GET['id'];
		M()->startTrans();
		$delarea=M()->table('RS_Freight_Area')->where('FreightID=%d',$id)->delete();
		$delfre=M()->table('RS_Freight')->where('ID=%d',$id)->delete();
		if ($delarea && $delfre) {
			M()->commit();
			$this->success('删除成功');
		}else{
			M()->rollback();
			$this->error('删除失败');
		}
	}
	/**
	 * 设置默认运费模板
	 */
	public function setMr(){
		$fid=$_GET['fid'];
		M()->table('RS_Freight')->where("Blong=%d and token='%s' and stoken='%s'",array(0,$this->token,$this->stoken))->setField('IsSet',0);
		if (M()->table('RS_Freight')->where('ID=%d',$fid)->setField('IsSet',1)) {
			$this->success('设置成功');
		}else{
			$this->error('设置失败');
		}
	}

	/**
	 * 限时特价商品检索
	 */
	public function searchs(){
		if (IS_POST) {
			$statu=$_POST['statu'];
		}else{
			$statu=$_GET['statu'];
		}
		if ($statu=='time') {
			if (IS_POST) {
				$where['stime']=$_POST['stime'];
				$where['etime']=$_POST['etime'];
				$str="token='".$this->token."' and stoken='".$this->stoken."' and CreateDate BETWEEN '".$_POST['stime']."' and '".$_POST['etime']."'";
			}else{
				$where['stime']=$_GET['stime'];
				$where['etime']=$_GET['etime'];
				$str="token='".$this->token."' and stoken='".$this->stoken."' and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."'";
			}
			$where['statu']='time';
			$count=$this->Product->where($str)->count();
			$page=new \Think\Page($count,25,$where);
			$products=$this->Product->where($str)->limit($page->firstRow.','.$page->listRows)->select();
			foreach ($products as &$pro) {
				$pro['img']=$pro['ProLogoImg'];

			}
			$this->assign(array('products'=>$products,'page'=>$page->show()));
			$this->display('index');
		}
		if ($statu=='key') {
			if (IS_POST) {
				$where="token='".$this->token."' and stoken='".$this->stoken."' and ";
				if ($_POST['ProName']) {
					$tempData['ProName']=$_POST['ProName'];
					$where.="ProName like '%".$_POST['ProName']."%' and ";
				};
				if ($_POST['ProNumber']) {
					$tempData['ProNumber']=$_POST['ProNumber'];
					$where.="ProNumber like '%".$_POST['ProNumber']."%' and ";
				};
				if ($_POST['ClassType']) {
					$tempData['ClassType']=$_POST['ClassType'];
					$where.="ClassType=".$_POST['ClassType']." and ";
				};
				$str=strrev(substr(strrev($where), 4));
			}else{
				$where="token='".$this->token."' and stoken='".$this->stoken."' and ";
				if ($_GET['ProName']) {
					$tempData['ProName']=$_GET['ProName'];
					$where.="ProName like '%".$_GET['ProName']."%' and ";
				};
				if ($_GET['ProNumber']) {
					$tempData['ProNumber']=$_GET['ProNumber'];
					$where.="ProNumber like '%".$_GET['ProNumber']."%' and ";
				};
				if ($_GET['ClassType']) {
					$tempData['ClassType']=$_GET['ClassType'];
					$where.="ClassType=".$_GET['ClassType']." and ";
				};
				$str=strrev(substr(strrev($where), 4));
			}
			$tempData['statu']='key';
			$count=$this->Product->where($str)->count();
			$page=new \Think\Page($count,20,$tempData);
			$products=$this->Product->where($str)->limit($page->firstRow.','.$page->listRows)->select();
			// echo M()->getlastSql();exit();
			foreach ($products as &$pro) {
				$pro['img']=$pro['ProLogoImg'];

			}
			$allClass=$this->Class->where("token='%s'",$this->token)->order('ClassSort')->select();
			$this->assign(array('products'=>$products,'page'=>$page->show(),'allClass'=>$allClass));
			$this->display('sprice');

		}
	}

	/**
	 * 特价信息设置
	 */
	public function setSprice(){
		if ($_POST['Price']) {
			$price=$_POST['Price'];
		}else{
			$this->error('请输入特价价格');
		}
		if ($_POST['Stime']) {
			$stime=$_POST['Stime'];
		}else{
			$this->error('请选择特价开始时间');
		}
		if ($_POST['Etime']) {
			$etime=$_POST['Etime'];
		}else{
			$this->error('请选择特价结束时间');
		}
		$proid=$_POST['ProId'];
		$remarks=$_POST['Remarks'];
		$data=array('stime'=>$stime,'etime'=>$etime,'LastUpdateDate'=>date('Y-m-d H:i:s',time()),'Ison'=>1,'sprice'=>$price,'Remarks'=>$remarks);
		// var_dump($data);exit();
		if (M()->table('RS_ProductOnsale')->where("ProId='%s'",$proid)->find()) {
			if (M()->table('RS_ProductOnsale')->where("ProId='%s'",$proid)->save($data)) {
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}else{
			$data['ProId']=$proid;
			if (M()->table('RS_ProductOnsale')->add($data)) {
				$this->success('设置成功');
			}else{
				// var_dump(M()->getlastsql());exit();
				$this->error('设置失败');
			}
		}
	}

	/**
	 * 取消特价
	 */
	public function noSprice(){
		$ProId=$_GET['pid'];
		if (M()->table('RS_ProductOnsale')->where("ProId='%s'",$ProId)->setField('Ison',0)) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 删除商品展示图
	 */
	public function delProImg(){
		$imgid=$_POST['id'];
		if (M()->table('Rs_ProductImage')->where('ID=%d',$imgid)->delete()) {
			echo "success";
		}else{
			echo M()->getlastsql();exit();
			echo "error";
		}
	}

	/**
	 * 优惠券
	 */
	public function coupons(){
		$time=date('Y-m-d H:i:s',time());
		// ExpiredDate>'{$time}' and
		$whereStr=" token='{$this->token}' and stoken='{$this->stoken}' and IsEnable='1'";
		$count=M()->table('RS_Coupon')->where($whereStr)->count();
		$page = new \Think\Page($count,10);
		$cards=M()->table('RS_Coupon')->where($whereStr)->field("ID,CouponId,CouponName,Rules,Count,AfterCount,CONVERT(varchar(20),CreateDate,120) as CreateDate,CONVERT(varchar(20),StartDate,120) as StartDate,CONVERT(varchar(20),ExpiredDate,120) as ExpiredDate,Type,IsShow")->limit($page->firstRow.','.$page->listRows)->order("ID desc")->select();
		// var_dump(M()->getlastsql());exit();
		$pagedata['page']=$page->show();
		$pagedata['cards']=$cards;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 添加优惠券
	 */
	public function savecoupons(){
		// var_dump($_REQUEST);exit();
		if (IS_POST) {
			// var_dump($_POST);exit;
			$tempStr='qwertyuioplkjhgfdsazxcvbnm';
			$tempNum='1234567890';
			$tempAry=array('现金抵扣券','折扣券','满减券','摇一摇');
			$CouponId=substr(str_shuffle($tempStr),5,5).substr(str_shuffle($tempNum),5,8);
			$data['CouponName']=$tempAry[$_POST['CouponType']];
			$data['Rules']=$_POST['Rules'];
			$data['Count']=$_POST['CouponNum'];
			$data['AfterCount']=$_POST['CouponNum'];
			$data['UserCount']=1;
			$data['Type']=$_POST['CouponType'];
			$data['StartDate']=$_POST['StartDate'];
			$data['ExpiredDate']=$_POST['ExpDate'];
			$data['token']=$this->token;
			$data['stoken']=$this->stoken;
			if ($_POST['oldid']) {
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				if (M()->table('RS_Coupon')->where("ID=%d",$_POST['oldid'])->save($data)) {
					$this->success('保存成功',U('Products/coupons'));
				}else{
					$this->error('保存失败');
				}
			}else{
				$data['CouponId']=$CouponId;
				if (M()->table('RS_Coupon')->add($data)) {
					$this->success('保存成功',U('Products/coupons'));
				}else{
					echo M()->getlastSql();exit();
					$this->error('保存失败');
				}
			}
		}
		// $this->display();
	}

	/**
	 * 添加编辑
	 */
	public function addCoupon(){
		if ($_GET['id']) {
			$info=M()->table('RS_Coupon')->where("ID=%d",$_GET['id'])->field("CONVERT(varchar(20),StartDate,120) as StartDate,CONVERT(varchar(20),ExpiredDate,120) as ExpiredDate,ID,Type,CouponName,Rules,Count,UserCount")->find();
			$pagedata['info']=$info;
		}
		$this->assign($pagedata);
		$this->display();
	}
	/**
	 * 删除优惠券
	 */
	public function setCoupon(){
		if (IS_POST) {
			$cid=$_POST['cid'];
			$id=$_POST['id'];
			$type=$_POST['type'];
			if ($type=='show') {
				if (M()->table('RS_Coupon')->where("ID=%d and CouponId='%s'",array($id,$cid))->setField('IsShow','1')) {
					$msg['status']='success';
				}else{
					$msg['status']='error';
					$msg['info']='处理失败';
					var_dump(M()->getlastsql());
				}
			}elseif ($type=='del') {
				if (M()->table('RS_Coupon')->where("ID=%d and CouponId='%s'",array($id,$cid))->setField('IsEnable','0')) {
					$msg['status']='success';
				}else{
					$msg['status']='error';
					$msg['info']='处理失败';
					var_dump(M()->getlastsql());
				}
			}
			echo json_encode($msg);
		}
	}



	/**
	 * 获取当前商户所有仓库表名
	 */
	 public function getCK($type='main'){
		 if ($type!='main') { //获取全部仓库表名--备用
			 $tempAry=array();
			 $tempAry[]='wh'.substr($this->token,-8,8);
			 $StoreIds=M()->table('RS_Store')->where("token='%s' and IsCheck='1'",$this->token)->getField('id',true);
		 		foreach ($StoreIds as $st) {
		 			$tempAry[]='wh'.substr($this->token,-8,8).'_'.$st;
		 		}
				return $tempAry;
		}else{ //返回主仓库表名
			return 'wh'.substr($this->token,-8,8);
		}
	 }

	 /**
	  * 设置摇一摇优惠券
	  */
	 public function setcpn(){
	 	$cid=$_GET['id'];
	 	M()->startTrans();
	 	$res=M()->table('RS_Coupon')->where("token='%s'",$this->token)->setField('UseType','0');
	 	$ress=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$cid))->setField('UseType','1');
	 	if ($res && $ress) {
	 		M()->commit();
	 		$this->success('设置成功');
	 	}else{
	 		M()->rollback();
	 		$this->error('设置失败');
	 	}
	 }

	 /**
	  * 设置注册送优惠券
	  */
		public function setreg(){
			$cid=$_GET['id'];
		 	M()->startTrans();
		 	$res=M()->table('RS_Coupon')->where("token='%s'",$this->token)->setField('IsReg','0');
		 	$ress=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$cid))->setField('IsReg','1');
		 	if ($res && $ress) {
		 		M()->commit();
		 		$this->success('设置成功');
		 	}else{
		 		M()->rollback();
		 		$this->error('设置失败');
		 	}
		}

		/**
 	  * 优惠规则
 	  */
 		public function discount(){
 			if (IS_POST) {
 				// var_dump($_POST);

 				$data['Consume']=trim($_POST['Consume']);
 				$data['DiscountType']=$_POST['DiscountType'];
 				$data['Discount']=trim($_POST['Discount']);
 				$data['stime']=trim($_POST['stime']);
 				$data['etime']=trim($_POST['etime']);
 				$data['token']=$this->token;
 				if ($_POST['id']) {
 					//
 					$data['LastUpdateTime']=date('Y-m-d H:i:s',time());

 					if ($_POST['DiscountType']=='1')
 					{  //根据优惠类型处理
 						$CouponId=$_POST['CouponId'];
 						$cdata['Rules']=$_POST['Discount'];
 						$cdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
 						$data['CouponId']=$CouponId;
 						// echo "<pre>";
 						// var_dump($cdata);exit;
 						//开始事务
 						M()->startTrans();
 						$cid=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->setField($cdata);
 						$res=M()->table('RS_Discount')->where("ID=%d",$_POST['id'])->save($data);
 						if ($cid && $res) {
 							M()->commit();
 							$this->success('修改成功');
 						}else{
							// echo M()->getlastSql();
 						// 	var_dump($cid,$res);exit;
 							M()->rollback();
 							$this->error('修改失败');
 						}
 					}
 					else if($_POST['DiscountType']=='2')
 					{
 						$CouponId=$_POST['CouponId'];
 						$cdata['Rules']=$_POST['Discount'];
 						$cdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
 						$data['CouponId']=$CouponId;
 						// echo "<pre>";
 						// var_dump($cdata);exit;
 						//开始事务
 						M()->startTrans();
 						$cid=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->setField($cdata);
 						$res=M()->table('RS_Discount')->where("ID=%d",$_POST['id'])->save($data);
 						if ($cid && $res) {
 							M()->commit();
 							$this->success('修改成功');
 						}else{
							// echo M()->getlastSql();
 						// 	var_dump($cid,$res);exit;
 							M()->rollback();
 							$this->error('修改失败');
 						}
 					}
 					else if ($_POST['DiscountType']=='3') {
 						if ($_POST['CouponNotes'] && $_POST['CouponNum']) {
	 						$CouponId=$_POST['CouponId'];
	 						$cdata['Rules']=$_POST['CouponNotes'];
	 						$cdata['UserCount']=$_POST['CouponNum'];
	 						$cdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
	 						$data['CouponId']=$CouponId;
	 						M()->startTrans();
	 						$cid=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->setField($cdata);
	 						$res=M()->table('RS_Discount')->where("ID=%d",$_POST['id'])->save($data);
	 						if ($cid && $res) {
	 							M()->commit();
	 							$this->success('修改成功');
	 						}else{
	 							M()->rollback();
	 							$this->error('修改失败');
	 						}
 						}else{
	 						if (M()->table('RS_Discount')->where('ID=%d',$_POST['id'])->save($data)) {
	 							$this->success('修改成功');
	 						}else{
	 							$this->error('修改失败');
	 						}
 						}
 					}
 					else
 					{
 						//直接插入
 						if (M()->table('RS_Discount')->where('ID=%d',$_POST['id'])->save($data)) {
 							$this->success('修改成功');
 						}else{
 							$this->error('修改失败');
 						}
 					}

 				}
 				else
 				{
				 	$tempStr='qwertyuioplkjhgfdsazxcvbnm';
					$tempNum='1234567890';
					$CouponId=substr(str_shuffle($tempStr),5,5).substr(str_shuffle($tempNum),5,8);
					if (M()->table('RS_Discount')->where("token='%s' and DiscountType='%s' and Consume=%d",array($this->token,$_POST['DiscountType'],trim($_POST['Consume'])))->find()) {
						$this->error('添加失败，该条件已存在！');
					}else{
	 					if ($_POST['DiscountType']=='1')
	 					{  //根据优惠类型处理

	 						$cdata['CouponId']=$CouponId;
	 						$cdata['CouponName']='现金抵扣券';
	 						$cdata['Rules']=$_POST['Discount'];
	 						$cdata['Count']=99999;
	 						$cdata['AfterCount']=99999;
	 						$cdata['UserCount']='1';
	 						$cdata['Type']='0';
	 						$cdata['IsShow']='1';
	 						$cdata['CreateDate']=date('Y-m-d H:i:s',time());
	 						$cdata['ExpiredDate']=date('Y-m-d 00:00:00',strtotime('+365 days'));
	 						$cdata['token']=$this->token;

	 						$data['CouponId']=$CouponId;
	 						// echo "<pre>";
	 						// var_dump($cdata);exit;
	 						//开始事务
							if (M()->table('RS_Discount')->where("token='%s' and DiscountType='%s' and Consume=%d",array($this->token,$_POST['DiscountType'],trim($_POST['Consume'])))->find()) {
								$this->error('添加失败! 该条件规则已存在');
							}
	 						M()->startTrans();
	 						$cid=M()->table('RS_Coupon')->add($cdata);
	 						$res=M()->table('RS_Discount')->add($data);
	 						if ($cid && $res) {
	 							M()->commit();
	 							$this->success('添加成功');
	 						}else{
	 							M()->rollback();
	 							$this->error('添加失败');
	 						}
	 					}
	 					else if($_POST['DiscountType']=='2')
	 					{
	 						$cdata['CouponId']=$CouponId;
	 						$cdata['CouponName']='现金抵扣券';
	 						$cdata['Rules']=$_POST['Discount'];
	 						$cdata['Count']=99999;
	 						$cdata['AfterCount']=99999;
	 						$cdata['UserCount']='1';
	 						$cdata['Type']='0';
	 						$cdata['IsShow']='1';
	 						$cdata['CreateDate']=date('Y-m-d H:i:s',time());
	 						$cdata['ExpiredDate']=date('Y-m-d 00:00:00',strtotime('+365 days'));
	 						$cdata['token']=$this->token;

	 						$data['CouponId']=$CouponId;
	 						// echo "<pre>";
	 						// var_dump($cdata);exit;
	 						//开始事务
							if (M()->table('RS_Discount')->where("token='%s' and DiscountType='%s' and Consume=%d",array($this->token,$_POST['DiscountType'],trim($_POST['Consume'])))->find()) {
								$this->error('添加失败! 该条件规则已存在');
							}
	 						M()->startTrans();
	 						$cid=M()->table('RS_Coupon')->add($cdata);
	 						$res=M()->table('RS_Discount')->add($data);
	 						if ($cid && $res) {
	 							M()->commit();
	 							$this->success('添加成功');
	 						}else{
	 							M()->rollback();
	 							$this->error('添加失败');
	 						}
	 					}
	 					else if ($_POST['DiscountType']=='3') {
	 						if ($_POST['CouponNotes'] && $_POST['CouponNum']) {
	 							M()->startTrans();
		 						$cdata['CouponId']=$CouponId;
		 						$cdata['CouponName']='现金抵扣券';
		 						$cdata['Rules']=$_POST['CouponNotes'];
		 						$cdata['Count']=99999;
		 						$cdata['AfterCount']=99999;
		 						$cdata['UserCount']=$_POST['CouponNum'];
		 						$cdata['Type']='0';
		 						$cdata['IsShow']='1';
		 						$cdata['CreateDate']=date('Y-m-d H:i:s',time());
		 						$cdata['ExpiredDate']=date('Y-m-d 00:00:00',strtotime('+365 days'));
		 						$cdata['token']=$this->token;
		 						$data['CouponNotes']=$_POST['CouponNotes'];
		 						$data['CouponNum']=$_POST['CouponNum'];
		 						$data['CouponId']=$CouponId;
		 						$cid=M()->table('RS_Coupon')->add($cdata);
		 						$res=M()->table('RS_Discount')->add($data);
		 						if ($cid && $res) {
		 							M()->commit();
		 							$this->success('添加成功');
		 						}else{
		 							M()->rollback();
		 							$this->error('添加失败');
		 						}
	 						}else{
		 						if (M()->table('RS_Discount')->add($data)) {
		 							$this->success('添加成功');
		 						}else{
		 							$this->error('添加失败');
		 						}
	 						}
	 					}
	 					else
	 					{
	 						//直接插入
	 						if (M()->table('RS_Discount')->add($data)) {
	 							$this->success('添加成功');
	 						}else{
	 							$this->error('添加失败');
	 						}
	 					}
					}
 				}
 			}else{
 				$count=M()->table('RS_Discount')->where("token='%s'",$this->token)->count();
 				$page= new \Think\Page($count,15);
 				$discounts=M()->table('RS_Discount')->where("token='%s'",$this->token)->limit($page->firstRow.','.$page->listRows)->select();
				// echo "<pre>";
				// var_dump($page);
 				$pagedata['discounts']=$discounts;
 				$pagedata['jsondata']=json_encode($discounts);
 				$pagedata['page']=$page->show();
				define('FPAGE','CUXIAO');
 				$this->assign($pagedata);
 				$this->display();
 			}
 		}

 		/**
 		 * 删除优惠规则
 		 */
 		 public function delDiscount(){
 			 if ($_GET['id']) {
 				 $id=$_GET['id'];
 			 	 $CouponId=M()->table('RS_Discount')->where('ID=%d',$id)->getField('CouponId');
 				 if ($CouponId) {
 					 M()->startTrans();
 					 $cid=M()->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($CouponId,$this->token))->delete();
 					 $res=M()->table('RS_Discount')->where("ID=%d and token='%s'",array($id,$this->token))->delete();
 					 if ($cid && $res) {
 					 	M()->commit();
 						$this->success('删除成功');
  					}else{
 						M()->rollback();
 						$this->error('删除失败');
 					}
 				}else{
 					if (M()->table('RS_Discount')->where("ID=%d and token='%s'",array($id,$this->token))->delete()) {
 						$this->success('删除成功');
 					}else{
 						$this->error('删除失败');
 					}
 				}
 			}else{
 				$this->error('非法操作');
 			}
 		 }

 		 /**
 		  * 赠品/积分设置
 		  */
 		 public function setProType(){
 		 	$tempData=$_POST;
 		 	if ($tempData['type']=='zp') {//赠品设置
 		 		if ($tempData['statu']=='1') { //取消
 		 			M()->startTrans();
 		 			$res=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('Iszp','0');
 		 			$res1=M()->table('RS_ProductList')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('Iszp','0');
 		 			if ($res && $res1) {
 		 				M()->commit();
 		 				echo json_encode("success");
 		 			}else{
 		 				M()->rollback();
 		 				echo json_encode("error");
 		 			}
 		 		}else{
 		 			M()->startTrans();
 		 			$res=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('Iszp','1');
 		 			$res1=M()->table('RS_ProductList')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('Iszp','1');
 		 			if ($res && $res1) {
 		 				M()->commit();
 		 				echo json_encode("success");
 		 			}else{
 		 				M()->rollback();
 		 				echo json_encode("error");
 		 			}
 		 		}
 		 	}elseif ($tempData['type']=='sc') {
 		 		if ($tempData['statu']=='1') { //取消
 		 			M()->startTrans();
 		 			$res=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('IsUseScore','0');
 		 			$res1=M()->table('RS_ProductList')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('IsUseScore','0');
 		 			if ($res && $res1) {
 		 				M()->commit();
 		 				echo json_encode("success");
 		 			}else{
 		 				M()->rollback();
 		 				echo json_encode("error");
 		 			}
 		 		}else{
 		 			$score=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->getField('Score');
 		 			if (intval($score)<=0) {
 		 				echo json_encode("noscore");
 		 			}else{
	 		 			M()->startTrans();
	 		 			$res=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('IsUseScore','1');
	 		 			$res1=M()->table('RS_ProductList')->where("token='%s' and ProId='%s'",array($this->token,$tempData['ProId']))->setField('IsUseScore','1');
	 		 			if ($res && $res1) {
	 		 				M()->commit();
	 		 				echo json_encode("success");
	 		 			}else{
	 		 				M()->rollback();
	 		 				echo json_encode("error");
	 		 			}
 		 			}
 		 		}
 		 	}
 		 }

 	/**
 	 * 商品组合优惠设置
 	 */
 	public function discountpart(){
 		if (IS_POST) {
 			$tempData=$_POST;
 			$DBdata['GroupId']=trim($tempData['GroupId']);
 			$DBdata['GroupName']=trim($tempData['GroupName']);
 			$DBdata['SDate']=$tempData['SDate'];
 			$DBdata['EDate']=$tempData['EDate'];
 			$DBdata['CreateDate']=date('Y-m-d h:i:s',time());
 			$DBdata['token']=$this->token;
 			if ($tempData['type']=='edit') {
 				if (M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$tempData['GroupId']))->save($DBdata)) {
 					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
 			}else{
	 			if (M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$tempData['GroupId']))->find()) {
	 				$this->error('ID重复,请重新生成ID后添加');
	 			}
	 			if (M()->table('RS_Groupdiscount')->add($DBdata)) {
	 				$this->success('添加成功');
	 			}else{
	 				$this->error('添加失败');
	 			}
 			}
 		}
		$count=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->count();
		$page = new \Think\Page($count,10);
		$lists=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->field("GroupId,GroupName,CONVERT(varchar(100),SDate,120) as SDate,CONVERT(varchar(100),EDate,120) as EDate,ProIdCards")->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($lists as &$list) {
			$ProIdCards=unserialize(stripcslashes($list['ProIdCards']));
			// echo "<pre>";
			// var_dump($ProIdCards);
			$sons=array();
			foreach ($ProIdCards as $key => $value) {
				$infos=M()->query("SELECT p.ProName,p.ProLogoImg,pl.ProIdCard,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3,pl.Price as oldPrice FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE pl.ProIdCard='".$key."' and pl.token='".$this->token."'")[0];
				// echo M()->getlastsql().'<br>';
				// var_dump($value);
				$infos['newPrice']=$value['Price'];
				$sons[]=$infos;
			}
			$list['pros']=$sons;
		}
		// echo "<pre>";
		// var_dump($lists);exit();
 		$pagedata['page']=$page->show();
 		$pagedata['lists']=$lists;
 		$pagedata['jsondata']=json_encode($lists);
 		$pagedata['GroupId']=$this->checkgid();
 		$this->assign($pagedata);
		define('FPAGE','CUXIAO');
 		$this->display();
 	}

 	/**
 	 * 删除组合
 	 */
 	public function delgroupdiscount(){
 		$GroupId=trim($_GET['gid']);
 		// if (M()->table('RS_Groupdiscountlist')->where("token='%s' and GroupId='%s'",array($this->token,$GroupId))->find()) {
	 	// 	M()->startTrans();
	 	// 	$res=M()->table('RS_Groupdiscountlist')->where("token='%s' and GroupId='%s'",array($this->token,$GroupId))->delete();
	 	// 	$res1=M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$GroupId))->delete();
	 	// 	// var_dump($res,$res1);
	 	// 	if ($res && $res1) {
	 	// 		M()->commit();
	 	// 		$this->success('删除成功');
	 	// 	}else{
	 	// 		M()->rollback();
	 	// 		$this->error('删除失败');
	 	// 	}
 		// }else{
 			if (M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$GroupId))->delete()) {
 				$this->success('删除成功');
 			}else{
 				$this->error('删除失败');
 			}
 		// }
 	}

 	/**
 	 * 获取组合ID
 	 */
 	public function getGid(){
 		$newGid['statu']='success';
 		$newGid['newGid']=$this->checkgid();
 		echo json_encode($newGid);
 	}

 	/**
 	 * 验证groupid唯一性
 	 */
 	public function checkgid(){
 		$newGid=$this->getStr(6,4);
 		if (M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$newGid))->find()) {
 			$this->checkgid();
 		}else{
 			return $newGid;
 		}
 	}

 	/**
 	 * 删除组合中某一商品
 	 */
 	public function delbyproidcard(){
 		$tempData=$_POST;
 		M()->startTrans();
 		$ProIdCards=M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$tempData['GroupId']))->getField('ProIdCards');
 		$tempAry=unserialize(stripslashes($ProIdCards));
 		// var_dump($tempAry);
 		unset($tempAry[$tempData['ProIdCard']]);
 		// var_dump($tempAry);
 		// array_splice($tempAry, array_search($tempData['ProIdCard'], $tempAry),1); //删除指定proidcard
 		asort($tempAry);
 		// var_dump($tempAry);
 		$newPram=serialize($tempAry);   //重新排列并序列化存入
 		if (M()->table('RS_Groupdiscount')->where("token='%s' and ProIdCards='%s'",array($this->token,$newPram))->find()) {
 			M()->rollback();
 			echo json_encode("cant"); //判断删除后是否与其他组合重复
 		}else{
	 		$newres=M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$tempData['GroupId']))->setField('ProIdCards',$newPram);
	 		// $sonres=M()->table('RS_Groupdiscountlist')->where("token='%s' and GroupId='%s' and ProIdCard='%s'",array($this->token,$tempData['GroupId'],$tempData['ProIdCard']))->delete();
	 		if ($newres) {
	 			M()->commit();
	 			echo json_encode("success");
	 		}else{
	 			M()->rollback();
	 			$this->LOGS('删除组合商品失败'.M()->getlastsql());
	 			echo json_encode("error");
	 		}
 		}
 	}

 	/**
 	 * 大礼包- -
 	 */
 	public function addgifts(){
 		if (IS_POST) {
 			$tempDB['CouponName']=$_POST['CouponName'];
 			$tempDB['IsEnable']=$_POST['IsEnable'];
 			$tempDB['Type']=5;
 			$tempDB['Count']=$_POST['Count'];
 			$tempDB['AfterCount']=$_POST['Count'];
 			$tempDB['token']=$this->token;
 			if ($_POST['CouponId']) {
 				if (M()->table('RS_Coupon')->where("CouponId='%s'",$_POST['CouponId'])->save($tempDB)) {
 					$this->success('保存成功');
 				}else{
	 				$this->LOGS('大礼包更新失败：'.M()->getlastsql());
 					$this->error('保存失败');
 				}
 			}else{
	 			$tempDB['CouponId']=$this->getStr(5,8,false);
	 			if (M()->table('RS_Coupon')->add($tempDB)) {
	 				$this->success('添加成功');
	 			}else{
	 				$this->LOGS('大礼包添加失败：'.M()->getlastsql());
	 				$this->error('添加失败');
	 			}
 			}
 		}else{
 			$count=M()->table('RS_Coupon')->where("token='%s' and Type='%s'",array($this->token,'5'))->count();
 			$page = new \Think\Page($count,15);
 			$lists=M()->table('RS_Coupon')->where("token='%s' and Type='%s'",array($this->token,'5'))->select();
 			foreach ($lists as &$list) {
 				$pros=unserialize(stripslashes($list['ProIdCards']));
 				$pinfo=array();
 				foreach ($pros as $po) {
 					$pinfo[]=M()->query("SELECT p.ProName,p.ProLogoImg,pl.Price,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3 FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE pl.ProIdCard='".$po."'")[0];
 				}
 				$list['pinfo']=$pinfo;
 			}
 			// echo "<pre>";
 			// var_dump($lists);exit();
 			$pagedata['lists']=$lists;
 			$pagedata['page']=$page->show();
 			$pagedata['jsondata']=json_encode($lists);
	 		$this->assign($pagedata);
			define('FPAGE','CUXIAO');
 			$this->display();
 		}
 	}

 	/**
 	 * 删除礼包中指定商品
 	 */
 	public function delgift(){
 		$tempData=$_POST;
 		$ProIdCards=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$tempData['CouponId']))->getField('ProIdCards');
 		$tempAry=unserialize(stripslashes($ProIdCards));
 		array_splice($tempAry, array_search($tempData['ProIdCard'], $tempAry),1); //删除指定proidcard
 		sort($tempAry);
 		$newPram=serialize($tempAry);   //重新排列并序列化存入
 		if (M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$tempData['CouponId']))->setField('ProIdCards',$newPram)) {
 			echo json_encode("success");
 		}else{
 			$this->LOGS('礼包商品删除失败'.M()->getlastsql());
 			echo json_encode("error");
 		}
 	}

 	/**
 	 * 设置注册礼包
 	 */
 	public function setGiftReg(){
 		$CouponId=$_GET['cid'];
 		M()->startTrans();
 		$res=M()->table('RS_Coupon')->where("token='%s' and Type='%s' and UseType='%s'",array($this->token,'5','3'))->setField('UseType','0');//其他 礼包设置为0
 		$res1=M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->setField('UseType','3');
 		//注册礼包设置成3
 		if ($res!==false && $res1) {
 			M()->commit();
 			$this->success('设置成功');
 		}else{
 			// var_dump($res);exit();
 			$this->LOGS('设置注册礼包失败：'.M()->getlastsql());
 			M()->rollback();
 			$this->success('设置失败');
 		}
 	}

 	/**
 	 * 设置礼包开关
 	 */
 	public function setopen(){
 		$CouponId=trim($_GET['cid']);
 		$type=trim($_GET['type']);
 		if ($type=='open') {
 			if (M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->setField('IsEnable','1')) {
 				$this->success('设置成功');
 			}else{
 				$this->LOGS('礼包开启失败:'.M()->getlastsql());
 				$this->error('设置失败');
 			}
 		}else{
 			if (M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->setField('IsEnable','0')) {
 				$this->success('设置成功');
 			}else{
 				$this->LOGS('礼包关闭失败：'.M()->getlastsql());
 				$this->error('设置失败');
 			}
 		}
 	}

 	/**
 	 * 删除礼包
 	 */
 	public function delgifts(){
 		$CouponId=trim($_GET['cid']);
 		if (M()->table('RS_Coupon')->where("token='%s' and CouponId='%s'",array($this->token,$CouponId))->delete()) {
 			$this->success('删除成功');
 		}else{
 			$this->LOGS('礼包删除失败'.M()->getlastsql());
 			$this->error('删除失败');
 		}
 	}

 	public function test(){

 		$res=M()->table('RS_Groupdiscount')->where("token='%s'",$this->token)->order('CreateDate desc')->getField('GroupId,ProIdCards,CreateDate');
 		// echo M()->getlastsql();
 		// echo "<pre>";
 		// var_dump($res);
 	}


 	/**
 	 * 限购设置
 	 */
 	public function setbuy(){
 		if (IS_POST) {
 			// var_dump($_POST);
 			$pid=$_POST['ProId'];
 			$ProId=explode('_', $pid)[0];
 			if (!M()->table('RS_ProductLimitSale')->where("token='%s' and stoken='%s' and ProIdCard='%s'",array($this->token,$this->stoken,$pid))->find()) {
 				$DB['ProId']=$ProId;
 				$DB['ProIdCard']=$pid;
 				$DB['StrDate']=$_POST['strtime'];
 				$DB['EndDate']=$_POST['endtime'];
 				$DB['Num']=$_POST['buynum'];
 				$DB['stoken']=$this->stoken;
 				$DB['token']=$this->token;
 				if (M()->table('RS_ProductLimitSale')->add($DB)) {
 					$this->success('设置成功');
 				}else{
 					$this->error('设置失败');
 				}
 			}else{
 				$this->error('该商品已在限购中');
 			}
 		}else{
 			$pros=M()->query("SELECT pl.ProIdCard as ProId,p.ProName+'#'+pl.ProSpec1+'_'+pl.ProSpec2+'_'+pl.ProSpec3 as ProName FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE p.token='{$this->token}' and p.stoken='{$this->stoken}' and pl.ProIdCard NOT IN (SELECT ProIdCard FROM RS_ProductLimitSale WHERE token='{$this->token}' and stoken='{$this->stoken}')");
 			// var_dump(M()->getlastsql());exit();
 			$pagedata['pros']=$pros;
 			$count = M()->table('RS_ProductLimitSale')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
 			$page  = new \Think\Page($count,15);
 			$limits=M()->table('RS_ProductLimitSale')->join("LEFT JOIN RS_Product ON RS_ProductLimitSale.ProId=RS_Product.ProId")->join("LEFT JOIN RS_ProductList ON RS_ProductLimitSale.ProIdCard=RS_ProductList.ProIdCard")->where("RS_ProductLimitSale.token='%s' and RS_ProductLimitSale.stoken='%s'",array($this->token,$this->stoken))->field("RS_ProductList.ProIdCard as ProId,RS_Product.ProName+'#'+RS_ProductList.ProSpec1+'_'+RS_ProductList.ProSpec2+'_'+RS_ProductList.ProSpec3 as ProName,RS_Product.ProLogoImg,RS_ProductLimitSale.Num,CONVERT(varchar(120),RS_ProductLimitSale.StrDate,20) as StrDate,CONVERT(varchar(120),RS_ProductLimitSale.EndDate,20) as EndDate")->limit($page->firstRow.','.$page->listRows)->select();
 			$pagedata['limits']=$limits;
 			$pagedata['page']=$page->show();
 			$this->assign($pagedata);
 			$this->display();
 		}
 	}

 	/**
 	 * 取消限购
 	 */
 	public function delprolimit(){
 		$pid=$_POST['pid'];
 		if (M()->table('RS_ProductLimitSale')->where("token='%s' and stoken='%s' and ProIdCard='%s'",array($this->token,$this->stoken,$pid))->delete()) {
 			$msg['status']='success';
 		}else{
 			$this->LOGS('限购商品删除失败--->>>'.M()->getlastsql());
 			$msg['status']='error';
 		}
 		echo json_encode($msg);
 	}

 	/**
 	 * 团购商品设置
 	 */
 	public function groupon(){
 		if (IS_POST) {
 			// echo "<pre>";
 			// var_dump($_POST);exit();
 			$pid=$_POST['ProId'];
 			$ProId=explode('_', $pid)[0];
 			if (M()->table('RS_ProductOngroupon')->where("token='%s' and stoken='%s' and ProIdCard='%s'",array($this->token,$this->stoken,$pid))->find()) {
 				$this->error('该属性商品已参加团购');
 			}else{
 				$GDB['ProIdCard']=$pid;
 				$GDB['ProId']=$ProId;
 				$GDB['StrDate']=$_POST['strtime'];
 				$GDB['EndDate']=$_POST['endtime'];
 				$GDB['Price']=$_POST['Price'];
 				$GDB['Num']=$_POST['buynum'];
 				$GDB['token']=$this->token;
 				$GDB['stoken']=$this->stoken;
 				if (M()->table('RS_ProductOngroupon')->add($GDB)) {
 					$this->success('设置成功');
 				}else{
 					$this->error('设置失败');
 				}
 			}
 		}else{
 			$pros=M()->query("SELECT pl.ProIdCard as ProId,p.ProName+'#'+pl.ProSpec1+'_'+pl.ProSpec2+'_'+pl.ProSpec3 as ProName FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE p.token='{$this->token}' and p.stoken='{$this->stoken}' and pl.ProIdCard NOT IN(SELECT ProIdCard FROM RS_ProductOngroupon WHERE token='{$this->token}' and stoken='{$this->stoken}')");
 			$count=M()->table('RS_ProductOngroupon')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
 			$page = new \Think\Page($count,15);
 			$limits=M()->table("RS_ProductOngroupon")->join("LEFT JOIN RS_Product ON RS_ProductOngroupon.ProId=RS_Product.ProId")->join("LEFT JOIN RS_ProductList ON RS_ProductOngroupon.ProIdCard=RS_ProductList.ProIdCard")->where("RS_ProductOngroupon.token='%s' and RS_ProductOngroupon.stoken='%s'",array($this->token,$this->stoken))->field("RS_ProductList.ProIdCard,RS_Product.ProName+'#'+RS_ProductList.ProSpec1+'_'+RS_ProductList.ProSpec2+'_'+RS_ProductList.ProSpec3 as ProName,RS_Product.ProLogoImg,RS_ProductOngroupon.Num,RS_ProductOngroupon.Price,CONVERT(varchar(120),RS_ProductOngroupon.StrDate,20) as StrDate,CONVERT(varchar(120),RS_ProductOngroupon.EndDate,20) as EndDate")->limit($page->firstRow.','.$page->listRows)->select();
 			$pagedata['pros']=$pros;
 			$pagedata['limits']=$limits;
 			$this->assign($pagedata);
 			$this->display();
 		}
 	}

 	/**
 	 * 取消团购
 	 */
 	public function delgroupon(){
 		$pid=$_POST['pid'];
 		if (M()->table('RS_ProductOngroupon')->where("token='%s' and stoken='%s' and ProIdCard='%s'",array($this->token,$this->stoken,$pid))->delete()) {
 			$msg['status']='success';
 		}else{
 			$msg['error']='error';
 		}
 		echo json_encode($msg);
 	}

	/**
 	 * 商户商品
 	 */
	public function merpro(){
		if (IS_POST) {
			$data=$_POST;
		}else{
			$data=$_GET;
		}
		unset($data['v']);
		$stoken=$data['merchants'];
		if ($data && $stoken) {
			$whereStr="token='{$this->token}' and stoken='{$stoken}'";
			if ($data['ProName']) {
				$whereStr.= " and (ProName LIKE '%{$data['ProName']}%')";
			}
			if ($data['ProNumber']) {
				$whereStr.= " and (ProNumber LIKE '%{$data['ProNumber']}%' OR (Barcode LIKE '%{$data['ProNumber']}%'))";
			}
			if ($data['ClassType']) {
				$whereStr.= " and ClassType={$data['ClassType']}";
			}
			if ($data['IsShelves']!='2' && $data['IsShelves']) {
				$whereStr.= " and IsShelves={$data['IsShelves']}";
			}
			$count=$this->Product->where($whereStr)->count();
			$page=new \Think\Page($count,30,$data);
			$products=$this->Product->where($whereStr)->order('ID desc')->limit($page->firstRow.','.$page->listRows)->select();
			// var_dump(M()->getlastsql());exit();
			foreach ($products as &$pro) {
				$pro['img']=$pro['ProLogoImg'];
			}
			$allClass=$this->Class->where("token='%s'",$this->token)->order('ClassSort')->select();
			$this->assign(array('products'=>$products,'page'=>$page->show(),'allClass'=>$allClass));
		}
		$merchants=M()->table('RS_Store')->where("token='%s' and stoken!='%s'",array($this->token,$this->stoken))->field('stoken,storename,id')->select();
		$this->assign('merchants',$merchants);
		$this->display();
	}

	/**
	 * 团购设置  2017-05-14
	 */
	public function GroupBuy(){
		if (IS_POST) {
			//团主体数据
			$GDB=array();
			$GDB['ProId']=explode('_', $_POST['ProId'])[0];
			$GDB['ProIdCard']=$_POST['ProId'];
			$GDB['StartDate']=$_POST['StartDate'];
			$GDB['EndDate']=$_POST['EndDate'];
			$GDB['ProductNum']=$_POST['ProductNum'];
			$GDB['token']=$this->token;
			$GDB['stoken']=$this->stoken;
			if ($_POST['oid']) {
				if (M()->table('RS_GroupBuy')->where("GroupId='%s'",$_POST['oid'])->save($GDB)) {
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
					$this->LOGS("团主体修改失败--->>>".M()->getlastsql());
				}			
			}else{
				$GDB['GroupId']=uniqid('GroupBuy');
				if (M()->table('RS_GroupBuy')->add($GDB)) {
					$this->success('添加成功');				
				}else{
					$this->error('添加失败');
					$this->LOGS("团购添加失败--->>>".M()->getlastsql());
				}
			}
		}else{
			$pagedata['NoSalePros']=M()->query("SELECT p.ProName+pl.ProSpec1 as ProName,pl.Price,pl.ProIdCard FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE p.token='{$this->token}' and p.stoken='0' and pl.IsDelete='0'");
			// var_dump($pagedata);
			$OnSalePros=M()->query("SELECT p.ProName+pl.ProSpec1 as ProName,gb.GroupId,CONVERT(varchar(20),StartDate,120) as StartDate,CONVERT(varchar(20),EndDate,120) as EndDate,gb.ProductNum,p.ProLogoImg,(CASE WHEN gb.StartDate<GETDATE() THEN '1' WHEN gb.EndDate<GETDATE() THEN '2' ELSE '0' END) AS IsStart FROM RS_GroupBuy gb LEFT JOIN RS_Product p ON gb.ProId=p.ProId LEFT JOIN RS_ProductList pl ON gb.ProIdCard=pl.ProIdCard WHERE gb.token='{$this->token}' and gb.stoken='{$this->stoken}' and gb.IsDelete='0'");
			$allgids=array();
			foreach ($OnSalePros as $osp) {
				$allgids[]=$osp['GroupId'];
			}
			$allrules=M()->table('RS_GroupBuyList')->where("token='{$this->token}' and stoken='{$this->stoken}' and GroupId in ('".implode("','", $allgids)."')")->select();
			//拼接规则数据
			foreach ($OnSalePros as $key => $value) {
				foreach ($allrules as $rule) {
					if ($value['GroupId']==$rule['GroupId']) {
						$OnSalePros[$key]['rules'][]=$rule;
					}
				}
			}
			$alldata=M()->table('RS_GroupBuy')->where("token='%s' and stoken='%s' and IsDelete='0'",array($this->token,$this->stoken))->field("CONVERT(varchar(20),StartDate,120) as StartDate,CONVERT(varchar(20),EndDate,120) as EndDate,GroupId,ProIdCard,ProductNum,(CASE WHEN StartDate<GETDATE() THEN '1' WHEN EndDate<GETDATE() THEN '2' ELSE '0' END) AS IsStart")->select();
			$alreadypros=M()->table('RS_GroupBuy')->where("token='%s' and stoken='%s' and IsDelete='0'",array($this->token,$this->stoken))->getField("ProIdCard",true);
			$pagedata['alldata']=json_encode($alldata);
			$pagedata['alreadypros']=json_encode($alreadypros);
			$pagedata['OnSalePros']=$OnSalePros;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 添加团购规则  2017-05-14
	 */
	public function addgrouprules(){
		if (IS_POST) {
			$glid=uniqid('GroupBuyList');
			$gldata=array();
			$gldata=$_POST;
			$gldata['token']=$this->token;
			$gldata['stoken']=$this->stoken;
			$gldata['GroupListId']=$glid;
			if (M()->table('RS_GroupBuyList')->add($gldata)) {
				$msg['status']='success';
				$msg['glid']=$glid;
				$msg['data']=$tmp;

			}else{
				$msg['status']='error';
			}
			echo json_encode($msg);
		}
	}

	/**
	 * 删除规则  2017-05-15
	 */
	public function delrules(){
		$glid=$_POST['glid'];
		if (M()->table('RS_GroupBuyList')->where("token='%s' and stoken='%s' and GroupListId='%s'",array($this->token,$this->stoken,$glid))->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
		}
		echo json_encode($msg);
	}

	/**
	 * 获取已有团规则  2017-05-15
	 */
	public function getrules(){
		$gid=$_POST['gid'];
		$tmprules=M()->table('RS_GroupBuyList')->where("GroupId='%s'",$gid)->getField("PeopleNum",true);
		$rules='n_'.implode('n_', $tmprules);
		echo json_encode($rules);
	}

	/**
	 * 团购管理
	 */
	public function GroupBuyManager(){
		if (IS_POST) {
			$Param=$_POST;
		}else{
			$Param=$_GET;
		}
		$whereStr="gby.token='{$this->token}' and gby.stoken='{$this->stoken}'";
		if ($Param['type']) {
			$whereStr.=" and gby.Status='{$Param['type']}'";
		}
		if ($Param['GroupId']) {
			$whereStr.=" and gby.GroupId='{$Param['GroupId']}'";
		}
		$count=M()->table('RS_GroupBuyer gby')->join("LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId")->join("LEFT JOIN RS_Product p ON gb.ProId=p.ProId")->join("LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId")->join("LEFT JOIN RS_Member m ON m.OpenId=gby.LeaderId")->where($whereStr)->count();
		$page=new \Think\Page($count,10,$Param);
		$Groups=M()->table('RS_GroupBuyer gby')->join("LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId")->join("LEFT JOIN RS_Product p ON gb.ProId=p.ProId")->join("LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId")->join("LEFT JOIN RS_Member m ON m.OpenId=gby.LeaderId")->where($whereStr)->field("p.ProName,p.ProLogoImg,m.MemberName,gbl.PeopleNum,gby.PeopleNum as op,gby.GroupBuyerId,(CASE gby.Status WHEN '0' THEN '进行中' WHEN '1' THEN '已完成' WHEN '2' THEN '已过期' WHEN '3' THEN '已退款' END) AS Stname,gby.Status,gbl.Price,gbl.BuyNum,CONVERT(varchar(20),gby.CreateDate,120) as CreateDate")->limit($page->firstRow.','.$page->listRows)->order("CreateDate")->select();
		// var_dump(M()->getlastSql());
		// $Groups=M()->query("SELECT p.ProName,p.ProLogoImg,m.MemberName,gbl.PeopleNum,gby.PeopleNum as op,gby.GroupBuyerId,(CASE gby.Status WHEN '0' THEN '进行中' WHEN '1' THEN '已完成' WHEN '2' THEN '已过期' WHEN '3' THEN '已退款' END) AS Stname,gby.Status,gbl.Price,gbl.BuyNum,CONVERT(varchar(20),gby.CreateDate,120) as CreateDate FROM RS_GroupBuyer gby LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId LEFT JOIN RS_Product p ON gb.ProId=p.ProId LEFT JOIN RS_GroupBuyList gbl ON gby.GroupListId=gbl.GroupListId LEFT JOIN RS_Member m ON gby.LeaderId=m.OpenId WHERE {$whereStr} ORDER BY CreateDate desc");
		$Gps=M()->query("SELECT CONVERT(varchar(20),gb.StartDate,120)+'_'+p.ProName+'_' as PreName,(CASE WHEN gb.EndDate < GETDATE() THEN '已过期' WHEN GB.IsDelete=1 THEN '已强制结束' ELSE '进行中' END) as Name,gb.GroupId FROM RS_GroupBuy gb LEFT JOIN RS_Product p ON gb.ProId=p.ProId WHERE gb.token='{$this->token}' and gb.stoken='{$this->stoken}' and gb.StartDate < GETDATE()");
		$pagedata['gps']=$Gps;
		$pagedata['Param']=$Param;
		$pagedata['page']=$page->show();
		$pagedata['Groups']=$Groups;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 强制关闭
	 */
	public function forceend(){
		$gid=$_GET['gid'];
		if (M()->table('RS_GroupBuy')->where("GroupId='%s'",$gid)->setField("IsDelete",1)) {
			$this->success('处理成功');
		}else{
			$this->error('处理失败');
		}
	}

	/**
	 * 团管理
	 */
	public function gpmanager(){
		$type=$_POST['type'];
		if ($type=='update') {
			//更新过期团信息
			$sql=M()->execute("UPDATE gby SET Status=2 FROM RS_GroupBuyer gby LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId WHERE gby.Status=0 and gb.EndDate < GETDATE() and gby.token='{$this->token}' and gby.stoken='{$this->stoken}'");
			// $sql=M()->table('RS_GroupBuyer gby')->join("LEFT JOIN RS_GroupBuy gb ON gby.GroupId=gb.GroupId")->where("gby.Status=0 and gby.token='{$this->token}' and gby.stoken='{$this->stoken}' and gb.EndDate<GETDATE()")->setField('gby.Status','2');		
			// var_dump($sql);exit();
			$msg['status']='success';
			$msg['info']='成功处理'.$sql.'条记录';
		}elseif ($type=='show') {
			$bid=$_POST['bid'];
			$data=M()->query("SELECT m.MemberName,CONVERT(float(53),g.Price,120) as Price,CONVERT(float(53),g.Money,120) as Money,g.Num as Count,CONVERT(varchar(20),g.CreateDate,120) as CreateDate,(CASE WHEN g.OpenId=gb.LeaderId THEN '1' ELSE '0' END) AS IsLeader,g.Status,gb.Status as gs,g.ID FROM RS_GroupBuyerList g LEFT JOIN RS_Member m ON g.OpenId=m.OpenId LEFT JOIN RS_GroupBuyer gb ON g.GroupBuyerId=gb.GroupBuyerId WHERE g.GroupBuyerId='{$bid}'");
			if ($data && count($data)>0) {
				$msg['status']='success';
				$msg['data']=$data;
			}else{
				$msg['status']='error';
			}
		}elseif ($type=='update_refund') {
			$tmdata=M()->query("SELECT COUNT(gbl.ID) as rnum,gb.PeopleNum as num,gb.GroupBuyerId FROM RS_GroupBuyer gb  LEFT JOIN RS_GroupBuyerList gbl ON gb.GroupBuyerId=gbl.GroupBuyerId WHERE gb.status=2 and gbl.Status=1 GROUP BY gb.PeopleNum,gb.GroupBuyerId");
			$i=0;
			$j=0;
			foreach ($tmdata as $td) {
				if ($td['rnum']==$td['num']) {
					if (M()->table('RS_GroupBuyer')->where("GroupBuyerId='%s'",$td['GroupBuyerId'])->setField('Status',3)) {
						$i++;
					}
					$j++;
				}
			}
			$msg['status']='success';
			$msg['info']='查询到'.$j.'个退款团信息，成功处理'.$i.'个团';
		}
		echo json_encode($msg);
	}

	/**
	 * 退款！！！
	 */
	public function GroupRefund(){
		$type=$_POST['type'];
		$Payapi= new PayapiController();
		if ($type=='all') {
			//批量退
			$allorders=M()->query("SELECT gl.ID,gl.OrderNo,gl.Money FROM RS_GroupBuyerList gl LEFT JOIN RS_GroupBuyer gb ON gl.GroupBuyerId=gb.GroupBuyerId WHERE gb.Status=2 and gl.Status=0 and gl.BackSuccess=0");
			$all=count($allorders);
			$i=0;
			$j=0;
			foreach ($allorders as $aor) {
				$eres=$Payapi->grouprefund($aor);
                if ($eres===true) {
                	if (M()->table('RS_GroupBuyerList')->where('ID=%d',$aor['ID'])->setField('Status',1)) {
                		$i++;
                	}else{
						$this->LOGS('未成团退款记录处理失败（已退款成功）--->>>'.$aor['ID']);
                	}
                	$j++;
                }elseif ($eres=='loading') {
                	if (M()->table('RS_GroupBuyerList')->where('ID=%d',$aor['ID'])->setField('BackSuccess','1')) {
                		$i++;
                	}else{
						$this->LOGS('未成团退款记录处理失败（已退款成功）--->>>'.$aor['ID']);
                	}
                	$j++;
                }
			}
			$msg['status']='success';
			$msg['all']=$all;
			$msg['i']=$i;
			$msg['j']=$j;
		}elseif ($type=='one') {
			//退一個团
			$bid=$_POST['bid'];
			$allorders=M()->query("SELECT gyl.ID,gyl.OrderNo,gyl.Money,gl.PeopleNum FROM RS_GroupBuyerList gyl LEFT JOIN RS_GroupBuyer gb ON gyl.GroupBuyerId=gb.GroupBuyerId LEFT JOIN RS_GroupBuyList gl ON gb.GroupListId=gl.GroupListId WHERE gyl.GroupBuyerId='{$bid}' and gyl.Status=0 and gb.Status=2 and gyl.BackSuccess=0");
			$msg['all']=count($allorders);
			$i=0;
			$j=0;
			$pon=$allorders[0]['PeopleNum'];
			foreach ($allorders as $aor) {
				$eres=$Payapi->grouprefund($aor);
				if ($eres===true) {
					if (M()->table('RS_GroupBuyerList')->where('ID=%d',$aor['ID'])->setField("Status",1)) {
						$i++;
					}else{
						$this->LOGS('未成团退款记录处理失败（已退款成功）--->>>'.$aor['ID']);
					}
					$j++;
				}elseif ($eres=='loading') {
					if (M()->table('RS_GroupBuyerList')->where('ID=%d',$aor['ID'])->setField("BackSuccess",'1')) {
						$i++;
					}else{
						$this->LOGS('未成团退款记录处理失败（已退款成功）--->>>'.$aor['ID']);
					}
					$j++;
				}
			}
			if ($i==$pon) {
				M()->table('RS_GroupBuyer')->where("GroupBuyerId='%s'",$bid)->setField('Status',3);
			}
			$msg['status']='success';
			$msg['i']=$i;
			$msg['j']=$j;
		}elseif ($type=='person') {
			//退一个人
			$id=$_POST['id'];
			$info=M()->table('RS_GroupBuyerList')->where("ID=%d and BackSuccess=0",$id)->find();
			if ($info) {
				$eres=$Payapi->grouprefund($info);
				if ($eres===true) {
					$msg['status']='success';
					if (M()->table('RS_GroupBuyerList')->where('ID=%d',$id)->setField('Status',1)) {
						$msg['info']='success';
					}else{
						$msg['info']='退款成功，订单处理失败';
						$this->LOGS('未成团退款记录处理失败（已退款成功）--->>>'.$id);
					}
				}elseif ($eres=='loading') {
					$msg['status']='success';
					if (M()->table('RS_GroupBuyerList')->where('ID=%d',$id)->setField('BackSuccess','1')) {
						$msg['info']='success';
					}else{
						$msg['info']='退款成功，订单处理失败';
						$this->LOGS('未成团退款记录处理失败（已退款成功）--->>>'.$id);
					}
				} else{
					$msg['status']='error';
				}				
			}else{
				$msg['status']='error';
				$msg['info']='退款申请已提交，请稍后查看退款结果(退款时长视银行处理时间而定)';
			}

		}
		echo json_encode($msg);
	}

	/**
	 * 取消团购
	 */
	public function delgroupbuy(){
		$gid=$_POST['gid'];
		if (M()->table('RS_GroupBuy')->where("GroupId='%s'",$gid)->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
		}
		echo json_encode($msg);
	}


}




 ?>
