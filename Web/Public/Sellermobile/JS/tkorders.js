$(document).ready(function(){
  /////////////点击退款按钮////////////////
  $('.tkorder').click(function(){
    var oid=$(this).attr('data-oid');
    $('.verimg').attr('src',verify_url);
    $('.vercode').val('');
    $('.vercouver').css('display','block');
    $('body').css('overflow','hidden');
    $('.suerver').attr('data-oid',oid);
  });
  ///////////取消验证码//////////////
  $('.qxver').click(function(){
    $('.verimg').attr('src','');
    $('.vercode').val('');
    $('.vercouver').css('display','none');
    $('body').css('overflow','auto');
    $('.suerver').attr('data-oid','');
  })
  //////////确认退款处理/////////////
  $('.suerver').click(function(){
    var oid=$(this).attr('data-oid');
    var vercode=$('.vercode').val();
    if ($.trim(vercode)=='') {
      tips('notice', '填写验证码!', 1500, 'weui_icon_notice');
      return false;
    }
    tips('waiting','正在处理···');
    $.ajax({
      type:"post",
      url:tk_url,
      data:"oid="+oid+"&vercode="+vercode+"&type=1",
      dateType:"json",
      complete: function(e){
      hidetips('waiting');
      },
      success: function(msg){
          if (msg.status == 'true') {
            $('.order_'+msg.info).remove();
            $('.verimg').attr('src','');
            $('.vercode').val('');
            $('.vercouver').css('display','none');
            $('body').css('overflow','auto');
            $('.suerver').attr('data-oid','');
            tips('notice', '处理完成!', 1500, 'weui_icon_toast');
            setTimeout(function(e){
              window.location.href=index_url;
            },1000);
          } else{
            if (msg.info=='vererror') {
              tips('notice', '验证码错误!', 1500, 'weui_icon_notice');
            } else {
            tips('notice', '处理失败!', 1500, 'weui_icon_notice');
            }
            $('.verimg').attr('src',verify_url);
          }
      },
    })
  });
  ////////拒绝退款//////////////////
  $('.tkjjorder').click(function(){
    var oid=$(this).attr('data-oid');
    tips('waiting','正在处理···');
    $.ajax({
      type:"post",
      url:tk_url,
      data:"oid="+oid+"&type=2",
      dateType:"json",
      complete: function(e){
      hidetips('waiting');
      },
      success: function(msg){
          if (msg.status == 'true') {
            $('.order_'+msg.info).remove();
            tips('notice', '处理完成!', 1500, 'weui_icon_toast');
            setTimeout(function(e){
              window.location.href=index_url;
            },1000);
          } else{
            tips('notice', '处理失败!', 1500, 'weui_icon_notice');
          }
      },
    })
  });
/////////更新验证码///////////////////////
  $('.verimg').click(function(){
    $(this).attr('src',verify_url);
  })
})
