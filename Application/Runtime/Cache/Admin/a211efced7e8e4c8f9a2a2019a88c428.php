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

<script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
    .ibox-title:hover{
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        var etime=Date.parse(new Date());
        var stime=etime-86400000;
        $.ajax({
            type:"post",
            url:"<?php echo U('Index/get_pv');?>",
            data:"stime="+stime+"&etime="+etime,
            dataType:"json",
            success:function(msg){
                console.log(msg);
                if (msg=='error') {
                    $("#pv_count").html('查询出错 :(');
                } else{
                    $("#pv_count").html(msg);
                }
            }
        })
        var eetime=Date.parse(new Date());
        var sstime=etime-2592000000;
        $.ajax({
            type:"post",
            url:"<?php echo U('Index/get_pv_month');?>",
            data:"stime="+sstime+"&etime="+eetime,
            dataType:"json",
            success:function(msg){
                console.log(msg);
                if (msg=='error') {
                    $("#pv_count_month").html('查询出错 :(');
                } else{
                    $("#pv_count_month").html(msg);
                }
            }
        })
        var eeetime=Date.parse(new Date());
        var ssstime=etime-604800000;
        $.ajax({
            type:"post",
            url:"<?php echo U('Index/get_pv_week');?>",
            data:"stime="+ssstime+"&etime="+eeetime,
            dataType:"json",
            success:function(msg){
                console.log(msg);
                if (msg=='error') {
                    $("#pv_count_week").html('查询出错 :(');
                } else{
                    $("#pv_count_week").html(msg);
                }
            }
        })
    })
</script>
<div class="wrapper wrapper-content animated fadeInRight">

<div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>中国地图</h5>
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
                    <div style="height:600px" id="echarts-map-chart"></div>
                </div>
            </div>
        </div>
    </div>


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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>6个月销售总额 <span style="font-size:1.5em;">￥<?php echo ($half); ?></span></h3>
                    <div style="padding-bottom:15px;text-align:right;">
                       最后统计时间： <?php echo ($etime); ?>
                   </div>
               </div>
               <div class="ibox-content">
                <div class="echarts" id="echarts-line-chart"></div>
            </div>
        </div>
    </div>
</div>


<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">月</span>
                <h5>访客</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins" id="pv_count_month"><small>加载中...</small></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('month');">
                <span class="label label-info pull-right">月</span>
                <h5>订单</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?php if($OrderCount): echo ($OrderCount); else: ?><small>暂无数据</small><?php endif; ?></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('month');">
                <span class="label label-info pull-right">月</span>
                <h5>销售额</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?php if($OrderPrices): echo number_format($OrderPrices,2); else: ?><small>暂无数据</small><?php endif; ?></h1>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('week');">
                <span class="label label-primary pull-right">周</span>
                <h5>访客</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins" id="pv_count_week"><small>加载中...</small></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('week');">
                <span class="label label-info pull-right">周</span>
                <h5>订单</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?php if($OrderCount_week): echo ($OrderCount_week); else: ?><small>暂无数据</small><?php endif; ?></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('week');">
                <span class="label label-info pull-right">周</span>
                <h5>销售额</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?php if($OrderPrices_week): echo number_format($OrderPrices_week,2); else: ?><small>暂无数据</small><?php endif; ?></h1>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">日</span>
                <h5>访客</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins" id="pv_count"><small>加载中...</small></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('day');">
                <span class="label label-info pull-right">日</span>
                <h5>订单</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?php if($OrderCount_day): echo ($OrderCount_day); else: ?><small>暂无数据</small><?php endif; ?></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title" onclick="getOinfo('day');">
                <span class="label label-info pull-right">日</span>
                <h5>销售额</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?php if($OrderPrices_day): echo number_format($OrderPrices_day,2); else: ?><small>暂无数据</small><?php endif; ?></h1>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/Public/Admin/Admin/js/plugins/pace/pace.min.js"></script>

<!-- Peity -->
<script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script>

<!-- Custom and plugin javascript -->
<!-- Flot -->
<!-- <script src="/Public/Admin/Admin/js/plugins/flot/jquery.flot.js"></script>
<script src="/Public/Admin/Admin/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/Public/Admin/Admin/js/plugins/flot/jquery.flot.pie.js"></script>
-->
<!-- Custom and plugin javascript -->
<script src="/Public/Admin/Admin/js/hplus.js?v=2.2.0"></script>
<script src="/Public/Admin/Admin/js/plugins/pace/pace.min.js"></script>

<!-- Flot demo data -->
<!-- <script src="/Public/Admin/Admin/js/demo/flot-demo.js"></script> -->

<!-- jQuery UI -->
<script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Jvectormap -->
<script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- EayPIE -->
<script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script>

<!-- Sparkline -->
<script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="/Public/Admin/plugins/area.js"></script>
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>


<!-- Sparkline demo data  -->
<script>NProgress.done()</script>
<script type="text/javascript">
    $(function () {
        var Money=<?php echo ($Money); ?>;
        var Mon=<?php echo ($Mon); ?>;
        var lineChart = echarts.init(document.getElementById("echarts-line-chart"));
        var lineoption = {
            title : {
                text: '6个月销售额'
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
        }
    },
    series : [
    {
        name:'销售额',
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

$(document).ready(function(){
        $('#sure').click(function(){
            var province=$('#province').val();
            if (province) {
                art.dialog.open("<?php echo U('ArtDialog/showmapdata');?>?province="+province,{width:1000,height:700});
            }else{
                art.dialog.tips('请选择省份');
            }
        })
    })
    var mapChart = echarts.init(document.getElementById("echarts-map-chart"));
    var mapoption = {
        title : {
            text: '门店销售状况',
            subtext: '销售额',
            x:'center'
        },
        tooltip : {
            trigger: 'item'
        },
        legend: {
            orient: 'vertical',
            x:'left',
            data:['销售额']
        },
        dataRange: {
            min: 0,
            max: <?php echo ($maxnum); ?>,
            x: 'left',
            y: 'bottom',
            text:['高','低'],           // 文本，默认为数值文本
            calculable : true
        },
        toolbox: {
            show: true,
            orient : 'vertical',
            x: 'right',
            y: 'center',
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: true},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        roamController: {
            show: true,
            x: 'right',
            mapTypeControl: {
                'china': true
            }
        },
        series : [
            {
                name: '销售额',
                type: 'map',
                mapType: 'china',
                roam: true,
                itemStyle:{
                    normal:{label:{show:true}},
                    emphasis:{label:{show:true}}
                },
                data:<?php echo ($moneydata); ?>
            },
            // {
            //     name: '销售量',
            //     type: 'map',
            //     mapType: 'china',
            //     itemStyle:{
            //         normal:{label:{show:true}},
            //         emphasis:{label:{show:true}}
            //     },
            //     data:<?php echo ($countdata); ?>
            // },
            // {
            //     name: '门店数量',
            //     type: 'map',
            //     mapType: 'china',
            //     itemStyle:{
            //         normal:{label:{show:true}},
            //         emphasis:{label:{show:true}}
            //     },
            //     data:<?php echo ($storedata); ?>
            // },
        ]
    };
    mapChart.setOption(mapoption); 
    mapChart.on('click',function(params){
        console.log(params.name);
        art.dialog.open("<?php echo U('ArtDialog/showmapdata');?>?province="+params.name,{width:1000,height:700});
    })


function getOinfo(type){
    var now=Date.parse(new Date());
    if (type=='month') {
        window.location.href="<?php echo U('Order/showindex');?>?start_time=<?php echo date('Y-m-d 00:00:00',strtotime('-30 day')); ?>&end_time=<?php echo date('Y-m-d H:i:s',time()); ?>";
    };
    if (type=='week') {
        window.location.href="<?php echo U('Order/showindex');?>?start_time=<?php echo date('Y-m-d 00:00:00',strtotime('-7 day')); ?>&end_time=<?php echo date('Y-m-d H:i:s',time()); ?>";
    };
    if (type=='day') {
        window.location.href="<?php echo U('Order/showindex');?>?start_time=<?php echo date('Y-m-d 00:00:00',time()); ?>&end_time=<?php echo date('Y-m-d H:i:s',time()); ?>";
    };
}
</script>
</body>

</html>