<?php
namespace Seller\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class ArtDialogController extends CommonController{
	public function setAttrValue(){
		$this->display();
	}
	/**
	 * 属性值操作
	 */
	public function saveAttrValue(){
		$data['AttributeId']=$_POST['AttributeId'];
		$data['AttributeValue']=$_POST['AttributeValue'];
		if (D('ProductAttributeValue')->where("AttributeValue='%s' and AttributeId=%d",array($_POST['AttributeValue'],$_POST['AttributeId']))->find()) {
				echo "errors";
		}else{
			if (D('ProductAttributeValue')->add($data)) {
				echo "success";
			}else{
				// echo D('ProductAttributeValue')->getlastSql();
				echo "error";
			}
		}

	}

	public function setAttr(){
		$attrs=D('ProductAttribute')->where("token='%s'",$this->token)->select();
		$this->assign('attrs',$attrs);
		$this->display();
	}

	public function getValues(){
		$id=$_POST['id'];
		$attrvalues=D('ProductAttributeValue')->where('AttributeId=%d',$id)->select();
		echo json_encode($attrvalues);
	}

	/**
	 * 商品二维码
	 */
	public function createQr(){
		$pid=$_GET['pid'];
		$zp=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$pid))->getField('Iszp');
		$scenes=M()->table('RS_Scene')->where("token='%s'",$this->token)->select();
		$goodsInfo=$this->readGoodInfo($pid);
		$this->assign('zp',$zp);
		$this->assign('ginfo',$goodsInfo['ProductList']);
		$this->assign(array('pid'=>$pid,'scenes'=>$scenes));
		$this->display();
	}
	/**
	 * 商品二维码生成
	 */
	public function getQr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// require 'E://web/webstore/Lib/ThinkPHP/Library/Vendor/PHPQR/phpqrcode.php';
		//$url=C('WEBURL')."/Product/".$_GET['pid'].".html?sid=".$_GET['sid'];
		if ($_GET['zp']) {
			$url='http://'.$_SERVER['HTTP_HOST'].U('Home/Account/QrCodeHandle',array('tp'=>'3','gid'=>$_GET['pid'],'sid'=>$_GET['sid'],'zp'=>'1'));
		}else{
			$url='http://'.$_SERVER['HTTP_HOST'].U('Home/Account/QrCodeHandle',array('tp'=>'3','gid'=>$_GET['pid'],'sid'=>$_GET['sid']));
		}
		$level="L";
		// $filename='./Uploads/1.png';
		$size=4;
		// \QRcode::png($url,$filename,$level,$size,'2');
		echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";;
		// echo "success";
	}
	/**
	 * 会员分配相关已弃用
	 */
	public function setmember(){
		$emps=M()->table('RS_GroupManager')->select();
		// var_dump($emps);
		$this->assign(array('emps'=>$emps));
		$this->display();
	}
	/**
	 * 会员分配
	 */
	public function savemember(){
		$str=$_POST['emps'];
		$id=$_POST['id'];
		if (M()->table('RS_Member')->where('ID=%d',$id)->setField('Employees',$str)) {
			$temp=explode(',', $str);
			foreach ($temp as $k) {
				$tempAry[]=M()->table('RS_GroupManager')->where('GroupId=%d',$k)->getField('GroupName');
			}
			$Tdata['data']=$tempAry;
			$Tdata['statu']='success';
			echo json_encode($Tdata);
		}else{
			echo "error";
		}
	}
	/**
	 * 打印发货单
	 */
	public function prinf(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfos=$model->query("SELECT o.TableId,o.EatingNums,o.ShortOid,o.OrderId,o.MessageByBuy,o.Price as PayMoney,o.RecevingName,CONVERT(float(53),o.DisMoney,120) as Coupon,CONVERT(varchar(20),o.CreateDate,120) as CreateDate,CONVERT(varchar(20),o.PayDate,120) as PayDate, ol.Price,ol.[Count],ol.Money,ol.ProName,ol.Spec FROM RS_OrderList ol LEFT JOIN RS_Order o ON ol.OrderId=o.OrderId WHERE ol.OrderId='{$oid}'");
		$allmoney=0;
		foreach ($oinfos as $key => $value) {
			$allmoney+=$value['Count'];
		}
		$pagedata['allcount']=$allmoney;
		$pagedata['oinfo']=$oinfos[0];
		$pagedata['pros']=$oinfos;
		$pagedata['sinfo']=M()->table('RS_Store')->where("stoken='%s'",$this->stoken)->find();
		$this->assign($pagedata);
		$this->display();
	}
	/**
	 * 打印发货单
	 */
	public function prinfuser(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfos=$model->query("SELECT o.TableId,o.EatingNums,o.ShortOid,o.OrderId,o.MessageByBuy,o.Price as PayMoney,o.RecevingName,CONVERT(float(53),o.DisMoney,120) as Coupon,CONVERT(varchar(20),o.CreateDate,120) as CreateDate,CONVERT(varchar(20),o.PayDate,120) as PayDate, ol.Price,ol.[Count],ol.Money,ol.ProName,ol.Spec FROM RS_OrderList ol LEFT JOIN RS_Order o ON ol.OrderId=o.OrderId WHERE ol.OrderId='{$oid}'");
		$allmoney=0;
		foreach ($oinfos as $key => $value) {
			$allmoney+=$value['Money'];
		}
		$pagedata['allmoney']=$allmoney;
		$pagedata['oinfo']=$oinfos[0];
		$pagedata['pros']=$oinfos;
		$pagedata['sinfo']=M()->table('RS_Store')->where("stoken='%s'",$this->stoken)->find();
		$this->assign($pagedata);
		$this->display();
	}
	/**
	 * 打印积分发货单
	 */
	public function prinfscore(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfo=$model->table('RS_ScoreOrder')->where("OrderId='%s'",$oid)->find();
		$time=$oinfo['PayDate'];
		foreach ($time as $key => $value) {
			if ($key=='date') {
				$oinfo['PayDate']=substr($value, 0,19);
			}
		}

		$oinfos=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,o.Price,o.ProIdCard,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_ScoreOrder r LEFT JOIN dbo.RS_ScoreOrderList o ON r.OrderId=o.OrderId
LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
		foreach ($oinfos as &$k) {
			$attrName="";
			$avalue=explode('_', $k['ProIdCard']);
			if ($avalue[1]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[1])->getField('AttributeValue');
			};
			if ($avalue[2]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[2])->getField('AttributeValue');
			};
			if ($avalue[3]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[3])->getField('AttributeValue');
			};
			// var_dump($attrName);exit();
			$k['attrValue']=$attrName;
		}
		$merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();
		$this->assign('merchant',$merchant);
		$this->assign(array('oinfo'=>$oinfo,'pros'=>$oinfos));
		$this->display('prinf');
	}
	/**
	 * 验货
	 */
	public function checks(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfo=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_Order r LEFT JOIN dbo.RS_OrderList o ON r.OrderId=o.OrderId
LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
		// var_dump($oinfo);
		$wuliu=$model->table('RS_Freight')->where("Blong=1 and token='".$this->token."'")->select();
		$freights=$model->query("SELECT f.ID,f.Opiece,f.Oadd,f.Tpiece,f.Tadd,f.FirstWeight,f.AddWeight,a.Price,a.Area FROM
			 dbo.RS_Freight f LEFT JOIN dbo.RS_Freight_Area a ON f.ID=a.FreightId");

		$this->assign(array('oinfo'=>$oinfo,'wuliu'=>$wuliu,'freights'=>json_encode($freights)));
		$this->display();
	}
	/**
	 * 验货完成
	 */
	public function setCheck(){
		$oid=$_POST['oid'];
		$freight=$_POST['freight'];
		$changeDate=array('IsCheck'=>1,'TrueFreight'=>$freight);
		if (M()->table('RS_Order')->where("OrderId='%s'",$oid)->setField($changeDate)) {
			echo "success";
		}else{
			// echo M()->getlastSql();
			echo "error";
		}
	}
	/**
	 * 积分验货
	 */
	public function checksscore(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfo=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_ScoreOrder r LEFT JOIN dbo.RS_ScoreOrderList o ON r.OrderId=o.OrderId
LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
		// var_dump($oinfo);
		$wuliu=$model->table('RS_Freight')->where("Blong=1 and token='".$this->token."'")->select();
		$freights=$model->query("SELECT f.ID,f.Opiece,f.Oadd,f.Tpiece,f.Tadd,f.FirstWeight,f.AddWeight,a.Price,a.Area FROM
			 dbo.RS_Freight f LEFT JOIN dbo.RS_Freight_Area a ON f.ID=a.FreightId");

		$this->assign(array('oinfo'=>$oinfo,'wuliu'=>$wuliu,'freights'=>json_encode($freights)));
		$this->display();
	}
	/**
	 * 积分验货完成
	 */
	public function setCheckscore(){
		$oid=$_POST['oid'];
		$freight=$_POST['freight'];
		$changeDate=array('IsCheck'=>1,'TrueFreight'=>$freight);
		if (M()->table('RS_ScoreOrder')->where("OrderId='%s'",$oid)->setField($changeDate)) {
			echo "success";
		}else{
			// echo M()->getlastSql();
			echo "error";
		}
	}

	/**
	 * 生成会员二维码
	 */
	public function MQr(){
		$scenes=M()->table('RS_Scene')->where("token='%s'",$this->token)->select();
		$this->assign(array('memberId'=>$_GET['mid'],'scenes'=>$scenes));
		$this->display();
	}

	public function GetMQr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// require 'E://web/webstore/Lib/ThinkPHP/Library/Vendor/PHPQR/phpqrcode.php';
		$url=C('WEBURL')."?sid=".$_GET['sid']."&memberId=".$_GET['memberId'];
		$level="L";
		// $filename='./Uploads/1.png';
		$size=4;
		// \QRcode::png($url,$filename,$level,$size,'2');
		echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";
		// echo "success";
	}


	/**
	 * 生成员工二维码
	 */
	public function AQr(){
		$scenes=M()->table('RS_Scene')->where("token='%s'",$this->token)->select();

		$this->assign(array('scenes'=>$scenes,'employeeId'=>$_GET['mid']));
		$this->display();
	}

	public function GetAQr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// require 'E://web/webstore/Lib/ThinkPHP/Library/Vendor/PHPQR/phpqrcode.php';
		$url=C('WEBURL')."?sid=".$_GET['sid']."&mid=".$_GET['mid'];
		$level="L";
		// $filename='./Uploads/1.png';
		$size=4;
		// \QRcode::png($url,$filename,$level,$size,'2');
		echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";
		// echo "success";
	}

	/**
	 * 打印快递单
	 */
	public function prinflog(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfo=$model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
		$time=$oinfo['PayDate'];
		foreach ($time as $key => $value) {
			if ($key=='date') {
				$oinfo['PayDate']=substr($value, 0,19);
			}
		}

		$oinfos=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,o.Price,o.ProIdCard,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_Order r LEFT JOIN dbo.RS_OrderList o ON r.OrderId=o.OrderId
LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
		foreach ($oinfos as &$k) {
			$attrName="";
			$avalue=explode('_', $k['ProIdCard']);
			if ($avalue[1]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[1])->getField('AttributeValue');
			};
			if ($avalue[2]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[2])->getField('AttributeValue');
			};
			if ($avalue[3]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[3])->getField('AttributeValue');
			};
			// var_dump($attrName);exit();
			$k['attrValue']=$attrName;
		}
		$merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();
		$this->assign('merchant',$merchant);
		$this->assign(array('oinfo'=>$oinfo,'pros'=>$oinfos));
		$this->display();
	}
	/**
	 * 打印积分快递单
	 */
	public function prinflogscore(){
		$model=M();
		$oid=$_GET['oid'];
		$oinfo=$model->table('RS_ScoreOrder')->where("OrderId='%s'",$oid)->find();
		$time=$oinfo['PayDate'];
		foreach ($time as $key => $value) {
			if ($key=='date') {
				$oinfo['PayDate']=substr($value, 0,19);
			}
		}

		$oinfos=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,o.Price,o.ProIdCard,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_ScoreOrder r LEFT JOIN dbo.RS_ScoreOrderList o ON r.OrderId=o.OrderId
LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
		foreach ($oinfos as &$k) {
			$attrName="";
			$avalue=explode('_', $k['ProIdCard']);
			if ($avalue[1]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[1])->getField('AttributeValue');
			};
			if ($avalue[2]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[2])->getField('AttributeValue');
			};
			if ($avalue[3]) {
				$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[3])->getField('AttributeValue');
			};
			// var_dump($attrName);exit();
			$k['attrValue']=$attrName;
		}
		$merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();
		$this->assign('merchant',$merchant);
		$this->assign(array('oinfo'=>$oinfo,'pros'=>$oinfos));
		$this->display('prinflog');
	}

	public function prinfs(){
		$model=M();
		$oids=$_GET['oids'];
		$poids=explode(',', $oids);
		$orderinfo=array();
		foreach ($poids as $oid) {
			$oinfo=$model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
			$time=$oinfo['PayDate'];
			foreach ($time as $key => $value) {
				if ($key=='date') {
					$oinfo['PayDate']=substr($value, 0,19);
				}
			}

			$oinfos=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,o.Price,o.ProIdCard,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_Order r LEFT JOIN dbo.RS_OrderList o ON r.OrderId=o.OrderId
	LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
			foreach ($oinfos as &$k) {
				$attrName="";
				$avalue=explode('_', $k['ProIdCard']);
				if ($avalue[1]) {
					$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[1])->getField('AttributeValue');
				};
				if ($avalue[2]) {
					$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[2])->getField('AttributeValue');
				};
				if ($avalue[3]) {
					$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[3])->getField('AttributeValue');
				};
				// var_dump($attrName);exit();
				$k['attrValue']=$attrName;
				// $oinfos[0]['oinfo']=$oinfo;
				$oinfo['pinfos']=$oinfos;
			}
			$orderinfo[]=$oinfo;
		}
		// echo "<pre>";
		// var_dump($orderinfo);
		$merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();
		$this->assign('merchant',$merchant);
		$this->assign('orderinfos',$orderinfo);
		$this->display();
	}

	/**
	 * 批量打印积分单
	 */
	public function prinfsscore(){
		$model=M();
		$oids=$_GET['oids'];
		$poids=explode(',', $oids);
		$orderinfo=array();
		foreach ($poids as $oid) {
			$oinfo=$model->table('RS_ScoreOrder')->where("OrderId='%s'",$oid)->find();
			$time=$oinfo['PayDate'];
			foreach ($time as $key => $value) {
				if ($key=='date') {
					$oinfo['PayDate']=substr($value, 0,19);
				}
			}

			$oinfos=$model->query("SELECT r.OrderId,r.RecevingProvince,p.ProId,o.Price,o.Count,o.Money,o.Price,o.ProIdCard,p.ProName,p.ProLogoImg,p.Barcode FROM dbo.RS_ScoreOrder r LEFT JOIN dbo.RS_ScoreOrderList o ON r.OrderId=o.OrderId
	LEFT JOIN dbo.RS_Product p ON o.ProId=p.ProId WHERE o.IsDelete=0 AND r.OrderId='".$oid."'");
			foreach ($oinfos as &$k) {
				$attrName="";
				$avalue=explode('_', $k['ProIdCard']);
				if ($avalue[1]) {
					$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[1])->getField('AttributeValue');
				};
				if ($avalue[2]) {
					$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[2])->getField('AttributeValue');
				};
				if ($avalue[3]) {
					$attrName.=$model->table('RS_ProductAttributeValue')->where('AttributeId=%d',$avalue[3])->getField('AttributeValue');
				};
				// var_dump($attrName);exit();
				$k['attrValue']=$attrName;
				// $oinfos[0]['oinfo']=$oinfo;
				$oinfo['pinfos']=$oinfos;
			}
			$orderinfo[]=$oinfo;
		}
		// echo "<pre>";
		// var_dump($orderinfo);
		$merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();
		$this->assign('merchant',$merchant);
		$this->assign('orderinfos',$orderinfo);
		$this->display('prinfs');
	}

	public function checkStore(){
		$str="0123456789zxcvbnmlkjhgfdsaqwertyuiop";
		$verify=substr(str_shuffle($str), mt_rand(0,34),1).substr(str_shuffle($str), mt_rand(0,34),1).substr(str_shuffle($str), mt_rand(0,34),1).substr(str_shuffle($str), mt_rand(0,34),1);
		$this->assign('id',$_GET['id']);
		$this->assign('verify',$verify);
		$this->display();
	}
	/**
	 * 商品二维码生成
	 */
	public function getStoreQr(){
		ob_clean();
		vendor('PHPQR.phpqrcode');
		// require 'E://web/webstore/Lib/ThinkPHP/Library/Vendor/PHPQR/phpqrcode.php';
		$data['verify']=$_GET['verify'];
		$data['verify_time']=date("Y-m-d H:i:s",time()+7200);
		if (M()->table('RS_Store')->where('id=%d',$_GET['id'])->setField($data)) {
			$url='http://'.$_SERVER['HTTP_HOST'].U('Home/Account/QrCodeHandle',array('tp'=>'4','id'=>$_GET['id'],'type'=>$_GET['type'],'stoken'=>$this->stoken));
			//C('WEBURL').'/Account/setCheckPeople/?id='..'&verify='.$_GET['verify'].'&round='.rand();
			$level="L";
			$size=4;
			// \QRcode::png($url,$filename,$level,$size,'2');
			echo "<img src='".\QRcode::png($url,false,$level,$size,'2')."' >";;
		}else{
			echo "error";
		}
	}

	//上传微信支付安全证书
	public function upcert(){
		$this->display();
	}

	public function upfile(){
		// var_dump($_FILES);exit;
		$filename=$_FILES['cert']['name'];
		$upload=new \Think\Upload();
		$upload->maxSize=10240;
		$tempath='Upload/cert/'.$this->token.'/';
		$upload->saveName='';
		$upload->replace=true;
		$upload->autoSub=false;
		//$upload->mimes=array('application/octet-stream');
		$upload->exts=array('pem');
		if (!is_dir($tempath)) {
			mkdir($tempath,0777,true);
		}
		$upload->savePath='./cert/'.$this->token.'/';
		$info=$upload->uploadOne($_FILES['cert']);
		if (!$info) {
			$this->error($upload->getError());
		}else{
			// if ($_POST['tumb']=='true') {
			// 	$img=new \Think\Image();
			// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
			// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
			// }
			$url=$tempath.$filename;
			// var_dump($this->ImgUrl);exit();
			$this->assign('file',$url);
			$this->display('upcert');
		}
	}


	public function readGoodInfo($GoodId)
	{
	    $json_string = file_get_contents($this->REALPATH.C('GOODS_INFO_PATH').$GoodId.'.json');//读取json内容
        $goodsInfo=array();
        if ($json_string) {
            $goodsInfo=json_decode($json_string,true);
        }
        else
        {
        	//如果没读取到文件 就读取数据库里的属性
        }

        return $goodsInfo;
	}


	public function inportXls(){
		$logfile=str_replace('\\','/',strrev(substr(strrev(dirname(__FILE__)),10)));
		//上传xls文件并导入相关信息到订单表
		if (IS_POST) {
			vendor("PHPExcel180.PHPExcel.IOFactory");

			$filename=$_FILES['cert']['name'];
			$upload=new \Think\Upload();
			$upload->maxSize=512000;
			$tempath='Upload/xls/'.$this->token.'/';
			$upload->saveName='';
			$upload->replace=true;
			$upload->autoSub=false;
			//$upload->mimes=array('application/octet-stream');
			$upload->exts=array('xlsx');
			if (!is_dir($tempath)) {
				mkdir($tempath,0777,true);
			}
			$upload->savePath='./xls/'.$this->token.'/';
			$info=$upload->uploadOne($_FILES['cert']);
			if (!$info) {
				$this->error($upload->getError());
			}else{
				// if ($_POST['tumb']=='true') {
				// 	$img=new \Think\Image();
				// 	$img->open('./Uploads'.substr($info['savepath'], 1).$info['savename']);
				// 	$img->thumb(150,150)->save('./Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
				// 	$this->assign('tumbimg','Uploads'.substr($info['savepath'], 1)."thumb_".$info['savename']);
				// }
				$url=$tempath.$filename;
				if (file_exists($url)) {
					$reader=\PHPExcel_IOFactory::createReader('Excel2007');
					$PHPExcel=$reader->load($url);
					$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
					$highestRow = $sheet->getHighestRow(); // 取得总行数
					$highestColumm = $sheet->getHighestColumn(); // 取得总列数
					/** 循环读取每个单元格的数据 */
					for ($row = 3; $row < 4; $row++){//行数是以第1行开始
						$datakey=array();
					    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
					    	$datakey[] = $sheet->getCell($column.$row)->getValue();
					    	// echo $column.$row.":".$sheet->getCell($column.$row)->getValue()."---";
					    }
					    // echo "<br>";
					    // $data[]=$datakey;
					}
					for ($row = 4; $row <= $highestRow; $row++){//行数是以第1行开始
						$dataset=array();
					    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
					    	$dataset[] = $sheet->getCell($column.$row)->getValue();
					    	// echo $column.$row.":".$sheet->getCell($column.$row)->getValue()."---";
					    }
					    $myAry=array_combine($datakey, $dataset);
					    // echo "<br>";
					    $data[$sheet->getCell('A'.$row)->getValue()]=$myAry;
					    // $data[]=$myAry;
					}
				}
				// echo "<pre>";
				// var_dump($data);exit;
				M()->startTrans();
				$res=true;
				$i=0;
				foreach ($data as $key => $value) {
					if ($value['LogisticsId']) {
						if (!$res=M()->table('RS_Order')->where("OrderId='%s' and token='%s'",array($key,$this->token))->find()) {
							if (!$res['LogisticsId']) {
								if (!M()->table('RS_Order')->where("OrderId='%s' and token='%s'",array($key,$this->token))->setField('LogisticsId',$value['LogisticsId'])) {
										file_put_contents($logfile.'/logs/inportXls_err.log',M()->getlastSql().PHP_EOL.'====='.date('Y-m-d H:i:s',time()).'====='.PHP_EOL,FILE_APPEND);
										$res=false;
										break;
								}else{
									$i++;
								}
							}
						}
					}
				}
				if ($res) {
					M()->commit();
					$this->assign('file','success');
					$this->assign('num',$i);
					$this->display('inportXls');
				}else{
					M()->rollback();
					$this->assign('file','error');
					$this->display('inportXls');
				}

				// var_dump($this->ImgUrl);exit();
			}
		}else{
			$this->display();
		}

	}

	/**
	 * 组合优惠选择商品
	 */
	public function chosePro(){
		$pros=M()->table('RS_Product')->where("token='%s' and stoken='%s' and IsUseScore<>'%s' and Iszp<>'%s'",array($this->token,$this->stoken,'1','1'))->field("ProId,ProName,ProLogoImg")->select();
		$pagedata['jsondata']=json_encode($pros);
		$pagedata['pros']=$pros;
		$this->assign($pagedata);
		$this->display();
	}
	/**
	 * 获取商品信息
	 */
	public function getprolist(){
		$pid=$_POST['ProId'];
		$prolist=M()->table('RS_ProductList')->where("token='%s' and ProId='%s'",array($this->token,$pid))->field('Price,ProIdCard,ProSpec1,ProSpec2,ProSpec3')->select();
		if ($prolist) {
			$pagedata['statu']='success';
			$pagedata['data']=$prolist;
			$pagedata['pinfo']=M()->table('RS_Product')->where("token='%s' and ProId='%s'",array($this->token,$pid))->field('ProName,ProLogoImg')->find();
		}else{
			$pagedata['statu']='error';
		}
		echo json_encode($pagedata);
	}

	/**
	 * 保存组合数据
	 */
	public function savegp(){
		$pros=json_decode($_POST['json'],true);
		$ProId=trim($_POST['ProId']);
		$GroupId=trim($_POST['GroupId']);
		$tempStatu=true;
		M()->startTrans();
		$ProIdCards=M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$GroupId))->getField('ProIdCards');
		$newProIdCards=$ProIdCards?unserialize(stripslashes($ProIdCards)):array(); //返序列化并分割成字符串

		foreach ($pros as $pro) {
			if (array_key_exists($pro['ProIdCard'], $newProIdCards)) {
				$pinfo=M()->query("SELECT p.ProName,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3 FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId=pl.ProId WHERE pl.ProIdCard='".$pro['ProIdCard']."'");
				$json['statu']='has';
				$json['info']='商品:'.$pinfo[0]['ProName']."/".$pinfo[0]['ProSpec1']."/".$pinfo[0]['ProSpec2']."/".$pinfo[0]['ProSpec3']." 已在此组合中存在!";
				M()->rollback();
				echo json_encode($json);
				return false;
			}else{
				$newProIdCards[$pro['ProIdCard']]['Price']=$pro['Price'];
				$newProIdCards[$pro['ProIdCard']]['ProId']=$ProId;
			}

			// $proids[]=$pro['ProIdCard'];
			// if (M()->table('RS_Groupdiscountlist')->where("token='%s' GroupId='%s' and ProIdCard",array($this->token,$GroupId,$pro['ProIdCard']))->find()) {
			// 	$pinfo=M()->query("SELECT p.ProName,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3 FROM RS_Product p LEFT JOIN RS_ProductList pl ON p.ProId=pl.ProId WHERE pl.ProIdCard='".$pro['ProIdCard']."'");
			// 	$json['statu']='has';
			// 	$json['info']='商品:'.$pinfo[0]['ProName']."/".$pinfo[0]['ProSpec1']."/".$pinfo[0]['ProSpec2']."/".$pinfo[0]['ProSpec3']." 已在此组合中存在!";
			// 	M()->rollback();
			// 	echo json_encode($json);
			// 	return false;
			// }
			// $newProIdCards.=$pro['ProIdCard'].'__';
			// $tempDB['GroupId']=$GroupId;
			// $tempDB['ProId']=$ProId;
			// $tempDB['ProIdCard']=$pro['ProIdCard'];
			// $tempDB['Price']=$pro['Price'];
			// $tempDB['token']=$this->token;
			// if (!M()->table('RS_Groupdiscountlist')->add($tempDB)) {
			// 	$tempStatu=false;
			// }
		}
		// $newIdAry=explode('__', $newProIdCards); //分割成数组并排序
		// if (is_array($newProIdCards) && is_array($proids)) {
		// 	$newIdAry=array_unique(array_merge($newProIdCards,$proids));
		// }elseif (is_array($newProIdCards)) {
		// 	$newIdAry=array_unique($newProIdCards);
		// }else{
		// 	$newIdAry=array_unique($proids);
		// }
		asort($newProIdCards);
		$count=count($newProIdCards);
		// var_dump($newIdAry);
		$newPram=serialize($newProIdCards); //序列化排序后数组并验证
		if (!$groupinfo=M()->table('RS_Groupdiscount')->where("ProIdCards='%s' and token='%s'",array($newPram,$this->token))->find()) {
			$tempData['ProIdCards']=$newPram;
			$tempData['ProCount']=$count;
			$res=M()->table('RS_Groupdiscount')->where("token='%s' and GroupId='%s'",array($this->token,$GroupId))->setField($tempData);
			if ($tempStatu && $res) {
				M()->commit();
				$json['statu']='success';
				echo json_encode($json);
			}else{
				M()->rollback();
				echo json_encode(M()->getlastsql());
			}
		}else{
			M()->rollback();
			echo json_encode(array('statu'=>'allhas','info'=>'此组合内容与组合:  "'.$groupinfo['GroupName'].'"  内容重复！'));
		}
	}

	/**
	 * 添加礼包商品
	 */
	public function choseGiftsPro(){
		$pros=M()->table('RS_Product')->where("token='%s' and IsUseScore<>'%s' and Iszp<>'%s'",array($this->token,'1','1'))->field("ProId,ProName,ProLogoImg")->select();
		$pagedata['jsondata']=json_encode($pros);
		$pagedata['pros']=$pros;
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 保存礼包商品
	 */
	public function savegift(){
		$CouponId=$_POST['CouponId'];
		$ProId=$_POST['ProId'];
		$pros=json_decode($_POST['json'],true);
		$oldpros=M()->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($CouponId,$this->token))->getField('ProIdCards');
		$newProIdCards=$oldpros?unserialize(stripslashes($oldpros)):""; //返序列化并分割成字符串
		foreach ($pros as $pro) {
			$proids[]=$pro['ProIdCard'];
			if (in_array($pro['ProIdCard'], $newProIdCards)) {
				$json['statu']='cant';
				$pinfo=M()->query("SELECT p.ProName,pl.ProSpec1,pl.ProSpec2,pl.ProSpec3 FROM RS_ProductList pl LEFT JOIN RS_Product p ON pl.ProId=p.ProId WHERE pl.ProIdCard='".$pro['ProIdCard']."'")[0];
				// var_dump($pinfo);exit();
				$json['info']='商品'.$pinfo['ProName'].'/'.$pinfo['ProSpec1'].'/'.$pinfo['ProSpec2'].'/'.$pinfo['ProSpec3'].' 已存在此礼包中';
				echo json_encode($json);exit();
			}
		}
		if (is_array($newProIdCards) && is_array($proids)) {
			$newIdAry=array_unique(array_merge($newProIdCards,$proids));
		}elseif (is_array($newProIdCards)) {
			$newIdAry=array_unique($newProIdCards);
		}else{
			$newIdAry=array_unique($proids);
		}
		sort($newIdAry);
		$newPram=serialize($newIdAry); //序列化排序后数组并验证
		if (M()->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($CouponId,$this->token))->setField('ProIdCards',$newPram)) {
			$json['statu']='success';
		}else{
			$json['statu']='error';
		}
		echo json_encode($json);
	}




}





 ?>
