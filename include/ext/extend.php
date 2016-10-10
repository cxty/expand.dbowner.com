<?php
/*
此文件extend.php在cpApp.class.php中默认会加载，不再需要手动加载
用户自定义的函数，建议写在这里

下面的函数是canphp框架的接口函数，
可自行实现功能，如果不需要，可以不去实现

注意：升级canphp框架时，不要直接覆盖本文件,避免自定义函数丢失
*/

/*
//模块执行结束之后，调用的接口函数
function cp_app_end()
{
	//在这里写代码实现你要实现的功能
}
*/

/*
//自定义模板标签解析函数
function tpl_parse_ext($template)
{
	//在这里实现的模块标签替换
	require_once(dirname(__FILE__)."/template_ext.php");
	$template=template_ext($template);
	return $template;

}
*/

/*
//自定义网址解析函数
function url_parse_ext()
{
	//在这里实现的模块标签替换
	cpApp::$module=trim($_GET['m']);
	cpApp::$action=trim($_GET['a']);
}
*/

/*
//自定义模型缓存读取
function db_cache_get_ext($key)
{
	return $data;
}
*/

/*
自定义模型缓存设置，
$key：字符串，需要自行创建哈希索引，如md5($key)
$data：字符串或数组，需要自行序列化
$expire：缓存时间，单位秒
*/
/*
function db_cache_set_ext($key,$data,$expire)
{
	return true;
}
*/

//下面是用户自定义的函数

?>