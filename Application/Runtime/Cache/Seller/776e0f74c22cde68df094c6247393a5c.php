<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>徽记食品</title>
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
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/plugins/chosen/chosen.css">
<style type="text/css">
	.tice{
		color:red;
	}
	.spl .chosen-container{
		width: 200px;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			                            <div class="alert alert-warning" style="color:red;">
			                            已选卖的商品修改价格，双击价格框修改价格，点击输入框之外的空白区域保存价格
                           </div>

			</div>
			<div class="ibox-content">
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<h5>设置商品</h5>
						<form role="form" class="form" id="savebuy"  method="post" action="">
							<div class="form-group">
								<label for="exampleInputPassword2" class="sr-only">选择商品</label>
								<select name="ProId" id="chosen" class="form-control" style="" value="">
									<option value="-1">请选择商品</option>
									<?php if(is_array($pros)): foreach($pros as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
								</select>
							</div>
							<div class="form-group attrs" >
							</div>
							<button class="btn btn-primary btn-outline"  type="submit">保存设置</button>
						</form>
					</div>
				</div>
				<div class="col-sm-12 col-md-12">
					<table class="table bordered">
						<thead>
							<tr>
								<td>商品名称</td>
								<td>规格/售价</td>
								<td>操作</td>
							</tr>
						</thead>
						<tbody>
						<?php if(is_array($ppss)): foreach($ppss as $key=>$li): ?><tr>
								<td><img src="<?php echo ($PICURL); echo ($li["ProLogoImg"]); ?>" style="width:100px;height:100px;" alt=""> <br><?php echo ($li["ProName"]); ?> </td>
								<td>
									<?php if(is_array($li["sons"])): foreach($li["sons"] as $key=>$son): echo ($son["Spec"]); ?>&emsp;/&emsp;￥：<input type="text" class="editprice" data-price='<?php echo ($son["Price"]); ?>' name="" value='<?php echo ($son["Price"]); ?>' readonly="readonly" id="<?php echo ($son["ProIdCard"]); ?>"> <br><?php endforeach; endif; ?>
								</td>
								<td><button class="btn btn-danger btn-xs clelimit" data-pid='<?php echo ($li["ProId"]); ?>'>删除</button>&emsp;&emsp;
								<?php if($li['ProType'] == 'new'): ?><button class="btn btn-primary btn-xs setnew" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='0'>取消新品</button>&emsp;&emsp;<button class="btn btn-xs btn-warning sethot" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='1'>设为热卖</button></td>
								<?php elseif($li['ProType'] == 'hot'): ?>
								<button class="btn btn-primary btn-xs setnew" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='1'>设为新品</button>&emsp;&emsp;<button class="btn btn-xs btn-warning sethot" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='0'>取消热卖</button></td>
								<?php elseif($li['ProType'] == 'all'): ?>
								<button class="btn btn-primary btn-xs setnew" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='0'>取消新品</button>&emsp;&emsp;<button class="btn btn-xs btn-warning sethot" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='0'>取消热卖</button></td>
								<?php else: ?>
								<button class="btn btn-primary btn-xs setnew" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='1'>设为新品</button>&emsp;&emsp;<button class="btn btn-xs btn-warning sethot" data-pid='<?php echo ($li["ProId"]); ?>' data-statu='1'>设为热卖</button></td><?php endif; ?>
							</tr><?php endforeach; endif; ?>
						</tbody>
					</table>
					<?php echo ($page); ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
var sondata=<?php echo ($prosons); ?>;
$(document).ready(function(){
	$('#chosen').chosen();
	$('#chosen').change(function(){
		// art.dialog.tips($(this).val());
		var pid=$(this).val();
		$.each(sondata,function(index,item){
			if (index==pid) {
				var _html='';
				console.log(item);
				$.each(item,function(key,value){
					// console.log(value);
					var tprice=parseFloat(value.Price);
					_html+='规格：'+value.Spec+'&emsp;平台价格：'+tprice.toFixed(2)+'&emsp;售价：<input class="prices" type="text" name="Prices[]" id="" value="" /><input class="proidcards" type="hidden" name="ProIdCards[]" value="'+value.ProIdCard+'" /><br>';
				})
				$('.attrs').html(_html);
			};
		})
	})
	$('#savebuy').submit(function(){
		var pid=$('#chosen').val();
		var prices=$('.prices');
		var pres=true;
		$.each(prices,function(index,item){
			if (!$(item).val()) {
				pres=false;
			};
		})
		if (pres && pid!='-1') {
			return true;
		}else{
			art.dialog.tips('请完善信息');
			return false;
		}
	})
	$('.clelimit').click(function(){
		var _this=$(this);
		var pid=_this.attr('data-pid');
		art.dialog.confirm('确定要删除此商品的价格设置吗？(删除此商品所有属性价格设置)',function(){
			window.location.href="<?php echo U('Products/delmerpro');?>?pid="+pid;
		})
	})

	$('.setnew').click(function(){
		var _this=$(this);
		var pid=_this.attr('data-pid');
		var statu=_this.attr('data-statu');
		$.ajax({
			url:"<?php echo U('Products/showHome');?>",
			type:"post",
			data:"type=new&pid="+pid+"&statu="+statu,
			dataType:"text",
			success:function(msg){
				if (msg=='success') {
					if (statu=='0') {
						_this.attr('data-statu',1).html('设为新品');
					}else{
						_this.attr('data-statu',0).html('取消新品');
					}
				}else{
					art.dialog.tips('设置失败');
				}
			}
		})
	})
	$('.sethot').click(function(){
		var _this=$(this);
		var pid=_this.attr('data-pid');
		var statu=_this.attr('data-statu');
		$.ajax({
			url:"<?php echo U('Products/showHome');?>",
			type:"post",
			data:"type=hot&pid="+pid+"&statu="+statu,
			dataType:"text",
			success:function(msg){
				if (msg=='success') {
					if (statu=='0') {
						_this.attr('data-statu',1).html('设为热卖');
					}else{
						_this.attr('data-statu',0).html('取消热卖');
					}
				}else{
					art.dialog.tips('设置失败');
				}
			}
		})
	})
	$('.editprice').dblclick(function(){
		$(this).attr('readonly',false).focus();
	})
	$('.editprice').blur(function(){
		var _this=$(this);
		if (!_this.attr('readonly')) {
			var sprice=_this.attr('data-price');
			var id=_this.attr('id');
			art.dialog.confirm('是否要保存此商品属性的选卖价格？',function(){
				var price=parseFloat(_this.val());
				if (price) {
					$.ajax({
						url:"<?php echo U('Products/editmerprice');?>",
						type:"post",
						data:"pid="+id+"&price="+price,
						dataType:"json",
						success:function(msg){
							if (msg.status=='success') {
								art.dialog.tips('修改成功');
								_this.attr('readonly',true).val(price).attr('data-price',price);
							}else{
								art.dialog.tips('修改失败');
								_this.attr('readonly',true).val(sprice);
							}
						}
					})
				}else{
					art.dialog.tips('请填写价格');
				}
			},function(){
				_this.attr('readonly',true).val(sprice);
			})
		}
	})
	// $('.editprice').click(function(){
	// 	var _this=$(this);
	// 	var id=_this.attr('id');
	// 	var price=_this.val()
	// })
})
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>