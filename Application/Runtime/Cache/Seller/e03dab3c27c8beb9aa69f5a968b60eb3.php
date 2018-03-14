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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">

<div class="row wrapper  white-bg" style="margin:0px 1%;">
  <div class="col-lg-12">
   <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>添加分类</h5>
        <div class="ibox-tools">
          <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
          </a>
        </div>
      </div>
      <div class="ibox-content">
        <form role="form" class="form-inline" action="" method="post" id="save">
          <div class="form-group">
            <label for="exampleInputPassword2" class="sr-only">分类名称</label>
            <input type="text" placeholder="请填写分类名称" name="ClassName" id="ClassName" class="form-control">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword2" class="sr-only">分类排序</label>
            <input type="text" placeholder="分类排序" name="ClassSort" id="ClassSort" class="form-control">
          </div>
          <div class="checkbox m-l m-r-xs">
            <label class="i-checks">
              <input type="radio" name="IsVisible" id="IsVisible" value="1" checked="checked"><i></i> 显示</label>
              <label class="i-checks">                
                <input type="radio" name="IsVisible" id="NoVisible" value="0"><i></i> 隐藏
              </label>
            </div>
            <div class="checkbox m-l m-r-xs">
              <input type="hidden" name="id" value="" id="classId">
              <button class="btn btn-white save" type="submit">保 存</button>
            </div>
          </form>


        </div>
      </div>
    </div>

    <div class="col-lg-10">
      <h3>分类管理</h3>
      <table class="table table-bordered c_content">
        <thead>
          <tr>
           <td>分类名称</td>
           <td>排序</td>
           <td>是否显示</td>
           <td>操作</td>
         </tr>
       </thead>
       <tbody>
         <?php if(is_array($lists)): foreach($lists as $key=>$li): ?><tr>
           <td><?php echo ($li["ClassName"]); ?></td>
           <td><?php echo ($li["ClassSort"]); ?></td>
           <td><?php if($li['IsVisible'] == '1'): ?>是<?php endif; if($li['IsVisible'] == '0'): ?>否<?php endif; ?></td>
           <td><button class="btn btn-warning btn-xs btn-outline edit" data-id="<?php echo ($li["ClassId"]); ?>">编辑</button>&emsp;<button class="btn btn-xs btn-outline btn-danger del" data-id="<?php echo ($li["ClassId"]); ?>">删除</button></td>
         </tr><?php endforeach; endif; ?>

     </tbody>
   </table>
   <div style="text-align:right;margin-bottom:100px;"><?php echo ($page); ?></div>
 </div>
</div>
</div>
<script type="text/javascript">
var data=<?php echo ($jsondata); ?>;
  $(document).ready(function(){
    $('.c_content').dataTable();
    $("#save").submit(function(){
      var classname=$("#ClassName").val();
      var classsort=$("#ClassSort").val();
      if (!classname) {
        layer.msg('请填写分类名称');
        return false;
      };
      if (!classsort) {
        layer.msg('请填写分类排序');
        return false;
      } else{
        return true;
      };
    })
    $(document).on('click','.edit',function(){
      var cid=$(this).attr('data-id');
      $.each(data,function(index,item){
        if (item.ClassId==cid) {
          $('#classId').val(cid);
          $('#ClassName').val(item.ClassName);
          $('#ClassSort').val(item.ClassSort);
          if (item.IsVisible=='1') {
            $('#IsVisible').attr('checked',true);
            $('#NoVisible').attr('checked',false);
          }else{
            $('#IsVisible').attr('checked',false);
            $('#NoVisible').attr('checked',true);
          }
          $('.save').html('保存修改');
        };
      })
    })
    $(document).on('click','.del',function(){
      var _this=$(this);
      var cid=_this.attr('data-id');
      layer.confirm('确定要删除吗？',{
        btn:['确定','取消'],
        shade:false
      },function(){
        layer.msg('处理中...');
        $.ajax({
          url:"<?php echo U('Products/delclass');?>",
          type:"post",
          data:"cid="+cid,
          dataType:"json",
          success:function(msg){
            if (msg.status=='success') {
              layer.msg('删除成功');
              _this.parent().parent().remove();
            }else{
              layer.msg(msg.info);
            }
          }
        })
      })
    })
  })
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>

<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/dataTables/jquery.dataTables.js?v=1"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/dataTables/dataTables.bootstrap.js?v=1"></script>