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
	.input-group{
		display: block!important;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;padding-top:20px;">
	<div class="col-sm-12">
		<h5>消息提醒信息设置</h5>
		<form action="" method='post' class='form form-horizontal'>
			
		  <div class="form-group">
		    <label for="" class="col-sm-2 control-label">提现申请消息接收人</label>
		    <div class="col-sm-4">
		      <input type="text" class="form-control" name="withdraw" required id="withdraw" placeholder="请填写正确的手机号" value="<?php echo ($withdraw); ?>">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="" class="col-sm-2 control-label">退款申请消息接收人</label>
		    <div class="col-sm-4">
		      <input type="text" class="form-control" name="refund" required id="refund" placeholder="请填写正确的手机号" value="<?php echo ($refund); ?>">
		    </div>
		  </div>
		  <div class="form-group" style="text-align:center;position: absolute;left: 400px">
		  	<button class="btn btn-primary btn-outline save" type="submit">保 存</button>
		  </div>
		</form>
	</div>
	


</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>