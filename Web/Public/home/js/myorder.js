mui.ready(function(){});
mui.plusReady(function(){});


//切换选项
$(".ordernav li").on("tap",function(){
	$(this).addClass("check").siblings().removeClass("check");
	var i=$(this).index();
	$("[data-oo='item']").fadeOut(0).eq(i).fadeIn(0);
})


//页面跳转
$(".contdiv").on("tap",function(){
	mui.openWindow({
		url:"detail.html",
		id:"detail.html"
	})
	
	
	
})


