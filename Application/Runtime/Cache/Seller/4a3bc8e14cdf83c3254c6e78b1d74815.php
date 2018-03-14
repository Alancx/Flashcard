<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>查找入库商品信息</title>
    <link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">

    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css?v=2.2.0" rel="stylesheet">

    <link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
    <style type="text/css">
        body {
            background-color: #F3F3F4;
            width: 1000px;
            height:600px;
        }
    </style>
    <script>
        var bindShowInfo={
            postProUrl:"<?php echo U('Invoicing/getproInfo');?>",
            postSaveUrl:"<?php echo U('Invoicing/saveInwarehouse');?>"
        };
        var supplier = [];
        var proCount = 0;
        var proinfo = {};
        var validater = {
            isnull: "不能为空",
            isintege:"最小为1的整数",
            isdecmal: "价格最小为0",
            issupplier: "供货商不为空"
        };

    </script>
    <!--商品规格合计小于100加载到页面中-->
    <?php $_RANGE_VAR_=explode(',',"1,200");if($proCount>= $_RANGE_VAR_[0] && $proCount<= $_RANGE_VAR_[1]):?><script>
            proCount = <?php echo ($proCount); ?>;
            proinfo = <?php echo ($proInfo); ?>;
        </script><?php endif; ?>
</head>
<body>
<div class="row">
    <div class="col-lg-9">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>商品信息</h5>
                <div class="ibox-tools">
                    <a class="collapse-card">
                        <i class="fa fa-chevron-up" id="InWarehouseId"><?php echo ($InWarehouseId); ?></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="commentForm" novalidate="novalidate">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <label for="ProType">商品分类：</label>
                            <select data-placeholder="请选择商品分类..." class="chosen-select" id="ProType" style="width:200px;"
                                    tabindex="2">
                                <option value="-1">请选择商品分类</option>
                                <?php if(is_array($typeList)): foreach($typeList as $key=>$vo): ?><option value="<?php echo ($vo["ClassId"]); ?>"><?php echo ($vo["ClassName"]); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-9">
                            <label for="ProType">商品名称或编码：</label>
                            <select data-placeholder="输入商品名称或编码查找..." class="chosen-select" id="ProName"
                                    style="width:500px;" tabindex="2">
                                <option value="-1">输入商品名称或编码查找</option>
                            </select>
                        </div>
                    </div>

                    <!--规格明细-->
                    <div class="form-group ibox-content" style="margin:0px;padding-left: 0px;padding-right: 0px;">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>规格编码</th>
                                <th>规格属性</th>
                                <th>剩余库存</th>
                                <th>入库数量</th>
                                <th>价格</th>
                            </tr>
                            </thead>
                            <tbody id="ProSpectbody">

                            </tbody>
                        </table>

                    </div>
                    <div class="form-group ibox-content" style="margin-bottom: 0px">
                        <p style="text-align: center;">
                            <button class="btn btn-outline btn-primary" type="button" style="width:100px;" id="btnOkInfo">提交所选</button>
                            <input type="hidden" value="<?php echo ($warehouseIndex); ?>" id="warehouse"/>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<!-- validatereg -->
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- Chosen -->
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<!-- artdialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script src="/Public/Admin/Seller/common/js/showInWarehouseProdialog.js?v=1.22"></script>
</body>
</html>