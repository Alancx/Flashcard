var public_url="a.php?shop/";
$(function () {
	setInterval(function showimg(){$("img[src='']:eq(0)").attr("src",$("img[src='']:eq(0)").attr("u"));},500);
	$(".car_bottom_left").click(function () {
		$(this).toggleClass("bg_car_bgclick");
		$(".car1_content_left").toggleClass("bg_car_bgclick");
		sum();
	})
	$(".car1_content_left").click(function () {
		$(this).toggleClass("bg_car_bgclick");
		sum();
	})
	$(".delete").click(function () {
		var url=$(this).attr("u").split(",");
		$.get(public_url+url[0]+"/del/"+url[1]+"/");
		$(this).parent().parent().remove();
		sum();
	})
	$(".default_left").click(function () {
		$(".default_left").removeClass("bg_car_bgclick");
		$(this).addClass("bg_car_bgclick");
		if($(this).attr("class").indexOf("addr")>-1){
			$.get(public_url+"agent/addr/"+$(this).attr("u")+"/",function(data,status){
				window.location.href=public_url+"order/";
			});
		}
	})
	$(".car_bottom_right").click(function () {
		var checked=$(".bg_car_bgclick");
		var url="";
		for(var i=0;i<checked.length;i++){
			if(checked.eq(i).parent().find(".delete").attr("u")){
				url+=checked.eq(i).parent().find(".delete").attr("u").split(",")[1]+",";
			}
		}
	})
});
function sum(type){
	var checked=$(".car1_content_left");
	var allprice=0;
	for(var i=0;i<checked.length;i++){
		if(checked.eq(i).attr("class").indexOf("bg_car_bgclick")>-1){
			allprice+=checked.eq(i).parent().find(".jine").html()*checked.eq(i).parent().find(".buy_num").html();
			$.get(public_url+"cart/balance/"+checked.eq(i).attr("u")+"/add/");
		}else{
			$.get(public_url+"cart/balance/"+checked.eq(i).attr("u")+"/less/");
		}
	}
	if(null==type){
		$('.addnum').eq(0).html(allprice.toFixed(2));
		$('.num_num').eq(0).html($(".car_content .bg_car_bgclick").length);
		if($(".car_content .bg_car_bgclick").length==$(".car_content").length && $(".car_content").length > 0){
			$(".car_bottom_left").addClass("bg_car_bgclick");
		}else{
			$(".car_bottom_left").removeClass("bg_car_bgclick").addClass("bg_car_bg");
		}
		return allprice.toFixed(2);
	}else{
		alert(allprice.toFixed(2));
	}
}