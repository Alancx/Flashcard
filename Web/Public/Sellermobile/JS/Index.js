$(document).ready(function(){
  $('.bot_menu>a>img').css('height',$('.bot_menu>a>img').width()+'px');
  ///////设置顶部信息(三十天的数据)//////////////
  var topChart = echarts.init(document.getElementById('topcharts'));
  var option = {
    tooltip: {
      trigger: 'axis',
      triggerOn:'click',
      formatter: function (params, ticket, callback) {
        return params[0].name+"<br>营收:￥"+params[0].value;
      },
      textStyle:{
        fontSize:10,
      },
      axisPointer:{
        type:'line',
        lineStyle:{
          width:2,
          color:'#7398cc'
        }
      }
    },
    grid:{
      containLabel:false,
      top:5,
      left:10,
      right:10,
      bottom:20
    },
    xAxis: {
      nameGap:0,
      type:'category',
      data: dataday,
      offset:0,
      boundaryGap: false,
      color:'#ffffff',
      axisLine:{
        onZero:false,
        lineStyle:{
          color:'#ffffff',
        }
      },
      axisTick:{
        show:false
      },
      axisLabel:{
        interval:1,
        margin:5,
        formatter: function (value, index){
          return value.substr(8);
        },
        textStyle:{
          fontSize:8
        }
      },
      splitLine: {
        show: true,
        interval:2,

        lineStyle:{
          color:'#ffffff'
        }
      },
      splitArea:{
        interval:0
      }
    },

    yAxis: [{
      show:true,
      position:'right',
      boundaryGap: false,
      // min:-100,
      // max:5000,
      type:'value',
      splitLine: {
        show: false
      },
      axisLine:{
        onZero:false,
        lineStyle:{
          color:'#ffffff',
        }
      },
      axisTick:{
        show:false
      },
      axisLabel:{
        show:false,
      },
    },{
      show:true,
      position:'left',
      boundaryGap: false,
      type:'value',
      splitLine: {
        show: false
      },
      axisLine:{
        onZero:false,
        lineStyle:{
          color:'#ffffff',
        }
      },
      axisTick:{
        show:false
      },
      axisLabel:{
        show:false,
      },
    },],
    series: [{
      name: '天收入',
      type: 'line',
      smooth: true,
      symbol:'rect',
      symbolSize:0,
      symbolRotate:45,
      showAllSymbol:true,
      connectNulls:true,
      clipOverflow:false,
      itemStyle:{
        normal:{
          color:'#ffffff'
        }
      },
      lineStyle:{
        normal:{
          color:'#ffffff',
          width:1
        }
      },
      data: daymon,
    }]
  };
  topChart.setOption(option);
  // topChart.dispatchAction({
  //   type: 'showTip',
  //   seriesIndex: 28,
  //   dataIndex: 0,
  // })
  /////////今日订单数据///////////////
  var todayChart = echarts.init(document.getElementById('centercharts'));
  var option = {
    tooltip: {
      trigger: 'axis',
      formatter: function (params, ticket, callback) {
        return params[0].name.substr(11,5)+"<br>收入:"+params[0].value;
      },
      textStyle:{
        fontSize:10,
      },
      axisPointer:{
        type:'line',
        lineStyle:{
          width:2,
          color:'#ff4200'
        }
      }
    },
    grid:{
      show:false,
      containLabel:false,
      top:20,
      left:20,
      right:20,
      bottom:20
    },
    xAxis: {
      nameGap:0,
      type:'category',
      data: odataday,
      boundaryGap: false,
      color:'#ffffff',
      axisLine:{
        lineStyle:{
          color:'#ffffff',
        }
      },
      axisTick:{
        show:false
      },
      axisLabel:{
        interval:5,
        margin:5,
        formatter: function (value, index){
          return value.substr(11,5);
        },
        textStyle:{
          fontSize:10
        }
      },
      splitLine: {
        show: true,
        interval:5,
        lineStyle:{
          color:'#ffffff'
        }
      },
      splitArea:{
        interval:0
      }
    },
    yAxis: {
      show:false,
      type:'value',
      splitLine: {
        show: false
      }
    },
    series: [{
      name: '订单数',
      type: 'line',
      smooth: true,
      symbol:'rect',
      symbolSize:0,
      symbolRotate:45,
      showAllSymbol:true,
      itemStyle:{
        normal:{
          color:'#ffffff'
        }
      },
      lineStyle:{
        normal:{
          color:'#ffffff',
          width:1
        }
      },
      data: odaymon,
    }]
  };
  // 使用刚指定的配置项和数据显示图表。
  todayChart.setOption(option);
  /////////////////点击添加商品///////////////
  $('.add_pro').click(function(){
    window.location.href = $(this).attr('data-url');
  });
  // /////////////////取消添加商品///////////////
  // $('.cancelpro').click(function(){
  //   $('.converaddpro').css('display','none');
  //   $('body').css('overflow','auto');
  // })
})
