$(function(){

	window.onload=function(){
		$('.shiyongguize').hide();

	}



	///tab1
	$('.tab1 span').click(function(){
		var spanLeft=$(this).position().left;
		var i=$(this).index();
		$('.tab1 span').css('color','#e87a77');
		$('.tab1nav').animate({
			'left':spanLeft,
		},300,function(){
		$('.tab1 span').eq(i).css('color','#fff');
		});

		$('[data="tab"]').css('display','none').eq(i).css('display','block');



	})






	//-------------------滑动导航条
	$("#me").click(function(){
		var chu=$('.mine li:first-child span').position().left,
		    wid=$('.mine li:first-child span').outerWidth(),
		    widsh=$('.sh li:first-child span').outerWidth();
		$('.tabnav').css({"left":chu});
		$('.mine li:first-child span').css('color','#ff3e00');
		$('.tabnav').css({'width':wid});
	})
//----------------------------mine
$('.mine ul li').click(function(){
		event.stopPropagation();
		var pl=$(this).children('span').position().left;
		var wid=$(this).children('span').outerWidth();
		$('.tabnav').stop(true,true).animate({
			"left":pl,
			"width":wid
		},"normal");
		$(this).children('span').css('color','#ff3e00');
		$(this).siblings().children('span').css('color','');

		var i=$(this).index();
		$('[data="tabtab"]').css('display','none').eq(i).css('display','block');



})

$('.btn').click(function(){
  var cpid=$(this).attr('data-cpid');
  if ($(this).hasClass('get')) {
    tips('waiting','领取中···');
    $.ajax({
      url:getcoupon_url,
      type:"post",
      data:"cpid="+cpid,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice','领取成功',2000);
          setTimeout(function(){
            window.location.reload();
          },1000);

        }else{
					console.log(msg.info);
            tips('notice',"领取失败",2000,'weui_icon_notice');
        }
      }
    })
  } else if ($(this).hasClass('use')) {
		// console.log('s');
  	window.location.href=classpage_url;
  }
})
//使用规则
// $(".guize").click(function(){
//
// //	alert('a')
// 	$(".shiyongguize").show(200);
//
// })

// $(".off").click(function(){
//
// //	alert('a')
// 	$(".shiyongguize").hide(200);
//
// })




})
