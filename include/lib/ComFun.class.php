<?php
/**
 * 处理用户信息类
 * 
 * @author wbqing405@sina.com
 */
class ComFun{
	static $key = '741123';
	static $iv = 'QWE123';
	/**
	 * 声明方法
	 */
	public static function _des($key, $iv){
		include_once('DES.class.php');
		return new DES($key, $iv);
	}
	/**
	 * 加密
	 */
	public static function _encrypt($value=false){
		if($value){
			$des = self::_des(self::$key,self::$iv);	
			return $des->encrypt($value);
		}
	}
	/**
	 * 解密
	 */
	public static function _decrypt($value=false){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return $des->decrypt($value);
		}
	}
	/**
	 * 加密url传递数据
	 */
	public static function _encryptUrl($value=false){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return str_replace('+', '%20', $des->encrypt($value));
		}
	}
	/**
	 * 解密url传递数据
	 */
	public static function _decryptUrl($value=false){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return $des->decrypt(str_replace(' ', '+', str_replace('%20', '+', $value)));
		}
	}
	/**
	 * url转化
	 */
	public function url_replace($string){
		return self::_decrypt(str_replace('%20', '+', $string));
	}
	/**
	 * urlencode处理
	 */
	public function urlencode_rfc3986($input) {
		if (is_scalar($input)) {
			return str_replace(
					'+',
					' ',
					str_replace('%7E', '~', rawurlencode(self::_encrypt($input)))
			);
		} else {
			return '';
		}
	}
	
	/**
	 * urldecode处理
	 */
	public static function urldecode_rfc3986($string) {
		return urldecode(self::_decrypt($string));
	}
	/**
	 * 对邮件数组进行加密处理
	 */
	public static function _encodeArr($arr){
		if(is_array($arr)){
			return self::urlencode_rfc3986(json_encode($arr));
		}
	}
	/**
	 * 对邮箱数组进行解密处理
	 */
	public static function _decodeArr($str){
		if($str){
			return json_decode(self::urldecode_rfc3986($str),true);
		}else{
			return $str;
		}
	}
	/**
	 * 对过期跳转登录url进行处理
	 */
	public static function getCallback () {
		if ( $_GET ) {
			if ( $_SERVER['QUERY_STRING'] ) {
				foreach ( $_GET as $_k => $_v ) {
					if ( $_k != '_module' && $_k != '_action' ) {
						if ( preg_match("/^[0-9]*$/", $_k ) ) {
							$_nArr[] = $_v;
						} else {
							$_vArr = $_v;
						}
					}
				}
				$_url = '/' . $_GET['_module'] . '/' . $_GET['_action'] . ( $_nArr ? '-' . implode('-', $_nArr) : '' ) . ( $_vArr ? '?' . http_build_query($_vArr) : '' );
			} else {
				$i = 1;
				foreach ( $_GET as $_k => $_v ) {
					if ( $_k != '_module' && $_k != '_action' ) {
						if ( preg_match("/^[0-9]*$/", $_k ) ) {
							$_nArr[$i] = $_v;
							$i++;
						} else {
							$_vArr[$_k] = $_v;
						}
	
					}
					$_aArr[] = $_v;
				}
	
				if ( count($_aArr) > 2 ) {
					$_nArr[0] = $_aArr[0];
					ksort($_nArr);
						
					$_url = '/' . $_GET['_module'] . '/' . $_GET['_action'] . ( $_nArr ? '-' . implode('-', $_nArr) : '' );
				} elseif ( count($_aArr) == 2 ) {
					$_url = '/' . $_GET['_module'] . '/' . $_GET['_action'];
				} else {
					$_url = '/';
				}
			}
		} else {
			$_url = '/';
		}
	
		return ComFun::_encryptUrl( $_url );
	}
	/**
	 * 注册$_COOKIES
	 */
	public static function SetCookies($params,$lifeTime=false){
		if(is_array($params)){
			session_start();
			//设定$_COOKIES保存时间;
			if(!$lifeTime){
				$lifeTime = 24 * 3600;
			}
			//self::pr($params);	
			foreach($params as $key=>$val){
				setcookie($key,self::_encrypt($val),time()+$lifeTime,"/");
			}
		}
	}
	/**
	 * 取$_COOKIE值 decrypt
	 */
	public static function getCookies($pStr=false){
		if(is_array($pStr)){
			return self::getCookiesArr($pStr);
		}elseif($pStr){
			return self::_decrypt($_COOKIE[$pStr]);
		}else{
			return self::getCookiesArr($_COOKIE);
		}
	}
	/**
	 * 对$_COOKIE数组的处理，是getCookies函数的后续
	 */
	public static function getCookiesArr($fieldArr){
		if(is_array($fieldArr)){
			foreach($fieldArr as $key=>$val){
				if($key == 'cp_language' || $key == 'PHPSESSID'){
					$cookies[$key] = $val;
				}else{
					$cookies[$key] = self::_decrypt($val);
				}
			}
		}
	
		return $cookies;
	}
	/**
	 * 注销$_COOKIES
	 */
	public static function destoryCookies($fieldArr=null){
		if(is_array($fieldArr)){
			$cookies = $fieldArr;
		}else{
			$cookies = $_COOKIE;
		}
		
		session_start();
		foreach($cookies as $key=>$val){
			setcookie($key,'',time()-3600,'/');
		}
	}
	/**
	 * 发送邮件
	 */
	public static function toSendMail($emailArr,$type){
		include_once('Email.class.php'); //邮件发送类
		$email = new Email();
		$backStr = $email->sendMail($emailArr,$type);
	}
	/**
	 * 随机明文 md5 16位
	 */
	public static function getRandom($len=10,$start=2,$end=16){
		$srcstr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
		mt_srand();
		$strs="";
	
		for($i=0;$i<$len;$i++){
			$strs.=$srcstr[mt_rand(0,35)];
		}
	
		$strs .= time();
	
		return substr(md5($strs),$start,$end);
	}
	/**
	 * 获取IP
	 */
	public static function getIP(){
		/*
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}elseif (getenv("HTTP_X_FORWARDED_FOR")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}elseif (getenv("HTTP_CLIENT_IP")){
			$ip = getenv("HTTP_CLIENT_IP");
		}elseif (getenv("REMOTE_ADDR")){
			$ip = getenv("REMOTE_ADDR");
		}else{
			$ip = "Unknown";
		}
		*/
		//return $_SERVER;
		if (! empty ( $_SERVER ['HTTP_CLIENT_IP'] )) {
			$ip = $_SERVER ['HTTP_CLIENT_IP'];
		} else if (! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
			$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER ['REMOTE_ADDR'];
		}
		return $ip;
	}
	/**
	 * 判断是用户是通过PC机还是手机访问网站
	 */
	public static function checkBrowse(){
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
		
		$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
		
		if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 另一种判断通过PC亦或手机
	 */
	public static function check_wap() {
		return stristr($_SERVER['HTTP_VIA'],"wap") ? true : false;
	}
	/**
	 * url回调参数构造
	 */
	public static function makeCallBack($fieldArr){
		if($fieldArr){
			foreach($fieldArr as $key=>$val){
				if($key != '_module' && $key != '_action'){
					$urlArr[$key] =  $val;
				}
			}
			if($urlArr){
				return http_build_query($urlArr);
			}
		}	
	}
	/**
	 * 回调参数保存为$_COOKIES(index)
	 */
	public static function SaveCallBack($fieldArr){
		if($fieldArr['ident']){
			$cookies['ident'] = $fieldArr['ident'];
			foreach($fieldArr as $key=>$val){
				if($key != '_module' && $key != '_action' && $key != 'ident'){
					$cookies[$fieldArr['ident'].'_'.$key] =  $val;
				}
			}
	
			self::SetCookies($cookies);
		}
	}
	/**
	 * 操作成功后，返回调用地址，默认个人中心
	 */
	public static function checkCallBack($fieldArr){
		if($fieldArr){
			if($fieldArr['redirect']){
				foreach($fieldArr as $key=>$val){
					if($key != '_module' && $key != '_action' && $key != 'redirect'){
						$urlArr[$key] =  $val;
					}
				}
				if($urlArr){
					return $fieldArr['redirect'].'?'.http_build_query($urlArr);
				}else{
					return $fieldArr['redirect'];
				}
			}else{
				return '/main/index';
			}
		}else{
			return '/main/index';
		}
	}
	/**
	 * 获取$_GET或$_POST参数
	 */
	public static function GetString($key, $len = 0, $def = null) {
		$_val = $_GET [$key] ? $_GET [$key] : $_POST [$key];
		self::pr($_val);exit;
		if ($_val) {
			$_val = $this->_addslashes ( $_val );
			if ($len > 0) {
				return substr ( $_val, 0, $len );
			} else {
				return $_val;
			}
		} else if ($def) {
			return $def;
		} else {
			return null;
		}
	}
	/**
	 * 权限判断
	 */
	public static function getGroupDeny(){
		$group = ComFun::getCookies('group');

		if(in_array($group,array(1))){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 分解UserID
	 */
	public static function getUserID($value){
		if($value){
			include_once('DES.class.php');
			$des = new DES('123123qw','qweqwe12');
			
			$strArr = explode('|', $des->decrypt(urldecode($value)));
	
			$UserID = $strArr[0];
			
			return $UserID;
		}
	} 
	/**
	 * 构造UserID
	 */
	public static function setUserID($UserID){
		$user_id = ComFun::getCookies('user_id');

		if($user_id){
			include_once('DES.class.php');
			$des = new DES('123123qw','qweqwe12');
			
			$strArr = explode('|', $des->decrypt(urldecode($user_id)));
			
			$userid_str = $UserID.'|'.$strArr[1];
			$userid_str = $des->encrypt($userid_str);
			
			return ComFun::de_urlencode_rfc3986($userid_str);
		}else{
			return '';
		}
	}
	/**
	 * 新的urlencode_rfc3986
	 */
	public static function de_urlencode_rfc3986($input){
		if (is_scalar($input)) {
			return str_replace(
					'+',
					' ',
					str_replace('%7E', '~', rawurlencode($input))
			);
		} else {
			return '';
		}
	}
	/**
	 * 取得用户的权限字符串
	 */
	public static function getUserPermission($model,$fieldArr){		
		$UserID = ComFun::getCookies('UserID');
		if($UserID){
			$sql = 'select b.fPermissions from tbFAQUserInfo a left join tbFAQUGroupInfo b on a.fGroupID = b.Autoid where a.UserID = \''.$UserID.'\'';
			$re = $model->query($sql);
	
			if($re){
				$pArr = json_decode($re[0]['fPermissions']);

				if($pArr){
					foreach($pArr as $val){
						if($val){
							foreach($val as $va){
								$npArr[] = $va;
							}
						}
					
					}
					
					if($npArr){
						$perStr = ',,'.implode(',',$npArr).',';
						$iper   = ','.intval($fieldArr['permission']).',';
					
						if(strpos($perStr, $iper)){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}				
			}else{
				return false;
			}
		}else{
			return false;
		}
	} 
	/**
	 * 过滤html标签
	 */
	public static function preg_html($str){
		$str = htmlspecialchars_decode($str);
		$str = preg_replace( "@<script(.*?)</script>@is", "", $str );
		$str = preg_replace( "@<iframe(.*?)</iframe>@is", "", $str );
		$str = preg_replace( "@<style(.*?)</style>@is", "", $str );
		//$str = preg_replace( "@<(.*?)>@is", "", $str );
		
		return $str;
	}
	/**
	 * 错误代码
	 */
	public static function getErrorArr(){
		return  include(dirname(dirname(dirname(__FILE__))).'/conf/error.php');	
	}
	/**
	 * 取指定错误说明
	 */
	public static function getErrorValue($value='ep1000'){
		$error = self::getErrorArr();
		
		return $error[$value];
	}
	/**
	 * 打印类
	 */
	public static function pr($arr=null){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}