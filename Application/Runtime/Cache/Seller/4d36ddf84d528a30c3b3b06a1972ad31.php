<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>光盘客</title>
    <meta name="keywords" content="徽记食品">
    <meta name="description" content="徽记食品">
    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    <script src="/Public/Admin/Admin/js/hplus.js"></script>

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>

<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>员工管理</h5>
	</div>
	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="<?php echo U('Admin/search');?>" class="form-inline" method="post" id="search">
			<div class="form-group">
				<input type="text" name="EmployeeId" placeholder="请填写员工账号" id="EmployeeId" class="form-control">
			</div>
			<div class="form-group">
				<input type="text" name="TrueName" id="TrueName" class="form-control" placeholder="请填写真实姓名(选填)">
			</div>
			<button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button> <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span>导出员工信息</button>
		</form>

	</div>
	<div class="col-sm-10 col-sm-offset-1">
		<?php if($employees['msg']): ?><h3><?php echo ($employees["msg"]); ?></h3>
		<?php else: ?>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>账号</th>
					<th>真实姓名</th>
					<th>性别</th>
					<th>录入人ID</th>
					<th>录入人姓名</th>
					<th>备注</th>
					<th>录入时间</th>
					<th>最后登录时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($employees)): foreach($employees as $key=>$emp): ?><tr>
						<td><?php echo ($emp["id"]); ?></td>
						<td><a href="###" onclick="getQr('<?php echo ($emp["EmployeeId"]); ?>');"><?php echo ($emp["userName"]); ?></a></td>
						<td><?php echo ($emp["TrueName"]); ?></td>
						<td><?php if($emp['Sex'] == '0'): ?>保密<?php endif; if($emp['Sex'] == '1'): ?>男<?php endif; if($emp['Sex'] == '2'): ?>女<?php endif; ?></td>
						<td><?php echo ($emp["InputId"]); ?></td>
						<td><?php echo ($emp["InputName"]); ?></td>
						<td><?php echo ($emp["Remarks"]); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$emp["CreateDate"])); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$emp["LastLoginDate"])); ?></td>
						<td><a href="###" onclick="edit('<?php echo ($emp["id"]); ?>')">编辑</a> | <a href="###" onclick="del('<?php echo ($emp["id"]); ?>');">删除</a></td>
					</tr><?php endforeach; endif; ?>
			</tbody>

		</table><?php endif; ?>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<script type="text/javascript">
	function edit(id){
		window.location.href="<?php echo U('Admin/edit');?>?id="+id;
	}

	function del(id){
		art.dialog.confirm('确定要删除吗？请慎重操作', function () {
			window.location.href="<?php echo U('Admin/del');?>?id="+id;
			// alert('hahaha')
		}, function () {
			art.dialog.tips('取消操作');
		});
	}

	function getQr(id){
		art.dialog.open("<?php echo U('ArtDialog/AQr');?>?mid="+id);
	}
	$(document).ready(function(){
		$("#search").submit(function(){
			var EmployeeId=$("#EmployeeId").val();
			var TrueName=$("#TrueName").val();
			var DepartmentName=$("#DepartmentName").val();
			if (!EmployeeId && !TrueName && !DepartmentName) {
				art.dialog.alert('请输入您要查询的信息');
				return false;
			}else{
				art.dialog({content:'正在查询....',lock:true});
				return true;
			}
		})
		$('#import').click(function(){
			var EmployeeId=$("#EmployeeId").val();
			var TrueName=$("#TrueName").val();
			var DepartmentName=$("#DepartmentName").val();
			// if (!EmployeeId && !TrueName && !DepartmentName) {
			// 	art.dialog.alert('请输入您要查询的信息');
			// 	return false;
			// }else{
				art.dialog.tips('正在处理...',3);
				window.location.href="<?php echo U('Admin/empOut');?>?EmployeeId="+EmployeeId+"&TrueName="+TrueName+"&DepartmentName="+DepartmentName;
				return true;
			// }
		})
	})

</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>