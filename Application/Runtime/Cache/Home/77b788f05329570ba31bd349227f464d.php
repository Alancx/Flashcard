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
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/font_gpke.css" />
  <link rel="stylesheet" type="text/css" href="/Public/newhome/css/mynew.css?v=1.0" />
</head>
<body>
  <div class="container"> 
</div>
  <!-- footer-menus -->
  <?php if($footerSign == 1): ?><div class="footer">
      <div class="home">
        <a href="<?php echo U('Index/Index');?>">
          <div class="ficon">
            <img src="/Public/theme2/Images/PageModelIcon/home<?php echo ($nowBootBarStatus[0]); ?>.png" alt="首页">
            <br/>
          </div>
          <span>首页</span>
        </a>
      </div>

      <div class="menu">
        <a href="<?php echo U('Index/Category');?>">
          <div class="ficon">
            <img src="/Public/theme2/Images/PageModelIcon/list<?php echo ($nowBootBarStatus[1]); ?>.png" alt="分类">
            <br/>
          </div>
          <span>分类</span>
        </a>
      </div>

      <div class="menu">
        <a href="<?php echo U('Index/Index',array('stoken'=>'0'));?>">
          <div class="ficon logoIndex">
            <img style="width:40px; height: 40px; margin:10px 10px;" src="/Public/Images/logoIndex<?php echo ($nowBootBarStatus[2]); ?>.png" alt="f">
            <br/>
          </div>
        </a>
      </div>

      <div class="menu">
        <a href="<?php echo U('LBS/myaddr');?>">
          <div class="ficon">
            <img src="/Public/theme2/Images/PageModelIcon/addr<?php echo ($nowBootBarStatus[3]); ?>.png" alt="附近门店">
            <br/>
          </div>
          <span>附近门店</span>
        </a>
      </div>
      <div class="menu">
        <a href="<?php echo U('User/Index');?>">
          <div class="ficon">
            <img src="/Public/theme2/Images/PageModelIcon/user<?php echo ($nowBootBarStatus[4]); ?>.png" alt="个人中心">
            <br/></div>
            <span>个人中心</span>
          </a>
        </div>


      </div><?php endif; ?>
    <!-- footer-menus -->
    <!-- 没有关注公众号end -->
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
  <script type="text/javascript" src="/Public/newhome/js/Base.js?v=1.0"></script>
  </html>