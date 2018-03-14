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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/sqWarehouse.css?v=1.4">

<div class="allconternts">
  <div class="part part_top">
    <label>订货申请单</label>
    <?php if($saveType == '0' ): ?><div class="orderinfo">
        <div class="infoitem">
          <label class="house" data-wid="wh<?php echo substr($sinfo['token'],-8);?>_<?php echo ($sinfo['id']); ?>" data-wname="<?php echo ($sinfo['storename']); ?>">订货仓库:</label><span><?php echo ($sinfo['storename']); ?></span>
        </div>
        <div class="infoitem">
          <label class="oname" data-uid="<?php echo ($uinfo['id']); ?>" data-uname="<?php echo ($uinfo['TrueName']); ?>">订货人员:</label><span><?php echo ($uinfo['TrueName']); ?></span>
        </div>
        <div class="infoitem">
          <label class="odate" data-date="<?php echo date('Y-m-d H:i');?>">订货日期:</label><span><?php echo date('Y-m-d H:i');?></span>
        </div>
        <div class="infoitem">
          <label class="otype">订货类型:</label><span>订货申请</span>
        </div>
      </div>
      <?php else: ?>
      <div class="orderinfo">
        <div class="infoitem">
          <label>系统单号:</label><span><?php echo ($saveType); ?></span>
        </div>
        <div class="infoitem">
          <label class="house" data-wid="<?php echo ($warhouse['InStorehouseId']); ?>" data-wname="<?php echo ($warhouse['InStorehouseName']); ?>">订货仓库:</label><span><?php echo ($warhouse['InStorehouseName']); ?></span>
        </div>
        <div class="infoitem">
          <label class="oname" data-uid="<?php echo ($warhouse['InputId']); ?>" data-uname="<?php echo ($warhouse['InputName']); ?>">订货人员:</label><span><?php echo ($warhouse['InputName']); ?></span>
        </div>
        <div class="infoitem">
          <label class="odate" data-date="<?php echo ($warhouse['oDate']); ?>">订货日期:</label><span><?php echo ($warhouse['oDate']); ?></span>
        </div>
        <div class="infoitem">
          <label class="otype">订货类型:</label><span>订货申请</span>
        </div>
      </div><?php endif; ?>
  </div>
  <div class="part pro_list">
    <label class="proinfo">订货商品 <span onclick="selectProFun();">添加商品</span></label>
  </div>
  <div class="prolist">
    <?php if(is_array($warhouselist)): foreach($warhouselist as $key=>$litem): ?><div class="proitem plitem" id="pl_<?php echo ($litem['ProIdCard']); ?>" data-pid="<?php echo ($litem['ProId']); ?>" data-pcid="<?php echo ($litem['ProIdCard']); ?>" data-pattr="<?php echo ($litem['ProSpec1']); ?>" data-pname="<?php echo ($litem['ProName']); ?>" data-cid="<?php echo ($litem['ClassId']); ?>" data-pimg="<?php echo ($litem['ProLogoImg']); ?>" data-cosp="<?php echo $litem['cosp']?sprintf("%.2f",$litem['cosp']):'0.00' ?>" data-price="<?php echo $litem['Price']?sprintf("%.2f",$litem['Price']):'0.00' ?>">
        <span onclick="delselpro(this)"></span>
        <img src="<?php echo ($litem['ProLogoImg']); ?>" alt="">
        <div class="pcontent">
          <label class="pname"><?php echo ($litem['ProName']); ?></label>
          <label class="pattr"><?php echo ($litem['ProSpec1']); ?></label>
          <label class="pprice">售价:<span>￥<?php echo $litem['Price']?sprintf("%.2f",$litem['Price']):'0.00' ?></span>&emsp;&emsp;进价:<span>￥<?php echo $litem['cosp']?sprintf("%.2f",$litem['cosp']):'0.00' ?></span></label>
        </div>
        <div class="pnum"><label>数量</label><input type="number" class="nums" name="" value="<?php echo ($litem['Count']); ?>"></div>
      </div><?php endforeach; endif; ?>
  </div>
  <div class="submitpro">
    <label class="submitproL" data-type="0">保存订货申请</label>
    <label class="submitproL" data-type="2">提交订货申请</label>
  </div>
</div>
<!-- /////选择平台商品////// -->
<div class="proconver">
  <div class="getproinfo">
    <?php if(is_array($cinfo)): foreach($cinfo as $key=>$citem): ?><div class="proitem" id="ps_<?php echo ($citem['ProIdCard']); ?>" data-pid="<?php echo ($citem['ProId']); ?>" data-pcid="<?php echo ($citem['ProIdCard']); ?>" data-pname="<?php echo ($citem['ProName']); ?>" data-cid="<?php echo ($citem['ClassId']); ?>" data-pattr="<?php echo ($citem['ProSpec1']); ?>" data-pimg="<?php echo ($citem['ProLogoImg']); ?>" data-cosp="<?php echo $citem['CosPrice']?sprintf("%.2f",$citem['CosPrice']):'0.00' ?>" data-price="<?php echo $citem['Price']?sprintf("%.2f",$citem['Price']):'0.00' ?>">
        <img src="<?php echo ($citem['ProLogoImg']); ?>" alt="">
        <div class="pcontent">
          <label class="pname"><?php echo ($citem['ProName']); ?></label>
          <label class="pattr"><?php echo ($citem['ProSpec1']); ?></label>
          <label class="pprice">售价:<span>￥<?php echo $citem['Price']?sprintf("%.2f",$citem['Price']):'0.00' ?></span>&emsp;&emsp;进价:<span>￥<?php echo $citem['CosPrice']?sprintf("%.2f",$citem['CosPrice']):'0.00' ?></span></label>
        </div>
        <div class="suresel">

        </div>
      </div><?php endforeach; endif; ?>
  </div>
  <div class="suregetpro">
    <label class="suregselpro">确定</label>
  </div>
</div>

<script type="text/javascript">
var addwh_url="<?php echo U('UMWareHouse/addSQWarehouse');?>";
var send_url="<?php echo U('Sellermobile/UMWareHouse/sqWarehouselist');?>";
var whid="<?php echo ($saveType); ?>";
</script>
<script src="/Public/Sellermobile/js/sqWarehouse.js?v=1.5" charset="utf-8"></script>

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