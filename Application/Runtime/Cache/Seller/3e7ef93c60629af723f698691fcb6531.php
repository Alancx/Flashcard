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
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;margin-top:20px;">
		<form action="" class="form-inline" method="post"  id="search">
	<div class="input-group">
		<span class="input-group-addon">截止时间</span>
		<input type="text" name="endtime" id="etime" class="form-control" placeholder="下单时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:'%y-%M-%d'})" value="<?php echo ($data["endtime"]); ?>">
	</div>
	<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button>&emsp;&emsp;<small style="color:red;">仅优惠订单扣除手续费 <?php if($sinfo['IsFreeCut'] == 1): ?><b style="color:#ad0dec;">免结算手续费时间段 <?php echo ($sinfo["FreeStime"]); ?>-<?php echo ($sinfo["FreeEtime"]); ?></b><?php endif; ?></small>
	<!-- <button class="btn btn-default btn-md btn-outline" type="button" id="updateCut">更新引流佣金</button>  -->
	 <!-- <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-export"></span> 导出</button> -->
</form>

</div>
	<div class="col-sm-12">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>金额</th>
					<th>扣点比率</th>
					<!-- <th>结算账户</th> -->
					<th>最后结算时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="tbody">
				<?php if($info): ?><tr>
					<td><?php echo ($info["Allmoney"]); ?> （平台扣点：<?php echo ($info["cut"]); ?>）</td>
					<td><?php echo ($info["CutNum"]); ?>%</td>
					<!-- <td><?php echo ($bkinfo["BankName"]); ?> <br> <?php echo ($bkinfo["IdCard"]); ?></td> -->
					<td><?php echo (date('Y-m-d H:i:s',$info["Lastcut"])); ?></td>
					<td><button class="btn btn-xs btn-warning cut" data-stoken='<?php echo ($info["stoken"]); ?>'  stime='<?php echo ($data["strtime"]); ?>' etime='<?php echo ($data["endtime"]); ?>' data-cut='<?php echo ($cutype); ?>'>结算</button>&emsp; <button class="btn btn-default btn-xs showdetail" stime='<?php echo ($data["strtime"]); ?>' etime='<?php echo ($data["endtime"]); ?>' data-stoken='<?php echo ($info["stoken"]); ?>'>查看明细</button></td>
				</tr>
				<?php else: ?>
				<tr>
					<td colspan="6">该商户暂无待结算信息</td>
				</tr><?php endif; ?>
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
		$('#search').submit(function(){
			var etime=$('#etime').val();
			var sid=$('#sid').val();			
			if (etime) {
				return true;
			}else{
				art.dialog.tips('请选择结算截止时间');
				return false;
			}
		})
		$('.cut').click(function(){
			var stoken=$(this).attr('data-stoken');
			var stime=$(this).attr('stime');
			var etime=$(this).attr('etime');
			var _html='';
			if (stime && etime) {
				_html='确定要对'+stime+'--'+etime+'（下单时间）内的订单结算吗？';
			}else{
				_html="确定要对全部订单结算吗？";
			}
			art.dialog.confirm(_html,function(){
				art.dialog.tips('处理中...');
				$.ajax({
					url:"<?php echo U('Stores/cuted');?>",
					type:"post",
					data:"stime="+stime+"&etime="+etime,
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							art.dialog.tips('结算完成');
							window.location.href="<?php echo U('Stores/CashManager');?>";
						}else{
							art.dialog.tips(msg.info);
						}
					}
				})
			},function(){});
		})
		$('.showdetail').click(function(){
			var stoken=$(this).attr('data-stoken');
			var stime=$(this).attr('stime');
			var etime=$(this).attr('etime');
			art.dialog.open("<?php echo U('Stores/getdetail');?>?stoken="+stoken+"&stime="+stime+"&etime="+etime,{width:900,height:600});
		})
		$('.showyl').click(function(){
			var stoken=$(this).attr('data-stoken');
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			art.dialog.open("<?php echo U('Storers/getyl');?>?stoken="+stoken+"&stime="+stime+"&etime="+etime,{width:900,height:600});
		})
	})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>