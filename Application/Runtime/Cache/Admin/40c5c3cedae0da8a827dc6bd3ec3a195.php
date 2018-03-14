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
    <div class="col-lg-12 col-md-12" style="">
		
    </div>
        <div class="col-lg-12">
            <div class="ibox-content dash-ibox-content">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>店铺信息</th>
                            <th>店主信息</th>
                            <th>开店时间</th>
                            <th>历史营收</th>
                            <th>当前余额</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($lists)): foreach($lists as $key=>$ass): ?><tr>
                            <td><?php echo ($ass["storename"]); ?> <br><?php echo ($ass["province"]); echo ($ass["city"]); echo ($ass["area"]); echo ($ass["addr"]); ?></td>
                            <td><?php echo ($ass["TrueName"]); ?> <br><?php echo ($ass["IdCard"]); ?> <br><?php echo ($ass["tel"]); ?></td>
                            <td><?php echo ($ass["CreateDate"]); ?></td>
                            <td><?php echo (number_format($ass["TotalMoney"],'2')); ?></td>
                            <td><?php echo (number_format($ass["Money"],'2')); ?></td>
                            <td><button data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary btn-outline showdetail" data-totalmoney="<?php echo ($ass["TotalMoney"]); ?>" data-money="<?php echo ($ass["Money"]); ?>" data-stoken='<?php echo ($ass["stoken"]); ?>'>查看账户明细</button></td>
                        </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align:right;"><?php echo ($page); ?></div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">账户明细 <span id='notice' style='font-weight:bold;color:red;font-size:.8em'></span></h4>
      </div>
      <div class="modal-body">
		<table class="table table-bordered table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th colspan="4">总营收：<span id='totalmoney'></span> &emsp; 当前余额：<span id='money'></span>&emsp;&emsp;&emsp; <small style='font-size:.5em;color:orange'>部分金额处理中（等待平台处理的提现申请）</small></th>
				</tr>
				<tr>
					<th>时间</th>
					<th>金额</th>
					<th>类型</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody id="detailist">
				
			</tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="showmore" data-page='0' data-stoken='' style="display:none;"></button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
      </div>
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
var pagesize=15;
	$(document).ready(function(){
		$('.showdetail').click(function(){
			var _this=$(this);
			var stoken=_this.attr('data-stoken');
			$('#showmore').attr('data-stoken',stoken);
			$('#totalmoney').html(_this.attr('data-totalmoney'));
			$('#money').html(_this.attr('data-money'));
			$('#detailist').html('');
			$('#notice').html('加载中...');
			$.ajax({
				url:"<?php echo U('Storers/showmoneydetail');?>",
				type:"post",
				data:"stoken="+stoken+"&type=first&pagesize="+pagesize,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var data=msg.data;
						var _html="";
						$.each(data,function(index,item){
							_html+="<tr><td>"+item.CreateDate+"</td><td>"+item.Money+"</td><td>"+item.Type+"</td><td>"+item.Useage+"</td></tr>";
						})
						$('#detailist').append(_html);
						if (msg.hasmore=='yes') {
							$('#showmore').show().attr('data-page',pagesize).html('加载更多');
						}
						$('#notice').html('');
					}else{
						$('#notice').html('暂未查询到记录');
					}
				}
			})
		})
		$(document).on('click','#showmore',function(){
			var _this=$(this);
			var stoken=_this.attr('data-stoken');
			var pagenum=_this.attr('data-page');
			$('#notice').html('加载中...');
			$.ajax({
				url:"<?php echo U('Storers/showmoneydetail');?>",
				type:"post",
				data:"type=second&pagesize="+pagesize+"&pagenum="+pagenum+"&stoken="+stoken,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var data=msg.data;
						var _html="";
						$.each(data,function(index,item){
							_html+="<tr><td>"+item.CreateDate+"</td><td>"+item.Money+"</td><td>"+item.Type+"</td><td>"+item.Useage+"</td></tr>";
						})
						$('#detailist').append(_html);
						if (msg.hasmore=='yes') {
							$('#showmore').show().attr('data-page',parseInt(pagenum)+parseInt(pagesize)).html('加载更多');
							$('#notice').html('');
						}else{
							$('#showmore').hide();
							$('#notice').html('没有更多了');
						}
					}else{
						$('#showmore').hide();
						$('#notice').html('没有更多了');
					}
				}
			})
		})
	})
</script>
</html>