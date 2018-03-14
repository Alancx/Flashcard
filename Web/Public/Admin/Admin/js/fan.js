$(function(){
	
$('.li2,.li3').css('display','none')	;

$('.fantab li').click(function(){
	var id=this.id;
	if(id=='tab1'){
		$('.li1,.li2,.li3').css('display','none');
		$('.li1').css('display','block');
	}else if(id=='tab2'){
		$('.li1,.li2,.li3').css('display','none');
		$('.li2').css('display','block');
	}else if(id=='tab3'){
		$('.li1,.li2,.li3').css('display','none');
		$('.li3').css('display','block');
	}
})
	
	
	
	
	
	
	
	
	
//----------滑动导条	
$(document).ready(function(){
//	var $liCur = $(".nav ul li.cur"),
//	  curP = $liCur.position().left,
//	  curW = $liCur.outerWidth(true),
      $('.fantab li:first-child').css('color','dodgerblue');
	var $slider = $(".curbg");
	    $targetEle = $(".fantab li");
//	$slider.animate({
//	  "left":curP,
//	  "width":curW
//	});
	$targetEle.click(function () {
		event.stopPropagation(); 
		$targetEle.css('color','#000');
		$(this).css('color','dodgerblue');
	  var $_parent = $(this);
		_width = $_parent.outerWidth(true);
		posL = $_parent.position().left;
	  $slider.stop(true, true).animate({
		"left":posL+20,
		"width":_width-38
	  }, 300);
	});
});
     
	
//	-----------最近消费/购买次数/商品均价
$('.fanhear p span:first-child').addClass('boo');
$('.fanhear span').click(function(){
	$(this).parent().children().siblings().removeClass('boo');
	$(this).addClass('boo');
});
	
	
//--------------------筛选结果----------------	
$('.number').prepend('共');
$('.number').append('人');
	


//----------全选------------------------
$('.checkedall').change(function(){
	var all=$(this).is(':checked');
	if(all==true){
		$('.checked').prop('checked', true)
	}else{
		$('.checked').prop('checked', false)
	}
	
})

$('.checked').change(function(){
	var all=$(this).is(':checked');
	if(all==false){
		$('.checkedall').prop('checked', false)
	}
	
})












})