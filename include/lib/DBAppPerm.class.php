<?php
/**
 * 权限处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBAppPerm{
	
	var $tbAppPermInfo = 'tbAppPermInfo'; //应用权限表
	
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
	 * 增加权限
	 */
	public function addAppPerm($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$data['AppGroupID']  = $fieldArr['AppGroupID'] ? $fieldArr['AppGroupID'] : 0;
			$data['PermCode']    = $fieldArr['PermCode'];
			$data['pState']      = $fieldArr['pState'];
			$data['pRead']       = $fieldArr['pRead'] ? $fieldArr['pRead'] : 1;
			$data['pWrite']      = $fieldArr['pWrite'] ? $fieldArr['pWrite'] : 0;
			$data['pDelete']     = $fieldArr['pDelete'] ? $fieldArr['pDelete'] : 0;
			$data['pStatues']    = 0;
			$data['pAppendTime'] = time();

			return $this->model->table($this->tbAppPermInfo)->data($data)->insert();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 修改权限
	 */
	public function updateAppPerm($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$data['PermCode']    = $fieldArr['PermCode'];
			$data['pState']      = $fieldArr['pState'];
			$data['pRead']       = $fieldArr['pRead'] ? $fieldArr['pRead'] : 1;
			$data['pWrite']      = $fieldArr['pWrite'] ? $fieldArr['pWrite'] : 0;
			$data['pDelete']     = $fieldArr['pDelete'] ? $fieldArr['pDelete'] : 0;
			
			$condition['AppPermID'] = $fieldArr['AppPermID'];

			return $this->model->table($this->tbAppPermInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 屏蔽权限
	 */
	public function delAppPerm($AppPermID){
		try{
			$AppPermID = $this->Addslashes->get_addslashes($AppPermID);
			
			$data['pStatues'] = 1;
			
			$condition['AppPermID'] = $AppPermID;
			
			return $this->model->table($this->tbAppPermInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 取权限列表
	 */
	public function getAppPermPageList($fieldArr,$order='pAppendTime desc',$pagesize,$page){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			$page = $page ? $page : 1;
			$limit_start = ($page - 1) * $pagesize;
			$limit = $limit_start . ',' . $pagesize;

			$condition['pStatues'] = 0;
			
			// 获取行数
			$count = $this->model->table($this->tbAppPermInfo)->field('AppPermID')->where($condition)->count();
			$list = $this->model->table($this->tbAppPermInfo)
								->field('AppPermID,
										AppGroupID,
										PermCode,
										pState,
										pRead,
										pWrite,
										pDelete,
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
	 * 取权限列表
	 */
	public function getAppPermListByID($AppPermID){
		try{
			$AppPermID = $this->Addslashes->get_addslashes($AppPermID);
			
			$condition['AppPermID'] = $AppPermID;
			
			return $this->model->table($this->tbAppPermInfo)
								->field('AppPermID,
										AppGroupID,
										PermCode,
										pState,
										pRead,
										pWrite,
										pDelete,
										pStatues,
										pAppendTime')
								->where($condition)->select();
			
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 权限选择列表
	 */
	public function getPermSelect($value='',$selectname='appPerm'){
		$html = '<select name="'.$selectname.'" id="'.$selectname.'">';
		if(intval($value) === 1){
			$html .= '<option value="0">'.Lang::get('No').'</option>';
			$html .= '<option value="1" selected="selected">'.Lang::get('Yes').'</option>';
		}else{
			$html .= '<option value="0" selected="selected">'.Lang::get('No').'</option>';
			$html .= '<option value="1">'.Lang::get('Yes').'</option>';
		}
		$html .= '</select>';
		
		return $html;
	}
}