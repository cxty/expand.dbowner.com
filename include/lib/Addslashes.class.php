<?php
/**
 * 用addslashes方法过滤数据
 * 
 * @author wbqing405@sina.com
 *
 */
class Addslashes{
	function __construct(){

	}
	
	/**
	 * 过滤数据
	 * 
	 * @param $request 需要过滤的数据
	 */
	public function get_addslashes($request){
		/* 过滤所有GET过来变量------------------------------------------------------------- */
		if(is_array($request)){
			$backArr = $this->loop_addslashes($request);
		}else{
			$backArr = $this->do_addslashes($request);
		}
			
		return $backArr;
	}
	/**
	 * 循环
	 */
	private function loop_addslashes($request){
		foreach($request as $key=>$val){
			if(is_array($val)){
				$backArr[$key] = self::loop_addslashes($val);
			}else{
				$backArr[$key] = $this->do_addslashes($val);
			}
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