<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?php echo ($Title); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" type="text/css" href="/Public/GroupBuys/css/mycenter.css" />
	<script src="/Public/GroupBuys/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<!---->
	<div class="shopinfor">
		<img src="<?php echo ($sinfo['Logoimg']); ?>" />
		<div class="">
			<div><?php echo ($sinfo['Storename']); ?></div>
			<p><?php echo ($sinfo['Address']); ?></p>
			<p class="phone"><?php echo ($sinfo['Phone']); ?></p>
		</div>
	</div>
	<!---->
	<div class="content">
		<ul class="tabul">
			<li class="click"><span>参加的团</span></li>
			<li class=""><span>红包奖励</span></li>
		</ul>
		<div class="showinfo" data-oo='tabdiv'>
			<?php if(is_array($ginfo)): foreach($ginfo as $key=>$gi): ?><div class="itinfor">
					<img src="<?php echo ($gi['Logoimg']); ?>" />
					<div class="">
						<div><?php echo ($gi['Grouptitle']); ?></div>
						<p><?php echo ($gi['Name']); ?> <?php echo ($gi['Phone']); ?></p>
						<p><?php echo ($gi['Cdata']); ?> 参团</p>
					</div>
				</div><?php endforeach; endif; ?>
			<!-- <div class="itinfor">
			<img src="img/20180115143604.jpg" />
			<div class="">
			<div>千人团购￥9.9包邮，美肤大礼包4合一，新年大礼盒</div>
			<p>崔小传 139****3717</p>
			<p>2018.1.16 11:28 参团</p>
		</div>
	</div> -->
</div>
<div class="showinfo" data-oo='tabdiv' hidden="">
	<?php if(is_array($rinfo)): foreach($rinfo as $key=>$ri): ?><div class="hongbao">
			<img src="/Public/GroupBuys/img/hongbao.png"/>
			<div class="hbinfor">
				<p>千人团购分享红包</p>
				<span class="color">￥<?php echo number_format(($ri['Price']),2); ?></span><span>[<?php echo ($ri['GetRedCode']); ?>]</span>
			</div>
			<div class="time">
				<?php if($ri["Status"] == '0' ): ?><span class="color">未领取</span>
					<?php else: ?>
					<span class="color">已领取</span><?php endif; ?>
				<p><?php echo ($ri['Cdata']); ?></p>
			</div>
		</div><?php endforeach; endif; ?>
	<!-- <div class="hongbao">
	<img src="/Public/GroupBuys/img/hongbao.png"/>
	<div class="hbinfor">
	<p>千人团购分享红包</p>
	<span class="color">￥0.5</span><span>[HB868]</span>
</div>
<div class="time">
<span class="color">未领取</span>
<p>2018.1.16 14:56</p>
</div>
</div> -->
</div>
</div>
</body>
<script type="text/javascript">
$(".tabul li").on('click', function() {
	$(this).addClass('click').siblings().removeClass('click');
	var i = $(this).index();
	$('[data-oo="tabdiv"]').css('display', 'none').eq(i).css('display', 'block');
})
</script>
</html>