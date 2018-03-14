<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
  <script src="/Public/newhome/js/jquery.min.js"  type="text/javascript" charset="utf-8"></script>
  <script src="/Public/newhome/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/mui.min.css" />
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/font_gpke.css?v=1.4" />
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/mynew.css?v=1.7" />
  <script type="text/javascript">
  var up_url = '<?php echo ($up_url); ?>';
  var nowmca_url = '<?php echo ($nowmca_url); ?>';
  </script>
  <script type="text/javascript" src="/Public/newhome/js/Base.js?v=2.1"></script>
</head>
<body>
  <div class="container">
<link rel="stylesheet" type="text/css" href="/Public/newhome/css/userorders.css?v=1.2" />
<div class="orderparts">
  <div class="seltoptab">
    <span class="otype getseltypeactive" data-type='0'>
      <span class="ggg icon_allorder"></span>
      <span class="typetext">全部</span>
    </span>
    <span class="otype" data-type='1'>
      <span class="ggg icon_dfukuan"></span>
      <span class="typetext">待付款</span>
    </span>
    <span class="otype" data-type='2'>
      <span class="ggg icon_dshiyong"></span>
      <span class="typetext">待使用</span>
    </span>
    <span class="otype" data-type='3'>
      <span class="ggg icon_dpingjia"></span>
      <span class="typetext">待评价</span>
    </span>
    <span class="otype" data-type='4'>
      <span class="ggg icon_shouhou"></span>
      <span class="typetext">退款/售后</span>
    </span>
  </div>
  <div class="orderlist">
    <?php if($orderinfo != null ): if(is_array($orderinfo)): foreach($orderinfo as $key=>$oinfo): ?><div class="orderinfo" data-oid="<?php echo ($oinfo['oid']); ?>" data-status ="<?php echo ($oinfo['status']); ?>" data-isevaluation ="<?php echo ($oinfo['isevaluation']); ?>" data-stoken ="<?php echo ($oinfo['stoken']); ?>">
          <div class="shopinfo">
            <img src="<?php echo ($oinfo['slogo']); ?>" alt="">
            <span>
              <span class="sname mui-ellipsis"><?php echo ($oinfo['sname']); ?></span>
              <span class="mui-icon mui-icon-arrowright righticon"></span>
              <span class="orderstatus">
                <?php switch($oinfo["status"]): case "1": ?>待付款<?php break;?>
                  <?php case "2": ?>待使用<?php break;?>
                  <?php case "3": ?>待使用<?php break;?>
                  <?php case "4": if($oinfo["isevaluation"] == 0): ?>待评价
                      <?php else: ?>
                      已完成<?php endif; break;?>
                  <?php case "5": ?>退款中<?php break;?>
                  <?php case "8": ?>已退款<?php break;?>
                  <?php default: ?>待付款<?php endswitch;?>
              </span>
              <span class="orderinfoid">订单号:<?php echo ($oinfo['oid']); ?><br>
                <?php if(!empty($oinfo["soid"])): ?><span>就餐码:<?php echo ($oinfo["soid"]); ?>;</span><?php endif; ?>
                <?php if(!empty($oinfo["tableid"])): ?><span>桌号:<?php echo ($oinfo["tableid"]); ?>;</span><?php endif; ?>
              </span>
              <!-- <?php if($oinfo['soid'] != null ): else: ?>
                <span class="orderinfoid">订单号:<?php echo ($oinfo['oid']); ?></span><?php endif; ?> -->
            </span>
          </div>
          <div class="prosinfo" RPath="<?php echo U('Orders/orderinfos',array('oid'=>$oinfo['oid']));?>">
            <?php $__FOR_START_27076__=1;$__FOR_END_27076__=4;for($i=$__FOR_START_27076__;$i < $__FOR_END_27076__;$i+=1){ if($oinfo['plist'][$i-1] != null ): ?><span><span><?php echo ($oinfo['plist'][$i-1]['pname']); ?></span><span>×<?php echo ($oinfo['plist'][$i-1]['pnums']); ?></span></span><?php endif; } ?>
            <div class="totalprice">
              <span class="mui-icon mui-icon-more"></span>
              <span class="total_price">共计<?php echo ($oinfo['count']); ?>件商品,实付<span><?php echo number_format(($oinfo['price']),2); ?></span></span>
            </div>
          </div>
          <div class="btngroup">
            <?php switch($oinfo["status"]): case "1": ?><span class="qxorder">取消订单</span><span class="payorder">立即付款</span><?php break;?>
              <?php case "2": ?><!-- <span class="tkorder">退款</span> -->
                <!-- <?php if($oinfo["tableid"] == ''): ?><span class="eatorder">就餐码</span>
                <?php else: ?>
                <span class="sureorder">确认完成</span><?php endif; ?> -->
              <span class="shareorder">立即分享</span><?php break;?>
            <?php case "3": ?><!-- <span class="tkorder">退款</span> -->
              <!-- <?php if($oinfo["tableid"] == ''): ?><span class="eatorder">就餐码</span>
              <?php else: ?>
              <span class="sureorder">确认完成</span><?php endif; ?> -->
            <span class="shareorder">立即分享</span><?php break;?>
          <?php case "4": if($oinfo["isevaluation"] == 0): ?><span class="evalorder">立即评价</span>
              <?php else: ?>
              <span class="delorder">删除订单</span><?php endif; ?>
            <span class="shareorder">立即分享</span><?php break;?>
          <?php case "8": ?><span class="qxorder">取消订单</span><span class="shareorder">立即分享</span><?php break;?>
          <?php default: endswitch;?>
      </div>
    </div><?php endforeach; endif; endif; ?>
</div>
</div>
<!-- 就餐码 -->
<div class="eatqrcodemark">
  <div class="showeatcode">
    <img src="" alt="">
    <span class="closeatcode">关闭</span>
  </div>
</div>
<!-- 就餐码end -->
<script type="text/javascript">
var eatordercode_url ="<?php echo U('Public/geteatcode',array('oid'=>'ORDERIDTEMP','type'=>'cancelorder','stoken'=>'STOKENTEMP'));?>";//获取就餐码
var getorderstatus_url="<?php echo U('User/getorderstatus');?>"; // 获得订单状态
var payorder_url="<?php echo U('Payment/Index');?>?oid=ORDERIDTEMPS";//立即支付
var setorderstatus_url="<?php echo U('User/setorderstatus');?>";//修改订单转态
var setorderend_url="<?php echo U('User/setorderend');?>";//确认完成
var gotoevaluation_url="<?php echo U('User/userevaluation',array('oid'=>'ORDERIDTEMPS','stoken'=>'STOKENTEMPS'));?>";//去评价订单信息
var paysuccess_url = "<?php echo U('Home/Orders/shareorderpages',array('oid'=>'ORDERIDTEMPS'));?>"; // 支付成功跳转页面
</script>
<script src="/Public/newhome/js/userorders.js?v=1.2" type="text/javascript" charset="utf-8"></script>
</div>
  <!-- footer-menus -->
  <?php if($footerSign == 1): ?><div style="height:51px; display:block"></div>
    <div class="footer">
      <a class="<?php echo ($nowBootBarStatus[0]); ?>" href="<?php echo U('Index/Index');?>">
        <span class="mui-icon ggg <?php echo ($nowBootBarIcon[0]); ?>"></span>
        <span class="mui-tab-label">首页</span>
      </a>
      <a class="<?php echo ($nowBootBarStatus[1]); ?>" href="<?php echo U('Index/activity');?>">
        <span class="mui-icon ggg <?php echo ($nowBootBarIcon[1]); ?>"></span>
        <span class="mui-tab-label">活动</span>
      </a>
      <a class="<?php echo ($nowBootBarStatus[2]); ?>" href="<?php echo U('User/Userorders');?>">
        <span class="mui-icon ggg <?php echo ($nowBootBarIcon[2]); ?>"></span>
        <span class="mui-tab-label">订单</span>
      </a>
      <a class="<?php echo ($nowBootBarStatus[3]); ?>" href="<?php echo U('User/Index');?>">
        <span class="mui-icon ggg <?php echo ($nowBootBarIcon[3]); ?>"></span>
        <span class="mui-tab-label">我的</span>
      </a>
    </div><?php endif; ?>
  <!-- footer-menus -->
  <!-- 等待框 -->
  <div id="showwaiting">
    <div class="showwaitinfo">
      <span class="mui-icon mui-icon-spinner-cycle mui-spin waitingicon"></span>
      <span class="waitingtext mui-ellipsis"></span>
    </div>
  </div>
  <!-- 等待框end -->
  <!-- 红包领取 -->
  <div class="maskpacket">
    <img src="/Public/newhome/img/redpacketsimg/bgimg.png" alt="">
    <div class="showpacketpart">
      <img src="/Public/newhome/img/redpacketsimg/showimg.png" alt="">
      <div class="showpacketinfo">
        <span>下单可抵现金使用</span>
        <span>无门槛红包</span>
      </div>
      <div class="showpacketbtn">
        <span class="showgetpacket">0.0</span>
      </div>
      <div class="showpackethint">
        <span>仅限本次下单使用!</span>
      </div>
    </div>
  </div>
  <div class="maskpacketnew">

  </div>
  <div class="showredinfo">
    <img src="/Public/newhome/img/redpacketsimg/redpackimg.png" class="redbgimg" alt="">
    <div class="showgetredprice">
      <span>0.00</span>
    </div>
    <div class="showredtextinfo">
      <span></span>
    </div>

    <div class="showredbtninfo">
      <div class="redbtn_1">
        <img src="/Public/newhome/img/redpacketsimg/redbtnimg.png" class="redbgimg" alt="">
        <span>查看红包</span>
      </div>
      <div class="redbtn_2">
        <img src="/Public/newhome/img/redpacketsimg/redbtnimg.png" class="redbgimg" alt="">
        <span>我的订单</span>
      </div>
    </div>
  </div>
</div>
<!-- 红包领取end -->
<!-- 是否关注公众号 -->
<div class="subscribemark">
</div>
<div class="showsubscribecode">
  <img src="/Public/newhome/img/subscribecode.jpg" alt="">
  <span>长按关注公众号</span>
</div>
<!-- 是否关注公众号end -->
<!-- 快捷导航 -->
<!-- 按钮部分 -->
<div class="shownavbtngroup">
  <span class="mui-icon mui-icon-bars shownavicon"></span>
  <span class="shownavtext">导航</span>
</div>
<!-- 展开部分 -->
<div class="navmarkinfo">
  <div class="closeinfo">
    <span class="mui-icon mui-icon-closeempty closebtn"></span>
    <span class="showclosetext">关闭</span>
  </div>
  <div class="showavlistinfo">
    <div class="navinfo" Rpath="<?php echo U('Index/Index');?>">
      <span class="mui-icon ggg home_icon"></span>
      <span class="showclosetext">首页</span>
    </div>
    <div class="navinfo" Rpath="<?php echo U('Index/activity');?>">
      <span class="mui-icon ggg huod_icon"></span>
      <span class="showclosetext">活动</span>
    </div>
    <div class="navinfo" Rpath="<?php echo U('User/Userorders');?>">
      <span class="mui-icon ggg order_icon"></span>
      <span class="showclosetext">订单</span>
    </div>
    <div class="navinfo" Rpath="<?php echo U('User/Index');?>">
      <span class="mui-icon ggg mine_icon"></span>
      <span class="showclosetext">我的</span>
    </div>
  </div>
</div>
<!-- 快捷导航end -->
<!-- 分享红包模块，首页，商品详情页，订单页面 -->
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
var PID="<?php echo ($pinfo['ProId']); ?>";
var SID;//分享预处理ID
var sharetitle;
var sharedesc;
var sharelink
var shareimgUrl;
var sharecouponinfo=<?php echo ($coninfo); ?>;
var shareorder="<?php echo ($shareorder); ?>";
var menulist = '<?php echo ($menulist); ?>';
var Subscribe = '<?php echo ($Subscribe); ?>';
var getonce = '<?php echo ($getonce); ?>';
var inredprice='<?php echo ($redprice); ?>';
$(document).ready(function() {

  // 是否显示关注二维码
  // if (getonce == '1' || getonce =='2') {
  //   if (Subscribe !='1') {
  //     $('.subscribemark').css('display','block');
  //     $('.showsubscribecode').css('display','block');
  //   }
  // }

  $('.subscribemark').on('tap',function(){
    $('.subscribemark').css('display','none');
    $('.showsubscribecode').css('display','none');
  })

  menulist=menulist.split(',');
  wx.config(<?php echo ($wxJSSDKConfigStr); ?>);
  wx.ready(function() {
    wx.hideAllNonBaseMenuItem();
  })
  showlinksshare();
  switch (nowmca_url) {
    case 'Home/Index/Index':
    getshareid();
    break;
    case 'Home/Goods/goods':
    getshareid();
    break;
    case 'Home/Orders/orderinfos':
    if ('<?php echo ($oinfo["Status"]); ?>' == '4') {
      // getshareid();
    }
    break;
    case 'Home/Orders/shareorderpages':
    getshareid();
    break;

  }
  // setTimeout(,2000);
  // 关闭红包
  $('.maskpacketnew').on('tap',function(){
    if (nowmca_url != 'Home/Payment/Index' && nowmca_url != 'Home/Orders/shareorderpages') {
      $('.maskpacketnew').css('display','none');
      $('.showredinfo').css('display','none');
    }
  })
  // 查看红包
  $('.redbtn_1').on('tap',function(){
    if (nowmca_url == 'Home/Payment/Index') {
      window.location.href = "<?php echo U('Home/Orders/shareorderpages',array('oid'=>$oid));?>";
    } else {
      window.location.href = "<?php echo U('User/myredpack');?>";
    }
  })
  // 我的订单
  $('.redbtn_2').on('tap',function(){
    window.location.href = "<?php echo U('User/Userorders');?>";
  })
  // 显示导航按钮
  if (nowmca_url!='Home/Index/Index' && nowmca_url!='Home/Index/activity' && nowmca_url!='Home/User/Userorders' && nowmca_url!='Home/User/Index' && nowmca_url!='Home/Table/Singlepoint') {
    $('.shownavbtngroup').css('display','block');
  }
  // 打开导航按钮
  $('.shownavbtngroup').on('tap',function(){
    $('.shownavbtngroup').css('display','none');
    $('.navmarkinfo').css('display','block');
  })
  // 关闭导航按钮
  $('.closeinfo').on('tap',function(){
    $('.shownavbtngroup').css('display','block');
    $('.navmarkinfo').css('display','none');
  });
  // 显示进店红包
  showinshopredinfo();
})
function getshareid(){
  $.ajax({
    url: "<?php echo U('Base/shareinfo');?>",
    type: "post",
    data: {
      Type: "<?php echo ($shareType); ?>",
      Sharer: '<?php echo ($shareMemberId); ?>',
      ShareId: '<?php echo ($ShareId); ?>',
      s: "preshare"
    },
    dataType: "json",
    success: function(msg) {
      if (msg.status == 'success') {
        SID = msg.ID;
        if(nowmca_url != 'Home/Orders/shareorderpages'){
          setshareinfo();
        }
      } else {

      }
    }
  })
}
function setshareinfo(){
  switch (nowmca_url) {
    case 'Home/Index/Index':
    sharetitle= '<?php echo ($sinfo["storename"]); ?>';
    sharedesc= '光盘客请您点击,点击有惊喜>>>';
    sharelink= window.location.protocol + '//' + window.location.host + '?SID=' + SID+'&stoken=<?php echo ($shopstoken); ?>&once=1';
    shareimgUrl= window.location.protocol + '//' + window.location.host + '<?php echo ($sinfo["Slogo"]); ?>';
    break;
    case 'Home/Goods/goods':
    sharetitle= '<?php echo ($pinfo["ProName"]); ?>';
    sharedesc= '光盘客请您点击,点击有惊喜>>>';
    sharelink= window.location.protocol + '//' + window.location.host+'/index.php/Home/Goods/goods/?pid='+PID+ '&SID=' + SID+'&stoken=<?php echo ($shopstoken); ?>&once=1';
    shareimgUrl= window.location.protocol + '//' + window.location.host + '<?php echo ($pinfo["ProLogoImg"]); ?>';
    break;
    case 'Home/Orders/orderinfos':
    sharetitle= '<?php echo ($sinfo["storename"]); ?>';
    sharedesc= '光盘客请您点击,点击有惊喜>>>';
    sharelink= window.location.protocol + '//' + window.location.host+'/index.php/Home/Orders/shareorderpage/'+ 'SID/' + SID+'/stoken/<?php echo ($shopstoken); ?>/once/1';
    shareimgUrl= window.location.protocol + '//' + window.location.host + '<?php echo ($sinfo["Slogo"]); ?>';
    break;
  }
  setwxshareinfo();
}
function setwxshareinfo(){
  wx.ready(function() {
    // alert(menulist);
    // console.log(menulist.length);
    wx.hideAllNonBaseMenuItem();
    wx.showMenuItems({
      menuList: menulist // 要显示的菜单项，所有menu项见附录3
      // menuList: [<?php echo ($menulist); ?>] // 要显示的菜单项，所有menu项见附录3
    });
    // 分享到朋友圈
    wx.onMenuShareTimeline({
      title: sharetitle,
      link: sharelink,
      imgUrl: shareimgUrl,
      success: function() {
        sharesuccess();
      },
    });
    // 分享到朋友
    wx.onMenuShareAppMessage({
      title: sharetitle,
      desc: sharedesc,
      link: sharelink,
      imgUrl: shareimgUrl,
      success: function() {
        sharesuccess();
      },
      cancel: function() {

      }
    });
  })
}
function sharesuccess(){
  $.ajax({
    url:"<?php echo U('Base/shareinfo');?>",
    type:"post",
    data:{
      s:"shareout",
      ID:SID
    },
    dataType:"json",
    success:function(msg){
      if (msg.status=='success') {
        if (msg.redpaper=='yes') {
          if (msg.red_type=='2') {
            var redinfo = msg.red_rule;
            redinfo = redinfo.split("/");
            var redprice = redinfo[1];
            var redtextinfo = '满'+redinfo[0]+'元可抵扣现金使用';
          } else {
            var redprice = msg.red_rule;
            var redtextinfo = '无门槛红包可抵扣现金使用';
          }
          $('.maskpacketnew').css('display','block');
          $('.showredinfo').css('display','block');
          $('.showgetredprice>span').text(redprice);
          $('.showredtextinfo>span').text(redtextinfo);
        }else{
          setTimeout(function(){window.location.href = "<?php echo U('User/Userorders');?>";},5000);
        }
      }else{
        setTimeout(function(){window.location.href = "<?php echo U('User/Userorders');?>";},5000);
      }

      if(nowmca_url == 'Home/Orders/shareorderpages'){
        $('.sharemark').css('display','none');
        $('.redbtn_2').css('display','block');
        // setTimeout(function(){window.location.href = "<?php echo U('User/Userorders');?>";},5000);
      }
    }
  })
}
function showlinksshare(){
  if (shareorder !='true') {
    if (sharecouponinfo != 1) {
      if (sharecouponinfo.Type=='2') {
        var redinfo = sharecouponinfo.Rules;
        redinfo = redinfo.split("/");
        var redprice = redinfo[1];
        var redtextinfo = '满'+redinfo[0]+'元可抵扣现金使用';
      } else {
        var redprice = sharecouponinfo.Rules;
        var redtextinfo = '无门槛红包可抵扣现金使用';
      }
      $('.maskpacketnew').css('display','block');
      $('.showredinfo').css('display','block');
      $('.showgetredprice>span').text(redprice);
      $('.showredtextinfo>span').text(redtextinfo);
    }
  }
}

function showinshopredinfo(){

  if (getonce != '3') {
    if (nowmca_url == 'Home/Index/Index' || nowmca_url == 'Home/Table/Singlepoint') {
      if (inredprice != '') {
        $('.maskpacket').css('display','block');
        $('.showgetpacket').text(inredprice);
        setTimeout(function(){$('.maskpacket').css('display','none');},3000);
      }
    }
  }
}
</script>
</body>
</html>