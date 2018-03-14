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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/Public/newadmin/css/plugins/dataTables/dataTables.bootstrap.css">
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="echarts" id="echarts-line-chart"></div>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin:0px;">
	<div class="col-sm-6 col-md-6 col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><span class="glyphicon glyphicon-signal"></span></h5>
			</div>
			<div class="ibox-content">
				<div class="echarts" id="echarts-pie-chart-area"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><span class="glyphicon glyphicon-signal"></span></h5>
			</div>
			<div class="ibox-content">
				<div class="echarts" id="echarts-pie-chart-province"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><span class="glyphicon glyphicon-signal"></span></h5>
			</div>
			<div class="ibox-content">
				<div class="echarts" id="echarts-pie-chart-city"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><span class="glyphicon glyphicon-signal"></span></h5>
			</div>
			<div class="ibox-content">
				<div class="echarts" id="echarts-pie-chart-store"></div>
			</div>
		</div>
	</div>
</div>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="ibox-content">
		<div class="col-md-12 col-lg-12">
			<form class="form-inline" method="post" id="searchofdata">
				<div class="form-group">
					<label class="sr-only"></label>
					<div class="input-group">
						<div class="input-group-addon">起</div>
						<input type="text" class="form-control" name="strtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd 00:00:00'})" id="strtime" placeholder="开始时间" value="<?php echo ($param["strtime"]); ?>">
					</div>
					<div class="input-group">
						<div class="input-group-addon">止</div>
						<input type="text" class="form-control" name="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd 00:00:00'})" id="endtime" placeholder="结束时间" value="<?php echo ($param["endtime"]); ?>">
					</div>
					<div class="input-group">
						<div class="input-group-addon">区域</div>
						<select name="AreaId" id="s_area" class="form-control">
							<option value="">请选择区域</option>
							<?php if(is_array($Area)): foreach($Area as $key=>$ar): ?><option value="<?php echo ($ar["ID"]); ?>" <?php if($ar == $param['AreaId']): ?>selected="selected"<?php endif; ?>><?php echo ($ar["AreaName"]); ?></option><?php endforeach; endif; ?>
						</select>
					</div>
					<div class="input-group">
						<div class="input-group-addon">省份</div>
						<select name="province" id="s_province" class="form-control">
							<option value="">请选择区域</option>
						</select>
					</div>
				</div>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&emsp;search</button>
			</form>
		</div>
	</div>
	<div class="col-sm-12 col-md-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<?php if(is_array($area_data)): foreach($area_data as $key=>$area): ?><div class="panel-body" style="padding:0px;">
						<div class="panel-group" id="panel<?php echo ($area["SSid"]); ?>" style="margin-bottom:0px;">
							<div class="panel panel-default" style="border-radius:0px;border:1px solid #ccc;">
								<div class="panel-heading">
									<h5 class="panel-title">
										<a data-toggle="collapse" data-parent="#panel<?php echo ($area["SSid"]); ?>" href="tabs_panels.html#collapse<?php echo ($area["SSid"]); ?>"><button class="btn btn-xs btn-defualt getareadata" data-area='<?php echo ($area["ID"]); ?>' data-st='0'>+</button></a>
										区域：<?php echo ($area["AreaName"]); ?> &emsp;销售额：<?php echo ($area["allmoney"]); ?> &emsp; 销售量：<?php echo ($area["pcount"]); ?> &emsp;店铺数量：<?php echo ($area["scount"]); ?>
									</h5>
								</div>
								<div id="collapse<?php echo ($area["SSid"]); ?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php if(is_array($area["area_province"])): foreach($area["area_province"] as $key=>$province): ?><div class="panel-body" style="padding:0px;">
												<div class="panel-group" id="panel<?php echo ($province["SSid"]); ?>" style="margin-bottom:0px;">
													<div class="panel panel-default" style="border-radius:0px;border:1px solid #ccc;">
														<div class="panel-heading">
															<h5 class="panel-title">
																<a data-toggle="collapse" data-parent="#panel<?php echo ($province["SSid"]); ?>" href="tabs_panels.html#collapse<?php echo ($province["SSid"]); ?>"><button class="btn btn-xs btn-defualt getprovincedata" data-area="<?php echo ($area["ID"]); ?>" data-province='<?php echo ($province["Area"]); ?>' data-st='0'>+</button></a>
																省份：<?php echo ($province["Area"]); ?> &emsp;销售额：<?php echo ($province["allmoney"]); ?> &emsp; 销售量：<?php echo ($province["pcount"]); ?> &emsp; 店铺数量：<?php echo ($province["scount"]); ?>
															</h5>
														</div>
														<div id="collapse<?php echo ($province["SSid"]); ?>" class="panel-collapse collapse">
															<div class="panel-body">
																<?php if(is_array($province["province_city"])): foreach($province["province_city"] as $city_key=>$city): ?><div class="panel-group" id="panel<?php echo ($city["SSid"]); ?>" style="margin-bottom:0px;">
																		<div class="panel panel-default" style="border-radius:0px;border:1px solid #ccc;">
																			<div class="panel-heading">
																				<h5 class="panel-title">
																					<a data-toggle="collapse" data-parent="#panel<?php echo ($city["SSid"]); ?>" href="tabs_panels.html#collapse<?php echo ($city["SSid"]); ?>"><button class="btn btn-xs btn-defualt getcitydata" data-city='<?php echo ($city["city"]); ?>' data-area="<?php echo ($area["ID"]); ?>" data-province="<?php echo ($province["Area"]); ?>" data-st='0'>+</button></a>
																					城市：<?php echo ($city["city"]); ?> &emsp;销售额：<?php echo ($city["allmoney"]); ?>&emsp;销售量：<?php echo ($city["pcount"]); ?> &emsp; 店铺数量：<?php echo ($city["scount"]); ?>
																				</h5>
																			</div>
																			<div id="collapse<?php echo ($city["SSid"]); ?>" class="panel-collapse collapse">
																				<div class="panel-body">
																				   <!-- = = 突然不识别了不知道咋回事 -->
																				   <?php foreach ($city['city_store'] as $sto): ?>
																						<div class="panel-body" style="padding:0px;">
																							<div class="panel-group" id="panel<?php echo ($sto["SSid"]); ?>" style="margin-bottom:0px;">
																								<div class="panel panel-default" style="border-radius:0px;border:1px solid #ccc;">
																									<div class="panel-heading">
																										<h5 class="panel-title">
																											<a data-toggle="collapse" data-parent="#panel<?php echo ($sto["SSid"]); ?>" href="tabs_panels.html#collapse<?php echo ($sto["SSid"]); ?>"><button class="btn btn-xs btn-danger showdetail_order" data-stoken='<?php echo ($sto["stoken"]); ?>' data-st='0'>+</button></a>
																											【店铺名称：<?php echo ($sto["storename"]); ?>】 &emsp; 【销售量：<?php echo ($sto["pcount"]); ?>】&emsp; 【销售额：<?php echo ($sto["allmoney"]); ?>】&emsp;店铺地址：<?php echo ($sto["addr"]); ?>  &emsp; 【管理人员：<?php echo ($sto["empinfo"]); ?>】
																										</h5>
																									</div>
																									<div id="collapse<?php echo ($sto["SSid"]); ?>" class="panel-collapse collapse">
																										<div class="panel-body table_content" id="<?php echo ($sto["stoken"]); ?>">
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																				   <?php endforeach ?>
																				</div>
																			</div>
																		</div>
																	</div><?php endforeach; endif; ?>
															</div>
														</div>
													</div>
												</div>
											</div><?php endforeach; endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div><?php endforeach; endif; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var Money=<?php echo ($area_value); ?>;
	var Mon=<?php echo ($area_name); ?>;
	var newdata=<?php echo ($area_pie); ?>;
	var alldata=<?php echo ($alldata); ?>;
	var areainfo=<?php echo ($Areainfo); ?>;
	$(document).ready(function(){
		createCharts(Money,Mon);
		echar_pie('area',Mon,newdata,'区域销售');
		echar_pie('city','0','0','省份');
		echar_pie('store','0','0','城市');
		echar_pie('province','0','0','区域')
	// echar_pie('city');
	// echar_pie('store');
	//区域选择变动
	$('#s_area').change(function(){
		var _html='<option value="">请选择省份</option>';
		var aid=$(this).val();
		$.each(areainfo,function(index,item){
			if (item.AreaId==aid) {
				_html+='<option value="'+item.Area+'">'+item.Area+'</option>';
			};
		})
		$('#s_province').html(_html);
	})
	$('.showdetail_order').click(function(){
		var _this=$(this);
		var stoken=_this.attr('data-stoken');
		var st=_this.attr('data-st');
		if (st=='0') {
			$('#'+stoken).html('');//清空原有的表格数据
			art.dialog.tips('数据处理中...',1000);
			$.ajax({
				url:"<?php echo U('Statcenter/alldetailofdata');?>",
				type:"post",
				data:"type=showdetail_order&stoken="+stoken+"&strtime="+$('#strtime').val()+"&endtime="+$('#endtime').val(),
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var data=msg.data;
						var _html='<div class="row"> <div class="col-sm-12"> <div class="ibox float-e-margins">  <div class="ibox-content"> <table class="table table-striped table-bordered table-hover dataTables-example"> <thead> <tr> <th>订单号</th> <th>商品名称</th> <th>属性</th> <th>售价</th> <th>数量</th><th>当前库存</th> </tr> </thead><tbody>';
						$.each(data,function(index,item){
							_html+='<tr class="'+item.OrderId+'"> <td>'+item.OrderId+'</td> <td>'+item.ProName+'</td> <td>'+item.Spec+'</td> <td class="center">'+item.Price+'</td> <td class="center">'+item.Count+'</td><td>'+item.StockCount+'</td></tr>';
						})
						_html+'</tbody> <tfoot> <tr> <th>订单号</th><th>商品名称</th> <th>属性</th> <th>售价</th> <th>数量</th><th>当前库存</th> </tr> </tfoot> </table> </div> </div> </div> </div>';
						$('#'+stoken).html(_html);
						$(".dataTables-example").dataTable();
						art.dialog.tips('加载完成');
					}else{
						art.dialog.tips(msg.info);
					}
				}
			})
_this.attr('data-st',1);
}else{
	_this.attr('data-st',0);
}
})
$('.getareadata').click(function(){
	var _this=$(this);
	var st=_this.attr('data-st');
	var area=_this.attr('data-area');
	if (st=='0') {
		$.each(alldata,function(aindex,aitem){
			if (area==aitem.ID) {
				createCharts(aitem.province_value,aitem.province_name);
				echar_pie('province',aitem.province_name,aitem.province_pie,aitem.AreaName);
			};
		})
		_this.attr('data-st','1');
	}else{
		createCharts(Money,Mon);
		echar_pie('province','0','0');
		_this.attr('data-st','0');
	}
})
$('.getprovincedata').click(function(){
	var _this=$(this);
	var st=_this.attr('data-st');
	var province=_this.attr('data-province');
	var area=_this.attr('data-area');
	console.log(province,area);
	if (st=='0') {
		$.each(alldata,function(aindex,aitem){
			if (aitem.ID==area) {
				$.each(aitem.area_province,function(pindex,pitem){
					if (pitem.Area==province) {
						createCharts(pitem.city_value,pitem.city_name);
						echar_pie('city',pitem.city_name,pitem.city_pie,province);
					};
				})
			};
		})
		_this.attr('data-st','1');
	}else{
		$.each(alldata,function(aindex,aitem){
			if (aitem.ID==area) {
				createCharts(aitem.province_value,aitem.province_name);
				echar_pie('city','0','0');
			};
		})
		_this.attr('data-st','0');
	}

})
$('.getcitydata').click(function(){
	var _this=$(this);
	var st=_this.attr('data-st');
	var city=_this.attr('data-city');
	var province=_this.attr('data-province');
	var area=_this.attr('data-area');
	if (st=='0') {
		$.each(alldata,function(aindex,aitem){
			if (aitem.ID==area) {
				$.each(aitem.area_province,function(pindex,pitem){
					if (pitem.Area==province) {
						$.each(pitem.province_city,function(cindex,citem){
							if (citem.city==city) {
								createCharts(citem.store_value,citem.store_name);
								echar_pie('store',citem.store_name,citem.store_pie,city);
							};
						})
					};
				})
			};
		})
		_this.attr('data-st','1');
	}else{
		$.each(alldata,function(aindex,aitem){
			if (aitem.ID==area) {
				$.each(aitem.area_province,function(pindex,pitem){
					if (pitem.Area==province) {
						createCharts(pitem.city_value,pitem.city_name);
						echar_pie('store','0','0');
					};
				})
			};
		})
		_this.attr('data-st','0');
	}
})

$('#searchofdata').submit(function(){
	var strtime=$('#strtime').val();
	var endtime=$('#endtime').val();
	var province=$('#s_province').val();
	if (strtime || endtime || province!='-1') {
		if (strtime || endtime) {
			if (strtime && endtime) {
				if (strtime<endtime) {
					art.dialog.tips('查询中...');
					return true;
				}else{
					art.dialog.tips('非法时间段');
					return false;
				}
			}else{
				art.dialog.tips('请选择完整的时间段');
				return false;
			}
		}else{
			art.dialog.tips('查询中...');
			return true;
		}
	}else{
		art.dialog.tips('请选择查询条件');
		return false;
	}
})
})
function createCharts(Money,Mon){var lineChart=echarts.init(document.getElementById("echarts-line-chart"));var lineoption={title:{text:'-'},tooltip:{trigger:'axis'},calculable:true,xAxis:[{type:'category',boundaryGap:false,data:Mon}],yAxis:[{type:'value',axisLabel:{formatter:'{value}'}}],toolbox:{show:true,orient:'horizontal',x:'right',y:'top',color:['#1e90ff','#22bb22','#4b0082','#d2691e'],backgroundColor:'rgba(0,0,0,0)',borderColor:'#ccc',borderWidth:0,padding:5,showTitle:true,feature:{mark:{show:true,title:{mark:'辅助线-开关',markUndo:'辅助线-删除',markClear:'辅助线-清空'},lineStyle:{width:1,color:'#1e90ff',type:'dashed'}},magicType:{show:true,title:{line:'动态类型切换-折线图',bar:'动态类型切换-柱形图',},type:['line','bar']},restore:{show:true,title:'还原',color:'black'},saveAsImage:{show:true,title:'保存为图片',type:'jpeg',lang:['点击本地保存']},}},series:[{name:'销售额',type:'line',data:Money,markLine:{data:[{type:'average',name:'平均值'}]}}]};lineChart.setOption(lineoption)}
function echar_pie(a, x, y, name) {
	var title = '';
	var subtitle = '';
	var x = x ? x : 0;
	var y = y ? y : 0;
	var name = name ? name : "-";
	if (a == 'province') {
		title = name;
		subtitle = '省份销售'
	} else if (a == 'city') {
		title = name;
		subtitle = '城市销售'
	} else if (a == 'store') {
		title = name;
		subtitle = '店铺销售'
	} else if (a == 'area') {
		title = name;
		subtitle = '区域销售';
	} else {
		title = '我也不知道这是啥';
		subtitle = '我也不知道这是啥'
	}
	var l = echarts.init(document.getElementById("echarts-pie-chart-" + a)),
	u = {
		title: {
			text: title,
			subtext: subtitle,
			x: "center"
		},
		tooltip: {
			trigger: "item",
			formatter: "{a} <br/>{b} : {c} ({d}%)"
		},
		toolbox: {
			show: !0,
			feature: {
				restore: {
					show: !0
				},
			}
		},
		legend: {
			orient: "vertical",
			x: "left",
			data: x
		},
		calculable: !0,
		series: [{
			name: "销售额",
			type: "pie",
			radius: "55%",
			center: ["50%", "60%"],
			data: y,
			itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                },
                normal:{ 
                  label:{ 
                    show: true, 
                    formatter: '{b} : {c} ({d}%)' 
                  }, 
                  labelLine :{show:true} 
                } 
        	}
		}]


	};
	l.setOption(u), $(window).resize(l.resize)
}
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>

<script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>
<script src="/Public/newadmin/js/plugins/jeditable/jquery.jeditable.js"></script>
<script src="/Public/newadmin/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/Public/newadmin/js/plugins/dataTables/dataTables.bootstrap.js"></script>