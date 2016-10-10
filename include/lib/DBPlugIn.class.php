<?php
/**
 * 插件类型处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBPlugIn{
	
	var $tbUserInfo = 'tbUserInfo'; //用户信息
	var $tbAppPlugInInfo = 'tbAppPlugInInfo'; //插件信息
	var $tbPlugInTypeInfo = 'tbPlugInTypeInfo'; //插件类型
	var $tbAppPlugInApiInfo = 'tbAppPlugInApiInfo'; //插件api地址
	var $tbAppPlugInInPutInfo = 'tbAppPlugInInPutInfo';//插件的输入输出参数
	var $tbAppOauthPlugInInfo = 'tbAppOauthPlugInInfo'; //应用附加功能信息
	
	public function __construct($model){
		$this->model = $model;
	
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 增加插件
	 */
	public function addPlugIn($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$pIcoCode = $fieldArr['pIcoCode_512'].',512|'.$fieldArr['pIcoCode_256'].',256|'.$fieldArr['pIcoCode_128'].',128|'.$fieldArr['pIcoCode_64'].',64';
			
			$data['UserID']         = $fieldArr['UserID'];
			$data['UID']            = $fieldArr['UID'];
			$data['PlugInName']     = $fieldArr['PlugInName'];
			$data['pUniqueCode']    = $fieldArr['pUniqueCode'];
			$data['PlugInName']     = $fieldArr['PlugInName'];
			$data['PlugInCode']     = $fieldArr['PlugInCode'];
			$data['PlugInTypeID']   = $fieldArr['PlugInTypeID'];
			$data['pProperty']      = $fieldArr['PlugInProperty'];
			$data['pIcoCode']       = $pIcoCode;		
			$data['pPlugInState']   = $fieldArr['pPlugInState'];
			$data['pInputState']    = $fieldArr['pInputState'];
			$data['pOutputState']   = $fieldArr['pOutputState'];
			$data['pPoint']         = $fieldArr['pPoint'];
			$data['pLevel']         = isset($fieldArr['pLevel']) ? $fieldArr['pLevel'] : 1;
			$data['pUrl']           = $fieldArr['pUrl'];
			$data['pStatues']       = 2; //待审核
			$data['pAppendTime']    = time();
			$data['pUpdateTime']    = time();

			return $this->model->table($this->tbAppPlugInInfo)->data($data)->insert();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 增加插件的URL
	 */
	public function addAppPlugInApiInfo($fieldArr){
		try{
			$data['AppPlugInID'] = $fieldArr['AppPlugInID'];
		 	$data['aApiName']    = $fieldArr['aApiName'];
		 	$data['aUrl']        = $fieldArr['aUrl'];
		 	$data['aStatus']     = 0;
		 	$data['aAppendTime'] = time();
		 	$data['aUpdateTime'] = time();
			
			return $this->model->table($this->tbAppPlugInApiInfo)->data($data)->insert();
		}catch(Exception $e){
			return false;
		}
	}
	
	/**
	 * 增加输入参数
	 */
	public function addPlugInParams($fieldArr){
		try{
			$data['AppPlugInID']  = $fieldArr['AppPlugInID'];
			$data['ApiID']        = $fieldArr['ApiID'];
			$data['pFieldName']   = $fieldArr['pFieldName'];
			$data['pFieldType']   = $fieldArr['pFieldType'];
			$data['pFieldState']  = $fieldArr['pFieldState'];
			$data['pType']  	  = $fieldArr['pType'];
			$data['pStatus']      = 0;
			$data['pAppendTime']  = time();
			$data['pUpdateTime']  = time();
			
			$this->model->table($this->tbAppPlugInInPutInfo)->data($data)->insert();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新指定ID的信息
	 */
	public function updatePlugIn($fieldArr){
		try{
			$condition['AppPlugInID'] = $fieldArr['AppPlugInID'];
			
			$pIcoCode = $fieldArr['pIcoCode_512'].',512|'.$fieldArr['pIcoCode_256'].',256|'.$fieldArr['pIcoCode_128'].',128|'.$fieldArr['pIcoCode_64'].',64';
				
			$data['PlugInName']     = $fieldArr['PlugInName'];
			$data['PlugInCode']     = $fieldArr['PlugInCode'];
			$data['PlugInTypeID']   = $fieldArr['PlugInTypeID'];
			$data['pProperty']      = $fieldArr['PlugInProperty'];
			$data['pIcoCode']       = $pIcoCode;
			$data['pPlugInState']   = $fieldArr['pPlugInState'];
			$data['pInputState']    = $fieldArr['pInputState'];
			$data['pOutputState']   = $fieldArr['pOutputState'];
			$data['pPoint']         = $fieldArr['pPoint'];
			$data['pUrl']           = $fieldArr['pUrl'];
			$data['pUpdateTime']    = time();

			return $this->model->table($this->tbAppPlugInInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新插件的Api地址
	 */
	public function updateApiUrlInfo($fieldArr){
		try{
			$cond['ApiID']    = $fieldArr['ApiID'];
			
			$data['aApiName'] = $fieldArr['aApiName'];
			$data['aUrl']     = $fieldArr['aUrl'];
			$this->model->table($this->tbAppPlugInApiInfo)->data($data)->where($cond)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新插件的输入参数
	 */
	public function updateInputParamInfo($fieldArr){
		try{
			$cond['ParamsID']    = $fieldArr['ParamsID'];
			
			$data['AppPlugInID']  = $fieldArr['AppPlugInID'];
			$data['ApiID']        = $fieldArr['ApiID'];
			$data['pFieldName']   = $fieldArr['pFieldName'];
			$data['pFieldType']   = $fieldArr['pFieldType'];
			$data['pFieldState']  = $fieldArr['pFieldState'];
			$data['pType']  	  = $fieldArr['pType'];
			$data['pUpdateTime']  = time();
			
			$this->model->table($this->tbAppPlugInInPutInfo)->data($data)->where($cond)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新插件的输出参数
	 */
	public function updateOutputParamInfo($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

			if(is_array($fieldArr['opFieldName'])){
				foreach($fieldArr['opFieldName'] as $key=>$val){
					if($val){
						if($fieldArr['opParamsID'][$key]){
							$condition['ParamsID'] = $fieldArr['opParamsID'][$key];
							
							$data['pFieldName']  = $val;
							$data['pFieldType']  = $fieldArr['opFieldType'][$key];
							$data['pFieldState'] = $fieldArr['opFieldState'][$key];
							$data['pUpdateTime'] = time();

							$this->model->table($this->tbAppPlugInInPutInfo)->data($data)->where($condition)->update();
						}else{
							$tArr['AppPlugInID']  = $fieldArr['AppPlugInID'];
							$tArr['pFieldName']   = $val;
							$tArr['pFieldType']   = $fieldArr['opFieldType'][$key];
							$tArr['pFieldState']  = $fieldArr['opFieldState'][$key];
							$tArr['pType']  	  = 2;
							
							$this->addPlugInParams($tArr);
						}
					}
				}
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 检查插件名是否存在
	 */
	public function checkPlugInName($fieldArr){
		try{
			$where = 'PlugInName = \''.$fieldArr['PlugInName'].'\'';
			if($fieldArr['AppPlugInID']){
				$where .= ' and AppPlugInID != \''.$fieldArr['AppPlugInID'].'\'';
			}
			
			$re = $this->model->table($this->tbAppPlugInInfo)->field('AppPlugInID')->where($where)->select();

			if($re){
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 检查插件代码是否存在
	 */
	public function checkPlugInCode($fieldArr){
		try{
			$where = 'PlugInCode = \''.$fieldArr['PlugInCode'].'\'';
			if($fieldArr['AppPlugInID']){
				$where .= ' and AppPlugInID != \''.$fieldArr['AppPlugInID'].'\'';
			}
				
			$re = $this->model->table($this->tbAppPlugInInfo)->field('AppPlugInID')->where($where)->select();
				
			if($re){
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	
	/**
	 * 检验用户UserID是否有使用插件PlugInCode的权限
	 */
	public function isUserUsePlugInValid ( $fieldArr ) {
		$da = 0;
		try {
			$cond['UserID']     = $fieldArr['UserID'];
			$cond['PlugInCode'] = $fieldArr['PlugInCode'];
			
			$re = $this->model->table($this->tbAppPlugInInfo)->field('AppPlugInID')->where($cond)->select();
			if ( $re ) {
				return $re[0]['AppPlugInID'];
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 通过AppInfoID检验用户UserID是否有使用插件PlugInCode的权限
	 */
	public function getUserUsePlugInValid ( $fieldArr ) {
		$da = array();
		try {
			$sql = 'select a.AppPlugInID,a.PlugInCode,a.pUniqueCode,a.PlugInTypeID,b.AppInfoID,a.pUrl from ' . $this->tbAppPlugInInfo . ' as a left join ' . $this->tbAppOauthPlugInInfo . ' b on a.AppPlugInID = b.AppPlugInID
where  b.AppInfoID = \'' . $fieldArr['AppInfoID'] . '\' and a.pUniqueCode = \'' . $fieldArr['pUniqueCode'] . '\'';

			$re = $this->model->query($sql);
			
			if ( $re ) {
				return $re[0];
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 取指定ID的信息
	 */
	public function getPlugInByID($AppPlugInID){
		try{
			$where = ' where a.pStatues != 1 and a.AppPlugInID = \''.$this->Addslashes->get_addslashes($AppPlugInID).'\'';
			$field = 'a.AppPlugInID,a.pUniqueCode,a.PlugInName,a.PlugInCode,a.PlugInTypeID,a.pProperty,b.PlugInType,a.pPlugInState,a.pIcoCode,a.pInputState,a.pOutputState,a.pPoint,a.pLevel,a.pUrl,a.pDefault,a.pStatues,a.pAppendTime,a.pUpdateTime';
			$sql = 'select '.$field.' from '.$this->tbAppPlugInInfo.' as a left join '.$this->tbPlugInTypeInfo.' as b on a.PlugInTypeID = b.PlugInTypeID '.$where;
			
			return $this->model->query($sql);
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 取插件信息
	 */
	public function getPlugInList($fieldArr='', $order='pUpdateTime desc'){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$where = ' where a.pStatues != 1';
			$field = 'a.AppPlugInID,a.PlugInName,a.PlugInCode,a.pProperty,a.PlugInTypeID,b.PlugInType,a.pIcoCode,a.pInputState,a.pOutputState,a.pLevel,a.pStatues,a.pAppendTime,a.pUpdateTime';
			$sql = 'select '.$field.' from '.$this->tbAppPlugInInfo.' as a left join '.$this->tbPlugInTypeInfo.' as b on a.PlugInTypeID = b.PlugInTypeID '.$where.' order by '.$order;
			
			return $this->model->query($sql);
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 通过curl选取的数据
	 * 取插件信息
	 */
	public function getPlugInListByCurl($fieldArr='', $order='pUpdateTime desc'){
		try{
			$where = ' where a.pStatues != 1';
			
			if ( $fieldArr['pUniqueCode'] ) {
				$pUniqueCode = explode(',', $fieldArr['pUniqueCode']);
				$where .= ' and a.pUniqueCode in (\'' . implode('\',\'', $pUniqueCode) . '\')';
			}
			
			$field = 'a.AppPlugInID,a.PlugInName,a.pUniqueCode,a.PlugInCode,a.pProperty,a.PlugInTypeID,b.PlugInType,a.pIcoCode,a.pInputState,a.pOutputState';
			$sql = 'select '.$field.' from '.$this->tbAppPlugInInfo.' as a left join '.$this->tbPlugInTypeInfo.' as b on a.PlugInTypeID = b.PlugInTypeID '.$where.' order by '.$order;
				
			return $this->model->query($sql);
		}catch(Exception $e){
			return false;
		}
	}
	
	
	
	/**
	 * 取文章分页列表
	 */
	public function getPlugInPage($fieldArr='',$order='pUpdateTime desc',$pagesize=10,$page=1){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
	
			$page = $page ? $page : 1;
			$limit_start = ($page - 1) * $pagesize;
			$limit = 'limit '.$limit_start . ',' . $pagesize;
	
			$where = ' where a.pStatues != 1';
			$condition['pStatues'] = $fieldArr['pStatues'];
			
			if($fieldArr['UserID']){
				$where .= ' and a.UserID = \''.$fieldArr['UserID'].'\'';
				$condition['UserID'] = $fieldArr['UserID'];
			}

			// 获取行数
			$count = $this->model->table($this->tbAppPlugInInfo)->field('AppPlugInID')->where($condition)->count();
				
			$field = 'a.AppPlugInID,a.PlugInName,a.PlugInCode,a.PlugInTypeID,b.PlugInType,a.pIcoCode,a.pPlugInState,a.pInputState,a.pOutputState,a.pLevel,a.pStatues,a.pAppendTime,a.pUpdateTime';
			$sql = 'select '.$field.' from '.$this->tbAppPlugInInfo.' as a left join '.$this->tbPlugInTypeInfo.' as b on a.PlugInTypeID = b.PlugInTypeID ' . $where . ' order by ' . $order . ' ' . $limit;;
			$list = $this->model->query($sql);
			
			return array (
					'count' => $count,
					'list' => $list
			);
		}catch(Exception $e){
			return array (
					'count' => 0,
					'list' => null
			);
		}
	}
	/**
	 * 取插件api地址
	 */
	public function getApiUrlByID($AppPlugInID){
		try{
			$condition['AppPlugInID'] = $AppPlugInID;
			$condition['aStatus']     = 0;
			
			$field = 'ApiID,aApiName,aUrl';
			
			return $this->model->table($this->tbAppPlugInApiInfo)->field($field)->where($condition)->select();
		}catch(Exception $e){
			return null;
		}
	}
	/**
	 * 取插件输入输出参数
	 */
	public function getParamsByID($AppPlugInID){
		try{
			$condition['AppPlugInID'] = $this->Addslashes->do_addslashes($AppPlugInID);
			$condition['pStatus']     = 0;
				
			$field = 'ApiID,ParamsID,pFieldName,pFieldType,pFieldState,pType';
				
			return $this->model->table($this->tbAppPlugInInPutInfo)->field($field)->where($condition)->select();
		}catch(Exception $e){
			return null;
		}
	}
	
	/**
	 * 取指定接口配置信息
	 */
	public function getApiBaseInfo ( $fieldArr ) {
		$da = array();
		try {
			$cond['AppPlugInID'] = $fieldArr['AppPlugInID'];
			$cond['aApiName']    = $fieldArr['aApiName'];
			
			$re = $this->model->table($this->tbAppPlugInApiInfo)->field('ApiID,aUrl')->where($cond)->select();
			
			if ( $re ) {
				return $re[0];
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 返回接口配置的输入参数
	 */
	public function getApiParams ( $fieldArr ) {
		$da = array();
		try {
			$cond['ApiID'] = $fieldArr['ApiID'];
			if ( isset($fieldArr['pType']) ) {
				$cond['pType'] = $fieldArr['pType'];
			}
			
			return $this->model->table($this->tbAppPlugInInPutInfo)->field('pFieldName,pFieldType,pFieldState,pType')->where($cond)->select();
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 删除api地址
	 */
	public function delApiUrlByID($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$condition['ApiID'] = $fieldArr['ApiID'];
			
			return $this->model->table($this->tbAppPlugInApiInfo)->where($condition)->delete();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 删除参数信息
	 */
	public function delParamByByID($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
				
			$condition['ParamsID'] = $fieldArr['ParamsID'];
				
			return $this->model->table($this->tbAppPlugInInPutInfo)->where($condition)->delete();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 删除参数信息
	 */
	public function delParamByApiID($fieldArr){
		try{
			$condition['ApiID'] = $fieldArr['ApiID'];
	
			return $this->model->table($this->tbAppPlugInInPutInfo)->where($condition)->delete();
		}catch(Exception $e){
			return false;
		}
	}
	
	/**
	 * 取用户扩展信息列表
	 */
	public function getExpandListByUserID ( $user_id ) {
		$da = array();
		try {
			return $this->model->query('select a.pUniqueCode,
												a.PlugInName,
												a.PlugInCode,
												a.PlugInTypeID,
												a.pProperty,
												a.pIcoCode,
												a.pPlugInState,
												a.pInputState,
												a.pOutputState,
												a.pPoint,
												a.pLevel,
												a.pUrl,
												a.pDefault,
												a.pStatues,
												a.pInside from ' . $this->tbAppPlugInInfo . ' as a right join ' . $this->tbUserInfo . ' as b on a.UID = b.UID
												where b.UserID = \'' . $user_id . '\'');
		} catch ( Exception $e ) {
			return $da;
		}
	}
}