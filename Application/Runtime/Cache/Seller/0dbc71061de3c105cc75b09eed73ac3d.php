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
<link rel="stylesheet" href="/Public/Admin/coupons.css">
<style type="text/css">
  body{
    background-color: #fff!important;
  }
</style>
<div class="row wrapper  white-bg" style="margin:0px 1%;">
<div class="col-lg-12">
	<div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <button type="button" class="btn btn-primary" id="addcoupons"><h5>添加优惠券</h5></button>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="col-lg-10">
<h3>优惠券管理</h3>
	<table class="table">
		<tr>
			<td>#</td>
			<td>创建时间</td>
			<td>卡片信息</td>
      <td>###</td>
			<td>操作</td>
		</tr>
        <?php if(is_array($coupons)): foreach($coupons as $key=>$cpn): ?><tr>
			<td><?php echo ($cpn["CouponId"]); ?></td>
			<td><?php echo ($cpn["LastUpdateDate"]); ?></td>
      <td>
        <div class="card">
          <div class="title" id="title"><?php echo ($cpn["CouponName"]); ?></div>
          <div class="content"><small id="content"><?php if($cpn['Type'] == '0'): ?>交易时使用此券可抵扣<?php echo ($cpn["Rules"]); ?>元<?php endif; if($cpn['Type'] == '1'): ?>交易时使用此券可享受<?php echo ($cpn["Rules"]); ?>折<?php endif; if($cpn['Type'] == '2'): ?>交易时使用此券,满<?php echo ($cpn["Rules"]["0"]); ?>元 可减免<?php echo ($cpn["Rules"]["1"]); ?>元<?php endif; ?></small></div>
          <div class="end">有效期：<span id="start"><?php echo ($cpn["CreateDate"]); ?></span> 至 <span id="en"><?php echo ($cpn["ExpiredDate"]); ?></span></div>
        </div>
      </td>
      <td><?php if($cpn["UseType"] == '0'): ?><button class="btn btn-primary btn-xs" onclick="goset('<?php echo ($cpn["CouponId"]); ?>');">设为摇一摇卡券</button><?php else: ?><button class="btn btn-danger btn-sm disabled" >摇一摇卡券</button><?php endif; ?>&emsp;<?php if($cpn['IsReg'] == '1'): ?><button class="btn btn-danger btn-sm disabled">注册赠送</button><?php else: ?><button class="btn btn-primary btn-xs" onclick="setreg('<?php echo ($cpn["CouponId"]); ?>')">设为注册赠送</button><?php endif; ?></td>
			<td><a href="###" onclick="edit('<?php echo ($cpn["CouponId"]); ?>');">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="###" onclick="del('<?php echo ($cpn["CouponId"]); ?>');">删除</a></td>
		</tr><?php endforeach; endif; ?>
	</table>
    <div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $("#addcoupons").click(function(){
    window.location.href="<?php echo U('Products/addcoupons');?>";
  })
})
function edit(id){
  window.location.href="<?php echo U('Products/editCoupon');?>?id="+id;
}
function del(id){
  art.dialog.confirm('确定要删除吗？',function(){
    window.location.href="<?php echo U('Products/delCoupon');?>?id="+id;
  });
}
function goset(id){
  window.location.href="<?php echo U('Products/setcpn');?>?id="+id;
}
function setreg(id){
  window.location.href="<?php echo U('Products/setreg');?>?id="+id;
}
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>