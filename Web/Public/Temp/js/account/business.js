/**
* 变更帐号
*/
function chgHWIDSubmitFun() {
    var userID = $("#chgHWIDform input[name='formBean.userID']").val();
    var newUserAccount = $("#chgHWIDform input[name='formBean.username']").val();
    var newAccountType = $("#chgHWIDform input[name='formBean.accountType']").val();
    var password = $("#chgHWIDform input[name='formBean.password']").val();
    var oldUserAccount = $("#chgHWIDform input[name='formBean.oldUserAccount']").val();
    var oldAccountType = $("#chgHWIDform input[name='formBean.oldAccountType']").val();
    var oauthCode = $("#chgHWIDform input[name='formBean.authCode']").val();
    var nonce = $("#chgHWIDform input[name='formBean.nonce']").val();
    var countryCode = $("#chgHWIDform select[name='formBean.countryCode']").val();
    var verifiedFlag = "0";
    var buttonSmt = $("#btnSubmit");
    //让按钮失效，为了保证按钮不会被多次点击
    buttonSmt.attr("disabled", "disabled");

    password = encodeURIComponent(password);

    if ("2" == newAccountType) {
        newUserAccount = countryCode + newUserAccount;
        newUserAccount = newUserAccount.replace("+", "00");
        verifiedFlag = "1";
    }

    var strParms = "chgHWID?userID=" + userID + "&newUserAccount=" + newUserAccount + "&newAccountType=" + newAccountType + "&password=" + password + "&oldUserAccount=" + oldUserAccount
	 + "&oldAccountType=" + oldAccountType + "&oauthCode=" + oauthCode + "&nonce=" + nonce + "&sourceflag=web&lang=" + lang;
    ajaxJSONP(strParms, function (data) {
        var flag = data.flag;
        if (flag == "1") {
            gotoUrl("chghwid_success.jsp?flag=1&userID=" + userID + "&oldAccountType=" + oldAccountType + "&oldUserAccount=" + oldUserAccount + "&newAccountType="
						 + newAccountType + "&newUserAccount=" + newUserAccount + "&verifiedFlag=" + verifiedFlag + "&lang=" + lang);
            return true;
        }
        else if (flag == "0") {
            gotoUrl("chghwid_success.jsp?flag=0&errorCode=" + data.errorCode + "&description=" + data.description + "&lang=" + lang);
            return true;
        }
        else {
            $("#showerror").html("变更帐号失败！");
            buttonSmt.removeAttr("disabled");
            return false;
        }

    });

}


/**
* 设置用户信息
*/
function setUserInfoFun() {
    var userID = $("#setUserInfoForm input[name='userinfobean.userID']").val();
    var nickName = $("#setUserInfoForm input[name='userinfobean.nickName']").val();
    var gender = $("#setUserInfoForm select[name='userinfobean.gender']").val();
    var birthDate = $("#setUserInfoForm input[name='userinfobean.birthDate']").val();
    var nationalCode = $("#setUserInfoForm select[name='userinfobean.nationalCode']").val();
    var tempSTToken = $("#setUserInfoForm input[name='userinfobean.tempSTToken']").val();
    var devID = $("#setUserInfoForm input[name='userinfobean.devID']").val();
    var devType = $("#setUserInfoForm input[name='userinfobean.devType']").val();
    var channel = $("#setUserInfoForm input[name='userinfobean.channel']").val();
    var appID = $("#setUserInfoForm input[name='userinfobean.appID']").val();
    var buttonSmt = $("#btnSubmit");
    //让按钮失效，为了保证按钮不会被多次点击
    buttonSmt.attr("disabled", "disabled");

    var strParms = "setUserInfo?userID=" + userID + "&nickName=" + nickName + "&gender=" + gender + "&birthDate=" + birthDate + "&nationalCode=" + nationalCode
	 + "&tempSTToken=" + tempSTToken + "&devID=" + devID + "&devType=" + devType + "&channel=" + channel + "&appID=" + appID + "&sourceflag=web&lang=" + lang;
    ajaxJSONP(strParms, function (data) {
        var flag = data.flag;
        if (flag == "1") {
            gotoUrl("setuserinfo_success.jsp?flag=1&lang=" + lang);
            return true;
        }
        else if (flag == "0") {
            gotoUrl("setuserinfo_success.jsp?flag=0&errorCode=" + data.errorCode + "&description=" + data.description + "&lang=" + lang);
            return true;
        }
        else {
            $("#showerror").html("设置用户信息失败！");
            buttonSmt.removeAttr("disabled");
            return false;
        }

    });

}


/**
* 找回密码业务处理部分函数1
*/
function getSafeAccountListFun() {
    var username = $("#resetByIdFrom input[name='formBean.username']").val();
    var reqClientType = $("#resetByIdFrom input[name='formBean.reqClientType']").val();
    var buttonSmt = $("#btnSubmit");
    //让按钮失效，为了保证按钮不会被多次点击
    buttonSmt.attr("disabled", "disabled");

    username = encodeURIComponent(username);

    var strParms = "getSafeAccountList";
    var dataParms = {
        "username": username,
        "reqClientType": reqClientType,
        "sourceflag": "web",
        "lang": lang
    };

    ajaxHandler(strParms, dataParms, function (data) {
        var flag = data.errorCode;
        if (flag == "0") {
            gotoUrl("resetpwdchoosetype.jsp?username=" + username + "&reqClientType=" + reqClientType + "&emaillist=" + data.emaillist + "&phonelist=" + data.phonelist + "&lang=" + lang);
            return true;
        }
        else if (flag == "1") {
            gotoUrl("resetpwdbyemail.jsp?username=" + username + "&reqClientType=" + reqClientType + "&emaillist=" + data.emaillist + "&lang=" + lang);
            return true;
        }
        else if (flag == "2") {
            gotoUrl("resetpwdbyphone.jsp?username=" + username + "&reqClientType=" + reqClientType + "&phonelist=" + data.phonelist + "&lang=" + lang);
            return true;
        }
        else if (flag == "3") {
            $("#showerror").html("您的帐号还没绑定安全邮箱或安全手机号码！");
            buttonSmt.removeAttr("disabled");
            return false;
        }
        else {
            $("#showerror").html("找回密码失败！");
            buttonSmt.removeAttr("disabled");
            return false;
        }
    }, function () { }, true, "json");
}

/**
* 找回密码业务处理安全手机
*/
function resetPwdByPhone() {
    var username = $("#resetPwdByPhoneForm input[name='formBean.username']").val();
    var password = $("#resetPwdByPhoneForm input[name='formBean.newPassword']").val();
    var reqClientType = $("#resetPwdByPhoneForm input[name='formBean.reqClientType']").val();
    var authCode = $("#resetPwdByPhoneForm input[name='formBean.authCode']").val();

    var buttonSmt = $("#btnSubmit");
    //让按钮失效，为了保证按钮不会被多次点击
    buttonSmt.attr("disabled", "disabled"); ;

    username = encodeURIComponent(username);

    if (-1 != password.indexOf(" ")) {
        $("#showerror").html("密码中不能包含空格！");
        $("#resetPwdByPhoneForm input[name='formBean.newPassword']").val("");
        $("#resetPwdByPhoneForm input[name='formBean.newPassword']").focus();
        $("#resetPwdByPhoneForm input[name='formBean.conformPassword']").val("");
        buttonSmt.removeAttr("disabled");
        return false;
    }

    var strParms = "resetPwdBySMS";
    var dataParms = {
        "username": username,
        "newPassword": encodeURIComponent(password),
        "authCode": authCode,
        "reqClientType": reqClientType,
        "sourceflag": "web",
        "lang": lang
    };

    ajaxHandler(strParms, dataParms, function (data) {

        var flag = data.isSuccess;
        if (flag == "1") {
            gotoUrl("resetpwd_success.jsp?flag=1&lang=" + lang);
            return true;
        }
        else if (flag == "0") {
            gotoUrl("resetpwd_success.jsp?flag=0&errorCode=" + data.errorCode + "&description=" + data.description + "&lang=" + lang);
            return true;
        }
        else {
            $("#showerror").html("找回密码失败！");
            return false;
        }
        buttonSmt.removeAttr("disabled");

    }, function () { }, true, "json");
}


/**
* 找回密码业务处理安全邮箱
*/
function resetPwdByEmail() {
    var username = $("#resetPwdByEmailForm input[name='formBean.username']").val();
    var reqClientType = $("#resetPwdByEmailForm input[name='formBean.reqClientType']").val();
    var lang = $("#resetPwdByEmailForm input[name='formBean.lang']").val();
    var emailaccount = $("#resetPwdByEmailForm select[name='formBean.emailaccount']").val();

    username = encodeURIComponent(username);

    var strParms = "resetPwdByEmail";
    var dataParms = {
        "username": username,
        "emailaccount": emailaccount,
        "reqClientType": reqClientType,
        "sourceflag": "web",
        "lang": lang
    };

    ajaxHandler(strParms, dataParms, function (data) {
        var flag = data.isSuccess;
        if (flag == "1") {
            gotoUrl("resetpwd_success.jsp?flag=1&lang=" + lang);
            return true;
        }
        else {
            if (data.errorCode == "0") {
                gotoUrl("resetpwd_success.jsp?flag=0&errorCode=" + data.errorCode + "&description=" + data.description + "&lang=" + lang);
                return true;
            }
            else {
                $("#showerror").html("找回密码失败！");
                return false;
            }
        }
    }, function () { }, true, "json");
}


function isLogOn(userAccount, logon_aclist) {
    var aclist = logon_aclist.split(",");
    var acnumber = aclist.length;
    var regx = /^(00861[1-9]|(1[1-9]))$/;

    if (0 == userAccount.indexOf("0086") && regx.test(userAccount)) {
        userAccount = userAccount.substring(4, userAccount.length);
    }

    for (var i = 0; i < acnumber; i++) {
        if (aclist[i] == userAccount) {
            $("#showerror").html("该帐号已经登录");
            return false;
        } else {
            var account = aclist[i];
            if (0 == account.indexOf("0086")) {
                account = account.substring(4, account.length);
                if (account == userAccount) {
                    $("#showerror").html("该帐号已经登录");
                    return false;
                }
            }

        }
    }

    return true;
}
 