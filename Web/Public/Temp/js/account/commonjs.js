var cfgRegex = {
    chinesePhone: /^(1[34578])[0-9]{9}$|(^(\+|00)852[9865])[0-9]{7}$/,
    hwPhone: /^[0-9]{5,10}$/,
    email: /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9\-]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/i,
    anyPhoneAndMail: /^(1[34578])[0-9]{9}$|^\s*([A-Za-z0-9_-]+(\.\w+)*@(\w+\.)+\w+)\s*$/,
    anyPhone: /^[0-9]{5,15}$/
};

function gotoUrl(strUrl) {
    location.href = strUrl;
}

//去掉左右空格
function Trim(text) {
    return text.replace(/(^\s*)|(\s*$)/g, "");
}
/**
* 用于验证表单
* @param valiType 枚举的类型可以选择如下：email、mobile、password、mobilecode
* @param strContent 被验证的字符内容
* @returns 返回true、false
**/
function valiRegular(valiType, strContent) {
    var regular = "";

    switch (valiType) {
        case "email":
            regular = /^\s*([A-Za-z0-9_-]+(\.\w+)*@(\w+\.)+\w+)\s*$/;
            break;
        case "mobile":
            regular = /^1\d{10}$/;
            break;
        case "password":
            regular = /^\S{6,32}$/;
            break;
        case "mobilecode":
            regular = /^\S{4}$/;
            break;
    }

    return regular.test(strContent);
}

/**
* 获取手机验证码
**/
function getMobileCode(smsReqType, reqClientType, lang) {
    var mobilephone = $("#username").val();
    var account = encodeURIComponent($("#account").val());
    var nationalcode = $("#input_languageCode").val();
    var getCodeButton = $("#getValiCode");
    getCodeButton.attr("IntervalTime", 60);
    getCodeButton.attr("disabled", "disabled");

    var pageTokenObj = $("#pageTokenID");

    if ("undefined" != typeof nationalcode) {
        mobilephone = nationalcode.replace("+", "00") + mobilephone;
    }
    if (mobilephone == "") {
        $("#msg_phone").html("<span class='vam icon-error'>手机号码不能为空！</span>");
        getCodeButton.removeAttr("disabled");
        return;
    }
    var parms = "getMobileValiCode";
    $.ajax({
        url: parms,
        data: {
            "account": account,
            "reqClientType": reqClientType,
            "smsReqType": smsReqType,
            "lang": lang,
            "pageToken": pageTokenObj.val(),
            "mobile": mobilephone
        },
        type: "POST",
        dataType: "text",
        success: function (data) {
            if (data == "success") {
                jsInnerTimeout();
            }
            else if (data == "70001102") {
                getCodeButton.removeAttr("disabled");
                $("#showerror").html("<span class='vam icon-error'>一分钟内只允许请求一次，请稍后重试</span>");
            }
            else if (data == "70001103") {
                getCodeButton.removeAttr("disabled");
                $("#showerror").html("<span class='vam icon-error'>超出一周内6条发送验证码的最大次数</span>");
            }
            else if (data == "70001104") {
                getCodeButton.removeAttr("disabled");
                $("#showerror").html("<span class='vam icon-error'>超出一天发送验证码最大次数</span>");
            }
            else {
                getCodeButton.removeAttr("disabled");
                $("#showerror").html("<span class='vam icon-error'>获取验证码失败！</span>");
            }
        }
    });
}

function jsInnerTimeout() {
    var codeObj = $("#getValiCode");
    var intAs = parseInt(codeObj.attr("IntervalTime"));

    intAs--;
    codeObj.attr("disabled", "disabled");
    if (intAs <= -1) {
        codeObj.removeAttr("disabled");
        codeObj.val("获取验证码");
        return true;
    }

    codeObj.val(intAs + 's');
    codeObj.attr("IntervalTime", intAs);

    setTimeout("jsInnerTimeout()", 1000);
}


function ajaxHandler_as(queryParms) {
    return $.ajax({ url: getWebUrl() + queryParms + "&reflushCode=" + Math.random(), type: "POST", async: false, dataType: "text" }).responseText;
}

function ajaxHandlerForHttps_as(queryParms) {
    return $.ajax({ url: getWebUrlHttps() + queryParms + "&reflushCode=" + Math.random(), type: "GET", async: false, dataType: "text" }).responseText;
}

function ajaxHandler_s(queryParms, funName) {
    $.ajax({
        url: getWebUrl() + queryParms + "&reflushCode=" + Math.random(),
        type: "POST",
        dataType: "text",
        success: function (data) {
            funName(data);
        }
    });
}

function ajaxHandlerForHttps_s(queryParms, funName) {
    $.ajax({
        url: getWebUrlHttps() + queryParms + "&reflushCode=" + Math.random(),
        type: "POST",
        dataType: "text",
        success: function (data) {
            funName(data);
        }
    });
}

function ajaxJSONPForHttp(queryParms, callbackfun) {
    $.getJSON(getWebUrl() + queryParms + "&reflushCode=" + Math.random() + "&callback=?", callbackfun);
}

function ajaxJSONP(queryParms, callbackfun) {
    $.getJSON(getWebUrlHttps() + queryParms + "&reflushCode=" + Math.random() + "&callback=?", callbackfun);
}

function ajaxJSONPForSelf(axjxUrl, queryParms, callbackfun) {
    $.getJSON(axjxUrl + queryParms + "&reflushCode=" + Math.random() + "&callback=?", callbackfun);
}

function ajaxHandler(interfaceName, dataParms, successFun, errorFun, isAsync, respnseDataType) {
    var responseData = $.ajax({
        url: getWebUrlHttps() + interfaceName + "?reflushCode=" + Math.random(),
        type: "POST",
        data: dataParms,
        success: function (data) {
            successFun(data);
        },
        error: errorFun,
        cache: false,
        dataType: respnseDataType,
        async: isAsync
    });

    return responseData;
}


/**
|    函数名称： setCookie
|    函数功能： 设置cookie函数
|    入口参数： name：cookie名称；value：cookie 值
**/
function setCookie(name, value) {
    var argv = setCookie.arguments;
    var argc = setCookie.arguments.length;
    var expires = (argc > 2) ? argv[2] : null;
    if (expires != null) {
        var LargeExpDate = new Date();
        LargeExpDate.setTime(LargeExpDate.getTime() + (expires * 1000 * 3600 * 24));
    }
    document.cookie = name + "=" + escape(value) + ((expires == null) ? "" : ("; expires=" + LargeExpDate.toGMTString())) + "; path=" + "/";
}

/**
|    函数名称： getCookie 
|    函数功能： 读取cookie函数
|    入口参数： Name：cookie名称 
**/
function getCookie(Name) {
    var search = Name + "=";
    if (document.cookie.length > 0) {
        offset = document.cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = document.cookie.indexOf(";", offset);
            if (end == -1) end = document.cookie.length;
            return unescape(document.cookie.substring(offset, end));
        }
        else return "";
    }
}

/**
|    函数名称： deleteCookie
|    函数功能： 删除cookie函数
|    入口参数： Name：cookie名称
**/
function delCookie(name) {
    var expdate = new Date();
    expdate.setTime(expdate.getTime() - (86400 * 1000 * 1));
    setCookie(name, "", expdate);
}

/**
|    locationSearch:字符串如:?a=b&b=2&c=3
|    key:关键字
|    return key所对应的value
**/
function getParm(locationSearch, key) {
    var tempQurey = locationSearch.replace("?", "");
    var strArray = tempQurey.split("&");

    for (var i = 0; i < strArray.length; i++) {
        var varTemp = strArray[i].split("=")[0];
        var varTempValue = strArray[i].split("=")[1];
        if (varTemp == key) {
            return varTempValue;
        }
    }
    return "";
}

function chkPwdComplexity(pass) {
    if (pass.length < 6) {
        return 0;
    }
    var ls = 0;
    if (pass.match(/([a-z])+/)) { ls++; }
    if (pass.match(/([0-9])+/)) { ls++; }
    if (pass.match(/([A-Z])+/)) { ls++; }
    if (pass.match(/.*([^a-zA-Z0-9])+.*/)) { ls++; }

    if (ls > 3) {
        ls = 3;
    }
    return ls;
}

function onPwdKeyUp(obj) {
    var matchResult = parseInt(chkPwdComplexity(obj.value));
    var pwd_bar = $(".pwd-letter span");
    var weak = "弱";
    var common = "中";
    var strong = "强";

    if ("undefined" != typeof rss) {
        if ("undefined" != typeof rss.weak) {
            weak = rss.weak;
        }
        if ("undefined" != typeof rss.common) {
            common = rss.common;
        }
        if ("undefined" != typeof rss.strong) {
            strong = rss.strong;
        }
    }

    pwd_bar.each(function (index) {

        if (index == 0) { pwd_bar[index].innerHTML = weak; }
        else if (index == 1) { pwd_bar[index].innerHTML = common; }
        else if (index == 2) { pwd_bar[index].innerHTML = strong; }

        var tempIndex = index + 1;
        if (tempIndex == matchResult || matchResult == 0) {
            $(".pwd-letter span").attr("class", "off_pwd");
            switch (matchResult) {
                case 1:
                    pwd_bar[0].className = "on_pwd_1";
                    break;
                case 2:
                    pwd_bar[0].className = "on_pwd_2";
                    pwd_bar[0].innerHTML = "&nbsp;";
                    pwd_bar[1].className = "on_pwd_2";
                    break;
                case 3:
                    pwd_bar[0].className = "on_pwd_3";
                    pwd_bar[0].innerHTML = "&nbsp;";
                    pwd_bar[1].className = "on_pwd_3";
                    pwd_bar[1].innerHTML = "&nbsp;";
                    pwd_bar[2].className = "on_pwd_3";
                    break;
            }
        }
    });
}

function getUrlParm(name) {
    var search = document.location.search, parts = (!search) ? [] : search.split('&'), params = {};

    for (var i = 0, len = parts.length; i < len; i++) {
        var param = parts[i].split('=');
        var pname = param[0];
        if (i == 0) {
            pname = pname.split('?')[1];
        }
        if (name == pname)
            return param[1];
    }
    ;
    return "";
}

var localSites = {
    localUrl: location.href.split("?")[0]
};