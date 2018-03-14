<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>光盘客</title>
    <meta name="keywords" content="徽记食品">
    <meta name="description" content="徽记食品">
    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    <script src="/Public/Admin/Admin/js/hplus.js"></script>

<style type="text/css">
	.keyword{
		border:1px solid #ccc;
		padding: 5px 10px;
		border-radius: 5px;
		display: block;
		float: left;
		margin-left: 10px;
		cursor: pointer;
	}
	.keyword:hover{
		background-color: #f00;
		color: #FFF;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0px;">
	<div class="ibox-title">
		<h5>默认备注管理</h5>
	</div>
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="" class="form-inline" method="post" id="search">
			<div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>备注内容</span>
					<input type="text" name="Content" id="Content" class="form-control">
				</div>
			</div>
			<button class="btn btn-primary btn-outline btn-md" type="button" id="savecontent">添加</button>
			<small>点击备注内容可删除</small>
			</div>
		</form>

	</div>
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="box">
			<?php if(is_array($rmks)): foreach($rmks as $key=>$rm): ?><span class='keyword' data-id='<?php echo ($rm["ID"]); ?>'><?php echo ($rm["content"]); ?></span><?php endforeach; endif; ?>
		</div>
		
	</div>
</div>


<script type="text/javascript" src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','#savecontent',function(){
			var content=$('#Content').val();
			if (content) {
				layer.msg('处理中');
				$.ajax({
					url:"<?php echo U('BaseSetting/defaultrmk');?>",
					type:"post",
					data:"content="+content,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							layer.msg('添加成功');
							$('.box').append('<span class="keyword" data-id="'+msg.id+'">'+content+'</span>');
							$('#Content').val('').focus();
						}else{
							layer.msg(msg.info);
						}
					}
				})
			};
		})
		$(document).on('click','.keyword',function(){
			var _this=$(this);
			layer.confirm('确定要删除此条默认备注内容吗？',{
				btn:['确定','取消'],
				shade:false
			},function(){
				layer.msg('处理中');
				$.ajax({
					url:"<?php echo U('BaseSetting/deldefrmk');?>",
					type:"post",
					data:"id="+_this.attr('data-id'),
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							layer.msg('已删除');
							_this.remove();
						}else{
							layer.msg(msg.info);
						}
					}
				})
			})
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>