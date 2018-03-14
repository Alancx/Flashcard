<?php

/*
商品相关操作类
*/
namespace Seller\Controller;
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
		$allClass=$this->Class->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->order('ClassSort')->select();
		$this->assign(array('products'=>$products,'page'=>$page->show(),'allClass'=>$allClass));
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
			$pss  = M()->table('RS_ProductOnsale po')->join("LEFT JOIN RS_ProductList pl ON po.ProIdCard=pl.ProIdCard and pl.IsDelete=0")->join("LEFT JOIN RS_Product p ON po.ProId=p.ProId")->where("po.token='%s' and po.stoken='%s'",array($this->token,$this->stoken))->field("p.ProName+'_'+pl.ProSpec1+'_'+pl.ProSpec2+'_'+pl.ProSpec3 as ProName,pl.Price,po.sprice,CONVERT(varchar(120),po.stime,120) as stime,CONVERT(varchar(120),po.etime,120) as etime,po.Remarks,po.ID,po.ProIdCard")->limit($page->firstRow.','.$page->listRows)->select();
			$pros=M()->query("SELECT p.ProName+'_'+pl.ProSpec1+'_'+pl.ProSpec2+'_'+pl.ProSpec3+'/'+CONVERT(varchar(120),pl.Price,120) as ProName,pl.ProIdCard FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE pl.IsDelete=0 and p.token='{$this->token}' and p.stoken='{$this->stoken}' and p.ProId NOT IN (SELECT ProId FROM RS_EatMore WHERE stoken='{$this->stoken}')");
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
	 * 商品添加页面
	 */
	public function proadd(){
		$oclass=$this->Class->where("ParentClassId=%d and token='%s' and stoken='%s'",array(0,$this->token,$this->stoken))->select();
		$allClass=$this->Class->getAll($this->token);
		$this->assign(array('oclass'=>$oclass));
		define('FPAGE','SHANGPIN');
		$this->display();
	}


	/**
	 * 保存添加数据
	 */
	public function savePro(){
		if (IS_POST) {
			//主表插入数据
			$ProId="pro".substr(time(), 3).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
			$Pdata['ProId']=$ProId;
			$Pdata['ProName']=htmlspecialchars($_POST['ProName']);
			$Pdata['ProTitle']=htmlspecialchars($_POST['ProTitle']);
			$Pdata['ProSubtitle']=htmlspecialchars($_POST['ProSubtitle']);
			$Pdata['Freight']=trim($_POST['Freight']);
			$Pdata['IsUseConpon']=$_POST['IsUseConpon'];
			$Pdata['IsShelves']=1;
			$Pdata['ClassType']=$_POST['ClassType'];
			$Pdata['ClassName']=$this->Class->where('ClassId=%d',$_POST['ClassType'])->getField('ClassName');
			$Pdata['ProContent']=htmlspecialchars($_POST['ProContent']);
			$Pdata['ProLogoImg']=$_POST['ProLogoImg'];
			$Pdata['Cut']=$_POST['Cut'];
			$Pdata['Cut2']=0;
			$Pdata['PriceRange']=$_POST['PriceRange'];
			$Pdata['Price']=$_POST['Price'];
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
			$Pdata['ProImgs']=serialize($_POST['imgs']);
			$Pdata['NumType']=$_POST['NumType'];
			//
			//子表插入数据
			$sonpids=$_POST['pids'];  //拼ProIdCard
			$specs=$_POST['specs'];  //属性名称
			// $inputCodes=$_POST['inputCodes'];  //商品编码
			foreach ($sonpids as $k=>$v) {
				$info['ProIdCard']=$ProId.'_'.$v;
				$info['ProSpec1']=$specs[$k];
				$info['ProSpec2']=' ';
				$info['ProSpec3']=' ';
				$info['ProSpec4']=' ';
				$info['ProSpec5']=' ';
				$info['ProIdInputCard']=' ';
				$info['Price']=$_POST['Price'];
				$info['ProId']=$ProId;
				$info['token']=$this->token;
				$info['stoken']=$this->stoken;
				$infos[$ProId.'_'.$v]=$info;
			}

			$model=M();
			//
			// $tbName=$this->getCK(); //获取主仓库表名
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

			if ($prores && $infores) {
				$model->commit();
				$this->success('添加成功',U('Products/index'));
			}else{
				$this->LOGS("prores=$prores ... infores=$infores ");
				$this->LOGS($this->SH()->getlastsql());
				$model->rollback();
				$this->error('添加失败');
			}
		}else{
			exit('非法操作');
		}
	}

	/**
	 * 分类管理
	 */
	public function category(){
		if (IS_POST) {
			if ($_POST['id']) {
				$db=array();
				$db['ClassName']=$_POST['ClassName'];
				$db['ClassSort']=$_POST['ClassSort'];
				$db['CreateDate']=date('Y-m-d H:i:s',time());
				if (M()->table('RS_ProductClass')->where("ClassId=%d",$_POST['id'])->save($db)) {
					$this->success('修改成功');
				}else{
					$this->LOGS('分类修改失败--->>>'.M()->getlastsql());
					$this->error('修改失败');
				}
			}else{
				$db=array();
				$db['ClassName']=$_POST['ClassName'];
				$db['ParentClassId']='0';
				$db['ClassSort']=$_POST['ClassSort'];
				$db['ClassGrade']='1';
				$db['IsVisible']=$_POST['IsVisible'];
				$db['token']=$this->token;
				$db['stoken']=$this->stoken;
				if (M()->table('RS_ProductClass')->add($db)) {
					$this->success('添加成功');
				}else{
					$this->LOGS("分类添加失败--->>>".M()->getlastsql());
					$this->error('添加失败');
				}
			}
		}else{
			$lists=M()->table('RS_ProductClass')->where("stoken='%s'",$this->stoken)->select();
			$pagedata['lists']=$lists;
			$pagedata['jsondata']=json_encode($lists);
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 删除分类
	 */
	public function delclass(){
		$cid=$_POST['cid'];
		if (M()->table('RS_ProductClass')->where("ClassId=%d",$cid)->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
			$msg['info']='删除失败';
		}
		$this->ajaxReturn($msg);
	}

	/**
	 * 商品属性删除
	 */
	public function delatr(){
		$pid=$_POST['pid'];
		$pres=M()->table('RS_ProductList')->where("ProIdCard='%s'",$pid)->setField('IsDelete',1);
		if ($pres) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
		}
		echo json_encode($msg);
	}
	/**
	 * 商品编辑页面数据
	 */
	public function proedit(){
		$pid=$_GET['pid'];
		$proinfo=M()->table('RS_Product')->where("ProId='%s'",$pid)->find();
		$prolist=M()->table('RS_ProductList')->where("ProId='%s' and IsDelete='0'",$pid)->order('ProIdCard asc')->select();
		$proinfo['ProImgs']=unserialize(stripcslashes($proinfo['ProImgs']));
		$pagedata['proinfo']=$proinfo;
		$pagedata['prolist']=$prolist;
		$class=M()->table('RS_ProductClass')->where("stoken='%s'",$this->stoken)->select();
		$pagedata['class']=$class;
		define('FPAGE','SHANGPIN');
		$pagedata['spid']=explode('_', $prolist[count($prolist)-1]['ProIdCard'])[1];
		$this->assign($pagedata);
		$this->display();
	}
	/**
	 * 保存商品修改数据
	 * 程序待优化。。。
	 */
	public function saveEdit(){
		$ProId=$_POST['ProId'];

		$proData['ProId']=$_POST['ProId'];
		$proData['ProName']=trim($_POST['ProName']);
		$proData['ProTitle']=$_POST['ProTitle'];
		$proData['ProSubtitle']=$_POST['ProSubtitle'];
		$proData['IsShelves']=1;
		$proData['ClassType']=$_POST['ClassType'];
		$proData['ClassName']=$this->Class->where('ClassId=%d',$_POST['ClassType'])->getField('ClassName');
		$proData['ProContent']=htmlspecialchars($_POST['ProContent']);
		$proData['LastUpdateDate']=date('Y-m-d H:i:s',time());
		$proData['ProLogoImg']=$_POST['ProLogoImg'];
		$proData['ProImgs']=serialize($_POST['ProImgs']);
		$proData['Price']=$_POST['Price'];
		$proData['PriceRange']=$_POST['PriceRange'];
		$proData['ProNumber']=trim($_POST['ProNumber']);
		$proData['Weight']=intval($_POST['Weight']);
		$proData['IsUseScore']=$_POST['IsUseScore'];
		$proData['Score']=$_POST['Score'];
		$proData['token']=$this->token;
		$proData['stoken']=$this->stoken;
		$proData['NumType']=$_POST['NumType'];
		// 主表数据end

		//子表数据start
		$ProIdCard=$_POST['ProIdCards'];  //编号
		$specs=$_POST['specs']; //属性
		foreach ($ProIdCard as $sk => $sv)
		{
			$son['Price']=$_POST['Price'];
			$son['ProIdInputCard']=' ';
			$son['ProIdCard']=$sv;
			$son['LastUpdateDate']=date('Y-m-d H:i:s',time());
			$son['ProId']=$_POST['ProId'];
			$son['ProSpec1']=$specs[$sk];
			$son['ProSpec2']=' ';
			$son['ProSpec3']=' ';
			$son['ProSpec4']=' ';
			$son['ProSpec5']=' ';
			$son['token']=$this->token;
			$son['stoken']=$this->stoken;
			$sonData[$sv]=$son;
		}
		//子表数据end
		if ($_POST['IsAddAttr'])
		{
			$numckdata=array();
			//子表新增数据start
			$newpids=$_POST['new_pids'];
			$newspecs=$_POST['new_specs'];
			foreach ($newpids as $nk => $nv) {
				$newSon['ProIdCard']=$proidcard=$ProId.'_'.$nv;
				$newSon['ProSpec1']=$newspecs[$nk];
				$newSon['ProSpec2']=' ';
				$newSon['ProSpec3']=' ';
				$newSon['ProSpec4']=' ';
				$newSon['ProSpec5']=' ';
				$newSon['LastUpdateDate']=date('Y-m-d H:i:s',time());
				$newSon['ProIdInputCard']=' ';
				$newSon['Price']=$_POST['Price'];
				$newSon['ProId']=$_POST['ProId'];
				$newSon['token']=$this->token;
				$newSon['stoken']=$this->stoken;
				$newSonData[$proidcard]=$newSon;
			}
		}

		$model=M();
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
		}

		if ($sonRes && $newSonRes && $prores) {
			$model->commit();
			$this->success('修改成功...',U('Products/index'));
		}else{
			$this->LOGS("商品修改失败--->>>$sonRes && $newSonRes && $prores");
			$model->rollback();
			$this->error('修改失败');
		}


	}
	/**
	 * 删除商品
	 */
	public function deletePro(){
		$pid=$_GET['id'];
		$model=M();
		$model->startTrans();
		$Pres=$model->table('RS_Product')->where("ProId='%s'",$pid)->delete();
		$PLres=$model->table('RS_ProductList')->where("ProId='%s'",$pid)->delete();
		if ($Pres && $PLres) {
			$model->commit();
			$this->success('删除成功');
		}else{
			$model->rollback();
			$this->error('删除失败');
		}
	}

	/**
	 * 特价商品
	 */
	public function spricepro(){
		if (IS_POST) {
			$db=array();
			$db['ProId']=$_POST['pros'];
			$db['Sprice']=$_POST['Sprice'];
			$db['stoken']=$this->stoken;
			if ($_POST['ID']) {
				if (M()->table('RS_ProductOnsale')->where("ID=%d",$_POST['ID'])->save($db)) {
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
				}
			}else{
				if (M()->table('RS_ProductOnsale')->add($db)) {
					$this->success('设置成功');
				}else{
					$this->error('设置失败');
				}
			}
		}else{
			// $pros=M()->table('RS_Product')->where("stoken='%s'",$this->stoken)->field("ProName,ProId")->select();
			$pros=M()->query("SELECT ProName,ProId,Price FROM RS_Product WHERE stoken='{$this->stoken}' and ProId NOT IN (SELECT ProId FROM RS_EatMore WHERE stoken='{$this->stoken}')");
			$hasPro=M()->table('RS_ProductOnsale')->where("stoken='%s'",$this->stoken)->getField("ProId",true);
			$prolist=M()->table('RS_ProductOnsale ps')->join("LEFT JOIN RS_Product p ON ps.ProId=p.ProId")->where("ps.stoken='{$this->stoken}'")->field("p.ProLogoImg,p.ProName,ps.ProId,ps.Sprice,ps.ID,p.Price")->select();
			$pagedata['prolist']=$prolist;
			$pagedata['jsondata']=json_encode($prolist);
			$pagedata['hasPro']=json_encode($hasPro);
			$pagedata['pros']=$pros;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 删除特价
	 */
	public function delsprice(){
		$id=$_POST['ID'];
		if (M()->table('RS_ProductOnsale')->where("ID=%d",$id)->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
			$msg['info']='处理失败';
		}
		$this->ajaxReturn($msg);
	}



	/**
	 * 特色商品
	 */
	public function sppro(){
		if (IS_POST) {
			$db=array();
			$db['ProId']=$_POST['pros'];
			$db['stoken']=$this->stoken;
			$db['ProLabel']='2';
			$db['LabelType']='2';
			$db['token']=$this->token;
			if (M()->table('RS_ProductLabelList')->add($db)) {
				$this->success('添加成功');
			}else{
				// var_dump(M()->getlastsql());
				$this->error('添加失败');
			}
		}else{
			$pros=M()->table('RS_Product')->where("stoken='%s'",$this->stoken)->field("ProName,ProId")->select();
			$hasPro=M()->table('RS_ProductLabelList')->where("stoken='%s' and LabelType='1'",$this->stoken)->getField("ProId",true);
			$prolist=M()->table('RS_ProductLabelList ps')->join("LEFT JOIN RS_Product p ON ps.ProId=p.ProId")->where("ps.stoken='{$this->stoken}' and ps.LabelType='2'")->field("p.ProLogoImg,p.ProName,ps.ProId,ps.ID")->select();
			$pagedata['prolist']=$prolist;
			$pagedata['jsondata']=json_encode($prolist);
			$pagedata['hasPro']=json_encode($hasPro);
			$pagedata['pros']=$pros;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 删除特色
	 */
	public function delspro(){
		$id=$_POST['ID'];
		if (M()->table('RS_ProductLabelList')->where("ID=%d",$id)->delete()) {
			$msg['status']='success';
		}else{
			$msg['status']='error';
			$msg['info']='删除失败';
		}
		$this->ajaxReturn($msg);
	}


	/**
	 * 活动商品
	 */
	public function activity(){
		if (IS_POST) {
			$db=array();
			$db['ProId']=$_POST['pros'];
			$db['stoken']=$this->stoken;
			$db['ProLabel']='1';
			$db['LabelType']='1';
			$db['token']=$this->token;
			if (M()->table('RS_ProductLabelList')->add($db)) {
				$this->success('添加成功');
			}else{
				// var_dump(M()->getlastsql());
				$this->error('添加失败');
			}
		}else{
			$pros=M()->table('RS_Product')->where("stoken='%s'",$this->stoken)->field("ProName,ProId")->select();
			$hasPro=M()->table('RS_ProductLabelList')->where("stoken='%s' and LabelType='1'",$this->stoken)->getField("ProId",true);
			$prolist=M()->table('RS_ProductLabelList ps')->join("LEFT JOIN RS_Product p ON ps.ProId=p.ProId")->where("ps.stoken='{$this->stoken}' and ps.LabelType='1'")->field("p.ProLogoImg,p.ProName,ps.ProId,ps.ID")->select();
			$pagedata['prolist']=$prolist;
			$pagedata['jsondata']=json_encode($prolist);
			$pagedata['hasPro']=json_encode($hasPro);
			$pagedata['pros']=$pros;
			$this->assign($pagedata);
			$this->display();
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
				$str="stoken='".$this->stoken."' and token='".$this->token."' and CreateDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
			}else{
				$str="stoken='".$this->stoken."' and token='".$this->token."' and IsShelves='".$tempData['IsShelves']."' AND  CreateDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
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
			$where="token='".$this->token."' and stoken='".$this->stoken."'";
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
			  $products=$this->Product->where($where)->field("ProNumber,ProName,Price,PriceRange,(CASE WHEN IsShelves=0 THEN '否' ELSE '是' END ) AS IsShelves,CONVERT(varchar(100), CreateDate, 120) as CreateDate,Barcode")->select();
              $xlsName="ProductList_".date('ymdHm');
              $xlsCell = array(
                  array('ProNumber' , '商品编号'),
                  array('ProName' , '商品名称'),
                  array('Price' , '价格'),
                  array('PriceRange','出售价格'),
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
	 * LabelType=0      *****开店暂无此权限*****
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
				$data['stoken']=$this->stoken;
				$data['token']=$this->token;
				if (M()->table('RS_ProductLabelList')->add($data)) {
					echo "success";
				}else{
					echo "error";
				}
			}else{
				$data['ProId']=$pid;
				$data['ProLabel']='2';
				$data['LabelType']=0;
				$data['stoken']=$this->stoken;
				$data['token']=$this->token;
				if (M()->table('RS_ProductLabelList')->add($data)) {
					echo "success";
				}else{
					var_dump(M()->getlastsql());
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
				break;
			}
			$this->LOGS($this->SH()->getlastsql());

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
			$this->SH()->rollback();
			$model->rollback();
			echo "error";
		}
	}
	/**
	 * 运费模板列表ME
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
	 * 限时特价商品检索  ******开店暂无权限*******
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
	 * 特价信息设置       ******开店暂无权限*******
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
	 * 取消特价        ******开店暂无权限*******
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
	 * 优惠券    ******开店暂无权限*******
	 */
	public function coupons(){
		$count=M()->table('RS_Coupon')->where("token='%s' and IsShow='%s' and stoken='%s'",array($this->token,'0',$this->stoken))->count();
		$page= new \Think\Page($count,10);
		$coupons=M()->table('RS_Coupon')->where("token='%s' and IsShow='%s' and stoken='%s'",array($this->token,'0',$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($coupons as &$cp) {
			if ($cp['Type']=='2') {
				$cp['Rules']=explode('/',$cp['Rules']);
			}
			if ($cp['Type']=='1') {
				$cp['Rules']=floatval($cp['Rules'])*10;
			}
			$tempC=$cp['CreateDate'];
			$tempE=$cp['ExpiredDate'];
			$tempL=$cp['LastUpdateDate'];
			foreach ($tempC as $key => $value) {
				if ($key=='date') {
					$cp['CreateDate']=$value;
				}
			}
			foreach ($tempE as $key => $value) {
				if ($key=='date') {
					$cp['ExpiredDate']=$value;
				}
			}
			foreach ($tempL as $key => $value) {
				if ($key=='date') {
					$cp['LastUpdateDate']=$value;
				}
			}
		}
		$this->assign(array('coupons'=>$coupons,'page'=>$page->show()));
		$this->display();
	}

	/**
	 * 添加优惠券      ******开店暂无权限*******
	 */
	public function addcoupons(){
		if (IS_POST) {
			// var_dump($_POST);exit;
			$tempStr='qwertyuioplkjhgfdsazxcvbnm';
			$tempNum='1234567890';
			$tempAry=array('现金抵扣券','折扣券','满减券','摇一摇');
			$CouponId=substr(str_shuffle($tempStr),5,5).substr(str_shuffle($tempNum),5,8);
			$data['CouponName']=$tempAry[$_POST['type']];
			$data['Rules']=$_POST['rules'];
			$data['Count']=$_POST['nums'];
			$data['AfterCount']=$_POST['nums'];
			$data['UserCount']='1';
			$data['Type']=$_POST['type'];
			$data['CreateDate']=$_POST['stime'];
			$data['ExpiredDate']=$_POST['etime'];
			$data['token']=$this->token;
			$data['stoken']=$this->stoken;
			if ($_POST['couponid']) {
				$data['CouponId']=$_POST['couponid'];
				$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
				if (M()->table('RS_Coupon')->where("CouponId='%s'",$_POST['couponid'])->save($data)) {
					$this->success('保存成功',U('Products/coupons'));
				}else{
					// var_dump(M()->getlastSql());exit;
					$this->error('保存失败');
				}
			}else{
				$data['CouponId']=$CouponId;
				if (M()->table('RS_Coupon')->add($data)) {
					$this->success('保存成功',U('Products/coupons'));
				}else{
					// echo M()->getlastSql();exit();
					$this->error('保存失败');
				}
			}
		}
		$this->display();
	}
	/**
	 * 删除优惠券       ******开店暂无权限*******
	 */
	public function delCoupon(){
		$id=$_GET['id'];
		M()->startTrans();
		$res=M()->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($id,$this->token))->delete();
		$ress=M()->table('RS_MemberCoupon')->where("CouponId='%s' and token='%s'",array($id,$this->token))->delete();
		if ($res && $ress!==false) {
			M()->commit();
			$this->success('删除成功');
		}else{
			M()->rollback();
			$this->error('删除失败');
		}
	}

	/**
	 * 编辑优惠券           ******开店暂无权限*******
	 */
	public function editCoupon(){
		$id=$_GET['id'];
		$coupon=M()->table('RS_Coupon')->where("CouponId='%s'",$id)->find();
		$tempC=$coupon['CreateDate'];
		$tempE=$coupon['ExpiredDate'];
		foreach ($tempC as $key => $value) {
			if ($key=='date') {
				$coupon['CreateDate']=$value;
			}
		}
		foreach ($tempE as $key => $value) {
			if ($key=='date') {
				$coupon['ExpiredDate']=$value;
			}
		}
		if ($coupon['Type']=='1') {
			$coupon['Rules']=$coupon['Rules']*10;
		}
		if ($coupon['Type']=='2') {
			$coupon['Ruless']=explode('/',$coupon['Rules']);
		}
		// var_dump($coupon['Ruless']);
		$this->assign('coupon',$coupon);
		$this->display('addcoupons');
	}



	/**
	 * 获取当前商户所有仓库表名
	 */
	 public function getCK($type='main'){
		 if ($type!='main') { //获取全部仓库表名--备用
			 $tempAry=array();
			 $tempAry[]='wh'.substr($this->token,-8,8);
			 $StoreIds=M()->table('RS_Store')->where("token='%s'",$this->token)->getField('id',true);
		 		foreach ($StoreIds as $st) {
		 			$tempAry[]='wh'.substr($this->token,-8,8).'_'.$st;
		 		}
				return $tempAry;
		}else{ //返回主仓库表名
			return 'wh'.substr($this->token,-8,8);
		}
	 }

	 /**
	  * 设置摇一摇优惠券  *******开店暂无权限********
	  */
	 public function setcpn(){
	 	$cid=$_GET['id'];
	 	M()->startTrans();
	 	$res=M()->table('RS_Coupon')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->setField('UseType','0');
	 	$ress=M()->table('RS_Coupon')->where("token='%s' and stoken='%s' and CouponId='%s'",array($this->token,$this->stoken,$cid))->setField('UseType','1');
	 	if ($res && $ress) {
	 		M()->commit();
	 		$this->success('设置成功');
	 	}else{
	 		M()->rollback();
	 		$this->error('设置失败');
	 	}
	 }

	 /**
	  * 设置注册送优惠券  *******开店暂无权限********
	  */
		public function setreg(){
			$cid=$_GET['id'];
		 	M()->startTrans();
		 	$res=M()->table('RS_Coupon')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->setField('IsReg','0');
		 	$ress=M()->table('RS_Coupon')->where("token='%s' and stoken='%s' and CouponId='%s'",array($this->token,$this->stoken,$cid))->setField('IsReg','1');
		 	if ($res && $ress) {
		 		M()->commit();
		 		$this->success('设置成功');
		 	}else{
		 		M()->rollback();
		 		$this->error('设置失败');
		 	}
		}

		/**
 	  * 优惠规则         *******开店暂无权限********
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
 				$data['stoken']=$this->stoken;
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
 					}else if ($_POST['DiscountType']=='3') {
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
	 						$cdata['stoken']=$this->stoken;

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
	 						$cdata['stoken']=$this->stoken;

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
		 						$cdata['stoken']=$this->stoken;
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
 				$count=M()->table('RS_Discount')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
 				$page= new \Think\Page($count,15);
 				$discounts=M()->table('RS_Discount')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
				// echo "<pre>";
				// var_dump($page);
 				$pagedata['discounts']=$discounts;
 				$pagedata['jsondata']=json_encode($discounts);
 				$pagedata['page']=$page->show();
 				$this->assign($pagedata);
 				$this->display();
 			}
 		}

 		/**
 		 * 删除优惠规则       *******开店暂无权限********
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
 		  * 赠品/积分设置        *******开店暂无权限********
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
 	 * 商品组合优惠设置        *******开店暂无权限********
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
 			$DBdata['stoken']=$this->stoken;
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
		$count=M()->table('RS_Groupdiscount')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
		$page = new \Think\Page($count,10);
		$lists=M()->table('RS_Groupdiscount')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->field("GroupId,GroupName,CONVERT(varchar(100),SDate,120) as SDate,CONVERT(varchar(100),EDate,120) as EDate,ProIdCards")->limit($page->firstRow.','.$page->listRows)->select();
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
 		$this->display();
 	}

 	/**
 	 * 删除组合          *******开店暂无权限********
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
 	 * 平台商品选卖
 	 */
 	public function merpros(){
 		if (IS_POST) {
 			// var_dump($_POST);
 			$pid=$_POST['ProId'];
 			$prices=$_POST['Prices'];
 			$proidcards=$_POST['ProIdCards'];
 			$ADB=array();
 			foreach ($prices as $pk => $pv) {
 				$DB=array();
 				$DB['ProId']=$pid;
 				$DB['Price']=$pv;
 				$DB['ProIdCard']=$proidcards[$pk];
 				$DB['token']=$this->token;
 				$DB['stoken']=$this->stoken;
 				$ADB[]=$DB;
 			}
 			$insertres=true;
 			M()->startTrans();
 			foreach ($ADB as $ad) {
 				if (!M()->table('RS_MerPros')->add($ad)) {
 					$insertres=false;
 					break;
 				}
 			}
 			if ($insertres) {
 				M()->commit();
 				$this->success('保存成功');
 			}else{
 				M()->rollback();
 				$this->error('保存失败');
 			}
 		}else{
 			$pros=M()->query("SELECT ProName,ProId FROM RS_Product WHERE token='{$this->token}' and stoken='0' and ProId NOT IN (SELECT ProId FROM RS_MerPros WHERE stoken='{$this->stoken}')");
 			$proson=M()->query("SELECT pl.ProSpec1 as Spec,pl.Price,pl.ProId,pl.ProIdCard FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE p.stoken='0' and  p.token='{$this->token}'");
 			$newData=array();
 			foreach ($proson as $psn) {
 				$newData[$psn['ProId']][]=$psn;
 			}
 			$count=M()->table('RS_MerPros mp')->join("LEFT JOIN RS_Product p ON mp.ProId=p.ProId")->join("LEFT JOIN RS_ProductList pl ON mp.ProIdCard=pl.ProIdCard")->where("mp.stoken='{$this->stoken}'")->count();
 			// var_dump(M()->getlastsql());exit();
 			$page = new \Think\Page($count,15);
 			$mypros=M()->table('RS_MerPros mp')->join("LEFT JOIN RS_Product p ON mp.ProId=p.ProId")->join("LEFT JOIN RS_ProductList pl ON mp.ProIdCard=pl.ProIdCard")->where("mp.stoken='{$this->stoken}'")->field("mp.ProId,mp.Price,p.ProName,pl.ProSpec1,p.ProLogoImg,mp.ProIdCard")->limit($page->firstRow.','.$page->listRows)->select();
 			// $
 			$proTypes=M()->table('RS_ProductLabelList')->where("stoken='%s'",$this->stoken)->field("ProId,ProLabel")->select();
 			$newptype=array();
 			foreach ($proTypes as $tp) {
 				$newptype[$tp['ProId']][]=$tp['ProLabel'];
 			}
 			$newpros=array();
 			foreach ($mypros as $mk => $mv) {
 				if (array_key_exists($mv['ProId'], $newpros)) {
 					$newpros[$mv['ProId']]['sons'][]=array('Spec'=>$mv['ProSpec1'],'Price'=>$mv['Price'],'ProIdCard'=>$mv['ProIdCard']);
 				}else{
 					$newpros[$mv['ProId']]=array('ProName'=>$mv['ProName'],'ProLogoImg'=>$mv['ProLogoImg'],'ProId'=>$mv['ProId'],'sons'=>array(array('ProIdCard'=>$mv['ProIdCard'],'Spec'=>$mv['ProSpec1'],'Price'=>$mv['Price'])));
 					if (array_key_exists($mv['ProId'], $newptype)) {
 						if (count($newptype[$mv['ProId']])==1) {
 							if ($newptype[$mv['ProId']]=='1') {
 								$newpros[$mv['ProId']]['ProType']='hot';
 							}else{
 								$newpros[$mv['ProId']]['ProType']='new';
 							}
 						}else{
							$newpros[$mv['ProId']]['ProType']='all';
 						}
 					}
 				}
 			}
 			// echo "<pre>";
 			// var_dump($newpros);
 			$pagedata['ppss']=$newpros;
 			$pagedata['pros']=$pros;
 			$pagedata['page']=$page->show();
 			$pagedata['prosons']=json_encode($newData);
 			$this->assign($pagedata);
 			$this->display();
 		}
 	}

 	/**
 	 * 删除
 	 */
 	public function delmerpro(){
 		$pid=$_GET['pid'];
 		M()->startTrans();
 		$mres=M()->table('RS_MerPros')->where("ProId='%s' and stoken='%s'",array($pid,$this->stoken))->delete();
 		$pres=M()->table('RS_ProductLabelList')->where("stoken='%s' and ProId='%s'",array($this->stoken,$pid))->delete();
 		if ($mres && $pres!==false) {
 			M()->commit();
 			$this->success('删除成功');
 		}else{
 			M()->rollback();
 			$this->error('删除失败');
 		}
 	}

 	/**
 	 * 修改选卖商品价格 2017-05-13
 	 */
 	public function editmerprice(){
 		$pid=$_POST['pid'];
 		$price=$_POST['price'];
 		if (M()->table('RS_MerPros')->where("ProIdCard='%s' and token='%s' and stoken='%s'",array($pid,$this->token,$this->stoken))->setField('Price',$price)) {
 			$msg['status']='success';
 			$msg['info']='处理成功';
 		}else{
 			$msg['status']='error';
 			$msg['info']='处理失败';
 			$this->LOGS('选卖商品价格修改失败--->>>'.M()->getlastsql());
 		}
 		echo json_encode($msg);
 	}
}




 ?>
