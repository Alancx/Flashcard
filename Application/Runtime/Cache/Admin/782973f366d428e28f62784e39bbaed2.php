<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>光盘客</title>
    <meta name="keywords" content="徽记食品">
    <meta name="description" content="徽记食品">
    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/messages_zh.min.js"></script>
<!-- <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=41HNxGdgWCLbloDklqd38kcuRSTKv2Li"></script> -->
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=NROBZ-QPFHX-DNA4U-7NIQN-RFGHS-4HFS4&libraries=convertor,geometry"></script>
<style type="text/css">
  .enotice{color: #f00;font-weight: bold;}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
  <div class="col-lg-8">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>商户申请</h5>
      </div>
      <div class="ibox-content">
        <form method="post" action="" class="form-horizontal" id="sv">
          <div class="form-group">
            <label class="col-sm-2 control-label">邀请码</label>

            <div class="col-sm-6">
              <input type="text" name="Invcode" required id="Invcode" class="form-control"
              value="" required="true"><span class="help-block m-b-none enotice"></span>

            </div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group">
            <label class="col-sm-2 control-label">商户名称</label>

            <div class="col-sm-6">
              <input type="text" name="storename" required id="storename" class="form-control"
              value="" required="true">
            </div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group">
            <label class="col-sm-2 control-label">手机号</label>
            <div class="col-sm-6">
            <input type="number" name="tel" id="tel" required class="form-control" value="" required="true">
              
              <span class="help-block m-b-none">作为后台账户使用 <small>(密码为手机号后四位)</small></span>
            </div>
          </div>
          <!-- <div class="hr-line-dashed"></div>
          <div class="form-group">
            <label class="col-sm-2 control-label">身份证号</label>
            <div class="col-sm-6">
              <input type="number" name="IdCard" id="IdCard" class="form-control" value="" required="true"> 
              <span class="help-block m-b-none"></span>
            </div>
          </div> -->
          <div class="hr-line-dashed"></div>
          <div class="form-group">
            <label class="col-sm-2 control-label">真实姓名</label>
            <div class="col-sm-6">
              <input type="text" name="TrueName" id="TrueName" class="form-control" value="" required="true"> 
            </div>
          </div>
          <!-- <div class="form-group">
            <label class="col-sm-2 control-label">身份验证</label>

            <div class="col-sm-4">
              <input type="text" name="IdInfo" id="IdInfo" value="<?php echo ($config["apiclient_cert"]); ?>" class="form-control required" readonly="readonly">
            </div>
            <div class="col-sm-2 col-xs-2">
              <button class="btn btn-primary btn-outline btn-xs" type="button" onclick="upfile('IdInfo')">点击上传手持身份证验证照片</button> <button class="btn btn-primary btn-outline btn-xs" type="button" onclick="show('IdInfo')">预 览</button>
            </div>
          </div>
          <div class="hr-line-dashed"></div> -->
          <div class="form-group">
            <label class="control-label col-sm-2">选择地址</label>
            <div class="col-sm-10 input-group">
              <select name="province" id="s_province" class="form-control" style="width:20%;"
              value="<?php echo ($store["province"]); ?>" onchange="checkField(this)">
            </select>
            <select name="city" id="s_city" class="form-control" style="width:20%;"
            value="<?php echo ($store["city"]); ?>" onchange="checkField(this)">
          </select>
          <select name="county" id="s_county" class="form-control" style="width:20%;"
          value="<?php echo ($store["area"]); ?>" onchange="checkField(this)">
        </select>
      </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
      <label class="col-sm-2 control-label">请输入详细地址</label>

      <div class="col-sm-6">
        <input type="text" name="addr" id="addr" class="form-control" value="" required="true">
      </div>
    </div>
    <div class="hr-line-dashed"></div>
    <input id="pointsX" name="lang"  type="hidden" value=""/>
    <input id="pointsY" name="lat"  type="hidden" value=""/>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-2">
        <button class="btn btn-primary btn-outline" type="submit">保存内容</button>
        <button class="btn btn-outline btn-warning" type="reset">取消</button>
      </div>
    </div>
  </form>
</div>
</div>
</div>
<div class="col-lg-4">
  <div class="ibox float-e-margins">
    <div class="ibox-title">
      <label class="control-label" style="margin-top: 25px">地图选址</label>
      <div id="allmap"
      style="width: 100%;height: 300px;border-style:dashed;border-width:1px;border-color: #b9b9b9">
    </div>
    <div id="lant"></div>
  </div>
</div>

</div>
</div>
<script type="text/javascript" src="/Public/Admin/plugins/area.js"></script>
<script type="text/javascript">
    new PCAS(["s_province", "请选择省份"], ["s_city", "请选择城市"], ["s_county", "请选择地区"]) //三级联动，有默认值，有文字提示信息
    $(document).ready(function(){
      $('input').on('keyup',function(){
        $(this).parent().find('span').html('');
      })
      $("#sv").validate();
      $(document).on('blur','#Invcode',function(){
        $.ajax({
          url:"<?php echo U('Storers/checkparam');?>",
          type:"post",
          data:{type:'invcode','key':$('#Invcode').val()},
          dataType:"json",
          success:function(msg){
            if (msg.status=='success') {

            }else{
              $('#Invcode').val('').focus();
              $('#Invcode').parent().find('span').html('邀请码错误');
            }
          }
        })
      })
      // $(document).on('blur','#IdCard',function(){
      //   $.ajax({
      //     url:"<?php echo U('Storers/checkparam');?>",
      //     type:"post",
      //     data:{type:"idcard",key:$('#IdCard').val()},
      //     dataType:"json",
      //     success:function(msg){
      //       if (msg.status=='success') {

      //       }else{
      //         $("#IdCard").val('').focus();
      //         $('#IdCard').parent().find('span').html('身份证号已使用');
      //       }
      //     }
      //   })
      // })

      $('#tel').blur(function(){
        $.ajax({
          url:"<?php echo U('Storers/checktel');?>",
          type:"post",
          data:"tel="+$(this).val(),
          dataType:"json",
          success:function(msg){
            if (msg.status=='error') {
              art.dialog.tips('手机号已被占用',3);
              $('#tel').val('').focus();
            };
          }
        })
      })

      $("#sv").submit(function(){
        if ($("#pointsX").val() && $("#pointsY").val()) {
          return true;
        }else{
          art.dialog.alert('请在地图上选定门店地址');
          return false;
        }
      })
    })

function upfile(id){
  art.dialog.data('domid',id);
  art.dialog.open("<?php echo U('Upimg/idinfo');?>");
}
function show(id){
  art.dialog({title:'图片预览',content:'<img src="<?php echo ($PICURL); ?>'+$('#'+id).val()+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
}

</script>

</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>

<script type="text/javascript">
//     // 百度地图API功能
//     var map = new BMap.Map("allmap");
//     var point = new BMap.Point(116.331398, 39.897445);

//     var top_right_navigation = new BMap.NavigationControl({
//         anchor: BMAP_ANCHOR_TOP_RIGHT,
//         type: BMAP_NAVIGATION_CONTROL_SMALL
//     }); //右上角，仅包含平移和缩放按钮
//     map.addControl(top_right_navigation);
//     map.centerAndZoom(point, 12);
//     map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
//     map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

//     function myFun(result) {
//         var cityName = result.name;
//         map.setCenter(cityName);
//     }
//     // var myCity = new BMap.LocalCity();
//     // myCity.get(myFun);
//     function checkField() {
//         var address = "";
//         address = $("#s_province").val() + $("#s_city").val() + $("#s_county").val();
//         if (address != "")
//             map.centerAndZoom(address, 12);      // 用城市名设置地图中心点
//     }
//     //单击获取点击的经纬度
//     map.addEventListener("click",function(e){
//         $("#lant").html('longitude:'+e.point.lng+' &emsp; latitude:'+e.point.lat);
//         $("#pointsX").val(e.point.lng);
//         $("#pointsY").val(e.point.lat);
//         var mpoint =new BMap.Point(e.point.lng, e.point.lat);
//         addMarker(mpoint);
//     });
//     function addMarker(mpoint){
//         map.clearOverlays();
//         var marker = new BMap.Marker(mpoint);
//         map.addOverlay(marker);
//     }
var wxmap,geocoder,smarker;

window.onload=function(){
  function init(){
   wxmap = new qq.maps.Map(document.getElementById("allmap"), {
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
   qq.maps.event.addListener(wxmap, 'click', function(event) {
    smarker.setPosition(event.latLng);
    $("#pointsX").val(event.latLng.lng);
    $("#pointsY").val(event.latLng.lat);
    $("#lant").html('longitude:'+event.latLng.lng+' &emsp; latitude:'+event.latLng.lat);
  });
 }
 init();
}
function checkField(sel) {
  var address = "";
  if ($(sel).attr('id')=='s_province') {
    address = $("#s_province").val();
  } else if ($(sel).attr('id')=='s_city') {
    address = $("#s_province").val() + $("#s_city").val();
  } else if ($(sel).attr('id')=='s_county') {
    address = $("#s_province").val() + $("#s_city").val() + $("#s_county").val();
  }
  if (address != ""){
    geocoder.getLocation(address);
  }
}

</script>