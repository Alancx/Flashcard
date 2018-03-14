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
		
<style type="text/css">
	td,th{text-align: center;font-size: 10px}
	.active>a{font-weight: bold;color:red!important;}
	a{color:#999;}
	table{
		margin-top: 0px;
	}
	.btn_delps>label{
		background-color: #ff4200;
		border-radius: 3px;
		color: #ffffff;
	}
</style>
<ul class="nav nav-tabs">
    <li class="active" style="width:50%;text-align:center;"><a data-toggle="tab" href="#tab-8"> 配送查询</a>
    </li>
    <li class="" style="width:50%;text-align:center;"><a data-toggle="tab" href="#tab-9"> 审核处理</a>
    </li>
</ul>
<div class="tab-content ">
    <div id="tab-8" class="tab-pane active">
			<ul class="nav nav-tabs" style="border-top:1px solid #ccc;margin-top:0px;border-bottom:0px solid #ccc;">
			    <li class="active" style="width:50%;text-align:center;"><a style="padding:5px 5px;border-radius:0px;border:0px solid #ccc;" data-toggle="tab" href="#tab-18"> 配送中</a>
			    </li>
			    <li class="" style="width:50%;text-align:center;"><a style="padding:5px 5px;border-radius:0px;border:0px solid #ccc;" data-toggle="tab" href="#tab-19"> 已完成</a>
			    </li>
			</ul>
			<div class="tab-content ">
			    <div id="tab-18" class="tab-pane active">
			            <table class="table table-bordered table-condensed">
			            	<thead>
			            		<tr>
				            		<td>单号</td>
				            		<td>配送信息</td>
				            		<td>接单时间</td>
												<td>操作</td>
			            		</tr>
			            	</thead>
			            	<tbody id="tbody-18">
			            	<?php if(is_array($psing)): foreach($psing as $key=>$o): ?><tr>
			            			<td><?php echo ($o["OrderId"]); ?></td>
			            			<td><?php echo ($o["Psinfo"]); ?></td>
			            			<td><?php echo (substr($o["GetDate"],"5")); ?></td>
												<td class="btn_delps" style="width:70px;">
													<?php if($o['status'] == 0 ): ?><label onclick="delpsnew(this)" data-oid="<?php echo ($o["OrderId"]); ?>" style="padding:5px 3px 5px 3px;">重新派单</label>
														<?php else: ?> 配送中<br>[已提货]<?php endif; ?>
												</td>
			            		</tr><?php endforeach; endif; ?>
			            	</tbody>
			            	<tfoot>
			            		<tr>
			            			<td colspan="4" data-tbody='tbody-18' data-type='getpsing' class="getmore" data-page='<?php echo ($pagesize); ?>'>加载更多</td>
			            		</tr>
			            	</tfoot>
			            </table>
			    </div>
			    <div id="tab-19" class="tab-pane">
			            <table class="table table-bordered table-condensed">
			            	<thead>
			            		<tr>
				            		<td>单号</td>
				            		<td>配送信息</td>
				            		<td>配送时间</td>
			            		</tr>
			            	</thead>
			            	<tbody id="tbody-19">
			            	<?php if(is_array($psend)): foreach($psend as $key=>$ps): ?><tr>
			            			<td><?php echo ($ps["OrderId"]); ?></td>
			            			<td><?php echo ($ps["Psinfo"]); ?></td>
			            			<td><span style='color:orange'><?php echo (substr($ps["GetDate"],"5")); ?></span><br><span style='color:green'><?php echo (substr($ps["OverDate"],"5")); ?></span></td>
			            		</tr><?php endforeach; endif; ?>
			            	</tbody>
			            	<tfoot>
			            		<tr>
			            			<td colspan="3" data-tbody='tbody-19' data-type='getpsend' class="getmore" data-page='<?php echo ($pagesize); ?>'>加载更多</td>
			            		</tr>
			            	</tfoot>
			            </table>
			    </div>
			</div>
    </div>
    <div id="tab-9" class="tab-pane">
			<ul class="nav nav-tabs" style="border-top:1px solid #ccc;margin-top:0px;border-bottom:0px solid #ccc;">
			    <li class="active" style="width:50%;text-align:center;"><a style="padding:5px 5px;border-radius:0px;border:0px solid #ccc;" data-toggle="tab" href="#tab-28"> 待审核</a>
			    </li>
			    <li class="" style="width:50%;text-align:center;"><a style="padding:5px 5px;border-radius:0px;border:0px solid #ccc;" data-toggle="tab" href="#tab-29"> 已审核</a>
			    </li>
			</ul>
			<div class="tab-content ">
			    <div id="tab-28" class="tab-pane active">
			            <table class="table table-bordered table-condensed">
			            	<tbody id="tbody-28">
			            	<?php if(is_array($psping)): foreach($psping as $key=>$op): ?><tr>
			            			<td><img src="<?php echo ($op["HeadImg"]); ?>" alt="" style="width:30px;height:30px;"><br><?php echo ($op["TrueName"]); ?> <br> <?php echo ($op["Phone"]); ?><br><?php echo (substr($op["AskDate"],'5')); ?> </td>
			            			<td><img src="<?php echo ($op["IdImg"]); ?>" class="idimg" alt="" style="max-height:50px;max-width:80px;"> <br><?php echo ($op["IdCard"]); ?></td>
			            			<td><button class="btn btn-xs btn-success gook" data-id='<?php echo ($op["ID"]); ?>'>同意申请</button><br><br><button class="btn btn-xs btn-default ref" data-id='<?php echo ($op["ID"]); ?>'>拒绝申请</button></td>
			            		</tr><?php endforeach; endif; ?>
			            	</tbody>
			            	<tfoot>
			            		<tr>
			            			<td colspan="3" data-tbody='tbody-28' data-type='getpsping' class="getmore" data-page='<?php echo ($pagesize); ?>'>加载更多</td>
			            		</tr>
			            	</tfoot>
			            </table>
			    </div>
			    <div id="tab-29" class="tab-pane">
			            <table class="table table-bordered table-condensed">
			            	<tbody id="tbody-29">
			            	<?php if(is_array($pspend)): foreach($pspend as $key=>$os): ?><tr>
			            			<td><img src="<?php echo ($os["HeadImg"]); ?>" alt="" style="width:30px;height:30px;"> <br><?php echo ($os["TrueName"]); ?> <br> <?php echo ($os["Phone"]); ?><br><?php echo (substr($os["AskDate"],'5')); ?></td>
			            			<td><img src="<?php echo ($os["IdImg"]); ?>" class="idimg" alt="" style="max-height:50px;max-width:80px;"> <br><?php echo ($os["IdCard"]); ?></td>
			            			<td>已通过</td>
			            		</tr><?php endforeach; endif; ?>
			            	</tbody>
			            	<tfoot>
			            		<tr>
			            			<td colspan="3" data-tbody='tbody-29' data-type='getpspend' class="getmore" data-page='<?php echo ($pagesize); ?>'>加载更多</td>
			            		</tr>
			            	</tfoot>
			            </table>
			    </div>
			</div>
    </div>
</div>
<div style="text-align:center;padding-top:10%;width:100%;height:100%;position:absolute;left:0;top:0;background:rgba(0,0,0,.6);display:none" id="imgbox">
	<img src="" style="max-width:100%;max-height:100%" alt="" class="showimg">
</div>
<script type="text/javascript">
var pagesize=<?php echo ($pagesize); ?>;
$(document).ready(function(){
	$(document).on('click','.idimg',function(){
		var src=$(this).attr('src');
		$('.showimg').attr('src',src);
		$('#imgbox').show();
	})
	$('#imgbox').click(function(){
		$(this).hide();
	})
	$(document).on('click','.gook',function(){
		var _this=$(this);
		var id=_this.attr('data-id');
		$.ajax({
			url:"<?php echo U('User/AboutPs');?>",
			type:"post",
			data:"type=gook&id="+id,
			dataType:"json",
			success:function(msg){
				if (msg.status) {
					_this.parent().html('已审核');
				}else{
					alert('处理失败');
				}
			}
		})
	})
	$(document).on('click','.ref',function(){
		var _this=$(this);
		var id=_this.attr('data-id');
		$.ajax({
			url:"<?php echo U('User/AboutPs');?>",
			type:"post",
			data:"type=ref&id="+id,
			dataType:"json",
			success:function(msg){
				if (msg.status) {
					_this.parent().html('已拒绝');
				}else{
					alert('处理失败');
				}
			}
		})
	})
	$('.getmore').click(function(){
		var _this=$(this);
		var tid=$(this).attr('data-tbody');
		var page=$(this).attr('data-page');
		var type=$(this).attr('data-type');
		console.log(tid,page);
		$.ajax({
			url:"<?php echo U('User/AboutPs');?>",
			type:"post",
			data:"type="+type+"&page="+page,
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					var _html='';
					$.each(msg.data,function(index,item){
						if (type=='getpsing') {
							if (item.status=='0') {
								_html+="<tr> <td>"+item.OrderId+"</td> <td>"+item.Psinfo+"</td> <td>"+item.GetDate.substr(5)+"</td> <td class='btn_delps' style='width:70px;'><label onclick='delpsnew(this)' data-oid="+item.OrderId+" style='padding:5px 3px 5px 3px;'>重新派单</label></td> </tr>";
							} else {
								_html+="<tr> <td>"+item.OrderId+"</td> <td>"+item.Psinfo+"</td> <td>"+item.GetDate.substr(5)+"</td> <td class='btn_delps' style='width:70px;'>配送中<br>[已提货]</td> </tr>";
							}
						}else if (type=='getpsping') {
							_html+='<tr> <td><img src="'+item.HeadImg+'" alt="" style="width:30px;height:30px;"> <br>'+item.TrueName+' <br> '+item.Phone+'</td> <br>'+item.AskDate.substr(5)+' <td><img src="'+item.IdImg+'" class="idimg" alt="" style="max-height:50px;max-width:80px;"> <br>'+item.IdCard+'</td> <td><button class="btn btn-xs btn-success gook" data-id="'+item.ID+'">同意申请</button><br><br><button class="btn btn-xs btn-default ref" data-id="'+item.ID+'">拒绝申请</button></td> </tr>';
						}else if (type=='getpspend') {
							_html+='<tr> <td><img src="'+item.HeadImg+'" alt="" style="width:30px;height:30px;"> <br>'+item.TrueName+' <br> '+item.Phone+'<br>'+item.AskDate.substr(5)+'</td> <td><img src="'+item.IdImg+'" class="idimg" alt="" style="max-height:50px;max-width:80px;"> <br>'+item.IdCard+'</td> <td>已通过</td> </tr>';
						}else if (type=='getpsend'){
							_html+="<tr> <td>"+item.OrderId+"</td> <td>"+item.Psinfo+"</td> <td><span style='color:orange'>"+item.GetDate.substr(5)+"</span><br><span style='color:green'>"+item.OverDate.substr(5)+"</span></td> </tr>";

						}
					})
					$('#'+tid).append(_html);
					if (msg.info=='ok') {
						_this.html('加载更多').attr('data-page',parseInt(pagesize)+parseInt(page));
					}else{
						_this.html('没有更多了').attr('data-page',parseInt(pagesize)+parseInt(page)).removeClass().addClass('disabled');
					}
				}else{
					_this.html(msg.info).removeClass().addClass('disabled');
				}
			}
		})
	})
})
</script>
<script type="text/javascript">
function delpsnew(label){
	var tempbtn=$(label);
	var oid = $(label).attr('data-oid');
	$.ajax({
		url:"<?php echo U('User/delPs');?>",
		type:"post",
		data:"oid="+oid,
		dataType:"json",
		success:function(msg){
			if (msg.status=='true') {
				tempbtn.parent().parent().remove();
				tips('notice', '操作完成!', 1500, 'weui_icon_toast');
			}else{
				tips('notice', '操作失败!', 1500, 'weui_icon_notice');
			}
		}
	})
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