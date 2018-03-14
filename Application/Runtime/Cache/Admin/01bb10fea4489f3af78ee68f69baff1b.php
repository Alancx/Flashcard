<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>推广管理</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
		<link href="/Public/newhome/css/merchant.css" rel="stylesheet" />

	</head>
	<style type="text/css">
		ul,
		li,
		p {
			margin: 0;
			padding: 0;
			list-style: none;
		}
		
		body {
			font-size: 14px;
		}
		
		.mui-bar-nav {
			background: #fff !important;
			box-shadow: none !important;
			border-bottom: 2px solid #EFEFF4;
		}
		
		.mui-bar-nav ul {
			width: calc(100% - 50px);
			height: 44px;
			float: left;
			line-height: 44px;
			text-align: center;
		}
		
		.mui-bar-nav ul li {
			width: 50%;
			height: 100%;
			float: left;
		}
		
		.checkli span {
			color: #FF4E00;
			border-bottom: 2px solid #FF4E00;
			font-weight: bolder;
		}
		
		.mui-bar-nav ul li span {
			display: inline-block;
			height: 100%;
			padding: 0 6px;
		}
		/*.mui-bar-nav ul li span>small{
			border: 1px solid #FF4E00;
			border-radius: 100px;
			padding: 0px 4px;
		}*/
		
		.tuig {
			display: inline-block;
			width: 50px;
			height: 44px;
			line-height: 44px;
			text-align: center;
		}
		
		.tuig span {
			background: #FF4E00;
			padding: 4px 8px;
			border-radius: 100px;
			color: #fff;
		}
		/**/
		
		.itemdiv {
			padding: 10px;
			background: #fff;
			overflow: hidden;
			position: relative;
		}
		
		.itemdiv:after {
			position: absolute;
			right: 0;
			bottom: 0;
			left: 15px;
			height: 1px;
			content: '';
			-webkit-transform: scaleY(.5);
			transform: scaleY(.5);
			background-color: #c8c7cc;
		}
		
		.itemdiv>img {
			width: 40px;
			height: 40px;
			display: block;
			float: left;
			border-radius: 10px;
			margin-right: 10px;
		}
		
		.itemdiv>div {
			width: calc(100% - 60px);
			height: 40px;
			float: left;
		}
		
		.itemdiv>div>p {
			font-size: 12px;
		}
		
		.people>img {
			border-radius: 100%;
		}
	</style>

	<body style="padding-bottom:50px">
		<nav class="mui-bar mui-bar-nav">
			<ul class="navul">
				<li class="checkli"><span>商家<small>（<?php echo ($scount); ?>）</small></span></li>
				<li><span>代理人<small>（<?php echo ($pcount); ?>）</small></span></li>
			</ul>
			<div class="tuig"><span>推广</span></div>
		</nav>
		<div class="mui-content" style="padding-top:30px;">
			<!--商家-->
			<ul class="mui-table-view">
			    <li class="mui-table-view-cell mui-media">
			        <a href="javascript:;">
			        	<?php if($account['Level'] == '1'): ?><img class="mui-media-object mui-pull-left" src="/Public/Images/medals/bronze.png"><?php elseif($account['Level'] == '2'): ?><img class="mui-media-object mui-pull-left" src="/Public/Images/medals/silver.png"><?php elseif($account['Level'] == '3'): ?><img class="mui-media-object mui-pull-left" src="/Public/Images/medals/gold.png"><?php endif; ?>
			            
			            <div class="mui-media-body" style="float:left; margin-left: 35px">
			                <?php echo ($account["TrueName"]); ?>&emsp; <span class="mui-navigate"> <?php if($account['Level'] == '1'): ?>铜牌会员<?php elseif($account['Level'] == '2'): ?>银牌会员<?php elseif($account['Level'] == '3'): ?>金牌会员<?php endif; ?></span>
			                <p class='mui-ellipsis'>账号 : &emsp;<?php echo ($account["Account"]); ?></p>
			            </div>
			        </a>
			    </li>
			</ul>
			<div class="" data-oo='tabitem'>
				<?php if(is_array($storelist)): foreach($storelist as $key=>$sl): ?><!--<div class="itemdiv shop">
						<img src="<?php echo ($sl["storename"]); ?>" />
						<div class="">
							<div class="mui-ellipsis"><?php echo ($sl["storename"]); ?></div>
							<p class="mui-ellipsis"><?php echo ($sl["province"]); echo ($sl["city"]); echo ($sl["area"]); echo ($sl["addr"]); ?></p>
						</div>
					</div>-->
					<ul class="mui-table-view">
						<li class=" mui-media  mui-table-view-cell ">
							<img class="mui-media-object mui-pull-left" src="<?php echo ($sl["Slogo"]); ?>">
							<div class="mui-media-body">
								<span class="titles"><?php echo ($sl["storename"]); ?></span>
								<p class="mui-ellipsis"><?php echo ($sl["province"]); echo ($sl["city"]); echo ($sl["area"]); echo ($sl["addr"]); ?></p>
							</div>
							<hr />
							<div class="mer-second">
								<p>历史营业额 (元) : <?php echo ($sl["son"]["all"]); ?></p>
								<p>当月营业额 (元) : <?php echo ($sl["son"]["month"]); ?></p>
								<p>今日营业额 (元) : <?php echo ($sl["son"]["today"]); ?></p>
								<p>&nbsp;&nbsp; 我的佣金(元) : <?php echo ($sl["son"]["myMoney"]); ?></p>
							</div>
						</li>
					</ul><?php endforeach; endif; ?>
				<nav class="mui-bar mui-bar-tab ">
					<a class="mui-tab-item mui-active" id="a1">
						<span>佣金已高达 : <?php echo ($sum); ?></span>
						<button type="button" class="mui-btn mui-btn-outlined">立即提现</button>
					</a>
				</nav>
			</div>
			<!--代理人-->
			<div class="" data-oo='tabitem' hidden="">
				<?php if(is_array($peoplelist)): foreach($peoplelist as $key=>$pl): ?><div class="itemdiv people" value="<?php echo ($pl["id"]); ?>">
						<img src="/Public/Admin/Admin/img/headimg/<?php echo ($pl["HeadImgUrl"]); ?>" />
						<div class="">
							<div class="mui-ellipsis"><?php echo ($pl["TrueName"]); ?></div>
								<span class="mui-icon mui-icon-forward" style="float:right;"></span>
								<span style="float:right;margin-right:20px"> 推广商家 <?php echo ($pl["scount"]); ?>家 </span>
							<p class="mui-ellipsis"><?php echo ($pl["userName"]); ?></p>
						</div>
					</div><?php endforeach; endif; ?>
			</div>
		</div>

		<script src="/Public/newhome/js/mui.min.js"></script>
		<script src="/Public/newadmin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		
		<script type="text/javascript">
			//切换--商家、代理人
			$(document).on('tap', '.navul li', function() {
				$(this).addClass('checkli').siblings().removeClass('checkli');
				var i = $(this).index();
				$('[data-oo="tabitem"]').fadeOut(0).eq(i).fadeIn(0)
			})
			//跳转---推广
			$(document).on('tap', '.tuig', function() {
				location.href = "<?php echo U('Tuier/tuinfo');?>";
			})
			$(document).on('tap','.mui-btn-outlined',function(){
				location.href = "<?php echo U('Tuier/getMoney');?>";
			})
			//代理人----商家
			$(document).on('tap','.people',function() {
				var id = $(this).attr('value');
				location.href = "<?php echo U('Tuier/agent');?>?id="+id;
			})
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				//获取代理人的唯一标识
				var invcode = $(".people").attr('value');
				$.ajax({
					url: "<?php echo U('Tuier/agent');?>",
					type: 'post',
					data: 'Invcode='+invcode,
					dataType: 'json',
					success: function(msg) {
						
					}
				})
				// console.log(invcode);
			})
		</script>
	</body>

</html>