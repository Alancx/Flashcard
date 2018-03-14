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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
	.tice{
		color:red;
	}
</style>

<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			</div>
			<div class="ibox-content">
				<div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:180px;border:0px solid red;position:absolute;top:5px;right:5px;">
					<div class="panel">
						<div class="panel-heading">
							<i class="fa fa-warning"></i> 提示信息
						</div>
						<div class="panel-body">
							<div class="alert alert-warning">
								?1、等级从1向后排列，1是最低等级，数字越大代表等级越高<br>
								例： 等级1的条件为0-200，名称为 初来乍到 ，则填写内容一次为 <kbd>1</kbd> <kbd>0</kbd> <kbd>200</kbd> <kbd>初来乍到</kbd>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="ibox float-e-margins">
					<h5>等级设置</h5>
							<form role="form" class="form-inline" method="post" action="">
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">等级</label>
									<input name="Sort" type="number" placeholder="等级" id="Sort" class="form-control" onblur="checks();">
								</div>
								<div class="form-group">
									<div class="input-group">
										<input name="Bot" type="number" placeholder="等级条件/最少(积分)" id="Bot"   class="form-control">
										<div class="input-group-addon">
											至
										</div>
										<input type="number" name="Top" id="Top" placeholder="等级条件/最多(积分)" value="" class="form-control">
									</div>
								</div>
								<br><br>
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">等级名称</label>
									<input name="Name" type="text" placeholder="等级名称" id="Name" class="form-control">
								</div>
								<div class="form-group">
									<div class="input-group">
										<input name="Discount" type="number" placeholder="等级折扣" id="Discount" class="form-control">
										<div class="input-group-addon">%</div>
									</div>
								</div>
									<input type="hidden" name="GradeId" id="GradeId" value="">
									<button class="btn btn-white" type="submit">保存设置</button>
								</form>
						</div>
					</div>
					<table class="table table-bordered">
						<tr>
							<td>等级</td>
							<td>等级名称</td>
							<td>等级条件(积分)</td>
							<td>等级折扣</td>
							<td>操作</td>
						</tr>
						<?php if(is_array($levels)): foreach($levels as $key=>$level): ?><tr>
							<td><?php echo ($level["Sort"]); ?></td>
							<td><?php echo ($level["Name"]); ?></td>
							<td><?php echo ($level["Bot"]); ?>-<?php echo ($level["Top"]); ?></td>
							<td><?php echo ($level['Discount']/10).'折'; ?></td>
							<td><a href="###" onclick="edit(<?php echo ($level["GradeId"]); ?>);">修改 </a>| <a href="###" onclick="del(<?php echo ($level["GradeId"]); ?>);"> 删除</a></td>
						</tr><?php endforeach; endif; ?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var ldata=<?php echo ($ldata); ?>;
	function checks(){
		var sort=$("#Sort").val();
		$.each(ldata,function(index,item){
			if (item.Sort==sort) {
				$("#Sort").val('');
				art.dialog.tips('该等级已存在');
				$('#Sort').focus();
				return false;
			};
		})
	}

	function edit(id){
		$.each(ldata,function(index,item){
			if (item.GradeId==id) {
				$("#Sort").val(item.Sort);
				$("#Bot").val(item.Bot);
				$("#Top").val(item.Top);
				$("#Name").val(item.Name);
				$("#GradeId").val(item.GradeId);
				$("#Discount").val(item.Discount);
			};
		})
	}

	function del(id){
		art.dialog.confirm('确定要删除吗？',function(){
			window.location.href="<?php echo U('Users/delL');?>?id="+id;
		},function(){
			art.dialog.tips('取消操作',1);
		})
	}
	</script>
	</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>