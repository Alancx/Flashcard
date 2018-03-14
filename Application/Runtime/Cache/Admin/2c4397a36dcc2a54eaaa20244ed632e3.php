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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<style type="text/css">
	.tice{
		color:red;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			</div>
			<div class="ibox-content">
				<div class="col-lg-3 col-md-4 col-lg-offset-8 col-md-offset-8 pull-right" style="height:300px;border:0px solid red;position:absolute;top:5px;right:5px;">
					<div class="panel">
						<div class="panel-heading">
							<i class="fa fa-warning"></i> 提示信息
						</div>
						<div class="panel-body">
							<div class="alert alert-warning">
								?1、每消费1元送x积分 <br>
								?2、签到积分设置、例：第一次签到送10分，以后的7天内每天连续签到+1天 积分比前一天增加5分，连续签到7天以上每天送100积分，不再递增。此时 <kbd>签到积分设置</kbd>中依次填写 <kbd>10</kbd> <kbd>7</kbd> <kbd>5</kbd> <kbd>100</kbd> <br>								<b><span style="color:red;">*</span>标记的为必填项、其他为选填项</b>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="col-sm-12">
					<div class="ibox float-e-margins">
					<h5>登陆积分设置  ?1</h5>
							<form role="form" class="form-inline" id="login" method="post" action="">
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number" name="score" placeholder="每日首次登陆送?积分" id="score" class="form-control" value="<?php echo ($login["score"]); ?>">
								</div>
								<div class="checkbox m-l m-r-xs">
									<label class="i-checks">
										<input type="checkbox" name="switchs" <?php if($login['switchs'] == 2): ?>checked="checked"<?php endif; ?>><i></i> 开启登陆送积分</label>
									</div>
									<input type="hidden" name="type" value="login">
									<input type="hidden" name="id" value="<?php echo ($login["ID"]); ?>">
									<button class="btn btn-white" type="submit">保存设置</button>
								</form>
						</div>
					</div> -->
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
					<h5>消费积分设置 ?2</h5>
							<form role="form" class="form-inline" id="cons"  method="post" action="">
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number"  name="score" placeholder="每消费1元送?积分" id="cscore" class="form-control" value="<?php echo ($cons["score"]); ?>">
								</div>
								<div class="checkbox m-l m-r-xs">
									<label class="i-checks">
										<input type="checkbox" name="switchs" <?php if($cons['switchs'] == 2): ?>checked="checked"<?php endif; ?>><i></i> 开启消费送积分</label>
									</div>
									<input type="hidden" name="type" value="cons">
									<input type="hidden" name="id" value="<?php echo ($cons["ID"]); ?>">
									<button class="btn btn-white" type="submit">保存设置</button>
								</form>
						</div>
					</div>
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
					<h5>签到积分设置 ?3</h5>
							<form role="form" class="form-inline" id="sign"  method="post" action="">
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number" style="width:150px;"  name="score" placeholder="首次签到送？积分" id="sscore" class="form-control" value="<?php echo ($sign["score"]); ?>">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number" style="width:150px;" name="days" placeholder="连续签到S天数" id="days" class="form-control" value="<?php echo ($sign["days"]); ?>">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number" style="width:150px;" name="adds" placeholder="连续签到递增？分" id="adds" class="form-control" value="<?php echo ($sign["adds"]); ?>">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number" style="width:150px;" name="scores" placeholder="连续签S天以上送？分" id="scores" class="form-control" value="<?php echo ($sign["scores"]); ?>">
								</div>
								<div class="checkbox m-l m-r-xs">
									<label class="i-checks">
										<input type="checkbox" name="switchs" <?php if($sign['switchs'] == 2): ?>checked="checked"<?php endif; ?>><i></i> 开启签到送积分</label>
									</div>
									<input type="hidden" name="type" value="sign">
									<input type="hidden" name="id" value="<?php echo ($sign["ID"]); ?>">
									<button class="btn btn-white" type="submit">保存设置</button>
								</form>
						</div>
					</div>
<!-- 				<div class="col-sm-12">
					<div class="ibox float-e-margins">
					<h5>推广积分设置 ?4</h5>
							<form role="form" class="form-inline" id="extends"  method="post" action="">
								<div class="form-group	col-xs-4">
									<label for="exampleInputEmail2" class="sr-only">积分数</label>
									<input type="number" name="score" placeholder="推广注册一个会员送？积分" id="escore" class="form-control" style="width:100%;margin-left:-15px;margin-right:-15px;" value="<?php echo ($extends["score"]); ?>">
								</div>
								<div class="checkbox m-l m-r-xs">
									<label class="i-checks">
										<input type="checkbox" name="switchs" <?php if($extends['switchs'] == 2): ?>checked="checked"<?php endif; ?>><i></i> 开启推广送积分</label>
									</div>
									<input type="hidden" name="type" value="extends">
									<button class="btn btn-white" type="submit">保存设置</button>
								</form>
						</div>
					</div>
 -->				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	//验证
		$(document).ready(function(){
			$("#login").submit(function(){
				var score=$("#score").val();
				if (score) {
					return true;
				}else{
					art.dialog.alert('请填写积分数');
					return false;
				}
			});
			$("#cons").submit(function(){
				var cscore=$("#cscore").val();
				if (cscore) {
					return true;
				}else{
					art.dialog.alert('请填写积分数');
					return false;
				}
			});
			$("#extends").submit(function(){
				var escore=$("#escore").val();
				if (escore) {
					return true;
				}else{
					art.dialog.alert('请填写积分数');
					return false;
				}
			});
			$("#sign").submit(function(){
				var sscore=$("#sscore").val();
				var days=$("#days").val();
				var adds=$("#adds").val();
				var scores=$("#scores").val();
				if (!sscore) {
					art.dialog.alert('请填写首次签到积分数');
					return false;
				};
				if (!days) {
					art.dialog.alert('请填写连续签到天数');
					return false;
				};
				if (!adds) {
					art.dialog.alert('请填写递增分数');
					return false;
				};
				if (!scores) {
					art.dialog.alert('请填写连续签到'+days+'天以上送积分数');
					return false;
				}else{
					return true;
				}
			})
		})
	</script>
	</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>