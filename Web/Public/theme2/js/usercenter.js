var addressIdS = null;
$(document).ready(function () {
  ///////////////////////////
  oFReader = new FileReader(),
  rFilter = /^(?:image\/jpeg|image\/png)$/i;

  oFReader.onload = function (oFREvent) {
    var cheight;
    $("#txxg").attr("src",oFREvent.target.result);
    cheight=$(document).height()-$('#txxg').height()-110;
    cheight=cheight/2;
    $('#txxg').css("margin-top", cheight+'px');
    // if ($('#txxg').height()>$(document).height()-45){
    //     $('#txxg').css("margin-top", "0px");
    //     $('#txxg').css("width", "auto");
    //     $('#txxg').height($(document).height()-45);
    // } else{
    //   cheight=$(document).height()-$('#txxg').height()-45;
    //   cheight=cheight/2;
    //   $('#txxg').css("margin-top", cheight+'px');
    // }

  };
  //////////////更新用户消息///////////////
  $('.synchro').click(function(){
    if($(this).hasClass('guanzhugzh')){
      $('.subscribe').click();
    } else {
      tips('waiting',"更新中",'weui_icon_notice');
      $('.getcode').attr('data-s','1');
      $.ajax({
        url:synchro_url,
        type:"post",
        data:{},
        dataType:"json",
        success:function(msg){
          hidetips('waiting');
          if (msg.status=='true') {
            tips('notice','更新完成',2000);
            setTimeout(function(){
              window.location.reload();
            },1500);
          }else{
            tips('notice',"更新失败",2000,'weui_icon_notice');
          }
        }
      })
    }
  })
  //////////////////////////////
  $('.register').click(function(){window.location.href=goregister})
  addressIdS = pList("addressPCD", "addressSelect");
  $('#esc').click(function () {
    $('#confirm').hide()
  });
  $('#enter').click(function () {
    $('#confirm').hide();
    $.ajax({
      type: "get",
      url: cdeladdr,
      data: 'id=' + $("#enter").attr("data-s"),
      dataType: "json",
      success: function (msg) {
        if (msg.status == 'true') {
          $("#" + $("#enter").attr("data-s")).remove();
          $("#enter").attr("data-s", "");
          tips('notice', '删除成功!', 1500, 'weui_icon_toast');
        } else {
          tips('notice', '删除失败!', 1500, 'weui_icon_notice');
        }
      }
    })
  })
})

///////头像修改///////
function updatetxxg(label){
  var cheight;
  $("#txxg").attr("src",$(".usercentertx").attr("src"));
  $("body").css("overflow", "hidden");
  $(".couverxgtx").css("display", "block");
  cheight=$(document).height()-$('#txxg').height()-110;
  cheight=cheight/2;
  $('#txxg').css("margin-top", cheight+'px');
}
//////确定选择头像///////////////
function qdtxxg(label){
  if($('#iptxtp').val() == ''){
    tips('notice', '请选择图片!', 1500, 'weui_icon_notice');
    return;
  }
  tips('waiting', '正在加载...', 15000);
  $.ajaxFileUpload({
    url: userimg, //用于文件上传的服务器端请求地址
    secureuri: false, //是否需要安全协议，一般设置为false
    fileElementId: 'iptxtp', //文件上传域的ID
    dataType: 'json', //返回值类型 一般设置为json
    success: function (data, status)  //服务器成功响应处理函数
    {
      if (data.status=='true'){
        $(".usercentertx").attr("src",data.datainfo);
        $("body").css("overflow", "auto");
        $(".couverxgtx").css("display", "none");
        tips('notice', '头像修改成功!', 1500, 'weui_icon_toast');
      }else{
        tips('notice', '头像修改失败!', 1500, 'weui_icon_notice');
      }
    },
    complete: function (e) {
      hidetips('waiting');
    },
    error: function (data,status,e)//服务器响应失败处理函数
    {
      tips('notice', '头像修改失败!', 1500, 'weui_icon_notice');
    }
  })

}
////关闭头像修改//////
function gbtxxg(label){
  $("body").css("overflow", "auto");
  $(".couverxgtx").css("display", "none");
}
//////选择头像图片/////
function seltxtp(ifile){
  if (ifile.files.length === 0){
    return;
  }
  var oFile = ifile.files[0];
  if (!rFilter.test(oFile.type)) {
    alert("请选择正确的图片");
    return;
  }else{
    oFReader.readAsDataURL(oFile);
  }
}
//////昵称修改//////
function nickupdate(label) {
  $("body").css("overflow", "hidden");
  $(".covernick").css("display", "block");
}
//////昵称返回//////
function nicknameback(label) {
  $("body").css("overflow", "auto");
  $(".covernick").css("display", "none");
}
//////昵称修改//////
function nicknamesave(label) {
  if ($(".nickname").val()) {
    $.ajax({
      type: "post",
      url: cname,
      data: "newname=" + $(".nickname").val(),
      dataType: "json",
      success: function (msg) {
        if (msg.status == 'true') {
          $(".nicknamesets>span").text($(".nickname").val());
          tips('notice', '修改成功!', 1500, 'weui_icon_toast');
        } else {
          tips('notice', '修改失败!', 1500, 'weui_icon_notice');
        }
      }
    })
  }
  $("body").css("overflow", "auto");
  $(".covernick").css("display", "none");
}

///////性别选择//////
function sexupdate(label) {
  $("body").css("overflow", "hidden");
  $(".coversex").css("display", "block");
}

//////性别选择完成//////
function sexsure(img) {
  if ($(img).hasClass("sexwomen")) {
    $.ajax({
      type: "post",
      url: csex,
      data: "sex=2",
      dataType: "json",
      success: function (msg) {
        if (msg.status == 'true') {
          $(".sexset>span").text("女");
          tips('notice', '修改成功!', 1500, 'weui_icon_toast');
        } else {
          tips('notice', '修改失败!', 1500, 'weui_icon_notice');
        }
      }
    })
  } else {
    $.ajax({
      type: "post",
      url: csex,
      data: "sex=1",
      dataType: "json",
      success: function (msg) {
        if (msg.status == 'true') {
          $(".sexset>span").text("男");
          tips('notice', '修改成功!', 1500, 'weui_icon_toast');
        } else {
          tips('notice', '修改失败!', 1500, 'weui_icon_notice');
        }
      }
    })
  }
  $("body").css("overflow", "auto");
  $(".coversex").css("display", "none");
}
///////地区选择/////
function selregion(label) {
  $(".coverregion").css("display", "block");
  $("#" + addressIdS[2]).css("display", "inline-block");
  $("#addressPCD > select").css("margin", "0px 5% 0px 5%");
}

//////当前城市选择//////
function seatupdate(label) {
  $("#" + addressIdS[2]).css("display", "none");
  $("body").css("overflow", "hidden");
  $(".coverregion").css("display", "block");
  $("#addressPCD > select").css("margin", "0px 5% 0px 15%");
}
///////关闭当前城市选择/////
function gbselregion(label) {
  if ($("#" + addressIdS[2]).css("display") == "none") {
    $("body").css("overflow", "auto");
    $(".coverregion").css("display", "none");
  } else {
    $(".coverregion").css("display", "none");
  }
}
///////完成当前城市选择/////
function wcselregion(label) {
  var strregion;
  if ($("#" + addressIdS[2]).css("display") == "none") {
    if ($("#" + addressIdS[0]).val() != "请选择") {
      strregion = $("#" + addressIdS[0]).val();
    } else {
      tips('notice', '选择省份!', 1500, 'weui_icon_notice');
      return false;
    }

    if ($("#" + addressIdS[1]).val() != "请选择") {
      strregion = strregion + '-' + $("#" + addressIdS[1]).val();
    }
    else {
      tips('notice', '选择城市!', 1500, 'weui_icon_notice');
      return false;
    }
    $.ajax({
      type: "post",
      url: carea,
      data: "province=" + $("#" + addressIdS[0]).val() + '&city=' + $("#" + addressIdS[1]).val(),
      dataType: "json",
      success: function (msg) {
        if (msg.status == 'true') {
          $(".cityset>span").text(strregion);
          tips('notice', '修改成功!', 1500, 'weui_icon_toast');
        } else {
          tips('notice', '修改失败!', 1500, 'weui_icon_notice');
        }
      }
    })
    $("body").css("overflow", "auto");
    $(".coverregion").css("display", "none");
  } else {
    if ($("#" + addressIdS[0]).val() != "请选择") {
      strregion = $("#" + addressIdS[0]).val();
      $(".shshregions").attr("data-province", $("#" + addressIdS[0]).val());
    } else {
      tips('notice', '选择省份!', 1500, 'weui_icon_notice');
      return false;
    }

    if ($("#" + addressIdS[1]).val() != "请选择") {
      strregion = strregion + $("#" + addressIdS[1]).val();
      $(".shshregions").attr("data-city", $("#" + addressIdS[1]).val());
    } else {
      tips('notice', '选择城市!', 1500, 'weui_icon_notice');
      return false;
    }

    if ($("#" + addressIdS[2]).val() != "请选择") {
      strregion = strregion + $("#" + addressIdS[2]).val();
      $(".shshregions").attr("data-area", $("#" + addressIdS[2]).val());
    } else {
      tips('notice', '选择地区!', 1500, 'weui_icon_notice');
      return false;
    }
    $(".shshregions").text(strregion);
    $(".coverregion").css("display", "none");
  }
}
///////收货地址/////
function addrupdate(label) {
  $(".addrselect").html("");
  $("body").css("overflow", "hidden");
  $(".coversel").css("display", "block");
  tips('waiting', '正在加载...', 15000);
  $.ajax({
    type: "post",
    url: caddr,
    data: "type=2",
    dataType: "json",
    complete: function (e) {
      hidetips('waiting');
    },
    success: function (msg) {
      if (msg.status == 'true') {
        var htmls = '';
        var datas = msg.addrs;
        for (var key in datas) {
          if ((datas[key].IsDefault) == 1) {
            htmls += '<div id=' + datas[key].ReceivingId + ' class="addr-box">' +
            '<label class="addrname">' + datas[key].Name + '</label>' +
            '<label class="addraddr" data-province=' + datas[key].Province +
            ' data-city=' + datas[key].City + ' data-area=' + datas[key].Area + ' data-address=' + datas[key].Address +
            '>' + datas[key].Province + datas[key].City + datas[key].Area + datas[key].Address + '</label>' +
            '<label class="addrphone">' + datas[key].Phone + '</label>' +
            '<div class="addrsedit">' +
            '<label class="addrdefault schecked">默认地址</label>' +
            '<label class="addrdel" addrsID=' + datas[key].ReceivingId + ' onclick="addressdel(this)">删除</label>' +
            ' <label class="addredit" addrsID=' + datas[key].ReceivingId + ' onclick="addressedit(this)">编辑</label>' +
            '</div></div> ';
          } else {
            htmls += '<div id=' + datas[key].ReceivingId + ' class="addr-box">' +
            '<label class="addrname">' + datas[key].Name + '</label>' +
            '<label class="addraddr" data-province=' + datas[key].Province +
            ' data-city=' + datas[key].City + ' data-area=' + datas[key].Area + ' data-address=' + datas[key].Address +
            '>' + datas[key].Province + datas[key].City + datas[key].Area + datas[key].Address + '</label>' +
            '<label class="addrphone">' + datas[key].Phone + '</label>' +
            '<div class="addrsedit">' +
            '<label class="addrdefault">默认地址</label>' +
            '<label class="addrdel" addrsID=' + datas[key].ReceivingId + ' onclick="addressdel(this)">删除</label>' +
            ' <label class="addredit" addrsID=' + datas[key].ReceivingId + ' onclick="addressedit(this)">编辑</label>' +
            '</div></div> ';
          }
        }
        htmls += '<div style="height: 55px"></div>';
        $(".addrselect").html(htmls);
        tips('notice', '加载完成!', 1500, 'weui_icon_toast');
      } else {
        tips('notice', '暂无添加地址!', 1500, 'weui_icon_notice');
      }
    }
  })
}
///////收货地址返回/////
function csback(label) {
  $("body").css("overflow", "auto");
  $(".coversel").css("display", "none");

}
////新增地址///////
function addressadd(label) {
  $(".coversel").css("display", "none");
  $(".coveredit").css("display", "block");
  $(".coveredit").addClass("add");
  $(".coveredit").attr("addrsID", "");
  $(".shnames").val("");
  $(".shphones").val("");
  $(".shshregions").text("");
  $(".shshregions").attr("data-province", "");
  $(".shshregions").attr("data-city", "");
  $(".shshregions").attr("data-area", "");
  $(".xxaddr").val("");
  $("#checkbox_c1").attr("checked", false);
}
/////删除地址//////
function addressdel(label) {
  $("#enter").attr("data-s", $(label).attr("addrsID"));
  tips('confirm', '确定要删除此收货地址吗？');
}
////修改地址///////
function addressedit(label) {
  $(".coversel").css("display", "none");
  $(".coveredit").css("display", "block");
  $(".coveredit").addClass("update");
  $(".coveredit").attr("addrsID", $(label).attr("addrsID"));
  $(".shnames").val(($(label).parent().parent().children(".addrname")).text());
  $(".shphones").val(($(label).parent().parent().children(".addrphone")).text());
  $(".shshregions").text(($(label).parent().parent().children(".addraddr")).attr("data-province") +
  ($(label).parent().parent().children(".addraddr")).attr("data-city") +
  ($(label).parent().parent().children(".addraddr")).attr("data-area"));
  $(".shshregions").attr("data-province", ($(label).parent().parent().children(".addraddr")).attr("data-province"));
  $(".shshregions").attr("data-city", ($(label).parent().parent().children(".addraddr")).attr("data-city"));
  $(".shshregions").attr("data-area", ($(label).parent().parent().children(".addraddr")).attr("data-area"));
  $(".xxaddr").val(($(label).parent().parent().children(".addraddr")).attr("data-address"));
  if (($(label).parent().children(".addrdefault")).hasClass("schecked")) {
    $("#checkbox_c1").prop("checked", true);
  } else {
    $("#checkbox_c1").prop("checked", false);
  }
  window.event.stopPropagation();
  window.event.cancelBubble = true;
}
///////地址编辑返回/////
function ceback(label) {
  $(".coversel").css("display", "block");
  $(".coveredit").css("display", "none");
  $(".coveredit").attr("class", "coveredit");
  $(".coveredit").attr("addrsID", "");

}
//////地址保存///////
function addresssave(label) {
  if ($(".shnames").val() == "") {
    tips('notice', '请填写收货人!', 1500, 'weui_icon_notice');
    return false;
  }
  if ($(".shphones").val() == "") {
    tips('notice', '请填写收货人电话!', 1500, 'weui_icon_notice');
    return false;
  }
  if ($(".shshregions").text() == "") {
    tips('notice', '请选择发货地址!', 1500, 'weui_icon_notice');
    return false;
  }
  if ($(".xxaddr").val() == "") {
    tips('notice', '请填写详细地址!', 1500, 'weui_icon_notice');
    return false;
  }
  var addrdata = '';
  var addrid = '';
  var htmls = '';
  addrdata = 'name=' + $(".shnames").val() + '&phone=' + $(".shphones").val() +
  '&province=' + $(".shshregions").attr('data-province') +
  '&city=' + $(".shshregions").attr('data-city') +
  '&area=' + $(".shshregions").attr('data-area') +
  '&address=' + $(".xxaddr").val();
  if ($("#checkbox_c1").prop("checked")) {
    addrdata += '&default=checked';
  }
  if ($(".coveredit").hasClass("update")) {
    addrid = $(".coveredit").attr("addrsID");
    addrdata += '&addrsid=' + $(".coveredit").attr("addrsID");
  }
  $.ajax({
    type: "post",
    url: ceditaddr,
    data: addrdata,
    dataType: "json",
    success: function (msg) {
      if (msg.status == 'true') {
        if ($(".coveredit").hasClass("update")) {
          $("#" + addrid + ">.addrname").text($(".shnames").val());
          $("#" + addrid + ">.addrphone").text($(".shphones").val());
          $("#" + addrid + ">.addraddr").text($(".shshregions").attr('data-province') + $(".shshregions").attr('data-city') +
          $(".shshregions").attr('data-area') + $(".xxaddr").val());
          $("#" + addrid + ">.addraddr").attr("data-province", $(".shshregions").attr('data-province'));
          $("#" + addrid + ">.addraddr").attr("data-city", $(".shshregions").attr('data-city'));
          $("#" + addrid + ">.addraddr").attr("data-area", $(".shshregions").attr('data-area'));
          $("#" + addrid + ">.addraddr").attr("data-address", $(".xxaddr").val());
          if ($("#checkbox_c1").prop("checked")) {
            $(".addrdefault").attr("class", "addrdefault");
            $("#" + addrid + ">.addrsedit>.addrdefault").addClass("schecked");
          }
        } else {
          htmls += '<div id=' + msg['info'] + ' class="addr-box">' +
          '<label class="addrname">' + $(".shnames").val() + '</label>' +
          '<label class="addraddr" data-province=' + $(".shshregions").attr('data-province') +
          ' data-city=' + $(".shshregions").attr('data-city') + ' data-area=' + $(".shshregions").attr('data-area') + ' data-address=' + $(".shphones").val() +
          '>' + $(".shshregions").attr('data-province') + $(".shshregions").attr('data-city') + $(".shshregions").attr('data-area') + $(".xxaddr").val() + '</label>' +
          '<label class="addrphone">' + $(".shphones").val() + '</label>' +
          '<div class="addrsedit">' +
          '<label class="addrdefault">默认地址</label>' +
          '<label class="addrdel" addrsID=' + msg['info'] + ' onclick="addressdel(this)">删除</label>' +
          ' <label class="addredit" addrsID=' + msg['info'] + ' onclick="addressedit(this)">编辑</label>' +
          '</div></div> ';
          $(".addrselect").prepend(htmls);
          if ($("#checkbox_c1").prop("checked")) {
            $(".addrdefault").attr("class", "addrdefault");
            $("#" + msg['info'] + ">.addrsedit>.addrdefault").addClass("schecked");
          }
        }
        tips('notice', '保存成功!', 1500, 'weui_icon_toast');
      } else {
        tips('notice', '保存失败!', 1500, 'weui_icon_notice');
      }
      $(".coversel").css("display", "block");
      $(".coveredit").css("display", "none");
      $(".coveredit").attr("class", "coveredit");
      $(".coveredit").attr("addrsID", "");
    }
  })
}
////////修改密码//////
function passwordupdate(label) {
  $("body").css("overflow", "hidden");
  $(".coverpassword").css("display", "block");
}////////修改密码返回//////
function passwordback(label) {
  $("body").css("overflow", "auto");
  $(".coverpassword").css("display", "none");
}
////////修改密码保存//////
function passwordsave(label) {
  var opass = $(".oldpassword").val();
  var pass = $(".newpassword").val();
  var npass = $(".newnextpassword").val();
  if (opass && pass && npass) {
    if (pass == npass) {
      $.ajax({
        type: "post",
        url: cmod,
        data: "password=" + pass + '&oldpassword=' + opass,
        dataType: "json",
        success: function (msg) {
          if (msg.status == 'true') {
            tips('notice', '修改成功!', 1500, 'weui_icon_toast');
          } else if (msg == 'error') {
            tips('notice', '修改失败!', 1500, 'weui_icon_notice');
          } else {
            tips('notice', '密码不正确!', 1500, 'weui_icon_notice');
          }
        }
      })
    } else {
      tips('notice', '密码不一致!', 1500, 'weui_icon_notice');
      return false;
    }

  } else {
    tips('notice', '密码不能为空!', 1500, 'weui_icon_notice');
    return false;
  }
  $("body").css("overflow", "auto");
  $(".coverpassword").css("display", "none");
}
///////打开二维码//////
function openqrcode(label) {
  $("body").css("overflow", "hidden");
  $(".coverqrcode").css("display", "block");
}
///////关闭二维码//////
function closeqrcode(label) {
  $("body").css("overflow", "auto");
  $(".coverqrcode").css("display", "none");
}
///////打开我的优惠券/////
function opencoupons(label) {
  $(".coupons").html("");
  $("body").css("overflow", "hidden");
  $(".couvercoupons").css("display", "block");
  tips('waiting', '正在加载...', 15000);
  $.ajax({
    type: "post",
    url: coupons,
    data: "ctype=2",
    dataType: "json",
    complete: function (e) {
      hidetips('waiting');
    },
    success: function (msg) {
      if (msg.status == 'true') {
        var htmls = '';
        var newdate = new Date();
        var nowdate = newdate.getFullYear() + '-' + (newdate.getMonth() + 1) + '-' + newdate.getDate() + ' ' +
        newdate.getHours() + ':' + newdate.getMinutes() + ':' + newdate.getSeconds();
        var nowdate = new Date(nowdate.replace("-", "/").replace("-", "/"));
        var cdatas = msg['cdatas'];
        for (var key in cdatas) {
          if (cdatas[key].Type == 0) {
            var expireddate = new Date((cdatas[key].ExpiredDate).replace("-", "/").replace("-", "/"));
            if (nowdate < expireddate) {
              htmls += '<div class="couponpart"><div class="coupondata">';
            } else {
              htmls += '<div class="couponpart couponwx"><div class="coupondata">';
            }
            htmls += '<label class="couponprice coupxjdk"><span>￥</span>' + cdatas[key].Rules + '<br><span>优惠券</span></label>' +
            '<label class="couponuse">' + cdatas[key].CouponName + '<br><span class="couponuse-1">此券可抵扣现金 <span>' + cdatas[key].Rules +
            ' </span>元</span><br><span class="couponuse-2">领取时间:' + cdatas[key].GetTime +
            '</span></label><label class="couponnum">×' + cdatas[key].CouponCount + '</label></div>' +
            ' <div class="coupontime coupxjtime"><label>有效期:' + cdatas[key].CreateDate + '--' + cdatas[key].ExpiredDate + '</label></div><span class="couponwxt"></span></div>';
          } else if (cdatas[key].Type == 1) {
            var expireddate = new Date((cdatas[key].ExpiredDate).replace("-", "/").replace("-", "/"));
            if (nowdate < expireddate) {
              htmls += '<div class="couponpart"><div class="coupondata">';
            } else {
              htmls += '<div class="couponpart couponwx"><div class="coupondata">';
            }
            htmls += '<label class="couponprice coupzk">' + cdatas[key].Rules + '<span>折</span><br><span>优惠券</span></label>' +
            '<label class="couponuse">' + cdatas[key].CouponName + '<br><span class="couponuse-1">此券可用于 <span>' + cdatas[key].Rules +
            ' </span>折优惠</span><br><span class="couponuse-2">领取时间:' + cdatas[key].GetTime +
            '</span></label><label class="couponnum">×' + cdatas[key].CouponCount + '</label></div>' +
            ' <div class="coupontime coupzktime"><label>有效期:' + cdatas[key].CreateDate + '--' + cdatas[key].ExpiredDate + '</label></div><span class="couponwxt"></span></div>';
          } else {
            var expireddate = new Date((cdatas[key].ExpiredDate).replace("-", "/").replace("-", "/"));
            if (nowdate < expireddate) {
              htmls += '<div class="couponpart"><div class="coupondata">';
            } else {
              htmls += '<div class="couponpart couponwx"><div class="coupondata">';
            }
            htmls += '<label class="couponprice"><span>￥</span>' + cdatas[key].Rules[1] + '<br><span>优惠券</span></label>' +
            '<label class="couponuse">' + cdatas[key].CouponName + '<br><span class="couponuse-1">金额满 <span>' + cdatas[key].Rules[0] +
            ' </span>可使用</span><br><span class="couponuse-2">领取时间:' + cdatas[key].GetTime +
            '</span></label><label class="couponnum">×' + cdatas[key].CouponCount + '</label></div>' +
            ' <div class="coupontime"><label>有效期:' + cdatas[key].CreateDate + '--' + cdatas[key].ExpiredDate + '</label></div><span class="couponwxt"></span></div>';
          }
        }
        htmls += ' <div style="height: 55px"></div>';
        $(".coupons").html(htmls);
        tips('notice', '加载完成!', 1500, 'weui_icon_toast');
      } else {
        tips('notice', '加载失败!', 1500, 'weui_icon_notice');
      }
    }
  })
}
///////我的优惠券返回/////
function backcoupons(label) {
  $("body").css("overflow", "auto");
  $(".couvercoupons").css("display", "none");

}
