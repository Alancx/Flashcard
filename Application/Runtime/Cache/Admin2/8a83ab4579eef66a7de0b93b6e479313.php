<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/note/css/index.css" />
		<style>
			.ren{
				padding-left:10px;
			}
		</style>
	</head>

	<body>
		<div class="top">
			<b>修改任务</b>
		</div>
		<div class="ren" styel="padding-left:10px;">
			<span>接收人</span>
			<div class="tianlist">
				<?php if(is_array($row)): foreach($row as $key=>$list): if($list["type"] == '1'): ?><span data-uid='<?php echo ($list["openid"]); ?>' class="chek"><?php echo ($list['name']); ?></span>
					<?php else: ?>
					<span data-uid='<?php echo ($list["openid"]); ?>'><?php echo ($list['name']); ?></span><?php endif; endforeach; endif; ?>
			</div>
		</div>
		<div class="zhong">
			<div class="title">
				标题
				<input type="text" id="title" name="title" value="<?php echo ($value['title']); ?>" />
			</div>
			<div class="content">
				内容
				<textarea id="content" rows="19" cols="20" name="content">
					<?php echo ($value['content']); ?>
				</textarea>
			</div>
			<input type="hidden" id="id" value="<?php echo ($value['id']); ?>">
		</div>
		
		<div class="bottom">
			<button class="bottom-right fb">直接发布</button>
			<button class="bottom-left bc" style="color:#000000; border:0px; line-height:100%;">保存</button>
		</div>
	</body>
	<script src="/Public/note/js/mui.min.js"></script>
	<script src="/Public/note/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		mui.init();		
	</script>
	<script type="text/javascript">
		$(document).on("tap",".tianlist span",function(){
			$(this).toggleClass("chek");	
		})
	</script>
	<script>
	$('.fb').on('tap',function(){
		var chec=$('.chek');
		var uidlist=new Array();
		$.each(chec,function(index,item){
			uidlist.push($(item).attr('data-uid'));
		})
		// console.log(uidlist);
		// return false;
		var title = $('#title').val();		
		var content = $('#content').val();
		var id = $('#id').val();
		var senddata = {
			id:id,
			title:title,
			content:content,
			idlist:uidlist,
		}
		// console.log(senddata);
		$.ajax({
	    url:"<?php echo U('Index/doupdata');?>",
	    type:"post",	    
	    data:senddata,
	    dataType:"json",
	    success:function(msg){
	      // hidetips('waiting');
	      if (msg.status=='success') {	
	      	 mui.toast('发布成功');
	      	 setTimeout(function(){
	      	 	self.location=document.referrer;
	      	 },2000);	      	 
	      } else {
	         mui.toast('发布失败');
	      }
	    }
    	})
	})	
	</script>
	<script>
	$('.bc').on('tap',function(){
		var chec=$('.chek');
		var uidlist=new Array();
		$.each(chec,function(index,item){
			uidlist.push($(item).attr('data-uid'));
		})
		// console.log(uidlist);
		// return false;
		var title = $('#title').val();		
		var content = $('#content').val();
		var id = $('#id').val();
		var senddata = {
			id:id,
			title:title,
			content:content,
			idlist:uidlist,
		}
		// console.log(senddata);
		$.ajax({
	    url:"<?php echo U('Index/updata');?>",
	    type:"post",	    
	    data:senddata,
	    dataType:"json",
	    success:function(msg){
	      // hidetips('waiting');
	      if (msg.status=='success') {	
	      	 mui.toast('保存成功');
	      	 setTimeout(function(){
	      	 	self.location=document.referrer;
	      	 },2000);	      	 
	      } else {
	         mui.toast('保存失败');

	      }
	    }
    	})
	})	
	</script>
</html>