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
<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px;">
	<div class="row">
		<div class="col-lg-7 col-md-7">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>基本信息录入 <?php echo $data[0]['Type'];?></h5>
                    <div class="ibox-tools">
                        <a class="collapse-card">
                            <i style="font-style: italic;" id="OutWarehouseId"><?php echo ($data[0]["OutWarehouseId"]); ?></i>
                        </a>
                    </div>
				</div>

                <div class="ibox-content" style="padding-bottom: 10px">
                    <form class="form-horizontal m-t" id="commentForm" novalidate="novalidate">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出库仓库：</label>
                            <div class="col-sm-8">
                                <select tabindex="1" class="form-control" id="OutStorehouse" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>" <?php if($vo['WarehouseCard'] == $data[0]['OutStorehouseId']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出库人：</label>
                            <div class="col-sm-8">
                                <select data-placeholder="请选择采购人..." class="chosen-select" id="OutputName" style="width:200px;" tabindex="2">
                                    <option value="-1">请选择出库人</option>
                                     <?php if(is_array($employee)): foreach($employee as $key=>$vo): ?><option value="<?php echo ($vo["Id"]); ?>" <?php if($vo['Id'] == $data[0]['OutputId'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出库单号：</label>
                            <div class="col-sm-8">
                                <input id="OutWarehouseNumber" type="text" class="form-control" value="<?php echo ($data[0]["OutWarehouseNumber"]); ?>" name="OutWarehouseNumber" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">发货日期：</label>
                            <div class="col-sm-8">
                                <input id="OutDate" type="text" class="form-control Wdate" value="<?php echo ($data[0]["Date"]); ?>" name="OutDate" required="" aria-required="true" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd HH:mm'})" style="width:200px;display: inline-block;" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                <a type="button" data-container="body" data-toggle="popover" data-placement="right" data-content="如：2015-06-05 15:30" data-original-title="" title="" aria-describedby="popover481307">格式提示</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出库类型：</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="OutType" name="OutType" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <option value="2" <?php if(!empty($data)): if($data[0]['Type'] == 2): ?>selected="selected"<?php endif; endif; ?> >线下销售</option>
                                    <option value="0" <?php if(!empty($data)): if($data[0]['Type'] == 0): ?>selected="selected"<?php endif; endif; ?> >调拨出库</option>
                                    <option value="1" <?php if(!empty($data)): if($data[0]['Type'] == 1): ?>selected="selected"<?php endif; endif; ?> >退货出库</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group out-type-0" <?php if(empty($data)): ?>style="display:none"<?php endif; ?> <?php if(!empty($data)): if($data[0]['Type'] == 1): ?>style="display:none"<?php endif; endif; ?> >
                            <label class="col-sm-2 control-label">调入仓库：</label>
                            <div class="col-sm-8">
                                <select tabindex="1" class="form-control" id="InStorehouse" name="InStorehouse" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <option value="-1" >请选择调入仓库</option>
                                    <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>" <?php if($vo['WarehouseCard'] == $data[0]['InStorehouseId']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group out-type-1" <?php if(empty($data)): ?>style="display:none"<?php endif; ?> <?php if(!empty($data)): if($data[0]['Type'] == 0): ?>style="display:none"<?php endif; endif; ?> >
                            <label class="col-sm-2 control-label">目标供应商：</label>
                            <div class="col-sm-8">
                                <select tabindex="1" class="form-control" id="SupplierName" name="SupplierName" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <option value="-1" >请选择退货目标供应商</option>
                                    <?php if(is_array($supplierlist)): foreach($supplierlist as $key=>$vo): ?><option value="<?php echo ($vo["ID"]); ?>" <?php if($vo['ID'] == $data[0]['InStorehouseId'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
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
                            <label class="col-sm-2 control-label text-info">出库/退货商品：</label>
                            <div class="col-sm-8">
                                <button class="btn btn-block btn-outline btn-primary" type="button" id="SelectPro">查找出库/退货商品</button>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <div class="col-sm-12">
                            <p>
                                <button class="btn btn-outline btn-primary" type="button" id="btnOkOut">提交出库单据</button>
                                <button class="btn btn-outline btn-danger" type="button" id="btnDelOut">删除单据</button>
                                <button class="btn btn-outline btn-info" type="button" id="btnTqOut">提取草稿/调拨单据</button>
                            </p></div>
                        </div>
                    </form>
                </div>
            </div>
		</div>

        <div class="col-lg-5 col-md-5">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>出库说明信息</h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-warning">
                       1、查找出库商品前需录入出库仓库，出库人，日期，出库类型，调入仓库或目标供应商<br/>
                       2、已选出库商品存在，不许修改基本信息数据！<br/>
                       3、提交出库单据：提交该出库库单据，同时跳转刷新页面！<br/>
                       3、出库单据实时保存商品信息，未录入的单据，可以通过提取草稿，找到单据，继续录入！<br/>
                       4、未出库的单据，最多保留10天！预期删除。<br/>
                       5、退货出库 实行 一供应商一单据！不同退货商品归属不同供应商，请分退货单录入！<br/>
                       6、线下销售 只作为一个出库单据，无销售出库对象!<br/>
                       7、手工出库单号【非必填项】系统不做唯一性检测，请按照手工单号录入！
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
					<h5>已选出库商品<small class="text-muted">出库商品信息</small></h5>
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
								<th>数量</th>
								<th>价格</th>
								<th>合计</th>
							</tr>
						</thead>
						<tbody id="outwarehouselist">
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
        postNewUrl:"<?php echo U('Invoicing/outwarehouse');?>",
        postProUrl:"<?php echo U('showOutWarehouseProdialog');?>",
        postSaveUrl:"<?php echo U('Invoicing/saveOutwarehouse');?>",
        postDraftUrl:"<?php echo U('showOutWarehouseDraft');?>"
    };
</script>
<script src="/Public/Admin/Seller/common/js/outwarehouse.js"></script>
<script>NProgress.done();</script>
</body>
</html>