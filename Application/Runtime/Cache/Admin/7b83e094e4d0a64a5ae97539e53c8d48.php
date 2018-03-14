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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>商户相关设置</h5>
	</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr>
					<th>门店名称</th>
					<th>联系电话</th>
					<th>门店地址</th>
					<th>扣点</th>
					<th>免扣点时段</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($stores)): foreach($stores as $key=>$emp): ?><tr>
						<td><?php echo ($emp["storename"]); ?></td>
						<td><?php echo ($emp["tel"]); ?></td>
						<td><?php echo ($emp["province"]); echo ($emp["city"]); echo ($emp["area"]); echo ($emp["addr"]); ?></td>
						<td><div class="input-group">
							<input type="text" name="CutNum" id="CutNum<?php echo ($emp["id"]); ?>" class="form-control input-xs" placeholder='5%扣点 填写5即可' value="<?php echo ($emp["CutNum"]); ?>">
							<span class='input-group-addon'>%</span>
						</div></td>
						<td>
						<div style="text-align:center;margin-bottom:10px;">
							<label class="radio-inline">
							  <input type="radio" name="IsFreeCut<?php echo ($emp["id"]); ?>" <?php if($emp['IsFreeCut'] == 1): ?>checked="checked"<?php endif; ?> id="IsFreeCut<?php echo ($emp["id"]); ?>" value="1"> 开启
							</label>
							<label class="radio-inline">
							  <input type="radio" name="IsFreeCut<?php echo ($emp["id"]); ?>" <?php if($emp['IsFreeCut'] == 0): ?>checked="checked"<?php endif; ?> id="NoFreeCut<?php echo ($emp["id"]); ?>" value="0"> 关闭
							</label>
						</div>
						<div class="input-group">
							<input type="text" name="FreeStime" id="FreeStime<?php echo ($emp["id"]); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'})" value="<?php echo ($emp["FreeStime"]); ?>" style="width:160px;" class="form-control">
							<span class="input-group-addon">至</span>
							<input type="text" name="FreeEtime" id="FreeEtime<?php echo ($emp["id"]); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'})" value="<?php echo ($emp["FreeEtime"]); ?>" style="width:160px;" class="form-control">
						</div></td>
						<td><button class="btn btn-xs btn-primary btn-outline savekd" data-id='<?php echo ($emp["id"]); ?>'>保存设置信息</button></td>
					</td>
				</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.savekd').click(function(){
			var id=$(this).attr('data-id');
			var CutNum=$("#CutNum"+id).val();
			var IsFreeCut=$("input:radio[name='IsFreeCut"+id+"']:checked").val();
			var FreeEtime=$('#FreeEtime'+id).val();
			var FreeStime=$('#FreeStime'+id).val();
			var Fpsper='0';
			if (CutNum>=0 && CutNum<100) {
				if (IsFreeCut==1) {
					if (FreeStime && FreeEtime && FreeStime<FreeEtime) {
						$.ajax({
							url:"<?php echo U('Storers/mercutinfo');?>",
							type:"post",
							data:"id="+id+"&CutNum="+CutNum+"&IsFreeCut="+IsFreeCut+"&FreeStime="+FreeStime+"&FreeEtime="+FreeEtime,
							dataType:"json",
							success:function(msg){
								if (msg.status=='success') {
									art.dialog.tips('保存成功');
								}else{
									art.dialog.tips(msg.info);
								}
							}
						})
					}else{
						art.dialog.tips('请完善免扣点时间段信息');
					}
				}else{
					$.ajax({
						url:"<?php echo U('Storers/mercutinfo');?>",
						type:"post",
						data:"id="+id+"&CutNum="+CutNum+"&IsFreeCut="+IsFreeCut,
						dataType:"json",
						success:function(msg){
							if (msg.status=='success') {
								art.dialog.tips('保存成功');
							}else{
								art.dialog.tips(msg.info);
							}
						}
					})
				}
			}else{
				art.dialog.tips('不合理的扣点数值');
				$('#CutNum'+id).val('').focus();
			}
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>