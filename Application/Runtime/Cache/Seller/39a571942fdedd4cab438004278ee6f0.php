<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>设置商品属性</title>

    <!-- Bootstrap -->
    <link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/Public/Admin/Admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
    <script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
    <link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    .attrvalues{
        border: 1px solid green;
        margin:auto 2px;
        border-radius: 2px;
        padding-left: 2px;
        padding-right: 2px;
    }
    .row{
        /*border: 2px solid red;*/
        margin: 15px;
    }
    .glyphicon-ok{
        color:green;
        font-size: 2em;
    }
    .glyphicon-remove{
        color:red;
    }
    </style>
  </head>
  <body>
    <div class="container" style="width:560px;height:380px;">
      <div class="row">
      <div class="col-sm-12"></div>
        <table class="table table-bordered">
            <tr>
                <td>请输入条码</td>
                <td colspan="2"><input type="text" name="noname" id="barcode" style="width:100%;"></td>
            </tr>
            <tr>
                <td colspan="3">商品信息</td>
            </tr>
            <?php if(is_array($oinfo)): foreach($oinfo as $key=>$info): ?><tr class="pinfo">
                <td colspan="3"><div class="row" style="margin:0px;"><div class="col-xs-3 pull-left"><img src="<?php echo ($info["ProLogoImg"]); ?>" alt="" width="60" height="60" border="1"></div><div class="col-xs-7 pull-left"> <?php echo ($info["ProName"]); ?> <br> <small style="color:#ccc;" ><input type="hidden" name="" class="bar" value="<?php echo ($info["Barcode"]); ?>"><?php echo ($info["Barcode"]); ?></small></div><div class="col-xs-2 pull-left"><span id="<?php echo ($info["Barcode"]); ?>"></span></div></div></td>
            </tr><?php endforeach; endif; ?>
            <tr>
                <td style="line-height:34px;">请选择运费模板</td>
                <td colspan="2"><select name="Logistacs" id="Logistacs" class="form-control" disabled="disabled" onchange="select();">
                    <option value="moren">请选择</option>
                    <?php if(is_array($wuliu)): foreach($wuliu as $key=>$wl): ?><option value="<?php echo ($wl["ID"]); ?>"><?php echo ($wl["Name"]); ?></option><?php endforeach; endif; ?>
                </select></td>
            </tr>
            <tr>
                <td>请输入重量（单位/g）</td>
                <td colspan="2"><input type="number" name="weight" onkeyup="getnum();" id="weight" class="form-control" disabled="disabled"></td>
            </tr>
            <tr>
                <td>运费</td>
                <td style="text-align:center"><span style="color:orange;font-size:1.5em;" id="money"></span><input type="hidden" name="ni" id="yunfei" value=""></td>
            </tr>
        </table>
        <div class="col-sm-12" style="text-align:right;">
            <button class="btn btn-success btn-outline" type="button" id="ok" disabled="disabled" >完成</button>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  </body>
  <script type="text/javascript">
  $(document).ready(function(){
    $("#barcode").focus();
    $("#ok").click(function(){
        var domid="<?php echo ($oinfo["0"]["OrderId"]); ?>";
        var freight=$("#yunfei").val();
        $.ajax({
            type:"post",
            url:"<?php echo U('ArtDialog/setCheck');?>",
            data:"oid="+domid+"&freight="+freight,
            dateType:"json",
            success:function(msg){
                if (msg=='success') {
                    var origin=artDialog.open.origin;
                    var dom = origin.document.getElementById("s"+domid);
                    // console.log(dom);
                    dom.innerHTML='<button class="btn btn-default btn-outline status-button" type="button" onclick="prints('+domid+');">打印发货单</button>&nbsp;<button class="btn btn-default btn-outline status-button" type="button" onclick="printlog('+domid+');">打印快递单</button>&nbsp;<a href="javascript:;" data-toggle="modal" data-order="'+domid+'" class="btn btn-outline btn-default status-button order_send" data-target="#ModelSend">发&nbsp;&nbsp;货</a>';
                    art.dialog.tips('验证完成');
                    setTimeout("art.dialog.close()", 1500 );
                }else{
                    art.dialog.tips('验证失败');
                }
            }
        })
    })

    $("#barcode").keydown(function(e){
        // console.log(e.keyCode);
        if (e.keyCode==13) {
            var barcode=$('#barcode').val();
            var temp=document.getElementsByClassName('bar');
            // var temp=$(".bar").val();
            var lang=$(".pinfo").length;
            var bs=false;
                $.each(temp,function(index,item){
                    if (barcode==item.value) {
                        bs=true;
                        $("#"+item.value).attr('class','glyphicon glyphicon-ok');
                        $("#barcode").val('');
                        $("#barcode").focus();
                        return false;
                    }
                })
                if (!bs) {
                    art.dialog.tips('未找到商品'+barcode);
                    $("#barcode").val('');
                    $("#barcode").focus();
                };
            var success=$(".glyphicon-ok").length;
            if (success==lang) {
                $("#Logistacs").attr('disabled',false);
                $("#weight").attr('disabled',false);
            };
        };
    })
  })

  // function checkbar(id){
  //   var barcode=$('#barcode').val();
  //   var temp=document.getElementsByClassName('bar');
  //   var lang=$(".pinfo").length;
  //       $.each(temp,function(index,item){
  //           if (barcode==item.value) {
  //               $("#"+item.value).attr('class','glyphicon glyphicon-ok');
  //               $("#barcode").val('');
  //               $("#barcode").focus();
  //           };
  //       })
  //   var success=$(".glyphicon-ok").length;
  //   if (success==lang) {
  //       $("#Logistacs").attr('disabled',false);
  //   };
  // }

  function select(){
    // alert($("#Logistacs").val);
    if ($("#Logistacs").val()!='moren') {
        $("#weight").attr('disabled',false);
    }else{
        $("#weight").val('');
        $("#money").html('');
        $("#yunfei").val('');
        $("#ok").attr('disabled','disabled');
        $("#weight").attr('disabled','disabled');
        console.log('ok')
    }

  }

  function getnum(){
    var weight=Math.abs(parseInt($("#weight").val()));
    var freights=<?php echo ($freights); ?>;
    var provience="<?php echo ($oinfo["0"]["RecevingProvince"]); ?>";
    var fid=$('#Logistacs').val();
    if (weight) {
        $.each(freights,function(index,item){
          // console.log(item.ID,fid,item.Area,provience);
            if (item.ID==fid && item.Area==provience) {
                var firstWeight=item.FirstWeight;
                var addWeight=item.AddWeight;
                var Foprice=item.Opiece;
                var Aoprice=item.Oadd;
                var Ftprice=item.Tpiece;
                var Atprice=item.Tadd;
                var blong=item.Price;
                if (blong=='1') {
                    if (weight>firstWeight) {
                        var S=weight-firstWeight;
                        var Sprice=Math.ceil(S/addWeight)*Aoprice;
                        var AllPrice=parseInt(Foprice)+parseInt(Sprice);
                        // console.log(AllPrice);
                        $("#money").html(AllPrice);
                        $("#yunfei").val(AllPrice);
                        $("#ok").removeAttr('disabled');
                    }else{
                        var AllPrice=Foprice;
                        // console.log(AllPrice);
                        $("#money").html(AllPrice);
                        $("#yunfei").val(AllPrice);
                        $("#ok").removeAttr('disabled');
                    }
                };
                if (blong=='2') {
                    if (weight>firstWeight) {
                        var S=weight-firstWeight;
                        var Sprice=Math.ceil(S/addWeight)*Atprice;
                        // console.log(Sprice);
                        var AllPrice=parseInt(Ftprice)+parseInt(Sprice);
                        $("#money").html(AllPrice);
                        $("#yunfei").val(AllPrice);
                        $("#ok").removeAttr('disabled');
                    }else{
                        var AllPrice=Ftprice;
                        $("#money").html(AllPrice);
                        $("#yunfei").val(AllPrice);
                        $("#ok").removeAttr('disabled');
                    }
                };

            };
        })
    }else{
        $("#money").html('');
        $("#yunfei").val('');
        $("#ok").attr('disabled','disabled');
    }

  }

  </script>

</html>