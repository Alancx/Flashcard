<?php
namespace Org\WeChar;
use Org\Public;
class public
{
	/**
	 * ��ȡ���뼶���ʱ���
	 */
	public static function getMillisecond()
	{
		//��ȡ�����ʱ���
		$time = explode ( " ", microtime () );
		$time = $time[1] . ($time[0] * 1000);
		$time2 = explode( ".", $time );
		$time = $time2[0];
		return $time;
	}

	/**
	 * 
	 * ��������ַ�����������32λ
	 * @param int $length
	 * @return ����������ַ���
	 */
	public static function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

}