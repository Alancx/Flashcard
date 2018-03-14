$(function(){
	///tab2
	$('.tab2 span').on('tap',function(){
		var spanLeft=$(this).position().left;
		var spanRight=$(this).position().right;
		var navLeft=$('.tab2nav').position().left;
		var i=$(this).index();
//		console.log(i);
		if(spanLeft>0&&i==1&&navLeft==0){
			$('.tab2nav').animate({'width':'100%'},200,function(){
				$('.tab2nav').animate({'left':spanLeft,'width':'50%'},200);
				$('.tab2 span').css('color','#dd0000');
				$('.tab2 span').eq(i).css('color','#fff');
			});
		}else if(spanLeft==0&&i==0&&navLeft!=0){
			$('.tab2nav').animate({'right':spanRight});
			$('.tab2nav').animate({'left':spanLeft,'width':'100%'},200,function(){
				$('.tab2nav').animate({'left':spanLeft,'width':'50%'},200);
				$('.tab2 span').css('color','#dd0000');
				$('.tab2 span').eq(i).css('color','#fff');
				});
		}else{
				$('.tab2 span').eq(i).css('color','#fff');
		}
		
		$('[data-oo="mytuan"]').css('display','none').eq(i).css('display','block');
	})
	
	
	
	mui.ready(function(){
		var objs=$('.probar');
		$.each(objs,function(index,item){
			mui(item).progressbar({progress:$(item).attr('data-probar')}).show();
		})
		//进度条
	})
	
	
//倒计时-----------------------
var intDiff = parseInt(60*60*72);//倒计时总秒数量
function timer(intDiff){
    window.setInterval(function(){
    var hour=0,
        minute=0,
        second=0;//时间默认值        
    if(intDiff > 0){
        hour = Math.floor(intDiff / (60 * 60)) ;
        minute = Math.floor(intDiff / 60) - (hour * 60);
        second = Math.floor(intDiff) - (hour * 60 * 60) - (minute * 60);
    }
    if (hour <= 9) hour = '0' + hour;
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    $('.hour_show').html(hour);
    $('.minute_show').html(minute);
    $('.second_show').html(second);
    intDiff--;
    }, 1000);
} 
$(function(){
    timer(intDiff);
}); 	
	

//页面跳转
//团购详情
$(".listdiv.statesuccess .ulview").on('tap',function(){
	var _gid=$(this).attr('data-id');
	mui.openWindow({
    	url:"GroupInfo.html?GroupId="+_gid,
    	id:"InGroup",
   		 waiting:{
     		 autoShow:false,//自动显示等待框，默认为true
      				}
		})
	
})
//转到首页
$(".mui-icon-home").on('tap',function(){
//	mui.openWindow({
//  	url:"MenuBar.html",
//  	id:"MenuBar",
// 		 waiting:{
//   		 autoShow:false,//自动显示等待框，默认为true
//    				}
//		})
       plus.webview.show('MenuBar');
	
})
	
	
	
	
	
	
////////////////////////	
})
