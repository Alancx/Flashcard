var codeIsCheck = false;

//验证表单数据
$('#loginForm').submit(function () {
    var userID = $('#login_userName');
    var userPwd = $('#login_password');
    var authcodeObj = $('#authcode');

    if (userID.val().trim() == "") {
        $('#errorMsgDiv').html("用户名不能为空。");
        userID.focus();
        return false;
    }
    if (userPwd.val().trim() == "") {
        $('#errorMsgDiv').html("密码不能为空。");
        userPwd.focus();
        return false;
    }
    if (authcodeObj.val().trim() == "") {
        $('#errorMsgDiv').html("验证码不能为空。");
        authcodeObj.focus();
        return false;
    }
    if (!codeIsCheck) {
        $('#errorMsgDiv').html("验证码错误。");
        authcodeObj.focus();
        return false;
    }
    $('#errorMsgDiv').html("");
    return true;
});

$('#loginBtn').click(function () {
    $('#loginForm').submit();
});

//失去焦点验证验证码
$('#authcode').keyup(function () {
    if ($('#authcode').val().length == 4) {
        checkRandomCode();
    }
    else {
        codeIsCheck = false;
        var codeStatus = $('#randomCodeError');
        codeStatus.removeClass("icon-error");
        codeStatus.removeClass("icon-ok");
    }
});

function checkRandomCode() {
    var authCodeObj = $('#authcode');
    var codeStatus = $('#randomCodeError');
    codeStatus.removeClass("icon-error");
    codeStatus.removeClass("icon-ok");
    if (authCodeObj.val() == "") {
        codeStatus.addClass("icon-error");
    }
    else {
        $.ajax({
            type: "POST",
            url: "../Handler/checkAuthCode.ashx",
            data: "code=" + authCodeObj.val(),
            dataType: "text",
            success: function (data) {
                if (data == "true") {
                    codeStatus.addClass("icon-ok");
                    codeIsCheck = true;
                    return true;
                }
                else {
                    codeStatus.addClass("icon-error");
                    chgRandomCodeForLogin();
                    codeIsCheck = false;
                    return false;
                }
            },
            error: function (e) {
                codeStatus.removeClass("icon-error");
                chgRandomCodeForLogin();
                codeIsCheck = false;
                return false;
            }
        });
    }
}

//刷新验证码
function chgRandomCodeForLogin() {
    document.getElementById("randomCode").src = "/Fun/RandomCode?t=" + new Date().getTime();
}

