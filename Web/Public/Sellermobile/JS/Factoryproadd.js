$(document).ready(function(){
  $('.saveselpro').click(function(){
    var proid=$(this).attr('data-proid');
    var res=true;
    var attrdata={};
    $('.setattr_price').each(function(index,item){
      var proidcode=$(item).attr('data-proidcode');
      var setprice=$(item).children('input').val();
      if ($.trim(setprice)=='') {
        res=false;
        return false;
      } else {
        var senditem={};
        senditem['proidcode']=proidcode;
        senditem['setprice']=setprice;
        attrdata[index]=senditem;
      }
    })
    if (res==false) {
      tips('notice', '填写完整!', 1500, 'weui_icon_notice');
      return false;
    }
    var senddata={
      'proid':proid,
      'attrdata':attrdata,
      'type':factype,
    }
    tips('waiting','正在保存···');
    $.ajax({
      url:savefactorypro_url,
      type:"post",
      data:senddata,
      dataType:"json",
      success:function(msg){
        if (msg.status=='true') {
          tips('notice', '保存成功!', 1500, 'weui_icon_toast');
          setTimeout(function(e){
            window.location.href=factorypro_url;
          },1500);
        } else{
          tips('notice', '保存失败!', 1500, 'weui_icon_notice');
        }
      },
      complete: function (e) {
        hidetips('waiting');
      },
      error: function (data,status,e)//服务器响应失败处理函数
      {
        //////
      }
    })
  })
})
