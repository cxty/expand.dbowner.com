<?php
/**
 * OAuth 1.0 处理类
 * 
 * @author wbqing405@sina.com
 *
 */
//include_once('ComFun.class.php'); //公共方法

class CommonOAuth2{
	var $code = 'code'; //默认的验证方式
	
	function __construct($config='',$token=null){
		$this->client_id      = $config['client_id'];
		$this->client_secret  = $config['client_secret'];
		$this->redirect_uri   = $config['redirect_uri'];
		
		$this->authorizeURL   = $config['authorizeURL'];
		$this->accessTokenURL = $config['accessTokenURL'];
		$this->host           = $config['host'];

		if($token){
			$this->access_token   = $token['access_token'];
			$this->refresh_token  = $token['refresh_token'];
			$this->user_id        = $token['user_id'];
		}else{
			$this->access_token   = ComFun::getCookies('access_token');
			$this->refresh_token  = ComFun::getCookies('refresh_token');
			$this->user_id        = ComFun::getCookies('user_id');
		}
		
		
		include('partner/DBOwnerOAuth.php');
		$this->DBOwnerOAuth = new DBOwnerOAuth($this->client_id,$this->client_secret,$this->access_token,$this->refresh_token);
	}
	
	/**
	 * 第一步请求用户授权临时信息
	 */
	public function getAuthorizeOAuth($seArr=false){
		$response_type = 'code';
	
		$authorize_request_url = $this->DBOwnerOAuth->getAuthorizeURL($this->authorizeURL,strtolower($this->redirect_uri) ,$response_type , $state = NULL, $display = NULL ,$seArr);
	
		return $authorize_request_url;
	}
	
	/**
	 * 第二步获取用户授权信息
	 */
	public function getAccessOAuth($fieldArr=''){
		if($fieldArr['code']){
			$key['code']   = $fieldArr['code'];
		}else{
			$key['code']   = $_GET['code'];
		}	
	
		$key['redirect_uri'] = $this->redirect_uri;
	//ComFun::pr($this->accessTokenURL);exit;
		return $this->DBOwnerOAuth->getAccessToken($this->accessTokenURL,$this->code,$key);
	}
	/**
	 * 获取用户信息
	 */
	public function getUserInfo(){
		$url = $this->host.'/users/show';
		
		$params['access_token'] = $this->access_token;
		
		$userInfo = $this->DBOwnerOAuth->get($url,$params);

		if(isset($userInfo['error'])){
			return false;
		}else{
			return $userInfo['data'];
		}	
	}
	/**
	 * 退出个人中心页面
	 */
	public function signout(){
		$url = $this->host.'/users/signout';
		
		$params['access_token'] = $this->access_token;
		
		$userInfo = $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 查询指定用户名的用户信息
	 */
	public function getUserInfoByName($fieldArr){
		$url = $this->host.'/users/show_by_name';
		
		$params['access_token'] = $this->access_token;
		$params['name']         = $fieldArr['name'];

		$re = $this->DBOwnerOAuth->get($url,$params);

		if(isset($re['error'])){
			$token = $this->fresh_token();
			
			$this->access_token = $token['access_token'];
			
			$tArr['name'] = $fieldArr['name'];
			
			$re = $this->getUserInfoByName($tArr);
		}

		if($re['status'] == 'success'){
			return $re['data'];
		}else{
			return '';
		}
	}
	/**
	 * 查询指定用户名的用户信息
	 */
	public function getUserInfoByUserID($fieldArr){
		$url = $this->host.'/users/show_by_userid';
	
		$params['access_token']  = $this->access_token;
		$params['user_id']       = $fieldArr['user_id'];

		$re = $this->DBOwnerOAuth->get($url,$params);

		if(isset($re['error'])){
			$token = $this->fresh_token();
				
			$this->access_token = $token['access_token'];
				
			$tArr['user_id'] = $fieldArr['user_id'];
				
			$re = $this->getUserInfoByUserID($tArr);
		}
	
		if($re['status'] == 'success'){
			return $re['data'];
		}else{
			return '';
		}
	}
	/**
	 * 刷新token值
	 */
	public function fresh_token(){
		$url = $this->host.'/users/fresh_token';
		
		$params['refresh_token'] = $this->refresh_token;
		
		$token = $this->DBOwnerOAuth->get($url,$params);
		
		ComFun::SetCookies($token);
		
		return $token;
	}
	/**
	 * 获取用户信息
	 */
	public function api_show(){
		$url = $this->host.'/users/show';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 退出此用户登录状态
	 */
	public function api_signout(){
		$url = $this->host.'/users/signout';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 判断是否过期
	 */
	public function api_istimeout(){
		$url = $this->host.'/users/istimeout';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 用refresh_token刷新access_token
	 */
	public function api_fresh_token(){
		$url = $this->host.'/users/fresh_token';
	
		$params['format']        = $this->format;
		$params['refresh_token'] = $this->refresh_token;
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 返回用户所有应用及其权限代码
	 */
	public function api_getapplist(){
		$url = $this->host.'/users/getapplist';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 查询指定用户名的用户信息
	 */
	public function api_show_by_name($fieldArr){
		$url = $this->host.'/users/show_by_name';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['name']         = $fieldArr['name'];
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 查询指定用户user_id的用户信息
	 */
	public function api_show_by_userid($fieldArr){
		$url = $this->host.'/users/show_by_userid';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['user_id']      = $fieldArr['user_id'];
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	/**
	 * 发布信息
	 */
	public function api_send_msg($fieldArr){
		$url = $this->host.'/content/send_msg';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['accepter']     = $fieldArr['accepter'];
		$params['theme']        = $fieldArr['theme'];
		$params['content']      = $fieldArr['content'];
	
		return $this->DBOwnerOAuth->post($url,$params);
	}
	/**
	 * 取用户未读短信息列表
	 */
	public function api_get_new_msg($fieldArr){
		$url = $this->host.'/content/get_new_msg';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['pagesize']     = $fieldArr['pagesize'];
		$params['page']         = $fieldArr['page'];
	
		return $this->DBOwnerOAuth->post($url,$params);
	}
	/**
	 * 取用户已读短信息列表
	 */
	public function api_get_read_msg($fieldArr){
		$url = $this->host.'/content/get_read_msg';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['pagesize']     = $fieldArr['pagesize'];
		$params['page']         = $fieldArr['page'];
	
		return $this->DBOwnerOAuth->post($url,$params);
	}
	/**
	 * 取用户已发送信息列表
	 */
	public function api_get_send_msg($fieldArr){
		$url = $this->host.'/content/get_send_msg';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['pagesize']     = $fieldArr['pagesize'];
		$params['page']         = $fieldArr['page'];
	
		return $this->DBOwnerOAuth->post($url,$params);
	}
	/**
	 * 取用户已删除信息列表
	 */
	public function api_get_del_msg($fieldArr){
		$url = $this->host.'/content/get_del_msg';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['pagesize']     = $fieldArr['pagesize'];
		$params['page']         = $fieldArr['page'];
	
		return $this->DBOwnerOAuth->post($url,$params);
	}
	/**
	 * 删除短信息
	 */
	public function api_del_msg($fieldArr){
		$url = $this->host.'/content/del_msg';
	
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['id']           = $fieldArr['id'];
		$params['type']         = $fieldArr['type'];
	
		return $this->DBOwnerOAuth->get($url,$params);
	}
	
	/**
	 * 注册用户信息
	 */
	public function api_register_user($fieldArr){
		$url = $this->host.'/account/register_user';
		
		$params['format']       = $this->format;
		$params['access_token'] = $this->access_token;
		$params['user_id']      = $fieldArr['user_id'];
		$params['client_id']    = $fieldArr['client_id'];
		
		return $this->DBOwnerOAuth->get($url,$params);
	}
}
?>