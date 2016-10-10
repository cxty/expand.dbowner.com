<?php 
/*
 * 邮件发送类
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8

class Email{
	
	public function __construct($base){
		$this->model = $base;		
	}
	
	public function getEmail(){
		echo 1111;
	}
}
?>