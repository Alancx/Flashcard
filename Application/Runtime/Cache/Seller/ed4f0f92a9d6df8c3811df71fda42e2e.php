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
  .p-box img{
    width: 80%;
  }
  .p-box{
    border-bottom: 1px solid #ccc;
    margin-top: 5px;
  }
</style>
</head><body><script>NProgress.start();$('.dropdown').click(function(){if ($('.dropdown-menu').attr('data-s')=='1') {$('.dropdown-menu').show().attr('data-s','0')}else{$('.dropdown-menu').hide().attr('data-s','1')};})</script><div id="wrapper"><nav class="navbar-default navbar-static-side" role="navigation"><div class="sidebar-collapse"><ul class="nav" id="side-menu"><li class="nav-header" style="text-align:center;padding-top:15px;"><span class='display:bolck;float:left;'><img alt="image" id="headimg" class="img-circle" src="/Public/Admin/Admin/img/headimg/<?php echo (session('HeadImgUrl')); ?>" style="width:65px;height:65px;border:1px solid #ccc;"/></span><div style="text-align:center;float:right;"><span style='font-weight:bold;color:white;font-size:15px;clear:both;'><?php echo (session('Sname')); ?></span><div class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><span class="clear"><span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['seller']['userinfo']['userName']; ?></strong></span><span class="text-muted text-xs block"><?php echo (session('Gname')); ?><b class="caret"></b></span></span></a><ul data-s='1' class="dropdown-menu animated fadeInRight m-t-xs"><li><a href="<?php echo U('My/head');?>">修改头像</a></li><!-- <li><a href="<?php echo U('My/info');?>">个人资料</a></li> --><li><a href="<?php echo U('My/modpass');?>">修改密码</a></li><li class="divider"></li><li><a href="<?php echo U('Public/logout');?>">安全退出</a></li></ul></div></div><div class="logo-element"></div></li><li title='主页' <?php if(CONTROLLER_NAME == 'Index'): ?>class='active'<?php endif; ?>>
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
       <h2><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="mini" href="#" style="margin-top:0px;margin-left:0px;"><i class="fa fa-bars"></i> </a>优惠管理</h2>
       <ol class="breadcrumb">
           <li>
               <a href="index.html">主页</a>
           </li>
           <li class="active">
               <strong>组合优惠管理</strong>
           </li>
       </ol>
   </div>
   <div class="col-lg-2"></div>
</div>

<div class="row wrapper  white-bg" style="margin:0px 1%;">
    <div class="col-lg-12">
       <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>添加组合</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form role="form" class="form-inline" action="" method="post" id="save">
                <div class="form-group">
                <div class="input-group">
                	<div class="input-group-addon">
                		<button class="btn btn-primary btn-outline btn-xs" type="button" id="getGid">重新生成ID</button>
                	</div>
                    <input type="text"  name="GroupId" id="GroupId" value="<?php echo ($GroupId); ?>" readonly="true" class="form-control">
                </div>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="请填写组合名称" name="GroupName" id="GroupName" class="form-control">
                </div>

                <div class="form-group">
                    <input type="text" name="SDate" id="SDate" placeholder="开始日期" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-{%d}'})" class="form-control">
                </div>

                <div class="form-group">
                    <input type="text" name="EDate" id="EDate" placeholder="结束日期" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-{%d+1}'})" class="form-control">
                </div>

                <div class="checkbox m-l m-r-xs">
                <input type="hidden" name="type" id="type" value="add">
                  <button class="btn btn-white" type="submit" id="btn-submit">保存</button>
                    </form>
                </div>
                <div class="alert alert-warning">

                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <h3>组合管理</h3>
            <table class="table table-bordered table-hover">
              <tr>
                 <td style="width:10%;">#</td>
                 <td style="width:10%;">组合名称</td>
                 <td style="width:50%;">组合商品</td>
                 <td style="width:20%;">生效时间</td>
                 <td style="width:10%;">操作</td>
             </tr>
             <?php if(is_array($lists)): foreach($lists as $key=>$list): ?><tr>
                 <td><?php echo ($list["GroupId"]); ?></td>
                 <td><?php echo ($list["GroupName"]); ?></td>
                 <td><div id="add<?php echo ($list["GroupId"]); echo ($list["ID"]); ?>">
                   <?php if(is_array($list["pros"])): foreach($list["pros"] as $key=>$pro): ?><div class="row p-box" data-pid='<?php echo ($pro["ProIdCard"]); ?>' id="<?php echo ($pro["ProIdCard"]); echo ($list["GroupId"]); ?>">
                     <div class="col-xs-3 col-sm-3 col-md-3">
                       <img src="<?php echo ($pro["ProLogoImg"]); ?>" alt="">
                     </div>
                     <div class="col-xs-7 col-sm-7 col-md-7">
                     <?php echo ($pro["ProName"]); ?><br>
                     <?php echo ($pro["ProSpec1"]); ?>/<?php echo ($pro["ProSpec2"]); ?>/<?php echo ($pro["ProSpec3"]); ?>
                     <br>
                     原价：<?php echo ($pro["oldPrice"]); ?>  
                     <br>
                     组合价：<?php echo ($pro["newPrice"]); ?>
                     </div>
                     <div class="col-xs-2 col-sm-2 col-md-2">
                       <button class="btn btn-xs btn-danger btn-outline" type="button" onclick="delpro('<?php echo ($pro["ProIdCard"]); ?>','<?php echo ($list["GroupId"]); ?>')">删除此商品</button>
                     </div>
                   </div><?php endforeach; endif; ?>
                 </div><button class="btn btn-xs btn-primary btn-outline btn-chose" style="margin-top:10px;text-align:center;" type="button" data-gid="<?php echo ($list["GroupId"]); ?>" data-id="<?php echo ($list["ID"]); ?>" onclick="chosePro('<?php echo ($list["ID"]); ?>','<?php echo ($list["GroupId"]); ?>');">添加商品</button></td>
                 <td><?php echo ($list["SDate"]); ?> 至 <br><?php echo ($list["EDate"]); ?></td>
                 <td><a href="###" onclick="edit('<?php echo ($list["GroupId"]); ?>');">修改</a> | <a href="###" onclick="del('<?php echo ($list["GroupId"]); ?>');">删除</a></td>
             </tr><?php endforeach; endif; ?>
     </table>
     <div style="text-align:right;margin-bottom:100px;"><?php echo ($page); ?></div>
 </div>
</div>
</div>
<script type="text/javascript">
var json=<?php echo ($jsondata); ?>;
function edit(id){
	$.each(json,function(index,item){
		if (item.GroupId==id) {
			$('#GroupId').val(item.GroupId);
			$('#GroupName').val(item.GroupName);
			$('#SDate').val(item.SDate);
			$('#EDate').val(item.EDate);
			$('#type').val('edit');
		};
	})
}
function del(id){
	art.dialog.confirm('此操作将该组合内商品一并删除，确定要删除吗？',function(){
		window.location.href="<?php echo U('Products/delgroupdiscount');?>?gid="+id;
	},function(){
		art.dialog.tips('取消操作',1);
	})
}

function chosePro(id,gid){
	art.dialog.data('thisid',id);
	art.dialog.data('groupid',gid);
	art.dialog.open("<?php echo U('ArtDialog/chosePro');?>",{width:800,height:600});
}

function delpro(id,gid){
  art.dialog.confirm('确定要把此商品从组合中删除吗？',function(){
    var notice=art.dialog({content:'正在处理...',lock:true});
    $.ajax({
      type:"post",
      url:"<?php echo U('Products/delbyproidcard');?>",
      data:"ProIdCard="+id+"&GroupId="+gid,
      dataType:"json",
      success:function(msg){
        notice.close();
        if (msg=='success') {
          art.dialog.tips('删除成功',1);
          $('#'+id+gid).remove();
        }else if (msg=='cant') {
          art.dialog.alert('删除失败！删除后将于其他组合重复！');
        }else{
          art.dialog.tips('删除失败',1);
        }
      }
    })
    // window.location.href="<?php echo U('Products/delbyproidcard');?>?ProIdCard="+id+"&GroupId="+gid;
  },function(){
    art.dialog.tips('取消操作');
  })
}


  $(document).ready(function(){
  	$('#save').submit(function(){
  		var GroupId=$('#GroupId').val();
  		var GroupName=$('#GroupName').val();
  		var SDate=$('#SDate').val();
  		var EDate=$('#EDate').val();
  		if (!GroupId) {
  			art.dialog.alert('ID丢失，请刷新页面或重新生成');
  			return false;
  		};
  		if (!GroupName) {
  			art.dialog.alert('请输入组合名称');
  			return false;
  		};
  		if (SDate && EDate) {
  			art.dialog.tips('正在处理...',10);
  			return true;
  		}else{
  			art.dialog.alert('请选择完整时间段');
  			return false;
  		}
  	})
  	$('#getGid').click(function(){
  		$.ajax({
  			type:"post",
  			url:"<?php echo U('Products/getGid');?>",
  			data:"oldgid="+$('#GroupId').val(),
  			dataType:"json",
  			success:function(msg){
  				if (msg.statu=='success') {
  					$('#GroupId').val(msg.newGid);
  					$('#GroupName').val('');
  					$('#SDate').val('');
  					$('#EDate').val('');
  					$('#type').val('add');
  				}else{
  					art.dialog.tips('获取失败',2);
  				}
  			}
  		})
  	})
  // 	$('btn-chose').click(function(){
  // 		var GroupId=$(this).attr('data-gid');
  // 		var domid='add'+$(this).attr('data-id');
		// art.dialog.data('thisid',domid);
		// art.dialog.data('groupid',GroupId);
		// art.dialog.open("<?php echo U('ArtDialog/chosePro');?>");
  // 	})
  })
</script>
<div class="footer"><div class="pull-right"></div><div><strong>Copyright</strong> Store &copy; 2016</div></div></div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>