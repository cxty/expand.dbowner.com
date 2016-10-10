<?php
/**
 *
 * 插件curl接口
 *
 * @author wbqing405@sina.com
 *
 */
class apiMod extends commonMod{
	/**
	 * 返回信息处理
	 */
	private function _return($state, $msg='', $data=null, $format='json') {
		//方法改造后返回参数进行变动（为了兼容改造前接口规范，特此处理）
		$rb = array(
				'state' => $state,
				'msg' => $msg,
				'data' => $data,
		);
	
		$this->_endLog( $rb );
	
		echo json_encode($rb);exit;
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
	 * 检验用户是否有权限访问接口
	 */
	private function _checkUserIsValid ( $params ) {
		$request = array(
				'access_token' => $params['access_token'],
				'AppID'        => $this->config['oauth']['client_id']
		);
		
		//数据缓存
		$memKey_result = '|api|_checkUserIsValid|GetUserIDByAccessTokenAndAppID|' . json_encode($request);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$rb = $menVal;
		} else {
			$dbSoap = new DBSoap( $this->config );
			$rb = $dbSoap->getTableInfo('auth', 'GetAuthAppID', $request);
			if ( !$rb ) {
				$this->_return(false , ComFun::getErrorValue('ep1002') );
			}
		
			$this->_Cache->set( $memKey_result, $rb, $this->config['MEM_EXPIRE'] );
		}
		
		return $rb;
	}
	
	/**
	 * 验证access_token有效性，有效则返回本平台的UserID
	 */
	private function _isAccessTokenValid ( $params ) {
		$request = array(
				'access_token' => $params['access_token'],
				'AppID'        => $params['AppID']
		);
		if ( !$request['access_token'] ) {
			$this->_return(false , Lang::get('MissingAccessToken', 'throwMsg') );
		}
		if ( !$request['AppID'] ) {
			$this->_return(false , Lang::get('MissingAnAppID', 'throwMsg') );
		}
	/*
		//数据缓存
		$memKey_result = '|api|_isAccessTokenValid|GetUserIDByAccessTokenAndAppID|' . json_encode($request);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$rb = $menVal;
		} else {
			
	
			$this->_Cache->set( $memKey_result, $rb, $this->config['MEM_EXPIRE'] );
		}
		*/
		
		$tArr['access_token'] = $request['access_token'];
		$tArr['AppID']        = $request['AppID'];
		$tArr['client_id']    = $this->config['oauth']['client_id'];
		$dbSoap = new DBSoap( $this->config );
		$rb = $dbSoap->getTableInfo('auth', 'GetUserIDByAccessTokenAndAppID', $tArr);
		
		if ( !$rb['data'] ) {
			$this->_return(false , Lang::get('NotPowerUseApp', 'throwMsg') );
		}
	
		return array('UserID' => $rb['data']['UserID']);
	}
	
	/**
	 * 验证应用是否有使用pUniqueCode的权限
	 */
	private function _isPlugInCodeValid ( $params ) {
		$request = array(
				'pUniqueCode' => $params['pUniqueCode'],
				//'UserID'     => $params['UserID'],
				'AppID'      => $params['AppID']
		);
	
		if ( !$request['pUniqueCode'] ) {
			$this->_return(false , Lang::get('MissingUniqueCode', 'throwMsg') );
		}
		if ( !$request['AppID'] ) {
			$this->_return(false , Lang::get('MissingAnAppID', 'throwMsg') );
		}
	/*
		$memKey_result = '|api|_isPlugInCodeValid|isUserUsePlugInValid|' . json_encode($request);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$re = $menVal;
		} else {
			
			
			$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		}
		*/
		$_dbPlugIn = new DBPlugIn($this->model);
		$request['AppInfoID'] = $request['AppID'];
		$re = $_dbPlugIn->getUserUsePlugInValid($request);
		
		if ( !$re ) {
			$this->_return(false , Lang::get('NotPowerUsePlugIn', 'throwMsg') );
		}
	
		return $re;
	}
	
	/**
	 * 以下判断只允许dev api平台的访问
	 */
	private function _devapi () {
		$_sign = isset($_GET['_sign']) ? $_GET['_sign'] : $_POST['_sign'];
		if ( !$_sign ) {
			$this->_return(false , Lang::get('WrongOrigin', 'throwMsg') );
		} else {
			if ( $_sign != md5(strtoupper(base64_encode($this->config['PLATFORM']['EXPAND']['API']))) ) {
				$this->_return(false , Lang::get('WrongOrigin', 'throwMsg') );
			}
		}
	}
	
	/**
	 * 获取插件列表
	 */
	public function getPlusListInfo(){
		$this->_startLog();
		
		$this->_devapi();
		
		//$tArr['access_token'] = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		$tArr['pUniqueCode']  = isset($_GET['UniqueCode']) ? $_GET['UniqueCode'] : $_POST['UniqueCode'];
		
		//$this->_checkUserIsValid( $tArr ); //用户授权
		
		$dbPlugIn = new DBPlugIn($this->model);
		
		$appList = $dbPlugIn->getPlugInListByCurl($tArr);
		
		if(is_array($appList)){
			foreach($appList as $key=>$val){
				$appList[$key]['ApiUrl'] = $dbPlugIn->getApiUrlByID($val['AppPlugInID']);
				$ApiParams = $dbPlugIn->getParamsByID($val['AppPlugInID']);
				if(is_array($ApiParams)){
					$i = $j = 0;
					foreach($ApiParams as $ke=>$va){
						if($va['pType'] == 1){
							$appList[$key]['input'][$i] = $va;
							unset($appList[$key]['input'][$i]['pType']);
							$i++;
						}else{
							$appList[$key]['output'][$j] = $va;
							unset($appList[$key]['output'][$j]['pType']);
							$j++;
						}
					}
				}
			}
		}
		
		$this->_return(true, 'ok', $appList);
	}
	
	/**
	 * 插件统一调用接口，curl方式
	 */
	public function plugin () {
		$this->_startLog();
		
		$this->_devapi();
		
		$request = array(
				'access_token' => isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'],
				'AppID'        => isset($_GET['AppID']) ? $_GET['AppID'] : $_POST['AppID'],
				'pUniqueCode'   => isset($_GET['UniqueCode']) ? $_GET['UniqueCode'] : $_POST['UniqueCode'],
				'api'          => isset($_GET['api']) ? $_GET['api'] : $_POST['api'],
				'method'       => isset($_GET['method']) ? $_GET['method'] : (isset($_POST['method']) ? $_POST['method'] : 'post'),
		);
		
		if ( !$request['api'] ) {
			$this->_return(false ,'missing api' );
		}
		
		//检验应用使用插件权限
		$rb = $this->_isPlugInCodeValid( $request );
		
		if ( in_array($rb['PlugInTypeID'], $this->config['DB']['PlugInTypeID']) ) {
			//检验用户有效性
			$token = $this->_isAccessTokenValid( $request );
			
			$request['UserID'] = $token['UserID'];
		}
		
		$dbPlugIn = new DBPlugIn($this->model);
		$reABI['AppPlugInID'] = $rb['AppPlugInID'];
		$reABI['aApiName']    = $request['api'];
		$rbABI = $dbPlugIn->getApiBaseInfo( $reABI );
		
		if ( $rbABI ) {
			$reAP['ApiID'] = $rbABI['ApiID'];
			$reAP['pType'] = 1;
			$rbAP = $dbPlugIn->getApiParams( $reAP );
			
			$rcd['access_token'] = $request['access_token'] . '';
			$rcd['AppID']        = $request['AppID'];
			$rcd['UniqueCode']   = $request['pUniqueCode'];
			$rcd['api']          = $request['api'];
			$rcd['method']       = $request['method'];
			
			$signArr = array(
					'AppID' => $rcd['AppID'],
					'UniqueCode' => $rcd['UniqueCode'],
					'api' => $rcd['api']
			);
			foreach ( $signArr as $k => $v ) {
				$_signArr[strtolower($k)] = $v;
			}
			ksort($_signArr);
			$rcd['_sign'] = strtoupper(md5(implode('|', $_signArr)));
			
			if ( $rbAP ) {
				foreach ( $rbAP as $k => $v ) {
					$rcd[$v['pFieldName']] = isset($_GET[$v['pFieldName']]) ? $_GET[$v['pFieldName']] : $_POST[$v['pFieldName']];
				}
			}
			
			$data = DBCurl::dbGet($rbABI['aUrl'], $request['method'], $rcd);
			
			if ( $data ) {
				foreach ( $data as $k => $v ) {
					if ( in_array(strtolower($k), array('state','msg')) ) {
						$_data[$k] = $v;
					}
				}
			}
			
			if ( isset($data['_sign']) ) {
				if ( $data['_sign'] != $rcd['_sign'] ) {
					$this->_return(false , Lang::get('NotEmptySign', 'throwMsg'), $data['data'] );
				}
			} else {
				$this->_return(false , Lang::get('WrongSign', 'throwMsg'), $data['data'] );
			}
			
			$this->_return($data['state'], $data['msg'], $data['data']);
		} else {
			$this->_return(false, 'the ' . $request['api'] . ' is not exist', null);
		}
	}
}