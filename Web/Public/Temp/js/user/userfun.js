/**
* 个人中心公共js方法调用区
*
*/

/**
* 省市级联操作
*
* @param   {String}    字符串
*/
var province_city_area = {
    bindProvince: function (Oprovince) {
        // 绑定省份
        var province = $("#" + Oprovince);
        var _html = '';
        $.each(arrCity, function (i, value) {
            if (i != 0) {
                _html += '<a href="javascript:void(0);" class="list-group-item">' + value.name + '</a>';
            }
        });
        province.empty().append(_html).show();
    },
    bindCity: function (OprovinceName, Ocity) {
        var _html = '';
        // 绑定城市
        $.each(arrCity, function (i, prostemp) {
            if (OprovinceName == prostemp.name) {
                $.each(prostemp.sub, function (index, vNames) {
                    if (index != 0) {
                        _html += '<a href="javascript:void(0);" class="list-group-item" data-type="' + prostemp.type + '">' + vNames.name + '</a>';
                    }
                });
            }
        });
        $("#" + Ocity).empty().append(_html).show();
    },
    bindArea: function (OprovinceName, OcityName, Oarea) {
        // 绑定市县区
        var _html = "";
        $.each(arrCity, function (i, prostemp) {
            if (OprovinceName == prostemp.name) {
                $.each(prostemp.sub, function (index, vNames) {
                    if (OcityName == vNames.name) {
                        $.each(vNames.sub, function (indexs, vAreas) {
                            if (indexs != 0) {
                                _html += '<a href="javascript:void(0);" class="list-group-item" data-type="' + prostemp.type + '">' + vAreas.name + '</a>';
                            }
                        });
                    }
                });
            }
        });
        $("#" + Oarea).empty().append(_html).show();
    }
}

/**
* 字符串转utf-8
*
* @param   {String}    字符串
*/
function toUtf8(str) {
    var out, i, len, c;
    out = "";
    len = str.length;
    for (i = 0; i < len; i++) {
        c = str.charCodeAt(i);
        if ((c >= 0x0001) && (c <= 0x007F)) {
            out += str.charAt(i);
        } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
        } else {
            out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
        }
    }
    return out;
}

/**
* 格式化日期
*
* @param   {String}    日期数据
*/
function ChangeDateFormat(d) {
    var date = new Date(parseInt(d.replace("/Date(", "").replace(")/", ""), 10));
    var month = padLeft(date.getMonth() + 1, 10);
    var currentDate = padLeft(date.getDate(), 10);
    var hour = padLeft(date.getHours(), 10);
    var minute = padLeft(date.getMinutes(), 10);
    return date.getFullYear() + "-" + month + "-" + currentDate + " " + hour + ":" + minute;
}

/**
* 字符串左边加0
*
* @param   {String}    字符串
*/
function padLeft(str, min) {
    if (str >= min)
        return str;
    else
        return "0" + str;
}