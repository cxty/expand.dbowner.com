<?php
/**
 * 测试接口
 *
 * @author wbqing405@sina.com
 *
 */
class TestInterface{
	
	public function __construct($model){
		$this->model = $model;
	}
	/**
	 * 取接口列表
	 */
	public function getInterList($value=''){
		$interInfo = include(dirname(dirname(dirname(__FILE__))).'/conf/Interface.php');
		//ComFun::pr($interInfo);
		
		$html = '';
		$html .= '<select name="intername" id="intername" onchange="testInter.getArg(this)">';
		$html .= '<option value="0">'.Lang::get('GetSelect').'</option>';
		
		if(is_array($interInfo)){
			foreach($interInfo as $val){
				if($val['InterValue'] == $value){
					$html .= '<option value="'.$val['InterValue'].'" selected>'.$val['InterEName'].'</option>';
				}else{
					$html .= '<option value="'.$val['InterValue'].'">'.$val['InterEName'].'</option>';
				}
			}
		}
		
		$html .= '</select>';
		
		return $html;
	}
	/**
	 * 处理测试接口
	 */
	public function mandTest($fieldArr){
		if($fieldArr['serverType'] == 2){
			$root = 'http://user.dbowner.com';			
		}else{
			$root = 'http://auth.dbowner.com';
		}
	
		foreach($fieldArr as $key=>$val){
			if(!in_array($key, array('serverType','reqInter','_module','_action'))){
				$reArr[$key] = urlencode($val);
			}
		}
		
		$tArr['client_id']      = $fieldArr['client_id'];
		$tArr['client_secret']  = $fieldArr['client_secret'];
		$tArr['redirect_uri']   = $fieldArr['redirect_uri'];
		$tArr['authorizeURL']   = $root.'/oauth/authorize';
		$tArr['accessTokenURL'] = $root.'/oauth/token2';
		$tArr['host']           = $root;
		
		$token['access_token']   = $fieldArr['access_token'];
		$token['refresh_token']  = $fieldArr['refresh_token'];
		$token['user_id']        = $fieldArr['user_id'];
		
		$CommonOAuth2 = $this->getClass('CommonOAuth2',$tArr,$token);
		
		$reurl = '';
		
		switch($fieldArr['reqInter']){
			case 'authorize':
				$reurl = $root.'/oauth/authorize?';
				break;
			case 'token2':
				$reurl = $root.'/oauth/token2?';
				$toArr['code'] = urlencode($fieldArr['code']);
				$re['back'] = $CommonOAuth2->getAccessOAuth($toArr);	
				break;
			case 'show':
				$re['back'] = $CommonOAuth2->api_show();
				break;
			case 'signout':
				$re['back'] = $CommonOAuth2->api_signout();
				break;
			case 'istimeout':
				$re['back'] = $CommonOAuth2->api_istimeout();
				break;
			case 'fresh_token':
				$re['back'] = $CommonOAuth2->api_fresh_token();
				break;
			case 'getapplist':
				$re['back'] = $CommonOAuth2->api_getapplist();
				break;
			case 'getApiInfow':
				//$re['back'] = $CommonOAuth2->api_show();
				break;
			case 'show_by_name':
				$toArr['name'] = $fieldArr['name'];
				$re['back'] = $CommonOAuth2->api_show_by_name($toArr);
				break;
			case 'show_by_userid':
				$toArr['user_id'] = $fieldArr['user_id'];
				$re['back'] = $CommonOAuth2->api_show_by_userid($toArr);
				break;
			case 'register_user':
				//$re['back'] = $CommonOAuth2->api_show($toArr);
				break;
			case 'friends':
				//$re['back'] = $CommonOAuth2->api_send_msg($toArr);
				break;
			case 'send_msg':
				$toArr['accepter'] = $fieldArr['accepter'];
				$toArr['theme']    = $fieldArr['theme'];
				$toArr['content']  = $fieldArr['content'];
				$re['back'] = $CommonOAuth2->api_send_msg($toArr);
				break;
			case 'get_new_msg':
				$toArr['pagesize'] = $fieldArr['pagesize'];
				$toArr['page']     = $fieldArr['page'];
				$re['back'] = $CommonOAuth2->api_get_new_msg($toArr);
				break;
			case 'get_read_msg':
				$toArr['pagesize'] = $fieldArr['pagesize'];
				$toArr['page']     = $fieldArr['page'];
				$re['back'] = $CommonOAuth2->api_get_read_msg($toArr);
				break;
			case 'get_send_msg':
				$toArr['pagesize'] = $fieldArr['pagesize'];
				$toArr['page']     = $fieldArr['page'];
				$re['back'] = $CommonOAuth2->api_get_send_msg($toArr);
				break;
			case 'get_del_msg':
				$toArr['pagesize'] = $fieldArr['pagesize'];
				$toArr['page']     = $fieldArr['page'];
				$re['back'] = $CommonOAuth2->api_get_del_msg($toArr);
				break;
			case 'del_msg':
				$toArr['id']    = $fieldArr['id'];
				$toArr['type']  = $fieldArr['type'];
				$re['back'] = $CommonOAuth2->api_del_msg($toArr);
				break;
			default:					
				break;
		}
		
		
		$re['request_url'] = $reurl.http_build_query($reArr);
		//ComFun::pr($re);
		return $re;
	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null,$token=null){
		switch($className){
			case 'CommonOAuth2':
				include('CommonOAuth2.php');
				return new CommonOAuth2($fieldArr,$token);
				break;
			default:
				break;
		}
	}
}