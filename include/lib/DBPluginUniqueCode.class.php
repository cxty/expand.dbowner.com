<?php
/**
 * @author wbqing405@sina.com
 * 
 * 插件验证码生成方法
 * 
 */
class DBPluginUniqueCode {
	
	static private $expireTime = 60; //过期时间
	
	/**
	 * 加密值算法
	 * $params加上时间戳，把所有的键名转成小写，对数据进行升序排列，再连城字符串，最后md5
	 */
	public static function getUniqueCode ( $params = array() ) {
		$params['timestamp'] =  time() / self::$expireTime % 200000000;
		foreach ( $params as $k => $v ) {
			$reb[strtolower($k)] = $v;
		}
		ksort($reb);
		ComFun::pr($reb);
		return md5(implode('', $reb));
	}
}