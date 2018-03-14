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
    <div class="col-lg-12 col-md-12" style="padding-bottom:10px;">
        <form action="" class="form-inline" method="post" id="saccess" style="margin-left:20px;">
            <div class="form-group">
                <input type="text" name="storename" id="storename" value="<?php echo ($data["storename"]); ?>" placeholder='请输入店铺名称' class='form-control'>
            </div>
            <button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button> 
            <!-- <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span>导 出</button> -->
        </form>
    </div>
        <div class="col-lg-12">
            <div class="ibox-content dash-ibox-content">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>店铺名称</th>
                            <th>银行</th>
                            <th>卡号</th>
                            <th>户主</th>
                            <th>联系人</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($banks)): foreach($banks as $key=>$ass): ?><tr>
                            <td><?php echo ($ass["storename"]); ?></td>
                            <td><?php echo ($ass["BankName"]); ?></td>
                            <td><?php echo ($ass["IdCard"]); ?></td>
                            <td><?php echo ($ass["IdName"]); ?></td>
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
    
</script>
</html>