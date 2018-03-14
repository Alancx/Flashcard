$(document).ready(function() {
    var outStorehouse = $('#OutStorehouse'); // 出库仓库
    var outputName = $("#OutputName"); // 出库人
    var outType = $("#OutType"); // 出库类型
    var outDate = $("#OutDate"); // 出库发货日期
    var inStorehouse = $("#InStorehouse"); // 调入仓库
    var supplierName = $("#SupplierName"); // 目标供应商
    var outwarehouselist = $("#outwarehouselist"); // 出库商品tab

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
    // 全选/反选 商品
    $(document).on("click", "#chkAll", function() {
        var _that = $(this).prop("checked");
        outwarehouselist.find('input[type="checkbox"]').each(function() {
            $(this).prop("checked", _that);
        });
    });

    // 出库类型
    outType.bind("change", function() {
        var vo=$(this).val();
        if (vo == "0") {
            $(".out-type-0").css("display", "block");
            $(".out-type-1").css("display", "none");
        } 
        else if(vo==1) 
        {
            $(".out-type-0").css("display", "none");
            $(".out-type-1").css("display", "block");
        }
        else
        {
            $(".out-type-0").css("display", "none");
            $(".out-type-1").css("display", "none");
        }
    });

    // 打开查找出库商品页面
    $("#SelectPro").bind("click", function() {

        if ($.trim(outputName.val()) == "-1") {
            art.dialog.alert('请选择出库人！');
            return false;
        }
        if ($.trim(outDate.val()) == "") {
            // 提示信息
            art.dialog.alert('请填写出库发货日期 格式 yyyy-MM-dd HH:mm！');
            return false;
        }
        var _tempoutstorehouse = $.trim(outStorehouse.val());
        var _tempType = $.trim(outType.val());
        if (_tempType == "0") {
            if ($.trim(inStorehouse.val()) == "-1") {
                art.dialog.alert("请选择调入仓库");
                return false;
            }
            if ($.trim(inStorehouse.val()) == _tempoutstorehouse) {
                art.dialog.alert("出库仓库不能与调入仓库相同！");
                return false;
            }
        } else if (_tempType == "1") {
            if ($.trim(supplierName.val()) == "-1") {
                art.dialog.alert("请选择退货目标供应商！");
                return false;
            }
        }

        // 打开
        art.dialog.open(bindDefaultInfo.postProUrl + "?w=" + $.trim(outStorehouse.val()) + "&pid=" + $.trim($("#OutWarehouseId").html()), {
            title: "查找出库库商品--" + outStorehouse.find("option:selected").text(),
            lock: true
        });
    });

    // 提取草稿单据
    $("#btnTqOut").bind("click", function() {
        art.dialog.open(bindDefaultInfo.postDraftUrl + "?w=" + $.trim(outStorehouse.val())+"&type="+$.trim(outType.val()), {
            title: "查找未出库单据--" + outStorehouse.find("option:selected").text(),
            lock: true
        });

    });

    // 删除单据
    $("#btnDelOut").bind("click", function() {
        var outid = $.trim($("#OutWarehouseId").html());
        if (outid == "") {
            art.dialog.alert('未找到出库单号'); // 提示语句
            return false;
        }
        art.dialog.confirm('您确定要删除该出库单据？', function () {
            $.ajax({
                type: "POST",
                url: bindDefaultInfo.postSaveUrl,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "del_out_warehouse",
                    "id": outid,
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


    // 删除商品
    $("#selectKC").bind("click", function() {
        var outid = $.trim($("#OutWarehouseId").html());
        if (outid == "") {
            art.dialog.alert('未找到出库单号'); // 提示语句
            return false;
        }
        var _len = $("#outwarehouselist tr").length;
        if (_len == 0) {
            art.dialog.alert('未找到已选出库商品规格信息！'); // 提示语句
            return false;
        }
        var attr = [];
        outwarehouselist.find('input[type="checkbox"]:checked').each(function() {
            attr.push({
                "card": $.trim($(this).prop('value'))
            });
        });
        if (attr.length == 0) {
            art.dialog.alert('未找到勾选待删除商品项！'); // 提示语句
            // 提示语句
            return false;
        }
        art.dialog.confirm('您确定要删除所选出库商品？', function () {
            art.dialog.tips('正在删除勾选商品..', 20, true);
            // 服务器提交数据
            $.ajax({
                type: "POST",
                url: bindDefaultInfo.postSaveUrl,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "del_pro_out_warehouse",
                    "id": outid,
                    "attr": attr,
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 20000, // 超时时间：20秒
                success: function(data) {
                    art.dialog.tips('已删除勾选商品..', 1);
                    // 成功
                    if (data.code == "0") {
                        outwarehouselist.find('input[type="checkbox"]:checked').each(function() {
                            $(this).parent().parent().remove(); // 获取tr
                        });
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

    // 提交单据
    $("#btnOkOut").bind("click", function() {

        var outid = $.trim($("#OutWarehouseId").html());
        if (outid == "") {
            art.dialog.alert('未找到出库单号'); // 提示语句
            return false;
        }
        if ($.trim(outputName.val()) == "-1") {
            art.dialog.alert('请选择出库人！');
            return false;
        }
        if ($.trim(outDate.val()) == "") {
            // 提示信息
            art.dialog.alert('请填写出库发货日期 格式 yyyy-MM-dd HH:mm！');
            return false;
        }
        var _len = $("#outwarehouselist tr").length;
        if (_len == 0) {
            art.dialog.alert('未找到已选出库商品规格信息！'); // 提示语句
            return false;
        }
        art.dialog.confirm('点击确定前请仔细核对该出库库单据是否正确，确定提交？', function () {
            art.dialog.tips('数据正在提交..', 20, true);
            // 服务器提交数据
            $.ajax({
                type: "POST",
                url: bindDefaultInfo.postSaveUrl,
                // contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "ok_out_warehouse",
                    "id": outid,
                    "wid": $.trim(outStorehouse.val()),
                    "number":$.trim($("#OutWarehouseNumber").val()),
                    "outputid": $.trim(outputName.val()),
                    "outputname": $.trim(outputName.find("option:selected").text()),
                    "type": $.trim(outType.val()),
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
});