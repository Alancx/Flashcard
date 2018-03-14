function del() {

    var sendData = "";
    var checkedId =new Array();
    $("#product-list").find("input[type=checkbox]").each(function () {
        if ($(this).attr("checked") == true) {
            sendData += $(this).attr("value") + ",";
            checkedId.push($(this).attr("value"));
        }
    });
    sendData = sendData.substr(0, sendData.length - 1);

    $.ajax({
        type: "POST",
        url: "../Handler/shoppingCart.ashx",
        data: "type=del&data=" + sendData,
        dataType: "json",
        success: function (data) {
            if (data.status == "true") {
                $("#product-list").children().each(function () {
                    if ($(this).find("input[type=checkbox]").attr("checked") == true) {
                        $(this).remove();
                        calculate();
                    }
                });
            }
            else {
                alert("删除失败");
            }
        },
        error: function (e) {

        }
    });
}

function editProNum(obj, proid) {
    if ($(obj).attr("old-num") != $(obj).val()) {
        $.ajax({
            type: "POST",
            url: "../Handler/shoppingCart.ashx",
            data: "type=edit&data=" + proid + "," + $(obj).val(),
            dataType: "json",
            success: function (data) {
                if (data.status == "true") {
                    $(obj).attr("old-num", $(obj).val());
                    calculate();
                }
                else {
                    alert("修改失败");
                    $(obj).val($(obj).attr("old-num"));
                }
            },
            error: function (e) {

            }
        });
    }
}
//var tempx = null;
function addProNum(obj, proid, num) {
    //tempx = $($(obj).parent(".p-amount"));
    var inputBox = $($(obj).parent(".p-amount")).find("span input")[0];
    //alert(inputBox);
    
    if ((parseFloat(inputBox.value) + parseFloat(num)) <= 0) {

    }
    else {
        inputBox.value = parseFloat(inputBox.value) + parseFloat(num);
        editProNum(inputBox, proid);
    }
}

var totalPrice = 0;
var proCount = 0;
function calculate() {

    $("#product-list").find("input[type=text]").each(function () {
        if ($(this).parents(".pro-panels").find("input[type=checkbox]").attr("checked")) {
            totalPrice += $(this).attr("price") * $(this).val();
            proCount++;
        }
    });
    if (proCount<=0) {
        conteneDisplay(false, true);
    }

    $("#totalPrice").html("¥&nbsp;" + totalPrice.toFixed(2));
    totalPrice = 0;
    proCount = 0;
}

function calculateX() {

    $("#product-list").find("input[type=text]").each(function () {
        if ($(this).parents(".pro-panels").find("input[type=checkbox]").attr("checked")) {
            totalPrice += $(this).attr("price") * $(this).val();
        }
    });

    $("#totalPrice").html("¥&nbsp;" + totalPrice.toFixed(2));
    totalPrice = 0;
    proCount = 0;
}


function conteneDisplay(c, s) {
    var contentObj = $("#system-content-section");
    var emptyAreaObj = $("#system-empty-area");
    var subbtnObj = $("#button-area-1");
    if (c) {
        contentObj.css("display", "block");
        subbtnObj.css("display", "block");
    }
    else {
        contentObj.css("display", "none");
        subbtnObj.css("display", "none");
    }

    if (s) {
        emptyAreaObj.css("display", "block");
    }
    else {
        emptyAreaObj.css("display", "none");
    }
}

function subOrder() {
    var checkPro = "";
    var tokens = [];    // 商铺token数组
    $("#product-list").find("input[type=text]").each(function () {
        if ($(this).parents(".pro-panels").find("input[type=checkbox]").attr("checked")) {
            checkPro += $(this).attr("proidcard") + ",";
            var _token = $(this).attr("data-token");
            if ($.inArray(_token, tokens) == -1) {
                tokens.push(_token);
            }
        }
    });

    if (checkPro == "") {
        alert("请选择至少一个商品。");
        return false;
    }

    // 添加判断同一个商户判断
    if (tokens.length > 1) {
        alert("不同商铺商品不能一起结算！");
        return false;
    }
    // &mctoken=" + tokens.join("") + "
    window.location.href = "/Order/CheckOrder/?type=cart&proIdCard=" + checkPro;
}