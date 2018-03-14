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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/messages_zh.min.js"></script>


                <div class="row  wrapper  white-bg" style="margin:0 1%;">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><?php echo ($pagename); ?></h5>
                            </div>
                            <div class="ibox-content">
                                <form method="post" action="<?php echo U('Admin/save');?>" class="form-horizontal" id="sv">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户名</label>

                                        <div class="col-sm-4">
                                            <input type="text" name="EmployeeId" id="EmployeeId" class="form-control required" >
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">真实姓名</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="TrueName" id="TrueName" class="form-control required" value=""> <span class="help-block m-b-none"></span>
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
                                        <label class="col-sm-2 control-label">选择门店</label>

                                        <div class="col-sm-4">
                                            <select class="form-control m-b required" name="DepartmentName" id="DepartmentName">
                                                <?php if(is_array($allParts)): foreach($allParts as $key=>$part): ?><!--                                                 <?php if($part['Grade'] == '1'): ?><option style="color:red;font-size:1.2em;" value="<?php echo ($part["id"]); ?>"><?php echo ($part["storename"]); ?></option>
                                                <?php else: ?> -->
                                                <option style="color:green;" value="<?php echo ($part["id"]); ?>"><?php echo ($part["storename"]); ?></option>
                                              <!--<?php endif; ?> --><?php endforeach; endif; ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择用户组</label>

                                        <div class="col-sm-4">
                                            <select class="form-control m-b required" name="GroupId" id="GroupId">
                                                <option value="">请选择</option>
                                                <?php if(is_array($groups)): foreach($groups as $key=>$group): ?><option style="color:green;" value="<?php echo ($group["GroupId"]); ?>"><?php echo ($group["GroupName"]); ?></option><?php endforeach; endif; ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">性别
                                        </label>

                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio"  value="1" id="optionsRadios1" name="Sex">男</label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" id="optionsRadios2" value="2" name="Sex">女</label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" id="optionsRadios2" checked="checked" value="0" name="Sex">保密</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><span style='color:red;'>是否允许登录</span>
                                        </label>

                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio"  value="1" id="optionsRadios1" name="IsLogin" checked="checked">是</label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" id="optionsRadios2" value="0" name="IsLogin">否</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
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
</script>

 </div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>