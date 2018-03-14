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
		<script src="/FlashCard/Public/home/js/mui.min.js"></script>
		<link href="/FlashCard/Public/home/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/FlashCard/Public/home/css/gpke_font.css" />
		<script type="text/javascript" charset="utf-8">
			mui.init();
		</script>
		<style>
			html {
				height: 100%;
				position: absolute;
			}
			
			body {
				font-size: 14px;
				height: 100%;
				background: #fff;
			}
			
			ul,
			li {
				padding: 0;
				margin: 0;
				list-style: none;
			}
			
			.img {
				width: 100%;
				height: 125px;
				padding: 10px;
			}
			
			.img img {
				width: 100%;
				height: 100%;
			}
			
			.zhong {
				overflow: hidden;
				background: #FFFFFF;
				padding-bottom: 50px;
			}
			
			.zhong-left {
				border-right: 1px rgb(239, 239, 244) solid;
				width: 50%;
				height: auto;
				float: left;
				padding: 10px 10px 0px 10px;
				position: relative;
				border-bottom: 2px solid #eee;
			}
			
			.zhong-right {
				border-left: 1px rgb(239, 239, 244) solid;
				width: 50%;
				height: auto;
				float: right;
				padding: 10px 10px 0px 10px;
				border-bottom: 2px solid #eee;
			}
			
			.zhong img {
				width: 100%;
				height: 100%;
			}
			
			.li {
				border: 1px rgb(251, 78, 65) solid;
				width: 40px;
				height: 20px;
				color: rgb(251, 78, 65);
				text-align: center;
				line-height: 20px;
			}
			
			.zhe {
				width: 60px;
				height: 20px;
				color: rgb(254, 90, 72);
				text-align: center;
				line-height: 20px;
			}
			
			.price {
				/*border:1px red solid;*/
				width: 60%;
				height: 30px;
				float: left;
				/*padding-left: 10px;*/
				line-height: 30px;
			}
			
			.price span {
				color: rgb(252, 78, 69);
				font-size: 14px;
			}
			
			.price del {
				font-size: 12px;
				color: rgb(153, 153, 153);
			}
			
			.mui-numbox {
				width: 40% !important;
				border: none !important;
				background: none !important;
				/*position: absolute;*/
				/*bottom: 0;*/
				/*right: 0;*/
				height: 25px !important;
				padding: 0 !important;
			}
			
			.mui-numbox .mui-btn {
				width: 20px !important;
				height: 20px !important;
			}
			
			.mui-btn-numbox-minus,
			.mui-btn-numbox-plus {
				border-radius: 100px !important;
				background: none !important;
				border: 1px solid #999 !important;
				width: 25px !important;
				height: 25px !important;
			}
			
			.mui-btn-numbox-plus {
				background: #ff8400 !important;
				border: none !important;
				color: #fff !important;
			}
			
			.mui-numbox .mui-input-numbox {
				border: none !important;
				height: 20px;
			}
			
			.mui-btn-numbox-minus,
			.mui-numbox .mui-input-numbox {
				display: none;
			}
			
			.bottom {
				width: 100%;
				height: 50px;
				overflow: visible;
				position: fixed;
				left: 0px;
				bottom: 0px;
				z-index: 9 !important;
			}
			
			.bottom-left {
				width: 70%;
				height: 50px;
				background: #EFEFF4;
				text-align: right;
				line-height: 50px;
				float: left;
			}
			
			.bottom-left-top {
				width: 100px;
				height: 35px;
				background: #ff8400;
				line-height: 35px;
				text-align: center;
				border-radius: 30px;
				float: left;
				margin: -10px 0px 0px 30px;
				position: relative;
				z-index: 11;
				color: #FFFFFF;
			}
			
			.bottom-left span {
				color: red;
			}
			
			.bottom-right {
				width: 30%;
				height: 50px;
				background: #ff8400;
				color: #FFFFFF;
				line-height: 50px;
				text-align: center;
				font-size: 16px;
				float: right;
			}
			
			.yuan {
				border: 1px #FFFFFF solid;
				width: 30px;
				height: 30px;
				border-radius: 30px;
				margin-top: 2px;
				margin-right: 5px;
				float: right;
				line-height: 30px;
			}
			
			.qw {
				display: none;
				position: fixed;
				background-color: rgba(0, 0, 0, 0.5);
				bottom: 40px;
				left: 0px;
				top: 0px;
				width: 100%;
				z-index: 8;
				overflow: hidden;
			}
			
			.showselprolist {
				display: none;
				position: absolute;
				height: 60%;
				bottom: 0px;
				left: 0px;
				width: 100%;
				background-color: #FFFFFF;
				z-index: 8;
			}
			
			.showselprolist ul {
				overflow: hidden;
			}
			
			.showselprolist ul li {
				padding: 10px;
				overflow: hidden;
				border-bottom: 1px solid #EFEFF4;
			}
			
			.selprotilte {
				display: block;
				height: 41px;
				width: 100%;
				border-bottom: solid 1px #EEEEEE;
			}
			
			.selprotilte>span {
				float: right;
				margin: 0px 10px 0px 0px;
				line-height: 40px;
				height: 40px;
				color: #666666;
			}
			
			.content-div-span {
				text-overflow: ellipsis;
				color: red;
				font-size: 16px;
			}
			
			.content-div-span small {
				color: #999;
			}
			
			.content-img {
				width: 60px;
				height: 60px;
				float: left;
				overflow: hidden;
				margin-right: 4px;
				display: block;
			}
			
			.content-img img {
				height: 100%;
			}
			
			.content-span {
				width: calc(100% - 64px);
				height: 60px;
				float: left;
				overflow: hidden;
				font-size: 12px;
				position: relative;
			}
			
			.content-span span {
				width: 100%;
			}
			
			.le {
				width: 50%;
				float: left;
			}
			
			.bot {
				position: fixed;
				bottom: 0px;
				/*left: 25px;*/
				/*right:10px;*/
				width: 100%;
				height: 55px;
				text-align: center;
				background: #FFFFFF;
			}
			
			.bot li {
				width: 25%;
				height: 50px;
				display: block;
				float: left;
				/*padding-left:15px ;*/
				/*font-size:px*/
			}
			
			.bot li p {
				font-size: 14px;
			}
			
			.bot-ul {
				padding-left: 5px;
				margin-top: 5px;
			}
			
			.bot-ul-li {
				font-weight: 900;
				color: #EC971F;
				color: rgb(255, 78, 0);
			}
			
			.home_icon.bot-ul-li:before {
				content: "\a002";
			}
			
			.huod_icon.bot-ul-li:before {
				content: "\a004";
			}
			
			.order_icon.bot-ul-li:before {
				content: "\a006";
			}
			
			.mine_icon.bot-ul-li:before {
				content: "\a008";
			}
			
			.bot-ul-li>p {
				color: #EC971F;
			}
		</style>
	</head>

	<body>
		<div class="img">
			<img src="/FlashCard/Public/home/img/banner1.jpg" />
		</div>
		<div class="zhong">
		<?php if(is_array($list)): foreach($list as $key=>$li): ?><div class="zhong-left"><img src="<?php echo ($li["ProLogoImg"]); ?>" />
				<span><?php echo ($li["ProName"]); ?></span>
				<p>月售<?php echo ($li["SalesCount"]); ?></p>
				<div class="li">
					力荐
				</div>
				<div class="price">
					<span>￥<?php echo ($li["Sprice"]); ?> </span><del>￥<?php echo ($li["Price"]); ?></del>
				</div>
				<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='10'>
					<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
					<input class="mui-input-numbox" type="number" disabled="" />
					<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
				</div>
			</div><?php endforeach; endif; ?>
		</div>
		<div class="bottom">
			<div class="bottom-left">
				<div class="bottom-left-top ">
					已点
					<div class="yuan">13</div>
				</div>
				共计: <span>￥140　</span>
			</div>
			<div class="bottom-right">
				去结算
			</div>
		</div>
		</div>

		<div class="qw">
			<div class="showselprolist">
				<div class="selprotilte">
					<span>清空菜单</span>

				</div>
				<ul class="content-ull">
					<li>
						<div class="content-img">
							<img src="img/spimg.jpg" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>

							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='10'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>

					<li>
						<div class="content-img">
							<img src="img/spimg.jpg" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>

							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='10'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>

				</ul>
			</div>

	</body>
	<script src="/FlashCard/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script>
		//点击已点
		$('.bottom-left-top ').on('tap', function() {
			if($('.qw').css('display') == 'none') {
				$('.qw').css('display', 'block');
				var bottomheight = $('.showselprolist').height();
				$('.showselprolist').css('bottom', 0 - bottomheight + 'px');
				$('.showselprolist').css('display', 'block');
				$('.showselprolist').animate({
					'bottom': '0px',
				}, 300);
			} else {
				var bottomheight = $('.showselprolist').height();
				$('.showselprolist').animate({
					'bottom': 0 - bottomheight + 'px',
				}, 300, function() {
					$('.qw').css('display', 'none');
				});
			}
		});

		mui('.bot-ul').on('tap', 'li', function() {
			if(!$(this).hasClass('bot-ul-li')) {
				$('.bot-ul-li').removeClass('bot-ul-li');
				$(this).addClass('bot-ul-li');
			}
		});
	</script>
	<script type="text/javascript">
		//加
		$(".mui-btn-numbox-plus").on("tap", function() {
			//				console.log("0")
			var itnum = $(this).parent().children(".mui-input-numbox").val();
			if(itnum > -1) {
				$(this).parent().children(".mui-input-numbox,.mui-btn-numbox-minus").css("display", "block");
			}
		})
		//减
		$(".mui-btn-numbox-minus").on("tap", function() {
			//	console.log("0")
			var itnum = $(this).parent().children(".mui-input-numbox").val();
			if(itnum <= 1) {
				$(this).parent().children(".mui-input-numbox,.mui-btn-numbox-minus").css("display", "");
			}
		})

		//页面跳转

		//商品详情
		$(".zhong>div>img").on("tap", function() {
			mui.openWindow({
				url: "goods.html",
				id: "goods.html",
			})
		})
	</script>

</html>