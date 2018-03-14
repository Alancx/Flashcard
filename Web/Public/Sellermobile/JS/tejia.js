$(document).ready(function(){

	$('.img').css('height',$('.img').width() / 8 * 3 + 'px');
	$('.tejia').css('display','none');
	$('.huodong').css('display','none');

	var itheight=$('.shang').height();
	$('.xia').css('height','calc(100% - 80px - '+itheight+')');

	// 保存显示名称内容
	$('.savebtn').click(function(){
		var nametype = $(this).attr('data-type');
		if (nametype == '0') {
			var nametext= $('.showtsname').val();
		} else {
			var nametext= $('.showtjname').val();
		}
		if ($.trim(nametext)!='') {

			var senddata = {
				type:nametype,
				name:nametext,
			}
			tips('waiting','正在保存中···');
			$.ajax({
				url:namesave_url,
				type:"post",
				data:senddata,
				dataType:"json",
				complete: function(e){
					hidetips('waiting');
				},
				success:function(msg){
					if (msg.status == 'true') {
						tips('notice', '保存成功!', 1500, 'weui_icon_toast');
					} else {
						tips('notice', '保存失败!', 1500, 'weui_icon_notice');
					}
				}
			})
		} else {
			tips('notice', '填写显示名称!', 1500, 'weui_icon_notice');
		}

	})
})
$('.tab_info').click(function(){
	if(!$(this).hasClass('tabavtive')){
		$('.tabavtive').removeClass('tabavtive');
		$(this).addClass('tabavtive');
		$('.theme').css('display','none');
		var classname = $(this).attr('data-info');
		$('.'+classname).css('display','block');
	}
})
$('.blank').click(function(){
	$('.choice').css('display','none');
})
