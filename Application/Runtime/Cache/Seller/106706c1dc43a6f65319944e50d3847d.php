<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>徽记食品</title>
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
<div class="row wrapper  white-bg" style="margin:0px 1%;">
    <div class="col-lg-12">
       <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><a href="<?php echo U('Products/addYF');?>" style="color:#000;"><button class="btn btn-white" type="button">添加运费模板</button></a></h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xs-12">
            <h3>运费模板管理</h3>
            <table class="table table-bordered">
              <tr>
                 <td style="width:2%;">#</td>
                 <td style="width:6%;">模板名称</td>
                 <td colspan="3" style="width:35%;">计价区一</td>
                 <td colspan="3" style="width:35%;">计价区二</td>
                 <td style="width:15%;">备注</td>
                 <td style="width:7%;">操作</td>
             </tr>
              <tr>
                 <td>#</td>
                 <td>#</td>
                 <td style="width:7%">首重价格</td>
                 <td style="width:7%">续重加价</td>
                 <td>计费区域</td>
                 <td style="width:7%">首重价格</td>
                 <td style="width:7%">续重加价</td>
                 <td>计费区域</td>
                 <td>#</td>
                 <td>#</td>
             </tr>
             <?php if(is_array($Freights)): foreach($Freights as $key=>$fre): ?><tr <?php if($fre['IsSet'] == 1): ?>class="info"<?php endif; ?>>
                 <td><?php echo ($fre["ID"]); ?></td>
                 <td><?php echo ($fre["Name"]); ?></td>
                 <td><?php echo ($fre["FirstWeight"]); ?>g/<?php echo ($fre["Opiece"]); ?></td>
                 <td><?php echo ($fre["AddWeight"]); ?>g/<?php echo ($fre["Oadd"]); ?></td>
                 <td><?php if(is_array($fre["Area"])): foreach($fre["Area"] as $key=>$area): ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo ($area); endforeach; endif; ?></td>
                 <td><?php echo ($fre["FirstWeight"]); ?>g/<?php echo ($fre["Tpiece"]); ?></td>
                 <td><?php echo ($fre["AddWeight"]); ?>g/<?php echo ($fre["Tadd"]); ?></td>
                 <td><?php if(is_array($fre["Area1"])): foreach($fre["Area1"] as $key=>$area): ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo ($area); endforeach; endif; ?></td>
                 <td><?php echo ($fre["Remarks"]); ?></td>
                 <td><a href="###" onclick="edit('<?php echo ($fre["ID"]); ?>');">编辑</a> | <a href="###" onclick="del('<?php echo ($fre["ID"]); ?>')">删除</a> <br><br><?php if($fre['IsSet'] == 0): ?><a href="###" onclick="setMr(<?php echo ($fre["ID"]); ?>);">设为默认</a><?php endif; ?></td>
             </tr><?php endforeach; endif; ?>
     </table>
     <div style="text-align:right;"><?php echo ($page); ?></div>
 </div>
</div>
</div>
<script type="text/javascript">
  function edit(id){
    window.location.href="<?php echo U('Products/editYF');?>?id="+id;
  }
  function del(id){
    art.dialog.confirm('确定要删除此模板吗？',function(){
      window.location.href="<?php echo U('Products/delYF');?>?id="+id;
    },function(){
      art.dialog.tips('操作取消');
    })
  }

  function setMr(id){
    window.location.href="<?php echo U('Products/setMr');?>?fid="+id;
  }
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>