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
<style type="text/css">
    .box{
        border:2px solid #ccc;
        border-radius: 5px;
        padding: 10px;
    }
</style>
</head><body><script>NProgress.start();$('.dropdown').click(function(){if ($('.dropdown-menu').attr('data-s')=='1') {$('.dropdown-menu').show().attr('data-s','0')}else{$('.dropdown-menu').hide().attr('data-s','1')};})</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header" style="text-align:center;padding-top:15px;"><span class='display:bolck;float:left;'><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><div style="text-align:center;float:right;"><span style='font-weight:bold;color:white;font-size:15px;clear:both;'><?php echo (session('Sname')); ?></span><div class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul data-s='1' class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div></div><div class="logo-element"></div></li><li title='主页' <?php if(FPAGE == 'Index/index'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("Index/index");?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<span class="nav-label">主页</span> </a></li><li title='基础设置' <?php if(FPAGE == 'BASE'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("BASE");?>"><i class="fa fa-barcode"></i>&nbsp;&nbsp;<span class="nav-label">基础设置</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("BaseSetting/timeset");?>">商城设置</a></li><li><a href="<?php echo U("BaseSetting/score");?>">积分设置</a></li><li><a href="<?php echo U("Warehouse/logistics");?>">物流设置</a></li><li><a href="<?php echo U("BaseSetting/yunfei");?>">物流运费设置</a></li><li><a href="<?php echo U("BaseSetting/modelmsg");?>">模板消息配置</a></li><li><a href="<?php echo U("BaseSetting/home");?>">首页轮播图设置</a></li><li><a href="<?php echo U("Payset/index");?>">支付配置</a></li><li><a href="<?php echo U("Extend/scene");?>">场景管理</a></li><li><a href="<?php echo U("BaseSetting/pageset");?>">主页设置</a></li></ul></li><li title='员工管理' <?php if(FPAGE == 'YUANGONG'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("YUANGONG");?>"><i class="fa fa-th"></i>&nbsp;&nbsp;<span class="nav-label">员工管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Admin/add");?>">添加员工</a></li><li><a href="<?php echo U("Admin/ticheng");?>">员工提成管理</a></li><li><a href="<?php echo U("Admin/index");?>">员工管理</a></li><li><a href="<?php echo U("Auth/group");?>">用户组管理</a></li></ul></li><li title='会员管理' <?php if(FPAGE == 'HUIYUAN'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("HUIYUAN");?>"><i class="fa fa-users"></i>&nbsp;&nbsp;<span class="nav-label">会员管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Users/level");?>">会员等级设置</a></li><li><a href="<?php echo U("Users/member");?>">会员查询</a></li><li><a href="<?php echo U("Users/cons");?>">会员消费信息</a></li><li><a href="<?php echo U("Users/extend");?>">会员推广信息</a></li><li><a href="<?php echo U("Users/tuiscenepro");?>">推广人商品统计</a></li><li><a href="<?php echo U("Users/extendcut");?>">会员推广佣金统计</a></li><li><a href="<?php echo U("Users/coupons");?>">会员优惠券</a></li><li><a href="<?php echo U("Users/getcash");?>">会员提现</a></li></ul></li><li title='门店管理' <?php if(FPAGE == 'MENDIAN'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("MENDIAN");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">门店管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Store/add");?>">门店添加</a></li><li><a href="<?php echo U("###");?>">核销员管理</a></li><li><a href="<?php echo U("Store/index");?>">门店管理</a></li></ul></li><li title='POS收银' <?php if(FPAGE == 'SHOUYIN'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("SHOUYIN");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">POS收银</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Cashier/Index");?>">POS收银</a></li><li><a href="<?php echo U("Warehouse/ScanPay");?>">收银台</a></li></ul></li><li title='商品管理' <?php if(FPAGE == 'SHANGPIN'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("SHANGPIN");?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">商品管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Products/category");?>">商品分类管理</a></li><li><a href="<?php echo U("Products/index");?>">商品管理</a></li><li><a href="<?php echo U("Products/yunfei");?>">运费模板管理</a></li><li><a href="<?php echo U("Products/attributes");?>">商品属性管理</a></li><li><a href="<?php echo U("Products/proadd");?>">商品详情添加</a></li><li><a href="<?php echo U("Products/setbuy");?>">商品限购设置</a></li><li><a href="<?php echo U("Products/groupon");?>">商品团购设置</a></li></ul></li><li title='仓库管理' <?php if(FPAGE == 'CANGKU'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("CANGKU");?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">仓库管理</span> </a><ul class="nav nav-second-level"><li><a href="###">盘点管理</a><ul class="nav nav-second-level"><li><a href="<?php echo U("Invoicing/inventory");?>">&nbsp;&nbsp;&nbsp;&nbsp;库存盘点</a></li><li><a href="<?php echo U("Invoicing/inventorylist");?>">&nbsp;&nbsp;&nbsp;&nbsp;盘点单查询</a></li></ul></li><li><a href="###">入库管理</a><ul class="nav nav-second-level"><li><a href="<?php echo U("Invoicing/inwarehouse");?>">&nbsp;&nbsp;&nbsp;&nbsp;入库单管理</a></li><li><a href="<?php echo U("Invoicing/inwarehouselist");?>">&nbsp;&nbsp;&nbsp;&nbsp;入库单查询</a></li></ul></li><li><a href="###">库存统计查询</a><ul class="nav nav-second-level"><li><a href="<?php echo U("Invoicing/index");?>">&nbsp;&nbsp;&nbsp;&nbsp;商户库存查询</a></li><li><a href="<?php echo U("Invoicing/supplierList");?>">&nbsp;&nbsp;&nbsp;&nbsp;供应商库存查询</a></li></ul></li><li><a href="###">出库管理</a><ul class="nav nav-second-level"><li><a href="<?php echo U("Invoicing/outwarehouse");?>">&nbsp;&nbsp;&nbsp;&nbsp;出库单管理</a></li><li><a href="<?php echo U("Invoicing/outwarehouselist");?>">&nbsp;&nbsp;&nbsp;&nbsp;出库单查询</a></li></ul></li></ul></li><li title='订单管理' <?php if(FPAGE == 'DINGDAN'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("DINGDAN");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">订单管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Order/allGiftOrder");?>">礼包订单</a></li><li><a href="<?php echo U("Order/index");?>">订单概况</a></li><li><a href="<?php echo U("Order/allOrder");?>">全部订单</a></li><li><a href="<?php echo U("Order/allScoreOrder");?>">积分订单</a></li></ul></li><li title='客服管理' <?php if(FPAGE == 'KEFU'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("KEFU");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">客服管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Service/index");?>">客户服务</a></li></ul></li><li title='商户管理' <?php if(FPAGE == 'SHANGHU'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("SHANGHU");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">商户管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Storers/register");?>">申请商户</a></li><li><a href="<?php echo U("Storers/checks");?>">商户审核</a></li><li><a href="<?php echo U("Storers/storecut");?>">商户结算</a></li></ul></li><li title='数据统计' <?php if(FPAGE == 'TONGJI'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("TONGJI");?>"><i class="fa fa-area-chart"></i>&nbsp;&nbsp;<span class="nav-label">数据统计</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Statcenter/poscash");?>">Pos收银统计</a></li><li><a href="<?php echo U("Order/assess");?>">评价统计分析</a></li><li><a href="<?php echo U("Statcenter/SceneSale");?>">场景销售统计</a></li><li><a href="<?php echo U("Statcenter/posemp");?>">收银员数据统计</a></li><li><a href="<?php echo U("Statcenter/scancash");?>">收银台现金统计</a></li><li><a href="<?php echo U("Statcenter/sceneInfo");?>">场景统计</a></li><li><a href="<?php echo U("Statcenter/yunfei");?>">运费统计</a></li><li><a href="<?php echo U("Statcenter/zpdata");?>">赠品统计</a></li><li><a href="<?php echo U("Statcenter/Cancels");?>">支付核销统计</a></li><li><a href="<?php echo U("Statcenter/getcancels");?>">提货核销统计</a></li><li><a href="<?php echo U("Statcenter/redpaper");?>">会员提现统计</a></li><li><a href="<?php echo U("Statcenter/discountorder");?>">订单优惠统计</a></li><li><a href="<?php echo U("Statcenter/PayType");?>">付款方式统计</a></li><li><a href="<?php echo U("Order/orderproinfo");?>">订单商品统计</a></li></ul></li><li title='促销管理' <?php if(FPAGE == 'CUXIAO'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("CUXIAO");?>"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;<span class="nav-label">促销管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Products/coupons");?>">优惠券管理</a></li><li><a href="<?php echo U("Products/sprice");?>">限时特价管理</a></li><li><a href="<?php echo U("Products/discount");?>">优惠设置</a></li><li><a href="<?php echo U("Products/discountpart");?>">组合优惠</a></li><li><a href="<?php echo U("Products/addgifts");?>">礼包管理</a></li></ul></li><li title='供货商管理' <?php if(FPAGE == 'GHS'): ?>class='active'<?php endif; ?>>
                        <a href="<?php echo U("GHS");?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<span class="nav-label">供货商管理</span> </a><ul class="nav nav-second-level"><li><a href="<?php echo U("Warehouse/suppliers");?>">供货商管理</a></li><li><a href="<?php echo U("Invoicing/supplierIn");?>">进货单结算</a></li><li><a href="<?php echo U("Invoicing/cashover");?>">供货商结算</a></li><li><a href="<?php echo U("Invoicing/supplierSale");?>">销售单结算</a></li></ul></li></ul></div></nav><div id="page-wrapper" class="gray-bg dashbard-1"><div class="row border-bottom"></div>

<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
       <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>权限管理</h2>
       <ol class="breadcrumb">
           <li>
               <a href="index.html">主页</a>
           </li>
           <li class="active">
               <strong>权限分配</strong>
           </li>
       </ol>
   </div>
   <div class="col-lg-2"></div>
</div>


<div class="row  wrapper  white-bg" style="margin:0 1%;">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>权限分配</h5>
            </div>
            <div class="ibox-content">
                <form method="post" action="<?php echo U('Auth/saveDis');?>" class="form-horizontal" id="savenote">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">请选择用户组</label>

                        <div class="col-sm-2">
                            <select class="form-control m-b" name="GroupId" id="GroupId" value="<?php echo ($GroupId); ?>" <?php if($GroupId): ?>disabled="disabled"<?php endif; ?>>
                                <option value="">请选择</option>
                                <?php if(is_array($groups)): foreach($groups as $key=>$group): if($group['GroupId'] == $GroupId): ?><option value="<?php echo ($group["GroupId"]); ?>" selected="selected"><?php echo ($group["GroupName"]); ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo ($group["GroupId"]); ?>"><?php echo ($group["GroupName"]); ?></option><?php endif; endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择权限</label>
                        <div class="col-sm-2 box" style="margin-bottom:20px;"><label> <input type="checkbox" name="" onclick="sall(this.checked);" id="">全选 </label></div>
                        <?php if(is_array($menus)): foreach($menus as $key=>$menu): ?><div class="col-sm-10 pull-right box" style="margin-bottom:2%;">
                                <div class="checkbox" style="border-bottom:1px solid #ccc;">
                                  <label>
                                    <input type="checkbox" dataname="" <?php if (in_array($menu['MenuId'], $nodes)): ?>
                                        checked="checked"
                                    <?php endif ?> name="son[]" class="<?php echo ($menu["MenuId"]); ?> all" onclick="csons(this.checked,'<?php echo ($menu["MenuId"]); ?>');" value="<?php echo ($menu["MenuId"]); ?>">
                                    <?php echo ($menu["MenuName"]); ?>
                                </label>
                                </div>
                            <?php if(is_array($menu["sons"])): foreach($menu["sons"] as $key=>$son): ?><label class="checkbox-inline">
                                <input type="checkbox" name="son[]" <?php if (in_array($son['MenuId'], $nodes)): ?>
                                    checked="checked"
                                <?php endif ?> class="<?php echo ($menu["MenuId"]); ?>_val all" value="<?php echo ($son["MenuId"]); ?>" onclick="roots(this.checked,'<?php echo ($menu["MenuId"]); ?>')" id="inlineCheckbox1"><?php echo ($son["MenuName"]); ?>
                            </label>
                                <?php if($son['sons']): ?><div style="display:inline;border-top:1px solid red;border-left:1px solid red;border-right:1px solid red;">
                                &nbsp;&nbsp;&nbsp;[<?php echo ($son["MenuName"]); ?>].子菜单：
                                <?php if(is_array($son["sons"])): foreach($son["sons"] as $key=>$sson): ?><label class="checkbox-inline">
                                        <input type="checkbox" name="son[]" <?php if (in_array($sson['MenuId'], $nodes)): ?>
                                            checked="checked"
                                        <?php endif ?> id="" class="<?php echo ($menu["MenuId"]); ?>_val all" value="<?php echo ($sson["MenuId"]); ?>"><?php echo ($sson["MenuName"]); ?>
                                    </label><?php endforeach; endif; ?>
                                </div><?php endif; endforeach; endif; ?>
                            </div><?php endforeach; endif; ?>

                        <?php if($GroupId): ?><input type="hidden" name="GroupId" value="<?php echo ($GroupId); ?>"><?php endif; ?>

                            </div>
                            <div class="form-group">
                            <input type="hidden" name="statu" value="<?php echo ($statu); ?>">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">
    function csons(statu,rid){
        // alert(statu);
        // alert(rid);
        if (statu) {
            $("."+rid+"_val").prop('checked',true);
        }else{
            $("."+rid+"_val").prop('checked',false);
        }
    }

    function roots(statu,id){
        if (statu) {
            $("."+id).prop('checked',true);
        };
        if (statu==false) {
            var sons=$("."+id+"_val");
            var status=true;
            $.each(sons,function(index,item){
                if (item.checked) {
                    status=false;
                };
            })
            if (status) {
                $("."+id).prop('checked',false);
            };
        };
    }
    $(document).ready(function(){
        $("#savenote").submit(function(){
            var gid=$("#GroupId").val();
            if (!gid) {
                art.dialog.alert('请选择用户组');
                return false;
            }else{
                art.dialog({content:'正在处理数据...',lock:true});
                return true;
            }
        })
    })

    function sall(statu){
        if (statu==true) {
            $(".all").prop('checked',true);
        };
        if (statu==false) {
            $(".all").prop('checked',false);
        };
    }
</script>
        <div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>