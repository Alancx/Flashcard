var getDatapager = function() {
    var data = {
        "ty": "get_supplier",
        "stime": $("#dateStart").val(),
        "etime": $("#dateEnd").val(),
        "wid": $("#Warehouse").val(),
        "supplier": $("#SupplierList").val(),
        "proname": $("#proName").val(),
        "pindex": binddefaultinfo.pno,
        "r": (Math.random() * Math.random())
    };
    var data_tbody = $("#data-tbody");
    data_tbody.empty();
    var alert_message = $("#alert_message");
    $.ajax({
        type: "POST",
        async: false,
        url: binddefaultinfo.postUrl,
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        data: data,
        dataType: "json",
        timeout: 20000,
        success: function(data) {
            if (data.code == "0") {
                alert_message.hide();
                alert_message.html("");
                binddefaultinfo.pageCount = data.pageCount; // 数据总条数
                binddefaultinfo.totalPage = data.totalPage; // 总页码
                var _html = "";
                $(data.dataPro).each(function(index, vo) {
                    var _type = "";
                    if (vo.ty == "入库") {
                        if (vo.Type == '0') {
                            // 入库类型 0：采购入库 1：调拨入库 2：退货入库 3：差错入库0：采购入库
                            _type = "采购入库";
                        } else if (vo.Type == "1") {
                            _type = "调拨入库";
                        } else if (vo.Type == "2") {
                            _type = "退货入库";
                        } else if (vo.Type == "3") {
                            _type = "采购入库";
                        }
                    } else {
                        _type = "退货出库";
                    }
                    _html += '<tr><td>' + vo.RowNumber + '</td><td>' + vo.ProName + '</td><td>' + vo.ProIdInputCard + '</td><td>' + vo.Spec + '</td><td>' + vo.Count + '</td><td>' + Number(vo.Price).toFixed(2) + '</td><td>' + Number(vo.Money).toFixed(2) + '</td><td>' + vo.number + '</td><td>' + _type + '</td><td>' + vo.Date + '</td><td>' + vo.supplier + '</td><td>' + vo.ty + '</td></tr>';
                });
                data_tbody.html(_html);
            } else {
                alert_message.show();
                alert_message.html("还没有相关数据！")
            }
        },
        error: function(XMLHttpRequest, textStatus, thrownError) {
            alert_message.show();
            if (textStatus == "timeout") {
                alert_message.html("请求超时!")
            } else {
                alert_message.html("发生未知异常错误!")
            }
        }
    });
};
var SetPager = function() {
    kkpager.generPageHtml({
        pno: binddefaultinfo.pno,
        total: binddefaultinfo.totalPage,
        totalRecords: binddefaultinfo.pageCount,
        isShowTotalRecords: true,
        isShowTotalPage: false,
        mode: "click",
        click: function(n) {
            NProgress.start();
            binddefaultinfo.pno = n;
            getDatapager();
            this._config['total'] = binddefaultinfo.totalPage;
            this._config['totalRecords'] = binddefaultinfo.pageCount;
            this.selectPage(n);
            NProgress.done();
            console.log(this._config);
            return false
        }
    }, true);
    NProgress.done();
};
$(document).ready(function() {
    var stime = $("#dateStart");
    var etime = $("#dateEnd");
    var warehouse = $("#Warehouse");
    var supplier = $("#SupplierList");
    var proname = $("#proName");

    var valiIs = function() {
        var s = $.trim(stime.val());
        var e = $.trim(etime.val());
        if (validateRules.isNull(s)) {
            art.dialog.alert("开始日期不能为空!");
            return false
        } else if (validateRules.isNull(e)) {
            art.dialog.alert("结束日期不能为空!");
            return false
        } else if (Date.parse(s) > Date.parse(e)) {
            art.dialog.alert("开始时间小于结束时间！");
            return false
        } else {
            return true;
        }
    };

    $(document).on("click", "#selectKC", function() {
        if (valiIs()) {
            NProgress.start();
            binddefaultinfo.pno = 1;
            binddefaultinfo.totalPage = 1;
            binddefaultinfo.pageCount = 0;
            getDatapager();
            SetPager()
        }
    });
});

