<?php
if (isset($_GET['dir'])){ //设置文件目录
$basedir=$_GET['dir'];
}else{
}
$basedir = 'D:\test\Home\Application\Admin\Controller';
$auto = 1;
checkdir($basedir);
function checkdir($basedir){
if ($dh = opendir($basedir)) {
while (($file = readdir($dh)) !== false) {
if ($file != '.' && $file != '..'){
if (!is_dir($basedir."/".$file)) {
echo "filename: $basedir/$file ".checkBOM("$basedir/$file")." <br>";
}else{
$dirname = $basedir."/".$file;
checkdir($dirname);
}
}
}
closedir($dh);
}
}
function checkBOM ($filename) {
global $auto;
$contents = file_get_contents($filename);
$charset[1] = substr($contents, 0, 1);
$charset[2] = substr($contents, 1, 1);
$charset[3] = substr($contents, 2, 1);
if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
if ($auto == 1) {
$rest = substr($contents, 3);
rewrite ($filename, $rest);
return ("<font color=red>BOM found, automatically removed._</font>");
} else {
return ("<font color=red>BOM found.</font>");
}
}
else return ("BOM Not Found.");
}
function rewrite ($filename, $data) {
$filenum = fopen($filename, "w");
flock($filenum, LOCK_EX);
fwrite($filenum, $data);
fclose($filenum);
}
// echo "<pre>";
// var_dump($_SERVER);exit();
// if ($_GET['code']) {
// 	$code=$_GET['code'];
// 	$resinfo=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxb8ba6e24cc7558a1&secret=de8d0191e6268631ee8de3037861c6fd&code=".$code."&grant_type=authorization_code");
// 	// var_dump("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WXAPPID')."&secret=".C('WXAPPSECRET')." &code=".$code."&grant_type=authorization_code");
// 	$uinfo=json_decode($resinfo,true);
// 	// var_dump($uinfo);exit();
// 	$openid=$uinfo['openid'];
// 	var_dump($openid);
// 	// session('openid',$openid);
// 	// echo "你好 <span style='color:red'><b>".$user['nickname']."</b></span> 我拿到了你的个人信息 <img src='".$user['headimgurl']."'>";
	
// }else{
// 	$redirect_uri='http://home.58ate.com/t.php';
// 	header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxb8ba6e24cc7558a1&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
// }

?>
