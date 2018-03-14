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
<style type="text/css">
.row{
  padding: 20px 40px 20px 40px;
}
.showtable>tbody>tr>td{
  width: 25%;
}
.input-group-addon{
  cursor: pointer;
}
.input_sel{
  position: relative;
}
.input_sel>label{
  position: absolute;
  width: 150px;
  margin: 0px;
  left: 0px;
  top: 7px;
  text-align: center;
}
</style>
<div class="row">
  <div class="input-group input_sel" style="padding:0px 0px 20px 150px;">
    <label>小店首页轮播图</label>
    <input type="text" class="form-control required" name="ImgPath" id="logoimg" value="">
     <span class="input-group-addon" onclick="upimg('logoimg')">上传</span>
  </div>
  <div class="input-group input_sel" style="padding:0px 0px 20px 150px;">
    <label>轮播图链接</label>
    <input type="text" class="form-control required" name="ImgPath" id="imghref" value="HTTP://">
    <span class="input-group-addon" onclick="saveimg(this)">保存 </span>
  </div>
  <div class="input-group input_sel" style="padding:0px 0px 20px 150px;">
    <label>小店首页商品一</label>
    <select name="pro-one" id="pro-one" class="form-control">
      <option value="">请选择</option>
      <?php if(is_array($proinfo)): foreach($proinfo as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>" data-img="<?php echo ($pro["ProLogoImg"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
    </select>
     <span class="input-group-addon" onclick="savepro(1)">保存 </span>
  </div>
  <div class="input-group input_sel" style="padding:0px 0px 20px 150px;">
    <label>小店首页商品二</label>
    <select name="pro-two" id="pro-two" class="form-control">
      <option value="">请选择</option>
      <?php if(is_array($proinfo)): foreach($proinfo as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>" data-img="<?php echo ($pro["ProLogoImg"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
    </select>
     <span class="input-group-addon" onclick="savepro(2)">保存 </span>
  </div>
  <table class="table tabel-hovered table-bordered showtable">
    <tbody>
      <tr class="home_img">
        <td>小店首页轮播图</td>
        <td>LB1</td>
        <td><?php echo ($homeimg['ImgPath']); ?></td>
        <td><img src="<?php echo ($PICURL); echo ($homeimg['ImgPath']); ?>" alt="" style="width:150px;"></td>
      </tr>
      <tr class="home_pro_1">
        <td>小店首页商品一</td>
        <td><?php echo ($prohot['ProId']); ?></td>
        <td><?php echo ($prohot['ProName']); ?></td>
        <td><img src="<?php echo ($PICURL); echo ($prohot['ProLogoImg']); ?>" alt="" style="width:150px;"></td>
      </tr>
      <tr class="home_pro_2">
        <td>小店首页商品二</td>
        <td><?php echo ($pronew['ProId']); ?></td>
        <td><?php echo ($pronew['ProName']); ?></td>
        <td><img src="<?php echo ($PICURL); echo ($pronew['ProLogoImg']); ?>" alt="" style="width:150px;"></td>
      </tr>
    </tbody>
  </table>
</div>

<script type="text/javascript">
var imgurl_href = "<?php echo ($PICURL); ?>";
function upimg(id){
  art.dialog.data('domid',id);
  art.dialog.open('<?php echo U('Upimg/home');?>');
};
function saveimg(){
  if ($('#logoimg').val()!='') {
    $('.home_img').children('td').eq(1).text($('#imghref').val());
    $('.home_img').children('td').eq(2).text($('#logoimg').val());
    $('.home_img').children('td').eq(3).children('img').attr('src',imgurl_href+$('#logoimg').val());
    var imgurl=$('#logoimg').val();
    var imghref=$('#imghref').val();
    $.ajax({
      type:"post",
      url:"<?php echo U('BaseSetting/homesave');?>",
      data:"imgurl="+imgurl+"&type=0&imghref="+imghref,
      dateType:"json",
      success:function(msg){
        if (msg.status=='true') {
          art.dialog.tips('保存成功',0.5);
        };
        if (msg.status=='error') {
          art.dialog.tips('保存失败',0.5);
        };
      }
    })
  }
};
function savepro(type){
  if (type==1) {
    if ($("#pro-one option:selected").val()=='') {
      return;
    }
    var pid=$("#pro-one option:selected").val();
    var pname=$("#pro-one option:selected").text();
    var pimg=$("#pro-one option:selected").attr('data-img');
    $('.home_pro_1').children('td').eq(1).text(pid);
    $('.home_pro_1').children('td').eq(2).text(pname);
    $('.home_pro_1').children('td').eq(3).children('img').attr('src',imgurl_href+pimg);
  } else {
    if ($("#pro-two option:selected").val()=='') {
      return;
    }
    var pid=$("#pro-two option:selected").val();
    var pname=$("#pro-two option:selected").text();
    var pimg=$("#pro-two option:selected").attr('data-img');
    $('.home_pro_2').children('td').eq(1).text(pid);
    $('.home_pro_2').children('td').eq(2).text(pname);
    $('.home_pro_2').children('td').eq(3).children('img').attr('src',imgurl_href+pimg);
  }
    $.ajax({
      type:"post",
      url:"<?php echo U('BaseSetting/homesave');?>",
      data:"pid="+pid+"&type="+type,
      dateType:"json",
      success:function(msg){
        if (msg.status=='true') {
          art.dialog.tips('保存成功',0.5);
        };
        if (msg.status=='error') {
          art.dialog.tips('保存失败',0.5);
        };
      }
    })
  // if ($('#logoimg').val()!='') {
  //   $('.home_img').children('td').eq(1).text($('#imghref').val());
  //   $('.home_img').children('td').eq(2).text($('#logoimg').val());
  //   $('.home_img').children('td').eq(3).children('img').attr('src',$('#logoimg').val());
  //   var imgurl=$('#logoimg').val();
  //   var imghref=$('#imghref').val();
  //   $.ajax({
  //     type:"post",
  //     url:"<?php echo U('BaseSetting/homesave');?>",
  //     data:"imgurl="+imgurl+"&type=1&imghref="+imghref,
  //     dateType:"json",
  //     success:function(msg){
  //       if (msg.status=='true') {
  //         art.dialog.tips('保存成功',0.5);
  //       };
  //       if (msg.status=='error') {
  //         art.dialog.tips('保存失败',0.5);
  //       };
  //     }
  //   })
  // }
};
</script>



</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>