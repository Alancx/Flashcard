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
 <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>座位详情</h5>
                    </div>
                    <div class="ibox-content">
                       
                        <form role="form" id="saveattr" method="post" class="form-inline">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class='input-group-addon'>座位号</span>
                                    <input type="text" name='Tname' placeholder="座位号" id="Groupname" class="form-control">
                                </div>
                            </div>
                            <!-- <input type="hidden" name="Id" value='' id="Id"> -->
                            <button class="btn btn-primary btn-outline" id="savebtn" type="submit">添加</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
            <div class="ibox float-e-margins" style="margin-top:-25px;">
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>座位号</th>
                                    <th>添加时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($allpos)): foreach($allpos as $key=>$list): ?><tr>
                                        <td><?php echo ($list["Tname"]); ?>号</td>
                                        <td><?php echo ($list["CreateDate"]); ?></td>
                                        <td><button class="btn btn-xs btn-danger btn-outline del" data-id='<?php echo ($list["ID"]); ?>'>删除</button>&emsp;<button class="btn btn-xs btn-default auto" data-id='<?php echo ($list["Tname"]); ?>' data-toggle='modal' data-target='#order_message'>生成二维码</button></td>
                                    </tr><?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal inmodal fade" id="order_message" tabindex="-1" role="dialog"  >
                <div class="modal-dialog modal-sm" style="width:428px;">
                    <div class="modal-content">
                        <div class="modal-header" style="padding:10px 15px;">
                            桌号二维码
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding:15px;text-align:center">
                            <div>
                            <!-- <img  src="<?php echo U('Seat/getQr');?>" alt=""> -->
                                <span id="img"></span>
                            </div>
                            <div>
                                <span style="font-weight: bold;">点击图片右键可保存</span>
                            </div>

                        </div>
                        <div class="modal-footer" style="text-align:center;">
                            <button type="button" class="btn btn-w-m btn-success input-sm" id="btn_message" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>
     <script type="text/javascript">
        $(document).ready(function() {
            //将input数据提交到控制器
            $('#saveattr').submit(function(){
                if ($('#Groupname').val()) {
                    return true;
                }else{
                    layer.msg('请填写座位号');
                    $('#Groupname').focus();
                    return false;
                }
            })

            $('.auto').on('click',function() {
                var id = $(this).attr('data-id');//获取ID的值
                 $("#img").html('<img src="<?php echo U('Seat/getQr');?>?id='+id+'" alt="" />');//把二维码写到span标签里面
            })
            //删除事件
            $('.del').on('click',function() {
                var id = $(this).attr('data-id');
                var _this = $(this);    //在ajax里面不能使用$(this)需要转换成_this形式 
                layer.confirm('确定要删除吗？',{
                    btn:['确定','再想想'],
                    shade:false
                },function(){
                    layer.msg('处理中...');
                    $.ajax({
                        url:"<?php echo U('Seat/del');?>",
                        type:'post',
                        data:'id='+id,
                        dataType:'json',
                        success:function(msg) {
                            if(msg.status=='success') {
                                layer.msg("删除成功");
                                //window. location.reload();//删除成功后刷新页面
                                _this.parent().parent().remove();
                            }else {
                                layer.msg(msg.info);
                            }
                        }
                    })
            })

         })
        })
    </script> 
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>