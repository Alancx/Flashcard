
function pay() {
    var formObj = $("#gotoPay");
    formObj.submit();
}

function selectPaymentType(e, a) {
    var d = $(e), c = $("#order-paymentMethod"), g = $("#order-paymentType"), f = d.val(), b = d.attr("data-type");
    if (a == 1) {
        $("#pamentTypeRadio-2").attr("checked", false);
        $("#pamentTypeRadio-7").attr("checked", false);
        $("#pamentTypeRadio-17").attr("checked", false);
    }
    else {
        if (a == 2) {
            $("#pamentTypeRadio-1").attr("checked", false);
            $("#pamentTypeRadio-7").attr("checked", false);
            $("#pamentTypeRadio-17").attr("checked", false);
        }
        else {
            if (a == 7) {
                $("#pamentTypeRadio-1").attr("checked", false);
                $("#pamentTypeRadio-2").attr("checked", false);
                $("#pamentTypeRadio-17").attr("checked", false);
            }
            else {
                if (a == 17) {
                    $("#pamentTypeRadio-1").attr("checked", false);
                    $("#pamentTypeRadio-2").attr("checked", false);
                    $("#pamentTypeRadio-7").attr("checked", false);

                }
            }
        }
    }
    c.val(f);
    g.val(b)
}
function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
    } else {
        return false;
    }
}

$(function () {
    if (isWeiXin() || true) {

        $.ajax({
            type: "POST",
            url: "/Handler/wxpay.ashx",
            data: "type=getOpenid&data=" + $("#order-orderCode").val(),
            dataType: "json",
            success: function (data) {
                //alert(data.status);
                //window.location.href = data.status;
            },
            error: function (e) {

            }
        });
        var d = $("#pamentTypeRadio-2");
        d.attr("checked", true);
        $("#order-paymentMethod").val(d.val());
        $("#order-paymentType").val(d.attr("data-type"));

        $("#WxpayDL").css("display", "block");
    }
    else {
        $("#pamentTypeRadio-1").attr("checked", true);

        var d = $("#pamentTypeRadio-1");
        d.attr("checked", true);
        $("#order-paymentMethod").val(d.val());
        $("#order-paymentType").val(d.attr("data-type"));

        $("#AlipayDL").css("display", "block");
    }
});
