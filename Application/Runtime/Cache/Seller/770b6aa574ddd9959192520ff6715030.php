<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>徽记食品</title>
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
<style type="text/css">
	td{
		text-align: center;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>配送人员管理</h5>
	</div>
	<div class="col-sm-12 col-md-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>真实姓名</th>
					<th>联系电话</th>
					<th>申请时间</th>
					<th>验证信息</th>
					<th>身份证号</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($stores)): foreach($stores as $key=>$emp): ?><tr>
						<td><img src="<?php echo ($PICURL); echo ($emp["HeadImg"]); ?>" style="width:80px;height:80px;" alt=""></td>
						<td><?php echo ($emp["TrueName"]); ?></td>
						<td><?php echo ($emp["Phone"]); ?></td>
						<td><?php echo ($emp["AskDate"]); ?></td>
						<td><img src="<?php echo ($PICURL); echo ($emp["IdImg"]); ?>" style="width:120px;height:120px;" alt=""><br><button class="btn btn-xs btn-primary btn-outline showimg" data-src="<?php echo ($PICURL); echo ($emp["IdImg"]); ?>">查看大图</button></td>
						<td><?php echo ($emp["IdCard"]); ?></td>
						<td><?php if($emp['Status'] == '0'): ?><button class="btn btn-xs btn-primary btn-outline success" data-id="<?php echo ($emp["ID"]); ?>">同意申请</button>&emsp;<button class="btn btn-xs btn-warning btn-outline refund" data-id="<?php echo ($emp["ID"]); ?>">拒绝申请</button><?php elseif($emp['Status'] == '1'): ?>&emsp;<button class="btn btn-xs btn-danger btn-outline delete" data-id="<?php echo ($emp["ID"]); ?>">删除(已同意申请)</button><?php elseif($emp['Status'] == '2'): ?>&emsp;<button class="btn btn-xs btn-danger btn-outline delete" data-id="<?php echo ($emp["ID"]); ?>">删除(已拒绝申请)</button><?php endif; ?></td>
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
        <h4 class="modal-title" id="myModalLabel">收银台</h4>
      </div>
      <div class="modal-body" style="text-align:center">
         <img src=""  id="getqr" style="height:150px;height:150px;" alt="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary data-dismiss="modal"">关闭</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.delete').click(function(){
			var _this=$(this);
			var ID=_this.attr('data-id');
			art.dialog.confirm('确定要删除此配送员吗？',function(){
				_this.addClass('disabled');
				///
				$.ajax({
					url:"<?php echo U('Store/changeStatu');?>",
					type:"post",
					data:"type=delete&id="+ID,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							_this.parent().parent().remove();
						}else{
							art.dialog.tips('处理失败');
							_this.removeClass('disabled');
						}
					}
				})
			});
		})
		$('.success').click(function(){
			var _this=$(this);
			var ID=_this.attr('data-id');
			art.dialog.confirm('确定要同意申请吗？',function(){
				_this.addClass('disabled');
				_this.siblings().addClass('disabled');
				///
				$.ajax({
					url:"<?php echo U('Store/changeStatu');?>",
					type:"post",
					data:"type=success&id="+ID,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							art.dialog.tips('处理成功');
							location.reload();
						}else{
							art.dialog.tips('处理失败');
							_this.removeClass('disabled');
							_this.siblings().removeClass('disabled');
						}
					}
				})
			})
		})
		$('.refund').click(function(){
			var _this=$(this);
			var ID=_this.attr('data-id');
			art.dialog.confirm('确定要拒绝申吗？',function(){
				_this.addClass('disabled');
				_this.siblings().addClass('disabled');
				///
				$.ajax({
					url:"<?php echo U('Store/changeStatu');?>",
					type:"post",
					data:"type=error&id="+ID,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							art.dialog.tips('处理成功');
							location.reload();
						}else{
							art.dialog.tips('处理失败');
							_this.removeClass('disabled');
							_this.siblings().removeClass('disabled');
						}
					}
				})
			})
		})
		$('.showimg').click(function(){
    		art.dialog({title:'图片预览',content:'<img src="'+$(this).attr('data-src')+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>