<?php
namespace Seller\Model;
use Think\Model;
class ProductClassModel extends Model{
	protected $tableName='ProductClass';
	protected $autoCheckFields = false;

	public function getAll($token){
		$oclass=$this->where("ParentClassId=0 and token='".$token."'")->select();
		// var_dump(M()->getlastsql());exit();
		foreach ($oclass as &$o) {
			$o['sonClass']=$this->where('ParentClassId=%d',$o['ClassId'])->select();
		}
		// var_dump($oclass);
		// return json_encode($oclass);
		$str="[{'id':'','province':'请选择',city:[]},";
		foreach ($oclass as $class) {
			$str.="{'id':'".$class['ClassId']."','province':'".$class['ClassName']."',city:[";
			$str.="{'cityname':'请选择','id':''},";
			foreach ($class['sonClass'] as $sclass) {
				$str.="{'cityname':'".$sclass['ClassName']."','id':'".$sclass['ClassId']."'},";
			}
			$str.="]},";
		}
		$str.="]";
		// var_dump($str);
		// $str="[{'id':'1','province':'顶级分类一',city[{'cityname':'子分类1-1','id':'3'},{'cityname':'子分类1-2','id':'4'},]},{'id':'2','province':'顶级分类二',city[{'cityname':'子分类2-1','id':'14'},{'cityname':'子分类2-2','id':'15'},{'cityname':'子分类2-3','id':'16'}]}]";
		// var_dump($str);exit();
		return $str;
	}
}













 ?>
