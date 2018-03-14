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

<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;margin-top:20px;">
		<form action="" class="form-inline" method="post"  id="search">
			<div class="form-group">
				<select name="stoken" id="sid" class="form-control" >
					<option value="">请选择结算商户</option>
					<?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["stoken"]); ?>" <?php if($param['stoken'] == $store['stoken']): ?>selected="selected"<?php endif; ?> style="color:green;font-size:1.1em;"><?php echo ($store["storename"]); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="form-group">
				<input type="text" name="stime" id="stime" class="form-control" placeholder='结算时间' onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  value="<?php echo ($param["stime"]); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="etime" id="etime" class="form-control" placeholder="结算时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($param["etime"]); ?>">
			</div>
			<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button>&emsp;&emsp;
			<!-- <button class="btn btn-default btn-md btn-outline" type="button" id="updateCut">更新引流佣金</button>  -->
			<!-- <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-export"></span> 导出</button> -->
		</form>

	</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>商户名称</th>
					<th>手机</th>
					<th>结算金额</th>
					<th>结算时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="tbody">
				<?php if(is_array($data)): foreach($data as $key=>$da): ?><tr>
						<td><?php echo ($da["storename"]); ?></td>
						<td><?php echo ($da["tel"]); ?></td>
						<td><?php echo ($da["Money"]); ?></td>
						<td><?php echo ($da["CreateDate"]); ?></td>
						<td><button class="btn btn-default btn-xs showdetail" data-toggle="modal" data-target="#showorder" data-id='<?php echo ($da["ID"]); ?>'>查看明细</button></td>
					</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<div class="modal inmodal fade" id="showorder" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title"></h4><small>查看明细</small>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<td>订单号</td>
							<td>订单金额</td>
							<td>下单时间</td>
						</tr>
					</thead>
					<tbody class="obody">

					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="">关闭</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript">
	$('#sid').chosen();
	$(document).ready(function(){
		$(document).on('click','.showdetail',function(){
			var _this=$(this);
			$('.obody').html('');
			layer.msg('加载中');
			$.ajax({
				url:"<?php echo U('Storers/showcutdetail');?>",
				type:"post",
				data:"id="+_this.attr('data-id'),
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var _html='';
						$.each(msg.data,function(index,item){
							var sp="";
							if (item.Dis=='1') {
								sp="<small style='color:red;'>优惠订单</small>";
							};
							_html+='<tr><td>'+item.OrderId+sp+'</td><td>'+item.Price+'</td><td>'+item.CreateDate+'</td></tr>';
						})
						$('.obody').html(_html);
					}else{
						layer.msg(msg.info);
					}
				}
			})
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>