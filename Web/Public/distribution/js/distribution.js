var gotime=120;
var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;
$(document).ready(function () {
  $('.part_idcard_img').css('height',$('.part_idcard_img').width()/8*5+'px');
  oFReader = new FileReader(),
  rFilter = /^(?:image\/jpeg|image\/png)$/i;
  oFReader.onload = function (oFREvent) {
    $(".head_img").attr("src",oFREvent.target.result);
    upuserimage();
  };

  IDoFReader = new FileReader(),
  IDrFilter = /^(?:image\/jpeg|image\/png)$/i;
  IDoFReader.onload = function (oFREvent) {
    $(".selidcard").attr("src",oFREvent.target.result);
    upidcardimg();
  };

  //////获取短信验证码///////
  $('.getcode').click(function(){
    var tel=$('.phone').val();
    if ($('.getcode').attr('data-s')=='1') {
      return;
    }
    if((tel.length!=11)||(!myreg.test(tel)))
    {
      tips('notice',"填写正确手机号",1500,'weui_icon_notice');
      return false;
    }
    tips('waiting',"发送中",15000);
    $('.getcode').attr('data-s','1');
    $.ajax({
      url:gotophnesms,
      type:"post",
      data:"tel="+tel,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice','发送成功，请等待接收短信',1500);
          $('#shphone').attr('readonly',true);
          settime();
        }else{
          $('.getcode').attr('data-s','0');
          if (msg.datainfo == 'phonehaserror') {
          tips('notice',"此手机号已经注册",1500,'weui_icon_notice');
          } else {
          tips('notice',"发送失败请稍后重试",1500,'weui_icon_notice');
          }
        }
      }
    })
  });
  ///////保存配送员信息///////////////
  $('.saveps').click(function(){
    if ($('#tximg').val()=='') {
      tips('notice',"上传头像",1500,'weui_icon_notice');
      return false;
    }
    if ($('#truename').val()=='') {
      tips('notice',"填写真实姓名",1500,'weui_icon_notice');
      return false;
    }
    if ($('.phone').val()=='') {
      tips('notice',"填写手机号码",1500,'weui_icon_notice');
      return false;
    }
    if ($('#pscode').val()=='') {
      tips('notice',"填写验证码",1500,'weui_icon_notice');
      return false;
    }
    if ($('#idcard').val()=='' || $('#idcard').val().length !=18 ) {
      tips('notice',"填写正确身份证号码",1500,'weui_icon_notice');
      return false;
    }
    if ($('#idcardimg').val()=='') {
      tips('notice',"上传身份证信息",1500,'weui_icon_notice');
      return false;
    }
    var savedata={
      'HeadImg':$('#tximg').val(),
      'TrueName':$('#truename').val(),
      'Phone':$('.phone').val(),
      'PhoneCode':$('#pscode').val(),
      'IdCard':$('#idcard').val(),
      'IdImg':$('#idcardimg').val(),
    };
    tips('waiting',"正在保存",15000);
    $.ajax({
      url:saveuser_url,
      type:"post",
      data:savedata,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice', '保存成功!', 1500, 'weui_icon_toast');
          setTimeout(function() {
            window.location.href=center_url;
          },1500)
        }else{
          if (msg.datainfo=='codeError') {
            tips('notice', '验证码错误!', 1500, 'weui_icon_notice');
          } else {
            tips('notice', '保存失败!', 1500, 'weui_icon_notice');
          }
        }
      }
    })
  })

})



//////选择头像上传图片/////
function seltxtp(ifile){
  if (ifile.files.length === 0){
    return;
  }
  var oFile = ifile.files[0];
  if (!rFilter.test(oFile.type)) {
    tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
    return;
  }else{
    oFReader.readAsDataURL(oFile);
  }
}

//////头像上传///////////////
function upuserimage(){
  tips('waiting', '正在加载...', 15000);
  $.ajaxFileUpload({
    url: userimg, //用于文件上传的服务器端请求地址
    secureuri: false, //是否需要安全协议，一般设置为false
    fileElementId: 'iptxtp', //文件上传域的ID
    dataType: 'json', //返回值类型 一般设置为json
    success: function (data, status)  //服务器成功响应处理函数
    {
      if (data.status=='true'){
        $("#tximg").val(data.datainfo);
        tips('notice', '上传成功!', 1500, 'weui_icon_toast');
      }else{
        $("#tximg").val('');
        tips('notice', '上传失败!', 1500, 'weui_icon_notice');
      }
    },
    complete: function (e) {
      hidetips('waiting');
    },
    error: function (data,status,e)//服务器响应失败处理函数
    {
      tips('notice', '上传失败!', 1500, 'weui_icon_notice');
    }
  })
}

//////选择身份证图片/////
function selidcard(ifile){
  if (ifile.files.length === 0){
    return;
  }
  var oFile = ifile.files[0];
  if (!IDrFilter.test(oFile.type)) {
    tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
    return;
  }else{
    IDoFReader.readAsDataURL(oFile);
  }
}
//////身份证上传///////////////
function upidcardimg(){
  tips('waiting', '正在加载...', 15000);
  $.ajaxFileUpload({
    url: useridcard, //用于文件上传的服务器端请求地址
    secureuri: false, //是否需要安全协议，一般设置为false
    fileElementId: 'idcards', //文件上传域的ID
    dataType: 'json', //返回值类型 一般设置为json
    success: function (data, status)  //服务器成功响应处理函数
    {
      if (data.status=='true'){
        $("#idcardimg").val(data.datainfo);
        tips('notice', '上传成功!', 1500, 'weui_icon_toast');
      }else{
        $("#idcardimg").val('');
        tips('notice', '上传失败!', 1500, 'weui_icon_notice');
      }
    },
    complete: function (e) {
      hidetips('waiting');
    },
    error: function (data,status,e)//服务器响应失败处理函数
    {
      tips('notice', '上传失败!', 1500, 'weui_icon_notice');
    }
  })
}
function settime(val) {
  if (gotime == 0) {
    $(".getcode").html('获取验证码');
    $('.getcode').attr('data-s','0');
    $('.phone').attr('readonly',false);
    gotime = 120;
    return;
  } else {
    $('.getcode').html("正在获取("+gotime+")");
    gotime--;
  }
  setTimeout(function() {
    settime(val)
  },1000)
}
