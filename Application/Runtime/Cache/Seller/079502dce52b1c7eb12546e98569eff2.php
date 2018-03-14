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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="col-sm-12" style="padding-top:20px;">
		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr>
					<th>时间</th>
					<th>变动类型</th>
					<th>变动金额</th>
					<!-- <th>实时余额</th> -->
					<th>###</th>
					<th>#</th>
				</tr>
				<tr>
					<th colspan="5">当前余额：<?php echo ($MoneyInfo["Money"]); ?>   &emsp;&emsp;&emsp;账户总额：<?php echo ($MoneyInfo["TotalMoney"]); ?>  &emsp; <small style="font-size:.8em;color:red;font-weight:300">部分资金提现中(未处理)</small></th>
				</tr>
			</thead>
			<tbody id="content">
				<?php if(is_array($lists)): foreach($lists as $key=>$list): ?><tr>
						<td><?php echo ($list["CreateDate"]); ?></td>
						<td><?php echo ($list["Type"]); ?></td>
						<td><?php echo ($list["Money"]); ?></td>
						<!-- <td><?php echo ($list["TmpMoney"]); ?></td> -->
						<td><?php echo ($list["Useage"]); ?></td>
						<td><?php if($list['Type'] == '支出'): else: ?><button class='btn btn-xs btn-default showdetail' data-id="<?php echo ($list["ID"]); ?>" data-toggle="modal" data-type='<?php echo ($list["Uname"]); ?>' data-target='#mingxi'>查看明细</button><?php endif; ?></td>
					</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
<div class="modal inmodal fade" id="mingxi" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg" style="">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 15px;">
				明细
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body" style="padding:15px">
				<table class='table table-bordered'>
					<thead class='thead'>
						
					</thead>
					<tbody class='tbody'>
						
					</tbody>
				</table>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-w-m btn-success input-sm" id="cls" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','.showdetail',function(){
			var type=$(this).attr('data-type');
			var ID=$(this).attr('data-id');
			art.dialog.tips('加载中...');
			$('.thead').html('');
			$('.tbody').html('');
			$.ajax({
				url:"<?php echo U('Stores/showmlist');?>",
				type:"post",
				data:"ID="+ID,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var data=msg.data;
						var _html='';
						if (type=='YL') {
							var head="<tr><td>订单号</td><td>订单金额</td><td>引流佣金</td><td>支付方式</td><td>下单时间</td></tr>";
							$.each(data,function(index,item){
								_html+='<tr><td>'+item.OrderId+'</td><td>'+item.Price+'</td><td>'+item.CutMoney+'</td><td>'+item.PayName+'</td><td>'+item.CreateDate+'</td></tr>';
							})
						}else if (type=='XS') {
							var head="<tr><td>订单号</td><td>订单金额</td><td>支付方式</td><td>下单时间</td></tr>";
							$.each(data,function(index,item){
								if (item.IsDis=='1') {
									var dis='<small style="color:red">优惠订单</small>';
								}else{
									var dis='';
								}
								_html+='<tr><td>'+item.OrderId+dis+'</td><td>'+item.Price+'</td><td>'+item.PayName+'</td><td>'+item.CreateDate+'</td></tr>';
							})
						};
						$('.thead').html(head);
						$('.tbody').html(_html);
					}else{
						$('#cls').click();
						art.dialog.tips(msg.info,20);
					}
				}
			})
		})
	})
</script>