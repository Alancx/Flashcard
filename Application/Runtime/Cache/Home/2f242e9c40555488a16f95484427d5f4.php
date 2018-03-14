<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<link href="/FlashCard/Public/home/css/mui.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="/FlashCard/Public/home/css/mycenter.css" />
	<link rel="stylesheet" type="text/css" href="/FlashCard/Public/home/css/font_gpke.css" />
</head>
<style type="text/css">
	.bot-ul-li {
		color: #FF4E00 !important;
	}
	
	.bot-ul-li .home_icon:before {
		content: "\a002";
	}
	
	.bot-ul-li .huod_icon:before {
		content: "\a004";
	}
	
	.bot-ul-li .order_icon:before {
		content: "\a006";
	}
	
	.bot-ul-li .mine_icon:before {
		content: "\a008";
	}
</style>
<body>
	<!---->
	<div class="infor">
		<div class="">
			<img src="img/img1.jpg" class="myphoto" />
			<span class="myname">Cui传</span>
		</div>
	</div>

	<div class="">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell myeye">
				<a class="mui-navigate-right">
					<small class="ggg icon_guanzhu"></small>
					<span>我的关注</span>
				</a>
			</li>
			<li class="mui-table-view-cell myshoucang">
				<a class="mui-navigate-right">
					<small class="ggg icon_shoucang"></small>
					<span>我的收藏</span>
				</a>
			</li>
			<li class="mui-table-view-cell myhb">
				<a class="mui-navigate-right">
					<small class="ggg icon_hongbao"></small>
					<span>我的红包</span>
				</a>
			</li>
		</ul>
		<!---->
		<ul class="mui-table-view">

			<li class="mui-table-view-cell kefuhtml">
				<a class="mui-navigate-right">
					<small class="ggg icon_kefu"></small>
					<span>客服中心</span>
				</a>
			</li>
			<li class="mui-table-view-cell prohtml">
				<a class="mui-navigate-right">
					<small class="ggg icon_help"></small>
					<span>问题帮助</span>
				</a>
			</li>
			<li class="mui-table-view-cell xieyihtml">
				<a class="mui-navigate-right">
					<small class="ggg icon_xieyi"></small>
					<span>协议说明</span>
				</a>
			</li>

		</ul>

	</div>
	<nav class="mui-bar mui-bar-tab">
		<a id="defaultTab" class="mui-tab-item " href="index.html">
			<span class="mui-icon ggg home_icon"></span>
			<span class="mui-tab-label">首页</span>
		</a>
		<a class="mui-tab-item" href="activity.html">
			<span class="mui-icon ggg huod_icon"></span>
			<span class="mui-tab-label">活动</span>
		</a>
		<a class="mui-tab-item" href="myorder.html">
			<span class="mui-icon ggg order_icon"></span>
			<span class="mui-tab-label">订单</span>
		</a>
		<a class="mui-tab-item bot-ul-li" href="mycenter.html">
			<span class="mui-icon ggg mine_icon"></span>
			<span class="mui-tab-label">我的</span>
		</a>
	</nav>
	<script src="/FlashCard/Public/home/js/mui.min.js"></script>
	<script src="/FlashCard/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/FlashCard/Public/home/js/mycenter.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		mui.init()
		$(".mui-bar a:first-child").on("tap",function(){
			location.href="<?php echo U('Index/index');?>";
		})
		$(".mui-bar a:nth-child(2)").on("tap",function(){
			location.href="<?php echo U('Index/activity');?>";
		})
		$(".mui-bar a:nth-child(3)").on("tap",function(){
			location.href="<?php echo U('Index/myorder');?>";
		})
		$(".mui-bar a:last-child").on("tap",function(){
			location.href="<?php echo U('Index/mycenter');?>";
		})

	</script>
</body>

</html>