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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/tejia.css?v=1.4">
<!-- <script src="/Public/Sellermobile/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script> -->
<script src="/Public/Sellermobile/js/base.js"></script>
<div class="tab">
	<span class="tab_info tabavtive" data-info="tese">特色</span>
	<span class="tab_info" data-info="tejia">特价</span>
	<span class="tab_info" data-info="huodong">活动</span>
</div>
<div class="content">
	<div class="theme tese">
		<div class="shang">
			<div class="img" data-type="tese"><img src="<?php echo ($img); ?>" /></div>
			<div class="text">
				<span>首页特色商品展示图</span>
				<div class="explain">
					<span>点击左侧图片</span>
					<span>可更换展示图</span>
				</div>
			</div>
		</div>
		<!-- <div class="showtitlename">
			<span class="showtitle">显示名称:</span>
			<?php if(empty($showname["tsshowname"])): ?><input class="showinputname showtsname" type="text" name="" value="特色" placeholder="显示名称">
				<?php else: ?>
				<input class="showinputname showtsname" type="text" name="" value="<?php echo ($showname["tsshowname"]); ?>" placeholder="显示名称"><?php endif; ?>
			<span class="savebtn" data-type="0">保存</span>
		</div> -->
		<div class="zhong">特色商品</div>
		<div class="header"><a href="<?php echo U('Activity/characteristic');?>" style="color:rgb(147,147,147);">添加特色商品</a></div>
		<div class="xia">
			<?php if(is_array($list)): foreach($list as $key=>$row): ?><div style="overflow:hidden;">
					<div class="pic"><img src="<?php echo ($row['ProLogoImg']); ?>" alt="" /></div>
					<div class="price">
						<span><?php echo ($row['ProName']); ?></span>
						<p style="color:red;">￥<?php echo ($row['Price']); ?></p>
					</div>
					<div class="aaa"><a data-type="tese" class="a" data-id="<?php echo ($row['ProId']); ?>">删除</a></div>
				</div><?php endforeach; endif; ?>
		</div>
	</div>

	<div class="theme tejia">
		<div class="shang">
			<div class="img" data-type='tejia'><img src="<?php echo ($img2); ?>" /></div>
			<div class="text">
				<span>首页特价商品展示图</span>
				<div class="explain">
					<span>点击左侧图片</span>
					<span>可更换展示图</span>
				</div>
			</div>
		</div>
		<!-- <div class="showtitlename">
			<span class="showtitle">显示名称:</span>
			<?php if(empty($showname["tjshowname"])): ?><input class="showinputname showtjname" type="text" name="" value="特价" placeholder="显示名称">
				<?php else: ?>
				<input class="showinputname showtjname" type="text" name="" value="<?php echo ($showname["tjshowname"]); ?>" placeholder="显示名称"><?php endif; ?>
			<span class="savebtn" data-type="1">保存</span>
		</div> -->
		<div class="zhong">特价商品</div>
		<div class="header"><a href="<?php echo U('Activity/translate');?>" style="color:rgb(147,147,147);">添加特价商品</a></div>
		<div class="xia">
			<?php if(is_array($rows)): foreach($rows as $key=>$listt): ?><div style="overflow:hidden;">
					<div class="pic"><img src="<?php echo ($listt['ProLogoImg']); ?>" alt="" /></div>
					<div class="price" style="overflow:hidden;">
						<span><?php echo ($listt['ProName']); ?></span>
						<div>
							<span style="color:red;">￥<?php echo ($listt['sprice']); ?></span> <del>￥<?php echo ($listt['Price']); ?></del>
						</div>
					</div>
					<div class="aaa" ><a data-type="tejia" class="a" data-id="<?php echo ($listt['ProId']); ?>">删除</a></div>
				</div><?php endforeach; endif; ?>
		</div>
	</div>

	<div class="theme huodong">
		<div class="shang">
			<div class="img" data-type="huodong"><img src="<?php echo ($img3); ?>" /></div>
			<div class="text">
				<span>首页活动商品展示图</span>
				<div class="explain">
					<span>点击左侧图片</span>
					<span>可更换展示图</span>
				</div>
			</div>
		</div>
		<div class="zhong">活动商品</div>
		<div class="header"><a href="<?php echo U('Activity/activity');?>" style="color:rgb(147,147,147);">添加活动商品</a></div>
		<div class="xia">
			<?php if(is_array($result)): foreach($result as $key=>$value): ?><div style="overflow:hidden;">
					<div class="pic"><img src="<?php echo ($value['ProLogoImg']); ?>" alt="" /></div>
					<div class="price">
						<span><?php echo ($value['ProName']); ?></span>
						<p style="color:red;">￥<?php echo ($value['Price']); ?></p>
					</div>
					<div class="aaa"><a data-type="huodong" data-id="<?php echo ($value['ProId']); ?>" class="a">删除</a></div>
				</div><?php endforeach; endif; ?>
		</div>

	</div>
</div>
<div class="choice">
	<div class="picture">
		<?php if(is_array($allimg)): foreach($allimg as $key=>$image): ?><img src="<?php echo ($image['imgurl']); ?>" class="image" data-url="<?php echo ($image['imgurl']); ?>"><?php endforeach; endif; ?>
	</div>
	<div class="blank"></div>
</div>
<script type="text/javascript">
	var namesave_url = "<?php echo U('Activity/namesave');?>";
</script>
<script src="/Public/Sellermobile/js/tejia.js?v=1.5" type="text/javascript" charset="utf-8"></script>
<script>
$('.a').click(function(){
	var type=$(this).attr('data-type');
	var id=$(this).attr('data-id');
	var senddata = {
		type:type,
		id:id,
	}
	tips('waiting','正在删除中···');
	$.ajax({
		url:"<?php echo U('Activity/updata');?>",
		type:"post",
		data:senddata,
		dataType:"json",
		complete: function(e){
			hidetips('waiting');
		},
		success:function(msg){
			if(msg.status=='true'){
				tips('notice', msg.info+'!', 1500, 'weui_icon_toast');
				setTimeout( window.location.reload(),1500);
			}else{
				tips('notice', msg.info+'!', 1500, 'weui_icon_notice');
			}
		}
	})

})
$('.img').click(function(){
	$('.choice').css('display','block');
	$('.picture img').css('height',$('.picture img').width() / 8 * 3 + 'px');
})
$('.image').click(function(){
	var type=$('.tabavtive').attr('data-info');
	var imgurl=$(this).attr('data-url');
	var senddata = {
		type:type,
		imgurl:imgurl
	}
	tips('waiting','正在保存中···');
	$.ajax({
		url:"<?php echo U('Activity/updataimg');?>",
		type:"post",
		data:senddata,
		dataType:"json",
		complete: function(e){
			hidetips('waiting');
		},
		success:function(msg){
			if(msg.status=='true'){
				tips('notice', msg.info+'!', 1500, 'weui_icon_toast');
				setTimeout( window.location.reload(),1500);
			}else{
				tips('notice', msg.info+'!', 1500, 'weui_icon_notice');
			}
		}
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