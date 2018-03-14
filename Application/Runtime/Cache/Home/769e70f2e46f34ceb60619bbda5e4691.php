<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>


	<meta charset="utf-8">
	<title>首页</title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" type="text/css" href="/FlashCard/Public/css/mui.min.css" />
	<link rel="stylesheet" type="text/css" href="/FlashCard/Public/css/index.css?v=1" />
	<link rel="stylesheet" type="text/css" href="/FlashCard/Public/css/font_gpke.css" />
	<style>
		html,
		body {
			background-color: #efeff4;
		}

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


</head>
<body>


<div class="head">
	<div class="shopinfor">
		<img src="/FlashCard/Public/img/5.png" class="shoplogo" />
		<div class="">
			<div class="shopname mui-ellipsis">百乐川烤鱼百乐川烤鱼百乐川烤鱼</div>
			<span class="shopphone">400-6800-3600</span>
		</div>
	</div>
	<div class="icons">
				<span>
					<small class="ggg address_icon"></small>
					<span>导航</span>
				</span>
				<span>
					<small class="ggg xin_icon"></small>
					<span>关注</span>
				</span>
	</div>
	<div class="text mui-ellipsis">
		金水区文化路农业路河南农业大学对面1209号对面1209号对面1209号
	</div>
	<img src="" class="bgimg" />
</div>
<!--领红包-->
<div class="hongbao">
	<img src="/FlashCard/Public/img/xiaohb1.png" />
	<div class="">
		<div class="hb lingwan"><span>10</span></div>
		<div class="hb"><span>5</span></div>
		<div class="hb"><span>2</span></div>
		<div class="hb"><span>1</span></div>
		<div class="hb"><span>0.5</span></div>
	</div>
</div>
<!--特色菜  特价菜-->
<div class="tdiv">
	<div class="tj">
		<span>特价</span>
		<img src="/FlashCard/Public/img/4.jpg" />
	</div>
	<div class="ts">
		<span>特色</span>
		<img src="/FlashCard/Public/img/4.jpg" />
	</div>
</div>

<!--点菜  评论  商家-->
<div id="outer" class="tabdiv">
	<div id="center">
		<ul id="tab">
			<li class="current" data-index='0'><span>点菜</span></li>
			<li class="" data-index='1'><span>评价999+</span></li>
			<li data-index='2'><span>商家</span></li>
		</ul>
	</div>
</div>


		<!--内容区-->
		<div class="contdiv" style="margin-bottom:100px;">
			<div class="posidiv">
				<!--点菜-->
				<div class="dcdiv" data-oo="tabi">
					<!--联动选项卡组-->
					<div class="ldiv">
						<ul>
							<?php $i=1; ?>
							<?php if(is_array($allpros)): foreach($allpros as $key=>$ap): ?><li class="mui-ellipsis-2 <?php if($i == 1): ?>actives<?php endif; ?>"><?php echo ($ap["ClassName"]); ?></li>
							<?php $i++; endforeach; endif; ?>
						</ul>
					</div>

					<div class="rdiv">
						<ul>
						<?php if(is_array($allpros)): foreach($allpros as $key=>$pro): ?><li >
								<div class="class-title"><span><?php echo ($pro["ClassName"]); ?></span></div>
								<?php if(is_array($pro["pros"])): foreach($pro["pros"] as $key=>$po): ?><div class="spitem">
									<img src="<?php echo ($po["ProLogoImg"]); ?>" />
									<div>
										<p class="mui-ellipsis"><?php echo ($po["ProName"]); ?></p>
										<div class="">
											<span><span class="jiage"><?php echo ($po["Price"]); ?></span ><span class="fubiao">/≈3斤</span></span>
											<p>月售<?php echo ($po["SaleCount"]); ?></p>
										</div>
										<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
											<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
											<input class="mui-input-numbox" type="number" disabled="" />
											<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
										</div>
									</div>
								</div><?php endforeach; endif; ?>
							</li><?php endforeach; endif; ?>
						</ul>
					</div>
				</div>
				<!--评价-->
				<div class="pjdiv" data-oo="tabi" hidden="">
					<div class="">
						<!--1-->
						<ul class="pjitem">
							<li>
								<img src="/FlashCard/Public/img/4.jpg" class="idphoto" />
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
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
								</div>
							</li>
						</ul>
						<!--2-->
						<ul class="pjitem">
							<li>
								<img src="/FlashCard/Public/img/4.jpg" class="idphoto" />
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
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!--商家-->
				<div class="sjdiv" data-oo="tabi" hidden="">
					<div class="">
						<!--地址电话介绍图-->
						<div class="">
							<div class="dizhi">
								<small class="ggg address_icon"></small>
								<span><?php echo ($sinfo["province"]); echo ($sinfo["city"]); echo ($sinfo["area"]); echo ($sinfo["addr"]); ?></span>
								<small class="ggg phone_icon"></small>
							</div>
							
							<div class="shopimg">
								<div class="">
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
								</div>
							</div>
						</div>
						
						<div style="padding: 10px;">商家评价 1236</div>
						<!--1-->
						<ul class="pjitem">
							<li>
								<img src="/FlashCard/Public/img/4.jpg" class="idphoto" />
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
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
								</div>
							</li>
						</ul>
						<!--2-->
						<ul class="pjitem">
							<li>
								<img src="/FlashCard/Public/img/4.jpg" class="idphoto" />
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
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
									<img src="/FlashCard/Public/img/4.jpg" />
								</div>
							</li>
						</ul>
					</div>
					
				</div>

			</div>
		</div>

		<!--底部结算栏-->
		<div id="yidian" class="bottom-left-top ">
			<small>已点</small>
			<small>9+</small>
		</div>
		<div class="qw">
			<div class="showselprolist">
				<div class="selprotilte">
					<span>清空菜单</span>
					<span class="gobuy">去结算</span>
					<span class="buymoney">￥144.5</span>
				</div>
				<ul class="content-ull">
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
					<li>
						<div class="content-img">
							<img src="/FlashCard/Public/img/5.png" />
						</div>
						<div class="content-span">
							<div class="content-ul-span mui-ellipsis">只做正宗川式烤鱼要吃就吃正宗只做正宗川式烤鱼要吃就吃正宗</div>
							<div class="le">
								<div class="content-div-span">￥140</div>
							</div>
							<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='100'>
								<button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
								<input class="mui-input-numbox" type="number" disabled="" />
								<button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>


    <nav class="mui-bar mui-bar-tab">
        <a id="defaultTab" class="mui-tab-item bot-ul-li" href="index.html">
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
        <a class="mui-tab-item" href="mycenter.html">
            <span class="mui-icon ggg mine_icon"></span>
            <span class="mui-tab-label">我的</span>
        </a>
    </nav>


</body>

	<script src="/FlashCard/Public/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/FlashCard/Public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/FlashCard/Public/js/index.js?v=1.1" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		mui.init();
	</script>
<script type="text/javascript">
		/*移动*/
	var startX = 0,
		startY = 0,
		endX = 0,
		endY = 0,
		isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),
		hasTouch = 'ontouchstart' in window && !isTouchPad;
	var divstarttop;
	var divstarleft;

	document.getElementById('yidian').addEventListener('touchstart', function(e) {
		var even = typeof event == "undefined" ? e : event;
		startX = hasTouch ? even.touches[0].pageX : even.pageX;
		startY = hasTouch ? even.touches[0].pageY : even.pageY;
		divstarttop = startY - this.offsetTop;
		divstarleft = startX - this.offsetLeft;
	});
	document.getElementById('yidian').addEventListener('touchmove', function(e) {
		var even = typeof event == "undefined" ? e : event;
		endX = hasTouch ? even.touches[0].pageX : even.pageX;
		endY = hasTouch ? even.touches[0].pageY : even.pageY;
		even.preventDefault();
		var divleft = endX - divstarleft;
		var divtop = endY - divstarttop;
		this.style.left = divleft + 'px';
		this.style.top = divtop + 'px';		
	});
	document.getElementById('yidian').addEventListener('touchend', function(e) {	
		if((this.offsetLeft + this.offsetWidth + 10) > document.documentElement.clientWidth){
			this.style.left = document.documentElement.clientWidth - this.offsetWidth - 10 + 'px';
		};
		if(this.offsetLeft < 10 ){
			this.style.left = 10 + 'px';
		};
		if((this.offsetTop + this.offsetHeight + 10) > document.documentElement.clientHeight){
			this.style.top = document.documentElement.clientHeight - this.offsetHeight - 10 + 'px';
		};
		if(this.offsetTop < 10 ){
			this.style.top = 10 + 'px';
		};
	});
	</script>
	<script type="text/javascript">
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
		//点击特价、特色
		$(".tdiv div").on("tap",function(){
			location.href="<?php echo U('Index/sprice');?>";
		})
	</script>

</html>