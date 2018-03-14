$(document).ready(function() {
    var ProName = $("#ProName"); // 获取 商品下拉框
    var ProClass = $("#ProType"); // 获取 商品类别
    var ProSpectbody = $("#ProSpectbody"); // 获取 规格表格
    var warehouse = $.trim($("#warehouse").val()); // 获取 仓库索引
    var btnOk = $("#btnOkInfo"); // 提交按钮
    // proType，ProName
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

    // 商品加载事件
    ProName.bind("load", function() {
        if (proCount > 0 && proCount <= 200) {
            var datas = $(proinfo.prodata); // 商品信息
            // 放全部数据
            datas.each(function(index, val) {
                ProName.append('<option value="' + val.id + '" data-classType="' + val.ty + '">' + val.na + '</option>');
            });
        }
    });

    // 类别改变事件
    ProClass.bind("change", function() {
        var _that = $(this);
        ProName.empty(); // 清空商品数据
        ProName.append('<option value="-1">输入商品名称或编码查找</option>');
        ProSpectbody.empty(); // 清空商品规格数据

        var classtype = $.trim(_that.val());
        // 筛选加载数据
        if (proCount > 0 && proCount <= 200) {
            var datas = $(proinfo.prodata); // 商品信息
            if (classtype == "-1") {
                // 放全部数据
                datas.each(function(index, val) {
                    ProName.append('<option value="' + val.id + '" data-classType="' + val.ty + '">' + val.na + '</option>');
                });
            } else {
                // 放类别数据
                datas.each(function(index, val) {

                    if (classtype == val.ty) {
                        ProName.append('<option value="' + val.id + '" data-classType="' + val.ty + '">' + val.na + '</option>');
                    }
                });
            }
            // 刷新数据
            ProName.trigger("chosen:updated");
        } else {
            if (classtype != "-1") {
                // 服务器检索数据
                $.ajax({
                    type: "POST",
                    url: bindShowInfo.postProUrl,
                    contentType: "application/x-www-form-urlencoded; charset=utf-8",
                    data: {
                        c: 'get_proname',
                        t: classtype
                    },
                    dataType: "json",
                    timeout: 20000, // 超时时间：20秒
                    success: function(data) {
                        $(data).each(function() {
                            ProName.append('<option value="' + val.id + '" data-classType="' + val.ty + '">' + val.na + '</option>');
                        });
                        ProName.trigger("chosen:updated");
                    }
                });
            }
        }
    });

    // 商品名称改变事件
    ProName.bind("change", function() {
        var _that = $(this);
        var _id = $.trim(_that.val()); // 获取 商品编号
        ProSpectbody.empty(); // 先清空规格数据

        // 获取已添加过的商品规格信息
        var _parAttr = [];
        $("#inwarehouselist", parent.window.document).find('input[type="checkbox"]').each(function() {
            _parAttr.push($.trim($(this).prop("value")));
        });

        // 筛选加载数据
        if (proCount > 0 && proCount <= 200) {
            if (_id != "-1") {
                $(proinfo.data).each(function(index, val) {
                    // 获取到该商品编号且不在父页面的商品规格信息中
                    if (_id == val.id && $.inArray(val.card, _parAttr) == -1) {
                        // 规格编码，规格，剩余库存，入库数量，入库价，供应商
                        ProSpectbody.append('<tr><td><input type="checkbox" value="' + val.card + '" name="proAttr"/></td><td>' + val.incard + '</td><td>' + val.spec + '</td><td>' + val.num + '</td><td><input type="text" class="form-control form-inline input-innum" style="width:80px;" value="1"/><small class="text-danger"></small></td><td><div class="input-group" style="display:inline-table;width: 115px;"><span class="input-group-addon">¥</span><input type="text" class="form-control input-price" value="0" style="width:80px;"></div><small class="text-danger"><br/></small></td><td><select class="form-control input-sm" style="font-size:12px;"><option value="-1">选择供货商</option>' + supplier.join("") + '</select></td></tr>');
                    }
                });
            }
        } else {
            if (_id != "-1") {
                // 服务器检索数据
                $.ajax({
                    type: "POST",
                    url: bindShowInfo.postProUrl,
                    contentType: "application/x-www-form-urlencoded; charset=utf-8",
                    data: {
                        c: 'get_prolist',
                        w: warehouse,
                        t: _id
                    },
                    dataType: "json",
                    timeout: 20000, // 超时时间：20秒
                    success: function(data) {
                        $(data).each(function(index, val) {
                            // 获取到该商品编号且不在父页面的商品规格信息中
                            if (_id == val.id && $.inArray(val.card, _parAttr) == -1) {
                                // 规格编码，规格，剩余库存，入库数量，入库价，供应商
                                ProSpectbody.append('<tr><td><input type="checkbox" value="' + val.card + '" name="proAttr"/></td><td>' + val.incard + '</td><td>' + val.spec + '</td><td>' + val.num + '</td><td><input type="text" class="form-control form-inline input-innum" style="width:80px;" value="1"/><small class="text-danger"></small></td><td><div class="input-group" style="display:inline-table;width: 115px;"><span class="input-group-addon">¥</span><input type="text" class="form-control input-price" value="0" style="width:80px;"></div><small class="text-danger"><br/></small></td><td><select class="form-control input-sm" style="font-size:12px;"><option value="-1">选择供货商</option>' + supplier.join("") + '</select></td></tr>');
                            }
                        });
                    }
                });
            }
        }
    });

    // 库存字符判断
    $(document).on("keyup", ".input-innum", function() {
        var _that = $(this);
        var _thatValue = $.trim(_that.prop("value"));
        var s = _that.parent().parent().find('input[type="checkbox"]');
        if (!validateRules.isPlusintege(_thatValue)) {
            _that.next(".text-danger").html(validater.isintege);
            if (s.prop("checked")) {
                s.prop("checked", false);
            }
            s.prop("disabled", "disabled");
        } else {
            _that.next(".text-danger").html("");
            s.prop("disabled", "");
            if (_thatValue != "" && !s.prop("checked")) {
                s.prop("checked", true);
            }
        }
    });

    // 价格字符判断
    $(document).on("keyup", ".input-price", function() {
        var _that = $(this);
        var _thatValue = $.trim(_that.prop("value"));
        var s = _that.parent().parent().parent().find('input[type="checkbox"]');
        if (!validateRules.isPlusdecmal(_thatValue)) {
            _that.parent().next(".text-danger").html("<br/>" + validater.isdecmal);
            if (s.prop("checked")) {
                s.prop("checked", false);
            }
            s.prop("disabled", "disabled");
        } else {
            _that.parent().next(".text-danger").html("");
            s.prop("disabled", "");
            if (_thatValue != "" && !s.prop("checked")) {
                s.prop("checked", true);
            }

        }
    });

    // 提交数据
    btnOk.bind("click", function() {
        var pro_id = $.trim(ProName.val());
        if (pro_id == "-1") {
            art.dialog.alert('请选择商品名称'); // 提示语句
            return false;
        }
        var _len = $("#ProSpectbody tr").length;
        if (_len == 0) {
            art.dialog.alert('未找到商品入库商品规格信息！'); // 提示语句
            return false;
        }

        var attr = [];
        var dataList = [];
        $('input[type="checkbox"][name="proAttr"]:checked').each(function() {
            var _thatchk = $(this);
            var attrid = _thatchk.prop('value'); // 规格编码
            var par = _thatchk.parent().parent(); // 获取tr
            var s = par.find("input[type='text']").first(); // 入库数量
            var b = par.find("input[type='text']").last(); // 价格
            var e = par.find("select").first(); // 供货商

            if ($.trim(s.prop("value")) == "") {
                s.next(".text-danger").html(validater.isnull);
                return false;
            } else {
                if (!validateRules.isPlusintege($.trim(s.prop("value")))) {
                    s.next(".text-danger").html(validater.isintege);
                    return false;
                }
            }

            if (validateRules.isNull($.trim(b.prop("value")))) {
                b.parent().next(".text-danger").html("<br/>" + validater.isnull);
                return false;
            } else {
                if (!validateRules.isPlusdecmal($.trim(b.prop("value")))) {
                    b.parent().next(".text-danger").html("<br/>" + validater.isdecmal);
                    return false;
                }
            }

            // 供应商不是必选
            // if($.trim(e.val())=="-1")
            // {
            //     e.next(".text-danger").html(validater.issupplier);
            //     return false;
            // }

            var _temp = {
                "card": attrid,
                "innum": $.trim(s.prop("value")),
                "price": $.trim(b.prop("value")),
                "supname": $.trim(e.val())
            };
            attr.push(_temp);

            // 输出到父页面的数据
            _temp = {
                "class": $("#ProType option:selected").text(),
                "proName": $("#ProName option:selected").text(),
                "card": attrid,
                "inputcard": $.trim(par.find("td:eq(1)").html()),
                "attrName": $.trim(par.find("td:eq(2)").html()),
                "innum": $.trim(s.prop("value")),
                "price": $.trim(b.prop("value")),
                "supname": $.trim(e.val())
            };
            dataList.push(_temp);
        });

        if (attr.length == 0) {
            art.dialog.alert('未找到勾选商品项！'); // 提示语句
            // 提示语句
            return false;
        }

        var InWarehouseId = $.trim($("#InWarehouseId").html());
        var data = {}; // 提交数据

        // 如果已经录入了至少一个商品
        if (InWarehouseId != "") {
            data = {
                "ty": "in_warehouse",
                "id": InWarehouseId,
                "classid": $.trim(ProClass.val()),
                "proid": pro_id,
                "attr": attr,
                "r": (Math.random() * Math.random())
            };
        } else {
            data = {
                "ty": "new_in_warehouse",
                "id": "",
                "inid": $("#inputWarehouse", parent.window.document).val(),
                "inname": $("#inputWarehouse", parent.window.document).find("option:selected").text(),
                "inpid": $("#inputName", parent.window.document).val(),
                "inpname": $("#inputName", parent.window.document).find("option:selected").text(),
                "proid": pro_id,
                "classid": $.trim(ProClass.val()),
                "attr": attr,
                "riqi": $.trim($("#inDate", parent.window.document).val()),
                "intype": $("#inType", parent.window.document).val(),
                "r": (Math.random() * Math.random())
            };
        }
        art.dialog.tips('数据正在提交..', 20, true);
        // 服务器提交数据
        $.ajax({
            type: "POST",
            url: bindShowInfo.postSaveUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: data,
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                art.dialog.tips('数据已提交成功，您可以继续添加其他入库商品..', 1);
                // 成功
                if (data.code == "0") {
                    if (InWarehouseId == "") {
                        $("#InWarehouseId").html(data.InWarehouseId);
                        // 父页面操作
                        $("#InWarehouseId", parent.window.document).html(data.InWarehouseId);
                    }

                    ProName.empty(); // 清空商品数据
                    ProName.append('<option value="-1">输入商品名称或编码查找</option>');
                    ProName.trigger("chosen:updated");

                    ProSpectbody.empty(); // 清空规格数据

                    // 父页面数据操作
                    if (InWarehouseId == "" || InWarehouseId== null) {
                        $("#inputWarehouse", parent.window.document).prop("disabled", "disabled");
                        $("#inDate", parent.window.document).prop("disabled", "disabled");
                    }

                    var inwarehouselist = $("#inwarehouselist", parent.window.document);
                    $(dataList).each(function(index, val) {
                        inwarehouselist.append('<tr><td><input type="checkbox" value="' + val.card + '"></td><td>' + val.class.replace("---", "") + '</td><td>' + val.proName + '</td><td>' + val.inputcard + '</td><td>' + val.attrName + '</td><td>' + val.innum + '</td><td>' + val.price + '</td><td>' + parseFloat(val.innum) * parseFloat(val.price) + '</td><td>' + (val.supname == "选择供货商" ? "" : val.supname) + '</td></tr>');
                    });
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