<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>自助结算</title>

	<link href="/Public/Admin/Admin/css/cashier.css?v=1.2" rel="stylesheet">
	<link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css" rel="stylesheet">
	<script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>  
	<script src="/Public/Admin/Admin/js/bootstrap.min.js"></script>     
	<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
	<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
</head>
<body>
	<div class="container">
		<div class="form-horizontal">
			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style=" text-align:center;">条码</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="Barcode"> 
				</div>
				<div class="tips col-sm-4 col-md-4">※请扫描商品条码/微信付款码 <span class='glyphicon glyphicon-fullscreen pull-right' style="font-size:1.2em;" id='fullscreen'></span></div>
			</div>
		</div>
		<table class="table table-bordered">
			<thead style=" text-align:center;">
				<tr>
					<td>操作</td>
					<td>条码</td>
					<td>商品名称</td>
					<td>单价</td>
					<td>折扣</td>
					<td>数量</td>
					<td>金额</td>
				</tr>
			</thead>
			<tbody style="text-align:center;" class="list_one" >
				
			</tbody>

		</table>
<div class="payment">
	<div>
		数量：<span class="allCount" id='OrderCount'>0</span>
	</div>
	<div>
		应收：<span class="allMoney" id='OrderMoney'>0</span>元 
			</div>
	<div>
		总优惠：<span class="offMoney" id='OrderCoupon'>0</span>
	</div>

<!-- 	<div class="se-type">
		支付方式：<select name="PayType" id="PayType" size="1">
		<option value="T">微信付款</option>
		<option value="YE">余额付款</option>
	</select>
	</div>
 -->
<div class="pull-right"><button class="btn btn-default " id="orderPay" style="font-size:1.5em;font-weight:bold;">刷 新</button></div>

</div>

<div class="foot">

<!-- 	<div>
		<span>会员登陆：</span><span class="memberName"><input type="text" id="getmember" value="" placeholder='扫描用户个人中心二维码' /></span>
	</div>
 --></div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close goshop" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">订单支付</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" class="close goshop" >返回继续购物</button>
      </div>
    </div>
  </div>
</div>
<div id="paywindow" data-toggle="modal" data-target="#myModal"></div>
</body>
<script type="text/javascript">
var OrderId='<?php echo ($OrderId); ?>';
var KEYTYPE='GETPRO';
$(document).ready(function(){
	setInterval(setfocus,3000);
	$('#Barcode').focus();
	$('#fullscreen').click(function(){
		$(this).hide();
		requestFullScreen();
		$('#Barcode').focus();
	})
	$('#Barcode').focus(function(){
		KEYTYPE='GETPRO';
	})
	$(document).on('click','.goshop',function(){
		KEYTYPE='GETPRO';
	})
	$(document).on('click','.lessnum',function(){
		var _this=$(this);
		var pid=$(this).parent().parent().attr('id');
		var num=$('#'+pid+'_count').val();
		if (num<=1) {
			art.dialog.tips('数量为1不可减');
			return;
		}else{
			_this.addClass('disabled');
			$.ajax({
				url:"<?php echo U('OfflineCashier/changnum');?>",
				type:"post",
				data:"pid="+pid+"&type=less&oid="+OrderId,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var rspData=msg.data;
						setSuccessData(rspData,pid);
					}else{
						art.dialog.tips(msg.info);
					}
					_this.removeClass('disabled');
				}
			})
		}
	})
	$(document).on('click','.addnum',function(){
		var _this=$(this);
		var pid=$(this).parent().parent().attr('id');
		_this.addClass('disabled');
		$.ajax({
			url:"<?php echo U('OfflineCashier/changnum');?>",
			type:"post",
			data:"pid="+pid+'&type=add&oid='+OrderId,
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					var rspData=msg.data;
					setSuccessData(rspData,pid);
				}else{
					art.dialog.tips(msg.info);
				}
				_this.removeClass('disabled');
			}
		})
	})

	$(document).on('click','.glyphicon-remove',function(){
		var _this=$(this).parent().parent();
		var pid=_this.attr('id');
		// art.dialog.confirm('确定要删除此商品吗？',function(){
			$.ajax({
				url:"<?php echo U('OfflineCashier/delpro');?>",
				type:"post",
				data:"pid="+pid+"&oid="+OrderId,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						var data=msg.data;
						$('#OrderCount').html(data.Count);
						$('#OrderMoney').html(data.Price);
						_this.remove();
					}else{
						art.dialog.tips(msg.info);
					}
				}
			})
		// })
	})

	$('#orderPay').click(function(){
		location.reload();
		// var PayType=$('#PayType').val();
		// var OrderMoney=parseFloat($('#OrderMoney').html());
		// if (PayType=='T') {
		// 	$.ajax({
		// 		url:"<?php echo U('OfflineCashier/getorderinfo');?>",
		// 		type:"post",
		// 		data:"oid="+OrderId,
		// 		dataType:"json",
		// 		success:function(msg){
		// 			if (msg.status=='success') {
		// 				var oinfo=msg.data;
		// 				var oson = oinfo.son;
		// 				var _html='<table class="table table-bordered"><thead><tr><th>商品名称</th><th>单价</th><th>数量</th><th>合计</th></tr></thead><tbody>';
		// 				$.each(oson,function(index,item){
		// 					var tprice=parseFloat(item.Price);
		// 					var tmoney=parseFloat(item.Money);
		// 					_html+='<tr><td>'+item.ProName+'</td><td>'+tprice.toFixed(2)+'</td><td>'+item.Count+'</td><td>'+tmoney.toFixed(2)+'</td></tr>';
		// 				})
		// 				_html+="</tbody><tfoot><tr><th colspan='2'>总计 </th><th>"+oinfo.Count+"</th><th>优惠："+parseFloat(oinfo.Coupon).toFixed(2)+"实收："+parseFloat(oinfo.Price).toFixed(2)+"</th></tr><tr><th colspan='4'><input type='password' name='auth_code' class='form-control' value='' id='auth_code' placeholder='请扫描用户付款码' /></th></tr></tfoot>";
		// 				$('.modal-body').html(_html);
		// 				$('#paywindow').click();
		// 				window.setTimeout(setfocus,1000);
		// 			}else{
		// 				art.dialog.tips('订单信息获取失败');
		// 			}
		// 		}
		// 	})
		// };
	})

	$(document).keydown(function(event){
		keyCode=event.keyCode;
		if (keyCode==13) {
			if (KEYTYPE=='GETPRO') {
				var barcode=$('#Barcode').val();
				if (barcode) {
					art.dialog.tips('处理中...',1);
					$.ajax({
						url:"<?php echo U('OfflineCashier/getpro');?>",
						type:"post",
						data:"barcode="+barcode+"&OrderId="+OrderId,
						dataType:"json",
						success:function(msg){
							if (msg.status=='success') {
								if (msg.type=='paying') {
									$('#Barcode').val('');
									art.dialog.tips('支付成功',3);
									setTimeout(ref,3000);
								}else{
									var rspData=msg.data;
									var OrderData=rspData.OrderInfo;
									var OrderListData=rspData.OrderList;
									var ProIdCard=OrderListData.ProIdCard;
									if (OrderListData.isnew==1) {
										var _html='<tr id="'+ProIdCard+'"><td><i class="glyphicon glyphicon-remove" style="color:red;font-size:1.2em;"></i></td><td>'+OrderListData.Barcode+'</td><td>'+OrderListData.ProName+'</td><td>'+parseFloat(OrderListData.Price).toFixed(2)+'</td><td id="'+ProIdCard+'_discount">'+parseFloat(OrderListData.Coupon).toFixed(2)+'</td><td ><button class="btn btn-xs btn-default pull-left lessnum" style="padding:20px;line-height:0.0;"> - </button><input type="text" value="'+OrderListData.Count+'" id="'+ProIdCard+'_count" class="num" readonly="true" /><button class="btn btn-xs btn-default pull-right addnum" style="padding:20px;line-height:0.0;"> + </button></td><td id="'+ProIdCard+'_money">'+parseFloat(OrderListData.Money).toFixed(2)+'</td></tr>';
										$('.list_one').append(_html);
									}else{
										$('#'+ProIdCard+'_money').html(parseFloat(OrderListData.Money).toFixed(2));
										$('#'+ProIdCard+'_count').val(OrderListData.Count);
									}
									$('#OrderCount').html(OrderData.Count);
									$('#OrderMoney').html(parseFloat(OrderData.Price).toFixed(2));
									$('#OrderCoupon').html(parseFloat(OrderData.Coupon).toFixed(2));
									$('#Barcode').val('').focus();
								}
							}else{
								art.dialog.tips(msg.info);
								$('#Barcode').val('').focus();
							}
						}
					})
				}else{
					art.dialog.tips('未获取条码信息');
				}
			}else if (KEYTYPE=='PAYING') {
				$('#auth_code').attr('readonly',true);
				KEYTYPE='';
				var auth_code=$('#auth_code').val();
				if (auth_code) {
					art.dialog.tips('处理中，请稍后...',100);
					$.ajax({
						url:"<?php echo U('OfflineCashier/WxScanPay');?>",
						type:"post",
						data:"oid="+OrderId+"&auth_code="+auth_code,
						dataType:"json",
						success:function(msg){
							if (msg.status=='success') {
								//支付完成后续处理
								art.dialog.tips('支付成功',3);
								setTimeout(ref,3000);
							}else{
								art.dialog.tips(msg.info);
								KEYTYPE='PAYING';
								$('#auth_code').attr('readonly',false);
							}
						}
					})
				}else{
					art.dialog.tips('未获取到授权码');
				}
			}else if (KEYTYPE=='GETMEMBER') {
				var memberinfo=$('#getmember').val();
				$('#getmember').val('');
				if (memberinfo) {
					art.dialog.tips('会员信息获取中...',2);
					$.ajax({
						url:"<?php echo U('OfflineCashier/getmember');?>",
						type:"post",
						data:"memberinfo="+memberinfo,
						dataType:"json",
						success:function(msg){
							if (msg.status=='success') {
								var minfo=msg.data;
								$('#getmember').val(minfo.MemberName).attr('readonly',true);
							}else{
								art.dialog.tips(msg.info,3);
							}
						}
					})
				}else{
					art.dialog.tips('未获取到用户授权',2);
				}
			};
		};
	})
	
	$(document).on('focus','#auth_code',function(){
		KEYTYPE='PAYING';
	})
	$(document).on('focus','#getmember',function(){
		KEYTYPE='GETMEMBER';
	})
	$(document).on('click','.close',function(){
		$('#Barcode').focus();
	})



})


function setSuccessData(data,pid){
	var OrderInfo=data.OrderInfo;
	var OrderList=data.OrderList;
	$('#'+pid+'_count').val(OrderList.Count);
	$('#'+pid+'_money').html(parseFloat(OrderList.Money).toFixed(2));
	$('#OrderCount').html(OrderInfo.Count);
	$('#OrderMoney').html(parseFloat(OrderInfo.Price).toFixed(2));
	$('#OrderCoupon').html(parseFloat(OrderInfo.Coupon).toFixed(2));
	$('#Barcode').val('').focus();
}


function requestFullScreen() {
	var de = document.documentElement;
	if (de.requestFullscreen) {
		de.requestFullscreen();
	} else if (de.mozRequestFullScreen) {
		de.mozRequestFullScreen();
	} else if (de.webkitRequestFullScreen) {
		de.webkitRequestFullScreen();
	}
}
function setfocus(){
	$('#auth_code').focus().val('');
}
function ref(){
	location.reload();
}
function setfocus(){
	$('#Barcode').focus();
}
</script>
</html>