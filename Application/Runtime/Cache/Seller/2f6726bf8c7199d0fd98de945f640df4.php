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
<style type="text/css">
    .card{
        width: 400px;
        height: 220px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 50px auto;
        position: relative;
        background: linear-gradient(to left,#fff,#999);
        box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
        color: black;
    }
    .card_title{
        text-align: center;
        font-size: 2em;
        height: 40px;
        margin-bottom: 30px;
        margin-top: 20px;
        color: black;
    }
    .card_rules{
        font-size: 14px;
        line-height: 32px;
        text-align: center;
        color: black;
    }
    .card_bot{
        position: absolute;
        width: 100%;
        left: 0px;
        bottom: 0px;
        height: 20px;
        text-align: center;
        font-size: .8em;
        color: black;
    }
    .vip{
        position: absolute;
        right: 5px;
        top: 5px;
        color: #ccc;
        font-size: 1.5em;
    }
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="alert alert-warning" style="color:red;">
                    1、如需长期有效的优惠券，有效区间设置长时间即可
                </div>
                <div class='col-sm-12 col-md-12'>
                    <h5>优惠券添加</h5>
                </div>
            </div>
            <div class="ibox-content">
                <div class='row'>
                    <div class='col-md-6 col-lg-6'>
                        <form id='fs' method='post' action="<?php echo U('Activity/saveCoupon');?>">
                            <div class="form-group">
                                <label for="">优惠券类型</label>
                                <select name="CouponType" id="CouponType" class='form-control'>
                                    <option value="">请选择</option>
                                    <!-- <option value="0" <?php if($info['Type'] == '0'): ?>selected='selected'<?php endif; ?>>红包抵扣券</option> -->
                                    <option value="2" <?php if($info['Type'] == '2'): ?>selected='selected'<?php endif; ?>>满减券</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">优惠规则</label>
                                <input type="text" name="Rules" id="Rules" value="<?php echo ($info["Rules"]); ?>" class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label for="">优惠券数量</label>
                                <input type="number" name="CouponNum" min='1' id="CouponNum" value="<?php echo ($info["Count"]); ?>" class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label for="">生效时间</label>
                                <input type="text" name="StartDate" value="<?php echo ($info["StartDate"]); ?>" id="StartDate" class='form-control' onClick="WdatePicker({el:'StartDate',onpicked:function(){showtime(this)},dateFmt:'yyyy-MM-dd HH:00:00',minDate:'%y-%M-{%d+0}'})">
                            </div>
                            <div class='form-group'>
                                <label for="">失效时间</label>
                                <input type="text" name="ExpDate" value="<?php echo ($info["ExpiredDate"]); ?>" id="ExpDate" class='form-control' onClick="WdatePicker({el:'ExpDate',onpicked:function(){showtime(this)},dateFmt:'yyyy-MM-dd HH:00:00',minDate:'%y-%M-%d {%H+1}:00:00'})">
                            </div>
                            <!--               <div class='form-group'>
                                            <label for="">限领数量</label>
                                            <input type="number" name="GetNum" id="GetNum" min='1' value='<?php echo ($info["UserCount"]); ?>' class='form-control'>
                                          </div>
                             -->              <input type="hidden" name="oldid" value="<?php echo ($info["ID"]); ?>">
                            <button class='btn btn-primary btn-outline' type='submit'>保存</button>
                        </form>
                    </div>
                    <div class='col-md-6 col-lg-6'>
                        <div class='card'>
                            <span class='vip'>VIP</span>
                            <div class='card_title'><?php echo ($info["CouponName"]); ?></div>
                            <div class='card_rules'><?php if($info['Type'] == '0'): ?>平台商品使用立减<?php echo ($info["Rules"]); ?>元<?php elseif($info['Type'] == '2'): $rs=explode('/', $info['Rules']); ?> 订单满 <?php echo ($rs[0]); ?> 立减 <?php echo ($rs[1]); endif; ?></div>
                            <div class='card_bot'>有效时间： <span id='start'><?php echo ($info["StartDate"]); ?></span> -- <span id='exp'><?php echo ($info["ExpiredDate"]); ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="order_message" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width:428px;">
        <div class="modal-content">
            <div class="modal-header" style="padding:10px 15px;">
                模态框 <span style="color:red" id='add_notice'></span>
                <button type="button" class="close" data-dismiss="modal" id="cls_modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body" style="padding:15px">

            </div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-w-m btn-success input-sm" data-gid='' id="btn_addrules">提交</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var cardtype='';
    $(document).ready(function(){
        $('#fs').submit(function(){
            if ($('#CouponType').val() && $('#Rules').val() && $('#CouponNum').val() && $('#StartDate').val() && $('#ExpDate').val()) {
                return true;
            }else{
                art.dialog.tips('请填写完整信息');
                return false;
            }
        })
        $('#CouponType').change(function(){
            var type=$('#CouponType option:selected').val();
            // console.log(type);
            cardtype=type;
            if (type) {
                update('title',type);
            }else{
                update('title','null');
            }
        })
        $('#Rules').keyup(function(){
            var rule=$(this).val();
            update('rule',rule);
        })
        // $('#')
    })
    function update(type,value){
        if (type=='title') {
            if (value=='0') {
                $('.card_title').html('红包抵扣券');
                $('#Rules').attr('placeholder','直接输入抵扣金额即可');
            }else if (value=='2') {
                $('.card_title').html('满减券');
                $('#Rules').attr('placeholder','满xx减yy  输入xx/yy 即可');
            }else{
                $('.card_title').html('');
            }
        }else if (type=='rule') {
            if (cardtype=='0') {
                $('.card_rules').html('平台商品使用立减'+value+'元');
            }else if (cardtype=='2') {
                var rs=value.split('/');
                $('.card_rules').html('满'+rs[0]+'减'+rs[1]+'元');
            };
        };
    }
    function showtime(ti){
        var datatype=$(ti).attr('id');
        if (datatype=='StartDate') {
            if ($(ti).val()>$('#ExpDate').val() && $('#ExpDate').val()) {
                art.dialog.tips('非法时间段');
                $(ti).val('');
            };
            $('#start').html($(ti).val());
        }else if (datatype=='ExpDate') {
            if ($(ti).val()<$('#StartDate').val() && $('#StartDate').val()) {
                art.dialog.tips('非法时间段');
                $(ti).val('');
            };
            $('#exp').html($(ti).val());
        };

    }
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>