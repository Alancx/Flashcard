function delmyorder(orderid, status) { $.ajax({ type: "POST", url: bindData.setUrl, data: { "ty": "del_myorder", "orderid": orderid, "status": status, "r": (Math.random() * Math.random()) }, dataType: "json", timeout: 10000, success: function (data) { if (data.success) { window.location.href = bindData.toUrl + '/?status=' + bindData.status } else { toastr.error("处理订单出现未知错误!"); } } }) }
function endmyorder(orderid, type) { App.dialog({ title: '订单-确认收货', text: '请确保已经收到货物!确认收货？', okButton: '确认', cancelButton: '取消' }, function (tryAgain) { if (tryAgain) { $.ajax({ type: "POST", url: bindData.setUrl, data: { "ty": "end_myorder", "orderid": orderid, "r": (Math.random() * Math.random()) }, dataType: "json", timeout: 10000, success: function (data) { if (data.success) { if (type == 0) { window.location.href = bindData.toUrl + '/?status=100' } else { window.location.href = bindData.nowUrl } } else { toastr.error("处理订单出现未知错误!"); } }, error: function (XMLHttpRequest, textStatus, thrownError) { if (textStatus == "timeout") { alert("请求超时！"); } else { toastr.error("发生未知异常错误!");  } } }) } }) }

function getSelectBackReason() {
    var _div = document.createElement("div");
    var _select = document.createElement("select");
    _select.id = "backreason";
    _select.className = "form-control";
    _select.options.add(new Option("请选择退款原因", ""));
    _select.options.add(new Option("缺货", "缺货"));
    _select.options.add(new Option("未按时发货", "未按时发货"));
    _select.options.add(new Option("排错/多拍/不想要", "排错/多拍/不想要"));
    _select.options.add(new Option("其他", ""));
    _div.appendChild(_select);
    return _div;
}
function backmoneyfun(orderid, type) {
    App.dialog({
        title: '申请退款',
        text: getSelectBackReason(),
        okButton: '提交申请',
        cancelButton: '取消'
    }, function (tryAgain) {
        if (tryAgain) {
            $.ajax({
                type: "POST",
                url: bindData.setUrl,
                data: {
                    "ty": "back_money",
                    "orderid": orderid,
                    "text": $("#backreason").val(),
                    "r": (Math.random() * Math.random())
                },
                dataType: "json",
                timeout: 10000,
                success: function (data) {
                    if (data.success) {
                        if (type == 0) {
                            window.location.href = bindData.toUrl + '/?status=' + bindData.status;
                        } else {
                            window.location.href = bindData.nowUrl;
                        }
                    } else { toastr.error("处理订单出现未知错误!"); }
                },
                error: function (XMLHttpRequest, textStatus, thrownError) {
                    if (textStatus == "timeout") {
                        toastr.warning("请求超时!");
                    } else {
                        toastr.error("发生未知异常错误!");
                    }
                }
            })
        }
    });
}