var sharetextArray =[
  '这家<span class="shop_name"></span>的<span class="pro_name"></span>真的好吃，你们来吃的话比我还要便宜哦！光盘客，真划算，越吃越便宜！',
  '这是我吃过的最好吃的<span class="pro_name"></span>,<span class="shop_name"></span>说了，你们来吃的话比我还要便宜哦！光盘客，真划算，越吃越便宜！',
  '<span class="shop_name"></span>的<span class="pro_name"></span>确实好吃，你们来吃的话比我还要便宜哦！光盘客，真划算，越吃越便宜！',
];
$(document).ready(function(){
  // 打开选择商品
  $('.selprosinfo').click(function(){
    $('.prolistmark').css('display','block');
  });
  // 关闭并选择
  $('.pro_info').click(function(){
    if(!$(this).hasClass('hasselproactive')){
      $('.hasselproactive').removeClass('hasselproactive');
      $(this).addClass('hasselproactive');
      $('.proimg').html('<img src="'+$(this).attr('data-plogo')+'">');
      $('.proname').text($(this).attr('data-pname'));
      $('.sharetext>.pro_name').text($(this).attr('data-pname'));
      PID = $(this).attr('data-pid');
      if ($(this).attr('data-numtype')=='1') {
        $('.proprice').html(parseFloat($(this).attr('data-price')).toFixed(2)+'<span>/份</span>');
      } else {
        $('.proprice').html(parseFloat($(this).attr('data-price')).toFixed(2)+'<span>/斤</span>');
      }
    }
    $('.prolistmark').css('display','none');
  });
  $('.pro_info:first-child').click();
  // 点击换一句
  $('.changetext').click(function(){
    var shopname = $('.sharetext>.shop_name').text();
    var proname = $('.sharetext>.pro_name').text();
    var ctype =$(this).attr('data-type');
    switch (ctype) {
      case '0':
      $('.sharetext').html(sharetextArray[1]);
      $(this).attr('data-type','1');
      break;
      case '1':
      $('.sharetext').html(sharetextArray[2]);
      $(this).attr('data-type','2');
      break;
      case '2':
      $('.sharetext').html(sharetextArray[0]);
      $(this).attr('data-type','0');
      break;
    }
    $('.sharetext>.shop_name').text(shopname);
    $('.sharetext>.pro_name').text(proname);
  });
  // 点击立即分享
  $('.bottombtn').click(function(){
    if ($.trim($('.pro_name').text()) !='') {
      // 分享信息
      sharetitle= $('.pro_name').text();
      sharedesc= $.trim($('.sharetext').text());
      sharelink= window.location.protocol + '//' + window.location.host+'/index.php/Home/Goods/goods/?pid='+PID+ '&SID=' + SID+'&stoken='+sharestoken+'&once=1';
      shareimgUrl= window.location.protocol + '//' + window.location.host + $('.proimg>img').attr('src');
      setwxshareinfo();
      $('.sharemark').css('display','block');
    } else {
      mui.toast('请选择分享商品');
    }
  });
})
