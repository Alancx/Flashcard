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

</head><body><script>NProgress.start();</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header" style="text-align:center;padding-top:15px;"><div style="text-align:center;font-weight:bold;color:white;font-size:15px;"><?php echo (session('Sname')); ?></div><div class="dropdown profile-element"><span><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Index/index');?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='商品管理' <?php if(CONTROLLER_NAME == 'Products'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Products/proadd');?>">商品添加</a></li><li><a href="<?php echo U('Products/index');?>">商品管理</a></li><li><a href="<?php echo U('Products/attributes');?>">商品属性管理</a></li><li><a href="<?php echo U('Products/discount');?>">优惠设置</a></li><li><a href="<?php echo U('Products/discountpart');?>">组合优惠</a></li><li><a href="<?php echo U('Products/coupons');?>">优惠券管理</a></li></ul></li><li title='仓库管理' <?php if(CONTROLLER_NAME == 'Invoicing'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Invoicing/inwarehouselist');?>">入库单查询</a></li><li><a href="<?php echo U('Invoicing/inwarehouse');?>">入库单管理</a></li><li><a href="<?php echo U('Invoicing/outwarehouselist');?>">出库单查询</a></li><li><a href="<?php echo U('Invoicing/outwarehouse');?>">出库单管理</a></li><li><a href="<?php echo U('Invoicing/inventorylist');?>">盘点单查询</a></li><li><a href="<?php echo U('Invoicing/inventory');?>">库存盘点</a></li><li><a href="<?php echo U('Invoicing/index');?>">库存查询</a></li><li><a href="<?php echo U('Invoicing/supplierList');?>">供应商库存查询</a></li></ul></li><li title='订单管理' <?php if(CONTROLLER_NAME == 'Order'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Order/index');?>">订单概况</a></li><li><a href="<?php echo U('Order/allOrder');?>">全部订单</a></li></ul></li><li title='员工管理' <?php if(CONTROLLER_NAME == 'Admin'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Admin/add');?>">添加员工</a></li><li><a href="<?php echo U('Admin/index');?>">员工管理</a></li><li><a href="<?php echo U('Auth/group');?>">用户组管理</a></li></ul></li><li title='数据统计' <?php if(CONTROLLER_NAME == 'Statcenter'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Statcenter/Cancels');?>">支付核销员管理</a></li><li><a href="<?php echo U('Statcenter/getcancels');?>">提货核销员管理</a></li><li><a href="<?php echo U('Statcenter/PayType');?>">付款方式统计</a></li><li><a href="<?php echo U('Statcenter/poscash');?>">POS收银统计</a></li><li><a href="<?php echo U('Statcenter/posemp');?>">收银员数据统计</a></li></ul></li><li title='前台收银' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Cashier');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">前台收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Cashier/Index');?>">前台收银</a></li><li><a href="<?php echo U('Warehouse/ScanPay');?>">收银台</a></li></ul></li><li title='门店管理' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Store/index');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a></li><li title='商户结算' <?php if(CONTROLLER_NAME == 'Stores'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">商户结算</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Stores/index');?>">商户结算</a></li></ul></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>

<!--面包屑 标题栏-->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>库存查询</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo U('Index/index');?>">主页</a>
            </li>
            <li class="active">
                <strong>库存查询</strong>
            </li>
        </ol>
    </div>
</div>
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
<div class="footer">
    <div>
        <strong>Copyright</strong> Store &copy; 2015
    </div>
</div>
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