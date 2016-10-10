<?php
class Fun{
	public function GetIP(){
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
			$ip = getenv("HTTP_CLIENT_IP");
		}else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
			$ip = getenv("REMOTE_ADDR");
		}else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
			$ip = $_SERVER['REMOTE_ADDR'];
		}else{
			$ip = "unknown";
		}
		
		return $ip;
	}
	
	/**
	 * 过滤数据
	 *
	 * @param $request 需要过滤的数据
	 */
	public function _addslashes($request, $force = 0){
		/* 过滤所有GET过来变量------------------------------------------------------------- */

		return $request;
		if(is_array($request)){
			return 2222;
			foreach($request as $key=>$val){
				if(is_array($val)){
					foreach($val as $k=>$v){
						$backArr[$key][$k] = $this->do_addslashes($v);
					}
				}else{
					$backArr[$key] = $this->do_addslashes($val);
				}
			}
		}else{
			return 111;
			$backArr = $this->do_addslashes($request);
		}
	
	
		return $backArr;
	}
	
	/**
	 * 过滤字符
	 *
	 * @param $str 需要过滤的字符
	 */
	public function do_addslashes($str){
		if (is_numeric($str)) {
			return $this->get_int($str);
		} else {
			return $this->get_str($str);
		}
	}
	
	
	/**
	 * 过滤函数 整型过滤函数
	 *
	 * @param  $number 整形数据
	 */
	private function get_int($number){
		return intval($number);
	}
	
	/**
	 * 过滤函数 字符串型过滤函数
	 *
	 * @param  $string 字符数据
	 */
	private function get_str($string){
		if (!get_magic_quotes_gpc()) {
			return addslashes($string);
		}
		return $string;
	}
}
?>