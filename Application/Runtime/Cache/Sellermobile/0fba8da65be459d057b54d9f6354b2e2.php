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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/revenue.css?v=1.3">
<link rel="stylesheet" href="/Public/Sellermobile/css/orders.css?v=2.0">
<script type="text/javascript" src="/Public/Sellermobile/js/echarts.common.min.js"></script>
<script type="text/javascript" src="/Public/Sellermobile/js/reorder.js?v=1.2"></script>
<style>
.nodata{
  display: block;
  padding: 20% 20% 0px 20%;
}
.nodata>img{
  width: 100%;
}
</style>
<div class="reheard reheardo">
  <div id="scharts" class="reheard-1">
  </div>
  <div class="reheard-2">
    <div>
      <label><?php echo $sdordercount?sprintf("%.0f",$sdordercount):'0' ?><br><span>下单笔数</span></label>
    </div>
    <div>
      <label><?php echo $sdorderpcount?sprintf("%.0f",$sdorderpcount):'0' ?><br><span>待付订单</span></label>
    </div>
    <div>
      <label><?php echo $sdorderfcount?sprintf("%.0f",$sdorderfcount):'0' ?><br><span>发货订单</span></label>
    </div>
  </div>
  <div class="reheard-2 reheardo-2 ">
    <div>
      <label><?php echo $sdorderypcount?sprintf("%.0f",$sdorderypcount):'0' ?><br><span>付款人数</span></label>
    </div>
    <div>
      <label><?php echo $sdordercount!='0'?(sprintf("%.2f",1-($sdorderpcount/$sdordercount))*100).'%':'0.00%' ?><br><span>购买转化率</span></label>
    </div>
    <div>
      <label><?php echo $sdorderypcount!='0'?sprintf("%.2f",$sdorderpmcount/$sdorderypcount):'0.00' ?><br><span>平均客单价</span></label>
    </div>
  </div>
</div>
<div class="part" style="display:none">
  <label>订单管理</label>
</div>
<div class="part-con" data-page="-1">
  <?php if(empty($orderlist)): ?><div class="nodata">
      <img src="/Public/Sellermobile/icon/watermark.png" alt="">
    </div>
    <?php else: ?>
    <?php if(is_array($orderlist)): foreach($orderlist as $key=>$oitem): ?><div class="orderinfo" style="margin-bottom:0px;border-width:5px;border-color:#eee;">
        <label class="orderid"><?php echo ($oitem['MemberName']); ?>
        <?php switch($oitem["Status"]): case "1": ?><span>待付款</span><br><?php break;?>
              <?php case "2": ?><span>待发货</span><br><?php break;?>
              <?php case "3": ?><span>待收货</span><br><?php break;?>
              <?php case "4": ?><span>已完成</span><br><?php break;?>
              <?php case "5": ?><span>退款中</span><br><?php break;?>
              <?php default: ?><span>已完成</span><br><?php endswitch;?>
          <span><?php echo ($oitem['OrderId']); ?></span>
          <span><?php echo ($oitem['oDate']); ?></span>
        </label>
        <div class="prolist">
          <?php if(is_array($oitem['prolist'])): foreach($oitem['prolist'] as $key=>$proinfo): ?><div class="proinfo">
              <img src="<?php echo ($proinfo['ProLogoImg']); ?>">
              <label class="proname"><?php echo ($proinfo['ProName']); ?></label>
              <label class="proattr"><?php echo ($proinfo['Spec']); ?>;</label>
              <label class="proprice" style="position:relative;text-align:left;"><span>￥</span><?php echo $proinfo['Price']?sprintf("%.2f",$proinfo['Price']):'0.00' ?><span>×<?php echo ($proinfo['Count']); ?></span></label>
            </div><?php endforeach; endif; ?>
        </div>
        <label class="totalprice" style="border:none;">共<?php echo ($oitem['Count']); ?>件,合计:<span>￥<?php echo $oitem['Price']?sprintf("%.2f",$oitem['Price']):'0.00' ?></span>(含运费:<?php echo $oitem['Freight']?sprintf("%.2f",$oitem['Freight']):'0.00' ?>)</label>
      </div><?php endforeach; endif; endif; ?>
</div>
<script type="text/javascript">
var dataday=<?php echo ($dataday); ?>;
var daymon=<?php echo ($daymon); ?>;
var selday="<?php echo ($getday); ?>";
var yesorder_url="<?php echo U('Record/otherdayorders');?>"; //加载某一天的订单
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