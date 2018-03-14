function showInvoice(a) {
    if ($("#invoice-header").hasClass("icon-arrows-top")) {
        $("#invoice-header").attr("class", "icon-arrows-down");
        $("#invoice-body").show();
        if ("1" == a)
        { $("#invoice-info").hide() }
    }

    else {
        $("#invoice-header").attr("class", "icon-arrows-top");
        $("#invoice-body").hide();
        if ("1" == a) {
            if (2 == gid("order-titleType").value) {
                $("#invoice-info").html($("#invoiceTitle").val())
            }
            else { $("#invoice-info").html("\u4e2a\u4eba") }
            $("#invoice-info").show()
        }
    }
}



function selectInvoice (e, b) {
    var d = $(e), c = $("#invoiceTitle");
     $("#order-titleType").val(b);
    if (b == 2) {
        $("#receipt-1").attr("checked", false);
        c.focus()
    }
    else {
        $("#receipt-2").attr("checked", false);
        receiptTypeText = "\u4e2a\u4eba"
    }
}

function subOrder() {
    var addressTemp = $.trim($("#addressTemp").val());
    if (addressTemp == "") {
        alert("未找到收货地址！");
        return false;
    } else {
        $("#addressID").val(addressTemp);
        $('#checkOrderForm').submit();
    }
}
var totalPriceTemp = 0;
function getPrice() {
    var productInfo = $(".osc-pro-area");

    for (var i = 0; i < productInfo.length; i++) {
      totalPriceTemp+=parseFloat($($(".osc-pro-area")[i]).find(".b li:last span").html());
    }

  $("#huawei_total_price").html("¥&nbsp;" + totalPriceTemp.toFixed(2));


  //totalPriceTemp += parseFloat($("#Freight").val());

  //parseFloat($("#discountTotalPrice").html())
  $("#total_price").html("¥&nbsp;" + (parseFloat($("#order-deliveryCharge").html().replace(/\¥&nbsp;/g, "")) + totalPriceTemp - parseFloat($("#discountTotalPrice").html())).toFixed(2));
  totalPriceTemp = 0;
}


function checkCCheck(obj) {
    if ($(obj).attr("checked") == true) {
        
        $("#cList").show();
    }
    else {
        $("#couponId").val("NULL");
        var rbObj = $(".coupon_rb");
        for (var i = 0; i < rbObj.length; i++) {
            $(rbObj[i]).attr("checked", false);
        }
        $("#discountTotalPrice").html("0.00");
        getPrice();
        $("#cList").hide();
    }
}

function selectCoupon(obj) {
    var cmoney = 0;

    switch ($(obj).attr("data-ctype")) {
        case "0":
            cmoney = parseFloat($(obj).attr("data-rules"));
            break;
        case "1":
            cmoney = parseFloat($("#huawei_total_price").html().replace(/\¥&nbsp;/g, "")) * (1-parseFloat($(obj).attr("data-rules")));
            break;
        case "2":
            cmoney = parseFloat($(obj).attr("data-rules").splie('/')[1]);
            break;
        default:
            cmoney = 0;
    }

    if (cmoney > parseFloat($("#huawei_total_price").html().replace(/\¥&nbsp;/g, ""))) {
        cmoney = parseFloat($("#huawei_total_price").html().replace(/\¥&nbsp;/g, ""));
    }

    $("#discountTotalPrice").html(cmoney.toFixed(2));
    $("#couponId").val($(obj).attr("data-cid"));
    getPrice();
}