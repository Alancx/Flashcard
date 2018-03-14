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
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<div class="row  wrapper  white-bg" style="margin:2% 1%;">
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="" class="form-inline" method="post" id="search">
			<div class="form-group">
				<input type="text" name="stime" id="stime" class="form-control" placeholder="请选择起始时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
			</div>
			<div class="form-group">
				<input type="text" name="etime" id="etime" class="form-control" placeholder="请选择查询结束时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
			</div>
			<div class="form-group">
				<input type="text" name="member" placeholder="请填写会员账号(选填)" id="member" class="form-control">
			</div>
			<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
			<button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span> 导 出</button>
		</form>

	</div>

	<div class="col-sm-12">
			<table class="table table-condensed table-bordered table-hover">
				<thead>
					<tr>
						<td>#</td>
						<td>账号</td>
						<td>真实姓名</td>
						<td>性别</td>
						<td>注册日期</td>
						<td>收货地址</td>
						<td>收货人名</td>
						<td>收货人电话</td>
						<td>操作</td>
					</tr>
				</thead>
				<?php if($mps['msg']): ?><h3><?php echo ($mps["msg"]); ?></h3>
				<?php else: ?>
				<tbody id="tbody">
					<?php if(is_array($mers)): foreach($mers as $key=>$mb): ?><tr>
							<td><?php echo ($mb["ID"]); ?></td>
							<td><?php echo ($mb["MemberId"]); ?></td>
							<td><?php echo ($mb["MemberName"]); ?></td>
							<td><?php echo ($mb["Sex"]); ?></td>
							<td><?php echo ($mb["RegisterDate"]); ?></td>
							<td><?php echo ($mb["Receving"]); ?></td>
							<td><?php echo ($mb["RecevingName"]); ?></td>
							<td><?php echo ($mb["RecevingPhone"]); ?></td>
							<td>
								<a class="btn btn-white btn-xs showorder" data-member='<?php echo ($mb["MemberId"]); ?>'>查看订单</a>
								<a class="btn btn-default btn-xs remark" data-member='<?php echo ($mb["ID"]); ?>' data-toggle="modal" data-target="#myModal6" data-rmk='<?php echo ($mb["Remarks"]); ?>'>备注</a>
								<a class="btn btn-warning btn-xs edit" data-member='<?php echo ($mb["ID"]); ?>'>编辑</a>
								<a class="btn btn-danger btn-xs delete" data-member='<?php echo ($mb["ID"]); ?>'>删除</a>
							</td>
						</tr><?php endforeach; endif; ?>
				</tbody><?php endif; ?>

			</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>


<!-- model -start -->
<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">会员备注信息</h5>
            </div>
            <div class="modal-body">
                <form action="##" class="form">
                	<div class="form-group" class="col-sm-12">
                		<label>备注信息</label>
                		<textarea name="Remarks" cols="30" rows="10" style="width:100%;" id="content" value=""></textarea>
                	</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks" data-member=''>保存</button>
            </div>
        </div>
    </div>
</div>
<!-- model -end -->
<script type="text/javascript">
var tempDom;
$(document).ready(function(){
	//删除
	$('.delete').click(function(){
		var mid=$(this).attr('data-member');
		art.dialog.confirm('确定要删除此会员吗？请慎重操作',function(){
			window.location.href="<?php echo U('Users/del');?>?id="+mid;
		});
	})
	//编辑
	$('.edit').click(function(){
		var id=$(this).attr('data-member');
		art.dialog.open('<?php echo U('Users/edit');?>?id='+id,{width:600,height:850});
	})
	$('.showorder').click(function(){
		window.location.href="<?php echo U('Order/allOrder');?>?username="+$(this).attr('data-member')+"&type=Users.member&state=0";
	})
	$('.remark').click(function(){
		tempDom=$(this);
		$('#content').val('');
		var rmk=$(this).attr('data-rmk');
		var id=$(this).attr('data-member');
		$('#content').val(rmk);
		$('#saveRemarks').attr('data-member',id);
	})

	$("#saveRemarks").click(function(){
		var id=$(this).attr('data-member');
		var text=$("#content").val();
		$.ajax({
			type:"post",
			url:"<?php echo U('Users/setRmks');?>",
			data:"id="+id+"&text="+text,
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					art.dialog.tips('操作成功');
					tempDom.attr('data-rmk',text);
				}else{
					art.dialog.tips('操作失败');
				}
			}
		})
	})

	$('#import').click(function(){
		var stime=$("#stime").val();
		var etime=$("#etime").val();
		var member=$("#member").val();
		if (stime || etime || member) {
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
						art.dialog.tips('正在处理...',3);
						window.location.href="<?php echo U('Users/memberOut');?>?stime="+stime+"&etime="+etime+"&member="+member;
					}else{
						art.dialog.tips('非法时间段');
					}
				}else{
					art.dialog.tips('请选择完整时间段');
				}
			}else{
				art.dialog.tips('正在处理...',3);
				window.location.href="<?php echo U('Users/memberOut');?>?member="+member;
			}
		}else{
			art.dialog.confirm('未选择导出条件，要导出全部数据吗？',function(){
				art.dialog.tips('正在处理...',3);
				window.location.href="<?php echo U('Users/memberOut');?>";
			})
		}
	})
	$('#search').submit(function(){
		var stime=$("#stime").val();
		var etime=$("#etime").val();
		var member=$("#member").val();
		if (stime || etime || member) {
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
						art.dialog.tips('正在处理...',3);
						return true;
					}else{
						art.dialog.tips('非法时间段');
						return false;
					}
				}else{
					art.dialog.tips('请选择完整时间段');
					return false;
				}
			}else{
				art.dialog.tips('正在处理...',3);
				return true;
			}
		}else{
			art.dialog.tips('请选择查询条件');
			return false;
		}
	})
})
function getQr(id){
	art.dialog.open('<?php echo U('ArtDialog/MQr');?>?mid='+id);
}
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>