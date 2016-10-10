<?php
/**
 * DB内部curl传递类
 *
 * @author wbqing405@sina.com
 */
class DBCurl{
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	private static $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	private static $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	private static $ssl_verifypeer = FALSE;
	/**
	 * Contains the last HTTP status code returned.
	 *
	 * @ignore
	 */
	private static $http_code;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	private static $http_info;
	/**
	 * 获取方法
	 */
	public static function dbGet($url, $method='GET', $parameters = array()){	
		$response = self::oAuthRequest($url, strtoupper($method), $parameters);

		if(!is_array($response)) {
			return json_decode($response, true);
		}else{
			return $response;
		}
	}
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	private static function oAuthRequest($url, $method, $parameters) {
		if(!$url){
			return array(
					'state' => 'false',
					'error' => 'ep1001',
					'msg'   => ComFun::getErrorValue('e1001'),
			);
		}

		switch ($method) {
			case 'GET':
				$url = $url . '?' . http_build_query($parameters);
	
				return self::http($url, 'GET');
			default:
				$body = array();
				if(is_array($parameters) || is_object($parameters)){
					$body = http_build_query($parameters);
				}
				
				return self::http($url, $method, $body);
		}
	}
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	private static function http($url, $method, $postfields = NULL, $headers = array()) {	
		$http_info = array();
		$http_code = '';
		$ci = curl_init();
		/* Curl settings */
 		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
 		//curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
 		//curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
 		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, self::$connecttimeout);
 		curl_setopt($ci, CURLOPT_TIMEOUT, self::$timeout);
 		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
 		curl_setopt($ci, CURLOPT_ENCODING, "");
 		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, self::$ssl_verifypeer);
// 		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
 		curl_setopt($ci, CURLOPT_HEADER, false);
	
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
				break;
		}
	
		//$headers[] = "Authorization: OAuth2 ".$this->access_token;
		$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
	
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
	
		$response = curl_exec($ci);

		self::$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);	
		self::$http_info = curl_getinfo($ci);
		
		curl_close ($ci);
		
		return $response;
	}
}