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

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
	.form-group{margin-bottom: 10px!important;}
</style>
<div class="row  wrapper  white-bg" style="margin:0px;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>推广人员提现信息管理</h5>
	</div>
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		

	</div>
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php if($employees['msg']): ?><h3><?php echo ($employees["msg"]); ?></h3>
		<?php else: ?>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>账号</th>
					<th>姓名</th>
					<th>提现时间</th>
					<th>提现金额 (元)</th>
					<th>账户信息</th>
					<th>当前状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($users)): foreach($users as $key=>$emp): ?><tr>
						<td><?php echo ($emp["userName"]); ?></td>
						<td><?php echo ($emp["TrueName"]); ?></td>
						<td><?php echo ($emp["createdate"]); ?></td>
						<td><?php echo ($emp["Money"]); ?></td>
						<td>户主：<?php echo ($emp["IdName"]); ?> <br>银行：<?php echo ($emp["BankName"]); ?> <br>卡号：<?php echo ($emp["BankId"]); ?></td>
						<td class="statu" value="<?php echo ($emp["Status"]); ?>"><?php if($emp['Status'] == '0'): ?>待处理<?php elseif($emp['Status'] == '1'): ?>已完成<?php elseif($emp['Status'] == '2'): ?>已拒绝<?php endif; ?></td>
						<td class="pass" value="<?php echo ($emp["id"]); ?>"><?php if($emp['Status'] == '1'): ?><button class="btn btn-xs btn-primary passcheck" data-id='<?php echo ($emp["Tid"]); ?>'>通过审核</button>&emsp;<?php elseif($emp['Status'] == '2'): ?><button class="btn btn-xs btn-danger passchecks" data-id='<?php echo ($emp["Tid"]); ?>'>已拒绝</button><?php elseif($emp['Status'] == '0'): ?>
							<button class="btn btn-xs btn-warning agree"  data-id='<?php echo ($emp["Tid"]); ?>'>同意</button> &emsp; <button class="btn btn-xs btn-danger refuse" data-id='<?php echo ($emp["Tid"]); ?>'>拒绝</button><?php endif; ?></td>
					</tr><?php endforeach; endif; ?>
			</tbody>

		</table><?php endif; ?>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>

<script src="/Public/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/Public/plugins/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
<!-- /*<script type="text/javascript">
	$(document).ready(function() {
    var inits = $(".init").DataTable();//初始化分页数据
})
</script>*/ -->
<script type="text/javascript">
// var jsondata=<?php echo ($jsondata); ?>;
	$(document).ready(function(){
		
		$(document).on('click','.refuse',function(){
			var _this=$(this);
			// alert($('.pass').attr('value'));
			layer.confirm('确定要拒绝审核吗？',{
				btn:['确定','取消'],
				shade:false
			},function(){
				layer.msg('处理中...');
				$.ajax({
					url:"<?php echo U('Admin/checkMoney');?>",
					type:"post",
					data:"tid="+_this.attr('data-id')+"&type="+'0'+"&id="+$('.pass').attr('value'),
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							layer.msg('处理成功');
							_this.parent().parent().find('.statu').html('已拒绝');
							// _this.remove();
							window.location.reload();
						}else{
							layer.msg(msg.info);
						}
					}
				})
			})
		})
		$(document).on('click','.agree',function(){
			var _this=$(this);
			layer.confirm('确定要通过审核吗？',{
				btn:['确定','取消'],
				shade:false
			},function(){
				layer.msg('处理中...');
				$.ajax({
					url:"<?php echo U('Admin/checkMoney');?>",
					type:"post",
					data:"tid="+_this.attr('data-id')+"&type="+'1'+"&id="+$('.pass').attr('value'),
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							layer.msg('处理成功');
							_this.parent().parent().find('.statu').html('已审核');
							// _this.remove();
							// $('.refuse').remove();
							window.location.reload();
						}else{
							layer.msg(msg.info);
						}
					}
				})
			})
		})
	})

</script> 
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>