var collectMark = false;

var havePrisex = false;

var contentInfoObj = $("#contentInfo").children("div");

function changeTag(a, b) {
    $.each(contentInfoObj, function () {
        if (b == $(this).attr('id')) {
            $(this).show();
        }
        else {
            $(this).hide();
        }
    });

    $.each($('.clearfix'), function () {
        $(this).find("a").removeClass("current");
    });

    $("." + $(a).attr("tag-type")).addClass("current");
    window.location.href = "#contentInfo";
}

var reviewG = 1;
var reviewM = 0;
var reviewB = 0;

var gIndex = 1;
var mIndex = 1;
var bIndex = 1;

var nowReview = 0;
var reviewLock = true;
//���۱�ǩ�л�
function changeComment(b, a) {
    $("#" + b + "-area").parent().closest(".content").find("ul").hide();
    $("#" + b + "-area").show();

    if (b == "positive") {
        nowReview = 0;
        if (reviewG == 2) {
            $("#loading span")[0].innerHTML = "已加载全部";
        }
    }
    else if (b == "general") {
        nowReview = 1;
        if (reviewM == 0) {
            sendRequest("Review", "&proid=" + $("#ProId").val() + "&rtype=1&pageIndex=" + mIndex);
        }
        else if (reviewM == 2) {
            $("#loading span")[0].innerHTML = "已加载全部";
        }

    }
    else if (b == "negative") {
        nowReview = 2;
        if (reviewB == 0) {
            sendRequest("Review", "&proid=" + $("#ProId").val() + "&rtype=2&pageIndex=" + bIndex);
        }
        else if (reviewB == 2) {
            $("#loading span")[0].innerHTML = "已加载全部";
        }
    }

    $(a).parent().closest("ul").find("li").removeClass("current");
    $(a).parent().addClass("current");
}

//��Ʒ�������
function checkQuantity() {

    var reg = new RegExp("^[0-9]*[1-9][0-9]*$");
    var quantityObj = $('#quantity');
    if (!reg.test(quantityObj.val())) {
        quantityObj.val("1");
    }
}
//����һ����Ʒ
function delPrdNum() {
    var quantityObj = $('#quantity');
    if (parseInt(quantityObj.val()) > 1) {
        quantityObj.val(parseInt(quantityObj.val()) - 1);
    }

}
//����һ����Ʒ
function addPrdNum() {
    var quantityObj = $('#quantity');
    quantityObj.val(parseInt(quantityObj.val()) + 1);
}


///////////////////////////////////////////////////////////////////////////////////////////
//��ȡ���Ա�ǩ
var arrtObjs = $('#skuList').find('dl');
//����ֵ����
var arrtValue = new Array();
//��ʼ��arrtValue
for (var i = 1; i < arrtObjs.length; i++) {
    arrtValue[i - 1] = "";
}

function selectAttr(parentIndex, index, data) {
    //selected
    var dataObj = $("#Arrt" + parentIndex);

    var ddObjs = $("#dl" + parentIndex).find("dd");

    for (var i = 0; i < ddObjs.length; i++) {
        if ($(ddObjs[i]).find("a").attr('id') == "AttrA" + parentIndex + "-" + index) {
            $(ddObjs[i]).find("a").addClass("selected");
        } else {
            $(ddObjs[i]).find("a").removeClass("selected");
        }

    }

    arrtValue[parentIndex - 1] = index;
    dataObj.val(data);
    checkGoods("getPrice");
}

//�������
function checkGoods(type) {
    //�ж������Ƿ�ȫ��ѡ��
    if (arrtValue.length == 0) {
        return false;
    } else {
        for (var i = 0; i < arrtValue.length; i++) {
            if (arrtValue[i] == "" || arrtValue[i] == null) {
                return false;
            }
        }
    }


    if (type == "buy") {
        return true;
    } else if (type == "getPrice") {
        var proid = $("#ProId").val();

        for (var i = 0; i < arrtValue.length; i++) {
            proid += '_' + arrtValue[i];
        }
        sendRequest("getPrice", proid);

    }
}
///////////////////////////////////////////////////////////////////////////////////////////

function wantToBuy(type) {
    if (checkGoods("buy")) {

        var sendData = '{"type":"' + type + '","products":[{"proID":"' + $("#ProId").val() + '","arrt":[';

        for (var i = 0; i < arrtValue.length; i++) {
            sendData += '"' + arrtValue[i] + '",';
        }

        sendData = sendData.substr(0, sendData.length - 1);
        sendData += '],"num":' + $('#quantity').val() + '}]} ';

        if (type == "buyNow") {
            window.location.href = "/Order/CheckOrder/?goodsInfo=" + encodeURI(sendData);
        } else {
            //sendRequest("getPrice", sendData);
        }
    } else {
        tips('请选择商品属性');
    }
}

//ֱ�ӹ���
function buyNow(data) {
    wantToBuy("buyNow");
}
//��ӵ����ﳵ
function addCart(obj) {
    if (checkGoods("buy")) {

        sendData = "&proID=" + $("#ProId").val() + "&arrt=";
        for (var i = 0; i < arrtValue.length; i++) {
            sendData += (arrtValue[i] + '_');
        }
        sendData = sendData.substr(0, sendData.length - 1);
        sendData += '&num=' + $('#quantity').val();
        console.log(sendData);
        sendRequest('cart', sendData);

    } else {
        tips('请选择商品属性');
    }
}

function sendRequest(type, dataPamar) {
    if (type == "Review") {
        reviewLock = false;
    }

    $.ajax({
        type: "POST",
        url: "../Handler/product.ashx",
        data: "type=" + type + "&data=" + dataPamar,
        dataType: "json",
        success: function (data) {
            if (data.status == "true") {
                if (type == "getPrice") {
                    if (!havePrisex) {
                        $("#pro-price").html(parseFloat(data.data.price).toFixed(2));
                    }

                } else if (type == "cart") {

                    tips('已加入购物车');
                    $("#cartNum").html(data.data.count);
                }
                else if (type == "collect") {
                    if (data.isCollect == "true") {
                        tips('已取消收藏');
                        $("#btnCollect").removeClass('collect');
                        $("#btnCollect").addClass('uncollect');
                        collectMark = false;
                    }
                    else {
                        tips('收藏成功');
                        $("#btnCollect").removeClass('uncollect');
                        $("#btnCollect").addClass('collect');
                        collectMark = true;
                    }
                }
                else if (type == "judgeCollect") {
                    if (data.data == "0") {
                        collectMark = false;
                    }
                    else {
                        collectMark = true;
                        $("#btnCollect").removeClass('uncollect');
                        $("#btnCollect").addClass('collect');
                    }
                }
                else if (type == "Review") {
                    if (data.info == "noData") {
                        $("#loading span")[0].innerHTML = "";
                        $("#review").html('<section class="system-empty" id="empty"><header class="h"><i class="icon-review-none"></i><p class="system-empty-title"><span>该商品暂无评价</span></p></header></section>');
                        reviewG = 2;
                        reviewM = 2;
                        reviewB = 2;
                    }
                    else if (data.info == "0") {
                        reviewG = 1;
                        if (data.data.next == "0") {
                            setReview(data.data.data, "positive-area", true);
                            reviewG = 2;
                        }
                        else {
                            setReview(data.data.data, "positive-area", false);
                            gIndex++;
                        }
                    }
                    else if (data.info == "1") {
                        reviewM = 1;
                        if (data.data.next == "0") {
                            setReview(data.data.data, "general-area", true);
                            reviewM = 2;
                        }
                        else {
                            setReview(data.data.data, "general-area", false);
                            mIndex++;
                        }

                    }
                    else if (data.info == "2") {
                        reviewB = 1;
                        if (data.data.next == "0") {
                            setReview(data.data.data, "negative-area", true);
                            reviewB = 2;
                        }
                        else {
                            setReview(data.data.data, "negative-area", false);
                            bIndex++;
                        }

                    }
                    else {
                        $("#prdDetailreview span")[0].innerHTML = "(" + data.data.all + ")";
                        $("#prdDetailPositive em")[0].innerHTML = "(" + data.data.g + ")";
                        $("#prdDetailMiddle em")[0].innerHTML = "(" + data.data.m + ")";
                        $("#prdDetailNavitive em")[0].innerHTML = "(" + data.data.b + ")";

                        var gVar = ((parseInt(data.data.g) / parseInt(data.data.all)) * 100).toFixed(2);
                        var mVar = ((parseInt(data.data.m) / parseInt(data.data.all)) * 100).toFixed(2);
                        var bVar = ((parseInt(data.data.b) / parseInt(data.data.all)) * 100).toFixed(2);

                        $(".review_rate header em")[0].innerHTML = gVar;

                        var reviewBarEMObj = $(".percent li em");
                        var reviewBarSpanObj = $(".percent li span span");

                        reviewBarEMObj[0].innerHTML = gVar + "%";
                        $(".percent li span span")[0].style.width = gVar + "%";

                        reviewBarEMObj[1].innerHTML = mVar + "%";
                        $(".percent li span span")[1].style.width = mVar + "%";

                        reviewBarEMObj[2].innerHTML = bVar + "%";
                        $(".percent li span span")[2].style.width = bVar + "%";

                        setReview(data.data.data, "positive-area", false);

                        gIndex++;
                    }
                    reviewLock = true;
                }
            } else {
                if (data.info == "userHaveNoLogin") {

                    tips('用户登录过期,正在自动登录。。。');
                    window.location.href = "/Home/index";
                }
                reviewLock = true;
            }
        },
        error: function (e) {
            reviewLock = true;
        }
    });
}

$("#productShare").click(function () {
    window.location.href = "/Product/GetShareCode";
});


//如果只有一个属性，就默认
function onlyOneArrt() {
    var tempArrtsDD = null;
    for (var i = 1; i < arrtObjs.length; i++) {
        tempArrtsDD = $(arrtObjs[i]).find("dd");
        if (tempArrtsDD.length == 1) {
            $(tempArrtsDD[0]).find("span").click();
        }
    }
    tempArrtsDD = null;
}


function collectPro() {
    if (collectMark) {
        //tips('该商品已收藏');
        sendRequest("collect", $("#ProId").val() + "&isCollect=true");
    }
    else {
        sendRequest("collect", $("#ProId").val() + "&isCollect=false");
    }
}

function getCollect() {
    sendRequest("judgeCollect", $("#ProId").val());
}

function getReview() {
    sendRequest("Review", "&proid=" + $("#ProId").val() + "&rtype=first&pageIndex=" + gIndex);
}

function setReview(content, id, end) {
    var setStr = "";
    for (var i = 0; i < content.length; i++) {
        setStr += '<li><p class="word">' + content[i].content + '</p><p class="name"><span>' + content[i].userId + '&nbsp;</span><span>' + content[i].date + '</span></p><p class="star"><b class="star-area"><s style="width:' + parseInt(content[i].cs) * 20 + '%;"></s></b></p></li>';
        //setStr += '<li><p class="word">' + content.content + '</p><p class="name"><span>'+content.userId+'&nbsp;</span><i class="icon-vip-3"></i><span>'+content.date+'</span></p><p class="star"><b class="star-area"><s style="width:'+parseInt(content.cs)*20+'%;"></s></b></p></li>';
    }

    if (end) {
        $("#loading span")[0].innerHTML = "已加载全部";
    }
    else {
        $("#loading span")[0].innerHTML = "点击加载更多";
    }

    $("#" + id).append(setStr);

}


$("#loading").click(function () {
    if (reviewLock) {

        if (nowReview == 0 && reviewG != 2) {
            sendRequest("Review", "&proid=" + $("#ProId").val() + "&rtype=0&pageIndex=" + gIndex);
        }
        else if (nowReview == 1 && reviewM != 2) {
            sendRequest("Review", "&proid=" + $("#ProId").val() + "&rtype=1&pageIndex=" + mIndex);
        }
        else if (nowReview == 2 && reviewB != 2) {
            sendRequest("Review", "&proid=" + $("#ProId").val() + "&rtype=2&pageIndex=" + bIndex);
        }

    }
});

function getPricex() {
    $.ajax({
        type: "POST",
        url: "../Handler/product.ashx",
        data: "type=pricex&data=" + $("#ProId").val(),
        dataType: "json",
        success: function (data) {
            if (data.info == "success") {
                $("#pro-price").html(parseFloat(data.data.price).toFixed(2));
                $("#pro-name").append("<p style=\"color:red;\">" + data.data.remark + "</p>")
                havePrisex = true;
            }

        },
        error: function (e) {

        }
    });
}
//document.body.clientHeight
$(function () {
    getCart("cartNum");
    judgeLogin("loginBtn");
    saveScene(window.location.search.substr(1));
    getCollect();
    onlyOneArrt();
    getPricex();
    getReview();


    //$('body').width();

});
//var navPointY = $('#TagNav').offset().top;
//$(window).scroll(function () {
//    if ($(document).scrollTop() >= navPointY) {
//        $("#TagNavFix").show();
//        $("#TagNav").hide();
//    }
//    else {
//        $("#TagNav").show();
//        $("#TagNavFix").hide();
//    }

//});

var bigImagesObj = $('#bigImages');
var bigImgObj = $('#bigImgImg');
var bigImgDivObj = $('#bigImgDiv');
var tempSmallObj = null;
var tempHeight = 0;
function viewBigImg(obj) {
    tempSmallObj = $(obj);
    bigImgObj.attr('src', tempSmallObj.attr('src'));

    tempHeight = (document.body.clientHeight - (document.body.clientWidth * tempSmallObj.height() / tempSmallObj.width())) / 2;

    bigImgDivObj.css("margin-top", tempHeight + "px");
    $(document.body).css("overflow", "hidden");
    bigImagesObj.show();
}

function viewBigImgReturn() {
    $(document.body).css("overflow", "auto");
    bigImagesObj.hide();
}


var tipsDivObj = $('#tipsDiv');
function tips(context) {
    tipsDivObj.html(context);
    tipsDivObj.show();
    setTimeout(function () {
        tipsDivObj.hide();
    }, 3000);
}