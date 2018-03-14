$(document).ready(function() {
    var Warehouse = $("#Warehouse");
    var proType = $("#proType");
    var stime = $("#stime");
    var proName = $("#proName");
    var exportKC=$("#exportKC");
    var datable;
    // 查询
    $("#selectKC").bind("click", function() {
        var s = $.trim(stime.val());
        if (validateRules.isNull(s)) {
            art.dialog.alert("开始日期不能为空！");
            return false;
        }
        var iszero = $("#isZero").prop("checked") == true ? 1 : 0;
        NProgress.start();
        if (datable != null) {
            console.log(datable);
            datable.fnClearTable(); // 清空数据
        }
        $.ajax({
            type: "POST",
            async: false,
            url: bindInfo.postUrl,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            data: {
                ty:"select",
                wcard: $.trim(Warehouse.val()),
                type: $.trim(proType.val()),
                name: $.trim(proName.val()),
                stime: s,
                iszero: iszero,
                t: (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 20000, // 超时时间：20秒
            success: function(data) {
                if (data.code == "0") {
                    // 加载实际数据
                    datable = $('#data-example').dataTable({
                        "bDestroy": true,
                        "data": data.dataPro,
                        "deferRender": true,
                        "oLanguage": {
                            'sSearch': '列数据查询:'
                        },
                        "columns": [{
                            "data": "RowNumber"
                        }, {
                            "data": "ClassName"
                        }, {
                            "data": "ProName"
                        }, {
                            "data": "ProIdInputCard",
                        }, {
                            "data": "Spec"
                        }, {
                            "data": "BeginCount"
                        }, {
                            "data": "LeijiInCount"
                        }, {
                            "data": "InCount",
                            render: function(a, b, c, d) {
                                return '<a class="href-data" data-type="1" data-id="' + c.ProIdCard + '">' + c.InCount + '</a>';
                            }
                        }, {
                            "data": "YingCount",
                            render: function(a, b, c, d) {
                                return '<a class="href-data" data-type="2" data-id="' + c.ProIdCard + '">' + c.YingCount + '</a>';
                            }
                        }, {
                            "data": "KuiCount",
                            render: function(a, b, c, d) {
                                return '<a class="href-data" data-type="3" data-id="' + c.ProIdCard + '">' + c.KuiCount + '</a>';
                            }
                        }, {
                            "data": "LeijiSalesCount"
                        }, {
                            "data": "SalesCount"
                            // ,render: function(a, b, c, d) {
                            //     return '<a class="href-data" data-type="4" data-id="' + c.ProIdCard + '">' + c.SalesCount + '</a>';
                            // }
                        }, {
                            "data": "OutCount",
                            render: function(a, b, c, d) {
                                return '<a class="href-data" data-type="5" data-id="' + c.ProIdCard + '">' + c.OutCount + '</a>';
                            }
                        }, {
                            "data": "TuiCount",
                            render: function(a, b, c, d) {
                                return '<a class="href-data" data-type="6" data-id="' + c.ProIdCard + '">' + c.TuiCount + '</a>';
                            }
                        }, {
                            "data": "NowCount"
                        },{
                            "data":"LimitCount"
                        }]
                    });
                    
                    // 判断 下限 变背景色提醒功能
                    $('#data-example tbody').find('tr').each(function(){
                        var last=$(this).find("td").last();
                        var _limit=last.html();
                        var _now=last.prev().html();
                        if((parseFloat(_now)-parseFloat(_limit)) <= 5){
                            $(this).css("background-color","#FEF3A3");
                        }
                    });
                } else {
                    art.dialog.tips('未查询到数据..', 1);
                }
            }
        });
        NProgress.done();
    });

    // 点击打开弹出层
    $(document).on("click", "#data-example .href-data", function() {
        var _that = $(this);
        var _title = "查看商品：" + _that.parent().parent().find("td:eq(2)").html() + "【" + _that.parent().parent().find("td:eq(3)").html() + "】";
        art.dialog.open(bindInfo.postdialogUrl + "?ty=" + $.trim(_that.attr("data-type")) + "&pid=" + $.trim(_that.attr("data-id")) + "&date=" + $.trim(stime.val()) + "&wid=" + $.trim(Warehouse.val()) + "&r=" + (Math.random() * Math.random()), {
            title: _title,
            width:1000,
            lock: true
        });
    });

    exportKC.bind("click",function(){
        var s = $.trim(stime.val());
        if (validateRules.isNull(s)) {
            art.dialog.alert("开始日期不能为空！");
            return false;
        }
        var iszero = $("#isZero").prop("checked") == true ? 1 : 0;
        location.href=bindInfo.postUrl+"?ty=export&wcard="+$.trim(Warehouse.val())+"&type="+$.trim(proType.val())+"&name="+$.trim(proName.val())+"&stime="+s+"&iszero"+iszero;
    });

    NProgress.done();
});