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
<link rel="stylesheet" type="text/css" href="/Public/Admin/Admin/css/my.css">

<style type="text/css">
    .pro{
        width: 20%;
        float: left;
        position: relative;
        margin-top: 15px;
    }
    .pinfo{
        width: 96%;
        margin: auto 2%;
    }
    .pinfo img{
        width: 100%;
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
    .qr{
      position: absolute;
      right: 45px;
      top: 10px;
      color: orange;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.2em;
  }
  .hot{
      position: absolute;
      right: 75px;
      top: 10px;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.2em;
  }
  .up{
      position: absolute;
      right: 135px;
      color: green;
      top: 10px;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.2em;
  }
  .zp{
      position: absolute;
      right: 165px;
      color: green;
      top: 10px;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.2em;
  }
  .score{
      position: absolute;
      right: 195px;
      color: green;
      top: 10px;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.2em;
  }
  .new{
      position: absolute;
      right: 105px;
      top: 10px;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.2em;
  }
  .remove{
      position: absolute;
      right: 15px;
      top: 10px;
      color: red;
      line-height: 1em;
      height: auto;
      z-index: 1;
      font-size: 1.1em;
  }
  .remove:hover{
    cursor: pointer;
    background: #ccc;
}
.up:hover{
    cursor:pointer;
    background: #fff;
}
.zp:hover{
    cursor:pointer;
    background: #fff;
}
.score:hover{
    cursor:pointer;
    background: #fff;
}
.new:hover{
    cursor: pointer;
    background: #fff;
}
.hot:hover{
    cursor: pointer;
    background: #fff;
}
.qr:hover{
    cursor: pointer;
    background: #ccc;
}
.del:hover{
    cursor: pointer;
    background: #ccc;
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
        <h2><a href="<?php echo U('Products/index');?>">商品管理</a></h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">主页</a>
            </li>
            <li class="active">
                <strong>商品管理</strong>
            </li>
        </ol>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><button class="btn btn-primary btn-outline"><a href="<?php echo U('Products/proadd');?>">添加商品</a></button></h5>
                    </div>
                    <div class="col-lg-12 col-md-12" style="border-bottom:0px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
                        <form action="<?php echo U('Products/search');?>" class="form-inline" method="post" id="search">
                            <div class="form-group">
                                <input type="text" name="ProName" placeholder="请填写商品名称" id="ProName" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="ProNumber" id="ProId" class="form-control" placeholder="请填写商品编号(选填)">
                            </div>
                            <div class="form-group">
                                <select name="ClassType" id="ClassType" class="form-control">
                                    <option value="">请选择分类(选填)</option>
                                    <?php if(is_array($allClass)): foreach($allClass as $key=>$part): if($part['ClassGrade'] == '1'): ?><option value="<?php echo ($part["ClassId"]); ?>" style="color:green;font-size:1.1em;"><?php echo ($part["ClassName"]); ?></option>
                                            <?php else: ?>
                                            <option value="<?php echo ($part["ClassId"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($part["ClassName"]); ?></option><?php endif; endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="IsShelves" id="IsShelves" class="form-control">
                                    <option value="2">全部</option>
                                    <option value="1">出售中</option>
                                    <option value="0">已下架</option>
                                </select>
                            </div><input type="hidden" name="statu" value="key">
                            <button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
                            <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span> 导 出</button>
                        </form>

                    </div>
                    <div class="col-lg-12 col-md-12" style="border-bottom:0px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
                        <form action="<?php echo U('Products/search');?>" class="form-inline" method="post" id="searchtime">
                            <div class="form-group">
                                <input type="text" name="stime" placeholder="请选择查询起始时间" id="stime" class="form-control"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                            </div>
                            <div class="form-group">
                                <input type="text" name="etime" id="etime" class="form-control" placeholder="请选择查询结束时间"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                            </div>
                            <div class="form-group">
                                <select name="IsShelves" id="IsShelves" class="form-control">
                                    <option value="2">全部</option>
                                    <option value="1">出售中</option>
                                    <option value="0">已下架</option>
                                </select>
                            </div><input type="hidden" name="statu" value="time">
                            <button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
                        </form>

                    </div>

                    <div class="ibox-content">
                        <?php $i=0 ?>
                        <?php if(is_array($products)): foreach($products as $key=>$pro): ?><div class="pro">
                                <span class="glyphicon glyphicon-remove remove" title="删除" alt="删除" onclick="dele('<?php echo ($pro["ProId"]); ?>')"></span>
                                <span class="qr glyphicon glyphicon-camera" title="点击生成二维码" alt="点击生成二维码" onclick="qr('<?php echo ($pro["ProId"]); ?>')"></span>
                                <span id="p<?php echo ($pro["ID"]); ?>" data="<?php echo ($pro["bq1"]); ?>" <?php if($pro['bq1'] == '1'): ?>style="color:red"<?php else: ?>style="color:#ccc"<?php endif; ?> class="hot glyphicon glyphicon-fire" title="热卖" onclick="hot('<?php echo ($pro["ProId"]); ?>','<?php echo ($pro["ID"]); ?>');"></span>
                                <span id="n<?php echo ($pro["ID"]); ?>" data="<?php echo ($pro["bq2"]); ?>" title="新品" class="new glyphicon glyphicon-tags" onclick="news('<?php echo ($pro["ProId"]); ?>','<?php echo ($pro["ID"]); ?>');" <?php if($pro['bq2'] == '2'): ?>style="color:green"<?php else: ?>style="color:#ccc"<?php endif; ?>></span>
                                <span id="cc<?php echo ($pro["ID"]); ?>" data="<?php echo ($pro["IsShelves"]); ?>" <?php if($pro['IsShelves'] == '1'): ?>class="up glyphicon glyphicon-circle-arrow-down" title="点击下架"<?php else: ?>class="up glyphicon glyphicon-circle-arrow-up" title="点击上架"<?php endif; ?> onclick="up('<?php echo ($pro["ProId"]); ?>','<?php echo ($pro["ID"]); ?>');"></span>
                                <span id="zp<?php echo ($pro["ID"]); ?>" class='zp glyphicon glyphicon-gift' onclick="setzp('<?php echo ($pro["ProId"]); ?>','<?php echo ($pro["ID"]); ?>')" <?php if($pro['Iszp'] == 1): ?>alt="取消赠品" title="取消赠品" style="color:green;" data-s="1"<?php else: ?>alt="设为赠品" title="设为赠品" style="color:#ccc"  data-s="0"<?php endif; ?> ></span>
                                <span id="sc<?php echo ($pro["ID"]); ?>" class='score glyphicon glyphicon-cutlery' onclick="setsc('<?php echo ($pro["ProId"]); ?>','<?php echo ($pro["ID"]); ?>')" <?php if($pro['IsUseScore'] == 1): ?>alt="取消积分兑换" title="取消积分兑换" style="color:green"  data-s="1"<?php else: ?>alt="设为积分兑换" title="设为积分兑换" style="color:#ccc"  data-s="0"<?php endif; ?> ></span> 
                                <a href="<?php echo U('Products/proedit');?>?pid=<?php echo ($pro["ProId"]); ?>">  <div class="pinfo"><img src="<?php echo ($pro["img"]); ?>" alt=""><div style="text-align:left;">商品名称：<?php echo ($pro["ProName"]); ?> <br> 商品编号：<?php echo ($pro["ProNumber"]); ?> <br>销量：<?php echo ($pro["SalesCount"]); ?>  <br>售价： <?php echo number_format($pro['PriceRange'],2) ?> </div></div></a></div>
                            <?php $i++; if ($i%5==0) { echo "<div style='clear:both;'></div><hr>"; } endforeach; endif; ?>
                        <div style="clear:both"></div>
                    </div>
                </div>
                <div style="text-align:center;"><?php echo ($page); ?></div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function del(id){
        // alert('删除预留，您正在删除商品:'+id);
        art.dialog.open('<?php echo U('Static/edit');?>?pid='+id,{width:600,height:960});
    }

    function qr(id){
        art.dialog.open('<?php echo U('ArtDialog/createQr');?>?pid='+id,{width:600,height:400});
    }

    function dele(id){
        art.dialog.confirm('您确定要删除吗？此商品的相关信息都会被删除，请慎重操作',function(){
            art.dialog({content:'正在处理...',lock:true});
            window.location.href="<?php echo U('Products/deletePro');?>?id="+id;
        },function(){
            art.dialog.tips('操作取消',0.5);
        })
    }
    $(document).ready(function(){
        $("#search").submit(function(){
            var name=$("#ProName").val();
            var id=$("#ProId").val();
            var cla=$("#ClassType").val();
            var IsShelves=$('#IsShelves').val();
            if (!name && !id && !cla && !IsShelves) {
                art.dialog.alert('请输入您要查询的信息');
                return false;
            }else{
                art.dialog({content:'正在查询...',lock:true});
                return true;
            }
        })
        $('#import').click(function(){
            var name=$("#ProName").val();
            var id=$("#ProId").val();
            var cla=$("#ClassType").val();
            var IsShelves=$('#IsShelves').val();
            if (!name && !id && !cla && !IsShelves) {
                art.dialog.alert('请输入您要查询的信息');
                return false;
            }else{
                art.dialog.tips('正在处理...',3);
                window.location.href="<?php echo U('Products/search');?>?type=import&statu=key&IsShelves="+IsShelves+"&ProName="+name+"&ProNumber="+id+"&ClassType="+cla;
            }
        })
        $("#searchtime").submit(function(){
            var stime=$("#stime").val();
            var etime=$("#etime").val();
            if (!stime) {
                art.dialog.alert('请选择查询起始时间');
                return false;
            };
            if (!etime) {
                art.dialog.alert('请选择查询结束时间');
                return false;
            };
            if (stime>etime) {
                art.dialog.alert('时间范围错误');
                return false;
            }else{
                art.dialog({content:'正在查询...',lock:true});
                return true;
            }

        })
    })

function hot(pid,id){
    var bq=$("#p"+id).attr('data');
    var statu='1'
    if (bq=='1') {
        var statu='0';
    }
    $.ajax({
        type:"post",
        url:"<?php echo U('Products/showHome');?>",
        data:"pid="+pid+"&statu="+statu+"&type=hot",
        dateType:"json",
        success:function(msg){
            if (msg=='success') {
                art.dialog.tips('设置成功');
                if (bq=='1') {
                    $("#p"+id).attr('style','color:#ccc');
                    $("#p"+id).attr('data','0');
                };
                if (bq=='0' || bq=='') {
                    $("#p"+id).attr('style','color:red');
                    $("#p"+id).attr('data','1');
                };
            };
            if (msg=='error') {
                art.dialog.tips('设置失败');
            };
        }
    })
}
function news(pid,id){
    var bq=$("#n"+id).attr('data');
    var statu='1'
    if (bq=='2') {
        var statu='0';
    }
    $.ajax({
        type:"post",
        url:"<?php echo U('Products/showHome');?>",
        data:"pid="+pid+"&statu="+statu+"&type=new",
        dateType:"json",
        success:function(msg){
            if (msg=='success') {
                art.dialog.tips('设置成功');
                if (bq=='2') {
                    $("#n"+id).attr('style','color:#ccc');
                    $("#n"+id).attr('data','0');
                };
                if (bq=='0' || bq=='') {
                    $("#n"+id).attr('style','color:green');
                    $("#n"+id).attr('data','2');
                };
            };
            if (msg=='error') {
                art.dialog.tips('设置失败');
            };
        }
    })
}

function up(pid,unid){
    var statu='1';
    if ($("#cc"+unid).attr('data')=='1') {
        statu='0';
    };
    $.ajax({
        type:"post",
        url:"<?php echo U('Products/setUp');?>",
        data:"pid="+pid+"&statu="+statu,
        dateType:"json",
        success:function(msg){
            if (msg=='success') {
                art.dialog.tips('操作成功...');
                if (statu=='1') {
                    $("#cc"+unid).attr('class','up glyphicon glyphicon-circle-arrow-down');
                    $("#cc"+unid).attr('title','点击下架');
                    $("#cc"+unid).attr('data','1');
                };
                if (statu=='0') {
                    $("#cc"+unid).attr('class','up glyphicon glyphicon-circle-arrow-up');
                    $("#cc"+unid).attr('title','点击上架');
                    $("#cc"+unid).attr('data','0');
                };
            };
            if (msg=='error') {
                art.dialog.tips('操作失败');
            };
        }
    })
}
//赠品操作
function setzp(pid,id){
    var statu=$('#zp'+id).attr('data-s');
    $.ajax({
        url:"<?php echo U('Products/setProType');?>",
        type:"post",
        data:"type=zp&statu="+statu+"&ProId="+pid,
        dataType:"json",
        success:function(msg){
            if (msg=='success') {
                art.dialog.tips('操作成功');
                if (statu=='1') {
                    $('#zp'+id).attr('data-s','0').attr('title','设为赠品').attr('alt','设为赠品').attr('style','color:#ccc');
                }else{
                    $('#zp'+id).attr('data-s','1').attr('title','取消赠品').attr('alt','取消赠品').attr('style','color:green');
                }
            };
        }
    })

}
//积分操作
function setsc(pid,id){
    var statu=$('#sc'+id).attr('data-s');
    $.ajax({
        url:"<?php echo U('Products/setProType');?>",
        type:"post",
        data:"type=sc&statu="+statu+"&ProId="+pid,
        dataType:"json",
        success:function(msg){
            if (msg=='success') {
                art.dialog.tips('操作成功');
                if (statu=='1') {
                    $('#sc'+id).attr('data-s','0').attr('title','设为积分兑换').attr('alt','设为积分兑换').attr('style','color:#ccc');
                }else{
                    $('#sc'+id).attr('data-s','1').attr('title','取消积分兑换').attr('alt','取消积分兑换').attr('style','color:green');
                }
            };
            if (msg=='noscore') {
                art.dialog.alert('该商品没有设置积分数，请设置积分数');
            }        }
    })
}
</script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>