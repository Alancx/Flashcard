<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
  <title>打印发货单</title>

  <!-- Bootstrap -->
  <link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">
  <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="/Public/Admin/Admin/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
  <script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
  <script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
  <link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style type="text/css">
       td{
        text-align: center;
        font-size: 5px!important;
      }
    </style>
  </head>
  <body>
    <div class="container" style="width:100%!important;" id="content">
      <div class="row">
        <div style="width:100%;"></div>
        <div class="col-xs-12" style="font-size:25px;text-align:center;"><b><?php echo ($sinfo["storename"]); ?></b></div>
        <div class="col-xs-12" style="font-size: 15px;">买家姓名：<?php echo ($oinfo["RecevingName"]); ?></div>
        <div class="col-xs-12" style="font-size: 20px;"><b>就餐码：<?php echo ($oinfo["ShortOid"]); ?></b></div>
        <div class="col-xs-12" style="font-size: 15px;">就餐人数：<?php echo ($oinfo["EatingNums"]); ?></div>
        <?php if($oinfo['TableId']): ?><div class="col-xs-12" style="font-size: 20px;"><b>桌号：<?php echo ($oinfo["TableId"]); ?></b></div><?php endif; ?>
        <div class="col-xs-12" style="font-size: 15px;">订单号：<?php echo ($oinfo["OrderId"]); ?></div>
        <div class="col-xs-12" style="font-size: 15px;">下单时间：<?php echo ($oinfo["CreateDate"]); ?></div>

        <div style="clear:both;"></div>
        <div style="width:100%;border-bottom:2px dotted #000;"></div>
        <table class="table table-bordered">
        	<tr>
    		<td style="font-size:5px;text-align:left;">菜品</td>
            <td style="font-size:5px;text-align:left;">单价</td>
            <td style="font-size:5px;text-align:left;">数量</td>
            <td style="font-size:5px;text-align:left;">合计</td>

          </tr>
          <?php if(is_array($pros)): foreach($pros as $key=>$pro): ?><tr>
            <td style="font-size:5px;text-align:left;"><?php echo ($pro["ProName"]); ?> <?php echo ($pro["Spec"]); ?></td>
            <td style="font-size:5px;text-align:left;"><?php echo ($pro["Price"]); ?></td>
            <td style="font-size:5px;text-align:left;"><?php echo ($pro["Count"]); ?></td>
            <td style="font-size:5px;text-align:left;"><?php echo ($pro["Money"]); ?></td>
          </tr><?php endforeach; endif; ?>
      </table>
      <div style="width:100%;border-bottom:2px dotted #000;"></div>
      <div class="col-xs-12">总计：<?php echo ($allmoney); ?></div>
      <div class="col-xs-12">优惠：<?php echo ($oinfo["Coupon"]); ?></div>
      <div class="col-xs-12"><b>实付：<?php echo ($oinfo["PayMoney"]); ?></b></div>
      <div class="col-xs-12" style="font-size: 18px;" ><b>备注信息：<?php echo ($oinfo["MessageByBuy"]); ?></b></div>
    </div>
    <div style="clear:both;"></div>
    <div style="width:100%;border-bottom:1px solid #ccc;"></div>
  </div>
  <div class="col-xs-12" style="text-align:right;margin-top:50px;margin-right:20px;"><button type="button" class="btn btn-success" id="btnPrint">&nbsp;&nbsp;&nbsp;&nbsp;打&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 印&nbsp;&nbsp;&nbsp;&nbsp;</button></div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
</body>
<script type="text/javascript" src="/Public/Admin/Admin/PrintArea.js?v=1"></script>
<script type="text/javascript">
  $(function(){
            //打印
            $("#btnPrint").bind("click",function(event){
              $("#content").printArea();
              art.dialog.close();
            });
          });
</script>
</html>