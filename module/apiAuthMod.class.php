<?php
/**
 *
 * 插件curl接口-----auth调用插件鉴权
 *
 * @author wbqing405@sina.com
 *
 */
class apiAuthMod extends commonMod{
	/**
	 * 返回信息处理
	 */
	private function _return($data=null) {
		if(isset($data['error'])){
			$data['msg'] = ComFun::getErrorValue($data['error']);
		}
	
		echo json_encode($data);exit;
	}
	/**
	 * 测试
	 */
	public function test(){
		$tArr['user_id'] = 'ajQ3VGU2NVRtOGh4WHlhWWZtSXhGQT09';
	
		$url = __ROOT__.$url.'/api/getPlusListInfo';
	
		ComFun::pr(DBCurl::dbGet($url, 'get', $tArr));
	}
	/**
	 * 检验应用是否有调用插件的权限
	 */
	public function checkUserValid($tArr){		
		$url = $this->config['PLATFORM']['Auth'].'/db/expAuthUserAndEncryptByAuth';
		
		$tArr['client_id'] = $this->config['oauth']['client_id'];
		$tArr['user_id']   = $tArr['user_id'];
		
		$token = DBCurl::dbGet($url, 'get', $tArr);
	
		if(!$token['state']){
			$this->_return(array('state' => 'false','error' => 'ep1002'));
		}
		
		return $token;
	}
	/**
	 * 验证二维码
	 */
	public function check(){
		$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_POST['user_id'];
		$AppPlugInID = isset($_GET['AppPlugInID']) ? $_GET['AppPlugInID'] : $_POST['AppPlugInID'];
		
		$tArr['user_id'] = $user_id;
		
		$re = $this->checkUserValid($tArr);
		
		$dbAppOauthPlugIn = $this->getClass('DBAppOauthPlugIn');
		$plugInfo = $dbAppOauthPlugIn->getAppOauthPlugInInfoByClientID($client_id);
		
		$rb = false;
		if($plugInfo){		
			foreach($plugInfo as $val){
				if(intval($val['AppPlugInID']) == intval($AppPlugInID)){					
					$aState  = $val['pStatues'];
					$expTime = $val['pExpTime'];
					$time    = $val['pUpdateTime'] + $expTime*24*3600;
		
					if($aState == 0 && ($expTime == 0 || $time > time())){
						$rb = true;
					}
					break;
				}
			}
		}
		
		$this->_return(array('state' => 'true','result' => $rb));
	}
	/**
	 * 取二维码
	 */
	public function get(){
		
	}
}