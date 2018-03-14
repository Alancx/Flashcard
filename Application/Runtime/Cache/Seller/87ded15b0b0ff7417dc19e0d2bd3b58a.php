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
<style type="text/css">
    .attrvalues{
        border: 1px solid green;
        margin:auto 2px;
        border-radius: 2px;
        padding-left: 2px;
        padding-right: 2px;
    }
    .attrvalues:hover{
        cursor: pointer;
        border: 1px solid red;
        border-radius: 2px;
        background: red;
    }
</style>
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


 <div class="row wrapper border-bottom white-bg page-heading">
     <div class="col-lg-10">
         <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>商品属性管理</h2>
         <ol class="breadcrumb">
             <li>
                 <a href="index.html">主页</a>
             </li>
             <li class="active">
                 <strong>商品属性管理</strong>
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
                                <h5>添加属性</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <form role="form" class="form-inline" action="<?php echo U('Products/saveAttrs');?>" method="post" id="save">
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">属性名称</label>
                                        <input type="text" name="AttributeName" placeholder="请填写属性名称"  class="form-control" id="AttributeName">
                                    </div>
                                    <div class="checkbox m-l m-r-xs">
                                        <label class="i-checks">
                                            <input type="radio" name="IsEnable" id="IsEnable" value="1" ><i></i> 启用</label>
                                    </div>
                                    <div class="checkbox m-l m-r-xs">
                                        <label class="i-checks">
                                            <input type="radio" name="IsEnable" id="DisEnable" value="0" checked="checked"><i></i> 不启用</label>
                                    </div>
                                    <input type="hidden" name="AttributeId" id="AttributeId" value="">
                                    <button class="btn btn-white" type="submit" id="saveNotice">保 存</button>
                                </form>
                            </div>
                <div class="alert alert-warning">
                                         1、属性添加请按照名对应值的方式添加 ，例如属性名称为颜色，属性值有 红色 绿色....<br>
                                     </div>
                        </div>
                    </div>

<div class="col-lg-10">
<h3>分类管理</h3>
	<table class="table">
		<tr>
			<td>ID</td>
			<td>属性名称</td>
			<td>属性值----<small><操作提示：*点击属性值，可以删除该属性值></small></td>
			<td>是否启用</td>
			<td>删除</td>
		</tr>
        <?php if(is_array($attrs)): foreach($attrs as $key=>$attr): ?><tr>
			<td><?php echo ($attr["AttributeId"]); ?></td>
			<td><?php echo ($attr["AttributeName"]); ?></td>
			<td > <div id="avalue<?php echo ($attr["AttributeId"]); ?>"> <?php if(is_array($attr["values"])): foreach($attr["values"] as $key=>$value): ?><span class="attrvalues" id="v<?php echo ($value["AttributeValueId"]); ?>" onclick="delValue('<?php echo ($value["AttributeValueId"]); ?>')"><?php echo ($value["AttributeValue"]); ?></span><?php endforeach; endif; ?></div><button class="btn btn-primary btn-outline btn-xs" onclick="setValue('<?php echo ($attr["AttributeId"]); ?>','<?php echo ($attr["AttributeName"]); ?>');" style="margin-top:2px;">添加</button></td>
			<td><?php if($attr['IsEnable'] == '1'): ?>是<?php endif; if($attr['IsEnable'] == '0'): ?>否<?php endif; ?></td>
			<td><a href="###" onclick="edit('<?php echo ($attr["AttributeId"]); ?>');">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="###" onclick="del('<?php echo U('Products/delAttribute',array('id'=>$attr['AttributeId']));?>');">删除</a></td>
		</tr><?php endforeach; endif; ?>
	</table>
    <div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
</div>
<script type="text/javascript">
	function del(url){
        art.dialog.confirm('该属性下的值也将全部被删除，确定要删除吗？',function(){
            art.dialog({content:'正在处理，请勿重复操作...',lock:true});
            window.location.href=url;
        },function(){
            art.dialog.tips('取消操作');
        })
	}

	function setValue(id,name){
		// alert(id);
		art.dialog.data('attrid',id);
		art.dialog.data('atrname',name);
		art.dialog.open("<?php echo U('ArtDialog/setAttrValue');?>");
	}

    function delValue(id){
        art.dialog.confirm('您确定要删除此属性值吗？',function(){
            if (id) {
                $.ajax({
                    type:'post',
                    url:'<?php echo U('Products/delAttrValue');?>',
                    data:'id='+id,
                    dateType:'json',
                    success:function(msg){
                        if (msg=='success') {
                            art.dialog.tips('删除成功');
                            $("#v"+id).hide();
                        };
                        if (msg=='error') {
                            art.dialog.tips('删除失败');
                        };
                    }
                })
            }else{
                art.dialog.tips('请刷新页面后删除新添加属性值');
            }

        },function(){
            art.dialog.tips('取消操作');
        })
    }

    function edit(id){
        var attrdata=<?php echo ($attrjson); ?>;
        $.each(attrdata,function(index,item){
            if (item.AttributeId==id) {
                $("#AttributeId").val(item.AttributeId);
                $("#AttributeName").val(item.AttributeName);
            };
        })
    }
    $("#save").submit(function(){
        var AttributeName=$("#AttributeName").val();
        if (!AttributeName) {
            alert('请填写属性名称');
            return false;
        } else{
            art.dialog({content:'正在提交数据...',lock:true});
            return true;
        }
    })
</script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>