var cuttype = '0';
$(document).ready(function(){
  // 初始化时间控件
  $('.showicon').datetimepicker({
    language:'cn',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
  }).on('changeDate',function(ev){
    $('.showdate').text(FromatStrtime(ev.date));
    $('.showdate').attr('data-date',ev.date.valueOf())
  });
  // 点击搜索按钮
  $('.suredate').click(function(){
    var etime=$('.showdate').attr('data-date');
    if (etime) {
      if (etime > (new Date()).valueOf()) {
        tips('notice', '选择日期不能超过当前时间!', 1500, 'weui_icon_notice');
        return false;
      }
      etime = FromatStrtimes(new Date(etime*1));

      tips('waiting','正在搜索···');
      $.ajax({
        type:"post",
        url:search_url,
        data:"endtime="+etime,
        dateType:"json",
        complete: function(e){
        hidetips('waiting');
        },
        success: function(msg){
            if (msg.status == 'true') {
              var alldata = msg.datainfo;
              $('.allprice').text(alldata['info']['Allmoney']+'元');
              $('.cutprice').text(alldata['info']['cut']+'元');
              $('.cutper').text(alldata['info']['CutNum']+'%');
              $('.lastcuttime').text(FromatStrtime(new Date(alldata['info']['Lastcut'] * 1000)));
              $('.surebtn').attr('stime',alldata['data']['strtime']);
              $('.surebtn').attr('etime',alldata['data']['endtime']);
              $('.lookinfo').attr('stime',alldata['data']['strtime']);
              $('.lookinfo').attr('etime',alldata['data']['endtime']);
              tips('notice', '搜索完成!', 1500, 'weui_icon_toast');
            } else{
              tips('notice', '搜索失败!', 1500, 'weui_icon_notice');
            }
        },
      })
    } else {
      tips('notice', '选择截至日期!', 1500, 'weui_icon_notice');
    }
  });
  // 点击结算按钮
  $('.surebtn').click(function(){
    var stime=$(this).attr('stime');
    var etime=$(this).attr('etime');
    if (stime !='' && etime!='') {
      tips('waiting','正在结算···');
      $.ajax({
        url:cut_url,
        type:"post",
        data:"stime="+stime+"&etime="+etime,
        dataType:"json",
        complete: function(e){
        hidetips('waiting');
        },
        success:function(msg){
          if (msg.status=='success') {
            tips('notice', '结算完成!', 1500, 'weui_icon_toast');
            setTimeout(function(e){
              window.location.href=getcut_url;
            },1000);
          }else{
            tips('notice', '处理失败!', 1500, 'weui_icon_notice');
          }
        }
      })
    } else {
      tips('notice', '选择结算日期搜索!', 1500, 'weui_icon_notice');
    }
  });

  // 查看明细
  $('.lookinfo').click(function(){
    var stime=$(this).attr('stime');
    var etime=$(this).attr('etime');
    if (stime !='' && etime!='') {
      tips('waiting','正在加载···');
      $.ajax({
        url:lookcut_url,
        type:"post",
        data:"stime="+stime+"&etime="+etime,
        dataType:"json",
        complete: function(e){
        hidetips('waiting');
        },
        success:function(msg){
          if (msg.status=='success') {
            tips('notice', '加载完成!', 1500, 'weui_icon_toast');
            var htmls = '';
            $.each(msg.info,function(index,item){
              if (item['IsDis'] == '1') {
                htmls +='<div class="orderinfo">'+
                  '<span>订单号:'+item['OrderId']+'<span>优惠订单</span></span>'+
                  '<span>金额<span>￥'+parseFloat(item['Price']).toFixed(2)+'</span></span>'+
                  '<span>下单时间<span>'+item['CreateDate']+'</span></span>'+
                '</div>';
              } else {
                htmls +='<div class="orderinfo">'+
                  '<span>订单号:'+item['OrderId']+'<span></span></span>'+
                  '<span>金额<span>￥'+parseFloat(item['Price']).toFixed(2)+'</span></span>'+
                  '<span>下单时间<span>'+item['CreateDate']+'</span></span>'+
                '</div>';
              }
            });
            $('.orderslist').html(htmls);
            $('.ordersmark').css('display','block');
          }else{
            tips('notice', '处理失败!', 1500, 'weui_icon_notice');
          }
        }
      })
    } else {
      tips('notice', '选择结算日期搜索!', 1500, 'weui_icon_notice');
    }
  })
  // 关闭查看
  $('.closeorders').click(function(){
    $('.orderslist').html('');
    $('.ordersmark').css('display','none');
  })
})



////时间戳格式化为yyyy-MM-dd hh:mm////////
var FromatStrtime = function(date) {
	var tempyear = date.getFullYear();
	var tempmonth = date.getMonth() + 1;
	var tempday = date.getDate();
	var temphour = date.getHours();
	var tempmin = date.getMinutes();
  var datestr = tempyear + '-' + (tempmonth < 10 ? '0' + tempmonth : tempmonth) + '-' + (tempday < 10 ? '0' + tempday : tempday);
	datestr = datestr + ' ' + (temphour < 10 ? '0' + temphour : temphour) + ':' + (tempmin < 10 ? '0' + tempmin : tempmin);
	return datestr;
};
////时间戳格式化为yyyy-MM-dd hh:mm:ss////////
var FromatStrtimes = function(date) {
  var tempyear = date.getFullYear();
	var tempmonth = date.getMonth() + 1;
	var tempday = date.getDate();
	var temphour = date.getHours();
	var tempmin = date.getMinutes();
	var tempsec = date.getSeconds();
	var datestr = tempyear + '-' + (tempmonth < 10 ? '0' + tempmonth : tempmonth) + '-' + (tempday < 10 ? '0' + tempday : tempday);
	datestr = datestr + ' ' + (temphour < 10 ? '0' + temphour : temphour) + ':' + (tempmin < 10 ? '0' + tempmin : tempmin) + ':' + (tempsec < 10 ? '0' + tempsec : tempsec);
	return datestr;
};
