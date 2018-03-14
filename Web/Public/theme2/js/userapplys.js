var gotime=120;
var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;
$(document).ready(function () {

  oFReader = new FileReader(),
  rFilter = /^(?:image\/jpeg|image\/png)$/i;

  oFReader.onload = function (oFREvent) {
    var cheight;
    $("#usphoto").attr("src",oFREvent.target.result);
    qdidcard();
  };

  ///展开和关闭事件
  $('.selsmap').click(function(){
    if($(this).next().css('display')=='none'){
      $(this).next().css('display','block');
      $('>span',this).css('background-image',"url('" + imgurl + "Arrow-2.png')");
      $('>span',this).css('background-size','13px');
      setTimeout(function(){
        wxmap.panTo(new qq.maps.LatLng(gelat, gelng));
      },100);
    } else{
      $(this).next().css('display','none');
      $('>span',this).css('background-image',"url('" + imgurl + "Arrow-1.png')");
      $('>span',this).css('background-size','7px');
    }

  })
  //////获取短信验证码///////
  $('.getcode').click(function(){
    var tel=$("#shphone").val();
    if ($('.getcode').attr('data-s')=='1') {
      return;
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
    $('.getcode').attr('data-s','1');
    $.ajax({
      url:getsmscode,
      type:"post",
      data:"tel="+tel,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice','发送成功，请等待接收短信',2000);
          $("#shphone").attr('readonly',true);
          settime();
        }else{
          $('.getcode').attr('data-s','0');
          if (msg.info=='hasphoneError') {
          tips('notice',"此手机号已申请过开店,无法再次申请",2500,'weui_icon_notice');
        } else {
        tips('notice',"发送失败请稍后重试",2500,'weui_icon_notice');
        }
        }
      }
    })
  });
})


//////选择上传图片/////
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

//////确定选择idcard///////////////
function qdidcard(){
  tips('waiting', '正在加载...', 15000);
  $.ajaxFileUpload({
    url: useridcard, //用于文件上传的服务器端请求地址
    secureuri: false, //是否需要安全协议，一般设置为false
    fileElementId: 'iptxtp', //文件上传域的ID
    dataType: 'json', //返回值类型 一般设置为json
    success: function (data, status)  //服务器成功响应处理函数
    {
      if (data.status=='true'){
        $("#idcard").val(data.datainfo);
        tips('notice', '上传成功!', 1500, 'weui_icon_toast');
      }else{
        $("#idcard").val('');
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
function settime() {
  if (gotime == 0) {
    $(".getcode").html('获取验证码');
    $('.getcode').attr('data-s','0');
    $("#shphone").attr('readonly',false);
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
/////确定/////////////
function sureclick(view){
  if ($("#shuname").val()==''){
    tips('notice', '填写真实姓名!', 1500, 'weui_icon_notice');
    return;
  }else if($("#shphone").val()==''){
    tips('notice', '填写手机号!', 1500, 'weui_icon_notice');
    return;
  }else if(($("#shphone").val().length!=11)||(!myreg.test($("#shphone").val()))){
    tips('notice', '填写正确手机号格式!', 1500, 'weui_icon_notice');
    return;
  }else if ($('#shcode').val()=='') {
    tips('notice', '填写验证码!', 1500, 'weui_icon_notice');
    return;
  } else if(($("#shidcard").val()=='')||($("#shidcard").val().length!=18)){
    tips('notice', '填写正确的身份证号!', 1500, 'weui_icon_notice');
    return;
  }else if($("#shsname").val()==''){
    tips('notice', '填写商铺名称!', 1500, 'weui_icon_notice');
    return;
  }else if($("#shsaddr").val()==''){
    tips('notice', '填写商铺地址!', 1500, 'weui_icon_notice');
    return;
  }else if($("#idcard").val()==''){
    tips('notice', '上传证件照片!', 1500, 'weui_icon_notice');
    return;
  } else if(($("#pointsX").val()=='')||($("#pointsY").val()=='')){
    tips('notice', '选择商铺坐标!', 1500, 'weui_icon_notice');
    return;
  }
  var dtype = $(view).attr('data-ty');
  var datashop='';
  var htmls='';
  datashop='shuname='+$("#shuname").val()+'&shphone='+$("#shphone").val()+'&smscode='+$('#shcode').val()+
  '&shsname='+$("#shsname").val()+'&shsaddr='+$("#shsaddr").val()+
  '&idcard='+$("#idcard").val()+'&shidcard='+$("#shidcard").val()+
  '&province='+$("#s_province").text()+'&city='+$("#s_city").text()+
  '&area='+$("#s_county").text()+'&spx='+$("#pointsX").val()+'&spy='+$("#pointsY").val();
  if (dtype=='add'){
    datashop+='&dtype=add';
  }else{
    datashop+='&dtype=update';
  }
  if (datashop==''){
    return;
  }
  tips('waiting', '正在加载...', 15000);
  $.ajax({
    type: "post",
    url: usersinfo,
    data: datashop,
    dataType: "json",
    complete: function (e) {
      hidetips('waiting');
    },
    success: function (msg) {
      if (msg.status == 'true') {
        if(msg.datainfo == 'xgSuccess'){
          // htmls='<button type="button" class="btn btn-warning ubtjxx" data-ty="update" onclick="sureclick(this)">修改资料</button>'+
          // '<label class="infomeg">审核通过!</label>'+
          // '<label>立即免费开店，手机随时管理</label>';
          htmls='<label class="infomeg">正在审核...</label>'+
          '<label>立即免费开店，手机随时管理</label>';
        } else{
          htmls='<label class="infomeg">正在审核...</label>'+
          '<label>立即免费开店，手机随时管理</label>';
        }
        $(".surebtn").html(htmls);
        tips('notice', '保存成功!', 1500, 'weui_icon_toast');
      } else {
        if (msg.datainfo=='phoneError') {
          tips('notice',"此手机号已申请过开店,无法再次申请",2500,'weui_icon_notice');
        } else {
        tips('notice', '保存失败!', 1500, 'weui_icon_notice');  
        }
      }
    }
  })

}
