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
<link href="/Public/Admin/Admin/css/plugins/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Admin/Admin/css/my.css">
<script src="/Public/Admin/Admin/js/plugins/chosen/chosen.jquery.js"></script>
<style type="text/css">
	.input-group{
		display: block!important;
	}
</style>
<div class="row  wrapper  white-bg" style="margin:0 1%;padding-bottom:10%;padding-top:20px;">
	<div class="col-sm-12">
		<h5>结算账户信息设置</h5>
		<form action="" method='post' class='form form-horizontal'>
			<div class="form-group">
		    <label for="inputEmail3" class="col-sm-2 control-label">银行名称</label>
		    <!--<div class="col-sm-10">
		    	<input type="text" name="BankName" class="form-control" value="<?php echo ($binfo["BankName"]); ?>" required id="">
		    </div>-->
				<div class="col-sm-4">
					<select class="form-control" name="BankName">
						<option value="<?php echo ($binfo["BankName"]); ?>">请选择</option>
						<?php if($binfo['BankName'] == '中国银行' ): ?><option selected="selected">中国银行</option>
						<?php else: ?>
							<option>中国银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '中国农业银行' ): ?><option selected="selected">中国农业银行</option>
						<?php else: ?>
							<option>中国农业银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '中国工商银行' ): ?><option selected="selected">中国工商银行</option>
						<?php else: ?>
							<option>中国工商银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '中国建设银行' ): ?><option selected="selected">中国建设银行</option>
						<?php else: ?>
							<option>中国建设银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '交通银行' ): ?><option selected="selected">交通银行</option>
						<?php else: ?>
							<option>交通银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '招商银行' ): ?><option selected="selected">招商银行</option>
						<?php else: ?>
							<option>招商银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '平安银行' ): ?><option selected="selected">平安银行</option>
						<?php else: ?>
							<option>平安银行</option><?php endif; ?>
						<?php if($binfo['BankName'] == '光大银行' ): ?><option selected="selected">光大银行</option>
						<?php else: ?>
							<option>光大银行</option><?php endif; ?>
					</select>
				</div>
			</div>
		  <div class="form-group">
		  	<label class="col-sm-2 control-label">银行卡号</label>
		  	<div class="col-sm-4">
		  		<input type="text" name="IdCard" class="form-control" required id="" value="<?php echo ($binfo["IdCard"]); ?>">
		  	</div>
		  </div>
		  <div class="form-group">
		    <label for="" class="col-sm-2 control-label">户主</label>
		    <div class="col-sm-4">
		      <input type="text" class="form-control" name="IdName" required id="IdName" value="<?php echo ($binfo["IdName"]); ?>" placeholder="开户人名">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="" class="col-sm-2 control-label">手机号码</label>
		    <div class="col-sm-4">
		      <input type="text" class="form-control" name="phone" required id="phone" value="<?php echo ($binfo["tel"]); ?>" placeholder="手机号码">
		    </div>
		  </div>
			<div class="form-group">
				<label for="" class="col-sm-2 control-label ">验证码</label>
				<div class="col-sm-3 ">
					<input type="text" class="form-control" required id="code" placeholder="请填写验证码">
					<input type="text" class="hcode" hidden value="0">
				</div>
				<button type="button" class="btn btn-default get-code" id="J_getCode">获取验证码</button>
				<button class="btn btn-small reset-code" id="J_resetCode" style="display:none;"><span id="J_second">60</span>秒后重发</button>
			</div>
		  <div class="form-group" style="text-align:center;position: absolute;left: 400px">
		  	<button class="btn btn-primary btn-outline save" type="submit">保 存</button>
		  </div>
		</form>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
		    $(document).on('click','.get-code',function() {
		        getCode(this);
		        var phone = $('#phone').val();
                $.ajax({
                    url : 'sendCode',
                    type : 'post',
                    data : 'phone='+phone,
                    dataType : 'json',
                    success : function(msg) {
                        if(msg.status == 'success') {
                            layer.msg(msg.info);
                            $('.hcode').val(msg.code);
                        }
                    }
                })
			})

            $(document).on('click', '.save', function () {
                var code = $('#code').val();
                var hcode = $('.hcode').val();
                // console.log(code);
                // console.log(hcode);
                if(code != hcode) {
                    layer.msg('验证码不正确');
                    return false;
                }
                if(code == '') {
                    layer.msg('请填写正确的验证码');
                    return false;
                }else {
                    $.ajax({
                        url : 'sinfoset',
                        type : 'post',
                        data : 'code='+code,
                        dataType : 'json',
                        success : function(msg) {
							if(msg.status == 'success') {
							    layer.msg(msg.info);
							}else {
                                layer.msg(msg.info);
                                return false;
							}
                        }
                    })
                }
            })


		})

	</script>
	<script type="text/javascript">
        /*获取验证码*/
        var isPhone = 1;
        function getCode(e){
            checkPhone(); //验证手机号码
            if(isPhone){
                resetCode(); //倒计时
            }else{
                $('#phone').focus();
            }

        }
        //验证手机号码
        function checkPhone(){
            var phone = $('#phone').val();
            var pattern = /^1[0-9]{10}$/;
            isPhone = 1;
            if(phone == '') {
               layer.msg('请输入手机号码');
                isPhone = 0;
                return;
            }
            if(!pattern.test(phone)){
                layer.msg('请输入正确的手机号码');
                isPhone = 0;
                return;
            }
        }
        //倒计时
        function resetCode(){
            $('#J_getCode').hide();
            $('#J_second').html('120');
            $('#J_resetCode').show();
            var second = 120;
            var timer = null;
            timer = setInterval(function(){
                second -= 1;
                if(second >0 ){
                    $('#J_second').html(second);
                }else{
                    clearInterval(timer);
                    $('#J_getCode').show();
                    $('#J_resetCode').hide();
                }
            },1000);
        }
	</script>


</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>