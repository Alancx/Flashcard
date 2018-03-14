<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="/Public/Admin/Admin/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Public/Admin/Admin/css/weui.min.css">
  <style type="text/css">
	.row{
		background-color: #ddd;
	}
	.box{
		text-align: center;
		margin-top: 15px;
		background-color: #fff;
		padding-top: 10px;
		padding-bottom: 10px;
		padding-left: 0px;
		padding-right: 0px;
	}
	.box>.form-control{
		border-radius: 0px;
		border-top: 0px solid #ccc;
		border-left: 0px solid #ccc;
		border-right: 0px solid #ccc;
		border-bottom: 1px solid #ddd;
		height: 40px;
	}
	body{
		background-color: #ddd;
	}
	.img-box{
		width: 100%;
		text-align: center;
		font-weight: bold;
		margin-bottom: 5px;
	}
	.img-box>img{
		width: 30%;
		margin: auto;
		border-radius: 50%;
		border:1px solid #ddd;
		margin-bottom: 5px;
	}
	#pay{
		background-color: #FFB322;
		border-color:#FFB322;
	}
	.discount{
		color: #AAA;
		line-height: 10px;
	}
  </style>
  <title>收银台</title>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 box">
				<div class="img-box"><img src="<?php echo $wxinfo['headimgurl'] ?>" alt=""><br><?php echo ($wxinfo['nickname']); ?></div>
				<select name="pro" id="pro" class="form-control">
					<option value="">选择商品</option>
					<?php if(is_array($pros)): foreach($pros as $key=>$ps): ?><option value="<?php echo ($key); ?>"><?php echo ($ps); ?></option><?php endforeach; endif; ?>
				</select>
				<input type="text" name="money" id="money" class="form-control" placeholder='&nbsp;付款金额'>
				<select name="payType" id="payType" class="form-control">
					<option value="">付款方式</option>
					<option value="XJ">现金支付</option>
					<option value="T">微信支付支付</option>
					<?php if (session('isMember')=='1'): ?>
						<option value="YE">余额支付</option>
					<?php endif ?>
				</select>
				<small class='discount'></small>
				<div style="height:20px;width:100%;"></div>
				<button class="btn btn-success btn-md" id="pay" style="width:90%;margin:auto;">确认付款</button>
				<!-- <a href="" class="btn btn-default btn-md">刷新</a> -->
				<div class='addr'></div>
			</div>
		</div>
	</div>
    <div id="toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content">已完成</p>
        </div>
    </div>
    <div id="loadingToast" class="weui_loading_toast" style="display:none;">
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
<script type="text/javascript" src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
	var jsApi='';
	var oid='';
	var cut=<?php echo $cut ?>;
	$(document).ready(function(){
		$('#payType').change(function(){
			var type=$(this).val();
			var money=$('#money').val();
			if (money) {
				if (type!='XJ') {
					var truemoney=parseFloat(money)*parseFloat(cut)/100;
					var discount=parseFloat(money)-parseFloat(truemoney);
					$('.discount').html('优惠金额：'+discount.toFixed(2)+'元，实收金额：'+truemoney.toFixed(2));
				}else{
					$('.discount').html('');
				}
			}else{
				art.dialog.tips('请输入付款金额');
				$('#money').focus();
				return false;
			}
		})
		$('#pay').click(function(){
			var money=$('#money').val();
			var payType=$('#payType').val();
			var proid=$('#pro').val();
			if (!proid) {
				$('.weui_toast_content').html('请选择付款商品');
				$('#toast').show();
				$('#pro').focus();
				setTimeout("$('#toast').hide()",1500);
				return false;
			};
			if (!parseFloat(money)) {
				$('.weui_toast_content').html('请输入付款金额');
				$('#toast').show();
				$('#money').focus();
				setTimeout("$('#toast').hide()",1500);
				return false;
			}
			if (!payType) {
				$('.weui_toast_content').html('请选择付款方式');
				$('#toast').show();
				$('#payType').focus();
				setTimeout("$('#toast').hide()",1500);
				return false;
			};
			if (payType=='XJ') {
				//现金交易处理
				$('.weui_toast_content').html('数据加载中...');
				$('#loadingToast').show();
				$.ajax({
					url:"<?php echo U('Index/pay');?>",
					type:"post",
					data:"money="+money+"&type=XJ&pid="+proid,
					dataType:"json",
					success:function(msg){
						$('#loadingToast').hide();
						if (msg.statu=='success') {
							alert('支付成功');
							window.location.reload();
						}else{
							$('.weui_toast_content').html(msg.info);
							$('#toast').show();
							setTimeout("$('#toast').hide()",1500);
						}
					}
				})
			}else if(payType=='YE'){
				//余额交易处理
				$('.weui_toast_content').html('数据加载中...');
				$('#loadingToast').show();
				$.ajax({
					url:"<?php echo U('Index/pay');?>",
					type:"post",
					data:"money="+money+"&type=YE&pid="+proid,
					dataType:"json",
					success:function(msg){
						$('#loadingToast').hide();
						if (msg.statu=='success') {
							alert('支付成功');
							window.location.reload();
						}else{
							$('.weui_toast_content').html(msg.info);
							$('#toast').show();
							setTimeout("$('#toast').hide()",1500);
						}
					}
				})
			}else{
				$('.weui_toast_content').html('数据加载中...');
				$('#loadingToast').show();
				$.ajax({
					url:"<?php echo U('Index/getPay');?>",
					type:"post",
					data:"money="+money+"&pid="+proid,
					dataType:"json",
					success:function(msg){
						$('#loadingToast').hide();
						if (msg.statu=='success') {
							oid=msg.oid;
							jsApi=msg.info;
							jsApiCall();
						}else{
							$('.weui_toast_content').html('获取支付信息失败');
							$('#toast').show();
							setTimeout("$('#toast').hide()",1500);
						}
					}
				})
			}
		})
	})
	//调用微信JS api 支付
	function jsApiCall()
	{
		// alert(oid)
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			jsApi,
			function(res){
				// $.ajax({
				// 	url:"<?php echo U('Index/writeMsg');?>",
				// 	type:"post",
				// 	data:'msg='+res.err_msg,
				// 	dataType:"json",
				// 	success:function(msg){
				// 		console.log(msg);
				// 	}
				// })
				WeixinJSBridge.log(res.err_msg);
				console.log(res);
				if (res.err_msg.indexOf('ok')>0) {
					$('.weui_toast_content').html('支付成功');
					$('#toast').show();
					setTimeout("$('#toast').hide()",1500);
					window.location.reload();
					// $.ajax({
					// 	type:"post",
					// 	url:"<?php echo U('Index/paysuccess');?>",
					// 	data:'oid='+oid,
					// 	dataType:'json',
					// 	success:function(msg){
					// 		if (msg=='success') {
					// 			alert('支付成功');
					// 			window.location.reload();
					// 		}else{
					// 			$('.weui_toast_content').html(msg);
					// 			$('#toast').show();
					// 			setTimeout("$('#toast').hide()",15000);
					// 		}
					// 		// if (msg=='error') {
					// 		// 	$('.weui_toast_content').html(msg);
					// 		// 	$('#toast').show();
					// 		// 	setTimeout("$('#toast').hide()",15000);
					// 		// };
					// 	}
					// })
					//支付成功
				}else if(res.err_msg.indexOf('cancel')>0){
					$('.weui_toast_content').html('取消支付');
					$('#toast').show();
					setTimeout("$('#toast').hide()",1500);
				}else if(res.err_msg.indexOf('fail')>0){
					$('.weui_toast_content').html('支付失败');
					$('#toast').show();
					setTimeout("$('#toast').hide()",1500);
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $address; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				$('.addr').html(value1 + value2 + value3 + value4 + ":" + tel);

			}
		);
	}
	
	// window.onload = function(){
	// 	if (typeof WeixinJSBridge == "undefined"){
	// 	    if( document.addEventListener ){
	// 	        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
	// 	    }else if (document.attachEvent){
	// 	        document.attachEvent('WeixinJSBridgeReady', editAddress); 
	// 	        document.attachEvent('onWeixinJSBridgeReady', editAddress);
	// 	    }
	// 	}else{
	// 		editAddress();
	// 	}
	// };
</script>
</html>