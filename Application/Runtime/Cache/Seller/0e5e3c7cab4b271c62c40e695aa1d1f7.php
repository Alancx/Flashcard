<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>APP消息推送</title>
    <link href="/Public/newadmin/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/Public/newadmin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/newadmin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/newadmin/css/animate.min.css" rel="stylesheet">
    <link href="/Public/newadmin/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/Public/newadmin/css/style.min.css?v=4.0.0" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Public/newadmin/css/plugins/webuploader/webuploader.css">
    <link rel="stylesheet" type="text/css" href="/Public/newadmin/css/demo/webuploader-demo.min.css?v=1">
    <script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/ueditor.config.js?v=11"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/ueditor.all.min.js"> </script>
    <style type="text/css">
    .upimg,.showimg{
        cursor: pointer;
    }
    .notice{
        position: fixed;
        top: 80px;
        right: 30px;
    }
    .spansure{
    	cursor: pointer;
    }
    </style>
</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>规则设置</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-8">
							<form class="form" method="post">
								<div class="form-group">
									<div class="input-group">
										<span class='input-group-addon'>选择商品</span>
										<select name="ProId" required id="ProId" class="form-control">
											<option value="">请选择商品</option>
											<?php if(is_array($prolists)): foreach($prolists as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>"><?php echo ($pro["ProName"]); ?> ￥ <?php echo ($pro["Price"]); ?></option><?php endforeach; endif; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<span class='input-group-addon'>轮次级别</span>
										<input type="number" name="Level" required id="Level" min="1" max="5" placeholder='1-5(含)之间' class="form-control">
										<span class="input-group-addon spansure">确定</span>
									</div>
								</div>
								<div class="Levelinfo">
									
								</div>
								<div class="form-group btn_box" style="display:none;">
								<input type="hidden" name="id" id="id">
									<button class="btn btn-primary btn-outline sendone" type="submit"> 保 存 </button>
								</div>
							</form>
							</div>
						</div>
					</div>
				</div>
			</div>
				<div class="col-sm-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>规则列表</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-12">
							<table class="table table-bordered list_box">
								<thead>
									<tr>
										<th>名称</th>
										<th>等级</th>
										<th>Lv1</th>
										<th>Lv2</th>
										<th>Lv3</th>
										<th>Lv4</th>
										<th>Lv5</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($haslist)): foreach($haslist as $key=>$hl): ?><tr>
										<td><?php echo ($hl["ProName"]); ?> ￥ <?php echo ($hl["Price"]); ?></td>
										<td><?php echo ($hl["Level"]); ?></td>
										<td><?php echo ($hl["Lv1"]); ?></td>
										<td><?php echo ($hl["Lv2"]); ?></td>
										<td><?php echo ($hl["Lv3"]); ?></td>
										<td><?php echo ($hl["Lv4"]); ?></td>
										<td><?php echo ($hl["Lv5"]); ?></td>
										<td><button class="btn btn-xs btn-outline btn-warning edit" data-id='<?php echo ($hl["ID"]); ?>'>编辑</button>&emsp;<button class="btn btn-xs btn-outline btn-danger delete" data-id='<?php echo ($hl["ID"]); ?>' data-pid='<?php echo ($hl["ProId"]); ?>'>删除</button></td>
									</tr><?php endforeach; endif; ?>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="/Public/newadmin/js/jquery.min.js?v=2.1.4"></script>
	<script src="/Public/newadmin/js/bootstrap.min.js?v=3.3.5"></script>
	<script src="/Public/newadmin/js/content.min.js?v=1.0.0"></script>
	<script src="/Public/newadmin/js/plugins/iCheck/icheck.min.js"></script>
	<script src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/newadmin/js/plugins/chosen/chosen.jquery.js"></script>
    <script src="/Public/newadmin/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/Public/newadmin/js/plugins/dataTables/dataTables.bootstrap.js"></script>
	<script type="text/javascript">
	var hasstr="<?php echo ($hasstr); ?>";
	var jsondata=<?php echo ($jsondata); ?>;
	$(document).ready(function(){
		$('.list_box').dataTable();
		$('#ProId').chosen();
		$(document).on('click','.spansure',function(){
			var num=$('#Level').val();
			if (num>0 && num<=5) {
				var _html=''
				for (var i = 1; i <= num; i++) {
					_html+='<div class="form-group"> <div class="input-group"> <span class="input-group-addon">第'+i+'次</span> <input type="text" required name="Lv'+i+'" id="Lv'+i+'" placeholder="请填写第'+i+'次吃的价格" class="form-control">  <span class="input-group-addon">元</span></div> </div>';
				};
				// layer.alert(_html);
				$('.Levelinfo').html(_html);
				$('.btn_box').show();
			}else{
				layer.msg('错误级别');
				$('#Level').focus();
				$('.btn_box').hide();
			}
		})
		$(document).on('change','#ProId',function(){
			var _pid=$(this).val();
			if (hasstr.indexOf(_pid)>0) {
				layer.msg('该商品已设置规则');
				$('#ProId').val('').trigger('chosen:updated');
			}
		})
		$(document).on('click','.delete',function(){
			var _this=$(this);
			layer.confirm('确定要删除此条优惠规则吗？',{
				btn:['确定','取消'],
				shade:false
			},function(){
				layer.msg('处理中...')
				$.ajax({
					type:"post",
					url:"<?php echo U('Activity/execeatmore');?>",
					data:{"type":'del','id':_this.attr('data-id')},
					dataType:"json",
					success:function(msg){
						if (msg.status=='success') {
							layer.msg('已删除');
							hasstr=hasstr.replace(_this.attr('data-pid'),'');
							_this.parent().parent().remove();
						}else{
							layer.msg(msg.info);
						}
					}
				})
			})
		})
		$(document).on('click','.edit',function(){
			var _this=$(this);
			$.each(jsondata,function(index,item){
				if (item.ID==_this.attr('data-id')) {
					$('#Level').val(item.Level);
					$('.spansure').click();
					$('#Lv1').val(item.Lv1);
					$('#Lv2').val(item.Lv2);
					$('#Lv3').val(item.Lv3);
					$('#Lv4').val(item.Lv4);
					$('#Lv5').val(item.Lv5);
					$('#ProId').val(item.ProId).trigger('chosen:updated');
					$('#id').val(item.ID);
				};
			})
		})
	})
	</script>
</body>

</html>