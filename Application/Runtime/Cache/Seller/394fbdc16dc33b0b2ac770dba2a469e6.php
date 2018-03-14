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

<link href="/Public/Admin/Admin/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
</head><body><script>NProgress.start();$('.dropdown').click(function(){if ($('.dropdown-menu').attr('data-s')=='1') {$('.dropdown-menu').show().attr('data-s','0')}else{$('.dropdown-menu').hide().attr('data-s','1')};})</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header" style="text-align:center;padding-top:15px;"><span class='display:bolck;float:left;'><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><div style="text-align:center;float:right;"><span style='font-weight:bold;color:white;font-size:15px;clear:both;'><?php echo (session('Sname')); ?></span><div class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul data-s='1' class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
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
        <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>商品入库管理</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo U('Index/index');?>">主页</a>
            </li>
            <li class="active">
                <strong>入库管理</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12" style="padding:0px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>入库信息检索</h5>

                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form role="form" class="form-inline" method="post">
                        <div class="form-group">
                            <div class="input-daterange input-group" id="datepicker">
                                <span class="input-group-addon text-info"
                                      style="font-size:12px;border-width:0px;background-color: #fff;">入库日期：</span>
                                <input type="text" class="form-control" placeholder="开始日期" id="dateStart"
                                       name="dateStart" value="<?php echo ($datatime["dateStart"]); ?>" style="width:100px;">
                                <span class="input-group-addon">到</span>
                                <input type="text" class="form-control" placeholder="结束日期" id="dateEnd" name="dateEnd"
                                       style="width:100px;" value="<?php echo ($datatime["dateEnd"]); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn open" style="border-right-width:0px;">
                                    <select class="form-control" style="font-size:12px;border-right-width:0px;"
                                            id="Warehouse" name="Warehouse">
                                        <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>"><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="input-group-btn open" style="border-right-width:0px;">
                                    <select class="form-control" style="font-size:12px;border-right-width:0px;" id="intype" name="intype">
                                        <option value="-1">全部</option>
                                        <option value="0" >采购入库</option>
                                        <option value="1" >调拨入库</option>
                                        <option value="2" >退货入库</option>
                                        <option value="3" >差错入库</option>
                                    </select>
                                </div>
                                <input type="text" id="incard" name="incard" class="form-control"
                                       placeholder="请输入入库单号...">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn open" style="border-right-width:0px;">
                                    <select class="form-control" style="font-size:12px;border-right-width:0px;"
                                            id="proType">
                                        <option value="">全部分类</option>
                                        <?php if(is_array($typeList)): foreach($typeList as $key=>$vo): ?><option value="<?php echo ($vo["ClassId"]); ?>"><?php echo ($vo["ClassName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <input type="text" id="proid" name="proid" class="form-control"
                                       placeholder="请输入商品名称或规格编号...">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline btn-primary btnselect" type="button" data-type="first"
                                    style="margin-bottom: 0px;width:80px;" id="btnSeloK"><i class="fa fa-search"></i>&nbsp;&nbsp;检索
                            </button>
                        </div>
                    </form>
                </div>
                <!-- <div class="chat-discussion"> -->
                <!-- 查询数据 -->
                <div class="ibox-content timeline">
                </div>
                <!-- </div> -->
                <div class="ibox-content">
                    <table cellspacing="0" cellpadding="0" border="0" class="ui-pg-table" style="width:100%;table-layout:fixed;height:100%;" role="row">
                    <tbody>
                        <tr>
                            <td style="text-align:left;">
                                每次
                                <select name="select-page-size" id="select-page-size" title="更改后重新查询起效" aria-controls="editable" class="input-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50" selected="selected">50</option>
                                    <option value="100">100</option>
                                </select> 条记录
                            </td>
                            <td style="text-align:center;">
                                <a href="javascript:;" data-type="more" style="display:none;" class="btn btn-block btn-outline btn-default btnselect" id="btnmore">加载更多</a>
                                <input type="hidden" value="0" id="hf-page-index" />
                                <input type="hidden" value="25" id="hf-page-count" />
                            </td>
                            <td style="text-align:right;">
                                显示  <span class="text-default" id="sp-page-start">1</span>
                                到  <span class="text-default" id="sp-page-end">0</span>
                                共  <span class="text-default" id="sp-page-count"></span> 项
                            </td>
                        </tr>
                    </tbody>
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
<!-- Data picker -->
<script src="/Public/Admin/Admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<!-- validatereg -->
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
    var bindinfo = {
        postUrl: "<?php echo U('Invoicing/getinwarehouselist');?>",
        postDelURl:"<?php echo U('Invoicing/saveInwarehouse');?>",
        postcontinueUrl:"<?php echo U('Invoicing/inwarehouse');?>",
        autoinw:"<?php echo U('Invoicing/autoinw');?>",
        stimeEmpty: "开始日期不能为空!",
        etimeEmpty: "结束日期不能为空!",
        stimeError: "开始日期格式错误，格式为：yyyy-MM-dd",
        etimeError: "结束日期格式错误，格式为：yyyy-MM-dd",
        setimeplus: "开始日期小于结束日期！",
        emptyinfo: "未检索到查询条件数据...",
        infoerror: "查询出现异常数据，输出失败！"
    };
</script>
<script src="/Public/Admin/Seller/common/js/inwarehouselist.js"></script>
<script>NProgress.done();</script>
</body>
</html>