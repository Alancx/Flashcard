<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>蜂之巢-真心好蜜 全球共享</title>
    <meta name="keywords" content="蜂之巢-真心好蜜 全球共享">
    <meta name="description" content="蜂之巢">
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

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
</head><body><script>NProgress.start();</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header"><div class="dropdown profile-element"><span><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo ($_SESSION['userinfo']['userName']); ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Index/index');?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='商品管理' <?php if(CONTROLLER_NAME == 'Products'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Products/proadd');?>">商品添加</a></li><li><a href="<?php echo U('Products/index');?>">商品管理</a></li><li><a href="<?php echo U('Products/attributes');?>">商品属性管理</a></li><li><a href="<?php echo U('Products/yunfei');?>">运费模板管理</a></li></ul></li><li title='仓库管理' <?php if(CONTROLLER_NAME == 'Invoicing'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Invoicing/inwarehouselist');?>">入库单查询</a></li><li><a href="<?php echo U('Invoicing/inwarehouse');?>">入库单管理</a></li><li><a href="<?php echo U('Invoicing/outwarehouselist');?>">出库单查询</a></li><li><a href="<?php echo U('Invoicing/outwarehouse');?>">出库单管理</a></li><li><a href="<?php echo U('Invoicing/inventorylist');?>">盘点单查询</a></li><li><a href="<?php echo U('Invoicing/inventory');?>">库存盘点</a></li><li><a href="<?php echo U('Invoicing/index');?>">库存查询</a></li></ul></li><li title='订单管理' <?php if(CONTROLLER_NAME == 'Order'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Order/index');?>">订单概况</a></li><li><a href="<?php echo U('Order/allOrder');?>">全部订单</a></li></ul></li><li title='员工管理' <?php if(CONTROLLER_NAME == 'Admin'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Admin/add');?>">添加员工</a></li><li><a href="<?php echo U('Admin/index');?>">员工管理</a></li><li><a href="<?php echo U('Auth/group');?>">用户组管理</a></li></ul></li><li title='数据统计' <?php if(CONTROLLER_NAME == 'Statcenter'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Statcenter/Cancels');?>">支付核销员管理</a></li><li><a href="<?php echo U('Statcenter/getcancels');?>">提货核销员管理</a></li><li><a href="<?php echo U('Statcenter/PayType');?>">付款方式统计</a></li><li><a href="<?php echo U('Statcenter/poscash');?>">POS收银统计</a></li><li><a href="<?php echo U('Statcenter/posemp');?>">收银员数据统计</a></li></ul></li><li title='基本设置' <?php if(CONTROLLER_NAME == 'Warehouse'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cog"></i>&nbsp;&nbsp;<span class="nav-label">基本设置</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Warehouse/logistics');?>">物流设置</a></li></ul></li><li title='前台收银' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Cashier');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">前台收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Cashier/Index');?>">前台收银</a></li></ul></li><li title='门店管理' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Store/index');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>


<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
       <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>运费模板</h2>
       <ol class="breadcrumb">
           <li>
               <a href="index.html">主页</a>
           </li>
           <li class="active">
               <strong>运费模板设置</strong>
           </li>
       </ol>
   </div>
   <div class="col-lg-2"></div>
</div>

<div class="row wrapper  white-bg" style="margin:0px 1%;">
    <div class="col-lg-12">
       <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><a href="<?php echo U('Products/addYF');?>" style="color:#000;"><button class="btn btn-white" type="button">添加运费模板</button></a></h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xs-12">
            <h3>运费模板管理</h3>
            <table class="table table-bordered">
              <tr>
                 <td style="width:2%;">#</td>
                 <td style="width:6%;">模板名称</td>
                 <td colspan="3" style="width:35%;">计价区一</td>
                 <td colspan="3" style="width:35%;">计价区二</td>
                 <td style="width:15%;">备注</td>
                 <td style="width:7%;">操作</td>
             </tr>
              <tr>
                 <td>#</td>
                 <td>#</td>
                 <td style="width:7%">首重价格</td>
                 <td style="width:7%">续重加价</td>
                 <td>计费区域</td>
                 <td style="width:7%">首重价格</td>
                 <td style="width:7%">续重加价</td>
                 <td>计费区域</td>
                 <td>#</td>
                 <td>#</td>
             </tr>
             <?php if(is_array($Freights)): foreach($Freights as $key=>$fre): ?><tr <?php if($fre['IsSet'] == 1): ?>class="info"<?php endif; ?>>
                 <td><?php echo ($fre["ID"]); ?></td>
                 <td><?php echo ($fre["Name"]); ?></td>
                 <td><?php echo ($fre["FirstWeight"]); ?>g/<?php echo ($fre["Opiece"]); ?></td>
                 <td><?php echo ($fre["AddWeight"]); ?>g/<?php echo ($fre["Oadd"]); ?></td>
                 <td><?php if(is_array($fre["Area"])): foreach($fre["Area"] as $key=>$area): ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo ($area); endforeach; endif; ?></td>
                 <td><?php echo ($fre["FirstWeight"]); ?>g/<?php echo ($fre["Tpiece"]); ?></td>
                 <td><?php echo ($fre["AddWeight"]); ?>g/<?php echo ($fre["Tadd"]); ?></td>
                 <td><?php if(is_array($fre["Area1"])): foreach($fre["Area1"] as $key=>$area): ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo ($area); endforeach; endif; ?></td>
                 <td><?php echo ($fre["Remarks"]); ?></td>
                 <td><a href="###" onclick="edit('<?php echo ($fre["ID"]); ?>');">编辑</a> | <a href="###" onclick="del('<?php echo ($fre["ID"]); ?>')">删除</a> <br><br><?php if($fre['IsSet'] == 0): ?><a href="###" onclick="setMr(<?php echo ($fre["ID"]); ?>);">设为默认</a><?php endif; ?></td>
             </tr><?php endforeach; endif; ?>
     </table>
     <div style="text-align:right;"><?php echo ($page); ?></div>
 </div>
</div>
</div>
<script type="text/javascript">
  function edit(id){
    window.location.href="<?php echo U('Products/editYF');?>?id="+id;
  }
  function del(id){
    art.dialog.confirm('确定要删除此模板吗？',function(){
      window.location.href="<?php echo U('Products/delYF');?>?id="+id;
    },function(){
      art.dialog.tips('操作取消');
    })
  }

  function setMr(id){
    window.location.href="<?php echo U('Products/setMr');?>?fid="+id;
  }
</script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>