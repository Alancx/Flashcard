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
<style type="text/css">
    .box{
        border:2px solid #ccc;
        border-radius: 5px;
        padding: 10px;
    }
</style>

<div class="row  wrapper  white-bg" style="margin:0 1%;">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>权限分配</h5>
            </div>
            <div class="ibox-content">
                <form method="post" action="<?php echo U('Auth/saveDis');?>" class="form-horizontal" id="savenote">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">请选择用户组</label>

                        <div class="col-sm-2">
                            <select class="form-control m-b" name="GroupId" id="GroupId" value="<?php echo ($GroupId); ?>" <?php if($GroupId): ?>disabled="disabled"<?php endif; ?>>
                                <option value="">请选择</option>
                                <?php if(is_array($groups)): foreach($groups as $key=>$group): if($group['GroupId'] == $GroupId): ?><option value="<?php echo ($group["GroupId"]); ?>" selected="selected"><?php echo ($group["GroupName"]); ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo ($group["GroupId"]); ?>"><?php echo ($group["GroupName"]); ?></option><?php endif; endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择权限</label>
                        <div class="col-sm-2 box" style="margin-bottom:20px;"><label> <input type="checkbox" name="" onclick="sall(this.checked);" id="">全选 </label></div>
                        <?php if(is_array($menus)): foreach($menus as $key=>$menu): ?><div class="col-sm-10 pull-right box" style="margin-bottom:2%;">
                                <div class="checkbox" style="border-bottom:1px solid #ccc;">
                                  <label>
                                    <input type="checkbox" dataname="" <?php if (in_array($menu['MenuId'], $nodes)): ?>
                                        checked="checked"
                                    <?php endif ?> name="son[]" class="<?php echo ($menu["MenuId"]); ?> all" onclick="csons(this.checked,'<?php echo ($menu["MenuId"]); ?>');" value="<?php echo ($menu["MenuId"]); ?>">
                                    <?php echo ($menu["MenuName"]); ?>
                                </label>
                                </div>
                            <?php if(is_array($menu["sons"])): foreach($menu["sons"] as $key=>$son): ?><label class="checkbox-inline">
                                <input type="checkbox" name="son[]" <?php if (in_array($son['MenuId'], $nodes)): ?>
                                    checked="checked"
                                <?php endif ?> class="<?php echo ($menu["MenuId"]); ?>_val all" value="<?php echo ($son["MenuId"]); ?>" onclick="roots(this.checked,'<?php echo ($menu["MenuId"]); ?>')" id="inlineCheckbox1"><?php echo ($son["MenuName"]); ?>
                            </label>
                                <?php if($son['sons']): ?><div style="display:inline;border-top:1px solid red;border-left:1px solid red;border-right:1px solid red;">
                                &nbsp;&nbsp;&nbsp;[<?php echo ($son["MenuName"]); ?>].子菜单：
                                <?php if(is_array($son["sons"])): foreach($son["sons"] as $key=>$sson): ?><label class="checkbox-inline">
                                        <input type="checkbox" name="son[]" <?php if (in_array($sson['MenuId'], $nodes)): ?>
                                            checked="checked"
                                        <?php endif ?> id="" class="<?php echo ($menu["MenuId"]); ?>_val all" value="<?php echo ($sson["MenuId"]); ?>"><?php echo ($sson["MenuName"]); ?>
                                    </label><?php endforeach; endif; ?>
                                </div><?php endif; endforeach; endif; ?>
                            </div><?php endforeach; endif; ?>

                        <?php if($GroupId): ?><input type="hidden" name="GroupId" value="<?php echo ($GroupId); ?>"><?php endif; ?>

                            </div>
                            <div class="form-group">
                            <input type="hidden" name="statu" value="<?php echo ($statu); ?>">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">
    function csons(statu,rid){
        // alert(statu);
        // alert(rid);
        if (statu) {
            $("."+rid+"_val").prop('checked',true);
        }else{
            $("."+rid+"_val").prop('checked',false);
        }
    }

    function roots(statu,id){
        if (statu) {
            $("."+id).prop('checked',true);
        };
        if (statu==false) {
            var sons=$("."+id+"_val");
            var status=true;
            $.each(sons,function(index,item){
                if (item.checked) {
                    status=false;
                };
            })
            if (status) {
                $("."+id).prop('checked',false);
            };
        };
    }
    $(document).ready(function(){
        $("#savenote").submit(function(){
            var gid=$("#GroupId").val();
            if (!gid) {
                art.dialog.alert('请选择用户组');
                return false;
            }else{
                art.dialog({content:'正在处理数据...',lock:true});
                return true;
            }
        })
    })

    function sall(statu){
        if (statu==true) {
            $(".all").prop('checked',true);
        };
        if (statu==false) {
            $(".all").prop('checked',false);
        };
    }
</script>
        </div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>