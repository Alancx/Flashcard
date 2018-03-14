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

<link href="/Public/Admin/Admin/common/css/order.style.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<style type="text/css">
ul,ol,li{
    list-style: none;
    margin: 0;
    padding: 0;
}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="" class="form-inline" method="post" id="saccess">
            <div class="form-group">
                <input type="text" name="stime" id="stime" class="form-control" placeholder="开始时间(下单)" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($data["stime"]); ?>">
            </div>
            <div class="form-group">
                <input type="text" name="etime" id="etime" class="form-control" placeholder="结束时间(下单)" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($data["etime"]); ?>">
            </div>
            <button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button> <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span>导 出</button>
        </form>
    </div>
        <div class="col-lg-12">
            <div class="ibox-content dash-ibox-content">
            	<p type="text-left" style="font-weight: bold;font-size: 12px;">总额 :&emsp;<span><?php echo (number_format($total,'2')); ?>&emsp;元</span></p>
                <table class="table table-condensed table-bordered">

                    <thead>
                        <tr>
                            <th>店铺名称</th>
                            <th>销售额</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($saledata)): foreach($saledata as $key=>$ass): ?><tr>
                            <td><?php echo ($ass["storename"]); ?></td>
                            <td><?php echo (number_format($ass["allmoney"],'2')); ?></td>
                            <td><?php echo ($ass["TrueName"]); ?></td>
                            <td><?php echo ($ass["tel"]); ?></td>
                        </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align:right;"><?php echo ($page); ?></div>
        </div>
    </div>
</div>
<!--底部版权-->

<!--js引用-->
<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript">NProgress.done()</script>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		$('#saccess').submit(function(){
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
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
				return true;
			}
		})
		$('#import').click(function(){
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
						art.dialog.tips('正在处理...',3);
						window.location.href="<?php echo U('Storers/mersalesout');?>?stime="+stime+"&etime="+etime;
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
				window.location.href="<?php echo U('Storers/mersalesout');?>";
			}
		})
	})
</script>
</html>