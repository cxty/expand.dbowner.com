<?php
/**
 * 统一接口记录信息
 *
 * @author wbqing405@sina.com
 */
class DBUniformInterfaceLog {
	
	var $tbUniformInterfaceLogInfo = 'tbUniformInterfaceLogInfo'; //页面请求信息记录
	
	public function __construct ( $model ) {
		$this->model = $model;
	}
	
	/**
	 * 记录请求信息记录
	 */
	public function addUniformInterfaceRequest ( $fieldArr ) {
		try {
			$_data['uilRequestParams'] = json_encode($fieldArr['uilRequestParams']);
			$_data['uilRequestTime']   = time();
			
			return $this->model->table($this->tbUniformInterfaceLogInfo)->data($_data)->insert();
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * 更新响应信息
	 */
	public function updateUniformInterfaceRespond ( $fieldArr ) {
		try {
			$_cond['UniformInterfaceLogID'] = $fieldArr['UniformInterfaceLogID'];
			
			$_data['uilRespondParams'] = json_encode($fieldArr['uilRespondParams']);
			$_data['uilRespondTime']   = time();
			
			$this->model->table($this->tbUniformInterfaceLogInfo)->data($_data)->where($_cond)->update();
			
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * 取信息
	 */
	public function getUniformInterface () {
		try {
			return $this->model->table($this->tbUniformInterfaceLogInfo)->select();
		} catch ( Exception $e ) {
			return false;
		}
	}
}