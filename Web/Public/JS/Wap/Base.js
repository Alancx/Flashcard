// JavaScript Document


//各类提示框
//////////////////////////////////////////////////////////////////////

//提示框
function tips(content)
{
	$(".tips").show();
	$(".tips").html(content);
	setTimeout(function(){$(".tips").hide();},3000);
}

//等待框
function waiting(content)
{
	$(".waiting").show();
	$(".waiting").html(content);
}
//关闭等待框
function closeWaiting()
{
	$(".waiting").hide();
	$(".waiting").html('');
}
/////////////////////////////////////////////////////////////////////

function backupPage()
{
	window.history.back(-1);
}

//页面加载方法
$(function(){
	//绑定跳转事件
	$("body [RPath]").click(function(){
		$(this).css('cursor','pointer');
		window.location.href=$(this).attr("RPath");
	});	
	
	//click事件元素加上pointer iphone浏览器有时不认
	$("body [onClick]").click(function(){
		$(this).css('cursor','pointer');
	});	   
});