App.controller('myindex_page', function (page) {
    this.onShow = function () {
        if (bindData.procollect != bindData.proyuancollect) {
            $("#pro_collect").html(bindData.procollect);
        }
       var content = $(".app-content");
       var container = $(".app-content .container-fluid");
       if (container.height() > content.height()) {
           content.removeClass("app-content");
       }
    };


    $(document).on("click", "#btnSign", function () {
        var _that = $(this);
        $.ajax({ type: "POST",
            url: bindData.setUrl,
            data: {
                "ty": "add_sign",
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                if (data.success == "0") {
                    $("#intergral").html(data.integral);
                    $("#tosign").html(data.totalSign);
                    $("#consign").html(data.conSign);
                    _that.html("当日已签到");
                    _that.attr("disabled", "disabled");

                }
                else if (data.success == "1") {
                    toastr.warning("今日已经签到");
                    _that.html("当日已签到");
                    _that.attr("disabled", "disabled");
                }
                else if (data.success == "2") {
                    toastr.warning("签到功能已弃用");
                }
                else {
                    toastr.error("签到发生错误!");
                }
            },
            error: function (XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    toastr.error("签到超时!");
                } else {
                    toastr.error("发生未知错误!");
                }
            }
        });
    });
});

// 我的余额
App.controller('myBalance_page', function (page) {
    this.onShow = function () {
        if (bindData.openid != "") {
            var list = $("#tc-info");
            $.ajax({ type: "POST",
                url: bindData.setUrl,
                data: {
                    "ty": "get_tc_moneys",
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 10000,
                success: function (data) {
                    list.html("");
                    $(data).each(function (index, val) {
                        var state = "";
                        if (val.Status == 1) {
                            state = "待处理";
                        }
                        else if (val.Status == 2) {
                            state = "已审核";
                        }
                        else if (val.Status == 3) {
                            state = "已处理";
                        }
                        list.append('<div class="col-xs-12"><p>提款人：' + val.GetName + '</p><p>提款账户：' + val.GetAccount + '</p><p>提款金额：￥' + val.Money + '</p><p>提交日期：' + ChangeDateFormat(val.CreateDate) + '</p><p>处理进度：<span style="color:#f00">' + state + '</span></p>');

                        var content = $(".app-content");
                        var container = $(".container-fluid");
                        if (container.height() > content.height()) {
                            content.removeClass("app-content");
                        }
                    });
                },
                error: function (XMLHttpRequest, textStatus, thrownError) {
                    if (textStatus == "timeout") {
                        list.html("加载超时!");
                    } else {
                        list.html("发生未知错误!");
                    }
                }
            });
        }
    };
    // 提交提现
    $(document).on("click", "#tc_btnok", function () {
        var moneys = $.trim($("#tc_moneyss").html());
        if (parseFloat(moneys) === 0) {
            toastr.warning("提现金额为0，无法提现！");
            return false;
        }
        var tcname = $.trim($("#tc_name").val());
        if (tcname == "") {
            toastr.warning("请填写提现人姓名");
            return false;
        }
        var tcaccount = $.trim($("#tc_account").val());
        if (tcaccount == "") {
            toastr.warning("请填写提现人收款账户");
            return false;
        }
        var type = $('#tcttt input[name="tcType"]:checked').val();
        var tcmoney = $.trim($("#tc_moneyss").val());
        $.ajax({ type: "POST",
            url: bindData.setUrl,
            data: {
                "ty": "tc_moneys",
                "type": 'WXPAY',
                "tcname": tcname,
                "tcaccount": tcaccount,
                "tcmoney": tcmoney,
                "tcdefault": ($("#add_default").prop("checked") == true ? "true" : "false"),
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                if (data.code == 0) {
                    $("#tc_moneys_p").html(data.money);
                    $("#tc_moneyss").val(data.money);
                    $("#tc_btnok").attr("disabled", "disabled");
                    alert("提现申请已提交！");
                    var _html = '<div class="col-xs-12"><p>提款人：' + tcname + '</p><p>提款账户：' + tcaccount + '</p><p>提款金额：￥' + tcmoney + '</p><p>提交日期：' + data.date + '</p><p>处理进度：<span style="color:#f00">待处理</span></p>';
                    $(_html).prependTo("#tc-info");
                } else if (data.code == 3) {
                    toastr.error("提现金额为0，无法提现！");
                    return false;
                } else if (data.code == 2) {
                    toastr.error("上次提现还未处理完成！");
                    return false;
                } else if (data.code == 5) {
                    toastr.error("未找到会员对应的Openid！");
                    return false;
                } else if (data.code == 1) {
                    toastr.error("提现出现未知异常！");
                    return false;
                }
            },
            error: function (XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    list.html("提交超时!");
                } else {
                    list.html("发生未知错误!");
                }
            }
        });
    });
});

// 我的收藏
App.controller('mycollection_page', function (page) {
    this.onShow = function () {
        if (bindData.procollect != bindData.proyuancollect) {
            $.ajax({ type: "POST",
                url: bindData.setUrl,
                data: {
                    "ty": "get_mycollect",
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 10000,
                success: function (data) {
                    var _html = '';
                    $(data).each(function (index, val) {
                        _html += '<div class="list-con col-xs-12" id="list-' + val.ID + '"><span class="glyphicon glyphicon-remove pull-right delcollection" data-id="' + val.ID + '"></span><a href="' + val.PageUrl + '"><div class="col-xs-3"><img src="' + val.ProLogoImg + '" alt="" style="width:60px;height:60px;"/></div><div class="col-xs-9"><h6>' + val.ProName + '</h6></div></a><div class="col-xs-12" style="text-align: right;"><small style="color: #ccc">收藏日期：' + val.CreateDate + '</small></div></div>';
                    });
                    $("#app-mycollection").html(_html);

                    var content = $(".app-content");
                    var container = $(".container-fluid");
                    if (container.height() > content.height()) {
                        content.removeClass("app-content");
                    }
                }
            });
        }
    };

    $(document).on("click", ".delcollection", function () {
        if (confirm("确定删除该收藏产品?")) {
            var cid = $.trim($(this).attr("data-id")); $.ajax({ type: "POST", url: bindData.setUrl, data: { "ty": "del_mycollection", "cid": cid, "r": (Math.random() * Math.random()) }, dataType: "json", timeout: 20000, success: function (data) {
                if (data.success) {
                    $("#list-" + cid).remove();
                    bindData.procollect = bindData.procollect - 1;
                    $("#pro_num").html(bindData.procollect);
                } else { toastr.error("连接超时，请稍后重试！"); }
            }
            })
        }
    });
});

// 我的优惠券
App.controller('mycoupon_page', function (page) {
    this.onShow = function () {
        var mycoupon = $("#mycoupon");
        $.ajax({
            type: "POST",
            url: bindData.postURLS,
            data: { "memberid": bindData.memberid },
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                mycoupon.empty();
                if (data.code == 0) {
                    $(data.coupondata).each(function (index, val) {

                        var rules1 = '';
                        var rules2 = '';

                        if (val.Type == "2") {
                            rules1 = val.Rules.split('/')[1];
                            rules2 = "满" + val.Rules.split('/')[0] + "元使用";
                        } else if (val.Type == "1") {
                            rules1 = Number(val.Rules) * 10 + "折";
                            rules2 = "直接抵用该" + val.CouponName + "设置折扣";
                        } else if (val.Type == "0") {
                            rules1 = val.Rules;
                            rules2 = "直接抵用该优惠券的金额";
                        }

                        mycoupon.append('<a class="mod_coupon bg_2 ' + val.ClassType + '" href="javascript:;" data-code="' + val.CouponId + '" data-id="' + val.id + '"><div class="info"><p class="discount">' + rules1 + '</p><div class="main"><p class="des">' + rules2 + '</p><p class="ex">使用时间：永久</p></div><div class="dot_hr"><i></i></div></div><div class="limit"><p>' + val.CouponName + '</p></div></a>');

                    });
                    
                    var content = $(".app-content");
                    if (mycoupon.height() + 120 > content.height()) {
                        content.removeClass("app-content");
                    }
                    
                } else if (data.code == 40002) {
                    mycoupon.append('<div class="app_empty">未找到您的优惠券</div>');
                }
                else {
                    toastr.error("参数错误!");
                }
            },
            error: function (XMLHttpRequest, textStatus, thrownError) {
                if (textStatus == "timeout") {
                    toastr.warning("请求超时!");
                } else {
                    toastr.error("发生未知异常错误!");
                }
            }
        });
    };
});