var eatcodeorderid='';
var settimenums =0;
$(document).ready(function(){
  // 选择订单转态
  $('.otype').on('tap',function(){
    if(!$(this).hasClass('getseltypeactive')){
      $('.getseltypeactive').removeClass('getseltypeactive');
      $(this).addClass('getseltypeactive');
      var stype = $(this).attr('data-type');
      $('.orderinfo').css('display','none');
      switch (stype) {
        case '0':
        $('.orderinfo').css('display','block');
        break;
        case '1':
        $('.orderinfo[data-status=1]').css('display','block');
        break;
        case '2':
        $('.orderinfo[data-status=2]').css('display','block');
        $('.orderinfo[data-status=3]').css('display','block');
        break;
        case '3':
        $('.orderinfo[data-status=4][data-isevaluation=0]').css('display','block');
        break;
        case '4':
        $('.orderinfo[data-status=5]').css('display','block');
        $('.orderinfo[data-status=8]').css('display','block');
        break;
        default:
        $('.orderinfo').css('display','none');
      }
    }
  });
  // 就餐码
  $('.eatorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    eatcodeorderid = oid;
    var stoken = $(this).parents('.orderinfo').attr('data-stoken');
    var tempurl = eatordercode_url;
    tempurl=tempurl.replace(/ORDERIDTEMP/g,oid);
    tempurl=tempurl.replace(/STOKENTEMP/g,stoken);
    $('.showeatcode>img').attr('src',tempurl);
    $('.eatqrcodemark').css('display','block');
    settimenums=0;
    setTime();
  });
  $('.closeatcode').on('tap',function(){
    $('.showeatcode>img').attr('src','');
    $('.eatqrcodemark').css('display','none');
  });
  // 去支付
  $('.payorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    var tempurl = payorder_url;
    tempurl=tempurl.replace(/ORDERIDTEMPS/g,oid);
    window.location.href=tempurl;
  });
  // 退款
  $('.tkorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    var status = '5';
    setorderstatus(oid,status);
  });
  // 取消订单
  $('.qxorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    var status = '9';
    setorderstatus(oid,status);
  });
  // 删除订单
  $('.delorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    var status = '10';
    setorderstatus(oid,status);
  });
  // 去评价
  $('.evalorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    var stoken = $(this).parents('.orderinfo').attr('data-stoken');
    var tempurl = gotoevaluation_url;
    tempurl=tempurl.replace(/ORDERIDTEMPS/g,oid);
    tempurl=tempurl.replace(/STOKENTEMPS/g,stoken);
    window.location.href=tempurl;
  });
  // 立即分享
  $('.shareorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    var tempurl = paysuccess_url;
    tempurl=tempurl.replace(/ORDERIDTEMPS/g,oid);
    window.location.href=tempurl;
  })
  // 确认完成
  $('.sureorder').on('tap',function(){
    var oid = $(this).parents('.orderinfo').attr('data-oid');
    showwaiting('正在处理...');
    $.ajax({
      url:setorderend_url,
      type:"post",
      data:{'oid':oid},
      dataType:"json",
      complete:function(){
        closeWaiting();
      },
      success:function(msg){
        if (msg['status'] == 'true') {
          mui.toast('处理成功');
          setTimeout(function(){window.location.reload();},1500);
        }
      },
      error:function(e){

      }
    });

  });

})

function setorderstatus(oid,status){
  showwaiting('正在处理...');
  $.ajax({
    url:setorderstatus_url,
    type:"post",
    data:{'oid':oid,'status':status},
    dataType:"json",
    complete:function(){
      closeWaiting();
    },
    success:function(msg){
      if (msg['status'] == 'true') {
        mui.toast('处理成功');
        setTimeout(function(){window.location.reload();},1500);
      }
    },
    error:function(e){

    }
  });
}
function setTime(){
  setTimeout(function(){
    if (eatcodeorderid!='' && settimenums<100) {
      settimenums++;
      $.ajax({
        url:getorderstatus_url,
        type:"post",
        data:{'oid':eatcodeorderid},
        dataType:"json",
        complete:function(){
        },
        success:function(msg){
          if (msg['status'] == 'true') {
            if (msg.info == '4') {
              eatcodeorderid='';
              window.location.reload();
            }
          }
        },
        error:function(e){

        }
      });
      setTime();
    } else {

    }
  },500);
}
