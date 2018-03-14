$(document).ready(function(){
  $('.setdelete').on('tap',function(){
    var _this = $(this);
    var mcid = _this.attr('data-mcid');
    showwaiting('正在取消...');
    $.ajax({
      url:setshopcollect_url,
      type:"post",
      data:{'mcid':mcid},
      dataType:"json",
      complete:function(){
        closeWaiting();
      },
      success:function(msg){
        if (msg['status'] == 'true') {
        _this.parents('.mui-media').remove();
        }
      },
      error:function(e){

      }
    });
  })
})
