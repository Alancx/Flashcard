mui.plusReady(function(){
	
})

//倒计时-----------------------
var intDiff = parseInt(60*60*4);//倒计时总秒数量
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
    $('#hour_show').html(hour);
    $('#minute_show').html(minute);
    $('#second_show').html(second);
    intDiff--;
    }, 1000);
} 
$(function(){
    timer(intDiff);
}); 


//支付方式
$(".zhifufangshi li").on("tap",function(){
	$(".zhifufangshi li .ggg").removeClass("checked_icon");
	$(this).children(".ggg").addClass("checked_icon");
	var itcolor=$(this).children(".fangshi").css("color");
	var ittext=$(this).children("span").text();
	$(this).children(".checked_icon").css("color",itcolor);
	$(".truezhifu").css("background",itcolor);
	$(".aa").text(ittext);
})


//支付状态
$(".truezhifu").on("tap",function(){
	var i=$(".checked_icon").hasClass("checked_icon");
	if(i!=true){
			mui.toast('请选择支付方式',{ duration:'long', type:'div' }) 
		}
	
})













