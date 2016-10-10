<?php 
/**
 * 用户登录处理
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBLogin{
	
	var $tbUserInfo = 'tbUserInfo'; //用户信息
	
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
	 * 检验用户登录
	 */
	public function checkLogin($UserID){
		try{	
			$condition['UserID'] = $this->Addslashes->get_addslashes($UserID);
			
			$re = $this->model->table($this->tbUserInfo)->field('UID,GroupID,uPermission,uLevel')->where($condition)->select();

			if($re){
				return $re[0];
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新用户登录信息
	 */
	public function updateUserLogin($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$condition['UserID'] = $fieldArr['UserID'];
			
			$data['uName']       = $fieldArr['uName'];
			$data['uUpdateTime'] = time();
			
			return $this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
}
?>