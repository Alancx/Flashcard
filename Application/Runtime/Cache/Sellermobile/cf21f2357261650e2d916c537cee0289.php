<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
	<title><?php echo ($Title); ?></title>
	<link href="/Public/Sellermobile/CSS/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/Sellermobile/CSS/weui.css" rel="stylesheet">
	<script src="/Public/Sellermobile/JS/jquery.min.js"></script>
	<script src="/Public/Sellermobile/JS/bootstrap.min.js"></script>
	<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="/Public/Sellermobile/JS/base.js"></script>
	<link rel="stylesheet" href="/Public/Sellermobile/CSS/pagemodel.css?v=1.0" media="screen" title="no title" charset="utf-8">

   <!-- 微信分享 -->
	 <script type="text/javascript">
	 var wxJSSDKConfig = <?php echo ($wxJSSDKConfigStr); ?>;
	 wx.config(wxJSSDKConfig);

	 wx.ready(function (a) {

	   wx.hideAllNonBaseMenuItem();

    <?php echo ($showlist); ?>
	  //  wx.showMenuItems({
	  //    menuList: ["menuItem:share:appMessage","menuItem:share:timeline"] // 要显示的菜单项，所有menu项见附录3
	  //  });
	   wx.onMenuShareAppMessage({
	     title: '<?php echo ($shopname); ?>',
	     desc: '<?php echo ($shopdesc); ?>',
	     link: '<?php echo ($shareUrl); ?>',
	     imgUrl: '<?php echo ($shareImg); ?>',
	     trigger: function (res) {

	     },
	     success: function (res) {
	       alert('已分享');
	     },
	     cancel: function (res) {
	       alert('已取消');
	     },
	     fail: function (res) {
	       alert(JSON.stringify(res));
	     }
	   });

	   wx.onMenuShareTimeline({
	     title: '<?php echo ($Title); ?>',
	     link: '<?php echo ($shareUrl); ?>',
	     imgUrl: '<?php echo ($shareImg); ?>',
	     trigger: function (res) {

	     },
	     success: function (res) {
	       alert('已分享');
	     },
	     cancel: function (res) {
	       alert('已取消');
	     },
	     fail: function (res) {
	       alert(JSON.stringify(res));
	     }
	   });
	 });
	 wx.error(function (res) {
	   // alert(res);
	 });
	 </script>

</head>
<body>
	<!-- 正文显示区域 -->
	<div class="container">
		
<script type="text/javascript" src="/Public/Sellermobile/js/echarts.min.js"></script>
<link rel="stylesheet" href="/Public/Sellermobile/css/Index.css?v=1.3">
<div class="indexcontent">
	<div class="part allcash">
		<div>
			<label class="cashtype" style="display:none;">现金收款<span><?php echo $cashmoney?sprintf("%.2f",$cashmoney):'0.00' ?>(元)<span></label>
			</div>
			<div>
				<label class="wxchattype">微信收款
					<div>
						<label><?php echo $wxmoney?sprintf("%.2f",$wxmoney):'0.00' ?>(元)</label>
						<a href="<?php echo U('Orders/incompleteorders');?>">
							<label><span>未完成</span><?php echo $wxhasnomoney?sprintf("%.2f",$wxhasnomoney):'0.00' ?>(元)</label>
						</a>
					</div>
				</label>
				<div class="wxcashinfo">
					<div>
						<label><?php echo $wxhasmoney?sprintf("%.2f",$wxhasmoney):'0.00' ?>(元)</label>
						<label>已完成</label>
					</div>
					<div>
						<label><?php echo $wxhasjsmoney?sprintf("%.2f",$wxhasjsmoney):'0.00' ?>(元)</label>
						<label>已结算</label>
					</div>
					<div>
						<label><?php echo $wxnojsmoney?sprintf("%.2f",$wxnojsmoney):'0.00' ?>(元)</label>
						<label>未结算</label>
					</div>
				</div>
			</div>
		</div>
	<div class="part topherd">
		<div class="sumtop">
			<label class="sumprice">近30天营业情况<br><span>收入总额<?php echo $avgprice?sprintf("%.2f",$avgprice):'0.00' ?>元<span></label>
			<!-- <label class="svgprice">最高日收入<?php echo ($maxev[dsum]); ?>元<br>最低日收入<?php echo ($minev[dsum]); ?>元</label> -->
		</div>
		<div id="topcharts">

		</div>
		<div class="revtop">
			<!-- <label class="revtop_item">今日访问量(<span>2000</span>人)</label> -->
			<label class="revtop_item">今日收入(<span><?php echo $todayprice?sprintf("%.2f",$todayprice):'0.00' ?></span>元)</label>
			<label class="revtop_item">今日订单数(<span><?php echo $todayorder?sprintf("%.0f",$todayorder):'0' ?></span>单)</label>
		</div>
	</div>
	<div class="part centerenv">
		<div class="sumcenter">
			<label class="sumprice">今日营业收入情况<br><span>收入总额<?php echo $todayprice?sprintf("%.2f",$todayprice):'0.00' ?>元<span></label>
			<!-- <label class="svgprice">最高日访客3600人<br>最低日访客2800人</label> -->
		</div>
		<div id="centercharts">

		</div>
	</div>
	<div class="part bottomenv">
		<div class="addpro">
			<label class="add_pro" data-url="<?php echo U('Products/proedit');?>"><span>添加商品</span></label>
		</div>
		<div class="botmenu">
			<div class="bot_menu">
				<a href="<?php echo U('Orders/index');?>">
					<img src="/Public/Sellermobile/icon/order_icon.png" alt="">
					<label>订单管理</label>
				</a>
			</div>
			<!-- <div class="bot_menu">
				<a href="<?php echo U('UMWareHouse/stockquery');?>">
					<img src="/Public/Sellermobile/icon/ck_icon.png" alt="">
					<label>库存管理</label>
				</a>
			</div> -->
			<div class="bot_menu">
				<a href="<?php echo U('Staff/ordercut');?>">
					<img src="/Public/Sellermobile/icon/ck_icon.png" alt="">
					<label>订单结算</label>
				</a>
			</div>
			<div class="bot_menu">
				<a href="<?php echo U('Staff/Staff');?>">
					<img src="/Public/Sellermobile/icon/kc_icon.png" alt="">
					<label>账户提现</label>
				</a>
			</div>
			<div class="bot_menu">
				<a href="<?php echo U('Record/record');?>">
					<img src="/Public/Sellermobile/icon/svg_icon.png" alt="">
					<label>数据统计</label>
				</a>
			</div>
			<div class="bot_menu">
				<a href="<?php echo U('Envelopes/index');?>">
					<img src="/Public/Sellermobile/icon/js_icon.png" alt="">
					<label>红包设置</label>
				</a>
			</div>
			<div class="bot_menu">
				<a href="<?php echo U('Cheap/index');?>">
					<img src="/Public/Sellermobile/icon/ps_icon.png" alt="">
					<label>越吃越便宜</label>
				</a>
			</div>
		</div>
	</div>
	<div class="converaddpro">
		<div>
			<div class="selpro">
				<div>
					<a href="<?php echo U('Products/Factorypro');?>">
					<img src="/Public/Sellermobile/icon/add_factory.png" alt="">
					<label>工厂选货</label>
				</a>
				</div>
				<div>
					<a href="<?php echo U('Products/proedit');?>">
					<img src="/Public/Sellermobile/icon/add_self.png" alt="">
					<label>自营商品</label>
				</a>
				</div>
			</div>
			<div class="cancelpro">取消</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//////三十天的数据情况////////////
var dataday=<?php echo ($dataday); ?>;
var daymon=<?php echo ($daymon); ?>;
///////今日数据情况////////
var odataday=<?php echo ($odataday); ?>;
var odaymon=<?php echo ($odaymon); ?>;
</script>
<script src="/Public/Sellermobile/js/Index.js?v=1.2" charset="utf-8"></script>

	</div>

	<!-- 底部导航栏 -->
	<?php if($footerSign == 1): ?><div style="height:50px"></div>
		<div class="footer">
			<div>
				<a href="<?php echo U('Index/Index');?>">
					<?php if (CONTROLLER_NAME=='Index' && ACTION_NAME=='Index'): ?>
					<img src="/Public/Sellermobile/icon/shop-act.png">
					<label style="color:#ff3e30">店铺</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/shop.png">
					<label>店铺</label>
				<?php endif; ?>
				</a>
			</div>
			<div>
				<a href="<?php echo U('Products/prolist');?>">
					<?php if (CONTROLLER_NAME=='Products' && ACTION_NAME=='prolist'): ?>
						<img src="/Public/Sellermobile/icon/product-act.png">
						<label style="color:#ff3e30">商品</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/product.png">
					<label>商品</label>
				<?php endif; ?>
				</a>
			</div>
			<div>
				<a>
					<img src="/Public/Sellermobile/icon/active.png">
					<label>动态</label>
				</a>
			</div>
			<div>
				<a href="<?php echo U('User/Index');?>">
					<?php if (CONTROLLER_NAME=='User' && ACTION_NAME=='Index'): ?>
					<img src="/Public/Sellermobile/icon/center-act.png">
					<label style="color:#ff3e30">我的</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/center.png">
					<label>我的</label>
				<?php endif; ?>
				</a>
				<!-- <a>
					<img src="/Public/Sellermobile/icon/center.png">
					<label>我的</label>
				</a> -->
			</div>
		</div><?php endif; ?>
	<!-- weui提示框 -->
	<div id="notice" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content"></p>
		</div>
	</div>

	<div class="weui_dialog_confirm" id="confirm" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">操作提示</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog default" id="esc">取消</a>
				<a href="javascript:;" class="weui_btn_dialog primary" id="enter" data-s="" data-idcard=''>确定</a>
			</div>
		</div>
	</div>


	<div class="weui_dialog_alert" id="alert" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">提示信息</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog primary" id='alertenter'>确定</a>
			</div>
		</div>
	</div>

	<div id="waiting" class="weui_loading_toast" style="display:none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<div class="weui_loading">
				<div class="weui_loading_leaf weui_loading_leaf_0"></div>
				<div class="weui_loading_leaf weui_loading_leaf_1"></div>
				<div class="weui_loading_leaf weui_loading_leaf_2"></div>
				<div class="weui_loading_leaf weui_loading_leaf_3"></div>
				<div class="weui_loading_leaf weui_loading_leaf_4"></div>
				<div class="weui_loading_leaf weui_loading_leaf_5"></div>
				<div class="weui_loading_leaf weui_loading_leaf_6"></div>
				<div class="weui_loading_leaf weui_loading_leaf_7"></div>
				<div class="weui_loading_leaf weui_loading_leaf_8"></div>
				<div class="weui_loading_leaf weui_loading_leaf_9"></div>
				<div class="weui_loading_leaf weui_loading_leaf_10"></div>
				<div class="weui_loading_leaf weui_loading_leaf_11"></div>
			</div>
			<p class="weui_toast_content">数据加载中</p>
		</div>
	</div>
</body>
</html>