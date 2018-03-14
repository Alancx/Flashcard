mui.ready(function() {
	//页头背景
	var itimg = $(".shoplogo").attr("src");
	$(".bgimg").attr("src", itimg);
	//
	
	
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
$("#yidian").on("tap",function(){
	$(".qw").slideToggle(0);
	$("body").toggleClass("tog");
})


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


 $('.ldiv ul li').click(function(){
      var i = $(this).index('.ldiv ul li');
      $('body, html').animate({scrollTop:$('.rdiv ul li').eq(i).offset().top-140},500);
    });


//加
$(".mui-btn-numbox-plus").on("tap", function() {
	//	console.log("0")
	var itnum = $(this).parent().children(".mui-input-numbox").val();
	if(itnum > -1) {
		$(this).parent().children(".mui-input-numbox,.mui-btn-numbox-minus").css("display", "block");
	}
})
//减
$(".mui-btn-numbox-minus").on("tap", function() {
	//	console.log("0")
	var itnum = $(this).parent().children(".mui-input-numbox").val();
	if(itnum <= 1) {
		$(this).parent().children(".mui-input-numbox,.mui-btn-numbox-minus").css("display", "");
	}
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
//点击特价、特色
$(".tdiv div").on("tap",function(){
	mui.openWindow({
		url:"zhekou.html",
		id:"zhekou.html",
	})
})
//商品详情
$(".spitem img").on("tap",function(e){
	mui.openWindow({
		url:"goods.html",
		id:"goods.html",
	})
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
