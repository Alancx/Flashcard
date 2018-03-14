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

<link href="/Public/Admin/Admin/js/plugins/kkpager/kkpager_blue.css" rel="stylesheet">
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
                            <div class="input-daterange input-group" id="datepicker">
                                <span class="input-group-addon text-info"
                                      style="font-size:12px;border-width:0px;background-color: #fff;">查询日期：</span>
                                <input type="text" class="form-control" placeholder="开始日期" id="dateStart" value="<?php echo $stime;?>"
                                       name="dateStart" style="width:100px;" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd'})">
                                <span class="input-group-addon">到</span>
                                <input type="text" class="form-control" placeholder="结束日期" id="dateEnd" name="dateEnd" value="<?php echo $etime;?>"
                                       style="width:100px;" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd'})">
                            </div>
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
                                        id="SupplierList">
                                    <?php if(is_array($supplierlist)): foreach($supplierlist as $key=>$vo): ?><option value="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                            <input type="text" id="proName" name="proName" class="form-control"
                                   placeholder="请输入商品名称或规格编号...">
                        </div>
                        <button class="btn btn-outline btn-primary btnselect" type="button"
                                style="margin-bottom: 0px;width:80px;" id="selectKC"><i class="fa fa-search"></i>&nbsp;&nbsp;检索
                        </button>
                       <!--  <button class="btn btn-outline btn-default btnselect" type="button"
                                style="margin-bottom: 0px;width:80px;" id="exportKC"><i class="fa fa-download"></i>&nbsp;&nbsp;导出
                        </button> -->
                    </form>
                </div>
                <!-- 内容信息 -->
                <div class="ibox-content">
                    <table class="table table-hover table-striped" id="data-example">
                        <thead>
                        <tr style="background-color:#eaeaea;">
                            <th>#</th>
                            <th>商品名称</th>
                            <th>规格编码</th>
                            <th>规格/属性</th>
                            <th>进出数</th>
                            <th>进出价</th>
                            <th>进出额</th>
                            <th>出入单号</th>
                            <th>类型</th>
                            <th>日期<i class="fa fa-sort-asc"</i></th>
                            <th>供应商</th>
                            <th>类别</th>
                        </tr>
                        </thead>
                        <tbody id="data-tbody">

                        </tbody>
                    </table>
                    <div class="alert alert-warning" id="alert_message">请选择筛选条件...</div>
                    <div id="kkpager"></div>
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
<!-- WdatePicker -->
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<!-- 分页 -->
<script src="/Public/Admin/Admin/js/plugins/kkpager/kkpager.min.js"></script>
<script>
var binddefaultinfo={
    postUrl:"<?php echo U('get_supplierBypage');?>",
    pno:1,         // 当前显示的页码
    pageCount:0,   // 数据条数
    totalPage:1   // 总页数
};
</script>
<script src="/Public/Admin/Admin/common/js/supplierList.js"></script>
<script>
SetPager();
</script>
</body>
</html>