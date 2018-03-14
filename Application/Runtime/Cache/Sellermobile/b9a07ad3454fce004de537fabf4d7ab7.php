<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>结算统计</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="/Public/sellermobile/CSS/jiesuantongji.css?v=2.9" />
		<script type="text/javascript" src="/Public/sellermobile/JS/jquery.min.js"></script>
		<script type="text/javascript" src="/Public/sellermobile/JS/jiesuantongji.js"></script>
	</head>
	<body>
		<!--页头-->
		<div class="jstj-yetou">
			<div class="yetou-lishi">
				<span>历史总进账：</span>
				<span><?php echo ($MoneyInfo["TotalMoney"]); ?><small> 元</small></span>
			</div>
			<div class="yetou-keti">
				<span>可提余额：</span>
				<span><b id='y_money'><?php echo ($MoneyInfo["Money"]); ?></b><small> 元</small></span>
				<div class="" style="display:none">
					<img src="/Public/sellermobile/icon/wenhao.png" />
				</div>
			</div>
			<div class="yetou-shenqing">
				<small>￥: </small>
				<input type="text" name="" id="get_money" value=""  placeholder="填写提现金额" <?php if($MoneyInfo['Money'] > 0): else: ?>readonly='readonly'<?php endif; ?> />
				<span id='get_btn'>申请提现</span>
			</div>
		</div>
		<!--结算明细  申请记录-->
		<ul class="jstj-tab">
			<li><span>结算明细</span></li>
			<li><span>申请记录</span></li>
		</ul>
		<div class="tabnav"></div>
		<hr style="border: none; height: 10px; background: #eee; padding: 0; margin: 0;" />

		<!--结算明细-->
		<div class="jsmingxi" data-oo = "tab">
			<ul class="mingxi">
				<li>
					<span>时间</span>
				</li>
				<li>结算详情</li>
				<li>
					<span class='clall'>全部</span>
					<span>
						<small class="shouru">收入</small>&nbsp;&nbsp; /&nbsp;&nbsp; <small class="zhichu">支出</small>
					</span>
				</li>
			</ul>
		<hr style="border: none; height: 1px; background: #eee; padding: 0; margin: 0;" />
		<div class="cutlist">
			<?php if(is_array($cutlist)): foreach($cutlist as $key=>$cl): ?><ul class="lis">
					<li><?php echo date('Y.m.d H:i',strtotime($cl['CreateDate'])); ?></li>
					<li><?php echo ($cl["Useage"]); ?></li>
					<li><?php echo ($cl["Type"]); echo ($cl["Money"]); ?></li>
				</ul><?php endforeach; endif; ?>
		</div>
		</div>
		<!--申请记录-->
		<div class="sqjilu" data-oo = "tab" hidden="">
			<ul class="jilu">
				<li>
					<span>时间</span>
				</li>
				<li>申请提现金额</li>
				<li class="zhuangtai">
					<span>全部</span>
					<img src="/Public/sellermobile/icon/boo.png"  class="boo"/>
				</li>
			</ul>
			<ul class="zhuangtaiul">
				<li class='sqall'>全部</li>
				<li class='sqing'>待处理</li>
				<li class='sqend'>已处理</li>
			</ul>
		<hr style="border: none; height: 1px; background: #eee; padding: 0; margin: 0;" />
		<div class="sqlist">
			<?php if(is_array($sqlist)): foreach($sqlist as $key=>$sl): ?><ul class="jiluli">
					<li><?php echo date('Y.m.d H:i',strtotime($sl['CreateDate'])); ?></li>
					<li><?php echo ($sl["Money"]); ?></li>
					<li><?php echo ($sl["Status"]); ?></li>
				</ul><?php endforeach; endif; ?>
		</div>
		</div>
	</body>
<script type="text/javascript">
var nowmoney=<?php echo ($MoneyInfo["Money"]); ?>;
	$(document).ready(function(){
		$('.clall').click(function(){
			getdata('cutall');
		})
		$('.shouru').click(function(){
			getdata('getmoney');
		})
		$('.zhichu').click(function(){
			getdata('outmoney');
		})
		$('.sqall').click(function(){getdata('sqall')});
		$('.sqing').click(function(){getdata('sqing')});
		$('.sqend').click(function(){getdata('sqend')});
		$('#get_btn').click(function(){
			if (nowmoney>0) {
				var get_money=$('#get_money').val();
				if (get_money) {
					if (get_money<=nowmoney) {
						$.ajax({
							url:"<?php echo U('Staff/getmoney');?>",
							type:"post",
							data:"money="+get_money,
							dataType:"json",
							success:function(msg){
								if (msg.status=='success') {
									nowmoney=(parseFloat($('#y_money').html())-parseFloat(get_money)).toFixed(2);
									$('#y_money').html(nowmoney);
									var data=msg.data;
				                    // $('.cutlist').prepend('<ul><li>'+data.CreateDate+'</li><li>账户提现</li><li>-'+data.Money+'</li></ul>');
				                    $('.sqlist').prepend('<ul class="jiluli"><li>'+data.CreateDate+'</li><li>'+data.Money+'</li><li>'+data.Status+'</li></ul>');
				                    $('#get_money').val('');
								}else{
									alert(msg.info);
								}
							}
						})
					}else{
						alert('余额不足');
					}
				}else{
					alert('请输入提现金额');
				}
			}else{
				alert('暂无可提现余额');
			}
		})
	})

	function getdata(type){
		$.ajax({
			url:"<?php echo U('Staff/getdata');?>",
			type:"post",
			data:"type="+type,
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					var data=msg.data;
					var _html='';
					if (type=='cutall' || type=='getmoney' || type=='outmoney') {
						$.each(data,function(index,item){
							_html+='<ul class="lis"><li>'+item.CreateDate+'</li><li>'+item.Useage+'</li><li>'+item.Type+item.Money+'</li></ul>'
						})
						$('.cutlist').html(_html);
					}else if (type=='sqall' || type=='sqing' || type=='sqend') {
						$.each(data,function(index,item){
							_html+='<ul class="jiluli"><li>'+item.CreateDate+'</li><li>'+item.Money+'</li><li>'+item.Status+'</li></ul>'
						})
						$('.sqlist').html(_html);
					};
				}else{
					alert('暂无数据');
				}
			}
		})
	}
</script>
</html>