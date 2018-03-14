<?php if (!defined('THINK_PATH')) exit();?><html>

	<head>
		<title>推广详情</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />

		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/newhome/css/merchant.css" />
	</head>
	</head>

	<body style="font-size: 14px;">
		<ul class="mui-table-view">
			
				<li class=" mui-media  mui-table-view-cell ">
					<img class="mui-media-object mui-pull-left" id="topImg" src="/Public/Admin/Admin/img/headimg/<?php echo ($peoplelist["HeadImgUrl"]); ?>">
					<div class="mui-media-body">
						<span class="titles"><?php echo ($peoplelist["TrueName"]); ?></span>
						<p class="mui-ellipsis"><?php echo ($peoplelist["userName"]); ?></p>
					</div>
				</li>
		

		</ul>

		<div style="padding: 13px;">
			代理的所有商家&nbsp; <?php echo ($scount); ?>家
		</div>
		
		<?php if(is_array($storelist)): foreach($storelist as $key=>$sl): ?><ul class="mui-table-view">
				<li class=" mui-media  mui-table-view-cell ">
					<img class="mui-media-object mui-pull-left" src="<?php echo ($sl["Slogo"]); ?>">
					<div class="mui-media-body">
						<span class="titles"><?php echo ($sl["storename"]); ?></span>
						<p class="mui-ellipsis"><?php echo ($sl["province"]); echo ($sl["city"]); echo ($sl["area"]); echo ($sl["addr"]); ?></p>
					</div>
					<hr />
					<!-- <div class="mer-second">
						<p>历史营业额 (元) : <?php echo ($sl["son"]["all"]); ?></p>
						<p>当月营业额 (元) : <?php echo ($sl["son"]["month"]); ?></p>
						<p>今日营业额 (元) : <?php echo ($sl["son"]["today"]); ?></p>
						<p>&nbsp;&nbsp; 我的佣金(元) : <?php echo ($sl["son"]["myMoney"]); ?></p>
					</div> -->
				</li>
			</ul><?php endforeach; endif; ?>
		<!--<ul class="mui-table-view">
			<li class=" mui-media  mui-table-view-cell ">
				<img class="mui-media-object mui-pull-left" src="/Public/Images/Wap/head.jpg">
				<div class="mui-media-body">
					<span class="titles">悠闲地吃货大餐厅</span>
					<p class="mui-ellipsis">河南省郑州市金水区文化路</p>
				</div>
				<hr />
				<div class="mer-second">
					<p>历史营业额(元) : 20000.00</p>
					<p>当月营业额(元) : 20000.00</p>
					<p>今日营业额(元) : 2000.00</p>
					<p>&nbsp;&nbsp; 我的佣金(元) : 2000.00</p>
				</div>
			</li>
		</ul>-->

	</body>

</html>