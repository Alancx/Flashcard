$(document).ready(function () {

  ///单击删除图片//////////////
  $('#esc').click(function(){
    hidetips('confirm');
  })
  $('#enter').click(function(){
    var simgsrc=$(this).attr('data-simg');
    $.each($('.imglist'),function(i,k){
      if(simgsrc==$(k).attr('src')){
        $(k).remove();
      }
    })
    hidetips('confirm');
  })
  // 保存按钮
  $('.savebtn').click(function(){
    saveshopimgs();
  })

  $('.imglist').on('touchstart',function(event){
    event.preventDefault();//阻止其他事件
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      $('#enter').attr('data-simg',$(this).attr('src'));
      tips('confirm', '确定要删除图片?', 10000);
    }
  })
})

osFReader = new FileReader(),
rsFilter = /^(?:image\/jpeg|image\/png)$/i;
osFReader.onload = function (oFREvent) {
  qdsimage();
};
function selsimage(ifile){
	if (ifile.files.length === 0){
		return;
	}
	var oFile = ifile.files[0];
	if (!rsFilter.test(oFile.type)) {
		tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
		return;
	}else{
		osFReader.readAsDataURL(oFile);
	}
}

function qdsimage(){
	var htmls="";
	$.ajaxFileUpload({
		url: shopimagesave, //用于文件上传的服务器端请求地址
		secureuri: false, //是否需要安全协议，一般设置为false
		fileElementId: 'selswimg', //文件上传域的ID
		dataType: 'json', //返回值类型 一般设置为json
		success: function (data, status)  //服务器成功响应处理函数
		{
			if (data.status=='true'){
				htmls='<img src="'+data.datainfo+'" class="imglist">';
				$(".addbtn").before(htmls);
				$('.imglist').on('touchstart',function(event){
					event.preventDefault();//阻止其他事件
					if (event.originalEvent.targetTouches.length == 1) {
						var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
						$('#enter').attr('data-simg',$(this).attr('src'));
						tips('confirm', '确定要删除图片?', 10000);
					}
				})
			}else{
				tips('notice', '上传失败!', 1500, 'weui_icon_notice');
			}
		},
		complete: function (e) {

		},
		error: function (data,status,e)//服务器响应失败处理函数
		{
			tips('notice', '上传失败!', 1500, 'weui_icon_notice');
		}
	})
}
function saveshopimgs(){
  if ($('.imglist').length>0) {
    var jsonShowimg={};
    $.each($('.imglist'),function(index,item){
      jsonShowimg[index]=$(item).attr('src');
    });
    tips('waiting','保存数据中···');
    $.ajax({
      url:shopimgurl,
      type:"post",
      data:{shopimgs:JSON.stringify(jsonShowimg)},
      dataType:"json",
      success:function(msg){
        if (msg.status=='true') {
          tips('notice', '保存成功!', 1500, 'weui_icon_toast');
        } else{
          tips('notice', '保存失败!', 1500, 'weui_icon_notice');
        }
      },
      complete: function (e) {
        hidetips('waiting');
      },
      error: function (data,status,e)//服务器响应失败处理函数
      {
        //////
      }
    })
  } else {
    tips('notice', '添加展示图!', 1500, 'weui_icon_notice');
  }
}
