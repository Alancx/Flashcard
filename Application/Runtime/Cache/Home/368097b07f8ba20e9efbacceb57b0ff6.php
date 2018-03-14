<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>支付</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Public/home/css/zhifu.css" />
		<link rel="stylesheet" type="text/css" href="/Public/home/css/font_gpke.css"/>
	</head>
	<body>
		
		<div class="Bcontent">
			<img src="/Public/home/img/banner1.jpg" width="100%" />
			<!--剩余时间-->
			<div class="yutime">
				<small>支付剩余时间：</small>
				<div class="time-item">
					<span id="hour_show">04</span>：
					<span id="minute_show">00</span>：
					<span id="second_show">00</span>
				</div>
			</div>
			<!--支付金额-->
				<div class="zhifujin">
					<small>支付金额:</small>
					<div class="jiage">
						<?php echo ($total); ?>
					</div>
				</div>
			<!--支付方式-->
			<ul class="zhifufangshi">
				<li>
					<small class="Bspa Bspaweinxin fangshi"></small>
					<span>微信支付</span>
					<small class="ggg unchecked_icon"></small>
				</li>
				<li>
					<small class="Bspa Bspazhifubao fangshi"></small>
					<span>支付宝支付</span>
					<small class="ggg unchecked_icon"></small>
				</li>
			</ul>
		</div>
		<!--确认支付-->
		<nav class="truezhifu">
			<small class="aa">支付方式</small>
			<span><?php echo ($total); ?></span>
			<small>元</small>
		</nav>
	</body>
	<script src="/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/home/js/mui.min.js"></script>
	<script src="/Public/home/js/zhifu.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		mui.init()
	</script>
</html>