<?php
/**
 *
 * 登录页面
 *
 * @author wbqing405@sina.com
 *
 */
class loginMod extends commonMod{
	/**
	 * 授权登录第一步
	 */
	public function login(){
		$url = $this->commonOAuth2->getAuthorizeOAuth();
	
		//后台用户登录
		$cookies['dbo'] = $_GET['dbo'];
		$ident = $_GET['ident'];
		if($ident){
			$cookies['ident'] = $ident;
			ComFun::saveCallBack($_GET);
		}
		ComFun::SetCookies($cookies);
	
		$this->redirect($url);
	}
	/**
	 * 授权登录第二步
	 */
	public function callback(){
		if(isset($_GET['access_token'])){
			$token['access_token']  = $_GET['access_token'];
			$token['refresh_token'] = $_GET['refresh_token'];
			$token['user_id']       = $_GET['user_id'];
		}else{
			$token = $this->commonOAuth2->getAccessOAuth();
		}
	
		ComFun::SetCookies($token);
	
		$this->redirect('/login/setLoginInfo');
	}
	/**
	 * 保存登录信息
	 */
	public function setLoginInfo(){	
		//取用户信息
		$userInfo = $this->commonOAuth2->getUserInfo();	
		
		if ( !$userInfo ) {
			$this->redirect('/throwMessage/throwMsg?tt=2&msgkey=Ex_SysAccessTokenTimeOut');
		}

		$UserID = $userInfo['id'];
	
		$cookies['UserID']  = $UserID;
		$cookies['user_id'] = $UserID;
		$cookies['uName']   = $userInfo['name'];
		$cookies['group']   = $userInfo['group'];
		$cookies['account'] = $userInfo['account'];
		$cookies['ico']     = $userInfo['ico']['m'];

		$ident = ComFun::getCookies('ident');
	
		$dbLogin = $this->getClass('DBLogin');
	
		if($ident == 'manage'){
			$cookies['DBAdmin'] = true; //是否有登录后台的权限
		}
	
		//检验用户是否已经注册,若未注册则添加用户信息
		//后台用户操作
		$rbArr = $dbLogin->checkLogin($UserID);
		
		if($rbArr){
			$cookies['DBAdmin'] = true;
			$cookies['UID']         = $rbArr['UID'];
			$cookies['GroupID']     = $rbArr['GroupID'];
			$cookies['uPermission'] = $rbArr['uPermission'];
			$cookies['invalid']   = true; //进入页面，有
			
			$dbLogin->updateUserLogin($cookies);
		}else{
			$cookies2['invalid']   = false; //进入页面，无
			ComFun::SetCookies($cookies2);
			
			$this->redirect('/throwMessage/throwMsg?tt=2&msgkey=Ex_UnVailid');
		}
		
		ComFun::SetCookies($cookies);
	
		//检验是否有回调地址,若无则调到后台页面
		$url = $_COOKIE['dbo'] ? ComFun::_decryptUrl(ComFun::getCookies('dbo')) : strtolower(__ROOT__);

		if ( strpos($url, '/throwMessage') !== false || strpos($url, '/login') !== false ) {
			$url = '/plugIn';
		}
		
		$dcookies['ident'] = $ident;
		ComFun::destoryCookies($dcookies);
	
		$this->redirect($url);
	}
	/**
	 * 退出
	 */
	public function loginOut(){
		ComFun::destoryCookies();
		
		$userInfo = $this->commonOAuth2->signout();
		
		$this->redirect('/login/login');
	}
	/**
	 * 检验用户是否过期
	 */
	public function checkLogin(){
		//取用户登录状态
		$re =  $this->commonOAuth2->api_istimeout();
		if(!isset($re['id'])){
			//过期删除cookies
			ComFun::destoryCookies();
			$this->commonOAuth2->signout();
			
			echo true;
		}else{
			echo false;
		}
	}
	/**
	 * 检验用户是否过期
	 */
	public function checkLoginStatues(){
		//取用户登录状态
		$re =  $this->commonOAuth2->api_istimeout();
		if(!isset($re['id'])){
			echo true;
		}else{
			echo false;
		}
	}
	/**
	 * 检验用户是否过期
	 */
// 	public function checkLoginStatues(){
// 		//取用户登录状态
// 		$re =  $this->commonOAuth2->api_istimeout();
// 		if(!isset($re['id'])){
// 			//错误抛出处理
// 			$refArr = explode('/', $_SERVER['HTTP_REFERER']);
// 			if(count($refArr) > 4){
// 				if(strtolower($refArr[3]) == 'throwmessage'){
// 					echo false;exit;
// 				}
// 			}
// 			echo true;
// 		}else{
// 			echo false;
// 		}
// 	}
}