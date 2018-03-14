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
    <link href="/Public/Admin/Admin/css/style.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    <script src="/Public/Admin/Admin/js/hplus.js"></script>

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>

<style type="text/css">
  .head{
  	margin:25px;
  }
</style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>个人资料</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">主页</a>
                </li>
                <li class="active">
                    <strong>修改头像 <small>双击选中头像即可更换</small></strong>
                </li>
            </ol>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">

                        <div class="ibox-content">
                        <?php for ($i=1; $i < 58; $i++) { ?>
                        <label class="head">  <img src="/Public/Admin/Admin/img/headimg/head (<?php echo ($i); ?>).png" alt="" width="100" height="100" ondblclick="editImg('head (<?php echo ($i); ?>).png')"><input type="radio" name="headimg" id="" style="display:block;margin-left:45%;"></label>
                        <?php } ?>
                        </div>
                    </div>
                    <div style="text-align:center;"><?php echo ($page); ?></div>
                </div>
            </div>

        </div>
    </div>

<script type="text/javascript">
	function editImg(img){
		// alert(img);
		$.ajax({
			type:"post",
			url:"<?php echo U('My/editImg');?>",
			data:"img="+img+"&uid="+<?php echo $_SESSION['seller']['userinfo']['ID']; ?>,
			dateType:'json',
			success:function(msg){
				if (msg=='success') {
					art.dialog.tips('修改成功 :)');
					$("#headimg").attr('src','/Public/Admin/Admin/img/headimg/'+img);
				};
				if (msg=='error') {
					art.dialog.tips('修改失败 :(');
				};
			}
		})
	}
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>