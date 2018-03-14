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
 <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;z-index:9;">
     <div class="panel">
         <div class="panel-heading">
             <i class="fa fa-warning"></i> 提示信息
         </div>
         <div class="panel-body">
             <div class="alert alert-warning">
              1、该页面内容<b style="color:red;">每项都为必填</b><br>
              2、<b>APPID</b>与<b>APPSECRET</b>可以在-》微信公众平台-》开发-》基本配置中查看<br>
              3、<b>商户号</b>可在-》微信公众平台-》微信支付 中查看<br>
              4、<b>API密匙</b> 微信商户平台-账户设置-》api安全 中设置 <br>
              5、apiclient_cert与apiclient_key证书可在微信商户平台-》账户设置-》操作证书 中查看下载<br>
              6、<b style="color:red;">特别注意apiclient_cert与apiclient_key证书上传时每个输入框内上传文件名称必须一致，否则将影响支付使用</b><br>
              &emsp;<br>
              <b  style="color:red;">请仔细对照各项参数无误之后保存内容，否则将影响支付使用</b>
          </div>
      </div>
  </div>
</div>


                <div class="row  wrapper  white-bg" style="margin:0 1%;">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>微信支付配置</h5>
                            </div>
                            <div class="ibox-content">
                                <form method="post" action="<?php echo U('Payset/wxpaysave');?>" class="form-horizontal" id="sv">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">APPID</label>

                                        <div class="col-sm-4">
                                            <input type="text" name="APPID" id="APPID" class="form-control required" value="<?php echo ($config["appid"]); ?>" >
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">APPSECRET</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="APPSECRET" id="APPSECRET" class="form-control required" value="<?php echo ($config["appsecret"]); ?>"> <span class="help-block m-b-none"></span>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">商户号</label>

                                        <div class="col-sm-4">
                                            <input type="number" name="mchid" id="mchid" class="form-control required" value="<?php echo ($config["mchid"]); ?>">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">API密匙</label>

                                        <div class="col-sm-4">
                                            <input type="text" name="api" id="api" value="<?php echo ($config["apikey"]); ?>" class="form-control required">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">apiclient_cert</label>

                                        <div class="col-sm-4">
                                            <input type="text" name="apiclient_cert" id="apiclient_cert" value="<?php echo ($config["apiclient_cert"]); ?>" class="form-control required" readonly="readonly">
                                        </div>
                                        <div class="col-sm-2 col-xs-2">
                                          <button class="btn btn-primary btn-outline btn-xs" type="button" onclick="upfile('apiclient_cert')">点击上传证书文件apiclient_cert</button>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">apiclient_key</label>

                                        <div class="col-sm-4">
                                            <input type="text" name="apiclient_key" id="apiclient_key" value="<?php echo ($config["apiclient_key"]); ?>" class="form-control required" readonly="readonly">
                                        </div>
                                        <div class="col-sm-2 col-xs-2">
                                          <button class="btn btn-primary btn-outline btn-xs" type="button" onclick="upfile('apiclient_key')">点击上传证书文件apiclient_key</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="ID" value="<?php echo ($config["ID"]); ?>">
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
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#sv").validate({
            rules: {
                repass:{
                        required: true,
                        equalTo: "#Password"
                },
                DepartmentName: 'required'
            }
        });
    })


    function upfile(id){
      	art.dialog.data('domid',id);
  		art.dialog.open("<?php echo U('ArtDialog/upcert');?>");
  	}
</script>

 </div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>