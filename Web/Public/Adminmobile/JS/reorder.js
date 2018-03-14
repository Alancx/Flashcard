$(document).ready(function () {
  var starX; //点击时的坐标信息可视区域
  var starY;//点击时的坐标信息可视区域
  var moveX;//滑动时的坐标信息可视区域
  var moveY;//滑动时的坐标信息可视区域
  ////////设置图标信息/////////////////////
  var myChart = echarts.init(document.getElementById('scharts'));
  var option = {
    tooltip: {
      trigger: 'axis',
      formatter: function (params, ticket, callback) {
        return params[0].name.substr(11,5)+"<br>下单笔数:"+params[0].value;
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
      data: dataday,
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
      data: daymon,
    }]
  };
  // 使用刚指定的配置项和数据显示图表。
  myChart.setOption(option);
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
          if(($('.proder').text()!='已全部加载完')&&($('.proder').text()!='正在加载···')){
            $('.proder').text('正在加载···');
            loadyesorder($(this).attr('data-page'));
          }
        }
      }
    }
  })
  //////固定显示列表的高度//////////////
  $('.part-con').css('height',$(window).height()-$('.reheard').height()-10+"px");
  loadyesorder('-1');
})
//////加载昨日的订单///////////////////
function loadyesorder(spage){
  var htmls='';
  spage=parseInt(spage)+1;
  $.ajax({
    type:"post",
    url:yesorder_url,
    data:"spage="+spage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          htmls+='<div class="sorder">'+
          '<label class="soderoid">订单号:'+data[key].OrderId+'</label>'+
          '<div class="prooders">';
          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            htmls+='<div class="prooder">'+
              '<img src='+datapro[pkey].ProLogoImg+'>'+
              '<label class="protitle">'+datapro[pkey].ProName+'<br><span>'+datapro[pkey].ProTitle+'</span></label>'+
              '<label class="proprice">'+datapro[pkey].Price+'<br><span>x'+datapro[pkey].Count+'</span></label>'+
              '</div>'
          }
          htmls+='</div>'+
          '<label class="totalprice">共'+data[key].Count+'件商品 合计:<span>￥'+
          data[key].Price+'</span>(含运费￥'+data[key].Freight+')</label></div>';
        }
         $(".proder").before(htmls);
        $(".part-con").attr("data-page",spage);
        $('.proder').text('加载更多');
      } else {
        $('.proder').text('已全部加载完');
      }
    }
  })
}
