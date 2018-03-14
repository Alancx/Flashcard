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
		<title>提交订单</title>
		<script src="/Public/home/js/mui.min.js"></script>
		<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Public/home/css/font_gpke.css" />
		<link rel="stylesheet" href="/Public/home/css/detail.css" />
		<script type="text/javascript" charset="utf-8">
			mui.init();
		</script>
</head>
<body>
	<div class="order">
		<div class="order-top">
			<div class="order-top-img">
				<img src="/Public/home/img//5.png"/>
			</div>
			<div class="order-top-text mui-ellipsis">
				豫川通汇麻辣香锅豫川通汇麻辣香锅豫川通汇麻辣香锅（黄河路店）
			</div>
			<div class="order-top-p">
				<p class="mui-icon mui-icon-arrowright"></p>
			</div>
		</div>

		<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="shop">
				<div class="shop-img">
					<img src="<?php echo ($vo["ProLogoImg"]); ?>"/>
				</div>
				<div class="shop-number">
					<?php echo ($vo["ProName"]); ?><br />
					x<?php echo ($vo["Num"]); ?>
				</div>
				<div class="shop-price">
					￥<?php echo ($vo["Price"]); ?>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>




		
	</div>
	
	<div class="jq">
		<?php if($length < 3): ?><div class="morelist" hidden>
				展开更多
				<span class="mui-icon mui-icon-arrowdown"></span>
			</div>
			<?php else: ?>
			<div class="morelist">
				展开更多
				<span class="mui-icon mui-icon-arrowdown"></span>
			</div><?php endif; ?>
		<div class="footer">
			<div class="tableware">
				餐具费
			</div>
			<div class="price">
				￥1
			</div>
		</div>
		<div class="jian" hidden>
			<small class="jiansmall">减</small>
			<span class="jian-text">
				满减优惠
			</span>
			<span class="jian-price">
				-￥15
			</span>
		</div>
		<div class="left"></div>
		<div class="right"></div>
		<div class="total">
				合计<span>￥<?php echo ($vo["total"]); ?></span>
		</div>
		<div class="left"></div>
		<div class="right"></div>
		<div class="eat">
			<div class="eat-people">电话商家</div>
		</div>
		
	</div>
	<div class="tail">
		<span>核销码</span>
		<span>评价</span>
		<span>退款</span>
		
		
	</div>
</body>
<script src="/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	$(".morelist").on("tap",function(){
		$(".order").toggleClass("sl");
	})
</script>
</html>