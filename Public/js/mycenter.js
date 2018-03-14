mui.ready(function(){
	
	
	//我的关注
	$(".myeye").on("tap",function(){
		mui.openWindow({
			url:"myeye.html",
			id:"myeye.html"
		})
	})
	//我的收藏
	$(".myshoucang").on("tap",function(){
		mui.openWindow({
			url:"myshoucang.html",
			id:"myshoucang.html"
		})
	})
	//我的红包
	$(".myhb").on("tap",function(){
		mui.openWindow({
			url:"myhongbao.html",
			id:"myhongbao.html"
		})
	})
	
	
	
	//联系我们
	$(".kefuhtml").on("tap",function(){
		mui.openWindow({
			url:"kefuhtml.html",
			id:"kefuhtml.html"
		})
	})
	//问题帮助
	$(".prohtml").on("tap",function(){
		mui.openWindow({
			url:"prohtml.html",
			id:"prohtml.html"
		})
	})
	//协议说明
	
	$(".xieyihtml").on("tap",function(){
		mui.openWindow({
			url:"xieyihtml.html",
			id:"xieyihtml.html"
		})
	})
})