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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/orders.css?v=2.4">
<script type="text/javascript" src="/Public/Sellermobile/js/orders.js?v=2.5"></script>

<!-- 顶部搜索框 -->
<!-- <div class="heard">
	<div class="heardtitle">
		<input type="text" name="keyword" id="keyword" class="search-input" placeholder="订单号"/>
		<?php echo $dfcount>=100 ? "99+":$dfcount; ?>
		<input type="button" class="search-btn"  value="">
	</div>
</div> -->

<div class="leftmenu">
	<ul id="tagsel">
		<li class="sel_tag" data-sel="sel_order_1"  data-more="more_1">待付款<span></span><span><?php echo $dfcount>=10000 ? "9999+":$dfcount; ?></span></li>
		<li data-sel="sel_order_2" data-more="more_2">待使用<span class="bline"></span><span><?php echo $dfhcount>=10000 ? "9999+":$dfhcount; ?></span></li>
		<li data-sel="sel_order_3" data-more="more_3" style="display:none;">已发货<span class="bline"></span><span><?php echo $yfhcount>=10000 ? "9999+":$yfhcount; ?></span></li>
		<li data-sel="sel_order_4" data-more="more_4" style="display:none;">待提货<span class="bline"></span><span><?php echo $dthcount>=10000 ? "9999+":$dthcount; ?></span></li>
		<li data-sel="sel_order_5" data-more="more_5">退款中<span class="bline"></span><span><?php echo $tkzcount>=10000 ? "9999+":$tkzcount; ?></span></li>
		<li data-sel="sel_order_6" data-more="more_6">已完成<span class="bline"></span><span><?php echo $ywccount>=10000 ? "9999+":$ywccount; ?></span></li>
		<li data-sel="sel_order_7" data-more="more_7" style="display:none">已关闭<span></span><span>10</span></li>
	</ul>
</div>
<div class="rightorders">
	<div class="taborder sel_order_1 sel_order" data-p="-1">
		<!-- <div class="orderinfo">
			<label class="orderid">会员名称<br><span>订单号:20160509123654</span><span>12-12 12:12:12</span></label>
			<div class="prolist">
				<div class="proinfo">
					<img src="/Public/Adminmobile/img/peitu.png">
					<label class="proname">蛋白素肉90gx2袋蛋白素肉90gx2袋蛋白素肉90gx2袋</label>
					<label class="proattr">袋装;300G</label>
					<label class="proprice"><span>￥</span>20.00<span>×3</span></label>
				</div>
			</div>
			<label class="totalprice">共6件,合计:<span>￥130.00</span>(含运费:10)</label>
		</div> -->
		<label class="moreorder more_1">加载更多</label>
	</div>
	<div class="taborder sel_order_2" data-p="-1">
		<label class="moreorder more_2">加载更多</label>
	</div>
	<div class="taborder sel_order_3" data-p="-1">
		<label class="moreorder more_3">加载更多</label>
	</div>
	<div class="taborder sel_order_4" data-p="-1">
		<label class="moreorder more_4">加载更多</label>
	</div>
	<div class="taborder sel_order_5" data-p="-1">
		<label class="moreorder more_5">加载更多</label>
	</div>
	<div class="taborder sel_order_6" data-p="-1">
		<label class="moreorder more_6">加载更多</label>
	</div>
	<div class="taborder sel_order_7" data-p="-1">
		<label class="moreorder more_7">加载更多</label>
	</div>
</div>
<script type="text/javascript">
 var loadmore_1_url="<?php echo U('Orders/getmoreorder_1');?>";//待付款
 var loadmore_2_url="<?php echo U('Orders/getmoreorder_2');?>";//待发货
 var loadmore_3_url="<?php echo U('Orders/getmoreorder_3');?>";//已发货
 var loadmore_4_url="<?php echo U('Orders/getmoreorder_4');?>";//待提货
 var loadmore_5_url="<?php echo U('Orders/getmoreorder_5');?>";//退款中
 var loadmore_6_url="<?php echo U('Orders/getmoreorder_6');?>";//已完成
 var loadmore_7_url="<?php echo U('Orders/getmoreorder_7');?>";//已关闭
</script>

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