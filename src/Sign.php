<?php 
namespace Entere\Sign;

class Sign {


	/**
	 * 除去数组中的空值和签名参数、然后排序、然后生成md5签名
	 * @param $params 签名参数组
	 * @param $key 签名密钥
	 * return 去掉空值与签名参数后并排序的新签名参数组
	 */
	public static function getSign($params,$key) {
		$params_filter = self::paramsFilter($params);//去空
		$params_sort = self::argSort($params_filter);//排序
		$prestr = self::createLinkstring($params_sort);//生成待签名的字串
		$params_sort['prestr'] = $prestr;
		$params_sort['sign'] = self::md5Sign($prestr, $key);
		return $params_sort;
	}


	/**
	 * 除去数组中的空值和签名参数
	 * @param $params 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	public static function paramsFilter($params) {
		$params_filter = array();

		while (list ($key, $value) = each ($params)) {
			if($key == "sign" || $key == "sign_type" || $value == ""){
				continue;
			} else {
				$params_filter[$key] = $params[$key];
			}
		}
		return $params_filter;
	}

	/**
	 * 对数组排序
	 * @param $params 排序前的数组
	 * return 排序后的数组
	 */
	public static function argSort($params) {
		ksort($params);
		reset($params);
		return $params;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $params 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	public static function createLinkstring($params) {
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
	 * 签名字符串
	 * @param $prestr 需要签名的字符串
	 * @param $key 私钥
	 * return 签名结果
	 */
	public static function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		
		return md5($prestr);
	}

}
