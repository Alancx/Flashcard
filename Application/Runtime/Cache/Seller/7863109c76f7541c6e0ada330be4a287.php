<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>店铺收银</title>
	<script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>  
	<link href="/Public/Admin/Admin/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
	<span class='glyphicon glyphicon-fullscreen pull-right' style="font-size:1.2em;position:absolute;right:10px;" id='fullscreen'></span>
	<div class="row" style="text-align:center">
		<button id='gocashier' class="btn btn-default">自助结算</button>
	</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		var width=window.screen.width;
		var height=parseInt(window.screen.height)-60;
		$('#gocashier').click(function(){
			window.open("<?php echo U('OfflineCashier/cashier');?>?token=<?php echo ($token); ?>&stoken=<?php echo ($stoken); ?>&sid=<?php echo ($storeid); ?>",'','height='+height+',width='+width+',toolbar=no,resizable=no,location=no,status=no,top=0,left=0');
		})
		$('#fullscreen').click(function(){
			$(this).hide();
			requestFullScreen();
		})
	})
	function requestFullScreen() {
		var de = document.documentElement;
		if (de.requestFullscreen) {
			de.requestFullscreen();
		} else if (de.mozRequestFullScreen) {
			de.mozRequestFullScreen();
		} else if (de.webkitRequestFullScreen) {
			de.webkitRequestFullScreen();
		}
	}
</script>
</html>