<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>发布任务</title>
		<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/note/css/add.css?v=1.0" />
		<style>
		.checkit{
			background: #007AFF;
			/*border: none;*/
			color: #fff;
			border:1px solid #007AFF;
		}
		</style>
	</head>
	<body>
		<div class="top">
			<b>发布任务</b>
			<a  class="aaa">添加收信人</a>
		</div>
		<div class="title">
			<a >任务主题</a>
			<input type="text" id="title" value="<?php echo ($list['title']); ?>"/>
		</div>
		<div class="dianji" hidden="" style="width:100%;">
			<?php if(is_array($rows)): foreach($rows as $key=>$list): ?><span data-id="<?php echo ($list['openid']); ?>" data-pic="<?php echo ($list['pic']); ?>" data-post="<?php echo ($list['post']); ?>"><?php echo ($list['name']); ?></span><?php endforeach; endif; ?>
			<div id="qding">
				确定
			</div>
		</div>
		
		<div class="sss">
			<!-- <div class="itmask" hidden=""></div> -->
			<?php if(is_array($resultt)): foreach($resultt as $key=>$value): ?><div class="itemi" data-id="<?php echo ($value['openid']); ?>">
			<div class="ren">
				<div class="mui-table-view-cell">
					<div class="mui-slider-handle">
						<div class="img"><img src="<?php echo ($value['pic']); ?>"/></div>
						<a  class="nnn" data-id="<?php echo ($value['openid']); ?>"><?php echo ($value['name']); ?></a>
						<div class="pp">
							<p class="mui-icon mui-icon-arrowup"></p>
						</div>
					</div>
					<div class="mui-slider-right mui-disabled aa">
						<a class="mui-btn" ><span class="mui-icon mui-icon-trash deleta"></span></a>
					</div>
				</div>
			</div>
			
			<div class="complete">
				<?php if(is_array($value['list'])): foreach($value['list'] as $key=>$mod): ?><div class="mui-table-view-cell renyuan_item">
					<div class="mui-slider-handle">
						<div class="zz"><?php echo ($mod['content']); ?></div>
					</div>
					<div class="mui-slider-right mui-disabled aa">
						<a class="mui-btn" data-id="<?php echo ($mod['tid']); ?>"><span class="edit">编辑</span></a>
						<a class="mui-btn" data-id="<?php echo ($mod['tid']); ?>"><span class="mui-icon mui-icon-trash delete"></span></a>
					</div>
				</div><?php endforeach; endif; ?>
				<div class="content">
					<textarea rows="4" cols="20"></textarea>
					<div class="text">
						<a  class="xa">添加</a>
					</div>
				</div>

			</div>
			</div><?php endforeach; endif; ?>
		</div>
		<div class="bottom">
			<div class="bottom-right fb">直接发布</div>
			<div class="bottom-left bc">保存</div>
			
		</div>
	</body>
	<script src="/Public/note/js/mui.min.js"></script>
	<script src="/Public/note/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script>
	var tid="<?php echo ($tid); ?>";
	</script>
	<script type="text/javascript" charset="utf-8">
		mui.init();
	</script>
	<script>
		//添加收信人
		$(document).on("tap", ".aaa", function() {
			$('.dianji').fadeToggle(0);
			$('.itmask').fadeToggle(0);
		})
		//选择收信人
		$(document).on("tap",'.dianji span',function(){
			$(this).toggleClass('checkit');
		})
		//确定收件人
		$(document).on("tap",'#qding',function(){
			$.each($('.checkit'),function(index,item){
				var uid=$(item).attr('data-id');
				var pic=$(item).attr('data-pic');
				var post=$(item).attr('data-post');
				var uname=$(item).text();
				if($('.itemi[data-id='+uid+']').length >0){
				} else {
					var html='<div class="itemi"  data-id="'+uid+'">'+
					'<div class="ren">'+
						'<div class="mui-table-view-cell">'+
							'<div class="mui-slider-handle">'+
								'<div class="img"><img src="'+pic+'" /></div>'+
								'<a class="nnn" data-id="'+uid+'">'+uname+'</a>'+
								'<div class="pp">'+
									'<p class="mui-icon mui-icon-arrowup"></p>'+
								'</div>'+
							'</div>'+
							'<div class="mui-slider-right mui-disabled aa">'+
								'<a class="mui-btn" ><span class="mui-icon mui-icon-trash deleta"></span></a>'+
							'</div>'+
						'</div>'+
					'</div>'+
					'<div class="complete">'+
						'<div class="content">'+
							'<textarea rows="4" cols="20"></textarea>'+
							'<div class="text">'+
								'<a  class="xa">添加</a>'+
							'</div>'+
						'</div>'+
					'</div>'+
					'</div>'; 
					$(".sss").append(html);			
				}
			})
			$('.dianji').fadeToggle(0);
			$('.itmask').fadeToggle(0);
			
		})
	</script>
	<script type="text/javascript">
		$(document).on("tap", ".ren .mui-slider-handle", function() {
			$(this).parents('.itemi').find('.complete').slideToggle(200);
		})
	</script>
	<script>
		$(document).on("tap",".xa",function(){
			var content = $(this).parent().parent().children("textarea").val();
			
			if(content.length<=0){	
			}else{
				if($(this).parents('.itemi').find('.editactive').length>0){
					$(this).parents('.itemi').find('.editactive').find('.zz').text(content);
					$(this).parents('.itemi').find('.editactive').removeClass('editactive');
				}else{
					var htmls='<div class="mui-table-view-cell renyuan_item" style="background:#FFFFFF;" data-tid="add">'+
							'<div class="mui-slider-handle">'+
								'<div class="zz" style="width:100%;background:#FFFFFF;"><span class="mui-icon mui-icon-arrowleft"></span>'+content+'</div>'+	 
							'</div>'+
							'<div class="mui-slider-right mui-disabled aa">'+
								'<a class="mui-btn"><span class="edit">编辑</span></a>'+
								'<a class="mui-btn" data-id=""><span class="mui-icon mui-icon-trash delete"></span></a>'+
							'</div>'+
						'</div>';
				$(this).parent().parent().parent().parent().find(".complete").before(htmls)
				}
				
				$('textarea').val('');
			}
		})
	</script>
	<script>
		$(document).on('tap','.delete',function(){
			$(this).parent().parent().parent().remove();
		})
	</script>
	<script>
	$(document).on('tap','.deleta',function(){
		$(this).parents('.itemi').remove();
	})
	</script>
	<script type="text/javascript">
		$(document).on('tap','.edit',function(){				
			var _this=$(this);
			var thistext=_this.parents(".renyuan_item").find('.zz').text();
			_this.parents(".renyuan_item").addClass('editactive');				
			_this.parents('.itemi').find('textarea').val(thistext);
		})
	</script>
	<script>
		$(document).on('tap','.bc',function(){
			var senddata={};
			senddata['rlist']={};
			$.each($('.itemi'),function(index,item){
				var id=$(item).attr('data-id');
				senddata['rlist'][id]={};				
				senddata['rlist'][id]['id']=id;
				senddata['rlist'][id]['contentlist']={};
				$.each($(item).find('.zz'),function(i,it){
					var content = $(it).text();
					senddata['rlist'][id]['contentlist'][i]=content;
				})
			})
			var title = $('#title').val();
			senddata['tid']=tid;
			senddata['title'] = title;
			senddata['type']='1';
			$.ajax({
				url:"<?php echo U('Index/doadd');?>",
				type:"post",
				data:senddata,
				dataType:"json",
				success:function(msg){
					if(msg.status=='true'){
						mui.toast(msg['info']);
						setTimeout(window.location=document.referrer,1500);
					}else{
						mui.toast(msg['info']);
					}
				}
			})

		})
	</script>
	<script>
		$(document).on('tap','.fb',function(){
			var senddata={};
			senddata['rlist']={};
			$.each($('.itemi'),function(index,item){
				var id=$(item).attr('data-id');
				senddata['rlist'][id]={};				
				senddata['rlist'][id]['id']=id;
				senddata['rlist'][id]['contentlist']={};
				$.each($(item).find('.zz'),function(i,it){
					var content = $(it).text();
					senddata['rlist'][id]['contentlist'][i]=content;
				})
			})
			var title = $('#title').val();
			senddata['tid']=tid;
			senddata['title'] = title;
			senddata['type']='2';
			$.ajax({
				url:"<?php echo U('Index/doadd');?>",
				type:"post",
				data:senddata,
				dataType:"json",
				success:function(msg){
					if(msg.status=='true'){
						mui.toast(msg['info']);
						setTimeout(window.location=document.referrer,1500);
					}else{
						mui.toast(msg['info']);
					}
				}
			})
		})
	</script>
</html>