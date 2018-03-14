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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<style type="text/css">
  body{
    background-color: #fff!important;
  }
</style>
<div class="row wrapper  white-bg" style="margin:0px 1%;">
    <div class="col-lg-12">
       <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>添加规则</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form role="form" class="form-inline" action="" method="post" id="save">
                <div class="form-group">
                        <label for="exampleInputEmail2" class="sr-only">优惠类型</label>
                        <select name="DiscountType" id="DiscountType" class="form-control" value="">
                           <option value="">请选择优惠类型</option>
                           <option value="0" id="c0">订单满减</option>
                           <option value="1" id="c1">消费返券</option>
                           <option value="2" id="c2">注册返券</option>
                           <option value="3" id="c3">充值满返</option>
                        </select>
                </div>
                <div class="form-group">
                    <input type="number" placeholder="请填写金额条件" name="Consume" id="Consume" class="form-control">
                </div>
                <div class="form-group">
                    <input type="number" placeholder="减免/返券 金额" name="Discount" id="Discount" class="form-control">
                </div>
                <div class="form-group cz" style="display:none;">
                    <input type="number" placeholder="赠送优惠券面额" name="CouponNotes" id="CouponNotes" class="form-control">
                </div>
                <div class="form-group cz" style="display:none;">
                    <input type="number" placeholder="赠送数量" name="CouponNum" id="CouponNum" class="form-control">
                </div>
                <div class="form-group">
                    <input type="text" name="stime" id="stime" placeholder="开始日期" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-{%d}'})" class="form-control">
                </div>

                <div class="form-group">
                    <input type="text" name="etime" id="etime" placeholder="结束日期" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-{%d+0}'})" class="form-control">
                </div>

                <div class="checkbox m-l m-r-xs">
                  <input type="hidden" name="id" id="ID" value="">
                  <input type="hidden" name="CouponId" id="CouponId" value="">
                  <button class="btn btn-white" type="submit" id="btn-submit">添加规则</button>
                    </form>
                </div>
                <div class="alert alert-warning">

                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h3>优惠管理</h3>
            <table class="table">
              <tr>
                 <td>#</td>
                 <td>优惠类型</td>
                 <td>优惠条件</td>
                 <td>优惠/返券金额</td>
                 <td>操作</td>
             </tr>
             <?php if(is_array($discounts)): foreach($discounts as $key=>$dis): ?><tr>
                 <td><?php echo ($dis["ID"]); ?></td>
                 <td>
                      <?php if($dis['DiscountType'] == '0'): ?>订单满减
                      <?php elseif($dis['DiscountType'] == '1'): ?>
                      消费返券
                      <?php elseif($dis['DiscountType'] == '2'): ?>
                      注册返券
                      <?php else: ?>
                      充值满返<?php endif; ?>
                    </td>
                 <td><?php echo ($dis["Consume"]); ?></td>
                 <td><?php echo ($dis["Discount"]); ?></td>
                 <td><a href="###" onclick="edit('<?php echo ($dis["ID"]); ?>');">修改</a> | <a href="###" onclick="del('<?php echo ($dis["ID"]); ?>');">删除</a></td>
             </tr><?php endforeach; endif; ?>
     </table>
     <div style="text-align:right;margin-bottom:100px;"><?php echo ($page); ?></div>
 </div>
</div>
</div>
<script type="text/javascript">
  var jsondata=<?php echo ($jsondata); ?>; //json数据
  //修改数据展示
  function edit(id){
    $.each(jsondata,function(index,item){
      if (item.ID==id) {
        $("#Consume").val(item.Consume);
        $("#Discount").val(item.Discount);
        $("#DiscountType").val(item.DiscountType);
        $("#stime").val(item.stime.date);
        $("#etime").val(item.etime.date);




        $("#ID").val(id);
        if (item.DiscountType=='1') {
          $("#CouponId").val(item.CouponId);
          $("#c1").attr('selected',true);
          $("#c2").attr('selected',false);
          $("#c0").attr('selected',false);
          $("#c3").attr('selected',false);
          $('.cz').hide();
        }
        else if (item.DiscountType=='2') {
          $("#CouponId").val(item.CouponId);
          $("#c2").attr('selected',true);
          $("#c0").attr('selected',false);
          $("#c1").attr('selected',false);
          $("#c3").attr('selected',false);
          $('.cz').hide();
        }
        else if (item.DiscountType=='3') {
          $("#CouponId").val(item.CouponId);
          $('#CouponNotes').val(item.CouponNotes);
          $('#CouponNum').val(item.CouponNum);
          $('.cz').show();
          $("#c2").attr('selected',false);
          $("#c0").attr('selected',false);
          $("#c1").attr('selected',false);
          $("#c3").attr('selected',true);
        }else
        {
          $('.cz').hide();
          $("#c0").attr('selected',true);
          $("#c1").attr('selected',false);
          $("#c2").attr('selected',false);
          $("#c3").attr('selected',false);
        }
        $("#btn-submit").html('保存规则')
        // console.log(item);
      }
    })
  }
  //删除提示
  function del(id){
    art.dialog.confirm('确定要删除此条优惠规则吗？',function(){
      window.location.href="<?php echo U('Products/delDiscount');?>?id="+id;
    },function(){
      art.dialog.tips('取消操作',1)
    })
  }
  $(document).ready(function(){
    $('#DiscountType').change(function(){
      var type=$(this).val();
      if (type=='3') {
        $('.cz').show();
      }else{
        $('.cz').hide();
      }
    })
    $('#save').submit(function(){
      var type=$('#DiscountType').val();
      var Consume=$('#Consume').val();
      var Discount=$('#Discount').val();
      var CouponNotes=$('#CouponNotes').val();
      var CouponNum=$('#CouponNum').val();
      var stime=$('#stime').val();
      var etime=$('#etime').val();
      if (!type) {
        art.dialog.alert('请选择优惠类型');
        return false;
      };
      if (!Consume) {
        art.dialog.alert('请输入金额条件');
          $('#Consume').focus();
        return false;
      };
      if (!Discount) {
        art.dialog.alert('请输入减免/返券 金额');
          $('#Discount').focus();
        return false;
      };
      if (CouponNotes || CouponNum) {
        if (!CouponNotes) {
          art.dialog.alert('请输入返券面额');
          $('#CouponNotes').focus();
          return false;
        };
        if (!CouponNum) {
          art.dialog.alert('请输入返券数量');
          $('#CouponNum').focus();
          return false;
        };
      };
      if (stime && etime) {
        return true
      }else{
        art.dialog.alert('请选择有效时间范围');
        return false;
      }
    })
  })
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>