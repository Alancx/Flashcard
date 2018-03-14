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
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/mynew.css?v=1.3" />
  <script type="text/javascript">
  var up_url = '<?php echo ($up_url); ?>';
  var nowmca_url = '<?php echo ($nowmca_url); ?>';
  </script>
  <script type="text/javascript" src="/Public/newhome/js/Base.js?v=1.6"></script>
</head>
<body>
  <div class="container">
<link rel="stylesheet" type="text/css" href="/Public/newhome/css/userevaluation.css?v=1.1" />
<div class="evalparts">
  <div class="evalinfopart" data-stoken="<?php echo ($sinfo["stoken"]); ?>" data-oid="<?php echo ($oid); ?>">
    <div class="shopevalpart">
      <div class="shopinfo">
        <img src="<?php echo ($sinfo["Slogo"]); ?>" alt="">
        <span class="mui-ellipsis"><?php echo ($sinfo["storename"]); ?><span class="mui-icon mui-icon-arrowright"></span></span>
      </div>
      <div class="sevalinfos">
        <div class="shopservice">
          <span>商家</span>
          <div class="starsinfo">
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
          </div>
        </div>
        <div class="shoppros">
          <span>菜品</span>
          <div class="starsinfo">
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
            <span class="ggg xing_icon"></span>
          </div>
        </div>
        <div class="inputtextinfo">
          <textarea name="inputinfo" class="inputinfo" placeholder="说两句,谢谢!"></textarea>
        </div>
      </div>
    </div>
    <!-- 商品 -->
    <div class="prolistpart">
      <?php if(is_array($olinfo)): foreach($olinfo as $key=>$ol): ?><div class="proevalpart" data-pid="<?php echo ($ol["ProId"]); ?>" data-olid="<?php echo ($ol["OrderListId"]); ?>">
          <div class="proinfo">
            <img src="<?php echo ($ol["ProLogoImg"]); ?>" alt="">
            <span class="mui-ellipsis"><?php echo ($ol["ProName"]); ?>
              <span class="mui-icon mui-icon-arrowright"></span>
              <div>
                <span><?php echo ($ol["Price"]); ?></span>
                <span><?php echo ($ol["Count"]); ?></span>
              </div>
            </span>
          </div>
          <div class="proevalinfos">
            <div class="shoppros">
              <span>菜品</span>
              <div class="starsinfo">
                <span class="ggg xing_icon"></span>
                <span class="ggg xing_icon"></span>
                <span class="ggg xing_icon"></span>
                <span class="ggg xing_icon"></span>
                <span class="ggg xing_icon"></span>
              </div>
            </div>
            <div class="inputtextinfo">
              <textarea name="inputinfo" class="inputinfo" placeholder="说两句,谢谢!"></textarea>
            </div>
          </div>
        </div><?php endforeach; endif; ?>

      <!-- <div class="proevalpart">
        <div class="proinfo">
          <img src="<?php echo ($sinfo["Slogo"]); ?>" alt="">
          <span class="mui-ellipsis"><?php echo ($sinfo["storename"]); ?><span class="mui-icon mui-icon-arrowright"></span></span>
        </div>
        <div class="proevalinfos">
          <div class="shoppros">
            <span>菜品</span>
            <div class="starsinfo">
              <span class="ggg xing_icon"></span>
              <span class="ggg xing_icon"></span>
              <span class="ggg xing_icon"></span>
              <span class="ggg xing_icon"></span>
              <span class="ggg xing_icon"></span>
            </div>
          </div>
          <div class="inputtextinfo">
            <textarea name="inputinfo" class="inputinfo" placeholder="说两句,谢谢!"></textarea>
          </div>
        </div>
      </div>       -->

    </div>




  </div>
  <div class="sendbtnpart">
    <span class="sendbtn">提交</span>
  </div>
</div>
<script type="text/javascript">
  var setuserevaluation_url ="<?php echo U('User/setuserevaluation');?>";//提交评论信息
  var userorders_url ="<?php echo U('User/Userorders');?>";//用户订单页面
</script>
<script src="/Public/newhome/js/userevaluation.js?v=1.1" type="text/javascript" charset="utf-8"></script>
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
        <span>当前红包可领取!</span>
      </div>
    </div>
  </div>
  <!-- 红包领取end -->
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
  $(document).ready(function() {
    
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
      console.log(menulist.length);
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
            $('.maskpacket').css('display','block');
            $('.showgetpacket').text(msg.red_rule);
            setTimeout(function(){$('.maskpacket').css('display','none');},5000);
          }else{

          }
        }else{

        }

        if(nowmca_url == 'Home/Orders/shareorderpages'){
          $('.sharemark').css('display','none');
          setTimeout(function(){window.location.href = "<?php echo U('User/Userorders');?>";},5000);
        }
      }
    })
  }
  function showlinksshare(){
    if (shareorder !='true') {
      if (sharecouponinfo != 1) {
        $('.maskpacket').css('display','block');
        $('.showgetpacket').text(sharecouponinfo.Rules);
        setTimeout(function(){$('.maskpacket').css('display','none');},5000);
      }
    }
  }
  </script>
</body>
</html>