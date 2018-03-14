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

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
</head><body><script>NProgress.start();$('.dropdown').click(function(){if ($('.dropdown-menu').attr('data-s')=='1') {$('.dropdown-menu').show().attr('data-s','0')}else{$('.dropdown-menu').hide().attr('data-s','1')};})</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header" style="text-align:center;padding-top:15px;"><span class='display:bolck;float:left;'><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><div style="text-align:center;float:right;"><span style='font-weight:bold;color:white;font-size:15px;clear:both;'><?php echo (session('Sname')); ?></span><div class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul data-s='1' class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div></div><div class="logo-element"></div></li><li title='主页' <?php if(FPAGE == 'Index/index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("Index/index");?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='商品管理' <?php if(FPAGE == ''): ?>class='active'<?php endif; ?>>
                        <a href="###"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Products/proadd");?>">商品添加</a></li><li><a href="<?php echo U("Products/index");?>">商品管理</a></li><li><a href="<?php echo U("Products/attributes");?>">商品属性管理</a></li><li><a href="<?php echo U("Products/discount");?>">优惠设置</a></li><li><a href="<?php echo U("Products/discountpart");?>">组合优惠</a></li><li><a href="<?php echo U("Products/coupons");?>">优惠券管理</a></li><li><a href="<?php echo U("Products/setbuy");?>">限购设置</a></li><li><a href="<?php echo U("Products/groupon");?>">团购设置</a></li></ul></li><li title='仓库管理' <?php if(FPAGE == ''): ?>class='active'<?php endif; ?>>
                        <a href="###"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Invoicing/inwarehouselist");?>">入库单查询</a></li><li><a href="<?php echo U("Invoicing/inwarehouse");?>">入库单管理</a></li><li><a href="<?php echo U("Invoicing/outwarehouselist");?>">出库单查询</a></li><li><a href="<?php echo U("Invoicing/outwarehouse");?>">出库单管理</a></li><li><a href="<?php echo U("Invoicing/inventorylist");?>">盘点单查询</a></li><li><a href="<?php echo U("Invoicing/inventory");?>">库存盘点</a></li><li><a href="<?php echo U("Invoicing/index");?>">库存查询</a></li><li><a href="<?php echo U("Invoicing/supplierList");?>">供应商库存查询</a></li></ul></li><li title='订单管理' <?php if(FPAGE == ''): ?>class='active'<?php endif; ?>>
                        <a href="###"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Order/index");?>">订单概况</a></li><li><a href="<?php echo U("Order/allOrder");?>">全部订单</a></li></ul></li><li title='员工管理' <?php if(FPAGE == ''): ?>class='active'<?php endif; ?>>
                        <a href="###"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Admin/add");?>">添加员工</a></li><li><a href="<?php echo U("Admin/index");?>">员工管理</a></li><li><a href="<?php echo U("Auth/group");?>">用户组管理</a></li></ul></li><li title='数据统计' <?php if(FPAGE == ''): ?>class='active'<?php endif; ?>>
                        <a href="###"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Statcenter/Cancels");?>">支付核销员管理</a></li><li><a href="<?php echo U("Statcenter/getcancels");?>">提货核销员管理</a></li><li><a href="<?php echo U("Statcenter/PayType");?>">付款方式统计</a></li><li><a href="<?php echo U("Statcenter/poscash");?>">POS收银统计</a></li><li><a href="<?php echo U("Statcenter/posemp");?>">收银员数据统计</a></li></ul></li><li title='前台收银' <?php if(FPAGE == 'Cashier'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("Cashier");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">前台收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Cashier/Index");?>">前台收银</a></li><li><a href="<?php echo U("Warehouse/ScanPay");?>">收银台</a></li></ul></li><li title='门店管理' <?php if(FPAGE == 'Store/index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("Store/index");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a></li><li title='商户结算' <?php if(FPAGE == ''): ?>class='active'<?php endif; ?>>
                        <a href="###"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">商户结算</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Stores/index");?>">商户结算</a></li></ul></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>


 <div class="row wrapper border-bottom white-bg page-heading">
     <div class="col-lg-10">
         <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>权限管理</h2>
         <ol class="breadcrumb">
             <li>
                 <a href="index.html">主页</a>
             </li>
             <li class="active">
                 <strong>用户组管理</strong>
             </li>
         </ol>
     </div>
 <div class="col-lg-2"></div>
 </div>



<div class="row wrapper  white-bg" style="margin:0px 1%;">
<div class="col-lg-12">
	<div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>添加用户组</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <form role="form" class="form-inline" action="<?php echo U('Auth/saveGroup');?>" method="post" id="save">
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">用户组名称</label>
                                        <input type="text" name="GroupName" placeholder="请填写用户组名称"  class="form-control" id="GroupName">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">用户组说明</label>
                                        <input type="text" name="Remarks" placeholder="请填写用户组说明(选填)"  class="form-control" id="Remarks">
                                    </div>
                                    <input type="hidden" name="GroupId" id="GroupId" value="">
                                    <button class="btn btn-white" type="submit" id="saveNotice">保 存</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                </form>
                            </div>
                        </div>
                    </div>
<div class="col-lg-10">
	<table class="table">
		<tr>
			<td>#</td>
			<td>用户组名称</td>
			<td>说明</td>
			<td>创建时间</td>
			<td>操作</td>
		</tr>
        <?php if(is_array($groups)): foreach($groups as $key=>$attr): ?><tr>
			<td><?php echo ($attr["GroupId"]); ?></td>
			<td><?php echo ($attr["GroupName"]); ?></td>
			<td ><?php echo ($attr["Remarks"]); ?></td>
			<td><?php echo (date("Y-m-d H:i:s",$attr["CreateDate"])); ?></td>
			<td><?php if($attr['GroupName'] == '超级管理组'): ?><a href="<?php echo U('Auth/distribute');?>?gid=<?php echo ($attr["GroupId"]); ?>">分配权限</a><?php else: ?><a href="###" onclick="edit('<?php echo ($attr["GroupId"]); ?>');">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Auth/distribute');?>?gid=<?php echo ($attr["GroupId"]); ?>">分配权限</a><?php endif; ?></td>
		</tr><?php endforeach; endif; ?>
	</table>
    <div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#save").submit(function(){
			var name=$("#GroupName").val();
			if (!name) {
				art.dialog.alert('请填写用户组名称');
				return false;
			}else{
				return true;
			}
		})
	})

	function edit(id){
		var data='';
		data=<?php echo ($jsondata); ?>;
		$.each(data,function(index,item){
			if (item.GroupId==id) {
				$("#GroupName").val(item.GroupName);
				$("#GroupId").val(item.GroupId);
				$("#Remarks").val(item.Remarks);
			};
		})
	}
</script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>