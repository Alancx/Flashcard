<?php
namespace Seller\Controller;
use Think\Controller;
class UsersController extends CommonController{

	public $tempSession;
	public $groupid;
	public function _initialize(){
		parent::_initialize();
		$this->tempSession=session('userinfo');
		$this->groupid=session('Gname');
		// var_dump($this->token);
	}

	public function member(){
		$pageCount=15;//每页条数
		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='%s' and token='%s'",array($this->tempSession['userName'],$this->token))->select();
		// }
		$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			$lobj=$m['LastUpdateDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=substr($v, 0,19);
				}
			}
			foreach ($lobj as $lk => $lv) {
				if ($lk=='date') {
					$m['LastUpdateDate']=substr($lv, 0,19);
				}
			}
			$ainfo=M()->table('RS_OrderRecevingAddress')->where("MemberId='%s' and IsDefault=%d",array($m['MemberId'],1))->find();
			$m['aProvince']=$ainfo['Province'];
			$m['aCity']=$ainfo['City'];
			$m['aArea']=$ainfo['Area'];
			$m['aAddress']=$ainfo['Address'];
			$m['aName']=$ainfo['Name'];
			$m['aPhone']=$ainfo['Phone'];
			$m['aPost']=$ainfo['Post'];
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display();
	}

	public function edit(){
		$id=$_GET['id'];
		$info=M()->table('RS_Member')->where('ID=%d',$id)->find();
		foreach ($info['RegisterDate'] as $key => $value) {
			if ($key=='date') {
				$info['RegisterDate']=$value;
			}
		}

		foreach ($info['LastUpdateDate'] as $k => $v) {
			if ($k=='date') {
				$info['LastUpdateDate']=$v;
			}
		}
		$info['addresss']=M()->table('RS_OrderRecevingAddress')->where("MemberId='%s' and IsDefault=%d",array($info['MemberId'],1))->find();
		$this->assign(array('info'=>$info));
		$this->display();
	}

	public function del(){
		$id=$_GET['id'];
		if (M()->table('RS_Member')->where('ID=%d',$id)->delete()) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	/**
	 * 搜索功能
	 */
	public function search(){
		$pageCount=15;//每页条数
		$stime=$_POST['strtime'];
		$etime=$_POST['endtime'];
		$member=$_POST['Member'];
		if ($member) {
			if ($stime && $etime) {
				$str="token='".$this->token."' and MemberId like '%".$member."%' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
			}else{
				$str="token='".$this->token."' and MemberId like '%".$member."%'";
			}
		}else{
			$str="token='".$this->token."' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
		}

		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where($str)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='".$this->tempSession['userName']."' AND ".$str)->select();
		// }
		$tempdate=M()->table('RS_Member')->where($str)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			$lobj=$m['LastUpdateDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=$v;
				}
			}
			foreach ($lobj as $lk => $lv) {
				if ($lk=='date') {
					$m['LastUpdateDate']=$lv;
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		if (!$mps) {
			$mps=array('statu'=>'error','msg'=>"您查询的数据不存在!");
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display('member');
	}
	/**
	 * 编辑
	 */
	public function saveedit(){
		$data['MemberName']=$_POST['MemberName'];
		$data['Phone']=$_POST['Phone'];
		$data['Province']=$_POST['Province'];
		$data['City']=$_POST['City'];
		$data['Address']=$_POST['Address'];
		if (M()->table('RS_Member')->where('ID=%d',$_POST['id'])->setField($data)) {
			echo "success";
		}else{
			echo "error";
		}
	}

	/**
	 * 设置会员备注信息
	 */
	public  function setRmks(){
		$id=$_POST['id'];
		$content=$_POST['text'];
		if (M()->table('RS_Member')->where('ID=%d',$id)->setField('Remarks',$content)) {
			echo "success";
		}else{
			// var_dump(M()->getlastsql());
			echo "error";
		}
	}

	/**
	 * 会员等级设置 ---暂不使用
	 */
	public function level(){
		if (IS_POST) {
			$data['Name']=trim($_POST['Name']);
			$data['Value']=intval($_POST['Value']);
			$data['Sort']=abs(intval($_POST['Sort']));
			if ($data['Name'] && $data['Value'] && $data['Sort']) {
				if ($this->check($data)) {
					if ($_POST['GradeId']) {
						$data['LastUpdateDate']=date('Y-m-d H:i:s',time());
						if (M()->table('RS_MemberGrade')->where("GradeId=%d",$_POST['GradeId'])->save($data)) {
							$this->success('修改成功');
						}else{
							$this->error('修改失败');
						}
					}else{
						if (M()->table('RS_MemberGrade')->add($data)) {
							$this->success('添加成功');
						}else{
							$this->error('添加失败');
						}
					}
				}else{
						$this->error('等级条件非法');
					}
			}else{
				$this->error('请填写完整数据');
			}
		}else{
			$levels=M()->table('RS_MemberGrade')->order('Sort')->select();
			$this->assign(array('levels'=>$levels,'ldata'=>json_encode($levels)));
			$this->display();
		}
	}
	/**
	 * ajax验证等级设置
	 */
	public function check($data){
		$model=M();
		$val=$data['Value'];
		$preV=$model->table('RS_MemberGrade')->where("Sort=%d",$data['Sort']-1)->getField('Value');
		$nextV=$model->table('RS_MemberGrade')->where("Sort=%d",$data['Sort']+1)->getField('Value');
		if ($val<=$preV && $preV) {
			return false;
		}elseif ($val>=$nextV && $nextV) {
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 删除等级
	 */
	public function delL(){
		$id=$_GET['id'];
		if (M()->table('RS_MemberGrade')->where('GradeId=%d',$id)->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	/**
	 * 会员推广信息
	 *
	 */
	public function extend(){
		$pageCount=15;//每页条数
		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='%s'",$this->tempSession['userName'])->select();
		// }
		$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=substr($v, 0,19);
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display();
	}


	/**
	 * 搜索功能 用户坑爹，功能重复
	 */
	public function searchs(){
		$pageCount=15;//每页条数
		$stime=$_POST['strtime'];
		$etime=$_POST['endtime'];
		$member=$_POST['Member'];
		if ($member) {
			if ($stime && $etime) {
				$str="token='".$this->token."' and MemberId like '%".$member."%' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
			}else{
				$str="token='".$this->token."' and MemberId like '%".$member."%'";
			}
		}else{
			$str="token='".$this->token."' RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
		}
		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where($str)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='".$this->tempSession['userName']."' AND ".$str)->select();
		// }
		$tempdate=M()->table('RS_Member')->where($str)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=$v;
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		if (!$mps) {
			$mps=array('statu'=>'error','msg'=>"您查询的数据不存在!");
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display('extend');
	}
	/**
	 * 会员详细信息展示
	 */
	public function show(){
		$id=$_GET['id'];
		$info=M()->table('RS_Member')->where('ID=%d',$id)->find();
		foreach ($info['RegisterDate'] as $key => $value) {
			if ($key=='date') {
				$info['RegisterDate']=$value;
			}
		}

		foreach ($info['LastUpdateDate'] as $k => $v) {
			if ($k=='date') {
				$info['LastUpdateDate']=$v;
			}
		}
		$this->assign(array('info'=>$info));
		$this->display();
	}

	public function showfans(){
		$pageCount=10;//每页条数
		$mid=$_GET['mid'];
		$fans=M()->table('RS_Member')->where("SceneMember='%s'",$mid)->select();
		foreach ($fans as &$fan) {
			$robj=$fan['RegisterDate'];
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$fan['RegisterDate']=$v;
				}
			}
		}
		$count=count($fans);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($fans[$i]) {
				$mps[]=$fans[$i];
			}
		}
		$this->assign(array('members'=>json_encode($fans),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display();
	}
	/**
	 * 会员消费信息
	 */
	public function cons(){
		$pageCount=15;//每页条数
		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='%s'",$this->tempSession['userName'])->select();
		// }
		$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=substr($v, 0,19);
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display();
	}


	/**
	 * 搜索功能 用户坑爹，功能重复
	 */
	public function searchss(){
		$pageCount=15;//每页条数
		$stime=$_POST['strtime'];
		$etime=$_POST['endtime'];
		$member=$_POST['Member'];
		if ($member) {
			if ($stime && $etime) {
				$str="token='".$this->token."' and MemberId like '%".$member."%' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
			}else{
				$str="token='".$this->token."' and MemberId like '%".$member."%'";
			}
		}else{
			$str="token='".$this->token."' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
		}

		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where($str)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='".$this->tempSession['userName']."' AND ".$str)->select();
		// }
		$tempdate=M()->table('RS_Member')->where($str)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=$v;
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		if (!$mps) {
			$mps=array('statu'=>'error','msg'=>"您查询的数据不存在!");
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display('cons');
	}

	/**
	 * 提现管理
	 */
	public function getcash(){
		$count=M()->table('RS_Drawmoneylist')->where("token='%s'",$this->token)->count();
		$page=new \Think\Page($count,20);
		$gets=M()->table('RS_Drawmoneylist')->where("token='%s'",$this->token)->order('ID desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($gets as &$v) {
			$Ctime=$v['CreateDate'];
			$Chtime=$v['CheckDate'];
			$Etime=$v['EndDate'];
			foreach ($Ctime as $k => $vv) {
				if ($k=='date') {
					$v['CreateDate']=substr($vv,0,19);
				}
			}
			foreach ($Chtime as $kk => $vvv) {
				if ($kk=='date') {
					$v['CheckDate']=substr($vvv,0,19);
				}
			}
			foreach ($Etime as $key => $value) {
				if ($key=='date') {
					$v['EndDate']=substr($value,0,19);
				}
			}
		}
		$this->assign(array('mps'=>$gets,'page'=>$page->show()));
		$this->display();
	}

	/**
	 * 提现审核操作
	 */
	public function checkcash(){
		$id=$_GET['id'];
		$tempData=array('Status'=>2,'CheckDate'=>date('Y-m-d H:i:s',time()));
		if (M()->table('RS_Drawmoneylist')->where('ID=%d',$id)->setField($tempData)) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 提现备注设置
	 */
	public function cashRmks(){
		$id=$_POST['id'];
		$content=$_POST['text'];
		if (M()->table('RS_Drawmoneylist')->where('ID=%d',$id)->setField('Remarks',$content)) {
			echo "success";
		}else{
			// var_dump(M()->getlastsql());
			echo "error";
		}
	}
	/**
	 * 搜索提现记录
	 */
	public function searchsss(){
		if (IS_POST) {
			$type=$_POST['type'];
			$account=$_POST['Member'];
			$where['Status']=$type;
			$where['token']=$this->token;
			$str="token='".$this->token."' Status=".$type;
			if ($account) {
				$str.=" and MemberId like '%".$account."%'";
				$where['MemberId']=$account;
			}
		}else{
			$type=$_GET['Status'];
			$account=$_GET['MemberId'];
			$where['Status']=$type;
			$where['token']=$this->token;
			$str="token='".$this->token."' Status=".$type;
			if ($account) {
				$str.=" and MemberId like '%".$account."%'";
				$where['MemberId']=$account;
			}
		}
		$count=M()->table('RS_Drawmoneylist')->where($str)->count();
		$page= new \Think\Page($count,1,$where);
		$gets=M()->table('RS_Drawmoneylist')->where($str)->limit($page->firstRow.','.$page->listRows)->select();
		// echo M()->getlastsql();
		foreach ($gets as &$v) {
			$Ctime=$v['CreateDate'];
			$Chtime=$v['CheckDate'];
			$Etime=$v['EndDate'];
			foreach ($Ctime as $k => $vv) {
				if ($k=='date') {
					$v['CreateDate']=substr($vv,0,19);
				}
			}
			foreach ($Chtime as $kk => $vvv) {
				if ($kk=='date') {
					$v['CheckDate']=substr($vvv,0,19);
				}
			}
			foreach ($Etime as $key => $value) {
				if ($key=='date') {
					$v['EndDate']=substr($value,0,19);
				}
			}
		}
		$this->assign(array('mps'=>$gets,'page'=>$page->show()));
		$this->display('getcash');
	}

	public function tuiscenepro(){
		$model=M();
		$pagesize=25;

        if (IS_GET) {

        	$username=isset($_GET["username"])?trim($_GET["username"]):"";
        	$pageindex=1;
        	if($username!="")
            {
                $this->assign("user_name",$username);
            }
            $scenename="";
            $proname="";
        	$this->assign('scenelist',$this->getScene());
        }

        if(IS_POST){
        	$pageindex=intval($_POST["pindex"]);
            $username=trim(htmlspecialchars($_POST["username"]));
            $scenename=trim(htmlspecialchars($_POST["scenename"]));
            $proname=trim(htmlspecialchars($_POST["proname"]));
        }

        //2 、表格数据信息
        $tblCount = $model->setIsProc(true)->query($this->tuisceneproProcSqls(0,$pagesize,$pageindex,$username,$scenename,$proname));
        $count=intval($tblCount[0]["rows"]);   // 获取查询条数

        if($count>0){
            $dataOrder=$model->setIsProc(true)->query($this->tuisceneproProcSqls(1,$pagesize,$pageindex,$username,$scenename,$proname));
            foreach ($dataOrder as $key => $value) {
                if (!$value['ProName']) {
                    unset($dataOrder[$key]);
                }
            }
        }

        if(IS_POST){
            if($count>0){
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".ceil($count / $pagesize).",\"dataOrder\":".json_encode($dataOrder)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }

        if(IS_GET){
        	if ($_GET['type']=="import") {
              $dataOrder=$model->setIsProc(true)->query($this->tuisceneproProcSqls(2,$pagesize,$pageindex,$username,$scenename,$proname));
              $xlsName="tuiscenepro_".date('ymdHm');
              $xlsCell = array(
                  array('ProName' , '商品名称'),
                  array('SalesCount' , '销售总量'),
                  array('Count' , '推广量'),
                  array('Money','商品推广订单额'),
                  array('SceneName' , '场景名称'),
                  array('SceneMember' , '推广人账号'),
                  array('MemberName' , '推广人昵称'),
              );
              exportExcel($xlsName,$xlsCell,$xlsData=$dataOrder);
        	}
            $this->assign("dataOrder",$dataOrder)->assign("pageCount",$count)->assign("totalPage",ceil($count / $pagesize))->display();
        }
	}

	private function tuisceneproProcSqls($getTotal,$pagesize=25,$pageindex=1,$username='',$scenename='',$proname='')
    {
        // 查询 记录总数:@getTotal=0
        // 查询 分页数据：1
        // 查询 导出数据: 2
        $sql=<<<TBL
DECLARE @return_value int
EXEC    @return_value = [dbo].[P_TuiScenepro_Pager]
        @username = N'{$username}',
        @scenename = N'{$scenename}',
        @proname = N'{$proname}',
        @pagesize = {$pagesize},
        @pageindex = {$pageindex},
        @getTotal = {$getTotal},
        @token = N'{$this->token}'
TBL;
        return $sql;
    }

    /**
     * 获取场景信息
    */
    public function getScene(){
        return M()->query("SELECT ID AS Id,SceneName AS Name FROM RS_Scene WHERE token='".$this->token."' ORDER BY Sort");
    }


    /**
     * 发放提现红包
     */
    public function gethb(){
			$dir=dirname(__FILE__);
			$merchant=$this->MSL('merchant')->where("token='%s'",$this->token)->find();//获取商户基本信息
			$wxinfo=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find(); //获取商户微信支付信息
			$apiclient_cert=str_replace('\\','/',str_replace('Application\Admin\Controller','',$dir)).'WEB/'.$wxinfo['apiclient_cert'];
			$apiclient_key=str_replace('\\','/',str_replace('Application\Admin\Controller','',$dir)).'WEB/'.$wxinfo['apiclient_key'];
			$id=$_POST['id'];
			$getinfo=M()->table('RS_Drawmoneylist')->where('ID=%d',$id)->find();
			if ($getinfo['AccountType']=='WXPAY') {
				$moneyType='CutMoney';
			}else{
				$moneyType='tCutMoney';
			}
			if ($getinfo['OpenId'] && $getinfo['Status']==2)
			{
    		//用户openid存在-->处理业务
				$mch_billno=$wxinfo['mchid'].date('Ymd',time()).substr(time(), 5).substr(microtime(), 2,5);
				$mch_id=$wxinfo['mchid'];
				$wxappid=$wxinfo['appid'];
				$send_name=$merchant['storeName'];
				$re_openid=$getinfo['OpenId'];
				$total_amount=floatval($getinfo['Money'])*100;
				$total_num=1;
				$client_ip=$_SERVER['SERVER_ADDR'];
				$nonce_str=md5($mch_billno);
	    	//参数集合


	    	// 排列数据
				$strA="act_name=提现红包&client_ip=".$client_ip."&mch_billno=".$mch_billno."&mch_id=".$mch_id."&nonce_str=".$nonce_str."&re_openid=".$re_openid."&remark=提现红包&send_name=".$send_name."&total_amount=".$total_amount."&total_num=".$total_num."&wishing=提现红包&wxappid=".$wxappid;
				$key=$wxinfo['apikey'];
	    	//
				$stringSignTemp=$strA."&key=".$key;
				$sign=strtoupper(md5($stringSignTemp));
	    	//生成签名
				$str="<xml><sign><![CDATA[%s]]></sign> <mch_billno><![CDATA[%s]]></mch_billno> <mch_id><![CDATA[%s]]></mch_id> <wxappid><![CDATA[%s]]></wxappid> <send_name><![CDATA[%s]]></send_name> <re_openid><![CDATA[%s]]></re_openid> <total_amount><![CDATA[%s]]></total_amount> <total_num><![CDATA[%s]]></total_num> <wishing><![CDATA[提现红包]]></wishing> <client_ip><![CDATA[%s]]></client_ip> <act_name><![CDATA[提现红包]]></act_name> <remark><![CDATA[提现红包]]></remark> <nonce_str><![CDATA[%s]]></nonce_str> </xml>";
				$data=sprintf($str,$sign,$mch_billno,$mch_id,$wxappid,$send_name,$re_openid,$total_amount,$total_num,$client_ip,$nonce_str);
				$url="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
	    	//提交红包请求
				$res=$this->https_post($url,$data,$apiclient_cert,$apiclient_key);
	    	// file_put_contents('111.txt', $res);

				// $payData=$this->MSL('wxpayset')->where(array('token'=>$this->token))->find();
				//
				// $wxData=new \Org\WeChar\Wx_Data();
				// $wxData->token=$this->token;
				// $wxData->values=array(
				// 	'mch_appid'=>$payData['appid'],
				// 	'mchid'=>$payData['mchid'],
				// 	'nonce_str'=>$wxData->createNonceStr('32'),
				// 	'partner_trade_no'=>"TX".date("YmdHis",time()).rand(1000,9999),
				// 	'openid'=>$getinfo['OpenId'],
				// 	'check_name'=>'OPTION_CHECK',
				// 	're_user_name	'=>'黎明',
				// 	'amount'=>$getinfo['Money']*100,
				// 	'desc'=>'用户提现',
				// 	'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
				// 	// 'refund_fee'=>$pid['Price']*100,
				// 	// 'op_user_id'=>$payData['mchid'],
				// );
				//
				// $wxData->values['sign']=$wxData->SetSign($payData['apikey']);
				// file_put_contents('1111.txt',json_encode($wxData->values));
				// $wxPayAPI= new \Org\WeChar\WxPay\WxPay_Api();
				//
 			// 	$xml=$wxData->FromXml($wxPayAPI->refund($wxData));
				//
				//
				$xml=simplexml_load_string($res);
				// file_put_contents('1.txt',$wxPayAPI->refund($wxData));

	    	// $result=json_decode(json_encode($xml),true);
				if ($xml->return_code=='SUCCESS') {
					if ($xml->result_code=='SUCCESS') {
	    			//提现成功记录状态更改
						$remarks="发放成功--> 红包金额:".($xml->total_amount/100)." --发放时间:".$xml->send_time." --红包微信单号:".$xml->send_listid;
						$data=array('Status'=>3,'EndDate'=>date("Y-m-d H:i:s",time()),'Remarks'=>$remarks,'IsSuccess'=>1);
						if (M()->table('RS_Drawmoneylist')->where('ID=%d',$id)->setField($data)) {
							echo "发放成功";
						}else{
							echo "发放成功，数据库处理失败";
						}
					}else{
	    			//提现失败 恢复用户可提现金额
						$remarks="发放失败-->错误代码:".$xml->err_code." --错误描述:".$xml->err_code_des." <br/> 用户提现金额已返回 ";
						$tempData=array('Status'=>3,'EndDate'=>date('Y-m-d H:i:s',time()),'Remarks'=>$remarks);
						$model=M();
						$model->startTrans();
						$cash=false;
						$umoney=false;
						if ($model->table('RS_Drawmoneylist')->where('ID=%d',$id)->setField($tempData)) {
							$cash=true;
						}
						if ($model->table('RS_Member')->where("MemberId='%s'",$getinfo['MemberId'])->setInc($moneyType,intval($getinfo['Money']))) {
							$umoney=true;
						}
						if ($cash && $umoney) {
							$model->commit();
							echo "发放失败,错误代码:".$xml->err_code.",错误描述:".$xml->err_code_des;
						}else{
							$model->rollback();
							echo "发放失败(数据库处理失败),错误代码:".$xml->err_code.",错误描述:".$xml->err_code_des;
						}

	    			// echo "发放失败,错误代码:".$xml['err_code'].",错误描述".$xml['err_code_des'];
					}
				}else{
	    		//提现失败 恢复用户可提现金额
	    		// var_dump($xml);
					$remarks="发放失败-->错误代码:".$xml->return_code." --错误描述:".$xml->return_msg." <br/> 用户提现金额已返回 ";
					$tempData=array('Status'=>3,'EndDate'=>date('Y-m-d H:i:s',time()),'Remarks'=>$remarks);
					$model=M();
					$model->startTrans();
					$cash=false;
					$umoney=false;
					if ($model->table('RS_Drawmoneylist')->where('ID=%d',$id)->setField($tempData)) {
						$cash=true;
					}
					if ($model->table('RS_Member')->where("MemberId='%s'",$getinfo['MemberId'])->setInc($moneyType,intval($getinfo['Money']))) {
						$umoney=true;
					}
					if ($cash && $umoney) {
						$model->commit();
						echo "发放失败,错误代码:".$xml->err_code.",错误描述:".$xml->err_code_des;
					}
					else
					{
						$model->rollback();
						echo "发放失败(数据库处理失败),错误代码:".$xml->err_code.",错误描述:".$xml->err_code_des;
					}
				}

			}else{
    		//用户openid不存在 -->处理状态 返回用户可提现金额
				if (!$getinfo['OpenId']) {
					$rmk="获取用户openid失败，请联系用户确认是否在平台使用过微信支付";
				};
				if ($getinfo['Status']!=2) {
					$rmk="该记录已处理，请勿重复处理";
				}
				$tempData=array('Status'=>3,'EndDate'=>date("Y-m-d H:i:s",time()),'Remarks'=>$rmk);
				$model=M();
				$model->startTrans();
				$cash=false;
				$umoney=false;
				if ($model->table('RS_Drawmoneylist')->where('ID=%d',$id)->setField($tempData)) {
					$cash=true;
				}
				if ($model->table('RS_Member')->where("MemberId='%s'",$getinfo['MemberId'])->setInc($moneyType,intval($getinfo['Money']))) {
					$umoney=true;
				}
				if ($cash && $umoney) {
					$model->commit();
					echo $rmk;
				}else{
					echo "处理失败";
					$model->rollback();
				}
			}
		}



    /**
     * curl_post调用
     */
	public function https_post($url,$data,$apiclient_cert,$apiclient_key)
	{
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($curl,CURLOPT_SSLCERT,$apiclient_cert);
			//默认格式为PEM，可以注释
			curl_setopt($curl,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($curl,CURLOPT_SSLKEY,$apiclient_key);
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($curl);
	    if (curl_errno($curl)) {
	       return 'Errno'.curl_error($curl);
	    }
	    curl_close($curl);
	    return $result;
	}

	/**
	 * 导出用户销售额
	 */
	/**
	     *
	     * 导出Excel
	     */
	  public  function expUser(){//导出Excel
	  	$mid=$_GET['mid'];
	        $xlsModel = M()->table('RS_Member');
	        $xlsData  = $xlsModel->where("token='%s' and SceneMember='%s'",array($this->token,$mid))->Field('ID,MemberId,MemberName,OrderMoney')->select();
	        $xlsName  = $mid.'_fans';
	        $xlsCell  = array(
	        array('ID','#'),
	        array('MemberId','账号'),
	        array('MemberName','昵称'),
	        array('OrderMoney','粉丝消费额'),
	        );
	        $file_title='总销售额：'.M()->table('RS_Member')->where("token='%s' and SceneMember='%s'",array($this->token,$mid))->sum('OrderMoney');
	        exportExcel($xlsName,$xlsCell,$xlsData,$file_title);
	   }


	public function chosetime(){
		$this->assign('mid',$_GET['mid']);
		$this->display();
	}

	/**
	 * 会员卡券
	 */
	 public function coupons(){
		$pageCount=15;//每页条数

 		// if ($this->groupid=='超级管理组') {
 		// 	$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
 		// }else{
 		// 	$tempdate=M()->table('RS_Member')->where("Employees='%s'",$this->tempSession['userName'])->select();
 		// }
		$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
 		foreach ($tempdate as &$m) {
			$counts=M()->table("RS_MemberCoupon")->where("MemberId='%s'",$m['MemberId'])->sum('CouponCount');
			$m['couponCount']=$counts?$counts:0;
 			$robj=$m['RegisterDate'];
 			if ($m['Sex']=='1') {
 				$m['Sex']='男';
 			}elseif ($m['Sex']=='2') {
 				$m['Sex']='女';
 			}else{
 				$m['Sex']='保密';
 			}
 			foreach ($robj as $k => $v) {
 				if ($k=='date') {
 					$m['RegisterDate']=substr($v, 0,19);
 				}
 			}
 		}
 		$count=count($tempdate);
 		if ($count%$pageCount) {
 			$i=1;
 		}else{
 			$i=0;
 		}
 		$page=floor($count/$pageCount)+$i;
 		for ($i=0; $i < $pageCount; $i++) {
 			if ($tempdate[$i]) {
 				$mps[]=$tempdate[$i];
 			}
 		}
 		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
 		$this->display();
	 }


	 public function scoupon(){
 		$pageCount=15;//每页条数
 		$stime=$_POST['strtime'];
 		$etime=$_POST['endtime'];
 		$member=$_POST['Member'];
 		if ($member) {
 			if ($stime && $etime) {
 				$str="token='".$this->token."' and MemberId like '%".$member."%' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
 			}else{
 				$str="token='".$this->token."' and MemberId like '%".$member."%'";
 			}
 		}else{
 			$str="token='".$this->token."' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
 		}

 		// if ($this->groupid=='超级管理组') {
 		// 	$tempdate=M()->table('RS_Member')->where($str)->select();
 		// }else{
 		// 	$tempdate=M()->table('RS_Member')->where("Employees='".$this->tempSession['userName']."' AND ".$str)->select();
 		// }
 		$tempdate=M()->table('RS_Member')->where($str)->select();
 		foreach ($tempdate as &$m) {
			$counts=M()->table('RS_MemberCoupon')->where("MemberId='%s'",$m['MemberId'])->sum("CouponCount");
			$m['couponCount']=$counts?$counts:0;
 			$robj=$m['RegisterDate'];
 			$lobj=$m['LastUpdateDate'];
 			if ($m['Sex']=='1') {
 				$m['Sex']='男';
 			}elseif ($m['Sex']=='2') {
 				$m['Sex']='女';
 			}else{
 				$m['Sex']='保密';
 			}
 			foreach ($robj as $k => $v) {
 				if ($k=='date') {
 					$m['RegisterDate']=$v;
 				}
 			}
 			foreach ($lobj as $lk => $lv) {
 				if ($lk=='date') {
 					$m['LastUpdateDate']=$lv;
 				}
 			}
 		}
 		$count=count($tempdate);
 		if ($count%$pageCount) {
 			$i=1;
 		}else{
 			$i=0;
 		}
 		$page=floor($count/$pageCount)+$i;
 		for ($i=0; $i < $pageCount; $i++) {
 			if ($tempdate[$i]) {
 				$mps[]=$tempdate[$i];
 			}
 		}
 		if (!$mps) {
 			$mps=array('statu'=>'error','msg'=>"您查询的数据不存在!");
 		}
 		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
 		$this->display('coupons');
 	}

	public function showcoupons(){
		$uid=$_GET['id'];
		$pagedata['MemberId']=$MemberId=M()->table('RS_Member')->where('ID=%d',$uid)->getField('MemberId');
		$sql="SELECT c.CouponId,c.CouponName,c.Rules,c.Type,CONVERT(varchar(100), mc.GetTime, 20)as GetTime,mc.CouponCount FROM RS_MemberCoupon mc LEFT JOIN RS_Coupon c ON c.CouponId=mc.CouponId WHERE MC.MemberId='".$MemberId."'";
		$coupons=M()->query($sql);
		// var_dump($coupons);
		$pagedata['coupons']=$coupons;
		//查询用户卡券信息
		$this->assign($pagedata);
		$this->display();
	}

	/**
	 * 设置注册权限
	 */
	 public function setreg(){
		 $id=$_POST['id'];
		 if (M()->table('RS_Member')->where("ID=%d",$id)->setField('IsReg','1')) {
		 	echo "success";
		}else{
			echo "error";
		}
	 }

	/**
	 * 会员信息导出
	 */
	public function memberOut(){
		$tempData=$_GET;
		$whereStr="token='".$this->token."'";
		$Pram="";
		if ($tempData['member']) {
			$whereStr.=" AND MemberId like'%".$tempData['member']."%'";
			$Pram.=" 会员账号：".$tempData['member'];
		}
		if ($tempData['stime']) {
			$whereStr.=" AND RegisterDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
			$Pram.=" 注册时间：".$tempData['stime']."--".$tempData['etime'];
		}
		// $xlsData=M()->table('RS_Member')->where($whereStr)->field("MemberId,MemberName,Province,City,Money,VirtualMoney,ContinuedSign,TotalSign,Integral,OrderMoney,SceneMember,Fans,CutTotalMoney,CutMoney,(CASE WHEN Sex=0 THEN '保密' ELSE (CASE WHEN Sex=1 THEN '男' ELSE (CASE WHEN Sex=2 THEN '女' ELSE '未知' END) END) END) AS Sex,(CASE WHEN IsReg='0' THEN '否' ELSE '是' END) AS IsReg,CONVERT(varchar(100), SignNewtime, 120) as SignNewtime,CONVERT(varchar(100), RegisterDate, 120) as RegisterDate")->select();
		$xlsData=M()->query("SELECT MemberId,MemberName,Province,City,Money,VirtualMoney,ContinuedSign,TotalSign,Integral,OrderMoney,SceneMember,Fans,CutTotalMoney,CutMoney,(CASE WHEN Sex=0 THEN '保密' ELSE (CASE WHEN Sex=1 THEN '男' ELSE (CASE WHEN Sex=2 THEN '女' ELSE '未知' END) END) END) AS Sex,(CASE WHEN IsReg='0' THEN '否' ELSE '是' END) AS IsReg,CONVERT(varchar(100), SignNewtime, 120) as SignNewtime,CONVERT(varchar(100), RegisterDate, 120) as RegisterDate FROM RS_Member WHERE ".$whereStr);
		// echo M()->getlastsql();
		// echo "<pre>";
		// var_dump($xlsData);exit();
	    $xlsName  = date('Y-m-d H:i:s',time())."__MemberLists";
	    $xlsCell  = array(
		    array('MemberId','会员账号'),
		    array('MemberName','会员昵称'),
		    array('Sex','性别'),
		    array('Province','省份'),
		    array('City','城市'),
		    array('Money','账户余额'),
		    array('VirtualMoney','奖励余额'),
		    array('ContinuedSign','连续签到天数'),
		    array('TotalSign','总签到天数'),
		    array('SignNewtime','上次签到时间'),
		    array('Integral','积分'),
		    array('RegisterDate','注册时间'),
		    array('OrderMoney','消费额'),
		    array('SceneMember','上级用户'),
		    array('Fans','粉丝数'),
		    array('CutTotalMoney','提成总额'),
		    array('CutMoney','可提现金额'),
		    array('IsReg','注册权限'),
	    );
	    // $tempData=M()->query("SELECT * FROM")
	    $Pram=$Pram?$Pram:"全部";
	    $file_title='总会员数:'.count($xlsData)."  查询条件:".$Pram;
	    // var_dump($file_title);exit();
	    exportExcel($xlsName,$xlsCell,$xlsData,$file_title);
	}









	/**
	 * 会员推广信息
	 *
	 */
	public function extendcut(){
		$pageCount=15;//每页条数
		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='%s'",$this->tempSession['userName'])->select();
		// }
		$tempdate=M()->table('RS_Member')->where("token='%s'",$this->token)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=substr($v, 0,19);
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display();
	}


	/**
	 * 搜索功能 用户坑爹，功能重复
	 */
	public function extendcutS(){
		$pageCount=15;//每页条数
		$stime=$_POST['strtime'];
		$etime=$_POST['endtime'];
		$member=$_POST['Member'];
		if ($member) {
			if ($stime && $etime) {
				$str="token='".$this->token."' and MemberId like '%".$member."%' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
			}else{
				$str="token='".$this->token."' and MemberId like '%".$member."%'";
			}
		}else{
			$str="token='".$this->token."' RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
		}
		// if ($this->groupid=='超级管理组') {
		// 	$tempdate=M()->table('RS_Member')->where($str)->select();
		// }else{
		// 	$tempdate=M()->table('RS_Member')->where("Employees='".$this->tempSession['userName']."' AND ".$str)->select();
		// }
		$tempdate=M()->table('RS_Member')->where($str)->select();
		foreach ($tempdate as &$m) {
			$robj=$m['RegisterDate'];
			if ($m['Sex']=='1') {
				$m['Sex']='男';
			}elseif ($m['Sex']=='2') {
				$m['Sex']='女';
			}else{
				$m['Sex']='保密';
			}
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=$v;
				}
			}
		}
		$count=count($tempdate);
		if ($count%$pageCount) {
			$i=1;
		}else{
			$i=0;
		}
		$page=floor($count/$pageCount)+$i;
		for ($i=0; $i < $pageCount; $i++) {
			if ($tempdate[$i]) {
				$mps[]=$tempdate[$i];
			}
		}
		if (!$mps) {
			$mps=array('statu'=>'error','msg'=>"您查询的数据不存在!");
		}
		$this->assign(array('members'=>json_encode($tempdate),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'mps'=>$mps));
		$this->display('extendcut');
	}
	/**
	 *  查看推广佣金明细
	 */
	public function showtk(){
		$tempData=$_GET;
		$whereStr="token='".$this->token."' and MemberId='".$tempData['mid']."' and Type='T' or Type='TTQ'";//根据推广佣金状态查询
		if ($tempData['stime']) {
			$whereStr.=" and CreateDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
		}
		$count=M()->table('RS_MemberCommission')->where($whereStr)->count();
		$page = new \Think\Page($count,20,$tempData);
		$lists=M()->table('RS_MemberCommission')->where($whereStr)->limit($page->firstRow.','.$page->listRows)->field("OrderId,FromMemberId,Money,CONVERT(varchar(120),CreateDate,120) as CreateDate")->select();
		$pagedata['lists']=$lists;
		$pagedata['page']=$page->show();
		$this->display();
	}

}













?>
