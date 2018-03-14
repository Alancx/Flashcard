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
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;margin-top:20px;">
		<form action="" class="form-inline" method="post"  id="search">
			<div class="form-group">
				<select name="sid" id="sid" class="form-control" >
					<option value="">请选择结算商户</option>
					<?php if(is_array($stores)): foreach($stores as $key=>$store): ?><option value="<?php echo ($store["stoken"]); ?>" style="color:green;font-size:1.1em;"><?php echo ($store["storename"]); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="form-group">
				<select name="Status" id="Status" class="form-control" >
					<option value="all">全部</option>
					<option value="0" style="color:green;font-size:1.1em;">未结算</option>
					<option value="1" style="color:green;font-size:1.1em;">已结算</option>
				</select>
			</div>
			<div class="form-group">
				<input type="text" name="strtime" id="stime" class="form-control" placeholder='申请时间(起)' onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  value="<?php echo ($data["strtime"]); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="endtime" id="etime" class="form-control" placeholder="申请时间(止)" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($data["endtime"]); ?>">
			</div>
			<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button> 
			<button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-export"></span> 导出</button>
		</form>
	</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>商户名称</th>
					<th>结算金额</th>
					<th>申请时间</th>
					<th>结算账户</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="tbody">
			<?php if(is_array($lists)): foreach($lists as $key=>$list): ?><tr>
					<td><?php echo ($list["storename"]); ?></td>
					<td><?php echo ($list["Money"]); ?></td>
					<td><?php echo ($list["CreateDate"]); ?></td>
					<td><?php echo ($list["IdName"]); ?> / <?php echo ($list["IdCard"]); ?>  <br> 户名：<?php echo ($list["GetName"]); ?> </td>
					<td><?php if($list['Status'] == '1'): ?>已完成<?php elseif($list['Status'] == '2'): ?>转账申请已提交<?php else: ?> <button class="btn btn-xs btn-danger cuted" data-id="<?php echo ($list["ID"]); ?>">结算</button><?php endif; ?></td>
				</tr><?php endforeach; endif; ?>
			</tbody>

                            <div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title">撤销备注内容</h4>
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
                                            <h4 class="modal-title">撤销备注内容</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="##" class="form">
                                            	<div class="form-group" class="col-sm-12">
                                            		<label>备注信息</label>
                                            		<textarea name="Remarks" cols="30" rows="10" style="width:100%;" id="contents" value=""></textarea>
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
	$('#sid').chosen();
	$(document).ready(function(){
		$('#import').click(function(){window.location.href="<?php echo U('Storers/cutrecord');?>?param=out&outtype=banks&stime="+$('#stime').val()+"&etime="+$('#etime').val()+"&sid="+$('#sid').val()+"&Status="+$('#Status').val();})
		$('#search').submit(function(){
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
						return true;
					}else{
						art.dialog.tips('非法时间区间');
						return false;
					}
				}else{
					art.dialog.tips('请填写完整时间段');
					return false;
				}
			}else{
				return true;
			}
		})
		$('.cut').click(function(){
			var stoken=$(this).attr('data-stoken');
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			var _html='';
			if (stime && etime) {
				_html='确定要对'+stime+'--'+etime+'（下单时间）内的订单结算吗？';
			}else{
				_html="确定要对全部订单结算吗？";
			}
			art.dialog.confirm(_html,function(){
				window.location.href="<?php echo U('Storers/cuted');?>?stoken="+stoken+"&stime="+stime+"&etime="+etime;
			},function(){});
		})
		$('.showdetail').click(function(){
			var stoken=$(this).attr('data-stoken');
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			art.dialog.open("<?php echo U('Storers/getdetail');?>?stoken="+stoken+"&stime="+stime+"&etime="+etime,{width:900,height:600});
		})
		$('.cuted').click(function(){
			var id=$(this).attr('data-id');
			var _this=$(this);
			art.dialog.confirm('确定已结算吗？',function(){
				art.dialog.tips('处理中...');
				$.ajax({
					url:"<?php echo U('Storers/recordcuted');?>",
					type:"post",
					data:"id="+id,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							art.dialog.tips('处理成功');
							_this.attr('class','btn btn-xs btn-default').html('已处理');
						}else{
							art.dialog.tips('处理失败');
						}
					}
				})
			})
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>