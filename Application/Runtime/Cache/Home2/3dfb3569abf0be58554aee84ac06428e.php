<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/note/css/wode.css" />
	</head>

	<body>
		<div class="top">
			<b>我的笔记</b>
			<div class="zhong">
				<a href="<?php echo U('Index/note');?>">新建笔记</a>
			</div>
		</div>
		<?php if(is_array($mod)): foreach($mod as $key=>$list): ?><div class="content">
			<span><?php echo ($list['addtime']); ?></span>
			<div class="content-text mui-table-view-cell">
				<div class="mui-slider-handle">
					<div class="title">
						<?php echo ($list['title']); ?>
					</div>
					<div class="title-text">
						<?php echo ($list['content']); ?> <br>
					</div>
				</div>
				<div class="mui-slider-right mui-disabled aa">
					<a  href="<?php echo U('Index/delete');?>?id=<?php echo ($list['id']); ?>"class="mui-btn"><span>删除</span></a>
				</div>
			</div>
		</div><?php endforeach; endif; ?>
		
	</body>
	<script src="/Public/note/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		mui.init();
	</script>

</html>