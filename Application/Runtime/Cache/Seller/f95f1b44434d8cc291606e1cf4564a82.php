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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<style type="text/css">
	.tice{
		color:red;
	}
	.spl .chosen-container{
		width: 200px;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			                            <div class="alert alert-warning" style="color:red;">
                               1、设置进阶商品后请设置该商品的三级提成  <b><金额></b> ，原有提成比例失效<br>
                               2、更换进阶商品后，前往商品编辑将更换前商品三级提成修改为百分比<br>
                           </div>

			</div>
			<div class="ibox-content">
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>设置限购商品</h5>
						<form role="form" class="form-inline" id="savebuy"  method="post" action="">
							<div class="form-group">
								<label for="exampleInputPassword2" class="sr-only">选择商品</label>
								<select name="ProId" id="chosen" class="form-control" style="width:100%;" value="">
									<option value="">请选择商品</option>
									<?php if(is_array($pros)): foreach($pros as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword2" class="sr-only"></label>
								<input type="text" name="strtime" id="strtime" class="form-control" placeholder='请选择开始时间'  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
							</div>
							<div class="form-group">
								<label for="exampleInputPassword2" class="sr-only">选择商品</label>
								<input type="text" name="endtime" id="endtime" class="form-control" placeholder='请选择结束时间'  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
							</div>
							<div class="form-group">
								<label for="exampleInputPassword2" class="sr-only">选择商品</label>
								<input type="number" name="buynum" id="buynum" class="form-control" placeholder='请填写限购数量'>
							</div>
							<button class="btn btn-primary btn-outline"  type="submit">保存设置</button>
						</form>
					</div>
				</div>
				<div class="col-sm-12 col-md-12">
					<table class="table bordered">
						<thead>
							<tr>
								<td>当前商品</td>
								<td>限购时间</td>
								<td>限购数量</td>
								<td>操作</td>
							</tr>
						</thead>
						<tbody>
						<?php if(is_array($limits)): foreach($limits as $key=>$li): ?><tr>
								<td><?php echo ($li["ProName"]); ?><br><img src="<?php echo ($li["ProLogoImg"]); ?>" style="width:100px;height:100px;" alt=""></td>
								<td><?php echo ($li["StrDate"]); ?>--<?php echo ($li["EndDate"]); ?></td>
								<td><?php echo ($li["Num"]); ?></td>
								<td><button class="btn btn-danger btn-xs clelimit" data-pid='<?php echo ($li["ProId"]); ?>'>取消限购</button></td>
							</tr><?php endforeach; endif; ?>
						</tbody>
					</table>
					<?php echo ($page); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal fade" id="order_message" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-sm" style="width:428px;">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 15px;">
				提成设置
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body" style="padding:15px">
				<div class="input-group">
					<span class='input-group-addon'>一级提成</span>
					<input type="text" name="Cut" id="Cut" class="form-control">
					<span class='input-group-addon'>元</span>
				</div>
				<div class="input-group">
					<span class='input-group-addon'>二级提成</span>
					<input type="text" name="Cut2" id="Cut2" class="form-control">
					<span class='input-group-addon'>元</span>
				</div>
				<div class="input-group">
					<span class='input-group-addon'>三级提成</span>
					<input type="text" name="Cut3" id="Cut3" class="form-control">
					<span class='input-group-addon'>元</span>
				</div>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-w-m btn-success input-sm" data-pid='' id="btn_message" data-dismiss="modal">提交</button>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal fade" id="order_messages" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-sm" style="width:428px;">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 15px;">
				设置礼包内容 <span class='notice' style='color:red;font-weight:bold'></span>
				<button type="button" class="close" data-dismiss="modal" id="lbclo">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body" style="padding:15px">
				<div class="input-group spl">
					<span class='input-group-addon'>选择商品</span>
					<select name="" id="ProIdCard" class="form-control">
						<option value="-1">请选择商品</option>
						<?php if(is_array($pross)): foreach($pross as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>" class="<?php echo ($pro["ProId"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
					</select>
					<input type="number" name="Num" id="Num" class="form-control" placeholder='请输入商品数量'>
				</div>
				<div style="text-align:right"><button class="btn btn-xs btn-danger addpro">添&emsp;加</button></div>
				<form id="prodata">
					<?php if(is_array($spdata)): foreach($spdata as $key=>$sp): ?><div id='<?php echo ($sp["ProIdCard"]); ?>' style='margin-top:5px;'><input type='hidden' name='pros[]' value='<?php echo ($sp["ProIds"]); ?>'><?php echo ($sp["ProName"]); ?>/数量：<?php echo ($sp["Num"]); ?>&emsp;&emsp;<button class='btn btn-danger btn-xs del' data-pid='<?php echo ($sp["ProIdCard"]); ?>'>删除</button>	</div><?php endforeach; endif; ?>
				</form>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-w-m btn-success input-sm" data-pid='' id="btn_messagess">保存内容</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#chosen').chosen();
	$('#savebuy').submit(function(){
		var strtime=$('#strtime').val();
		var endtime=$('#endtime').val();
		var buynum=$('#buynum').val();
		var pid=$('#chosen').val();
		if (strtime && endtime && buynum && pid) {
			if (strtime>endtime) {
				art.dialog.tips('无效的时间区间');
				return false;
			}else if (buynum<=0) {
				art.dialog.tips('限购数量需大于1');
				return false;
			}else{
				art.dialog.tips('正在提交...');
				return true;
			}
		}else{
			art.dialog.tips('请完善限购信息');
			return false;
		}
	})
	$(document).on('click','.clelimit',function(){
		var _this=$(this);
		var pid=_this.attr('data-pid');
		art.dialog.confirm('确定要取消此商品限购设置吗？',function(){
			$.ajax({
				url:"<?php echo U('Products/delprolimit');?>",
				type:"post",
				data:"pid="+pid,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						art.dialog.tips('操作成功');
						_this.parent().parent().remove();
					}else{
						art.dialog.tips('操作失败');
					}
				}
			})
		})
	})
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>