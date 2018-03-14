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
<script type="text/javascript" src="/Public/Admin/Admin/js/serviceindex.js?v=1.2"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/ajaxfileupload.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/serviceindex.css?v=1.2">

<div class="row  wrapper  white-bg chatpart" >
  <div class="chat chatcontent col-lg-9">
    <label class="chattop memname">选择在线客户</label>
    <div class="contents">
      <!-- <div class="chatto chatcontent">
        <img src="/Public/theme2/images/touxiang.png">
        <label class="content">大家好你好我也好</label>
        <span></span>
      </div> -->
      <!-- <div class="chatfrom chatcontent">
        <img src="/Public/theme2/images/touxiang.png">
        <label class="content">大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好大家好你好我也好</label>
        <span></span>
      </div> -->
    </div>
    <div class="inputcontent input-group">
      <div class="input-group-btn">
        <img class="sendimg" src="/Public/theme2/Images/sendimg.png">
        <input type="file" id="simg" name="simg" onchange="selsimg(this)">
      </div>
      <input type="text" class="editchat" name="editchat" id="editchat">
      <span class="input-group-btn">
        <button class="btnsend" type="button">发送</button>
      </span>
    </div>
  </div>
  <div class="chat memlist col-lg-3">
    <div class="memberlist">
      <label class="chattop">在线客户</label>
      <ul class="listmem">
        <!-- <li class="limember"><img src="/Public/theme2/images/touxiang.png">13711555815</li> -->
      </ul>
    </div>
  </div>
</div>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>

<script type="text/javascript">
  var sendmsg_url="<?php echo U('Service/sendmsg');?>"; //发送消息
   var kefuid="<?php echo ($serviceid); ?>";
   var kefuname="<?php echo ($servicename); ?>";
   var kefuimg="/Public/Admin/Admin/img/headimg/<?php echo ($serviceimg); ?>";
   var kefusimg_url= "<?php echo U('Service/savesengimg');?>";//保存发送图片
</script>