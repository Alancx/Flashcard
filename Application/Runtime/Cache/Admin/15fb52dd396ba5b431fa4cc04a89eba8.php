<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>推广信息</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
	</head>
	<style type="text/css">
		ul,li,p{margin: 0 ; padding: 0 ; list-style: none;}
		body{font-size: 14px;background: #fff;}
		.mui-bar-nav{
			background: #fff !important;
			box-shadow: none !important;
			border-bottom: 2px solid #EFEFF4;
			padding: 0;
		}
		.mui-bar-nav ul{
			width: 100%;
			height: 44px;
			float: left;
			line-height: 44px;
			text-align: center;
		}
		.mui-bar-nav ul li{
			width: 50%;
			height: 100%;
			float: left;
		}
		.checkli{
			color: #FF4E00;
			border-bottom: 1px solid #FF4E00;
			font-weight: bolder;
		}
		.mui-content{background: #fff;}
		.ewmdiv{
			/*padding: 20px 40px;*/
			margin-top: 40px;
		}
		.ewmdiv>img{
			width: 260px;
			height: 260px;
			display: block;
			margin: auto;
			margin-bottom: 20px;
		}
		.ewmdiv>p{
			text-align: center;
			font-size: 12px;
		}
		.ewmdiv>div{
			text-align: center;
			margin: 10px 0;
		}
		.mma{
			color: #FF4E00;
			font-size: 20px;
		}
	</style>
	<body>
		<nav class="mui-bar mui-bar-nav">
			<ul class="navul">
				<li class="checkli">商家入驻</li>
				<li>代理商</li>
			</ul>
		</nav>
		<div class="mui-content">
			<!--商家入驻-->
			<div class="" data-oo='tabitem'>
				<div class="ewmdiv">
					<img src="<?php echo U('Tuier/getuiqr');?>?type=store&Invcode=<?php echo ($Invcode); ?>" />
					<p>商家入驻请扫描上方二维码</p>
					<div class="">
						<label>邀请码：</label>
						<span class="mma"><?php echo ($Invcode); ?></span>
					</div>
				</div>


			</div>
			<!--分销代理-->
			<div class="" data-oo='tabitem' hidden="">
				<div class="ewmdiv">
					<img src="<?php echo U('Tuier/getuiqr');?>?type=people&Invcode=<?php echo ($Invcode); ?>" />
					<p>代理商请扫描上方二维码</p>
					<div class="">
						<label>邀请码：</label>
						<span class="mma"><?php echo ($Invcode); ?></span>
					</div>
				</div>
			</div>

		</div>


		<script src="/Public/newhome/js/mui.min.js"></script>
		<script src="/Public/newadmin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		<script type="text/javascript">
			//切换--商家、代理人
			$(document).on('tap','.navul li',function(){
				$(this).addClass('checkli').siblings().removeClass('checkli');
				var i=$(this).index();
				$('[data-oo="tabitem"]').fadeOut(0).eq(i).fadeIn(0)
			})
		</script>
	</body>

</html>