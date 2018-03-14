$(document).ready(function(){
  $(document).on("click", ".btn_send", function() {
    var orderid=$(this).attr('data-oid');
		var sendtype = $(".sendtype"+orderid);
		if (validateRules.isNull($.trim(sendtype.val()))) {
			sendtype.focus();
			return false;
		}
		var sendcard = $(".sendCard"+orderid);
		if (validateRules.isNull($.trim(sendcard.val()))) {
			sendcard.focus();
			return false;
		}
		var b = false;
		var modal_table_send = $(".modal_table_send"+orderid);
		modal_table_send.find(".temp_order_count"+orderid).each(function(index, val) {
			var _that = $(this);
			var count = parseFloat($.trim(_that.html()));
			var stockCount = $.trim(_that.attr("data-stockcount"));
			if (!validateRules.isPlusdecmal(stockCount)) {
				alert(_that.attr("data-proname") + "，已经下架或删除，无法发货！");
				b = true;
				return false;
			} else {
				if (count - parseFloat(stockCount) > 0) {
					alert(_that.attr("data-proname") + "，库存不足！");
					b = true;
					return false;
				}
			}
		});
		if (b) {
			return false;
		}

		// var order_no = $.trim($(this).attr("data-order"));
		$.ajax({
			type: "POST",
			url: binddefaultinfo.postinfoUrl,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: {
				"ty": "update_sendinfo",
				"order_no": orderid,
				"sendname":sendtype.find("option:selected").text(),
				"sendnumber": $.trim(sendtype.val()),
				"card": $.trim(sendcard.val()),
				"r": (Math.random() * Math.random())
			},
			dataType: "json",
			timeout: 20000,
			success: function(data) {
				if (data.code == "0") {
          art.dialog.tips('已发货',2);
          $('#father'+orderid).remove();
					// sendcard.val("");
					// $("#btn_send_hide").click();
					// $("#torderlist").find(".order_send").each(function(index, val) {
					// 	var _that = $(this);
					// 	if ($.trim(_that.attr("data-order")) == order_no) {
					// 		_that.attr("disabled", true);
					// 		_that.css("color", "#337ab7");
					// 		_that.html("已&nbsp;发&nbsp;货");
					// 	}
					// });
				}else{
          art.dialog.tips('发货失败',2);
          console.log(data);
        }
			}
		});

	});
})
