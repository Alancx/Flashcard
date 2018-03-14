<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>我的收藏</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/home/css/mui.min.css" rel="stylesheet" />
	</head>
<style type="text/css">
	
	.delebtn a span{
		color: #fff;
		background: #ff0000;
		padding: 4px 10px;
		border-radius:100px ;
	}
	.mui-media-body p{
		font-size: 12px;
	}
	.mui-media-object{
		width: 80px !important;
		height: 80px !important;
		max-width: 80px !important;
	}
	.jiage{
		color: #ff0000;
	}
	.jiage:before{
		content: "￥";
		font-size: 12px;
	}
</style>
	<body>
	<p style="text-align: center; margin-top: 10px; font-size: 12px;">我收藏的美食，左滑可以取消收藏。</p>
		<?php if(is_array($collect)): $i = 0; $__LIST__ = $collect;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($msg["type"] == 1): ?><ul class="mui-table-view total" >
					<li class=" mui-media  mui-table-view-cell ">
						<a href="javascript:;" class="mui-slider-handle" data-id="<?php echo ($vo["ProId"]); ?>">
							<img class="mui-media-object mui-pull-left" src="<?php echo ($vo["ProLogoImg"]); ?>">
							<div class="mui-media-body">
								<div class="mui-ellipsis"><?php echo ($vo["ProName"]); ?></div>
								<p><?php echo ($msg["sinfo"]["storename"]); ?></p>
								<span class="jiage"><?php echo ($vo["Price"]); ?></span>
							</div>
						</a>
						<div class="mui-slider-right mui-disabled delebtn" data-oo="<?php echo ($vo["ProId"]); ?>">
							<a class="mui-btn cancle">
							<span>取消收藏</span>
							</a>
					    </div>
					</li>
				</ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</body>
	<script src="/Public/home/js/mui.min.js"></script>
	<script src="/Public/home/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
        mui.init();
	</script>
	<script type="text/javascript">
		$('.mui-slider-handle').on("tap",function() {
			window.location.href = "<?php echo U('Goods/goods');?>?pid="+$(this).attr('data-id');
		})
		/*$('.mui-media  mui-table-view-cell').on("tap",function() {
			window.location.href = "<?php echo U('Index/index');?>";
		})*/
	</script>
	<script type="text/javascript">
		//取消收藏操作
            $(document).on("tap",".cancle span",function () {
                var _this = $(this);
				var ProId = _this.parent().parent().attr('data-oo');//获取ProId的值
                $.ajax({
                    url: "<?php echo U('Collect/cancel');?>",
                    type: "post",
                    data: {
                        ProId: ProId,
                    },
                    dataType: "json",
                    success : function(msg) {
                        if(msg.status=="success") {
                            _this.parents(".mui-table-view-cell").remove();
                        }
                    }
                })
            })
	</script>

</html>