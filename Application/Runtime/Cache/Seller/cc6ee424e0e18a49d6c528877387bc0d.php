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

<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<!--面包屑 标题栏-->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 col-md-12" style="padding:0px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>盘点信息检索<small class="text-danger">仅支持单个盘点单据查询</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form role="form" class="form-inline" method="post">

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn open">
                                    <select class="form-control" style="font-size:12px;" id="Warehouse" name="Warehouse">
                                        <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>"><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                 <select data-placeholder="请选择盘点日期/盘点单号" class="chosen-select" name="inDate" id="inDate" tabindex="2">
                                    <option value="-1">请选择盘点日期/盘点单号</option>
                                    <?php if(is_array($dataIddate)): foreach($dataIddate as $key=>$vo): if($vo['WarehouseCard'] == $warehouseList[0]['WarehouseCard'] ): ?><option value="<?php echo ($vo["InventoryId"]); ?>"><?php echo ($vo["Date"]); ?>___<?php echo ($vo["InventoryId"]); ?>___<?php echo ($vo["Status"]); ?></option><?php endif; endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline btn-primary btnselect" type="button" style="margin-bottom: 0px;width:80px;" id="btnSeloK"><i class="fa fa-search"></i>&nbsp;&nbsp;检索
                            </button>
                             <button class="btn btn-outline btn-default btnselect" type="button"
                                style="margin-bottom: 0px;width:80px;" id="exportKC"><i class="fa fa-download"></i>&nbsp;&nbsp;导出
                        </button>
                        </div>
                    </form>
                </div>
                <!-- <div class="chat-discussion"> -->
                <!-- 查询数据 -->
                <div class="ibox-content timeline">

                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>
<!--底部版权-->

<!--js引用-->
<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Chosen -->
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>

<script src="/Public/Admin/Admin/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/Public/Admin/Admin/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
    var bindinfo = {
        postUrl: "<?php echo U('Invoicing/getInventoryList');?>",
        postDelURl: "<?php echo U('Invoicing/saveInventory');?>",
        postcontinueUrl: "<?php echo U('Invoicing/inventory');?>",
        emptyinfo: "未检索到查询条件数据...",
        infoerror: "查询出现异常数据，输出失败！"
    };
    var tempdata = [];
    var num = 0;
</script>
<?php if(!empty($num)): ?><script>
    num=<?php echo ($num); ?>;
    tempdata=<?php echo ($dataiddate); ?>;
</script><?php endif; ?>
<script src="/Public/Admin/Admin/common/js/inventorylist.js"></script>
</body>
</html>