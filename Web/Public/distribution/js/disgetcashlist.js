$(document).ready(function(){
  $('.orderlist').css('height',$(window).innerHeight()-$('.toptotalpart').outerHeight()-$('.seltype').outerHeight()+'px');
  $('.sel_type').click(function(){
    if (!$(this).hasClass('seltypeactive')) {
      $('.seltypeactive').removeClass('seltypeactive');
      $(this).addClass('seltypeactive');
      var type=$(this).attr('data-type');
      if (type=='1') {
        $('.btn_getcash').css('display','block');
      } else {
        $('.btn_getcash').css('display','none');
      }
      $('.order_list').css('display','none');
      $('.order_list_'+type).css('display','block');
    }
  })
  $('.orderinfo>span').click(function(){
    if ($(this).hasClass('selorderactive')) {
      $(this).removeClass('selorderactive');
    } else {
      $(this).addClass('selorderactive');
    }
    if ($('.selorderactive').length==$('.orderinfo').length) {
      $('.getallorder>span').addClass('allorderactive');
    } else {
      $('.getallorder>span').removeClass('allorderactive');
    }
  });
  $('.getallorder').click(function(){
    if ($('.getallorder>span').hasClass('allorderactive')) {
      $('.getallorder>span').removeClass('allorderactive');
      $('.orderinfo>span').removeClass('selorderactive');
    } else {
      $('.getallorder>span').addClass('allorderactive');
      $('.orderinfo>span').addClass('selorderactive');
    }
  })
  $('.getselcash').click(function(){
    if ($('.selorderactive').length>0) {
      var cashjson={};
      $('.selorderactive').each(function(index,item){
        var stoken=$(item).parent().attr('data-stoken');
        var price=$(item).parent().attr('data-price');
        var orderid=$(item).parent().attr('data-oid');
        if (cashjson[stoken]) {
          cashjson[stoken]['money']=(parseFloat(cashjson[stoken]['money'])+parseFloat(price)).toFixed(2);
          cashjson[stoken]['olist']=cashjson[stoken]['olist']+','+orderid;
        } else {
          cashjson[stoken]={};
          cashjson[stoken]['money']=price;
          cashjson[stoken]['olist']=orderid;
        }
      });
      cashjsonstr=JSON.stringify(cashjson);
      tips('waiting',"提交中",15000);
      $.ajax({
        url:savecash_url,
        type:"post",
        data:"data="+cashjsonstr,
        dataType:"json",
        complete:function(){
          hidetips('waiting');          
        },
        success:function(msg){
          if (msg.status=='true') {
            tips('notice',"提现提交成功",1500);
            setTimeout(function(){
              window.location.reload();
            },1500);
          }else{
            tips('notice',"提现提交失败",1500,'weui_icon_notice');
          }
        }
      })
    } else {
      tips('notice',"选择提现订单",1500,'weui_icon_notice');
    }
  });
})
