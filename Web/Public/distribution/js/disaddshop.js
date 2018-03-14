$(document).ready(function(){
  $('.shoptype').click(function(){
    if (!$(this).hasClass('selshopactive')) {
      var type=$(this).attr('data-type');
      $('.selshopactive').removeClass('selshopactive');
      $(this).addClass('selshopactive');
      if (type=='0') {
        $('.noselect').css('display','block');
        $('.hasselect').css('display','none');
      } else {
        $('.noselect').css('display','none');
        $('.hasselect').css('display','block');
      }
    }
  })
  $('.shopinfo').click(function(){
    if ($(this).hasClass('selshop')) {
      $(this).removeClass('selshop');
    } else {
      $(this).addClass('selshop');
    }
    $('.saveselshop').text('提交('+$('.selshop').length+')')
  });
  $('.saveselshop').click(function(){
    if ($('.selshop').length==0) {
      tips('notice',"请选择门店",1500,'weui_icon_notice');
    } else {
      var sidarry={};
      $('.selshop').each(function(index,item){
        sidarry[$(item).attr('data-sid')]=$(item).attr('data-stoken');
      });
      tips('waiting',"正在提交信息",15000);
      $.ajax({
        url:savesidlist,
        type:"post",
        data:"sidlist="+JSON.stringify(sidarry),
        dataType:"json",
        success:function(msg){
          hidetips('waiting');
          if (msg.status=='true') {
            tips('notice','提交成功',1500);
            setTimeout(function() {
              window.location.href=center_url;
            },1500)
          }else{
            tips('notice',"提交失败",1500,'weui_icon_notice');
          }
        }
      })
    }
  });
})
