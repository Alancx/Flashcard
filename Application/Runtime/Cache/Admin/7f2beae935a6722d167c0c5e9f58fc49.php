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

<div class="row wrapper  white-bg" style="margin:0px 1%;">
<div class="col-lg-12">
    <div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>物流管理</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <form role="form" class="form-inline" action="<?php echo U('Warehouse/savewuliu');?>" method="post" id="save">
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">物流公司名称</label>
                                        <input type="text" name="Name" placeholder="请填写物流名称"  class="form-control" id="WarehouseName">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">物流编号</label>
                                        <input type="text" name="Number" placeholder="请填写物流编号"  class="form-control" id="WarehouseName">
                                    </div>
                                    <div class="checkbox m-l m-r-xs">
                                        <label class="i-checks">
                                            <input type="radio" name="IsDefault" id="IsVisible" value="1" ><i></i> 默认物流</label>
                                    </div>
                                    <button class="btn btn-white" type="submit" id="saveNotice">保 存</button>&nbsp;&nbsp;&nbsp;&nbsp;<small style="color:red">注* 物流名称添加后无法修改、删除</small>< <b><a href="/Public/logistics.xlsx">下载查看物流编号</a></b> >
                                </form>
                            </div>
                        </div>
                    </div>
<div class="col-lg-10">
<h3>仓库</h3>
    <table class="table table-hover">
        <tr>
            <td>ID</td>
            <td>物流名称</td>
            <td>物流编号</td>
            <td>创建时间</td>
            <td>是否默认</td>
            <!-- <td>操作</td> -->
        </tr>
        <?php if(is_array($infos)): foreach($infos as $key=>$info): ?><tr>
            <td><?php echo ($info["ID"]); ?></td>
            <td><?php echo ($info["Name"]); ?></td>
            <td><?php echo ($info["Number"]); ?></td>
            <td ><?php echo ($info["CreateDate"]); ?></td>
            <td id="ids<?php echo ($info["ID"]); ?>"><input type="radio" name="noname" onclick="moren(this.checked,this.id);" id="<?php echo ($info["ID"]); ?>" <?php if($info['IsDefault'] == 1): ?>checked="checked"<?php endif; ?>></td>
            <!-- <td id="id<?php echo ($info["ID"]); ?>"><?php if($info['IsDefault'] == 1): ?><a href="###" onclick="quxiao('<?php echo ($info["ID"]); ?>')">取消默认</a><?php endif; if($info['IsDefault'] == 0): ?><a href="###" onclick="moren('<?php echo ($info["ID"]); ?>')">设为默认</a><?php endif; ?></td> -->
        </tr><?php endforeach; endif; ?>
    </table>
    <div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#save").submit(function(){
            var name=$("#WarehouseName").val();
            var sort=$("#Sort").val();
            if (!name) {
                alert('请填写物流名称');
                return false;
            }else{
                return true;
            };
        })
    })

    // function quxiao(id){
    //     var notice=art.dialog({content:'正在处理...',lock:true});
    //     $.ajax({
    //         type:"post",
    //         url:"<?php echo U('Warehouse/changemoren');?>",
    //         data:"id="+id+"&statu=no",
    //         dateType:"json",
    //         success:function(msg){
    //             if (msg=='success') {
    //                 notice.close();
    //                 art.dialog.tips('设置成功');
    //                 $("#id"+id).html('<a href="###" onclick="moren(\''+id+'\')">设为默认</a>');
    //                 $("#ids"+id).html('否')
    //             };
    //             if (msg=='error') {
    //                 notice.close();
    //                 art.dialog.tips('设置失败');
    //             };
    //         }
    //     })
    // }
    function moren(statu,id){
        var notices=art.dialog({content:'正在处理...',lock:true});
        $.ajax({
            type:"post",
            url:"<?php echo U('Warehouse/changemoren');?>",
            data:"id="+id+"&statu=yes",
            dateType:"json",
            success:function(msg){
                if (msg=='success') {
                    notices.close();
                    art.dialog.tips('设置成功');
                    $("#"+id).prop('checked',true);
                };
                if (msg=='error') {
                    notices.close();
                    art.dialog.tips('设置失败');
                };
            }
        })
    }
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>