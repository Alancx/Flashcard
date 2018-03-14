<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="/Public/note/css/yuangong.css" />
	<title>任务首页</title>
</head>
<body>
	<div class="top">
		<div class="top-shang">
			<div class="name"><?php echo (session('name')); ?></div>
			<div class="pic">
				<img src="<?php echo (session('pic')); ?>"/>
			</div>
		</div>
		<div class="xiala">
			<div class="">
			<select name="" style="border:0px !important;" class="seltype">
				<?php if(is_array($weeklist)): foreach($weeklist as $key=>$w): ?><option data-year="<?php echo ($w["year"]); ?>" data-week="<?php echo ($w["week"]); ?>"><?php echo ($w['year']); ?>年<?php echo ($w['week']); ?>周</option><?php endforeach; endif; ?>
			</select>
			<span class="aa"></span>
			</div>
		</div>
	</div>
	<div class="zhong">
		<?php if(is_array($result)): foreach($result as $key=>$row): ?><div class="head">
			<span><?php echo ($row['addtime']); ?></span>
		<div class="content" style="padding-left:15px;padding-right:15px;">
			<div class="text"><?php echo ($row['title']); ?></div>
			<div class="pp" style="right:15px;"><span class="mui-icon mui-icon-arrowup"></span></div>
		</div>
		<?php if(is_array($row['list'])): foreach($row['list'] as $key=>$list): ?><div class="complete">
			<div class="complete-text" >
				<?php echo ($list['content']); ?>
			</div>
			<div class="ppp">
				<?php if($list['state'] == '1' ): ?><div class="mui-input-row mui-checkbox " style="width: 40px !important; height: 40px;">
					    <input name="Checkbox" type="checkbox" data-tid="<?php echo ($list['id']); ?>" class="check nocheck">
					</div>
				<?php else: ?>
					<div class="mui-input-row mui-checkbox " style="width: 40px !important; height: 40px;">
				    	<input name="Checkbox" type="checkbox"  checked disabled>
					</div><?php endif; ?>
			</div>
		</div><?php endforeach; endif; ?>
		<div class="bjd"  style="width:100%;text-align:right;padding-right:15px;height:40px;line-height:40px; background:#ffffff;"><a >报进度</a></div>
		</div><?php endforeach; endif; ?>
	</div>
	<div style="diaplay:block;height:60px;"></div>
	<div class="bottom">
		<div class="wo"><a href="<?php echo U('Index/select');?>" style="color:#ffffff;">我的笔记</a></div>
		<div class="xie"><a href="<?php echo U('Index/note');?>" style="color:#ffffff;">写笔记</a></div>
	</div>
</body>
<script src="/Public/note/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/Public/note/js/jquery-2.1.0.js"></script>
<script type="text/javascript">
	$(document).on("tap", ".content", function() {
		$(this).parents('.head').find('.complete').slideToggle(200);
		$(this).parents('.head').find('.bjd').slideToggle(200);
	})
</script>
<script>
	$(document).on("tap",'.bjd',function(){
			var pobj = $(this).parents('.head');
			var senddata = {};
			$.each($(pobj).find('.complete'),function(index,item){
				if ($(item).find('.check').prop('checked')==true) {
					var tid=$(this).find('.check').attr('data-tid');
					senddata[index]=tid;
				}else{
				}
			})
			$.ajax({
				url:"<?php echo U('Index/updata');?>",
				type:"post",
				data:senddata,
				dataType:"json",
				success:function(msg){
					if(msg.status=='true'){
						mui.toast(msg['info']);
						setTimeout(window.location.reload(),1500);
					}else{
						mui.toast(msg['info']);
					}
				}
			})

	})
</script>
<script>
	$('.seltype').change(function(){
		var weeks=$(this).children('option:selected').attr('data-week');
		var year=$(this).children('option:selected').attr('data-year');
		var senddata = {
			weeks:weeks,
			year:year,
		}
		$.ajax({
			url:"<?php echo U('Index/addtime');?>",
			type:"post",
			data:senddata,
			dataType:"json",
			success:function(msg){
				var _html='';
				var list=msg['info'];
				$.each(list,function(index,item){
					var _htmls='';
					var row=item['list'];
					if(msg['sta']=='2'){
					$.each(row,function(i,it){
						if(it['state']=='2'){
							_htmls+='<div class="complete">'+
							'<div class="complete-text" >'+it['content']+'</div>'+
							'<div class="ppp">'+
								'<div class="mui-input-row mui-checkbox " style="width: 40px !important; height: 40px;">'+
								'<input name="Checkbox" type="checkbox"  checked disabled>'+
								'</div>'+
							'</div>'+
							'</div>';

							}else{
								_htmls+='<div class="complete">'+
								'<div class="complete-text" >'+it['content']+'</div>'+
								'<div class="ppp">'+
									'<div class="mui-input-row mui-checkbox " style="width: 40px !important; height: 40px;">'+
									'<input name="Checkbox" type="checkbox"  disabled>'+
									'</div>'+
								'</div>'+
								'</div>';
							}
					})

					_html+='<div class="head">'+
					'<span></span>'+
					'<div class="content">'+
						'<div class="text" style="padding-right:15px; padding-left:15px;">'+item['title']+'</div>'+
						'<div class="pp"><span class="mui-icon mui-icon-arrowup"></span></div>'+
					'</div>'+
						_htmls+
					'</div>';

					}else{
						$.each(row,function(i,it){

							if(it['state']=='2'){
							_htmls+='<div class="complete">'+
							'<div class="complete-text" >'+it['content']+'</div>'+
							'<div class="ppp">'+
								'<div class="mui-input-row mui-checkbox " style="width: 40px !important; height: 40px;">'+
								'<input name="Checkbox" type="checkbox"   checked disabled>'+
								'</div>'+
							'</div>'+
							'</div>';

							}else{
								_htmls+='<div class="complete">'+
								'<div class="complete-text" >'+it['content']+'</div>'+
								'<div class="ppp">'+
									'<div class="mui-input-row mui-checkbox " style="width: 40px !important; height: 40px;">'+
									'<input name="Checkbox" type="checkbox" data-tid="'+it['id']+'" class="check ">'+
									'</div>'+
								'</div>'+
								'</div>';
							}



					})

						_html+='<div class="head">'+
						'<span></span>'+
					'<div class="content">'+
						'<div class="text" style="padding-right:15px; padding-left:15px;">'+item['title']+'</div>'+
						'<div class="pp"><span class="mui-icon mui-icon-arrowup"></span></div>'+
					'</div>'+
						_htmls+
						'<div class="bjd"  style="width:100%;text-align:right;padding-right:15px;height:40px;line-height:40px; background:#ffffff;"><a >报进度</a></div>'+
						'</div>';
					}


				})
				$('.zhong').html(_html);

			}
		})
	})
</script>
</html>