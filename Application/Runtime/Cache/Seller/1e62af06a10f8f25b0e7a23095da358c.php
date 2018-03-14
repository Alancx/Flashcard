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
		<h5>配送费用设置 <small>配送费类型为<b>固定金额</b>时，最高/最低配送费不生效</small></h5> 
	</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr>
					<th>门店地址</th>
					<th width='100'>配送费类型</th>
					<th>起配价 &emsp; <button class='btn btn-xs btn-danger' data-container="body" data-toggle="popover" data-placement="right" data-content="起配价：免配送费用的价格（如起配价填写30,支付金额大于等于30免配送费）"> &nbsp;?&nbsp; </button></th>
					<th>配送费 &emsp; <button class='btn btn-xs btn-danger' data-container="body" data-toggle="popover" data-placement="right" data-content="配送费：1% 填写1 即可"> &nbsp;?&nbsp; </button></th>
					<th>最低配送费&emsp; <button class='btn btn-xs btn-danger' data-container="body" data-toggle="popover" data-placement="right" data-content="保底配送费"> &nbsp;?&nbsp; </button></th>
					<th>最高配送费&emsp; <button class='btn btn-xs btn-danger' data-container="body" data-toggle="popover" data-placement="right" data-content="最高配送费"> &nbsp;?&nbsp; </button></th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($store)): foreach($store as $key=>$emp): ?><tr>
						<td><?php echo ($emp["province"]); echo ($emp["city"]); echo ($emp["area"]); echo ($emp["addr"]); ?></td>
						<td><div class="input-group">
							<div class="radio">
							  <label>
							    <input type="radio" name="PsgetType" class='psradio' id="optionsRadios1" value="0" <?php if($emp['PsgetType'] == '0'): ?>checked<?php endif; ?> >
							    按百分比
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="PsgetType" class='psradio' id="optionsRadios2" value="1" <?php if($emp['PsgetType'] == '1'): ?>checked<?php endif; ?> >
							    按固定金额
							  </label>
							</div>
						</div></td>
						<td><div class="input-group">
							<input type="text" name="PsPrice" id="PsPrice<?php echo ($emp["id"]); ?>" class="form-control input-xs" placeholder='满？元配送' value="<?php echo ($emp["PsPrice"]); ?>">
							<span class='input-group-addon'>元</span>
						</div></td>
						<td><div class="input-group">
							<input type="text" name="PsGet" id="PsGet<?php echo ($emp["id"]); ?>" class="form-control input-xs" placeholder='配送费用(付配送员)' value="<?php echo ($emp["PsGet"]); ?>">
							<span class='input-group-addon'><span class='psgettype'><?php if($emp['PsgetType'] == '1'): ?>元<?php else: ?>%<?php endif; ?></span>/单</span>
						</div></td>
						<td><div class="input-group">
							<input type="text" name="MinPsGet" id="MinPsGet<?php echo ($emp["id"]); ?>" class="form-control input-xs" placeholder='最低配送费(保底)' value="<?php echo ($emp["MinPsGet"]); ?>">
							<span class='input-group-addon'>元/单</span>
						</div></td>
						<td><div class="input-group">
							<input type="text" name="MaxPsGet" id="MaxPsGet<?php echo ($emp["id"]); ?>" class="form-control input-xs" placeholder='最高配送费' value="<?php echo ($emp["MaxPsGet"]); ?>">
							<span class='input-group-addon'>元/单</span>
						</div></td>
						<td><button class="btn btn-xs btn-primary btn-outline savekd" data-id='<?php echo ($emp["id"]); ?>'>保存设置信息</button></td>
					</td>
				</tr>
				<tr>
					<td colspan='7' class='text-danger'>
						<b>设置说明 </b><br>按百分比：订单金额超出起配价时，配送员获取最高配送费，配送费由商户支付。 <br> 按固定金额：订单金额超出起配价时，配送员获取配送费金额，配送费由商户支付
					</td>
				</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.psradio').click(function(){
			if ($(this).val()=='0') {
				$('.psgettype').html('%');
			}else if ($(this).val()=='1') {
				$('.psgettype').html('元');
			};
		})
		$('.savekd').click(function(){
			var id=$(this).attr('data-id');
			// var CutNum=$("#CutNum"+id).val();
			var PsPrice=$('#PsPrice'+id).val();
			var PsGet=$('#PsGet'+id).val();
			var MinPsGet=$('#MinPsGet'+id).val();
			var MaxPsGet=$('#MaxPsGet'+id).val();
			var PsgetType=$('input[name="PsgetType"]:checked').val();
			if (PsPrice && PsGet && MinPsGet && MaxPsGet) {
				$.ajax({
					url:"<?php echo U('Stores/mercutinfo');?>",
					type:"post",
					data:"id="+id+"&PsPrice="+PsPrice+"&PsGet="+PsGet+"&MinPsGet="+MinPsGet+"&MaxPsGet="+MaxPsGet+"&PsgetType="+PsgetType,
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
				art.dialog.tips('请完善设置信息');
				if (PsPrice) {
					$('#PsGet'+id).focus();
				}else{
					$('#PsPrice'+id).focus();
				}
			}
		})
	})
</script>
<script type="text/javascript" src="/Public/newadmin/js/content.min.js?v=1.0.0"></script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>