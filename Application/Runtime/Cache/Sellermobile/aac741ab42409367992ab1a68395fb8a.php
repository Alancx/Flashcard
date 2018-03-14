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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/tkorders.css?v=1.1">
<style media="screen">
  .order_remark{
    display: block;
    overflow: hidden;
    border-bottom: 1px solid #dddddd;
    font-size: 14px;
    color: #666666;
    padding: 10px;
    line-height: 16px;
  }
</style>
<?php if(empty($orders)): ?><div class="nodata">
    <img src="/Public/Sellermobile/icon/watermark.png" alt="">
  </div>
  <?php else: ?>
  <div class="ordercontents">
    <?php if(is_array($orders)): foreach($orders as $key=>$info): ?><div class="orderinfo order_<?php echo ($info['OrderId']); ?>">
        <label class="orderid"><?php echo ($info['MemberName']); ?><span>待完成</span><br><span><?php echo ($info['OrderId']); ?></span><span><?php echo ($info['oDate']); ?></span><br>
          <?php if(!empty($info["ShortOid"])): ?><span>就餐码:<?php echo ($info['ShortOid']); ?>;</span><?php endif; ?>
          <?php if(!empty($info["TableId"])): ?><span>桌号:<?php echo ($info['TableId']); ?>;</span><?php endif; ?>
        </label>
        <?php if(!empty($info["MessageByBuy"])): ?><label class="order_remark">备注:<?php echo ($info['MessageByBuy']); ?></label><?php endif; ?>
        <div class="prolist">
          <?php if(is_array($info['prolist'])): foreach($info['prolist'] as $key=>$proinfo): ?><div class="proinfo">
              <img src="<?php echo ($proinfo['ProLogoImg']); ?>">
              <label class="proname"><?php echo ($proinfo['ProName']); ?></label>
              <label class="proattr"><?php echo ($proinfo['Spec']); ?></label>
              <label class="proprice"><span>￥</span><?php echo $proinfo['Price']?sprintf("%.2f",$proinfo['Price']):'0.00' ?><span>×<?php echo ($proinfo['Count']); ?></span></label>
            </div><?php endforeach; endif; ?>
        </div>
        <label class="totalprice">共<?php echo ($info['Count']); ?>件,合计:<span>￥<?php echo $info['Price']?sprintf("%.2f",$info['Price']):'0.00' ?></span></label>
        <div class="btn_group">
          <!-- <label class="tkorder" data-oid="<?php echo ($info['OrderId']); ?>">同意退款</label> -->
          <label class="sureorder" data-oid="<?php echo ($info['OrderId']); ?>">确认完成</label>
        </div>
      </div><?php endforeach; endif; ?>
  </div><?php endif; ?>

<script type="text/javascript">
var sureorder_url="<?php echo U('Orders/setendorder');?>";
</script>

<script type="text/javascript">
$(document).ready(function(){
  $('.sureorder').click(function(){
    var oid = $(this).attr('data-oid');
    tips('waiting','正在处理···');
    $.ajax({
      type:"post",
      url:sureorder_url,
      data:"oid="+oid,
      dateType:"json",
      complete: function(e){
        hidetips('waiting');
      },
      success: function(msg){
        if (msg.status == 'true') {
          tips('notice', '处理完成!', 1500, 'weui_icon_toast');
          setTimeout(function(e){
            window.location.reload();
          },1000);
        } else{
          tips('notice', '处理失败!', 1500, 'weui_icon_notice');
        }
      },
    })





  })
})
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