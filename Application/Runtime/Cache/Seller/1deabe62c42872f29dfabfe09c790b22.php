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
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<style type="text/css">
	td{
		/*text-align: center;*/
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>配送员提现申请</h5>
	</div>
	<div class="col-sm-12 col-md-12"  style="padding-bottom:10px;">
		<form class="form-inline" method="post" id="ser">
		  <div class="form-group">
		    <label for="exampleInputName2">配送员姓名</label>
		    <input type="text" class="form-control" name="MemberName" value="<?php echo ($Param["MemberName"]); ?>" id="exampleInputName2" placeholder="配送员姓名">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputEmail2">记录类型</label>
		    <select name="Status" id="" class="form-control">
		    	<option value="">全部</option>
		    	<option value="0"  <?php if($Param['Status'] == '0'): ?>selected="selected"<?php endif; ?>   >待审核</option>
		    	<option value="1"  <?php if($Param['Status'] == '1'): ?>selected="selected"<?php endif; ?>   >已审核</option>
		    	<option value="2"  <?php if($Param['Status'] == '2'): ?>selected="selected"<?php endif; ?>   >已完成</option>
		    	<option value="3"  <?php if($Param['Status'] == '3'): ?>selected="selected"<?php endif; ?>   >已拒绝</option>
		    </select>
		  </div>
		  <div class="form-group">
		    <label for="exampleInputEmail2">申请时间</label>
		    <input type="text" name="StartDate" id="StartDate" class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})" value="<?php echo ($Param["StartDate"]); ?>" placeholder='开始时间'>
		  </div>
		  <div class="form-group">
		    <input type="text" name="EndDate" id="EndDate" value="<?php echo ($Param["EndDate"]); ?>" class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})" placeholder='结束时间'>
		  </div>
		  <button type="submit" class="btn btn-default" >搜 索</button>
		</form>
	</div>
	<div class="col-sm-12 col-md-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>配送员</th>
					<th>提现金额</th>
					<th>申请时间</th>
					<th>当前状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($lists)): foreach($lists as $key=>$or): ?><tr>
						<td><?php echo ($or["TrueName"]); ?></td>
						<td><?php echo ($or["Money"]); ?></td>
						<td><?php echo ($or["CreateDate"]); ?></td>
						<td><?php echo ($or["Stname"]); ?></td>
						<td><?php if($or['Status'] == '0'): ?><button class="btn btn-primary btn-xs btn-outline pspass" data-id='<?php echo ($or["ID"]); ?>'>同意申请</button> &emsp; <button class="btn btn-warning btn-xs btn-outline refund" data-id='<?php echo ($or["ID"]); ?>'>拒绝申请</button><?php elseif($or['Status'] == '1'): ?>已审核/等待平台处理<?php elseif($or['Status'] == '2'): ?>已完成<?php elseif($or['Status'] == '3'): ?>已拒绝申请<?php endif; ?></td>
					</tr><?php endforeach; endif; ?>
			</tbody>

		</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.pspass').click(function(){
		var _this=$(this);
		var id=_this.attr('data-id');
		art.dialog.confirm('确定要同意提现申请吗？',function(){
			_this.addClass('disabled').html('处理中...');
			$.ajax({
				url:"<?php echo U('Store/setpscheck');?>",
				type:"post",
				data:"type=pass&id="+id,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						_this.parent().html('已审核/等待平台处理');
					}else{
						art.dialog.tips(msg.info);
						_this.removeClass('disabled').html('同意申请');
					}
				}
			})
		})
	})
	$('.refund').click(function(){
		var _this=$(this);
		var id=_this.attr('data-id');
		art.dialog.confirm('确定要拒绝提现申请吗？',function(){
			_this.addClass('disabled').html('处理中...');
			$.ajax({
				url:"<?php echo U('Store/setpscheck');?>",
				type:"post",
				data:"type=refund&id="+id,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						_this.parent().html('已拒绝');
					}else{
						_art.dialog.tips(msg.info);
						_this.removeClass('disabled').html('拒绝申请');
					}
				}
			})
		})
	})
	$('#ser').submit(function(){
		var StartDate=$('#StartDate').val();
		var EndDate=$('#EndDate').val();
		if (StartDate || EndDate) {
			if (StartDate && EndDate) {
				if (StartDate>EndDate) {
					art.dialog.tips('非法时间区间');
					return false;
				}else{
					return true;
				}
			}else{
				art.dialog.tips('请选择完整时间区间');
				return false;
			}
		}else{
			return true;
		}
	})
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>