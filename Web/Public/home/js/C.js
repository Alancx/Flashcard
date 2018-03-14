var scrolltype = '1';
mui.ready(function() {
	
	//页头背景
	var itimg = $(".shoplogo").attr("src");
	$(".bgimg").attr("src", itimg);
	
})

//点菜-评论 -商家
$(document).on('tap', '#tab li', function() {
	$(this).addClass("current").siblings().removeClass("current");
	var i = $(this).index();
	$("[data-oo='tab']").fadeOut(0).eq(i).fadeIn(0);
	$(window).scrollTop(150);
});

$(document).on("tap", '.lefttab ul li', function() {
	scrolltype = '0';
	var i = $(this).index('.lefttab ul li');
	$(this).addClass("actives").siblings().removeClass("actives");
	var ittt = $('.righttab ul li').eq(i).position().top;
	var itttli = $('.righttab ul').position().top;
	$('.righttab').animate({
		scrollTop: ittt - itttli
	}, 100,function(){
		scrolltype = '1';
	});
	$(window).scrollTop(150);
});

//屏幕滚动事件
$(window).on("scroll", function() {
	if($(window).scrollTop() >= 150) {
		$('#center').css('position', 'fixed');
		$(".contdiv").addClass("aa");
	} else {
		$('#center').css('position', 'none');
		$(".contdiv").removeClass("aa");
	};
});

//左右联动事件
$(".righttab").on("scroll", function() {
	if(scrolltype != '1') {
		return false;
	}
	var _this = $(this);
	var thistop = _this.children().position().top;
	//滚动到标杆位置,左侧导航加actives
	$('.righttab ul li').each(function(index, item) {
		var target = parseInt($(this).position().top);
		var i = $(this).index();
		if(target <= 0) {
			$('.lefttab ul li').removeClass('actives').eq(i).addClass('actives');
		}
	});
})