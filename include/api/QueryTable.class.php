<?php
/**
 * 用户处理类
 *
 * @author wbqing405@sina.com
 */
header('Content-type:text/html;charset=utf-8');

class QueryTable{	
	
	public function __construct($model){
		$this->model = $model;
	}
	/**
	 * 增加表的信息
	 */
	public function inTableData($tableName,$data){
		try{
			$re = $this->model->table($tableName)->data($data)->insert();
			if($re){
				return $re;
			}else{
				return 0;
			}
		}catch(Exception $e){
			return 0;
		}
	}
	/**
	 * 更新表的信息
	 */
	public function upTableData($tableName,$condition,$data){
		try{
			if($this->model->table($tableName)->data($data)->where($condition)->update()){
				return 1;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 删除表的信息
	 */
	public function deTableData($tableName,$condition){
		try{
			if($this->model->table($tableName)->where($condition)->delete()){
				return 1;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 选表的信息
	 */
	public function seTableData($tableName,$condition,$order='',$field='*'){
		try{
			$re = $this->model->table($tableName)->field($field)->where($condition)->order($order)->select();
			
			if($re){
				return $re;
			}else{
				return null;
			}
		}catch(Exception $e){
			return null;
		}	
	}
	/**
	 * 选出表的列表信息
	 */
	public function geTableData($tableName,$page=1,$pagesize=10,$condition,$order,$field='*'){
		try{
			$page = $page ? $page : 1;
			$limit = (($page - 1) * $pagesize) . ',' . $pagesize;
			// 获取行数
			$count = $this->model->table($tableName)->where($condition)->count();
			$list = $this->model->table($tableName)->field($field)->where($condition)->order($order)->limit($limit)->select();
			
			if($list){
				$listInfo = $list;
			}else{
				$listInfo = null;
			}
			return array (
					'count' => $count,
					'list' => $listInfo
			);
		}catch(Exception $e){
			return array(
					'count' => 0,
					'list' => null
					);
		}
	}
	/**
	 * 执行原始查询语句
	 */
	public function getQueryData($sql){
		try{
			$re = $this->model->query($sql);
				
			if($re){
				return $re;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 选出表的列表信息
	 */
	public function getQueryListData($sql,$page=1,$pagesize=10,$tableName,$condition,$field='autoid'){
		try{
			$page = $page ? $page : 1;
			$limit = (($page - 1) * $pagesize) . ',' . $pagesize;
			// 获取行数
			$count = $this->model->table($tableName)->field($field)->where($condition)->count();
			
			$sql .= ' limit '.$limit;
			
			$list = $this->model->query($sql);
				
			return array (
					'count' => $count,
					'list' => $list
			);
		}catch(Exception $e){
			return array(
					'count' => 0,
					'list' => null
			);
		}
	}
}
?>