<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
	<title><?php echo ($Title); ?></title>
	<link href="/Public/Sellermobile/CSS/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/Sellermobile/CSS/weui.css" rel="stylesheet">
	<script src="/Public/Sellermobile/JS/jquery.min.js"></script>
	<script src="/Public/Sellermobile/JS/bootstrap.min.js"></script>
	<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="/Public/Sellermobile/JS/base.js"></script>
	<link rel="stylesheet" href="/Public/Sellermobile/CSS/pagemodel.css?v=1.0" media="screen" title="no title" charset="utf-8">

   <!-- 微信分享 -->
	 <script type="text/javascript">
	 var wxJSSDKConfig = <?php echo ($wxJSSDKConfigStr); ?>;
	 wx.config(wxJSSDKConfig);

	 wx.ready(function (a) {

	   wx.hideAllNonBaseMenuItem();

    <?php echo ($showlist); ?>
	  //  wx.showMenuItems({
	  //    menuList: ["menuItem:share:appMessage","menuItem:share:timeline"] // 要显示的菜单项，所有menu项见附录3
	  //  });
	   wx.onMenuShareAppMessage({
	     title: '<?php echo ($shopname); ?>',
	     desc: '<?php echo ($shopdesc); ?>',
	     link: '<?php echo ($shareUrl); ?>',
	     imgUrl: '<?php echo ($shareImg); ?>',
	     trigger: function (res) {

	     },
	     success: function (res) {
	       alert('已分享');
	     },
	     cancel: function (res) {
	       alert('已取消');
	     },
	     fail: function (res) {
	       alert(JSON.stringify(res));
	     }
	   });

	   wx.onMenuShareTimeline({
	     title: '<?php echo ($Title); ?>',
	     link: '<?php echo ($shareUrl); ?>',
	     imgUrl: '<?php echo ($shareImg); ?>',
	     trigger: function (res) {

	     },
	     success: function (res) {
	       alert('已分享');
	     },
	     cancel: function (res) {
	       alert('已取消');
	     },
	     fail: function (res) {
	       alert(JSON.stringify(res));
	     }
	   });
	 });
	 wx.error(function (res) {
	   // alert(res);
	 });
	 </script>

</head>
<body>
	<!-- 正文显示区域 -->
	<div class="container">
		
<!-- <script src="/Public/Adminmobile/js/DatePicker/WdatePicker.js"></script> -->
<script src="/Public/Adminmobile/js/mobisscroll3.0/js/mobiscroll.custom-3.0.0-beta2.min.js"></script>
<link rel="stylesheet" href="/Public/Adminmobile/js/mobisscroll3.0/css/mobiscroll.custom-3.0.0-beta2.min.css">
<style type="text/css">

.proinfo{
  height:65%;
  width:100%;
  overflow: auto;
}

.proinfo .title{
  position: fixed;
  width:100%;
  height:30px;
  line-height:30px;
  margin:auto;
  background-color: #FAFAFC;
}

.proinfo .prodiv{
  display: table;
  width:98%;
  height:30px;
  line-height:30px;
  margin:auto;
}

.prodiv div{float:left;}
.prodiv div:nth-child(1){width:5%;}
.prodiv div:nth-child(2){width:60%;}
.prodiv div:nth-child(3){width:18%;}
.prodiv div:nth-child(4){width:17%;}


.attrs-info {
  padding-left: 15px;
  padding-right: 15px;
  margin-bottom: 15px;
}

.attrs-info b {
  padding-top: 15px;
  padding-bottom: 10px;
  font-size: 14px;
  color: #333;
  display: block;
}

.attrs-info span{
  float: left;
  font-size: 14px;
  width: 80px;
  color: #fff;
  padding: 5px 10px;
  border-radius: 3px;
  margin: 5px 8px 5px auto;
  text-align: center;
}

.attrs-info .attrs-list{
  display: inline-table;
  word-wrap: break-word;
}

.attrs-info .atr{
  background-color: #ccc;
}

.attrs-info .atr-select {
  background-color: #ffb222;
}
#inDate{
  background-color: #ffffff!important;
}


</style>


<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px;">
  <div>
    <div class="col-lg-7">
      <div class="ibox float-e-margins">

        <div class="ibox-title">
          <h5>入库单录入</h5>
          <div class="ibox-tools">
            <a class="collapse-card">
              <i style="font-style: italic;" id="InWarehouseId"></i>
            </a>
          </div>
        </div>

        <div class="ibox-content" style="padding-bottom: 10px">

          <form class="form-horizontal m-t" id="commentForm" novalidate="novalidate">

            <div class="form-group">
              <label class="col-sm-2 control-label">入库类型：</label>
              <div class="col-sm-8">
                <select class="form-control" id="inType">
                  <option value="0" selected="selected">采购入库</option>
<!--                   <option value="1">调拨入库</option>
                  <option value="2">退货入库</option>
                  <option value="3">差错入库</option> -->
                  <option value="4">订货入库</option>
                </select>
              </div>
            </div>

            <div class="form-group rkOrderIdDiv">
              <label class="col-sm-2 control-label">订货单选择：</label>
              <div class="col-sm-8">
                <select class="form-control" id="rkOrderId">
                    <option value="-1" selected="selected">请选择订货单</option>
                  <?php if(is_array($rkOrder)): foreach($rkOrder as $key=>$ro): ?><option value="<?php echo ($ro['InWarehouseId']); ?>"><?php echo ($ro['InWarehouseId']); ?></option><?php endforeach; endif; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">入库单号：</label>
              <div class="col-sm-8">
                <input id="InWarehouseNumber" type="text" class="form-control" value="" name="InWarehouseNumber">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label"><i class="fa fa-bookmark" style="color:red"></i>入库仓库：</label>
              <div class="col-sm-8">
                <select tabindex="1" class="form-control" id="inputWarehouse">
                    <option value="wh<?php echo substr($sinfo['token'],-8);?>_<?php echo ($sinfo['id']); ?>"><?php echo ($sinfo['storename']); ?></option>
                </select>

              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">入库人：</label>
              <div class="col-sm-8">
                <select class="form-control" id="inputName">
                  <?php if(is_array($uinfo)): foreach($uinfo as $key=>$ui): ?><option value="<?php echo ($ui['id']); ?>"><?php echo ($ui['TrueName']); ?></option><?php endforeach; endif; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">入库日期：</label>
              <div class="col-sm-8">
                <!-- <input id="inDate" type="text" class="form-control Wdate" value="" name="inDate" required="" aria-required="true" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd HH:mm'})" style="width:200px;display: inline-block;"> -->
                <input id="inDate" type="text" class="form-control " value="<?php echo date('Y-m-d H:i');?>" name="inDate" required="" aria-required="true" style="display: inline-block;">

                <!-- <a type="button" data-container="body" data-toggle="popover" data-placement="right" data-content="如：2015-06-05 15:30" data-original-title="" title="" aria-describedby="popover481307">格式提示</a> -->
              </div>
            </div>



<!--             <div class="form-group">
              <label class="col-sm-2 control-label">备注说明：</label>
              <div class="col-sm-8">
                <textarea id="Remarks" name="comment" value="" class="form-control" required="" aria-required="true"></textarea>
              </div>
            </div> -->

            <div class="form-group">
              <div class="col-sm-8">
                <button class="btn btn-block btn-outline btn-primary" type="button" id="SelectPro" onclick="selectProFun();">添加入库商品</button>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-8">
                <p>
                  <button class="btn btn-block btn-outline btn-primary" type="button" id="btnOkIn" onclick="subPage();">提交入库单据</button>
                </p>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<div id="addpro" style="display:none;">
  <div id="addprobg" style="position: fixed;z-index: 0;top: 0;left: 0;width:100%;height:100%;background-color:rgba(0, 0, 0, 0.6);">

  </div>

  <div style="position: fixed;z-index: 0;width: 95%;height:90%;top: 50%;left: 50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);background-color: #FAFAFC;border-radius: 3px;">

    <div style="padding:15px;font-size:16px;"><strong>添加入库商品</strong></div>

    <div class="form-group selectPro">

      <label class="control-label" style="padding-left:20px;">选择分类：</label>

      <select class="form-control" style="width:150px;display:inline;" id="addproclass" onchange="getProduct();">
        <option value="-1" selected="selected">请选择分类</option>
        <?php if(is_array($cinfo)): foreach($cinfo as $key=>$ci): ?><option value="<?php echo ($ci['ClassId']); ?>"><?php echo ($ci['ClassName']); ?></option><?php endforeach; endif; ?>
      </select>

    </div>

    <div class="form-group selectPro">
      <label class="control-label" style="padding-left:20px;">选择商品：</label>

      <select class="form-control" style="width:150px;display:inline;" id="addpropro" onchange="getProAttr();">
        <option value="-1" selected="selected">请选择商品</option>
      </select>

    </div>

    <div class="proinfo">
      <div class="title">
        <div style="float:left;width:5%;">&nbsp;</div>
        <div style="float:left;width:60%;">&nbsp;商品</div>
        <div style="float:left;width:18%;">价格</div>
        <div style="float:left;width:17%;">数量</div>
      </div>
      <div style="width:100%;height:30px;"></div>

      <div id="prolist">

      </div>


      <div style="line-height:40px;line-height:40px;position: fixed;bottom: 0px;height: 40px;width: 100%; background-color:#FAFAFC;">
        <div style="float:left;width:100%;text-align:center;" onclick="subPro();"><span>确定</span></div>
      </div>

    </div>


    <div id="proattr" style="background-color:#FAFAFC;border:1px solid #333333;height:60%;width:100%;bottom:0px;position:fixed;overflow: auto; display:none;">

      <div style="line-height:40px;line-height:40px;position: fixed;height: 40px;width: 100%; background-color:#FAFAFC;">
        选择商品属性
      </div>
      <div id="proattrsign" style="height: 40px;width: 100%;"></div>

      <div style="height: 40px;width: 100%;"></div>

      <div style="line-height:40px;line-height:40px;position: fixed;bottom: 0px;height: 40px;width: 100%; background-color:#FAFAFC;">
        <div style="float:left;width:50%;text-align:center;" onclick="subAttr();"><span>确定</span></div>
        <div style="float:left;width:50%;text-align:center;" onclick="escAttr();"><span>取消</span></div>
      </div>

    </div>


  </div>

</div>
<script type="text/javascript">
/////日期控件//////
$(function () {
  var currYear = (new Date()).getFullYear();
  var opt={
        preset: 'date', //日期
        theme: 'android-holo-light', //皮肤样式
        lang: 'zh',  //只用语言
        display: 'bottom', //显示方式
        mode: 'clickpick', //日期选择模式
        dateFormat: 'yy-mm-dd', // 日期格式
        monthNames:['01','02','03','04','05','06','07','08','09','10','11','12'],
        monthNamesShort:['01','02','03','04','05','06','07','08','09','10','11','12'],
        setText: '确定', //确认按钮名称
        cancelText: '取消',//取消按钮名
        dateOrder: 'yymmdd', //面板中日期排列格式
        endYear:2020 //结束年份
      };
  // $("#inDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
   $("#inDate").mobiscroll().datetime(opt);


   $("#inType").change(function(e){

      if ($(this).val()=='4')
      {
        $('.rkOrderIdDiv').show();
        $('#rkOrderId').val('-1');
        $('#InWarehouseNumber').attr('disabled',true);

        $('.selectPro').hide();


        $('#SelectPro').text('查看订货商品');
      }
      else
      {
        $('.rkOrderIdDiv').hide();
        $('#rkOrderId').val('-1');
        $('#InWarehouseNumber').attr('disabled',false);

        $('.selectPro').show();

        $('#SelectPro').text('添加入库商品');
      }
      $('#prolist').html('');

   });

   $("#rkOrderId").change(function(e){
    if ($(this).val()=='-1')
    {
      $('#InWarehouseNumber').val('');
      $('#prolist').html('');
    }
    else
    {


      if (ajaxLock) {
        return false;
      }
      ajaxLock=true;

      var nowRKID=$(this).val();

      $.ajax({
        url:"<?php echo U('UMWareHouse/getSQWarehouseInfo');?>",
        type:"post",
        data:{sqid:nowRKID},
        dataType:"json",
        beforeSend:function(){
          tips('waiting','数据处理中...');
        },
        success:function(msg)
        {
          if (msg.status)
          {

              $('#InWarehouseNumber').val(nowRKID);

              var tempHtmlStr="";

              for (var i = msg.data.length - 1; i >= 0; i--) {

                tempHtmlStr+='<div class="prodiv pro" data-id="" data-cid="" data-price="0" data-nums="0"><div><span class="removepro glyphicon glyphicon-remove" aria-hidden="true"></span></div><div>'+msg.data[i]['ProName']+','+msg.data[i]['ProSpec1']+'</div><div>'+msg.data[i]['Price']+'</div><div>'+msg.data[i]['Count']+'</div></div>';



              }
              pageObj.prolist.html(tempHtmlStr);


          }
          else
          {
            tips('notice','操作失败',2000,'weui_icon_notice');
          }
        },
        complete:function()
        {
          $("#waiting").hide();
          ajaxLock=false;
        }
      });




    }
   });




  //$('.rkOrderIdDiv').hide();







  $('.rkOrderIdDiv').show();
  $('#rkOrderId').val('-1');
  $('#InWarehouseNumber').attr('disabled',true);

  $('.selectPro').hide();


  $('#SelectPro').text('查看订货商品');

  $('#inType').val('4');




});
var ajaxLock=false;
var testTemp=null;
var pageObj={addproclass:$("#addproclass"),addpropro:$("#addpropro"),proinfo:$("#proinfo"),addpro:$("#addpro"),prolist:$("#prolist"),proattr:$("#proattr"),proattrsign:$("#proattrsign")};

function selectProFun()
{
  pageObj.addpro.show(100);
}
function subPro()
{
  pageObj.addpro.hide(100);
}
function getProduct()
{
  var cidVar=pageObj.addproclass.val();
  if (cidVar=='-1') {
    pageObj.addpropro.find('option[value!="-1"]').remove();
    return false;
  }

  if (ajaxLock) {
    return false;
  }
  ajaxLock=true;

  $.ajax({
    url:"<?php echo U('UMWareHouse/getProduct');?>",
    type:"post",
    data:{"type":"cg","cid":cidVar},
    dataType:"json",
    beforeSend:function(){
      tips('waiting','数据处理中...');
    },
    success:function(msg)
    {
      if (msg.status)
      {
        if (msg.data!='-1')
        {
          setProduct(msg.data);
        }
        else
        {
          tips('notice','该分类下没有商品',2000,'weui_icon_notice');
        }
      }
      else
      {
        tips('notice','操作失败',2000,'weui_icon_notice');
      }
    },
    complete:function()
    {
      $("#waiting").hide();
      ajaxLock=false;
    }
  });
}

function setProduct(data)
{
  pageObj.addpropro.find('option[value!="-1"]').remove();

  var appendStr="";

  for(var key in data){
    appendStr+="<option value=\""+data[key].ProId+"\">"+data[key].ProName+"</option>";
  }
  pageObj.addpropro.append(appendStr);
}



function getProAttr()
{
  var pidVar=pageObj.addpropro.val();
  if (pidVar=='-1') {
    return false;
  }

  if (ajaxLock) {
    return false;
  }
  ajaxLock=true;

  $.ajax({
    url:"<?php echo U('UMWareHouse/getProAttr');?>",
    type:"post",
    data:{"pid":pidVar},
    dataType:"json",
    beforeSend:function(){
      tips('waiting','数据处理中...');
    },
    success:function(msg)
    {
      if (msg.status)
      {
        if (msg.data!='-1')
        {
          setProAttr(msg.data);
          pageObj.proattr.show(300);
        }
        else
        {
          tips('notice','获取数据错误',2000,'weui_icon_notice');
        }
      }
      else
      {
        tips('notice','操作失败',2000,'weui_icon_notice');
      }
    },
    complete:function()
    {
      $("#waiting").hide();
      ajaxLock=false;
    }
  });
}

function setProAttr(data)
{
  pageObj.proattr.find('.attrs-info').remove();

  var appendStr="";


    appendStr+="<div class=\"attrs-info\"><b>规格</b><div class=\"attrs-list\">";

    for(var key in data)
    {
      appendStr+="<span class=\"attr atr\" data-s=\""+data[key]['ProIdCard']+"\">"+data[key]['ProSpec1']+"</span>";
    }

    appendStr+="</div></div>";
  // console.log(appendStr);
  pageObj.proattrsign.after(appendStr);
  bindAttrClick();
}

// <div class="attrs-info">
// <b>重量</b>
//     <div class="attrs-list">
//         <span class="attr atr" data-s="7">375g</span>
//         <span class="attr atr" data-s="8">375g</span>
//     </div>
// </div>


var selectedAttrObj=null;
function bindAttrClick(){

  $(".attr").click(function(){
    selectedAttrObj=$(this);
    selectedAttrObj.parent().find(".attr").removeClass("atr-select").removeClass("atr").addClass("atr");
    selectedAttrObj.addClass("atr-select");
    selectedAttrObj=null;
  });
}

function subAttr()
{
  if ($(".attrs-info").length!=pageObj.proattr.find(".atr-select").length) {
    tips('notice','请选择商品属性',2000,'weui_icon_notice');
    return false;
  }
  else
  {
    //添加商品到商品列表
    var proinfoArray={pname:pageObj.addpropro.find("option:selected").text(),pcid:pageObj.addpropro.val(),cid:pageObj.addproclass.val(),pcname:""};
    var attrSelectObj=null;
    $(".atr-select").each(function(k,v){
      attrSelectObj=$(v);
      proinfoArray.pcid=attrSelectObj.attr('data-s');
      proinfoArray.pcname+=attrSelectObj.html()+",";

    });

    if ($(".pro-"+proinfoArray.pcid).length>0) {
      tips('notice','已经添加过该商品',2000,'weui_icon_notice');
    }
    else
    {

      var insertProStr=proinfoStr.replace(/PROIDREPLACE/g, proinfoArray.pcid);
      insertProStr=insertProStr.replace(/PROCIDREPLACE/g, proinfoArray.cid);
      insertProStr=insertProStr.replace(/PRONAMEREPLACE/g, proinfoArray.pname+" "+proinfoArray.pcname);
      pageObj.prolist.append(insertProStr);
      bindRemoveProClick();
    }

    pageObj.proattr.hide(300);
    pageObj.addpropro.get(0).selectedIndex=0;
  }

}

var proinfoStr='<div class="prodiv pro-PROIDREPLACE" data-id="PROIDREPLACE" data-cid="PROCIDREPLACE" data-price="0" data-nums="0"><div><span class="removepro glyphicon glyphicon-remove" aria-hidden="true"></span></div><div>PRONAMEREPLACE</div><div><input type="text" class="form-control input-innum price" style="width:80%;padding: 6px 6px;" value="0"></div><div><input type="text" class="form-control input-innum nums" style="width:80%;padding: 6px 6px;" value="0"></div></div>';

function escAttr()
{
  pageObj.addpropro.get(0).selectedIndex=0;
  pageObj.proattr.hide(300);
}

function bindRemoveProClick()
{
  $(".removepro").click(function(){
    $(this).parents('.prodiv').remove();
  });
}


function subPage()
{
  //InWarehouseNumber   inputWarehouse   inputName   inDate     inType  Remarks   prolist
  var inputVar={id:$("#InWarehouseNumber").val(),whid:$("#inputWarehouse").val(),nums:0,whname:$("#inputWarehouse").find("option:selected").text(),ipid:$("#inputName").val(),ipname:$("#inputName").find("option:selected").text(),idate:$("#inDate").val(),itype:$("#inType").val(),remarks:$("#Remarks").val()};
  var proarray={};

  if (inputVar.whid=="-1") {
    tips('notice','请选择入库仓库',2000,'weui_icon_notice');
    return false;
  }
  if (inputVar.ipid=="-1") {
    tips('notice','请选择入库人',2000,'weui_icon_notice');
    return false;
  }
  if (inputVar.idate=="") {
    tips('notice','请填写入库日期',2000,'weui_icon_notice');
    return false;
  }

  //pageObj.prolist

  var tempNowProObj=null;

  pageObj.prolist.find(".prodiv").each(function(k,v){
    tempNowProObj=$(v);
    proarray[tempNowProObj.attr("data-id")]={price:tempNowProObj.find(".price").val(),nums:tempNowProObj.find(".nums").val(),cid:tempNowProObj.attr("data-cid")};
    tempNowProObj=null;
  });

  if (ajaxLock) {
    return false;
  }
  ajaxLock=true;

  $.ajax({
    url:"<?php echo U('UMWareHouse/addInWarehouse');?>",
    type:"post",
    data:{mt:inputVar,st:proarray},
    dataType:"json",
    beforeSend:function(){
      tips('waiting','数据处理中...');
    },
    success:function(msg)
    {
      if (msg.status)
      {
        tips('notice','入库成功',2000,'weui_icon_notice');

        setTimeout(function(e){
            window.location.href="<?php echo U('UMWareHouse/whmage');?>";
        },2000);
      }
      else
      {
        tips('notice','操作失败',2000,'weui_icon_notice');
      }
    },
    complete:function()
    {
      $("#waiting").hide();
      ajaxLock=false;
    }
  });
}


</script>

	</div>

	<!-- 底部导航栏 -->
	<?php if($footerSign == 1): ?><div style="height:50px"></div>
		<div class="footer">
			<div>
				<a href="<?php echo U('Index/Index');?>">
					<?php if (CONTROLLER_NAME=='Index' && ACTION_NAME=='Index'): ?>
					<img src="/Public/Sellermobile/icon/shop-act.png">
					<label style="color:#ff3e30">店铺</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/shop.png">
					<label>店铺</label>
				<?php endif; ?>
				</a>
			</div>
			<div>
				<a href="<?php echo U('Products/prolist');?>">
					<?php if (CONTROLLER_NAME=='Products' && ACTION_NAME=='prolist'): ?>
						<img src="/Public/Sellermobile/icon/product-act.png">
						<label style="color:#ff3e30">商品</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/product.png">
					<label>商品</label>
				<?php endif; ?>
				</a>
			</div>
			<div>
				<a>
					<img src="/Public/Sellermobile/icon/active.png">
					<label>动态</label>
				</a>
			</div>
			<div>
				<a href="<?php echo U('User/Index');?>">
					<?php if (CONTROLLER_NAME=='User' && ACTION_NAME=='Index'): ?>
					<img src="/Public/Sellermobile/icon/center-act.png">
					<label style="color:#ff3e30">我的</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/center.png">
					<label>我的</label>
				<?php endif; ?>
				</a>
				<!-- <a>
					<img src="/Public/Sellermobile/icon/center.png">
					<label>我的</label>
				</a> -->
			</div>
		</div><?php endif; ?>
	<!-- weui提示框 -->
	<div id="notice" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content"></p>
		</div>
	</div>

	<div class="weui_dialog_confirm" id="confirm" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">操作提示</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog default" id="esc">取消</a>
				<a href="javascript:;" class="weui_btn_dialog primary" id="enter" data-s="" data-idcard=''>确定</a>
			</div>
		</div>
	</div>


	<div class="weui_dialog_alert" id="alert" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">提示信息</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog primary" id='alertenter'>确定</a>
			</div>
		</div>
	</div>

	<div id="waiting" class="weui_loading_toast" style="display:none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<div class="weui_loading">
				<div class="weui_loading_leaf weui_loading_leaf_0"></div>
				<div class="weui_loading_leaf weui_loading_leaf_1"></div>
				<div class="weui_loading_leaf weui_loading_leaf_2"></div>
				<div class="weui_loading_leaf weui_loading_leaf_3"></div>
				<div class="weui_loading_leaf weui_loading_leaf_4"></div>
				<div class="weui_loading_leaf weui_loading_leaf_5"></div>
				<div class="weui_loading_leaf weui_loading_leaf_6"></div>
				<div class="weui_loading_leaf weui_loading_leaf_7"></div>
				<div class="weui_loading_leaf weui_loading_leaf_8"></div>
				<div class="weui_loading_leaf weui_loading_leaf_9"></div>
				<div class="weui_loading_leaf weui_loading_leaf_10"></div>
				<div class="weui_loading_leaf weui_loading_leaf_11"></div>
			</div>
			<p class="weui_toast_content">数据加载中</p>
		</div>
	</div>
</body>
</html>