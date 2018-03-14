<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>光盘客</title>
    <meta name="keywords" content="徽记食品">
    <meta name="description" content="徽记食品">
    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    <script src="/Public/Admin/Admin/js/hplus.js"></script>

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<style type="text/css">
	.card{
		width: 200px;
		height: 110px;
		border: 1px solid #ccc;
		border-radius: 5px;
		margin:auto;
		position: relative;
		background: linear-gradient(to left,#fff,#999);
		box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
		color: black;
	}
	.left-card{
		margin-left: 20px;
		margin-bottom: 20px;
		float: left;
		/*padding-left: 20px;*/
		padding-top: 15px;
		/*padding-bottom: 15px;*/
		width: 200px;
		height: 110px;
		border: 1px solid #ccc;
		border-radius: 5px;
		position: relative;
		background: linear-gradient(to left,#fff,#999);
		box-shadow: 2px 2px 2px rgba(0,0,0,0.7);
		color: black;
	}
	.card_title{
		text-align: center;
		font-size: 1.2em;
		height: 20px;
		margin-bottom: 15px;
		margin-top: 10px;
		color: black;
	}
	.card_rules{
		font-size: .6em;
		line-height: 32px;
		text-align: center;
		color: black;
	}
	.checked .card_title,.checked .card_rules{
		color: #fff;
	}
	.card_bot{
		position: absolute;
		width: 100%;
		left: 0px;
		bottom: 0px;
		height: 10px;    
		text-align: center;
		font-size: .8em;
		color: black;
	}
	.vip{
		position: absolute;
		right: 5px;
		top: 5px;
		color: #ccc;
		font-size: 1.5em;
	}
	.checked{
		background: linear-gradient(to left,#FF0000,#000000);
		box-shadow: 15px 15px 15px rgba(0,0,0,0.7);
		-webkit-transition: all .5s;
		-moz-transition: all .5s;
		-ms-transition: all .5s;
		-o-transition: all .5s;
		transition: all .5s;

	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<div class="alert alert-warning" style="color:red;">
					1、优惠券管理
				</div>
			</div>
			<div class="ibox-content">
				<div class='row'>
					<table class='table table-hover'>
						<thead>
							<tr>
								<th>#</th>
								<th>#</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>分享人红包</td>
								<td class="Forsharer"><?php if($Forsharer): ?><div class='card'>
									<span class='vip'>VIP</span>
									<div class='card_title'><?php echo ($Forsharer["CouponName"]); ?></div>
									<div class='card_rules'>
											下单立减<?php echo ($Forsharer["Rules"]); ?>元
									</div>
								</div><?php else: ?>暂无<?php endif; ?></td>
								<td><button class="btn btn-xs btn-warning btn-outline change" data-id="<?php echo ($Forsharer["CouponId"]); ?>" data-type="Forsharer" data-toggle="modal" data-target="#changcard">变更</button>&emsp;<button class="btn btn-xs btn-danger btn-outline delete" data-type="Forsharer" data-id="<?php echo ($Forsharer["CouponId"]); ?>">删除</button></td>
							</tr>
							<tr>
								<td>分享红包</td>
								<td class="Forshare"><?php if($Forshare): ?><div class='card'>
									<span class='vip'>VIP</span>
									<div class='card_title'><?php echo ($Forshare["CouponName"]); ?></div>
									<div class='card_rules'>
											下单立减<?php echo ($Forshare["Rules"]); ?>元
									</div>
								</div><?php else: ?>暂无<?php endif; ?></td>
								<td><button class="btn btn-xs btn-warning btn-outline change" data-id="<?php echo ($Forshare["CouponId"]); ?>" data-type="Forshare" data-toggle="modal" data-target="#changcard">变更</button>&emsp;<button class="btn btn-xs btn-danger btn-outline delete" data-type="Forshare" data-id="<?php echo ($Forshare["CouponId"]); ?>">删除</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal fade" id="changcard" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-md" style="width:720px;">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 15px;">
				选择卡券 <span class='notice' style='color:red;font-weight:bold'></span>
				<button type="button" class="close" data-dismiss="modal" id="lbclo">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body" style="padding:15px">
				

			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-w-m btn-success input-sm" data-type='' id="btn_messagess">保存设置</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(document).on('click','.change',function(){
		var _this=$(this);
		layer.msg('加载中...');
		$.ajax({
			url:"<?php echo U('Activity/getcoupons');?>",
			type:"post",
			data:"type=share",
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					var _html='';
					$.each(msg.data,function(index,item){
						var rs=item.Rules.split('/');
						if (item.CouponId==_this.attr('data-id')) {
							_html+='<div class="left-card checked" data-id="'+item.CouponId+'"> <span class="vip">VIP</span> <div class="card_title">'+item.CouponName+'</div> <div class="card_rules">  订单满 '+rs[0]+' 立减  '+rs[1]+'元 </div> </div>';
						}else{
							_html+='<div class="left-card" data-id="'+item.CouponId+'"> <span class="vip">VIP</span> <div class="card_title">'+item.CouponName+'</div> <div class="card_rules">  订单满 '+rs[0]+' 立减  '+rs[1]+'元 </div> </div>';
						}
					});
					_html+='<div style="clear:both"></div>';
					$('.modal-body').html(_html);
					$('#btn_messagess').attr('data-type',_this.attr('data-type'));
				}else{
					layer.msg(msg.info);
					$('.close').click();
				}
			}
		})
	})
	$(document).on('click','.left-card',function(){
		var _this=$(this);
		$('.left-card').removeClass('checked');
		_this.addClass('checked');

	})

	$(document).on('click','.delete',function(){
		var _this=$(this);
		layer.confirm('确定要取消分享红包吗？',{
			btn:['确定','取消'],
			shade:false
		},function(){
			layer.msg('处理中');
			$.ajax({
				url:"<?php echo U('Activity/shareredpaper');?>",
				type:"post",
				data:"type=del&s="+_this.attr('data-type'),
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						layer.msg('处理成功');
						$('.'+_this.attr('data-type')).html('暂无');
						_this.parent().children('.change').attr('data-id','');
					}else{
						layer.msg(msg.info);
					}
				}
			})
		})
	})

	$(document).on('click','#btn_messagess',function(){
		if ($('.checked').length>0) {
			var _this=$(this);
			var type=_this.attr('data-type');
			var cid=$('.checked').attr('data-id');
			var _html=$('.checked');
			_this.addClass('disabled').html('处理中...');
			$.ajax({
				url:"<?php echo U('Activity/shareredpaper');?>",
				type:"post",
				data:{
					type:type,
					cid:cid
				},
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						layer.msg('保存成功');
						$('.close').click();					
						$('.'+type).html(_html);
						$('.'+type).children('.left-card').attr('class','card');
						$('.'+type).parent().find('.change').attr('data-id',cid);
					}else{
						layer.msg(msg.info);
					}
					_this.removeClass('disabled').html('保存设置');
				}
			})
		}else{
			layer.msg('未选择卡券');
		}
	})
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>