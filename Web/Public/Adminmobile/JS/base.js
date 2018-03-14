function tips(type,msg,time,icon){
  var time=time||10000;
  var icon=icon||'weui_icon_toast';
  //提示图标样式 toast为 √  nitoce为 ！
  if (type=='notice') {
    $(".weui_toast_content").html(msg);
    $("#"+type+" i").attr('class',icon);
  }else if (type=='confirm') {
    $(".weui_dialog_bd").html(msg);
  }else if (type=='alert') {
    $(".weui_dialog_bd").html(msg);
  }else if (type=='waiting') {
    $(".weui_toast_content").html(msg);
  }
  $("#"+type).show();
  setTimeout('hidetips("'+type+'")',time);
}
function hidetips(type){
  $('#'+type).hide();
}
