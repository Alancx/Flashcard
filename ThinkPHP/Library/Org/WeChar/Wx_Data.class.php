<?php
/**
* 2015-06-29 修复签名问题
**/
// require_once "WxPay.Config.php";

/**
 * 
 * 数据对象基础类，该类中定义数据类最基本的行为，包括：
 * 计算/设置/获取签名、输出xml格式的参数、从xml读取数据对象等
 * @author widyhu
 *
 */
namespace Org\WeChar;
use Org\WeChar\Wx_Exception;
class Wx_Data
{
	public $values = array();
	public $token='';
	public $result=array();
	/**
	* 设置签名，详见签名生成算法
	* @param string $value 
	**/
	public function SetSign($key)
	{
		$sign = $this->MakeSign($key);
		return $sign;
	}
	
	/**
	* 获取签名，详见签名生成算法的值
	* @return 值
	**/
	public function GetSign()
	{
		return $this->values['sign'];
	}
	
	/**
	* 判断签名，详见签名生成算法是否存在
	* @return true 或 false
	**/
	public function IsSignSet()
	{
		return array_key_exists('sign', $this->values);
	}

	/**
	 * 输出xml字符
	 * @throws Wx_Exception
	**/
	public function ToXml()
	{
		if(!is_array($this->values) 
			|| count($this->values) <= 0)
		{
    		throw new Wx_Exception("数组数据异常！");
    	}
    	
    	$xml = "<xml>";
    	foreach ($this->values as $key=>$val)
    	{
    		$xml.="<".$key.">".$val."</".$key.">";
    		// if (is_numeric($val)){
    		// 	$xml.="<".$key.">".$val."</".$key.">";
    		// }else{
    		// 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    		// }
        }
        $xml.="</xml>";
        return $xml; 
	}
	
    /**
     * 将xml转为array
     * @param string $xml
     * @throws Wx_Exception
     */
	public function FromXml($xml)
	{	
		if(!$xml){
			throw new Wx_Exception("xml数据异常！");
		}
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $this->result;
	}
	
	/**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams()
	{
		$buff = "";
		foreach ($this->values as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	/**
	 * 生成签名
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function MakeSign($key)
	{
		//签名步骤一：按字典序排序参数
		ksort($this->values);
		$string = $this->ToUrlParams();

		//签名步骤二：在string后加入KEY
		$string = $string."&key=".$key;
		
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);

		return $result;
	}
	
	/**
	 * 获取设置的值
	 */
	public function GetValues()
	{
		return $this->values;
	}

	public function createNonceStr($length = 16)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
			for ($i = 0; $i < $length; $i++) {
				$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			}
		return $str;
	}


}