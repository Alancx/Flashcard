<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>noname</title>

    <!-- Bootstrap -->
    <link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/my.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/Public/Admin/Admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
      <table class="table table-hovered table-borderd">
      	<thead>
      		<tr>
      			<th>#</th>
      			<th>订单号</th>
            <th>金额</th>
      			<th>支付方式</th>
      		</tr>
      	</thead>
      	<tbody>
      	<?php if(is_array($infolist)): foreach($infolist as $key=>$info): ?><tr>
      			<td>#</td>
      			<td><?php echo ($info["OrderId"]); ?></td>
            <td><?php echo ($info["Price"]); ?></td>
      			<td><?php echo ($info["PayName"]); ?></td>
      		</tr><?php endforeach; endif; ?>
      	</tbody>
      	<tfoot>
      		<tr>
      			<th colspan="3"><?php echo ($page); ?></th>
      		</tr>
      	</tfoot>
      </table>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  </body>

</html>