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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<style type="text/css">
	.ibox{
		margin-bottom: 0px!important;
	}
</style>
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


<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>数据统计</h2>
		<ol class="breadcrumb">
			<li>
				<a href="index.html">主页</a>
			</li>
			<li class="active">
				<strong>付款方式统计</strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-2"></div>
</div>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="row">
		<div class="col-lg-6" style="display:none;">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="flot-chart">
						<div class="flot-chart-content" id="flot-bar-chart"></div>
					</div>
				</div>
			</div>
		</div>
 		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo ($data["strtime"]); ?>-<?php echo ($data["endtime"]); ?> 支付金额 <span style="font-size:1.5em;">￥<?php echo ($allmoney); ?></span></h5>
					<div style="padding-bottom:15px;text-align:right;">
						最后统计时间： <?php echo ($data["endtime"]); ?>
					</div>
				</div>
				<div class="ibox-content">
					<div class="echarts" id="echarts-line-chart"></div>
				</div>
			</div>
		</div>
		</div>
	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="<?php echo U('Statcenter/PayType');?>" class="form-inline" method="post"  id="search">
<!-- 			<div class="form-group">
				<label for="exampleInputPassword2" class="sr-only">选择场景</label>
				<select name="sid" id="chosen" class="form-control" value="">
					<option value="">请选择场景</option>
					<?php if(is_array($allScene)): foreach($allScene as $key=>$scene): ?><option value="<?php echo ($scene["ID"]); ?>"><?php echo ($scene["SceneName"]); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
		-->			<div class="form-group">
		<select name="PayName" id="PayName" class="form-control">
			<option value="">请选择支付类型</option>
			<option value="XJ" style="color:green;font-size:1.1em;">现金支付</option>
			<option value="T" style="color:orange;font-size:1.1em;">微信支付</option>
			<option value="YE" style="color:green;font-size:1.1em;">余额支付</option>
			<option value="JL" style="color:orange;font-size:1.1em;">奖励余额支付</option>
		</select>
	</div>
	<div class="form-group">
		<input type="text" name="strtime" id="stime" class="form-control" placeholder="请选择开始时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($data["strtime"]); ?>">
	</div>
	<div class="form-group">
		<input type="text" name="endtime" id="etime" class="form-control" placeholder="请选择结束时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($data["endtime"]); ?>">
	</div>
	<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button> <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-export"></span> 导出</button>
</form>

</div>
<div class="col-sm-10 col-sm-offset-1">
	<?php if($errmsg): ?><h3><?php echo ($errmsg); ?></h3>
		<?php else: ?>
		<table class="table table-hover table-bordered">
			<thead>
				<tr class="success">
					<th colspan="5">支付方式：<?php if($data['PayName'] == 'XJ'): ?>现金支付<?php elseif($data['PayName'] == 'T'): ?>微信支付<?php elseif($data['PayName'] == 'YE'): ?>余额支付<?php elseif($data['PayName'] == 'JL'): ?>奖励余额支付<?php else: ?> 全部<?php endif; ?> <?php if($data['strtime']): ?>&emsp;&emsp;&emsp;查询时间：<?php echo ($data["strtime"]); ?>——<?php echo ($data['endtime']); endif; ?> </th>
				</tr>
				<?php if($data['PayName']): ?><tr>
						<th>订单号</th>
						<th>用户ID</th>
						<th>支付金额</th>
						<th>付款方式</th>
						<th>付款时间</th>
					</tr>
					<?php else: ?>
					<?php if(is_array($tempAry)): foreach($tempAry as $skey=>$ary): ?><tr>
							<td colspan="5">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h5 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseOne<?php echo ($skey); ?>"><?php if($ary['PayName'] == 'XJ'): ?>现金支付<?php elseif($ary['PayName'] == 'T'): ?>微信支付<?php elseif($ary['PayName'] == 'YE'): ?>余额支付<?php elseif($ary['PayName'] == 'JL'): ?>奖励余额支付<?php else: ?>未知类型<?php endif; ?> &emsp;&emsp;&emsp; 订单总数：<?php echo ($ary['count']); ?> &emsp;&emsp;&emsp;订单总额：<?php echo ($ary['price']); ?></a>
										</h5>
									</div>
									<div id="collapseOne<?php echo ($skey); ?>" class="panel-collapse collapse">
										<div class="panel-body">
											<table class="table table-hover table-bordered">
												<thead>
													<tr>
														<th>订单号</th>
														<th>用户ID</th>
														<th>支付金额</th>
														<th>付款方式</th>
														<th>付款时间</th>
													</tr>
												</thead>
												<tbody id="tbody<?php echo ($skey); ?>">
													<?php if(is_array($ary["sons"])): foreach($ary["sons"] as $key=>$son): ?><tr>
															<td><a href="<?php echo U('Order/allOrder');?>?oid=<?php echo ($son["OrderId"]); ?>"><?php echo ($son["OrderId"]); ?></a></td>
															<td><?php echo ($son["MemberId"]); ?></td>
															<td><?php echo ($son["Price"]); ?></td>
															<td><?php if($son['PayName'] == 'XJ'): ?>现金支付<?php elseif($son['PayName'] == 'T'): ?>微信支付<?php elseif($son['PayName'] == 'YE'): ?>余额支付<?php elseif($son['PayName'] == 'JL'): ?>奖励余额支付<?php else: ?>未知类型<?php endif; ?></td>
															<td><?php echo ($son["PayDate"]); ?></td>
														</tr><?php endforeach; endif; ?>
												</tbody>
											</table>
											<div style="text-align:center"><button class="btn btn-primary btn-outline addmore" data-key="<?php echo ($skey); ?>" data-stime="<?php echo ($data["strtime"]); ?>" data-etime="<?php echo ($data["endtime"]); ?>" data-payname="<?php echo ($ary["PayName"]); ?>" data-page='19' id="btn<?php echo ($skey); ?>">点击加载更多...</button></div>
										</div>
									</div>
								</div>
							</td>
						</tr><?php endforeach; endif; endif; ?>

			</thead>
			<?php if($data['PayName']): ?><tbody>
					<?php if(is_array($lists)): foreach($lists as $key=>$list): ?><tr>
							<td><a href="<?php echo U('Order/allOrder');?>?oid=<?php echo ($list["OrderId"]); ?>"><?php echo ($list["OrderId"]); ?></a></td>
							<td><?php echo ($list["MemberId"]); ?></td>
							<td><?php echo ($list["Price"]); ?></td>
							<?php if($list['PayName'] == 'XJ'): ?><td class="info">现金支付<?php elseif($list['PayName'] == 'T'): ?><td class="success">微信支付<?php elseif($list['PayName'] == 'YE'): ?><td class="warning">余额支付<?php elseif($list['PayName'] == 'JL'): ?><td class="danger">奖励余额支付<?php else: ?> <td class="active">其他<?php endif; ?></td>
							<td><?php echo ($list["PayDate"]); ?></td>
						</tr><?php endforeach; endif; ?>
				</tbody>
				<thead>
					<tr class="info">
						<th colspan="5">总订单数：<?php echo ($count); ?> &emsp;&emsp;总订单额：<?php echo ($allmoney); ?></th>
					</tr>
				</thead><?php endif; ?>
		</table>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div><?php endif; ?>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#search").submit(function(){
			var PayName=$('#PayName').val();
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (PayName || stime || etime) {
				if (stime || etime) {
					if (stime && etime) {
						if (stime>etime) {
							art.dialog.alert('非法时间段');
							return false;
						}else{
							art.dialog.tips('正在查询...',100);
							return true;
						}
					}else{
						art.dialog.alert('请选择完整时间');
						return false;
					}
				}else{
					art.dialog.tips('查询中...',100);
					return true;
				}
			}else{
				art.dialog.alert('请选择查询条件');
				return false;
			}
		})
		$('#import').click(function(){
			var PayName=$('#PayName').val();
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (PayName || stime || etime) {
				if (stime || etime) {
					if (stime && etime) {
						if (stime>etime) {
							art.dialog.alert('非法时间段');
							return false;
						}else{
							art.dialog.tips('正在查询...',3);
							window.location.href="<?php echo U('Statcenter/PayOut');?>?PayName="+PayName+"&stime="+stime+"&etime="+etime;
						}
					}else{
						art.dialog.alert('请选择完整时间');
						return false;
					}
				}else{
					art.dialog.tips('查询中...',3);
					window.location.href="<?php echo U('Statcenter/PayOut');?>?PayName="+PayName+"&stime="+stime+"&etime="+etime;
				}
			}else{
				art.dialog.alert('请选择查询条件');
				return false;
			}
		})

		$('.addmore').click(function(){
			art.dialog.tips('正在查询...',2);
			var stime=$(this).attr('data-stime');
			var etime=$(this).attr('data-etime');
			var PayName=$(this).attr('data-payname');
			var page=$(this).attr('data-page');
			var key=$(this).attr('data-key');
			console.log(stime,etime,PayName,page,key);
			$.ajax({
				url:"<?php echo U('Statcenter/getMore');?>",
				type:"post",
				data:"stime="+stime+"&etime="+etime+"&PayName="+PayName+"&page="+page+"&key="+key,
				dataType:"json",
				success:function(msg){
					if (msg.statu=='success') {
						var html='';
						$.each(msg.data,function(index,item){
							if (item.PayName=='XJ') {
								var PayName='现金支付';
							}else if (item.PayName=='T') {
								var PayName='微信支付';
							}else if (item.PayName=='T') {
								var PayName='余额支付';
							}else if (item.PayName=='T') {
								var PayName='奖励余额支付';
							}
							html+="<tr><td><a href=\"<?php echo U('Order/allOrder');?>?oid="+item.OrderId+"\">"+item.OrderId+"</a></td><td>"+item.MemberId+"</td><td>"+item.Price+"</td><td>"+PayName+"</td><td>"+item.PayDate+"</td></tr>";
						})
						$(html).appendTo($('#tbody'+key));
						$('#btn'+key).attr('data-page',parseInt(page)+20);
					};
					if (msg.statu=='error') {
						if (msg.info=='nomore') {
							art.dialog.tips('没有更多了');
							$('#btn'+key).html('没有更多了...').addClass('disabled');
						};
					};
				}
			})
		})
	})

	$(function () {
		var Money=<?php echo ($moneys); ?>;
		var Mon=<?php echo ($types); ?>;
		var counts=<?php echo ($counts); ?>;
		var lineChart = echarts.init(document.getElementById("echarts-line-chart"));
		var lineoption = {
			title : {
				text: '支付类型统计'
			},
			tooltip : {
				trigger: 'axis'
			},
	        legend: {
	            data:['金额','数量']
	        },
			calculable : true,
			xAxis : [
			{
				type : 'category',
				boundaryGap : true,
				data : Mon
			}
			],
			yAxis : [
			{
				type : 'value',
				axisLabel : {
					formatter: '{value}'
				}
			}
			],
			toolbox: {
				show : true,
        orient: 'horizontal',      // 布局方式，默认为水平布局，可选为：
                                   // 'horizontal' ¦ 'vertical'
        x: 'right',                // 水平安放位置，默认为全图右对齐，可选为：
                                   // 'center' ¦ 'left' ¦ 'right'
                                   // ¦ {number}（x坐标，单位px）
        y: 'top',                  // 垂直安放位置，默认为全图顶端，可选为：
                                   // 'top' ¦ 'bottom' ¦ 'center'
                                   // ¦ {number}（y坐标，单位px）
                                   color : ['#1e90ff','#22bb22','#4b0082','#d2691e'],
        backgroundColor: 'rgba(0,0,0,0)', // 工具箱背景颜色
        borderColor: '#ccc',       // 工具箱边框颜色
        borderWidth: 0,            // 工具箱边框线宽，单位px，默认为0（无边框）
        padding: 5,                // 工具箱内边距，单位px，默认各方向内边距为5，
        showTitle: true,
        feature : {
        	mark : {
        		show : true,
        		title : {
        			mark : '辅助线-开关',
        			markUndo : '辅助线-删除',
        			markClear : '辅助线-清空'
        		},
        		lineStyle : {
        			width : 1,
        			color : '#1e90ff',
        			type : 'dashed'
        		}
        	},
        	magicType: {
        		show : true,
        		title : {
        			line : '动态类型切换-折线图',
        			bar : '动态类型切换-柱形图',
        		},
        		type : ['bar', 'line']
        	},
        	restore : {
        		show : true,
        		title : '还原',
        		color : 'black'
        	},
        	saveAsImage : {
        		show : true,
        		title : '保存为图片',
        		type : 'jpeg',
        		lang : ['点击本地保存']
        	},
        	myTool : {
        		show : true,
        		title : '自定义扩展方法',
        		icon : 'image://../asset/ico/favicon.png',
        		onclick : function (){
        			alert('myToolHandler')
        		}
        	}
        }
    },
    series : [
    {
    	name:'金额',
    	type:'bar',
    	data:Money,
    	markLine : {
    		data : [
    		{type : 'average', name : '平均值'}
    		]
    	}
    },
    {
    	name:'数量',
    	type:'bar',
    	data:counts,
    	markLine : {
    		data : [
    		{type : 'average', name : '平均值'}
    		]
    	}
    }
    ]
};
lineChart.setOption(lineoption);

});


</script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>

<script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>