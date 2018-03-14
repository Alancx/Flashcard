$(document).ready(function(){
  $('.ordertype').click(function(){
    if (!$(this).hasClass('selorderactive')) {
      var type= $(this).attr('data-type');
      $('.selorderactive').removeClass('selorderactive');
      $(this).addClass('selorderactive');
      if (type=='0') {
        $('.dising').css('display','block');
        $('.disend').css('display','none');
      } else {
        $('.dising').css('display','none');
        $('.disend').css('display','block');
        if ($('.disend').attr('type-page')=='0') {
          $('.disend>.getmoreorder').click();
        }
      }
    }
  })
  $('.seldising').click(function(){
    var isboss=$(this).attr('data-boss');
    var type=$(this).attr('data-type');
    if (isboss=='1') {
      return false;
    }
    if (type=='0') {
      $(this).text('休息中');
      $(this).attr('data-type','1');
    } else {
      $(this).text('配送中');
      $(this).attr('data-type','0');
    }
    $.ajax({
      url:updatedistype,
      type:"post",
      data:"type="+type,
      dataType:"json",
      success:function(msg){
        if (msg.status=='true') {

        }else{
          if (type=='0') {
            $('.seldising').text('配送中');
            $('.seldising').attr('data-type','0');
          } else {
            $('.seldising').text('休息中');
            $('.seldising').attr('data-type','1');
          }
        }
      }
    })
  });
  $('.getmoreorder').click(function(){
    _label=$(this);
    if (_label.text()=='加载更多') {
      var page=_label.parent().attr('type-page');
      var type=_label.attr('data-type');
      var senddata={
        'page':page,
        'type':type
      };
      _label=$(this);
      _label.text('正在加载···');
      $.ajax({
        url:getmoreorder_url,
        type:"post",
        data:"page="+page+"&type="+type,
        dataType:"json",
        success:function(msg){
          if (msg.status=='true') {
            $.each(msg.datainfo,function(index,item){
              if (type=='ordering') {
                var htmls='<div class="orderinfo">'+
                '<div class="order_info">'+
                '<label class="orderid">订单号 :'+item['OrderId']+'</label>'+
                '<label class="orderprice">配送费 :￥'+parseFloat(item['Price']).toFixed(2)+'</label>'+
                '<label class="ordername"><span>收货人姓名:</span>'+item['RecevingName']+'</label>'+
                '<label class="orderphone"><span>收货人电话:</span>'+item['RecevingPhone']+'</label>'+
                '<label class="orderaddr"><span>收货人地址:</span>'+item['addr']+'</label>'+
                '<label class="prolist"><span>商品信息:</span>PROLISTINFONAME</label></div>';
                var html='';
                $.each(item.prolist,function(i,it){
                  html+=it['product'];
                });
                htmls=htmls.replace(/PROLISTINFONAME/g,html);
                if (item['Status']=='0') {
                  htmls+='<div class="btn_group"><label class="suresend" data-oid="E201705191105537814" onclick="suresend(this)">确认提货</label></div></div>'
                } else {
                  htmls+='<div class="btn_group"><label class="suresend" data-oid="E201705191105537814" onclick="suresend(this)">确认送达</label></div></div>'
                }
                _label.before(htmls);
              } else {
                var htmls='<div class="orderinfo">'+
                '<div class="order_info">'+
                '<label class="orderid">订单号 :'+item['OrderId']+'</label>'+
                '<label class="orderprice">配送费 :￥'+parseFloat(item['Price']).toFixed(2)+'</label>'+
                '<label class="ordername"><span>收货人姓名:</span>'+item['RecevingName']+'</label>'+
                '<label class="orderphone"><span>收货人电话:</span>'+item['RecevingPhone']+'</label>'+
                '<label class="orderaddr"><span>收货人地址:</span>'+item['addr']+'</label>'+
                '<label class="prolist"><span>商品信息:</span>PROLISTINFONAME</label></div>';
                var html='';
                $.each(item.prolist,function(i,it){
                  html+=it['product'];
                });
                htmls=htmls.replace(/PROLISTINFONAME/g,html);
                _label.before(htmls);
              }
            })
            _label.text('加载更多');
            _label.parent().attr('type-page',parseInt(page)+1);
          }else{
            _label.text('已全部加载');
          }
        }
      })
    }
  })
})

////////确认送达和确认提货//////////
var locksend=false;
function suresend(label){
  if (locksend) {
    return false;
  }
  var _label=$(label);
  var oid=_label.attr('data-oid');
  if (_label.text()=='确认提货') {
    var type='0';
    locksend=true;
    tips('waiting',"提交中",15000);
    $.ajax({
      url:savesidlist,
      type:"post",
      data:"type="+type+"&oid="+oid,
      dataType:"json",
      complete:function(){
        hidetips('waiting');
        locksend=false;
      },
      success:function(msg){
        if (msg.status=='true') {
            tips('notice','提货成功',1500);
            _label.text('确认送达');
        }else{
          tips('notice',"处理失败",1500,'weui_icon_notice');
        }
      }
    })
  } else {
    $('.convercode').css('display','block');
    _label.addClass('sendcompleteactive');
  }
}
///////////////////////////////////
function quxiaocode(){
  $('.convercode').css('display','none');
  $('.sendcompleteactive').removeClass('sendcompleteactive');
  $('#input_code').val('');
}
//////////////////////////////////
function surecode(){
  var _label =$('.sendcompleteactive');
  var oid=_label.attr('data-oid');
  var type='1';
  var code=$('#input_code').val();
  if (code=='') {
    tips('notice',"填写验证码",1500,'weui_icon_notice');
    return false;
  }
  locksend=true;
  $('.convercode').css('display','none');
  $('.sendcompleteactive').removeClass('sendcompleteactive');
  $('#input_code').val('');
  tips('waiting',"提交中",15000);
  $.ajax({
    url:savesidlist,
    type:"post",
    data:"type="+type+"&oid="+oid+"&code="+code,
    dataType:"json",
    complete:function(){
      hidetips('waiting');
      locksend=false;
    },
    success:function(msg){
      if (msg.status=='true') {
        tips('notice','配送完成',1500);
          _label.parent().css('display','none');
      }else{
        if (msg.datainfo=='codeError') {
        tips('notice',"验证码错误",1500,'weui_icon_notice');
      } else {
      tips('notice',"处理失败",1500,'weui_icon_notice');
      }
      }
    }
  })
}
