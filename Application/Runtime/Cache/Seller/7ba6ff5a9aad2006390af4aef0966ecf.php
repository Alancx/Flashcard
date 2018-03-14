<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<title><?php echo C('SITE_NAME');?>-<?php echo ($Title); ?></title>
<!-- Bootstrap -->
<!-- <link href="/Public/Plugins/bootstrap/CSS/bootstrap.min.css" rel="stylesheet"> -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/Public/JS/jquery.min.js"></script>
<script src="/Public/Plugins/qrcode/jquery.qrcode.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="/Public/Plugins/bootstrap/JS/bootstrap.min.js"></script> -->
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style type="text/css">

body
{
  width: 1000px;
  margin: 0px;
}

.container
{
  width: 100%;
}

.head
{
 width: 1000px;
 height: 70px;
 position: fixed;
 top: 0px;
 background-color: #FECE4B;
 border-bottom: 1px solid #EEEEEE;
}

.head div
{
  float: left;
}

.head .info
{
  width: 250px;
  height: 70px;
}

.head .menu
{
  width: 150px;
  height: 70px;
  font-size: 16px;
  color: #FFFFFF;
  line-height: 16px;
  text-align: center;
}

.head .menu div
{
  width: 20px;
  height: 20px;
  margin: 10px auto;
  float: none;
  background-size: 20px 20px;
  background-repeat: no-repeat;
}

.selected-tag
{
  background-color: #FEC04B;
}

.head .menu .sy-div
{
  background-image: url(/Public/Images/Web/icon/shouyintai.png);
}

.head .menu .hy-div
{
  background-image: url(/Public/Images/Web/icon/huiyuan.png);
}

.head .menu .tj-div
{
  background-image: url(/Public/Images/Web/icon/shuju.png);
}

.head .menu .sz-div
{
  background-image: url(/Public/Images/Web/icon/shezhi.png);
}

.head .menu .zl-div
{
  background-image: url(/Public/Images/Web/icon/xiugai.png);
}


.goodsInfo
{


}
#goodsList{
  margin-bottom: 50px;
  display: block;
}

.selected-goodsList
{
  background-color:#DCC368 !important;
}

.mouseover-goodsList
{
  background-color: #CCCCCC;
}

.goodsInfo .goodsList
{
  width: 1000px;
  height: 50px;
  text-align: center;
}

.goodsList-goods
{
  color: #333333;
  font-size: 14px;
}

.goodsList-title
{
  position: fixed;
  background-color: #FEC04B;
  top: 70px;
  color: #FFFFFF;
  font-size: 18px;
}

.goodsList-single
{
  background-color: #EEEEEE;
}

.goodsInfo .goodsList div
{
    line-height: 50px;
    height: 50px;
    float: left;
    /*border-left: 1px solid #FECE4B;*/
    border-right: 1px solid #FECE4B;
}

.goodsList div:nth-child(1)
{
  width: 180px;
}

.goodsList div:nth-child(2)
{
  width: 120px;
}

.goodsList div:nth-child(3)
{
  width: 120px;
}

.goodsList div:nth-child(4)
{
  width: 120px;
}

.goodsList div:nth-child(5)
{
  width: 180px;
}

.goodsList div:nth-child(6)
{
  width: 188px;
}

.goodsInfo
{
  width: 1000px;
  height: 50px;
  text-align: center;
  background-color: #FFE08D;
  border-top: 1px solid #FECE4B;
  border-bottom: 1px solid #FECE4B;
}

.goodsInfo div
{
    line-height: 50px;
    /*height: 50px;*/
    float: left;
}


.foot
{
  width: 1000px;
  position: fixed;
  bottom: 0px;
  height: 60px;
  font-size: 20px;
  text-align: center;
  background-color: #FECE4B;
}

.foot div
{
  height: 60px;
  float: left;
  color: #FFFFFF;
  border-left: 1px solid #FECE4B;
  border-right: 1px solid #FECE4B;
  line-height: 60px;
}

.foot span
{
  color: #FF0000;
}

.foot div:nth-child(1)
{
  width: 250px;
}

.foot div:nth-child(2)
{
  width: 250px;
}

.foot div:nth-child(3)
{
  width: 250px;
}

.foot div:nth-child(4)
{
  width: 188px;
}


.tips,.paymoneyDiv{overflow:hidden}
.tips,.waiting,.paymoneyDiv{border-radius:10px;color:#fff;text-align:center;z-index:1000;display:none;position:fixed}
.waiting{width:400px;height:82px;line-height:90pt;background:url(/Public/Images/Wap/loading.gif) center 14px no-repeat rgba(0,0,0,.5);background-size:20px;left:35%;top:150px;margin:-41px 0 0 -52px}
.tips,.paymoneyDiv{width:400px;min-height:25px;word-wrap:break-word;word-break:break-all;padding:10px 0;background:rgba(0,0,0,.5);top:150px;left:35%;border:1px solid #fff}
.paymoneyDiv{height: 125px;}
.paymoneyDiv div{padding: 8px 10px;}
.paymoneyDiv input{height: 21px;font-size: 24px;width: 100px;}

.sons div{
  border-left:1px solid #ccc!important;
  border-top:1px solid #ccc!important;
  border-right: 0px solid #ccc!important;
}


    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>

<div class="container">


<div class="head">
  <div class="info">
    

  </div>

  <div class="menu"  onclick="window.location.href='<?php echo U('Cashier/Cashier');?>'">
    <div class="sy-div"></div>
    <span>收银台</span>
  </div>

  <div class="menu selected-tag">
    <div class="tj-div"></div>
    <span>数据统计</span>
  </div>

</div>

<div style="width: 1000px; height: 70px;"></div>

<div class="goodsInfo">
  <div class="goodsList-title goodsList">
    <!-- <div>选择</div> -->
    <div>订单编号</div>
    <div>商品数量</div>
    <div>总价</div>
    <div>优惠金额</div>
    <div>成交时间</div>
    <div>操作</div>
  </div>

<div style="width: 1000px;height: 50px;"></div>


<?PHP  $money=0;$count=0;$discount=0; ?>


<div id="goodsList">

  <?php if(is_array($olist)): foreach($olist as $key=>$oli): ?><div id="o<?php echo ($oli['OrderId']); ?>" class="goodsList goodsList-goods mianorder" style="border-bottom:1px solid #ccc;border-top:1px solid #FECE4B" data-id="<?php echo ($oli['OrderId']); ?>" data-type="0">
      <!-- <div><input type="checkbox" id="cb<?php echo ($oli['OrderId']); ?>" name="cb<?php echo ($oli['OrderId']); ?>" /></div> -->
      <div><?php echo ($oli['OrderId']); ?></div>
      <div><?php echo ($oli['Count']); ?></div>
      <div><?php echo ($oli['Price']); ?></div>
      <div><?php echo ($oli['Coupon']); ?></div>
      <div><?php echo ($oli['Date']); ?></div>
      <div><?php if($oli['Status'] == '11'): ?><button disabled="true">已撤销</button><?php else: ?><button onclick="reovke('<?php echo ($oli['OrderId']); ?>')">撤销</button><?php endif; ?></div>
    </div>
    <div style="width:100%; display:none;" id="son<?php echo ($oli['OrderId']); ?>" class="sons">
      <div style="width:24%"><small>商品名称</small></div>
      <div style="width:24%"><small>规格</small></div>
      <div style="width:24%"><small>价格</small></div>
      <div style="width:24%"><small>数量</small></div>
    </div>
    <div  class="sons" id="sons<?php echo ($oli['OrderId']); ?>" style="width:100%; display:none;">
    <?php if(is_array($oli["sons"])): foreach($oli["sons"] as $key=>$o): ?><div style="width:24%"><small><?php echo ($o["ProName"]); ?></small></div>
      <div style="width:24%"><small><?php echo ($o["Spec"]); ?></small></div>
      <div style="width:24%"><small><?php echo ($o["Money"]); ?></small></div>
      <div style="width:24%"><small><?php echo ($o["Count"]); ?></small></div><?php endforeach; endif; ?>
    </div>

    <?PHP  if ($oli['Status']!='11') { $money+=$oli['Price']; $count+=$oli['Count']; $discount+=$oli['Coupon']; } ?>

<div style="clear:both"></div><?php endforeach; endif; ?>
</div>

<div style="width: 1000px;height:60px;clear:both"></div>

<div class="foot">
  <div>
    数量：<span class="allCount"><?PHP echo $count; ?></span>
  </div>
  <div>
    总额：<span class="allMoney"><?PHP echo $money; ?></span>
  </div>
  <div>
    总优惠：<span class="offMoney"><?PHP echo $discount; ?></span>
  </div>
  <div>
    <button id="import">导出</button>
  </div>
</div>

</div>

<div class="tips" style="display: none;"></div>
<div class="waiting" style="display: none;"></div>

<!-- footer-menus -->
</body>
<script type="text/javascript">
  $(document).ready(function(){
    $('.mianorder').click(function(){
      var id=$(this).attr('data-id');
      var type=$(this).attr('data-type');
      if (type=='0') {
        $('#son'+id).show();
        $('#sons'+id).show();
        $(this).attr('data-type','1');
      };
      if (type=='1') {
        $('#son'+id).hide();
        $('#sons'+id).hide();
        $(this).attr('data-type','0');
      };
      // $('.sons').hide();
    })
    $('#import').click(function(){
      window.location.href="<?php echo U('Cashier/orderOut');?>";
    })
  })
  function reovke(oid){
    if (confirm('确定要撤销此商品吗？')) {
      window.location.href="<?php echo U('Cashier/revoke');?>?oid="+oid
    };
  }
</script>
</html>