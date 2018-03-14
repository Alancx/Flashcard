<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>echar</title>

    <link href="/Public/newadmin/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/Public/newadmin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">

    <link href="/Public/newadmin/css/animate.min.css" rel="stylesheet">
    <link href="/Public/newadmin/css/style.min.css?v=4.0.0" rel="stylesheet">
    <link rel="stylesheet" href="/Public/Admin/Admin/css/fan.css">

</head>

<body class="gray-bg">

		<ul class="fantab">
			<li id="tab1">会员信息</li>
			<li id="tab2">新增购买会员 </li>
			<li id="tab3">会员增长趋势</li>
			<div class="curbg"></div>
		</ul>

		<div class="li1">
			<div class="fanhear">
				<p>最近消费：<span data-p='all'>不限</span><span data-p='aweek'>1周内</span><span data-p='tweek'>2周内</span><small class="zdy"></small> &emsp;<small style='font-size:0.4em;'>仅查询有购买记录的用户, 订单状态为已付款、已发货、已收货、退款中、已完成的订单</small></p>
				<button class="btn btn-xs btn-primary" id="sminfo">筛选</button>
			</div>
			<div class="jieguo">
				<p>筛选结果（<span class="number"><?php echo ($count); ?></span>）</p>
				<br />
				<ul class="header">
					<li>#</li>
					<li>会员</li>
					<li>手机号</li>
					<li>积分</li>
					<li>注册时间</li>
					<li>最后购买</li>
					<li>购买次数</li>
					<li>购买量</li>
					<li>均价</li>
					<!-- <li>操作</li> -->
				</ul>
				<?php if(is_array($lists)): foreach($lists as $key=>$li): ?><hr class="hr" />
				<ul class="conli header">
					<li><img src="<?php echo ($li["HeadImgUrl"]); ?>" style="width:60px;height:60px;border:none;"  alt=""></li>
					<li><?php echo ($li["MemberName"]); ?></li>
					<li><?php echo ($li["Phone"]); ?></li>
					<li><?php echo ($li["Integral"]); ?></li>
					<li> <?php echo ($li["RegisterDate"]); ?> </li> 
					<li> <?php echo ($li["LastBuyTime"]); ?></li>
					<li><?php echo ($li["OrderCount"]); ?></li>
					<li><?php echo ($li["count"]); ?></li>
					<li><?php echo ($li["avgs"]); ?></li>
					<!-- <li><button class="btn btn-xs btn-default btn-outline">操作</button></li> -->

				</ul><?php endforeach; endif; ?>
				<hr class="hr" />
				<div><?php echo ($page); ?></div>
			</div>
		</div>

		<div class="li2">
			<div class="fanhear">
			<a href="<?php echo U('Users/newUserBuy');?>" class="btn btn-xs btn-default">导出</a>
			</div>
			<div class="jieguo">
				<ul class="header">
					<li>#</li>
					<li>会员</li>
					<li>手机号</li>
					<li>积分</li>
					<li>注册时间</li>
					<li>最后购买</li>
				</ul>
				<div class="content2">
				</div>
			</div>
		</div>

		<div class="li3">

			<div class="fanhear">
				
			</div>
			<div class="jieguo">

				<hr class="hr" />
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-sm-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>折线图</h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
										<a class="dropdown-toggle" data-toggle="dropdown" href="graph_flot.html#">
											<i class="fa fa-wrench"></i>
										</a>
										<ul class="dropdown-menu dropdown-user">
											<li><a href="graph_flot.html#">选项1</a>
											</li>
											<li><a href="graph_flot.html#">选项2</a>
											</li>
										</ul>
										<a class="close-link">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content">
									<div class="echarts" id="echarts-line-chart"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr class="hr" />
			</div>
		</div>
    
    <script src="/Public/newadmin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/newadmin/js/bootstrap.min.js?v=3.3.5"></script>
    <script src="/Public/newadmin/js/plugins/echarts/echarts-all.js"></script>
    <script src="/Public/newadmin/js/content.min.js?v=1.0.0"></script>
    <script src="/Public/Admin/Admin/js/fan.js" type="text/javascript"></script>
</body>
<script type="text/javascript">
$(document).ready(function(){
	$('#sminfo').click(function(){
		var time=$('.boo').attr('data-p');
		window.location.href="<?php echo U('Users/dowhat');?>?time="+time;
	})
	$('#tab2').click(function(){
		$.ajax({
			url:"<?php echo U('Users/newUserBuy');?>",
			type:"post",
			data:"1=1",
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					var data=msg.data;
					var _html='';
					$.each(data,function(index,item){
						_html+='<hr class="hr" /> <ul class="conli header" > <li><img src="'+item.HeadImgUrl+'" style="width:60px;height:60px;border:none;"></li> <li>'+item.MemberName+'</li><li>'+item.Phone+'</li> <li>'+item.Integral+'</li> <li>'+item.RegisterDate+'</li> <li>'+item.LastBuyTime+'</li> </ul>';
					})
					$('.content2').html(_html);
				};
			}
		})
	})
	$('#tab3').click(function(){
		var count='';
		var date='';
		var allcount='';
		$.ajax({
			url:"<?php echo U('Users/getseven');?>",
			type:"post",
			data:"1=1",
			dataType:"json",
			success:function(msg){
				count=msg.count;
				date=msg.date;
				// allcount=msg.allcount;


				var e = echarts.init(document.getElementById("echarts-line-chart")),
					a = {
						title: {
							text: "一周新增会员趋势"
						},
						tooltip: {
							trigger: "axis"
						},
						legend: {
							data: ["新增会员", "会员数量"]
						},
						grid: {
							x: 40,
							x2: 40,
							y2: 24
						},
						calculable: !0,
						xAxis: [{
							type: "category",
							boundaryGap: !1,
							data: date
						}],
						yAxis: [{
							type: "value",
							axisLabel: {
								formatter: "{value} 人"
							}
						}],
						series: [{
							name: "新增会员",
							type: "line",
							data: count,
							markPoint: {
								data: [{
									type: "max",
									name: "最大值"
								}, {
									type: "min",
									name: "最小值"
								}]
							},
							markLine: {
								data: [{
									type: "average",
									name: "平均值"
								}]
							}
						}, 
						// {
						// 	name: "会员数量",
						// 	type: "line",
						// 	data: allcount,
						// 	markLine: {
						// 		data: [{
						// 			type: "average",
						// 			name: "平均值"
						// 		}]
						// 	}
						// }
						]
					};
				e.setOption(a), $(window).resize(e.resize);
			}
		})
		
	})
})
</script>
</html>