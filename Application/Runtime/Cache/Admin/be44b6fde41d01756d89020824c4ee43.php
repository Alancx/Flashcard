<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>商品添加</title>
    <link href="/Public/newadmin/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/Public/newadmin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/newadmin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/newadmin/css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="/Public/newadmin/css/animate.min.css" rel="stylesheet">
    <link href="/Public/newadmin/css/style.min.css?v=4.0.0" rel="stylesheet">
    <link href="/Public/newadmin/css/demo/style.min.css?v=4.0.0" rel="stylesheet">

    <style type="text/css">
    .upimg,.showimg{
        cursor: pointer;
    }
    .notice{
        position: fixed;
        top: 80px;
        right: 30px;
    }
    </style>
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>商品图片添加&nbsp; <small>(图片尺寸800*300最佳)</small></h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" id="saveproinfo">
                            <div class="form-group">
                                <label class="control-label col-sm-2">商品Logo图</label>
                                <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" name="Logoimg" id="Logoimg" class="form-control"><div class="input-group-addon btn-primary upimg" data-toggle='modal' data-target='#modal-form'>上传</div><div class="input-group-addon btn-warning showimg" data-toggle='modal' data-target='#showimg'>预览</div>
                                </div><!-- <span class="help-block m-b-none">必填信息</span> -->
                                </div>
                            </div>
                            
                           <!--  <div class="form-group">
                               <div class="col-sm-4 col-sm-offset-2">
                               记一下所使用的规格名称
                               <input type="hidden" name="attrnames" id="attrnames">
                               
                                   <button class="btn btn-primary" type="submit">保存内容</button>
                                   <button class="btn btn-white" type="reset">取消</button>
                               </div>
                           </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="width: 100%;">
        <?php if(is_array($list)): foreach($list as $k=>$vo): ?><div style="width: 20%;float: left;text-align: center;">
                <a class="fancybox total"  href="<?php echo ($vo["imgurl"]); ?>">
                    <img alt="image" src="<?php echo ($vo["imgurl"]); ?>" style="width: 100%;padding: 5px" />
                </a>
                <?php if($vo["default"] == '1'): ?><!-- <if condition="$vo['default'] eq '1'"> -->
                    <button type="button" class="btn btn-default defs" data-set="<?php echo ($vo["default"]); ?>" data-id="<?php echo ($k); ?>">默认</button>
                <?php else: ?>
                    <button type="button" class="btn btn-info def" data-det="<?php echo ($vo["default"]); ?>" data-id="<?php echo ($k); ?>">设为默认</button><?php endif; ?>
                <button type="button" class="btn btn-warning delete" data-id="<?php echo ($k); ?>">删除</button>
            </div><?php endforeach; endif; ?>
    </div>

    <div id="modal-form" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="close_up" data-dismiss="modal" style="position:absolute;right:5px;top:5px;padding:5px;cursor: pointer;">&times;</div>
                    <div class="form-group" style="margin-top:20px;">
                        <input type="file" name="logo" id="logo" class="form-control" placeholder='选择图片'>
                    </div>
                    <div style="text-align:right;">
                        <button class="btn btn-primary btn-outline" id="startup">开始上传</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="showimg" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width:100%;width:500px;">
                <div class="modal-body">
                    <div data-dismiss="modal" style="position:absolute;right:5px;top:5px;padding:5px;cursor: pointer;">&times;</div>
                    <div style="text-align:center">
                        <img src="" alt="" id="show_img" style="max-width:100%;width:500px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/Public/newadmin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/newadmin/js/bootstrap.min.js?v=3.3.5"></script>
    <script src="/Public/newadmin/js/content.min.js?v=1.0.0"></script>
    <script src="/Public/newadmin/js/plugins/iCheck/icheck.min.js"></script>
    <script src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/newadmin/css/fancybox/jquery.fancybox.js"></script>
    <script>
        $(document).ready(function(){$(".fancybox").fancybox({openEffect:"none",closeEffect:"none"})});
    </script>
    <script>
        $(document).ready(function(){
            $('#startup').click(function(){
                upload($(this),$('#logo'));
            })
            $('.showimg').click(function(){
                $('#show_img').attr('src',$('#Logoimg').val());
            })
            //设置默认
            $(".def").click(function() {
                var last = $('.defs').attr('data-id');
                var id = $(this).attr('data-id');
                $.ajax({
                    url:"<?php echo U('Upimg/changSet');?>",
                    type:'post',
                    data:'id='+id+'&last='+last,
                    dataType:'json',
                    success:function(msg) {
                        if(msg.status == "success") {
                            layer.msg('设置成功');
                            window.location.reload();
                        }
                    }
                })
            })
            // 删除图片
            $(".delete").click(function() {
                var id = $(this).attr('data-id');
                    $.ajax({
                        url:"<?php echo U('Upimg/delImg');?>",
                        type:'post',
                        data:'id='+id,
                        dataType:'json',
                        success:function(msg) {
                            if(msg.status == "success") {
                                layer.msg(msg.info);
                                window.location.reload();
                            }else {
                                layer.msg(msg.info);
                            }
                        }
                    })
            })
        });







        function upload(msgdom,imgdom){
            msgdom.addClass('disabled').html('正在上传<span class="jdbar">0</span>%');
            //1、准备FormData
            var fd = new FormData();
            fd.append("imgs",imgdom[0].files[0]);
            var imginfo=imgdom[0].files[0];
            //创建xhr对象
            if (imginfo['size']>=2097152) {
                $('#warninginfo').html('图片过大，上传缓慢')
                // alert('图片大小超出2M');
                // return false;
            };
            var xhr = new XMLHttpRequest();

            //监听状态，实时响应
            //xhr和xhr.upload 都有progress事件，xhr.progress是下载进度，xhr.upload.progress是上传进度
            xhr.upload.onprogress = function(event){
                if(event.lengthComputable){
                    var percent = Math.round(event.loaded * 100 / event.total);
                    // console.log('%d%',percent);
                    $('.jdbar').html(percent);
                }
            }

            //传输开始事件
            xhr.onloadstart = function(event){
            }

            //ajax过程成功完成事件
            xhr.onload = function(event){
                msgdom.html('开始上传').removeClass('disabled');
                var data=eval("("+xhr.responseText+")");
                    if (data.status=='success'){
                        $('#selswimg').val('');
                        $('#Logoimg').val(data.img);
                        $('.close_up').click();
                        layer.msg('上传成功');
                        window.location.reload();
                    }else{
                        layer.msg(data.info);
                    }
            }

            //ajax过程发生错误事件
            xhr.onerror = function(event){
                msgdom.removeClass('disabled').html('开始上传');
                layer.msg('发生错误')
            }

            //ajax被取消
            xhr.open('POST',"<?php echo U('Upimg/Saveprologo');?>",true);
            xhr.send(fd);
        }
    </script>
</body>

</html>