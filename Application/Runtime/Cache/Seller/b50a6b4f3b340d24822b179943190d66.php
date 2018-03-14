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

<link href="/Public/Admin/Admin/common/css/order.style.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<style type="text/css">
ul,ol,li{
    list-style: none;
    margin: 0;
    padding: 0;
}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
        <form action="<?php echo U('Order/saccess');?>" class="form-inline" method="post" id="saccess">
            <div class="form-group">
                <select name="ProS" id="ProS" class="form-control">
                    <option value="">请选商品评分</option>
                            <option value="5">5分</option>
                            <option value="4">4分</option>
                            <option value="3">3分</option>
                            <option value="2">2分</option>
                            <option value="1">1分</option>
                </select>
            </div>
            <div class="form-group">
                <select name="SerS" id="SerS" class="form-control">
                    <option value="">请选服务评分</option>
                            <option value="5">5分</option>
                            <option value="4">4分</option>
                            <option value="3">3分</option>
                            <option value="2">2分</option>
                            <option value="1">1分</option>
                </select>
            </div>
            <div class="form-group">
                <select name="LogS" id="LogS" class="form-control">
                    <option value="">请选物流评分</option>
                            <option value="5">5分</option>
                            <option value="4">4分</option>
                            <option value="3">3分</option>
                            <option value="2">2分</option>
                            <option value="1">1分</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="stime" id="stime" class="form-control" placeholder="开始时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
            </div>
            <div class="form-group">
                <input type="text" name="etime" id="etime" class="form-control" placeholder="结束时间" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
            </div>
            <div class="form-group">
                <input type="text" name="MemberId" placeholder="请填写会员账号（选填）" id="MemberId" class="form-control">
            </div>
            <button class="btn btn-primary btn-outline btn-md"><span class="glyphicon glyphicon-search"></span> 搜 索</button> <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span>导 出</button>
        </form>

    </div>
        <div class="col-lg-12">
            <div class="ibox-content dash-ibox-content">
                <?php if($status): ?>您查询的信息不存在
                <?php else: ?>
                    <div class="ibox-content" >
                        <div class="col-sm-6"><h3>评价内容</h3></div>
                        <div class="col-sm-3"><h3>评价人</h3></div>
                        <div class="col-sm-3"><h3>订单信息</h3></div>
                        <div style="clear:both;"></div>
                    </div>
                    <?php if(is_array($assess)): foreach($assess as $key=>$ass): ?><div class="ibox-content" >
                        <div class="col-sm-1" style="border-right:1px solid #ccc;">商品描述：<?php echo ($ass["ClassScore"]); ?> 分<br>服务评价：<?php echo ($ass["ServiceScore"]); ?> 分 <br>物流评价：<?php echo ($ass["LogisticsScore"]); ?> 分</div>
                        <div class="col-sm-4"><?php echo ($ass["Content"]); ?></div>
                        <div class="col-sm-3">买家：<?php echo ($ass["MemberName"]); ?> <br><br> 评价时间：<?php echo ($ass["ctime"]); ?></div>
                        <div class="col-sm-3">订单编号：<?php echo ($ass["OrderId"]); ?>   <br>商品名称：<?php echo ($ass["ProName"]); ?></div>
                        <div style="clear:both;"></div>
                    </div><?php endforeach; endif; endif; ?>
            </div>
            <div style="text-align:right;"><?php echo ($page); ?></div>
        </div>
    </div>
</div>
<!--底部版权-->

<!--js引用-->
<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/Admin/Admin/js/plugins/echarts/echarts-all.js"></script>
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/Admin/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript">NProgress.done()</script>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $('#saccess').submit(function(){
            var stime=$("#stime").val();
            var etime=$('#etime').val();
            var ProS=$('#ProS').val();
            var SerS=$('#SerS').val();
            var LogS=$('#LogS').val();
            var MemberId=$('#MemberId').val();
            if (!stime && !etime && !ProS && !LogS && !SerS && !MemberId) {
                art.dialog.alert('请至少选择一项查询条件');
                return false;
            };
            if (stime || etime) {
                if (stime && etime) {
                    if (stime>etime) {
                        art.dialog.alert('非法时间');
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    art.dialog.alert('请选择完整的时间段');
                    return false;
                }
            }else{
                return true;
            }
        })

        $('#import').click(function(){
            var stime=$("#stime").val();
            var etime=$('#etime').val();
            var ProS=$('#ProS').val();
            var SerS=$('#SerS').val();
            var LogS=$('#LogS').val();
            var MemberId=$('#MemberId').val();
            if (!stime && !etime && !ProS && !LogS && !SerS && !MemberId) {
                art.dialog.alert('请至少选择一项查询条件');
                return false;
            };
            if (stime || etime) {
                if (stime && etime) {
                    if (stime>etime) {
                        art.dialog.alert('非法时间');
                        return false;
                    }else{
                        art.dialog.tips('正正处理...',3);
                        window.location.href="<?php echo U('Order/assessOut');?>?stime="+stime+"&etime="+etime+"&ProS="+ProS+"&SerS="+SerS+"&LogS="+LogS+"&MemberId="+MemberId;
                        return true;
                    }
                }else{
                    art.dialog.alert('请选择完整的时间段');
                    return false;
                }
            }else{
                art.dialog.tips('正正处理...',3);
                window.location.href="<?php echo U('Order/assessOut');?>?stime="+stime+"&etime="+etime+"&ProS="+ProS+"&SerS="+SerS+"&LogS="+LogS+"&MemberId="+MemberId;
                return true;
            }
        })
    })
</script>
</html>