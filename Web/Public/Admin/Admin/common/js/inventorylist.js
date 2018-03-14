var bindinlist = function(type, wid) {
    var _indate = $('#inDate');
    if (type != 0) {
        _indate.append('<option value="-1">请选择盘点日期/盘点单号</option>');
    }
    $(tempdata).each(function(index, val) {
        if (wid == val.StorehouseId) {
            _indate.append('<option value="' + val.InventoryId + '">' + val.Date + '___' + val.InventoryId + '___' + val.Status + '</option>');
        }
    });
};
$(document).ready(function() {
    var inDate = $('#inDate');
    var btnok = $("#btnSeloK"); // 检索
    var Warehouse = $("#Warehouse"); // 仓库
    var timelinelist = $(".timeline"); // 数据信息

    if (num > 0) {
        var _wid = $.trim(Warehouse.val());
        bindinlist(0, _wid);
    }
    // 单号信息
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

    Warehouse.bind("change", function() {
        inDate.empty();
        var _wid = $.trim($(this).val());
        if (num > 0) {
            bindinlist(1, _wid);
            // 刷新数据
            inDate.trigger("chosen:updated");
        } else {
            art.dialog.tips('正在处理..', 0.2);
            $.ajax({
                type: "POST",
                url: bindinfo.postUrl,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                data: {
                    "ty": "get_parlist",
                    "wid": _wid,
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 20000, // 超时时间：20秒
                success: function(datas) {
                    art.dialog.tips('正在处理..', 0.2);
                    inDate.append('<option value="-1">请选择盘点日期/盘点单号</option>');
                    $(datas).each(function(index, val) {
                        inDate.append('<option value="' + val.InventoryId + '">' + val.Date + '___' + val.InventoryId + '___' + val.Status + '</option>');
                    });
                    inDate.trigger("chosen:updated");
                }
            });
        }
    });

    $(document).on("click", ".par-in-a", function() {
        var _that = $(this);
        var type = $.trim(_that.attr("data-type"));
        var inid = $.trim(_that.attr("data-cid"));
        if (type == "delete") {
            NProgress.start();
            $.ajax({
                type: "POST",
                async: false,
                url: bindinfo.postDelURl,
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
                        resets();
                        if (num > 0) {
                            $(tempdata).each(function(index, val) {
                                if (_wid == val.StorehouseId) {
                                    tempdata.splice(index, 1); // 删除该项json
                                }
                            });
                        }
                        var selOpt = $("#inDate option:selected");
                        selOpt.remove();

                        // 刷新数据
                        inDate.trigger("chosen:updated");
                    } else {
                        art.dialog.alert('删除单据出现异常，请稍后重试'); // 提示语句
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
            NProgress.done();
        } else {
            window.location.href = bindinfo.postcontinueUrl + "?cid=" + inid;
        }
    });

    $("#exportKC").bind("click",function(){
        var inid = $.trim(inDate.val());
        if (inid == "-1") {
            art.dialog.alert("请选择盘点日期...");
            return false;
        }
        location.href=bindinfo.postUrl+"?ty=export&id="+inid;
    });

    // 加载数据信息
    $(document).on("click", ".btnselect", function() {
        var inid = $.trim(inDate.val());
        if (inid == "-1") {
            art.dialog.alert("请选择盘点日期...");
            return false;
        }
        NProgress.start();
        // 服务器提交数据
        $.ajax({
            type: "POST",
            async: false,
            url: bindinfo.postUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: {
                "ty": "get_list",
                "id": inid,
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                resets();
                // 成功
                if (data.code == "0") {

                    var _html = '<div class="timeline-item"><div class="row"><div class="col-xs-3 date"><i class="fa fa-file-text"></i><small>' + data.datapar[0].Date + '</small><br><small class="' + (data.datapar[0].Status == "已提交" ? "text-navy" : "text-danger") + '">' + data.datapar[0].Status + '</small></div><div class="col-xs-9 content"><p class="m-b-xs" style="border-width:1px;padding-bottom:10px;"><span class="label label-primary">盘点单号：' + data.datapar[0].InventoryId + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">盘点仓库：' + data.datapar[0].StorehouseName + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">盘点人：' + data.datapar[0].InputName + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">总数量：' + data.datapar[0].Count + '</span><small class="pull-right text-navy">';
                    if (data.datapar[0].Status == "草稿") {
                        _html += '<a class="text-navy par-in-a" data-cid=' + data.datapar[0].InventoryId + ' data-type="continue">继续盘点</a> &nbsp;&nbsp;&nbsp;&nbsp; <a class="text-danger par-in-a" data-cid=' + data.datapar[0].InventoryId + ' data-type="delete">删除</a>';
                    }
                    _html += '</small>';
                    // 备注
                    if (!validateRules.isNull(data.datapar[0].Remarks)) {
                        _html += '<div class="well">备注：' + data.datapar[0].Remarks + '</div>';
                    }
                    // 子单表格数据
                    _html += '<table class="table table-striped table-hover"><thead><tr><th>#</th><th>上级分类</th><th>商品名称</th><th>商品规格编码</th><th>规格/属性</th><th>账面库存</th><th>实盘数量</th><th>盘存差</th><th>修改日期</th></tr></thead><tbody>';
                    $(data.datapar.datason).each(function(index, vo) {
                        _html += '<tr><td>' + (index + 1) + '<td>' + vo.ClassName + '</td><td>' + vo.ProName + '</td><td>' + vo.ProIdInputCard + '</td><td>' + vo.Spec + '</td><td>' + vo.BookCount + '</td><td>' + vo.ActualCount + '</td><td>' + vo.CountPoor + '</td><td>' + vo.Date + '</td></tr>';
                    });
                    // 输出底部信息
                    _html += '</tbody></table></div></div></div>';
                    timelinelist.html(_html); // 放数据

                    // 渲染数据
                    $(".table").dataTable({
                        "oLanguage": {
                            'sSearch': '列数据筛选:'
                        },
                        "pageLength": 25,
                        "bDestroy": true
                    });

                } else {
                    timelinelist.html('<div class="alert alert-warning" >' + bindinfo.emptyinfo + '</div>');
                }
            },
            error: function(XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    art.dialog.tips('检索超时超时..', 1);
                } else {
                    art.dialog.tips('发生未知异常错误..', 1);
                }
            }
        });
        NProgress.done();
    });

    // 重置数据
    var resets = function() {
        timelinelist.empty();
    };
    NProgress.done();
});
