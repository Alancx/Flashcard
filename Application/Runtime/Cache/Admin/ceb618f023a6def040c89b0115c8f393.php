<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>我要开店</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="/Public/newhome/css/mui.min.css" />
	<link rel="stylesheet" type="text/css" href="/Public/newhome/css/mui.picker.min.css" />
	<link rel="stylesheet" type="text/css" href="/Public/newhome/css/mui.poppicker.css" />
	<link rel="stylesheet" type="text/css" href="/Public/newhome/css/SetupShop.css?v=1.0" />
	<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=NROBZ-QPFHX-DNA4U-7NIQN-RFGHS-4HFS4&libraries=convertor,geometry"></script>
	<script type="text/javascript" src="https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js"></script>
</head>
<body>
	<div class="content mui-scroll-wrapper">
		<div class="mui-scroll">
			<form class="mui-input-group">
				<div class="mui-input-row">
					<label>真实姓名：</label>
					<input id="truename" type="text" class="mui-input-clear" placeholder="输入身份证上姓名">
				</div>

				<div class="mui-input-row" style="display:none;">
					<label>身份证号：</label>
					<input id="idcardno" type="text" class="mui-input-clear" placeholder="输入身份证号">
				</div>
				<div class="mui-input-row">
					<label>商铺名称：</label>
					<input id="shopname" type="text" class="mui-input-clear" placeholder="设置商铺名称">
				</div>

				<div id="seladdr" class="mui-input-row">
					<label>所在地：</label>
					<div id="Add" class="dizhi mui-navigate-right mui-ellipsis" data-p="" data-c="" data-a="">
						<span id="addrinfo" class="right_none">请选择</span>
					</div>
				</div>
				<div class="xxdd">
					<label>详细地址：</label>
					<textarea id="addrxx" rows="" cols="" placeholder="输入店铺地址"></textarea>
					<span id="getmap" class="mui-icon mui-icon-location"></span>
				</div>
				<div class="picture" style="display:none">
					<div>上传<span id="lookimg" class="red">手持身份证</span>照片</div>
					<small>
						<small class="mui-icon mui-icon-info"></small>
						<small>请保证身份证号清晰可见</small>
					</small>
					<div class="photo">
						<a id="idcardimg">
							上传照片
						</a>
						<img src="" alt="" id="usphoto">
						<input type="file" id="iptxtp" name="iptxtp" onchange="seltxtp(this)">
					</div>
				</div>
				<div class="mui-input-row">
					<label>邀请码：</label>
					<input id="sinvcode" type="tel"  value="<?php echo ($Invcode); ?>" readonly=true>
				</div>
				<div class="mui-input-row">
					<label>手机号：</label>
					<input id="sphone" type="tel" class="mui-input-clear" placeholder="请输入手机号码">
				</div>
			</form>
			<span id="showtips"></span>
			<button id="RegBtn" type="button" class="mui-btn mui-btn-red mui-btn-block">立即申请</button>
		</div>
	</div>
	<div class="map">
		<div id="map">

		</div>
		<div class="btngroup">
			<span class="closemapsel">取消</span>
			<span class="suremapsel">确定</span>
		</div>
	</div>

	<input id="pointsX" name="lang"  type="hidden" value=""/>
	<input id="pointsY" name="lat"  type="hidden" value=""/>
	<input id="idcard" name="idcard"  type="hidden" value=""/>

	<!-- 是否关注公众号 -->
	<div class="subscribemark">
	</div>
	<div class="showsubscribecode">
		<img src="/Public/newhome/img/subscribecode.jpg" alt="">
		<span>长按关注公众号</span>
	</div>
	<!-- 是否关注公众号end -->
</body>
<script type="text/javascript">
	var useridcard= "<?php echo U('Writeinfo/useridcard');?>";//修改会员idcard
	var saveshopinfo_url= "<?php echo U('Writeinfo/writestore');?>";//修改会员idcard
	var shoplogn_url= "<?php echo U('Sellermobile/Public/login');?>";//修改会员idcard
	var useropenid= "<?php echo ($openid); ?>";//openid
	var usernickname= "<?php echo ($nickname); ?>";//用户昵称
	var subscribe= "<?php echo ($subscribe); ?>";//是否关注公众号
</script>
<script type="text/javascript" charset="utf-8" src="/Public/newhome/js/mui.min.js"></script>
<script src="/Public/newadmin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newhome/js/base.js"></script>
<script src="/Public/newhome/js/ajaxfileupload.js"></script>
<script src="/Public/newhome/js/mui.picker.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newhome/js/city.data-3.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newhome/js/SetupShop.js?v=1.4" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
mui.init()//初始化滚动组件***
mui('.mui-scroll-wrapper').scroll({
	deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
});
</script>
<script type="text/javascript">
var wxmap,geocoder,smarker;
window.onload=function(){
	function init(){
		wxmap = new qq.maps.Map(document.getElementById("map"), {
			center: new qq.maps.LatLng(39.916527,116.397128),      // 地图的中心地理坐标。
			zoom:13,
			mapTypeControlOptions: {
				mapTypeIds: [
				],
				position: qq.maps.ControlPosition.TOP_LEFT
			},
			zoomControl: true,
			zoomControlOptions: {
				position: qq.maps.ControlPosition.TOP_RIGHT,
				style: qq.maps.ZoomControlStyle.DEFAULT
			}
		});
		smarker = new qq.maps.Marker({
			map:wxmap,
			position: new qq.maps.LatLng(39.916527,116.397128)
		});
		geocoder = new qq.maps.Geocoder({
			complete : function(result){
				wxmap.setCenter(result.detail.location);
				smarker.setPosition(result.detail.location);
			}
		});
	}
	init();
	geolocation.getLocation(showPosition, showErr, options);
}
var gelat='NULLGE';
var gelng='NULLGE';
//腾讯地图获取当前位置
var geolocation = new qq.maps.Geolocation("NROBZ-QPFHX-DNA4U-7NIQN-RFGHS-4HFS4", "web商店");
var options = {timeout: 6000};


//定位成功返回函数
function showPosition(position) {
	gelat=position.lat;
	gelng=position.lng;
	// $("#pointsX").val(gelng);
	// $("#pointsY").val(gelat);
	smarker.setPosition(new qq.maps.LatLng(gelat, gelng));
	var selcircle=new qq.maps.Circle({
		map:wxmap,
		center:new qq.maps.LatLng(position.lat, position.lng),
		radius:3000,
		fillColor:new qq.maps.Color(0,0,0,0.3),
		strokeWeight:0
	});
	qq.maps.event.addListener(selcircle,"click",function(event){
		smarker.setPosition(event.latLng);
		gelat=event.latLng.lat;
		gelng=event.latLng.lng;
		// $("#pointsX").val(event.latLng.lng);
		// $("#pointsY").val(event.latLng.lat);
	});
};
//定位失败返回函数
function showErr() {
	console.log('定位失败');
};



</script>
</html>