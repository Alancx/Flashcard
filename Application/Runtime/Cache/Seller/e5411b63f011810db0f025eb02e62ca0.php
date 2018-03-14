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

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">

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
					<h5>7天核销数据 <span style="font-size:1.5em;">￥<?php echo ($allmoney); ?></span></h5>
					<div style="padding-bottom:15px;text-align:right;">
						最后统计时间： <?php echo ($estime); ?>
					</div>
				</div>
				<div class="ibox-content">
					<div class="echarts" id="echarts-line-chart"></div>
				</div>
			</div>
		</div>
	</div>

<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;">
	<div class="ibox-title">
		<h5><?php echo ($title); ?></h5>
	</div>
	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="<?php echo U('Statcenter/searchcan');?>" class="form-inline" method="post" id="search">
			<div class="form-group">
				<input type="text" name="strtime" id="stime" class="form-control" placeholder="请选择起始时间(发货)" onfocus="WdatePicker({maxDate:'%y-%M-{%d-0}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($stime); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="endtime" id="etime" class="form-control" placeholder="请选择查询结束时间(发货)" onfocus="WdatePicker({maxDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo ($etime); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="username" placeholder="请填写核销员姓名(选填)" id="username" class="form-control">
			</div>
			<input type="hidden" name="CanType" id="CanType" value="<?php echo ($Ctype); ?>">
			<button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
			<button class="btn btn-default btn-outline btn-md" id="import" type="button"><span class="glyphicon glyphicon-export"></span> 导 出</button>
		</form>

	</div>
	<div class="col-sm-10 col-sm-offset-1">
		<?php if($errmsg): ?><h3><?php echo ($errmsg); ?></h3>
			<?php else: ?>
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>姓名</th>
						<th>所属门店</th>
						<th>核销订单数 <?php echo ($CanType); ?> </th>
						<th>核销金额 <?php echo ($CanType); ?> </th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($userlists)): foreach($userlists as $key=>$user): ?><tr>
							<td><?php echo ($user["id"]); ?></td>
							<td><?php echo ($user["username"]); ?></td>
							<td><?php echo ($user["storename"]); ?></td>
							<td><?php echo ($user["count"]); ?></td>
							<td><?php echo ($user["price"]); ?></td>
							<td><a href="###" onclick="show('<?php echo ($user["openid"]); ?>','<?php echo ($Ctype); ?>','<?php echo ($user["stoken"]); ?>')">查看详情</a> | <a href="###" onclick="imports('<?php echo ($user["openid"]); ?>','<?php echo ($Ctype); ?>')">导出数据</a></td>
						</tr><?php endforeach; endif; ?>
				</tbody>

			</table><?php endif; ?>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<script type="text/javascript">
	function show(id,ctype,stoken){
		art.dialog.open('<?php echo U('Store/show');?>?sid='+id+'&stoken='+stoken+'&CanType='+ctype+"&stime="+'<?php echo ($stime); ?>'+"&etime="+'<?php echo ($etime); ?>',{width:'600px'});
	}

	function del(id){
		art.dialog.confirm('确定要删除吗？请慎重操作', function () {
			window.location.href="<?php echo U('Admin/del');?>?id="+id;
			// alert('hahaha')
		}, function () {
			art.dialog.tips('取消操作');
		});
	}

	function imports(id,ctype){
		window.location.href='<?php echo U('Store/showOut');?>?sid='+id+'&CanType='+ctype+"&stime="+'<?php echo ($stime); ?>'+"&etime="+'<?php echo ($etime); ?>';
	}

	$(document).ready(function(){
		$("#search").submit(function(){
			var username=$("#username").val();
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			if (username || etime || stime) {
				if (etime || stime) {
					if (etime && stime) {
						if (stime>etime) {
							art.dialog.alert('非法时间段');
							return false;
						}else{
							art.dialog({content:'正在查询....',lock:true});
							return true;
						}
					}else{
						art.dialog.alert('请选择完整时间段');
						return false;
					}
				}else{
					art.dialog({content:'正在查询....',lock:true});
					return true;
				}
			}else{
				art.dialog.alert('请输入查询信息');
				return false;
			}
		})

		$("#import").click(function(){
			var username=$("#username").val();
			var stime=$('#stime').val();
			var etime=$('#etime').val();
			var CanType=$('#CanType').val();
			if (username || etime || stime) {
				if (etime || stime) {
					if (etime && stime) {
						if (stime>etime) {
							art.dialog.alert('非法时间段');
							return false;
						}else{
							art.dialog.tips('正在查询....',3);
							window.location.href="<?php echo U('Statcenter/cancelsOut');?>?CanType="+CanType+"&strtime="+stime+"&endtime="+etime+"&username="+username;
							return true;
						}
					}else{
						art.dialog.alert('请选择完整时间段');
						return false;
					}
				}else{
					art.dialog.tips('正在查询....',3);
					window.location.href="<?php echo U('Statcenter/cancelsOut');?>?CanType="+CanType+"&strtime="+stime+"&endtime="+etime+"&username="+username;
					return true;
				}
			}else{
				art.dialog.alert('请输入查询信息');
				return false;
			}
		})
	})









	$(function () {
		var Money=<?php echo ($money); ?>;
		var Mon=<?php echo ($week); ?>;
		var lineChart = echarts.init(document.getElementById("echarts-line-chart"));
		var lineoption = {
			title : {
				text: '7天核销数据'
			},
			tooltip : {
				trigger: 'axis'
			},
			calculable : true,
			xAxis : [
			{
				type : 'category',
				boundaryGap : false,
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
        		type : ['line', 'bar']
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
    	name:'核销金额',
    	type:'line',
    	data:Money,
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
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>

<script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>