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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/Admin/addcoupons.js"></script>
<link rel="stylesheet" href="/Public/Admin/addcoupons.css">
<style type="text/css">
    .form-group{
      padding-top: 50px;
    }
    body{
    background-color: #fff!important;
  }
</style>
<div class="row wrapper  white-bg" style="margin:0px 1%;">
<div class="col-lg-12">

<div class="col-lg-10" style="padding-bottom:50px;">
  <form class="form" style="margin-top:50px;" method="post" <?php if($coupon['CouponId']): ?>action="<?php echo U('Products/addcoupons');?>"<?php endif; ?>>
    <div class="form-group">
      <label for="type" class="col-sm-2 control-label">请选择优惠券类型</label>
      <div class="col-sm-10">
        <select class="form-control" name="type" id="type" value="<?php echo ($coupon["Type"]); ?>">
          <option value="">请选择</option>
        <option value="0" <?php if($coupon['Type'] == '0'): ?>selected="selected"<?php endif; ?> >现金抵扣券</option>
        <option value="1" <?php if($coupon['Type'] == '1'): ?>selected="selected"<?php endif; ?> >折扣券</option>
        <option value="2" <?php if($coupon['Type'] == '2'): ?>selected="selected"<?php endif; ?> >满减券</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-2 control-label">请填写优惠规则</label>
      <div class="col-sm-10">
        <input type="text" name="rules" id="rules" class="form-control" value="<?php echo ($coupon["Rules"]); ?>" placeholder="">
      </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-2 control-label">生效时间</label>
      <div class="col-sm-10">
        <input type="text" name="stime" id="stime" value="<?php echo ($coupon["CreateDate"]); ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-{%d+0}'})" class="form-control">
      </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-2 control-label">失效时间</label>
      <div class="col-sm-10">
        <input type="text" name="etime" id="etime" value="<?php echo ($coupon["ExpiredDate"]); ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-{%d+0}'})" class="form-control">
      </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-2 control-label">数量</label>
      <div class="col-sm-10">
        <input type="number" name="nums" id="nums" value="<?php echo ($coupon["Count"]); ?>" class="form-control">
      </div>
    </div>
<!--     <div class="form-group">
      <label for="type" class="col-sm-2 control-label">用户可领取数量</label>
      <div class="col-sm-10">
        <input type="number" name="usernums" id="usernums" value="<?php echo ($coupon["UserCount"]); ?>" class="form-control">
      </div>
    </div>
 -->    <div class="col-sm-12">
      <div class="card">
        <div class="title" id="title"><?php echo ($coupon["CouponName"]); ?></div>
        <div class="content"><small id="content"><?php if($coupon['Type'] == '0'): ?>交易时使用此券可抵扣<?php echo ($coupon["Rules"]); ?>元<?php endif; if($coupon['Type'] == '1'): ?>交易时使用此券可享受<?php echo ($coupon["Rules"]); ?>折<?php endif; if($coupon['Type'] == '2'): ?>交易时使用此券,满<?php echo ($coupon["Ruless"]["0"]); ?>元 可减免<?php echo ($coupon["Ruless"]["1"]); ?>元<?php endif; ?></small></div>
        <div class="end">有效期：<span id="start"><?php echo ($coupon["CreateDate"]); ?></span> 至 <span id="en"><?php echo ($coupon["ExpiredDate"]); ?></span></div>
      </div>
    </div>
    <input type="hidden" name="couponid" value="<?php echo ($coupon["CouponId"]); ?>">
    <div class="form-group">
      <div class="col-sm-10 col-sm-offset-2"><button type="submit" class="btn btn-success btn-outline">保存内容</button></div>
    </div>
  </form>
</div>
</div>
</div>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>