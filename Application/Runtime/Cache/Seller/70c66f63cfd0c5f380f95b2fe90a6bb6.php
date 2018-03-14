<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>文件上传</title>

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
    h2{
        font-weight: bolder;
        color: green;
    }
    </style>
  </head>
  <body>
  <?php if($imgurl == ''): ?><form action="<?php echo U('Upimg/editpro');?>" method="post" enctype="multipart/form-data">
    <table class="table">
        <tr>
            <td><input type="file" name="img" id=""></td>
            <input type="hidden" name="logo" id="logo" value="">
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;上&nbsp;&nbsp;&nbsp;&nbsp;传&nbsp;&nbsp;&nbsp;&nbsp;" class="btn btn-success btn-sm"></td>
        </tr>
    </table>
    </form>
    <?php else: ?>
    <div style="text-align:center; margin-top:15%;"><h2>上传成功！</h2></div><?php endif; ?>
    <script type="text/javascript">
    $(document).ready(function(){
      var logo=art.dialog.data('domid');
      $('#logo').val(logo);

    })
    var statu="<?php echo ($imgurl); ?>";
    function load(){
        var domid=art.dialog.data('domid');
        var origin=artDialog.open.origin;
        var dom = origin.document.getElementById(domid);
        dom.value=statu;
        setTimeout("art.dialog.close()", 1500 )
        var cdom=origin.document.getElementById(domid+"_con");
        // console.log(cdom);
        var img_url="<?php echo ($PICURL); ?>"+statu;
        console.log(img_url);
        cdom.innerHTML='<img src="'+img_url+'" id="'+domid+'_url" alt="">';
    }
    if (statu) {
        load();
    };
    </script>
  </body>
</html>