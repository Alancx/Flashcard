var backrefArray =[
	('Home/Index/Index').toLowerCase(),
	('Home/Index/activity').toLowerCase(),
	('Home/Goods/goods').toLowerCase(),
];
// var backrefArray =[
// 	window.location.protocol + '//' + window.location.host+'/',
// 	window.location.protocol + '//' + window.location.host+'/Home/Index/Index',
// 	window.location.protocol + '//' + window.location.host+'/Home/Index/activity',
// 	window.location.protocol + '//' + window.location.host+'/Home/Goods/goods',
// ];
//等待框
function showwaiting(content){
	var showtext = content || '加载中';
	$("#showwaiting").css('display','block');
	$("#showwaiting>.showwaitinfo>.waitingtext").text(showtext);
	setTimeout(function(){closeWaiting()},10000);
}
//关闭等待框
function closeWaiting(){
	$("#showwaiting").css('display','none');
	$("#showwaiting>.showwaitinfo>.waitingtext").text('');
}

/////////////////////////////////////////////////////////////////////

//页面加载方法
$(function(){
	if (window.history && window.history.pushState) {
		$(window).on('popstate',function() {
			if (nowmca_url.toLowerCase()=='home/index/index') {
				WeixinJSBridge.call('closeWindow');
			} else {
				var referrerpage = document.referrer;
				referrerpage = referrerpage.toLowerCase();
				var needback = false;
				$.each(backrefArray,function(index,item){
					if (referrerpage.indexOf(item)!=-1) {
						needback = true;
						return false;
					}
				});
				if (needback === true) {
					location.replace(document.referrer);
				} else {
					history.go(-2);
				}
				// }
			}
		});
		window.history.pushState('forward', null, '#');
		window.history.forward(1);
	}
	//绑定跳转事件
	$("body [RPath]").on('tap',function(){
		$(this).css('cursor','pointer');
		window.location.href=$(this).attr("RPath");
	})
});
