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
	<div class="ibox-title">
		<form class="form-inline">
		  <div class="form-group">
		    <label class="sr-only" for="exampleInputAmount"></label>
		    <div class="input-group">
		      <div class="input-group-addon">当前可提金额￥：<span id='Money' data-money='<?php echo ($Money); ?>'><?php echo ($Money); ?></span></div>
		      <input type="text" class="form-control" id="Gmoney" <?php if($Money == 0): ?>readonly='readyonly'<?php endif; ?> placeholder="请输入提现金额">
		      <!-- <div class="input-group-addon"></div> -->
		    </div>
		  </div>
		  <?php if($hasbk == '1'): ?><button type="button" class="btn btn-primary" id="getmoney">提交申请</button><?php else: ?><small style="color:red;">请设置账户信息后提现</small><?php endif; ?>
		  
		</form>
		<form class="form-inline" method="post" id="search">
		<br>
		<h4>提现记录查询</h4>
		  <div class="form-group">
		    <label class="sr-only"></label>
		    <div class="input-group">
		      <div class="input-group-addon">选择状态</div>
		      <select name="Status" id="Status" class="form-control">
		      	<option value="">请选择</option>
		      	<option value="0" <?php if($Param['Status'] == '0'): ?>selected="selected"<?php endif; ?>>待处理</option>
		      	<option value="1" <?php if($Param['Status'] == '1'): ?>selected="selected"<?php endif; ?>>已处理</option>
		      </select>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="sr-only"></label>
		    <div class="input-group">
		      <div class="input-group-addon">开始时间</div>
		      <input type="text" name="StartDate"  class="form-control" value="<?php echo ($Param["StartDate"]); ?>" id="StartDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})">
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="sr-only"></label>
		    <div class="input-group">
		      <div class="input-group-addon">结束时间</div>
		      <input type="text" name="EndDate"  class="form-control" value="<?php echo ($Param["EndDate"]); ?>" id="EndDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})">
		    </div>
		  </div>
		  <button type="submit" class="btn btn-primary">查询</button>
		</form>
	</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr>
					<th>申请时间</th>
					<th>金额</th>
					<th>当前状态</th>
					<th>账户信息</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="content">
				<?php if(is_array($lists)): foreach($lists as $key=>$emp): ?><tr>
						<td><?php echo ($emp["CreateDate"]); ?></td>
						<td><?php echo ($emp["Money"]); ?></td>
						<td><?php echo ($emp["Status"]); ?></td>
						<td><?php echo ($emp["GetName"]); ?><br><?php echo ($emp["tel"]); ?><br><?php echo ($emp["IdName"]); ?><br><?php echo ($emp["IdCard"]); ?></td>
						<td><?php if($emp['Astu'] == '0'): ?><button class="btn btn-default btn-xs cancel" data-id="<?php echo ($emp["ID"]); ?>">取消申请</button><?php else: ?>已完成<?php endif; ?></td>
				</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#search').submit(function(){
		var StartDate=$('#StartDate').val();
		var EndDate=$('#EndDate').val();
		if (StartDate || EndDate) {
			if (StartDate && EndDate) {
				if (StartDate<EndDate) {
					return true;
				}else{
					art.dialog.tips('非法时间区间');
					return false;
				}
			}else{
				art.dialog.tips('请选择完整的时间区间');
				return false;
			}
		}else{
			return true;
		}
	})
	$('#Gmoney').keyup(function(){
		var mo=$(this).val();
		var allmoney=$('#Money').attr('data-money');
		if (parseFloat(allmoney)<parseFloat(mo)) {
			art.dialog.tips('非法金额');
			$('#Gmoney').val(allmoney);
		};
	})
	$(document).on("click",".cancel",function(){
		var _this=$(this);
		art.dialog.confirm('确定要取消此申请吗？申请记录将删除!!!',function(){
			_this.addClass('disabled').html('处理中...');
			$.ajax({
				url:"<?php echo U('Stores/cancelMoney');?>",
				type:"post",
				data:"ID="+_this.attr('data-id'),
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						_this.parent().parent().remove();
						art.dialog.tips('处理成功');
						$('#Money').attr('data-money',msg.Money).html(msg.Money);
					}else{
						art.dialog.tips(msg.info);
						_this.removeClass('disabled').html('取消申请');
					}
				}
			})
		})
	})
	$('#getmoney').click(function(){
		var _this=$(this);
		var money=$('#Gmoney').val();
		var allmoney=$('#Money').attr('data-money');
		if (parseFloat(money)>parseFloat(allmoney)) {
			art.dialog.tips('非法金额');
			return false;
		}else if (!money) {
			art.dialog.tips('请输入提现金额');
			return false;
		}else{
			_this.addClass('disabled').html('提交中...');
			$.ajax({
				url:"<?php echo U('Stores/getmoney');?>",
				type:"post",
				data:"money="+money,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var nowmoney=parseFloat(allmoney)-parseFloat(money);
						$('#Money').attr('data-money',nowmoney).html(nowmoney.toFixed(2));
						$('#Gmoney').val('');
						var data=msg.data;
						var tphtml="<tr><td>"+data.CreateDate+"</td><td>"+data.Money+"</td><td>"+data.Status+"</td><td>"+data.GetName+"<br>"+data.IdName+"<br>"+data.IdCard+"</td><td><button class='btn btn-xs btn-default cancel' data-id='"+data.ID+"'>取消申请</button></td></tr>";
						$('#content').prepend(tphtml);
						_this.removeClass('disabled').html('提交申请');
						art.dialog.tips('提交成功');
					}else{
						art.dialog.tips(msg.info);
						_this.removeClass('disabled').html('提交申请');
					}
				}
			})
		}
	})
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>