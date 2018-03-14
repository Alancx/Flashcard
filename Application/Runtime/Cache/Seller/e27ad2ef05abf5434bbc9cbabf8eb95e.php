<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>核销员绑定</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
	</head>
	<style type="text/css">
		.mui-input-row {
			background: #fff;

			}
		.mui-input-row>label{
			text-align: right;
			width: 90px !important;
			position: relative;
			color: #666;
		}
		
		.mui-input-row>input{
			width: calc(100% - 90px) !important;
		}	
		.mui-input-row:after {
			position: absolute;
			right: 0;
			bottom: 0;
			left: 15px;
			height: 1px;
			content: '';
			-webkit-transform: scaleY(.5);
			transform: scaleY(.5);
			background-color: #c8c7cc;
		}
		/*.getcode{
			position: absolute;
			right:10px;
			top: 9px;
			color: #ff5800;
		}*/
		nav{
			line-height: 44px;
			text-align: center;
			background: #ff5800 !important;
			color: #fff;
		}
	</style>
	<body>
	    <div class="mui-input-row">
	        <label>姓名</label>
	        <input type="text" class="nickname" placeholder="真实姓名" value="<?php echo ($nickname); ?>" readonly="">
	    </div>
	    <div class="mui-input-row">
	        <label>手机号</label>
	        <input type="text" class="phone" placeholder="手机号">
	    </div>
	    <div class="mui-input-row">
	        <label>验证码</label>
	        <input type="text" class="verify" placeholder="商户验证码">
	        <!--<span class="getcode">获取验证码</span>-->
	    </div>
		
		<nav class="mui-bar mui-bar-footer savecancel">
			立即绑定
		</nav>
		
		<script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
		<script src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
	</body>
<script type="text/javascript">
	var verify="<?php echo ($verify); ?>";
	var stoken="<?php echo ($stoken); ?>";
	var openid="<?php echo ($openid); ?>";
	$(document).on('touchstart','.savecancel',function(){
		var phone=$('.phone').val();
		var newveri=$('.verify').val();
		if (!openid) {
			layer.msg('非法操作');
			setTimeout('closes()',1500);
			return false;
		}else if (!phone) {
			layer.msg('请填写手机号');
			$('.phone').focus();
			return false;
		}else if (!newveri || verify!=newveri) {
			layer.msg('验证码错误');
			$('.verify').focus();
			return false;
		}else{
			layer.msg('处理中');
			$.ajax({
				url:"<?php echo U('Base/saveCancel');?>",
				type:"post",
				data:{stoken:stoken,openid:openid,username:$('.nickname').val(),phone:phone},
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						layer.msg('绑定成功!');
						setTimeout('closes()',1500);
					}else{
						layer.alert(msg.info);
					}
				}
			})
		}
	})
	function closes(){
		WeixinJSBridge.call('closeWindow');
	}
</script>
</html>