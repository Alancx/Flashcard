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

<link href="/Public/Admin/Admin/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<!--面包屑 标题栏-->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12" style="padding:0px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>入库信息检索</h5>

                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="col-lg-12 col-md-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content" style='padding:0px;'>
                                <div class="alert alert-warning" style='margin:0px;color:red;font-weight:bold;'>
                                   <!-- 1、7天内未處理的單據將過期作廢<br/> -->
                                   1、7天内未处理的单据将过期作废<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form role="form" class="form-inline" method="post">
                        <div class="form-group">
                            <div class="input-daterange input-group" id="datepicker">
                                <span class="input-group-addon text-info"
                                      style="font-size:12px;border-width:0px;background-color: #fff;">入库日期：</span>
                                <input type="text" class="form-control" placeholder="开始日期" id="dateStart"
                                       name="dateStart" value="<?php echo ($datatime["dateStart"]); ?>" style="width:100px;">
                                <span class="input-group-addon">到</span>
                                <input type="text" class="form-control" placeholder="结束日期" id="dateEnd" name="dateEnd"
                                       style="width:100px;" value="<?php echo ($datatime["dateEnd"]); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn open" style="border-right-width:0px;">
                                    <select class="form-control" style="font-size:12px;border-right-width:0px;"
                                            id="Warehouse" name="Warehouse">
                                        <?php if(is_array($warehouseList)): foreach($warehouseList as $key=>$vo): ?><option value="<?php echo ($vo["WarehouseCard"]); ?>"><?php echo ($vo["WarehouseName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="input-group-btn open" style="border-right-width:0px;">
                                    <select class="form-control" style="font-size:12px;border-right-width:0px;" id="intype" name="intype">
                                        <option value="-1">全部</option>
                                        <option value="0" >采购入库</option>
                                        <option value="1" >调拨入库</option>
                                        <option value="2" >退货入库</option>
                                        <option value="3" >差错入库</option>
                                    </select>
                                </div>
                                <input type="text" id="incard" name="incard" class="form-control"
                                       placeholder="请输入入库单号...">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn open" style="border-right-width:0px;">
                                    <select class="form-control" style="font-size:12px;border-right-width:0px;"
                                            id="proType">
                                        <option value="">全部分类</option>
                                        <?php if(is_array($typeList)): foreach($typeList as $key=>$vo): ?><option value="<?php echo ($vo["ClassId"]); ?>"><?php echo ($vo["ClassName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <input type="text" id="proid" name="proid" class="form-control"
                                       placeholder="请输入商品名称或规格编号...">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline btn-primary btnselect" type="button" data-type="first"
                                    style="margin-bottom: 0px;width:80px;" id="btnSeloK"><i class="fa fa-search"></i>&nbsp;&nbsp;检索
                            </button>
                        </div>
                    </form>
                </div>
                <!-- <div class="chat-discussion"> -->
                <!-- 查询数据 -->
                <div class="ibox-content timeline">
                </div>
                <!-- </div> -->
                <div class="ibox-content">
                    <table cellspacing="0" cellpadding="0" border="0" class="ui-pg-table" style="width:100%;table-layout:fixed;height:100%;" role="row">
                    <tbody>
                        <tr>
                            <td style="text-align:left;">
                                每次
                                <select name="select-page-size" id="select-page-size" title="更改后重新查询起效" aria-controls="editable" class="input-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50" selected="selected">50</option>
                                    <option value="100">100</option>
                                </select> 条记录
                            </td>
                            <td style="text-align:center;">
                                <a href="javascript:;" data-type="more" style="display:none;" class="btn btn-block btn-outline btn-default btnselect" id="btnmore">加载更多</a>
                                <input type="hidden" value="0" id="hf-page-index" />
                                <input type="hidden" value="25" id="hf-page-count" />
                            </td>
                            <td style="text-align:right;">
                                显示  <span class="text-default" id="sp-page-start">1</span>
                                到  <span class="text-default" id="sp-page-end">0</span>
                                共  <span class="text-default" id="sp-page-count"></span> 项
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">微信支付<small id='pay_notice' style='color:red;font-weight:bold;'></small></h4>
      </div>
      <div class="modal-body" style="text-align:center;">
        <img src="" id="wxpayimg" style="width:150px;height:150px;" alt="">
        <br>
        请使用微信扫码支付
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="pay_box">关闭</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--底部版权-->

<!--js引用-->
<!-- Mainly scripts -->
<script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>
<script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Data picker -->
<script src="/Public/Admin/Admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<!-- validatereg -->
<script src="/Public/Admin/Admin/common/js/validaterReg.js"></script>
<!-- artDialog -->
<script src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/Admin/artDialog/plugins/iframeTools.source.js"></script>
<script>
    var bindinfo = {
        postUrl: "<?php echo U('Invoicing/getinwarehouselist');?>",
        postDelURl:"<?php echo U('Invoicing/saveInwarehouse');?>",
        postcontinueUrl:"<?php echo U('Invoicing/inwarehouse');?>",
        autoinw:"<?php echo U('Invoicing/autoinw');?>",
        stimeEmpty: "开始日期不能为空!",
        etimeEmpty: "结束日期不能为空!",
        stimeError: "开始日期格式错误，格式为：yyyy-MM-dd",
        etimeError: "结束日期格式错误，格式为：yyyy-MM-dd",
        setimeplus: "开始日期小于结束日期！",
        emptyinfo: "未检索到查询条件数据...",
        infoerror: "查询出现异常数据，输出失败！"
    };
    var getpay="<?php echo U('Invoicing/getwxpayqr');?>";
    var qrurl="<?php echo U('Invoicing/getwxqr');?>";
    var notifyurl="<?php echo U('Invoicing/notify');?>";
    var _tmpdom='';
    var cid='';
</script>
<script src="/Public/Admin/Seller/common/js/inwarehouselist.js?v=1.15"></script>
<script>NProgress.done();</script>
</body>
</html>