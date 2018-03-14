<?php
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class StaticController extends Controller{
	public function index(){
		// var_dump($_POST);exit();
		$pid=$_GET['pid'];
		$imgData=M()->table('RS_ProductImage')->where("ProId='%s'",$pid)->select();
		// var_dump(M()->getlastSql());
		$proinfo=M()->table('RS_Product')->where("ProId='%s'",$pid)->find();
		$basePage=file_get_contents(C('STATICPATH')."Model.html");
		$context["{PAGE-product-Image}"]='<ul style="width: 100%;">';
		$heightprice=M()->table('RS_ProductList')->where("ProId='%s'",$pid)->max('Price');
		$lowprice=M()->table('RS_ProductList')->where("ProId='%s'",$pid)->min('Price');
		$str='[';
		foreach ($imgData as $img) {
			$str.="{width:'100%',height:'auto',content:'".$img['ProImage']."'},";
		}
		$str.="]";
		$context["{jsonData}"]=$str;
		$context["{PAGE-product-id}"]=$pid;

		//读取属性
		$sons=M()->table('RS_ProductList')->where("ProId='%s' and IsDelete=%d",array($pid,0))->select();
		// echo "<pre>";
		$ary=explode("_",substr($sons[0]['ProIdCard'], strpos($sons[0]['ProIdCard'], '_')+1));
		$tempAttr=array(
			'sp1'=>array(),
			'sp2'=>array(),
			'sp3'=>array(),
			);
		foreach ($sons as $son) {
			$tempSon=explode("_", substr($son['ProIdCard'], strpos($son['ProIdCard'], '_')+1));
			array_push($tempAttr['sp1'], $tempSon[0]);
			array_push($tempAttr['sp2'], $tempSon[1]);
			array_push($tempAttr['sp3'], $tempSon[2]);
			// $tempAttr[4]=explode("_", substr($son['ProIdCard'], strpos($son['ProIdCard'], '_')+1))[3];
			// $tempAttr[5]=explode("_", substr($son['ProIdCard'], strpos($son['ProIdCard'], '_')+1))[4];
		}
		$tempAttr['sp1']=array_unique($tempAttr['sp1']);
		$tempAttr['sp2']=array_unique($tempAttr['sp2']);
		$tempAttr['sp3']=array_unique($tempAttr['sp3']);
		foreach ($tempAttr as &$temp) {
			$temps=array();
			foreach ($temp as &$tp) {
				// echo $tp;
				$tps['value']=M()->table('RS_ProductAttributeValue')->where('AttributeValueId=%d',$tp)->getField('AttributeValue');
				$tps['id']=$tp;
				$temps[]=$tps;
			}
			$attrinfo[]=$temps;
		}

		foreach ($ary as $ar) {
			$tempAry[]=M()->table('RS_ProductAttributeValue')->where("AttributeValueId=%d",$ar)->getField('AttributeId');
		}
		foreach ($tempAry as $t) {
			$tempD['AttributeName']=M()->table('RS_ProductAttribute')->where('AttributeId=%d',$t)->getField('AttributeName');
			$tempD['id']=$t;
			$tempData[]=$tempD;
		}
		foreach ($tempData as $k => &$v) {
			$v['attrinfos']=$attrinfo[$k];
		}
		//外循环属性类别
		$context["{PAGE-product-Attr}"]="";
		$i=1;
		foreach ($tempData as $tData) {
			$context["{PAGE-product-Attr}"].='<dl id="dl'.$i.'"><dt><label>'.$tData['AttributeName'].'：</label></dt>';
			//内循环属性名
			foreach ($tData['attrinfos'] as $info) {
				$context["{PAGE-product-Attr}"].='<dd><a href="javascript:;" id=\'AttrA'.$i.'-'.$info['id'].'\' class=""><span onclick="selectAttr(\''.$i.'\',\''.$info['id'].'\',\''.$info['id']."_".$info['value'].'\')">'.$info['value'].'</span></a></dd>';
			}
			$context["{PAGE-product-Attr}"].='<input type="hidden" id="Arrt'.$i.'" value="" /></dl>';
			$i++;
		}

		//读取图文详情
		$context["{PAGE-product-Info}"]=str_replace('/web/', '/', htmlspecialchars_decode($proinfo['ProContent']));


		//读取基本信息
		$context["{CONTEXT-TITLE}"]=$proinfo['ProTitle'];
		$context["{CONTEXT-KEYWORDS}"]=$proinfo['KeyWord'];
		$context["{CONTEXT-DESCRIPTION}"]=$proinfo['Remarks'];

		$context["{CONTEXT-PRODUCTNAME}"]=$proinfo['ProName'];
		$context["{CONTEXT-PRODUCTDESCRIPTION}"]=$proinfo['ProSubtitle'];
		$context["{CONTEXT-PRICE}"]=intval($lowprice)."-".intval($heightprice);
		$context["{PAGE-product-Parameter}"]='';
		if (IS_POST) {
			$context["{PAGE-product-Parameter}"]=$_POST["subContextHidden"];
			$file=C('STATICPATH').$pid.".html";
			if (file_put_contents($file,strtr($basePage,$context))) {
				//$filename=substr($file, 7);
				$filename='/Product/'.$pid.'.html';
				M()->table('RS_Product')->where("ProId='%s'",$pid)->setField('PageUrl',$filename);
				echo "success";
			}else{
				echo "error";
			}
		}else{
			$file=C('STATICPATH').$pid.".html";
			if (file_put_contents($file,strtr($basePage,$context))) {
				//$filename=substr($file, 7);
				$filename='/Product/'.$pid.'.html';
				M()->table('RS_Product')->where("ProId='%s'",$pid)->setField('PageUrl',$filename);
				$this->success('数据处理完成...',U('Products/index'));
			}else{
				$this->error('处理失败...',U('Products/index'));
			}
		}


	}

	public function edit(){
		$pid=$_GET['pid'];
		$imgData=M()->table('RS_ProductImage')->where("ProId='%s'",$pid)->select();
		// var_dump(M()->getlastSql());
		$proinfo=M()->table('RS_Product')->where("ProId='%s'",$pid)->find();
		$context["{PAGE-product-Image}"]='<ul style="width: 100%;">';
		$heightprice=M()->table('RS_ProductList')->where("ProId='%s'",$pid)->max('Price');
		$lowprice=M()->table('RS_ProductList')->where("ProId='%s'",$pid)->min('Price');
		$str='[';
		foreach ($imgData as $img) {
			$str.="{width:'100%',height:'auto',content:'".__ROOT__."/web".$img['ProImage']."'},";
		}
		$str.="]";

		$sons=M()->table('RS_ProductList')->where("ProId='%s' and IsDelete=%d",array($pid,0))->select();
		$ary=explode("_",substr($sons[0]['ProIdCard'], strpos($sons[0]['ProIdCard'], '_')+1));
		$tempAttr=array(
			'sp1'=>array(),
			'sp2'=>array(),
			'sp3'=>array(),
			);
		foreach ($sons as $son) {
			$tempSon=explode("_", substr($son['ProIdCard'], strpos($son['ProIdCard'], '_')+1));
			array_push($tempAttr['sp1'], $tempSon[0]);
			array_push($tempAttr['sp2'], $tempSon[1]);
			array_push($tempAttr['sp3'], $tempSon[2]);
			// $tempAttr[4]=explode("_", substr($son['ProIdCard'], strpos($son['ProIdCard'], '_')+1))[3];
			// $tempAttr[5]=explode("_", substr($son['ProIdCard'], strpos($son['ProIdCard'], '_')+1))[4];
		}
		$tempAttr['sp1']=array_unique($tempAttr['sp1']);
		$tempAttr['sp2']=array_unique($tempAttr['sp2']);
		$tempAttr['sp3']=array_unique($tempAttr['sp3']);
		foreach ($tempAttr as &$temp) {
			$temps=array();
			foreach ($temp as &$tp) {
				// echo $tp;
				$tps['value']=M()->table('RS_ProductAttributeValue')->where('AttributeValueId=%d',$tp)->getField('AttributeValue');
				$tps['id']=$tp;
				$temps[]=$tps;
			}
			$attrinfo[]=$temps;
		}

		foreach ($ary as $ar) {
			$tempAry[]=M()->table('RS_ProductAttributeValue')->where("AttributeValueId=%d",$ar)->getField('AttributeId');
		}
		foreach ($tempAry as $t) {
			$tempD['AttributeName']=M()->table('RS_ProductAttribute')->where('AttributeId=%d',$t)->getField('AttributeName');
			$tempD['id']=$t;
			$tempData[]=$tempD;
		}
		foreach ($tempData as $k => &$v) {
			$v['attrinfos']=$attrinfo[$k];
		}
		//外循环属性类别
		$con="";
		$i=1;
		foreach ($tempData as $tData) {
			$con.='<dl id="dl'.$i.'"><dt><label>'.$tData['AttributeName'].'：</label></dt>';
			//内循环属性名
			foreach ($tData['attrinfos'] as $info) {
				$con.='<dd><a href="javascript:;" id=\'AttrA'.$i.'-'.$info['id'].'\' class=""><span onclick="selectAttr(\''.$i.'\',\''.$info['id'].'\',\''.$info['id']."_".$info['value'].'\')">'.$info['value'].'</span></a></dd>';
			}
			$con.='<input type="hidden" id="Arrt'.$i.'" value="" /></dl>';
			$i++;
		}
		$proinfo['ProContent']=str_replace('/web/', '/', htmlspecialchars_decode($proinfo['ProContent']));
		// var_dump($proinfo['ProContent']);
		$this->assign(array('proinfo'=>$proinfo,'price'=>intval($lowprice)."-".intval($heightprice),'jsonData'=>$str,'con'=>$con,'pid'=>$pid));
		$this->display();

	}
}









 ?>
