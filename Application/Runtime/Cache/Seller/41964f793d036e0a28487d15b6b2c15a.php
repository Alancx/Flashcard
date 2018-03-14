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

<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<!--面包屑 标题栏-->
<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px;">
    <div class="row">
        <div class="col-lg-7 col-md-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本信息录入</h5>
                    <div class="ibox-tools">
                        <a class="collapse-card">
                            <i style="font-style: italic;" id="InWarehouseId"><?php echo ($data[0]["InWarehouseId"]); ?></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="padding-bottom: 10px">
                    <form class="form-horizontal m-t" id="commentForm" novalidate="novalidate">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><i class="fa fa-bookmark" style="color:red"></i>入库仓库：</label>
                            <div class="col-sm-8">
                                <select tabindex="1" class="form-control" id="inputWarehouse" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>" <?php if($vo['WarehouseCard'] == $data[0]['InStorehouseId'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">入库人：</label>
                            <div class="col-sm-8">
                                <select data-placeholder="请选择入库人..." class="chosen-select" id="inputName" style="width:200px;" tabindex="2">
                                    <option value="-1">请选择入库人</option>
                                    <?php if(is_array($employee)): foreach($employee as $key=>$vo): ?><option value="<?php echo ($vo["Id"]); ?>" <?php if($vo['Id'] == $data[0]['InputId'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">入库日期：</label>
                            <div class="col-sm-8">
                                <input id="inDate" type="text" class="form-control Wdate" value="<?php echo ($data[0]["Date"]); ?>" name="inDate" required="" aria-required="true" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd HH:mm'})" style="width:200px;display: inline-block;" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                <a type="button" data-container="body" data-toggle="popover" data-placement="right" data-content="如：2015-06-05 15:30" data-original-title="" title="" aria-describedby="popover481307">格式提示</a>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 control-label">入库单号：</label>
                            <div class="col-sm-8">
                                <input id="InWarehouseNumber" type="text" class="form-control" value="<?php echo ($data[0]["InWarehouseNumber"]); ?>" name="InWarehouseNumber" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">入库类型：</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="inType">
                                    <option value="0" <?php if($data[0]['Type'] == 0): ?>selected="selected"<?php endif; ?> >采购入库</option>
                                    <option value="1" <?php if($data[0]['Type'] == 1): ?>selected="selected"<?php endif; ?> >调拨入库</option>
                                    <option value="2" <?php if($data[0]['Type'] == 2): ?>selected="selected"<?php endif; ?> >退货入库</option>
                                    <option value="3" <?php if($data[0]['Type'] == 3): ?>selected="selected"<?php endif; ?> >差错入库</option>
                                    <option value="4" <?php if($data[0]['Type'] == 4): ?>selected="selected"<?php endif; ?> >申请入库</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注说明：</label>
                            <div class="col-sm-8">
                                <textarea id="Remarks" name="comment" value="<?php echo ($data[0]["Remarks"]); ?>" class="form-control" required="" aria-required="true"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label text-info">入库商品：</label>
                            <div class="col-sm-8">
                                <button class="btn btn-block btn-outline btn-primary" type="button" id="SelectPro">查找入库商品</button>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <div class="col-sm-8">
                            <p>
                                <button class="btn btn-outline btn-primary" type="button" id="btnOkIn">提交入库单据</button>
                                <button class="btn btn-outline btn-danger" type="button" id="btnDelIn">删除单据</button>
                                <button class="btn btn-outline btn-info" type="button" id="btnTqIn">提取草稿/调拨单据</button>
                            </p></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-5">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>入库说明信息</h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-warning">
                       1、查找入库商品前需录入入库仓库，采购人，日期，已选入库商品存在，不许修改基本信息数据！<br/>
                       2、提交入库单据：提交该入库单据，同时跳转页面，录入新的入库单据！<br/>
                       3、入库单据实时保存商品信息，未录入的单据，可以通过提取草稿，找到单据，继续录入！<br/>
                       4、多仓库调拨单据在提取调拨单据中可以提取（最多当前日期前30天），无需重复录入入库单！，<br/>
                       5、手工入库单号【非必填项】系统不做唯一性检测，请按照手工单号录入！
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-top: 0px;">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title" style="padding-bottom: 0px;">
                    <h5>已选入库商品<small class="text-muted">入库商品信息</small></h5>
                    <div class="ibox-content" style="padding:10px;">
                        <label class="checkbox-inline"><input type="checkbox" id="chkAll">全选/取消</label>
                        <button class="btn btn-outline btn-danger" type="button" id="selectKC" style="margin-bottom: 0px;">删除所选商品</button>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>上级分类</th>
                                <th>商品名称</th>
                                <th>商品规格编码</th>
                                <th>规格/属性</th>
                                <th>入库数量</th>
                                <th>入库价格</th>
                                <th>合计</th>
                            </tr>
                        </thead>
                        <tbody id="inwarehouselist">
                            <?php if(is_array($data)): foreach($data as $k=>$vo): ?><tr>
                                    <td><input type="checkbox" value="<?php echo ($vo["ProIdCard"]); ?>"/></td>
                                    <td><?php echo ($vo["ClassName"]); ?></td>
                                    <td><?php echo ($vo["ProName"]); ?></td>
                                    <td><?php echo ($vo["ProIdInputCard"]); ?></td>
                                    <td><?php echo ($vo["Spec"]); ?></td>
                                    <td><?php echo ($vo["Count"]); ?></td>
                                    <td><?php echo ($vo["price"]); ?></td>
                                    <td><?php echo ($vo["Money"]); ?></td>
                                </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--底部版权-->

<!--js引用-->

<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Chosen -->
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<!-- WdatePicker -->
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>

<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
    var bindDefaultInfo={
        postNewUrl:"<?php echo U('Invoicing/inwarehouse');?>",
        postProUrl:"<?php echo U('showInWarehouseProdialog');?>",
        postSaveUrl:"<?php echo U('Invoicing/saveInwarehouse');?>",
        postDraftUrl:"<?php echo U('showInWarehouseDraft');?>",
        warelist:"<?php echo U('inwarehouselist');?>"
    };
</script>
<script src="/Public/Admin/Seller/common/js/inwarehouse.js?v=1.32"></script>
<script>NProgress.done();</script>
</body>
</html>