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
<link rel="stylesheet" type="text/css" href="/Public/newhome/css/Singlepoint.css?v=1.2" />
<div class="allparts">

  <div class="tophead">
    <div class="shopinfor">
      <img src="<?php echo ($sinfo["Slogo"]); ?>" class="shoplogo" />
      <div class="">
        <div class="shopname mui-ellipsis"><?php echo ($sinfo["storename"]); ?></div>
        <span class="shopphone" RPath="tel:<?php echo ($sinfo["tel"]); ?>"><?php echo ($sinfo["tel"]); ?></span>
      </div>
    </div>
    <div class="tabileinfo">
      <span class="tabileid">桌号:<span><?php echo ($tid); ?>号</span></span>
      <img src="/Public/newhome/img/erweima.png" alt="" class="showcode">
      <span class="imgshowtext mui-ellipsis">点击二维码共同点餐</span>
    </div>
    <div class="text mui-ellipsis">
      <?php echo ($sinfo["city"]); ?>&nbsp;<?php echo ($sinfo["area"]); echo ($sinfo["addr"]); ?>
    </div>
    <img src="<?php echo ($sinfo["Slogo"]); ?>" class="bgimg" />
  </div>



  <div class="propart">
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
                  <img src="<?php echo ($po["ProLogoImg"]); ?>"/>
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
                  <img src="<?php echo ($po["ProLogoImg"]); ?>"/>
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

  <!-- 显示二维码 -->
  <div class="codemark">
    <div class="showcodeinfo">
      <span>微信扫一扫</span>
      <img src="" alt="">
      <span>扫码共同点餐</span>
    </div>
  </div>

  <!-- 显示商品详情 -->
  <div class="proinfo_mark">
    <div class="pro_info_part">
      <div class="pro_info_top">
        <span class="pro_info_mas mui-ellipsis">
          <span class="pro_name mui-ellipsis"></span>
          <span class="pro_sale mui-ellipsis"></span>
        </span>
        <span class="closeproinfo mui-icon mui-icon-closeempty"></span>
      </div>
      <div class="pro_img_list">

      </div>

      <div class="pro_info_bottom">
        <div class="pro_info_list">
          <span>规格:</span>
          <div class="pro_spec_info_list">
            <!-- <span class="p_spec_info mui-ellipsis" data-pid="pro5483288892" data-plid="pro5483288892_01" data-plspec="微辣">
              <span class="mui-ellipsis">微辣</span>
            </span>
            <span class="p_spec_info mui-ellipsis" data-pid="pro5483288892" data-plid="pro5483288892_02" data-plspec="中辣">
              <span class="mui-ellipsis">中辣</span>
            </span>
            <span class="p_spec_info mui-ellipsis" data-pid="pro5483288892" data-plid="pro5483288892_02" data-plspec="中辣">
              <span class="mui-ellipsis">中辣</span>
            </span>
            <span class="p_spec_info mui-ellipsis" data-pid="pro5483288892" data-plid="pro5483288892_02" data-plspec="中辣">
              <span class="mui-ellipsis">中辣</span>
            </span>
            <span class="p_spec_info mui-ellipsis" data-pid="pro5483288892" data-plid="pro5483288892_02" data-plspec="中辣">
              <span class="mui-ellipsis">中辣</span>
            </span>
            <span class="p_spec_info mui-ellipsis" data-pid="pro5483288892" data-plid="pro5483288892_02" data-plspec="中辣">
              <span class="mui-ellipsis">中辣</span>
            </span> -->
          </div>
        </div>
        <div class="pro_spec_bottom">
          <span class="prospec_price">0</span>
          <span class="prospe_chas"></span>
          <div class="shownumbtn" data-pid="" data-pname="" data-pimg="" data-price="" data-cid="" data-plid="" data-plspec="" data-numtype="">
            <!-- <span class="minusbtn ggg minus_icon" style="display: none;"></span>
            <span class="showselnum" style="display: none;">0</span>
            <span class="plusbtn ggg plus_icon" style="display: block;"></span> -->
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- 显示商品详情end -->

</div>
<script type="text/javascript">
var cartslist = '<?php echo ($cartinfo); ?>';
var updatecart_url = "<?php echo U('Public/updatecart');?>";
var order_url = "<?php echo U('Orders/submitorder');?>";
var allproinfo ='<?php echo ($allproinfo); ?>';
var oid = '<?php echo ($oid); ?>';
</script>

<script src="/Public/newhome/js/Singlepoint.js?v=1.2" type="text/javascript" charset="utf-8"></script>
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