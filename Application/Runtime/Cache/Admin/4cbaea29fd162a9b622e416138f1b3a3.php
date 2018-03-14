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
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>商户审核</h5>
	</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>门店名称</th>
					<th>联系电话</th>
					<th>门店地址</th>
					<!-- <th>身份证号</th> -->
					<!-- <th>验证信息</th> -->
					<th>申请时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($stores)): foreach($stores as $key=>$emp): ?><tr>
						<td><?php echo ($emp["id"]); ?></td>
						<td><?php echo ($emp["storename"]); ?></td>
						<td><?php echo ($emp["tel"]); ?></td>
						<td><?php echo ($emp["province"]); echo ($emp["city"]); echo ($emp["area"]); echo ($emp["addr"]); ?></td>
						<!-- <td><?php echo ($emp["IdCard"]); ?></td> -->
						<!-- <td><img src="<?php echo ($PICURL); echo ($emp["IdInfo"]); ?>" alt="" width="150" height="150" id="<?php echo ($emp["id"]); ?>"><br><button class="btn btn-xs btn-primary btn-outline" onclick="show('<?php echo ($emp["id"]); ?>')">查看原图</button></td> -->
						<td><?php echo ($emp["CreateDate"]); ?></td>
						<td><?php if($emp['IsCheck'] == '0'): ?><button class="btn btn-primary btn-xs btn-outline" onclick="pass('<?php echo ($emp["id"]); ?>')">通过申请</button><br><br><button class="btn btn-xs btn-warning btn-outline" onclick="Reject('<?php echo ($emp["id"]); ?>')" data-toggle="modal" data-target="#myModal6">拒绝申请</button><br><br> <small>未审核</small><?php elseif($emp['IsCheck'] == '2'): ?><button class="btn btn-danger btn-xs btn-outline">删除</button><br><br><button class="btn btn-white btn-xs btn-outline showrmk" data-rmk="<?php echo ($emp["Checkmark"]); echo ($emp["Remarks"]); ?>" data-toggle="modal" data-target="#myModal7">查看备注</button><br><br><small>审核未通过</small><?php else: ?><button class="btn btn-white btn-xs btn-outline showrmk" data-rmk="<?php echo ($emp["Checkmark"]); echo ($emp["Remarks"]); ?>"  data-toggle="modal" data-target="#myModal7">查看备注</button><br><br><small>审核已通过</small><?php endif; ?>
							<?php if (empty($emp['openid']) && $emp['IsCheck']==1): ?>
								<!-- <button class="btn btn-default btn-xs showqr" data-id='<?php echo ($emp["id"]); ?>' data-toggle="modal" data-target="#showqr">查看绑定二维码</button> -->
							<?php else: ?>
								
							<?php endif ?>
						</if>
						
					</td>
				</tr><?php endforeach; endif; ?>
		</tbody>






		<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">拒绝原因</h4>
					</div>
					<div class="modal-body">
						<form action="##" class="form">
							<div class="form-group" class="col-sm-12">
								<label>拒绝原因</label>
								<select name="backres" id="backres" class="form-control">
									<option value="身份信息不清晰">身份信息不清晰</option>
									<option value="门店名称不规则">门店名称不规则</option>
									<option value="门店地址不明确">门店地址不明确</option>
									<option value="身份信息不一致">身份信息不一致</option>
								</select>
							</div>
							<div class="form-group" class="col-sm-12">
								<label>备注信息</label>
								<textarea name="Remarks" cols="30" rows="5" style="width:100%;" id="content" value=""></textarea>
								<input type="hidden" name="hid" id="hid" value="">
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks">提交</button>
					</div>
				</div>
			</div>
		</div>


		<div class="modal inmodal fade" id="myModal7" tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">备注内容</h4>
					</div>
					<div class="modal-body">
						<form action="##" class="form">
							<div class="form-group" class="col-sm-12">
								<label>备注信息</label>
								<textarea name="Remarks" cols="30" rows="10" style="width:100%;" id="contentss" value=""></textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						
					</div>
				</div>
			</div>
		</div>
		<div class="modal inmodal fade" id="showqr" tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">qrcode</h4>
					</div>
					<div class="modal-body">
						<form action="##" class="form">
							<div class="form-group" class="col-sm-12" style="text-align:center">
								<img src="" alt="" style="width:200px;height:200px;" id="qre">
								<br>
								<small>请使用微信扫码</small>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						
					</div>
				</div>
			</div>
		</div>







	</table>
	<div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
<script type="text/javascript">
	function show(id){
		art.dialog({title:'图片预览',content:'<img src="'+$('#'+id).attr('src')+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
	}

	function showrmk(content){
		$('#contents').val(content);
	}
	function Reject(id){
		//拒绝
		$("#hid").val(id);
	}
	function pass(id){
		//通过
		art.dialog.confirm('确定要通过审核吗？',function(){
			window.location.href="<?php echo U('Storers/checking');?>?statu=1&id="+id;
		},function(){
			art.dialog.tips('取消操作');
		})
	}
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
	$(document).ready(function(){
		$('.showqr').click(function(){
			$('#qre').attr('src','');
			var id=$(this).attr('data-id');
			var img="<?php echo U('Storers/showqr');?>?id="+id;
			$('#qre').attr('src',img);
		})
		$('#saveRemarks').click(function(){
			var id=$('#hid').val();
			var rmk=$('#content').val();
			var brmk=$('#backres').val();
			if (brmk && id) {
				art.dialog.confirm('确定要拒绝该申请吗？',function(){
					window.location.href="<?php echo U('Storers/checking');?>?statu=2&id="+id+"&Remarks="+rmk+"&Brmk="+brmk;
				},function(){
					art.dialog.tips('取消操作');
				})
			}else{
				art.dialog.alert('请填写备注内容');
			}
		})
		$('.showrmk').click(function(){
			$('#contentss').val('');
			var rmk=$(this).attr('data-rmk');
			$('#contentss').val(rmk);
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>