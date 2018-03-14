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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
	.form-group{margin-bottom: 10px!important;}
</style>
<div class="row  wrapper  white-bg" style="margin:0px;padding-bottom:10%;">
	<div class="ibox-title">
		<h5>推广人员管理</h5>
	</div>
	<div class="col-lg-12 col-md-12" style="border-bottom:1px solid #ccc;padding-bottom:10px;margin-bottom:20px;">
		<form action="" class="form-inline" method="post" id="search">
			<div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>姓名</span>
					<input type="text" name="TrueName" required id="TrueName" class="form-control">
				</div>
			</div>
			<!-- <div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>用户组</span>
					<select name="GroupId" required id="GroupId" class="form-control">
						<option value="">请选择</option>
						<?php if(is_array($groups)): foreach($groups as $key=>$gp): ?><option value="<?php echo ($gp["GroupId"]); ?>"><?php echo ($gp["GroupName"]); ?></option><?php endforeach; endif; ?>
					</select>
				</div>
			</div> -->
			<div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>账号</span>
					<input type="text" name="userName" required id="userName" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>密码</span>
					<input type="password" name="Password" required id="Password" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>确认密码</span>
					<input type="password" name="Repass" id="Repass" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class='input-group-addon'>邀请码</span>
					<input type="text" name="Invcoded" id="Invcoded" placeholder='选填' class="form-control">
				</div>
			</div>
			<div class="form-group">
			<input type="hidden" name="id" id="id">
			<button class="btn btn-primary btn-outline btn-md" type="submit">保存</button> <button class="btn btn-default btn-outline btn-md" type="button" id="import"><span class="glyphicon glyphicon-import"></span>导出全部推广人信息</button>
			</div>
		</form>

	</div>
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php if($employees['msg']): ?><h3><?php echo ($employees["msg"]); ?></h3>
		<?php else: ?>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>账号</th>
					<th>姓名</th>
					<th>邀请码</th>
					<th>最后登录时间</th>
					<th>当前状态</th>
					<th>会员等级</th>
					<th>推广人数量</th>
					<th>推广店数量</th>
					<th>操作</th>
				
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($users)): foreach($users as $key=>$emp): ?><tr>
						<td><?php echo ($emp["Account"]); ?></td>
						<td><?php echo ($emp["TrueName"]); ?></td>
						<td><?php echo ($emp["Invcode"]); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$emp["LastLoginDate"])); ?></td>
						<td class="status"><?php if($emp['IsCheck'] == '0'): ?>待审核<?php else: ?>已审核<?php endif; ?></td>
						<!-- <td class="level" data-id="<?php echo ($emp["ID"]); ?>"><?php if($emp['Level'] == '1'): ?>铜牌会员<?php elseif($emp['Level'] == '2'): ?>银牌会员<?php elseif($emp['Level'] == '3'): ?>金牌会员<?php endif; ?></td> -->
						<td><?php echo ($emp["LevelName"]); ?>&emsp;<button type="button" class="btn btn-xs btn-warning editTuier" data-toggle="modal" data-target="#editTuier" data-id="<?php echo ($emp["ID"]); ?>">修改</button></td>
						<td><button type="button" class="btn btn-primary btn-xs showpeople" data-toggle="modal" data-target="#myModal1" data-id="<?php echo ($emp["Invcode"]); ?>"><?php echo ($emp["num"]); ?></button>&emsp;<button class="btn btn-default btn-outline tuiernum" type="button" data-id="<?php echo ($emp["Invcode"]); ?>"><span class="glyphicon glyphicon-import"></span>导出</button></td>
						<td><button type="button" class="btn btn-primary btn-xs showstore" data-toggle="modal" data-target="#myModal2" data-id="<?php echo ($emp["Invcode"]); ?>"><?php echo ($emp["count"]); ?></button>&emsp;<button class="btn btn-default btn-outline shopnum" type="button" data-id="<?php echo ($emp["Invcode"]); ?>"><span class="glyphicon glyphicon-import"></span>导出</button></td>
						<td><?php if($emp['IsCheck'] == '0'): ?><button type="button" class="btn btn-primary btn-xs passcheck" data-toggle="modal" data-target="#myModal3" data-id='<?php echo ($emp["ID"]); ?>'>通过审核</button><?php endif; ?>&emsp;<button class="btn btn-xs btn-warning edit" data-id='<?php echo ($emp["ID"]); ?>'>编辑</button> &emsp; <button class="btn btn-xs btn-danger delete" data-id='<?php echo ($emp["ID"]); ?>'>删除</button></td>
					</tr><?php endforeach; endif; ?>
			</tbody>

		</table><?php endif; ?>
		<div style="text-align:right;"><?php echo ($page); ?></div>
	</div>
</div>
<!-- 推广人详情模态框 -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 800px">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">推广人详情</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>推广人姓名</th>
                <th>推广人账号</th>
                <th>邀请码</th>
                <th>推广人数量</th>
                <th>推广店面数量</th>
                <th>当前状态</th>
            </tr>
            </thead>
            <tbody class="stores">
            
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<!-- 推广店铺的详情模态框 -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 800px">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">推广店铺的详情</h4>
      </div>
      <div class="modal-body">
         <table class="table table-bordered">
            <thead>
            <tr>
                <th>店名称</th>
                <th>地址</th>
                <th>手机号</th>
                <th>店主</th>
                <th>创建时间</th>
                <th>总收入</th>
            </tr>
            </thead>
            <tbody class="peopleinfo">
           
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<!-- 会员审核详情模态框 -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">会员审核详情</h4>
      </div>
      <div class="modal-body">
      	<div style="text-align: center;" class="hy">
	        <label class="radio-inline">
	        		<input type="radio" name="Options" id="inlineRadio1" value="1" checked="checked"> 银牌会员
	        </label>
	        <label class="radio-inline">
	        	<input type="radio" name="Options" id="inlineRadio2" value="2"> 金牌会员
	        </label>
	        <label class="radio-inline">
	        	<input type="radio" name="Options" id="inlineRadio3" value="3"> 钻石会员
	        </label> 
	        <label class="radio-inline">
	        	<input type="radio" name="Options" id="inlineRadio3" value="4"> 资源合作
	        </label> 
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="saves" data-dismiss="modal">保存</button>
      </div>
    </div>
  </div>
</div>
<!-- 会员等级修改 -->
<div class="modal fade" id="editTuier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">会员等级修改详情</h4>
      </div>
      <div class="modal-body">
      	<div style="text-align: center;" class="hyedit">
	        
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="subSave" data-dismiss="modal">保存</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/Public/newadmin/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript">
var jsondata=<?php echo ($jsondata); ?>;
	$(document).ready(function(){
		// console.log(jsondata);
		$('.showorder').click(function(){
			art.dialog.open('<?php echo U('Admin/details');?>?id='+$(this).attr('data-member'),{width:800,height:600});
		})
		$('#search').submit(function(){
			var pass=$('#Password').val();
			var repa=$('#Repass').val();
			if ($('#id').val()) {
				if (pass || repa) {
					if (pass!=repa) {
						layer.msg('两次输入密码不一致');
						return false;
					}else{
						layer.msg('处理中...');
						return true;
					}
				}else{
					layer.msg('处理中...');
					return true;
				}
			}else{
				if (pass==repa && pass && repa) {
					layer.msg('处理中...');
					return true;
				}else{
					layer.alert('两次输入密码不一致');
					return false;
				}
			}
		})
		// 处理会员等级信息
		/*$(document).on("click",".edit",function() {
			//定义全局的变量给模态框使用
			 window.ids = $(this).attr('data-id');
			 console.log(ids);
			 var _html = "";
			 var status = "";
			$.each(jsondata,function(index,item) {
				// console.log(item.Level);
				if(item.ID == ids) {
					if(item.Level == '1') {
						 status1 = 'checked="checked"';
						 status2 = "";
						 status3 = "";
					}else if(item.Level == '2'){
						 status2 = 'checked="checked"';
						 status1 = "";
						 status3 = "";
					}else if(item.Level == '3'){
						 status3 = 'checked="checked"';
						 status1 = "";
						 status2 = "";
					}
					_html += '<label class="radio-inline"><input type="radio" name="Options" id="inlineRadio1" value="1" '+status1+'> 铜牌会员</label><label class="radio-inline"><input type="radio" name="Options" id="inlineRadio2" value="2" '+status2+'> 银牌会员</label><label class="radio-inline"><input type="radio" name="Options" id="inlineRadio3" value="3" '+status3+'> 金牌会员</label>';
				$('.hy').html(_html);
				}
			})
		})*/
		/*$(document).on("click","#saves",function() {
			var id = $('.passcheck').attr('data-id');
			var level = $('input:radio[name="Options"]:checked').val();//获取单选框中的值
			/*console.log(id);
			console.log(level);
			layer.confirm('确定要通过审核吗？',{
								btn:['确定','取消'],
								shade:false
							},function(){
								layer.msg('处理中...');
								$.ajax({
									url:"<?php echo U('Admin/checktuier');?>",
									type:"post",
									data:"id="+id+"&Level="+level,
									dataType:"json",
									success:function(msg){
										if (msg.status=='success') {
											layer.msg('处理成功');
											$('.passcheck').parent().parent().find('.status').html('已审核');
											$('.passcheck').remove();
										}else{
											layer.msg(msg.info);
										}
									}
								})
							})
			
		})*/
		$(document).on('click','.passcheck',function() {
			window.ids = $(this).attr('data-id');
			// console.log(ids);
		})
		$(document).on("click","#saves",function() {
			// var id = $('.passcheck').attr('data-id');
			var level= $('input:radio[name="Options"]:checked').val();//获取单选框中的值
			// console.log(ids);
			// console.log(level);
			$.ajax({
				url:"<?php echo U('Admin/checktuier');?>",
				type: 'post',
				data:'ID='+ids+'&Level='+level,
				dataType:'json',
				success:function(msg) {
					if(msg.status == "success") {
						window.location.reload();
					}else {
						layer.msg(msg.info);
					}
				}
			})
		})

		//保存修改的会员等级详情
		$(document).on("click",".editTuier",function() {
			window.id = $(this).attr('data-id');
			$.ajax({
				url: "<?php echo U('Admin/editTuier');?>",
				type: 'post',
				data : 'ID='+id,
				dataType : 'json',
				success : function(msg) {
					var _html = "";
					var check1 = "";
					var check2 = "";
					var check3 = "";
					var check4 = "";
					if(msg.status=="success") {
						var level = msg.level;
						if(level == '1') {
							 check1 = 'checked="checked"';
							 check2 = "";
							 check3 = "";
							 check4 = "";
						}else if(level == '2') {
							check2 = 'checked="checked"';
							check1 = "";
							check3 = "";
							check4 = "";
						}else if(level == '3') {
							check3 = 'checked="checked"';
							check2 = "";
							 check1 = "";
							 check4 = "";
						}else if(level == '4') {
							check4 = 'checked="checked"';
							check2 = "";
							check3 = "";
							check1 = "";
						}
						_html = '<label class="radio-inline"><input type="radio" name="Options" id="inlineRadio1" value="1" '+check1+'> 银牌会员</label><label class="radio-inline"><input type="radio" name="Options" id="inlineRadio2" value="2" '+check2+'> 金牌会员</label><label class="radio-inline"><input type="radio" name="Options" id="inlineRadio3" value="3" '+check3+'> 钻石会员</label><label class="radio-inline"><input type="radio" name="Options" id="inlineRadio3" value="4" '+check4+'> 资源合作</label> ';
						$(".hyedit").html(_html);

					}
				}
			})

		})
		$(document).on("click","#subSave",function() {
			var level= $('input:radio[name="Options"]:checked').val();//获取单选框中的值
			$.ajax({
				url:"<?php echo U('Admin/subSave');?>",
				type: 'post',
				data:'ID='+id+'&Level='+level,
				dataType:'json',
				success:function(msg) {
					if(msg.status == "success") {
						window.location.reload();
					}else {
						layer.msg(msg.info);
					}
				}
			})
		})
		
		$(document).on('click','.edit',function(){
			var id = $(this).attr('data-id');
			$.each(jsondata,function(index,item){
				if(item.ID == id) {
					$('#TrueName').val(item.TrueName);
					$('#userName').val(item.Account);
					$('#Invcoded').val(item.Invcoded);
					$('#id').val(item.ID);
					$('#Password').attr('required',false);
				}
			})
		})
		$(document).on('click','.delete',function(){
			var id=$(this).attr('data-id');
			var _this = $(this);
			layer.confirm('确定要删除人员吗？',{
				btn:['确定','取消'],
				shade:false
			},function(){
				layer.msg('处理中...');
				$.ajax({
					url: "<?php echo U('Admin/delAccount');?>",
					type: "post",
					data: "id="+id,
					dataType: "json",
					success:function(msg) {
						if(msg.type == "success") {
							layer.msg('处理成功');
							window.location.reload();
						}else {
							layer.msg(msg.info);
						}
					}
				})
			})
		})
		
		$(document).on('click','.showstore',function() {
			var _this = $(this);
			var id = _this.attr('data-id');
			$.ajax({
				url:"<?php echo U('Admin/storeDetail');?>",
				type:'post',
				data:'id='+id,
				dataType:"json",
				success:function(msg) {
					var data = msg.storeInfo;
					var _html = '';
					if(msg.type == "success") {
						$.each(data,function(index,item) {
							_html += "<tr><td>"+item.storename+"</td><td>"+item.province+item.city+item.area+item.addr+"</td><td>"+item.tel+"</td><th>"+item.TrueName+"</th><td>"+item.createdate+"</td><td>"+item.TotalMoney+"</td></tr>";
						})
					}else {
							_html = '<tr><td colspan="5">该用户暂无推广信息</td></tr>';
						}
					$(".peopleinfo").html(_html);
				}
			})
		})

		$(document).on('click','.showpeople',function() {
			var _this = $(this);
			var id = _this.attr('data-id');
			$.ajax({
				url:"<?php echo U('Admin/peopleDetail');?>",
				type:'post',
				data:'id='+id,
				dataType:"json",
				success:function(msg) {
					var data = msg.tuinfo;
					var _html = '';
					var status;
					if(msg.type == "success") {
						$.each(data,function(index,item) {
							if(item.IsCheck == '0') {
								status = "待审核";
							}else {
								status = "已审核";
							}
							_html += "<tr><td>"+item.TrueName+"</td><td>"+item.Account+"</td><th>"+item.Invcode+"</th><td>"+item.pcount+"</td><td>"+item.scount+"</td><td>"+status+"</td></tr>";
						})
					}else {
							_html = '<tr><td colspan="5">该用户暂无推广信息</td></tr>';
						}
					$(".stores").html(_html);
				}
			})
		})

		$(document).on("click","#import",function() {
			// art.dialog({time:3,content:"正在处理..."});
			art.dialog.tips("正在处理...",2);
			window.location.href = "<?php echo U('Admin/empTuier');?>";
		})
		$(document).on('click','.tuiernum',function(){
			var _this=$(this);
			var id = _this.attr('data-id');
			art.dialog.tips("正在处理...",2);
			window.location.href = "<?php echo U('Admin/tuiernum');?>?id="+id;
		})

		$(document).on('click','.shopnum',function(){
			var _this=$(this);
			var id = _this.attr('data-id');
			art.dialog.tips("正在处理...",2);
			window.location.href = "<?php echo U('Admin/shopnum');?>?id="+id;
			
		})







	})

</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>