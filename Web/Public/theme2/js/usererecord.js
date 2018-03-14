$(document).ready(function () {
	var mydate = new Date();
	new YMDselect('recordyear', 'recordmonth', mydate.getFullYear(), mydate.getMonth() + 1);
	  $('.recordlist').css('height', $(document).height() - 40 + "px");
		$('.recordcons').css('width', $(document).width() - 65 + "px");

		$("#recordmonth").change(function () {
				var dataym = '';
				if ($("#recordmonth").val() < 10) {
						dataym = $("#recordyear").val() + '-0' + $("#recordmonth").val();
				} else {
						dataym = $("#recordyear").val() + '-' + $("#recordmonth").val();
				}
				tips('waiting','正在加载...',15000);
				$.ajax({
						type: "post",
						url: urecord,
						data: 'dhtype=1' + '&dataym=' + dataym,
						dateType: "json",
						complete:function(msg){
							hidetips('waiting');
						},
						success: function (msg) {
								if (msg.status == 'true') {
										var htmls = '';
										var bdatas = msg['dhdata'];
										for (var key in bdatas) {
												htmls += '<div class="recordpart">' +
														'<img class="recordimg" src="'+bdatas[key].ProLogoImg+'">' +
														'<label class="recordcons">-'+bdatas[key].Money+'积分<span class="recordcons-1">'+bdatas[key].PayDate+
														'</span><br><span>'+bdatas[key].ProTitle+'</span></label></div>';
										}
										$(".recordlist").html(htmls);
										tips('notice', '加载完成!', 1500, 'weui_icon_toast');
										$('.recordcons').css('width', $(document).width() - 65 + "px");
								} else {
										$(".recordlist").html("");
										tips('notice', '此月无兑换数据!', 1500, 'weui_icon_notice');
								}
						}
				})
		})

});
