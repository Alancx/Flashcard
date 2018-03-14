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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
	.tice{
		color:red;
	}
</style>

<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			</div>
			<div class="ibox-content">
				<div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
					<div class="panel">
						<div class="panel-heading">
							<i class="fa fa-warning"></i> 提示信息
						</div>
						<div class="panel-body">
							<div class="alert alert-warning">
								?1、用户订单交易完成之后？天之后不能退款<br>
								?3、商城名称 <br>
								?6、商城发货地址（用于打印快递单） <br>
								?7、默认评论（用户快捷评论内容） <br>
							</div>
						</div>
					</div>
				</div>
<!-- 				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>引流佣金</h5>
						<form role="form" class="form-inline"  method="post" action="">
							<div class="input-group">
								<input type="number"  name="Cut" placeholder="引流佣金" id="Cut" class="form-control" value="<?php echo ($time["Cut"]); ?>">
								<div class="input-group-addon">%</div>
							</div>
							<button class="btn btn-white btn-outline"  id="m-cut" type="button">保存设置</button>
						</form>
					</div>
				</div>
 -->
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>退款时间设置  ?1</h5>
						<form role="form" class="form-inline" method="post" action="">
							<div class="form-group">
								<label for="exampleInputEmail2" class="sr-only">天数</label>
								<input type="number" name="score" placeholder="？天之后无法退款" id="tkday" class="form-control" value="<?php echo ($time["TKtime"]); ?>">
							</div>
							<button class="btn btn-white btn-outline"  id="tk" type="button">保存设置</button>
						</form>
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>商城名称设置 ?3</h5>
						<form role="form" class="form-inline"  method="post" action="">
							<div class="form-group">
								<label for="exampleInputEmail2" class="sr-only">名称</label>
								<input type="text"  name="score" placeholder="商城名称" id="storename" class="form-control" value="<?php echo ($time["storeName"]); ?>">
							</div>
							<button class="btn btn-white btn-outline"  id="sname" type="button">保存设置</button>
						</form>
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>发货地址设置 ?6</h5>
						<form role="form" class="form-inline"  method="post" action="">
							<div class="form-group">
								<label for="exampleInputEmail2" class="sr-only">地址</label>
								<input type="text"  name="score" style="width: 400px;" placeholder="商城发货地址" id="sendaddr" class="form-control" value="<?php echo ($time["sendAddr"]); ?>">
							</div>
							<button class="btn btn-white btn-outline"  id="addr" type="button">保存设置</button>
						</form>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>默认评价内容 ?7</h5>
						<form role="form" class="form-inline"  method="post" action="">
							<div class="form-group">
								<label for="exampleInputEmail2" class="sr-only">评价</label>
								<input type="text"  name="score" style="width: 400px;" placeholder="默认评价内容" id="defaulteval" class="form-control" value="">
							</div>
							<button class="btn btn-white btn-outline"  id="eval" type="button">保存设置</button>
						</form>
					</div>
				</div>
				<div class="col-sm-12 col-md-12">
					<?php if($evals): ?><table class="table bordered">
							<thead>
								<tr>
									<td>评价内容</td>
									<td>操作</td>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($evals)): foreach($evals as $key=>$eval): ?><tr>
										<td><?php echo ($eval["content"]); ?></td>
										<td><button class="btn btn-warning btn-xs" onclick="delval('<?php echo ($eval["ID"]); ?>')">删除</button></td>
									</tr><?php endforeach; endif; ?>
							</tbody>
						</table><?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function delval(id){
		art.dialog.confirm('确定要删除吗？',function(){
			window.location.href='<?php echo U('BaseSetting/delval');?>?id='+id;
		},function(){
			art.dialog.tips('取消操作',1);
		})
	}
	//验证
	$(document).ready(function(){
        $("#chosen").chosen();
        $("#chosen1").chosen();
        $("#chosen2").chosen();
		$('#tkday').keyup(function(){
			var tkday=$(this).val();
			$(this).val(parseInt(tkday));
        // $(this).val(parsetInt($(this).val()));
    })
		$('#txday').keyup(function(){
			var txday=$(this).val();
			$(this).val(parseInt(txday));
        // $(this).val(parsetInt($(this).val()));
    })
		$("#tk").click(function(){
			var tk=$('#tkday').val();
			if (tk) {
				art.dialog.tips('正在保存');
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+tk+"&type=tk",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}else{
				art.dialog.alert('请填写天数');
			}
		})
		$("#tx").click(function(){
			var tx=$('#txday').val();
			if (tx) {
				art.dialog.tips('正在保存');
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+tx+"&type=tx",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}else{
				art.dialog.alert('请填写天数');
			}
		})
		$("#sname").click(function(){
			var storename=$('#storename').val();
			if (storename) {
				art.dialog.tips('正在保存');
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+storename+"&type=storename",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}else{
				art.dialog.alert('请填写商城名称');
			}
		})
		$("#addr").click(function(){
			var sendaddr=$('#sendaddr').val();
			if (sendaddr) {
				art.dialog.tips('正在保存');
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+sendaddr+"&type=sendaddr",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}else{
				art.dialog.alert('请填写发货地址');
			}
		})
		$("#eval").click(function(){
			var defaulteval=$('#defaulteval').val();
			if (defaulteval) {
				art.dialog.tips('正在保存');
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+defaulteval+"&type=defaulteval",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
							window.location.reload();
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}else{
				art.dialog.alert('请填写评价内容');
			}
		})
		$('#m-cut').click(function(){
			var cut=$('#Cut').val();
			if (!cut) {
				art.dialog.alert('请填写扣点');
				$('#Cut').focus();
			}else{
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+cut+"&type=cut",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
							window.location.reload();
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}
		})
		$('#m-mcut').click(function(){
			var mcut=$('#Mcut').val();
			if (!mcut) {
				art.dialog.alert('请填写扣点');
				$('#Mcut').focus();
			}else{
				$.ajax({
					url:"<?php echo U('BaseSetting/saveTime');?>",
					type:"post",
					data:"time="+mcut+"&type=mcut",
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('保存成功');
							window.location.reload();
						}else{
							art.dialog.tips('保存失败');
						}
					}
				})
			}
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>