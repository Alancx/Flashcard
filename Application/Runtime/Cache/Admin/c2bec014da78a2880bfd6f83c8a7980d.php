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
                                <h5>添加用户组</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <form role="form" class="form-inline" action="<?php echo U('Auth/saveGroup');?>" method="post" id="save">
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">用户组名称</label>
                                        <input type="text" name="GroupName" placeholder="请填写用户组名称"  class="form-control" id="GroupName">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail2" class="sr-only">用户组说明</label>
                                        <input type="text" name="Remarks" placeholder="请填写用户组说明(选填)"  class="form-control" id="Remarks">
                                    </div>
                                    <input type="hidden" name="GroupId" id="GroupId" value="">
                                    <button class="btn btn-white" type="submit" id="saveNotice">保 存</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                </form>
                            </div>
                        </div>
                    </div>
<div class="col-lg-10">
	<table class="table">
		<tr>
			<td>#</td>
			<td>用户组名称</td>
			<td>说明</td>
			<td>创建时间</td>
			<td>操作</td>
		</tr>
        <?php if(is_array($groups)): foreach($groups as $key=>$attr): ?><tr>
			<td><?php echo ($attr["GroupId"]); ?></td>
			<td><?php echo ($attr["GroupName"]); ?></td>
			<td ><?php echo ($attr["Remarks"]); ?></td>
			<td><?php echo (date("Y-m-d H:i:s",$attr["CreateDate"])); ?></td>
			<td><?php if($attr['GroupName'] == '超级管理组'): ?><a href="<?php echo U('Auth/distribute');?>?gid=<?php echo ($attr["GroupId"]); ?>">分配权限</a><?php else: ?><a href="###" onclick="edit('<?php echo ($attr["GroupId"]); ?>');">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Auth/distribute');?>?gid=<?php echo ($attr["GroupId"]); ?>">分配权限</a><?php endif; ?></td>
		</tr><?php endforeach; endif; ?>
	</table>
    <div style="text-align:right;"><?php echo ($page); ?></div>
</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#save").submit(function(){
			var name=$("#GroupName").val();
			if (!name) {
				art.dialog.alert('请填写用户组名称');
				return false;
			}else{
				return true;
			}
		})
	})

	function edit(id){
		var data='';
		data=<?php echo ($jsondata); ?>;
		$.each(data,function(index,item){
			if (item.GroupId==id) {
				$("#GroupName").val(item.GroupName);
				$("#GroupId").val(item.GroupId);
				$("#Remarks").val(item.Remarks);
			};
		})
	}
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>