<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="/Public/note/css/guanliyuan.css?v=1.1" />
	<title>管理员首页</title>
</head>
<body>
	<div class="top">
		<div class="top-shang">
			<div class="name"><?php echo (session('name')); ?></div>
			<div class="admin">管理员</div>
			<div class="pic">
				<img src="<?php echo (session('pic')); ?>"/>
			</div>
		</div>
		<div class="xiala">
			<div class="">
				<select name="" class="seltype">
					<?php if(is_array($weeklist)): foreach($weeklist as $key=>$w): ?><option data-year="<?php echo ($w["year"]); ?>" data-week="<?php echo ($w["week"]); ?>"><?php echo ($w['year']); ?>年<?php echo ($w['week']); ?>周</option><?php endforeach; endif; ?>
			</select>
			<span class="aa"></span>
			</div>
		</div>
	</div>
	<div class="zhong">
		<?php if(is_array($row)): foreach($row as $key=>$list): ?><div class="content">
			<div class="content-top">
				<a href="<?php echo U('Index/jindu',array('tid'=>$list['id']));?>" style="color:#000000;"><div class="text"><?php echo ($list['title']); ?>&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo ($list['addtime']); ?></div></a>
			</div>
			<?php if($list['status'] == '1'): ?><div class="content-botom">
				<div class="fabu sendfabu" data-tid="<?php echo ($list['id']); ?>"><a>发布</a></div>
				<div class="xiu"><a href="<?php echo U('Index/add',array('tid'=>$list['id']));?>">编辑</a></div>
			</div>
			<?php else: ?>
				<div class="content-botom">
					<div class="fabu"><a >已发布</a></div>
				</div><?php endif; ?>
		</div><?php endforeach; endif; ?>
	</div>
	<div style="diaplay:block;height:60px;"></div>
	<div class="bottom">
		<div class="renwu"><a href="<?php echo U('Index/add',array('tid'=>'add'));?>">发布任务</a></div>
		<!-- <div class="jindu"><a href="<?php echo U('Index/jindu');?>">进度</a></div>	 -->
	</div>
</body>
<script src="/Public/note/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/note/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
<script>
	var edit_url="<?php echo U('Index/add',array('tid'=>'RENWUID'));?>";
	var jindu_url="<?php echo U('Index/jindu',array('tid'=>'RENWUID'));?>";
	// replace
	$(document).ready(function(){
		$('.seltype').change(function(){
			var weeks=$(this).children('option:selected').attr('data-week');
			var year=$(this).children('option:selected').attr('data-year');
			var senddata = {
				weeks:weeks,
				year:year,
			}
			// console.log(senddata);
			$.ajax({
				url:"<?php echo U('Index/dotime');?>",
				type:"post",
				data:senddata,
				dataType:"json",
				success:function(msg){
					if(msg['status']=='true'){
						var _html='';
						var list=msg['info'];
						$.each(list,function(index,item){
							var url=edit_url.replace(/RENWUID/,item['id']);
							var urll=jindu_url.replace(/RENWUID/,item['id']);
							if(msg['sta']=='1'){
								if(item['status']=='1'){
									_html+='<div class="content">'+
									'<div class="content-top">'+
										'<a href="'+urll+'" style="color:#000000;"><div class="text mui-ellipsis">'+item['title']+'&nbsp&nbsp&nbsp&nbsp&nbsp'+item['addtime']+'</div></a>'+
									'</div>'+
									'<div class="content-botom">'+
										'<div class="fabu sendfabu"data-tid="'+item['id']+'"><a >发布</a></div>'+
										'<div class="xiu"><a href='+url+'>编辑</a></div>'+
									'</div>'+
									'</div>';
								}else{
									_html+='<div class="content">'+
									'<div class="content-top">'+
										'<a href="'+urll+'" style="color:#000000;"><div class="text mui-ellipsis">'+item['title']+'&nbsp&nbsp&nbsp&nbsp&nbsp'+item['addtime']+'</div></a>'+
									'</div>'+
									'<div class="content-botom">'+
										'<div class="fabu"><a >以发布</a></div>'+
										// '<div class="xiu"><a href='+url+'>编辑</a></div>'+
									'</div>'+
									'</div>';
								}
							}else{
								if(item['status']=='1'){
									_html+='<div class="content">'+
									'<div class="content-top">'+
										'<div class="text mui-ellipsis">'+item['title']+'</div>'+
									'</div>'+
									'<div class="content-botom">'+
										'<div class="fabu "><a >已过期</a></div>'+
									'</div>'+
									'</div>';
								}else{
									_html+='<div class="content">'+
									'<div class="content-top">'+
										'<a href="'+urll+'" style="color:#000000;"><div class="text mui-ellipsis">'+item['title']+'&nbsp&nbsp&nbsp&nbsp&nbsp'+item['addtime']+'</div></a>'+
									'</div>'+
									'<div class="content-botom">'+
										'<div class="fabu "><a >已发布</a></div>'+
									'</div>'+
									'</div>';
								}
								
							}
							
						})
						$('.zhong').html(_html);
					}else{
						mui.toast(msg['info']);
						$('.zhong').html('');
					}
				}
			})
		})	
		
		$(document).on('tap','.sendfabu',function(){
			var tid=$(this).attr('data-tid');
			var senddata = {
				tid:tid,
			}
			$.ajax({
				url:"<?php echo U('Index/updata');?>",
				type:"post",
				data:senddata,
				dataType:"json",
				success:function(msg){
					if(msg.status=='true'){
						mui.toast(msg['info']);
						setTimeout(location.reload(),1500);
					}else{
						mui.toast(msg['info']);
					}
				}
			})
		})
	})
</script>
</html>