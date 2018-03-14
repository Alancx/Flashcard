$(document).ready(function() {

    var inputName = $("#inputName"); // 入库人
    var inDate = $('#inDate'); // 日期
    var warehouse = $("#inputWarehouse"); // 仓库列表
    var inwarehouselist = $("#inwarehouselist"); // 商品表格信息
    var supplier = $('#SupplierId');

    // 采购人信息
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

    // 全选/反选
    $(document).on("click", "#chkAll", function() {
        var _that = $(this).prop("checked");
        inwarehouselist.find('input[type="checkbox"]').each(function() {
            $(this).prop("checked", _that);
        });
    });

    // 打开查找入库商品页面
    $("#SelectPro").bind("click", function() {

        if ($.trim(inputName.val()) == "-1") {
            art.dialog.alert('请选择采购入库人！');
            return false;
        }

        if ($.trim(supplier.val()) == "-1") {
            art.dialog.alert('请选择供货商');
            return false;
        };

        if ($.trim(inDate.val()) == "") {
            // 提示信息
            art.dialog.alert('请填写采购入库日期 格式 yyyy-MM-dd HH:mm！');
            return false;
        }

        // 打开
        art.dialog.open(bindDefaultInfo.postProUrl+"?w=" + $.trim(warehouse.val()) + "&pid=" + $.trim($("#InWarehouseId").html()+"&type="+$('#inType').val() + "&supplierid="+$.trim(supplier.val())), {
            title: "查找入库商品--" + warehouse.find("option:selected").text(),
            lock: true,
            width:900
        });
    });

    // 删除商品
    $("#selectKC").bind("click", function() {
        var inid = $.trim($("#InWarehouseId").html());
        if (inid == "") {
            art.dialog.alert('未找到入库单据编号'); // 提示语句
            return false;
        }

        var _len = $("#inwarehouselist tr").length;
        if (_len == 0) {
            art.dialog.alert('未找到已选入库商品规格信息！'); // 提示语句
            return false;
        }

        var attr = [];
        inwarehouselist.find('input[type="checkbox"]:checked').each(function() {
            attr.push({
                "card": $.trim($(this).prop('value'))
            });
        });
        if (attr.length == 0) {
            art.dialog.alert('未找到勾选待删除商品项！'); // 提示语句
            // 提示语句
            return false;
        }
        art.dialog.confirm('您确定要删除所选入库商品？', function () {
            art.dialog.tips('正在删除勾选商品..', 20, true);
            // 服务器提交数据
            $.ajax({
                type: "POST",
                url: bindDefaultInfo.postSaveUrl,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "del_pro_in_warehouse",
                    "id": inid,
                    "attr": attr,
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 20000, // 超时时间：20秒
                success: function(data) {
                    art.dialog.tips('已删除勾选商品..', 1);
                    // 成功
                    if (data.code == "0") {
                        inwarehouselist.find('input[type="checkbox"]:checked').each(function() {
                            $(this).parent().parent().remove(); // 获取tr
                        });

                        // if($("#inwarehouselist tr").length==0)
                        // {
                        //     warehouse.removeAttr("disabled");
                        //     inDate.removeAttr("disabled");
                        // }
                    } else {
                        art.dialog.alert('删除商品出现异常，请稍后重试'); // 提示语句
                        // 错误信息输出
                    }
                },
                error: function(XMLHttpRequest, textStatus, thrownError) {
                    if (textStatus == "timeout") {
                        art.dialog.tips('删除超时，或删除失败..', 1);
                    } else {
                        art.dialog.tips('发生未知异常错误..', 1);
                    }
                }
            });
        });
    });

    // 提取草稿单据
    $("#btnTqIn").bind("click", function() {
        art.dialog.open(bindDefaultInfo.postDraftUrl+"?w=" + $.trim(warehouse.val())+"&type="+$.trim($("#inType").val()), {
            title: "查找未入库单据--" + warehouse.find("option:selected").text(),
            lock: true
        });
    });

    // 删除单据
    $("#btnDelIn").bind("click", function() {
        var inid = $.trim($("#InWarehouseId").html());
        if (inid == "") {
            art.dialog.alert('未找到入库单据编号'); // 提示语句
            return false;
        }
        art.dialog.confirm('您确定要删除该入库单据？', function () {
            art.dialog.tips('正在删除单据..', 20, true);
            $.ajax({
                type: "POST",
                url: bindDefaultInfo.postSaveUrl,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "del_in_warehouse",
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
    });

    // 提交单据
    $("#btnOkIn").bind("click", function() {
        var inid = $.trim($("#InWarehouseId").html());
        if (inid == "") {
            art.dialog.alert('未找到入库单据编号'); // 提示语句
            return false;
        }

        if ($.trim(inputName.val()) == "-1") {
            art.dialog.alert('请选择采购入库人！');
            return false;
        }

        if ($.trim(inDate.val()) == "") {
            // 提示信息
            art.dialog.alert('请填写采购入库日期 格式 yyyy-MM-dd HH:mm！');
            return false;
        }

        var _len = $("#inwarehouselist tr").length;
        if (_len == 0) {
            art.dialog.alert('未找到已选入库商品规格信息！'); // 提示语句
            return false;
        }
        art.dialog.confirm('点击确定前请仔细核对该入库单据是否正确，确定提交？', function () {
            art.dialog.tips('数据正在提交..', 20, true);
            // 服务器提交数据
            $.ajax({
                type: "POST",
                url: bindDefaultInfo.postSaveUrl,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "ok_in_warehouse",
                    "id": inid,
                    "wid": $.trim($("#inputWarehouse").val()),
                    "number":$.trim($("#InWarehouseNumber").val()),
                    "inputid": $.trim(inputName.val()),
                    "inputname": $.trim(inputName.find("option:selected").text()),
                    "type": $.trim($("#inType").val()),
                    "remarks": $.trim($("#Remarks").val()),
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 20000, // 超时时间：20秒
                success: function(data) {
                    art.dialog.tips('数据已提交成功，您可以录入新的单据..', 1);
                    // 成功
                    if (data.code == "0") {
                        window.location.href = bindDefaultInfo.postNewUrl;
                    } else {
                        if (data.code=='2') {
                            art.dialog.alert('入库申请单需等待平台审核'); // 提示语句
                        }else{
                            art.dialog.alert('保存出现异常，请稍后重试'); // 提示语句
                        }
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
});