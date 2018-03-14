<?php
namespace Home\Controller;
class JiFenStoreController extends BaseController {
	public function __initialize(){
		parent::__initialize();
	}


	/**
	 * 商品搜索结果处理
	 */
	public function search(){
		$key=$_GET['keyword'];
		// $pros=$this->BM('Product')->where("proname like %'%s'%",$key)->select();
		$pros=$this->BM()->query("SELECT * FROM RS_Product WHERE ProName LIKE '%".$key."%' or ProTitle like '%".$key."%'");
		// var_dump($pros);exit();
		$this->assign('pros',$pros);
		$this->display();
	}

	//商品明细
	public function Goods()
	{

		$uid=$this->webParam['uid'];
		$pid=$_GET['pid'];
		$type=$_GET['type'];

		$goodsInfo=$this->readGoodInfo($_GET['pid']);

    	$sqlStr="SELECT pe.*,m.MemberName,m.HeadImgUrl FROM  RS_ProductEvaluation pe LEFT JOIN RS_Member m ON pe.MemberId = m.MemberId WHERE  pe.ProId='".$pid."' and pe.IsDelete='0'";

		$evals=$this->BM()->query($sqlStr); //获取商品评价内容

		array_push($this->wxJSSDKConfigArray['jsApiList'],'onMenuShareAppMessage');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'onMenuShareTimeline');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'showMenuItems');

		$sqlStr=A('Public')->getProsInfoSQL('pl.*,p.Score',$this->webParam['token'],'0'," p.ProId = '".$_GET['pid']."' AND p.IsUseScore=1");

		$prosInfo=$this->BM()->query($sqlStr);

		$this->assign('wxJSSDKConfigStr',json_encode($this->wxJSSDKConfigArray));

		$this->assign('shareImg','https://'.$this->webParam['host'].$goodsInfo['ProLogoImg']);
		$this->assign('shareUrl','https://'.$this->webParam['host'].U('Home/JiFenStore/Goods',array('pid'=>$_GET['pid'],'stoken'=>'0')));

		$service=$this->UM()->query("SELECT d.userName,d.id FROM tb_menugroup a RIGHT JOIN tb_groupmanger b
			ON a.GroupId=b.GroupId RIGHT JOIN tb_usergroup c ON a.GroupId=c.GroupId
			RIGHT JOIN tb_user d ON c.userId=d.id
			WHERE a.MenuId='m1401' AND d.token='".$this->webParam['token']."'");

		$this->assign('evals',$evals);
		$this->assign('info',$goodsInfo);
		$this->assign('Title',$goodsInfo['ProName']);
		$this->assign('attrs',$prosInfo);
		$this->assign("service",$service);
        $this->display();

	}


	/**
	* 获取商品库存量 和 价格
	*/
	public function getNums()
	{
		 // 商品库存查询  勿改动
	 	$pid=$_POST['pid'];

		$wid=substr($this->webParam['token'],-8,8);

		$sqlStr=A('Public')->getProsInfoSQL('pl.ProIdCard,p.Score',$this->webParam['token'],'0'," pl.ProIdCard = '".$pid."' AND p.IsUseScore=1");

		$prosInfo=$this->BM()->query($sqlStr);

		$prosInfo=$prosInfo[0];

		$tempPrice=$prosInfo['Score'];

		$wInfo=$this->WM('wh'.$wid)->where(array('ProIdCard'=>$pid))->find();

		if ($wInfo['StockCount']>0)
		{
			$this->ajaxReturn(array('status'=>true,'count'=>$wInfo['StockCount'],'price'=>$tempPrice,'sSign'=>$sSign),'JSON');
		}
		else
		{
			$this->ajaxReturn(array('status'=>false,'count'=>0,'price'=>$tempPrice,'sSign'=>$sSign),'JSON');
		}


	}

	public function readGoodInfo($GoodId)
	{
	    $json_string = file_get_contents($this->webParam['realpath'].C('GOODS_INFO_PATH').$GoodId.'.json');//读取json内容
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



}







 ?>
