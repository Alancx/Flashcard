// JavaScript Document
//等待框
function waiting(content)
{
	$("#waiting").show();
	$("#waiting").html(content);
}
//关闭等待框
function closeWaiting()
{
	$("#waiting").hide();
	$("#waiting").html('');
}

function tips(type,msg,time,icon){
	var time=time||10000;
	var icon=icon||'weui_icon_toast';
	var type=type||'notice';
	//提示图标样式 toast为 √  notice为 ！
	if (type=='notice')
	{
		$(".weui_toast_content").html(msg);
		$("#"+type+" i").attr('class',icon);
	}
	else if (type=='confirm')
	{
		$(".weui_dialog_bd").html(msg);
	}
	else if (type=='alert')
	{
		$(".weui_dialog_bd").html(msg);
	}
	else if (type=='waiting')
	{
		$(".weui_toast_content").html(msg);
	}
	$("#"+type).show();

	setTimeout('hidetips("'+type+'")',time);
}
function hidetips(type){
	$('#'+type).hide();
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
