var saveBase64Img = function (base64Str, memberid,object) {
    $.ajax({
        type: "POST",
        url: "/Handler/publicInfo.ashx",
        data: { "type": "saveBase64Img", "data": base64Str,"memberid":memberid },
        dataType: "text",
        timeout: 20000,
        success: function (data) {
            if (data != "false") {
                object.attr("src", data);
            }
            else {

            }
        },
    });
};