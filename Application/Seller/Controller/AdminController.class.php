<?php
namespace Seller\Controller;
use Think\Controller;
class AdminController extends CommonController{
	public $logStr;
	public function _initialize(){
		parent::_initialize();
		$this->logStr=':::操作人:'.session('uinfo')['userName'];
	}

	public function index(){
		$count=$this->MSL('user')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
		$page= new \Think\Page($count,20);
		$employees=$this->MSL('user')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($employees as &$emp) {
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
		$this->assign(array('employees'=>$employees,'page'=>$page->show(),'allparts'=>$allParts));
		// echo "<pre>";
		// var_dump($employees);
		$this->display();
	}

	public function add(){
		$allParts=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->order('id')->select();
		$groups=$this->MSL('groupmanger')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
		$this->assign(array('allParts'=>$allParts,'pagename'=>'添加管理员','groups'=>$groups));
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
		$data['stoken']=$this->stoken;
		// $data['token']=$this->token;
		$data['token']=$this->token;
		$Gdata['GroupId']=$_POST['GroupId'];
		$Gdata['InputName']=$sinfo['userName'];
		$Gdata['InputId']=$sinfo['ID'];
		$Gdata['token']=$this->token;
		$Gdata['stoken']=$this->stoken;
		if ($_POST['id']) {
			$Gdata['LastUpdateDate']=time();
			$data['LastUpdateDate']=time();
			$model=$this->MSL('user');
			$model->startTrans();
			$pres=$model->where('id=%d',$_POST['id'])->save($data);
			// $Gdata['EmployeeId']=$id;
			$res=$this->MSL('usergroup')->where("id=%d",$_POST['id'])->save($Gdata);
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
		$allParts=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->order('id')->select();

		$groups=$this->MSL('groupmanger')->where("token='%s'",$this->token)->select();
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
		$str="token='".$this->token."' and stoken='".$this->stoken."'";
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
	 * 员工提成管理   *******开店暂无权限*********
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
		$this->display();
	}

	/**
	 * 提现处理     *******开店暂无权限*********
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

	public function Scaner(){
		if (IS_POST) {
			
		}else{
			$count=M()->table('RS_Cancel')->where("stoken='%s'",$this->stoken)->count();
			$page=new \Think\Page($count,20);
			$list=M()->table("RS_Cancel")->where("stoken='%s'",$this->stoken)->field("id,username,phone,CONVERT(varchar(20),CreateDate,120) as CreateDate")->limit($page->firstRow.','.$page->listRows)->select();
			$pagedata['lists']=$list;
			$pagedata['page']=$page->show();
			$this->assign($pagedata);
			$this->display();
		}
	}



}













 ?>
