$(document).ready(function () {

    if ($(".myoderstitle").attr("datype") != "") {
        seloderlx($(".sel-" + ($(".myoderstitle").attr("datype"))));
    }
    $('.oderdata').click(function(){
      var oid=$(this).parent().attr('dataoid');
      // console.log(oid);
      window.location.href=showorder+oid;
    })
    $('.oderspro').click(function(){
      var oid=$(this).parent().attr('dataoid');
      // console.log(oid);
      window.location.href=showorder+oid;
    })
    $('.odertotelprice').click(function(){
      var oid=$(this).parent().attr('dataoid');
      // console.log(oid);
      window.location.href=showorder+oid;
    })
    $('.btnpropj').click(function(){
       event.preventDefault();//阻止其他事件
       event.stopPropagation();
      // var oid=$(this).parent().attr('dataoid');
      // console.log(oid);
      // window.location.href=showorder+oid;
    })
    /////联系客服///////
    $('.getkefu').click(function(){
      var stoken=$(this).attr('data-stoken');
      if (stoken!='0') {
        window.location.href = "tel:"+$(this).attr('data-tel');
      } else {
        if($('.cusercover').css('display')=='none'){
          $('.cusercover').css('display','block');
          $("body").css("overflow", "hidden");
          $('.selkf').css('height',$('.cuserlist').height()-99+'px');
        }
      }
    });
    $('#cancelkf').click(function(){
      $('.cusercover').css('display','none');
      $('body').css('overflow', 'auto');
    })
    $('#surekf').click(function(){
      if($('.kfactive').length<=0){
        tips('notice', '选择客服!', 1500, 'weui_icon_notice');
      } else {
        var userid=$('.kfactive').attr('data-userid');
        cservice_url=cservice_url.replace(/SELUSERID/g,userid);
        cservice_url='http://'+window.location.host+cservice_url;
        window.location.href=cservice_url;
      }
    })
    if (ishasorder()=='true') {
      $('.isshow').css('display','none');
    } else {
      $('.isshow').css('display','block');
    }
})
function selectkf(label) {
    $(".selkfli").removeClass("kfactive");
    $(label).addClass("kfactive");
}
///////检查当前选择订单状态是否有数据/////
function ishasorder(){
  var hasorder='false';
  $(".alloder").children(".useroders").each(function (index, item) {
      if ($(item).css('display')!='none') {
        hasorder='true';
      }
  })
  return hasorder;
}
///////选择订单类型////////////
function seloderlx(div) {
  // alert('123');
    if (!$(div).hasClass("oderselactive")) {
        $(".oderselactive").removeClass("oderselactive");
        $(div).addClass("oderselactive");
        if ($(div).hasClass("sel-1")) {
            $(".alloder").children(".useroders").each(function (index, itme) {
                $(itme).css("display", "block");
            })
        } else if ($(div).hasClass("sel-2")) {
            $(".alloder").children(".useroders").each(function (index, itme) {
                if ($(itme).attr("datastatus") == "1") {
                    $(itme).css("display", "block");
                } else {
                    $(itme).css("display", "none");
                }
            })
        } else if ($(div).hasClass("sel-3")) {
            $(".alloder").children(".useroders").each(function (index, itme) {
                if ($(itme).attr("datastatus") == "2") {
                    $(itme).css("display", "block");
                } else {
                    $(itme).css("display", "none");
                }
            })
        } else if ($(div).hasClass("sel-4")) {
            $(".alloder").children(".useroders").each(function (index, itme) {
                if ($(itme).attr("datastatus") == "3") {
                    $(itme).css("display", "block");
                } else {
                    $(itme).css("display", "none");
                }
            })
        } else if ($(div).hasClass("sel-5")) {
            $(".alloder").children(".useroders").each(function (index, itme) {
                if (($(itme).attr("datastatus") == "4") && ($(itme).attr("data-evl")!="1") ) {
                    $(itme).css("display", "block");
                } else {
                    $(itme).css("display", "none");
                }
            })
        }
        if (ishasorder()=='true') {
          $('.isshow').css('display','none');
        } else {
          $('.isshow').css('display','block');
        }
    }
}
function choseStore(oid) {
    if (oid != '') {
        $("body").css("overflow", "hidden");
        $(".odselmdcover").css("display", "block");
        $("#suremd").attr("dataoid", oid);
    }
}
function mdsure(label) {

    var mdid = $(".mdactive").attr("datamdid");
    var oid = noworderid;
    $("body").css("overflow", "auto");
    $(".odselmdcover").css("display", "none");
    tkqr(oid, mdid,'tk');
   /* if ((mdid != '') && (typeof(mdid) != 'undefined')) {
        $.ajax({
            url: otatus,
            type: "post",
            dataType: "json",
            data: {"type": "tkmd", "oid": oid, "mdid": mdid},
            complete: function (e) {
                /////
            },
            success: function (data) {
                if (data.status == 'true') {
                    var htmls = "";
                    if (data.ostatus == 5) {
                        $("." + oid).attr("datastatus", "5");
                        $("." + oid + ">.oderdata>span").text("正在退款");
                        $("." + oid + ">.oderspro>.oderpro>button").remove();
                        htmls = ' <button type="button" class="btnprored" onclick="orderbtn(' + '\'qxtk\'' + ',\'' + oid + '\')">取消退款</button>';
                        htmls += '<button type="button" onclick="opewm(\'' + oid + '\',\'' + mdid + '\')">退款码</button>';
                        $("." + oid + ">.btn-groups").html(htmls);
                    }
                    $("body").css("overflow", "auto");
                    $(".odselmdcover").css("display", "none");
                    tips('notice', '订单处理完成!', 1500, 'weui_icon_toast');
                }
                else {

                    tips('notice', '订单处理失败!', 1500, 'weui_icon_notice');
                }
            },
            error: function (e) {
                tips('notice', '订单处理失败!', 1500, 'weui_icon_notice');
            }
        })
    } else {
        tips('notice', '请选择门店!', 1500, 'weui_icon_notice');
    }*/
}
function opewm(url) {
    tkqrcode(url);
    $("body").css("overflow", "hidden");
    $(".coverqrcode").css("display", "block");
}
function closerewm(label) {
    stopGetOrderStatus=true;
    $("body").css("overflow", "auto");
    $(".coverqrcode").css("display", "none");
}
function backod(label) {
    $("body").css("overflow", "auto");
    $(".odselmdcover").css("display", "none");
}
function selectmd(label) {
    $(".selmdli").removeClass("mdactive");
    $(label).addClass("mdactive");
}

function orderbtn(type, oid) {

    if (lockNowTask) {
        return;
    }

  if (type=='tk-XJ') {
    $('.odselmdcover').show();
    noworderid=oid;
    return false;
  }
    lockNowTask=true;
    if ((type != '') && (oid != ''))
    {
        $.ajax({
            url: otatus,
            type: "post",
            dataType: "json",
            data: {"type": type, "oid": oid},
            complete: function (e) {
                /////
                lockNowTask=false;
            },
            success: function (data) {

                if (data.status == 'true') {
                    var htmls = "";
                    if (data.ostatus == 10) {
                        $("." + oid).attr("datastatus", "10");
                        $("." + oid).remove();
                    }
                    else if (data.ostatus == 4)
                    {
                        $("." + oid).attr("datastatus", "4");
                        $("." + oid + ">.oderdata>span").text("交易完成");
                        htmls = '<button type="button" class="btnpropj">评价</button>';
                        $("." + oid + ">.oderspro>.oderpro").append(htmls);
                        if (data.tp == 'XJ') {
                            htmls = ' <button type="button" onclick="choseStore(\'' + oid + '\')">退款</button>';
                        }
                        else
                        {
                            htmls = ' <button type="button" onclick="orderbtn(' + '\'tk-'+ data.tp +'\''+ ',\'' + oid + '\')">退款</button>';
                        }
                        $("." + oid + ">.btn-groups").html(htmls);
                    }
                    else if (data.ostatus == 5)
                    {
                        $("." + oid).attr("datastatus", "5");
                        $("." + oid + ">.oderdata>span").text("正在退款");
                        $("." + oid + ">.oderspro>.oderpro>button").remove();
                        //htmls = ' <button type="button" class="btnprored" onclick="orderbtn(' + '\'qxtk\'' + ',\'' + oid + '\')">取消退款</button>';
                        htmls="";
                        if ((data.ptype == 'XJ') || (data.ptype == 'XJPAY')) {
                            htmls += '<button type="button" onclick="opewm(\'' + oid + '\',\'' + mdid + '\')">退款码</button>';
                        }
                        $("." + oid + ">.btn-groups").html(htmls);

                    }
                    else if (data.ostatus == 9)
                    {
                      $("." + oid).attr("datastatus", "10");
                      $("." + oid).remove();
                        // $("." + oid).attr("datastatus", "9");
                        // $("." + oid + ">.oderdata>span").text("订单取消");
                        // htmls = '';
                        // $("." + oid + ">.btn-groups").html(htmls);
                    }
                    else if (data.ostatus == 2)
                    {
                        $("." + oid).attr("datastatus", "2");
                        $("." + oid + ">.oderdata>span").text("已付款");
                        if ((data.ptype == 'XJ') || (data.ptype == 'XJPAY')) {
                            htmls = ' <button type="button" onclick="choseStore(\'' + oid + '\')">退款</button>';
                        } else {
                            htmls = ' <button type="button" onclick="orderbtn(' + '\'tk\'' + ',\'' + oid + '\')">退款</button>';
                        }
                        $("." + oid + ">.btn-groups").html(htmls);
                    }
                    else if (data.ostatus == 3)
                    {
                        $("." + oid).attr("datastatus", "3");
                        //alert("sel");
                        if ((data.ptype == 'XJ') || (data.ptype == 'XJPAY')) {
                            $("." + oid + ">.oderdata>span").text("客户上门提货");
                            htmls += ' <button type="button" class="btnprored" onclick="orderbtn(' + '\'sh\'' + ',\'' + oid + '\')">确认收货</button>';
                            htmls += '<button type="button" onclick="tkqr(\'' + oid + '\',\'' + mdid + '\',' + '\'zt\'' + ')">提货码</button>';
                        } else {
                            $("." + oid + ">.oderdata>span").text("商家已发货");
                            htmls += ' <button type="button" class="btnprored" onclick="orderbtn(' + '\'sh\'' + ',\'' + oid + '\')">确认收货</button>';
                            htmls += ' <button type="button" onclick="orderbtn(' + '\'tk\'' + ',\'' + oid + '\')">退款</button>';
                        }
                        $("." + oid + ">.btn-groups").html(htmls);
                    }

                    tips('notice', '订单处理完成!', 1500, 'weui_icon_toast');

                }
                else {

                    tips('notice', '订单处理失败!', 1500, 'weui_icon_notice');
                    //setTimeout(function() { window.location.href='/Home/User/usermyoders';}, 1000);
                }
            },
            error: function (e) {
                lockNowTask=false;
                tips('notice', '订单处理失败!', 1500, 'weui_icon_notice');
            }
        })
    }
    else
    {
        lockNowTask=false;
    }
}
function tkqr(oid,mid,type){
  if (type=='tk') {
    var _url=tkurlvar;
    getPayStatus(3,oid,[8]);
  };
  if (type=='zt') {
    var _url=zturlvar;
    getPayStatus(2,oid,[4]);
  }
  _url=_url.replace('OIDREPLACE',oid);
  _url=_url.replace('MDIDREPLACE',mid);
  opewm(_url);
};



function tkqrcode(url,width,height){
  var width=width||200;
  var height=height||200;
    var _urls = url;
    // _url=_url.replace('OIDREPLACE',oid);
    // _url=_url.replace('MDIDREPLACE',mdid);
    var tuiqrcode_div = $("#tkqrcodediv");
    tuiqrcode_div.empty();
    tuiqrcode_div.qrcode({
        render: "canvas",
        width: 200,
        height: 200,
        text: _urls,
    });
}



    function getPayStatus(type,oid,wv)
    {
        if (stopGetOrderStatus) {
            stopGetOrderStatus=false;
            return;
        }

          $.ajax({
              //提交数据的类型 POST GET
              type: "POST",
              //提交的网址
              url: checkOrderUrl,
              //提交的数据
              data: {type: type, gid: oid,wv:wv},
              //返回数据的格式
              datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
              //在请求之前调用的函数

              beforeSend: function () {

              },
              //成功返回之后调用的函数
              success: function (data) {

                  if (data.res==0) {
                      $('#qr-codess').html('交易成功，3秒后刷新页面');
                      setTimeout(function() { window.location.href=gomyorder;}, 3000);

                  }
                  else if (data.res==1) {

                  }
                  else
                  {

                  }

                  if (data.isStop==true) {
                    //停止了
                    stopGetOrderStatus=true;
                  }else{
                    getPayStatus(type,oid,wv);
                  }
              },
              //调用出错执行的函数
              error: function () {
                  //请求出错处理
                  getPayStatus(type,oid,wv);
              },
              //调用执行后调用的函数
              complete: function (XMLHttpRequest, textStatus) {

              }
          });
    }
