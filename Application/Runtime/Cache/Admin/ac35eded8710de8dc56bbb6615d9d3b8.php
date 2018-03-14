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
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="ibox-title">
		<h5>提现管理</h5>
	</div>

	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="<?php echo U('Users/searchsss');?>" class="form-inline" method="post" id="search">
			<div class="form-group">
				<select name="type" id="type" class="form-control">
					<option value="">请选择查询类型</option>
					<option value="1">待审核</option>
					<option value="2">已审核</option>
					<option value="3">已处理</option>
				</select>
			</div>
			<div class="form-group">
				<input type="text" name="Member" placeholder="请填写会员账号(选填)" id="member" class="form-control">
			</div>
			<button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
		</form>

	</div>

	<div class="col-sm-12">
			<table class="table table-condensed table-bordered table-hover">
				<thead>
					<tr>
						<td style="width:5%">#</td>
						<td style="width:10%">提现账号</td>
						<td style="width:12%">提现人</td>
						<!-- <td style="width:5%">提现账户</td> -->
						<td style="width:9%">类型</td>
						<td style="width:13%">提现金额</td>
						<td style="width:18%">申请时间</td>
						<td style="width:18%">处理时间</td>
						<td style="width:10%">状态</td>
						<td style="width:10%">操作</td>
					</tr>
				</thead>
				<?php if($mps['msg']): ?><h3><?php echo ($mps["msg"]); ?></h3>
				<?php else: ?>
				<tbody id="tbody">
					<?php if(is_array($mps)): foreach($mps as $key=>$mber): ?><tr>
							<td><?php echo ($mber["ID"]); ?></td>
							<td><?php echo ($mber["MemberId"]); ?></td>
							<td><?php echo ($mber["GetName"]); ?></td>
							<!-- <td><?php echo ($mber["GetAccount"]); ?></td> -->
							<td><?php if($mber['AccountType'] != 'WXPAY'): ?>推广佣金<?php else: ?>会员收益<?php endif; ?></td>
							<td><?php echo ($mber["Money"]); ?></td>
							<td><?php echo ($mber["CreateDate"]); ?></td>
							<td><?php echo ($mber["EndDate"]); ?></td>
							<td><?php if($mber['Status'] == 1): ?>待审核<?php endif; if($mber['Status'] == 2): ?>已审核<?php endif; if($mber['Status'] == 3): ?>已处理<?php endif; ?></td>
							<td id="<?php echo ($mber['ID']); ?>"><?php if($mber['Status'] == 1): ?><button class="btn btn-primary btn-oultline btn-xs" type="button" onclick="checks('<?php echo ($mber['ID']); ?>');">通过审核</button><?php endif; if($mber['Status'] == 2): ?><button  class="btn btn-warning btn-oultline btn-xs" type="button" onclick="givecash('<?php echo ($mber['ID']); ?>');">发放红包</button><?php endif; ?>&nbsp;&nbsp;<button  class="btn btn-success btn-oultline btn-xs" type="button"   data-toggle="modal" data-target="#myModal6" onclick="Rmk('<?php echo ($mber["ID"]); ?>')">备注信息</button><input type="hidden" name="remarks" id="id<?php echo ($mber["ID"]); ?>" value="<?php echo ($mber["Remarks"]); ?>"></td>
						</tr><?php endforeach; endif; ?>
				</tbody><?php endif; ?>

			</table>

		<div><?php echo ($page); ?></div>
	</div>
</div>


<!-- model -start -->
                            <div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title">提现备注信息</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="##" class="form">
                                            	<div class="form-group" class="col-sm-12">
                                            		<label>备注信息</label>
                                            		<textarea name="Remarks" cols="30" rows="10" style="width:100%;" id="content" value=""></textarea>
                                            		<input type="hidden" name="hid" id="hid" value="">
                                            	</div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks">保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

<!-- model -end -->
<script type="text/javascript">

	function Rmk(id){
		var remarks=$("#id"+id).val();
		$("#hid").val(id);
		$("#content").val(remarks);
	}

	function checks(id){
		art.dialog.confirm('确定要通过审核吗？',function(){
			window.location.href="<?php echo U('Users/checkcash');?>?id="+id;
		},function(){
			art.dialog.tips('取消操作',1);
		})
	}

	function givecash(id){
		art.dialog.confirm('是否确认发放提现红包？',function(){
            var cash=art.dialog.tips('正在处理数据...',20,true);
			$.ajax({
				type:"post",
				url:"<?php echo U('Users/gethb');?>",
				data:"id="+id,
				dateType:"json",
				success:function(msg){
					art.dialog.alert(msg);
					$("#"+id).html('&nbsp;&nbsp;<button  class="btn btn-success btn-oultline btn-xs" type="button"   data-toggle="modal" data-target="#myModal6" onclick="Rmk(\''+id+'\')">备注信息</button><input type="hidden" name="remarks" id="id'+id+'" value="'+msg+'">')
					cash.close();
				}
			})
			// window.location.href="<?php echo U('Users/gethb');?>?id="+id;
		},function(){
			art.dialog.tips('取消操作');
		})
	}

	// function nocash(id){
	// 	art.dialog.confirm('请在备注信息中注明转账失败原因',function(){
	// 		window.location.href="<?php echo U('Users/nocash');?>?id="+id;
	// 	},function(){
	// 		art.dialog.tips('取消操作',1);
	// 	})
	// }


	$(document).ready(function(){
		$("#search").submit(function(){
			var type=$("#type").val();
			var member=$("#member").val();
			if (!member && !type) {
				art.dialog.alert('请输入查询内容');
				return false;
			};
			if (!type) {
				art.dialog.alert('请选择查询类型');
				return false;
			} else{
				return true;
			}

		})


		$("#saveRemarks").click(function(){
			var id=$("#hid").val();
			var text=$("#content").val();
			console.log(text);
			$.ajax({
				type:"post",
				url:"<?php echo U('Users/cashRmks');?>",
				data:"id="+id+"&text="+text,
				dateType:"json",
				success:function(msg){
					if (msg=='success') {
						art.dialog.tips('备注成功');
						$("#id"+id).val(text);
					};
					if (msg=='error') {
						art.dialog.tips('备注失败');
					};
				}
			})
		})



	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>