<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>提示</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
	</head>
<style type="text/css">
	.errorinfor{
		position: absolute;
		top: 0px;
		left: 0;
		width: 100%;
		height: 200px;
		text-align: center;
		background: url(/Public/newhome/img/backg.png) no-repeat center center;
		background-size:150px auto ;
	}
	.errorinfor div{
		position: absolute;
		bottom: -20px;
		width: 100%;
		color: #FF5800;
	}
</style>
	<body>
		<div class="errorinfor">
			<div><?php echo ($info); ?></div>
		</div>
	</body>

</html>