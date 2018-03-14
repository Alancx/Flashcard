$(document).ready(function() {

    var dateStart = $('#dateStart'); // 开始日期
    var dateEnd = $('#dateEnd'); // 结束日期
    var btnok = $("#btnSeloK"); // 检索
    var Warehouse = $("#Warehouse"); // 仓库
    var incard = $("#incard"); // 入库单号
    var proType = $("#proType"); // 商品类别
    var proid = $("#proid"); // 商品名称或商品规格编号
    var timelinelist = $(".timeline"); // 数据信息
    var btnmore = $("#btnmore");
    var selpagesize = $("#select-page-size"); // 页条数
    var selpageindex = $("#hf-page-index"); // 页索引
    var selpagecount = $("#hf-page-count"); // 总数据
    var selpageend = $("#sp-page-end"); // 已查询条数
    var outtype=$("#outtype");

    // 日历控件
    dateStart.datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });

    // 日历控件
    dateEnd.datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });

    $(document).on("click", ".par-in-a", function() {
        var _that = $(this);
        var type = $.trim(_that.attr("data-type"));
        var tempOutWarehouseId = $.trim(_that.attr("data-cid"));
        if (type == "delete") {
            art.dialog.confirm('点击确定删除该单据，不能恢复撤销，确定删除？', function () {
                $.ajax({
                    type: "POST",
                    url: bindinfo.postDelURl,
                    contentType: "application/x-www-form-urlencoded; charset=utf-8",
                    data: {
                        "ty": "del_out_warehouse",
                        "id": tempOutWarehouseId,
                        "r": (Math.random() * Math.random())
                    },
                    dataType: "json",
                    timeout: 20000, // 超时时间：20秒
                    success: function(data) {
                        if (data.code == "0") {
                            var this_index = parseInt($.trim(_that.parent().find(".timeline-index").html())); // 获取当前索引
                            _that.parent().parent().parent().parent().parent().remove(); // 删除该单据
                            // 更改索引值
                            timelinelist.find(".timeline-index").each(function() {
                                var _that_son = $(this);
                                var _that_son_index = parseInt($.trim(_that_son.html()));
                                if (_that_son_index > this_index) {
                                    _that_son.html(_that_son_index - 1);
                                }
                            });

                            var templastindex = parseInt($.trim(selpageend.html())) - 1; // 获取当前显示的最后索引值
                            selpageend.html(templastindex); // 更改最后大小值
                            var temptotal = parseInt($.trim(selpagecount.val())) - 1;
                            selpagecount.val(temptotal); // 更改记录总数
                            $("#sp-page-count").html(temptotal); // 更改记录总数
                            btnmore.css("display", "none");

                            var temppagesize = parseInt($.trim(selpagesize.val())); // 获取设置的页大小

                            //总页码
                            var rpage = temptotal % temppagesize;
                            if (rpage > 0) {
                                rpage = parseInt(temptotal / temppagesize) + 1;
                            } else {
                                rpage = parseInt(temptotal / temppagesize);
                            }

                            // 当前显示的最后页码
                            var rpages = templastindex % temppagesize;
                            if (rpages > 0) {
                                rpages = parseInt(templastindex / temppagesize) + 1;
                            } else {
                                rpages = parseInt(templastindex / temppagesize);
                            }

                            if ((templastindex == 0 || templastindex == (rpages - 1)) && temptotal > 0 && templastindex != temptotal) {
                                selpageindex.val(rpages);
                                btnmore.css("display", "block");
                            } else {
                                if (templastindex != rpages * temppagesize && templastindex != temptotal) {
                                    selpageindex.val(rpages);
                                    btnmore.css("display", "block");
                                } else {
                                    selpageindex.val(rpages);
                                    btnmore.css("display", "none");
                                }
                            }

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
            });
        } else {
            window.location.href = bindinfo.postcontinueUrl + "?cid=" + tempOutWarehouseId;
        }
    });


    // 加载数据信息
    $(document).on("click", ".btnselect", function() {

        // 数据判断
        var s = $.trim(dateStart.val());
        if (validateRules.isNull(s)) {
            art.dialog.alert(bindinfo.stimeEmpty);
            return false;
        } else {
            if (!validateRules.isDate(s)) {
                art.dialog.alert(bindinfo.stimeError);
                return false;
            }
        }
        var e = $.trim(dateEnd.val());
        if (validateRules.isNull(e)) {
            art.dialog.alert(bindinfo.etimeEmpty);
            return false;
        } else {
            if (!validateRules.isDate(e)) {
                art.dialog.alert(bindinfo.etimeError);
                return false;
            }
        }

        if (Date.parse(s) > Date.parse(e)) {
            art.dialog.alert(bindinfo.setimeplus);
            return false;
        }

        var bty = $.trim($(this).attr("data-type"));
        var tempindexend = parseInt($.trim(selpageend.html()));
        if (bty == "first") {
            timelinelist.empty();
            tempindexend = 0;
        }
        var data = {
            "ty": bty,
            "w": $.trim(Warehouse.val()),
            "cid": $.trim(incard.val()),
            "t": $.trim(proType.val()),
            "pid": $.trim(proid.val()),
            "stime": s,
            "etime": e,
            "outtype":outtype.val(),
            "pindex": (bty == "first" ? 1 : (parseInt($.trim(selpageindex.val())) + 1)), // 页码
            "psize": $.trim(selpagesize.val()), // 页条数
            "pcount": (bty == "first" ? $.trim(selpagesize.val()) : $.trim(selpagecount.val())), // 总数
            "r": (Math.random() * Math.random())
        };

        art.dialog.tips('正在查询出库商品..', 20);

        // 服务器提交数据
        $.ajax({
            type: "POST",
            url: bindinfo.postUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: data,
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                art.dialog.tips('正在处理获取数据..', 0.5);
                // 成功
                if (data.code == "0") {
                    if (data.pagecount > 0) {
                        var datalist = [];
                        var _html = "";
                        var _rows = 0;
                        $(data.datapar).each(function(index, val) {

                            _rows++;

                            var _type="";
                            var deltime = 8;
                            if(val.Type==2)
                            {
                                _type="线下销售";
                            }
                            else if(val.Type==1)
                            {
                                _type="退货出库";
                                deltime = 30;
                            }
                            else if(val.Type==0){
                                _type="调拨出库";
                            }

                            // 主单数据
                            _html = '<div class="timeline-item"><div class="row"><div class="col-xs-2 date"><i class="fa fa-file-text"></i><small>' + val.Date + '</small><br><small class="' + (val.Status == "已出库" ? "text-navy" : "text-danger") + '">' + val.Status + '</small></div><div class="col-xs-10 content"><p class="m-b-xs" style="border-width:1px;padding-bottom:10px;"><span class="label label-primary">系统单号：' + val.OutWarehouseId + '，手工单号：' + val.OutWarehouseNumber + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">类型：' + _type + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">目标：' + val.InStorehouseName + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">出库人：' + val.OutputName + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">总数量：' + val.Count + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">总金额：' + Number(val.Money).toFixed(2) + '</span><small class="pull-right text-navy">';

                            if (val.Status == "待出库") {

                                if (dateDiff(val.Date, new Date()) < deltime) {
                                    _html += '<a class="text-navy par-in-a" data-cid=' + val.OutWarehouseId + ' data-type="continue">继续出库</a> &nbsp;&nbsp;&nbsp;&nbsp; <a class="text-danger par-in-a" data-cid=' + val.OutWarehouseId + ' data-type="delete">删除</a>';
                                } else {
                                    _html += '<a class="text-navy par-in-a" data-cid=' + val.OutWarehouseId + ' data-type="delete">出库过期，点击删除</a>';
                                }
                            }

                            _html += '&nbsp;&nbsp;&nbsp;&nbsp;<span class="timeline-index">' + (tempindexend + _rows) + "</span></small>";
                            // 备注
                            if (!validateRules.isNull(val.Remarks)) {
                                _html += '<div class="well">备注：' + val.Remarks + '</div>';
                            }

                            // 子单表格数据
                            _html += '<table class="table table-striped table-hover"><thead><tr><th>#</th><th>上级分类</th><th>商品名称</th><th>商品规格编码</th><th>规格/属性</th><th>入库数量</th><th>入库价格</th><th>合计</th></tr></thead><tbody>';

                            $(val.datason).each(function(index, vo) {
                                _html += '<tr><td>' + (index + 1) + '<td>' + vo.ClassName + '</td><td>' + vo.ProName + '</td><td>' + vo.ProIdInputCard + '</td><td>' + vo.Spec + '</td><td>' + vo.Count + '</td><td>' + Number(vo.Price).toFixed(2) + '</td><td>' + Number(vo.Money).toFixed(2) + '</td></tr>';
                            });

                            // 输出底部信息
                            _html += '</tbody></table></div></div></div>';
                            datalist.push(_html);
                        });
                        timelinelist.append(datalist.join("")); // 放数据

                        $("#sp-page-end").html(tempindexend + _rows);
                        $("#sp-page-count").html(data.pagecount);
                        selpagecount.val(data.pagecount);
                        selpageindex.val(data.pageindex);

                        if (tempindexend + _rows < data.pagecount) {
                            btnmore.css("display", "block");
                        } else {
                            btnmore.css("display", "none");
                        }

                    } else if (data.pagecount == 0) {
                        // 空数据信息处理
                        resets();
                        timelinelist.html('<div class="alert alert-warning" >' + bindinfo.emptyinfo + '</div>');
                    } else if (data.pagecount < 0) {
                        btnmore.css("display", "none");
                    }
                } else {

                    resets();
                    timelinelist.html('<div class="alert alert-warning" >' + bindinfo.infoerror + '</div>');
                    // 错误信息输出
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
    });

    // 重置数据
    var resets = function() {
        timelinelist.empty();
        $("#sp-page-end").html("0");
        $("#sp-page-count").html("0");
        selpagecount.val("25");
        selpageindex.val("0");
        btnmore.css("display", "none");
    }
});