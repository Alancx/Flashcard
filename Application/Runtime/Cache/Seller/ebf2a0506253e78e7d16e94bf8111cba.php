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
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/Admin/ueditor/ueditor.all.js?v=2.1"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<script type="text/javascript" src="/Public/Admin/diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="/Public/Admin/diyUpload/js/diyUpload.js?v=1.2"></script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="/Public/Admin/diyUpload/css/diyUpload.css">

<style type="text/css">
  .form-control1 {
    display: block;
    width: 100%;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  }
  #notices{
    color: red;
    font-weight: bold;
    font-size: 1.2em;
  }
  .tice{
    color:red;
  }
  #box{ width:100%; min-height:400px; background:#FF9}
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
    <form method="post" class="form-horizontal" action="<?php echo U('Products/savePro');?>" id="savePro">

      <div class="panel-heading">
        <div class="panel-title m-b-md">
        </div>
        <div class="panel-options">

          <ul class="nav nav-tabs" style="text-align:center;">
            <li class="active" id="t0"><a data-toggle="tab" href="proadd.html#tab-1" aria-expanded="true">分类属性</a>
            </li>
            <li class="" id="t1"><a  href="proadd.html#tab-2" id="bbinfo" >基本信息</a>
            </li>
            <li class="" id="t2"><a  href="proadd.html#tab-3" id="xxinfo" >详情信息</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="panel-body">

        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="ibox-content" style="position:relative;">
              <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">选择分类 <span class="tice"><b>*</b></span></label>
                <div class="input-group m-b col-lg-3 col-md-4">
                  <select data-placeholder="请选择分类" class="chosen-select" name="ClassType" id="chose" style="width:350px;" tabindex="2" >
                    <option value="">请选择分类</option>
                    <?php if(is_array($oclass)): foreach($oclass as $key=>$oc): ?><option value="<?php echo ($oc["ClassId"]); ?>" hassubinfo="true"><?php echo ($oc["ClassName"]); ?></option><?php endforeach; endif; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">计数类型 <span class="tice"><b>*</b></span></label>
                <div class="input-group m-b col-lg-3 col-md-4">
                  <label class="radio-inline">
                    <input type="radio" name="NumType" checked="checked" id="inlineradio1" value="1"> 按份计数(用户只能选择整数份)
                  </label>
                  <label class="radio-inline" style="margin-left:0px;">
                    <input type="radio" name="NumType" id="inlineradio2" value="2"> 按重量计数(用户可以选择详细重量*精确一位小数)
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">商品属性 <span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <div class="form-group" id="valuearea">

                  </div>
                  <div class="input-group m-b col-lg-10 col-md-10" id="chose-attr" value="1">
                    <table class="table table-bordered table-condensed" style="z-index:66;">
                      <thead>
                        <tr>
                          <th>属性名称</th>
<!--                           <th>条码</th>
                          <th>售价</th>
                          <th>数量</th>
                          <th>商品编码</th>
                        -->                          <th>操作</th>
                      </tr>
                    </thead>
                    <tbody id="atrcontent">

                    </tbody>
                  </table>
                  <hr>
                </div>
                <div class="input-group m-b">
                  <button type="button" class="btn btn-outline btn-primary btn-md" id="add" onclick="setAttrs();">添加属性</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <button class="btn btn-warning btn-md btn-outline" onclick="clears();" type="button">清空属性</button>
                </div>
              </div>
              <div style="border:0px solid orange;" class="input-group col-sm-8 col-sm-offset-2 col-lg-offset-2 col-lg-7" id="table">
              </div>
            </div>
              <!-- <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                <div class="panel">
                  <div class="panel-heading">
                    <i class="fa fa-warning"></i> 提示信息
                  </div>
                  <div class="panel-body">
                    <div class="alert alert-warning">
                      1、分类选择请选择到具体的子分类<br>
                      2、商品属性确定后，只可以增加，不允许删除<br>
                      <b><span style="color:red;">*</span>标记的为必填项、其他为选填项</b>
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                  <button class="btn btn-primary btn outline" id="next0" type="button">下一步</button>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-2" class="tab-pane">
            <div class="ibox-content" style="position:relative;">

              <!-- <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                <div class="panel">
                  <div class="panel-heading">
                    <i class="fa fa-warning"></i> 提示信息
                  </div>
                  <div class="panel-body">
                    <div class="alert alert-warning">
                      1、商品名称，标题为必填项<br>
                      2、提成按百分比计算，填写5即代表5%，请不要再输入%符号<br>
                      <b><span style="color:red;">*</span>标记的为必填项、其他为选填项</b>
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="form-group" >
                <label class="col-sm-2 control-label">商品编号</label>

                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ProNumber">
                </div>
              </div>
              <div class="form-group" id="ProName" >
                <label class="col-sm-2 control-label">商品名称 <span class="tice"><b>*</b></span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="ProName_c" name="ProName" value="">
                </div>
              </div>
              <div class="form-group" id="ProTitle">
                <label class="col-sm-2 control-label">商品标题 <span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="ProTitle_c" name="ProTitle"> <span class="help-block m-b-none"></span>
                </div>
              </div>
              <div class="form-group" id="ProTitle">
                <label class="col-sm-2 control-label">售价 <span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="Price" name="Price"> <span class="help-block m-b-none"></span>
                </div>
              </div>
              <div class="form-group" id="ProTitle">
                <label class="col-sm-2 control-label">原价<span class="tice"><b>*</b></span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="PriceRange" name="PriceRange"> <span class="help-block m-b-none"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 control-label">是否使用积分兑换</label>

                <div class="col-lg-10 col-md-10 col-sm-10">
                  <div class="radio">
                    <label><input type="radio" name="IsUseScore" id="IsUseScore" data-s="" value="1" class="optionsRadios">是</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="IsUseScore" id="NOUseScore" value="0" checked="checked" class="optionsRadios">否</label>
                  </div>

                </div>
              </div>
              <div class="form-group" id="">
                <label class="col-lg-2 col-md-2 col-sm-2 control-label">兑换所需积分数</label>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <div class="input-group">
                    <input type="number" name="Score" id="Score" class="form-control"><span class="input-group-addon">分</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 control-label">是否立即上架</label>

                <div class="col-lg-10 col-md-10 col-sm-10">
                  <div class="radio">
                    <label><input type="radio" name="IsShelves" id="" value="1" checked="checked" class="optionsRadios">是</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="IsShelves" id="" value="0" class="optionsRadios">否 <!-- 定时上架：<input id="d11" type="text" name="ShelvesDate" onClick="WdatePicker()" placeholder="点击选择日期"/> --></label>
                  </div>

                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                  <button class="btn btn-primary btn outline" id="next" type="button">下一步</button>
                </div>
              </div>
            </div>
          </div>

          <div id="tab-3" class="tab-pane" style="display:none;">
            <div class="ibox-content"  style="position:relative;">
              <div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
                <div class="panel">
                  <div class="panel-heading">
                    <i class="fa fa-warning"></i> 提示信息
                  </div>
                  <div class="panel-body">
                    <div class="alert alert-warning">
                      1、商品主图图请上传大小为200*200的图片，以免影响显示效果<br>
                      2、商品展示图请上传宽高比例为16：9的图片  分辨率640*360最佳<br>
                      3、商品展示图最多显示6张
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group has-error" id="logoimg_c">
                <label class="col-sm-2 control-label">商品主图 <span class="tice"><b>*</b></span></label>

                <div class="col-sm-6">
                  <div class="input-group m-b">
                    <input type="text" class="form-control" name="ProLogoImg" id="logoimg" value=""> <span class="input-group-addon"><a href="###" onclick="upimg('logoimg')">上传</a></span><span class="input-group-addon"><a href="###"  id="logoimg_y">预览 </a> </span>
                  </div>
                </div>
              </div>
              <div class="form-group has-warning" id="img1_c">
                <label class="col-sm-2 control-label">商品展示图 <span class="tice"><b>*</b></span></label>

                <div class="col-sm-6">
                  <div id="box">
                    <div id="test" ></div>
                  </div>
                </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group">
                <label class="col-sm-2 control-label">商品详情</label>
                <div class="col-sm-8">
                  <div class="">
                    <div id="img_html" style="float:left;width:100%;"></div><div class="addimg" data-toggle='modal' data-target="#modal-form" >+</div><div style="clear;"></div>
                  </div>
                  <input type="hidden" name="ProContent" id="ProContent">
                </div>
              </div>
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

<script type="text/javascript">
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
    $('.showimg').click(function(){
        $('#show_img').attr('src',$('#Logoimg').val());
    })
    $('#NOUseScore').click(function(){$("#IsUseScore").attr('data-s','0')})
    $('#IsUseScore').click(function(){$("#IsUseScore").attr('data-s','1')})
    $('#test').diyUpload({
      url:'<?php echo U('Upimg/saveimg');?>',
      success:function( data ) {
        console.log( data );
        $('<input type="hidden" class="tempClass" name="imgs[]" value="'+data+'" />').appendTo("#img1_c");
      },
      error:function( err ) {
        console.log( err );
      }
    });
    $("#savePro").submit(function(){
      var ClassType=$("#chose").val();
      var ProName=$('#ProName_c').val();
      var Supplier=$('#SupplierId').val();
      var ProTitle=$("#ProTitle_c").val();
      var imgs=$('.tempClass').val();
      var logoimg=$("#logoimg").val();
      var IsUseScore=$("#IsUseScore").attr('data-s');
      var score=$("#Score").val();
      $('#ProContent').val($('#img_html').html());
      if (!ClassType) {
        art.dialog.alert('请选择商品所属分类');
        return false;
      };
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
      if (!imgs) {
        art.dialog.alert('请上传商品展示图');
        return false;
      }
      if(!logoimg) {
        art.dialog.alert('请上传商品LOGO图');
        return false;
      }else{
        art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
        return true;
      }
    })
$("#addimport").click(function(){$("#imports").show();});
$("#next0").click(function(){
  var ClassType=$("#chose").val();
  var classs=$("#sonClass").val();
    // var Supplier=$('#SupplierId').val();
    if (!ClassType) {
      art.dialog.tips('请选择商品所属分类',2);
      return false;
    };
    // if (!Supplier) {
    //   art.dialog.tips('请选择供货商',2);
    //   return false;
    // };
     // && $('.input_barcode:last').val() && $('.input_nums:last').val() && $('.input_price').val())
if (!$('.input_spec:last').val()) {
  art.dialog.tips('请完善属性信息',2);
  return false;
}else{
  $("#tab-3").attr('style','display:none');
  $("#tab-2").attr('style','display:block');
  $("#tab-1").attr('style','display:none');
  $("#t0").attr('class','');
  $("#t1").attr('class','active');
  $("#t2").attr('class','');
}

})
$("#next").click(function(){
  var ProName=$('#ProName_c').val();
  var ProTitle=$("#ProTitle_c").val();
  var Weight=$("#Weight").val();
  var IsUseScore=$("#IsUseScore").attr('data-s');
  var score=$("#Score").val();
    // console.log(classs);
    // console.log(ClassType);
    if (!ProName) {
      art.dialog.alert('请填写商品名称');
      return false;
    };
    if (!ProTitle) {
      art.dialog.alert('请填写商品标题');
      return false;
    }
    if (IsUseScore=='1') {
      if (!score) {
        art.dialog.alert('请填写兑换所需积分数');
        return false;
      };
    }else{
      $("#tab-3").attr('style','display:block');
      $("#tab-2").attr('style','display:none');
      $("#tab-1").attr('style','display:none');
      $("#t0").attr('class','');
      $("#t1").attr('class','');
      $("#t2").attr('class','active');
    }

  })
$("#t0").click(function(){
  $("#tab-3").attr('style','display:none;');
  $("#tab-2").attr('style','display:none;');
  $("#tab-1").attr('style','display:block;');
  $("#t0").attr('class','active');
  $("#t1").attr('class','');
  $("#t2").attr('class','');
})
$("#t1").click(function(){
  art.dialog.tips('请选择分类属性后点击下一步');
})
$("#t2").click(function(){
  art.dialog.tips('请填写基本信息后点击下一步');
})

$("#logoimg_y").click(function(){
  art.dialog({title:'图片预览',content:'<img src="<?php echo ($PICURL); ?>'+$('#logoimg').val()+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
})
$("#mianimg_y").click(function(){
  art.dialog({title:'图片预览',content:'<img src="<?php echo ($PICURL); ?>'+$('#mainimg').val()+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
})
$("#saveimg").click(function(){
  art.dialog({title:'提示',content:'正在提交数据，请勿重复操作...',lock:true});
})


$(document).on('click','.delatr',function(){
  $(this).parent().parent().remove();
})
})


var config = {
  '#sonClass':{},
  '.chosen-select': {},
  '.chosen-select-deselect': {
    allow_single_deselect: true
  },
  '.chosen-select-no-single': {
    disable_search_threshold: 10
  },
  '.chosen-select-no-results': {
    no_results_text: 'Oops, nothing found!'
  },
  '.chosen-select-width': {
    width: "95%"
  }
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}


function upimg(id){
  art.dialog.data('homeDemoPath', "<?php echo U('Product/proadd');?>");
  art.dialog.data('domid',id);
  art.dialog.open('<?php echo U('Upimg/index');?>');
};
function clears(){
  $('#atrcontent').html('');
}
function checklang(id){
  var v=$("#"+id).val();
  if (v.length>2) {
    $("#"+id).val(v.substr(0,2));
    art.dialog.tips('非法数字',0.5);
  };
}

var spid=1;
//添加属性，改版功能
function setAttrs(){
  var _tphtml="<tr class='attr_tr'><td><input type='text' name='specs[]' class='input_spec' placeholder='请输入属性名称' /><input type='hidden' name='pids[]' value='"+spid+"'/></td><td><button class='btn btn-danger btn-xs delatr' type='button'>删除</button></td></tr>";
  // <td><input type='text' name='barcodes[]' class='input_barcode' placeholder='请输入商品条码'/></td><td><input type='text' name='prices[]'  class='input_price' placeholder='请输入售价'/></td><td><input type='number' name='nums[]' class='input_nums' placeholder='请输入数量'/></td><td><input type='text' name='inputCodes[]' class='input_codes' placeholder='请输入商品编码' /></td>
  var atrlang=$('.attr_tr').length;
  if (atrlang=='0') {
    $('#atrcontent').append(_tphtml);
    spid++;
    $('.input_spec:last').focus();
  }else{
    // && $('.input_barcode:last').val() && $('.input_nums:last').val() && $('.input_price').val() && $('.input_codes:last').val()
    if ($('.input_spec:last').val()) {
      $('#atrcontent').append(_tphtml);
      spid++;
      $('.input_spec:last').focus();
    }else{
      art.dialog.tips('请完善属性信息',2);
    }
  }
}
</script>
<script type="text/javascript">
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
<script type="text/javascript" src="/Public/Admin/Admin/view/proadd.js"></script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>