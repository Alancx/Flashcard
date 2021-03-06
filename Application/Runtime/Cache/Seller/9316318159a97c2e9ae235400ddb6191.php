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

<!--面包屑 标题栏-->
<!--主体部分-->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>条件检索
                        <small class="text-info">查询只查询所选仓库，及 查询条件下的商品</small>
                    </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <!-- 条件检索 -->
                <div class="ibox-content">
                    <form role="form" class="form-inline" method="post">
                        <div class="form-group">
                            <label for="stime" class="sr-only">开始日期</label>
                            <input type="text" title="双击确定日期"
                                   onfocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss'})"
                                   placeholder="开始日期..." id="stime" class="Wdate form-control input-sm" value="<?php echo ($stime); ?>">
                        </div>
                        <div class="input-group">
                            <div class="input-group-btn open" style="border-right-width:0px;">
                                <select class="form-control" style="font-size:12px;border-right-width:0px;"
                                        id="Warehouse" name="Warehouse">
                                    <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>"><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="input-group-btn open" style="border-right-width:0px;">
                                <select class="form-control" style="font-size:12px;border-right-width:0px;"
                                        id="proType">
                                    <option value="">全部商品分类</option>
                                    <?php if(is_array($typeList)): foreach($typeList as $key=>$vo): ?><option value="<?php echo ($vo["ClassId"]); ?>"><?php echo ($vo["ClassName"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                            <input type="text" id="proName" name="proName" class="form-control"
                                   placeholder="请输入商品名称或规格编号...">
                        </div>
                        <div class="input-group">
                            <label class="checkbox-inline"><input type="checkbox" value="1" id="isZero">库存不为0</label>
                        </div>
                        <button class="btn btn-outline btn-primary btnselect" type="button"
                                style="margin-bottom: 0px;width:80px;" id="selectKC"><i class="fa fa-search"></i>&nbsp;&nbsp;检索
                        </button>
                        <button class="btn btn-outline btn-default btnselect" type="button"
                                style="margin-bottom: 0px;width:80px;" id="exportKC"><i class="fa fa-download"></i>&nbsp;&nbsp;导出
                        </button>
                    </form>
                </div>
                <!-- 内容信息 -->
                <div class="ibox-content">
                    <table class="table table-hover table-striped display" id="data-example">
                        <thead>
                        <tr style="background-color:#eaeaea;">
                            <th>序号</th>
                            <th>上级分类</th>
                            <th>商品名称</th>
                            <th>规格编码</th>
                            <th>规格/属性</th>
                            <th>期初库存</th>
                            <th>累计进货</th>
                            <th>进货数</th>
                            <th>盘盈</th>
                            <th>盘亏</th>
                            <th>累计销售数</th>
                            <th>销售数</th>
                            <th>调拨数</th>
                            <th>退货数【供应商】</th>
                            <th>当前库存</th>
                            <th>库存下限</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>序号</th>
                            <th>上级分类</th>
                            <th>商品名称</th>
                            <th>商品编码</th>
                            <th>规格/属性</th>
                            <th>期初库存</th>
                            <th>累计进货</th>
                            <th>进货数</th>
                            <th>盘盈</th>
                            <th>盘亏</th>
                            <th>累计销售数</th>
                            <th>销售数</th>
                            <th>调拨数</th>
                            <th>退货数【供应商】</th>
                            <th>当前库存</th>
                            <th>库存下限</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

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
<!-- dataTables -->
<script src="/Public/Admin/Admin/js/plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<!-- WdatePicker -->
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
var bindInfo={
    postUrl:"<?php echo U('Invoicing/kcselect');?>",
    postdialogUrl:"<?php echo U('Invoicing/indexinfo');?>"
}
</script>
<script src="/Public/Admin/Admin/common/js/invoicing_index.js"></script>
</body>
</html>