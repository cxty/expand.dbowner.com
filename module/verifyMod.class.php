<?php
/**
 *
 * 插件统一管理入口：内嵌方法
 *
 * @author wbqing405@sina.com
 *
 */
class verifyMod extends commonMod {
	/**
	 * 页面跳转
	 */
	private function _redirect ( $url ) {
		$this->_endLog( array('url' => $url) );
		$this->redirect($url);
	}
	
	/**
	 * 页面请求开始信息记录
	 */
	private function _startLog () {
		$this->dbUniformInterfaceLog = new DBUniformInterfaceLog($this->model);
		$this->UniformInterfaceLogID = $this->dbUniformInterfaceLog->addUniformInterfaceRequest( array('uilRequestParams' => $_GET) );		
	}
	
	/**
	 * 页面请求结束信息记录
	 */
	private function _endLog ( $params ) {
		$this->dbUniformInterfaceLog->updateUniformInterfaceRespond( array('UniformInterfaceLogID' => $this->UniformInterfaceLogID,'uilRespondParams' => $params) );
	}
	
	/**
	 * url重组
	 */
	private function _rePackageUrl () {
		$params = array();
		foreach ( $_GET as $k => $v ) {
			if ( !in_array(strtolower($k), array('_module','_action','plugincode')) ) {
				$params[$k] = $v;
			}
		}
		
		if ( $params ) {
			return http_build_query($params);
		} else {
			return '';	
		}
	}
	
	/**
	 * 重组request参数
	 */
	private function _rePackageRequest () {
		$params = array();
		foreach ( $_GET as $k => $v ) {
			if ( !in_array(strtolower($k), array('_module','_action','plugincode')) ) {
				$params[$k] = $v;
			}
		}
	
		return $params;
	}
	
	/**
	 * 验证access_token有效性，有效则返回本平台的UserID
	 */
	private function _isAccessTokenValid () {
		$request = array(
				'access_token' => $_GET['access_token'],
				'AppID'        => $_GET['AppID']
				);
		if ( !$request['access_token'] ) {
			$this->_redirect('/throwMessage/remind-MissingAccessToken');
		}
		if ( !$request['AppID'] ) {
			$this->_redirect('/throwMessage/remind-MissingAnAppID');
		}
		
		//数据缓存
		$memKey_result = '|verify|_isAccessTokenValid|GetUserIDByAccessTokenAndAppID|' . json_encode($request);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$rb = $menVal;
		} else {
			$tArr['access_token'] = $request['access_token'];
			$tArr['AppID']        = $request['AppID'];
			$tArr['client_id']    = $this->config['oauth']['client_id'];
			$dbSoap = new DBSoap( $this->config );
			$rb = $dbSoap->getTableInfo('auth', 'GetUserIDByAccessTokenAndAppID', $tArr);
			
			if ( !$rb['data'] ) {
				$this->_redirect('/throwMessage/remind-NotPowerUseApp');
			}
				
			$this->_Cache->set( $memKey_result, $rb, $this->config['MEM_EXPIRE'] );
		}
		
		return array('UserID' => $rb['data']['UserID']);
	}
	
	/**
	 * 验证应用是否有使用PlugInCode的权限
	 */
	private function _isPlugInCodeValid ( $params ) {
		//请求信息记录
		$this->_startLog();
		
		$request = array(
				'pUniqueCode' => $_GET['pUniqueCode'],
				'UserID'     => $params['UserID'],
				'AppID'      => $_GET['AppID']
		);
		
		if ( !$request['pUniqueCode'] ) {
			$this->_redirect('/throwMessage/remind-MissingpUniqueCode');
		}
		if ( !$request['AppID'] ) {
			$this->_redirect('/throwMessage/remind-MissingAnAppID');
		}
		/*
		$memKey_result = '|verify|_isPlugInCodeValid|isUserUsePlugInValid|' . json_encode($request);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$re = $menVal;
		} else {
			$_dbPlugIn = new DBPlugIn($this->model);
			$request['AppInfoID'] = $request['AppID'];
			$re = $_dbPlugIn->getUserUsePlugInValid($request);
				
			$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		}
		*/
		$_dbPlugIn = new DBPlugIn($this->model);
		$request['AppInfoID'] = $request['AppID'];
		$re = $_dbPlugIn->getUserUsePlugInValid($request);
		
		if ( !$re ) {
			$this->_redirect('/throwMessage/remind-NotPowerUsePlugIn');
		}
		
		return $re;
	}
	
	/**
	 * 中转站，验证用户是否有调用插件的权限，有则跳转到插件界面，无则返回错误提示信息
	 */
	public function plugin () {
		//检验用户有效性
		$token = $this->_isAccessTokenValid();
		
		//检验应用使用插件权限
		$rb = $this->_isPlugInCodeValid($token);
	
		//url重构
		$params = $this->_rePackageRequest();
		
		if ( $rb['pUrl'] ) {
			$uniArr = $params;
			$uniArr['identCode'] = $rb['pUniqueCode'];
			$url = $rb['pUrl'] . '?code=' . DBPluginUniqueCode::getUniqueCode( $uniArr ) . '&' . http_build_query($params);
	//echo $url;exit;
			$this->_redirect($url);
		} else {
			$this->_redirect('/throwMessage/remind-EmptyaUrl');
		}
	}
}