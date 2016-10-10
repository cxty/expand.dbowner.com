<?php

class DBOErrorRecord{
	
	var $tbSysErrorLogInfo = 'tbSysErrorLogInfo'; //系统运行错误记录信息 
	
	function __construct($model){
		$this->model = $model;
	}
	
    public function getRecord($params=null){
    	echo  $this->tbSysErrorLogInfo;
		echo dirname(dirname(dirname(__FILE__))).'/conf/config.php';
		include_once(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
		echo '</pre>';print_r($config);echo '</pre>';
		//$model = new DBOModel (); //实例化数据库模型类
		//$this->model =  self::$global ['model'];
		//echo '<pre>';print_r($config);echo '</pre>';
		//echo '<pre>';print_r($this->config);echo '</pre>';
		$sql = 'select * from tbSysErrorLogInfo';
		$info = $this->model->query($sql);
		//$info = $model->table($this->tbSysErrorLogInfo)->select();
		if($info){
			echo 1;
		}else{
			echo 2;
		}
	}
}