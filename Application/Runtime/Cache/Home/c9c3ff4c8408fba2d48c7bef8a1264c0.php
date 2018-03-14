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
		<title>商品详情</title>
		<script src="/Public/home/js/mui.min.js"></script>
		<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Public/home/css/font_gpke.css" />
		<link rel="stylesheet" href="/Public/home/css/goods.css" />
		<script type="text/javascript" charset="utf-8">
			mui.init();
		</script>

	</head>

	<body>
		<div class="pic">
			<img src="<?php echo ($list["ProLogoImg"]); ?>" />
		</div>
		<div class="head">
			<div class="head-name"><span class="name"><?php echo ($pinfo["ProName"]); ?></span>
				<span class="number">月售&nbsp;<?php echo ($pinfo["SalesCount"]); ?></span>
				<span class="li">力荐</span>
			</div>

			<div class="head-bottom">
				<div class="price">
					<span>￥<?php echo ($pinfo["Price"]); ?> </span><del>￥<?php echo ($pinfo["PriceRange"]); ?></del>
				</div>
				<div class="gouwu">
					<span class="">+</span>
				</div>
				<span class="sc">收藏</span>
			</div>
		</div>
		<div class="xian">
			<div class="xian-lv">
				鲜
			</div>
			<span class="mui-ellipsis">食材绿色健康，秘制工艺，独家口感红烧肉！！</span>
		</div>
		<div class="liang">
			<div class="liang-lv">
				靓
			</div>
			<span class="mui-ellipsis">口碑商家，菜品量大分足，绝对实惠！</span>
		</div>
		<div class="logo">
			<div class="logo-img">
				<img src="/Public/home/img/5.png" data-url="<?php echo U('Index/index');?>"/>
			</div>
			<div class="logo-zhong" >
				<sapn >百乐川烤鱼店</sapn>
			</div>
			<div class="follow">
				13968人关注
			</div>
		</div>
		<div class="pingjia">
			<div class="evaluate">
				<div class="evaluate-text">
					外卖评价
					<span>(好评度<span>96%</span>)</span>
				</div>
				<div class="evaluate-number">
					1256条评论
				</div>
			</div>
			<!--评价-->
			<div class="pjdiv">
				<div class="">
					<!--1-->
					<ul class="pjitem">
						<li>
							<img src="/Public/home/img/4.jpg" class="idphoto" />
						</li>
						<li>
							<div class="idname">
								<span>紫御琳琳</span>
								<div>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>

									<small class="mui-pull-right">2017.11.8</small>
								</div>
							</div>
							<div class="idtext">
								环境挺好，火锅也挺好，味道美，分量足，还会再来的。
							</div>
							<div class="idimg">
								<img src="/Public/home/img/4.jpg" />
								<img src="/Public/home/img/4.jpg" />
								<img src="/Public/home/img/4.jpg" />
							</div>
						</li>
					</ul>
					<!--2-->
					<ul class="pjitem">
						<li>
							<img src="/Public/home/img/4.jpg" class="idphoto" />
						</li>
						<li>
							<div class="idname">
								<span>紫御琳琳</span>
								<div>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>
									<small class="ggg xing_icon"></small>

									<small class="mui-pull-right">2017.11.8</small>
								</div>
							</div>
							<div class="idtext">
								环境挺好，火锅也挺好，味道美，分量足，还会再来的。
							</div>
							<div class="idimg">
								<img src="/Public/home/img/4.jpg" />
								<img src="/Public/home/img/4.jpg" />
								<img src="/Public/home/img/4.jpg" />
								<img src="/Public/home/img/4.jpg" />
							</div>
						</li>
					</ul>
				</div>
			</div>

		</div>
		<div class="bottom">
			<div class="bottom-left">
				<div class="bottom-left-top ">
					已点
					<!-- <div class="yuan">13</div> -->
				</div>
				共计: <span>￥140　</span>
			</div>
			<div class="bottom-right gobuy" data-url="<?php echo U('Submit/index');?>">
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
							<img src="/Public/home/img/spimg.jpg" />
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
							<img src="/Public/home/img/spimg.jpg" />
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
		</div>

	</body>
	<script src="/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		$(document).on("tap",".sc",function(){
//			alert("aaa");
			$(this).toggleClass("aa");
			var ittext = $(this).text();
			console.log(ittext);
			if(ittext=="收藏") {
			    $(this).text("已收藏");
			} else {
			    $(this).text("收藏");
			}
		})
	</script>
	<script type="text/javascript">
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
		
		// $(document).on('tap','.logo',function(){
		// 	window.location.href="index.html";
		// })
		
		//去结算
		$(".gobuy").on("tap",function(){
		//	mui.openWindow({
		//		url:"SubmitOrder.html",
		//		id:"SubmitOrder.html",
		//	})
			window.location.href = $(this).attr('data-url');
		})

		$(".logo-img img").on("tap",function() {
			window.location.href = $(this).attr('data-url');
			// console.log($(this).attr('data-url'));//打印测试
		})
		
		
	</script>

</html>