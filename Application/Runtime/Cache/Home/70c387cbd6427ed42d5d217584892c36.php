<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo ($Title); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" type="text/css" href="/Public/GroupBuys/css/tuan.css?v=1.7"/>
	<link rel="stylesheet" type="text/css" href="/Public/GroupBuys/css/weui/weui.css"/>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
	<script src="/Public/GroupBuys/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/GroupBuys/js/Base.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<div class="contentinfo">
		<div class="content">
			<!--banner-->
			<img src="<?php echo ($groupinfo["Logoimg"]); ?>"  class="banner"/>
			<!--活动参加的人数-->
			<div class="peoplenumber">
				<span>当前参与活动人数<span><?php echo ($ubcount); ?></span>人</span>
				<span><span><?php echo ($uvcount); ?></span>人浏览</span>
			</div>
			<!--人员列表-->
			<ul class="peoplelist">
				<?php if(is_array($uvinfo)): foreach($uvinfo as $key=>$uv): ?><li><img src="<?php echo ($uv['Headimg']); ?>"><span><?php echo ($uv['Nickname']); ?></span></li><?php endforeach; endif; ?>
			</ul>
			<!--截止时间-->
			<div class="runtime">
				<h2>活动截止倒计时</h2>
				<div class="run_time">
					<span class="d">0</span>天
					<span class="h">0</span>时
					<span class="m">0</span>分
					<span class="s">0</span>秒
				</div>
			</div>
			<!--推荐奖励-->
			<div class="share">
				<h3>分享有奖</h3>
				<div class="redinfo">
					<img src="/Public/GroupBuys/img/hongbao.png" class="" />
					<span><?php echo ($groupinfo['Redpaper']); ?>元</span>
				</div>
				<button class="sharebtn">立即分享</button>
			</div>

			<div class="shareinfo">
				<h3>分享有奖</h3>
				<div class="red_info">
					<?php $__FOR_START_28396__=1;$__FOR_END_28396__=5;for($i=$__FOR_START_28396__;$i < $__FOR_END_28396__;$i+=1){ if($sharenums >= $i ): ?><div class="redinfo">
								<img src="/Public/GroupBuys/img/hbinfo_1.png"/>
							</div>
							<?php else: ?>
							<div class="redinfo">
								<img src="/Public/GroupBuys/img/hbinfo_2.png"/>
							</div><?php endif; } ?>
				</div>
			</div>
			<!--图文详情-->
			<div class="textimg">
				<?php echo ($groupinfo['Imgs']); ?>
			</div>
		</div>
		<!-- 填写留言 -->
		<div class="showremakinfo">
			<div class="setremarkinfo">
				<span class="showtitle"><span><?php echo ($remarkcount); ?>条</span>留言信息</span>
				<div class="inputinfo">
					<input type="text" name="" value="" class="input_remark" placeholder="填写留言信息">
					<span class="sendinputremak">发表</span>
				</div>
			</div>
			<div class="showremaklist">
				<?php if(is_array($remarkinfo)): foreach($remarkinfo as $key=>$rk): ?><div class="show_remark">
						<img src="<?php echo ($rk['Headimg']); ?>" alt="">
						<span class="show_remarktime"><span><?php echo ($rk['Nickname']); ?></span><?php echo ($rk['Cdata']); ?></span>
						<span class="show_remarkcontent"><?php echo ($rk['Content']); ?></span>
					</div><?php endforeach; endif; ?>
			</div>
		</div>
		<!--滚动信息-->
		<div class="scrollinfor">
			<ul class="scroll_list_1">
				<?php if(is_array($scrollinfo)): foreach($scrollinfo as $key=>$ub): ?><li>
						<img src="<?php echo ($ub['Headimg']); ?>" />
						<span><?php echo ($ub['Nickname']); echo ($ub['Content']); ?></span>
					</li><?php endforeach; endif; ?>
			</ul>
			<ul class="scroll_list_2">
				<?php if(is_array($scrollinfo)): foreach($scrollinfo as $key=>$ubi): ?><li>
						<img src="<?php echo ($ubi['Headimg']); ?>" />
						<span><?php echo ($ubi['Nickname']); echo ($ubi['Content']); ?></span>
					</li><?php endforeach; endif; ?>
			</ul>
		</div>
	</div>

	<!--客服、音乐-->
	<div class="headerimg">
		<img src="/Public/GroupBuys/img/kefu.png" class="kefu" />
		<img src="/Public/GroupBuys/img/music.png" class="music on" />
	</div>
	<!--参团-->
	<div class="cantuan">
		<span class="goto">
			<span>立即<br />参加</span>
		</span>
	</div>

	<div class="mymask">
		<div class="mymask_tap">

		</div>
	</div>
	<div class="fillinfor">
		<div class="shopname">
			<?php echo ($sinfo["Storename"]); ?>
		</div>
		<p>请输入姓名电话参加活动</p>
		<ul>
			<li>
				<label>您的姓名</label>
				<input type="text" class="buyname" name="buyname" placeholder="填写姓名"/>
			</li>
			<li>
				<label>手机号码</label>
				<input type="text" class="buyphone" name="buyphone" placeholder="填写电话"/>
			</li>
		</ul>
		<div class="okgo">
			确认参加
		</div>
	</div>
	<div class="sharemask">
		<img src="/Public/GroupBuys/img/share.png" alt="">
	</div>


	<!--音乐-->
	<audio id="myAudio" src="" autoplay="autoplay" loop="loop"></audio>
	<!--客服-->
	<div class="kefudiv">
		<div class="mask"></div>
		<div class="kefucontent">
			<p>扫描下方二维码联系客服</p>
			<img src="<?php echo ($sinfo["Qrimg"]); ?>" />
			<p class="itphone">客服电话：<span class="phone"><?php echo ($sinfo["Phone"]); ?></span></p>
		</div>
	</div>

	<div class="wxgghdiv">
		<div class="mask"></div>
		<div class="kefucontent">
			<p>关注公众号</p>
			<img src="<?php echo ($sinfo["Qrimg"]); ?>" />
			<p>长按图片可关注公众号</p>
		</div>
	</div>

	<div class="showgoto">
		<img src="/Public/GroupBuys/img/tel.png" class="showphone" alt="">
		<img src="/Public/GroupBuys/img/close.png" class="showclose" alt="">
		<span class="showtext"><span>嗨!&emsp;<?php echo ($uname); ?></span>&emsp;<?php echo ($Title); ?></span>
		<span class="sureget">立即参与</span>
	</div>

	<!-- 微信提示框 -->
	<div id="notice" class="wxtip" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content"></p>
		</div>
	</div>

	<div class="weui_dialog_confirm wxtip" id="confirm" style="display: none;">
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


	<div class="weui_dialog_alert wxtip" id="alert" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">提示信息</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog primary" id='alertenter'>确定</a>
			</div>
		</div>
	</div>

	<div id="waiting" class="weui_loading_toast wxtip" style="display:none;">
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
	<!-- 微信提示框end -->
	<!-- 无团购信息时 -->
	<?php if($hasgroup == 'false'): ?><div class="shownogroupinfo">
		</div><?php endif; ?>

	<!--红包掉落-->
	<div class="hbcontent">
		<img src="/Public/GroupBuys/img/hb_bg.png" class="hb_bg"/>
		<div class="">
			<img src="/Public/GroupBuys/img/unlook.png" class="unlook" />
			<div class="look">
				<img src="/Public/GroupBuys/img/look.png" class="" />
				<div>
					<p>恭喜获得红包</p>
					<span class="redprice">0</span>
				</div>
				<p class="closeredinfo">关闭</p>
			</div>
		</div>
	</div>

</body>
<script type="text/javascript">
var intdiff = <?php echo ($time); ?>;
var imgroot_url ="/Public/GroupBuys/";
var autio_url ="<?php echo ($groupinfo['Bgmsc']); ?>";
var hasgroup ="<?php echo ($hasgroup); ?>";
var oid = "";
var gid = "<?php echo ($groupinfo['GroupId']); ?>";
var createorder_url="<?php echo U('GroupBuys/createorder');?>";
var payLock=false;
var wxpaydata='';
var subscribe = "<?php echo ($subscribe); ?>";
var sharesuccess_url ="<?php echo U('GroupBuys/sharesuccess');?>";
var saveremark_url ="<?php echo U('GroupBuys/saveremark');?>";
</script>
<script src="/Public/GroupBuys/js/tuan.js?v=2.4" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
wx.config(<?php echo ($wxJSSDKConfigStr); ?>);
wx.ready(function() {
	wx.hideAllNonBaseMenuItem();
	wx.showMenuItems({
		menuList: ['menuItem:share:appMessage','menuItem:share:timeline'] // 要显示的菜单项，所有menu项见附录3
	});

	// 分享到朋友圈
	wx.onMenuShareTimeline({
		title: '<?php echo ($shareinfo["title"]); ?>',
		link: '<?php echo ($shareinfo["link"]); ?>',
		imgUrl: '<?php echo ($shareinfo["img"]); ?>',
		success: function() {
			// alert('分享成功');
			sharesuccess();
			$('.sharemask').css('display','none');
			tips('notice','分享成功',1500,'weui_icon_toast');
		},
		cancel: function(){
			// alert('取消分享');
			$('.sharemask').css('display','none');
			tips('notice','取消分享',1500,'weui_icon_notice');
		},
		fail: function(res){
			// alet('分享失败');
			$('.sharemask').css('display','none');
			tips('notice','分享失败',1500,'weui_icon_notice');
		}
	});
	// 分享到朋友
	wx.onMenuShareAppMessage({
		title: '<?php echo ($shareinfo["title"]); ?>',
		desc: '<?php echo ($shareinfo["desc"]); ?>',
		link: '<?php echo ($shareinfo["link"]); ?>',
		imgUrl: '<?php echo ($shareinfo["img"]); ?>',
		success: function() {
			// alert('分享成功');
			sharesuccess();
			$('.sharemask').css('display','none');
			tips('notice','分享成功',1500,'weui_icon_toast');
		},
		cancel: function(){
			// alert('取消分享');
			$('.sharemask').css('display','none');
			tips('notice','取消分享',1500,'weui_icon_notice');
		},
		fail: function(res){
			// alet('分享失败');
			$('.sharemask').css('display','none');
			tips('notice','分享失败',1500,'weui_icon_notice');
		}
	});
})
</script>
</html>