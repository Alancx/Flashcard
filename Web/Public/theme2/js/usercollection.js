$(document).ready(function () {
    $('#esc').click(function () {
        $('#confirm').hide()
    });
    $('#enter').click(function () {
        $('#confirm').hide();
        $.ajax({
            type: "post",
            url: ubill,
            data: 'pid=' + $("#enter").attr("data-s"),
            dataType: "json",
            success: function (msg) {
                if (msg.status=='true') {
                    $("#" + $("#enter").attr("data-s")).remove();
                    $("#enter").attr("data-s", "");
                    tips('notice', '删除成功!', 1500, 'weui_icon_toast');
                } else {
                    tips('notice', '删除失败!', 1500, 'weui_icon_notice');
                }
            }
        })
    })
    if (ishasorder()=='true') {
      $('.isshow').css('display','none');
      $('body').css('background-color','#f3f3f3');
    } else {
      $('.isshow').css('display','block');
      $('body').css('background-color','#f6f6f6');
    }
})
function ishasorder(){
  var hasorder='false';
  $(".collectionpart").each(function (index, item) {
      if ($(item).css('display')!='none') {
        hasorder='true';
      }
  })
  return hasorder;
}
function delcollect(span){
    event.preventDefault();
    $("#enter").attr("data-s", $(span).attr("proid"));
    tips('confirm', '确定要删除此收藏吗？');
}
