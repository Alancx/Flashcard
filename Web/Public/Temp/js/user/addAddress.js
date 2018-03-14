
var addressIdS = null;
function sub() {
    if (checkData()) {

        $("#provinceS").val($("#" + addressIdS[0]).val());
        $("#cityS").val($("#" + addressIdS[1]).val());
        $("#areaS").val($("#" + addressIdS[2]).val());
        $('#addAddress').submit();
    }
}

function checkData() {
    //联系人不能为空
    if ($("#consignee").val() == "") {
        $("#errorMsgDiv").html("联系人不能为空。");
        $("#consignee").focus();
        return false;
    }
    //不能为空
    if ($("#mobile").val() == "") {
        $("#errorMsgDiv").html("联系方式不能为空。");
        $("#mobile").focus();
        return false;
    }

    if ($("#" + addressIdS[0]).val() == "请选择") {
        $("#errorMsgDiv").html("请选择一个省份。");
        $("#" + addressIdS[0]).focus();
        return false;
    }

    if ($("#" + addressIdS[1]).val() == "请选择") {
        $("#errorMsgDiv").html("请选择一个城市。");
        $("#" + addressIdS[1]).focus();
        return false;
    }

    if ($("#" + addressIdS[2]).val() == "请选择") {
        $("#errorMsgDiv").html("请选择一个地区。");
        $("#" + addressIdS[2]).focus();
        return false;
    }

    if ($("#address").val() == "") {
        $("#errorMsgDiv").html("地址不能为空。");
        $("#address").focus();
        return false;
    }
    $("#errorMsgDiv").html("");
    return true;
}

$(function () {
    addressIdS=pList("addressPCD", "ec_linkSelect");
});