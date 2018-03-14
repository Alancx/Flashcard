<?php
namespace Home\Controller;
class ProductController extends BaseController {
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


		if ($_GET['stoken']) 
		{
			//临时处理办法，反正不稳定
		}
		else
		{
			$this->redirect('Home/Product/Goods',array('pid'=>$_GET['pid'],'stoken'=>$this->webParam['stoken']));
		}


		$uid=$this->webParam['uid'];
		$pid=$_GET['pid'];
		$type=$_GET['type'];

		$goodsInfo=$this->readGoodInfo($_GET['pid']);

    	$sqlStr="SELECT pe.*,m.MemberName,m.HeadImgUrl FROM  RS_ProductEvaluation pe LEFT JOIN RS_Member m ON pe.MemberId = m.MemberId WHERE  pe.ProId='".$pid."' and pe.IsDelete='0'";

		$evals=$this->BM()->query($sqlStr); //获取商品评价内容

		if ($cls=$this->BM('Membercollect')->where("MemberId='%s' and ProId='%s'",array($uid,$pid))->find())
		{
			if ($cls['IsDelete']==1)
			{
				$isclt=0;
			}
			else
			{
				$isclt=1;
			}
		}
		else
		{
			$isclt=0; //是否收藏状态判断
		}

		array_push($this->wxJSSDKConfigArray['jsApiList'],'onMenuShareAppMessage');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'onMenuShareTimeline');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'showMenuItems');
		array_push($this->wxJSSDKConfigArray['jsApiList'],'getLocation');


// var_dump($this->webParam);


		$sqlStr=A('Public')->getProsInfoSQL('pl.*',$this->webParam['token'],$this->webParam['stoken']," p.ProId = '".$_GET['pid']."' AND pl.IsDelete='0' ");

		$prosInfo=$this->BM()->query($sqlStr);

// 		//$prospec=$ProsInfo;
 //var_dump($sqlStr);
		$isSelfPro=false;

		$proPrice=array('max'=>0,'min'=>1000000);

		$tempPrice=0;

		$sInfo=array('res'=>false,'remark'=>'');//特价信息

		foreach ($prosInfo as $key => $value)
		{
			if ($value['stoken']!="0")
			{
				$isSelfPro=true;
			}
			else
			{
				$isSelfPro=false;
			}

			if ($value['sprice'])
			{
				$tempPrice=$value['sprice'];
				$sInfo['res']=true;
			}
			else
			{
				$tempPrice=$value['tPrice'];
			}

			if ($proPrice['max']<$tempPrice)
			{
				$proPrice['max']=$tempPrice;
			}

			if ($proPrice['min']>$tempPrice)
			{
				$proPrice['min']=$tempPrice;
			}

		}

		if ($proPrice['max']==$proPrice['min'])
		{
			$tempPrice=$proPrice['max'].'';
		}
		else
		{
			$tempPrice=$proPrice['min'].'-'.$proPrice['max'];
		}


		$this->assign('selfpro',$isSelfPro);

		$this->assign('sInfo',$sInfo);

		$this->assign('ProPrice',$tempPrice);


		$this->assign('wxJSSDKConfigStr',json_encode($this->wxJSSDKConfigArray));



		$this->assign('shareImg','https://'.$this->webParam['host'].$goodsInfo['ProLogoImg']);
		$this->assign('shareUrl','https://'.$this->webParam['host'].U('Home/Product/Goods',array('pid'=>$_GET['pid'],'stoken'=>$this->webParam['stoken'])));



		$service=$this->UM()->query("SELECT d.userName,d.id FROM tb_menugroup a RIGHT JOIN tb_groupmanger b
			ON a.GroupId=b.GroupId RIGHT JOIN tb_usergroup c ON a.GroupId=c.GroupId
			RIGHT JOIN tb_user d ON c.userId=d.id
			WHERE a.MenuId='m1401' AND d.token='".$this->webParam['token']."'");

    	$this->assign('spriceinfo',$sprice);
		$this->assign('evals',$evals);
		$this->assign('isclt',$isclt);
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

	 	$type=$_POST['isOther'];


		if ($type=='O') 
		{


			if ($_POST['stoken']=='0') 
			{
				$wid=substr($this->webParam['token'],-8,8);
			}
			else
			{
				$wid=substr($this->webParam['token'],-8,8).'_'.$_POST['stoken'];
			}

			$ostoreInfo=$this->WM('store')->where(array('id'=>$_POST['stoken']))->find();

			$sqlStr=A('Public')->getProsInfoSQL('pl.ProIdCard',$this->webParam['token'],$ostoreInfo['stoken']," pl.ProIdCard = '".$pid."'");

			$prosInfo=$this->BM()->query($sqlStr);

			$prosInfo=$prosInfo[0];

			$tempPrice=0;

			$sSign=false;

			if ($prosInfo['sprice'])
			{
				$tempPrice=$prosInfo['sprice'];
				$sSign=true;
			}
			else
			{
				$tempPrice=$prosInfo['tPrice'];
			}

			$wInfo=$this->WM('wh'.$wid)->where(array('ProIdCard'=>$pid))->find();

			
			if ($wInfo['StockCount']>0)
			{
				$this->ajaxReturn(array('status'=>true,'count'=>$wInfo['StockCount'],'price'=>$tempPrice,'sSign'=>$sSign),'JSON');
			}
			else
			{
				$storex=A('Public')->getRangesStore($_POST['lat'],$_POST['lon'],5000);

				$this->ajaxReturn(array('status'=>false,'count'=>0,'price'=>$tempPrice,'sSign'=>$sSign),'JSON');
			}


		}
		else
		{


			$sqlStr=A('Public')->getProsInfoSQL('pl.ProIdCard',$this->webParam['token'],$this->webParam['stoken']," pl.ProIdCard = '".$pid."'");

			$prosInfo=$this->BM()->query($sqlStr);

			$prosInfo=$prosInfo[0];

			$tempPrice=0;

			$sSign=false;

			if ($prosInfo['sprice'])
			{
				$tempPrice=$prosInfo['sprice'];
				$sSign=true;
			}
			else
			{
				$tempPrice=$prosInfo['tPrice'];
			}


			$wInfo=$this->WM('wh'.$this->webParam['wid'])->where(array('ProIdCard'=>$pid))->find();

			if ($wInfo['StockCount']>0)
			{
				$this->ajaxReturn(array('status'=>true,'count'=>$wInfo['StockCount'],'price'=>$tempPrice,'sSign'=>$sSign,'showStore'=>false),'JSON');
			}
			else
			{
				$storex=A('Public')->getRangesStore($_POST['lat'],$_POST['lon'],5000);

				$this->ajaxReturn(array('status'=>false,'count'=>0,'price'=>$tempPrice,'sSign'=>$sSign,'showStore'=>true,'storeData'=>$storex),'JSON');
			}
		}



	}

	///////////////////////////////////////////////////////////////////////
	//购物车
	//添加到购物车
	public function AddCart()
	{
		$GoodsInfo=$_POST;

		if(empty($GoodsInfo['id'])||empty($GoodsInfo['attr'])||empty($GoodsInfo['nums']))
		{
			$this->ajaxReturn(array('status'=>'false','info'=>'GoodsInfoIsNull'),'JSON');
		}
		else
		{

			$res=$this->addCarts($GoodsInfo);

			if ($res)
			{
				$this->ajaxReturn(array('status'=>'true','info'=>'Success'),'JSON');
			}
			else
			{
				$this->ajaxReturn(array('status'=>'false','info'=>'Error'),'JSON');
			}
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

	/**
	 * 收藏商品
	 */
	public function collect(){
		$type=$_POST['type'];
		$pid=$_POST['pid'];
		$uid=$this->webParam["uid"];
		// echo $_COOKIE['user_UserID'];exit();
        if(empty($this->webParam['uid'])||$this->webParam['uid']=="NULLVALUE"){
			// $this->error('请登录',U('Account/Login'));
			echo json_encode("nologin");
			exit();
		}
		// var_dump($uid);
		$data['MemberId']=$uid;
		$data['ProId']=$pid;
		$data['token']=$this->webParam['token'];
		if ($type=='noclt') {
			if ($this->BM('Membercollect')->where("MemberId='%s' and ProId='%s'",array($uid,$pid))->find()) {
				if ($this->BM('Membercollect')->where("MemberId='%s' and ProId='%s'",array($uid,$pid))->setField('IsDelete',0)) {
							$json['status']='true';
							// echo json_encode("{'status':'true'}");
						}else{
							$json['status']='false';
							// echo json_encode("{'status':'true'}");
						}
			}else{
				if ($this->BM('Membercollect')->add($data)) {
					$json['status']='true';
					// echo json_encode("{'status':'true'}");
				}else{
					$json['status']='false';
					// echo $this->BM()->getlastsql();
					// echo json_encode("{'status':'false'}");
				}
			}
		};
		if ($type=='clted') {
			if ($this->BM('Membercollect')->where("MemberId='%s' and ProId='%s'",array($uid,$pid))->setField('IsDelete',1)) {
				$json['status']='true';
			}else{
				$json['status']='false';
			}
		}
		echo json_encode($json);
	}


}







 ?>
