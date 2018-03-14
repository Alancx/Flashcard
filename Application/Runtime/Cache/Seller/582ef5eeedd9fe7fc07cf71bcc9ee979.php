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
    }
    a{
        color: #666;
        font-style: normal;
    }
    a:hover{
        font-style: normal;
        color: #222;
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

.del:hover{
    cursor: pointer;
    background: #ccc;
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
  .up:hover{
    cursor:pointer;
    background: #fff;
}
</style>
<div class="row wrapper border-bottom white-bg page-heading">

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
                    <div class="ibox-content">
                        <?php $i=0 ?>
                        <?php if(is_array($products)): foreach($products as $key=>$pro): ?><div class="pro">
                            <span id="cc<?php echo ($pro["ID"]); ?>" data="<?php echo ($pro["IsShelves"]); ?>" <?php if($pro['IsShelves'] == '1'): ?>class="up glyphicon glyphicon-circle-arrow-down" title="点击下架"<?php else: ?>class="up glyphicon glyphicon-circle-arrow-up" title="点击上架"<?php endif; ?> onclick="up('<?php echo ($pro["ProId"]); ?>','<?php echo ($pro["ID"]); ?>');"></span>
                                <span class="glyphicon glyphicon-remove remove" title="删除" alt="删除" onclick="dele('<?php echo ($pro["ProId"]); ?>')"></span>
                                <a href="<?php echo U('Products/proedit');?>?pid=<?php echo ($pro["ProId"]); ?>">  <div class="pinfo"><img src="<?php echo ($PICURL); echo ($pro["img"]); ?>" alt=""><div style="text-align:left;">商品名称：<?php echo ($pro["ProName"]); ?> <br> 商品编号：<?php echo ($pro["ProNumber"]); ?> <br>销量：<?php echo ($pro["SalesCount"]); ?>  <br>售价： <?php echo number_format($pro['Price'],2) ?> </div></div></a></div>
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
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>