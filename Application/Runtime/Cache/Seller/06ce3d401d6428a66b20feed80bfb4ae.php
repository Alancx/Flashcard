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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.all.min.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<style type="text/css">
    .attrvalues{
        border: 1px solid green;
        margin:auto 2px;
        border-radius: 2px;
        padding-left: 2px;
        padding-right: 2px;
    }
    img{
        width: 100%;
        margin-bottom: 0px;

    }
    .img-box{
        width:150px;height:170px;border:1px solid #ccc;
        margin-top: 10px;
        margin-left: 15px;
    }
    .img-content{
        width:100%;height:150px;
    }
    .img-btn{
        width:100%;height:20px;text-align:center;
    }
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
       <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>商品管理</h2>
       <ol class="breadcrumb">
           <li>
               <a href="index.html">主页</a>
           </li>
           <li class="active">
               <strong>商品修改</strong>
           </li>
       </ol>
   </div>
   <div class="col-lg-2"></div>
</div>

<div class="row">
    <div class="panel blank-panel">
        <form method="post" class="form-horizontal" action="<?php echo U('Products/saveEdit');?>" id="savePro">
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>商品添加 <small>products</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content"  style="position:relative;">
                            <div class="form-group">
                                <label class="col-lg-2" style="text-align:right;">当前分类</label>
                                <div class="input-group m-b col-lg-3 col-md-4"><span id="hclasstype"><input type="hidden" id="ClassType" name="ClassType"  value="<?php echo ($proinfo["ClassType"]); ?>"></span>
                                    <?php echo ($nowClass); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button class="btn btn-primary btn-outline" type="button"  onclick="editClass();" >修改</button>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-2" style="text-align:right;">当前属性</label>
                                <div class="input-group m-b col-lg-6 col-md-6" id="table">

                                </div>
                                <div class="col-lg-offset-2 col-md-offset-2"><button class="btn btn-primary btn-outline" type="button" onclick="showTable();"><span id="btn-name">查看属性</span></button></div>

                            </div>
                            <div class="form-group" id="chose-class">
                                <label class="col-lg-2" style="text-align:right;">选择分类</label>
                                <div class="input-group m-b col-lg-3 col-md-4">
                                    <select data-placeholder="请选择分类" class="chosen-select" id="chose" style="width:350px;" tabindex="2" >
                                        <option value="">请选择分类</option>
                                        <?php if(is_array($oclass)): foreach($oclass as $key=>$oc): ?><option value="<?php echo ($oc["ClassId"]); ?>" hassubinfo="true"><?php echo ($oc["ClassName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <label class="col-lg-2" style="text-align:right;">选择子分类</label>
                                <div class="input-group m-b col-lg-3">
                                    <select data-placeholder="请选择子分类" class="" name="ClassType" id="sonClass" style="width:350px;" tabindex="2">
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <i class="fa fa-warning"></i> 提示信息
                                    </div>
                                    <div class="panel-body">
                                        <div class="alert alert-warning">
                                         1、如有新增属性信息，请勾选新增属性修改<br>
                                         2、如有新图片改动，请勾选展示图修改<br>
                                         3、LOGO图请选择200*200图片上传，以免影响显示效果<br>
                                         4、商品展示图请上传宽高比例为16：9的图片 分辨率640*360最佳<br>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="form-group" id="chose-attrs">
                            <label class="col-lg-2" style="text-align:right;">商品属性</label>
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
                                    <button type="button" class="btn btn-outline btn-primary btn-md" id="yes" onclick="sayyes();">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary btn-md btn-outline" onclick="clears();" type="button">清空已选属性</button>
                                </div>
                            </div>
                            <div style="border:0px solid orange;" class="input-group col-sm-8 col-sm-offset-2 col-lg-offset-2 col-lg-7"  id="table">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>

                            <div class="col-lg-10">
                                <div class="radio">
                                    <label><input type="radio" name="IsAddAttr" id="" value="1" class="optionsRadios">新增商品属性修改请勾选此项</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group" >
                          <label class="col-sm-2 control-label">商品价格 <span class="tice"><b>*</b></span></label>

                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="oldprice" value="<?php echo ($proinfo["Price"]); ?>">
                          </div>
                        </div>
                        <div class="form-group" >
                          <label class="col-sm-2 control-label">出售价格 <span class="tice"><b>*</b></span></label>

                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="price" value="<?php echo ($proinfo["PriceRange"]); ?>">
                          </div>
                        </div>

                         <div class="form-group" >
                            <label class="col-sm-2 control-label">商品编号</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ProNumber" value="<?php echo ($proinfo["ProNumber"]); ?>">
                            </div>
                        </div>
                         <div class="form-group" >
                            <label class="col-sm-2 control-label">商品条码</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="Barcode" value="<?php echo ($proinfo["Barcode"]); ?>">
                            </div>
                            <div class="col-sm-2"><button class="btn btn-xs btn-default btn-outline" type="button" id="addimport">添加导入信息</button></div>
                         </div>
                        <div class="form-group" id="imports" style="display:none;">
                           <label class="col-sm-2 control-label">关联导入信息</label>

                           <div class="col-sm-4">
                             <textarea name="imports" rows="2" cols="5" class="form-control" placeholder="多个ID请以 '/' 隔开"><?php echo ($imports); ?></textarea>
                               <input type="hidden" class="form-control" name="changimport" id="changimport" value="">
                           </div>
                           <div class="col-sm-2"><small>关联导入订单商品信息</small></div>
                       </div>

                        <div class="form-group" id="ProName" >
                            <label class="col-sm-2 control-label">商品名称</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="ProName_c" name="ProName" value="<?php echo ($proinfo["ProName"]); ?>">
                            </div>
                        </div>
                        <div class="form-group" id="ProTitle">
                            <label class="col-sm-2 control-label">商品标题</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="ProTitle_c" name="ProTitle" value="<?php echo ($proinfo["ProTitle"]); ?>"> <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group" id="ProSubtitle">
                            <label class="col-sm-2 control-label">商品说明/副标题</label>

                            <div class="col-sm-6">
                                <textarea name="ProSubtitle" id="ProSubtitle_c" cols="30" rows="3" class="form-control"><?php echo ($proinfo["ProSubtitle"]); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="ProSubtitle">
                            <label class="col-sm-2 control-label">商品重量/单位(g) <span class="tice"><b>*</b></span></label>

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" name="Weight" class="form-control" id="Weight" value="<?php echo ($proinfo["Weight"]); ?>"><span class="input-group-addon">g</span>
                                </div>
                            </div>
                        </div>

<!--                         <div class="form-group" id="FreightRemarks">
                            <label class="col-sm-2 control-label">运费说明</label>

                            <div class="col-sm-6">
                                <textarea name="FreightRemarks" id="FreightRemarks_c" cols="30" rows="4" class="form-control"><?php echo ($proinfo["FreightRemarks"]); ?></textarea>
                            </div>
                        </div>
 -->                        <div class="form-group" id="Remarks">
                            <label class="col-lg-2 control-label">备注说明</label>

                            <div class="col-lg-6">
                                <textarea name="Remarks" id="Remarks_c" cols="30" rows="4" class="form-control"><?php echo ($proinfo["Remarks"]); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="KeyWord">
                            <label class="col-lg-2 control-label">检索关键字</label>

                            <div class="col-lg-6">
                                <input type="text" name="KeyWord" class="form-control" id="KeyWord_c" value="<?php echo ($proinfo["KeyWord"]); ?>">
                            </div>
                        </div>
                        <div class="form-group" id="">
                            <label class="col-lg-2 control-label">员工提成</label>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="number" name="EmpCut" class="form-control" id="m1" onkeyup="checklang('m1');" value="<?php echo ($proinfo["EmpCut"]); ?>"><span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="">
                            <label class="col-lg-2 control-label">一级提成</label>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" name="Cut" class="form-control" id="c1" onkeyup="checklang('c1');" value="<?php echo ($proinfo["Cut"]); ?>"><span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="">
                            <label class="col-lg-2 control-label">推广佣金</label>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" name="ExtendCut" class="form-control" id="c2" onkeyup="checklang('c2');" value="<?php echo ($proinfo["ExtendCut"]); ?>"><span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
<!--                        <div class="form-group" id="">
                            <label class="col-lg-2 control-label">三级提成</label>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" name="Cut3" class="form-control" id="c3" onkeyup="checklang('c3');" value="<?php echo ($proinfo["Cut3"]); ?>"><span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
 -->                        <div class="form-group">
                            <label class="col-lg-2 control-label">是否使用优惠券</label>

                            <div class="col-lg-10">
                                <div class="radio">
                                    <label><input type="radio" name="IsUseConpon" id="" value="1" <?php if($proinfo['IsUseConpon'] == '1'): ?>checked="checked"<?php endif; ?> class="optionsRadios">是</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="IsUseConpon" id="" value="0" <?php if($proinfo['IsUseConpon'] == '0'): ?>checked="checked"<?php endif; ?> class="optionsRadios">否</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">是否为赠品</label>

                            <div class="col-lg-10">
                                <div class="radio">
                                    <label><input type="radio" name="Iszp" id="" value="1" <?php if($proinfo['Iszp'] == '1'): ?>checked="checked"<?php endif; ?> class="optionsRadios">是</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="Iszp" id="" value="0" <?php if($proinfo['Iszp'] == '0'): ?>checked="checked"<?php endif; ?> class="optionsRadios">否</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">是否使用积分兑换</label>

                            <div class="col-lg-10">
                                <div class="radio">
                                    <label><input type="radio" name="IsUseScore" id="IsUseScore" value="1" <?php if($proinfo['IsUseScore'] == 1): ?>checked="checked" data-s="1"<?php endif; ?> class="optionsRadios">是</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="IsUseScore" id="NOUseScore" value="0" <?php if($proinfo['IsUseScore'] == 0): ?>checked="checked"<?php endif; ?> class="optionsRadios">否</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" id="">
                            <label class="col-lg-2 control-label">兑换所需积分数</label>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" name="Score" class="form-control" id="Score"  value="<?php echo ($proinfo["Score"]); ?>"><span class="input-group-addon">分</span>
                                </div>
                            </div>
                        </div>
<!--                         <div class="form-group">
                            <label class="col-lg-2 control-label">是否立即上架</label>

                            <div class="col-lg-10">
                                <div class="radio">
                                    <label><input type="radio" name="IsShelves" id="" value="1" <?php if($proinfo['IsShelves'] == '1'): ?>checked="checked"<?php endif; ?> class="optionsRadios">是</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="IsShelves" id="" value="0" <?php if($proinfo['IsShelves'] == '0'): ?>checked="checked"<?php endif; ?> class="optionsRadios">否</label>
                                    否 定时上架：<input id="d11" type="text" name="ShelvesDate" onClick="WdatePicker()" value="<?php echo ($proinfo['ShelvesDate']); ?>" placeholder="点击选择日期"/>
                                </div>

                            </div>
                        </div>
 -->                        <div class="form-group has-error" id="ProLogoImg_c">
                            <label class="col-sm-2 control-label">商品主图</label>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="ProLogoImg_con">
                                            <img id="ProLogoImg_url" src="<?php echo ($proinfo['ProLogoImg']); ?>" alt="">
                                        </div>
                                        <div class="img-btn">
                                            <button class="btn btn-xs btn-warning" type="button" onclick="upimg('ProLogoImg')">修改</button>
                                            <input type="hidden" class="form-control" name="ProLogoImg" id="ProLogoImg" value="<?php echo ($proinfo['ProLogoImg']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>


                        <div class="form-group has-error" id="mainimg_c">
                            <label class="col-sm-2 control-label">商品展示图</label>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="mainimg_con">
                                        <?php if($proinfo['imgs'][0]): ?><img id="mainimg_url" src="<?php echo ($proinfo['imgs'][0]); ?>" alt="">
                                            <?php else: ?>
                                            暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('mainimg')">修改</button><input type="hidden" class="form-control" name="mainImg" id="mainimg" value="<?php echo ($proinfo['imgs'][0]); ?>">
                                        </div>
                                    </div>
                                    <?php for ($i=1; $i < 6; $i++) { ?>
                                        <div class="pull-left img-box">
                                            <div class="img-content" id="img<?php echo ($i); ?>_con">
                                                <?php if($proinfo['imgs'][$i]): ?><img id="img1_url" src="<?php echo ($proinfo['imgs'][$i]); ?>" alt="">
                                                <?php $isimg=1; ?>
                                                <?php else: ?>
                                                <?php $isimg=0; ?>
                                                暂无图片<?php endif; ?>
                                            </div>
                                            <div class="img-btn">
                                                <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img<?php echo ($i); ?>')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($isimg); ?>','img<?php echo ($i); ?>')">删除</button><input type="hidden" class="form-control" name="img<?php echo ($i); ?>" id="img<?php echo ($i); ?>" value="<?php echo ($proinfo['imgs'][$i]); ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
<!--                                     <div class="pull-left img-box">
                                        <div class="img-content" id="img1_con">
                                            <?php if($proinfo['imgs'][1]['ProImage']): ?><img id="img1_url" src="<?php echo ($proinfo['imgs'][1]['ProImage']); ?>" alt="">
                                            <?php else: ?>
                                            暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img1')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($proinfo["imgs"]["1"]["ID"]); ?>','img1')">删除</button><input type="hidden" class="form-control" name="img1" id="img1" value="<?php echo ($proinfo['imgs'][1]['ProImage']); ?>"><input type="hidden" name="img1id" id="img1id" value="<?php echo ($proinfo["imgs"]["1"]["ID"]); ?>">
                                        </div>
                                    </div>
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="img2_con">
                                            <?php if($proinfo['imgs'][2]['ProImage']): ?><img id="img2_url" src="/web<?php echo ($proinfo['imgs'][2]['ProImage']); ?>" alt="">
                                            <?php else: ?>
                                            暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img2')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($proinfo["imgs"]["2"]["ID"]); ?>','img2')">删除</button><input type="hidden" class="form-control" name="img2" id="img2" value="<?php echo ($proinfo['imgs'][2]['ProImage']); ?>"><input type="hidden" name="img2id" id="img2id" value="<?php echo ($proinfo["imgs"]["2"]["ID"]); ?>">
                                        </div>
                                    </div>
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="img3_con">
                                            <?php if($proinfo['imgs'][3]['ProImage']): ?><img id="img3_url" src="/web<?php echo ($proinfo['imgs'][3]['ProImage']); ?>" alt="">
                                            <?php else: ?>
                                            暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img3')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($proinfo["imgs"]["3"]["ID"]); ?>','img3')">删除</button><input type="hidden" class="form-control" name="img3" id="img3" value="<?php echo ($proinfo["imgs"]["3"]["ProImage"]); ?>"><input type="hidden" name="img3id" id="img3id" value="<?php echo ($proinfo["imgs"]["3"]["ID"]); ?>">
                                        </div>
                                    </div>
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="img4_con">
                                            <?php if($proinfo['imgs'][4]['ProImage']): ?><img id="img4_url" src="/web<?php echo ($proinfo['imgs'][4]['ProImage']); ?>" alt="">
                                            <?php else: ?>
                                            暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img4')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($proinfo["imgs"]["4"]["ID"]); ?>','img4')">删除</button><input type="hidden" class="form-control" name="img4" id="img4" value="<?php echo ($proinfo["imgs"]["4"]["ProImage"]); ?>"><input type="hidden" name="img4id" id="img4id" value="<?php echo ($proinfo["imgs"]["4"]["ID"]); ?>">
                                        </div>
                                    </div>
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="img5_con">
                                            <?php if($proinfo['imgs'][5]['ProImage']): ?><img id="img5_url" src="/web<?php echo ($proinfo['imgs'][5]['ProImage']); ?>" alt="">
                                             <?php else: ?>
                                             暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img5')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($proinfo["imgs"]["5"]["ID"]); ?>','img5')">删除</button><input type="hidden" class="form-control" name="img5" id="img5" value="<?php echo ($proinfo["imgs"]["5"]["ProImage"]); ?>"><input type="hidden" name="img5id" id="img5id" value="<?php echo ($proinfo["imgs"]["5"]["ID"]); ?>">
                                        </div>
                                    </div>
 -->
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>

                            <div class="col-lg-10">
                                <div class="radio">
                                    <label><input type="radio" name="IschangImg" id="" value="1" class="optionsRadios">展示图修改请勾选此项</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品详情</label>
                            <div class="col-sm-8">
                                <textarea name="ProContent" class="form-control1" id="editor" style="width:100%;height:600px;"><?php echo ($proinfo["ProContent"]); ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="ProId" value="<?php echo ($proinfo["ProId"]); ?>">
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
        showTable();
        $('#NOUseScore').click(function(){$("#IsUseScore").attr('data-s','0')})
        $('#IsUseScore').click(function(){$("#IsUseScore").attr('data-s','1')})
        $("#chose-class").hide();
        $("#chose-attr").hide();
        $("#chose-attrs").hide();
        $("#saveimg").click(function(){
            art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
        })
        $("#savePro").submit(function(){
            // var ProAttr=$("price[]").val();
            var ProName=$('#ProName_c').val();
            var ProTitle=$("#ProTitle_c").val();
            var logoimg=$("#ProLogoImg").val();
            var IsUseScore=$("#IsUseScore").attr('data-s');
            var score=$("#Score").val();
            // alert(logoimg);
            // console.log($("#ProLogoImg").val());
            // return false;
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
            if (!logoimg) {
                art.dialog.alert('请上传商品主图');
                return false;
            }else{
                art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
                // return true;
            }
        })

        $("#addimport").click(function(){$("#imports").show();$("#changimport").val('true')});
    })
function upimg(id){
    // art.dialog.data('homeDemoPath', "<?php echo U('Product/proadd');?>");
    art.dialog.data('domid',id);
    art.dialog.open('<?php echo U('Upimg/editpro');?>');
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
    };
}

//生成属性表格
function showTable(){
  var count=<?php echo ($attrcount); ?>;
  var prolist=<?php echo ($ProductList); ?>;
  var ats=<?php echo ($atnames); ?>;
  // alert(attrs);
  // console.log(attrs);
  // console.log(count);
  // console.log(ats);
  $("#table").html('');
  var table=$("<table class='table table-bordered table-responsive'></table>");
  table.appendTo($("#table"));
  var thead=$("<thead></thead>");
  thead.appendTo(table);
  var trhead=$("<tr></tr>");
  trhead.appendTo(thead);
  var tds='';
  for(var i  in ats){
    tds+="<td>"+ats[i]+"</td>";
  }
  tds+="<td>编号</td><td>操作</td>"
  $(tds).appendTo(trhead);
  var tbody=$("<tbody id='tbody'></tbody>")
  tbody.appendTo(table);
  for(var j in prolist){
    var trbody=$("<tr id='"+prolist[j].ProIdCard+"'></tr>")
    trbody.appendTo(tbody);
    if (count==1) {
      $('<td>'+prolist[j].ProSpec1+'</td><input type="hidden" name="oldproidcard[]" value="'+prolist[j].ProIdCard+'" /><td><input type="text" name="ProIdInputCard[]" id="" value="'+prolist[j].ProIdInputCard+'" /></td><td><button class="btn btn-xs btn-danger" type="button" onclick="delAttr(\''+prolist[j].ProIdCard+'\');">删除</button></td>').appendTo(trbody);
    };
    if (count==2) {
      $('<td>'+prolist[j].ProSpec1+'</td><input type="hidden" name="oldproidcard[]" value="'+prolist[j].ProIdCard+'" /><td>'+prolist[j].ProSpec2+'</td><td><input type="text" name="ProIdInputCard[]" id="" value="'+prolist[j].ProIdInputCard+'" /></td><td><button class="btn btn-xs btn-danger" type="button" onclick="delAttr(\''+prolist[j].ProIdCard+'\');">删除</button></td>').appendTo(trbody);
    };
    if (count==3) {
      $('<td>'+prolist[j].ProSpec1+'</td><input type="hidden" name="oldproidcard[]" value="'+prolist[j].ProIdCard+'" /><td>'+prolist[j].ProSpec2+'</td><td>'+prolist[j].ProSpec3+'</td><td><input type="text" name="ProIdInputCard[]" id="" value="'+prolist[j].ProIdInputCard+'" /></td><td><button class="btn btn-xs btn-danger" type="button" onclick="delAttr(\''+prolist[j].ProIdCard+'\');">删除</button></td>').appendTo(trbody);
    }
  }
  $("<button type='button' class='btn btn-xs btn-primary' onclick='addNewAttr();'>添加属性</button>").appendTo(table);
  $("#btn-name").text('重置');
}


//添加新属性
function addNewAttr(){
  var ats=<?php echo ($atnames); ?>;
  var tempStr=<?php echo ($tempStr); ?>;
  var count=<?php echo ($attrcount); ?>;
  var tbody=$("#tbody");
  var tempTD='<tr>';
  for(var i in ats){
    var tempId=ats[i].split('_')[1];
    var tempOPTION='';
    // var tempOPTION='<option value="">请选择</option>';
    for(var j in tempStr[tempId]){
      tempOPTION+='<option value="'+tempStr[tempId][j].AttributeValueId+'_'+tempStr[tempId][j].AttributeValue+'">'+tempStr[tempId][j].AttributeValue+'</option>';
    }
    tempTD+='<td><select name="prospec'+i+'[]" id="">'+tempOPTION+'</select></td>';
    // console.log(tempOPTION);
  }
  tempTD+='<td><input type="text" name="num[]" id=""  onblur="check($this.val());"/></td></tr>';
  $(tempTD).appendTo(tbody);
}



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

$(function() {
    var data=<?php echo ($allClass); ?>;
    console.log(data);
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

function getAttrbute(){
    var allAttr=<?php echo ($attrdata); ?>;
    var AttributeId=$("#chose-attrid").val();
    var AttributeName=$("#name"+AttributeId).text();
    $.each(allAttr,function(index,item){
        if (AttributeId==item.AttributeId) {
            var mainId=$(".varea").length;
            var html='<div class="col-sm-10  varea"  value="'+AttributeName+'" style="margin-bottom:10px;border-bottom:1px solid #aaa;padding-bottom:5px;"><span style="font-size:1.5em;" id="an'+mainId+'">'+AttributeName+'</span> <br/><br/> ';
            for (var i in item.values) {
                html+='<label class="checkbox-inline dady" value="'+AttributeName+'" ><input type="checkbox" name="values'+mainId+'" /*onclick="noticer($(this).val(),$(this).parent().attr(\'value\'),$(this).parents(\'.varea\').attr(\'id\'));" */class="values" value="'+item.values[i].AttributeValueId+'" id="AttributeName'+item.values[i].AttributeValueId+'"><span class="attrvalues" id="txt'+item.values[i].AttributeValueId+'">'+item.values[i].AttributeValue+'</span></label>';
            };
            html+='</div>';
            $(html).appendTo("#valuearea");
            var lang=$(".varea");
                // alert(lang.length);
                if (lang.length>2) {
                    $("#add").hide();
                    $("#yes").show();
                };
                $("#chose-attr").hide();
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
    var itemColumHead = $("<td style='width:70px;'>价格</td><td  style=\"width:70px;\">出售价格</td> <td>编号</td>");
    itemColumHead.appendTo(trhead);
    var tbody = $("<tbody></tbody>");
    tbody.appendTo(table);
    if (langs==1) {
        var atids=new Array();
        var atnames=new Array();
        var values1=document.getElementsByName('values0');
        for (var i = 0; i < values1.length; i++) {
            if (values1[i].checked==true) {
                atids.push(values1[i].value);
                atnames.push($("#txt"+values1[i].value).text());
                var trtbody=$("<tr></tr>");
                trtbody.appendTo(tbody);
                $("<td>"+$("#txt"+values1[i].value).text()+"</td>").appendTo(trtbody);
                $("<td><input type='text' name='oldprice[]' onblur='check($(this).val());'/></td><td><input type='text' onblur='check($(this).val());' name='price[]' /></td><td><input type='text' onblur='check($(this).val());' name='num[]' /></td><input type='hidden' name='datastr[]' value='"+values1[i].value+"|"+$("#txt"+values1[i].value).text()+"' /> <input type='hidden' name='atids' value='"+atids+"'><input type='hidden' name='atnames' value='"+atnames+"'>").appendTo(trtbody);
            };
        };
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
                atids.push(values1[i].value);
                atnames.push($("#txt"+values1[i].value).text());
                for (var j = 0; j < values2.length; j++) {
                    if (values2[j].checked==true) {
                        atids1.push(values2[j].value);
                        atnames1.push($("#txt"+values2[j].value).text());
                        var trtbody=$("<tr></tr>");
                        trtbody.appendTo(tbody);
                        $("<td>"+$("#txt"+values1[i].value).text()+"</td><td>"+$("#txt"+values2[j].value).text()+"</td>").appendTo(trtbody);
                        $("<td><input type='text' name='oldprice[]'  onblur='check($(this).val());' /></td><td><input type='text' onblur='check($(this).val());' name='price[]' /></td><td><input type='text'  onblur='check($(this).val());' name='num[]' /></td><input type='hidden' name='datastr[]' value='"+values1[i].value+"_"+values2[j].value+"|"+$("#txt"+values1[i].value).text()+"|"+$("#txt"+values2[j].value).text()+"' /> <input type='hidden' name='atids' value='"+atids+"'><input type='hidden' name='atids1' value='"+atids1+"'><input type='hidden' name='atnames' value='"+atnames+"'><input type='hidden' name='atnames1' value='"+atnames1+"'>").appendTo(trtbody);
                    };
                };
            };
        };
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
                atids.push(values1[i].value);
                atnames.push($("#txt"+values1[i].value).text());
                for (var j = 0; j < values2.length; j++) {
                    if (values2[j].checked==true) {
                        atids1.push(values2[j].value);
                        atnames1.push($("#txt"+values2[j].value).text());
                        for (var k = 0; k < values3.length; k++) {
                            if (values3[k].checked==true) {
                                atids2.push(values3[k].value);
                                atnames2.push($("#txt"+values3[k].value).text());
                                var trtbody=$("<tr></tr>");
                                trtbody.appendTo(tbody);
                                $("<td>"+$("#txt"+values1[i].value).text()+"</td><td>"+$("#txt"+values2[j].value).text()+"</td><td>"+$("#txt"+values3[k].value).text()+"</td>").appendTo(trtbody);
                                $("<td><input type='text' name='oldprice[]' onblur='check($(this).val());'/></td><td><input type='text'  onblur='check($(this).val());' name='price[]' /></td><td><input type='text' onblur='check($(this).val());' name='num[]' /></td><input type='hidden' name='datastr[]' value='"+values1[i].value+"_"+values2[j].value+"_"+values3[k].value+"|"+$("#txt"+values1[i].value).text()+"|"+$("#txt"+values2[j].value).text()+"|"+$("#txt"+values3[k].value).text()+"' /> <input type='hidden' name='atids' value='"+atids+"'><input type='hidden' name='atids1' value='"+atids1+"'><input type='hidden' name='atids2' value='"+atids2+"'><input type='hidden' name='atnames' value='"+atnames+"'><input type='hidden' name='atnames1' value='"+atnames1+"'><input type='hidden' name='atnames2' value='"+atnames2+"'>").appendTo(trtbody);
                            };
                        };
                    };
                };
            };
        };
    };

}

function clears(){
    var lang=$("varea");
    if (lang.length<3) {
        $("#add").show();
    };
    $('#valuearea').html('');
    $("#table").html('');
}

function editClass(){
    // alert($("#hclasstype").html());
    $("#hclasstype").html('');
    $('#chose-class').show();
}

function addAttr(){
    $("#chose-attrs").show();
}

function checklang(id){
    var v=$("#"+id).val();
    if (v.length>2) {
        $("#"+id).val(v.substr(0,2));
        art.dialog.tips('非法数字',0.5);
    };
}

function delAttr(id){
  // art.dialog.alert(id);
    art.dialog.confirm("确定要删除吗?此操作即时生效",function(){
        $.ajax({
            type:"post",
            url:"<?php echo U('Products/delAttr');?>",
            data:"id="+id,
            dateType:"json",
            success:function(msg){
                console.log(msg);
                if (msg=='success') {
                    art.dialog.tips('删除成功',1);
                    $("#"+id).remove();
                } else{
                    art.dialog.tips('删除失败',1);
                }
            }
        })
    },function(){
        art.dialog.tips('取消',1);
    })
}


function del(id,imgid){
    if (id==1) {
        art.dialog.confirm('确定要删除吗？',function(){
            art.dialog.tips('此栏位图片已删除,保存修改后生效');
            $("#"+imgid+"_con").html('图片已删除,保存修改后生效');
            $("#"+imgid+"id").val('');
            $("#"+imgid).val('');
        },function(){
            art.dialog.tips('取消操作',1);
        })
    }else{
        art.dialog.alert('此栏位没有图片');
    }
}




</script>

<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>