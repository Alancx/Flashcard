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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/bankcards.css?v=1.0">
<?php if($bankinfo == ''): ?><div class="cards">
    <label>无银行卡绑定</label>
  </div>
  <?php else: ?>
  <div class="cards">
    <label><?php echo ($bankinfo["BankName"]); ?></label>
    <label>**** **** **** <?php echo substr($bankinfo['IdCard'],-4);?></label>
    <?php if($bankinfo["BankName"] == '中国银行'): ?><img src="/Public/Sellermobile/icon/iconbank/zgbank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '中国农业银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/nybank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '中国工商银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/gsbank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '中国建设银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/jsbank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '交通银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/jtbank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '招商银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/zsbank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '平安银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/pabank.png" alt="">
      <?php elseif($bankinfo["BankName"] == '光大银行'): ?>
      <img src="/Public/Sellermobile/icon/iconbank/gdbank.png" alt=""><?php endif; ?>
  </div><?php endif; ?>
<div class="addcards">
  <label>添加银行卡</label>
  <div class="inputcard">
    <div>
      <label>银行卡号:</label>
      <input type="number" name="cardsid" value="" placeholder="请输入银行卡号码">
    </div>
    <div id="wbanks">
      <label>开户银行:</label>
      <input type="text" name="bankname" value="" placeholder="请选择开户行" readonly="true">
      <input type="hidden" name="bankcid" value="">
    </div>
    <div>
      <label>开户姓名:</label>
      <input type="text" name="membername" value="" placeholder="请填写银行卡绑定的姓名">
    </div>
    <div>
      <label>手机号码:</label>
      <input type="text" name="phoneno" value="" placeholder="请填写手机号码">
    </div>
    <div class="verget">
      <label>验证码:</label>
      <input type="text" name="vernumber" value="" placeholder="请填写验证码">
      <span id="getver" data-s="0">获取验证码</span>
    </div>
  </div>
</div>
<button type="" name="" class="savebutton">保 存</button>

<div class="cardcover">
  <div>
    <label>中国银行
      <input type="radio" name="banksinfo" value="中国银行" data-bid="BOC">
    </label>
    <label>中国农业银行
      <input type="radio" name="banksinfo" value="中国农业银行" data-bid="ABC">
    </label>
    <label>中国工商银行
      <input type="radio" name="banksinfo" value="中国工商银行" data-bid="ICBC">
    </label>
    <label>中国建设银行
      <input type="radio" name="banksinfo" value="中国建设银行" data-bid="CCB">
    </label>
    <label>交通银行
      <input type="radio" name="banksinfo" value="交通银行" data-bid="BCOM">
    </label>
    <label>招商银行
      <input type="radio" name="banksinfo" value="招商银行" data-bid="CMB">
    </label>
    <label>平安银行
      <input type="radio" name="banksinfo" value="平安银行" data-bid="PAB">
    </label>
    <label>光大银行
      <input type="radio" name="banksinfo" value="光大银行" data-bid="CEB">
    </label>
  </div>
</div>
<script type="text/javascript">
var gotime=120;
var type="<?php echo ($type); ?>";
$(document).ready(function(){
  $('#wbanks').click(function(){
    $('.cardcover').css('display','block');
    $('.cardcover>div').css('margin-top',($('.cardcover').height()-$('.cardcover>div').height())/2+'px');
  })
  $("input[name='banksinfo']").change(function(){
    $("input[name='bankname']").val($(this).val());
    $("input[name='bankcid']").val($(this).attr('data-bid'));
    $('.cardcover').css('display','none');
  })
  //////获取短信验证码///////
  $('#getver').click(function(){
    var tel=$("input[name='phoneno']").val();
    if ($('#getver').attr('data-s')=='1') {
      return;
    }
    if (tel=='') {
      tips('notice', '填写手机号!', 1500, 'weui_icon_notice');
      return;
    }
    tips('waiting',"发送中",'weui_icon_notice');
    $('#getver').attr('data-s','1');
    $.ajax({
      url:"<?php echo U('User/sendmsmcode');?>",
      type:"post",
      data:"tel="+tel,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
          tips('notice','发送成功，请等待接收短信',2000);
          $("input[name='phoneno']").attr('readonly',true);
          settime();
        }else{
          $('#getver').attr('data-s','0');
          tips('notice',"发送失败请稍后重试",2500,'weui_icon_notice');
        }
      }
    })
  });
  /////////保存/////////
  $('.savebutton').click(function(){
    if ($("input[name='cardsid']").val()=='') {
      tips('notice','请输入银行卡号',1500,'weui_icon_notice');
      return false;
    }
    if ($("input[name='bankname']").val()=='') {
      tips('notice','请选择银行类型',1500,'weui_icon_notice');
      return false;
    }
    if ($("input[name='membername']").val()=='') {
      tips('notice','请填写开户姓名',1500,'weui_icon_notice');
      return false;
    }
    if ($("input[name='phoneno']").val()=='') {
      tips('notice','请填写手机号码',1500,'weui_icon_notice');
      return false;
    }
    if ($("input[name='vernumber']").val()=='') {
      tips('notice','请填写验证码',1500,'weui_icon_notice');
      return false;
    }
    var savedata={
      'type':type,
      'IdType':$("input[name='bankcid']").val(),
      'IdCard':$("input[name='cardsid']").val(),
      'IdName':$("input[name='membername']").val(),
      'BankName':$("input[name='bankname']").val(),
      'tel':$("input[name='phoneno']").val(),
      'smsbcode':$("input[name='vernumber']").val(),
    };
    tips('waiting',"保存中",8000,'weui_icon_notice');
    $('#getver').attr('data-s','1');
    $.ajax({
      url:"<?php echo U('User/savebanks');?>",
      type:"post",
      data:savedata,
      dataType:"json",
      success:function(msg){
        hidetips('waiting');
        if (msg.status=='true') {
           tips('notice','保存成功',2500,'weui_icon_toast');
           window.location.reload();
        }else{
          if (msg.datainfo=='codeError') {
            tips('notice',"验证码错误",2500,'weui_icon_notice');
          } else{
            tips('notice',"保存失败",2500,'weui_icon_notice');
          }
        }
      }
    })
  })
})
function settime() {
  if (gotime == 0) {
    $("#getver").html('获取验证码');
    $('#getver').attr('data-s','0');
    $("input[name='phoneno']").attr('readonly',false);
    gotime = 120;
    return;
  } else {
    $('#getver').html("正在获取("+gotime+")");
    gotime--;
  }
  setTimeout(function() {
    settime()
  },1000)
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