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
				<table class="table table-bordered">
					<thead>
						<tr>
							<td>等级名称</td>
							<td>佣金比例 <small>按扣点比例的百分比计算 <b>扣点6%时填写50即从6%中提取50%</b></small></td>
							<td>操作</td>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($data)): foreach($data as $key=>$da): ?><tr>
							<td><?php echo ($da["LevelName"]); ?></td>
							<td><div class="input-group"><input type="text" name="" value="<?php echo ($da["LevelCut"]); ?>" class="form-control" id="cut<?php echo ($da["ID"]); ?>"><span class='input-group-addon'>%</span></div></td>
							<td><button class="btn btn-xs btn-primary saveset" data-id='<?php echo ($da["ID"]); ?>'>保存</button></td>
						</tr><?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//验证
	$(document).ready(function(){
		$(document).on('click','.saveset',function(){
			var id=$(this).attr('data-id');
			var cut=$('#cut'+id).val();
			if (id>0 && id<100) {
				art.dialog.tips('处理中...');
				$.ajax({
					url:"<?php echo U('Admin/LevelSet');?>",
					type:'post',
					data:"id="+id+"&cut="+cut,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							art.dialog.tips('保存成功');
						}else{
							art.dialog.tips(msg.info);
						}
					}
				})
			}else{
				art.dialog.alert('请填写合法的佣金比例');
			}
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>