<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>徽记食品</title>
    <meta name="keywords" content="徽记食品">
    <meta name="description" content="徽记食品">
    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    <script src="/Public/Admin/Admin/js/hplus.js"></script>

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=41HNxGdgWCLbloDklqd38kcuRSTKv2Li"></script>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
    <div class="col-lg-8 col-md-8">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>门店管理</h5>
            </div>
            <div class="ibox-content">
                <form method="post"  class="form-horizontal" id="sv" action="<?php echo U('Store/add');?>" >
                    <div class="form-group">
                        <label class="col-sm-2 control-label">门店名称</label>

                        <div class="col-sm-6">
                            <input type="text" name="StoreName" id="EmployeeId" class="form-control"
                                   value="<?php echo ($store["storename"]); ?>" required="true">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">手机号</label>
                        <div class="col-sm-6">
                            <input type="number" name="tel" id="TrueName" class="form-control" value="<?php echo ($store["tel"]); ?>" required="true">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">选择地址</label>
                        <div class="col-sm-10 input-group">
                            <select name="province" id="s_province" class="form-control" style="width:20%;"
                                    value="<?php echo ($store["province"]); ?>" onchange="checkField()">
                            </select>
                            <select name="city" id="s_city" class="form-control" style="width:20%;"
                                    value="" onchange="checkField()">
                            </select>
                            <select name="county" id="s_county" class="form-control" style="width:20%;"
                                    value="" onchange="checkField()">
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">请输入详细地址</label>

                        <div class="col-sm-6">
                            <input type="text" name="addr" id="addr" class="form-control" value="<?php echo ($store["addr"]); ?>" required="true">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <input id="pointsX" name="lang"  type="hidden" value="<?php echo ($store["lang"]); ?>"/>
                    <input id="pointsY" name="lat"  type="hidden" value="<?php echo ($store["lat"]); ?>"/>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <input type="hidden" name="id" value="<?php echo ($store["id"]); ?>">
                            <input type="hidden" name="ischangeaddr" value="0" id="ischange">
                            <button class="btn btn-primary btn-outline" type="submit">保存内容</button>
                            <button class="btn btn-outline btn-warning" type="reset">取消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
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
    new PCAS(["s_province=<?php echo ($store["province"]); ?>"], ["s_city=<?php echo ($store["city"]); ?>"], ["s_county=<?php echo ($store["county"]); ?>"])	//三级联动，有默认值，有文字提示信息
    $(document).ready(function(){
      $("#sv").submit(function(){
        if ($("#pointsX").val() && $("#pointsY").val()) {
          return true;
        }else{
          art.dialog.alert('请在地图上选定门店地址');
          return false;
        }
      })
    })
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>

<script type="text/javascript">
  var sid="<?php echo ($store["id"]); ?>";
  var storeid=sid?sid:false;
  // 百度地图API功能
  var map = new BMap.Map("allmap");
    lang=<?php echo ($store["lang"]); ?>;
    lat=<?php echo ($store["lat"]); ?>;
  var point= new BMap.Point(lang, lat);
  var top_right_navigation = new BMap.NavigationControl({
      anchor: BMAP_ANCHOR_TOP_RIGHT,
      type: BMAP_NAVIGATION_CONTROL_SMALL
  }); //右上角，仅包含平移和缩放按钮
  map.addControl(top_right_navigation);
  map.centerAndZoom(point, 12);
  map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
  map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

  function myFun(result) {
      var cityName = result.name;
      map.setCenter(cityName);
  }
  // var myCity = new BMap.LocalCity();
  // myCity.get(myFun);
  function checkField() {
      var address = "";
      address = $("#s_province").val() + $("#s_city").val() + $("#s_county").val();
      console.log(address);
      if (address != "")
          map.centerAndZoom(address, 12);      // 用城市名设置地图中心点
          $("#ischange").val('1');
  }
  //单击获取点击的经纬度
  map.addEventListener("click",function(e){
      $("#lant").html('longitude:'+e.point.lng+' &emsp; latitude:'+e.point.lat);
      $("#pointsX").val(e.point.lng);
      $("#pointsY").val(e.point.lat);
      var mpoint =new BMap.Point(e.point.lng, e.point.lat);
      addMarker(mpoint);
  });
  function addMarker(mpoint){
      map.clearOverlays();
      var marker = new BMap.Marker(mpoint);
      map.addOverlay(marker);
  }
</script>