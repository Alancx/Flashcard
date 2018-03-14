//选择地址
var addbtn = document.getElementById("Add");
var citypicker = new mui.PopPicker({
	layer: 3
});
citypicker.setData(cityData3);
addbtn.addEventListener('tap', function() {

	citypicker.show(function(items) {
		addbtn.innerText = (items[0] || {}).text + ' ' + (items[1] || {}).text + ' ' + (items[2] || {}).text;

		$('#Add').attr('data-p',(items[0] || {}).text);
		$('#Add').attr('data-c',(items[1] || {}).text);
		$('#Add').attr('data-a',(items[2] || {}).text);
		//返回 false 可以阻止选择框的关闭
		//return false;
	})
})

$(document).ready(function(){
	// if (subscribe !='1') {
	// 		$('.subscribemark').css('display','block');
	// 		$('.showsubscribecode').css('display','block');
	// }
	// 选择上传的身份证照片信息
	oFReader = new FileReader(),
	rFilter = /^(?:image\/jpeg|image\/png)$/i;

	oFReader.onload = function (oFREvent) {
		$('.photo>a').css('display','none');
		$("#usphoto").attr("src",oFREvent.target.result);
		$('#usphoto').css('display','block');
		qdidcard();
	};
	// 打开地图选点说明
	$('#getmap').on('tap',function(){
		$('.map').css('display','block');
		setTimeout(function(){
			wxmap.panTo(new qq.maps.LatLng(gelat, gelng));
		},100);
	})
	//关闭地图选点
	$('.closemapsel').on('tap',function(){
		$('.map').css('display','none');
	})
	//确定地图选点
	$('.suremapsel').on('tap',function(){
		$('.map').css('display','none');
		$("#pointsX").val(gelng);
		$("#pointsY").val(gelat);
	});
	// 立即申请
	$('#RegBtn').on('tap',function(){
		sendshopinfo();
	})
})

//////选择上传图片/////
function seltxtp(ifile){
	if (ifile.files.length === 0){
		return;
	}
	var oFile = ifile.files[0];
	if (!rFilter.test(oFile.type)) {
		mui.toast('选择正确的图片格式');
		return;
	}else{
		oFReader.readAsDataURL(oFile);
	}
}

//////确定选择idcard///////////////
function qdidcard(){
	showwaiting('正在处理...');
	$.ajaxFileUpload({
		url: useridcard, //用于文件上传的服务器端请求地址
		secureuri: false, //是否需要安全协议，一般设置为false
		fileElementId: 'iptxtp', //文件上传域的ID
		dataType: 'json', //返回值类型 一般设置为json
		success: function (data, status)  //服务器成功响应处理函数
		{
			if (data.status=='true'){
				$("#idcard").val(data.datainfo);
				mui.toast('上传成功');
			}else{
				$("#idcard").val('');
				mui.toast('上传失败');
			}
		},
		complete: function (e) {
			closeWaiting();
		},
		error: function (data,status,e)//服务器响应失败处理函数
		{
			$("#idcard").val('');
			mui.toast('上传失败');
		}
	})
}
// 开店申请提交
function sendshopinfo(){
	if ($('#truename').val()=='') {
		mui.toast('填写姓名');
		return false;
	} else if ($('#shopname').val()=='') {
		mui.toast('填写店铺名称');
		return false;
	}else if ($('#Add').attr('data-p')=='' || $('#Add').attr('data-c')=='' || $('#Add').attr('data-a')=='') {
		mui.toast('选择完整所在地');
		return false;
	}else if ($('#addrxx').val()=='') {
		mui.toast('填写详细地址');
		return false;
	} else if ($('#pointsX').val()=='' || $('#pointsY').val()=='') {
		mui.toast('选择店铺所在地图位置');
		return false;
	} else if ($('#sphone').val()=='') {
		mui.toast('填写手机号码');
		return false;
	}

	var senddata={
		'truename':$('#truename').val(),
		'idcardno':$('#idcardno').val(),
		'shopname':$('#shopname').val(),
		'province':$('#Add').attr('data-p'),
		'city':$('#Add').attr('data-c'),
		'area':$('#Add').attr('data-a'),
		'addr':$('#addrxx').val(),
		'tel':$('#sphone').val(),
		'lang':$('#pointsY').val(),
		'lat':$('#pointsX').val(),
		'IdInfo':$('#idcard').val(),
		'Invcode':$('#sinvcode').val(),
		'openid':useropenid,
		'nickname':usernickname,
	}
	$('#RegBtn').text('正在提交...');
	$('#RegBtn').addClass('hassendactive');
	$.ajax({
		type: "post",
		url: saveshopinfo_url,
		data: senddata,
		dataType: "json",
		complete: function (e) {

		},
		success: function (msg) {
			if (msg.status == 'true') {
				mui.toast('申请成功');
				setTimeout(function(){
					window.location.href=shoplogn_url;
				},1500);
			} else {
				$('#RegBtn').text('立即申请');
				$('#RegBtn').removeClass('hassendactive');
				if (msg.datainfo=='phoneError') {
					mui.toast('此手机号已注册');
				} else {
					mui.toast('申请失败');
				}
			}
		}
	})

}
