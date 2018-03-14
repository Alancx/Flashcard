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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">

<style type="text/css">
    .pro{
        width: 20%;
        float: left;
        position: relative;
        margin-top: 15px;
        /*border: 1px solid green;*/
        /*border-bottom: 1px solid #ccc;*/
    }
    .pinfo{
        width: 96%;
        margin: auto 2%;
        /*height: 250px;*/
        /*border: 1px solid orange;*/
    }
    td img{
        width: 100px;
        height: 100px;
        /*min-width: 290px;*/
    }
    a{
        color: #666;
        font-style: normal;
    }
    a:hover{
        font-style: normal;
        color: #222;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">

  <div class="wrapper wrapper-content animated fadeInRight" style="min-height:700px;">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="col-lg-12 col-md-12" style="border-bottom:0px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
            <form action="" class="form-inline" method="post" id="saveprice">
              <div class="form-group">
                <select name="pros" id="pros" class="form-control">
                  <option value="-1">请选择商品</option>
                  <?php if(is_array($pros)): foreach($pros as $key=>$pro): ?><option value="<?php echo ($pro["ProIdCard"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
                </select>
              </div>
              <div class="form-group">
                <input type="text" name="Sprice" id="Sprice" class="form-control" placeholder="特价价格">
              </div><br><br>
              <div class="form-group">
                <input type="text" name="Stime" id="Stime" class="form-control" placeholder='开始时间'  onfocus="WdatePicker({minDate:'%y-%M-{%d+0}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
              </div>
              <div class="form-group">
                <input type="text" name="Etime" id="Etime" class="form-control" placeholder='结束时间' onfocus="WdatePicker({minDate:'%y-%M-{%d+0}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
              </div>
              <div class="form-group">
                <textarea name="Remarks" id="Remarks"  class="form-control" placeholder='特价备注'></textarea>
              </div>
              <input type="hidden" name="ID" id="HID" value="">
              <button class="btn btn-primary btn-outline btn-md">保 存</button>
            </form>

          </div>
          <div class="ibox-content">
           <div class="alert alert-warning" style="position:absolute;right:0px;top:0px;">
             1、如需修改特价设置，请先关闭特价，然后修改开启<br>
             2、特价说明会显示在前台特价商品页<br>
             3、如下列商品出现红色框说明特价商品已过期
           </div>

           <table class="table table-bordered table-hover">
            <thead>
             <tr>
              <th>商品名称/规格</th>
              <th>原价</th>
              <th>特价</th>
              <th>开始时间</th>
              <th>结束时间</th>
              <th>特价说明</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
           <?php if(is_array($prodata)): foreach($prodata as $key=>$pro): ?><tr>
              <td><?php echo ($pro["ProName"]); ?></td>
              <td><?php echo ($pro["Price"]); ?></td>
              <td><?php echo ($pro["sprice"]); ?></td>
              <td><?php echo ($pro["stime"]); ?></td>
              <td><?php echo ($pro["etime"]); ?></td>
              <td><?php echo ($pro["Remarks"]); ?></td>
              <td><button class="btn btn-warning btn-xs edit" data-id='<?php echo ($pro["ID"]); ?>'>编辑</button>&emsp;<button class="btn btn-danger btn-xs delete" data-id='<?php echo ($pro["ID"]); ?>'>删除</button></td>
            </tr><?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div style="text-align:center;"><?php echo ($page); ?></div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
var jsondata=<?php echo ($jsondata); ?>;
var hasPro=<?php echo ($hasPro); ?>;
$(document).ready(function(){
  $('#pros').chosen();
  $('#pros').change(function(){
    var pid=$(this).val();
    $.each(hasPro,function(index,item){
      if (pid==item) {
        $('#pros').val('');
        $('#pros').trigger('chosen:updated');
        art.dialog.tips('该商品已设置限时特价');
      };
    })
  })
  $('#saveprice').submit(function(){
    var ProId=$('#pros').val();
    var Sprice=$('#Sprice').val();
    var Stime=$('#Stime').val();
    var Etime=$('#Etime').val();
    console.log(ProId,Sprice,Stime,Etime);
    if (ProId && ProId!='-1' && Sprice && Stime && Etime) {
      if (Stime>Etime) {
        art.dialog.tips('非法时间段',2);
        return false;
      }else{
        art.dialog.tips('处理中...',3);
        return true;
      }
    }else{
      art.dialog.tips('请完善商品特价信息');
      return false;
    }
  })

  $(document).on('click','.edit',function(){
    var ID=$(this).attr('data-id');
    $.each(jsondata,function(index,item){
      if (item.ID==ID) {
        console.log(item);
        $("#pros").val(item.ProIdCard);//设置值
        $('#pros').trigger('chosen:updated');//更新选项
        $('#Sprice').val(item.sprice);
        $('#Stime').val(item.stime);
        $('#Etime').val(item.etime);
        $('#Remarks').val(item.Remarks);
        $('#HID').val(ID);
      };
    })
  })
  $(document).on('click','.delete',function(){
    var _this=$(this);
    var ID=_this.attr('data-id');
    art.dialog.confirm('确定要删除此特价商品吗？',function(){
        art.dialog.tips('处理中...');
        $.ajax({
          url:"<?php echo U('Products/delsprice');?>",
          type:"post",
          data:"ID="+ID,
          dataType:"json",
          success:function(msg){
            if (msg.status=='success') {
              art.dialog.tips('删除成功');
              _this.parent().parent().remove();
            }else{
              art.dialog.tips('处理失败');
            }
          }
        })
    })
  })
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>