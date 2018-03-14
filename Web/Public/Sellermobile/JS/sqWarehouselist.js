$(document).ready(function(){
  ////////删除//////////////
  $('.delnow').click(function(){
    var whid=$(this).attr('data-whid');
    $.ajax({
      url:delwh_url,
      type:"post",
      data:{whid:whid},
      dataType:"json",
      beforeSend:function(){
        tips('waiting','数据处理中...');
      },
      success:function(msg)
      {
        if (msg.status=='true')
        {
          tips('notice','删除成功',2000);
          setTimeout(function(e){
            window.location.reload();
          },2000);
        }
        else
        {
          tips('notice','删除失败',2000,'weui_icon_notice');
        }
      },
      complete:function()
      {
        $("#waiting").hide();
      }
    });
  })
  //////////立即支付//////////////
  $('.paynow').click(function(){
    var whid=$(this).attr('data-whid');
    InWarehouseId=whid;
    var tprice=$(this).attr('data-tprice');
    if (tprice=='0.00') {
      tips('notice','金额为0不能支付',2000,'weui_icon_notice');
      return false;
    } else {
      $.ajax({
        url:paywh_url,
        type:"post",
        data:{whid:whid,tprice:tprice},
        dataType:"json",
        beforeSend:function(){
          tips('waiting','支付请求中...');
        },
        success:function(msg)
        {
          if (msg.status=='true')
          {
            paydata=msg.info;
            callpay();
          }
          else
          {
            tips('notice','支付请求失败',2000,'weui_icon_notice');
          }
        },
        complete:function()
        {
          $("#waiting").hide();
        }
      });
    }
  })
  //////////立即提交////////////
  $('.sendnow').click(function(){
    var _label=$(this);
    var whid=_label.attr('data-whid');
    $.ajax({
      url:sendwh_url,
      type:"post",
      data:{whid:whid},
      dataType:"json",
      beforeSend:function(){
        tips('waiting','订单提交中...');
      },
      success:function(msg)
      {
        if (msg.status=='true')
        {
          tips('notice','提交成功',2000);
          setTimeout(function(e){
            window.location.reload();
          },2000);
        }
        else
        {
          tips('notice','订单提交失败',2000,'weui_icon_notice');
        }
      },
      complete:function()
      {
        $("#waiting").hide();
      }
    });
  })
})
