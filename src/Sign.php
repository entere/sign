<?php 

namespace Entere\Sign;

class Sign {


	/**
	 * 除去数组中的空值和签名参数、然后排序、然后生成md5签名
	 * @param $params 签名参数组
	 * @param $secret 签名密钥
	 * @return array 去掉空值与签名参数后并排序的新签名参数组
	 */
	public static function generateSign($params, $secret) {
		$params = self::paramsFilter($params);//去空
		$params = self::argSort($params);//排序
		//$preSign = self::generateHttpBuildQuery($paramsSort);//生成待签名的字串
		$preSign = self::generateStr($params);
		$result = [];
		$result['params'] = $params;
		$result['result']['sign'] = self::md5Sign($preSign, $secret);
		$result['result']['preSign'] = $preSign;
		return $result;
	}


	/**
	 * 除去数组中的空值和签名参数
	 * @param $params 签名参数组
	 * @return array 去掉空值与签名参数后的新签名参数组
	 */
	public static function paramsFilter($params) {
		$paramsFilter = array();

		while (list ($key, $value) = each ($params)) {
			if($key == "sign" || $key == "sign_type" || self::checkEmpty($value) === true){
				continue;
			} else {
				$paramsFilter[$key] = $params[$key];
			}
		}
		return $paramsFilter;
	}

	/**
	 * 检测值是否为空 
	 * @param    string                   		$value 待检测的值
	 * @return   boolean                     	 null | "" | unsset 返回 true;
	 */
	protected static function checkEmpty($value) {
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;
		return false;
	}

	/**
	 * 对数组排序
	 * @param $params 排序前的数组
	 * @return array 排序后的数组
	 */
	public static function argSort($params) {
		ksort($params);
		reset($params);
		return $params;
	}

	/**
	 * 方法1：把数组所有元素，按照“key1=value1&key2=value2”的模式拼接成字符串
	 * @param $params 需要拼接的一维数组
	 * @return string 
	 */
	public static function generateHttpBuildQuery($params) {
		$arg  = "";
		while (list ($key, $value) = each ($params)) {
			$arg.=$key."=".$value."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}

	/**
	 * 方法2：把数组所有元素，按照“key1value1key2value2”的模式拼接成字符串
	 * @param $params 需要拼接的一维数组
	 * @return string
	 */
	public static function generateStr($params) {
		$arg  = "";
		while (list ($key, $value) = each ($params)) {
			$arg.=$key.$value;
		}
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}

	/**
	 * 签名字符串
	 * @param $preSign 需要签名的字符串
	 * @param $secret 私钥
	 * @return string 签名结果
	 */
	public static function md5Sign($preSign, $secret) {
		$preSign = $preSign . $secret;
		
		return strtoupper(md5($preSign));
	}

}
