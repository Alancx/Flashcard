// console.log(type);
var newtotal=0;
var getDatapager = function(type) {
	var data;
	if (type == "all") {
		data = {
			"ty": type,
			"state": binddefaultinfo.state,
			"pindex": binddefaultinfo.pno,
			"order": "DESC",
			"r": (Math.random() * Math.random())
		}
	} else {
		data = {
			"ty": type,
			"state": binddefaultinfo.state,
			"order_no": $.trim($("#order_no").val()),
			"user_name": $.trim($("#user_name").val()),
			"tel": $.trim($("#tel").val()),
			"start_time": $.trim($("#start_time").val()),
			"end_time": $.trim($("#end_time").val()),
			"buy_way": $.trim($("#buy_way").val()),
			"pindex": binddefaultinfo.pno,
			"order": "DESC",
			"r": (Math.random() * Math.random())
		}
	}
	var alert_message = $("#alert_message");
	var tbody = $("#torderlist");
	tbody.html("");
	$.ajax({
		type: "POST",
		async: false,
		url: binddefaultinfo.postUrl,
		contentType: "application/x-www-form-urlencoded; charset=utf-8",
		data: data,
		dataType: "json",
		timeout: 20000,
		success: function(data) {
			if (data.code == "0") {
				alert_message.css("display", "none");
				// newtotal=data.pageCount;
				binddefaultinfo.pageCount = data.pageCount;
				binddefaultinfo.totalPage = data.totalPage;
				// SetPager();
				var _html = "";
				var rowspan = "";
				$(data.dataOrder).each(function(index, vo) {
					var _payname="积分付款";
					_html = '<tr><td colspan="7"></td></tr><tr><td colspan="7"><input type="checkbox" name="poid" id="" value="'+vo.OrderId+'" />  <span style="color:#000">订单号：' + vo.OrderId + '</span><span class="c-gray">' + _payname + '</span><span class="pull-right"><a data-toggle="modal" data-order="' + vo.OrderId + '" class="data-order-info" data-target="#orderinfo">查看详情</a>&nbsp;-&nbsp;<a data-toggle="modal" data-order="' + vo.OrderId + '" class="data_order_message" data-target="#order_message">备注</a></span></td></tr>';
					$(vo.datason).each(function(sonindex, son) {
						rowspan = "";
						if (parseInt(vo.sonCount) != 1 && sonindex == 0) {
							rowspan = ' rowspan="' + vo.sonCount + '"'
						}
						var _tempState = parseInt(vo.Status);
						var _tempHtmlState = "";
						switch (_tempState) {
							case 1:
								_tempHtmlState = "<p>等待买家付款</p>";
								break;
							case 2:
								_tempHtmlState = '<p>已付款，等待发货</p><p id="s'+vo.OrderId+'"><button class="btn btn-default btn-outline status-button" type="button" onclick="prints(\''+vo.OrderId+'\');">打印发货单</button> &nbsp;<button class="btn btn-default btn-outline status-button" type="button" onclick="printlog(\''+vo.OrderId+'\');">打印快递单</button> &nbsp;<a href="javascript:;" data-toggle="modal" data-order="'+vo.OrderId+'" class="btn btn-outline btn-default status-button order_send" data-target="#ModelSend">发&nbsp;&nbsp;货</a></p>';
								break;
							case 3:
								_tempHtmlState = "<p>卖家已发货</p>";
								break;
							case 4:
								_tempHtmlState = "<p>已收货，交易完成</p>";
								break;
							case 5:
								_tempHtmlState = '<p>买家已退款</p><p><a href="javascript:;" class="btn btn-outline btn-default status-button order_back" data-type="money"  data-transaction="' + vo.TransactionId + '"  data-order="' + vo.OrderId + '">点击确认退款</a></p>';
								break;
							case 6:
								_tempHtmlState = '<p>买家已退货</p><p><a href="javascript:;" class="btn btn-outline btn-default status-button order_back" data-type="pro" data-order="' + vo.OrderId + '">确认收到退货</a></p>';
								break;
							case 7:
								_tempHtmlState = "<p>买家退货成功</p>";
								break;
							case 8:
								_tempHtmlState = "<p>买家退款成功</p>";
								break;
							case 9:
								_tempHtmlState="<p>已过期/已取消，订单关闭</p>";
								break;
							case 10:
								_tempHtmlState = "<p>已收货，交易完成</p>";
								break;
							// default:
							// 	break
						}
						// console.log();
						if (vo.RecevingPost=='ZT') {
							var rname='店铺自提货';
							if (data.SceneContent) {
								rname+="("+data.SceneContent+")";						
							};
						}else{
							var rname=vo.Name;
						}
						_html += '<tr><td class="image-cell"><img style="width:60px;height:60px;" src="' + binddefaultinfo.postRootUrl + son.ProLogoImg + '" alt="" /></td><td class="product-cell"><p><a href="' + binddefaultinfo.postWebRootUrl + "?pid=" + son.ProId + '" target="_blank" title="' + son.ProName + '">' + son.ProName + "</a></p><p>" + (son.Spec==null?"":son.Spec) + "</p></td><td><p>" + Number(son.Price).toFixed(2) + "</p><p>( " + son.Count + " 件)</p></td>";
						if (sonindex == 0) {
							_html += "<td " + rowspan + "><p>" + rname + "</p><p>" + vo.Tel + "</p></td><td " + rowspan + ">" + vo.Date + "</td><td " + rowspan + ">" + _tempHtmlState + "</td><td " + rowspan + "><p>" + Number(vo.Price).toFixed(2) + "</p><p><small class='c-gray'>(含运费：" + Number(vo.Freight).toFixed(2) + ")</small></p></td>"
						}
						_html += "</tr>"
					});
					tbody.append(_html)
				})
			} else {
				alert_message.css("display", "block");
				alert_message.html("还没有相关数据！")
			}
		},
		error: function(XMLHttpRequest, textStatus, thrownError) {
			if (textStatus == "timeout") {
				alert_message.css("display", "block");
				alert_message.html("请求超时!")
			} else {
				alert_message.css("display", "block");
				alert_message.html("发生未知异常错误!")
			}
		}
	})
};
var SetPager = function() {
	// var totals= newtotal || binddefaultinfo.pageCount;
	kkpager.generPageHtml({
		pno: binddefaultinfo.pno,
		total: binddefaultinfo.totalPage,
		totalRecords: binddefaultinfo.pageCount,
		// totalRecords: totals,
		isShowTotalRecords: true,
		isShowTotalPage: false,
		mode: "click",
		click: function(n) {
			NProgress.start();
			binddefaultinfo.pno = n;
			getDatapager(binddefaultinfo.type, binddefaultinfo.state);
			this.selectPage(n);
			// this.init(config);
			NProgress.done();
			return false;
		}
	}, true);
	NProgress.done();
	// console.log(binddefaultinfo.pageCount);
};
$(document).ready(function() {
	var allorder_form=$("#allorder_form");
	var input_state=$("#state");
	var input_buy_way=$("#buy_way");
	var start_time = $("#start_time");
	var end_time = $("#end_time");
	var btn_excel=$("#btn_excel");
	$(".btn-xs").bind("click", function() {
		var nowtime = addDate(CurentTime("dd"), -1);
		var temp_start_time = addDate(nowtime, -parseInt($.trim($(this).attr("data-day"))));
		start_time.val(temp_start_time + " 00:00:00");
		end_time.val(nowtime + " 23:59:59")
	});
	// 确认退款、退货
	$(document).on("click", ".order_back", function() {
		var _that = $(this);
		var _order_no = $.trim(_that.attr("data-order"));
		var type = $.trim(_that.attr("data-type"));
		art.dialog.confirm('确定要进行退款操作吗？',function(){
			if (type=='money') {
				window.location.href="refund.html?oid="+_order_no;
			};
		},function(){
			art.dialog.tips('取消操作',1);
		})
		// var _that = $(this);
		// var _order_no = $.trim(_that.attr("data-order"));
		// var type = $.trim(_that.attr("data-type"));
		// $.ajax({
		// 	type: "POST",
		// 	url: binddefaultinfo.postinfoUrl,
		// 	contentType: "application/x-www-form-urlencoded; charset=utf-8",
		// 	data: {
		// 		"ty": "update_back",
		// 		"back": type,
		// 		"order_no": _order_no,
		// 		"r": (Math.random() * Math.random())
		// 	},
		// 	dataType: "json",
		// 	timeout: 20000,
		// 	success: function(data) {
		// 		if (data.code == "0") {
		// 			_that.attr("disabled", true);
		// 			_that.css("color", "#337ab7");
		// 			if (type == "pro") {
		// 				_that.html("买家退货成功");
		// 			}
		// 			if (type == "money") {
		// 				_that.html("卖家退款成功");
		// 			}
		// 		}
		// 	}
		// });
	});
	// 打开发货
	$(document).on("click", ".order_send", function() {
		var _that = $(this);
		var _order_no = $.trim(_that.attr("data-order"));
		$.ajax({
			type: "POST",
			url: binddefaultinfo.postinfoUrl,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: {
				"ty": "get_sendinfo",
				"order_no": _order_no,
				"r": (Math.random() * Math.random())
			},
			dataType: "json",
			timeout: 20000,
			success: function(datas) {
				var data = datas[0];
				var modal_send_table1 = $("#modal_send_table1");
				modal_send_table1.html("");
				var _payname="积分付款";
				if (data.RecevingPost=='ZT') {
					var rname='店铺自提货';
					if (data.SceneContent) {
						rname+="("+data.SceneContent+")";						
					};
				}else{
					var rname=data.MemberId;
				}
				modal_send_table1.html('<tr><td style="width:60px;">订单编号：</td><td><span>' + data.OrderId + '</span><span></span></td></tr><tr><td>付款方式：</td><td>' + _payname + '</td><tr><td>付款时间：</td><td><span>' + data.PayDate + '</span></td></tr><tr><td>买家：</td><td><span>' + rname + '</span></td></tr><tr><td>收货人：</td><td>' + data.RecevingName + '</td></tr><tr><td>联系方式：</td><td>' + data.RecevingPhone + '</td></tr><tr><td>收货地址：</td><td><p>' + data.Receving + '</p></td></tr>');
				var _html = '<tr><td style="width:60px;">买家留言：</td><td>';
				if (data.MessageByBuy != "") {
					_html += "<p>" + data.MessageByBuy + "</p>";
				} else {
					_html += "-";
				}
				_html += "</td></tr>";
				var modal_send_table2 = $("#modal_send_table2");
				modal_send_table2.html("");
				modal_send_table2.html(_html);

				var modal_table_send = $("#modal_table_send");
				modal_table_send.html("");
				_html = "";
				$(datas.list).each(function(index, son) {
					var _limit="";
					if((parseFloat(son.Count)-parseFloat(son.StockCount))>0)
					{
						_limit='<span style="color:red;">库存不足</span>';
					}

					_html += '<tr class="test-item"><td class="td-goods-image"><div class="ui-centered-image" style="width:48px;height:48px;"><img src="' + binddefaultinfo.postRootUrl + son.ProLogoImg + '" style="max-width:48px;max-height:48px;"></div></td><td><a href="' + binddefaultinfo.postRootUrl + son.PageUrl + '" target="_blank">' + son.ProName + '</a><p class="c-gray">' + (son.Spec==null?"":son.Spec) + '</p></td><td><span>' + Number(son.Price).toFixed(2) + '</span></td><td class="temp_order_count" data-proname="' + son.ProName + ":" + son.Spec + '" data-stockcount="' + son.StockCount + '">' + son.Count + '</td><td><p>' + Number(son.Money).toFixed(2) + '</p><div></div></td><td>' + son.StockCount + _limit +'</td><td style="color:red;">' + son.LimitCount + '</td></tr>';
				});
				modal_table_send.html(_html);
				$("#btn_send").attr("data-order", data.OrderId);
			},
			error: function(XMLHttpRequest, textStatus, thrownError) {
				if (textStatus == "timeout") {} else {}
			}
		});
	});
	// 发货
	$(document).on("click", "#btn_send", function() {
		var sendtype = $("#sendtype");
		if (validateRules.isNull($.trim(sendtype.val()))) {
			sendtype.focus();
			return false;
		}
		var sendcard = $("#sendCard");
		if (validateRules.isNull($.trim(sendcard.val()))) {
			sendcard.focus();
			return false;
		}
		var b = false;
		var modal_table_send = $("#modal_table_send");
		modal_table_send.find(".temp_order_count").each(function(index, val) {
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

		var order_no = $.trim($(this).attr("data-order"));
		$.ajax({
			type: "POST",
			url: binddefaultinfo.postinfoUrl,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: {
				"ty": "update_sendinfo",
				"order_no": order_no,
				"sendname":sendtype.find("option:selected").text(),
				"sendnumber": $.trim(sendtype.val()),
				"card": $.trim(sendcard.val()),
				"r": (Math.random() * Math.random())
			},
			dataType: "json",
			timeout: 20000,
			success: function(data) {
				if (data.code == "0") {
					sendcard.val("");
					$("#btn_send_hide").click();
					$("#torderlist").find(".order_send").each(function(index, val) {
						var _that = $(this);
						if ($.trim(_that.attr("data-order")) == order_no) {
							_that.attr("disabled", true);
							_that.css("color", "#337ab7");
							_that.html("已&nbsp;发&nbsp;货");
						}
					});
				}
			}
		});

	});
	// 订单明细
	$(document).on("click", ".data-order-info", function() {
		NProgress.start();
		var _that = $(this);
		var _order_no = $.trim(_that.attr("data-order"));
		var modal_status_step = $("#modal_status_step");
		modal_status_step.html("");
		var modal_order_status = $("#modal_order_status");
		var modal_order_status_desc = $("#modal_order_status_desc");
		modal_order_status.html("");
		modal_order_status_desc.html("");
		$.ajax({
			type: "POST",
			url: binddefaultinfo.postinfoUrl,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: {
				"ty": "get_info",
				"order_no": _order_no,
				"r": (Math.random() * Math.random())
			},
			dataType: "json",
			timeout: 20000,
			success: function(datas) {
				var data = datas[0];
				var _state = parseInt(data.Status);
				switch (_state) {
					case 1:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta"></div></li><li><div class="ui-step-title">商家发货</div><div class="ui-step-number">3</div><div class="ui-step-meta"></div></li><li><div class="ui-step-title">交易完成</div><div class="ui-step-number">4</div><div class="ui-step-meta"></div></li>';
						modal_order_status.html("等待买家付款");
						modal_order_status_desc.html("<div><span>如买家未在规定时间内付款，订单将按照设置逾期自动关闭；</span></div>");
						break;
					case 2:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.PayDate + '</div></li><li><div class="ui-step-title">商家发货</div><div class="ui-step-number">3</div><div class="ui-step-meta"></div></li><li><div class="ui-step-title">交易完成</div><div class="ui-step-number">4</div><div class="ui-step-meta"></div></li>';
						modal_order_status.html("等待发货");
						break;
					case 3:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.PayDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">商家发货</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.ShipDate + '</div></li><li><div class="ui-step-title">交易完成</div><div class="ui-step-number">4</div><div class="ui-step-meta"></div></li>';
						modal_order_status.html("已发货，等待交易成功");
						modal_order_status_desc.html("<div><span>买家如在<em> 发货后15天内 </em>没有申请退款，交易将自动完成；</span></div>");
						break;
					case 4:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.PayDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">商家发货</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.ShipDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">交易完成</div><div class="ui-step-number">4</div><div class="ui-step-meta">' + data.GetDate + "</div></li>";
						modal_order_status.html("交易完成");
						break;
					case 5:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.PayDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">退款中</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.BackMoneyDate + '</div></li><li><div class="ui-step-title">退款成功</div><div class="ui-step-number">4</div><div class="ui-step-meta"></div></li>';
						modal_order_status.html("退款中");
						break;
					case 6:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">买家收货</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.GetDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">买家退货</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.BackProDate + '</div></li><li><div class="ui-step-title">退货成功</div><div class="ui-step-number">4</div><div class="ui-step-meta"></div></li>';
						modal_order_status.html("退货中");
						break;
					case 7:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">买家收货</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.GetDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">买家退货</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.BackProDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">退货成功</div><div class="ui-step-number">4</div><div class="ui-step-meta">' + data.BackProOkDate + "</div></li>";
						modal_order_status.html("卖家收货");
						break;
					case 8:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.PayDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">退款中</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.BackMoneyDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">退款成功</div><div class="ui-step-number">4</div><div class="ui-step-meta">' + data.BackMoneyOkDate + "</div></li>";
						modal_order_status.html("退款成功");
						break;
					case 9:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">订单已取消</div><div class="ui-step-number">2</div><div class="ui-step-meta">'+data.ValidDate+'</div></li>';
						modal_order_status.html("已取消");
						break;
					case 10:
						_html = '<li class="ui-step-done"><div class="ui-step-title">买家下单</div><div class="ui-step-number">1</div><div class="ui-step-meta">' + data.CreateDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">付款至商城</div><div class="ui-step-number">2</div><div class="ui-step-meta">' + data.PayDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">商家发货</div><div class="ui-step-number">3</div><div class="ui-step-meta">' + data.ShipDate + '</div></li><li class="ui-step-done"><div class="ui-step-title">交易完成</div><div class="ui-step-number">4</div><div class="ui-step-meta">' + data.GetDate + "</div></li>";
						modal_order_status.html("交易完成");
						break;
					default:
						modal_order_status.html("未知");
						break
				}
				modal_status_step.html(_html);
				_html = "";
				var modal_info_table1 = $("#modal_info_table1");
				modal_info_table1.html("");
				var _payname="积分付款";
				if (data.RecevingPost=='ZT') {
					var rname='店铺自提货';
					if (data.SceneContent) {
						rname+="("+data.SceneContent+")";						
					};
				}else{
					var rname=data.MemberId;
				}
				modal_info_table1.html('<tr><td style="width:60px;">订单编号：</td><td><span>' + _order_no + '</span><span></span></td></tr><tr><td>付款方式：</td><td>' + _payname + '</td><tr><td>买家：</td><td><span>' + rname + '</span></td></tr>');
				if (data.Receving != "") {
					_html += "<tr><td>收货信息：</td><td><p>" + data.Receving + "</p></td></tr>"
				}
				_html += '<tr><td style="width:60px;">买家留言：</td><td>';
				if (data.MessageByBuy != "") {
					_html += "<p>" + data.MessageByBuy + "</p>";
				} else {
					_html += "-"
				}
				_html += "</td></tr>";
				var modal_info_table2 = $("#modal_info_table2");
				modal_info_table2.empty();
				modal_info_table2.html(_html);
				var modal_order_sell_message = $("#modal_order_sell_message");
				modal_order_sell_message.html("");
				modal_order_sell_message.html("卖家备注：" + (data.MessageBySeller == "" ? "-" : data.MessageBySeller));
				_html = "";
				var modaltableinfo = $("#modal_table_info");
				modaltableinfo.html("");
				if (_state != 1 && _state != 2) {
					_html += '<tr class="tr-express"><td><strong><span>物流 -</span></strong></td><td><span class="express-meta">' + data.Logistics + '</span><span class="express-meta"><span>运单号：</span><span>' + data.LogisticsId + '</span></span></td><td colspan="4"></td><td class="td-postage" rowspan="10000">' + (parseInt(data.Freight) == 0 ? "免运费" : Number(data.Freight).toFixed(2)) + "</td></tr>"
				}
				$(datas.list).each(function(index, son) {
					_html += '<tr class="test-item"><td class="td-goods-image"><div class="ui-centered-image" style="width:48px;height:48px;"><img src="' + binddefaultinfo.postRootUrl + son.ProLogoImg + '" style="max-width:48px;max-height:48px;"></div></td><td><a href="' + binddefaultinfo.postWebRootUrl + '?pid=' + son.ProId + '" target="_blank">' + son.ProName + '</a><p class="c-gray">' + (son.Spec==null?"":son.Spec) + "</p></td><td><span>" + Number(son.Price).toFixed(2) + "</span></td><td>" + son.Count + "</td><td><p>" + Number(son.Money).toFixed(2) + "</p><div></div></td>";
					switch (_state) {
						case 1:
							_html += "<td>待付款</td>";
							break;
						case 2:
							_html += "<td>待发货</td>";
							break;
						case 3:
							_html += "<td>已发货</td>";
							break;
						case 4:
							_html += "<td>交易完成</td>";
							break;
						case 5:
							_html += "<td>退款中</td>";
							break;
						case 6:
							_html += "<td>退货中</td>";
							break;
						case 7:
							_html += "<td>卖家收货</td>";
							break;
						case 8:
							_html += "<td>退款成功</td>";
							break;
						case 9:
							_html += "<td>订单已关闭</td>";
							break;
						case 10:
							_html += "<td>交易完成</td>";
							break;
						default:
							_html += "<td></td>";
							break
					}
					if ((_state == 1 || _state == 2) && index == 0) {
						_html += '<td class="td-postage" rowspan="10000">' + (parseInt(data.Freight) == 0 ? "免运费" : Number(data.Freight).toFixed(2)) + "</td>"
					}
					_html += "</tr>"
				});
				modaltableinfo.html(_html);
				var tfoot_real_money = $("#tfoot_real_money");
				tfoot_real_money.html("");
				tfoot_real_money.html(Number(data.Price).toFixed(2))
			},
			error: function(XMLHttpRequest, textStatus, thrownError) {
				if (textStatus == "timeout") {} else {}
			}
		});
		NProgress.done();
	});
	// 订单卖家备注
	$(document).on("click", ".data_order_message", function() {
		binddefaultinfo.order_no = $.trim($(this).attr("data-order"));
		$.ajax({
			type: "POST",
			url: binddefaultinfo.postinfoUrl,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: {
				"ty": "get_message",
				"order_no": binddefaultinfo.order_no,
				"r": (Math.random() * Math.random())
			},
			dataType: "json",
			timeout: 20000,
			success: function(datas) {
				$("#js-remark").val(datas[0].MessageBySeller);
			}
		})
	});
	// 更新订单卖家备注
	$(document).on("click", "#btn_message", function() {
		var message = $("#js-remark").val();
		$.ajax({
			type: "POST",
			url: binddefaultinfo.postinfoUrl,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: {
				"ty": "update_message",
				"order_no": binddefaultinfo.order_no,
				"msg": message,
				"r": (Math.random() * Math.random())
			},
			dataType: "json",
			timeout: 20000,
			success: function(data) {
				if (data == "1") {
					binddefaultinfo.order_no = "";
				}
			}
		})
	});
	var valiIs=function(){
		var s = $.trim(start_time.val());
		var e = $.trim(end_time.val());
		if (validateRules.isNull(s)) {
			art.dialog.alert("下单时间：开始日期不能为空!");
			return false
		}
		else if(validateRules.isNull(e)) {
			art.dialog.alert("下单时间：结束日期不能为空!");
			return false
		}
		else if (Date.parse(s) > Date.parse(e)) {
			art.dialog.alert("下单时间：开始时间小于结束时间！");
			return false
		}
		else {
			return true;
		}
	};
	// 筛选
	$("#btn_select").bind("click", function() {
		NProgress.start();
		binddefaultinfo.state = $("#state").val();
		$(".nav-tabs").find("a").each(function(index, val) {
			var _that = $(this);
			if (_that.attr("data-state") == binddefaultinfo.state) {
				_that.attr("aria-expanded", "true");
				_that.parent().attr("class", "active")
			} else {
				_that.attr("aria-expanded", "false");
				_that.parent().attr("class", "")
			}
		});
		binddefaultinfo.pno = 1;
		binddefaultinfo.type = "select";
		binddefaultinfo.pageCount = 0;
		binddefaultinfo.totalPage = 1;
		getDatapager("select");
		SetPager()
	});
	$("#export-close").bind("click",function(){btn_excel.attr("data-toggle","");});
	btn_excel.bind("click", function() {
		if(valiIs()){
			$(this).attr("data-toggle","modal");
			$("#export_time").html($.trim(start_time.val())+"  至  "+$.trim(end_time.val()));
			$("#export_state").html(input_state.find("option:selected").text());
			$("#export_pay_type").html(input_buy_way.find("option:selected").text());
			$('#export_user_name').html($.trim($("#user_name").val()));
		}
	});
	$(document).on("click",".btn-js-export",function(){
		var ty=$(this).attr("data-export-type");
		if(ty=="default"){
			location.href=binddefaultinfo.postExport+"?ty="+$(this).attr("data-export-type")+"&state="+input_state.val()+"&user_name="+$.trim($("#user_name").val())+"&start_time="+$.trim($("#start_time").val())+"&end_time="+$.trim($("#end_time").val())+"&buy_way="+ $.trim($("#buy_way").val())+"&r="+(Math.random() * Math.random());
		};
		if (ty=='newxls') {
			location.href=binddefaultinfo.postNewXls+"?ty="+$(this).attr("data-export-type")+"&state="+input_state.val()+"&user_name="+$.trim($("#user_name").val())+"&start_time="+$.trim($("#start_time").val())+"&end_time="+$.trim($("#end_time").val())+"&buy_way="+ $.trim($("#buy_way").val())+"&r="+(Math.random() * Math.random());
		}
	});
	// 面板切换
	$(".panel-active").bind("click", function() {
		NProgress.start();
		allorder_form.find("input[type='text']").val('');
		input_state.val("0");
	    input_buy_way.val("ALL");
		// 将筛选弄空
		binddefaultinfo.pno = 1;
		binddefaultinfo.type = "all";
		binddefaultinfo.pageCount = 0;
		binddefaultinfo.totalPage = 1;
		binddefaultinfo.state = $(this).attr("data-state");
		getDatapager("all");
		SetPager();
	})
});
