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
 <div class="row wrapper border-bottom white-bg page-heading">
     <div class="col-lg-10">
         <h2>管理员设置</h2>
         <ol class="breadcrumb">
             <li>
                 <a href="index.html">主页</a>
             </li>
             <li class="active">
                 <strong>修改密码</strong>
             </li>
         </ol>
     </div>
 <div class="col-lg-2"></div>
 </div>


                <div class="row  wrapper  white-bg" style="margin:0 1%;">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>修改密码</h5>
                            </div>
                            <div class="ibox-content">
                                <form method="post" action="<?php echo U('My/savepass');?>" class="form-horizontal" id="svs">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户名</label>

                                        <div class="col-sm-4">
                                            <input type="text" name="userName" id="userName" readonly="readonly" value="<?php echo ($info["userName"]); ?>" class="form-control required" >
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">旧密码</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="oldPass" id="oldPass" class="form-control required" value=""> <span class="help-block m-b-none"></span>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">密码</label>

                                        <div class="col-sm-4">
                                            <input type="password" name="Password" id="Password" class="form-control required" value="">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">确认密码</label>

                                        <div class="col-sm-4">
                                            <input type="password" name="repass" id="repass" class="form-control">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-outline" type="button" id="sv">保存内容</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#sv").click(function(){
        	var oldPass=$("#oldPass").val();
        	var password=$("#Password").val();
        	var repass=$("#repass").val();
        	if (!oldPass) {
        		art.dialog.alert('请输入旧密码');
        		return false;
        	};
        	if (!password) {
        		art.dialog.alert('请输入密码');
        		return false;
        	};
        	if (repass!=password) {
        		art.dialog.alert('两次输入不一致');
        		return false;
        	}else{
        		$.ajax({
	        		type:"post",
	        		url:"<?php echo U('My/checkpass');?>",
	        		data:"password="+oldPass,
	        		dateType:"json",
	        		success:function(msg){
	        			if (msg=='success') {
	        				$("#svs").submit();
	        			};
	        			if (msg=='error') {
	        				art.dialog.alert('旧密码错误，请重新输入');
	        				return false;
	        			};
	        		}
	        	})
        	}
        })
    })
</script>

 </div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>