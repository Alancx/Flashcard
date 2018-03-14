mui.ready(function() {
	//页头背景
	var itimg = $(".shoplogo").attr("src");
	$(".bgimg").attr("src", itimg);
	//
	getusercart();	
})
mui.plusReady(function() {
	//页头背景
	var itimg = $(".shoplogo").attr("src");
	$(".bgimg").attr("src", itimg);
	//
//	$("[data-oo='tabi']").height($(window).height() - 100);
//	$(".contdiv").height($(window).height() - 100);
})

//点菜-评论 -商家
mui('#tab').on('tap', 'li', function() {
	$(this).addClass("current").siblings().removeClass("current");
	var i = $(this).index();
	$("[data-oo='tabi']").fadeOut(0).eq(i).fadeIn(0);
});

//已点菜单



//滚动到154px位置，点菜评价商家浮动
$(window).on("scroll",function() {
	if($(window).scrollTop() >= 154) {
		$('#center').css('position', 'fixed');
		$('.ldiv').css('position','fixed');
		$('.rdiv').css('margin-left',$('.left').width());
		
	} else {
		$('#center').css('position', '');
		$('.ldiv').css('position','');
		$('.rdiv').css('margin-left','');
	};
	
	
	//滚动到标杆位置,左侧导航加actives
	$('.rdiv ul li').each(function(){
		var target = parseInt($(this).offset().top-$(window).scrollTop()-140);
		var i = $(this).index();
		if (target<=0) {
			$('.ldiv ul li').removeClass('actives');
			$('.ldiv ul li').eq(i).addClass('actives');
		}
	});
})


$(document).on('click','.ldiv ul li',function(){
	var i = $(this).index('.ldiv ul li');
	$('body, html').animate({scrollTop:$('.rdiv ul li').eq(i).offset().top-140},500);
})



//关注
$(".icons span:last-child").on("tap",function(){
	$(this).find(".ggg").toggleClass("xin_fillicon");
	$(this).find("span").toggleClass("ygz");
	var istrue=$(this).find("span").hasClass("ygz");
	if(istrue==true){
		$(this).find("span").text("取关");
	}else{
		$(this).find("span").text("关注");
	}
})






//-------------页面跳转--------------

//点击红包
$(".hb:not(.lingwan)").on("tap",function(){
	mui.openWindow({
		url:"gethongbao.html",
		id:"gethongbao.html"
	})
})

//商品详情
$(".spitem img").on("tap",function(e){
	location.href=$(this).attr('data-url');
})


//点击地址   触发点击商家
$(".text").on("tap",function(){
	var ittxet=$(this).text();
	var itphone=$(".shopphone").text();
	mui.alert(ittxet+"电话："+itphone,"商家地址","知道了")
})
//去结算
$(".gobuy").on("tap",function(){
	mui.openWindow({
		url:"SubmitOrder.html",
		id:"SubmitOrder.html",
	})
})

function getusercart(){	
	$.ajax({
		url:getcarturl,
		type:"post",
		data:"1=1",
		dataType:"json",
		success:function(msg){
			if (msg.status=='success') {                               
				var data=msg.data;
				$.each(data,function(index,item){
					$(".mui-numbox[data-pid="+index+"]>.mui-input-numbox").val(item.Num);
					$(".mui-numbox[data-pid="+index+"]>.mui-input-numbox").css('display','block');//显示input框
					$(".mui-numbox[data-pid="+index+"]>.mui-btn-numbox-minus").css('display','block');//显示减号
					$(".mui-numbox[data-pid="+index+"]>.mui-btn-numbox-minus").attr('disabled',false);
				})
			}
		}	
	})
}

// function getproalltotalprice(){
// 	var objarray = $('.list_box>li');
// }