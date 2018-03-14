<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends CommonController{
	public $logStr;
	public function _initialize(){
		parent::_initialize();
		$this->logStr=':::操作人:'.session('uinfo')['userName'];
	}

	public function index(){
		$count=$this->MSL('user')->where("token='%s' and stoken='%s' and UserType='0'",array($this->token,$this->stoken))->count();
		$page= new \Think\Page($count,20);
		$employees=$this->MSL('user')->where("token='%s' and stoken='%s'  and UserType='0'",array($this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($employees as &$emp) {
			$emp['DepartmentName']=M()->table('RS_Department')->where('ID=%d',$emp['DepartmentName'])->getField('Name');
			$tobj=$emp['CreateDate'];
			foreach ($tobj as $k => $v) {
				if ($k=='date') {
					$emp['CreateDate']=$v;
				}
			}
			$lobj=$emp['LastLoginDate'];
			foreach ($lobj as $lk => $lv) {
				if ($lk=='date') {
					$emp['LastLoginDate']=$lv;
				}
			}
		}
		$thisuid=session('userinfo')['ID'];
		$IsLeader=$this->MSL('user')->where("id=%d",$thisuid)->getField('IsLeader');
		$this->assign('IsLeader',$IsLeader);
		$allParts=M()->table('RS_Department')->where("token='%s'",$this->token)->order('Sort')->select();
		$this->assign(array('employees'=>$employees,'page'=>$page->show(),'allparts'=>$allParts));
		// echo "<pre>";
		// var_dump($employees);
		define('FPAGE', 'YUANGONG');
		
		$this->display();
	}

	public function add(){
		$allParts=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->order('id')->select();
		$groups=$this->MSL('groupmanger')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		$this->assign(array('allParts'=>$allParts,'pagename'=>'添加管理员','groups'=>$groups));
		define('FPAGE', 'YUANGONG');
		
		$this->display();
	}

	public function save(){
		$data['userName']=$_POST['EmployeeId'];
		$data['TrueName']=$_POST['TrueName'];
		if ($_POST['Password']) {
			$data['Password']=md5($_POST['Password']);
		}
		$data['DepartmentName']=$_POST['DepartmentName'];
		$data['Sex']=$_POST['Sex'];
		$data['IsLogin']=$_POST['IsLogin'];
		// $data['InputId']=session('userinfo')['ID'];
		// $data['InputName']=session('userinfo')['EmployeeId'];
		$sinfo=session('userinfo');
		$data['InputId']=$sinfo['ID'];
		$data['InputName']=$sinfo['userName'];
		$data['token']=$this->token;
		$data['CreateDate']=time();
		$data['CreateDate']=time();
		$data['Invcode']=strtoupper(substr(md5(uniqid('INV')), -20,20));
		// $data['token']=$this->token;
		$data['token']=$this->token;
		$data['stoken']='0';
		$data['IsLeader']=$_POST['IsLeader']?$_POST['IsLeader']:0;
		$data['IsServer']=$_POST['IsServer']?$_POST['IsServer']:0;
		$Gdata['GroupId']=$_POST['GroupId'];
		$Gdata['InputName']=$sinfo['userName'];
		$Gdata['InputId']=$sinfo['ID'];
		$Gdata['token']=$this->token;
		$Gdata['stoken']='0';
		if ($_POST['id']) {
			$Gdata['LastUpdateDate']=time();
			$data['LastUpdateDate']=time();
			$model=$this->MSL('user');
			$model->startTrans();
			$pres=$model->where('id=%d',$_POST['id'])->save($data);
			// $Gdata['EmployeeId']=$id;
			$res=$this->MSL('usergroup')->where("userId=%d",$_POST['id'])->save($Gdata);
			if ($pres && $res) {
				$this->LOGS('修改成功,修改用户：'.$_POST['EmployeeId'].$this->logStr);  //记录日志文件
				$model->commit();
				$this->success('修改成功',U('Admin/index'));
			}else{
				$this->LOGS('修改失败：$pres='.intval($pres).'/$res='.intval($res).$_POST['EmployeeId'].$this->logStr);   //记录日志文件
				// echo $model->getlastsql();exit();
				$model->rollback();
				$this->error('修改失败');
			}
		}else{
			$data['HeadImgUrl']='default.png';
			$model=$this->MSL('user');
			$model->startTrans();
			$id=$model->add($data);
			$sql=$this->MSL()->getlastsql();
			// var_dump($id);exit();
			$Gdata['userId']=$id;
			$res=$this->MSL('usergroup')->add($Gdata);
			$sql2=$this->MSL()->getlastsql();
			if ($id && $res) {
				$this->LOGS('添加成功，添加用户：'.$_POST['EmployeeId'].$this->logStr);
				$model->commit();
				$this->success('添加成功',U('Admin/index'));
			}else{
				// $this->LOGS('修改修改失败：$id='.intval($id).'/$res='.intval($res).$this->logStr);
				$this->LOGS('添加失败：$id='.intval($id).'/$res='.intval($res).':::'.$sql.'///'.$sql2.$this->logStr);
				// echo $model->getlastsql();exit();
				$model->rollback();
				$this->error('添加失败');
			}
		}
	}

	public function partment(){
		$count=M()->table('RS_Department')->where("token='%s'",$this->token)->count();
		$page=new \Think\Page($count,20);
		$parts=M()->table('RS_Department')->where("Grade=1 and token='".$this->token."'")->select();
		$allParts=M()->table('RS_Department')->where("token='%s'",$this->token)->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($allParts as &$p) {
			$tempvar=M()->table('RS_Department')->where('ID=%d',$p['ParentId'])->getField('Sort');
			// $p['ParentSort']=substr($tempvar, 0,2);
			$p['ParentSort']=$tempvar;
			if ($p['ParentId']) {
				// $p['show']=explode('-', $p['Sort'])[1];
				$t=explode('-', $p['Sort']);
				$p['show']=$t[1];
			}else{
				$p['show']=$p['Sort'];
			}
		}
		$this->assign(array('parts'=>$parts,'allParts'=>$allParts,'jsonData'=>json_encode($allParts),'pdata'=>json_encode($parts),'page'=>$page->show()));
		$this->display();
	}

	public function savePartment(){
		// var_dump($_POST);

		$data['Name']=trim($_POST['Name']);
		$data['ParentId']=$_POST['ParentId'];
		$data['token']=$this->token;
		$data['Remarks']=htmlspecialchars(trim($_POST['Remarks']));
		if ($_POST['ParentId']) {
			$data['Grade']=2;
		}else{
			$data['Grade']=1;
		};

		if (strlen($_POST['Sort'])<2) {
			$sort="0".$_POST['Sort'];
		}else{
			$sort=$_POST['Sort'];
		};
		$preID=$_POST['ParentSort'];
		if ($_POST['ParentId']) {
			$data['Sort']=$preID."-".$sort;
		}else{
			$data['Sort']=$sort;
		}
		// var_dump($data);exit();
		if ($_POST['id']) {
			if (M()->table('RS_Department')->where('ID=%d',$_POST['id'])->save($data)) {
				$this->LOGS('修改成功');
				$this->success('修改成功');
			}else{
				$this->LOGS('修改失败');
				$this->error('修改失败');
			}
		}else{
			if (M()->table('RS_Department')->add($data)) {
				$this->LOGS('添加成功');
				$this->success('添加成功');
			}else{
				$this->LOGS('添加失败');
				$this->error('添加失败');
			}
		}
	}

	public function edit(){
		$id=$_GET['id'];
		$allParts=M()->table('RS_Store')->where("token='%s'",$this->token)->order('id')->select();

		$groups=$this->MSL('groupmanger')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		$info=$this->MSL('user')->where('id=%d',$id)->find();
		$info['GroupId']=$this->MSL('usergroup')->where('userId=%d',$info['id'])->getField('GroupId');
		$this->assign(array('allParts'=>$allParts,'pagename'=>'添加管理员','info'=>$info,'groups'=>$groups));
		$this->display();
	}


	public function del(){
		$id=$_GET['id'];
		$isAdmin=$this->MSL('user')->where('id=%d',$id)->getField('IsAdmin');
		if ($id!='2') {
			if ($this->MSL('user')->where('id=%d',$id)->delete()) {
				$this->LOGS('删除成功,删除用户ID：'.$id);
				$this->success('删除成功');
			}else{
				$this->LOGS('删除失败');
				$this->error('删除失败');
			}
		}else{
			$this->LOGS('尝试删除初始管理员');
			$this->error('系统用户无法删除');
		}
	}

	public function delPart(){
		$id=$_GET['id'];
		if (M()->table('RS_Department')->where('ID=%d',$id)->delete()) {
			$this->LOGS('删除失败');
			$this->success('操作成功');
		}else{
			$this->LOGS('删除失败');
			$this->error('操作失败');
		}
	}


	/**
	 * 分配会员，功能已废弃
	 */
	public function member(){
		$count=M()->table('RS_Member')->count();
		$page=new \Think\Page($count,20);
		$members=M()->table('RS_Member')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($members as &$m) {
			$tempD=array();
			$robj=$m['RegisterDate'];
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=$v;
				}
			}
			$temp=explode(',', $m['Employees']);
			foreach ($temp as $kk) {
				$tempD[]=M()->table('RS_GroupManager')->where('GroupId=%d',$kk)->getField('GroupName');
			}
			$m['Employees']=$tempD;
		}
		$this->assign(array('members'=>$members,'page'=>$page->show()));
		$this->display();
	}

	/**
	 * 员工信息检索
	 */
	public function search(){
		if (IS_POST) {
			$tempData=$_POST;
		}else{
			$tempData=$_GET;
		}
		$str="token='".$this->token."'";
		if ($tempData['EmployeeId']) {
			$str.="AND userName like '%".$tempData['EmployeeId']."%'";
		}
		if ($tempData['TrueName']) {
			$str.="AND TrueName like '%".$tempData['TrueName']."%'";
		}
		if ($tempData['DepartmentName']) {
			$str.="AND DepartmentName='".$tempData['DepartmentName']."'";
		}
		$count=$this->MSL('user')->where($str)->count();
		$page= new \Think\Page($count,15,$tempData);
		$res=$this->MSL('user')->where($str)->limit($page->firstRow.','.$page->listRows)->select();
		// echo $this->MSL()->getlastsql();exit();
		foreach ($res as &$r) {
			$r['DepartmentName']=M()->table('RS_Department')->where('ID=%d',$r['DepartmentName'])->getField('Name');
			$tobj=$r['CreateDate'];
			foreach ($tobj as $k => $v) {
				if ($k=='date') {
					$r['CreateDate']=$v;
				}
			}
			$lobj=$r['LastLoginDate'];
			foreach ($lobj as $lk => $lv) {
				if ($lk=='date') {
					$r['LastLoginDate']=$lv;
				}
			}
		}
		if (!$res) {
			$res=array('statu'=>'error','msg'=>"您查询的信息不存在!");
		}
		$allParts=M()->table('RS_Department')->where("token='%s'",$this->token)->order('Sort')->select();
		$this->assign(array('employees'=>$res,'page'=>$page->show(),'allparts'=>$allParts));
		define('FPAGE', 'YUANGONG');
		
		$this->display('index');
		// var_dump($where);
	}

	/**
	 * 员工信息导出
	 */
	/**
	 * 员工信息导出
	 */
	public function empOut(){
		if (IS_POST) {
			$tempData=$_POST;
		}else{
			$tempData=$_GET;
		}
		$str="token='".$this->token."'";
		$pram="查询条件：";
		$isall=true;
		if ($tempData['EmployeeId']) {
			$str.="AND userName like '%".$tempData['EmployeeId']."%'";
			$pram.=" 员工账号(模糊查询)：".$tempData['EmployeeId'];
			$isall=false;
		}
		if ($tempData['TrueName']) {
			$str.="AND TrueName like '%".$tempData['TrueName']."%'";
			$pram.="  真实姓名：".$tempData['TrueName'];
			$isall=false;
		}
		$res=$this->MSL('user')->where($str)->select();
		// echo $this->MSL()->getlastsql();exit();
		foreach ($res as &$r) {
			$tobj=$r['CreateDate'];
			if ($r['Sex']=='0') {
				$r['Sex']='保密';
			}
			if ($r['Sex']=='1') {
				$r['Sex']='男';
			}
			if ($r['Sex']=='2') {
				$r['Sex']='女';
			}
			$r['CreateDate']=date('Y-m-d H:i:s',$tobj);
		}
        $xlsName="employees_".date('ymdHm');
        $xlsCell = array(
            array('userName' , '员工账户'),
            array('TrueName' , '员工姓名'),
            array('Sex' , '性别'),
            array('Remarks' ,'备注内容'),
            array('CreateDate' , '添加时间'),
        );
        if ($isall) {
        	$file_title="查询条件：全部";
        }else{
        	$file_title=$pram;
        }
        exportExcel($xlsName,$xlsCell,$res,$file_title);
	}


	/**
	 * 会员分配搜索，功能已废弃
	 */
	public function searchUser(){
		$stime=$_POST['strtime'];
		$etime=$_POST['endtime'];
		$member=$_POST['Member'];
		if ($member) {
			if ($stime && $etime) {
				$str="MemberId like '%".$member."%' and RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
			}else{
				$str="MemberId like '%".$member."%'";
			}
		}else{
			$str="RegisterDate BETWEEN '".$stime."' AND '".$etime."'";
		}
		$count=M()->table('RS_Member')->where($str)->count();
		$page=new \Think\Page($count,20);
		$members=M()->table('RS_Member')->where($str)->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($members as &$m) {
			$robj=$m['RegisterDate'];
			foreach ($robj as $k => $v) {
				if ($k=='date') {
					$m['RegisterDate']=$v;
				}
			}
			$tempD=array();
			$temp=explode(',', $m['Employees']);
			foreach ($temp as $kk) {
				$tempD[]=$this->MSL('user')->where('ID=%d',$kk)->getField('userName');
			}
			$m['Employees']=$tempD;
		}
		if (!$members) {
			$members=array('statu'=>'error','msg'=>"您查询的数据不存在!");
		}
		$this->assign(array('members'=>$members,'page'=>$page->show()));
		$this->display('member');
	}

	public function import(){

	}

	/**
	 * 员工提成管理
	 */
	public function ticheng(){
		$emps=$this->MSL('user')->where("token='%s'",$this->token)->select();
		foreach ($emps as &$emp) {
			// $tempD=$emp['LastLoginDate'];
			// foreach ($tempD as $k => $v) {
			// 	if ($k=='date') {
			// 		$emp['LastLoginDate']=$v;
			// 	}
			// }
			$mers=M()->table('RS_Member')->where("Employees='%s'",$emp['id'])->getField('MemberId',true);
			$str="MemberId in('".implode("','", $mers)."')";
			$orders=M()->table('RS_Order')->where($str)->getField('OrderId',true);
			$allnums=M()->query("SELECT sum(o.Money*p.EmpCut/100) as allnums FROM RS_ProductList as p LEFT JOIN RS_OrderList as o ON o.ProIdCard=p.ProIdCard WHERE o.OrderId in ('".implode("','", $orders)."')");
			$getnums=M()->query("SELECT sum(o.Money*p.EmpCut/100) as getnums FROM RS_ProductList as p LEFT JOIN RS_OrderList as o ON o.ProIdCard=p.ProIdCard WHERE o.OrderId in ('".implode("','", $orders)."') AND o.EmpCut='success'");
			$emp['allnums']=$allnums[0]['allnums']?number_format($allnums[0]['allnums'],2):0;
			$gets=number_format($allnums[0]['allnums']-$getnums[0]['getnums'],2);
			$emp['getnums']=$gets;
			// echo M()->getlastSql();
			// var_dump($allnums);var_dump($gets);exit();
		}
		$this->assign('emps',$emps);
		define('FPAGE', 'YUANGONG');
		
		$this->display();
	}

	/**
	 * 提现处理
	 */
	public function getcash(){
		$id=$_GET['id'];
		$mers=M()->table('RS_Member')->where("Employees='%s'",$id)->getField('MemberId',true);
		$str="MemberId in('".implode("','", $mers)."')";
		$orders=M()->table('RS_Order')->where($str)->getField('OrderId',true);
		M()->query("UPDATE RS_OrderList SET EmpCut='success' WHERE OrderId in ('".implode("','", $orders)."')");
		$this->LOGS('体现成功');
		$this->success('操作成功',U('Admin/ticheng'));
	}

	/**
	 * 核销员管理
	 */

	 public function Cancels(){
		 $count=M()->table('RS_Cancel')->count();
		 $pagesize=15;//分页大小
		 $page=new \Think\Page($count,$pagesize);
		 $userlists=M()->query("SELECT TOP ".$pagesize." s.storename,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.ZTname LEFT JOIN RS_Store s on c.storeid=s.id WHERE c.id not in(select top ".$page->firstRow." id from RS_Cancel) GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id");
		//  var_dump(M()->getlastsql());
		 $pagedata['CanType']='(全部)';
		 $pagedata['Ctype']='all';
		 $pagedata['userlists']=$userlists;
		 $pagedata['page']=$page->show();
		 $this->assign($pagedata);
		 $this->display();
	 }

	 /**
	  * 核销查询
	  */
		public function searchcan(){
			if (IS_POST) {
				$CanType=$_POST['CanType'];
				$username=$_POST['username']?$_POST['username']:false;
			}else{
				$CanType=$_GET['CanType'];
				$username=$_GET['username']?$_GET['username']:false;
			}
			if ($username) {
				$where['CanType']=$CanType;
				$where['username']=$username;
				$sql=" AND c.username like '%".$username."%' AND o.CanType='".$CanType."'";
				$count=M()->table('RS_Cancel')->where("username like '%".$username."%'")->count();
			}else{
				$where['CanType']=$CanType;
				$sql=" AND o.CanType='".$CanType."'";
				$count=count(M()->query("SELECT ZTname FROM RS_Order WHERE CanType='".$CanType."' GROUP BY ZTname"));
				// var_dump($count);exit;
			}
			$pagesize=1;//分页大小
			$page=new \Think\Page($count,$pagesize,$where);
			$userlists=M()->query("SELECT TOP ".$pagesize." s.storename,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.ZTname LEFT JOIN RS_Store s on c.storeid=s.id WHERE c.id not in(select top ".$page->firstRow." id from RS_Cancel)".$sql." GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id");
			// var_dump(M()->getlastsql());
			// var_dump($userlists);
			if (!$userlists) {
				$pagedata['errmsg']='您查询的信息不存在!';
			}
			if ($CanType=='pay') {
				$pagedata['CanType']='(付款)';
				$pagedata['Ctype']='pay';
			}else{
				$pagedata['CanType']='(提货)';
				$pagedata['Ctype']='get';
			}
			$pagedata['userlists']=$userlists;
			$pagedata['page']=$page->show();
			$this->assign($pagedata);
			$this->display('Cancels');
		}


	/**
	 * 员工关联门店
	 */
	public function EmpOfStore(){
		if (IS_POST) {
			// echo "<pre>";
			// var_dump($_POST);exit();
			$EmpId=$_POST['EmpId'];
			$Sts=$_POST['StoreId'];
			M()->startTrans();
			M()->table('RS_EmpOfStore')->where("EmpId=%d",$EmpId)->delete();
			$sres=true;
			foreach ($Sts as $st) {
				$DB['EmpId']=$EmpId;
				$DB['StoreId']=$st;
				$DB['token']=$this->token;
				if (!M()->table('RS_EmpOfStore')->add($DB)) {
					$sres=false;
					break;
				}
			}
			if ($sres) {
				M()->commit();
				$this->success('保存成功',U('Admin/Index'));
			}else{
				M()->rollback();
				$this->error('保存失败');
			}
		}else{
			$id=$_GET['id'];
			$EmpInfo=$this->MSL('user')->where('id=%d',$id)->find();
			$Stores=M()->query("SELECT * FROM RS_Store WHERE IsCheck=1 and id NOT IN (SELECT StoreId FROM RS_EmpOfStore WHERE token='{$this->token}')");
			$MyStores=M()->table('RS_EmpOfStore eos')->join("LEFT JOIN RS_Store s ON eos.StoreId=s.id")->where("eos.token='%s' and eos.EmpId=%d",array($this->token,$id))->field("s.storename,s.id,s.province,s.city,s.area,s.addr")->select();
			$pagedata['Disabled']=$Disabled;
			$pagedata['MyStores']=$MyStores;
			$pagedata['Stores']=$Stores;
			$pagedata['EmpInfo']=$EmpInfo;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 区域管理
	 */
	public function AreaManager(){
		if (IS_POST) {
			if ($_POST['ID']) {
				if (M()->table('RS_AreaManager')->where("ID=%d",$_POST['ID'])->setField("AreaName",$_POST['AreaName'])) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}else{
				$DB['AreaName']=$_POST['AreaName'];
				$DB['token']=$this->token;
				if (M()->table('RS_AreaManager')->add($DB)) {
					$this->success('保存成功');
				}else{
					$this->error('保存失败');
				}
			}
		}else{
			$areas=M()->table('RS_AreaManager')->where("token='%s'",$this->token)->select();
			$areasons=M()->table('RS_AreaList')->where("token='%s'",$this->token)->select();
			$pagedata['jsondata']=json_encode($areas);
			foreach ($areas as &$as) {
				$sons=array();
				foreach ($areasons as $ass) {
					if ($as['ID']==$ass['AreaId']) {
						$sons[]=$ass;
					}
				}
				$as['sons']=$sons;
			}
			$pagedata['areas']=$areas;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 删除区域
	 */
	public function delarea(){
		$id=$_POST['id'];
		M()->startTrans();
		$ares=M()->table('RS_AreaManager')->where("ID=%d",$id)->delete();
		$oares=M()->table('RS_AreaList')->where("AreaId=%d",$id)->delete();
		if ($ares!==false && $oares!==false) {
			M()->commit();
			$msg['status']='success';
		}else{
			M()->rollback();
			$msg['status']='error';
		}
		echo json_encode($msg);
	}

	/**
	 * 区域划分
	 */
	public function SetAreaInfo(){
		if (IS_POST) {
			// echo "<pre>";
			// var_dump($_POST);
			$AreaId=$_POST['AreaId'];
			$Areas=$_POST['AreaName'];
			M()->startTrans();
			$ares=true;
			M()->table('RS_AreaList')->where("AreaId=%d",$_POST['AreaId'])->delete();
			foreach ($Areas as $as) {
				$DB['AreaId']=$AreaId;
				$DB['Area']=$as;
				$DB['token']=$this->token;
				if (!M()->table('RS_AreaList')->add($DB)) {
					$ares=false;
					break;
				}
			}
			if ($ares) {
				M()->commit();
				$this->success('保存成功',U('Admin/AreaManager'));
			}else{
				M()->rollback();
				$this->error('保存失败');
			}
		}else{
			$Myinfo=M()->table('RS_AreaManager')->where("ID=%d",$_GET['id'])->find();
			$MyArea=M()->table('RS_AreaList')->where("token='%s' and AreaId=%d",array($this->token,$_GET['id']))->getField("Area",true);
			$FullArea=C('FULLAREA');
			$area=M()->table('RS_AreaList')->where("token='%s'",$this->token)->getField('Area',true);
			foreach ($FullArea as $ak=>$aea) {
				if (in_array($aea, $area)) {
					unset($FullArea[$ak]);
				}
			}
			$pagedata['FullArea']=$FullArea;
			$pagedata['Myinfo']=$Myinfo;
			$pagedata['MyArea']=$MyArea;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 员工关联区域
	 */
	public function EmpOfArea(){
		if (IS_POST) {
			// echo "<pre>";
			// var_dump($_POST);
			if ($this->MSL('user')->where("id=%d",$_POST['id'])->setField('AreaIds',serialize($_POST['AreaIds']))) {
				$this->success('保存成功',U('Admin/Index'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$Myinfo=$this->MSL('user')->where("id=%d",$_GET['id'])->find();
			$MyArea=unserialize($Myinfo['AreaIds']);
			// var_dump($MyArea);exit();
			if (count($MyArea)>0 && $MyArea) {
				$NowArea=M()->table('RS_AreaManager')->where("token='%s' and ID NOT IN %s",array($this->token,'('.implode(',', $MyArea).')'))->select();
				$MyArea=M()->table('RS_AreaManager')->where("token='%s' and ID IN %s",array($this->token,'('.implode(',', $MyArea).')'))->select();
			}else{
				$NowArea=M()->table('RS_AreaManager')->where("token='%s'",$this->token)->select();
				$MyArea=array();
			}
			// echo M()->getlastSql();exit();
			$pagedata['NowArea']=$NowArea;
			$pagedata['MyArea']=$MyArea;
			$pagedata['Myinfo']=$Myinfo;
			$this->assign($pagedata);
			$this->display();
		}
	}

	/**
	 * 参数检查
	 */
	public function checkparam(){
		$type=$_POST['type'];
		$key=$_POST['key'];
		if ($type=='name') {
			if ($this->MSL('user')->where("userName='%s'",$key)->find()) {
				$msg['status']='error';
			}else{
				$msg['status']='success';
			}
		}
		$this->ajaxReturn($msg);
	}




	/**
	 * 推广人员管理
	 */
	public function tuiers(){
		if (IS_POST) {
			$sinfo=session('userinfo');
			$db=array();
			$db['Account']=$_POST['userName'];
			$db['TrueName']=$_POST['TrueName'];
			$db['CreateDate']=time();
			$db['HeadImgUrl']='default.png';
			$db['Invcoded']=$_POST['Invcoded'];
			$ures=$gres=true;
			if ($_POST['id']) {
				if ($_POST['Password']) {
					$db['Password']=md5($_POST['Password']);
				}
				$db['LastUpdateDate']=time();
				$ures=M()->table('RS_Tuier')->where("ID=%d",$_POST['id'])->save($db);
				if($ures) {
					$this->success('保存成功');
				}else {
					$this->error('保存失败');
				}
			}else{
				$db['Password']=md5($_POST['Password']);
				$db['Invcode']=strtoupper(substr(md5(uniqid('INV')), -20,20));
				$ures = M()->table("RS_Tuier")->add($db);
				if($ures) {
					$this->success('保存成功');
				}else {
					$this->error('保存失败');
				}
			}
			
		}else{
			$count = M()->table("RS_Tuier")->count();
			$page = new \Think\Page($count,10);
			$show = $page->show();
			// $users = M()->query("SELECT a.*,ISNULL(b.nums, 0) as num FROM(SELECT rt.ID,rt.Account,rt.Invcode,rt.IsCheck,rt.TrueName,rt.LastLoginDate,rt.Invcoded,rt.[Level],count(rs.id) as count FROM RS_Tuier rt LEFT JOIN RS_Store rs ON rt.Invcode = rs.Invcode GROUP BY rt.ID,rt.Account,rt.TrueName,rt.LastLoginDate,rt.[Level],rt.Invcode,rt.Invcoded,rt.IsCheck) a LEFT JOIN (select count(ID) as nums,Invcoded FROM RS_Tuier GROUP BY Invcoded) b on a.Invcode = b.Invcoded ");
			$users = M()->query("SELECT COUNT(s.id) as  count,ts.* FROM (SELECT COUNT(rt.ID) as num,l.LevelName,t.Invcode,t.ID,t.Account,t.IsCheck,t.TrueName,t.LastLoginDate FROM RS_Tuier t LEFT JOIN RS_Tuier rt ON t.Invcode=rt.Invcoded LEFT JOIN RS_LevelInfo l ON t.[Level]=l.LevelLabel GROUP BY l.LevelName,t.ID,t.Account,t.Invcode,t.IsCheck,t.TrueName,t.LastLoginDate ) ts LEFT JOIN RS_Store s ON s.Invcode=ts.Invcode GROUP BY ts.ID,ts.num,ts.LevelName,ts.Invcode,ts.Account,ts.IsCheck,ts.TrueName,ts.LastLoginDate ");

			$pagedata['users']=$users;
			$pagedata['page'] = $show;
			$pagedata['jsondata']=json_encode($users);
			$this->assign($pagedata);
			$this->display();
		}
	}
	/*
		为推广人添加会员等级
	*/
		public function addCount() {
			if(IS_POST) {
				$id = $_POST['ID'];
				$data['Level'] = $_POST['Level'];
				// $res = M()->table("RS_Tuier")->save($data);
				$res = M()->table("RS_Tuier")->where("ID=%d",$id)->field('Level')->save($data);
				if($res) {
					$msg['type'] = "success";
					$msg['info'] = "更新会员成功";
				}else {
					$msg['type'] = "error";
					$msg['info'] = "更新会员失败";
				}
			}
			$this->ajaxReturn($msg);
		}
	/*
		删除
	*/
		public function delAccount() {
			if(IS_POST) {
				$id = $_POST['id'];
				$res = M()->table("RS_Tuier")->where("ID=%d",$id)->delete();
				if($res) {
					$msg['type'] = "success";
				}else {
					$msg['type'] = "error";
					$msg['info'] = "删除失败";
				}
			}
			$this->ajaxReturn($msg);
		}


	public function editTuier(){
		if (IS_POST) {
			$id = $_POST['ID'];
			$res = M()->table('RS_Tuier')->where("ID=%d",$id)->find();
			if($res) {
				$msg['status'] = "success";
				$msg['level'] = $res['Level'];
			}else {
				$msg['status'] = "error";
  			}
  			$this->ajaxReturn($msg);
  		}
	}

	public function subSave() {
		if(IS_POST) {
			$id = $_POST['ID'];
			$level = $_POST['Level'];
			$res = M()->table('RS_Tuier')->where("ID=%d",$id)->setField('Level',$level);
			if($res) {
				$msg['status'] = "success";
			}else {
				$msg['status'] = "error";
				$msg['info'] = "修改失败";
			}
			$this->ajaxReturn($msg);
		}
	}


	public function checktuier(){
		if (IS_POST) {
			$id = $_POST['ID'];
			$data['Level'] = $_POST['Level'];
			$data['IsCheck'] = '1';
			$res = M()->table("RS_Tuier")->where("ID=%d",$id)->setField($data);
			if ($res) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='处理失败';
			}
		}else{
			$msg['status']='error';
			$msg['info']='非法操作';
		}
		$this->ajaxReturn($msg);
	}
	/*
		推广人信息导出
	*/
	public function empTuier() {
		//查询出所有的数据
		// $xlsData = M()->table("RS_Tuier")->select();
		// $xlsData = M()->query("SELECT a.*,ISNULL(b.nums, 0) as num FROM(SELECT rt.ID,rt.Account,rt.Invcode,rt.IsCheck,rt.TrueName,rt.LastLoginDate,rt.[Level],count(rs.id) as count FROM RS_Tuier rt LEFT JOIN RS_Store rs ON rt.Invcode = rs.Invcode GROUP BY rt.ID,rt.Account,rt.TrueName,rt.LastLoginDate,rt.[Level],rt.Invcode,rt.IsCheck) a LEFT JOIN (select count(ID) as nums,Invcoded FROMRS_Tuier GROUP BY Invcoded) b on a.Invcode = b.Invcoded");
		$xlsData = M()->query("SELECT COUNT(s.id) as  count,ts.* FROM (SELECT COUNT(rt.ID) as num,l.LevelName,t.Invcode,t.ID,t.Account,t.IsCheck,t.TrueName,t.LastLoginDate FROM RS_Tuier t LEFT JOIN RS_Tuier rt ON t.Invcode=rt.Invcoded LEFT JOIN RS_LevelInfo l ON t.[Level]=l.LevelLabel GROUP BY l.LevelName,t.ID,t.Account,t.Invcode,t.IsCheck,t.TrueName,t.LastLoginDate ) ts LEFT JOIN RS_Store s ON s.Invcode=ts.Invcode GROUP BY ts.ID,ts.num,ts.LevelName,ts.Invcode,ts.Account,ts.IsCheck,ts.TrueName,ts.LastLoginDate ");

		foreach ($xlsData as &$value) {
			if($value['IsCheck'] == '0') {
				$value['IsCheck'] = "待审核";
			}else {
				$value['IsCheck'] = "已审核";
			}
			$lastTime = $value['LastLoginDate'];
			$value['LastLoginDate'] = date("Y-m-d H:i",$lastTime);
		}
		//excel表的名字
		$xlsName = "tuiers_".date('ymdHms');
		//excel表单列名称
		$xlsCell = array(
			array('Account','账号'),
			array('TrueName','姓名'),
			array('Invcode','邀请码'),
			array('LastLoginDate','最后登陆时间'),
			array('IsCheck','当前状态'),
			array('count','推广人数量'),
			array('num','推广店数量')
		);
		//导出Excel表信息
		exportExcel($xlsName,$xlsCell,$xlsData);
	}
	/*
	*推广人数量导出
	*/
	public function tuiernum() {
		$id = $_GET['id'];
		//获取当前账号下的所有推广人信息
		$tuinfo = M()->table("RS_Tuier")->where("Invcoded='%s'",$id)->select(); 
		$invcode = array();
		foreach ($tuinfo as $key => $value) {
			$invcode[] = $value['Invcode'];
		}
		$people = M()->table("RS_Tuier")->where("Invcoded in('".implode("','", $invcode)."')")->group('Invcoded')->getField("Invcoded,count(*) as count");
		if(!empty($tuinfo)) {
			foreach ($tuinfo as $key => $value) {
				$value['pcount'] = $people[$value['Invcode']]?$people[$value['Invcode']]:'0';
				$tuinfo[$key] = $value;
			}
		}
		//推广店面查询
		$store = M()->table("RS_Store")->where("Invcode in('".implode("','", $invcode)."')")->group('Invcode')->getField("Invcode,count(*) as count");
		if(!empty($tuinfo)) {
			foreach ($tuinfo as $k => $v) {
				$v['scount'] = $store[$v['Invcode']]?$store[$v['Invcode']]:'0';
				$tuinfo[$k] = $v;
				if($v['IsCheck'] == '0' ){
					$status = "待审核";
				}else {
					$status = "已审核";
				}
				$tuinfo[$k]['status'] = $status;
				
			}
		}
		$info = M()->table('RS_Tuier')->where("Invcode='%s'",$id)->find();
		$xlsName = $info['TrueName'].'_'.$info['Account'].'_'.date('ymdHms');
		//excel表单列名称
		$xlsCell = array(
			array('TrueName','推广人姓名'),
			array('Account','推广人账号'),
			array('Invcode','邀请码'),
			array('pcount','推广人数量'),
			array('scount','推广人店面数量'),
			array('status','当前状态')
		);
		exportExcel($xlsName,$xlsCell,$tuinfo);
	}
	/*
	*店面数量导出
	*/
	public function shopnum() {
		$id = $_GET['id'];
		$xlsData = M()->query("SELECT storename,tel,(province+''+city+''+area+''+ addr) as addr,Checkmark,CONVERT(VARCHAR(20),CreateDate,120) as createdate,convert(float(53),TotalMoney,120) as TotalMoney,TrueName FROM RS_Store WHERE Invcode='{$id}'");
		$info = M()->table('RS_Tuier')->where("Invcode='%s'",$id)->find();
		$xlsName = $info['TrueName'].'_'.$info['Account'].'_'.date('ymdHms');
		//excel表单列名称
		$xlsCell = array(
			array('storename','店名称'),
			array('addr','地址'),
			array('tel','手机号'),
			array('TrueName','店主'),
			array('createdate','创建时间'),
			array('TotalMoney','总收入')
		);
		exportExcel($xlsName,$xlsCell,$xlsData);
	}
	


	public function peopleDetail() {
		$id = $_POST['id'];
		//获取当前账号下的所有推广人信息
		$tuinfo = M()->table("RS_Tuier")->where("Invcoded='%s'",$id)->select(); 
		// echo "<pre>";
		// var_dump($tuinfo);die();
		$invcode = array();
		foreach ($tuinfo as $key => $value) {
			$invcode[] = $value['Invcode'];
		}
		$people = M()->table("RS_Tuier")->where("Invcoded in('".implode("','", $invcode)."')")->group('Invcoded')->getField("Invcoded,count(*) as count");
		if(!empty($tuinfo)) {
			foreach ($tuinfo as $key => $value) {
				$value['pcount'] = $people[$value['Invcode']]?$people[$value['Invcode']]:'0';
				$tuinfo[$key] = $value;
			}
		}
		//推广店面查询
		$store = M()->table("RS_Store")->where("Invcode in('".implode("','", $invcode)."')")->group('Invcode')->getField("Invcode,count(*) as count");
		if(!empty($tuinfo)) {
			foreach ($tuinfo as $k => $v) {
				$v['scount'] = $store[$v['Invcode']]?$store[$v['Invcode']]:'0';
				$tuinfo[$k] = $v;
			}
		}
		if($tuinfo) {
			$msg['type'] = "success";
		}else {
			$msg['type'] = "error";
		}
		$msg['tuinfo'] = $tuinfo;
		$msg['turename'] = $TrueName;
		$this->ajaxReturn($msg);
	}



	public function storeDetail() {
		$id = $_POST['id'];
		// $info = $this->MSL('user')->where('id=%d',$id)->find();
		//获取用户名和Invcode
		// $TrueName = $info['TrueName'];
		// $invcodes = $info['Invcode'];
		//获取当前账号下的所有推广人信息
		$tuinfo = M()->table("RS_Tuier")->where("Invcoded= '%s'",$id)->select(); 
		$invcode = array();
		foreach ($tuinfo as $key => $value) {
			$invcode[] = $value['Invcode'];
		}
		//获取当前推广人的推广店铺信息
		$storeInfo = M()->query("SELECT storename,tel,province,city,area,addr,Checkmark,CONVERT(VARCHAR(20),CreateDate,120) as createdate,convert(float(53),TotalMoney,120) as TotalMoney,TrueName FROM RS_Store WHERE Invcode='{$id}'");
		if($storeInfo) {
			$msg['type'] = "success";
		}else {
			$msg['type'] = "error";
		}
		$msg['storeInfo'] = $storeInfo;
		$msg['turename'] = $TrueName;
		$this->ajaxReturn($msg);
	}




	//推广人提现信息
	public function getMoney() {
		//查询出分页的总数量
		$count = M()->table("RS_TuierGetmoney")->count();
		$page = new \Think\Page($count,10);
		$show = $page->show();
		//查询出用户信息
		$username = M()->table("RS_Tuier")->Field("ID as id,Account as userName,TrueName")->select();
		//查询出提现信息
		$bankmsg = M()->table("RS_TuierGetmoney")->Field("Tid,TuierId,Money,Status,CONVERT(VARCHAR(20),CreateDate,120) as createdate,BankId,IdName,BankName")->limit($page->firstRow.','.$page->listRows)->select();
		// $bankinfo = M()->table()
		//拼接数组
		$newUser = array();
		foreach ($username as $key => $value) {
			$newUser[$value['id']] = $value;
		}
		foreach ($bankmsg as $k => $v) {
			$bankmsg[$k]['userName'] = $newUser[$v['TuierId']]['userName'];
			$bankmsg[$k]['TrueName'] = $newUser[$v['TuierId']]['TrueName'];
			$bankmsg[$k]['id'] = $newUser[$v['TuierId']]['id'];
		}

		$this->assign('page',$show);
		$this->assign("users",$bankmsg);
		$this->display();
	}

	public function checkMoney() {
		$tid = $_POST['tid'];
		$id = $_POST['id'];
		$type = $_POST['type'];
		if($type == '1') {//同意提现
			$data['Status'] = '1';
			$result = M()->table('RS_TuierGetmoney')->where("Tid='{$tid}'")->save($data);
			/*//如果同意提现
			$allpos =M()->table('RS_TuierGetmoney')->where("Tid='{$tid}'")->find();
			$money = $allpos['Money'];//将提现金额获取出来
			//我的佣金总金额
			$myMoney = M()->query("select CONVERT(VARCHAR(20),ISNULL(SUM(Money),0)) as Money FROM RS_TuiMoneyManager WHERE TuierId='{$id}' AND Type='add' GROUP BY stoken");
			if(!empty($myMoney)) {
				foreach($myMoney as $k=>$v) {
					$sum += (float)$v['Money'];
				}
			}else {
				$sum = '0.00';
			}
			$updateMoney = (float)$sum-(float)$money;
				//剩余金额 = 将总金额-提现金额
//			$usermoney = $this->MSL()->table('tb_user')->save();
			//$rsmoney = M()->query("update ");
			$rsmoney = M()->query("update RS_TuiMoneyManager set Money = {$updateMoney} where Type='add'");*/
			if($result) {
				$msg['status'] = "success";
			}else {
				$msg['info'] = "执行失败";
			}
		}else { //提现拒绝
			$data['Status'] = '2';
			//如果拒绝提现
			$allpos =M()->table('RS_TuierGetmoney')->where("Tid='{$tid}'")->find();
			$money = $allpos['Money'];//将提现金额获取出来
			// $ures=$this->MSL()->table('tb_user')->where("id=%d",$id)->setInc('Money',$money);
			$ures = M()->table("RS_Tuier")->where("id=%d",$id)->setInc('Money',$money);
			// var_dump($this->MSL()->getlastsql());
			//修改提现记录表里面的状态
			$result = M()->table('RS_TuierGetmoney')->where("Tid='{$tid}'")->save($data);
			//如果拒绝则删除掉RS_TuiMoneyManage表对应数据
			$del = M()->table('RS_TuiMoneyManager')->where("ID='{$tid}'")->delete();
			if($result) {
				$msg['status']='success';
			}else {
				$msg['status'] = "error";
				$msg['info']='处理失败';
			}
		}
		$this->ajaxReturn($msg);
		
		
	}

	/**
	 * 推广人等级设置
	 */
	public function LevelSet(){
		if (IS_POST) {
			$id=$_POST['id'];
			if (M()->table('RS_LevelInfo')->where("ID=%d",$id)->setField("LevelCut",$_POST['cut'])) {
				$msg['status']='success';
			}else{
				$msg['status']='error';
				$msg['info']='处理失败';
			}
			$this->ajaxReturn($msg);
		}else{
			$data=M()->table('RS_LevelInfo')->where("LevelType='TUIER'")->select();
			$pagedata['data']=$data;
			$this->assign($pagedata);
			$this->display();
		}
	}




}













 ?>
