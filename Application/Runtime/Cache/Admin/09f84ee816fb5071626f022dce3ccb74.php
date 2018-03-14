<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>注册推广人</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="/Public/newhome/css/mui.min.css" />
		<link rel="stylesheet" type="text/css" href="/Public/newhome/css/Setuppeople.css" />
	</head>

	<body>


		<div class="content mui-scroll-wrapper">
			<div class="mui-scroll">

				<form class="mui-input-group" method="post" id="save">
					<div class="mui-input-row">
						<label>姓名：</label>
						<input id="TrueName" name="TrueName" required type="text" placeholder="输入姓名">
					</div>

					<div class="mui-input-row">
						<label>手机号：</label>
						<input id="userName" name="userName" required type="tel" placeholder="输入账号">
					</div>
					<div class="mui-input-row">
						<label>密码：</label>
						<input id="Password" name="Password" required type="password" placeholder="设置密码">
					</div>
					<div class="mui-input-row">
						<label>确认密码：</label>
						<input id="Repass" type="password" placeholder="确认密码">
					</div>
					<div class="mui-input-row">
						<label>邀请码：</label>
						<input id="Invcode" name="Invcode" type="tel" value="<?php echo ($Invcode); ?>" readonly="readonly" placeholder="邀请码选填">
					</div>
					<!-- <div class="mui-input-row">
						<label>手机号：</label>
						<input id="sphone" type="tel" class="mui-input-clear" placeholder="请输入手机号码">
					</div>
					<div id="yzm" class="mui-input-row">
						<label>验证码：</label>
						<input id="scode" type="text" placeholder="请输入验证码">
						<input id="timebtn" type="button" value="获取验证码" />
					</div> -->
				<span id="showtips"></span>
				<button id="RegBtn" class="mui-btn mui-btn-red mui-btn-block" type="submit">立即申请</button>
				</form>
			</div>
		</div>

	</body>
	<script type="text/javascript" charset="utf-8" src="/Public/newhome/js/mui.min.js"></script>
	<script type="text/javascript" src="/Public/newadmin/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
	<script type="text/javascript">
		mui.init()//初始化滚动组件***
		mui('.mui-scroll-wrapper').scroll({
			deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
		});
		$(document).ready(function(){
			$('#userName').blur(function(){
				if (!(/(13\d|14[579]|15[^4\D]|17[^49\D]|18\d)\d{8}/g.test($(this).val()))) {
					layer.alert('手机号码格式错误');
				};
			})
			$('#save').submit(function(){
				var pass=$('#Password').val();
				var repa=$('#Repass').val();
				if (!(/(13\d|14[579]|15[^4\D]|17[^49\D]|18\d)\d{8}/g.test($('#userName').val()))) {
					layer.alert('手机号码格式错误');
					return false;
				}else if (pass==repa && pass && repa) {
					return true;
				}else{
					layer.alert('两次输入密码不一致');
					return false;
				}
			})
		})
	</script>
</html>