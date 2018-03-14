<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Public/home/css/kefu.css" />
	</head>
	<body>
		<div class="Bcontent">
		<ul class="kefuul">
			<li>
				<span>客服电话：</span><span class="phone">400-6800-9609</span>
			</li>
			<li>
				<span>客服邮箱：</span><span>GuangPanKe@163.com</span>
			</li>
			<li>
				<span>光盘客：</span><span>GuangPanKe</span>
			</li>
		</ul>
		</div>
		
	</body>
	<script src="/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/home/js/mui.min.js"></script>
	<script type="text/javascript">
		mui.init()
		mui.plusReady(function() {});
		$(".phone").on("tap", function() {
			var tel = $(this).text();
			mui.confirm("客服电话", tel, ['取消', '联系'], function(e) {
				if(e.index == 1) {
					location.href = "TEL:" + tel;
				}
			}, 'div');

		})
	</script>

</html>