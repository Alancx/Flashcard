<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')) { echo ''; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>

<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
</style>
</head>
<body>
<div class="system-message" style="display:none;">
<?php if(isset($message)) {?>
<h1>:)</h1>
<p class="success"><?php echo($message); ?></p>
<?php }else{?>
<h1>:(</h1>
<p class="error"><?php echo($error); ?></p>
<?php }?>
<p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
</div>
<input type="hidden" name="msg" id="msg" value="<?php if (isset($message)) {echo $message;}else{echo $error;} ?>" />
<script type="text/javascript">
var text=$("#msg").val();
art.dialog.tips(text,2);
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
    var time = --wait.innerHTML;
    if(time <= 0) {
        location.href = href;
        clearInterval(interval);
    };
}, 1000);
})();
</script>
</body>
</html>