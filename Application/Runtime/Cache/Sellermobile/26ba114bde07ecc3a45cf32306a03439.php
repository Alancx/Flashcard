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
		
<script src="/Public/Plugins/Swiper/swiper-3.3.1.jquery.min.js"></script>
<script type="text/javascript" src="/Public/Sellermobile/js/ajaxfileupload.js"></script>
<link rel="stylesheet" href="/Public/Plugins/Swiper/swiper-3.3.1.min.css">
<link rel="stylesheet" href="/Public/Sellermobile/CSS/pageset.css">


<!-- 预览效果区域 -->
<div class="home-contents">
  <!-- 轮播图区域 -->
  <div class="swiper-container swiper-container-home">
    <div class="overburden">
      <div>
        <label class="addlb">添加</label>
        <label class="updatelb">修改</label>
        <label class="deletelb">删除</label>
      </div>
    </div>
    <div class="swiper-wrapper">
      <?php if(empty($homeimg['ImgPath'])): ?><div class="swiper-slide">
          <img src="http://placehold.it/800x500" class="imgslide">
        </div>
        <?php else: ?>
        <div class="swiper-slide">
          <img src="<?php echo ($homeimg['ImgPath']); ?>" class="imgslide">
        </div><?php endif; ?>
      <?php if(is_array($lbdata)): foreach($lbdata as $key=>$ci): ?><div class="swiper-slide" data-url="<?php echo ($ci['ImgPath']); ?>" data-href="<?php echo ($ci['ImgUrl']); ?>" data-hid="<?php echo ($ci['ID']); ?>">
          <img src="<?php echo ($ci['ImgPath']); ?>" class="imgslide">
        </div><?php endforeach; endif; ?>
    </div>
    <div class="swiper-pagination swiper-pagination-home"></div>
  </div>
  <!-- 轮播图区域end -->
  <!-- 门店基本信息 -->
  <div class="childshop">
    <?php if(empty($shopinfo['Slogo'])): ?><img src="/Public/theme2/images/shoplogo.png" class="childshop_img" alt="">
      <?php else: ?>
      <img src="<?php echo ($shopinfo["Slogo"]); ?>" class="childshop_img" alt=""><?php endif; ?>
    <label class="childshop_name"><?php echo ($shopinfo["storename"]); ?></label>
    <label class="childshop_title"><?php echo ($shopinfo["Descinfo"]); ?></label>
  </div>
  <!-- end门店基本信息 -->
  <!-- 产品区域一 -->
  <div class="propart">
    <div class="pro-title">
      <label class="pro_hot">热销商品<span>更多</span></label>
    </div>
    <div class="pro-line-1">
      <?php if(empty($selhotinfo[0])): ?><div class="pro-line-1-left">
          <div class="overburden">
            <div>
              <label class="updatepro updatehot" data-s="1" data-type="add">编辑</label>
            </div>
          </div>
          <img src="http://placehold.it/100x100" alt="">
          <label>商品名称<span>￥0.00</span></label>
        </div>
        <?php else: ?>
        <div class="pro-line-1-left">
          <div class="overburden">
            <div>
              <label class="updatepro updatehot" data-s="1" data-type="<?php echo ($selhotinfo[0]['ID']); ?>">编辑</label>
            </div>
          </div>
          <img src="<?php echo ($selhotinfo[0]['ProLogoImg']); ?>" alt="">
          <label><?php echo ($selhotinfo[0]['ProName']); ?><span>￥<?php echo number_format(($selhotinfo[0]['PriceRange']),2); ?></span></label>
        </div><?php endif; ?>
      <div class="pro-line-1-right">
        <div>
          <label><?php echo ($prohot['ProName']); ?></label>
          <label>￥<?php echo number_format(($prohot['PriceRange']),2); ?></label>
        </div>
        <img src="<?php echo ($prohot['ProLogoImg']); ?>" alt="">
      </div>
      <div class="pro-line-1-right">
        <?php if(empty($selhotinfo[1])): ?><div class="overburden">
              <div>
                <label class="updatepro updatehot" data-s="2" data-type="add">编辑</label>
              </div>
            </div>
            <div class="hotpnpc">
              <label>商品名称</label>
              <label>￥0.00</label>
            </div>
            <img src="http://placehold.it/100x100" alt="">
          <?php else: ?>
            <div class="overburden">
              <div>
                <label class="updatepro updatehot" data-s="2" data-type="<?php echo ($selhotinfo[1]['ID']); ?>">编辑</label>
              </div>
            </div>
            <div class="hotpnpc">
              <label><?php echo ($selhotinfo['1']['ProName']); ?></label>
              <label>￥<?php echo number_format(($selhotinfo[1]['PriceRange']),2); ?></label>
            </div>
            <img src="<?php echo ($selhotinfo['1']['ProLogoImg']); ?>" alt=""><?php endif; ?>
      </div>
    </div>
    <div class="swiper-container swiper-container-class">
      <div class="swiper-wrapper">
        <?php if(empty($info["classdata"])): $__FOR_START_1479__=1;$__FOR_END_1479__=5;for($i=$__FOR_START_1479__;$i < $__FOR_END_1479__;$i+=1){ ?><div class="swiper-slide">
              <div class="classtype">
                <img src="http://placehold.it/100x100" alt="">
                <label>分类名称</label>
              </div>
            </div><?php } ?>
          <?php else: ?>
          <?php if(is_array($info["classdata"])): foreach($info["classdata"] as $key=>$classinfo): ?><div class="swiper-slide">
              <a>
                <div class="classtype">
                  <img src="<?php echo ($classinfo['imgurl']); ?>" alt="">
                  <label><?php echo ($classinfo['cname']); ?></label>
                </div>
              </a>
            </div><?php endforeach; endif; endif; ?>
      </div>
    </div>
  </div>
  <!-- 产品区域一end -->
  <!-- 产品区域二 -->
  <div class="propart">
    <div class="pro-title">
      <label class="pro_new">新品推荐<span>更多</span></label>
    </div>
    <div class="pro-line-3">
      <div>
        <div>
          <label><?php echo ($pronew['ProName']); ?></label>
          <label>￥<?php echo number_format(($pronew['PriceRange']),2); ?></label>
        </div>
        <img src="<?php echo ($pronew['ProLogoImg']); ?>" alt="">
      </div>

      <div>
        <?php if(empty($selnewinfo[0])): ?><div class="overburden">
            <div>
              <label class="updatepro updatenew" data-s="1" data-type="add">编辑</label>
            </div>
          </div>
          <div class="newpnpc">
            <label>商品名称</label>
            <label>￥0.00</label>
          </div>
          <img src="http://placehold.it/100x100" alt="">
          <?php else: ?>
          <div class="overburden">
            <div>
              <label class="updatepro updatenew" data-s="1" data-type="<?php echo ($selnewinfo[0]['ID']); ?>">编辑</label>
            </div>
          </div>
          <div class="newpnpc">
            <label><?php echo ($selnewinfo['0']['ProName']); ?></label>
            <label>￥<?php echo number_format(($selnewinfo[0]['PriceRange']),2); ?></label>
          </div>
          <img src="<?php echo ($selnewinfo['0']['ProLogoImg']); ?>" alt=""><?php endif; ?>
      </div>

      <div>
        <?php if(empty($selnewinfo[1])): ?><div class="overburden">
            <div>
              <label class="updatepro updatenew" data-s="1" data-type="add">编辑</label>
            </div>
          </div>
          <div class="newpnpc">
            <label>商品名称</label>
            <label>￥0.00</label>
          </div>
          <img src="http://placehold.it/100x100" alt="">
          <?php else: ?>
          <div class="overburden">
            <div>
              <label class="updatepro updatenew" data-s="1" data-type="<?php echo ($selnewinfo[1]['ID']); ?>">编辑</label>
            </div>
          </div>
          <div class="newpnpc">
            <label><?php echo ($selnewinfo['0']['ProName']); ?></label>
            <label>￥<?php echo number_format(($selnewinfo[0]['PriceRange']),2); ?></label>
          </div>
          <img src="<?php echo ($selnewinfo['1']['ProLogoImg']); ?>" alt=""><?php endif; ?>
      </div>

      <div>
        <?php if(empty($selnewinfo[2])): ?><div class="overburden">
            <div>
              <label class="updatepro updatenew" data-s="1" data-type="add">编辑</label>
            </div>
          </div>
          <div class="newpnpc">
            <label>商品名称</label>
            <label>￥0.00</label>
          </div>
          <img src="http://placehold.it/100x100" alt="">
          <?php else: ?>
          <div class="overburden">
            <div>
              <label class="updatepro updatenew" data-s="1" data-type="<?php echo ($selnewinfo[2]['ID']); ?>">编辑</label>
            </div>
          </div>
          <div class="newpnpc">
            <label><?php echo ($selnewinfo['2']['ProName']); ?></label>
            <label>￥<?php echo number_format(($selnewinfo[2]['PriceRange']),2); ?></label>
          </div>
          <img src="<?php echo ($selnewinfo['2']['ProLogoImg']); ?>" alt=""><?php endif; ?>
      </div>
    </div>
  </div>
  <!-- 产品区域二end -->
</div>
<div class="lbinfosetconver">
  <div class="setlbinfo">
    <img src="/Public/Sellermobile/Icon/add_img.png" alt="">
    <input type="file" name="selimg" id="selimg" value="" onchange="lbimage(this)">
    <input type="hidden" id="lbimage" value="">
  </div>
  <div class="herfinfo">
    <label>轮播图链接:</label>
    <input type="text" id="lbinput" name="" value="" placeholder='已"HTTP://"开头[可以为空]'>
  </div>
  <div class="btnlb">
    <label class="quxiaolb">取消</label>
    <label class="baocunlb">保存</label>
  </div>
</div>
<div class="prohotconver">
  <div>
    <div class="proinfos">
      <?php if(is_array($hotproinfo)): foreach($hotproinfo as $key=>$hotitem): ?><div class="pro_info hotproinfo" data-pid="<?php echo ($hotitem['ProId']); ?>" data-imgurl="<?php echo ($hotitem['ProLogoImg']); ?>" data-pname="<?php echo ($hotitem['ProName']); ?>" data-price="<?php echo number_format(($hotitem['PriceRange']),2); ?>">
          <img src="<?php echo ($hotitem['ProLogoImg']); ?>" alt="">
          <label><?php echo ($hotitem['ProName']); ?></label>
          <label>￥<?php echo number_format(($hotitem['PriceRange']),2); ?></label>
        </div><?php endforeach; endif; ?>
      <?php if(is_array($newproinfo)): foreach($newproinfo as $key=>$newitem): ?><div class="pro_info newproinfo" data-pid="<?php echo ($newitem['ProId']); ?>" data-imgurl="<?php echo ($newitem['ProLogoImg']); ?>" data-pname="<?php echo ($newitem['ProName']); ?>" data-price="<?php echo number_format(($newitem['PriceRange']),2); ?>">
          <img src="<?php echo ($newitem['ProLogoImg']); ?>" alt="">
          <label><?php echo ($newitem['ProName']); ?></label>
          <label>￥<?php echo number_format(($newitem['PriceRange']),2); ?></label>
        </div><?php endforeach; endif; ?>
    </div>
    <div class="proqubtn">
      <label class="proqusel">取消</label>
    </div>
  </div>
</div>
<script type="text/javascript">
  var lbimgsaveurl="<?php echo U('User/lbimage');?>";
  var savehomeurl="<?php echo U('User/pageset');?>";
  var lbimgdeleteurl="<?php echo U('User/delonhome');?>";
</script>
<script src="/Public/Sellermobile/JS/pageset.js?v=1.0" charset="utf-8"></script>

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