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
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<script type="text/javascript" src="/Public/Admin/diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="/Public/Admin/diyUpload/js/diyUpload.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="/Public/Admin/diyUpload/css/diyUpload.css">

<style type="text/css">
  .form-control1 {
    display: block;
    width: 100%;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  }
  #notices{
    color: red;
    font-weight: bold;
    font-size: 1.2em;
  }
  .tice{
    color:red;
  }
  #box{ width:100%; min-height:400px; background:#FF9}

</style>
</head><body><script>NProgress.start();</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header" style="text-align:center;padding-top:15px;"><div style="text-align:center;font-weight:bold;color:white;font-size:15px;"><?php echo (session('Sname')); ?></div><div class="dropdown profile-element"><span><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Index/index');?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='商品管理' <?php if(CONTROLLER_NAME == 'Products'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Products/proadd');?>">商品添加</a></li><li><a href="<?php echo U('Products/index');?>">商品管理</a></li><li><a href="<?php echo U('Products/attributes');?>">商品属性管理</a></li><li><a href="<?php echo U('Products/discount');?>">优惠设置</a></li><li><a href="<?php echo U('Products/discountpart');?>">组合优惠</a></li><li><a href="<?php echo U('Products/coupons');?>">优惠券管理</a></li></ul></li><li title='仓库管理' <?php if(CONTROLLER_NAME == 'Invoicing'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Invoicing/inwarehouselist');?>">入库单查询</a></li><li><a href="<?php echo U('Invoicing/inwarehouse');?>">入库单管理</a></li><li><a href="<?php echo U('Invoicing/outwarehouselist');?>">出库单查询</a></li><li><a href="<?php echo U('Invoicing/outwarehouse');?>">出库单管理</a></li><li><a href="<?php echo U('Invoicing/inventorylist');?>">盘点单查询</a></li><li><a href="<?php echo U('Invoicing/inventory');?>">库存盘点</a></li><li><a href="<?php echo U('Invoicing/index');?>">库存查询</a></li><li><a href="<?php echo U('Invoicing/supplierList');?>">供应商库存查询</a></li></ul></li><li title='订单管理' <?php if(CONTROLLER_NAME == 'Order'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Order/index');?>">订单概况</a></li><li><a href="<?php echo U('Order/allOrder');?>">全部订单</a></li></ul></li><li title='员工管理' <?php if(CONTROLLER_NAME == 'Admin'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Admin/add');?>">添加员工</a></li><li><a href="<?php echo U('Admin/index');?>">员工管理</a></li><li><a href="<?php echo U('Auth/group');?>">用户组管理</a></li></ul></li><li title='数据统计' <?php if(CONTROLLER_NAME == 'Statcenter'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Statcenter/Cancels');?>">支付核销员管理</a></li><li><a href="<?php echo U('Statcenter/getcancels');?>">提货核销员管理</a></li><li><a href="<?php echo U('Statcenter/PayType');?>">付款方式统计</a></li><li><a href="<?php echo U('Statcenter/poscash');?>">POS收银统计</a></li><li><a href="<?php echo U('Statcenter/posemp');?>">收银员数据统计</a></li></ul></li><li title='前台收银' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Cashier');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">前台收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Cashier/Index');?>">前台收银</a></li><li><a href="<?php echo U('Warehouse/ScanPay');?>">收银台</a></li></ul></li><li title='门店管理' <?php if(CONTROLLER_NAME == ''): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('Store/index');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a></li><li title='商户结算' <?php if(CONTROLLER_NAME == 'Stores'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U('');?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">商户结算</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U('Stores/index');?>">商户结算</a></li></ul></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>


<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>商品添加</h2>
    <ol class="breadcrumb">
      <li>
        <a href="index.html">主页</a>
      </li>
      <li class="active">
        <strong>商品添加</strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">

  </div>
</div>
<div class="row">
  <div class="panel blank-panel">
    <form method="post" class="form-horizontal" action="<?php echo U('Products/savePro');?>" id="savePro">

      <div class="panel-heading">
        <div class="panel-title m-b-md">
        </div>
        <div class="panel-options">

          <ul class="nav nav-tabs" style="text-align:center;">
            <li class="active" id="t0"><a data-toggle="tab" href="proadd.html#tab-1" aria-expanded="true">分类属性</a>
            </li>
            <li class="" id="t1"><a  href="proadd.html#tab-2" id="bbinfo" >基本信息</a>
            </li>
            <li class="" id="t2"><a  href="proadd.html#tab-3" id="xxinfo" >详情信息</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="panel-body">

        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="ibox-content" style="position:relative;">
              <div class="form-group">
                <label class="col-lg-2" style="text-align:right;">选择分类 <span class="tice"><b>*</b></span></label>
                <div class="input-group m-b col-lg-3 col-md-4">
                  <select data-placeholder="请选择分类" class="chosen-select" name="ClassType1" id="chose" style="width:350px;" tabindex="2" >
                    <option value="">请选择分类</option>
                    <?php if(is_array($oclass)): foreach($oclass as $key=>$oc): ?><option value="<?php echo ($oc["ClassId"]); ?>" hassubinfo="true"><?php echo ($oc["ClassName"]); ?></option><?php endforeach; endif; ?>
                  </select>
                </div>
                <label class="col-lg-2" style="text-align:right;">选择子分类 </label>
                <div class="input-group m-b col-lg-3">
                  <select data-placeholder="请选择子分类" class="" name="ClassType" id="sonClass" style="width:350px;" tabindex="2">


                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2" style="text-align:right;">商品属性 <span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <div class="form-group" id="valuearea">

                  </div>
                  <div class="input-group m-b col-lg-3 col-md-4" id="chose-attr" value="1">
                    <select data-placeholder="请选择分类" class="chosen-select" id="chose-attrid" onchange="getAttrbute();" style="width:150px;" tabindex="2" >
                      <option value="">选择属性</option>
                      <?php if(is_array($attributes)): foreach($attributes as $key=>$attr): ?><option value="<?php echo ($attr["AttributeId"]); ?>" id="name<?php echo ($attr["AttributeId"]); ?>" hassubinfo="true"><?php echo ($attr["AttributeName"]); ?></option><?php endforeach; endif; ?>
                    </select>
                    <hr>
                  </div>
                  <div class="input-group m-b">
                    <button type="button" class="btn btn-outline btn-primary btn-md" id="add" onclick="setAttr();">添加属性</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-outline btn-primary btn-md" id="yes" onclick="sayyes();">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-warning btn-md btn-outline" onclick="clears();" type="button">清空已选属性</button>
                  </div>
                </div>
                <div style="border:0px solid orange;" class="input-group col-sm-8 col-sm-offset-2 col-lg-offset-2 col-lg-7" id="table">

                </div>
              </div>
              <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                <div class="panel">
                  <div class="panel-heading">
                    <i class="fa fa-warning"></i> 提示信息
                  </div>
                  <div class="panel-body">
                    <div class="alert alert-warning">
                      1、分类选择请选择到具体的子分类<br>
                      2、商品属性确定后，只可以增加，不允许删除<br>
                      <b><span style="color:red;">*</span>标记的为必填项、其他为选填项</b>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                  <button class="btn btn-primary btn outline" id="next0" type="button">下一步</button>
                </div>
              </div>
            </div>
          </div>


          <div id="tab-2" class="tab-pane">
            <div class="ibox-content" style="position:relative;">

              <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                <div class="panel">
                  <div class="panel-heading">
                    <i class="fa fa-warning"></i> 提示信息
                  </div>
                  <div class="panel-body">
                    <div class="alert alert-warning">
                      1、商品名称，标题为必填项<br>
                      2、提成按百分比计算，填写5即代表5%，请不要再输入%符号<br>
                      <b><span style="color:red;">*</span>标记的为必填项、其他为选填项</b>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group" >
                <label class="col-sm-2 control-label">商品价格 <span class="tice"><b>*</b></span></label>

                <div class="col-sm-4">
                  <input type="text" class="form-control" name="oldprice">
                </div>
              </div>
              <div class="form-group" >
                <label class="col-sm-2 control-label">出售价格 <span class="tice"><b>*</b></span></label>

                <div class="col-sm-4">
                  <input type="text" class="form-control" name="price">
                </div>
              </div>

              <div class="form-group" >
                <label class="col-sm-2 control-label">商品编号</label>

                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ProNumber">
                </div>
              </div>
              <div class="form-group" >
                <label class="col-sm-2 control-label">商品条码</label>

                <div class="col-sm-4">
                  <input type="text" class="form-control" name="Barcode">
                </div>
                <div class="col-sm-2"><button class="btn btn-xs btn-default btn-outline" type="button" id="addimport">添加导入信息</button></div>
              </div>
              <div class="form-group" id="imports" style="display:none;">
                <label class="col-sm-2 control-label">关联导入信息</label>

                <div class="col-sm-4">
                  <textarea name="imports" rows="2" cols="5" class="form-control" placeholder="多个ID请以 '/' 隔开"></textarea>
                </div>
                <div class="col-sm-2"><small>关联导入订单商品信息</small></div>
              </div>
              <div class="form-group" id="ProName" >
                <label class="col-sm-2 control-label">商品名称 <span class="tice"><b>*</b></span></label>

                <div class="col-sm-4">
                  <input type="text" class="form-control" id="ProName_c" name="ProName" value="">
                </div>
              </div>
              <div class="form-group" id="ProTitle">
                <label class="col-sm-2 control-label">商品标题 <span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="ProTitle_c" name="ProTitle"> <span class="help-block m-b-none"></span>
                </div>
              </div>
              <div class="form-group" id="ProSubtitle">
                <label class="col-sm-2 control-label">商品说明/副标题 </label>

                <div class="col-sm-6">
                  <textarea name="ProSubtitle" id="ProSubtitle_c" cols="30" rows="3" class="form-control"></textarea>
                </div>
              </div>
              <div class="form-group" id="ProSubtitle">
                <label class="col-sm-2 control-label">商品重量/单位(g) <span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <input type="number" name="Weight" class="form-control" id="Weight" ><span class="input-group-addon">g</span>
                  </div>
                </div>
              </div>
              <!--                         <div class="form-group" id="FreightRemarks">
              <label class="col-sm-2 control-label">运费说明</label>

              <div class="col-sm-6">
              <textarea name="FreightRemarks" id="FreightRemarks_c" cols="30" rows="4" class="form-control"></textarea>
            </div>
          </div>
        -->                        <div class="form-group" id="Remarks">
        <label class="col-lg-2 control-label">备注说明</label>

        <div class="col-lg-6">
          <textarea name="Remarks" id="Remarks_c" cols="30" rows="4" class="form-control"></textarea>
        </div>
      </div>
      <div class="form-group" id="KeyWord">
        <label class="col-lg-2 control-label">检索关键字</label>

        <div class="col-lg-6">
          <input type="text" name="KeyWord" class="form-control" id="KeyWord_c">
        </div>
      </div>
      <div class="form-group" id="">
        <label class="col-lg-2 control-label">员工提成</label>

        <div class="col-lg-6">
          <div class="input-group">
            <input type="number" name="EmpCut" class="form-control" id="m1" onkeyup="checklang('m1');"><span class="input-group-addon">%</span>
          </div>
        </div>
      </div>
      <div class="form-group" id="">
        <label class="col-lg-2 control-label">一级提成</label>

        <div class="col-lg-6">
          <div class="input-group">
            <input type="number" name="Cut" class="form-control" id="c1" onkeyup="checklang('c1');"><span class="input-group-addon">%</span>
          </div>
        </div>
      </div>
      <div class="form-group" id="">
      <label class="col-lg-2 control-label">推广佣金</label>

        <div class="col-lg-6">
          <div class="input-group">
            <input type="number" name="ExtendCut" class="form-control" id="c2" onkeyup="checklang('c2');"><span class="input-group-addon">%</span>
          </div>
        </div>
      </div>
<!--
<div class="form-group" id="">
<label class="col-lg-2 control-label">三级提成</label>

<div class="col-lg-6">
<div class="input-group">
<input type="number" name="Cut3" class="form-control" id="c3" onkeyup="checklang('c3');"><span class="input-group-addon">%</span>
</div>
</div>
</div>
-->                        
<div class="form-group">
  <label class="col-lg-2 control-label">是否使用优惠券</label>

  <div class="col-lg-10">
    <div class="radio">
      <label><input type="radio" name="IsUseConpon" id="" value="1" class="optionsRadios">是</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="IsUseConpon" id="" value="0" checked="checked" class="optionsRadios">否</label>
    </div>

  </div>
</div>
<div class="form-group">
  <label class="col-lg-2 control-label">是否为赠品</label>

  <div class="col-lg-10">
    <div class="radio">
      <label><input type="radio" name="Iszp" id="" value="1" class="optionsRadios">是</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="Iszp" id="" value="0" checked="checked" class="optionsRadios">否</label>
    </div>

  </div>
</div>

<div class="form-group">
  <label class="col-lg-2 control-label">是否使用积分兑换</label>

  <div class="col-lg-10">
    <div class="radio">
      <label><input type="radio" name="IsUseScore" id="IsUseScore" data-s="" value="1" class="optionsRadios">是</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="IsUseScore" id="NOUseScore" value="0" checked="checked" class="optionsRadios">否</label>
    </div>

  </div>
</div>
<div class="form-group" id="">
  <label class="col-lg-2 control-label">兑换所需积分数</label>
  <div class="col-lg-6">
    <div class="input-group">
    <input type="number" name="Score" id="Score" class="form-control"><span class="input-group-addon">分</span>
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-lg-2 control-label">是否立即上架</label>

  <div class="col-lg-10">
    <div class="radio">
      <label><input type="radio" name="IsShelves" id="" value="1" checked="checked" class="optionsRadios">是</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="IsShelves" id="" value="0" class="optionsRadios">否 <!-- 定时上架：<input id="d11" type="text" name="ShelvesDate" onClick="WdatePicker()" placeholder="点击选择日期"/> --></label>
    </div>

  </div>
</div>
<div class="form-group">
  <div class="col-sm-4 col-sm-offset-2">
    <button class="btn btn-primary btn outline" id="next" type="button">下一步</button>
  </div>
</div>
</div>
</div>

<div id="tab-3" class="tab-pane" style="display:none;">
  <div class="ibox-content"  style="position:relative;">
    <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
      <div class="panel">
        <div class="panel-heading">
          <i class="fa fa-warning"></i> 提示信息
        </div>
        <div class="panel-body">
          <div class="alert alert-warning">
            1、商品主图图请上传大小为200*200的图片，以免影响显示效果<br>
            2、商品展示图请上传宽高比例为16：9的图片  分辨率640*360最佳<br>
            3、商品展示图最多显示6张
          </div>
        </div>
      </div>
    </div>
    <div class="form-group has-error" id="logoimg_c">
      <label class="col-sm-2 control-label">商品主图 <span class="tice"><b>*</b></span></label>

      <div class="col-sm-6">
        <div class="input-group m-b">
          <input type="text" class="form-control" name="ProLogoImg" id="logoimg" value=""> <span class="input-group-addon"><a href="###" onclick="upimg('logoimg')">上传</a></span><span class="input-group-addon"><a href="###"  id="logoimg_y">预览 </a> </span>
        </div>
      </div>
    </div>
    <div class="form-group has-warning" id="img1_c">
      <label class="col-sm-2 control-label">商品展示图 <span class="tice"><b>*</b></span></label>

      <div class="col-sm-6">
        <div id="box">
          <div id="test" ></div>
        </div>
      </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
      <label class="col-sm-2 control-label">商品详情</label>
      <div class="col-sm-8">
        <textarea name="ProContent" class="form-control1" id="editor" style="width:100%;height:600px;"></textarea>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-2">
        <button class="btn btn-primary" type="submit" id="savepost">保存</button>
      </div>
    </div>
  </div>

</div>
</div>

</div>

</div>
</form>

</div>




<script type="text/javascript">
  var ue = UE.getEditor('editor');

  $(document).ready(function(){
    $('#NOUseScore').click(function(){$("#IsUseScore").attr('data-s','0')})
    $('#IsUseScore').click(function(){$("#IsUseScore").attr('data-s','1')})

    $('#test').diyUpload({
      url:'<?php echo U('Upimg/saveimg');?>',
      success:function( data ) {
        console.log( data );
        $('<input type="hidden" class="tempClass" name="imgs[]" value="'+data+'" />').appendTo("#img1_c");
      },
      error:function( err ) {
        console.log( err );
      }
    });
    $("#savePro").submit(function(){
    // var ProAttr=$("price[]").val();
    var ClassType=$("#chose").val();
    var ProName=$('#ProName_c').val();
    var ProTitle=$("#ProTitle_c").val();
    var imgs=$('.tempClass').val();
    var logoimg=$("#logoimg").val();
    var IsUseScore=$("#IsUseScore").attr('data-s');
    var score=$("#Score").val();

    if ($("#isattr").val()!='yes') {
      art.dialog.alert('请选择商品属性');
      return false;
    };
    if (!ClassType) {
      art.dialog.alert('请选择商品所属分类');
      return false;
    };
    if (!ProName) {
      art.dialog.alert('请填写商品名称');
      return false;
    };
    if (!ProTitle) {
      art.dialog.alert('请填写商品标题');
      return false;
    };
    if (IsUseScore=='1') {
      if (!score) {
        art.dialog.alert('请填写兑换所需积分数');
        return false;
      };
    };
    if (!imgs) {
      art.dialog.alert('请上传商品展示图');
      return false;
    }
    if(!logoimg) {
      art.dialog.alert('请上传商品LOGO图');
      return false;
    }else{
      art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
      return true;
    }
  })
$("#addimport").click(function(){$("#imports").show();});
$("#next0").click(function(){
  var ClassType=$("#chose").val();
  var classs=$("#sonClass").val();
  if (!ClassType) {
    art.dialog.alert('请选择商品所属分类');
    return false;
  };
  if ($("#isattr").val()!='yes') {
    art.dialog.alert('请选择商品属性');
    return false;
  }else{
    $("#tab-3").attr('style','display:none');
    $("#tab-2").attr('style','display:block');
    $("#tab-1").attr('style','display:none');
    $("#t0").attr('class','');
    $("#t1").attr('class','active');
    $("#t2").attr('class','');
  }

})
$("#next").click(function(){
  var ProName=$('#ProName_c').val();
  var ProTitle=$("#ProTitle_c").val();
  var Weight=$("#Weight").val();
  var IsUseScore=$("#IsUseScore").attr('data-s');
  var score=$("#Score").val();
    // console.log(classs);
    // console.log(ClassType);
    if (!ProName) {
      art.dialog.alert('请填写商品名称');
      return false;
    };
    if (!ProTitle) {
      art.dialog.alert('请填写商品标题');
      return false;
    }
    if (IsUseScore=='1') {
      if (!score) {
        art.dialog.alert('请填写兑换所需积分数');
        return false;
      };
    };
    if (!Weight) {
      art.dialog.alert('请填写商品重量');
      return false;
    }else{
      $("#tab-3").attr('style','display:block');
      $("#tab-2").attr('style','display:none');
      $("#tab-1").attr('style','display:none');
      $("#t0").attr('class','');
      $("#t1").attr('class','');
      $("#t2").attr('class','active');
    }

  })
$("#t0").click(function(){
  $("#tab-3").attr('style','display:none;');
  $("#tab-2").attr('style','display:none;');
  $("#tab-1").attr('style','display:block;');
  $("#t0").attr('class','active');
  $("#t1").attr('class','');
  $("#t2").attr('class','');
})
$("#t1").click(function(){
  art.dialog.tips('请选择分类属性后点击下一步');
})
$("#t2").click(function(){
  art.dialog.tips('请填写基本信息后点击下一步');
})

})




var config = {
  '#sonClass':{},
  '.chosen-select': {},
  '.chosen-select-deselect': {
    allow_single_deselect: true
  },
  '.chosen-select-no-single': {
    disable_search_threshold: 10
  },
  '.chosen-select-no-results': {
    no_results_text: 'Oops, nothing found!'
  },
  '.chosen-select-width': {
    width: "95%"
  }
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}

</script>
<script type="text/javascript">

  $(function() {
    var data=<?php echo ($allClass); ?>;
  //初始化方法，初始化chosen控件和数据
  var init = function() {
    var option = [];
    $.each(data,
      function(index, item) {
        var province = item.province;
        option.push('<option value="'+item.id+'">',province,'</option>');
      });
    option = option.join('');
    $('#chose').html(option);
    $("#chose").chosen({
      disable_search_threshold: 5,
      no_results_text: "Oops, nothing found!",
      width: "100PX",
      enable_split_word_search: false,
      placeholder_text_single: '请选择'
    });
    $("#sonClass").chosen({
      disable_search_threshold: 5,
      no_results_text: "Oops, nothing found!",
      width: "100PX",
      enable_split_word_search: false,
      placeholder_text_single: '请选择'
    });
  }
    //执行init方法
    init();
    //省份变动触发事件
    $('#chose').change(function(){
      var province = $(this).val();
      var citys = null;
      $.each(data,function(index,item){
        if(item.id == province){
          citys = item.city;
        }
      });
      var option = [];
      $.each(citys,function(index,item){
        option.push('<option value="'+item.id+'">',item.cityname,'</option>');
      });
      $('#sonClass').html(option.join(''));
      $('#sonClass').trigger('chosen:updated');
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#logoimg_y").click(function(){
      art.dialog({title:'图片预览',content:'<img src="'+$('#logoimg').val()+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
    })
    $("#mianimg_y").click(function(){
      art.dialog({title:'图片预览',content:'<img src="'+$('#mainimg').val()+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
    })
    $("#saveimg").click(function(){
      art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
    })

  })
  function upimg(id){
    art.dialog.data('homeDemoPath', "<?php echo U('Product/proadd');?>");
    art.dialog.data('domid',id);
    // art.dialog.data('width','500px;');
    // 此时 iframeA.html 页面可以使用 art.dialog.data('test') 获取到数据，如：
    // document.getElementById('aInput').value = art.dialog.data('test');
    art.dialog.open('<?php echo U('Upimg/index');?>');
  };

  function setAttrs(){
    art.dialog.data('proid',$("#ProId_c").val());
    art.dialog.data('attrid','attributes');
    art.dialog.open('<?php echo U('artDialog/setAttr');?>',{width:600,height:400});
  }

  function setAttr(){
    var lang=$(".varea");
    if (lang.length<3) {
      $("#chose-attr").show();
      $('#chose-attr .chosen-single').html('<span>选择属性</span>');
    };
  }



  function getAttrbute(){
    var allAttr=<?php echo ($attrdata); ?>;
    var AttributeId=$("#chose-attrid").val();
    var AttributeName=$("#name"+AttributeId).text();
    $.each(allAttr,function(index,item){
      if (AttributeId==item.AttributeId) {
        var mainId=$(".varea").length;
        var html='<div class="col-sm-10  varea"  value="'+AttributeName+'" style="margin-bottom:10px;border-bottom:1px solid #aaa;padding-bottom:5px;"><span style="font-size:1.5em;" id="an'+mainId+'">'+AttributeName+'_'+AttributeId+'</span> <br/><br/> ';
        for (var i in item.values) {
          html+='<label class="checkbox-inline dady" value="'+AttributeName+'" ><input type="checkbox" name="values'+mainId+'" /*onclick="noticer($(this).val(),$(this).parent().attr(\'value\'),$(this).parents(\'.varea\').attr(\'id\'));" */class="values" value="'+item.values[i].AttributeValueId+'" id="AttributeName'+item.values[i].AttributeValueId+'"><span class="attrvalues" id="txt'+item.values[i].AttributeValueId+'">'+item.values[i].AttributeValue+'</span></label>';
        };
        html+='</div>';
        $(html).appendTo("#valuearea");
        var lang=$(".varea");
        if (lang.length>2) {
          $("#add").hide();
          $("#yes").show();
        };
        $("#chose-attr").hide();
        $("#chose-attrid").chosen();
      };
    })
}


function sayyes(){
  var  langs=$(".varea").length;
  $("#table").html('');
  $('<span id="notices"></span>').appendTo($("#table"));
  var table=$("<table class='table table-bordered'></table>");
  table.appendTo($("#table"));
  var thead=$("<thead></thead>");
  thead.appendTo(table);
  var trhead = $("<tr></tr>");
  trhead.appendTo(thead);
  var an1=$("#an0").text();
  var an2=$("#an1").text();
  var an3=$("#an2").text();
  if (langs==1) {
    $("<td>"+an1+"</td><input type=\"hidden\" name=\"atname1\" value=\""+an1+"\">").appendTo(trhead);
  };
  if (langs==2) {
    $("<td>"+an1+"</td><input type=\"hidden\" name=\"atname1\" value=\""+an1+"\"><td>"+an2+"</td><input type=\"hidden\" name=\"atname2\" value=\""+an2+"\">").appendTo(trhead);
  };
  if (langs==3) {
    $("<td>"+an1+"</td><input type=\"hidden\" name=\"atname1\" value=\""+an1+"\"><td>"+an2+"</td><input type=\"hidden\" name=\"atname2\" value=\""+an2+"\"><td>"+an3+"</td><input type=\"hidden\" name=\"atname3\" value=\""+an3+"\">").appendTo(trhead);
  };
  var itemColumHead = $(" <td>编号</td>");
  itemColumHead.appendTo(trhead);
  var tbody = $("<tbody></tbody>");
  tbody.appendTo(table);
  var atrvls=false;
  if (langs==1) {
    var atids=new Array();
    var atnames=new Array();
    var values1=document.getElementsByName('values0');
    for (var i = 0; i < values1.length; i++) {
      if (values1[i].checked==true) {
        atrvls=true;
        atids.push("_"+values1[i].value);
        atnames.push($("#txt"+values1[i].value).text());
        var trtbody=$("<tr></tr>");
        trtbody.appendTo(tbody);
        $("<td>"+$("#txt"+values1[i].value).text()+"</td>").appendTo(trtbody);
        $("<td><input type='text' onblur='check($(this).val());' name='num[]' /></td><input type='hidden' name='datastr[]' value='"+values1[i].value+"|"+$("#txt"+values1[i].value).text()+"' /> <input type='hidden' name='atids' value='"+atids+"'><input type='hidden' name='atnames' value='"+atnames+"'>").appendTo(trtbody);
      };
    };
    $("#chose-attrid").chosen();
  };
  if (langs==2) {
    var atids=new Array();
    var atnames=new Array();
    var atids1=new Array();
    var atnames1=new Array();
    var values1=document.getElementsByName('values0');
    var values2=document.getElementsByName('values1');
    for (var i = 0; i < values1.length; i++) {
      if (values1[i].checked==true) {
        atrvls=true;
        atids.push("_"+values1[i].value);
        atnames.push($("#txt"+values1[i].value).text());
        for (var j = 0; j < values2.length; j++) {
          if (values2[j].checked==true) {
            atids1.push("_"+values2[j].value);
            atnames1.push($("#txt"+values2[j].value).text());
            var trtbody=$("<tr></tr>");
            trtbody.appendTo(tbody);
            $("<td>"+$("#txt"+values1[i].value).text()+"</td><td>"+$("#txt"+values2[j].value).text()+"</td>").appendTo(trtbody);
            $("<td><input type='text'  onblur='check($(this).val());' name='num[]' /></td><input type='hidden' name='datastr[]' value='"+values1[i].value+"_"+values2[j].value+"|"+$("#txt"+values1[i].value).text()+"|"+$("#txt"+values2[j].value).text()+"' /> <input type='hidden' name='atids' value='"+atids+"'><input type='hidden' name='atids1' value='"+atids1+"'><input type='hidden' name='atnames' value='"+atnames+"'><input type='hidden' name='atnames1' value='"+atnames1+"'>").appendTo(trtbody);
          };
        };
      };
    };
    $("#chose-attrid").chosen();
  };
  if (langs==3) {
    var atids=new Array();
    var atnames=new Array();
    var atids1=new Array();
    var atnames1=new Array();
    var atids2=new Array();
    var atnames2=new Array();
    var values1=document.getElementsByName('values0');
    var values2=document.getElementsByName('values1');
    var values3=document.getElementsByName('values2');
    for (var i = 0; i < values1.length; i++) {
      if (values1[i].checked==true) {
        atrvls=true;
        atids.push("_"+values1[i].value);
        atnames.push($("#txt"+values1[i].value).text());
        for (var j = 0; j < values2.length; j++) {
          if (values2[j].checked==true) {
            atids1.push("_"+values2[j].value);
            atnames1.push($("#txt"+values2[j].value).text());
            for (var k = 0; k < values3.length; k++) {
              if (values3[k].checked==true) {
                atids2.push("_"+values3[k].value);
                atnames2.push($("#txt"+values3[k].value).text());
                var trtbody=$("<tr></tr>");
                trtbody.appendTo(tbody);
                $("<td>"+$("#txt"+values1[i].value).text()+"</td><td>"+$("#txt"+values2[j].value).text()+"</td><td>"+$("#txt"+values3[k].value).text()+"</td>").appendTo(trtbody);
                $("</td><td><input type='text' onblur='check($(this).val());' name='num[]' /></td><input type='hidden' name='datastr[]' value='"+values1[i].value+"_"+values2[j].value+"_"+values3[k].value+"|"+$("#txt"+values1[i].value).text()+"|"+$("#txt"+values2[j].value).text()+"|"+$("#txt"+values3[k].value).text()+"' /> <input type='hidden' name='atids' value='"+atids+"'><input type='hidden' name='atids1' value='"+atids1+"'><input type='hidden' name='atids2' value='"+atids2+"'><input type='hidden' name='atnames' value='"+atnames+"'><input type='hidden' name='atnames1' value='"+atnames1+"'><input type='hidden' name='atnames2' value='"+atnames2+"'>").appendTo(trtbody);
              };
            };
          };
        };
      };
    };
    $("#chose-attrid").chosen();
  };
  if (atrvls==false) {
    art.dialog.alert('请选择属性值');
    $("#table").html('');
  }

}

function clears(){
  var lang=$("varea");
  if (lang.length<3) {
    $("#add").show();
  };
  $('#valuearea').html('');
  $("#table").html('');
  $("#chose-attrid").chosen();
}
function check(va){
  if (!va) {
    $("#notices").html('');
    $('#notices').html('提示信息：请完整填写此表中的内容');
  }else{
    $("#notices").html('');
    $("#notices").html('<input type="hidden" name="isattr" id="isattr" value="yes"/>')
  }
}

function checklang(id){
  var v=$("#"+id).val();
  if (v.length>2) {
    $("#"+id).val(v.substr(0,2));
    art.dialog.tips('非法数字',0.5);
  };
}
</script><script type="text/javascript" src="/Public/Admin/Admin/view/proadd.js"></script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>