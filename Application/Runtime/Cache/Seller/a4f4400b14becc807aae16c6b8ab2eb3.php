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

<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
	.tice{
		color:red;
	}
</style>

<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			</div>
			<div class="ibox-content">
				<div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
					<div class="panel">
						<div class="panel-heading">
							<i class="fa fa-warning"></i> 提示信息
						</div>
						<div class="panel-body">
							<div class="alert alert-warning">
							<span style='color:red;font-weight:bolder'>*所有二维码仅支持微信扫一扫</span> <br>
								?1、收银台入口，只能在微信浏览器中打开<br>
								?2、消息窗入口，可在微信/浏览器中打开 <br>
								?3、绑定门店收银信息接收人，每个门店只能绑定一个微信用户，每次绑定将覆盖上次绑定的用户<br>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>收银台入口  ?1</h5>
						选择门店 <select name="store" id="Store">
							<option value="">请选择门店</option>
							<?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["id"]); ?>"><?php echo ($store["storename"]); ?></option><?php endforeach; endif; ?>
						</select>
						选择员工 <select name="emp1" id="emp1">
							<option value="">请选择员工</option>
							<?php if(is_array($emps)): foreach($emps as $key=>$emp): ?><option value="<?php echo ($emp["id"]); ?>"><?php echo ($emp["userName"]); ?></option><?php endforeach; endif; ?>
						</select>
						<button class="btn btn-default btn-xs" id="pay-show">确定</button>
						<a id="paya" href="" target='_blank'></a>
						<br>
						<img id="payimg" src="" alt="" style="width:150px;height:150px;">
					</div>
				</div>
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>消息窗入口  ?2</h5>
						选择门店 <select name="mstore" id="mstore">
							<option value="">请选择门店</option>
							<?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["id"]); ?>"><?php echo ($store["storename"]); ?></option><?php endforeach; endif; ?>
						</select>
						选择员工 <select name="emp2" id="emp2">
							<option value="">请选择员工</option>
							<?php if(is_array($emps)): foreach($emps as $key=>$emp): ?><option value="<?php echo ($emp["id"]); ?>"><?php echo ($emp["userName"]); ?></option><?php endforeach; endif; ?>
						</select>
						<button class="btn btn-default btn-xs" id="msg-show">确定</button>
						<a id="msga" href=""  target='_blank'></a>
						<br>
						<img src="" id="msgimg" alt="" style="width:150px;height:150px;">
					</div>
				</div>
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>绑定门店消息接收人  ?3</h5>
						选择门店 <select name="resid" id="resid">
							<option value="">请选择门店</option>
							<?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["id"]); ?>"><?php echo ($store["storename"]); ?></option><?php endforeach; endif; ?>
						</select>
						<button class="btn btn-default btn-xs" id="msg-recever">确定</button>
						<!-- <a id="recever" href=""  target='_blank'></a> -->
						<br>
						<img src="" id="msgrecever" alt="" style="width:150px;height:150px;">
					</div>
				</div>
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>收银台商品添加 ?</h5>
						<form role="form" class="form-inline"  method="post" action="<?php echo U('Warehouse/saveScanPro');?>" id="save">
							<div class="form-group">
								<label for="exampleInputPassword2" class="sr-only">选择商品</label>
								<select name="ProId" id="chosen" class="form-control" value="">
									<option value="">请选择商品</option>
									<?php if(is_array($pros)): foreach($pros as $key=>$pinfo): ?><option value="<?php echo ($key); ?>"><?php echo ($pinfo); ?></option><?php endforeach; endif; ?>
								</select>
							</div>
							<input type="hidden" name="type" value="add">
							<button class="btn btn-white btn-outline"  type="submit">添加商品</button>
						</form>
					</div>
					<div class="col-sm-12">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>商品名称</th>
									<th>img</th>
									<th>价格</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php if(is_array($cpros)): foreach($cpros as $key=>$cp): ?><tr id="<?php echo ($cp["ProId"]); ?>">
									<td><?php echo ($cp["ProName"]); ?></td>
									<td><img src="<?php echo ($cp["ProLogoImg"]); ?>" alt="" width="100" height="100"></td>
									<td><?php echo ($cp["PriceRange"]); ?></td>
									<td><button class="btn btn-danger btn-xs" onclick="del('<?php echo ($cp["ProId"]); ?>')">删 除</button></td>
								</tr><?php endforeach; endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
        $("#chosen").chosen();
        $('#pay-show').click(function(){
        	var sid=$('#Store').val();
        	var emp=$('#emp1').val();
        	if (sid && emp) {
	        	$('#payimg').attr('src','<?php echo U('Warehouse/showQr');?>?type=scanpay&sid='+sid+"&mid="+emp+"&stoken=<?php echo $this->token ?>");
	        	var src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/?token='.$token; ?>&sid="+sid+"&mid="+emp;
	        	$('#paya').attr('href',src);
        	}else{
        		if (!sid) {
        			art.dialog.tips('请选择门店');
        			$('#Store').focus();
        			return false;
        		};
        		if (!emp) {
        			art.dialog.tips('请选择员工');
        			$('#emp1').focus();
        			return false;
        		};
        	}
        })
        $('#msg-show').click(function(){
        	var sid=$('#mstore').val();
        	var emp=$('#emp2').val();
        	if (sid && emp) {
	        	$('#msgimg').attr('src','<?php echo U('Warehouse/showQr');?>?type=msg&sid='+sid+"&mid="+emp);
	        	var src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/msg.html?token='.$token; ?>&sid="+sid+"&mid="+emp;
	        	$('#msga').attr('href',src);
        	}else{
        		if (!sid) {
        			art.dialog.tips('请选择门店');
        			$('#mstore').focus();
        			return false;
        		};
        		if (!emp) {
        			art.dialog.tips('请选择员工');
        			$('#emp2').focus();
        			return false;
        		};
        	}
        })
        $('#msg-recever').click(function(){
        	var sid=$('#resid').val();
        	if (!sid) {
        		art.dialog.tips('请选择门店');
        		$('#resid').focus();
        	}else{
        		$('#msgrecever').attr('src','<?php echo U('Warehouse/showQr');?>?type=setrecever&sid='+sid);
        	}
        })
        // $('#Store').change(function(){
        // 	if ($(this).val()) {
	       //  	$('#payimg').attr('src','<?php echo U('Warehouse/showQr');?>?type=scanpay&sid='+$(this).val());
	       //  	var src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/?token='.$token; ?>&sid="+$(this).val();
	       //  	$('#paya').attr('href',src);
        // 	}else{
        // 		art.dialog.tips('请选择门店');
        // 	}
        // })
        // $('#mstore').change(function(){
        // 	if ($(this).val()) {
	       //  	$('#msgimg').attr('src','<?php echo U('Warehouse/showQr');?>?type=msg&sid='+$(this).val());
	       //  	var src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Smodel/Base/msg.html?token='.$token; ?>&sid="+$(this).val();
	       //  	$('#msga').attr('href',src);
        // 	}else{
        // 		art.dialog.tips('请选择门店');
        // 	}
        // })
        $('#save').submit(function(){
        	var pid=$('#chosen').val();
        	if (pid) {
        		return true;
        	}else{
        		art.dialog.tips('请选择商品');
        		$('#chosen').focus();
        		return false;
        	}
        })
	})
    function del(id){
    	art.dialog.confirm('确定要删除吗？',function(){
    		$.ajax({
    			url:"<?php echo U('Warehouse/saveScanPro');?>",
    			type:"post",
    			data:"ProId="+id,
    			dataType:"json",
    			success:function(msg){
    				if (msg=='success') {
    					art.dialog.tips('删除成功');
    					$('#'+id).remove();
    				}else{
    					art.dialog.tips('删除失败');
    				}
    			}
    		})
    	},function(){
    		art.dialog.tips('取消操作');
    	})
    }
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>