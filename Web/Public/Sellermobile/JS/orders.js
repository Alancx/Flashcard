var funlist={};
$(document).ready(function () {
  var starX; //点击时的坐标信息可视区域
  var starY;//点击时的坐标信息可视区域
  var moveX;//滑动时的坐标信息可视区域
  var moveY;//滑动时的坐标信息可视区域
  //切换订单tab
  $('#tagsel>li').click(function(){
    if(!$(this).hasClass('sel_tag')){
      var sel_order=$(this).attr('data-sel');
      var sel_more=$(this).attr('data-more');
      // $('.rightorders').css('overflow','hidden');
      $('.sel_tag').removeClass('sel_tag');
      $(this).addClass('sel_tag');
      $('.sel_order').removeClass('sel_order');
      $('.'+sel_order).addClass('sel_order');
      if($('.'+sel_order).attr('data-p')=='-1'){
        if(($('.'+sel_more).text()!='已全部加载完')&&($('.'+sel_more).text()!='正在加载···')){
          $('.'+sel_more).text('正在加载···');
          funlist[sel_more]($('.'+sel_order).attr('data-p'));
        }
      }
    }
  })
  //开始滑动事件获取坐标信息
  $(document.body).on('touchstart',function(event){
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      starX=touch.clientX;
      starY=touch.clientY;
    }
  })
  //滑动过程中事件
  $('.rightorders').on('touchmove',function(event){
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      moveX=touch.clientX;
      moveY=touch.clientY;
      if((starY-moveY)>0){
        if($(this)[0].scrollTop+$(this).height()>=$(this)[0].scrollHeight){
          event.preventDefault();//阻止其他事件
          var sel_order=$(this).attr('data-sel');
          var sel_more=$('.sel_tag').attr('data-more');
          if(($('.'+sel_more).text()!='已全部加载完')&&($('.'+sel_more).text()!='正在加载···')){
            $('.'+sel_more).text('正在加载···');
            funlist[sel_more]($('.'+sel_order).attr('data-p'));
          }
        }
      }
    }
  })
  //默认加载第一项
  $('.more_1').text('正在加载···');
  funlist['more_1']('-1');
  //固定左侧和右侧高度
  $('.rightorders').css('height',$(window).height()-50+"px");
  $('#tagsel>li').css('width',($(window).width()-10)/4+"px");
})
funlist.more_1= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_1_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          var temphtml='<br>';
          if (data[key].ShortOid!='' && data[key].ShortOid!=null) {
            temphtml += '<span>就餐码:'+data[key].ShortOid+'</span>';
          }
          if (data[key].TableId!='' && data[key].TableId!=null) {
            temphtml += '<span>桌号:'+data[key].TableId+'</span>';
          }
          htmls+='<div class="orderinfo">'+
          '<label class="orderid">'+data[key].MemberName+'<span>待付款</span>'+
          '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
          '</span>'+temphtml+'</label><div class="prolist">';
          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }

            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+'</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          // htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          // '</span>(含运费:'+data[key].Freight+')</label></div>';
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span></label></div>';

        }
        $(".more_1").before(htmls);
        $(".sel_order_1").attr("data-p",ppage);
        $('.more_1').text('加载更多');
      } else {
        $('.more_1').text('已全部加载完');
      }
    }
  })
}
funlist.more_2= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_2_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          var temphtml='<br>';
          if (data[key].ShortOid!='' && data[key].ShortOid!=null) {
            temphtml += '<span>就餐码:'+data[key].ShortOid+'</span>';
          }
          if (data[key].TableId!='' && data[key].TableId!=null) {
            temphtml += '<span>桌号:'+data[key].TableId+'</span>';
          }

          if (data[key].PayName=="T") {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>待使用</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span>'+temphtml+'</label><div class="prolist">';
          } else {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>待使用</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span>'+temphtml+'</label><div class="prolist">';
          }

          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }
            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+'</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span></label></div>';
        }
        $(".more_2").before(htmls);
        $(".sel_order_2").attr("data-p",ppage);
        $('.more_2').text('加载更多');
      } else {
        $('.more_2').text('已全部加载完');
      }
    }
  })
}
funlist.more_3= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_3_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          if (data[key].PayName=="T") {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>已发货(微信付款)</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span></label><div class="prolist">';
          } else {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>已发货(现金付款)</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span></label><div class="prolist">';
          }
          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }
            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+';</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span>(含运费:'+data[key].Freight+')</label></div>';
        }
        $(".more_3").before(htmls);
        $(".sel_order_3").attr("data-p",ppage);
        $('.more_3').text('加载更多');
      } else {
        $('.more_3').text('已全部加载完');
      }
    }
  })
}
funlist.more_4= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_4_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          if (data[key].PayName=="T") {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>待提货(微信付款)</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span></label><div class="prolist">';
          } else {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>待提货(现金付款)</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span></label><div class="prolist">';
          }

          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }
            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+';</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span>(含运费:'+data[key].Freight+')</label></div>';
        }
        $(".more_4").before(htmls);
        $(".sel_order_4").attr("data-p",ppage);
        $('.more_4').text('加载更多');
      } else {
        $('.more_4').text('已全部加载完');
      }
    }
  })
}
funlist.more_5= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_5_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          var temphtml='<br>';
          if (data[key].ShortOid!='' && data[key].ShortOid!=null) {
            temphtml += '<span>就餐码:'+data[key].ShortOid+'</span>';
          }
          if (data[key].TableId!='' && data[key].TableId!=null) {
            temphtml += '<span>桌号:'+data[key].TableId+'</span>';
          }

          if (data[key].PayName=="T") {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>退款中</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span>'+temphtml+'</label><div class="prolist">';
          } else {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>退款中</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span>'+temphtml+'</label><div class="prolist">';
          }

          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }
            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+'</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span></label></div>';
        }
        $(".more_5").before(htmls);
        $(".sel_order_5").attr("data-p",ppage);
        $('.more_5').text('加载更多');
      } else {
        $('.more_5').text('已全部加载完');
      }
    }
  })
}
funlist.more_6= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_6_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          var temphtml='<br>';
          if (data[key].ShortOid!='' && data[key].ShortOid!=null) {
            temphtml += '<span>就餐码:'+data[key].ShortOid+'</span>';
          }
          if (data[key].TableId!='' && data[key].TableId!=null) {
            temphtml += '<span>桌号:'+data[key].TableId+'</span>';
          }
          if (data[key].PayName=="T") {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>已完成</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span>'+temphtml+'</label><div class="prolist">';
          } else {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>已完成</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span>'+temphtml+'</label><div class="prolist">';
          }

          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }
            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+'</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span></label></div>';

        }
        $(".more_6").before(htmls);
        $(".sel_order_6").attr("data-p",ppage);
        $('.more_6').text('加载更多');
      } else {
        $('.more_6').text('已全部加载完');
      }
    }
  })
}
funlist.more_7= function(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_7_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          if (data[key].PayName=="T") {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>已关闭(微信付款)</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span></label><div class="prolist">';
          } else {
            htmls+='<div class="orderinfo">'+
            '<label class="orderid">'+data[key].MemberName+'<span>已关闭(现金付款)</span>'+
            '<br><span>'+data[key].OrderId+'</span><span>'+(data[key].oDate).substring(5)+
            '</span></label><div class="prolist">';
          }

          var datapro=data[key].prolist;
          for (var pkey in datapro) {
            if (datapro[pkey].Spec==null) {
              var specattr=[];
            } else {
              var specattr=datapro[pkey].Spec.split('_');
              for (var i = 0; i < specattr.length; i++) {
                if((specattr[i]==" ")||(specattr[i]=="")){
                  specattr.splice(i,1);
                  i=i-1;
                }
              }
            }
            htmls+='<div class="proinfo">'+
            '<img src='+datapro[pkey].ProLogoImg+'>'+
            '<label class="proname">'+datapro[pkey].ProName+datapro[pkey].ProTitle+'</label>'+
            '<label class="proattr">'+specattr.join(",")+';</label>'+
            '<label class="proprice"><span>￥</span>'+datapro[pkey].Price+'<span>×'+datapro[pkey].Count+'</span></label></div>';
          }
          htmls+='</div><label class="totalprice">共'+data[key].Count+'件,合计:<span>￥'+data[key].Price+
          '</span>(含运费:'+data[key].Freight+')</label></div>';
        }
        $(".more_7").before(htmls);
        $(".sel_order_7").attr("data-p",ppage);
        $('.more_7').text('加载更多');
      } else {
        $('.more_7').text('已全部加载完');
      }
    }
  })
}
