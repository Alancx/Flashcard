<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>


	<meta charset="UTF-8">
	<title>订单</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="/Public/home/css/myorder.css"/>
	<link rel="stylesheet" type="text/css" href="/Public/home/css/font_gpke.css"/>
	

	<style type="text/css">
		.bot-ul-li {
			color: #FF4E00 !important;
		}
		
		.bot-ul-li .home_icon:before {
			content: "\a002";
		}
		
		.bot-ul-li .huod_icon:before {
			content: "\a004";
		}
		
		.bot-ul-li .order_icon:before {
			content: "\a006";
		}
		
		.bot-ul-li .mine_icon:before {
			content: "\a008";
		}
	</style>

<body>
<div class="showcontents">
</div>


	<!--订单-->
	<ul class="ordernav">
		<li class="check">
			<span class="ggg icon_allorder"></span>
			<span>全部</span>
		</li>
		<li>
			<span class="ggg icon_dfukuan"></span>
			<span>待付款</span>
		</li>
		<li>
			<span class="ggg icon_dshiyong"></span>
			<span>待使用</span>
		</li>
		<li>
			<span class="ggg icon_dpingjia"></span>
			<span>待评价</span>
		</li>
		<li>
			<span class="ggg icon_shouhou"></span>
			<span>退款/售后</span>
		</li>
	</ul>
	
	
	<div class="" data-oo="item">
		
		<!--待使用-->
		<!-- <div class="orderdiv">
			<img src="/Public/home/img/img1.jpg" class="shoplogo" />
			<div class="shoptitle">
				<div class="mui-ellipsis shopname">百乐川烤鱼店百乐川烤鱼店百乐川烤鱼店鱼店</div>
				<small class="mui-icon mui-icon-arrowright"></small>
				<span class="mui-pull-right dshiyong">待使用</span>
			</div>
			<div class="contdiv">
				<p>
					<span>烤清江鱼</span>
					<span class="mui-pull-right">x1</span>
				</p>
				<p>
					<span>烤清江鱼</span>
					<span class="mui-pull-right">x1</span>
				</p>
				<p>
					<span>烤清江鱼</span>
					<span class="mui-pull-right">x1</span>
				</p>
				<div id="">
					<span>...</span>
					<span class="mui-pull-right">
						共11件商品，实付￥128.3
					</span>
				</div>
			</div>
			<div class="bottomdiv">
				<span class="hexiao">核销码</span>
				<span>退款</span>
			</div>
		</div> -->
		
		<!--待付款-->
		<?php if($type == 1): ?><div class="orderdiv" >
				<img src="<?php echo ($sinfo["Slogo"]); ?>" class="shoplogo" />
				<div class="shoptitle" data-url="<?php echo U('Index/index');?>">
					<div class="mui-ellipsis shopname" ><?php echo ($sinfo["storename"]); ?></div>
					<small class="mui-icon mui-icon-arrowright"></small>
					<span class="mui-pull-right dshiyong">待付款</span>
				</div>
				<div class="contdiv">
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p>
							<span><?php echo ($vo["ProName"]); ?></span>
							<span class="mui-pull-right">x<?php echo ($vo["Num"]); ?></span>
						</p><?php endforeach; endif; else: echo "" ;endif; ?>
					<div id="">
						<span>...</span>
						<span class="mui-pull-right">
							共<?php echo ($num); ?>件商品，实付￥<?php echo ($vo["total"]); ?>
						</span>
					</div>
				</div>
				<div class="bottomdiv">
					<small class="mui-icon mui-icon-trash mui-pull-left"></small>
					<span>去付款</span>
				</div>
			</div><?php endif; ?>
		<!--待评价-->
		<!-- <div class="orderdiv">
			<img src="/Public/home/img/img1.jpg" class="shoplogo" />
			<div class="shoptitle">
				<div class="mui-ellipsis shopname">百乐川烤鱼店百乐川烤鱼店百乐川烤鱼店鱼店</div>
				<small class="mui-icon mui-icon-arrowright"></small>
				<span class="mui-pull-right dshiyong">待评价</span>
			</div>
			<div class="contdiv">
				<p>
					<span>烤清江鱼</span>
					<span class="mui-pull-right">x1</span>
				</p>
				<p>
					<span>烤清江鱼</span>
					<span class="mui-pull-right">x1</span>
				</p>
				<p>
					<span>烤清江鱼</span>
					<span class="mui-pull-right">x1</span>
				</p>
				<div id="">
					<span>...</span>
					<span class="mui-pull-right">
						共11件商品，实付￥128.3
					</span>
				</div>
			</div>
			<div class="bottomdiv qupingjia">
				<small class="mui-icon mui-icon-trash mui-pull-left"></small>
				<span >去评价</span>
			</div>
		</div> -->
		
		
		
		
		
	</div>
	
	
	
	
	<div class="" data-oo="item" hidden="">
		<!---->
		<?php if($type == 1): ?><div class="orderdiv">
				<img src="<?php echo ($sinfo["Slogo"]); ?>" class="shoplogo" />
				<div class="shoptitle" data-url="<?php echo U('Index/index');?>">
					<div class="mui-ellipsis shopname" ><?php echo ($sinfo["storename"]); ?></div>
					<small class="mui-icon mui-icon-arrowright"></small>
					<span class="mui-pull-right dshiyong">待付款</span>
				</div>
				<div class="contdiv">
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p>
							<span><?php echo ($vo["ProName"]); ?></span>
							<span class="mui-pull-right">x<?php echo ($vo["Num"]); ?></span>
						</p><?php endforeach; endif; else: echo "" ;endif; ?>
					<div >
						<span>...</span>
						<span class="mui-pull-right">
							共<?php echo ($num); ?>件商品，实付￥<?php echo ($vo["total"]); ?>
						</span>
					</div>
				</div>
				<div class="bottomdiv">
					<small class="mui-icon mui-icon-trash mui-pull-left"></small>
					<span>去付款</span>
				</div>
			</div><?php endif; ?>
		
	</div>
	



	


    <nav class="mui-bar mui-bar-tab">
        <a id="defaultTab" class="mui-tab-item bot-ul-li" href="index.html">
            <span class="mui-icon ggg home_icon"></span>
            <span class="mui-tab-label">首页</span>
        </a>
        <a class="mui-tab-item" href="activity.html">
            <span class="mui-icon ggg huod_icon"></span>
            <span class="mui-tab-label">活动</span>
        </a>
        <a class="mui-tab-item" href="myorder.html">
            <span class="mui-icon ggg order_icon"></span>
            <span class="mui-tab-label">订单</span>
        </a>
        <a class="mui-tab-item" href="mycenter.html">
            <span class="mui-icon ggg mine_icon"></span>
            <span class="mui-tab-label">我的</span>
        </a>
    </nav>

</body>

<script src="/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/home/js/mui.min.js"></script>
<script src="/Public/home/js/myorder.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	mui.init()
	
	$(".mui-bar a:first-child").on("tap",function(){
		location.href="<?php echo U('Index/index');?>";
	})
	$(".mui-bar a:nth-child(2)").on("tap",function(){
		location.href="<?php echo U('Index/activity');?>";
	})
	$(".mui-bar a:nth-child(3)").on("tap",function(){
		location.href="<?php echo U('Index/myorder');?>";
	})
	$(".mui-bar a:last-child").on("tap",function(){
		location.href="<?php echo U('Index/mycenter');?>";
	})
	$(".shoptitle").on("tap",function() {
		window.location.href = $(this).attr('data-url');
	})

</script>
<script type="text/javascript">
		//核销码
		$(".hexiao").on("tap",function(){
			location.href="myserviceindex.html"
		})
		
		
	</script>


</html>