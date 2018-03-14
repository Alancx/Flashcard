$(function(){
	
	
	
		
//---------页面加载完成时tab的样式	
	$(document).ready(function(){
		var chu=$('.jstj-tab li:first-child span').position().left;
		$('.tabnav').css({"left":chu});
		$('.jstj-tab li:first-child span').css('color','#c7b299');
	})
	
//---------------------滑动导航条
	$('.jstj-tab li').click(function(){
		event.stopPropagation();
		var pl=$(this).children('span').position().left;
		$('.tabnav').stop(true,true).animate({
			"left":pl
		},"normal");
	
	$(this).children('span').css('color','#c7b299');
		$(this).siblings().children('span').css('color','');
		var i=$(this).index();
		$('[data-oo=tab]').css('display','none').eq(i).css('display','block');
	})
//状态
$('.zhuangtai').click(function(){
	$('.boo').toggleClass('toggle');
	$('.zhuangtaiul').slideToggle();
})
$('.zhuangtaiul li').click(function(){
	var ittext=$(this).text();
	$('.zhuangtaiul').css('display','none');
	$('.zhuangtai span').text(ittext);
	$('.boo').toggleClass('toggle');
})

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})