<?php
@date_default_timezone_set('PRC');//定义时区，校正时间为北京时间
if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);//获取客户端语言
	$lang = $matches[1];
}

define('DBO_PATH',dirname(__FILE__).'/include/');//系统目录

//登录判断
if(isset($_COOKIE['UserID'])){
	define('DBOwner',true);
}else{
	define('DBOwner',false);
} 

require(dirname(__FILE__).'/conf/360_safe3.php');//加载过滤文件
require(dirname(__FILE__).'/conf/config.php');//加载配置
require(DBO_PATH.'core/DBOApp.class.php');//加载应用控制类

$app=new DBOApp($config);//实例化单一入口应用控制类

//执行项目
$app->run();

?>