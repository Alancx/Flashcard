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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;margin-top:20px;">
		<form action="" class="form-inline" method="post"  id="search">
		<div class="form-group">
		<select name="sid" id="sid" class="form-control" value="<?php echo ($pageinfo["sid"]); ?>">
			<option value="">请选择结算商户</option>
			<?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["id"]); ?>" style="color:green;font-size:1.1em;"><?php echo ($store["storename"]); ?></option><?php endforeach; endif; ?>
		</select>
	</div>
	<div class="form-group">
		<input type="text" name="strtime" id="stime" class="form-control" readonly="true" value="<?php echo ($stime); ?>">
	</div>
	<div class="form-group">
		<input type="text" name="endtime" id="etime" class="form-control" placeholder="请选择结束时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($endtime); ?>">
	</div>
	<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
	 <!-- <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-export"></span> 导出</button> -->
</form>

</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>商户名称</th>
					<th colspan="2">结算信息</th>
					<th colspan="2">其他信息</th>
					<th>操作</th>
				</tr>
				<?php if($pageinfo): ?><tr>
					<td><?php echo ($storename); ?></td>
					<td colspan="2">可结算总额：<?php echo ($cutinfo["sallmoney"]); ?> 订单数量：<?php echo ($cutinfo["scount"]); ?></td>
					<td colspan="2"></td>
					<td><button class="btn btn-primary btn-xs btn-outline" onclick="cuted('<?php echo ($pageinfo["sid"]); ?>','<?php echo ($stime); ?>','<?php echo ($endtime); ?>')">结算</button> &emsp; <button class="btn btn-default btn-xs" onclick="showdate('<?php echo ($pageinfo["sid"]); ?>')">查看明细</button></td>
				</tr><?php endif; ?>
			</thead>
			<!-- <div id="body" class="col-sm-12 col-md-12">
				
			</div> -->
			<tbody id="tbody">
					
			</tbody>









		</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<script type="text/javascript">
	var jsondata=<?php echo ($jsondata); ?>;
	$('#sid').chosen();
	$(document).ready(function(){
		$('#sid').change(function(){
			$.each(jsondata,function(index,item){
				if ($('#sid').val()==item.id) {
					$('#stime').val(item.ctime);
				};
			})
		})
	})
	//查看更多
	function showdate(sid){
		var stime=$('#stime').val();
		var etime=$('#etime').val();
		if (stime && etime) {
			$.ajax({
				url:"<?php echo U('Stores/getmore');?>",
				type:"post",
				data:"stime="+stime+"&etime="+etime+"&sid="+sid,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var tbody='<tr><th>订单号</th><th>订单额</th><th>商品数量</th><th>运费</th><th>会员ID</th><th>付款时间</th></tr>';
						$.each(msg.data,function(index,item){
							tbody+='<tr><td><a href="<?php echo U('Order/allorder');?>?oid='+item.OrderId+'">'+item.OrderId+'</a></td><td>'+item.Price+'</td><td>'+item.Count+'</td><td>'+item.Freight+'</td><td>'+item.MemberId+'</td><td>'+item.PayDate+'</td></tr>';
						})
						$('#tbody').html(tbody);
						//
					}else{
						art.dialog.alert(msg.info);
					}
				}
			})
		}else{
			$.ajax({
				url:"<?php echo U('Stores/getmore');?>",
				type:"post",
				data:"sid="+sid,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var tbody='<tr><th>订单号</th><th>订单额</th><th>商品数量</th><th>运费</th><th>会员ID</th><th>付款时间</th></tr>';
						$.each(msg.data,function(index,item){
							tbody+='<tr><td><a href="<?php echo U('Order/allorder');?>?oid='+item.OrderId+'">'+item.OrderId+'</a></td><td>'+item.Price+'</td><td>'+item.Count+'</td><td>'+item.Freight+'</td><td>'+item.MemberId+'</td><td>'+item.PayDate+'</td></tr>';
						})
						$('#tbody').html(tbody);
						//
					}else{
						art.dialog.alert(msg.info);
					}
				}
			})
		}
	}



	function cuted(sid,stime,etime){
		art.dialog.confirm('请在线下完成交易后确认，确认后将处理对应数据',function(){
			if (etime) {
				$.ajax({
					url:"<?php echo U('Stores/index');?>",
					type:"post",
					data:"type=cuted&sid="+sid+"&strtime="+stime+"&endtime="+etime,
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('结算完成');
							window.location.reload();
						}else{
							art.dialog.alert(msg);
						}
					}
				})
			}else{
				$.ajax({
					url:"<?php echo U('Stores/index');?>",
					type:"post",
					data:"type=cuted&sid="+sid,
					dataType:"json",
					success:function(msg){
						if (msg=='success') {
							art.dialog.tips('结算完成');
							window.location.reload();
						}else{
							art.dialog.alert(msg);
						}
					}
				})
			}
		},function(){
			art.dialog.tips('取消操作');
		})
		console.log(sid,stime,etime);
	}

</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>