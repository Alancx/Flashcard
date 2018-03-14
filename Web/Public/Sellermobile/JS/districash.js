$(document).ready(function(){
  //////调整高度//////////////////
  $('.partcontents').css('height',$('.partdis').height()-$('.parttop').height()-10+'px');
  $('.discashlist').css('height',$('.partcontents').height()-$('.pctitle').outerHeight()+'px');

  $('.parttop>label').click(function(){
    if (!$(this).hasClass('seltopactive')) {
      var tid=$(this).attr('data-type');
      $('.seltopactive').removeClass('seltopactive');
      $(this).addClass('seltopactive');
      $('.discashlist>div').css('display','none');
      $('.discashlist>.districash_list_'+tid).css('display','block');
    }
  });
});
///////////////////////////////////////////
function agreeclick(span){
  var type='pass';
  var id=$(span).parent().attr('data-id');
  sendtype(type,id);
};
function refuseclick(span){
  var type='refund';
  var id=$(span).parent().attr('data-id');
  sendtype(type,id);
};

function sendtype(type,id){
  tips('waiting','正在处理···');
  $.ajax({
    type:"post",
    url:setpscheckurl,
    data:"type="+type+"&id="+id,
    dateType:"json",
    complete: function(e){
    hidetips('waiting');
    },
    success: function(msg){
        if (msg.status == 'true') {
          tips('notice', '处理完成!', 1500, 'weui_icon_toast');
          setTimeout(function(e){
            window.location.reload();
          },1000);
        } else{
          tips('notice', '处理失败!', 1500, 'weui_icon_notice');
        }
    },
  })
};
