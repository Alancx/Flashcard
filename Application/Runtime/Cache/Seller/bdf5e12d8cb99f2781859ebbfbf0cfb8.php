<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>未入库单据查询</title>
    <link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">

    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css?v=2.2.0" rel="stylesheet">

    <link href="/Public/Admin/Admin/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <style type="text/css">
        body {
            background-color: #F3F3F4;
            width: 800px;
            height: 500px;
        }
        .href-data:hover{text-decoration: underline;}
    </style>
    <script>
        var bindinfo={
            postUrl:"<?php echo U('Invoicing/inwarehouse');?>",
            stimeEmpty:"开始日期不能为空!",
            etimeEmpty:"结束日期不能为空!",
            stimeError:"开始日期格式错误，格式为：yyyy-MM-dd",
            etimeError:"结束日期格式错误，格式为：yyyy-MM-dd",
            setimeplus:"开始日期小于结束日期！",
            stime_0:"最多提取当前日期前7天的单据！",
            stime_1:"最多提取当前日期前30天的单据!"
        };
    </script>
</head>
<body>
<div class="row">
    <div class="col-lg-9">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form role="form" class="form-inline" method="post" action="<?php echo U('Invoicing/showInWarehouseDraft');?>" id="formselect">
                    <div class="form-group" style="margin-right:30px;">
                        <select class="form-control input-sm" id="intype" name="intype" tabindex="1" style="font-size:12px;">
                            <option value="0" <?php if($p['type'] == 0): ?>selected="selected"<?php endif; ?>>采购草稿单</option>
                            <option value="1" <?php if($p['type'] == 1): ?>selected="selected"<?php endif; ?>>调拨确认单</option>
                            <option value="2" <?php if($p['type'] == 2): ?>selected="selected"<?php endif; ?>>退货入库草稿单</option>
                            <option value="3" <?php if($p['type'] == 3): ?>selected="selected"<?php endif; ?>>差错入库草稿单</option>
                            <option value="4" <?php if($p['type'] == 4): ?>selected="selected"<?php endif; ?>>入库申请单</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <small class="text-info">入库日期：</small>
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" placeholder="开始日期" id="dateStart" name="dateStart" value="<?php echo ($p["dateStart"]); ?>" style="width:120px;">
                            <span class="input-group-addon">到</span>
                            <input type="text" class="input-sm form-control" placeholder="结束日期" id="dateEnd" name="dateEnd" style="width:120px;" value="<?php echo ($p["dateEnd"]); ?>">
                        </div>
                    </div>
                    <button class="btn btn-outline btn-primary" id="selectRK" type="submit"
                            style=" margin-bottom: 0px;width:80px;">查找
                    </button>
                    <input type="hidden" value="<?php echo ($p["w"]); ?>" id="warehouse" name="warehouse"/>
                </form>
            </div>
            <div class="ibox-content">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>系统入库单号</th>
                        <th>入库日期</th>
                        <th>入库人</th>
                        <th>入库类型</th>
                        <th>备注说明</th>
                    </tr>
                    </thead>
                    <tbody id="ProSpectbody">
                       <?php if(is_array($data)): foreach($data as $k=>$vo): ?><tr>
                                <td><?php echo ($k+1); ?></td>
                                <td><a class="href-data"><?php echo ($vo["InWarehouseId"]); ?></a></td>
                                <td><?php echo ($vo["Date"]); ?></td>
                                <td><?php echo ($vo["InputName"]); ?></td>
                                <td>
                                    <?php if($vo['Type'] == 0): ?>采购入库
                                    <?php elseif($vo['Type'] == 1): ?>调拨入库
                                    <?php elseif($vo['Type'] == 2): ?>退货入库
                                    <?php elseif($vo['Type'] == 3): ?>差错入库
                                    <?php elseif($vo['Type'] == 4): ?>入库申请
                                    <?php else: endif; ?>
                                </td>
                                <td><?php echo ($vo["Remarks"]); ?></td>
                            </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<!-- validatereg -->
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- Data picker -->
<script src="/Public/Admin/Admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {

        var dateStart = $("#dateStart");
        var dateEnd = $("#dateEnd");

        // 日历控件
        dateStart.datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });

        // 日历控件
        dateEnd.datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            defaultDate: 0
        });
        // 跳转
        $(document).on("click", ".href-data", function() {
            parent.window.location.href = bindinfo.postUrl + "?cid=" + $.trim($(this).html());
        });

        $("#formselect").submit(function() {
            var s = $.trim(dateStart.val());
            if (validateRules.isNull(s)) {
                parent.art.dialog.alert(bindinfo.stimeEmpty);
                return false;
            } else {
                if (!validateRules.isDate(s)) {
                    parent.art.dialog.alert(bindinfo.stimeError);
                    return false;
                }
            }
            var e = $.trim(dateEnd.val());
            if (validateRules.isNull(e)) {
                parent.art.dialog.alert(bindinfo.etimeEmpty);
                return false;
            } else {
                if (!validateRules.isDate(e)) {
                    parent.art.dialog.alert(bindinfo.etimeError);
                    return false;
                }
            }
            if (Date.parse(s) > Date.parse(e)) {
                parent.art.dialog.alert(bindinfo.setimeplus);
                return false;
            }
            var t = $("#intype").val();
            if (t == "0" || t=="3") {
                if (dateDiff(s, new Date()) > 8) {
                    parent.art.dialog.alert(bindinfo.stime_0);
                    return false;
                }
            }
            if (t == "1" || t=="2") {
                if (dateDiff(s, new Date()) > 30) {
                    parent.art.dialog.alert(bindinfo.stime_1);
                    return false;
                }
            }
            parent.art.dialog.tips("正在查询", 0.1, false);
            return true;
        });
    });
</script>
</body>
</html>