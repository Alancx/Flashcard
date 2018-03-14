<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>徽记食品</title>
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
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<style type="text/css">
	td{
		text-align: center;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>配送订单信息</h5>
	</div>
	<div class="col-sm-12 col-md-12"  style="padding-bottom:10px;">
		<form class="form-inline" method="post">
		  <div class="form-group">
		    <label for="exampleInputName2">订单号</label>
		    <input type="text" class="form-control" name="OrderId" id="exampleInputName2" value="<?php echo ($param['OrderId']); ?>" placeholder="订单号">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputEmail2">配送员名/手机号</label>
		    <input type="text" class="form-control" id="exampleInputEmail2" name='Psinfo' value="<?php echo ($param['Psinfo']); ?>" placeholder="配送员名/手机号">
		  </div>
		  <button type="submit" class="btn btn-default">搜 索</button>
		</form>
	</div>
	<div class="col-sm-12 col-md-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>订单号</th>
					<th>抢单时间</th>
					<th>收货信息</th>
					<th>配送员信息</th>
					<th>当前状态</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($order)): foreach($order as $key=>$or): ?><tr>
						<td><?php echo ($or["OrderId"]); ?></td>
						<td><?php echo ($or["GetDate"]); ?></td>
						<td><?php echo ($or["Receving"]); ?></td>
						<td><?php echo ($or["PS"]); ?></td>
						<td><?php if($or['Status'] == '2'): ?><span style="color:green;font-weight:bold">配送完成</span><br><span style='color:green'><?php echo ($or["OverDate"]); ?></span><?php elseif($or['Status'] == '1'): ?><span style='color:orange;font-weight:bold'>配送中(已提货)</span><?php else: ?>等待取货 <br> <button class="btn btn-warning btn-xs resend" type="button" data-oid='<?php echo ($or["OrderId"]); ?>' title="用于配送员抢单长时间未取货时使用" alt="用于配送员抢单长时间未取货时使用">重新发送抢单信息</button><?php endif; ?></td>
					</tr><?php endforeach; endif; ?>
			</tbody>

		</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.resend').click(function(){
		var _this=$(this);
		art.dialog.confirm("确定要重新发布抢单信息吗？",function(){
			_this.html('处理中...').addClass('disabled');
			$.ajax({
				url:"<?php echo U('Store/resend');?>",
				type:"post",
				data:"oid="+_this.attr('data-oid'),
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						art.dialog.tips('处理成功');
						_this.parent().parent().remove();
					}else{
						_this.html('重新发送抢单信息').removeClass('disabled');
						art.dialog.tips('处理失败');
					}
				}
			})
		})
	})
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>