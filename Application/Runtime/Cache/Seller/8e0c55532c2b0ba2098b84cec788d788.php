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

<script>NProgress.start();
var _hmt= _hmt || [];
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol +
"hm.baidu.com/h.js%5fe6394d7eb4d1c46a81d464d46db432' type='text/javascript'%3E%3C/script%3E"));
</script>
<link href="/Public/Admin/Admin/common/css/order.style.css" rel="stylesheet">
<style type="text/css">
ul,ol,li{
    list-style: none;
    margin: 0;
    padding: 0;
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


<!--面包屑 标题栏-->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>订单管理</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo U('Index/index');?>">主页</a>
            </li>
            <li class="active"> <strong>订单概况</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content dash-ibox-content">
                <div id="js-overview" class="dash-bar clearfix">
                    <div class="js-cont dash-todo__body">
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=0&start_time=<?php echo ($start7_time); ?>&end_time=<?php echo ($end_time); ?>"><?php echo ($options[0]["Rows"]); ?></a>
                                </span>
                                <span class="info-description">7天订单笔数</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=1"><?php echo ($options[1]["Rows"]); ?></a>
                                </span>
                                <span class="info-description">待付款</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=2"><?php echo ($options[2]["Rows"]); ?></a>
                                </span>
                                <span class="info-description">待发货</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=0&start_time=<?php echo ($start7_time); ?>&end_time=<?php echo ($end_time); ?>">￥<?php echo (number_format($options[0]["Money"],2,',',' ')); ?></a>
                                </span>
                                <span class="info-description">7天订单额</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=0&start_time=<?php echo ($start1_time); ?>&end_time=<?php echo ($end_time); ?>"><?php echo ($options[3]["Rows"]); ?></a>
                                </span>
                                <span class="info-description">昨日订单笔数</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=2&start_time=<?php echo ($start1_time); ?>&end_time=<?php echo ($end_time); ?>"><?php echo ($options[4]["Rows"]); ?></a>
                                </span>
                                <span class="info-description">昨日付款订单</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-group__inner">
                                <span class="h4">
                                    <a href="<?php echo U('allorder');?>?type=index&state=3&start_time=<?php echo ($start1_time); ?>&end_time=<?php echo ($end_time); ?>"><?php echo ($options[5]["Rows"]); ?></a>
                                </span>
                                <span class="info-description">昨日发货订单</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox-content" style="padding-bottom:0px;">
                <form role="form" class="form-horizontal" method="post" id="allorder_form">
                    <div class="filter-groups" style="width:580px;">
                        <div class="form-group">
                            <label class="control-label">筛选日期：</label>
                            <div class="controls">
                                <input readonly type="text" name="start_time" id="start_time" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',autoPickDate:true,maxDate:'%y-%M-{%d-2}',isShowClear:false})" class="Wdate form-control input-sm" style="width:150px;display:inline-block;" value="<?php echo (date('Y-m-d',strtotime($start7_time))); ?>">
                                <span>至</span>
                                <input readonly type="text" name="end_time" id="end_time" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',autoPickDate:true,maxDate:'%y-%M-%d',isShowClear:false})" class="Wdate form-control input-sm" style="width:150px;display:inline-block;" value="<?php echo (date('Y-m-d',strtotime($end_time))); ?>">
                                &nbsp;&nbsp;
                                <a class="btn btn-xs" data-day="6">最近7天</a>
                                &nbsp;
                                <a class="btn btn-xs" data-day="29">最近30天</a>
                            </div>
                        </div>
                    </div>
                    <div style="clear:left;"></div>
                    <div>
                        <button type="button" class="btn btn-w-m btn-success btn-sm" id="btn_select">筛选</button>
                    </div>
                </form>
            </div>
                <div id="js-pagedata" class="widget widget-pagedata">
                    <div class="widget-inner">
                        <div class="widget-head">
                            <h3 class="widget-title"><span id="top_day">7</span>天订单趋势</h3>
                            <ul class="widget-nav">
                                <li>
                                    <a class="new-window a_order_count" data-state="0">详细数据>>></a>
                                </li>
                            </ul>
                            <div class="help tooltip-demo">
                                <a href="javascript:void(0);" class="js-help-icon" data-toggle="tooltip" data-placement="left" data-original-title="订单笔数：所有用户的订单总数。付款订单：已经付款的订单总数。发货笔数：已经发货的订单总数。">?</a>
                            </div>
                        </div>
                        <div class="widget-body with-border">
                            <div class="js-body widget-body__inner clearfix">
                                <div class="js-body-chart chart-body" style="cursor: default;">
                                    <div style="position: relative; overflow: hidden; height: 320px;" class="echarts" id="echarts-line-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/Admin/js/plugins/toastr/toastr.min.js"></script>
<script>
var bindoption={postallUrl:"<?php echo U('allorder');?>",postUrl:"<?php echo U('index');?>",date:[<?php echo (implode(",",$outdate)); ?>],down:[<?php echo (implode(",",$downdata)); ?>],pay:[<?php echo (implode(",",$paydata)); ?>],send:[<?php echo (implode(",",$senddata)); ?>],isdate:"开始日期小于结束日期"};
 $(function(){toastr.options={"closeButton":true,"debug":false,"progressBar":false,"positionClass":"toast-top-center","onclick":null,"showDuration":"300","hideDuration":"1000","timeOut":"1500","extendedTimeOut":"0","showEasing":"swing","hideEasing":"linear","showMethod":"fadeIn","hideMethod":"fadeOut"};var echarts_line_chart=document.getElementById("echarts-line-chart");var top_day=$("#top_day");var lineChart=echarts.init(echarts_line_chart);var lineoption={tooltip:{trigger:"axis"},legend:{data:["订单笔数","付款订单","发货笔数"]},toolbox:{show:true,feature:{dataView:{show:true,readOnly:false},magicType:{show:true,type:["line","bar"]},restore:{show:true},saveAsImage:{show:true}}},calculable:true,xAxis:[{type:"category",boundaryGap:false,data:bindoption.date}],yAxis:[{type:"value",axisLabel:{formatter:"{value}"}}],series:[{name:"订单笔数",type:"line",data:bindoption.down,symbol:'none',markPoint:{data:[{type:"max",name:"最大值"},{type:"min",name:"最小值"}]}},{name:"付款订单",type:"line",data:bindoption.pay,symbol:'none',markPoint:{data:[{type:"max",name:"最大值"},{type:"min",name:"最小值"}]}},{name:"发货笔数",type:"line",data:bindoption.send,symbol:'none',markPoint:{data:[{type:"max",name:"最大值"},{type:"min",name:"最小值"}]}}]};lineChart.setOption(lineoption);var start_time=$("#start_time"),end_time=$("#end_time");$(".btn-xs").bind("click",function(){var nowtime=addDate(CurentTime("dd"),-1);var temp_start_time=addDate(nowtime,-parseInt($.trim($(this).attr("data-day"))));start_time.val(temp_start_time);end_time.val(nowtime);bindChart(temp_start_time,nowtime)});$(document).on("click",".a_order_count",function(){var s=$.trim(start_time.val());var e=$.trim(end_time.val());if(Date.parse(s)>Date.parse(e)){toastr.warning(bindoption.isdate);return false}window.location.href=bindoption.postallUrl+"?type=index&state="+$(this).attr("data-state")+"&start_time="+s+" 00:00:00&end_time="+e+" 23:59:59"});$("#btn_select").bind("click",function(){var s=$.trim(start_time.val());var e=$.trim(end_time.val());if(Date.parse(s)>Date.parse(e)){toastr.warning(bindoption.isdate);return false}bindChart(s,e)});var bindChart=function(s,e){NProgress.start();top_day.html(daysBetween(e,s)+1);lineChart=echarts.init(echarts_line_chart);lineChart.showLoading();$.ajax({type:"POST",url:bindoption.postUrl,contentType:"application/x-www-form-urlencoded; charset=utf-8",data:{"start_time":s,"end_time":e,"r":(Math.random()*Math.random())},dataType:"json",timeout:20000,success:function(data){if(code="0"){lineoption.xAxis[0].data=data.date.split(',');lineoption.series[0].data=data.downdata.split(',');lineoption.series[1].data=data.paydata.split(',');lineoption.series[2].data=data.senddata.split(',');lineChart.refresh();lineChart.hideLoading();lineChart.setOption(lineoption)}NProgress.done()}})};NProgress.done()});
</script>
</body>
</html>