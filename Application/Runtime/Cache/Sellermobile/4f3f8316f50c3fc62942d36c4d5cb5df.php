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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/envelopes.css?v=1.1" />
<script src="/Public/Sellermobile/js/base.js"></script>
	<div class="top">红包</div>
	<div class="shang">
		<div class="showinshop">
			<span>进店红包</span>
			<input class="inputinred" type="number" name="" value="<?php echo ($inredprice); ?>" placeholder="红包金额">
			<span class="saveinred">保存</span>
		</div>
		<?php if($result == ''): ?><div class="shang-top">
				<span>分享人</span>
				<div class="text">
					从下方选择一个红包
				</div>
			</div>
		<?php else: ?>
			<div class="shang-top">
				<span>分享人</span>
				<div class="zhong">
					<div class="zhogn-left">￥<?php echo ($result['Rules']); ?> <span><?php echo ($result['CouponName']); ?></span></div>
					<div class="time" style="margin-left:10px;"><span ><?php echo ($result['StartDate']); ?>至<?php echo ($result['ExpiredDate']); ?></span></div>
				</div>
				<div class="left"></div>
				<div class="right"></div>
				 <a class="updata" data-id="<?php echo ($result['CouponId']); ?>">删除</a>
			</div><?php endif; ?>
		<?php if($row == ''): ?><div class="shang-bottom">
				<span>被分享人</span>
				<div class="text">
					从下方选择一个红包
				</div>
			</div>
		<?php else: ?>
			<div class="shang-top">
				<span>被分享人</span>
				<div class="zhong">
					<div class="zhogn-left">￥<?php echo ($row['Rules']); ?> <span><?php echo ($row['CouponName']); ?></span></div>
					<div class="time" style="margin-left:10px;"><span ><?php echo ($row['StartDate']); ?>至<?php echo ($row['ExpiredDate']); ?></span></div>

				</div>
				<div class="left"></div>
				<div class="right"></div>
				 <a class="update" data-id="<?php echo ($row['CouponId']); ?>">删除</a>
			</div><?php endif; ?>

	</div>
	<div class="middle">红包列表 <a href="<?php echo U('Envelopes/add',array('id'=>'add'));?>">添加红包</a></div>
	<?php if(is_array($list)): foreach($list as $key=>$row): ?><div class="xia">

		<div class="xia-top">
			<div class="zhong">
				<div class="zhogn-left">￥<?php echo ($row['Rules']); ?> <span><?php echo ($row['CouponName']); ?></span></div>
				<div class="time" style="margin-left:10px;"><span ><?php echo ($row['StartDate']); ?>至<?php echo ($row['ExpiredDate']); ?></span></div>
			</div>
			<div class="left"></div>
			<div class="right"></div>
			<div class="link"><a href="<?php echo U('Envelopes/add',array('id'=>$row['CouponId']));?>">编辑</a> <a data-id="<?php echo ($row['CouponId']); ?>" class="delete">删除</a><a class="edit" >设置</a></div>
			<div class="eject">
				<div class="up" data-id="<?php echo ($row['CouponId']); ?>">设为分享人红包</div>
				<div class="lower" data-id="<?php echo ($row['CouponId']); ?>">设为被分享人红包</div>
				<div class="aa"></div>
			</div>
		</div>

	</div><?php endforeach; endif; ?>
</body>
<script src="/Public/Sellermobile/js/setup.js?v1.3"></script>
<script>
	// $(document).on('click','.delete',function(){
	$('.delete').click(function(){
		var id=$(this).attr('data-id');
		var senddata = {
			id:id,
		}
		// console.log('sss');
		tips('waiting','正在删除中···');
		$.ajax({
			url:"<?php echo U('Envelopes/deleta');?>",
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
	// $(document).on('click','.up',function(){
    $('.up').click(function(){
		var id=$(this).attr('data-id');
		var senddata = {
			id:id,
		}
		tips('waiting','正在设置中···');
		$.ajax({
			url:"<?php echo U('Envelopes/up');?>",
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
	// $(document).on('click','.lower',function(){
		$('.lower').click(function(){
		var id=$(this).attr('data-id');
		var senddata = {
			id:id,
		}
		tips('waiting','正在处理中···');
		$.ajax({
			url:"<?php echo U('Envelopes/data');?>",
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
	// $(document).on('click','.updata',function(){
		$('.updata').click(function(){
		var id=$(this).attr('data-id');
		var senddata = {
			id:id,
		}
		tips('waiting','正在删除中···');
		$.ajax({
			url:"<?php echo U('Envelopes/updata');?>",
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
		// console.log(id);
	})
	// $(document).on('click','.update',function(){
		$('.update').click(function(){
		var id=$(this).attr('data-id');
		var senddata = {
			id:id,
		}
		tips('waiting','正在删除中···');
		$.ajax({
			url:"<?php echo U('Envelopes/update');?>",
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
	});
	// 保存进店红包
	$('.saveinred').click(function(){
		var inredprice = $('.inputinred').val();
		var reg = /^[0-9]+.?[0-9]*$/;
		if (reg.test(inredprice)) {
			var senddata = {
				redprice:inredprice,
			}
			tips('waiting','正在保存中···');
			$.ajax({
				url:"<?php echo U('Envelopes/saveinred');?>",
				type:"post",
				data:senddata,
				dataType:"json",
				complete: function(e){
					hidetips('waiting');
				},
				success:function(msg){
					if(msg.status=='true'){
						tips('notice', '保存成功!', 1500, 'weui_icon_toast');
					}else{
						tips('notice', '保存失败!', 1500, 'weui_icon_notice');
					}
				}
			})
		} else {
			tips('notice', '填写有效的金额!', 1500, 'weui_icon_notice');
		}
	})
</script>
</html>

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