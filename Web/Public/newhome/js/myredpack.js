$(document).ready(function(){
  //使用/未使用
  $(".navt li").on("tap",function(){
    $(this).addClass("checkli").siblings().removeClass("checkli");
    var i=$(this).index();
    $("[data-oo='item']").fadeOut(0).eq(i).fadeIn(0);
  })
})
