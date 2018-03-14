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
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/font_gpke.css?v=1.3" />
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/mynew.css?v=1.6" />
  <script type="text/javascript">
  var up_url = '<?php echo ($up_url); ?>';
  var nowmca_url = '<?php echo ($nowmca_url); ?>';
  </script>
  <script type="text/javascript" src="/Public/newhome/js/Base.js?v=2.1"></script>
</head>
<body>
  <div class="container">
<link rel="stylesheet" type="text/css" href="/Public/newhome/css/orderinfos.css?v=1.1" />
<div class="submitorderpart">
  <div class="orderpart">
    <div class="topsinfo">
      <img src="<?php echo ($sinfo['Slogo']); ?>" alt="">
      <span class="sname"><?php echo ($sinfo['storename']); ?></span>
      <span class="mui-icon mui-icon-arrowright sicon"></span>
    </div>
    <div class="proslist">
      <div class="pro_list">

      </div>
      <div class="getmorepros">
        <span class="getmorebtn" data-type="0"><span class="showtext">展开更多</span><span class="mui-icon mui-icon-arrowdown showgeticon"></span></span>
      </div>
    </div>
    <div class="showpriceinfo">
      <!-- <div class="cutleryfee">
        <span>餐具费</span>
        <span>0.00</span>
      </div> -->
      <!-- <div class="fullreduction">
        <span>满减优惠</span>
        <span>0.00</span>
      </div> -->
      <?php if($oinfo['CouponListId'] != '0'): ?><div class="redparts">
          <span>红包</span>
          <span class="redprice"><?php echo ($oinfo["Coupon"]); ?></span>
        </div><?php endif; ?>
      <!-- <div class="redparts">
        <span>就餐人数</span>
        <span class=""><?php echo ($oinfo["TableId"]); ?></span>
      </div> -->
      <div class="showlines">
        <span class="lineleft"></span>
        <span class="linecenter"></span>
        <span class="lineright"></span>
      </div>
      <div class="alltotalprice">
        <span class="aprice"><?php echo number_format(($oinfo['Price']),2); ?></span>
        <span>合计</span>
      </div>
      <div class="showlines">
        <span class="lineleft"></span>
        <span class="linecenter"></span>
        <span class="lineright"></span>
      </div>
      <div class="telinfo">
        <span class="ggg phone_icon" RPath="tel:<?php echo ($sinfo["tel"]); ?>">电话商家</span>
      </div>
    </div>
  </div>
  <div class="bottompart">
    <!-- <?php switch($oinfo["Status"]): case "1": ?><span class="qxorder">取消订单</span><span class="payorder">立即付款</span><?php break;?>
      <?php case "2": ?><span class="tkorder">退款</span><span class="eatorder">就餐码</span><?php break;?>
      <?php case "3": ?><span class="tkorder">退款</span><span class="eatorder">就餐码</span><?php break;?>
      <?php case "4": if($oinfo["IsEvaluation"] == 0): ?><span class="evalorder">立即评价</span>
          <?php else: ?>
          <span class="delorder">删除订单</span><?php endif; break;?>
      <?php case "8": ?><span class="qxorder">取消订单</span><?php break;?>
      <?php default: endswitch;?> -->
    <?php switch($oinfo["Status"]): case "2": ?><span class="shareorder" RPath="<?php echo U('Home/Orders/shareorderpages',array('oid'=>$orderid));?>">立即分享</span><?php break;?>
      <?php case "3": ?><span class="shareorder" RPath="<?php echo U('Home/Orders/shareorderpages',array('oid'=>$orderid));?>">立即分享</span><?php break;?>
      <?php case "4": ?><span class="shareorder" RPath="<?php echo U('Home/Orders/shareorderpages',array('oid'=>$orderid));?>">立即分享</span><?php break;?>
      <?php case "8": ?><span class="shareorder" RPath="<?php echo U('Home/Orders/shareorderpages',array('oid'=>$orderid));?>">立即分享</span><?php break;?>
      <?php default: endswitch;?>

  </div>
</div>
<script src="/Public/newhome/js/orderinfos.js?v=1.3" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
  var olinfo = '<?php echo ($olinfo); ?>';
  var orderid ='<?php echo ($orderid); ?>';
</script>


<!-- <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> -->
<!-- <script type="text/javascript">
var SID;//分享预处理ID
$(document).ready(function() {
    $.ajax({
        url: "<?php echo U('Orders/shareinfo');?>",
        type: "post",
        data: {
            Type: "O",
            //分享类型 商品分享
            Sharer: '<?php echo ($oinfo["MemberId"]); ?>',
            ShareId: orderid,
            s: "preshare"
        },
        dataType: "json",
        success: function(msg) {
            if (msg.status == 'success') {
                SID = msg.ID;
            } else {

            }
        }
    })
    wx.config(<?php echo ($wxJSSDKConfigStr); ?>);
    wx.ready(function() {
        wx.onMenuShareTimeline({

            title: '<?php echo ($sinfo["storename"]); ?>',
            // 分享标题

            link: window.location.href + '&SID=' + SID,
            // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致

            imgUrl: window.location.protocol + '//' + window.location.host + '<?php echo ($sinfo["Slogo"]); ?>',
            // 分享图标

            success: function() {
                // 用户确认分享后执行的回调函数
                sharesuccess();

            },
        });
        wx.onMenuShareAppMessage({

            title: '<?php echo ($sinfo["storename"]); ?>',
            // 分享标题

            desc: '<?php echo ($sinfo["storename"]); ?>',
            // 分享描述

            link: window.location.href + '&SID=' + SID,
            // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致

            imgUrl: window.location.protocol + '//' + window.location.host + '<?php echo ($sinfo["Slogo"]); ?>',
            // 分享图标

            type: '',
            // 分享类型,music、video或link，不填默认为link

            dataUrl: '',
            // 如果type是music或video，则要提供数据链接，默认为空

            success: function() {

                sharesuccess();

            },

            cancel: function() {

                // 用户取消分享后执行的回调函数

            }

        });
    })
})
function sharesuccess(){
  $.ajax({
      url:"<?php echo U('Orders/shareinfo');?>",
      type:"post",
      data:{
        s:"shareout",
        ID:SID
    },
    dataType:"json",
    success:function(msg){
        if (msg.status=='success') {
          console.log(msg);
              //成功信息  判断是否有红包
              if (msg.redpaper=='yes') {
                //有红包处理一下
            }else{
                //没红包，普通的分享
            }
        }else{
              //错误信息
          }
      }
  })
}
</script> -->
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