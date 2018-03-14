<?php if (!defined('THINK_PATH')) exit();?><html>

	<head>
		<meta charset="UTF-8">
		<title>我的佣金</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Public/newhome/css/withdraw.css" />

	</head>

	<body>
		<div class="infor">
			<div class="">
				<span class="myCommission">我的佣金 (元)</span>
				<span class="commissionRule">佣金规则</span>
				<span class="myMoney"><?php echo ($sum); ?></span>
			</div>
		</div>

		<div class="withdrawMoney">
			<ul class="mui-table-view">
				<p class="withBank"><span class="tx">提现到  </span>  
					<span class="bank" value="<?php echo ($bank["ID"]); ?>">
						<?php if($type == 1): echo ($bank["BankName"]); ?> (<?php echo ($bank["bankCard"]); ?>)
						<?php else: ?>
							点击添加银行卡信息<?php endif; ?>
					</span>
					<!--<span class="dy">&gt;</span>-->
					<span class="mui-icon mui-icon-forward"></span>
				</p>
				
				<li class="mui-table-view-cell">
					<!--<p>提现金额 </p>-->
					<span class="rmb">¥</span>
					<input type="text" class="putmoney"/>
					<button type="button" class="mui-btn mui-btn-warning mui-btn-outlined" id="withdrawButton">提现</button>
				</li>
			</ul>
		</div>
		<div style="padding: 10px;">
			收益明细
		</div>
		<div class="mx">
			<?php if(is_array($detail)): $i = 0; $__LIST__ = $detail;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pro): $mod = ($i % 2 );++$i;?><ul class="mui-table-view" style="font-size: 14px!important">
					<li class=" mui-media  mui-table-view-cell ">
						<input type="text" class="hid" value="<?php echo ($pro["TuierId"]); ?>" hidden="hidden"/>
						<!-- <img class="mui-media-object mui-pull-left" src="<?php echo ($pro["Slogo"]); ?>"> -->
						<div class="mui-media-body">
							<!-- <span class="titles"><?php echo ($pro["storename"]); ?></span> -->
							<?php if($pro["Type"] == 'add'): ?><span class="nums">佣金:  + <?php echo ($pro["Money"]); ?> 元</span>
							<?php else: ?>
								<span class="nums">提现:  - <?php echo ($pro["Money"]); ?> 元</span>
								<?php if($pro["Status"] == '0'): ?><span class="statu"> 待处理</span>
								<?php elseif($pro["Status"] == '1'): ?>
									<span class="statu"> 已完成</span>
								<?php elseif($pro["Status"] == '2'): ?>
									<span class="statu"> 已拒绝</span><?php endif; endif; ?>
							<span class="creatTime"> <?php echo ($pro["createdate"]); ?></span>
						</div>
					</li>
				</ul><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<script src="/Public/newhome/js/mui.min.js"></script>
		<script src="/Public/newadmin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/Public/JS/plugins/layer/layer.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		<script type="text/javascript">
			$(document).on("tap",".withBank",function() {
				var id = $(".bank").attr('value');
				location.href = "<?php echo U('Tuier/bankMessage');?>?id="+id;
			})
			$(document).on("tap",".commissionRule",function() {
				window.location.href = "<?php echo U('Tuier/rules');?>";
			})
			$(document).ready(function() {
				//点击提现按钮
				$("#withdrawButton").on("tap",function() {
					var TuierId = $(".hid").val();
					var money = $(".putmoney").val();
					$.ajax({
						url:"<?php echo U('Tuier/getMoney');?>",
						type:'post',
						data:'money='+money+'&TuierId='+TuierId,
						dataType:'json',
						success:function(msg) {
							if(money == ''){
								layer.msg("请填写提现金额");
							}else {
								if(msg.type == "success") {
									if(msg.bankmsg){
										var updateMoney = msg.money;
										var _html = "";
										var data = msg.ajaxMoney;
										$.each(data,function(index,item) {
											if(item.Type == 'add'){
												var status ='<span class="nums">佣金: + '+item.Money+'</span>';
												var style = '<span class="statu"> </span>';
											}else{
												var status = '<span class="nums">提现: - '+item.Money+'</span>';
												if (item.Status == '0') {
									            	var style = '<span class="statu"> 待处理</span>';
									        	}
									        	else if (item.Status == '1'){
								                	var style = '<span class="statu"> 已完成</span>';
								                }
								                else if(item.Status == '2') {
								                	var style = '<span class="statu"> 已拒绝</span>';
								                }
											}
											_html += '<div class="mx"><ul class="mui-table-view"><li class="mui-media mui-table-view-cell"><input type="text" class="hid" value='+item.TuierId+' hidden><div class="mui-media-body">'+status+style+'<span class="creatTime">'+item.createdate+'</span></div></li></ul></div>';
										});
										$(".mx").html(_html);
										$('.myMoney').html(updateMoney);
										$('.putmoney').val('');
									}else {
										layer.msg('操作失败');
									}
								}else {
										layer.msg("请先添加银行卡信息");
									
	//								layer.msg('提现金额超出总金额');
								}
							}
							
						}
					})
				})
			})
		</script>
	</body>

</html>