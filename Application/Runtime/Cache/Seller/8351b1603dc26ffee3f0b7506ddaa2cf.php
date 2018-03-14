<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="height:auto">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="renderer" content="webkit">

  <title>store</title>

  <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
  <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">

  <!-- Morris -->
  <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
  <link href="/Public/Admin/Admin/css/style.css?v=2.2.0" rel="stylesheet">
  <link href="/Public/Admin/Admin/css/my.css" rel="stylesheet">
  <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>

  <script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
  <script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
</head>

<body style="background-color:#fff;height:auto;">

  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2"></div>
  </div>
  <div class="row  wrapper  white-bg" style="margin:0 1%;">
    <div class="col-sm-10 col-sm-offset-1">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="5" style="text-align:center;"><?php echo ($username); ?>的核销记录</th>
          </tr>
          <tr>
            <th>订单号</th>
            <th>核销金额</th>
            <th>支付方式</th>
            <th>核销类型</th>
            <th>核销时间</th>
          </tr>
        </thead>
        <tbody>
          <?php if($lists): if(is_array($lists)): foreach($lists as $key=>$li): ?><tr>
                <td><?php echo ($li["OrderId"]); ?></td>
                <td><?php echo ($li["Price"]); ?></td>
                <td><?php if($li['PayName'] == 'XJ'): ?>现金支付<?php else: ?>其他<?php endif; ?></td>
                <td><?php if($CanType == 'get'): ?>提货<?php else: ?>支付<?php endif; ?></td>
                <td><?php echo ($li["Cantime"]); ?></td>
              </tr><?php endforeach; endif; ?>
            <?php else: ?>
            <tr>
              <td colspan="5">该核销员暂无核销记录</td>
            </tr><?php endif; ?>
        </tbody>
      </table>
      <div class="page"><?php echo ($page); ?></div>
    </div>
  </div>
  <!-- Mainly scripts -->
  <script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
</body>
</html>