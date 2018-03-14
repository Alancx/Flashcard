<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>订单核销</title>
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
		<style type="text/css">
		*{margin:0;padding:0;list-style:none}body,html{background:#f4f4f4;font-size:14px}body{padding-bottom:55px;padding-top:10px}.order{background:#FFF;margin:0 10px 0 10px;border-top:1px solid #eee;clear:both}.shop{width:100%;height:60px;clear:both;border-bottom:1px solid #eee}.shop-img{width:40px;height:40px;margin:10px;float:left}.shop-img img{width:100%;height:100%}.shop-number{width:calc(100%-60px);float:left;margin-top:10px}.shop-price{width:calc(100%-(100%-60px));float:right;margin-top:10px;margin-right:10px}.jq{margin:0 10px 0 10px;padding:10px 10px 5px 10px;background:#FFF;text-align:left!important}.jq>div{padding-bottom:5px}.jq>div>span:first-child{display:inline-block;width:70px;text-align:right}.money{color:#ff5800;font-size:18px}.footer{width:100%;overflow:hidden}.leftright{position:relative;height:0}.leftright:before{content:"";display:inline-block;width:20px;height:20px;position:absolute;top:-10px;left:0;border-radius:17px;background:#f4f4f4}.leftright:after{content:"";display:inline-block;width:20px;height:20px;position:absolute;right:0;top:-10px;border-radius:17px;background:#f4f4f4}.tail{width:100%;height:50px;background:#FFF;position:fixed;bottom:0;line-height:50px;text-align:center;color:#fff;background:#ff5800}
	</style>

		<script type="text/javascript" charset="utf-8">
			mui.init();
		</script>
</head>
<body>
	<div class="jq">
		<div class="">
			<span>姓名：</span>
			<span><?php echo ($minfo["MemberName"]); ?><small></small></span>
		</div><div class="" style="display:none;">
			<span>电话：</span>
			<span><?php echo ($minfo["Phone"]); ?></span>
		</div>
		<div class="">
			<span>应付款：</span>
			<span>￥<?php echo number_format(($oinfo['Price']),2); ?></span>
		</div>
		<div class="">
			<span>实付款：</span>
			<span class="money">￥<?php echo number_format(($oinfo['Price']),2); ?></span>
			<span class="">(优惠￥<?php echo number_format(($oinfo['DisMoney']),2); ?>)</span>
		</div>
		<div class="" style="display:none;">
			<span>付款时间：</span>
			<span><?php echo ($oinfo["PayDate"]); ?></span>
		</div>
	</div>
	<div class="leftright"></div>
	<div class="order">
		<?php if(is_array($oinfo["sons"])): foreach($oinfo["sons"] as $key=>$son): ?><div class="shop">
			<div class="shop-img">
				<img src="<?php echo ($son["ProLogoImg"]); ?>"/>
			</div>
			<div class="shop-number">
				<?php echo ($son["ProName"]); ?>(<?php echo ($son["Spec"]); ?>)<br />
				x1
			</div>
			<div class="shop-price">
				￥<?php echo number_format(($son['Price']),2); ?>
			</div>
		</div><?php endforeach; endif; ?>
	</div>

	<div class="tail">
		确认核销
	</div>
</body>
<script type="text/javascript" src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript">
var oid ="<?php echo ($oinfo['OrderId']); ?>";
	$(document).ready(function(){
		$(document).on('touchstart','.tail',function(){
			layer.confirm('确定订单无误并核销吗？',{
				btn:['确定','取消'],
				shade:false
			},function(){
				layer.msg('处理中...');
				$.ajax({
					url:"<?php echo U('Base/setorderstatus');?>",
					type:"post",
					data:{'oid':oid},
					dataType:"json",
					complete:function(){

					},
					success:function(msg){
						if (msg.status=='true') {
							setTimeout('closes()',1500);
						  layer.msg('核销成功');
						} else {
							layer.msg('处理失败');
						}

					},
					error:function(e){
            layer.msg('处理失败');
					}
				});
			})
		})
	})
	function closes(){
		WeixinJSBridge.call('closeWindow');
	}
</script>
</html>