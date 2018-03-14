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

<style type="text/css">
    .up-tr{
        color:red;
    }
</style>
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px;">
	<div class="row">
		<div class="col-lg-6 col-md-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>基本信息录入</h5>
                    <div class="ibox-tools">
                        <a class="collapse-card">
                            <i style="font-style: italic;" id="InventoryId"><?php echo ($data[0]['InventoryId']); ?></i>
                        </a>
                    </div>
				</div>

                <div class="ibox-content" style="padding-bottom: 10px">
                    <form class="form-horizontal m-t" id="commentForm" novalidate="novalidate">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">盘点仓库：</label>
                            <div class="col-sm-8">
                                <select tabindex="1" class="form-control" id="storehouseName" name="storehouseName" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                    <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>" <?php if($vo['WarehouseCard'] == $data[0]['StorehouseId'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">盘点人：</label>
                            <div class="col-sm-8">
                                <select data-placeholder="请选择盘点人..." class="chosen-select" id="inputName" style="width:200px;" tabindex="2">
                                    <option value="-1">请选择盘点人</option>
                                    <?php if(is_array($employee)): foreach($employee as $key=>$vo): ?><option value="<?php echo ($vo["Id"]); ?>" <?php if($vo['Id'] == $data[0]['InputID'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">盘点日期：</label>
                            <div class="col-sm-8">
                                <input id="inDate" type="text" class="form-control Wdate" value="<?php echo ($data[0]['Date']); ?>" name="inDate" required="" aria-required="true" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd HH:mm'})" style="width:200px;display: inline-block;" <?php if(!empty($cid)): ?>disabled="disabled"<?php endif; ?> >
                                <a type="button" data-container="body" data-toggle="popover" data-placement="right" data-content="如：2015-06-05 15:30" data-original-title="" title="" aria-describedby="popover481307">格式提示</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">备注说明：</label>
                            <div class="col-sm-8">
                                <textarea id="Remarks" name="comment" value="<?php echo ($data[0]['Remarks']); ?>" class="form-control" required="" aria-required="true"></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="text-align:center;clear:left;">
                            <label class="col-sm-3 control-label">加载盘点商品：</label>
                            <div class="col-sm-8">
                                <button class="btn btn-block btn-outline btn-primary" type="button" <?php if(!empty($cid)): ?>title="已禁用" disabled="disabled"<?php endif; ?> id="SelectPro">生成盘点商品</button>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <div class="col-sm-8">
                            <p>
                                <button class="btn btn-outline btn-primary" type="button" id="btnOkIn">提交盘点单据</button>
                                <button class="btn btn-outline btn-danger" type="button" id="btnDelIn">删除单据</button>
                                <button class="btn btn-outline btn-info" type="button" id="btnTqIn">提取草稿</button>
                            </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
		</div>

        <div class="col-lg-6 col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>盘点商品信息说明</h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-warning">
                       1、选择盘点仓库，盘点人，日期后，点击“生成盘点商品”加载商品信息！<br/>
                       2、根据盘点仓库的数量，修改实盘数量，该信息修改后，系统实时保存！<br/>
                       3、未盘点完的单据，在提取草稿可以提取到！<br/>
                       4、商品盘点完后，请点击 提交盘点单据 进行结转仓库库存！<br/>
                       5、建议大于或等于一个月盘点一次仓库，即录入一次该单据！
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
					<h5>盘点商品明细<small class="text-danger">点击实盘数量编辑，回车修改实盘数量</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-hover" id="tabInfo">
						<thead>
							<tr>
								<th>#</th>
								<th>上级分类</th>
								<th>商品名称</th>
								<th>商品规格编码</th>
                                <th>规格/属性</th>
								<th>账面库存</th>
								<th>实盘数量</th>
								<th>盘存差</th>
                                <th>操作时间</th>
                                <th>是否上架</th>
							</tr>
						</thead>
                        <?php if(is_array($dataPro)): foreach($dataPro as $k=>$vo): ?><tr>
                                    <td><?php echo ($vo["RowNumber"]); ?></td>
                                    <td><?php echo ($vo["ClassName"]); ?></td>
                                    <td><?php echo ($vo["ProName"]); ?></td>
                                    <td><?php echo ($vo["ProIdInputCard"]); ?></td>
                                    <td><?php echo ($vo["Spec"]); ?></td>
                                    <td><span data-token="<?php echo ($vo["ProIdCard"]); ?>"><?php echo ($vo["BookCount"]); ?></span></td>
                                    <td><?php echo ($vo["ActualCount"]); ?></td>
                                    <td><?php echo ($vo["CountPoor"]); ?></td>
                                    <td><?php echo ($vo["Date"]); ?></td>
                                    <td><?php echo ($vo["IsShelves"]); ?></td>
                                </tr><?php endforeach; endif; ?>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>上级分类</th>
                                <th>商品名称</th>
                                <th>商品规格编码</th>
                                <th>规格/属性</th>
                                <th>账面库存</th>
                                <th>实盘数量</th>
                                <th>盘存差</th>
                                <th>操作时间</th>
                                <th>是否上架</th>
                            </tr>
                        </tfoot>
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
<script src="/Public/Admin/Admin/js/plugins/jeditable/jquery.jeditable.js"></script>

<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- Chosen -->
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<!-- dataTables -->
<script src="/Public/Admin/Admin/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/Public/Admin/Admin/js/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- WdatePicker -->
<script src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
    var bindDefaultInfo={
        postNewUrl:"<?php echo U('Invoicing/inventory');?>",
        postSaveUrl:"<?php echo U('Invoicing/saveInventory');?>",
        postDraftUrl:"<?php echo U('Invoicing/showInventoryDraft');?>",
        cid:""
    };
</script>
<?php if(!empty($cid)): ?><script>
    bindDefaultInfo.cid="<?php echo ($cid); ?>";
</script><?php endif; ?>
<script src="/Public/Admin/Admin/common/js/inventory.js"></script>
<?php if(!empty($cid)): ?><script>
    bindDataPro(0, "");
</script><?php endif; ?>

<?php if(empty($cid)): ?><script>
NProgress.done();
</script><?php endif; ?>
</body>
</html>