// 忘记密码出错接口实现
function errorCallback(errorCode, description) {
    window.webLoader.errorCallback(errorCode, description);
}

//忘记密码回调接口实现
function forgotPasswordCallback(successFlag) {
    window.webLoader.forgotPasswordCallback(successFlag);
}

//设置用户信息回调接口实现
function setUserInfoCallback(successFlag) {
    window.webLoader.setUserInfoCallback(successFlag);
}

//授权回调接口实现
function authorizeCallback(accessToken, expiresIn, uid, accountName, siteId) {
    window.webLoader.authorizeCallback(accessToken, expiresIn, uid, accountName, siteId);
}

//帐号变更接口实现
function chgHwIDCallback(userID, oldAccountType, oldUseAccount, newAccountType, newUseAccount, verifiedFlag) {
    window.webLoader.chgHwIDCallback(userID, oldAccountType, oldUseAccount, newAccountType, newUseAccount, verifiedFlag);
}

//退回前一步用的
function goHBack() {
    try {
        window.webLoader.goBack();
    }
    catch (e) {
        history.go(-1);
    }
}

//判断一个用户的是否已经登录
function isAccountExist(userAccount) {
    try {
        return window.webLoader.isAccountExist(userAccount);
    }
    catch (e) {
        return false;
    }
}

//销户结果状态 ok,cancel
function delUserNoticeApp(Command) {
    window.webLoader.intoApp(Command);
}
