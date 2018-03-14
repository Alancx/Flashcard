$(document).ready(function () {
    var mydate = new Date();
    new YMDselect('billyear', 'billmonth', mydate.getFullYear(), mydate.getMonth() + 1);

    $("#billmonth").change(function () {
        var dataym = '';
        if ($("#billmonth").val() < 10) {
            dataym = $("#billyear").val() + '-0' + $("#billmonth").val();
        } else {
            dataym = $("#billyear").val() + '-' + $("#billmonth").val();
        }
        $.ajax({
            type: "post",
            url: ubill,
            data: 'btype=2' + '&dataym=' + dataym,
            dateType: "json",
            success: function (msg) {
                if (msg.status == 'true') {
                    var htmls = '';
                    var bdatas = msg['billinfo'];
                    for (var key in bdatas) {
                        htmls += '<div class="billlb">' +
                            '<label class="billlb-1">订单号:' + bdatas[key].OrderId + '<br><span>' + bdatas[key].PayDate + '</span></label>' +
                            '<label class="billlb-2">-' + bdatas[key].Price + '</label></div>';
                    }
                    $(".billpart").html(htmls);
                    tips('notice', '加载完成!', 1500, 'weui_icon_toast');
                } else {
                    $(".billpart").html("");
                    tips('notice', '此月无消费数据!', 1500, 'weui_icon_notice');
                }
            }
        })
    })
})
