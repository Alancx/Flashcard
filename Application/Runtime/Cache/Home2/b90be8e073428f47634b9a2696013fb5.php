<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>修改笔记</title>

		<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/note/css/index.css" />

	</head>
	<body>
		
		<div class="top">
			<b>修改笔记</b>
		</div>
		<form action="<?php echo U('Index/doupdate');?>" method="post">
		<div class="zhong">
			<div class="title">
				标题
				<input type="text" name="title" value="<?php echo ($mod['title']); ?>"/>
			</div>
			<div class="content">
				内容
			<textarea rows="19" cols="20" name="content" ><?php echo ($mod['content']); ?></textarea>
			</div>
		</div>
		<input type="hidden" value="<?php echo ($mod['id']); ?>" name="id">
		<div class="bottom">
			<button type="submit" class="bottom-right" style="line-height:100%;">修改</button>
		</div>
		</form>
		
	</body>
	<script src="/Public/note/js/mui.min.js"></script>
	<script src="/Public/note/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		mui.init();
	</script>
</html>