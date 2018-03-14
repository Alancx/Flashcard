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
<link href="/Public/Admin/Admin/common/css/order.style.css" rel="stylesheet">
<style type="text/css">
    .ibox-title:hover{
        cursor: pointer;
    }
    body{
        background-color: #fff!important;
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
        <div class="col-lg-6 col-md-6" style="display:none;">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-bar-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>6个月销售总额 <span style="font-size:1.5em;">￥<?php echo ($half); ?></span></h3>
                    <div style="padding-bottom:15px;text-align:right;">
                        <button class="btn btn-success btn-outline btn-xs" type="button" data-toggle='modal' data-target='#showlogo'>更新日志</button>
                       <button class="btn btn-white btn-outline btn-xs" type="button" data-toggle="modal" data-target="#order_message">店铺二维码</button>
                       <br>
                       最后统计时间： <?php echo ($etime); ?>
                   </div>
               </div>
               <div class="ibox-content">
                <div class="echarts" id="echarts-line-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="order_message" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width:428px;">
        <div class="modal-content">
            <div class="modal-header" style="padding:10px 15px;">
                店铺二维码
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body" style="padding:15px;text-align:center">
               <div>
               <img src="<?php echo U('Index/showqr');?>" alt="">
               </div>
              <!--  <br><br><br><br>
              <p>收银台二维码</p>
              <img src="<?php echo U('Index/getqr');?>">
              <br>
              收银台地址: <a href="<?php echo ($url); ?>" target="_blank"><?php echo ($url); ?></a> -->
           </div> 
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-w-m btn-success input-sm" id="btn_message" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="showlogo" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width:428px;">
        <div class="modal-content">
            <div class="modal-header" style="padding:10px 15px;">
                更新日志
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body" style="padding:15px;text-align:center">
            <p>订单管理优化、新增默认备注内容管理 、优化多吃优惠设置、优化特价商品设置<small>2018-01-22</small></p>
           </div> 
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-w-m btn-success input-sm" id="btn_message" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4">
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

    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4">
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

    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4">
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
    <div class="col-lg-4 col-md-4">
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