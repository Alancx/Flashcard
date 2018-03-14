<?php
namespace SellermobileApp\Controller;
use Think\Controller;
class TestController extends Controller{

  public function test(){
    $timetemp=time();
    echo $timetemp;
    echo '<br>';
    $timetemps=strtotime('+1day',$timetemp);
    echo '<br>';
    echo $timetemps-$timetemp;
  }

  /**
	 * 获取随机字符串
	 * $str 字符串长度、$num数字长度 $showtime是否使用时间戳
	 */
	public function testgetStr(){
		$s='qwertyuioplkjhgfdsazxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ';
		$n='1234567890';
		$ss="";
    $str=16;
    $num=16;
    $showtime=true;
		for ($i=0; $i < $str; $i++) {
			$ss.=substr(str_shuffle($s), $i,1);
		}
		$nn="";
		for ($i=0; $i < $num; $i++) {
			$nn.=substr(str_shuffle($n), 1,1);
		}
		if ($showtime) {
			$String=$ss.substr(time(), 1,7).$nn;
		}else{
			$String=$ss.$nn;
		}
		echo strlen($String);
	}

}?>
