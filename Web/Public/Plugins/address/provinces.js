var cObj = null;
var tempObj = null;
var tempObjSon = null;

function pList(cid,css) {
    cObj = document.getElementById(cid);

    tempObj = document.createElement("select");
    tempObj.setAttribute("id", cid + "P");
    tempObj.setAttribute("class", css);
    tempObj.setAttribute("onchange", "selectProvinces(this,\"" + cid + "C" + "\",\"" + cid + "A" + "\")");
    for (var i = 0; i < arrCity.length; i++) {

        tempObjSon = document.createElement("option");
        tempObjSon.setAttribute("value", arrCity[i].name);
        tempObjSon.innerHTML = arrCity[i].name;
        tempObj.appendChild(tempObjSon);
        tempObjSon = null;
    }

    cObj.appendChild(tempObj);
    tempObj = null;
    tempObjSon = null;
    ////////////////////////////////////////////
    tempObj = document.createElement("select");
    tempObj.setAttribute("id", cid + "C");
    tempObj.setAttribute("class", css);
    tempObj.setAttribute("onchange", "selectCity(this,\"" + cid + "P\",\"" + cid + "A" + "\")");
    tempObjSon = document.createElement("option");
    tempObjSon.setAttribute("value", "请选择");
    tempObjSon.innerHTML = "请选择";
    tempObj.appendChild(tempObjSon);

    cObj.appendChild(tempObj);
    tempObj = null;
    tempObjSon = null;
    //////////////////////////////////////////
    tempObj = document.createElement("select");
    tempObj.setAttribute("id", cid + "A");
    tempObj.setAttribute("class", css);

    tempObjSon = document.createElement("option");
    tempObjSon.setAttribute("value", "请选择");
    tempObjSon.innerHTML = "请选择";
    tempObj.appendChild(tempObjSon);

    cObj.appendChild(tempObj);
    tempObj = null;
    tempObjSon = null;

    return [cid + "P", cid + "C", cid + "A"]; 
}

function selectProvinces(obj, cityID,areaID) {
    tempObj = document.getElementById(cityID);
    tempObj.innerHTML = "";

    if (obj.options[obj.selectedIndex].value == "请选择") {
        tempObjSon = document.createElement("option");
        tempObjSon.setAttribute("value", "请选择");
        tempObjSon.innerHTML = "请选择";
        tempObj.appendChild(tempObjSon);

        tempObj = null;
        tempObjSon = null;

       

    }
    else {
        for (var i = 0; i < arrCity[obj.selectedIndex].sub.length; i++) {

            tempObjSon = document.createElement("option");
            tempObjSon.setAttribute("value", arrCity[obj.selectedIndex].sub[i].name);
            tempObjSon.innerHTML = arrCity[obj.selectedIndex].sub[i].name;
            tempObj.appendChild(tempObjSon);

            tempObjSon = null;
        }
        tempObj = null;

        tempObj = document.getElementById(areaID);
        tempObj.innerHTML = "";

        tempObjSon = document.createElement("option");
        tempObjSon.setAttribute("value", "请选择");
        tempObjSon.innerHTML = "请选择";
        tempObj.appendChild(tempObjSon);
    }
    tempObj = null;
    tempObjSon = null;
}

function selectCity(obj, provincesID, areaID) {

    tempObj = document.getElementById(areaID);
    tempObj.innerHTML = "";

    if (obj.options[obj.selectedIndex].value == "请选择") {

        tempObjSon = document.createElement("option");
        tempObjSon.setAttribute("value", "请选择");
        tempObjSon.innerHTML = "请选择";
        tempObj.appendChild(tempObjSon);
    }
    else {
        var pIndex = document.getElementById(provincesID).selectedIndex;

        if (arrCity[pIndex].sub[obj.selectedIndex].sub == null || arrCity[pIndex].sub[obj.selectedIndex].sub == "undefined") {
            tempObjSon = document.createElement("option");
            tempObjSon.setAttribute("value", "-");
            tempObjSon.innerHTML = "-";
            tempObj.appendChild(tempObjSon);
            tempObjSon = null;
        }
        else {
            for (var i = 0; i < arrCity[pIndex].sub[obj.selectedIndex].sub.length; i++) {
                tempObjSon = document.createElement("option");
                tempObjSon.setAttribute("value", arrCity[pIndex].sub[obj.selectedIndex].sub[i].name);
                tempObjSon.innerHTML = arrCity[pIndex].sub[obj.selectedIndex].sub[i].name;
                tempObj.appendChild(tempObjSon);
                tempObjSon = null;
            }
        }
    }
    tempObj = null;
    tempObjSon = null;
}