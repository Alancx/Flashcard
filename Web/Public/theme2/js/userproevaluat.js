$(document).ready(function () {
    $(".proevalxj>div>span").click(function(){
       if($(this).hasClass("sp")){
           $(this).prevAll(".sp").addClass("selxj");
           $(this).addClass("selxj");
           $(this).nextAll(".sp").removeClass("selxj");
           $(".sppf").val($(this).attr("dataxj"));
       } else if($(this).hasClass("fw")){
           $(this).prevAll(".fw").addClass("selxj");
           $(this).addClass("selxj");
           $(this).nextAll(".fw").removeClass("selxj");
           $(".fwpf").val($(this).attr("dataxj"));
       }else if($(this).hasClass("wl")){
           $(this).prevAll(".wl").addClass("selxj");
           $(this).addClass("selxj");
           $(this).nextAll(".wl").removeClass("selxj");
           $(".wlpf").val($(this).attr("dataxj"));
       }
    })

    $('.evaltjpl').click(function(){
      var content=$('.porevalpy').val();
      var server=$('.fwpf').val();
      var product=$('.sppf').val();
      var logistic=$('.wlpf').val();
      var oid=$('#oid').val();
      var olid=$('#olid').val();
      var pid=$('#pid').val();
      if (!content) {
        tips('notice','请填写评价内容',1500,'weui_icon_notice');
        return false;
      };
      if (!product) {
        tips('notice','请对商品评分',1500,'weui_icon_notice');
        return false;
      };
      if (!server) {
        tips('notice','请对服务评分',1500,'weui_icon_notice');
        return false;
      };
      if (!logistic) {
        tips('notice','请对物流评分',1500,'weui_icon_notice');
        return false;
      }else{
        tips('waiting','正在提交数据...');
        $.ajax({
          type:"post",
          url:savepj,
          data:"content="+content+"&server="+server+"&product="+product+"&logistic="+logistic+"&pid="+pid+"&oid="+oid+"&olid="+olid,
          dataType:"json",
          success:function(msg){
            $('#waiting').hide();
            if (msg.status=='true') {
              tips('notice','评价成功',1500);
              window.location.href=toOrder;
            };
            if (msg.status=='false') {
              tips('notice','评价失败',1500,'weui_icon_notice');
              console.log(msg.info);
            }
          }
        })
      }
    })
})

function openpjnr(span){
   // window.event.stopPropagation();
   // window.event.cancelBubble=true;
  if (!$(".moplnr").hasClass("open")){
    $(".moplnr").css("display","block");
    $(".moplnr").addClass("open");
    $('.tblx').css("-moz-transform","rotate(90deg)");
    $('.tblx').css("-webkit-transform","rotate(90deg)");
    $('.tblx').css("transform","rotate(90deg)");
  } else{
    $(".moplnr").css("display","none");
    $(".moplnr").removeClass("open");
    $('.tblx').css("-moz-transform","rotate(0deg)");
    $('.tblx').css("-webkit-transform","rotate(0deg)");
    $('.tblx').css("transform","rotate(0deg)");
  }
}

function selpjnr(label){
$(".porevalpy").val($(label).attr("datanr"));
$(".moplnr").css("display","none");
$(".moplnr").removeClass("open");
$(".tblx").css("-moz-transform","rotate(0deg)");
$(".tblx").css("-webkit-transform","rotate(0deg)");
$(".tblx").css("transform","rotate(0deg)");
}
