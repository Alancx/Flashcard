<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>我的关注</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
	</head>
<style type="text/css">
	
	.delebtn a span{
		color: #fff;
		background: #ff8400;
		padding: 4px 10px;
		border-radius:100px ;
	}
	.mui-media-body p{
		font-size: 12px;
	}
</style>
	<body>
		<p style="text-align: center; margin-top: 10px; font-size: 12px;">我关注的店铺，左滑可以取消关注。</p>
		<ul class="mui-table-view">
			<li class=" mui-media  mui-table-view-cell ">
				<a href="javascript:;" class="mui-slider-handle">
					<img class="mui-media-object mui-pull-left" src="/Public/home/img/spimg.jpg">
					<div class="mui-media-body">
						<span>鲜味道烤鱼店</span>
						<p class="mui-ellipsis">金水区文化路农业路河南农业大学对面1209号对面1209号对面1209号</p>
					</div>
				</a>
				<div class="mui-slider-right mui-disabled delebtn">
					<a class="mui-btn"><span>取消关注</span></a>
				</div>
			</li>
			<li class=" mui-media  mui-table-view-cell ">
				<a href="javascript:;" class="mui-slider-handle">
					<img class="mui-media-object mui-pull-left" src="/Public/home/img/img1.jpg">
					<div class="mui-media-body">
						<span>玉膳坊茶酒楼</span>
						<p class="mui-ellipsis">金水区文化路农业路河南农业大学对面1209号对面1209号对面1209号</p>
					</div>
				</a>
				<div class="mui-slider-right mui-disabled delebtn">
					<a class="mui-btn"><span>取消关注</span></a>
				</div>
			</li>
			<li class=" mui-media  mui-table-view-cell ">
				<a href="javascript:;" class="mui-slider-handle">
					<img class="mui-media-object mui-pull-left" src="/Public/home/img/4.jpg">
					<div class="mui-media-body">
						<span>信阳家常菜</span>
						<p class="mui-ellipsis">金水区文化路农业路河南农业大学对面1209号对面1209号对面1209号</p>
					</div>
				</a>
				<div class="mui-slider-right mui-disabled delebtn">
					<a class="mui-btn"><span>取消关注</span></a>
				</div>
			</li>
		</ul>

		<script src="/Public/home/js/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>