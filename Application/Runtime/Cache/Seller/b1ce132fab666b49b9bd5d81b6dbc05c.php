<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>设置商品属性</title>

    <!-- Bootstrap -->
    <link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/Public/Admin/Admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    </style>
  </head>
  <body>
    <div class="container" style="width:560px;height:380px;">
      <div class="row">
        <form action="">
        <h4 style="text-align:center;">商品属性值设置</h4>
        <hr>
          <div class="form-group col-sm-5">
          <label for="attriButes">属性值</label>
            <input type="text" name="AttributeValue" id="AttributeValue" class="form-control">
          </div>
          <input type="hidden" name="AttributeId" value="" id="AttributeId">
          <div style="text-align:center"><button type="button" class="btn btn-success btn-md" onclick="setAttr();">添 加</button></div>
        </form>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  </body>
<script type="text/javascript">
var AttributeId=art.dialog.data('attrid');
var atrname=art.dialog.data('atrname');
$(document).ready(function(){
	$("#AttributeId").val(AttributeId);
	$("H4").html("<b>"+atrname+"属性值设置</b>");
})
  function setAttr(){
      var AttributeValue=$("#AttributeValue").val();
      var AttributeId=$("#AttributeId").val();
      // alert(AttributeId);
      // alert(AttributeValue);
      var artnotice= art.dialog({content:'正在提交数据，请勿重复操作...',lock:true});
      var statu=false;
      $.ajax({
        type:'post',
        url:'<?php echo U('artDialog/saveAttrValue');?>',
        data:'AttributeId='+AttributeId+"&AttributeValue="+AttributeValue,
        dateType:'json',
        success:function(msg){
          if (msg=='error') {
            alert('添加失败');
            artnotice.close();
            setTimeout("art.dialog.close()", 1000 )
          };
          if (msg=='errors') {
            $(".row").html('<h3 style="text-align:center;margin-top:20%;color:red;"><b>属性值重复！</b></h3>');
            artnotice.close();
            setTimeout("art.dialog.close()", 1000 )
          };
          if (msg=='success') {
            var origin=artDialog.open.origin;
            var dom=origin.document.getElementById("avalue"+AttributeId);
            var html="<span class='attrvalues'  onclick='delValue(\"<?php echo ($value["AttributeValueId"]); ?>\")'>"+AttributeValue+"</span>";
              dom.innerHTML+=html;
              artnotice.close();
            $(".row").html('<h3 style="text-align:center;margin-top:20%;color:green;"><b>添加成功</b></h3>');
            setTimeout("art.dialog.close()", 1000 )
          };
          console.log(statu);
        }
      });
  }
</script>
</html>