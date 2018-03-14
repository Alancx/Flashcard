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

<style type="text/css">
    .up-tr{
        color:red;
    }
</style>
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
</head><body><script>NProgress.start();</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header"><div class="dropdown profile-element"><span><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Index/index');?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='商品管理' <?php if(CONTROLLER_NAME == 'Products'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Products/proadd');?>">商品添加</a></li><li><a href="<?php echo U('Products/index');?>">商品管理</a></li><li><a href="<?php echo U('Products/attributes');?>">商品属性管理</a></li><li><a href="<?php echo U('Products/discount');?>">优惠设置</a></li><li><a href="<?php echo U('Products/discountpart');?>">组合优惠</a></li><li><a href="<?php echo U('Products/coupons');?>">优惠券管理</a></li></ul></li><li title='仓库管理' <?php if(CONTROLLER_NAME == 'Invoicing'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Invoicing/inwarehouselist');?>">入库单查询</a></li><li><a href="<?php echo U('Invoicing/inwarehouse');?>">入库单管理</a></li><li><a href="<?php echo U('Invoicing/outwarehouselist');?>">出库单查询</a></li><li><a href="<?php echo U('Invoicing/outwarehouse');?>">出库单管理</a></li><li><a href="<?php echo U('Invoicing/inventorylist');?>">盘点单查询</a></li><li><a href="<?php echo U('Invoicing/inventory');?>">库存盘点</a></li><li><a href="<?php echo U('Invoicing/index');?>">库存查询</a></li><li><a href="<?php echo U('Invoicing/supplierList');?>">供应商库存查询</a></li></ul></li><li title='订单管理' <?php if(CONTROLLER_NAME == 'Order'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Order/index');?>">订单概况</a></li><li><a href="<?php echo U('Order/allOrder');?>">全部订单</a></li></ul></li><li title='员工管理' <?php if(CONTROLLER_NAME == 'Admin'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Admin/add');?>">添加员工</a></li><li><a href="<?php echo U('Admin/index');?>">员工管理</a></li><li><a href="<?php echo U('Auth/group');?>">用户组管理</a></li></ul></li><li title='数据统计' <?php if(CONTROLLER_NAME == 'Statcenter'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Statcenter/Cancels');?>">支付核销员管理</a></li><li><a href="<?php echo U('Statcenter/getcancels');?>">提货核销员管理</a></li><li><a href="<?php echo U('Statcenter/PayType');?>">付款方式统计</a></li><li><a href="<?php echo U('Statcenter/poscash');?>">POS收银统计</a></li><li><a href="<?php echo U('Statcenter/posemp');?>">收银员数据统计</a></li></ul></li><li title='前台收银' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Cashier');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">前台收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Cashier/Index');?>">前台收银</a></li></ul></li><li title='门店管理' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Store/index');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a></li><li title='商户结算' <?php if(CONTROLLER_NAME == 'Stores'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">商户结算</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Stores/index');?>">商户结算</a></li></ul></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>

<!--面包屑 标题栏-->
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>商品盘存录入</h2>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo U('Index/index');?>">主页</a>
			</li>
			<li class="active">
				<strong>商品盘点录入</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px;">
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>基本信息录入</h5>
                    <div class="ibox-tools">
                        <a class="collapse-card">
                            <i style="font-style: italic;" id="InventoryId"><?php echo ($data[0]['InventoryId']); ?></i>
                        </a>
                    </div>
				</div>

                <div class="ibox-content" style="padding-bottom: 10px">
                    <form class="form-horizontal m-t" id="commentForm" novalidate="novalidate">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">盘点仓库：</label>
                            <div class="col-sm-8">
                                <select tabindex="1" class="form-control" id="storehouseName" name="storehouseName" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>" <?php if($vo['WarehouseCard'] == $data[0]['StorehouseId'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">盘点人：</label>
                            <div class="col-sm-8">
                                <select data-placeholder="请选择盘点人..." class="chosen-select" id="inputName" style="width:200px;" tabindex="2">
                                    <option value="-1">请选择盘点人</option>
                                    <?php if(is_array($employee)): foreach($employee as $key=>$vo): ?><option value="<?php echo ($vo["Id"]); ?>" <?php if($vo['Id'] == $data[0]['InputID'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">盘点日期：</label>
                            <div class="col-sm-8">
                                <input id="inDate" type="text" class="form-control Wdate" value="<?php echo ($data[0]['Date']); ?>" name="inDate" required="" aria-required="true" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd HH:mm'})" style="width:200px;display: inline-block;" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                <a type="button" data-container="body" data-toggle="popover" data-placement="right" data-content="如：2015-06-05 15:30" data-original-title="" title="" aria-describedby="popover481307">格式提示</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">备注说明：</label>
                            <div class="col-sm-8">
                                <textarea id="Remarks" name="comment" value="<?php echo ($data[0]['Remarks']); ?>" class="form-control" required="" aria-required="true"></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="text-align:center;clear:left;">
                            <label class="col-sm-3 control-label">加载盘点商品：</label>
                            <div class="col-sm-8">
                                <button class="btn btn-block btn-outline btn-primary" type="button" <?php if(!empty($cid)): ?>title="已禁用" disabled="disabled"<?php endif; ?> id="SelectPro">生成盘点商品</button>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <div class="col-sm-8">
                            <p>
                                <button class="btn btn-outline btn-primary" type="button" id="btnOkIn">提交盘点单据</button>
                                <button class="btn btn-outline btn-danger" type="button" id="btnDelIn">删除单据</button>
                                <button class="btn btn-outline btn-info" type="button" id="btnTqIn">提取草稿</button>
                            </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
		</div>

        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>盘点商品信息说明</h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-warning">
                       1、选择盘点仓库，盘点人，日期后，点击“生成盘点商品”加载商品信息！<br/>
                       2、根据盘点仓库的数量，修改实盘数量，该信息修改后，系统实时保存！<br/>
                       3、未盘点完的单据，在提取草稿可以提取到！<br/>
                       4、商品盘点完后，请点击 提交盘点单据 进行结转仓库库存！<br/>
                       5、建议大于或等于一个月盘点一次仓库，即录入一次该单据！
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-top: 0px;">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title" style="padding-bottom: 0px;">
					<h5>盘点商品明细<small class="text-danger">点击实盘数量编辑，回车修改实盘数量</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-hover" id="tabInfo">
						<thead>
							<tr>
								<th>#</th>
								<th>上级分类</th>
								<th>商品名称</th>
								<th>商品规格编码</th>
                                <th>规格/属性</th>
								<th>账面库存</th>
								<th>实盘数量</th>
								<th>盘存差</th>
                                <th>操作时间</th>
                                <th>是否上架</th>
							</tr>
						</thead>
                        <?php if(is_array($dataPro)): foreach($dataPro as $k=>$vo): ?><tr>
                                    <td><?php echo ($vo["RowNumber"]); ?></td>
                                    <td><?php echo ($vo["ClassName"]); ?></td>
                                    <td><?php echo ($vo["ProName"]); ?></td>
                                    <td><?php echo ($vo["ProIdInputCard"]); ?></td>
                                    <td><?php echo ($vo["Spec"]); ?></td>
                                    <td><span data-token="<?php echo ($vo["ProIdCard"]); ?>"><?php echo ($vo["BookCount"]); ?></span></td>
                                    <td><?php echo ($vo["ActualCount"]); ?></td>
                                    <td><?php echo ($vo["CountPoor"]); ?></td>
                                    <td><?php echo ($vo["Date"]); ?></td>
                                    <td><?php echo ($vo["IsShelves"]); ?></td>
                                </tr><?php endforeach; endif; ?>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>上级分类</th>
                                <th>商品名称</th>
                                <th>商品规格编码</th>
                                <th>规格/属性</th>
                                <th>账面库存</th>
                                <th>实盘数量</th>
                                <th>盘存差</th>
                                <th>操作时间</th>
                                <th>是否上架</th>
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
<script src="/Public/Admin/Admin/js/plugins/jeditable/jquery.jeditable.js"></script>

<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- Chosen -->
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<!-- dataTables -->
<script src="/Public/Admin/Admin/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/Public/Admin/Admin/js/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- WdatePicker -->
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
    var bindDefaultInfo={
        postNewUrl:"<?php echo U('Invoicing/inventory');?>",
        postSaveUrl:"<?php echo U('Invoicing/saveInventory');?>",
        postDraftUrl:"<?php echo U('Invoicing/showInventoryDraft');?>",
        cid:""
    };
</script>
<?php if(!empty($cid)): ?><script>
    bindDefaultInfo.cid="<?php echo ($cid); ?>";
</script><?php endif; ?>
<script src="/Public/Admin/Admin/common/js/inventory.js"></script>
<?php if(!empty($cid)): ?><script>
    bindDataPro(0, "");
</script><?php endif; ?>

<?php if(empty($cid)): ?><script>
NProgress.done();
</script><?php endif; ?>
</body>
</html>