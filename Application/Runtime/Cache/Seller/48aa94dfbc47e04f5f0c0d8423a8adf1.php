<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
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
<script src="/Public/JS/plugins/LodopFuncs.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="/Public/Plugins/bootstrap/JS/bootstrap.min.js"></script> -->
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style type="text/css">body{width:1000px;margin:0}.container{width:100%}.head{width:1000px;height:70px;position:fixed;top:0;background-color:#FECE4B;border-bottom:1px solid #EEE}.head div{float:left}.head .info{width:250px;height:70px}.head .menu{width:150px;height:70px;font-size:16px;color:#FFF;line-height:16px;text-align:center}.head .menu div{width:20px;height:20px;margin:10px auto;float:none;background-size:20px 20px;background-repeat:no-repeat}.selected-tag{background-color:#FEC04B}.head .menu .sy-div{background-image:url(/Public/Images/Web/icon/shouyintai.png)}.head .menu .hy-div{background-image:url(/Public/Images/Web/icon/huiyuan.png)}.head .menu .tj-div{background-image:url(/Public/Images/Web/icon/shuju.png)}.head .menu .sz-div{background-image:url(/Public/Images/Web/icon/shezhi.png)}.head .menu .zl-div{background-image:url(/Public/Images/Web/icon/xiugai.png)}.selected-goodsList{background-color:#DCC368!important}.mouseover-goodsList{background-color:#CCC}.goodsInfo .goodsList{width:1000px;height:50px;text-align:center}.goodsList-goods{color:#333;font-size:14px}.goodsList-title{position:fixed;background-color:#FEC04B;top:70px;color:#FFF;font-size:18px}.goodsList-single{background-color:#EEE}.goodsInfo .goodsList div{line-height:50px;height:50px;float:left;border-left:1px solid #FECE4B;border-right:1px solid #FECE4B}.goodsList div:nth-child(1){width:90px}.goodsList div:nth-child(2){width:150px}.goodsList div:nth-child(3){width:210px}.goodsList div:nth-child(4){width:120px}.goodsList div:nth-child(5){width:120px}.goodsList div:nth-child(6){width:120px}.goodsList div:nth-child(7){width:176px}.goodsInfo .addGoods{width:1000px;height:50px;text-align:center;background-color:#FFE08D;border-top:1px solid #FECE4B;border-bottom:1px solid #FECE4B}.goodsInfo .addGoods div{line-height:50px;height:50px;float:left}.addGoods div:nth-child(1){width:90px}.addGoods div:nth-child(2){width:360px}.payment{width:1000px;position:fixed;bottom:77px;height:60px;font-size:20px;text-align:center;background-color:#FECE4B}.payment div{height:60px;float:left;color:#FFF;border-left:1px solid #FECE4B;border-right:1px solid #FECE4B;line-height:60px}.payment span{color:red}.payment div:nth-child(1){width:200px}.payment div:nth-child(2){width:200px}.payment div:nth-child(3){width:200px}.payment div:nth-child(4){width:210px}.payment div:nth-child(5){width:162px}.foot{width:1000px;position:fixed;bottom:0;height:77px;background-color:#E1F8Fa}.foot div{margin-top:15px}.foot span{color:#333;font-size:14px;margin-left:15px}.choseCoupon{position:absolute;width:400px;height:150px;background:rgba(0,0,0,.5);left:300px;top:150px;border-radius:5px}.choseCoupon div{width:300px;margin-left:50px;margin-top:15px}.choseCoupon span{font-size:10px;color:#ccc}.choseCoupon div input{width:110px}.paymoneyDiv,.tips{overflow:hidden}.paymoneyDiv,.tips,.waiting{border-radius:10px;color:#fff;text-align:center;z-index:1000;display:none;position:fixed}.waiting{width:400px;height:82px;line-height:90pt;background:url(/Public/Images/Wap/loading.gif) center 14px no-repeat rgba(0,0,0,.5);background-size:20px;left:35%;top:150px;margin:-41px 0 0 -52px}.paymoneyDiv,.tips{width:400px;min-height:25px;word-wrap:break-word;word-break:break-all;padding:10px 0;background:rgba(0,0,0,.5);top:150px;left:35%;border:1px solid #fff}.paymoneyDiv div{padding:8px 10px}.paymoneyDiv input{height:21px;font-size:24px;width:100px}.paymoneyDivs,.tips{overflow:hidden}.paymoneyDivs,.tips,.waiting{border-radius:10px;color:#fff;text-align:center;z-index:1000;display:none;position:fixed}.waiting{width:400px;height:82px;line-height:90pt;background:url(/Public/Images/Wap/loading.gif) center 14px no-repeat rgba(0,0,0,.5);background-size:20px;left:35%;top:150px;margin:-41px 0 0 -52px}.paymoneyDivs,.tips{width:400px;min-height:25px;word-wrap:break-word;word-break:break-all;padding:10px 0;background:rgba(0,0,0,.5);top:150px;left:35%;border:1px solid #fff}.paymoneyDivs div{padding:8px 10px}.paymoneyDivs input{height:21px;font-size:24px;width:60%}.otherInfo{color:#aaa!important;font-size:10px!important}.otherInfo{color:#aaa!important;font-size:10px!important}
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>

<div class="container">


<div class="head">
  <div class="info">
    

  </div>

  <div class="menu selected-tag">
    <div class="sy-div"></div>
    <span>收银台</span>
  </div>

  <div class="menu" onclick="window.location.href='<?php echo U('Seller/Cashier/Order');?>'">
    <div class="tj-div"></div>
    <span>数据统计</span>
  </div>

</div>

<div style="width: 1000px; height: 70px;"></div>

<div class="goodsInfo">
  <div class="goodsList-title goodsList">
    <div>序号</div>
    <div>货号</div>
    <div>商品名称</div>
    <div>单价</div>
    <div>折扣</div>
    <div>数量</div>
    <div>金额</div>
  </div>

  <div style="width: 1000px;height: 50px;"></div>

<div id="goodsList">


</div>


<div class="addGoods goodsList-goods">
  <div>货号</div>
  <div><input type="text" id="goodsId" value="" style="height: 30px; font-size: 18px;"></input></div>

</div>



<div style="width: 1000px;height:137px"></div>

<div class="payment">
  <div>
    数量：<span class="allCount">0</span>
  </div>
  <div>
    应收：<span class="allMoney">0</span>元 <span class="allscore">0</span>积分
  </div>
  <div>
    总优惠：<span class="offMoney">0</span>
  </div>

  <div>
    支付方式：<select name="PayType" id="PayType" size="1">
      <option value="XJ" selected="selected">现金付款</option>
      <option value="YE">余额付款</option>
      <option value="JL">奖励余额付款</option>
      <option value="T">微信收款</option>
    </select>
  </div>
<!--   <div>
    找零：<span class="change">0</span>
  </div>
 -->
<div style="float: right; width: 176px;"><input type="button" value="结算" onclick="pay();"></input></div>

</div>



<div class="foot">
  
  <div>
    <span>会员：</span><span class="memberName"><input type="text" id="memberId" value=""></input></span>
    <span>订单号：</span><span class="orderId"><?php echo ($orderId); ?></span>
    <span style="display:none;"  class="discount">账户余额：</span><span id='MemberMoney' ></span>
    <span style="display:none;"  class="discount">奖励余额：</span><span id='VirtualMoney' ></span>
    <span style="display:none;"  class="discount">账户积分：</span><span id='userscore' ></span>
  </div>

  <div>
    <span class="otherInfo">快捷键说明：ctrl选择货号输入框、alt 选择会员输入框、 esc选中支付方式 ↑ ↓ 选择支付方式 、insert键 支付 、delete键删除选中商品、</span>
  </div>

<!--   <div>
    <span>收银员：</span><span class="userId">0</span>
    <span>仓库：</span><span class="warehouse">0</span>
  </div> -->
<div id="downloadlodop" style="float:right; width:100px; margin-right:10px;display:block;position: fixed;bottom: 10px;right: 10px;"><a href="/Public/download/PRINTPLUGINS.zip" target="_blank">下载插件</a></div>
</div>

</div>

<div class="tips" style="display: none;"></div>
<div class="waiting" style="display: none;"></div>

<div class="paymoneyDiv" style="display: none;">
    <div>
      <label>应收金额</label>
      <input type="text" id="pmMoney" disabled="disabled" value="0"></input>元
    </div>
    <div>
      <label>实收金额</label>
      <input type="text" id="pmPayMoney" value=""></input>元
    </div>
    <div>
      <label>找零金额</label>
      <input type="text" id="pmBackMoney" disabled="disabled" value="0"></input>元
    </div>
    <div><span id='msg'></span></div>
</div>
<div class="paymoneyDivs" style="display: none;">
    <div>
      <label>应收金额</label>
      <input type="text" id="pmMoneys" disabled="disabled" value="0"></input>元
    </div>
    <div>
      <label>请 扫 码</label>
      <input type="text" id="wecode"></input>
    </div>
    <div><span id='msg'></span></div>
</div>
<div class="choseCoupon" style="display: none;">
    <div>
      <label>选择优惠券</label>
      <select name="CouponId" id="CouponId">
        <option value="nouser">不使用优惠券</option>
        <option value="">list</option>
      </select>
    </div>
    <div>
      <label>优惠后金额</label>
      <input type="text" id="pmBackMoneys" disabled="disabled" value="0"></input>元
    </div>
    <div><span>快捷键说明： ↑ ↓ 键选择优惠券 Insert键下单支付</span></span></div>
</div>


<div id="ddd" style="width:100%; display:none;">
<table width="180" border="0" cellpadding="0" id="printTab" cellspacing="0" style="font-family:幼圆;font-size:10px;border-style:none;">
  <tbody>
    <tr>
      <td height="15px" colspan="4" style="text-align:center; font-size:12px;">[SHOPNAME]欢迎您</td>
    </tr>
    <tr>
      <td height="15px" colspan="4" style="text-align:center; font-size:12px;">[COMPANYNAME]</td>
    </tr>
    <tr>
      <td colspan="4" ><hr  style="height:1px;border:none;border-top:1px solid #000;"/></td>
    </tr>
    <tr>
      <td colspan="4">销售单号:[ORDERID]</td>
    </tr>
    <tr>
      <td colspan="4">顾客名称:[USERID]</td>
    </tr>
    <tr>
      <td colspan="4">账户余额:[USERMONEY]</td>
    </tr>
    <tr>
      <td colspan="4">奖励余额:[USERVIRTUALMONEY]</td>
    </tr>
    <tr>
      <td colspan="4">积分余额:[USERSCORE]</td>
    </tr>
    <tr >
      <td colspan="4" ><hr  style="height:1px;border:none;border-top:1px solid #000;"/></td>
    </tr>
    <tr>
      <td width="40%" style="text-align:center;">商品名称</td>
      <td width="20%" style="text-align:center;">折后价</td>
      <td width="20%" style="text-align:center;">数量</td>
      <td width="20%" style="text-align:center;">金额</td>
    </tr>
    <tr >
      <td colspan="4" ><hr style="height:1px;border:none;border-top:1px solid #000;"/></td>
    </tr>
    
    <tr><td colspan="4">[GOODSINFO]</td></tr>
    
    <tr>
      <td colspan="4"><hr style="height:1px;border:none;border-top:1px solid #000;"/></td>
    </tr>
    <tr >
      <td colspan="4">
      合计数量:[ALLCOUT]个    合计金额:[ALLMONEY]元
      合计积分:[ALLSCORE]分
      </td>
    </tr>
    <tr >
      <td colspan="4" >折前:[OLDMONEY]元　优惠:[OFFMONEY]元</td>
    </tr>
    <tr >
      <td colspan="4" >实收:[USERPAYMONEY]元　找零:[BACKMONEY]元</td>
    </tr>
    <tr>
      <td colspan="4" ><hr style="height:1px;border:none;border-top:1px solid #000;"/></td>
    </tr>
    <tr>
      <td colspan="4" >收银员:[CASHER]</td>
    </tr>
    <tr>
      <td colspan="4" >销售时间:[SELLTIME]</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:center; height:20px; ">欢迎再次光临</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:center;">联系电话：[SHOPPHONE]</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:center;"></td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:center;"></td>
    </tr>
  </tbody>
</table>

</div>
<div id="shopQRcode" style="display:none"></div>
<!-- footer-menus -->
</body>
<!--<script type="text/javascript" src="/Public/JS/Wap/car.js"></script>-->
<!-- <script type="text/javascript" src="/Public/JS/Wap/Base.js"></script> -->
<script type="text/javascript">
var base64codeQRcode='';

var nowSubType="";
var orderIdVar="<?php echo ($orderId); ?>";

var allGoodsIdArray=new Array();

var nowSelectGoodsListItemObj=null;
var altKey=false;
var MemberCoupons='';
var goodsListObj=$('#goodsList');
var goodsListItemObj=null;
var MemberInfo="";   //用户信息
var MemberCoupons='no';  //用户优惠券
var _OrderCouponId='';  //订单使用的优惠券ID
var tempvar='';
var _OrderType='';//订单类型
var goodsListTempHTML="";
var goodsListParam={id:"",no:"",goodsId:"",goodsName:"",price:"",discount:"",count:"",money:""};

var goodsIdTextBoxObj=$('#goodsId');

var publicObjArray={allCount:$('.allCount'),allMoney:$('.allMoney'),offMoney:$('.offMoney'),payMoney:$('.payMoney'),change:$('.change'),orderId:$('.orderId'),memberId:$('#memberId'),otherInfo:$('.otherInfo')};

var cashPublicObjArray={pmDiv:$(".paymoneyDiv"),pmMoney:$('#pmMoney'),pmPayMoney:$('#pmPayMoney'),pmBackMoney:$('#pmBackMoney')};

var tempPaymentData=null;

var tempGoodsNumArray=new Array();
function bindGoodsListItemAction(obj)
{
  obj.mouseover(function(){
    $(this).addClass('mouseover-goodsList');
  });

  obj.mouseout(function(){
    $(this).removeClass('mouseover-goodsList');
  });

  obj.click(function(){
    tempGoodsNumArray=new Array();
    nowSelectGoodsListItemObj=$(this);
    if ($(this).hasClass('selected-goodsList')==true) {
      
      nowSelectGoodsListItemObj.removeClass('selected-goodsList');
      goodsIdTextBoxObj.focus();
    }
    else
    {
      goodsListObj.find('.goodsList').removeClass('selected-goodsList');
      nowSelectGoodsListItemObj.addClass('selected-goodsList');
      nowSubType="SELECTGOODS";
    }
  });
}

function bindGoodsListItemActionInit()
{
  goodsListItemObj=goodsListObj.find('.goodsList');
  goodsListItemObj.mouseover(function(){
    $(this).addClass('mouseover-goodsList');
  });

  goodsListItemObj.mouseout(function(){
    $(this).removeClass('mouseover-goodsList');
  });

  goodsListItemObj.click(function(){
    tempGoodsNumArray=new Array();
    if ($(this).hasClass('selected-goodsList')=="true") {
      nowSelectGoodsListItemObj.removeClass('selected-goodsList');
      goodsIdTextBoxObj.focus();
    }
    else
    {
      goodsListItemObj.removeClass('selected-goodsList');
      nowSelectGoodsListItemObj.addClass('selected-goodsList');
      nowSubType="SELECTGOODS";
    }
  });
}

var tempGoodsNumStr='';
function subChange(nowKeyCode)
{
  if (nowSubType=="INPUTGOODSID") {
    if (nowKeyCode==13) {
      if (goodsIdTextBoxObj.val()!="") {
        publicAjaxSub("<?php echo U('Cashier/getGoodsPrice');?>",{type:"ADDGOODS",oid:orderIdVar,pid:goodsIdTextBoxObj.val(),memberid:$('#memberId').val()});
      }
      else
      {
        tips('请输入产品编号');
      }
    }
    else if (nowKeyCode==45) 
    {
      if (allGoodsIdArray.length==0) {
        tips('无消费信息');
      }
      else
      {
        if (MemberInfo && _OrderType!='discount') {
          nowSubType='CHOSECOUPON';
          var _tempMoney=parseFloat($('.allMoney').html());
          var _tempHtml="<option value='nouser'>不使用优惠券</option>";
          for (var i = MemberCoupons.length - 1; i >= 0; i--) {
            var _tempPrice=0;
            if (MemberCoupons[i].Type=='0') {
              if (MemberCoupons[i].Rules<=_tempMoney) {
                _tempPrice=_tempMoney-parseFloat(MemberCoupons[i].Rules);
                _tempHtml+="<option value='"+MemberCoupons[i].CouponId+"' data-rule='"+MemberCoupons[i].Rules+"' id='"+MemberCoupons[i].CouponId+"' data-type='"+MemberCoupons[i].Type+"' data-money='"+_tempPrice+"'>"+MemberCoupons[i].CouponName+MemberCoupons[i].Rules+" 元</option>";
              };
            }else if (MemberCoupons[i].Type=='1') {
              _tempPrice=_tempMoney*parseFloat(MemberCoupons[i].Rules);
              _tempHtml+="<option value='"+MemberCoupons[i].CouponId+"' data-rule='"+MemberCoupons[i].Rules+"' id='"+MemberCoupons[i].CouponId+"'  data-type='"+MemberCoupons[i].Type+"'  data-money='"+_tempPrice+"'>"+MemberCoupons[i].CouponName+parseFloat(MemberCoupons[i].Rules)*10+" 折</option>"; 
            }else{
              var _tempA=MemberCoupons[i].Rules.split('/');
              console.log(_tempA[0]);
              if (_tempA[0]<=_tempMoney) {
                _tempPrice=parseFloat(_tempMoney)-parseFloat(_tempA[1]);
                _tempHtml+="<option value='"+MemberCoupons[i].CouponId+"' data-rule='"+MemberCoupons[i].Rules+"' id='"+MemberCoupons[i].CouponId+"'  data-type='"+MemberCoupons[i].Type+"'  data-money='"+_tempPrice+"'>满"+_tempA[0]+"减"+_tempA[1]+"</option>"
              };
            }
          };
          $('#CouponId').html(_tempHtml);
          $('.choseCoupon').show();
          $('#CouponId').focus();
          $('#pmBackMoneys').val(_tempMoney);
          // console.log(_tempHtml)
        }else{
          pay();
        }
      }
    }
    else if (nowKeyCode==27)  //alt+c 选择付款方式
    {
     $('#PayType').focus();
     // if ($('#PayType').attr('size')==1) {
     //  $('#PayType').attr('size',$('#PayType option').length);
     // }else{
     //  $('#PayType').attr('size','1')
     // }
    }
    else if (nowKeyCode==18) 
    {
      $('#memberId').focus();
    }
  }
  else if (nowSubType=="SELECTGOODS") 
  {
    if (nowKeyCode==46)
    {
      publicAjaxSub("<?php echo U('Cashier/delGoods');?>",{type:"DELGOODS",oid:orderIdVar,gid:nowSelectGoodsListItemObj.attr('id').substr(1)});
    }
    else if ((nowKeyCode>=48&&nowKeyCode<=57)||(nowKeyCode>=96&&nowKeyCode<=105))
    {
      tempGoodsNumArray.push(keyCodeToNum(nowKeyCode));
    }
    else if (nowKeyCode==45) 
    {
      tempGoodsNumStr='';
      tempGoodsNumArray.forEach(function(e){
        tempGoodsNumStr+=e.toString();
      });
      if (tempGoodsNumStr=='') {
        goodsIdTextBoxObj.focus();
      }
      else
      {
        publicAjaxSub("<?php echo U('Cashier/changeNum');?>",{type:"CHANGENUM",oid:orderIdVar,gid:nowSelectGoodsListItemObj.attr('id').substr(1),num:tempGoodsNumStr});
      }
    }
  }
  else if (nowSubType=="GETMEMBER") 
  {
    if (nowKeyCode==13) {
       publicAjaxSub("<?php echo U('Cashier/getMemberInfo');?>",{type:"GETMEMBER",uid:publicObjArray.memberId.val(),oid:orderIdVar});
    }
    
    if (nowKeyCode==17) {
      goodsIdTextBoxObj.focus();
    }
  }
  else if (nowSubType=='GETBACKMONEY') 
  {
    if (nowKeyCode==13) 
    {
      if (parseFloat(cashPublicObjArray.pmBackMoney.val())>=0) 
      {
        ticketModel();
        window.location.reload();
        tips('结算完成');        
      }
      else
      {
        tips('付款金额必须大于实付金额');
      }
    }
  }else if (nowSubType=='CHOSECOUPON') {
    if (nowKeyCode==45) {
      $('.choseCoupon').hide();
      pay();
    };
  }else if (nowSubType=='WECODE') {
    if (nowKeyCode==13) {
      $(".paymoneyDivs").hide();
      waiting('正在处理...')
      var code=$('#wecode').val();
      if (code) {
        $.ajax({
          type:"post",
          url:"<?php echo U('Cashier/wecode');?>",
          data:"auth_code="+code+"&OrderId="+tempPaymentData.oid,
          dataType:"json",
          success:function(msg){
              closeWaiting();
              $('#wecode').val('');
              if (msg.status=='true') {
                  tips('结算完成');
                  ticketModel();
                  nowSubType='INPUTGOODSID';
                  window.location.reload();
              }else{
                tips('结算失败:'+msg.info);
              }
          }
        })
      };
    };
  };
}
//

var tempPublicGoodsInfoObj=null;
 function addgoods(data)
 {
    goodsListParam.id=data.pid;
    goodsListParam.no="";
    goodsListParam.goodsId=data.pid;
    goodsListParam.goodsName=data.pname;
    // console.log(data);
    if (data.score>0) {
      goodsListParam.price=data.score+'积分';
      goodsListParam.money=data.allscore+'积分';
    }else{
      goodsListParam.price=data.price;
      goodsListParam.money=data.money;
    }
    goodsListParam.discount=data.sprice;
    goodsListParam.count=data.num;

    if (allGoodsIdArray.indexOf(data.pid)<0) 
    {

      allGoodsIdArray.push(data.pid);
      goodsListObj.append(getGoodsListHTML());
      bindGoodsListItemAction(goodsListObj.find('.goodsList:last'));
    }
    else
    {

      tempPublicGoodsInfoObj=$('#g'+data.pid);

      tempPublicGoodsInfoObj.find('.goods-count').html(data.num);
      if (data.score>0) {
        tempPublicGoodsInfoObj.find('.goods-money').html(data.allscore+'积分');
      }else{
        tempPublicGoodsInfoObj.find('.goods-money').html(data.money);
      }

      tempPublicGoodsInfoObj=null;
    }

    goodsIdTextBoxObj.val("");
 }

 function pay()
 {
    var PayType=$('#PayType').val();
    // console.log(PayType);
    var totalMoney=parseFloat($('.allMoney').html());
    if (PayType=='XJ') {
      publicAjaxSub("<?php echo U('Cashier/storeCashier');?>",{type:"PAYMENT",oid:orderIdVar,memberId:publicObjArray.memberId.val(),PayType:PayType,OrderCouponId:_OrderCouponId});
    }else if(PayType=='T'){
      publicAjaxSub("<?php echo U('Cashier/storeCashier');?>",{type:"PAYMENT",oid:orderIdVar,memberId:publicObjArray.memberId.val(),PayType:PayType,OrderCouponId:_OrderCouponId});
    }else{
      if (MemberInfo) {
        if (PayType=='YE') {
          if (parseFloat(MemberInfo.Money)<totalMoney) {
            tips('账户余额不足，请选择其他支付方式');
            $('#PayType').focus();
          }else{
            publicAjaxSub("<?php echo U('Cashier/storeCashier');?>",{type:"PAYMENT",oid:orderIdVar,memberId:publicObjArray.memberId.val(),PayType:PayType,OrderCouponId:_OrderCouponId});
          }
        };
        if (PayType=='JL') {
          if (parseFloat(MemberInfo.VirtualMoney)<totalMoney) {
            tips('账户奖励余额不足，请选择其他支付方式');
            $('PayType').focus();
          }else{
            publicAjaxSub("<?php echo U('Cashier/storeCashier');?>",{type:"PAYMENT",oid:orderIdVar,memberId:publicObjArray.memberId.val(),PayType:PayType,OrderCouponId:_OrderCouponId});
          }
        };
      }else{
        tips('请输入会员账号后使用余额/奖励余额付款');
        $('#memberId').focus();
      }
    }
 }

 function getGoodsListHTML()
 {
  goodsListTempHTML='<div id="g'+goodsListParam.id+'" class="goodsList goodsList-goods"><div>'+goodsListParam.no+'</div><div>'+goodsListParam.goodsId+'</div><div>'+goodsListParam.goodsName+'</div><div class="goods-price">'+goodsListParam.price+'</div><div class="goods-discount">'+goodsListParam.discount+'</div><div class="goods-count">'+goodsListParam.count+'</div><div class="goods-money">'+goodsListParam.money+'</div></div>';
  return goodsListTempHTML;
 }
//8989898989
  var tempPromotion='';
  function setOrderInfo(ac,am,om,promotion,score,type)
  {
    var type=type||'';
    tempPromotion='';
    publicObjArray.allCount.html(ac);
    if (type=='discount') {
      publicObjArray.allMoney.html(am);
      publicObjArray.offMoney.html(om);
    }else{
      publicObjArray.allMoney.html(am-promotion.p1m);
      publicObjArray.offMoney.html(om+promotion.p1m);
    }
    $('.allscore').html(score).attr('data-s',score);

    if (promotion.p1m>0) {
      tempPromotion='消费满'+promotion.p1.Consume+'元减'+promotion.p1.Discount+'元。';
    }

    if (promotion.p2m>0) {
      tempPromotion+='消费满'+promotion.p2.Consume+'送'+promotion.p2.Discount+'元消费券。';
    }
    publicObjArray.otherInfo.html(tempPromotion);
  }

  function setDiscount(data){
    // console.log(data.length); 
    // console.log(data);
    var tempa=Object.keys(tempvar);
    // console.log(tempa);
    // console.log(tempa.length);
    // console.log(data.pid);
    if (tempa.length==1) {
      var ky=allGoodsIdArray[0];
      if (data.sprice && data.sprice>0) {
        $('#g'+ky+" .goods-price").html(data.sprice);
      }else{
        $('#g'+ky+" .goods-price").html(data.price);
      }
      $('#g'+ky+" .goods-discount").html(parseFloat(data.price)-parseFloat(data.sprice));
      $('#g'+ky+" .goods-money").html(parseFloat(data.sprice)*data.num);
    }else{
      $.each(data,function(index,item){
        $('#g'+item.pid+" .goods-price").html(item.sprice);
        $('#g'+item.pid+" .goods-discount").html(parseFloat(item.price)-parseFloat(item.sprice));
        $('#g'+item.pid+" .goods-money").html(parseFloat(item.sprice)*item.num);
      })
    }
  }


var subLock=false;
 function publicAjaxSub(url,datap)
 {

          if (subLock) {return false;}

          subLock=true;

          waiting('正在加载数据...');
          $.ajax({
          //提交数据的类型 POST GET
          type: "POST",
          //提交的网址
          url: url,
          //提交的数据
          data: datap,
          //返回数据的格式
          datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
          //在请求之前调用的函数
          beforeSend: function () {

          },
          //成功返回之后调用的函数
          success: function (data) 
          {
             if (datap.type=="ADDGOODS") 
             {
                if (data.status=="true") 
                {
                  if (data.data.canusescore=='no') {
                    tips('请输入会员账号后添加积分商品');
                    $('#goodsId').val('');
                    $('#memberId').focus();
                  }else if (data.data.canusescore=='noscore') {
                    tips('会员积分不足！');
                    $('#goodsId').val('');
                  }else if (data.data.ordertype=='userscore') {
                    tips('普通商品不可与积分商品混合下单');
                    $('#goodsId').val('');
                  }else if(data.data.ordertype=='cantscore'){
                    tips('积分商品不可与普通商品混合下单');
                    $('#goodsId').val('');
                  }else{
                    if (data.ordertype=='discount') {
                      _OrderType='discount';
                      addgoods(data.data);
                      setOrderInfo(data.data.oInfo.count,data.data.oInfo.money,data.data.oInfo.discount,data.promotion,data.data.oInfo.score,'discount');
                      setDiscount(data.orderdata.goods);
                    }else{
                      addgoods(data.data);
                      setOrderInfo(data.data.oInfo.count,data.data.oInfo.money,data.data.oInfo.discount,data.promotion,data.data.oInfo.score);
                    }
                    // console.log(data.data);
                  }
                }
                else
                {
                  if (data.info=="CantFindGoods") {
                    tips('没有此商品');
                  }
                  else
                  {
                    tips('获取数据出错');
                  }
                  goodsIdTextBoxObj.val('');
                }
              }
              else if (datap.type=="PAYMENT") 
              {
                if (data.status=="true") {
                  //tips('结算完成');
                  tempPaymentData=data.ticket;
                  // console.log(data.ticket);
                  if (datap.PayType=='XJ') {
                    cashPublicObjArray.pmMoney.val(tempPaymentData.money);
                    $('#msg').html(tempPaymentData.msg);
                    cashPublicObjArray.pmDiv.show();
                    cashPublicObjArray.pmPayMoney.focus();
                    // console.log(nowSubType);
                    // tips('结算完成');
                  };
                  if (datap.PayType=='YE') {
                    $('#MemberMoney').html('');
                    $('#VirtualMoney').html('');
                    $('#userscore').html('');
                    $('.discount').hide();
                    MemberInfo="";
                    tips('结算完成');
                    ticketModel();
                    window.location.reload();
                  };
                  if (datap.PayType=='JL') {
                    $('#MemberMoney').html('');
                    $('#VirtualMoney').html('');
                    $('#userscore').html('');
                    $('.discount').hide();
                    MemberInfo="";
                    tips('结算完成');
                    ticketModel();
                    window.location.reload();
                  };
                  if (datap.PayType=='T') {
                    $('#MemberMoney').html('');
                    $('#VirtualMoney').html('');
                    $('#userscore').html('');
                    $('.discount').hide();
                    MemberInfo="";
                    $('#pmMoneys').val(tempPaymentData.money);
                    $('.paymoneyDivs').show();
                    $('#wecode').focus();
                    nowSubType='WECODE';
                  };
                  orderIdVar=data.data.nextOid;

                }
                else
                {
                  console.log(data);
                  tips('获取数据出错');
                }
              }
              else if (datap.type=="GETMEMBER") 
              {
                if (data.status=="true") {
                  // orderIdVar=data.data.orderid;
                  MemberInfo=data.data;//保存用户信息
                  $('#MemberMoney').html(parseFloat(data.data.Money));
                  $('#VirtualMoney').html(parseFloat(data.data.VirtualMoney));
                  if (data.data.Integral) {
                    $('#userscore').html(parseFloat(data.data.Integral));
                  }else{
                    $('#userscore').html('0');
                  }
                  $('.discount').show();
                  // allParamInit();
                  goodsIdTextBoxObj.focus();
                  if (data.membercoupons) {
                    MemberCoupons=data.membercoupons;
                    // var _temp="<option value='nouse'>不使用优惠券</option>"
                    // $.each(data.membercoupons,function(index,item){
                    //   _temp+="<option value='"+item.CouponId+"'>"+item.CouponName+"</option>";
                    // })
                    // $('#CouponId').html(_temp);
                  };
                }
                else
                {
                  if (data.info=="noUser") {
                    tips('未找到用户');
                  }
                  else
                  {
                    tips('获取数据出错');
                  }
                }
              }
              else if (datap.type=="DELGOODS") {
                if (data.status=="true") {
                  tips('删除成功');
                  setOrderInfo(data.data.nums,data.data.money,data.data.discount,data.promotion,data.data.allscore);
                  nowSelectGoodsListItemObj.remove();
                  allGoodsIdArray.splice(allGoodsIdArray.indexOf(datap.gid),1);
                  goodsIdTextBoxObj.focus();
                  tempvar=data.data.goods;
                  if (data.dtype=='change') {
                      var tempa=Object.keys(tempvar);
                      if (tempa.length>1) {
                        setDiscount(data.data.goods);
                      }else{
                        setDiscount(data.orderdata);
                      }
                  };
                }
                else
                {
                  
                }
              }
              else if (datap.type=="CHANGENUM") {
                if (data.status=="true") {
                  tips('修改成功');
                  setOrderInfo(data.data.nums,data.data.money,data.data.discount,data.promotion);
                  nowSelectGoodsListItemObj.find('.goods-count').html(data.goodsInfo.num);
                  nowSelectGoodsListItemObj.find('.goods-money').html(data.goodsInfo.money);
                  goodsIdTextBoxObj.focus();
                }
                else
                {
                  
                }
              }
          },
          //调用出错执行的函数
          error: function () {
              //请求出错处理
              subLock=false;
          },
          //调用执行后调用的函数
          complete: function (XMLHttpRequest, textStatus) {
            closeWaiting();
            subLock=false;
          }
      });

 }

var ticketVal=$('#ddd').html();
var tempTicketStr='';


 function ticketModel(data)
 {
    //tempPaymentData

    tempTicketStr='';
    for(var key in tempPaymentData.goods){
      tempTicketStr+='<tr><td>'+tempPaymentData.goods[key].name+'</td><td style="text-align:center;">'+tempPaymentData.goods[key].price+'</td><td style="text-align:center;">'+tempPaymentData.goods[key].count+'</td><td style="text-align:center;">'+tempPaymentData.goods[key].money+'</td></tr>';
    }

    tempTicketStr=ticketVal.replace('<tr><td colspan="4">[GOODSINFO]</td></tr>',tempTicketStr);
    tempTicketStr=tempTicketStr.replace('[SHOPNAME]',tempPaymentData.shop);
    tempTicketStr=tempTicketStr.replace('[COMPANYNAME]',tempPaymentData.company);
    tempTicketStr=tempTicketStr.replace('[ORDERID]',tempPaymentData.orderid);

    if (tempPaymentData.memberid=='') {
      tempTicketStr=tempTicketStr.replace('[USERID]','散客');
      tempTicketStr=tempTicketStr.replace('[USERMONEY]','');
      tempTicketStr=tempTicketStr.replace('[USERVIRTUALMONEY]','');
    }
    else
    {
      tempTicketStr=tempTicketStr.replace('[USERID]',tempPaymentData.memberid);
      tempTicketStr=tempTicketStr.replace('[USERMONEY]',tempPaymentData.userMoney);
      tempTicketStr=tempTicketStr.replace('[USERVIRTUALMONEY]',tempPaymentData.userVirtualMoney);
      tempTicketStr=tempTicketStr.replace('[USERSCORE]',tempPaymentData.score);
    }

    
    tempTicketStr=tempTicketStr.replace('[ALLCOUT]',tempPaymentData.count);
    tempTicketStr=tempTicketStr.replace('[ALLSCORE]',tempPaymentData.allscore);
    tempTicketStr=tempTicketStr.replace('[ALLMONEY]',tempPaymentData.money);
    tempTicketStr=tempTicketStr.replace('[OLDMONEY]',tempPaymentData.oldmoney);
    tempTicketStr=tempTicketStr.replace('[OFFMONEY]',tempPaymentData.offmoney);
    tempTicketStr=tempTicketStr.replace('[USERPAYMONEY]',cashPublicObjArray.pmPayMoney.val());
    tempTicketStr=tempTicketStr.replace('[BACKMONEY]',cashPublicObjArray.pmBackMoney.val());
    tempTicketStr=tempTicketStr.replace('[CASHER]',tempPaymentData.casher);
    tempTicketStr=tempTicketStr.replace('[SELLTIME]',tempPaymentData.date);
    tempTicketStr=tempTicketStr.replace('[SHOPPHONE]',tempPaymentData.phone);

    tempPaymentData=null;

    printTicket(tempTicketStr);
    tempTicketStr='';
 }
// 


function allParamInit()
{
  //goodsIdTextBoxObj.focus();
  allGoodsIdArray=new Array();
  nowSelectGoodsListItemObj=null;
  goodsListObj.html('');
  goodsListItemObj=null;
  publicObjArray.orderId.html(orderIdVar);
  publicObjArray.allCount.html('0');
  publicObjArray.allMoney.html('0');
  $('.allscore').html('');
  MemberInfo="";
  $('.discount').hide();
  $('#userscore').html('');
  $('#MemberMoney').html('');
  $('#VirtualMoney').html('');
}


var tempKeyCode=null;

function keyDownEvent()
{
  $(document).keydown(function(e){
    tempKeyCode=e.keyCode;
    altKey=e.altKey;
    // console.log(altKey);
    subChange(e.keyCode);
  });
}

function keyCodeToNum(keyCode)
{
    switch(keyCode){
      case 48: return 0;
      break;
      case 96: return 0;
      break;
      case 49: return 1;
      break;
      case 97: return 1;
      break;
      case 50: return 2;
      break;
      case 98: return 2;
      break;
      case 51: return 3;
      break;
      case 99: return 3;
      break;
      case 52: return 4;
      break;
      case 100: return 4;
      break;
      case 53: return 5;
      break;
      case 101: return 5;
      break;
      case 54: return 6;
      break;
      case 102: return 6;
      break;
      case 55: return 7;
      break;
      case 103: return 7;
      break;
      case 56: return 8;
      break;
      case 104: return 8;
      break;
      case 57: return 9;
      break;
      case 105: return 9;
      break;
      default: return 0;
      break;
    }
}

//提示框
function tips(content)
{
  $(".tips").show();
  $(".tips").html(content);
  setTimeout(function(){$(".tips").hide();},3000);
}

//等待框
function waiting(content)
{
  $(".waiting").show();
  $(".waiting").html(content);
}
//关闭等待框
function closeWaiting()
{
  $(".waiting").hide();
  $(".waiting").html('');
}

var LODOP;
function printTicket(data)
{


  cashPublicObjArray.pmDiv.hide();
  cashPublicObjArray.pmMoney.val('0');
  cashPublicObjArray.pmPayMoney.val('');
  cashPublicObjArray.pmBackMoney.val('0');
  publicObjArray.memberId.val('');
  allParamInit();
  goodsIdTextBoxObj.focus();

  LODOP = getLodop();    // 初始化
  LODOP.PRINT_INIT("打印");    
  var objprinthtml = document.getElementById("ddd");    // 获取打印内容
  //objprinthtml.innerHTML=data;
  //var strFormHtml = objprinthtml.innerHTML;
  var height = objprinthtml.offsetHeight + 20;   // 设置打印高度
  LODOP.SET_PRINT_PAGESIZE(3, "58mm", 0, "");    // 设置纸张大小（固定宽度，随高度自适应）
  LODOP.SET_PRINT_MODE("TEXT_ONLY_MODE", false); // 设置打印类型
  LODOP.ADD_PRINT_HTM(0, 0, "58mm", height, data);    // 打印内容信息
  //LODOP.ADD_PRINT_IMAGE(height,0,"100%","100%",base64codeQRcode);
  LODOP.SET_PREVIEW_WINDOW(0, 1, 0, 800, 600, ""); //打印前弹出选择打印机的对话框
  LODOP.SET_PRINT_MODE("AUTO_CLOSE_PREWINDOW", 1); //打印后自动关闭预览窗口 
  //LODOP.PRINT();
  LODOP.PREVIEW(); 

  //publicObjArray.memberId.focus();
}



$(function(){

  goodsIdTextBoxObj.focus(function(){
    nowSelectGoodsListItemObj=null;
    goodsListItemObj=goodsListObj.find('.goodsList');
    goodsListItemObj.removeClass('selected-goodsList');
    nowSubType="INPUTGOODSID";
  });

  publicObjArray.memberId.focus(function(){
    nowSubType="GETMEMBER";
  });

  cashPublicObjArray.pmPayMoney.focus(function(e){
    nowSubType="GETBACKMONEY";
  });

  cashPublicObjArray.pmPayMoney.keyup(function(e){
    //alert(parseFloat(cashPublicObjArray.pmPayMoney.val()));
    //alert(parseFloat(cashPublicObjArray.pmMoney.val()));
    var tempBack=parseFloat(cashPublicObjArray.pmPayMoney.val())-parseFloat(cashPublicObjArray.pmMoney.val());
    cashPublicObjArray.pmBackMoney.val(tempBack.toFixed(2));
  });

  keyDownEvent();

  nowSubType="INPUTGOODSID";






  goodsIdTextBoxObj.focus();
  //publicObjArray.memberId.focus();


  // var lodopObj=getLodop();
  // alert(lodopObj);
  // if (!lodopObj.VERSION) {
     $('#downloadlodop').show();


  // }

    var _url = "<?php echo ($qrcodeUrl); ?>";
    var tuiqrcode_div = $("#shopQRcode");
    tuiqrcode_div.empty();
    tuiqrcode_div.qrcode({
      render: "canvas",
      width: 150,
      height: 150,
      text: _url
    });

    base64codeQRcode=$('#shopQRcode canvas')[0].toDataURL();
    //$('#shopImg').attr('src',base64codeQRcode);


    $('#CouponId').change(function(){
      var _tempMoney=parseFloat($('.allMoney').html());
      var CouponId=$(this).val();
      if (CouponId!='nouser') {
        $('#pmBackMoneys').val(parseFloat($('#'+CouponId).attr('data-money')));
        _OrderCouponId=CouponId;
      }else{
        $('#pmBackMoneys').val(_tempMoney);
        _OrderCouponId='no';
      }
    })
});

// console.log(tempKeyCode,altKey);


</script>
</html>