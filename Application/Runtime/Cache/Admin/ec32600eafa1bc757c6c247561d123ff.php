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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<style type="text/css">
  .card{
    width: 200px;
    height: 110px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin:auto;
    position: relative;
    background: linear-gradient(to left,#fff,#999);
    box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
    color: black;
  }
  .card_title{
    text-align: center;
    font-size: 1.2em;
    height: 20px;
    margin-bottom: 15px;
    margin-top: 10px;
    color: black;
  }
  .card_rules{
    font-size: .6em;
    line-height: 32px;
    text-align: center;
    color: black;
  }
  .card_bot{
    position: absolute;
    width: 100%;
    left: 0px;
    bottom: 0px;
    height: 10px;    
    text-align: center;
    font-size: .8em;
    color: black;
  }
  .vip{
    position: absolute;
    right: 5px;
    top: 5px;
    color: #ccc;
    font-size: 1.5em;
  }
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <div class="alert alert-warning" style="color:red;">
          1、优惠券管理
        </div>
        <button class='btn btn-default addcoupon' >添加优惠券</button>
      </div>
      <div class="ibox-content">
        <div class='row'>
          <table class='table table-hover table-bordered'>
            <thead>
              <tr>
                <th>#</th>
                <th>卡片信息</th>
                <th>有效时间/数量信息</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($cards)): foreach($cards as $key=>$card): ?><tr>
                <td><?php echo ($card["CouponId"]); ?></td>
                <td>
                  <div class='card'>
                    <span class='vip'>VIP</span>
                    <div class='card_title'><?php echo ($card["CouponName"]); ?></div>
                    <div class='card_rules'>
                      <?php if($card['Type'] == '0'): ?>平台商品使用立减<?php echo ($card["Rules"]); ?>元<?php endif; ?>
                    </div>
                  </div>
                </td>
                <td><?php echo ($card["StartDate"]); ?>--<?php echo ($card["ExpiredDate"]); ?> <br> 发布量：<?php echo ($card["Count"]); ?> <br> 剩余量：<?php echo ($card["AfterCount"]); ?>  </td>
                <td><?php if($card['IsShow'] == '1'): ?><button class='btn btn-xs btn-warning disabled'>已发布</button> &emsp; <button class='btn btn-xs btn-danger delcoupon' data-cid='<?php echo ($card["CouponId"]); ?>' data-id="<?php echo ($card["ID"]); ?>">删除</button><?php else: ?><button class='btn btn-xs btn-primary showing' data-cid="<?php echo ($card["CouponId"]); ?>" data-id="<?php echo ($card["ID"]); ?>" title='发布后用户可以领取' alt='发布后用户可以领取'>发布</button>&emsp; <button class='btn btn-xs btn-warning editing' data-id='<?php echo ($card["ID"]); ?>'>编辑</button>&emsp;<button class='btn btn-xs btn-danger delcoupon' data-cid='<?php echo ($card["CouponId"]); ?>' data-id="<?php echo ($card["ID"]); ?>">删除</button><?php endif; ?></td>
              </tr><?php endforeach; endif; ?>
            </tbody>
          </table>
          <?php echo ($page); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var cardtype='';
  $(document).ready(function(){
    $('.addcoupon').click(function(){
      window.location.href="<?php echo U('Products/addCoupon');?>";
    })
    $('.showing').click(function(){
      var _this=$(this);
      var cid=_this.attr('data-cid');
      var id=_this.attr('data-id');
      art.dialog.confirm('确定要发布此优惠券吗？',function(){
        _this.addClass('disabled').html('处理中...');
        $.ajax({
          url:"<?php echo U('Products/setCoupon');?>",
          type:"post",
          data:"type=show&cid="+cid+'&id='+id,
          dataType:"json",
          success:function(msg){
            if (msg.status=='success') {
              art.dialog.tips('已发布');
              _this.parent().html("<button class='btn btn-xs btn-warning disabled'>已发布</button> &emsp; <button class='btn btn-xs btn-danger delcoupon' data-cid='"+cid+"' data-id='"+id+"'>删除</button>");
            }else{
              art.dialog.tips(msg.info);
              _this.removeClass('disabled').html('发布');
            }
          }
        })
      })
    })
    $(document).on('click','.delcoupon',function(){
      var _this=$(this);
      var cid=_this.attr('data-cid');
      var id=_this.attr('data-id');
      art.dialog.confirm('确定要删除此优惠券吗？',function(){
        _this.addClass('disabled').html('处理中...');
        $.ajax({
          url:"<?php echo U('Products/setCoupon');?>",
          type:"post",
          data:"type=del&cid="+cid+"&id="+id,
          dataType:"json",
          success:function(msg){
            if (msg.status=='success') {
              art.dialog.tips('删除成功');
              _this.parent().parent().remove();
            }else{
              art.dialog.tips(msg.info);
            }
          }
        })
      })
    })
    $('.editing').click(function(){
      window.location.href="<?php echo U('Products/addCoupon');?>?id="+$(this).attr('data-id');
    })



    })
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>