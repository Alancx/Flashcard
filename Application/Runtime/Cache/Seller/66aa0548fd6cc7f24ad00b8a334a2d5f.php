<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>蜂之巢-真心好蜜 全球共享</title>
    <meta name="keywords" content="蜂之巢-真心好蜜 全球共享">
    <meta name="description" content="蜂之巢">
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
</head><body><script>NProgress.start();</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header"><div class="dropdown profile-element"><span><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo ($_SESSION['userinfo']['userName']); ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Index/index');?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='商品管理' <?php if(CONTROLLER_NAME == 'Products'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Products/proadd');?>">商品添加</a></li><li><a href="<?php echo U('Products/index');?>">商品管理</a></li><li><a href="<?php echo U('Products/attributes');?>">商品属性管理</a></li><li><a href="<?php echo U('Products/yunfei');?>">运费模板管理</a></li></ul></li><li title='仓库管理' <?php if(CONTROLLER_NAME == 'Invoicing'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Invoicing/inwarehouselist');?>">入库单查询</a></li><li><a href="<?php echo U('Invoicing/inwarehouse');?>">入库单管理</a></li><li><a href="<?php echo U('Invoicing/outwarehouselist');?>">出库单查询</a></li><li><a href="<?php echo U('Invoicing/outwarehouse');?>">出库单管理</a></li><li><a href="<?php echo U('Invoicing/inventorylist');?>">盘点单查询</a></li><li><a href="<?php echo U('Invoicing/inventory');?>">库存盘点</a></li><li><a href="<?php echo U('Invoicing/index');?>">库存查询</a></li></ul></li><li title='订单管理' <?php if(CONTROLLER_NAME == 'Order'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Order/index');?>">订单概况</a></li><li><a href="<?php echo U('Order/allOrder');?>">全部订单</a></li></ul></li><li title='员工管理' <?php if(CONTROLLER_NAME == 'Admin'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Admin/add');?>">添加员工</a></li><li><a href="<?php echo U('Admin/index');?>">员工管理</a></li><li><a href="<?php echo U('Auth/group');?>">用户组管理</a></li></ul></li><li title='数据统计' <?php if(CONTROLLER_NAME == 'Statcenter'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Statcenter/Cancels');?>">支付核销员管理</a></li><li><a href="<?php echo U('Statcenter/getcancels');?>">提货核销员管理</a></li><li><a href="<?php echo U('Statcenter/PayType');?>">付款方式统计</a></li><li><a href="<?php echo U('Statcenter/poscash');?>">POS收银统计</a></li><li><a href="<?php echo U('Statcenter/posemp');?>">收银员数据统计</a></li></ul></li><li title='基本设置' <?php if(CONTROLLER_NAME == 'Warehouse'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cog"></i>&nbsp;&nbsp;<span class="nav-label">基本设置</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Warehouse/logistics');?>">物流设置</a></li></ul></li><li title='前台收银' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Cashier');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">前台收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Cashier/Index');?>">前台收银</a></li></ul></li><li title='门店管理' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Store/index');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>

<style type="text/css">
  .tice{
    color:red;
}
</style>
 <div class="row wrapper border-bottom white-bg page-heading">
     <div class="col-lg-10">
         <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>运费模板设置</h2>
         <ol class="breadcrumb">
             <li>
                 <a href="index.html">主页</a>
             </li>
             <li class="active">
                 <strong>运费模板设置</strong>
             </li>
         </ol>
     </div>
 <div class="col-lg-2"></div>
 </div>


                <div class="row  wrapper  white-bg" style="margin:0 1%;">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                            </div>
                            <div class="ibox-content">
                                                        <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <i class="fa fa-warning"></i> 提示信息
                                    </div>
                                    <div class="panel-body">
                                        <div class="alert alert-warning">
                                         1、计费区一与计费区二地区信息不能有相同<br>
                                         <b><span style="color:red;">*</span>标记的为必填项、其他为选填项</b>
                                     </div>
                                 </div>
                             </div>
                         </div>

                                <form method="post" action="<?php echo U('Products/addYF');?>" class="form-horizontal" id="save">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">模板名称<span class="tice"><b>*</b></span></label>

                                        <div class="col-sm-4">
                                            <input type="text" name="name" id="name" class="form-control" value="<?php echo ($info["Name"]); ?>" >
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">首重重量<span class="tice"><b>*</b></span></label>

                                        <div class="col-sm-4">
                                            <div class="input-group">
                                              <input type="number" name="FirstWeight" class="form-control" id="FirstWeight" value="<?php echo ($info["FirstWeight"]); ?>"><span class="input-group-addon">g</span>
                                          </div>
                                        </div>
                                        <div class="col-sm-2"><b>重量单位为g ，请填写整数 </b></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">续重重量<span class="tice"><b>*</b></span></label>

                                        <div class="col-sm-4">
                                            <div class="input-group">
                                              <input type="number" name="AddWeight" class="form-control" id="AddWeight" value="<?php echo ($info["AddWeight"]); ?>"><span class="input-group-addon">g</span>
                                          </div>
                                        </div>
                                        <div class="col-sm-2"><b>重量单位为g ，请填写整数 </b></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">计费区域一 首重价格<span class="tice"><b>*</b></span></label>
                                        <div class="col-sm-4">
                                            <input type="number" name="Opiece" id="Opiece" class="form-control" value="<?php echo ($info["Opiece"]); ?>"> <span class="help-block m-b-none"></span>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">计费区域一 续重加价<span class="tice"><b>*</b></span></label>
                                        <div class="col-sm-4">
                                            <input type="number" name="Oadd" id="Oadd" class="form-control" value="<?php echo ($info["Oadd"]); ?>"> <span class="help-block m-b-none"></span>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择地区<span class="tice"><b>*</b></span></label>
                                        <div class="col-sm-2"><label><input type="checkbox" name="" id="prov" onclick="provs(this.checked);">全选</label></div>
                                        <div class="col-sm-8 col-sm-offset-2">
                                           <?php $i=1 ?>
                                           <?php if(is_array($area)): foreach($area as $key=>$are): ?><div class="checkbox-inline pull-left"><label><input type="checkbox" onclick="pp(this.checked,this.id);" class="prov" name="province[]" value="<?php echo ($are); ?>" id="p<?php echo $i; ?>" <?php if (in_array($are,$area1)): ?>
                                             checked="checked"
                                           <?php endif ?>><?php echo ($are); ?></label></div>
                                           <?php $i++; endforeach; endif; ?>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">计费区域二 首重价格</label>

                                        <div class="col-sm-4">
                                            <input type="number" name="Tpiece" id="Tpiece" class="form-control" value="<?php echo ($info["Tpiece"]); ?>">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">计费区域二 续重加价</label>

                                        <div class="col-sm-4">
                                            <input type="number" name="Tadd" id="Tadd" class="form-control" value="<?php echo ($info["Tadd"]); ?>">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择地区</label>

                                        <div class="col-sm-2"><label><input type="checkbox" name="" id="prov1" onclick="provs1(this.checked);">全选</label></div>
                                        <div class="col-sm-8 col-sm-offset-2">
                                          <?php $j=1; ?>
                                            <?php if(is_array($area)): foreach($area as $key=>$ar): ?><div class="checkbox-inline pull-left"><label><input type="checkbox" onclick="ppp(this.checked,this.id);" class="prov1" name="province1[]" value="<?php echo ($ar); ?>" id="pp<?php echo $j; ?>" <?php if (in_array($ar,$area2)): ?>
                                             checked="checked"
                                           <?php endif ?>><?php echo ($ar); ?></label></div>
                                           <?php $j++; endforeach; endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">备注</label>

                                        <div class="col-sm-4">
                                            <textarea name="Remarks" id="Remarks" cols="30" rows="6" class="form-control"><?php echo ($info["Remarks"]); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <input type="hidden" name="ID" value="<?php echo ($info["ID"]); ?>">
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
  $("#save").submit(function(){
    var name=$("#name").val();
    var opiece=$("#Opiece").val();
    var oadd=$("#Oadd").val();
    var fw=$("#FirstWeight").val();
    var aw=$("#AddWeight").val();
    var pros=$(".prov");
    if (!name) {
      art.dialog.alert('请填写模板名称');
      return false;
    };
    if (!fw) {
      art.dialog.alert('请填写首重重量');
      return false;
    };
    if (!aw) {
      art.dialog.alert('请填写续重重量');
      return false;
    };
    if (!opiece) {
      art.dialog.alert('请填写计费区域一 首件价格');
      return false;
    };
    if (!oadd) {
      art.dialog.alert('请填写第二件加价');
      return false;
    };
    var statu=false;
    $.each(pros,function(index,item){
      if (item.checked) {
        statu=true;
      };
    })
    if (statu) {
      return true;
    }else{
      art.dialog.alert('请选择地区');
      return false;
    }
  })
})
  function provs(statu){
    if (statu==true) {
      var pr=$(".prov");
      var pst=true;
      $.each(pr,function(index,item){
        if (item.disabled) {
          pst=false;
        }
      })
        if (pst) {
          $('.prov').prop('checked',true);
          $('.prov1').prop('disabled',true);
          $('#prov1').prop('disabled',true);
        };
    };
    if (statu==false) {
      $('.prov').prop('checked',false);
      $('.prov1').prop('disabled',false);
      $("#prov1").prop('disabled',false);
    };
  }
  function provs1(statu){
    if (statu==true) {
      var ppr=$(".prov1");
      var ppst=true;
      $.each(ppr,function(index,item){
        if (item.disabled) {
          ppst=false;
        };
      })
      if (ppst) {
        $('.prov1').prop('checked',true);
        $('.prov').prop('disabled',true);
        $('#prov').prop('disabled',true);
      };
    };
    if (statu==false) {
      $('.prov1').prop('checked',false);
      $('.prov').prop('disabled',false);
      $("#prov").prop('disabled',false);
    };
  }

  function pp(statu,id){
    if (statu) {
      $("#prov").prop('checked',false);
      $("#prov").prop('disabled',true);
      $("#prov1").prop('checked',false);
      $("#prov1").prop('disabled',true);
      $("#p"+id).prop('checked',false);
      $("#p"+id).prop('disabled',true);
    };
    if (statu==false) {
      $("#p"+id).prop('disabled',false);
      var p1=$(".prov");
      var ss=true;
      $.each(p1,function(index,item){
        if (item.checked) {
          ss=false;
        };
      })
      if (ss) {
        $("#prov").prop('checked',false);
        $("#prov").prop('disabled',false);
        $("#prov1").prop('disabled',false);
      }else{
        $("#prov").prop('checked',false);
        $("#prov").prop('disabled',true);
      }
    };
  }
  function ppp(statu,id){
    cid=id.substr(1,3);
    if (statu) {
      $("#prov1").prop('checked',false);
      $("#prov1").prop('disabled',true);
      $("#prov").prop('checked',false);
      $("#prov").prop('disabled',true);
      $("#"+cid).prop('checked',false);
      $("#"+cid).prop('disabled',true);
    };
    if (statu==false) {
      $("#"+cid).prop('disabled',false);
      var p1=$(".prov1");
      var ss=true;
      $.each(p1,function(index,item){
        if (item.checked) {
          ss=false;
        };
      })
      if (ss) {
        $("#prov1").prop('checked',false);
        $("#prov1").prop('disabled',false);
        $("#prov").prop('disabled',false);
      }else{
        $("#prov1").prop('checked',false);
        $("#prov1").prop('disabled',true);
      }
    };
  }
</script>
 <div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>