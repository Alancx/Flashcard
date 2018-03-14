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

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">

<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5><button class="btn btn-primary btn-outline addcancel" data-toggle="modal" data-target="#myModal">添加核销员</button></h5>
	</div>
	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="<?php echo U('Admin/searchcan');?>" class="form-inline" method="post" id="search">
		<!-- 	<div class="form-group">
				<input type="text" name="username" placeholder="请填写核销员姓名(选填)" id="username" class="form-control">
			</div>
			<div class="form-group">
				<select name="CanType" id="CanType" class="form-control">
					<option value="">请选择核销类型</option>
          <option value="pay" style="color:green;font-size:1.1em;">付款</option>
          <option value="get" style="color:orange;font-size:1.1em;">提货</option>
				</select>
			</div>
			<button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button> -->
		</form>

	</div>
	<div class="col-sm-10 col-sm-offset-1">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>姓名</th>
					<th>手机</th>
					<th>添加时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($lists)): foreach($lists as $key=>$user): ?><tr>
						<td><?php echo ($user["username"]); ?></td>
						<td><?php echo ($user["phone"]); ?></td>
						<td><?php echo ($user["CreateDate"]); ?></td>
						<td> <button class='btn btn-danger btn-xs btn-outline delcancel' data-id='<?php echo ($user["id"]); ?>'>删除</button></td>
					</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加核销员 <small style='color:red;font-weight:bold;' id='notice'></small></h4>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 col-sm-12" style="text-align:center">
        	<small style='display:block;'>请使用微信扫描二维码</small>
        	<img src="" id="cancelQr" alt="">
        	<br>
        	<span>验证码 <b id='verify'></b></span>
        	<small style='display:block;'>验证码两小时后失效 </small>
        </div>
        <div style="clear:both">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	function show(id,ctype){
		art.dialog.open('<?php echo U('Store/show');?>?sid='+id+'&CanType='+ctype,{width:'600px'});
	}

	function del(id){
		art.dialog.confirm('确定要删除吗？请慎重操作', function () {
			window.location.href="<?php echo U('Admin/del');?>?id="+id;
			// alert('hahaha')
		}, function () {
			art.dialog.tips('取消操作');
		});
	}

	function getQr(id){
		art.dialog.open("<?php echo U('ArtDialog/AQr');?>?mid="+id);
	}
	var qrurl="<?php echo U('Common/getQrCode');?>";
	$(document).ready(function(){
		$('.addcancel').click(function(){
			$('#notice').html('加载中...');
			$('#cancelQr').attr('src','');
			$.ajax({
				url:"<?php echo U('Stores/getcancelverify');?>",
				type:"post",
				data:"1=1",
				dataType:"json",
				success:function(msg){
					if (msg.status='success') {
						$('#cancelQr').attr('src',qrurl+'?type=scaner');
						$('#verify').html(msg.verify);
						$('#notice').html('');
					}else{
						$('#notice').html('加载失败');
					}
				}
			})
		})
		$('.delcancel').click(function(){
			var id=$(this).attr('data-id');
			art.dialog.confirm('确定要删除此核销员吗？',function(){
				window.location.href="<?php echo U('Stores/delcancel');?>?id="+id;
			})
		})
	})

</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>