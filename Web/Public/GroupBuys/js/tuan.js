var isclose='0';
$(document).ready(function(){
	// hbshow();
	if (hasgroup == 'false') {
		return false;
	}

	// 自动消失
	setTimeout(function(){
		if ($('.showgoto').css('display')=='block') {
			if (isclose=='0') {
				$(".showgoto").animate({left:"-100%"},300,function(){
					$(".showgoto").css('display','none');
				});
			}
		}
	},3000);
	// 光柱公众号
	if (subscribe !='1') {
		$(".wxgghdiv").css('display','block');
	}
	//关闭参加
	$('.showclose').on('click',function(){
		if ($('.showgoto').css('display')=='block') {
			isclose='1';
			$(".showgoto").animate({left:"-100%"},300,function(){
				$(".showgoto").css('display','none');
			});
		}
	})
	//立即参加
	$(".goto").on('click',function(){
		if ($('.showgoto').css('display')=='none') {
			$(".showgoto").css('display','block');
			$(".showgoto").animate({left:"0px"},300);
		}
	})
	$('.sureget').on('click',function(){
		$(".mymask").css('display','block');
		$(".fillinfor").addClass('show');
	});
	$(".mymask_tap").on('click',function(){
		$(".mymask").css('display','none');
		$(".fillinfor").removeClass('show');
	})
	//音乐开关
	$(".music").on('click',function(){
		var x = document.getElementById("myAudio");
		if(!$(".music").hasClass('on')){
			$(this).addClass('on');
			x.play();
		}else{
			$(this).removeClass('on')
			x.pause();
		}
	})
	//客服
	$(".kefu").on('click',function(){
		$(".kefudiv").css('display','block');
	})
	$(".kefudiv  .mask").on('click',function(){
		$(".kefudiv").css('display','none');
	})
	$(".itphone").on('click',function(){
		var itphone=$('.phone').text();
		location.href='tel:'+itphone;
	});

  // 关闭红包
	$('.closeredinfo').on('click',function(){
		$('.hbcontent').css('display','none');
	})
  // 发表留言
	$('.sendinputremak').on('click',function(){
		sendinputremak();
	});


	// 点击分享按钮
	$('.sharebtn').on('click',function(){
		$('.sharemask').css('display','block');
	});
	// 隐藏
	$('.sharemask').on('click',function(){
		$('.sharemask').css('display','none');
	});
	//点击确认参加
	$(".okgo").on('click',function(){
		sendcreateorder();
	})
	setmarquee();	
	timer(intdiff);
});
document.addEventListener("WeixinJSBridgeReady",function(){
	if (hasgroup == 'false') {
		tips('notice','无团购信息',20000,'weui_icon_notice');
		setTimeout(function(){WeixinJSBridge.call('closeWindow');},3000);
		return false;
	}
	$('#myAudio').attr('src',autio_url);
	setTimeout(function(){document.getElementById("myAudio").play();},1000);
},false);
// 设置滚动
function setmarquee() {
	if ($('.scrollinfor>.scroll_list_2>li').length>0) {
		setTimeout(function(){
			if( $('.scrollinfor')[0].scrollHeight-$('.scrollinfor').scrollTop()<=$('.scrollinfor').height()){
				$('.scrollinfor').scrollTop($('.scrollinfor>.scroll_list_2').height());
			} else {
				$('.scrollinfor').scrollTop($('.scrollinfor').scrollTop() + 1);
			}
			setmarquee();
		},15);
	}
}

// 设置倒计时
function timer(intDiff){
	if (intDiff<=0) {
		$('.runtime>h2').text('团购结束');
	};
	var timec= setInterval(function(){
		if (intDiff<=0) {
			$('.runtime>h2').text('团购结束');
			clearInterval(timec);
		}else{
			var days=0, hour=0,minute=0,second=0;//时间默认值
			if(intDiff > 0){
				days=Math.floor(intDiff/(3600*24));
				hour = Math.floor((intDiff-(days*86400))/3600);
				minute=Math.floor((intDiff-(days*86400)-(hour*3600))/60);
				second=intDiff-(days*86400)-(hour*3600)-(minute*60);
			}
			if (hour <= 9) hour = '0' + hour;
			if (minute <= 9) minute = '0' + minute;
			if (second <= 9) second = '0' + second;
			$('.d').text(days);
			$('.h').text(hour);
			$('.m').text(minute);
			$('.s').text(second);
			intDiff--;
		}
	}, 1000);
}

// 确认参加生成订单
function sendcreateorder(){
	var bname = $('.buyname').val();
	var bphone = $('.buyphone').val();
	if ( $.trim(bname) == '') {
		tips('notice','填写姓名',1500,'weui_icon_notice');
		return false;
	}
	if ( $.trim(bphone) == '') {
		tips('notice','填写电话',1500,'weui_icon_notice');
		return false;
	}
	var senddata={
		'gid':gid,
		'bname':bname,
		'bphone':bphone,
	};
	tips('waiting','保存订单中···');
	$.ajax({
		url:createorder_url,
		type:"post",
		data:senddata,
		dataType:"json",
		success:function(msg){
			if (msg.status=='true') {
				// console.log(msg);
				wxpaydata= msg.info;
				callpay();
			} else{
				// tips('notice', '保存失败!', 1500, 'weui_icon_notice');
			}
		},
		complete: function (e) {
			hidetips('waiting');
		},
		error: function (data,status,e){

		}
	})
}
function sharesuccess(){
	var senddata={
		'gid':gid,
	};
	$.ajax({
		url:sharesuccess_url,
		type:"post",
		data:senddata,
		dataType:"json",
		success:function(msg){
			if (msg.status == 'true') {
				var shareinfo = msg.info;
				if (shareinfo['sharenums'] != false) {
					if (shareinfo['sharenums'] >=4) {
						tips('notice','已经点亮全部图标,可到店领取礼品!',1500,'weui_icon_toast');
					} else {
						tips('notice','已经点亮'+shareinfo['sharenums']+'图标!',1500,'weui_icon_toast');
					}
					$('.red_info>.redinfo').each(function(index,item){
						if (index+1 <= shareinfo['sharenums']) {
							$(item).find('img').attr('src',imgroot_url+'img/hbinfo_1.png');
						} else {
							$(item).find('img').attr('src',imgroot_url+'img/hbinfo_2.png');
						}
					})
				}
				if (shareinfo['hasred'] !='false') {
					var rprice = shareinfo['hasred'];
					hbshow(rprice);
				}
			}
		},
		complete: function (e) {

		},
		error: function (data,status,e){

		}
	})
}

// 发表留言
function sendinputremak() {
	var remarkinfo = $('.input_remark').val();
	if ($.trim(remarkinfo) != '') {
		var senddata={
			'gid':gid,
			'remark':remarkinfo,
		};
		tips('waiting','发表中···');
		$.ajax({
			url:saveremark_url,
			type:"post",
			data:senddata,
			dataType:"json",
			success:function(msg){
				if (msg.status =='true') {
					tips('notice','留言成功',1500,'weui_icon_toast');
					setTimeout(function(){location.reload();},1500);
				} else {
					tips('notice','留言失败',1500,'weui_icon_notice');
				}
			},
			complete: function (e) {
				hidetips('waiting');
			},
			error: function (data,status,e){

			}
		})
	} else {
		tips('notice','填写留言信息',1500,'weui_icon_notice');
	}
}





function jsApiCall(){
	WeixinJSBridge.invoke('getBrandWCPayRequest',wxpaydata,
	function (res)
	{
		if(res.err_msg == "get_brand_wcpay_request:cancel")
		{
			payLock=false;
			alert("您取消了支付");
		}
		else if(res.err_msg == "get_brand_wcpay_request:fail")
		{
			payLock=false;
			alert("支付失败,错误信息："+res.err_desc);
		}
		else if(res.err_msg == "get_brand_wcpay_request:ok")
		{
			alert("支付成功");
			setTimeout(function() { window.location.reload();}, 1000);
		}
		else
		{
			payLock=false;
			alert("支付遇到未知错误。");
		}
	}
);
}

function callpay()
{
	if (payLock) {
		return;
	}
	payLock=true;
	if (typeof WeixinJSBridge == "undefined")
	{
		if (document.addEventListener)
		{
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}
		else if (document.attachEvent)
		{
			document.attachEvent('WeixinJSBridgeReady', jsApiCall);
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}
	else
	{
		jsApiCall();
	}
}


function hbshow(rprice){
			var windh,lookh,unlookh,bgh;
			$('.redprice').text( parseFloat(rprice).toFixed(1));
			$(".hbcontent").fadeIn(0,function(){
				windh=$(window).height();
				lookh=$(".look").outerHeight();
				unlookh=$(".unlook").outerHeight();
				bgh=$(".hb_bg").outerHeight();
				//星光背景
				$(".hb_bg").animate({
				'top':0
				},800);
				//未拆红包
				$(".unlook").animate({
				'top':(windh - unlookh)/2
				},1000);
				$(".unlook").css('animation',"doudong 1.5s ease");
			});
			$(".unlook").on("click",function(){
				$(".unlook").css('animation',"dou 1.5s ease infinite");
				setTimeout(function(){
					$(".unlook").css('display','none');
					//已经拆红包
					$(".look").animate({
						'top':(windh - lookh)/2,
					},300);
				},1500)


			})
		}
