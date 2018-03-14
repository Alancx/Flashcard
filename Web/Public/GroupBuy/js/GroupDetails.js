//初始化页面
mui.plusReady(function(){});

$(function(){
	//---------页面加载完成时tab的样式	
	$(document).ready(function(){
		var chu=$('.ultab li:first-child span').position().left;
		$('.tabnav').css({"left":chu});
		$('.ultab li:first-child span').css('color','#dd0000');
	})
	
//---------------------滑动导航条
	$('.ultab li').on('tap',function(){
		event.stopPropagation();
		var pl=$(this).children('span').position().left;
		var wi=$(this).children('span').outerWidth();
		$('.tabnav').stop(true,true).animate({
			"left":pl,
			"width":wi
		},"normal");
		
//------------------tab点击事件		
		$(this).children('span').css('color','#dd0000');
		$(this).siblings().children('span').css('color','');
		var i=$(this).index();
		$('[data-oo=tab]').css('display','none').eq(i).css('display','block');
		mui('.mui-scroll-wrapper').scroll().scrollTo(0,0,100);//100毫秒滚动到顶
		
	})
	

//倒计时-----------------------
function timer(intDiff){
	if (intDiff<=0) {
		$('#KaiTuan').text('团购结束').attr('id','overBtn').unbind('tap');
		$('#second_show').html('00');
	};
	var timec=window.setInterval(function(){
		if (intDiff<=0) {
			$('#KaiTuan').text('团购结束').attr('id','overBtn').unbind('tap');
			$('#second_show').html('00');
			clearInterval(timec);
		}else{
			var hour=0,
			minute=0,
	        second=0;//时间默认值        
	        if(intDiff > 0){
	        	days=Math.floor(intDiff/(3600*24));
	        	hour = Math.floor((intDiff-(days*86400))/3600);
	        	minute=Math.floor((intDiff-(days*86400)-(hour*3600))/60);
	        	second=intDiff-(days*86400)-(hour*3600)-(minute*60);
	        }
	        if (hour <= 9) hour = '0' + hour;
	        if (minute <= 9) minute = '0' + minute;
	        if (second <= 9) second = '0' + second;
	        $('#hour_show').html(hour);
	        $('#minute_show').html(minute);
	        $('#second_show').html(second);
	        $('#days_show').html(days);
	        intDiff--;
	    }
	}, 1000);
} 
$(function(){
    timer(intDiff);
}); 
//收藏
$(".sc span").on('tap',function(){
	$(this).toggleClass('mui-icon-extra-heart-filled');
})

//开团
$("#KaiTuan,#DanMai").on('tap',function(){
//	alert('a')
	$(".Spshuxing").addClass('tran');
	$("#mask").css("display",'block');
})
$('#gofororder').on('tap',function(){	
	var count=$('#chosenumber').val();
	var glid=$('.ed').attr('data-rid');
	if (count && glid) {
		window.location.href=gofororder+'?count='+count+'&glid='+glid+'&type=open';
	}else{
		mui.toast('请选择团购规则');
	}
})
//关闭
$("#close").on('tap',function(){
//	alert('a')
	$(".Spshuxing").removeClass('tran');
	$("#mask").css("display",'none');
	
})
//属性选择
$(".threesix small,.kouwei small,.baozhuang small").on('tap',function(){
//	alert('a');
	$(this).parent().children('small').removeClass('ed').addClass('un');
	$(this).removeClass('un').addClass('ed')
	var max=$(this).attr('data-max');
	mui('.mui-numbox').numbox().setOption('max',max);
	mui('.mui-numbox').numbox().setValue(1);
	// if (max) {
	// 	$('#chosenumber').attr('max',max).val('1');
	// };
})
// $('.mui-numbox-btn-minus').unbind('tap');
// $('.mui-numbox-btn-minus').on('tap',function(){
// 	var tmpnum=parseInt($('#chosenumber').val())-1;
// 	console.log(tmpnum);
// 	if (tmpnum<=0) {
// 		$('#chosenumber').val('1');
// 	};
// 	return false;
// })
// $('.mui-numbox-btn-plus').on('tap',function(){
// 	var tmpnum=parseInt($('#chosenumber').val());
// 	var max=parseInt($("#chosenumber").attr('max'));
// 	if (tmpnum>max) {
// 		$('#chosenumber').val(max);
// 		console.log(tmpnum);
// 		console.log(max);
// 	};
// })
//事件触发
//产品参数
$("#cpcs").on('tap',function(){
	var btn=document.getElementById("tab2");;
	mui.trigger(btn,'tap');
	mui('.mui-scroll-wrapper').scroll().scrollTo(0,0,100);//100毫秒滚动到顶
})
//选择规格
$("#xzgg").on('tap',function(){
	var btn=document.getElementById("KaiTuan");;
	mui.trigger(btn,'tap');
})



//全部评价
$("#PJ").on('tap',function(){
	var btn=document.getElementById("tab3");;
	mui.trigger(btn,'tap');
	mui('.mui-scroll-wrapper').scroll().scrollTo(0,0,100);//100毫秒滚动到顶
})

//团购列表-查看更多

//去参团
$("#tuanlist li").on('tap',function(){
	mui.openWindow({
    url:"InGroup.html",
    id:"InGroup",
    waiting:{
      autoShow:false,//自动显示等待框，默认为true
      }
})
})

$(document).on('tap','.gengduo',function(){
	window.location.href=grouplist+"?gid="+gid;
})

/////////////////////
})

