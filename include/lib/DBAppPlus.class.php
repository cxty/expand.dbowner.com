<?php
/**
 * 插件处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBAppPlus{
	var $tbPlugInTypeInfo = 'tbPlugInTypeInfo'; //插件表
	
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
	public function addAppPlus($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
				
			$data['AppGroupID']  = $fieldArr['AppGroupID'] ? $fieldArr['AppGroupID'] : 0;
			$data['PlusCode']    = $fieldArr['PlusCode'];
			$data['pState']      = $fieldArr['pState'];
			$data['pStatues']    = 0;
			$data['pAppendTime'] = time();
	
			return $this->model->table($this->tbAppPlusInfo)->data($data)->insert();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 取插件列表
	 */
	public function getAppPlusPageList($fieldArr,$order='pAppendTime desc',$pagesize,$page){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			$page = $page ? $page : 1;
			$limit_start = ($page - 1) * $pagesize;
			$limit = $limit_start . ',' . $pagesize;
	
			$condition['pStatues'] = 0;
				
			// 获取行数
			$count = $this->model->table($this->tbAppPlusInfo)->field('AppPermID')->where($condition)->count();
			$list = $this->model->table($this->tbAppPlusInfo)
								->field('AppPlusID,
										AppGroupID,
										PlusCode,
										pState,
										pStatues,
										pAppendTime')
								->where($condition)->order($order)->limit($limit)->select();
	
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
	 * 取插件列表
	 */
	public function getAppPlusListByID($AppPlusID){
		try{
			$AppPlusID = $this->Addslashes->get_addslashes($AppPlusID);
				
			$condition['AppPlusID'] = $AppPlusID;
				
			return $this->model->table($this->tbAppPlusInfo)
							->field('AppPlusID,
									AppGroupID,
									PlusCode,
									pState,
									pStatues,
									pAppendTime')
							->where($condition)->select();		
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新插件
	 */
	public function updateAppPlus($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
				
			$data['PlusCode']    = $fieldArr['PlusCode'];
			$data['pState']      = $fieldArr['pState'];
			
			$condition['AppPlusID'] = $fieldArr['AppPlusID'];
		
			return $this->model->table($this->tbAppPlusInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 屏蔽插件
	 */
	public function delAppPlus($AppPlusID){
		try{
			$AppPlusID = $this->Addslashes->get_addslashes($AppPlusID);
				
			$data['pStatues'] = 1;
				
			$condition['AppPlusID'] = $AppPlusID;
				
			return $this->model->table($this->tbAppPlusInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
}