$(document).ready(function () {
  var starX; //点击时的坐标信息可视区域
  var starY;//点击时的坐标信息可视区域
  var moveX;//滑动时的坐标信息可视区域
  var moveY;//滑动时的坐标信息可视区域
  ///////设置图标信息//////////////
  var myChart = echarts.init(document.getElementById('scharts'));
  var option = {
    tooltip: {
      trigger: 'axis',
      triggerOn:'click',
      formatter: function (params, ticket, callback) {
        seldayven(params[0].name);
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
      data: dataday,
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
          return value.substr(5);
        },
        textStyle:{
          fontSize:10
        }
      },
      splitLine: {
        show: true,
        interval:0,
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
  myChart.setOption(option);
  myChart.dispatchAction({
    type: 'showTip',
    seriesIndex: 0,
    dataIndex: 0,
  })
  // 订单列表开始滑动时的坐标信息///////////////
  $(document.body).on('touchstart',function(event){
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      starX=touch.clientX;
      starY=touch.clientY;
    }
  })
  //滑动事件
  $('.part-con').on('touchmove',function(event){
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      moveX=touch.clientX;
      moveY=touch.clientY;
      if((starY-moveY)>0){
        if($(this)[0].scrollTop+$(this).height()>=$(this)[0].scrollHeight){
          event.preventDefault();//阻止其他事件          
          if(($('.proven').text()!='已全部加载完')&&($('.proven').text()!='正在加载···')){
            $('.proven').text('正在加载···');
            loaddayven($(this).attr('data-day'),$(this).attr('data-page'));
          }
        }
      }
    }
  })
  //////固定显示列表的高度//////////////
  $('.part-con').css('height',$(window).height()-$('.reheard').height()-10+"px");
})
///////点击图标加载所选日期数据/////////////////
function seldayven(selday){
  $('.part-con').attr('data-day',selday);
  $('.part-con').attr('data-page','-1');
  $('.part-con').children('.esven').remove();
  $('.proven').text('加载更多');
  loaddayven(selday,'-1');
}
//////加载所选日期的营业///////////////////
function loaddayven(selday,spage){
  var htmls='';
  spage=parseInt(spage)+1;
  $.ajax({
    type:"post",
    url:dayven_url,
    data:"sday="+selday+"&spage="+spage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          htmls+='<div class="esven">'+
          '<label class="soderid">订单号:'+data[key].OrderId+'<br><span>'+data[key].PayDate+'</span></label>'+
          '<label class="sprice">'+data[key].Price+'</label></div>';
        }
        $(".proven").before(htmls);
        $(".part-con").attr("data-page",spage);
        $('.proven').text('加载更多');
      } else {
        $('.proven').text('已全部加载完');
      }
    }
  })
}
