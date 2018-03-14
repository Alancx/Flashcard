var codeIsCheck = false;

//验证表单数据
$('#registerForm').submit(function () {

    if (phoneCheck('username')) {
        var userID = $('#username');
        var userPwd = $('#password');
        var authcodeObj = $('#randomCode');
        var chkAgreeObj = $('#chkAgreeTerm');

        if (userPwd.val().trim() == "") {
            $('#showErrorMsg').html("密码不能为空。");
            userPwd.focus();
            return false;
        }
        if (authcodeObj.val().trim() == "") {
            $('#showErrorMsg').html("验证码不能为空。");
            authcodeObj.focus();
            return false;
        }
        if (!codeIsCheck) {
            $('#showErrorMsg').html("验证码输入错误。");
            authcodeObj.focus();
            return false;
        }

        if ($("#password").val() != $("#repassword").val()) {
            $('#showErrorMsg').html("两次输入密码不一致。");
            authcodeObj.focus();
            return false;
        }

        $('#showErrorMsg').html("");
        return true;
    }
    else {
        return false;
    }


});

$('#regBtn').click(function () {
    $('#registerForm').submit();
});

$('#username').blur(function () {
    phoneCheck('username');
});

////验证手机格式
function phoneCheck(obj) {
    var objName = eval("document.all." + obj);
    var pattern = /^0?1[0-9][0-9]\d{8}$/;
    if (!pattern.test(objName.value)) {
        $('#showErrorMsg').html("手机号格式错误。");
        document.getElementById("randomCode").setAttribute("disabled", true);
        document.getElementById("sendAuthCode").setAttribute("disabled", true);
        objName.focus();
        return false;
    }
    else {
        $('#showErrorMsg').html("");
        document.getElementById("randomCode").removeAttribute("disabled");
        document.getElementById("sendAuthCode").removeAttribute("disabled");
         return true;
    }
   
}  

//密码显示
var changeTypeObj = $("#changeType");
var showPwdObj = $("#showPwd");
var passwordObj = $("#password");
var isShowPwd = true;

changeTypeObj.click(function () {
    if (isShowPwd) {
        showPwdObj.val(passwordObj.val());
        changeTypeObj.html("隐藏");
        isShowPwd = false;
        passwordObj.hide();
        showPwdObj.show();
    }
    else if (!isShowPwd) {
        passwordObj.val(showPwdObj.val());
        changeTypeObj.html("显示");
        isShowPwd = true;
        passwordObj.show();
        showPwdObj.hide();
    }
});

showPwdObj.blur(function () {
    passwordObj.val(showPwdObj.val());
});

passwordObj.blur(function () {
    showPwdObj.val(passwordObj.val());
});



//密码显示
var rechangeTypeObj = $("#rechangeType");
var reshowPwdObj = $("#reshowPwd");
var repasswordObj = $("#repassword");
var reisShowPwd = true;

rechangeTypeObj.click(function () {
    if (reisShowPwd) {
        reshowPwdObj.val(passwordObj.val());
        rechangeTypeObj.html("隐藏");
        reisShowPwd = false;
        repasswordObj.hide();
        reshowPwdObj.show();
    }
    else if (!reisShowPwd) {
        repasswordObj.val(showPwdObj.val());
        rechangeTypeObj.html("显示");
        reisShowPwd = true;
        repasswordObj.show();
        reshowPwdObj.hide();
    }
});

reshowPwdObj.blur(function () {
    repasswordObj.val(reshowPwdObj.val());
});

repasswordObj.blur(function () {
    showPwdObj.val(repasswordObj.val());
});




//短信验证
var countdown = 60;
var codeNO = "";
function getSMSAuthCode(obj) {

    if (countdown == 0) {
        obj.removeAttribute("disabled");
        obj.value = "免费获取验证码";
        countdown = 60;
    } else {
        obj.setAttribute("disabled", true);
        obj.value = countdown + "秒后可重新发送。";
        countdown--;
        setTimeout(function () { getSMSAuthCode(obj) }, 1000);
    }
}

function sendSMSAuthCode(obj) {
    if ($("#username").val() == "") {
        $('#showErrorMsg').html("请填写正确的手机号。");
    }
    else {
        $('#showErrorMsg').html("");
        $.ajax({
            type: "POST",
            url: "../Handler/SMAuthCode.ashx",
            data: "type=send&&data=" + $("#username").val() + "",
            dataType: "json",
            success: function (data) {
                if (data.status == "true") {
                    codeNO = data.codeNo;
                    getSMSAuthCode(obj);
                    $('#showErrorMsg').html("获取验证码成功，序号：" + codeNO + "，请查收。");
                }
                else {
                    countdown = 60;
                    $('#showErrorMsg').html("获取验证码失败，请重试。");
                }
            },
            error: function (xhr, type, exception) {
               // alert(xhr.responseText, "Failed");
                countdown = 60;
                $('#showErrorMsg').html("获取验证码失败，请重试。");
            }
        });

    }
}

function checkAuthCode() {
    $('#randomCodeError').removeClass("icon-error");
    $('#randomCodeError').removeClass("icon-ok");
    if ($("#randomCode").val() == "") {
//        $('#randomCodeError').addClass("icon-error");
        //        $('#showErrorMsg').html("请填写验证码。");
        codeIsCheck = false;
    }
    else {
        $('#showErrorMsg').html("");
        $.ajax({
            type: "POST",
            url: "../Handler/SMAuthCode.ashx",
            data: "type=check&&data=" + $("#username").val() + $("#randomCode").val(),
            dataType: "json",
            success: function (data) {
                if (data.status == "true") {
                    $('#randomCodeError').addClass("icon-ok");
                    codeIsCheck = true;
                }
                else {
                    $('#randomCodeError').addClass("icon-error");
                    codeIsCheck = false;
                }
            },
            error: function (xhr, type, exception) {
                $('#randomCodeError').addClass("icon-error");
                $('#showErrorMsg').html("验证码校验失败。");
                codeIsCheck = false;
            }
        });

    }
}


$('#randomCode').keyup(function () {
    if ($('#randomCode').val().length == 6) {
        checkAuthCode();
    }
    else {
        codeIsCheck = false;
        var codeStatus = $('#randomCodeError');
        codeStatus.removeClass("icon-error");
        codeStatus.removeClass("icon-ok");
    }
});
