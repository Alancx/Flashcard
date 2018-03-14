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

<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>门店管理</h5>
	</div>
	<div class="col-sm-10 col-sm-offset-1">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>门店名称</th>
					<th>联系电话</th>
					<th>门店地址</th>
					<th>核销总额</th>
					<th>核销订单数</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($stores)): foreach($stores as $key=>$emp): ?><tr>
						<td><?php echo ($emp["id"]); ?></td>
						<td><?php echo ($emp["storename"]); ?></td>
						<td><?php echo ($emp["tel"]); ?></td>
						<td><?php echo ($emp["province"]); echo ($emp["city"]); echo ($emp["area"]); echo ($emp["addr"]); ?></td>
						<td><?php echo ($emp["moneys"]); ?></td>
						<td><?php if($emp['ordernums']): ?><a href="<?php echo U('Order/allOrder');?>?username=<?php echo ($emp["id"]); ?>"><?php echo ($emp["ordernums"]); ?></a><?php else: echo ($emp["ordernums"]); endif; ?></td>
						<td> <a data-toggle="modal" data-target="#myModal" class="cashierqr" data-sid='<?php echo ($emp["id"]); ?>'>前往收银台</a> |<a href="###" onclick="getq('<?php echo ($emp["id"]); ?>');">添加核销员</a> | <a href="###" onclick="edit('<?php echo ($emp["id"]); ?>')">编辑</a> | <a href="###" onclick="del('<?php echo ($emp["id"]); ?>');">删除</a>			|			<a href="<?php echo U('Store/userlist');?>?sid=<?php echo ($emp["id"]); ?>&type=XJ">管理支付核销员</a> |
							<a href="<?php echo U('Store/userlist');?>?sid=<?php echo ($emp["id"]); ?>&type=TH">管理提货核销员</a> 
						</td>
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
	function del(id){
		art.dialog.confirm('确定要删除吗？,此操作将把该门店所有核销员一并删除！！！',function(){
			window.location.href="<?php echo U('Store/del');?>?id="+id;
		},function(){
			art.dialog.tips('取消操作');
		})
	}
	function edit(id){
		window.location.href="<?php echo U('Store/edit');?>?id="+id;
	}
	function getq(id){
		art.dialog.confirm('此操作将把上次生成的验证码作废，请确认？',function(){
			art.dialog.open('<?php echo U('ArtDialog/checkStore');?>?id='+id);
		},function(){
			art.dialog.tips('取消操作');
		})
	}
	$('.cashierqr').click(function(){
		var id=$(this).attr('data-sid');
		$('#getqr').attr('src','<?php echo U('Store/getcashierqr');?>?id='+id);
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>