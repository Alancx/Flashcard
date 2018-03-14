// 我的资料
App.controller('myinfo_page', function (page) {

    $(page).find("#list-sex").on("click", function () {
        var _that = $(this);
        App.dialog({
            title: '更改性别',
            okButton: '男',
            cancelButton: '女'
        }, function (tryAgain) {
            var _sex = _that.attr("data-sex");
            if (_sex == "0" || (_sex == "1" && !tryAgain) || (_sex == "2" && tryAgain)) {
                $.ajax({
                    type: "POST",
                    url: bindData.setUrl,
                    data: {
                        "ty": "set_sex",
                        "mid": bindData.memberid,
                        "sex": (tryAgain == "ok" ? 1 : 2),
                        "r": (Math.random() * Math.random())
                    },
                    dataType: "json",
                    timeout: 10000,
                    success: function (data) {
                        if (data.success) {
                            $("#list-sex-value").html(data.sex);
                            _that.attr("data-sex", (tryAgain == "ok" ? 1 : 2));
                        } else {
                            toastr.error("更改性别出错!");
                        }
                    }
                });
            }
        });
    });

    $(page).find("#list-province").on("click", function () {
        App.load("myprovince_page", {
            "1": "1"
        });
    });
});

// 昵称
App.controller('nickname_page', function (page) {
    $(page).find(".btn-ok").on('click', function () {
        var nickname = $.trim($(page).find("#nickname").val());
        if (nickname != "") {
            $.ajax({
                type: "POST",
                url: bindData.setUrl,
                data: {
                    "ty": "set_nickname",
                    "mid": bindData.memberid,
                    "nickname": nickname,
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 10000,
                success: function (data) {
                    if (data.success) {
                        App.back(function () {
                            $("#my_nickname").html(nickname);
                        });
                    } else {
                        toastr.error("昵称保存出错!");
                    }
                }
            });
        }
    });
});

// 个性签名
App.controller('signature_page', function (page) {
    $(page).find(".btn-ok").on('click', function () {
        var signature_input = $(page).find("#signature");
        var signature = $.trim(signature_input.val());
        if (signature != "") {
            $.ajax({
                type: "POST",
                url: bindData.setUrl,
                data: {
                    "ty": "set_signature",
                    "mid": bindData.memberid,
                    "signature": signature,
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 10000,
                success: function (data) {
                    if (data.success) {
                        App.back(function () {
                            $("#my_signature").html(signature);
                        });
                    } else {
                        toastr.error("个性签名保存出错!");
                    }
                }
            });
        }
    });
});

// 收货地址
App.controller('addresslist_page', function (page) {
    this.onShow = function () {
        var _addresshtml = '';
        $.ajax({
            type: "POST",
            url: bindData.setUrl,
            async: false,
            data: {
                "ty": "get_myAddress",
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                var _addresshtml = '';
                $(data).each(function (index, val) {
                    var _area = "";
                    if (val.Area != "" && val.Area != null) {
                        _area = '&nbsp;&nbsp;<span class="list-addarea">' + val.Area + '</span>';
                    }
                    _addresshtml += '<div class="list-con col-xs-12" id="list-' + val.ReceivingId + '"> <div class="col-xs-12"><h4><span class="list-addname">' + val.Name + '</span><small class="list-phone">' + val.Phone + '</small><small class="list-addpost pull-right">' + (val.Post == null ? "" : val.Post) + '</small></h4></div><div class="col-xs-12"><p class="list-city"><span class="list-addprovince">' + val.Province + '</span>&nbsp;&nbsp;<span class="list-addcity">' + val.City + '</span>' + _area + '&nbsp;&nbsp;<span class="list-addaddress">' + val.Address + '</span></p></div><div class="col-xs-12 col-sys">';
                    if (val.IsDefault) {
                        _addresshtml += '<div class="col-xs-4 app-button address-default" data-id="' + val.ReceivingId + '" data-type="true"><span class="glyphicon glyphicon-asterisk" style="color:red">默认地址</span></div>';
                    } else {
                        _addresshtml += '<div class="col-xs-4 app-button address-default" data-id="' + val.ReceivingId + '" data-type="false"><span class="glyphicon glyphicon-asterisk">设为默认</span></div>';
                    }
                    _addresshtml += '<div class="col-xs-4 app-button address-edt" style="text-align:right;" data-id="' + val.ReceivingId + '"><span class="glyphicon glyphicon-pencil">编辑</span></div><div class="col-xs-4 app-button address-del" data-type="' + (val.IsDefault == true ? "true" : "false") + '" style="text-align:right;" data-id="' + val.ReceivingId + '"><span class="glyphicon glyphicon-trash" >删除</span></div></div></div>';
                });
                $("#addresslist_row").html(_addresshtml);

                var content = $(".app-content");
                var container = $(".app-content .container-fluid");
                if (container.height() > content.height()) {
                    content.removeClass("app-content");
                }
            }
        });
    };

    $(document).on("click", ".address-del", function () {
        var _that = $(this);
        if (_that.attr("data-type") == "true") {
            toastr.warning("默认地址不能删除!");
            return false;
        } else {
            if (confirm("您确定要删除该地址？")) {
                var aid = $.trim(_that.attr("data-id"));
                $.ajax({
                    type: "POST",
                    url: bindData.setUrl,
                    data: {
                        "ty": "del_myaddress",
                        "aid": aid,
                        "r": (Math.random() * Math.random())
                    },
                    dataType: "json",
                    timeout: 10000,
                    success: function (data) {
                        if (data.success) {
                            $("#list-" + aid).remove();
                        } else {
                            toastr.error("删除失败!");
                        }
                    }
                });
            }
        }
    });

    $(document).on("click", ".address-default", function () {
        var _that = $(this);
        if (_that.attr("data-type") == "false") {
            var aid = $.trim(_that.attr("data-id"));
            $.ajax({
                type: "POST",
                url: bindData.setUrl,
                data: {
                    "ty": "set_addressdefault",
                    "aid": aid,
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 10000,
                success: function (data) {
                    if (data.success) {
                        var aid_list = $("#list-" + aid);
                        $("#addresslist_row").find('.list-con').each(function (index, val) {
                            var _par = $(val).find(".address-default").first();
                            if (_par.attr("data-id") == aid) {
                                _par.attr("data-type", "true");
                                _par.find(".glyphicon-asterisk").first().html("默认地址");
                                _par.find(".glyphicon-asterisk").css("color", "red");
                                _par.parent().find(".address-del").first().attr("data-type", "true");
                            } else {
                                _par.attr("data-type", "false");
                                _par.find(".glyphicon-asterisk").first().html("设为默认");
                                _par.find(".glyphicon-asterisk").css("color", "#999");
                                _par.parent().find(".address-del").first().attr("data-type", "false");
                            }
                            aid_list.prev().before(aid_list);
                        });
                    } else {
                        toastr.error("设置失败!");
                    }
                }
            });
        }
    });

    $(document).on("click", ".address-edt", function () {
        var aid = $(this).attr("data-id");
        var listres = $("#list-" + aid);
        var binddata = {
            aid: aid,
            province: listres.find(".list-addprovince").first().html(),
            city: listres.find(".list-addcity").first().html(),
            area: listres.find(".list-addarea").first().html(),
            address: listres.find(".list-addaddress").first().html(),
            name: listres.find(".list-addname").first().html(),
            post: listres.find(".list-addpost").first().html(),
            phone: listres.find(".list-phone").first().html(),
            isdefault: listres.find(".address-del").first().attr("data-type")
        };
        App.load("address_edt_page", binddata);
    });

    $(page).find("#addresstopage").on("click", function () {
        App.load("address_add_page", {
            "1": "1"
        });
    });
});

// 我的地区设置
App.controller('myprovince_page', function (page) {
    
    // 加载数据
    this.onShow = function () {
        province_city_area.bindProvince("my_province");
        $("#my_city").empty().hide();
    }

    // 点击省份
    $(document).on("click","#my_province .list-group-item",function(){
        var _that=$(this);
        var _province=$.trim(_that.html());
        if(_that.hasClass("active")){
            province_city_area.bindProvince("a_province");
            $("#my_hfprovince").val("");
        }
        else
        {
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active">' + _province + '</a>');
            province_city_area.bindCity(_province,"my_city");
            $("#my_hfprovince").val(_province);
        }
    });

    // 点击城市
    $(document).on("click","#my_city .list-group-item",function(){
        var _that=$(this);
        var _city=$.trim(_that.html()); // 获取城市
        var _province=$("#my_hfprovince").val();
        $.ajax({
            type: "POST",
            url: bindData.setUrl,
            data: {
                "ty": "set_my_province_city",
                "mid": bindData.memberid,
                "res_province": _province,
                "res_city": _city,
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                if (data.success) {
                    App.back("myinfo_page", function () {
                        $("#list-province-value").html(_province);
                        $("#list-province-city-value").html(_city);
                    });
                } else {
                    toastr.error("添加出现错误!");
                }
            }
        });
    });
});

// 收货地址添加
App.controller('address_add_page', function (page) {

    // 加载数据
    this.onShow = function () {
        province_city_area.bindProvince("a_province");
        $("#a_city").empty().hide();
        $("#a_area").empty().hide();
        $("#a_address_info").hide();
    }

    // 点击省份
    $(document).on("click","#a_province .list-group-item",function(){
        var _that=$(this);
        var _province=$.trim(_that.html());
        $("#a_address_info").hide();
        $("#a_hfarea").val("");
        $("#a_hfcity").val("");
        if(_that.hasClass("active")){
            province_city_area.bindProvince("a_province");
            $("#a_city").empty().hide();
            $("#a_area").empty().hide();
            $("#a_address_info").hide();
            $("#a_hfprovince").val("");
        }
        else
        {
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active">' + _province + '</a>');
            province_city_area.bindCity(_province,"a_city");
            $("#a_hfprovince").val(_province);
        }
    });

    // 点击城市
    $(document).on("click","#a_city .list-group-item",function(){
        var _that=$(this);
        var _city=$.trim(_that.html()); // 获取城市
        var type=_that.attr("data-type");   // 判断是否存在下级
        var _province=$("#a_hfprovince").val();
        $("#a_hfarea").val("");
        if(_that.hasClass("active")){
            province_city_area.bindCity(_province,"a_city");
            $("#a_area").empty().hide();
            $("#a_hfarea").val("");
            $("#a_hfcity").val("");
            $("#a_address_info").hide();
        }else{
            $("#a_hfcity").val(_city);
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="' + type + '">' + _city + '</a>');
            if(type=="1"){
                province_city_area.bindArea(_province,_city,"a_area");

                if($("#a_area").html()==""){
                	$("#a_address_info").show();
                }

            }else{
                $("#a_address_info").show();
            }
        }
    });

    // 点击市辖区
    $(document).on("click","#a_area .list-group-item",function(){
        var _that=$(this);
        var _area=$.trim(_that.html()); // 获取市辖区
        var type=_that.attr("data-type");   // 判断是否存在下级
        if(_that.hasClass("active")){
            $("#a_hfarea").val("");
            $("#a_address_info").hide();
            province_city_area.bindArea($.trim($("#a_hfprovince").val()),$.trim($("#a_hfcity").val()),"a_area");
        }
        else{
            $("#a_hfarea").val(_area);
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="' + type + '">' + _area + '</a>');
            $("#a_address_info").show();
        }
    });

    $(document).on("click", "#res_btnok", function () {
        var res_address = $.trim($("#res_address").val());
        if (res_address == "") {
            toastr.warning("详细地址不能为空!");
            return false;
        }
        var res_name = $.trim($("#res_name").val());
        if (res_name == "") {
            toastr.warning("收货人姓名不能为空!");
            return false;
        }
        var res_phone = $.trim($("#res_phone").val());
        if (res_phone == "") {
            toastr.warning("收货人联系电话不能为空!");
            return false;
        }
        $.ajax({
            type: "POST",
            url: bindData.setUrl,
            data: {
                "ty": "add_myaddress",
                "mid": bindData.memberid,
                "res_province": $.trim($("#a_hfprovince").val()),
                "res_city": $.trim($("#a_hfcity").val()),
                "res_area": $.trim($("#a_hfarea").val()),
                "res_address": res_address,
                "res_name": res_name,
                "res_phone": res_phone,
                "res_post": $.trim($("#res_post").val()),
                "res_default": ($("#add_default").prop("checked") == true ? "true" : "false"),
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                if (data.success) {
                    App.back("addresslist_page");
                } else {
                    toastr.error("添加出现错误!");
                }
            }
        });
    });
});

// 收货地址修改
App.controller('address_edt_page', function (page, binddatas) {

    // 绑定基本数据
    $(page).find("#e_hfprovince").val(binddatas.province);
    $(page).find("#e_hfcity").val(binddatas.city);
    $(page).find("#e_hfarea").val(binddatas.area);
    $(page).find("#edt_address").val(binddatas.address);
    $(page).find("#edt_name").val(binddatas.name);
    $(page).find("#edt_phone").val(binddatas.phone);
    $(page).find("#edt_post").val(binddatas.post);
    $(page).find("#edt_default").attr("checked", (binddatas.isdefault == "true" ? true : false));
    $(page).find("#edt_btnok").attr("data-defalut", (binddatas.isdefault == "true" ? true : false)).attr("data-id", binddatas.aid);
    $(page).find("#e_province").empty().append('<a href="javascript:void(0);" class="list-group-item active">' + binddatas.province + '</a>');

    // 绑定数据信息
    if(binddatas.area=="" || binddatas.area==null || binddatas.area=="undefined"){
        $(page).find("#e_city").empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="0">' + binddatas.city + '</a>');
        $(page).find("#e_area").empty();
    }
    else
    {
        $(page).find("#e_city").empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="1">' + binddatas.city + '</a>');
        $(page).find("#e_area").empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="1">' + binddatas.area + '</a>');
    }

    // 点击省份
    $(document).on("click","#e_province .list-group-item",function(){
        var _that=$(this);
        var _province=$.trim(_that.html());
        $("#e_address_info").hide();
        $("#e_hfarea").val("");
        $("#e_hfcity").val("");
        if(_that.hasClass("active")){
            province_city_area.bindProvince("e_province");
            $("#e_city").empty().hide();
            $("#e_area").empty().hide();
            $("#e_address_info").hide();
            $("#e_hfprovince").val("");
        }
        else
        {
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active">' + _province + '</a>');
            province_city_area.bindCity(_province,"e_city");
            $("#e_hfprovince").val(_province);
        }
    });

    // 点击城市
    $(document).on("click","#e_city .list-group-item",function(){
        var _that=$(this);
        var _city=$.trim(_that.html()); // 获取城市
        var type=_that.attr("data-type");   // 判断是否存在下级
        var _province=$("#e_hfprovince").val();
        $("#e_hfarea").val("");
        if(_that.hasClass("active")){
            province_city_area.bindCity(_province,"e_city");
            $("#e_area").empty().hide();
            $("#e_hfarea").val("");
            $("#e_hfcity").val("");
            $("#e_address_info").hide();
        }else{
            $("#e_hfcity").val(_city);
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="' + type + '">' + _city + '</a>');
            if(type=="1"){
                province_city_area.bindArea(_province,_city,"e_area");
                if($.trim($("#e_area").html())==""){
            		$("#e_address_info").show();
            	}
            }else{
                $("#e_address_info").show();
            }
        }
    });

    // 点击市辖区
    $(document).on("click","#e_area .list-group-item",function(){
        var _that=$(this);
        var _area=$.trim(_that.html()); // 获取市辖区
        var type=_that.attr("data-type");   // 判断是否存在下级
        if(_that.hasClass("active")){
            $("#e_hfarea").val("");
            $("#e_address_info").hide();
            province_city_area.bindArea($.trim($("#e_hfprovince").val()),$.trim($("#e_hfcity").val()),"e_area");
        }
        else{
            $("#e_hfarea").val(_area);
            _that.parent().empty().append('<a href="javascript:void(0);" class="list-group-item active" data-type="' + type + '">' + _area + '</a>');
            $("#e_address_info").show();
        }
    });

    $(document).on("click", "#edt_btnok", function () {
        var res_address = $.trim($("#edt_address").val());
        if (res_address == "") {
            toastr.warning("详细地址不能为空!");
            return false;
        }
        var res_name = $.trim($("#edt_name").val());
        if (res_name == "") {
            toastr.warning("收货人姓名不能为空!");
            return false;
        }
        var res_phone = $.trim($("#edt_phone").val());
        if (res_phone == "") {
            toastr.warning("收货人联系电话不能为空!");
            return false;
        }
        $.ajax({
            type: "POST",
            url: bindData.setUrl,
            data: {
                "ty": "edt_myaddress",
                "mid": bindData.memberid,
                "res_id": $(this).attr("data-id"),
                "res_province": $.trim($("#e_hfprovince").val()),
                "res_city": $.trim($("#e_hfcity").val()),
                "res_area": $.trim($("#e_hfarea").val()),
                "res_address": res_address,
                "res_name": res_name,
                "res_phone": res_phone,
                "res_post": $.trim($("#edt_post").val()),
                "res_default": ($("#edt_default").prop("checked") == true ? "true" : "false"),
                "res_redefault": $(this).attr("data-defalut"),
                "r": (Math.random() * Math.random())
            },
            dataType: "json",
            timeout: 10000,
            success: function (data) {
                if (data.success) {
                    App.back("addresslist_page");
                } else {
                    toastr.error("修改出现错误!");
                }
            }
        });
    });
});




