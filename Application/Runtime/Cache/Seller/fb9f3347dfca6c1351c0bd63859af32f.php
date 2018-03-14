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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.config.js?v=1"></script>
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.all.js?v=1.1"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<style type="text/css">
    .attrvalues{
        border: 1px solid green;
        margin:auto 2px;
        border-radius: 2px;
        padding-left: 2px;
        padding-right: 2px;
    }
    img{
        width: 100%;
        margin-bottom: 0px;

    }
    .img-box{
        width:150px;height:170px;border:1px solid #ccc;
        margin-top: 10px;
        margin-left: 15px;
    }
    .img-content{
        width:100%;height:150px;
    }
    .img-btn{
        width:100%;height:20px;text-align:center;
    }
    .addimg{
    cursor: pointer;
    font-size: 100px;
    color: #ccc;
    float: left;
    width: 50px;
    height: 50px;
    line-height: 50px;
  }
  #img_html p{
    width: 30%;
    float: left;
    margin-left: 3%;
    margin-bottom: 10px;
    display: inline-block;
    position: relative;
  }
  #img_html p:nth-child(3n+1){
    clear: both;
  }
  #img_html p:after{
    content: '点击删除图片';
    position: absolute;
    left: 15%;
    top: 40%;
    display: none;
    font-size: 2em;
    color: #ff0000;
    cursor: pointer;
  }
  #img_html p:hover:after{
    display: block;
  }
  #img_html p img{
    width: 100%;
  }
</style>
<div class="row">
    <div class="panel blank-panel">
        <form method="post" class="form-horizontal" action="<?php echo U('Products/saveEdit');?>" id="savePro">
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>商品编辑 <small>products</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content"  style="position:relative;">
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">当前分类</label>
                                <div class="col-sm-6">
                                    <select name="ClassType" id="ClassType" class="form-control col-sm-6">
                                        <?php if(is_array($class)): foreach($class as $key=>$cls): ?><option value="<?php echo ($cls["ClassId"]); ?>"  <?php if($proinfo['ClassType'] == $cls['ClassId']): ?>selected="selected"<?php endif; ?>  ><?php echo ($cls["ClassName"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                              <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">计数类型 <span class="tice"><b>*</b></span></label>
                                <div class="input-group m-b col-lg-3 col-md-4">
                                  <label class="radio-inline">
                                    <input type="radio" name="NumType" <?php if($proinfo['NumType'] == '1'): ?>checked="checked"<?php endif; ?> id="inlineradio1" value="1"> 按份计数(用户只能选择整数份)
                                  </label>
                                  <label class="radio-inline" style="margin-left:0px;">
                                    <input type="radio" name="NumType" <?php if($proinfo['NumType'] == '2'): ?>checked="checked"<?php endif; ?> id="inlineradio2" value="2"> 按重量计数(用户可以选择详细重量*精确一位小数)
                                  </label>
                                </div>
                              </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">当前属性</label>
                                <div class="input-group m-b col-lg-6 col-md-6 col-sm-6 col-md-6" id="table">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>属性</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(is_array($prolist)): foreach($prolist as $key=>$pson): ?><tr>
                                                    <td><input type="text" name="specs[]" value="<?php echo ($pson["ProSpec1"]); ?>" id=""><input type="hidden" name="ProIdCards[]" class='old_pid' value="<?php echo ($pson["ProIdCard"]); ?>"></td>
                                                    <td><button type="button" class="btn btn-danger btn-xs pson_delete" data-pid='<?php echo ($pson["ProIdCard"]); ?>'>删除</button></td>
                                                </tr><?php endforeach; endif; ?>
                                        </tbody>
                                        <tbody id="newatrcontent">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-offset-2 col-md-offset-2"><button class="btn btn-primary btn-outline btn-xs" id="addnewatr" type="button">添加新属性</button></div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-2 control-label"></label>

                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <div class="radio">
                                        <label><input type="radio" name="IsAddAttr" id="" value="1" class="optionsRadios">新增商品属性修改请勾选此项</label>
                                    </div>
                                </div>
                            </div>
                         <!--  <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">选择商品类型</label>
                            <div class="input-group m-b col-lg-3 col-md-4">
                              <select class="form-control" name="ProType" id="ProType" style="width:350px;" tabindex="2" >
                                <option value="1" <?php if($proinfo['ProType'] == '1'): ?>selected="selected"<?php endif; ?> >单品</option>
                                <option value="2" <?php if($proinfo['ProType'] == '2'): ?>selected="selected"<?php endif; ?> >组合商品</option>
                              </select>
                            </div>
                        </div> -->
                            <!-- <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <i class="fa fa-warning"></i> 提示信息
                                    </div>
                                    <div class="panel-body">
                                        <div class="alert alert-warning">
                                           1、如有新增属性信息，请勾选新增属性修改<br>
                                           2、如有新图片改动，请勾选展示图修改<br>
                                           3、LOGO图请选择200*200图片上传，以免影响显示效果<br>
                                           4、商品展示图请上传宽高比例为16：9的图片 分辨率640*360最佳<br>
                                       </div>
                                   </div>
                               </div>
                           </div> -->



                           <div class="form-group" >
                            <label class="col-sm-2 control-label">商品编号</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ProNumber" value="<?php echo ($proinfo["ProNumber"]); ?>">
                            </div>
                        </div>

                        <div class="form-group" id="ProName" >
                            <label class="col-sm-2 control-label">商品名称</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="ProName_c" name="ProName" value="<?php echo ($proinfo["ProName"]); ?>">
                            </div>
                        </div>
                        <div class="form-group" id="ProTitle">
                            <label class="col-sm-2 control-label">商品标题</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="ProTitle_c" name="ProTitle" value="<?php echo ($proinfo["ProTitle"]); ?>"> <span class="help-block m-b-none"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">售价</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="Price" name="Price" value="<?php echo ($proinfo["Price"]); ?>"> <span class="help-block m-b-none"></span>
                            </div>
                        </div>

                        <div class="form-group" id="">
                            <label class="col-sm-2 control-label">原价</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="PriceRange" name="PriceRange" value="<?php echo ($proinfo["PriceRange"]); ?>"> <span class="help-block m-b-none"></span>
                            </div>
                        </div>

                        <div class="form-group" id="ProSubtitle">
                            <label class="col-sm-2 control-label">商品重量/单位(g) <span class="tice"><b>*</b></span></label>

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" name="Weight" class="form-control" id="Weight" value="<?php echo ($proinfo["Weight"]); ?>"><span class="input-group-addon">g</span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 control-label">是否使用积分兑换</label>

                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="radio">
                                    <label><input type="radio" name="IsUseScore" id="IsUseScore" value="1" <?php if($proinfo['IsUseScore'] == 1): ?>checked="checked" data-s="1"<?php endif; ?> class="optionsRadios">是</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="IsUseScore" id="NOUseScore" value="0" <?php if($proinfo['IsUseScore'] == 0): ?>checked="checked"<?php endif; ?> class="optionsRadios">否</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" id="">
                            <label class="col-lg-2 col-md-2 col-sm-2 control-label">兑换所需积分数</label>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <input type="text" name="Score" class="form-control" id="Score"  value="<?php echo ($proinfo["Score"]); ?>"><span class="input-group-addon">分</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-error" id="ProLogoImg_c">
                            <label class="col-sm-2 control-label">商品主图</label>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="ProLogoImg_con">
                                            <img id="ProLogoImg_url" src="<?php echo ($PICURL); echo ($proinfo['ProLogoImg']); ?>" alt="">
                                        </div>
                                        <div class="img-btn">
                                            <button class="btn btn-xs btn-warning" type="button" onclick="upimg('ProLogoImg')">修改</button>
                                            <input type="hidden" class="form-control" name="ProLogoImg" id="ProLogoImg" value="<?php echo ($proinfo['ProLogoImg']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>

                        <div class="form-group has-error" id="mainimg_c">
                            <label class="col-sm-2 control-label">商品展示图</label>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="mainimg_con">
                                            <?php if($proinfo['ProImgs'][0]): ?><img id="mainimg_url" src="<?php echo ($PICURL); echo ($proinfo['ProImgs'][0]); ?>" alt="">
                                                <?php else: ?>
                                                暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('mainimg')">修改</button><input type="hidden" class="form-control" name="ProImgs[]" id="mainimg" value="<?php echo ($proinfo['ProImgs'][0]); ?>">
                                        </div>
                                    </div>
                                    <?php for ($i=1; $i < 6; $i++) { ?>
                                    <div class="pull-left img-box">
                                        <div class="img-content" id="img<?php echo ($i); ?>_con">
                                            <?php if($proinfo['ProImgs'][$i]): ?><img id="img<?php echo ($i); ?>_url" src="<?php echo ($PICURL); echo ($proinfo['ProImgs'][$i]); ?>" alt="">
                                                <?php $isimg=1; ?>
                                                <?php else: ?>
                                                <?php $isimg=0; ?>
                                                暂无图片<?php endif; ?>
                                        </div>
                                        <div class="img-btn">
                                            <button type="button" class="btn btn-xs btn-warning" onclick="upimg('img<?php echo ($i); ?>')">修改</button>&emsp;<button type="button" class="btn btn-xs btn-danger" onclick="del('<?php echo ($isimg); ?>','img<?php echo ($i); ?>')">删除</button><input type="hidden" class="form-control" name="ProImgs[]" id="img<?php echo ($i); ?>" value="<?php echo ($proinfo['ProImgs'][$i]); ?>">
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 control-label"></label>

                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="radio">
                                    <label><input type="radio" name="IschangImg" id="" value="1" class="optionsRadios">展示图修改请勾选此项</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品详情</label>
                            <div class="col-sm-8">
                                <div class="">
                                <div id="img_html" style="float:left;width:100%;"><?php echo html_entity_decode($proinfo['ProContent']); ?></div><div class="addimg" data-toggle='modal' data-target="#modal-form" >+</div><div style="clear;"></div>
                              </div>
                              <input type="hidden" name="ProContent" id="ProContent">
                            </div>
                        </div>
                        <input type="hidden" name="ProId" value="<?php echo ($proinfo["ProId"]); ?>">
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" type="submit" id="savepost">保存</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

    </div>
</form>

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

<script type="text/javascript">
    var pid='<?php echo ($proinfo["ProId"]); ?>';
    // var ue = UE.getEditor('editor');
    $(document).ready(function(){
        $(document).on('click','#img_html p',function(){
          var _this=$(this);
          art.dialog.confirm('确定要删除吗？',function(){
            _this.remove();
          })
        })
        $('#startup').click(function(){
            upload($(this),$('#logo'));
        })
        $('#NOUseScore').click(function(){$("#IsUseScore").attr('data-s','0')})
        $('#IsUseScore').click(function(){$("#IsUseScore").attr('data-s','1')})
        $("#saveimg").click(function(){
            art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
        })
        $("#savePro").submit(function(){
            var ProName=$('#ProName_c').val();
            var ProTitle=$("#ProTitle_c").val();
            var logoimg=$("#ProLogoImg").val();
            var IsUseScore=$("#IsUseScore").attr('data-s');
            var score=$("#Score").val();
            $('#ProContent').val($('#img_html').html());
            if (!ProName) {
                art.dialog.alert('请填写商品名称');
                return false;
            };
            if (!ProTitle) {
                art.dialog.alert('请填写商品标题');
                return false;
            };
            if (IsUseScore=='1') {
                if (!score) {
                    art.dialog.alert('请填写兑换所需积分数');
                    return false;
                };
            };
            if (!logoimg) {
                art.dialog.alert('请上传商品主图');
                return false;
            }else{
                art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
                return true;
            }
        })

        var lastid=$('.old_pid:last').val();
        var spid=<?php echo ($spid); ?>;
        $('#addnewatr').click(function(){
            var _tphtml="<tr class='attr_tr'><td><input type='text' name='new_specs[]' class='input_spec' placeholder='请输入属性名称' /><input type='hidden' name='new_pids[]' value='"+spid+"'/></td><td><button class='btn btn-danger btn-xs delatr' type='button'>删除</button></td></tr>";
            var atrlang=$('.attr_tr').length;
            if (atrlang=='0') {
                $('#newatrcontent').append(_tphtml);
                spid++;
                $('.input_spec:last').focus();
            }else{
                if ($('.input_spec:last').val()) {
                    $('#newatrcontent').append(_tphtml);
                    spid++;
                    $('.input_spec:last').focus();
                }else{
                  art.dialog.tips('请完善属性信息',2);
              }
          }        
        })
        $(document).on('click','.delatr',function(){
            $(this).parent().parent().remove();
        })
        $(document).on('click','.pson_delete',function(){
            var pid=$(this).attr('data-pid');
            var _this=$(this);
            art.dialog.confirm('确定要删除此属性吗（立即生效）？',function(){
                art.dialog.tips('处理中...',20);
                $.ajax({
                    url:"<?php echo U('Products/delatr');?>",
                    type:"post",
                    data:"pid="+pid,
                    dataType:"json",
                    success:function(msg){
                        if (msg.status=='success') {
                            art.dialog.tips('处理成功');
                            _this.parent().parent().remove();
                        }else{
                            art.dialog.tips('处理失败');
                        }
                    }
                })
            })
        })

})
function upimg(id){
    // art.dialog.data('homeDemoPath', "<?php echo U('Product/proadd');?>");
    art.dialog.data('domid',id);
    art.dialog.open('<?php echo U('Upimg/editpro');?>');
};


function addAttr(){
    $("#chose-attrs").show();
}

function checklang(id){
    var v=$("#"+id).val();
    if (v.length>2) {
        $("#"+id).val(v.substr(0,2));
        art.dialog.tips('非法数字',0.5);
    };
}

function del(id,imgid){
    if (id==1) {
        art.dialog.confirm('确定要删除吗？',function(){
            art.dialog.tips('此栏位图片已删除,保存修改后生效');
            $("#"+imgid+"_con").html('图片已删除,保存修改后生效');
            $("#"+imgid+"id").val('');
            $("#"+imgid).val('');
        },function(){
            art.dialog.tips('取消操作',1);
        })
    }else{
        art.dialog.alert('此栏位没有图片');
    }
}


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
                data=eval("("+xhr.responseText+")");
                    if (data.status=='success'){
                        var _html='<p><img src="'+data.img+'" alt="" /></p>';
                        $('#img_html').append(_html);
                        $('.close_up').click();
                        layer.msg('上传成功');
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

</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>