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
					<h5><?php echo ($data["stime"]); ?>-<?php echo ($data["etime"]); ?> 支付金额 <span style="font-size:1.5em;">￥<?php echo ($allmoneys); ?></span></h5>
					<div style="padding-bottom:15px;text-align:right;">
						最后统计时间： <?php echo ($data["etime"]); ?>
					</div>
				</div>
				<div class="ibox-content">
					<div class="echarts" id="echarts-line-chart"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="<?php echo U('Statcenter/posemp');?>" class="form-inline" method="post"  id="search">
			<div class="form-group">
				<input type="text" name="stime" id="stime" value="<?php echo ($data["stime"]); ?>" class="form-control" placeholder="请选择开始时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
			</div>
			<div class="form-group">
				<input type="text" name="etime" id="etime" value="<?php echo ($data["etime"]); ?>" class="form-control" placeholder="请选择结束时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
			</div>
			<button class="btn btn-primary btn-outline btn-md" type="submit"><span class="glyphicon glyphicon-search"></span> 搜 索</button> <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-export"></span> 导出</button> &emsp; <small>默认导出当月数据</small>
		</form>

	</div>
	<div class="col-sm-10 col-sm-offset-1">
		<?php if($errmsg): ?><h3><?php echo ($errmsg); ?></h3>
			<?php else: ?>
			<table class="table table-hover table-bordered">
				<thead>
					<tr class="success">
						<th colspan="5">查询时间： <?php echo ($data["stime"]); ?>——<?php echo ($data["etime"]); ?> </th>
					</tr>
					<?php if(is_array($lists)): foreach($lists as $key=>$list): ?><tr>
							<td colspan="5">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h5 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseOne<?php echo ($list["userName"]); ?>">收银员账号：<?php echo ($list["userName"]); ?> &emsp; 订单数：<?php echo ($list["count"]); ?> &emsp; 订单额：<?php echo ($list["money"]); ?></a>
										</h5>
									</div>
									<div id="collapseOne<?php echo ($list["userName"]); ?>" class="panel-collapse collapse">
										<div class="panel-body">
											<table class="table table-hover table-bordered">
												<thead>
													<tr>
														<th>订单号</th>
														<th>用户ID</th>
														<th>支付金额</th>
														<th>数量</th>
														<th>付款时间</th>
													</tr>
												</thead>
												<tbody id="tbody<?php echo ($list["userName"]); ?>">
													<?php if(is_array($list["oinfos"])): foreach($list["oinfos"] as $key=>$son): ?><tr>
															<td><a href="<?php echo U('Order/allOrder');?>?oid=<?php echo ($son["OrderId"]); ?>"><?php echo ($son["OrderId"]); ?></a></td>
															<td><?php echo ($son["MemberId"]); ?></td>
															<td><?php echo ($son["Price"]); ?></td>
															<td><?php echo ($son["Count"]); ?></td>
															<td><?php echo ($son["PayDate"]); ?></td>
														</tr><?php endforeach; endif; ?>
												</tbody>
											</table>
											<div style="text-align:center"><button class="btn btn-primary btn-outline addmore" data-key="<?php echo ($list["userName"]); ?>" data-stime="<?php echo ($data["stime"]); ?>" data-etime="<?php echo ($data["etime"]); ?>" data-payname="<?php echo ($ary["PayName"]); ?>" data-page='19' id="btn<?php echo ($list["userName"]); ?>">点击加载更多...</button></div>
										</div>
									</div>
								</div>
							</td>
						</tr><?php endforeach; endif; ?>
				</thead>
<!-- 				<thead>
					<tr class="info">
						<th colspan="5">订单总数：<?php echo ($count); ?> </th>
					</tr>
				</thead>
 -->			</table><?php endif; ?>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#search").submit(function(){
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
						art.dialog.tips('查询中...',100);
						return true;
					}else{
						art.dialog.alert('非法时间段');
						return false;
					}
				}else{
					art.dialog.alert('请选择完整的时间段');
					return false;
				}
			}else{
				art.dialog.tips('查询中...',100);
				return true;
			}
		})
		$('#import').click(function(){
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (stime || etime) {
				if (stime && etime) {
					if (stime<etime) {
							art.dialog.tips('数据处理中...',3);
							window.location.href="<?php echo U('Statcenter/posempOut');?>?stime="+stime+"&etime="+etime;
					}else{
						art.dialog.alert('非法时间段');
						return false;
					}
				}else{
					art.dialog.alert('请选择完整的时间段');
					return false;
				}
			}else{
					art.dialog.tips('数据处理中...',3);
					window.location.href="<?php echo U('Statcenter/posempOut');?>?stime="+stime+"&etime="+etime;
			}
		})

		$('.addmore').click(function(){
			art.dialog.tips('正在查询...',2);
			var stime=$(this).attr('data-stime');
			var etime=$(this).attr('data-etime');
			var page=$(this).attr('data-page');
			var key=$(this).attr('data-key');
			console.log(stime,etime,page,key);
			$.ajax({
				url:"<?php echo U('Statcenter/getMorepos');?>",
				type:"post",
				data:"stime="+stime+"&etime="+etime+"&page="+page+"&key="+key,
				dataType:"json",
				success:function(msg){
					if (msg.statu=='success') {
						var html='';
						$.each(msg.data,function(index,item){
							html+="<tr><td><a href=\"<?php echo U('Order/allOrder');?>?oid="+item.OrderId+"\">"+item.OrderId+"</a></td><td>"+item.MemberId+"</td><td>"+item.Price+"</td><td>"+item.Count+"</td><td>"+item.PayDate+"</td></tr>";
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
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>

<script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>