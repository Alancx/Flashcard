var gotime=120;
var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;
$(document).ready(function () {
  $('.getcode').click(function(){
    var tel=$("input[name='phone']").val();
    if ($(this).attr('data-s')=='1') {
      return false;
    }
    if (tel=='') {
      tips('notice', '填写手机号!', 1500, 'weui_icon_notice');
      return;
    }
    if((tel.length!=11)||(!myreg.test(tel))){
      tips('notice', '填写正确手机号格式!', 1500, 'weui_icon_notice');
      return;
    }
    tips('waiting',"发送中");
    $.ajax({
      url:gotophnesms,
      type:"post",
      data:"tel="+tel,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice','发送成功，请等待接收短信',2000);
          $("input[name='phone']").attr('readonly',true);
          settime();
        }else{
          $('.getcode').attr('data-s','0');
          if(msg.datainfo='phoneError'){
            tips('notice',"此手机号已绑定,无法再次绑定",2500,'weui_icon_notice');
          } else {
            tips('notice',"发送失败请稍后重试",2500,'weui_icon_notice');
          }
        }
      }
    })
  });
  $('.savephone').click(function(){
    var tel=$("input[name='phone']").val();
    var code=$("input[name='phonecode']").val();
    if(code==''){
      tips('notice',"填写验证码",2500,'weui_icon_notice');
      return false;
    }
    tips('waiting',"保存中");
    $.ajax({
      url:savephone,
      type:"post",
      data:"tel="+tel+"&smscode="+code,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice','保存成功',2000);
          setTimeout(function() {
            window.location.href=gouserindex;
          },1000);
        }else{
          if (msg.datainfo=='phonecode') {
            tips('notice',"验证码错误",2000,'weui_icon_notice');
          } else if(msg.datainfo=='phoneError'){
            tips('notice',"此手机号已绑定,无法再次绑定",2500,'weui_icon_notice');
          } else {
            tips('notice',"保存失败",2000,'weui_icon_notice');
          }
        }
      }
    })
  });
})
function settime() {
  if (gotime == 0) {
    $(".getcode").html('获取验证码');
    $('.getcode').attr('data-s','0');
    $("input[name='phone']").attr('readonly',false);
    gotime = 120;
    return;
  } else {
    $('.getcode').html("正在获取("+gotime+")");
    gotime--;
  }
  setTimeout(function() {
    settime()
  },1000)
}
