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
<link rel="stylesheet" type="text/css" href="/Public/newhome/css/index.css?v=2.1" />
<div class="contents">
  <!-- 顶部店铺信息 -->
  <div class="tophead">
    <div class="shopinfor">
      <img src="<?php echo ($sinfo["Slogo"]); ?>" class="shoplogo" />
      <div class="">
        <div class="shopname mui-ellipsis"><?php echo ($sinfo["storename"]); ?></div>
        <span class="shopphone" RPath="tel:<?php echo ($sinfo["tel"]); ?>"><?php echo ($sinfo["tel"]); ?></span>
      </div>
    </div>
    <div class="icons">
      <span class="setshoplx" data-sname="<?php echo ($sinfo["storename"]); ?>" data-lat="<?php echo ($sinfo["lat"]); ?>" data-lng="<?php echo ($sinfo["lang"]); ?>">
        <small class="ggg address_icon"></small>
        <span>导航</span>
      </span>
      <?php if($colltype == 0 ): ?><span class="setshopgz" data-type='0'>
          <small class="ggg xin_icon"></small>
          <span>关注</span>
        </span>
        <?php else: ?>
        <span class="setshopgz" data-type='1'>
          <small class="ggg xin_fillicon"></small>
          <span>已关注</span>
        </span><?php endif; ?>
    </div>
    <div class="text mui-ellipsis">
      <?php echo ($sinfo["city"]); ?>&nbsp;<?php echo ($sinfo["area"]); echo ($sinfo["addr"]); ?>
    </div>
    <img src="" class="bgimg" />
  </div>
  <!-- 中间区域信息 -->
  <div class="allproinfo">
    <div class="contentstop">
      <!--红包部分-->
      <?php if($coupon != null ): ?><div class="redenvelpart">
          <div class="redenveltitle">
            <span>领红包</span>
            <span>少花钱</span>
          </div>
          <div class="redenvellist">
            <div class="redenvel_list">
              <?php if(is_array($coupon)): $i = 0; $__LIST__ = $coupon;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="redinfo" data-id="<?php echo ($vo["CouponId"]); ?>" RPath="<?php echo U('Index/redenvel',array('rid'=>$vo['CouponId']));?>"><span><?php echo ($vo["Rules"]); ?></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
          </div>
        </div><?php endif; ?>
      <?php if($gginfo != null ): ?><div class="marqueeinfo">
          <img src="" class="bgimg" />
          <marquee scrollamount="5">【光盘客】<?php echo ($gginfo); ?></marquee>
        </div><?php endif; ?>
      <!--活动区域部分信息-->
      <div class="activitypart">
        <div class="activitypart_1" RPath="<?php echo U('Index/activity',array('type'=>TJ));?>">
          <span>特价</span>
          <?php if($tjimg != null ): ?><img src="<?php echo ($tjimg); ?>"/>
            <?php else: ?>
            <img src="/Public/newhome/img/activityimg.jpg" /><?php endif; ?>
        </div>
        <div class="activitypart_2" RPath="<?php echo U('Index/activity',array('type'=>TS));?>">
          <span>特色</span>
          <?php if($tsimg != null ): ?><img src="<?php echo ($tsimg); ?>"/>
            <?php else: ?>
            <img src="/Public/newhome/img/activityimg.jpg" /><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="tabpart">
      <ul id="tab_part">
        <li class="current" data-index='0'><span>点单</span><span class="tab_line"></span></li>
        <li data-index='1'><span>评价(<?php echo ($sevalnum); ?>)</span><span class="tab_line"></span></li>
        <li data-index='2'><span>商家</span><span class="tab_line"></span></li>
      </ul>
    </div>
    <div class="allinfopart">
      <div class="propart" data-oo="tab">
        <div class="lefttab">
          <ul>
            <?php if(is_array($allpros)): foreach($allpros as $key=>$ap): ?><li class="mui-ellipsis-2"><span class="mui-ellipsis-2"><?php echo ($ap["ClassName"]); ?></span></li><?php endforeach; endif; ?>
          </ul>
        </div>
        <div class="righttab">
          <ul>
            <?php if(is_array($allpros)): foreach($allpros as $key=>$pro): ?><li>
                <div class="class-title"><span><?php echo ($pro["ClassName"]); ?></span></div>
                <?php if(is_array($pro["pros"])): foreach($pro["pros"] as $key=>$po): if($po['plcount'] > 1 ): ?><div class="spitem outproinfo" data-pid="<?php echo ($po["ProId"]); ?>" data-cid="<?php echo ($pro["ClassId"]); ?>">
                      <img src="<?php echo ($po["ProLogoImg"]); ?>" RPath="<?php echo U('Goods/goods');?>?pid=<?php echo ($po["ProId"]); ?>"/>
                      <div>
                        <p class="mui-ellipsis"><?php echo ($po["ProName"]); ?></p>
                        <div class="">
                          <span>
                            <?php if($po['NumType'] == '1' ): ?><span class="jiage"><?php echo $po['Price']?sprintf("%.2f",$po['Price']):'0.00' ?><span>/份</span></span>
                              <?php else: ?>
                              <span class="jiage"><?php echo $po['Price']?sprintf("%.2f",$po['Price']):'0.00' ?><span>/斤</span></span><?php endif; ?>
                            <?php if($po['Price'] != $po['OldPrice'] ): ?><span class="fubiao"><del><?php echo $po['OldPrice']?sprintf("%.2f",$po['OldPrice']):'0.00' ?></del></span>
                              <?php else: ?>
                              <span class="fubiao"></span><?php endif; ?>
                          </span>
                          <p class="showsale">已售<?php echo ($po["SalesCount"]); ?></p>
                        </div>
                        <div class="shownumbtn"  data-pid="<?php echo ($po["ProId"]); ?>" data-pname="<?php echo ($po["ProName"]); ?>" data-pimg="<?php echo ($po["ProLogoImg"]); ?>" data-price="<?php echo ($po["Price"]); ?>" data-cid="<?php echo ($pro["ClassId"]); ?>" data-plcount="<?php echo ($po["plcount"]); ?>" data-numtype="<?php echo ($po["NumType"]); ?>">
                          <span class="selSpec">选规格</span>
                        </div>
                      </div>
                      <?php if($po['Level'] != ''): ?><span class="eatmore">越吃越便宜</span><?php endif; ?>
                    </div>
                  <?php else: ?>
                  <div class="spitem outproinfo" data-pid="<?php echo ($po["ProId"]); ?>" data-cid="<?php echo ($pro["ClassId"]); ?>" data-plid="<?php echo ($po['prolist'][0]['ProIdCard']); ?>">
                    <img src="<?php echo ($po["ProLogoImg"]); ?>" RPath="<?php echo U('Goods/goods');?>?pid=<?php echo ($po["ProId"]); ?>"/>
                    <div>
                      <p class="mui-ellipsis"><?php echo ($po["ProName"]); ?></p>
                      <div class="">
                        <span>
                          <?php if($po['NumType'] == '1' ): ?><span class="jiage"><?php echo $po['Price']?sprintf("%.2f",$po['Price']):'0.00' ?><span>/份</span></span>
                            <?php else: ?>
                            <span class="jiage"><?php echo $po['Price']?sprintf("%.2f",$po['Price']):'0.00' ?><span>/斤</span></span><?php endif; ?>
                          <?php if($po['Price'] != $po['OldPrice'] ): ?><span class="fubiao"><del><?php echo $po['OldPrice']?sprintf("%.2f",$po['OldPrice']):'0.00' ?></del></span>
                            <?php else: ?>
                            <span class="fubiao"></span><?php endif; ?>
                        </span>
                        <p class="showsale">已售<?php echo ($po["SalesCount"]); ?></p>
                      </div>
                      <div class="shownumbtn"  data-pid="<?php echo ($po["ProId"]); ?>" data-pname="<?php echo ($po["ProName"]); ?>" data-pimg="<?php echo ($po["ProLogoImg"]); ?>" data-price="<?php echo ($po["Price"]); ?>" data-cid="<?php echo ($pro["ClassId"]); ?>" data-plcount="<?php echo ($po["plcount"]); ?>" data-plid="<?php echo ($po['prolist'][0]['ProIdCard']); ?>" data-plspec="<?php echo ($po['prolist'][0]['ProSpec']); ?>" data-numtype="<?php echo ($po["NumType"]); ?>">
                        <?php if($po['NumType'] == '1' ): ?><span class="minusbtn ggg minus_icon"></span>
                          <span class="showselnum">0</span>
                          <span class="plusbtn ggg plus_icon"></span>
                          <?php else: ?>
                          <input type="text" name="proweight" class="proweight" value="0"><?php endif; ?>
                      </div>
                    </div>
                    <?php if($po['Level'] != ''): ?><span class="eatmore">越吃越便宜</span><?php endif; ?>
                  </div><?php endif; endforeach; endif; ?>
              </li><?php endforeach; endif; ?>
          </ul>
        </div>

      </div>
      <div class="evaluatepart" data-oo="tab" hidden="">
        <ul>
          <?php if(is_array($shopeval)): foreach($shopeval as $key=>$sv): ?><li class="evaluate_list">
              <img class="userhead" src="<?php echo ($sv["HeadImgUrl"]); ?>" alt="">
              <div class="righteval">
                <span class="userinfo">
                  <span class="username"><?php echo ($sv["MemberName"]); ?></span>
                  <span class="setstarts">
                    <?php $__FOR_START_19465__=1;$__FOR_END_19465__=6;for($i=$__FOR_START_19465__;$i < $__FOR_END_19465__;$i+=1){ if($sv['ServiceScore'] >= $i): ?><span class="ggg xing_icon setstartactive"></span>
                        <?php else: ?>
                        <span class="ggg xing_icon"></span><?php endif; } ?>
                  </span>
                  <span class="usertime"><?php echo ($sv["cdate"]); ?></span>
                </span>
                <span class="usersetevaluate mui-ellipsis-2"><?php echo ($sv["Content"]); ?></span>
                <div class="usersetimglist">
                  <!-- <img src="/Public/newhome/img/spimg.jpg" alt="">
                  <img src="/Public/newhome/img/spimg.jpg" alt="">
                  <img src="/Public/newhome/img/spimg.jpg" alt=""> -->
                </div>
              </div>
            </li><?php endforeach; endif; ?>
        </ul>
      </div>
      <div class="businesspart" data-oo="tab" hidden="">
        <ul>
          <li class="business_info">
            <span  class="ggg address_icon"></span>
            <span class="business_addr mui-ellipsis"><?php echo ($sinfo["city"]); echo ($sinfo["area"]); echo ($sinfo["addr"]); ?></span>
            <span  class="ggg phone_icon" RPath="tel:<?php echo ($sinfo["tel"]); ?>"></span>
          </li>
          <li class="business_imgs">
            <?php if(is_array($simgs)): foreach($simgs as $key=>$si): ?><img src="<?php echo ($si); ?>" alt=""><?php endforeach; endif; ?>
          </li>
        </ul>
      </div>
    </div>

  </div>
  <!-- 底部结算区域 -->
  <div class="bottompart">
    <div class="showgetpronum">
      <span>已点</span>
      <span>0</span>
    </div>
    <span class="gotopay">去结算</span>
    <span class="showtotal mui-ellipsis"><span class="totalprice">0.00</span></span>
    <div class="hasselprosmark">
      <div class="hasselpros">
        <div class="hasselprostop">
          <span class="cleanselpros">清空菜单</span>
          <span class="downcarts">收起</span>
        </div>
        <div class="selproslist">
          <!-- <div class="spitem inproinfo" data-pid="pro0640928931" data-cid="undefined">
            <img src="/Upload/Uoloads/2017-11-14/5a0a8d07a5677.jpg">
            <div>
              <p class="selpronameinfo">
                <span class="selproname mui-ellipsis">小盘咸鱼</span>
                <span class="selprospec mui-ellipsis">(微辣)</span>
              </p>
              <span class="jiage">39</span>
              <div class="shownumbtn" data-pid="pro0640928931" data-pname="小盘咸鱼" data-pimg="/Upload/Uoloads/2017-11-14/5a0a8d07a5677.jpg" data-price="39" data-cid="undefined" data-plid="undefined" data-plspec="undefined">
                <span class="minusbtn ggg minus_icon"></span>
                <span class="showselnum">1</span>
                <span class="plusbtn ggg plus_icon"></span>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>

  <!-- 选择规格属性 -->
  <div class="prospecmark">
    <div class="prospecinfo">
      <div class="prospec_top">
        <span class="pspecname mui-ellipsis"></span>
        <span class="closeselprospec mui-icon mui-icon-closeempty"></span>
      </div>
      <div class="prospec_list">
        <span>规格:</span>
        <div class="prospec_info_list">

        </div>
        <!-- <span class="pspec_info mui-ellipsis"><span>微辣</span></span>
        <span class="pspec_info mui-ellipsis"><span>重辣</span></span>
        <span class="pspec_info mui-ellipsis"><span>变态辣</span></span>
        <span class="pspec_info mui-ellipsis"><span>超级辣</span></span>
        <span class="pspec_info mui-ellipsis"><span>重辣</span></span>
        <span class="pspec_info mui-ellipsis"><span>变态辣</span></span>
        <span class="pspec_info mui-ellipsis pspec_infoactive"><span>超级辣</span></span> -->
      </div>
      <div class="prospec_bottom">
        <span class="prospecprice">0</span>
        <span class="prospechas"></span>
        <div class="shownumbtn"  data-pid="" data-pname="" data-pimg="" data-price="" data-cid="" data-plid="" data-plspec="" data-numtype="">
          <!-- <span class="minusbtn ggg minus_icon"></span>
          <span class="showselnum">0</span>
          <span class="plusbtn ggg plus_icon"></span> -->
        </div>
      </div>
    </div>
  </div>
  <!-- 选择规格属性end -->

</div>
<script src="/Public/newhome/js/index.js?v=2.2" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var updatecart_url = "<?php echo U('Public/updatecart');?>";
var cartslist = '<?php echo ($cartinfo); ?>';
var order_url = "<?php echo U('Orders/submitorder');?>";
var setshopcollect_url = "<?php echo U('Index/setshopcollect');?>";
var allproinfo ='<?php echo ($allproinfo); ?>';
</script>
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