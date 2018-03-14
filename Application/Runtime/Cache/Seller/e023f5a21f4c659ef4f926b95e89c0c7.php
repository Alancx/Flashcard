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
<link href="/Public/Admin/Admin/common/css/order.style.css" rel="stylesheet">
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
		<h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>订单管理</h2>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo U('Index/index');?>">主页</a>
			</li>
			<li class="active"> <strong>全部订单</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row order-all">
		<div class="col-lg-12">
			<div class="ibox-title">
				<h5>
					订单信息
					<small>显示条件筛选后的订单</small>
				</h5>
			</div>
			<div class="ibox-content">
				<form role="form" class="form-horizontal" method="post" id="allorder_form">
					<div class="filter-groups">
						<div class="form-group">
							<label class="control-label">订单号：</label>
							<div class="controls">
								<input type="text" name="order_no" class="form-control input-sm" id="order_no" maxlength="50" value="<?php echo ($order_no); ?>"></div>
						</div>
						<div class="form-group">
							<label class="control-label">会员账号或收货人：</label>
							<div class="controls">
								<input type="text" name="user_name" class="form-control input-sm" id="user_name" maxlength="50" value="<?php echo ($user_name); ?>"></div>
						</div>
						<div class="form-group">
							<label class="control-label">收货人手机：</label>
							<div class="controls">
								<input type="text" name="tel" class="form-control input-sm" id="tel" maxlength="50"></div>
						</div>
					</div>

					<div class="filter-groups" style="width:580px;">
						<div class="form-group">
							<label class="control-label">下单时间：</label>
							<div class="controls">
								<input type="text" name="start_time" id="start_time" onfocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',autoPickDate:true,maxDate:'%y-%M-{%d-0}'})" class="Wdate form-control input-sm" style="width:150px;display:inline-block;" value="<?php echo ($start_time); ?>">
								<span>至</span>
								<input type="text" name="end_time" id="end_time" onfocus="WdatePicker({startDate:'%y-%M-%d 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',autoPickDate:true,maxDate:'%y-%M-{%d}'})" class="Wdate form-control input-sm" style="width:150px;display:inline-block;" value="<?php echo ($end_time); ?>">
								&nbsp;&nbsp;
								<a class="btn btn-xs" data-day="6">最近7天</a>
								&nbsp;
								<a class="btn btn-xs" data-day="29">最近30天</a>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">订单状态：</label>
							<div class="controls">
								<select name="state" id="state" class="form-control input-sm" style="font-size:12px;width:120px;display:inline-block;">
									<option value="0" <?php if($state == 0): ?>selected="selected"<?php endif; ?> >全部</option>
									<option value="1" <?php if($state == 1): ?>selected="selected"<?php endif; ?>>待付款</option>
									<option value="2" <?php if($state == 2): ?>selected="selected"<?php endif; ?>>待发货</option>
									<option value="3" <?php if($state == 3): ?>selected="selected"<?php endif; ?>>已发货</option>
									<option value="4" <?php if(($state == 4) or ($state == 10)): ?>selected="selected"<?php endif; ?>>已完成</option>
									<option value="5" <?php if($state == 5): ?>selected="selected"<?php endif; ?>>退款中</option>
									<option value="6" <?php if($state == 6): ?>selected="selected"<?php endif; ?>>退货中</option>
									<option value="100" <?php if(($state == 7) or ($state == 8) or ($state == 9)): ?>selected="selected"<?php endif; ?>>已关闭</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">付款方式：</label>
							<div class="controls">
								<select name="buy_way" id="buy_way" class="form-control input-sm" style="font-size:12px;width:120px;display:inline-block;">
									<option value="ALL">全部</option>
									<option value="XJ">现金</option>
									<option value="T">微信支付</option>
									<option value="YE">余额支付</option>
									<option value="JL">奖励余额支付</option>
								</select>
							</div>

<!-- <option value="wxpay">微信安全支付</option>
									<option value="umpay">银行卡付款</option>
									<option value="codpay">货到付款/到店付款</option>
									<option value="peerpay">找人代付</option>
									<option value="presentpay">领取赠品</option>
									<option value="couponpay">优惠兑换</option> -->

						</div>
					</div>
					<div style="clear:left;"></div>
					<div>
						<button type="button" class="btn btn-w-m btn-success" id="btn_select">筛选</button>
						<button type="button" class="btn btn-w-m btn-white"  id="btn_excel" data-toggle="" data-target="#modaltoExcel" ><i class="fa fa-download"></i>&nbsp;&nbsp;批量导出</button>
						<button type="button" class="btn btn-w-m btn-default" id="pfahuo">批量打印发货单</button>
						<button type="button" class="btn btn-w-m btn-default" id="inport">导入订单数据</button>
						<!-- <button type="button" class="btn btn-w-m btn-default" id="pkuaidi">批量打印快递单</button> -->
						<button type="button" class="btn btn-w-m btn-primary btn-outline" id="sendX">批量发货</button>
					</div>
				</form>
			</div>
			<!-- 条件检索 -->
			<div class="ibox-content" style="padding-top:0px;">
				<div class="panel blank-panel">
					<!-- <div style="clear:both;"></div> -->
					<div class="panel-heading">
						<div class="panel-options">
							<ul class="nav nav-tabs">
								<li <?php if($state == 0): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="0" class="panel-active" <?php if($state == 0): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-th"></span>
										全部
									</a>
								</li>
								<li <?php if($state == 1): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="1" class="panel-active" <?php if($state == 1): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-yen"></span>
										待付款
									</a>
								</li>
								<li <?php if($state == 2): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="2" class="panel-active" <?php if($state == 2): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-hourglass"></span>
										待发货
									</a>
								</li>
								<li <?php if($state == 3): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="3" class="panel-active" <?php if($state == 3): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-hand-right"></span>
										已发货
									</a>
								</li>
								<li <?php if(($state == 4) or ($state == 10)): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="4" class="panel-active" <?php if(($state == 4) or ($state == 10)): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-ok-sign"></span>
										已完成
									</a>
								</li>
								<li <?php if($state == 5): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="5" class="panel-active" <?php if($state == 5): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-dashboard"></span>
										退款中
									</a>
								</li>
								<li <?php if($state == 6): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="6" class="panel-active" <?php if($state == 6): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-dashboard"></span>
										退货中
									</a>
								</li>
								<li <?php if(($state == 7) or ($state == 8) or ($state == 9)): ?>class="active"<?php endif; ?> >
									<a data-toggle="tab" data-state="100" class="panel-active" <?php if(($state == 7) or ($state == 8) or ($state == 9)): ?>aria-expanded="true"<?php else: ?>aria-expanded="false"<?php endif; ?>>
										<span class="glyphicon glyphicon-remove"></span>
										已关闭
									</a>
								</li>
							</ul>
							<!-- <a href="javascript:void(0);" class="js-help-icon pull-right" style="position:relative;top:0px;">?</a> -->
						</div>
					</div>
					<!-- <div style="clear:both;"></div> -->
					<div class="panel-body">
						<div class="tab-content">
						<!--  -->
							<div id="tablist" class="tab-pane active">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th colspan="2">商品</th>
											<th>单价/数量</th>
											<th>买家</th>
											<th>下单时间</th>
											<th>订单状态</th>
											<th>实付金额</th>
										</tr>
									</thead>
									<tbody id="torderlist">
										<?php if(is_array($dataOrder)): foreach($dataOrder as $k=>$vo): ?><tr>
												<td colspan="7"></td>
											</tr>
											<tr>
												<td colspan="7">
													<input type="checkbox" name="poid" id="" value="<?php echo ($vo["OrderId"]); ?>">
													<span style="color:#000">订单号：<?php echo ($vo["OrderId"]); ?></span>
													<span class="c-gray">
													<?php if($vo['PayName'] == 'XJ'): ?>现金付款
														<?php elseif($vo['PayName'] == 'T'): ?>微信支付
														<?php elseif($vo['PayName'] == 'YE'): ?>余额支付
														<?php elseif($vo['PayName'] == 'JL'): ?>奖励余额支付<?php endif; ?>
													</span>
													<span class="pull-right">
														<a data-toggle="modal" data-target="#orderinfo" data-order="<?php echo ($vo["OrderId"]); ?>" class="data-order-info">查看详情</a>&nbsp;-&nbsp;<a data-toggle="modal" data-order="<?php echo ($vo["OrderId"]); ?>" class="data_order_message" data-target="#order_message">备注</a>
													</span>
												</td>
											</tr>
											<?php if(is_array($vo['datason'])): foreach($vo['datason'] as $i=>$son): ?><tr>
													<td class="image-cell" >
														<img src="<?php echo ($son["ProLogoImg"]); ?>" alt='' style="width:60px;height:60px;"/>
													</td>
													<td class="product-cell">
														<p>
															<a href="<?php echo U('Home/Product/Goods');?>?pid=<?php echo ($son["ProId"]); ?>" target="_blank" title="<?php echo ($son["ProName"]); ?>"><?php echo ($son["ProName"]); ?></a>
														</p>
														<p><?php echo ($son["Spec"]); ?></p>
													</td>
													<td>
														<p><?php echo (sprintf('%.2f',$son["Price"])); ?></p>
														<p>(<?php echo ($son["Count"]); ?> 件)</p>
													</td>
													<?php if($i == 0): ?><td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<?php if($vo['RecevingPost'] == 'ZT'): ?><p>店铺自提货<?php if($vo['SceneContent']): ?>(<?php echo ($vo["SceneContent"]); ?>)<?php endif; ?></p>
															<?php else: ?>
															<p><?php echo ($vo["Name"]); ?></p>
															<p><?php echo ($vo["Tel"]); ?></p><?php endif; ?>

														</td>
														<td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<?php echo ($vo["Date"]); ?>
														</td>
														<td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<?php if($vo['Status'] == 1): ?><p>等待买家付款</p>
															<?php elseif($vo['Status'] == 2): ?>

															<?php if($vo['RecevingPost'] == 'ZT'): ?><p>店铺自提货</p>
															<?php else: ?>

																<p>已付款，等待发货</p>
																<p id="s<?php echo ($vo["OrderId"]); ?>"><button class="btn btn-default btn-outline status-button" type="button" onclick="prints('<?php echo ($vo["OrderId"]); ?>');">打印发货单</button>&nbsp;<button class="btn btn-default btn-outline status-button" type="button" onclick="printlog('<?php echo ($vo["OrderId"]); ?>');">打印快递单</button> <?php if($vo['IsCheck'] == 1): ?>&nbsp;<a href="javascript:;" data-toggle="modal" data-order="<?php echo ($vo["OrderId"]); ?>" class="btn btn-outline btn-default status-button order_send" data-target="#ModelSend">发&nbsp;&nbsp;货</a><?php else: ?> <button class="btn btn-default btn-outline status-button" type="button" onclick="checks('<?php echo ($vo["OrderId"]); ?>');">验 货</button><?php endif; ?></p><?php endif; ?>





															<?php elseif($vo['Status'] == 3): ?>
																<p>卖家已发货</p>
															<?php elseif(($vo['Status'] == 4) or ($vo['Status'] == 10)): ?>
																<p>已收货，交易完成</p>
															<?php elseif($vo['Status'] == 5): ?>
																<p>买家已退款</p>
																<p><a href="javascript:;" data-order="<?php echo ($vo["OrderId"]); ?>" class="btn btn-outline btn-default status-button order_back" data-transaction="<?php echo ($vo["TransactionId"]); ?>" data-type="money">点击确认退款</a></p>
															<?php elseif($vo['Status'] == 6): ?>
																<p>买家已退货</p>
																<p><a href="javascript:;" data-order="<?php echo ($vo["OrderId"]); ?>" class="btn btn-outline btn-default status-button order_back" data-type="pro">确认收到退货</a></p>
															<?php elseif($vo['Status'] == 7): ?>
																<p>买家退货成功</p>
															<?php elseif($vo['Status'] == 8): ?>
																<p>买家退款成功</p>
															<?php elseif($vo['Status'] == 9): ?>
																<p>已过期，订单关闭</p>
															<?php elseif($vo['Status'] == 11): ?>
																<p>订单已撤销</p>
															<?php else: endif; ?>
														</td>
														<td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<p><?php echo (sprintf('%.2f',$vo["Price"])); ?></p>
															<p><small class="c-gray">(含运费：<?php echo ($vo["Freight"]); ?>)</small></p>
														</td><?php endif; ?>
												</tr><?php endforeach; endif; endforeach; endif; ?>
									</tbody>
								</table>
								<?php if($pageCount == 0): ?><div class="alert alert-warning" id="alert_message" style="display:block;">还没有相关数据！</div>
								<?php else: ?>
									<div class="alert alert-warning" id="alert_message" style="display:none;"></div><?php endif; ?>
								<div id="kkpager"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 订单详情 start chat-discussion -->
<div class="modal inmodal fade" id="orderinfo" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 15px;">
				订单详情
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body page-trade-order-detail" style="padding:15px">
				<!-- 货物进程状态 -->
				<div class="step-region">
					<ul class="ui-step ui-step-4" id="modal_status_step">
					</ul>
				</div>
				<!-- 订单明细 -->
				<div class="content-region clearfix">
					<div class="info-region">
						<h3>
							<span>订单信息</span>
						</h3>
						<table class="info-table">
							<tbody id="modal_info_table1">
							</tbody>
						</table>
						<div class="dashed-line"></div>
						<table class="info-table">
							<tbody id="modal_info_table2">
							</tbody>
						</table>
						<!-- 二维码扫描部分 -->
						<div class="promotion-app hide">
							<div class="promotion-app-qrcode"></div>
							<div class="promotion-app-content">
								<p>随时随地经营店铺</p>
								<p>订单发货、库存管理通通搞定</p>
							</div>
						</div>
					</div>
					<div class="state-region">
						<div style="padding:0px 0 30px 40px;">
							<h3 class="state-title">
								<span class="icon info">!</span>
								<span>订单状态：</span>
								<span id="modal_order_status"></span>
							</h3>
							<div class="state-desc" id="modal_order_status_desc">
							</div>
							<div class="state-action" id="modal_order_sell_message">
								备注：-
							</div>
						</div>
						<div class="state-remind-region">
							<div class="dashed-line"></div>
							<div class="state-remind">
								<h4>温馨提醒：</h4>
								<ul>
									<li>请务必等待订单状态变更为“买家已付款，等待卖家发货”后再进行发货。</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- 订单商品明细 -->
				<table class="ui-table ui-table-simple goods-table">
					<thead>
						<tr>
							<th></th>
							<th class="cell-30">商品名称</th>
							<th>单价(元)</th>
							<th>数量</th>
							<th class="cell-13">小计(元)</th>
							<th>状态</th>
							<th class="text-center">运费</th>
						</tr>
					</thead>
					<tbody id="modal_table_info">
					</tbody>
					<tfoot>
						<tr>
							<td colspan="7" class="text-right">
								<span class="c-gray">实收总价：</span>
								<span class="real-pay ui-money-income">
									<span>¥</span>
									<span id="tfoot_real_money"></span>
								</span>
							</td>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>
</div>
<!-- 订单详情 end -->
<!-- 订单导出 start -->
<div class="modal inmodal fade" id="modaltoExcel" tabindex="-1" role="dialog"  aria-hidden="true">
 	<div class="modal-dialog ">
 		<div class="modal-content">
 			<div class="modal-header" style="padding:10px 15px;">
 				订单-导出
 				<button type="button" class="close" data-dismiss="modal">
 					<span aria-hidden="true" id="export-close">&times;</span>
 					<span class="sr-only">Close</span>
 				</button>
 			</div>
 			<div class="modal-body page-trade-order-detail" style="padding:15px">
 				<div class="clearfix">
	 				<div class="filter-meta">
	 					<span>下单时间：</span>
	 					<span id="export_time"></span>
	 				</div>
 				</div>
 				<div class="clearfix">
	 				<div class="filter-meta">
	 					<span>订单状态：</span>
	 					<span id="export_state"></span>
	 				</div>
	 				<div class="filter-meta">
	 					<span>付款方式：</span>
	 					<span id="export_pay_type"></span>
	 				</div>
	 				<div class="filter-meta">
	 					<span>收件人：</span>
	 					<span id="export_user_name"></span>
	 				</div>
 				</div>
 			</div>
 			<div class="modal-footer" style="text-align:left;">
 				<button type="button" class="btn btn-white btn-sm btn-js-export" data-export-type="default">下载普通报表</button>
				<button type="button" class="btn btn-white btn-sm btn-js-export" data-export-type="check" disabled="disabled">下载对账报表</button>
				<button type="button" class="btn btn-white btn-sm btn-js-export" data-export-type="newxls">下载指定报表(导入使用)</button>
            </div>
 		</div>
 	</div>
 </div>
<!-- 订单添加备注 end -->
<!-- 订单添加备注 start -->
<div class="modal inmodal fade" id="order_message" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-sm" style="width:428px;">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 15px;">
				订单备注
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body" style="padding:15px">
				<textarea class="js-remark" id="js-remark" rows="4" placeholder="最多可输入256个字符" maxlength="256" style="width: 396px;"></textarea>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-w-m btn-success input-sm" id="btn_message" data-dismiss="modal">提交</button>
			</div>
		</div>
	</div>
</div>
<!-- 订单添加备注 end -->
<!-- 发货 -->
<div class="modal inmodal fade" id="ModelSend" tabindex="-1" role="dialog"  aria-hidden="true">
 	<div class="modal-dialog modal-lg">
 		<div class="modal-content">
 			<div class="modal-header" style="padding:10px 15px;">
 				订单-发货
 				<button type="button" class="close" data-dismiss="modal">
 					<span aria-hidden="true">&times;</span>
 					<span class="sr-only">Close</span>
 				</button>
 			</div>
 			<div class="modal-body page-trade-order-detail" style="padding:15px">
 				<div class="content-region clearfix">
					<div class="info-region">
						<h3>
							<span>订单信息</span>
						</h3>
						<table class="info-table">
							<tbody id="modal_send_table1">
							</tbody>
						</table>
						<div class="dashed-line"></div>
						<table class="info-table">
							<tbody id="modal_send_table2">
							</tbody>
						</table>
					</div>
					<div class="state-region">
						<div style="padding:0px 0 30px 40px;">
							<h3 class="state-title">
								<span class="icon info">~</span>
								<span>发货信息：</span>
							</h3>
							<div class="state-desc" id="modal_order_status_desc" style="margin-bottom:0px;margin-top:20px;">
								<table class="info-table">
									<tbody>
										<tr>
											<td style="padding-bottom:0px;vertical-align:middle;">选择快递：</td>
											<td style="padding-bottom:0px;">
												<div class="form-inline">
													<select id="sendtype" name="sendtype" class="form-control input-sm" style="font-size:12px;">
														<option value="">请选择快递公司</option>
														<?php if(is_array($send)): foreach($send as $k=>$vo): ?><option value="<?php echo ($vo["Number"]); ?>" <?php if($k == 0): ?>selected="selected"<?php endif; ?>><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; ?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td style="padding-bottom:0px;padding-top:8px;vertical-align:middle;">
											快递单号：
										</td>
										<td style="padding-top:15px;">
											<div class="form-inline">
												<input type="text" placeholder="请输入快递单号" maxlength="50" id="sendCard" name="sendCard" class="form-control input-sm">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="padding-top:20px;">
											<button type="button" class="btn btn-w-m btn-success input-sm" data-order="" id="btn_send">提交</button>
											<button type="button" class="hide" data-dismiss="modal" id="btn_send_hide">提交</button>
										</td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="state-remind-region">
							<div class="dashed-line"></div>
							<div class="state-remind">
								<h4>温馨提醒：</h4>
								<ul>
									<li>请务必等待订单状态变更为“买家已付款，等待卖家发货”后再进行发货。</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
 				<table class="ui-table ui-table-simple goods-table">
					<thead>
						<tr>
							<th></th>
							<th class="cell-30">商品名称</th>
							<th>单价(元)</th>
							<th>数量</th>
							<th class="cell-13">小计(元)</th>
							<th>当前库存</th>
							<th>库存下限</th>
						</tr>
					</thead>
					<tbody id="modal_table_send">
					</tbody>
				</table>
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
<!-- WdatePicker -->
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<!-- 分页 -->
<script src="/Public/Admin/Admin/js/plugins/kkpager/kkpager.js"></script>
<script>
	var binddefaultinfo={
		postRootUrl:"",
		// postWebRootUrl:"<?php echo C(SHOPWEBROOT);?>",
		// postWebRootUrl:"http://"+"<?php echo ($URL); ?>",
		postWebRootUrl:"<?php echo U('Home/Product/Goods');?>",
		postUrl:"<?php echo U('allOrder');?>",
		postExport:"<?php echo U('exportorder');?>",
		postNewXls:"<?php echo U('exportxls');?>",
		postinfoUrl:"<?php echo U('getOrderInfoByno');?>",
		pageCount:"<?php echo ($pageCount); ?>",	// 数据条数
		totalPage:"<?php echo ($totalPage); ?>",	// 总页数
		pno:1, // 当前显示的页码
		type:"<?php echo ($selecttype); ?>",
		state:"<?php echo ($state); ?>",
		order_no:""
	};
</script>
<script src="/Public/Admin/Admin/common/js/allorder.js?v2.2"></script>
<script>SetPager();</script>
<script type="text/javascript">
	function prints(oid){
		art.dialog.open('<?php echo U('ArtDialog/prinf');?>?oid='+oid,{width:800});
	}
	function printlog(oid){
		art.dialog.open('<?php echo U('ArtDialog/prinflog');?>?oid='+oid,{width:800});
	}
	function checks(oid){
		art.dialog.open('<?php echo U('ArtDialog/checks');?>?oid='+oid,{width:600});
	}
	$(document).ready(function(){
		$("#pfahuo").click(function(){
			var poids=document.getElementsByName('poid');
			check_val = [];
			var status=false;
				for(k in poids){
					if(poids[k].checked)
						check_val.push(poids[k].value);
						status=true;
				}
				if (check_val==false) {
					art.dialog.alert('请选择订单');
					return false;
				};
			art.dialog.data('poids',check_val);
			art.dialog.open('<?php echo U('ArtDialog/prinfs');?>?oids='+check_val,{width:800});
			})
			$('#inport').click(function(){
				art.dialog.open('<?php echo U('ArtDialog/inportXls');?>',{width:600});
			})
			$('#sendX').click(function(){
				var stime=$('#start_time').val();
				var etime=$('#end_time').val();
				if (stime && etime) {
					art.dialog.open('<?php echo U('Order/sendX');?>?stime='+stime+'&etime='+etime,{width:1000,height:800});
				}else{
					art.dialog.alert('请选择下单时间');
				}
			})
	})
</script>
</body>
</html>