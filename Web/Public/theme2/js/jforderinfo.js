var addressIdS = null;
$(document).ready(function () {

    addressIdS = pList("addressPCD", "addressSelect");
    $("#alertenter").click(function () {
        $("#alert").hide()
    });

    // $('#gopay').click(function () {
    //     $('.btn-warning').addClass('disabled');
    //     tips('waiting','前往付款...');
    //     window.location.href = payurl + '?oid=' + $('#orderid').html();
    // });

    $('#checkbox_c1').click(function () {
        if ($('#checkbox_c1').attr('data-s') == '0') {
            $('#checkbox_c1').attr('data-s', 'checked');
        } else {
            $('#checkbox_c1').attr('data-s', '0');
        }
    });

    $('#esc').click(function () {
        $('#confirm').hide();
    });

    $('#enter').click(function () {
        $('#confirm').hide();
        $.ajax({
            type: "get",
            url: cdeladdr,
            data: 'id=' + $("#enter").attr("data-s"),
            dateType: "json",
            success: function (msg) {
                if (msg.status == 'false') {
                    if($("#enter").attr("data-s")==$(".oraddrs").attr("data-s")){
                        $(".oraddrs").attr('data-s', "");
                        $(".oraddrs>.address-name").text("");
                        $(".oraddrs>.address-addr").text("");
                        $(".oraddrs>.telephone").text("");
                        $('.oraddrs').attr('data-prov', "");
                    }
                    $("#" + $("#enter").attr("data-s")).remove();
                    $("#enter").attr("data-s", "");
                    tips('notice', '删除成功!', 1500, 'weui_icon_toast');
                } else {
                    tips('notice', '删除失败!', 1500, 'weui_icon_notice');
                }
            }
        });
    });

    getAllMoney();

});

function getLessMoney()
{
    //暂不使用
}

function getAllMoney()
{
    //var totalMoneyVar=parseFloat($('#totleMoney').val())+parseFloat($('#psMoney').val())+parseFloat($('#freightMoney').val())-parseFloat($('#lessMoney').val())-parseFloat($('#couponmoney').val());

    //$('#freight').html("￥"+(parseFloat($('#psMoney').val())+parseFloat($('#freightMoney').val())).toFixed(2));

    var totalMoneyVar=parseFloat($('#totleMoney').val());
    $('#price-box').html(totalMoneyVar.toFixed(2));
}



//地址选择///
function selectaddr(div) {
    $("body").css("overflow", "hidden");
    $(".coversel").css("display", "block");
}

///确定选择地址///
function sueraddr(div) 
{


    if ($('#psType').val()=='KD') 
    {
        tips('waiting', '正在处理数据');

        $.ajax({
            url: getFreightURL,
            type: "post",
            data: {"weight":$('#proWeight').val(),"province":$(div).attr('data-prov')},
            dataType: "json",
            complate:function(msg)
            {
                $("#waiting").hide();
            },
            success: function (msg) 
            {
                if (msg.status == 'true') 
                {

                    $(".oraddrs").attr('data-s', $(div).attr('data-s'));
                    $(".oraddrs>.address-name").text(($(div).children(".addrname")).text());
                    $(".oraddrs>.address-addr").text(($(div).children(".addraddr")).text());
                    $(".oraddrs>.telephone").text(($(div).children(".addrphone")).text());
                    $('.oraddrs').attr('data-prov', $(div).attr('data-prov'));


                    $('#psMoney').val('0');
                    $('#freightMoney').val(msg.info);
                }
                else 
                {
                    tips('notice', '运费计算出错', 2000, 'weui_icon_notice');
                }
            }
        });
    }
    else if ($('#psType').val()=='PS') 
    {
        $('#freightMoney').val('0');
        $('#psMoney').val($('#psMoney').attr('data-s'));

        $(".oraddrs").attr('data-s', $(div).attr('data-s'));
        $(".oraddrs>.address-name").text(($(div).children(".addrname")).text());
        $(".oraddrs>.address-addr").text(($(div).children(".addraddr")).text());
        $(".oraddrs>.telephone").text(($(div).children(".addrphone")).text());
        $('.oraddrs').attr('data-prov', $(div).attr('data-prov'));
    }
    else
    {
        $('#psMoney').val('0');
        $('#freightMoney').val('0');

        $(".oraddrs").attr('data-s', $(div).attr('data-s'));
        $(".oraddrs>.address-name").text(($(div).children(".addrname")).text());
        $(".oraddrs>.address-addr").text(($(div).children(".addraddr")).text());
        $(".oraddrs>.telephone").text(($(div).children(".addrphone")).text());
        $('.oraddrs').attr('data-prov', $(div).attr('data-prov'));
    }

    getAllMoney();

    $("body").css("overflow", "auto");
    $(".coversel").css("display", "none");
}

function getFreightFun()
{

}

////修改地址///////
function addressedit(label) {

    $(".coversel").css("display", "none");
    $(".coveredit").css("display", "block");
    $(".coveredit").addClass("update");
    $('.addressedit').attr('data-s', $(label).parent().parent().attr('data-s'));
    $(".shnames").val(($(label).parent().parent().children(".addrname")).text());
    $(".shphones").val(($(label).parent().parent().children(".addrphone")).text());
    $(".shshregions").text(($(label).parent().parent().children(".addraddr")).attr("data-province") +
        ($(label).parent().parent().children(".addraddr")).attr("data-city") +
        ($(label).parent().parent().children(".addraddr")).attr("data-area"));
    $(".shshregions").attr("data-province", ($(label).parent().parent().children(".addraddr")).attr("data-province"));
    $(".shshregions").attr("data-city", ($(label).parent().parent().children(".addraddr")).attr("data-city"));
    $(".shshregions").attr("data-area", ($(label).parent().parent().children(".addraddr")).attr("data-area"));
    $(".shregion").val(($(label).parent().parent().children(".addraddr")).attr("dataregion"));
    $(".xxaddr").val(($(label).parent().parent().children(".addraddr")).attr("data-address"));

    if (($(label).parent().children(".addrdefault")).hasClass("schecked")) 
    {
        $("#checkbox_c1").prop("checked", true);
    } 
    else 
    {
        $("#checkbox_c1").prop("checked", false);
    }

    window.event.stopPropagation();
    window.event.cancelBubble = true;
}

////新增地址///////
function addressadd(label) {
    $(".coversel").css("display", "none");
    $(".coveredit").css("display", "block");
    $(".coveredit").addClass("add");
    $(".shnames").val("");
    $(".shphones").val("");
    $(".shshregions").text("");
    $(".shregion").val("");
    $(".xxaddr").val("");
    $("#checkbox_c1").attr("checked", false);
}

/////删除地址//////
function addressdel(label) {
    window.event.stopPropagation();
    window.event.cancelBubble = true;
    $("#enter").attr("data-s", $(label).attr("addrsID"));
    tips('confirm', '确定要删除此收货地址吗？');
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
    // console.log($('.shshregions').html());
    var province = $('.shshregions').attr('data-province');
    var city = $('.shshregions').attr('data-city');
    var area = $('.shshregions').attr('data-area');
    var address = $('.xxaddr').val();
    var phone = $('.shphones').val();
    var rname = $('.shnames').val();
    var id = $('.addressedit').attr('data-s');


    var htmls = '';
    tips('waiting', '正在处理数据');
    $.ajax({
        url: saveaddr,
        type: "post",
        data: {
            "name": rname,
            "phone": phone,
            "province": province,
            "city": city,
            "area": area,
            "address": address,
            "post": "0",
            "default": $('#checkbox_c1').attr('data-s'),
            "addrsid": id,

        },
        dataType: "json",
        success: function (msg) {
            $("#waiting").hide();
            if (msg.status == 'true') {
                var rid = msg.info;
                ///////
                if ($(".coveredit").hasClass("update")) {
                    $("#" + id + ">.addrname").text($(".shnames").val());
                    $("#" + id + ">.addrphone").text($(".shphones").val());
                    $("#" + id + ">.addraddr").text($(".shshregions").attr('data-province') + $(".shshregions").attr('data-city') +
                        $(".shshregions").attr('data-area') + $(".xxaddr").val());
                    $("#" + id + ">.addraddr").attr("data-province", $(".shshregions").attr('data-province'));
                    $("#" + id + ">.addraddr").attr("data-city", $(".shshregions").attr('data-city'));
                    $("#" + id + ">.addraddr").attr("data-area", $(".shshregions").attr('data-area'));
                    $("#" + id + ">.addraddr").attr("data-address", $(".xxaddr").val());
                    if ($("#checkbox_c1").prop("checked")) {
                        $(".addrdefault").attr("class", "addrdefault");
                        $("#" + id + ">.addrsedit>.addrdefault").addClass("schecked");
                    }
                } else {
                    htmls += '<div id=' + rid + ' class="addr-box" data-s=' + rid + ' data-prov=' + $(".shshregions").attr('data-province') + 'onclick="sueraddr(this)" >' +
                        '<label class="addrname">' + $(".shnames").val() + '</label>' +
                        '<label class="addraddr" data-province=' + $(".shshregions").attr('data-province') +
                        ' data-city=' + $(".shshregions").attr('data-city') + ' data-area=' + $(".shshregions").attr('data-area') + ' data-address=' + $(".shphones").val() +
                        '>' + $(".shshregions").attr('data-province') + $(".shshregions").attr('data-city') + $(".shshregions").attr('data-area') + $(".xxaddr").val() + '</label>' +
                        '<label class="addrphone">' + $(".shphones").val() + '</label>' +
                        '<div class="addrsedit">' +
                        '<label class="addrdefault">默认地址</label>' +
                        '<label class="addrdel" addrsID=' + rid + ' onclick="addressdel(this)">删除</label>' +
                        ' <label class="addredit"  onclick="addressedit(this)">编辑</label>' +
                        '</div></div> ';
                    $(".addrselect").prepend(htmls);
                    if ($("#checkbox_c1").prop("checked")) {
                        $(".addrdefault").attr("class", "addrdefault");
                        $("#" + rid + ">.addrsedit>.addrdefault").addClass("schecked");
                    }
                }
                ////////
                $(".oraddrs>.address-name").text($(".shnames").val());
                $(".oraddrs>.address-addr").text($(".shshregions").text() + $(".xxaddr").val());
                $(".oraddrs>.telephone").text($(".shphones").val());
                $('.oraddrs').attr('data-s', rid);
                $("body").css("overflow", "auto");
                $(".coveredit").css("display", "none");
                $(".coveredit").attr("class", "coveredit");
                $('.oraddrs').attr('data-prov', province);
            } else {
                console.log(msg);
                tips('notice', '保存出错', 2000, 'weui_icon_notice');
            }
        }
    });
}

///////地址选择返回/////
function csback(label) {
    $("body").css("overflow", "auto");
    $(".coversel").css("display", "none");

}
///////地址编辑返回/////
function ceback(label) {
    $(".coversel").css("display", "block");
    $(".coveredit").css("display", "none");
    $(".coveredit").attr("class", "coveredit");

}
///////地区选择/////
function selregion(label) {
    $(".coverregion").css("display", "block");

}
///////关闭地区选择/////
function gbselregion(label) {
    $(".coverregion").css("display", "none");
}
///////完成地区选择/////
function wcselregion(label) {
    var strregion;
    if ($("#" + addressIdS[0]).val() != "请选择") {
        strregion = $("#" + addressIdS[0]).val();
        $('.shshregions').attr('data-province', $("#" + addressIdS[0]).val());
    } else {
        tips('notice', '选择省份!', 1500, 'weui_icon_notice');
        return false;
    }

    if ($("#" + addressIdS[1]).val() != "请选择") {
        strregion = strregion + $("#" + addressIdS[1]).val();
        $('.shshregions').attr('data-city', $("#" + addressIdS[1]).val());
    } else {
        tips('notice', '选择城市!', 1500, 'weui_icon_notice');
        return false;
    }


    if ($("#" + addressIdS[2]).val() != "请选择") {
        strregion = strregion + $("#" + addressIdS[2]).val();
        $('.shshregions').attr('data-area', $("#" + addressIdS[2]).val());
    } else {
        tips('notice', '选择地区!', 1500, 'weui_icon_notice');
        return false;
    }

    $(".shshregions").text(strregion);
    $(".shregion").val(strregion);
    $(".coverregion").css("display", "none");
}

////////打开优惠///////
function selyhxx(label) {
    $("body").css("overflow", "hidden");
    $(".coveryouhui").css("display", "block");
}
////////打开优惠///////
function gbyhxx(label) 
{

    var tempObj = $('.yhcardactive');

    if (tempObj.length <= 0) {
        tips('notice', '请选择一张优惠券', 1500, 'weui_icon_notice');
        return false;
    }

    var rules = tempObj.attr('data-rules');
    var type = tempObj.attr('data-type');
    var cid = tempObj.attr('data-couponid');
    var cstoken=tempObj.attr('data-stoken');

    var totalprice = parseFloat($('.ordertotal').attr('data-allprice'));
    var youhui = parseFloat($('.selyhs').attr('data-old'));


    var newtotal = 0;
    var newyouhui = 0;

    // var newtotal=0;
    if (type == '0') 
    {
        if (cstoken=="0") 
        {

            var hjGoodsMoney=parseFloat($('#hjMoney').val());

            if (hjGoodsMoney>rules) 
            {
                newyouhui=rules;
            }
            else
            {
                newyouhui=hjGoodsMoney;
            }

        }
        else
        {

        }


        newtotal=totalprice-newyouhui;
    }

    if (type == '1') 
    {
         newtotal = totalprice * parseFloat(rules);
         newyouhui = totalprice - newtotal + youhui;
    }

    if (type == '2') 
    {
         rule = rules.split('/');
        if (totalprice > rule[0]) 
        {
            tips('notice', '您的订单无法使用此优惠券', 1500, 'weui_icon_notice');
            return false;
        }
        else {
             newtotal = totalprice - parseFloat(rule[1]);
             newyouhui = youhui + parseFloat(rule[1]);
        }
    }

    $('.ordertotal').attr('datatotal', newtotal);
    $('#price-box').html(newtotal);
    $('.selyhs').attr('data-s', newyouhui).html("￥ -" + newyouhui).attr('data-cid', cid);
    $('#couponmoney').val(newyouhui);
    $('#CouponId').val(cid);
    //关闭优惠券选择页面
    $("body").css("overflow", "auto");
    $(".coveryouhui").css("display", "none");
}

// var OrderMoney=0;

// var totleMoneyObj=$('#totleMoney');

// var hjMoneyObj=$('#hjMoney');
// var sMoneyObj=$('#sMoney');

// var CouponIdObj=$('#CouponId');
// var couponmoneyObj=$('#couponmoney');

// var freightMoneyObj=$('#freightMoney');
// var proWeightObj=$('#proWeight');

// var psTypeObj=$('#psType');
// var psMoneyObj=$('#psMoney');

// var isOther=$('#isOther');
// var oStokenObj=$('#oStoken');

// //合计
// function getMoney()
// {
//     parseFloat(rule[1]);
// }


/////////////选择优惠券///////
function selectyhcard(label) 
{
    $(".yhcardactive").removeClass("yhcardactive");
    $(label).addClass("yhcardactive");
}

///////打开配送方式/////
function seldistr(obj) 
{

    var psSelectObj=null;

    var NowPSType=$('#psType').val();

    switch(NowPSType)
    {
        case 'KD':psSelectObj=$(".distrkd"); break;
        case 'PS':psSelectObj=$(".distrps"); break;
        case 'ZT':psSelectObj=$(".distrzt"); break;
        default:

        break;
    }

    $("body").css("overflow", "hidden");
    $(".coverdistr").css("display", "block");
}

///////选择配送方式/////
function seldistrs(Obj,NowPSType) 
{

    if (NowPSType=='KD') {

        tips('waiting', '正在处理数据');

        $.ajax({
            url: getFreightURL,
            type: "post",
            data: {"weight":$('#proWeight').val(),"province":$('.oraddrs').attr('data-prov')},
            dataType: "json",
            complate:function(msg)
            {
                $("#waiting").hide();
            },
            success: function (msg) 
            {
                if (msg.status == 'true') 
                {
                    var psTypeDivs=$('.psTypeDiv');
                    var tempNowPSObj=null;

                    $.each(psTypeDivs,function(k,v){
                        tempNowPSObj=$(v);
                        tempNowPSObj.removeClass("distractive");
                    });

                    $(Obj).addClass('distractive');
                    $('#freightMoney').val(msg.info);
                    
                }
                else 
                {
                    tips('notice', '运费计算出错', 2000, 'weui_icon_notice');
                }
            }
        });

    }
    else
    {
        var psTypeDivs=$('.psTypeDiv');
        var tempNowPSObj=null;

        $.each(psTypeDivs,function(k,v){
            tempNowPSObj=$(v);
            tempNowPSObj.removeClass("distractive");
        });

        $(Obj).addClass('distractive');
    }



    
}

///////关闭配送方式/////
function gbseldistr() 
{

    var NowPSType=$('.distractive').attr('data-s');

    $('#psType').val(NowPSType);


    switch(NowPSType)
    {
        case 'KD': 
            $('.seldistr').html('快递');
            $('#psMoney').val('0');
            break;
        case 'PS':
            $('.seldistr').html('配送');

            $('#freightMoney').val('0');
            $('#psMoney').val($('#psMoney').attr('data-s'));
            break;
        case 'ZT':
            $('.seldistr').html('自提');

            $('#freightMoney').val('0');
            $('#psMoney').val('0');
            break;
        default:
            $('.seldistr').html('ERROR');
            break;
    }

    getAllMoney();
    $("body").css("overflow", "auto");
    $(".coverdistr").css("display", "none");

}
///////支付方式/////
function payment(div) {
    if (!$(div).hasClass("payactive")) {
        $(".payactive>img").attr("src", imgurl + "weixuanze.png");
        $(".payment").removeClass("payactive");
        $(div).addClass("payactive");
        $(".payactive>img").attr("src", imgurl + "xuanze2.png");
        if ($(div).hasClass("payrccheck")) {
            $(".payrcs").css("display", "block");
            $(".payrccheck").css("border-width", "0px");
            $(".czye").css("background-image", "url('" + imgurl + "xuanze2.png')");
            $(".czye").addClass("payrcsactive");
            $(".jlye").css("background-image", "url('" + imgurl + "weixuanze.png')");
            $(".jlye").removeClass("payrcsactive");
        } else {
            $(".payrcs").css("display", "none");
            $(".payrccheck").css("border-width", "1px");
        }
    }
}
///////选择会员充值方式/////
// function paymentrcs(label) 
// {
//     if (!$(label).hasClass("payrcsactive")) {
//         if ($(label).hasClass("czye")) {
//             $(".czye").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".czye").addClass("payrcsactive");
//             $(".jlye").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".jlye").removeClass("payrcsactive");
//         } else {
//             $(".jlye").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".jlye").addClass("payrcsactive");
//             $(".czye").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".czye").removeClass("payrcsactive");
//         }
//     }
// }

////////提交订单///////////
function tjdd(label) 
{
    var canSend = false;  //判断发货区域

    var psTypeVar = $('#psType').val();

    var addid = $('.oraddrs').attr('data-s');
    //计算运费

    if (psTypeVar=='KD'||psTypeVar=='PS') {
        if (!addid) {
            tips('notice','请填写详细地址！',2000,'weui_icon_notice');
            return false;
        }
    }


    var province = $('.oraddrs').attr('data-prov');

    if (psTypeVar == 'KD') 
    {
        $.each(fjson, function (index, item) {
            if (item.Area == province) 
            {
                canSend = true;
            }
        });
    }
    
    if (psTypeVar == 'ZT'||psTypeVar=='PS') 
    {
        canSend = true;
    }

    if (canSend)
    {

        if ($(".paywechatcheck").hasClass("payactive"))
        {
            $(".paylx").text("微信支付").attr('data-s', 'T');
        }
        else if ($(".paycashcheck").hasClass("payactive"))
        {
            $(".paylx").text("现金支付").attr('data-s', 'XJ');
        }
        else if ($(".payrccheck").hasClass("payactive"))
        {
            // if ($(".czye").hasClass("payrcsactive")) {
            //     $(".paylx").text("充值支付").attr('data-s', 'YE');
            // } else if ($(".jlye").hasClass("payrcsactive")) {
            //     $(".paylx").text("充值奖励支付").attr('data-s', 'JL');
            // }
        }

        

        $("body").css("overflow", "hidden");
        $(".coverpayer").css("display", "block");


        var orderid = $("#orderid").html();
        var payType = $('.paylx').attr('data-s');
        var cid = $('#CouponId').val();
        var isOther=$('#isOther').val();
        var oStoken=$('#oStoken').val();
        // console.log(orderid+"|"+totalprice+"|"+freight+"|"+sendType+"|付款方式"+payType+"|"+oldOrder+"|"+addid);

        tips('waiting', '正在保存订单数据');
        $.ajax({
            url: saveorder,
            type: "post",
            data: {
                "orderid": orderid,
                "addid": addid,
                "sendType": psTypeVar,
                "cid": cid,
                "payType": payType,
                "isOther":isOther,
                "oStoken":oStoken,
            },
            dataType: "json",
            success: function (msg) 
            {
                $("#waiting").hide();

                if (msg.status == 'true')
                {
                    if (msg.info == 'Success') 
                    {
                        $(".payerje").text(msg.money+'积分');
                        $('.btn-warning').removeClass('disabled');
                        $('.payergb').remove();

                        tips('notice', '交易完成', 2000, 'weui_icon_notice');

                        setTimeout(function(e){
                            window.location.href=returnUrl;
                        },3000);
                    }
                }
                if (msg.status == 'false')
                {
                    if (msg.info == 'hadOrder')
                    {
                        tips('notice', '订单已存在！', 2000, 'weui_icon_notice');
                    }
                    else if (msg.info == 'YENoEnough.')
                    {
                        tips('notice', '会员余额不足！', 2000, 'weui_icon_notice');
                    }
                    else
                    {
                        tips('notice','订单处理失败！',2000,'weui_icon_notice');
                    }
                    $('.btn-warning').attr('class','btn btn-warning disabled');
                }
                // console.log(msg);
            }
        })
    } else {
        tips('notice', '商家暂不对' + province + '地区提供发货，请更换收货地址', 2000, 'weui_icon_notice');
        return false;
    }
    
}


///////关闭订单///////////
function gbdd(span) {
    $("body").css("overflow", "auto");
    $(".coverpayer").css("display", "none");
    $('.btn-warning').addClass('disabled');
}


// var addressIdS = null;
// $(document).ready(function () 
// {
//     addressIdS = pList("addressPCD", "addressSelect");
//     $("#alertenter").click(function () {
//         $("#alert").hide()
//     });

//     $('#gopay').click(function () {
//         $('.btn-warning').addClass('disabled');
//         tips('waiting','前往付款...');
//         window.location.href = payurl + '?oid=' + $('#orderid').html();
//     });

//     $('#checkbox_c1').click(function () {
//         if ($('#checkbox_c1').attr('data-s') == '0') {
//             $('#checkbox_c1').attr('data-s', 'checked');
//         } else {
//             $('#checkbox_c1').attr('data-s', '0');
//         }
//     });

//     $("#chosestore").click(function () {
//         $('.oraddrs').attr('data-s', $('.xzmd :selected').attr('data-s'))
//     });


//     $('#esc').click(function () {
//         $('#confirm').hide()
//     });

//     $('#enter').click(function () {
//         $('#confirm').hide();
//         $.ajax({
//             type: "get",
//             url: cdeladdr,
//             data: 'id=' + $("#enter").attr("data-s"),
//             dateType: "json",
//             success: function (msg) {
//                 if (msg.status == 'false') {
//                     if($("#enter").attr("data-s")==$(".oraddrs").attr("data-s")){
//                         $(".oraddrs").attr('data-s', "");
//                         $(".oraddrs>.address-name").text("");
//                         $(".oraddrs>.address-addr").text("");
//                         $(".oraddrs>.telephone").text("");
//                         $('.oraddrs').attr('data-prov', "");
//                     }
//                     $("#" + $("#enter").attr("data-s")).remove();
//                     $("#enter").attr("data-s", "");
//                     tips('notice', '删除成功!', 1500, 'weui_icon_toast');
//                 } else {
//                     tips('notice', '删除失败!', 1500, 'weui_icon_notice');
//                 }
//             }
//         });
//     });

//     // $('option').click(function(){$(this).parent().attr('data-s',$(this).attr('data-s'))})

// });
// //地址选择///
// function selectaddr(div) {
//     $("body").css("overflow", "hidden");
//     $(".coversel").css("display", "block");
// }

// ///确定选择地址///
// function sueraddr(div) {
//     $(".oraddrs").attr('data-s', $(div).attr('data-s'));
//     $(".oraddrs>.address-name").text(($(div).children(".addrname")).text());
//     $(".oraddrs>.address-addr").text(($(div).children(".addraddr")).text());
//     $(".oraddrs>.telephone").text(($(div).children(".addrphone")).text());
//     $('.oraddrs').attr('data-prov', $(div).attr('data-prov'));
//     $("body").css("overflow", "auto");
//     $(".coversel").css("display", "none");
// }
// ////修改地址///////
// function addressedit(label) {
//     /* $(".coversel").css("display", "none");
//      $(".coveredit").css("display", "block");
//      $(".coveredit").addClass("update");
//      $(".coveredit").attr("addrsID", $(label).attr("addrsID"));
//      $(".shnames").val(($(label).parent().parent().children(".addrname")).text());
//      $(".shphones").val(($(label).parent().parent().children(".addrphone")).text());
//      $(".shshregions").text(($(label).parent().parent().children(".addraddr")).attr("data-province") +
//      ($(label).parent().parent().children(".addraddr")).attr("data-city") +
//      ($(label).parent().parent().children(".addraddr")).attr("data-area"));
//      $(".shshregions").attr("data-province", ($(label).parent().parent().children(".addraddr")).attr("data-province"));
//      $(".shshregions").attr("data-city", ($(label).parent().parent().children(".addraddr")).attr("data-city"));
//      $(".shshregions").attr("data-area", ($(label).parent().parent().children(".addraddr")).attr("data-area"));
//      $(".xxaddr").val(($(label).parent().parent().children(".addraddr")).attr("data-address"));
//      if (($(label).parent().children(".addrdefault")).hasClass("schecked")) {
//      $("#checkbox_c1").prop("checked", true);
//      } else {
//      $("#checkbox_c1").prop("checked", false);
//      }*/
//     /*window.event.stopPropagation();
//      window.event.cancelBubble = true;*/
//     ///////
//     $(".coversel").css("display", "none");
//     $(".coveredit").css("display", "block");
//     $(".coveredit").addClass("update");
//     $('.addressedit').attr('data-s', $(label).parent().parent().attr('data-s'));
//     $(".shnames").val(($(label).parent().parent().children(".addrname")).text());
//     $(".shphones").val(($(label).parent().parent().children(".addrphone")).text());
//     $(".shshregions").text(($(label).parent().parent().children(".addraddr")).attr("data-province") +
//         ($(label).parent().parent().children(".addraddr")).attr("data-city") +
//         ($(label).parent().parent().children(".addraddr")).attr("data-area"));
//     $(".shshregions").attr("data-province", ($(label).parent().parent().children(".addraddr")).attr("data-province"));
//     $(".shshregions").attr("data-city", ($(label).parent().parent().children(".addraddr")).attr("data-city"));
//     $(".shshregions").attr("data-area", ($(label).parent().parent().children(".addraddr")).attr("data-area"));
//     $(".shregion").val(($(label).parent().parent().children(".addraddr")).attr("dataregion"));
//     $(".xxaddr").val(($(label).parent().parent().children(".addraddr")).attr("data-address"));
//     if (($(label).parent().children(".addrdefault")).hasClass("schecked")) {
//         $("#checkbox_c1").prop("checked", true);
//     } else {
//         $("#checkbox_c1").prop("checked", false);
//     }
//     window.event.stopPropagation();
//     window.event.cancelBubble = true;
// }

// ////新增地址///////
// function addressadd(label) {
//     $(".coversel").css("display", "none");
//     $(".coveredit").css("display", "block");
//     $(".coveredit").addClass("add");
//     $(".shnames").val("");
//     $(".shphones").val("");
//     $(".shshregions").text("");
//     $(".shregion").val("");
//     $(".xxaddr").val("");
//     $("#checkbox_c1").attr("checked", false);
// }

// /////删除地址//////
// function addressdel(label) {
//     window.event.stopPropagation();
//     window.event.cancelBubble = true;
//     $("#enter").attr("data-s", $(label).attr("addrsID"));
//     tips('confirm', '确定要删除此收货地址吗？');
// }
// //////地址保存///////
// function addresssave(label) {
//     if ($(".shnames").val() == "") {
//         tips('notice', '请填写收货人!', 1500, 'weui_icon_notice');
//         return false;
//     }
//     if ($(".shphones").val() == "") {
//         tips('notice', '请填写收货人电话!', 1500, 'weui_icon_notice');
//         return false;
//     }
//     if ($(".shshregions").text() == "") {
//         tips('notice', '请选择发货地址!', 1500, 'weui_icon_notice');
//         return false;
//     }
//     if ($(".xxaddr").val() == "") {
//         tips('notice', '请填写详细地址!', 1500, 'weui_icon_notice');
//         return false;
//     }
//     // console.log($('.shshregions').html());
//     var province = $('.shshregions').attr('data-province');
//     var city = $('.shshregions').attr('data-city');
//     var area = $('.shshregions').attr('data-area');
//     var address = $('.xxaddr').val();
//     var phone = $('.shphones').val();
//     var rname = $('.shnames').val();
//     var id = $('.addressedit').attr('data-s');
//     var htmls = '';
//     tips('waiting', '正在处理数据');
//     $.ajax({
//         url: saveaddr,
//         type: "post",
//         data: {
//             "name": rname,
//             "phone": phone,
//             "province": province,
//             "city": city,
//             "area": area,
//             "address": address,
//             "post": "0",
//             "default": $('#checkbox_c1').attr('data-s'),
//             "addrsid": id
//         },
//         dataType: "json",
//         success: function (msg) {
//             $("#waiting").hide();
//             if (msg.status == 'true') {
//                 var rid = msg.info;
//                 ///////
//                 if ($(".coveredit").hasClass("update")) {
//                     $("#" + id + ">.addrname").text($(".shnames").val());
//                     $("#" + id + ">.addrphone").text($(".shphones").val());
//                     $("#" + id + ">.addraddr").text($(".shshregions").attr('data-province') + $(".shshregions").attr('data-city') +
//                         $(".shshregions").attr('data-area') + $(".xxaddr").val());
//                     $("#" + id + ">.addraddr").attr("data-province", $(".shshregions").attr('data-province'));
//                     $("#" + id + ">.addraddr").attr("data-city", $(".shshregions").attr('data-city'));
//                     $("#" + id + ">.addraddr").attr("data-area", $(".shshregions").attr('data-area'));
//                     $("#" + id + ">.addraddr").attr("data-address", $(".xxaddr").val());
//                     if ($("#checkbox_c1").prop("checked")) {
//                         $(".addrdefault").attr("class", "addrdefault");
//                         $("#" + id + ">.addrsedit>.addrdefault").addClass("schecked");
//                     }
//                 } else {
//                     htmls += '<div id=' + rid + ' class="addr-box" data-s=' + rid + ' data-prov=' + $(".shshregions").attr('data-province') + 'onclick="sueraddr(this)" >' +
//                         '<label class="addrname">' + $(".shnames").val() + '</label>' +
//                         '<label class="addraddr" data-province=' + $(".shshregions").attr('data-province') +
//                         ' data-city=' + $(".shshregions").attr('data-city') + ' data-area=' + $(".shshregions").attr('data-area') + ' data-address=' + $(".shphones").val() +
//                         '>' + $(".shshregions").attr('data-province') + $(".shshregions").attr('data-city') + $(".shshregions").attr('data-area') + $(".xxaddr").val() + '</label>' +
//                         '<label class="addrphone">' + $(".shphones").val() + '</label>' +
//                         '<div class="addrsedit">' +
//                         '<label class="addrdefault">默认地址</label>' +
//                         '<label class="addrdel" addrsID=' + rid + ' onclick="addressdel(this)">删除</label>' +
//                         ' <label class="addredit"  onclick="addressedit(this)">编辑</label>' +
//                         '</div></div> ';
//                     $(".addrselect").prepend(htmls);
//                     if ($("#checkbox_c1").prop("checked")) {
//                         $(".addrdefault").attr("class", "addrdefault");
//                         $("#" + rid + ">.addrsedit>.addrdefault").addClass("schecked");
//                     }
//                 }
//                 ////////
//                 $(".oraddrs>.address-name").text($(".shnames").val());
//                 $(".oraddrs>.address-addr").text($(".shshregions").text() + $(".xxaddr").val());
//                 $(".oraddrs>.telephone").text($(".shphones").val());
//                 $('.oraddrs').attr('data-s', rid);
//                 $("body").css("overflow", "auto");
//                 $(".coveredit").css("display", "none");
//                 $(".coveredit").attr("class", "coveredit");
//                 $('.oraddrs').attr('data-prov', province);
//             } else {
//                 console.log(msg);
//                 tips('notice', '保存出错', 2000, 'weui_icon_notice');
//             }
//         }
//     });
// }

// ///////地址选择返回/////
// function csback(label) {
//     $("body").css("overflow", "auto");
//     $(".coversel").css("display", "none");

// }
// ///////地址编辑返回/////
// function ceback(label) {
//     $(".coversel").css("display", "block");
//     $(".coveredit").css("display", "none");
//     $(".coveredit").attr("class", "coveredit");

// }
// ///////地区选择/////
// function selregion(label) {
//     $(".coverregion").css("display", "block");

// }
// ///////关闭地区选择/////
// function gbselregion(label) {
//     $(".coverregion").css("display", "none");
// }
// ///////完成地区选择/////
// function wcselregion(label) {
//     var strregion;
//     if ($("#" + addressIdS[0]).val() != "请选择") {
//         strregion = $("#" + addressIdS[0]).val();
//         $('.shshregions').attr('data-province', $("#" + addressIdS[0]).val());
//     } else {
//         tips('notice', '选择省份!', 1500, 'weui_icon_notice');
//         return false;
//     }

//     if ($("#" + addressIdS[1]).val() != "请选择") {
//         strregion = strregion + $("#" + addressIdS[1]).val();
//         $('.shshregions').attr('data-city', $("#" + addressIdS[1]).val());
//     } else {
//         tips('notice', '选择城市!', 1500, 'weui_icon_notice');
//         return false;
//     }


//     if ($("#" + addressIdS[2]).val() != "请选择") {
//         strregion = strregion + $("#" + addressIdS[2]).val();
//         $('.shshregions').attr('data-area', $("#" + addressIdS[2]).val());
//     } else {
//         tips('notice', '选择地区!', 1500, 'weui_icon_notice');
//         return false;
//     }

//     $(".shshregions").text(strregion);
//     $(".shregion").val(strregion);
//     $(".coverregion").css("display", "none");
// }
// ////////打开优惠///////
// function selyhxx(label) {
//     $("body").css("overflow", "hidden");
//     $(".coveryouhui").css("display", "block");
// }
// ////////打开优惠///////
// function gbyhxx(label) {


//     var tempObj = $('.yhcardactive');

//     if (tempObj.length <= 0) {
//         tips('notice', '请选择一张优惠券', 1500, 'weui_icon_notice');
//         return false;
//     }

//     var rules = tempObj.attr('data-rules');
//     var type = tempObj.attr('data-type');
//     var cid = tempObj.attr('data-couponid');
//     var totalprice = parseFloat($('.ordertotal').attr('data-allprice'));
//     var youhui = parseFloat($('.selyhs').attr('data-old'));

//     // var newtotal=0;
//     if (type == '0') {
//         var newtotal = totalprice - parseFloat(rules);
//         var newyouhui = youhui + parseFloat(rules);
//     }
//     if (type == '1') {
//         var newtotal = totalprice * parseFloat(rules);
//         var newyouhui = totalprice - newtotal + youhui;
//     }
//     if (type == '2') {
//         var rule = rules.split('/');
//         if (totalprice > rule[0]) {
//             tips('notice', '您的订单无法使用此优惠券', 1500, 'weui_icon_notice');
//             return false;
//         }
//         else {
//             var newtotal = totalprice - parseFloat(rule[1]);
//             var newyouhui = youhui + parseFloat(rule[1]);
//         }
//     }
//     $('.ordertotal').attr('datatotal', newtotal);
//     $('#price-box').html(newtotal);
//     $('.selyhs').attr('data-s', newyouhui).html("￥ -" + newyouhui).attr('data-cid', cid);
//     $('#CouponId').val(cid);
//     //关闭优惠券选择页面
//     $("body").css("overflow", "auto");
//     $(".coveryouhui").css("display", "none");


// }
// /////////////选择优惠券///////
// function selectyhcard(label) {
//     $(".yhcardactive").removeClass("yhcardactive");
//     $(label).addClass("yhcardactive");

// }
// ///////打开配送方式/////
// function seldistr(label) {
//     if ($(label).attr("datalx") == "0") {
//         $(".distrkd").attr("datasz", $(label).attr("datasz"));
//         $(".distrkd>label").html('快递 ');
//         seldistrs($(".distrkd"));
//     } else {
//         $(".distrzt").attr("datamd", $(label).attr("datamd"));
//         seldistrs($(".distrzt"));
//     }
//     $("body").css("overflow", "hidden");
//     $(".coverdistr").css("display", "block");
// }
// ///////选择配送方式/////
// function seldistrs(div) {
//     if (!$(div).hasClass("distractive")) {
//         if ($(div).hasClass("distrkd")) {
//             $(".distrkd>label").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".distrkd").addClass("distractive");
//             $(".distrzt>label").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".distrzt").removeClass("distractive");
//         } else {
//             $(".distrzt>label").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".distrzt").addClass("distractive");
//             $(".distrkd>label").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".distrkd").removeClass("distractive");
//         }
//     }
// }
// ///////关闭配送方式/////
// function gbseldistr(label) {
//     var totalprice = $(".ordertotal").attr('datatotal');
//     var freight = $("#freight").attr('data-s');
//     var status = $("#freight").attr('data-st');
//     var newtotal = parseFloat(totalprice) + parseFloat(freight);
//     var youhui = parseFloat($('.selyhs').attr('data-s'));
//     if ($(".distrkd").hasClass("distractive")) { //快递操作
//         if (status == 1) {
//             $("#freight").html('￥' + freight).attr('data-st', '0');
//             $('.ordertotal').attr('datatotal', newtotal).attr('data-allprice', newtotal + youhui);
//             $('#price-box').html(newtotal);
//         }
//         ;
//         $(".seldistr").attr("datalx", "0");
//         $(".seldistr").attr("datasz", $(".distrkd").attr("datasz"));
//         $(".seldistr").html('快递 ');
//         $('.oraddrs').attr('style', '');
//         $('.orderaddr').attr('style', '')
//         $('.orderaddr').show();
//     }
//     else {
//         if ($('.xzmd').val() == '0') {
//             tips('notice', '请选择自提门店', 1500, 'weui_icon_notice');
//             return false
//         }
//         var newtotal = parseFloat(totalprice) - parseFloat(freight);
//         if (status == 0) {
//             $("#freight").html('￥ 0.00').attr('data-st', '1');
//             $('.ordertotal').attr('datatotal', newtotal).attr('data-allprice', newtotal + youhui);
//             $('#price-box').html(newtotal);
//         }
//         ;
//         $(".seldistr").attr("datalx", "1");
//         $(".seldistr").attr("datamd", $(".distrzt>select").val());
//         $(".seldistr").html('自提 ' + ' <span>' + $(".distrzt>select").val() + '</span>');
//         $('.oraddrs').attr('style', 'z-index:-1;');
//         $('.orderaddr').attr('style', ';color:#ccc');
//         $('.orderaddr').hide();
//     }
//     $("body").css("overflow", "auto");
//     $(".coverdistr").css("display", "none");
// }
// ///////支付方式/////
// function payment(div) {
//     if (!$(div).hasClass("payactive")) {
//         $(".payactive>img").attr("src", imgurl + "weixuanze.png");
//         $(".payment").removeClass("payactive");
//         $(div).addClass("payactive");
//         $(".payactive>img").attr("src", imgurl + "xuanze2.png");
//         if ($(div).hasClass("payrccheck")) {
//             $(".payrcs").css("display", "block");
//             $(".payrccheck").css("border-width", "0px");
//             $(".czye").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".czye").addClass("payrcsactive");
//             $(".jlye").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".jlye").removeClass("payrcsactive");
//         } else {
//             $(".payrcs").css("display", "none");
//             $(".payrccheck").css("border-width", "1px");
//         }
//     }
// }
// ///////选择会员充值方式/////
// function paymentrcs(label) {
//     if (!$(label).hasClass("payrcsactive")) {
//         if ($(label).hasClass("czye")) {
//             $(".czye").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".czye").addClass("payrcsactive");
//             $(".jlye").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".jlye").removeClass("payrcsactive");
//         } else {
//             $(".jlye").css("background-image", "url('" + imgurl + "xuanze2.png')");
//             $(".jlye").addClass("payrcsactive");
//             $(".czye").css("background-image", "url('" + imgurl + "weixuanze.png')");
//             $(".czye").removeClass("payrcsactive");
//         }
//     }
// }
// ////////提交订单///////////
// function tjdd(label) {
//     var canSend = false;  //判断发货区域

//     var type = $('.seldistr').attr('datalx');
//     if (type == '1') {
//         var freight = 0;
//         var sendType = 'ZT';
//     } else {
//         var freight = parseFloat($('#freight').attr('data-s'));
//         var sendType = 'KD';
//     }


//     var province = $('.oraddrs').attr('data-prov');
//     if (sendType == 'KD') {
//         $.each(fjson, function (index, item) {
//             if (item.Area == province) {
//                 // console.log(item);
//                 canSend = true;
//             }
//             ;

//         })
//     }
//     ;
//     if (sendType == 'ZT') {
//         canSend = true;
//     }
//     // console.log(canSend);return false;


//     //
//     if (canSend)
//     {

//         if ($(".paywechatcheck").hasClass("payactive"))
//         {
//             $(".paylx").text("微信支付").attr('data-s', 'T');
//         }
//         else if ($(".paycashcheck").hasClass("payactive"))
//         {
//             $(".paylx").text("现金支付").attr('data-s', 'XJ');

//             if ($('#xjmd').val()=='0') {
//                 tips('notice', '请选择支付门店', 2000, 'weui_icon_notice');
//             }


//             //
//         }
//         else if ($(".payrccheck").hasClass("payactive"))
//         {
//             if ($(".czye").hasClass("payrcsactive")) {
//                 $(".paylx").text("充值支付").attr('data-s', 'YE');
//             } else if ($(".jlye").hasClass("payrcsactive")) {
//                 $(".paylx").text("充值奖励支付").attr('data-s', 'JL');
//             }
//         }
//         $(".payerje").text($(".ordertotal").attr("datatotal")+'元');

//         $("body").css("overflow", "hidden");
//         $(".coverpayer").css("display", "block");


//         var orderid = $("#orderid").html();
//         var totalprice = parseFloat($('.ordertotal').attr('datatotal'));
//         var payType = $('.paylx').attr('data-s');
//         var oldOrder = $('#oldOrder').attr('data-s');
//         var addid = $('.oraddrs').attr('data-s');
//         var cid = $('#CouponId').val();
//         // console.log(orderid+"|"+totalprice+"|"+freight+"|"+sendType+"|付款方式"+payType+"|"+oldOrder+"|"+addid);

//         tips('waiting', '正在保存订单数据');
//         $.ajax({
//             url: saveorder,
//             type: "post",
//             data: {
//                 "orderid": orderid,
//                 "totalPrice": totalprice,
//                 "freight": freight,
//                 "addid": addid,
//                 "sendType": sendType,
//                 "cid": cid,
//                 "payType": payType,
//                 'oldOrder': oldOrder,
//                 'xjstore':$('#xjmd').val()
//             },
//             dataType: "json",
//             success: function (msg) {
//                 $("#waiting").hide();
//                 if (msg.status == 'true')
//                 {
//                     if (msg.info == 'Success') {
//                         $('.btn-warning').removeClass('disabled');
//                         $('.payergb').remove();
//                     }
//                 }
//                 if (msg.status == 'false')
//                 {
//                     if (msg.info == 'hadOrder')
//                     {
//                         tips('notice', '订单已存在！', 2000, 'weui_icon_notice');
//                     }
//                     else if (msg.info == 'YENoEnough.')
//                     {
//                         tips('notice', '会员余额不足！', 2000, 'weui_icon_notice');
//                     }
//                     else
//                     {
//                         tips('notice','订单处理失败！',2000,'weui_icon_notice');
//                     }
//                     $('.btn-warning').attr('class','btn btn-warning disabled');
//                 }
//                 // console.log(msg);
//             }
//         })
//     } else {
//         tips('notice', '商家暂不对' + province + '地区提供发货，请更换收货地址', 2000, 'weui_icon_notice');
//         return false;
//     }
//     ;
// }

// function jfjs()
// {
//     var canSend = false;  //判断发货区域

//     var type = $('.seldistr').attr('datalx');
//     if (type == '1')
//     {
//         var sendType = 'ZT';
//     } 
//     else 
//     {
//         var sendType = 'KD';
//     }
//     var province = $('.oraddrs').attr('data-prov');
//     if (sendType == 'KD')
//     {
//         $.each(fjson, function (index, item)
//         {
//             if (item.Area == province) {
//                 canSend = true;
//             }
//         });
//     }
//     if (sendType == 'ZT') {
//         canSend = true;
//     }
//     if (canSend)
//     {
//         var orderid = $("#orderid").html();
//         var totalprice = parseFloat($('.ordertotal').attr('datatotal'));
//         var oldOrder = $('#oldOrder').attr('data-s');
//         var addid = $('.oraddrs').attr('data-s');

//         tips('waiting', '正在保存订单数据');
//         $.ajax({
//             url: saveorder,
//             type: "post",
//             data: {
//                 "orderid": orderid,
//                 "totalPrice": totalprice,
//                 "addid": addid,
//                 "sendType": sendType,
//                 'oldOrder': oldOrder,
//             },
//             dataType: "json",
//             success: function (msg) {
//                 $("#waiting").hide();
//                 if (msg.status == 'true')
//                 {
//                   window.location.href=gomyorder;
//                 }
//                 if (msg.status == 'false')
//                 {
//                     if (msg.info == 'hadOrder')
//                     {
//                         tips('notice', '订单已存在！', 2000, 'weui_icon_notice');
//                     }
//                     else if (msg.info == 'YENoEnough.')
//                     {
//                         tips('notice', '会员余额不足！', 2000, 'weui_icon_notice');
//                     }
//                     else
//                     {
//                         tips('notice','订单处理失败！',2000,'weui_icon_notice');
//                     }
//                 }
//             }
//         });
//     }
//     else
//     {
//         tips('notice', '商家暂不对' + province + '地区提供发货，请更换收货地址', 2000, 'weui_icon_notice');
//         return false;
//     }

// }


// function lgjs()
// {
//     var canSend = false;  //判断发货区域

//     var type = $('.seldistr').attr('datalx');
//     if (type == '1')
//     {
//         var sendType = 'ZT';
//     } 
//     else 
//     {
//         var sendType = 'KD';
//     }
//     var province = $('.oraddrs').attr('data-prov');
//     if (sendType == 'KD')
//     {
//         $.each(fjson, function (index, item)
//         {
//             if (item.Area == province) {
//                 canSend = true;
//             }
//         });
//     }
//     if (sendType == 'ZT') {
//         canSend = true;
//     }
//     if (canSend)
//     {
//         var orderid = $("#orderid").html();
//         var totalprice = parseFloat($('.ordertotal').attr('datatotal'));
//         var oldOrder = $('#oldOrder').attr('data-s');
//         var addid = $('.oraddrs').attr('data-s');

//         tips('waiting', '正在保存订单数据');
//         $.ajax({
//             url: saveorder,
//             type: "post",
//             data: {
//                 "orderid": orderid,
//                 "totalPrice": totalprice,
//                 "addid": addid,
//                 "sendType": sendType,
//                 'oldOrder': oldOrder,
//                 'cid':cid,
//                 'pic':pic,
//             },
//             dataType: "json",
//             success: function (msg) {
//                 $("#waiting").hide();
//                 if (msg.status == 'true')
//                 {
//                     if (msg.payStatus==1) 
//                     {
//                         window.location.href=subOrder.replace('ORDERIDREPLACE',orderid);
//                     }
//                     else
//                     {
//                         window.location.href=gomyorder;
//                     }
//                   //
//                 }
//                 if (msg.status == 'false')
//                 {
//                     if (msg.info == 'hadOrder')
//                     {
//                         tips('notice', '订单已存在！', 2000, 'weui_icon_notice');
//                     }
//                     else if (msg.info == 'YENoEnough.')
//                     {
//                         tips('notice', '会员余额不足！', 2000, 'weui_icon_notice');
//                     }
//                     else
//                     {
//                         tips('notice','订单处理失败！',2000,'weui_icon_notice');
//                     }
//                 }
//             }
//         });
//     }
//     else
//     {
//         tips('notice', '商家暂不对' + province + '地区提供发货，请更换收货地址', 2000, 'weui_icon_notice');
//         return false;
//     }

// }


// ///////关闭订单///////////
// function gbdd(span) {
//     $("body").css("overflow", "auto");
//     $(".coverpayer").css("display", "none");
//     $('.btn-warning').addClass('disabled');
// }
