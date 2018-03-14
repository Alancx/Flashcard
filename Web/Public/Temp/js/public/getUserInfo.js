function sendUserRequest(type, dataPamar,id) {
    $.ajax({
        type: "POST",
        url: "/Handler/publicInfo.ashx",
        data: "type=" + type + "&data=" + dataPamar,
        dataType: "json",
        success: function (data) {
            if (data.status == "true") {
                if (type == "cart") {
                    $("#" + id).html(data.info);
                }
                else if (type == "login") {
                    if (data.info=="1") {
                        $("#" + id).html("注销");
                        $("#" + id).attr("href","/Account/Logout");
                    }
                }
                else if (type == "scene") {
                   
                }

            }
            else {

            }
        },
        error: function (e) {

        }
    });
}

function getCart(id) {
    sendUserRequest("cart", "", id);
}

function judgeLogin(id) {
    //sendUserRequest("login", "", id);
}

function saveScene(data) {
    if (data != "") {
        var tempSceneArray = window.location.search.substr(1).split('&');
        var tempSceneJson = "[";
        for (var i = 0; i < tempSceneArray.length; i++) {
            if (tempSceneArray[i]!="") {
                tempSceneJson += "\"" + tempSceneArray[i] + "\",";
            }
        }
        tempSceneJson = tempSceneJson.substr(0,tempSceneJson.length-1)+"]";
        sendUserRequest("scene", tempSceneJson, "");
    }
}
