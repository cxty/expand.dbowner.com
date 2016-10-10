<?php
/**
 * 应用附加功能信息处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBAppOauthPlugIn{
	
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
	 * 取应用插件申请情况
	 */
	public function getAppOauthPlugInInfoByClientID($client_id){
		try{
			$condition['AppInfoID'] = $this->Addslashes->do_addslashes($client_id);
			
			$field = 'AppPlugInID,pUpdateTime,pExpTime,pStatues';
			
			return $this->model->table($this->tbAppOauthPlugInInfo)->field($field)->where($condition)->select();
		}catch(Exception $e){
			return null;
		}
	}
}