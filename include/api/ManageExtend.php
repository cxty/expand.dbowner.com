<?php
/**
 * SOAP服务器处理类
*
* @author wbqing405@sina.com
*/
include('Server.class.php');

class ManageExtend extends Server{

	var $tbUserInfo = 'tbUserInfo'; //用户授权信息
	var $tbPlugInTypeInfo = 'tbPlugInTypeInfo'; //插件类型
	var $tbAppPlugInInfo = 'tbAppPlugInInfo'; //插件信息
	var $tbOauthPermInfo = 'tbOauthPermInfo'; //授权登录权限信息
	var $tbInviteCodeInfo = 'tbInviteCodeInfo'; //邀请码信息表
	var $tbAppOauthPlugInInfo = 'tbAppOauthPlugInInfo'; //应用附加功能信息
	var $tbUserOauthPermInfo = 'tbUserOauthPermInfo'; //用户登录授权信息
	var $tbAppSoapLogInfo = 'tbAppSoapLogInfo'; //接口访问记录
	var $tbAppPlugInApiInfo = 'tbAppPlugInApiInfo'; //插件API地址
	var $tbAppPlugInInPutInfo = 'tbAppPlugInInPutInfo'; //插件输入输出参数
	
	public $authorized = false;

	public function __construct($model=null){
		$this->model = $model;
		include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
		$this->config      = $config;
		
		$this->SOAP_USER   = $this->config['DES']['SOAP_USER'];
		$this->DES_PWD     = $this->config['DES']['SOAP_PWD'];
		$this->DES_IV      = $this->config['DES']['SOAP_IV'];
		$this->user        = $this->config['DES']['SOAP_USER'];

		$this->ClientIP = ComFun::GetIP ();
		
		if (! in_array ( $this->ClientIP, $this->config ['DES']['SOAP_SERVER_CLIENTIP'] )) {
			$this->authorized = false;
			return parent::Unauthorized_IP();
		}
	}

	/**
	 * 接口鉴权
	 *
	 * @param array $a
	 * @throws SoapFault
	 */
	public function Auth($a) {
		if ($a->user === $this->user) {
			$this->authorized = true;
			return $this->_return ( true, 'OK', null );
		} else {
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 负责data加密
	 *
	 * @see Service::_return()
	 */
	public function _return($state, $msg, $data) {
		if($data){
			return parent::_return ( $state, $msg, $this->_encrypt ( json_encode ( array (
					'data' => $data
			) ), $this->DES_PWD, $this->DES_IV ) );
		}else{
			return parent::_return ( $state, $msg, $data );
		}
	}
	/**
	 * 负责解密data,还原客户端传来的参数
	 */
	public function _value($data) {
		if (isset ( $data )) {
			return json_decode ( trim ( $this->_decrypt ( $data, $this->DES_PWD , $this->DES_IV ) ) );
		} else {
			return $data;
		}
	}
	/**
	 * 数组转化
	 */
	public function arrAddslashes($data){
		foreach($data as $key=>$val){
			$rb[$key] = parent::_addslashes($val);
		}
		return $rb;
	}
	/**
	 * 字符串转化
	 */
	public function strAddslashes($str){
		return parent::_addslashes($str);
	}
	/**
	 * 数据库连接
	 */
	public function requireConnect(){
		$this->connect = parent::RequireClass($this->model);
	}
	
	//=====以下是后台调用接口=====
	//用户信息
	/**
	 * 取用户信息
	 */
	public function SelectUserInfo($pa){
		//return $this->_return ( true, 'OK', $this->ClientIP );
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$exp = parent::RequireClass($this->model);
	
				//$field = 'UID,GroupID,UserID,uName,uLevel,uState,uAppendTime,uUpdateTime';
				
				$rb = $exp->seTableData($this->tbUserInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户信息
	 */
	public function UpdateUserInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$condition['UID'] = $this->strAddslashes($data->UID);
	
				if(isset($data->GroupID)){
					$udata['GroupID'] = $this->strAddslashes($data->GroupID);
				}
				if(isset($data->uName)){
					$udata['uName'] = $this->strAddslashes($data->uName);
				}
				if(isset($data->uLevel)){
					$udata['uLevel']  = $this->strAddslashes($data->uLevel);
				}
				if(isset($data->uState)){
					$udata['uState']  = ($this->strAddslashes($data->uState) == 1 )  ? 1 : 0;
				}
				
				$udata['uUpdateTime']  = time();
	
				$rb = $exp->upTableData($this->tbUserInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户信息列表
	 */
	public function GetUserInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->geTableData($this->tbUserInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户信息
	 */
	public function InsertUserInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->UserID)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->uName)){
					$this->_return ( false, 'Value Missing', $rb );
				}
	
				$exp = parent::RequireClass($this->model);
	
				$idata['GroupID']     = isset($data->GroupID) ? $this->strAddslashes($data->GroupID) : 0;
				$idata['UserID']      = $this->strAddslashes($data->UserID);
				$idata['uName']       = $this->strAddslashes($data->uName);
				$idata['uPermission'] = $this->strAddslashes($data->uPermission);
				$idata['uLevel']      = isset($data->uLevel) ? $this->strAddslashes($data->uLevel) : 0;
				$idata['uState']      = 0;
				$idata['uAppendTime'] = time();
				$idata['uUpdateTime'] = time();
				
	
				$rb = $exp->inTableData($this->tbUserInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户信息
	 */
	public function DeleteUserInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbUserInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//插件类型
	/**
	 * 取插件类型
	 */
	public function SelectPlugInTypeInfo($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$field = 'PlugInTypeID,PlugInType,pStatues,pAppendTime';

				$rb = $exp->seTableData($this->tbPlugInTypeInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新插件类型
	 */
	public function UpdatePlugInTypeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				$exp = parent::RequireClass($this->model);
	
				//$condition['PlugInTypeID'] = $this->strAddslashes($data->PlugInTypeID);
	
				if(isset($data->PlugInType)){
					$udata['PlugInType'] = $this->strAddslashes($data->PlugInType);
				}
				if(isset($data->pStatues)){
					$udata['pStatues'] = $this->strAddslashes($data->pStatues);
				}
	
				$rb = $exp->upTableData($this->tbPlugInTypeInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取插件类型 列表
	 */
	public function GetPlugInTypeInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				$exp = parent::RequireClass($this->model);

				$rb = $exp->geTableData($this->tbPlugInTypeInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加插件类型
	 */
	public function InsertPlugInTypeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->PlugInType)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				
				$exp = parent::RequireClass($this->model);
				
				$tArr['PlugInType'] = $this->strAddslashes($data->PlugInType);
				
				if($exp->seTableData($this->tbPlugInTypeInfo, $tArr)){
					return $this->_return ( false, 'PlugInType Repeat', null );
				}
				
				
				
				$PlugInTypeID = isset($data->PlugInTypeID) ? $this->strAddslashes($data->PlugInTypeID) : 0;
				
				$tArr2['pPID'] = $PlugInTypeID;
				$field = 'PlugInTypeID,PlugInType,pDepth,pOrder,pStatues,pAppendTime';
				$order = 'pOrder desc';
				$rb2 = $exp->seTableData($this->tbPlugInTypeInfo, $tArr2, $order, $field);
				
				if($rb2){
					$pDepth = $rb2[0]['pDepth'];
					$pFID   = $rb2[0]['PlugInTypeID'];
					$pOrder = count($rb2) + 1;
				}else{
					$tArr3['PlugInTypeID'] = $PlugInTypeID;
					$rb3 = $exp->seTableData($this->tbPlugInTypeInfo, $tArr3, '', 'pDepth,pOrder');
										
					$pDepth = $rb3 ? ($rb3[0]['pDepth']+1) : 1;
					$pFID   = $PlugInTypeID;
					$pOrder = 1;;
				}
				
				$idata['PlugInType']    = $this->strAddslashes($data->PlugInType);
				$idata['pDepth']        = $pDepth;		
				$idata['pPID']          = $PlugInTypeID;
				$idata['pFID']          = $pFID;
				$idata['pOrder']        = $pOrder;
				$idata['pStatues']      = 0;
				$idata['pAppendTime']   = time();
				
				$rb = $exp->inTableData($this->tbPlugInTypeInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除插件类型
	 */
	public function DeletePlugInTypeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbPlugInTypeInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//插件信息
	/**
	 * 取
	 */
	public function SelectAppPlugInInfo($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				//return $this->_return ( true, 'OK', $data->condition );
				$exp = parent::RequireClass($this->model);
	
				//$field = 'AppPlugInID,PlugInName,PlugInCode,PlugInTypeID,pIcoCode,pInputState,pOutputState,pLevel,pStatues,pAppendTime,pUpdateTime';
	
				$rb = $exp->seTableData($this->tbAppPlugInInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				if($rb){
					foreach($rb as $key=>$val){
						
						$tArr['PlugInTypeID'] = $val['PlugInTypeID'];
						
						$re = $exp->seTableData($this->tbPlugInTypeInfo,$tArr,'', 'PlugInType');
											
						if($re){
							$rb[$key]['PlugInType'] = $re[0]['PlugInType'];
						}else{
							$rb[$key]['PlugInType'] = '';
						}
					}
				}
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新
	 */
	public function UpdateAppPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$condition['AppPlugInID'] = $this->strAddslashes($data->AppPlugInID);
	
				if(isset($data->PlugInName)){
					$udata['PlugInName'] = $this->strAddslashes($data->PlugInName);
				}
				if(isset($data->PlugInCode)){
					$udata['PlugInCode'] = $this->strAddslashes($data->PlugInCode);
				}
				if(isset($data->PlugInTypeID)){
					$udata['PlugInTypeID'] = $this->strAddslashes($data->PlugInTypeID);
				}
				if(isset($data->pIcoCode)){
					$udata['pIcoCode'] = $this->strAddslashes($data->pIcoCode);
				}
				if(isset($data->pInputState)){
					$udata['pInputState'] = $this->strAddslashes($data->pInputState);
				}
				if(isset($data->pOutputState)){
					$udata['pOutputState'] = $this->strAddslashes($data->pOutputState);
				}
				if(isset($data->pLevel)){
					$udata['pLevel'] = $this->strAddslashes($data->pLevel);
				}
				if(isset($data->pStatues)){
					$udata['pStatues'] = $this->strAddslashes($data->pStatues);
				}
	
				$udata['pUpdateTime'] = time();
				
				$rb = $exp->upTableData($this->tbAppPlugInInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取列表
	 */
	public function GetAppPlugInInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$field = 'AppPlugInID,PlugInName,PlugInCode,PlugInTypeID,pIcoCode,pInputState,pOutputState,pLevel,pStatues,pAppendTime,pUpdateTime';
				
				$rb = $exp->geTableData($this->tbAppPlugInInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$data->condition,$data->order);
	
				if($rb['list']){
					foreach($rb['list'] as $key=>$val){
				
						$tArr['PlugInTypeID'] = $val['PlugInTypeID'];
				
						$re = $exp->seTableData($this->tbPlugInTypeInfo,$tArr,'', 'PlugInType');
				
						if($re){
							$rb['list'][$key]['PlugInType'] = $re[0]['PlugInType'];
						}else{
							$rb['list'][$key]['PlugInType'] = '';
						}
					}
				}
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加
	 */
	public function InsertAppPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
				
				$PlugInName = $this->strAddslashes($data->PlugInName);
				$PlugInCode = $this->strAddslashes($data->PlugInCode);
				
				if(!isset($PlugInName)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($PlugInCode)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->PlugInTypeID)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->pIcoCode)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				
				
				$tArr['PlugInName'] = $PlugInName;
				$rb = $exp->seTableData($this->tbAppPlugInInfo,$tArr,'', 'AppPlugInID');
				if($rb){
					return $this->_return ( true, 'Value Repeat', false );
				}
				
				unset($tArr['PlugInName']);
					
				if(preg_match("/^[a-zA-Z]*$/", $PlugInCode)){
					$tArr['PlugInCode'] = $PlugInCode;
					$rb = $exp->seTableData($this->tbAppPlugInInfo,$tArr,'', 'AppPlugInID');
					if($rb){
						return $this->_return ( true, 'Value Repeat', false );
					}
				}else{
					return $this->_return ( true, 'Not Pure English', false );
				}	
	
				$idata['PlugInName']    = $PlugInName;
				$idata['PlugInCode']    = $PlugInCode;
				$idata['PlugInTypeID']  = $this->strAddslashes($data->PlugInTypeID);
				$idata['pIcoCode']      = $this->strAddslashes($data->pIcoCode);
				$idata['pInputState']   = $this->strAddslashes($data->pInputState);
				$idata['pOutputState']  = $this->strAddslashes($data->pOutputState);
				$idata['pLevel']        = isset($data->pIcoCode) ? $this->strAddslashes($data->pIcoCode) : 1;
				$idata['pStatues']      = 0;
				$idata['pAppendTime']   = time();
				$idata['pUpdateTime']   = time();
	
				$rb = $exp->inTableData($this->tbAppPlugInInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除
	 */
	public function DeleteAppPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbAppPlugInInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//授权登录权限信息
	/**
	 * 取
	 */
	public function SelectOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$exp = parent::RequireClass($this->model);
	
// 				if($data->condition){
// 					$where = 'oStatues = 0 and '.$this->strAddslashes($data->condition);
// 				}else{
// 					$where = 'oStatues = 0';
// 				}
				
				//$field = 'PermCode,oState,oRead,oWrite,oDelete';
	
				$rb = $exp->seTableData($this->tbOauthPermInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新
	 */
	public function UpdateOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$exp = parent::RequireClass($this->model);
	
				//$condition['OauthPermID'] = $this->strAddslashes($data->OauthPermID);
	
				if(isset($data->AppGroupID)){
					$udata['AppGroupID'] = $this->strAddslashes($data->AppGroupID);
				}
				if(isset($data->PermCode)){
					$PermCode = $this->strAddslashes($data->PermCode);
					
					$tArr['PermCode'] = $PermCode;
					$Nrb = $exp->seTableData($this->tbOauthPermInfo,$tArr);
					$Orb = $exp->seTableData($this->tbOauthPermInfo,$this->strAddslashes($data->condition));
					
					if($Nrb && $Orb){
						if($Nrb[0]['PermCode'] == $PermCode && $Orb[0]['PermCode'] != $PermCode){
							return $this->_return ( false, 'PermCode already exists', null );
						}
					}

					$udata['PermCode'] = $PermCode;
				}
				if(isset($data->oState)){
					$udata['oState'] = $this->strAddslashes($data->oState);
				}
				if(isset($data->oIsDefaule)){
					$udata['oIsDefaule'] = $this->strAddslashes($data->oIsDefaule);
				}
				if(isset($data->oIsDisable)){
					$udata['oIsDisable'] = $this->strAddslashes($data->oIsDisable);
				}
				if(isset($data->oRead)){
					$udata['oRead'] = $this->strAddslashes($data->oRead);
				}
				if(isset($data->oWrite)){
					$udata['oWrite'] = $this->strAddslashes($data->oWrite);
				}
				if(isset($data->oDelete)){
					$udata['oDelete'] = $this->strAddslashes($data->oDelete);
				}
				if(isset($data->oStatues)){
					$udata['oStatues'] = $this->strAddslashes($data->oStatues);
				}
	
				$udata['oUpdateTime'] = time();
	
				$rb = $exp->upTableData($this->tbOauthPermInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取列表
	 */
	public function GetOauthPermInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
				
// 				if($data->condition){
// 					$where = 'oStatues = 0 and '.$this->strAddslashes($data->condition);
// 				}else{
// 					$where = 'oStatues = 0';
// 				}
				//$field = 'PermCode,oState,oRead,oWrite,oDelete';
				
				$rb = $exp->geTableData($this->tbOauthPermInfo, parent::getListPage($data->page), parent::getListPageSize($data->pagesize), $this->strAddslashes($data->condition), $this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加
	 */
	public function InsertOauthPermInfo($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$exp = parent::RequireClass($this->model);
	
				$PermCode = $this->strAddslashes($data->PermCode);

				if(!isset($PermCode)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				
				if(parent::checkEnglishValue($PermCode)){
					$tArr['PermCode'] = $PermCode;
					$rb = $exp->seTableData($this->tbOauthPermInfo,$tArr,'', 'OauthPermID');
					if($rb){
						return $this->_return ( false, 'Value Repeat', null );
					}
				}else{
					return $this->_return ( true, 'Not Pure English', false );
				}
				
				$idata['AppGroupID']  = isset($data->AppGroupID) ? $this->strAddslashes($data->AppGroupID) : 0;				
				$idata['PermCode']    = $this->strAddslashes($data->PermCode);
				$idata['oState']      = $this->strAddslashes($data->oState);
				$idata['oIsDefaule']  = isset($data->oIsDefaule) ? $this->strAddslashes($data->oIsDefaule) : 0;
				$idata['oIsDisable']  = isset($data->oIsDisable) ? $this->strAddslashes($data->oIsDisable) : 0;
				$idata['oRead']       = isset($data->oRead) ? $this->strAddslashes($data->oRead) : 1;
				$idata['oWrite']      = isset($data->oWrite) ? $this->strAddslashes($data->oWrite) : 0;
				$idata['oDelete']     = isset($data->oDelete) ? $this->strAddslashes($data->oDelete) : 0;
				$idata['oStatues']    = isset($data->oStatues) ? $this->strAddslashes($data->oStatues) : 0;
				$idata['oAppendTime'] = time();
				$idata['oUpdateTime'] = time();

				$rb = $exp->inTableData($this->tbOauthPermInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除
	 */
	public function DeleteOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbOauthPermInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//邀请码信息表
	/**
	 * 取
	 */
	public function SelectInviteCodeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$field = 'AppInfoID,InviteCode,FUID,TUID,iAppendTime,iUseTime';
	
				$rb = $exp->seTableData($this->tbInviteCodeInfo, $this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新
	 */
	public function UpdateInviteCodeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				$exp = parent::RequireClass($this->model);
				
				if(isset($data->TUID) && isset($data->InviteCode)){
					//$udata['InviteCode'] = $this->strAddslashes($data->InviteCode);
					$udata['TUID']       = $this->strAddslashes($data->TUID);
					$udata['iUseTime']   = time();
				}
				if(isset($data->iStatus)){
					$udata['iStatus'] = $this->strAddslashes($data->iStatus);
				}
	
				$rb = $exp->upTableData($this->tbInviteCodeInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 用户激活帐号
	 */
	public function GetActiveInviteCode($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->seTableData($this->tbInviteCodeInfo, $this->strAddslashes($data->condition));

				if($rb){
					if($rb[0]['TUID']){
						return $this->_return ( true, 'OK', '-2' ); //激活码已经被使用
					}
				}else{
					return $this->_return ( true, 'OK', '-1' ); //激活码不存在
				}
				
				if(!isset($data->TUID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}
				
				$udata['TUID']       = $this->strAddslashes($data->TUID);
				$udata['iUseTime']   = time();
				
				$rb = $exp->upTableData($this->tbInviteCodeInfo,$this->strAddslashes($data->condition),$udata);
				
				return $this->_return ( true, 'OK', $rb );
				
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取列表
	 */
	public function GetInviteCodeInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
				
				//$field = 'AppInfoID,InviteCode,FUID,TUID,iAppendTime,iUseTime';
				
// 				$condition = $this->strAddslashes($data->condition);
// 				if($condition){
// 					$where = 'iStatus = 0 and '.$condition;
// 				}else{
// 					$where = 'iStatus = 0';
// 				}
				
				$rb = $exp->geTableData($this->tbInviteCodeInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize), $this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加
	 */
	public function InsertInviteCodeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				if(!isset($data->AppInfoID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->FUID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}

				$exp = parent::RequireClass($this->model);
				
				$InviteCode = parent::getUniqueInviteCode($exp);
	
				$idata['AppInfoID']   = $this->strAddslashes($data->AppInfoID);
				$idata['InviteCode']  = $InviteCode;
				$idata['FUID']        = $this->strAddslashes($data->FUID);
				$idata['iStatus']     = 0;
				$idata['iAppendTime'] = time();

				$rb = $exp->inTableData($this->tbInviteCodeInfo,$idata);
				
				if($rb){
					$rbk['InviteCodeID'] = $rb;
					$rbk['InviteCode']   = $InviteCode;
				}else{
					$rbk = $rb;
				}

				return $this->_return ( true, 'OK', $rbk );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除
	 */
	public function DeleteInviteCodeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbInviteCodeInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//应用附加功能信息
	/**
	 * 取
	 */
	public function SelectAppOauthPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$field = 'AppOauthPlusID,AppPlugInID,AppInfoID,PlusCode,pAppendTime,pUpdateTime,pExpTime,pStatues';
	
				$rb = $exp->seTableData($this->tbAppOauthPlugInInfo, $this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新
	 */
	public function UpdateAppOauthPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);

				if(!isset($data->AppInfoID)){
					return $this->_return ( false, 'Value Missing', 'Missing AppInfoID' );
				}else{
					$condition['AppInfoID'] = $data->AppInfoID;
				}
				
				if(empty($data->Plus)){
					return $this->_return ( false, 'Value Missing', 'Plus is not Empty' );					
				}else{
					if(!is_array($data->Plus)){
						return $this->_return ( false, 'Value Missing', 'Plus is not Array' );
					}
				}
				
				foreach($data->Plus as $key=>$val){
					$idArr[] = $val->AppPlugInID;
					$condition['AppPlugInID'] = $val->AppPlugInID;
							
					$tArr['OrderID']  = $val->OrderID;
					$tArr['pExpTime'] = $val->pExpTime;
					$tArr['pStatues'] = isset($val->pStatues) ? $val->pStatues : 0;
						
					if($exp->seTableData($this->tbAppOauthPlugInInfo, $condition, '','AppPlugInID')){
						$tArr['pUpdateTime']  = time();
			
						$exp->upTableData($this->tbAppOauthPlugInInfo, $condition, $tArr);
					}else{
						$tArr['AppInfoID']    = $data->AppInfoID;
						$tArr['AppPlugInID']  = $val->AppPlugInID;
						$tArr['pAppendTime']  = time();
						$tArr['pUpdateTime']  = time();
			
						$exp->inTableData($this->tbAppOauthPlugInInfo, $tArr);
					}
				}
				
				unset($condition['AppPlugInID']);
				
				$reArr = $exp->seTableData($this->tbAppOauthPlugInInfo, $condition, '','AppPlugInID');
				
				if($reArr){
					$_reArr = array();
					foreach($reArr as $key=>$val){
						if(!in_array($val['AppPlugInID'], $idArr)){
							$_reArr[] = $val['AppPlugInID'];
						}
					}
					
					if($_reArr){
						$where = 'AppInfoID = \''.$data->AppInfoID.'\' and AppPlugInID in ('.implode(',', $_reArr).')';
							
						$rb = $exp->deTableData($this->tbAppOauthPlugInInfo, $where);
					}	
				}

				return $this->_return ( true, 'OK', 'success');
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取列表
	 */
	public function GetAppOauthPlugInInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->geTableData($this->tbAppOauthPlugInInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				//return $this->_return ( true, 'OK', $rb );
				if($rb['list']){
					foreach($rb['list'] as $key=>$val){
						$condition['AppPlugInID'] = $val['AppPlugInID'];
						$re = $exp->seTableData($this->tbAppPlugInInfo,$condition);
						if($re){
							$rb['list'][$key]['PlugInName'] = $re[0]['PlugInName'];
						}else{
							$rb['list'][$key]['PlugInName'] = '';
						}
					}
				}
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加
	 */
	public function InsertAppOauthPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->AppPlugInID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->AppInfoID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}

				$exp = parent::RequireClass($this->model);
	
				$condition['AppPlugInID'] = $this->strAddslashes($data->AppPlugInID);
				$condition['AppInfoID'] = $this->strAddslashes($data->AppInfoID);
				
				$re = $exp->seTableData($this->tbAppOauthPlugInInfo, $this->strAddslashes($condition), '', 'AppOauthPlusID');
	
				if($re){
					$rb = $re[0]['AppOauthPlusID'];
				}else{
					$idata['AppPlugInID']   = $this->strAddslashes($data->AppPlugInID);
					$idata['AppInfoID']     = $this->strAddslashes($data->AppInfoID);
					$idata['pAppendTime']   = time();
					$idata['pUpdateTime']   = time();
					$idata['pExpTime']      = isset($data->pExpTime) ? $this->strAddslashes($data->pExpTime) : 0;
					$idata['pStatues']      = 0;
					
					$rb = $exp->inTableData($this->tbAppOauthPlugInInfo,$idata);
				}
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除
	 */
	public function DeleteAppOauthPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				return $this->_return ( true, 'OK', $data );
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbAppOauthPlugInInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//用户登录授权信息
	/**
	 * 取
	 */
	public function SelectUserOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$field = 'UserOauthPermID,AppInfoID,UserID,uPermission,uStatues,uAppendTime,uUpdateTime';
	
				$rb = $exp->seTableData($this->tbUserOauthPermInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新
	 */
	public function UpdateUserOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$condition['UserOauthPermID'] = $this->strAddslashes($data->UserOauthPermID);
	
				if(isset($data->uPermission)){
					$udata['uPermission']    = $this->strAddslashes($data->uPermission);
				}
				if(isset($data->uStatues)){
					$udata['uStatues']    = $this->strAddslashes($data->uStatues);
				}
				$udata['uUpdateTime'] = time();
	
				$rb = $exp->upTableData($this->tbUserOauthPermInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取列表
	 */
	public function GetUserOauthPermInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->geTableData($this->tbUserOauthPermInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加
	 */
	public function InsertUserOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->AppInfoID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->UserID)){
					return $this->_return ( false, 'Value Missing', $rb );
				}
				
				$exp = parent::RequireClass($this->model);
				
				$condition['AppInfoID']   = $this->strAddslashes($data->AppInfoID);
				$condition['UserID']     = $this->strAddslashes($data->UserID);

				$re = $exp->seTableData($this->tbUserOauthPermInfo, $condition);
				
				if($re){
					$nPermArr = json_decode($this->strAddslashes($data->uPermission), true);
					$uPermArr = json_decode($re[0]['uPermission'], true);

					if(is_array($uPermArr) && is_array($nPermArr)){
						foreach($uPermArr as $key=>$val){
							if(intval($val['AppPlugInID']) == intval($nPermArr[0]['AppPlugInID'])){
								$uPermArr[$key]['Attach'] = $nPermArr[0]['Attach'];
							}
						}
					}
					
					$udata['uPermission'] = json_encode($uPermArr);
					$udata['uUpdateTime'] = time();

					$exp->upTableData($this->tbUserOauthPermInfo, $condition, $udata);
					
					$rb = $re[0]['UserOauthPermID']; 
				}else{
					$idata['AppInfoID']     = $this->strAddslashes($data->AppInfoID);
					$idata['UserID']        = $this->strAddslashes($data->UserID);
					$idata['uPermission']   = $this->strAddslashes($data->uPermission);
					$idata['uStatues']      = 0;
					$idata['uAppendTime']   = time();
					$idata['uUpdateTime']   = time();
						
					$rb = $exp->inTableData($this->tbUserOauthPermInfo,$idata);
				}
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除
	 */
	public function DeleteUserOauthPermInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbUserOauthPermInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//记录应用访问接口
	/**
	 * 取
	 */
	public function SelectAppSoapLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				//$field = 'UserOauthPermID,AppInfoID,UserID,uPermission,uStatues,uAppendTime,uUpdateTime';
	
				$rb = $exp->seTableData($this->tbAppSoapLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新
	 */
	public function UpdateAppSoapLogInfo($pa){
		return $this->_return ( true, 'OK', 'function is making' );
	}
	/**
	 * 取列表
	 */
	public function GetAppSoapLogInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->geTableData($this->tbAppSoapLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加
	 */
	public function InsertAppSoapLogInfo($pa){
		return $this->_return ( true, 'OK', 'function is making' );
	}
	/**
	 * 删除
	 */
	public function DeleteAppSoapLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$exp = parent::RequireClass($this->model);
	
				$rb = $exp->deTableData($this->tbAppSoapLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	//=====以下是特殊处理接口调用=====
	/**
	 * 取类
	 */
	private function getClass($className,$fieldArr=''){
		$connect = parent::getConnect();
		switch($className){
			case 'DBPlugInClass':
				include(dirname(dirname(__FILE__)).'/lib/DBPlugInClass.class.php');
				return new DBPlugInClass($connect);
				break;
			case 'DBextendSoap':
				include(dirname(dirname(__FILE__)).'/lib/DBextendSoap.class.php');
				return new DBextendSoap($this->config);
				break;
			default:
				break;
		}
	}
	/**
	 * 插件类型选择框
	 */
	public function GetPlugInTypeSelect($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$dbPlugInClass = $this->getClass('DBPlugInClass');
				
				$value = isset($data->value) ? $this->strAddslashes($data->value) : 0;
				$root = isset($data->root) ? $this->strAddslashes($data->root) : false;
				$selectName = isset($data->selectName) ? $this->strAddslashes($data->selectName) : 'PlugInTypeID';
				$select = $dbPlugInClass->getPlugInSelect($value, $selectName, $root);
				return $this->_return ( true, 'OK', $select );
			}else{
				return $this->_return ( false, 'Data Error', $rb);
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取类型分级数组
	 */
	public function GetPlugInTypeClass($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				if(empty($data->PlugInTypeID)){
					return $this->_return ( false, 'PlugInTypeID is Null', null);
				}
				$dbPlugInClass = $this->getClass('DBPlugInClass');
				$rb = $dbPlugInClass->getNavLevel($this->strAddslashes($data->PlugInTypeID));
				if($rb){
					return $this->_return ( true, 'OK', $rb);
				}else{
					return $this->_return ( false, 'PlugInTypeID is exist', null);
				}		
			}else{
				return $this->_return ( false, 'Data Error', $rb);
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 移动插件类别
	 */
	public function GetPlugInTypeClassMove($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if(!isset($data->PlugInTypeID)){
					return $this->_return ( false, 'PlugInTypeID is not exist', null);
				}
				if(!isset($data->pPID)){
					return $this->_return ( false, 'pPID is not exist', null);
				}

				$dbPlugInClass = $this->getClass('DBPlugInClass');
				$dbPlugInClass->movePlugInTypeClass($this->strAddslashes($data->PlugInTypeID), $this->strAddslashes($data->pPID));
				
				return $this->_return ( true, 'OK', true);
			}else{
				return $this->_return ( false, 'Data Error', $rb);
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 移动同父类下的排序
	 */
	public function GetPlugInTypeClassOrder($pa){
		return $this->_return ( false, 'function is not exist', null);
	}
	
	//=====以下是单独接口调用=====
	/**
	 * 连接数据库
	 */
	private function _requireConnect(){
		$this->_connect = parent::RequireClass($this->model);
	}
	/**
	 * 请求参数记录
	 */
	private function logRequestParams($fieldArr){
		if(isset($fieldArr['RequestParams'])){
			$data = $this->_value ( json_decode ( $fieldArr['RequestParams']->data )->data );
			
			$lArr['AppInfoID']     = $this->strAddslashes($data->AppInfoID);
			$lArr['SoapName']      = $fieldArr['SoapName'];
			$lArr['RequestParams'] = json_encode($data);
			$lArr['aRequestTime']  = time();
			
			$AppSoapLogID = $this->_connect->inTableData($this->tbAppSoapLogInfo,$lArr);
			
			if(!isset($data->AppInfoID)){
				$AppSoapLogID = false;
			}
		}else{
			$AppSoapLogID = false;
		}

		return $AppSoapLogID;
	}
	
	/**
	 * 取插件信息
	 */
	public function GetPlugInInfo($pa){	
		$this->_requireConnect();
		
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				//$rb = $this->_connect->seTableData($this->tbAppPlugInInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				$rb = $this->_connect->geTableData($this->tbAppPlugInInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				
				$rbArr['count'] = $rb['count'];
				
				if($rb['list']){
					foreach($rb['list'] as $key=>$val){
						$tArr['PlugInTypeID'] = $val['PlugInTypeID'];
				
						$re = $this->_connect->seTableData($this->tbPlugInTypeInfo,$tArr,'', 'PlugInType');

						$rbArr['list'][$key]['AppPlugInID']  = $val['AppPlugInID'];
						$rbArr['list'][$key]['PlugInTypeID'] = $val['PlugInTypeID'];
						if($re){
							$rbArr['list'][$key]['PlugInType'] = $re[0]['PlugInType'];
						}else{
							$rbArr['list'][$key]['PlugInType'] = '';
						}
						$rbArr['list'][$key]['PlugInName']   = $val['PlugInName'];
						$rbArr['list'][$key]['UserID']       = $val['UserID'];
						$rbArr['list'][$key]['PlugInCode']   = $val['PlugInCode'];
						$rbArr['list'][$key]['pIcoCode']     = $val['pIcoCode'];
						$rbArr['list'][$key]['pInputState']  = $val['pInputState'];
						$rbArr['list'][$key]['pOutputState'] = $val['pOutputState'];
						$rbArr['list'][$key]['pDefault']     = $val['pDefault'];
						$rbArr['list'][$key]['pStatues']     = $val['pStatues'];
						$rbArr['list'][$key]['pAppendTime']  = $val['pAppendTime'];
						$rbArr['list'][$key]['pUpdateTime']  = $val['pUpdateTime'];
					}
				}
			
				return $this->_return ( true, 'OK', $rbArr);
			}else{
				return $this->_return ( false, 'Data Error', $rb);
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取应用插件信息
	 */
	public function GetAppPlugInInfo($pa){
		$this->_requireConnect();

		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->AppInfoID)){					
					return $this->_return ( false, 'Value Missing', $rb );
				}

				$where = 'a.AppInfoID = \''.$data->AppInfoID.'\'';
				$field = 'a.OrderID,a.AppInfoID,a.pAppendTime,a.pUpdateTime,a.pExpTime';
				$field .= ',b.AppPlugInID,b.UserID,b.pUniqueCode,b.PlugInName,b.PlugInCode,b.PlugInTypeID,c.PlugInType,b.pProperty,b.pIcoCode,b.pPlugInState,b.pPoint,b.pLevel,b.pDefault';
				$sql = 'select '.$field.' from '.$this->tbAppOauthPlugInInfo.' as a left join '.$this->tbAppPlugInInfo.' as b on a.AppPlugInID =  b.AppPlugInID left join '.$this->tbPlugInTypeInfo.' as c on b.PlugInTypeID = c.PlugInTypeID where '.$where.' order by a.pUpdateTime desc';
			
				$rb = $this->_connect->getQueryData($sql);
				
				if($rb){
					foreach($rb as $ke=>$va){
						$condition['AppPlugInID'] = $va['AppPlugInID'];
						
						$api = $this->_connect->seTableData($this->tbAppPlugInApiInfo, $condition,'AppPlugInID asc','ApiID,aApiName as apiname');
						$re = $this->_connect->seTableData($this->tbAppPlugInInPutInfo, $condition, 'ApiID asc,pAppendTime asc', 'ApiID,pFieldName as fieldname,pFieldType as fieldtype,pFieldState as fieldstate,pType');
						if($api){
							foreach ( $api as $k2 => $v2 ) {
								$i = 0;
								$j = 0;
								foreach($re as $k3=>$v3){
									if ( $v2['ApiID'] == $v3['ApiID'] ) {
										if($v3['pType'] == 1){
											$api[$k2]['input'][$i] = $v3;
											unset($api[$k2]['input'][$i]['pType']);
											unset($api[$k2]['input'][$i]['ApiID']);
											$i++;
										}else{
											$api[$k2]['output'][$j] = $v3;
											unset($api[$k2]['output'][$j]['pType']);
											unset($api[$k2]['output'][$j]['ApiID']);
											$j++;
										}		
									}
								}
								unset($api[$k2]['ApiID']);
							}
						}
						
						$rb[$ke]['api'] = $api;
					}
				}
		
				return $this->_return ( true, 'OK', $rb);
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 用户登录授权信息
	 */
	public function GetAuthPlugInInfo($pa){
		$this->_requireConnect();
		
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				$rb = $this->_connect->seTableData($this->tbUserOauthPermInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
		
				return $this->_return ( true, 'OK', $rb);
			}else{
				return $this->_return ( false, 'Data Error', $rb);
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新删除数据
	 */
	public function SetAppPlugInInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				$this->_requireConnect();
				
				$rb = $this->_connect->seTableData($this->tbUserOauthPermInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
		
				return $this->_return ( true, 'OK', $rb);
			}else{
				return $this->_return ( false, 'Data Error', $rb);
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 取用户扩展插件列表
	 */
	public function GetUserExpandList ( $pa ) {
		if($this->authorized){
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( !isset($data->user_id) ) {
					return $this->_return ( false, 'user_id is missing', null );
				}
				
				$dbPlugIn = parent::getlib('DBPlugIn');
				$_rb = $dbPlugIn->getExpandListByUserID ( $data->user_id );
		
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', null );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
}
?>