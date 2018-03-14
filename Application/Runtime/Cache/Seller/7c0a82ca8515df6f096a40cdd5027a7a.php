<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>光盘客</title>
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
	.edit_order_proname{
		display: inline-block;
		line-height: 20px;
		float: left;
		overflow: hidden;
	    text-overflow: ellipsis;
	    width: 150px;
	}
	#oinfo td{
		padding: 0px!important;
	}
</style>
<link href="/Public/Admin/Admin/common/css/order.style.css?v=1" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row order-all">
		<div class="col-lg-12">
			<div class="ibox-title">
				<h5>
					订单信息
					<small><?php echo ($param["start_time"]); ?>--<?php echo ($param["end_time"]); ?></small>
				</h5>
			</div>
			<div class='ibox-tools'>
				<button class='btn btn-xs btn-danger btn-outline export' data-s='<?php echo ($param["start_time"]); ?>' data-e='<?php echo ($param["end_time"]); ?>'>导出订单信息</button>
			</div>
			<div class="ibox-content" style="padding-top:0px;">
				<div class="panel blank-panel">
					<!-- <div style="clear:both;"></div> -->
					<div class="panel-body">
						<div class="tab-content">
						<!--  -->
							<div id="tablist" class="tab-pane active">
								<table class="table table-bordered table-hover" id="allorder" data-toggle="table">
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
										<?php if(is_array($allOrder)): foreach($allOrder as $k=>$vo): ?><tr>
												<td colspan="7"></td>
											</tr>
											<tr>
												<td colspan="7">
													<span style="color:#000">订单号：<?php echo ($vo["OrderId"]); ?></span>
													<span class="c-gray">
													<?php if($vo['PayName'] == 'XJ'): ?>现金付款
														<?php elseif($vo['PayName'] == 'T'): ?>微信支付
														<?php elseif($vo['PayName'] == 'YE'): ?>余额支付
														<?php elseif($vo['PayName'] == 'JL'): ?>奖励余额支付
														<?php elseif($vo['PayName'] == 'POSXJ'): ?>POS端现金支付
														<?php elseif($vo['PayName'] == 'ALIPAY'): ?>支付宝付款<?php endif; ?>
													</span>
													<span class="pull-right">
														
													</span>
												</td>
											</tr>
											<?php if(is_array($vo['sons'])): foreach($vo['sons'] as $i=>$son): ?><tr>
													<td class="image-cell" >
														<img src="<?php echo ($PICURL); echo ($son["ProLogoImg"]); ?>" alt='' style="width:60px;height:60px;"/>
													</td>
													<td class="product-cell">
														<p>
															<a href="<?php echo U('Home/Product/Goods');?>?pid=<?php echo ($son["ProId"]); ?>" target="_blank" title="<?php echo ($son["ProName"]); ?>"><?php echo ($son["ProName"]); ?></a>
														</p>
														<p><?php echo ($son["Spec"]); ?></p>
													</td>
													<td>
														<p class='<?php echo ($vo["OrderId"]); ?>_<?php echo ($son["ProIdCard"]); ?>_price'><?php echo (sprintf('%.2f',$son["Price"])); ?></p>
														<p class='<?php echo ($vo["OrderId"]); ?>_<?php echo ($son["ProIdCard"]); ?>_count'>(<?php echo ($son["Count"]); ?> 件)</p>
													</td>
													<?php if($i == 0): ?><td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<?php if($vo['RecevingPost'] == 'ZT'): ?><p>店铺自提货<?php if($vo['SceneContent']): ?>(<?php echo ($vo["SceneContent"]); ?>)<?php endif; ?></p>
															<?php else: ?>
															<p><?php echo ($vo["RecevingName"]); ?></p>
															<p><?php echo ($vo["RecevingPhone"]); ?></p><?php endif; ?>

														</td>
														<td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<?php echo ($vo["CreateDate"]); ?>
														</td>
														<td <?php if(($vo['sonCount'] != 1) and ($i == 0)): ?>rowspan="<?php echo ($vo["sonCount"]); ?>"<?php endif; ?>>
															<?php if($vo['Status'] == 1): ?><p>等待买家付款</p>
															<?php elseif($vo['Status'] == 2): ?>

															<?php if($vo['RecevingPost'] == 'ZT'): ?><p>店铺自提货</p>
															<?php else: ?>

																<p>已付款，等待发货</p><?php endif; ?>





															<?php elseif($vo['Status'] == 3): ?>
																<p>卖家已发货</p>
															<?php elseif(($vo['Status'] == 4) or ($vo['Status'] == 10)): ?>
																<p>已收货，交易完成</p>
															<?php elseif($vo['Status'] == 5): ?>
																<p>退款中</p>
															<?php elseif($vo['Status'] == 6): ?>
																<p>买家已退货</p>
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
															<p class='all_<?php echo ($vo["OrderId"]); ?>'><?php echo (sprintf('%.2f',$vo["orderMoney"])); ?></p>
															<p><small class="c-gray ">(含运费：<span class='cf_<?php echo ($vo["OrderId"]); ?>'><?php echo ($vo["Freight"]); ?></span>)</small></p>
														</td><?php endif; ?>
												</tr><?php endforeach; endif; endforeach; endif; ?>
									</tbody>
								</table>
								<div><?php echo ($page); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 订单添加备注 end -->
<!--底部版权-->

<!--js引用-->
<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.export').click(function(){
			var start_time=$(this).attr('data-s');
			var end_time=$(this).attr('data-e');
			window.location.href="<?php echo U('Order/exportshowindex');?>?start_time="+start_time+"&end_time="+end_time;
		})
	})
</script>
</body>
</html>