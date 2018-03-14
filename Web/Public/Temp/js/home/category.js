var bigClassObjs = $("#bigClass").find("li");
var smallClassObjs = $("#smallClass");
$(function () {

//    if (bigClassObjs.length > 0) {
//        $(bigClassObjs[0]).find("a").click();
//    }

});

function changeClass(id, obj) {

    window.location.href = "/Product/List/?classId=" + id + "&className=" + $(obj).attr("data-cname");
//    $.ajax({
//        type: "POST",
//        url: "../Handler/category.ashx",
//        data: "id=" + id,
//        dataType: "json",
//        success: function (data) {
//            if (data.status == "true") {
//                for (var i = 0; i < bigClassObjs.length; i++) {
//                    $(bigClassObjs[i]).find("a").removeClass("current");
//                }
//                $(obj).addClass("current");
//                outPrintSmallClass(data);
//            }
//            else {
//                if (data.info == "dataIsNull") {
//                    for (var i = 0; i < bigClassObjs.length; i++) {
//                        $(bigClassObjs[i]).find("a").removeClass("current");
//                    }
//                    $(obj).addClass("current");
//                    smallClassObjs.html('<li  style="width:100%; height:15em; line-height:15em; text-align:center; font-size:2em; color:#808080; vertical-align:middle;">此分类下没有商品</li>');
//                }
//            }
//        },
//        error: function (e) {

//        }
//    });
}

var tempSmallClassHTML = "";
function outPrintSmallClass(data) {
    tempSmallClassHTML = "";
    for (var i = 0; i < data.data.length; i++) {
        tempSmallClassHTML += '<li class="sc-pro-item" onclick="location.href=\'' + data.data[i].pageUrl + '\'"><div class="pro-panels pro-panels-2"><p class="p-img"><img src="' + data.data[i].proLogoImg + '" alt=""></p> <p class="p-name"><span>' + data.data[i].proName + '</span></p> <p class="p-promotion"><span>销量：' + data.data[i].salesCount + '</span></p> <p class="p-price"><b><em>¥' + data.data[i].basePrice + '</em></b></p></div></li>';
    }
    smallClassObjs.html(tempSmallClassHTML);
}