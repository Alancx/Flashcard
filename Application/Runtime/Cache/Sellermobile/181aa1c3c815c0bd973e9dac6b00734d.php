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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/sqWarehouselist.css?v=1.2">
<div class="whlist">
  <?php if(is_array($wahouselist)): foreach($wahouselist as $key=>$whitem): if($whitem['IsPay'] == '0' ): ?><div class="whinfo">
        <?php switch($whitem['Status']): case "0": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>未支付(待提交)</span></label><?php break;?>
          <?php case "2": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>未支付(待审核)</span></label><?php break;?>
          <?php case "3": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>未支付(已审核)</span></label><?php break;?>
          <?php case "4": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>未支付(已拒绝)</span></label><?php break; endswitch;?>
        <label class="whrh">采购人员:<span><?php echo ($whitem['InputName']); ?></span></label>
        <label class="whprice">商品金额:<span>￥<?php echo $whitem['Money']?sprintf("%.2f",$whitem['Money']):'0.00' ?></span></label>
        <label class="whnum">商品数量:<span><?php echo ($whitem['Count']); ?></span></label>
        <div class="btngroup">
          <?php switch($whitem['Status']): case "0": ?><label class="sendnow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>" data-tprice="<?php echo $whitem['Money']?sprintf("%.2f",$whitem['Money']):'0.00' ?>">立即提交</label>
              <a href="<?php echo U('UMWareHouse/sqWarehouse',array('whid'=>$whitem['InWarehouseId']));?>">
                <label class="updatenow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>">编辑</label>
              </a>
              <label class="delnow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>">删除</label><?php break;?>
            <?php case "2": ?><label class="paynow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>" data-tprice="<?php echo $whitem['Money']?sprintf("%.2f",$whitem['Money']):'0.00' ?>">立即支付</label>
              <label class="delnow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>">删除</label><?php break;?>
            <?php default: ?>
            <a href="<?php echo U('UMWareHouse/looksqWarehouse',array('whid'=>$whitem['InWarehouseId']));?>">
              <label class="looknow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>">查看详情</label>
            </a><?php endswitch;?>
        </div>
      </div>
      <?php elseif($whitem['IsPay'] == '1'): ?>
      <div class="whinfo">
        <?php switch($whitem['Status']): case "0": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>已支付(待提交)</span></label><?php break;?>
          <?php case "2": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>已支付(待审核)</span></label><?php break;?>
          <?php case "3": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>已支付(已审核)</span></label><?php break;?>
          <?php case "4": ?><label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>已支付(已拒绝)</span></label><?php break; endswitch;?>
        <label class="whrh">采购人员:<span><?php echo ($whitem['InputName']); ?></span></label>
        <label class="whprice">商品金额:<span>￥<?php echo $whitem['Money']?sprintf("%.2f",$whitem['Money']):'0.00' ?></span></label>
        <label class="whnum">商品数量:<span><?php echo ($whitem['Count']); ?></span></label>
        <div class="btngroup">
          <a href="<?php echo U('UMWareHouse/looksqWarehouse',array('whid'=>$whitem['InWarehouseId']));?>">
            <label class="looknow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>">查看详情</label>
          </a>
        </div>
      </div>
      <?php else: ?>
      <div class="whinfo">
        <label class="whid"><?php echo ($whitem['InWarehouseId']); ?><span>已支付</span></label>
        <label class="whrh">采购人员:<span><?php echo ($whitem['InputName']); ?></span></label>
        <label class="whprice">商品金额:<span>￥<?php echo $whitem['Money']?sprintf("%.2f",$whitem['Money']):'0.00' ?></span></label>
        <label class="whnum">商品数量:<span><?php echo ($whitem['Count']); ?></span></label>
        <div class="btngroup">
          <a href="<?php echo U('UMWareHouse/looksqWarehouse',array('whid'=>$whitem['InWarehouseId']));?>">
            <label class="looknow" data-whid="<?php echo ($whitem['InWarehouseId']); ?>">查看详情</label>
          </a>
        </div>
      </div><?php endif; endforeach; endif; ?>
</div>
<div class="addwarehouse">
  <a href="<?php echo U('UMWareHouse/sqWarehouse');?>">
    <label class="addwh">新增订货申请</label>
  </a>
</div>
<script type="text/javascript">
var InWarehouseId='';
var delwh_url="<?php echo U('UMWareHouse/delsqWarehouse');?>";
var paywh_url="https://<?php echo $_SERVER['HTTP_HOST']; echo U('UMWareHouse/paysqWarehouse');?>";
var sendwh_url="<?php echo U('UMWareHouse/sendsqWarehouse');?>";
var payend_url="<?php echo U('UMWareHouse/sqWarehouselist',array('whid'=>'INWAREHOUSEID'));?>";
</script>
<script type="text/javascript">
var payLock=false;
var paydata='';
function jsApiCall()
{
  // console.log(data['package']);
  WeixinJSBridge.invoke('getBrandWCPayRequest',{
    "appId":paydata['appId'],
    "timeStamp":paydata['timeStamp'],
    "nonceStr":paydata['nonceStr'],
    "package":paydata['package'],
    "signType":paydata['signType'],
    "paySign":paydata['paySign']
  },
  function (res)
  {
    if(res.err_msg == "get_brand_wcpay_request:cancel")
    {
      payLock=false;
      paydata='';
      alert("您取消了支付");
    }
    else if(res.err_msg == "get_brand_wcpay_request:fail")
    {
      payLock=false;
      paydata='';
      alert("支付失败,错误信息："+res.err_desc);
    }
    else if(res.err_msg == "get_brand_wcpay_request:ok")
    {
      alert("支付成功");
      payend_url=payend_url.replace(/INWAREHOUSEID/g,InWarehouseId);
      setTimeout(function() { window.location.href=payend_url;}, 1000);
    }
    else
    {
      payLock=false;
      paydata='';
      alert("支付遇到未知错误。");
    }
  }
);
}

function callpay()
{
  if (payLock) {
    return;
  }
  payLock=true;
  if (typeof WeixinJSBridge == "undefined")
  {
    if (document.addEventListener)
    {
      document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
    }
    else if (document.attachEvent)
    {
      document.attachEvent('WeixinJSBridgeReady', jsApiCall);
      document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
    }
  }
  else
  {
    jsApiCall();
  }
}
</script>
<script src="/Public/Sellermobile/js/sqWarehouselist.js?v=1.1" charset="utf-8"></script>

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