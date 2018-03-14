$(document).ready(function() {
    var warehouse = $("#storehouseName"); // 仓库列表
    var inventoryId = $("#InventoryId"); // 盘点单号
    var inputName = $("#inputName"); // 盘点人
    var SelectPro = $("#SelectPro"); // 盘点商品按钮
    var inDate = $("#inDate"); // 盘点日期

    // 盘点人信息
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {
            allow_single_deselect: true
        },
        '.chosen-select-no-single': {
            disable_search_threshold: 10
        },
        '.chosen-select-no-results': {
            no_results_text: 'Oops, nothing found!'
        },
        '.chosen-select-width': {
            width: "95%"
        }
    };

    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    // 加载盘点商品
    SelectPro.bind("click", function() {
        if ($.trim(inputName.val()) == "-1") {
            art.dialog.alert('请选择盘点人！');
            return false;
        }
        if ($.trim(inDate.val()) == "") {
            // 提示信息
            art.dialog.alert('请填写盘点日期 格式 yyyy-MM-dd HH:mm！');
            return false;
        }
        NProgress.start();
        $.ajax({
            type: "POST",
            url: bindDefaultInfo.postSaveUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: {
                "ty": "new_inventory",
                "wid": $.trim(warehouse.val()),
                "wname": $.trim(warehouse.find("option:selected").text()),
                "riqi": $.trim(inDate.val()),
                "inputid": $.trim(inputName.val()),
                "inputname": $.trim(inputName.find("option:selected").text()),
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                if (data.code == "0") {
                    inventoryId.html(data.InventoryId);
                    bindDefaultInfo.cid = data.inventoryId;

                    // 禁用
                    warehouse.prop("disabled", "disabled");
                    inDate.prop("disabled", "disabled");
                    SelectPro.prop("disabled", "disabled");

                    // 绑定商品数据
                    bindDataPro(1, data.dataPro);

                } else {
                    art.dialog.alert('删除单据出现异常，请稍后重试'); // 提示语句
                    // 错误信息输出
                }
            },
            error: function(XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    art.dialog.tips('删除超时，删除失败..', 1);
                } else {
                    art.dialog.tips('发生未知异常错误..', 1);
                }
            }
        });
    });

    // 提取草稿单据
    $("#btnTqIn").bind("click", function() {
        art.dialog.open(bindDefaultInfo.postDraftUrl + "?w=" + $.trim(warehouse.val()), {
            title: "查找盘点单据--" + warehouse.find("option:selected").text(),
            lock: true
        });
    });

    // 删除单据
    $("#btnDelIn").bind("click", function() {
        var inid = $.trim(inventoryId.html());
        if (inid == "") {
            art.dialog.alert('未找到盘点单号'); // 提示语句
            return false;
        }
        art.dialog.tips('正在删除单据..', 20, true);
        $.ajax({
            type: "POST",
            url: bindDefaultInfo.postSaveUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: {
                "ty": "del_inventory",
                "id": inid,
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                if (data.code == "0") {
                    window.location.href = bindDefaultInfo.postNewUrl;
                } else {
                    art.dialog.alert('删除单据出现异常，请稍后重试'); // 提示语句
                    // 错误信息输出
                }
            },
            error: function(XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    art.dialog.tips('删除超时，删除失败..', 1);
                } else {
                    art.dialog.tips('发生未知异常错误..', 1);
                }
            }
        });
    });

    // 提交单据
    $("#btnOkIn").bind("click", function() {

        var inid = $.trim(inventoryId.html());
        if (inid == "") {
            art.dialog.alert('未找到盘点单号'); // 提示语句
            return false;
        }

        if ($.trim(inputName.val()) == "-1") {
            art.dialog.alert('请选择盘点人！');
            return false;
        }

        if ($.trim(inDate.val()) == "") {
            // 提示信息
            art.dialog.alert('请填写盘点日期 格式 yyyy-MM-dd HH:mm！');
            return false;
        }

        var _len = $("#tabInfo tbody tr").length;
        if (_len == 0) {
            art.dialog.alert('未找到已选盘点商品规格信息！'); // 提示语句
            return false;
        }

        art.dialog.tips('数据正在提交..', 20, true);

        // 服务器提交数据
        $.ajax({
            type: "POST",
            url: bindDefaultInfo.postSaveUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: {
                "ty": "ok_inventory",
                "id": inid,
                "wid": $.trim(warehouse.val()),
                "inputid": $.trim(inputName.val()),
                "inputname": $.trim(inputName.find("option:selected").text()),
                "remarks": $.trim($("#Remarks").val()),
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                // 成功
                if (data.code == "0") {
                    art.dialog.tips('数据已提交成功，您可以录入其他仓库的盘点单据..', 1);
                    window.location.href = bindDefaultInfo.postNewUrl;
                } else {
                    art.dialog.alert('保存出现异常，请稍后重试'); // 提示语句
                    // 错误信息输出
                }
            },
            error: function(XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    art.dialog.tips('提交超时，或提交失败..', 1);
                } else {
                    art.dialog.tips('发生未知异常错误..', 1);
                }
            }
        });
    });
});
// 绑定商品列表信息
var bindDataPro = function(type, dataPro) {
    var inid = $.trim($("#InventoryId").html());
    var datable; // 获取修改的表格对象
    var edttr; // 获取修改行对象
    // 如果是提取草稿加载
    if (type == 0) {
        // 设置 商品规格数据
        datable = $('#tabInfo').dataTable({
            "oLanguage": {
                'sSearch': '列数据筛选:'
            },
            "bDestroy": true
        });
    } else {
        datable = $('#tabInfo').dataTable({
            "bDestroy": true,
            "oLanguage": {
                'sSearch': '列数据筛选:'
            },
            "data": dataPro,
            "columns": [{
                "data": "RowNumber"
            }, {
                "data": "ClassName"
            }, {
                "data": "ProName"
            }, {
                "data": "ProIdInputCard"
            }, {
                "data": "Spec"
            }, {
                "data": "BookCount",
                render: function(a, b, c, d) {
                    return '<span data-token="' + c.ProIdCard + '">' + c.BookCount + '</span>';
                }
            }, {
                "data": "ActualCount"
            }, {
                "data": "CountPoor"
            }, {
                "data": "Date"
            }, {
                "data": "IsShelves"
            }]
        });
    }
    // 设置 实盘数量编辑
    datable.$('td:eq(6)').editable(bindDefaultInfo.postSaveUrl, {
        "callback": function(sValue, y) {
            sValue = JSON.parse(sValue);
            var aPos = datable.fnGetPosition(this);
            datable.fnUpdate(sValue.anum, aPos[0], aPos[1]);
            if (edttr != null) {
                edttr.addClass("up-tr");
                edttr.find("td:eq(7)").html(sValue.pnum);
                edttr.find("td:eq(8)").html(sValue.date);
            }
        },
        "submitdata": function(value, settings) {
            var _that = $(this);
            edttr = _that.parent();
            var tdnum = edttr.find("td:eq(5)").find("span");
            var proidcard = $.trim(tdnum.attr("data-token")); // 获取 商品规格编号
            var num = $.trim(tdnum.html()); // 获取 账面库存
            return {
                "ty": "up_pro_inventory",
                "cid": inid,
                "pid": proidcard,
                "num": num,
                "column": datable.fnGetPosition(this)[2],
                "r": (Math.random() * Math.random())
            };
        },
        "width": "90%",
        "height": "100%",
        tooltip: '单击可以编辑...'
    });
    NProgress.done();
};