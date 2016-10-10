<?php
include('conf.php');

//网站全局配置
$config['ver']									='1.0';

//网站全局配置结束

//日志和错误调试配置
$config['DEBUG']								=true;								//是否开启调试模式，true开启，false关闭
$config['LOG_ON']								=true;								//是否开启出错信息保存到文件，true开启，false不开启
$config['LOG_PATH']								='./data/log/';					//出错信息存放的目录，出错信息以天为单位存放
$config['ERROR_URL']							='';							//出错信息重定向页面，为空采用默认的出错页面
$config['ERROR_HANDLE']							=false;

//应用配置
		//网址配置
$config['URL_REWRITE_ON']						=true;						//是否开启重写，true开启重写,false关闭重写
$config['URL_MODULE_DEPR']						='/';						//模块分隔符
$config['URL_ACTION_DEPR']						='-';						//操作分隔符
$config['URL_PARAM_DEPR']						='-';						//参数分隔符
$config['URL_HTML_SUFFIX']						='.html';					//伪静态后缀设置，，例如 .html 
		
		//模块配置
$config['MODULE_PATH']							='./module/';					//模块存放目录
$config['MODULE_SUFFIX']						='Mod.class.php';			//模块后缀
$config['MODULE_INIT']							='init.php';					//初始程序
$config['MODULE_DEFAULT']						='index';					//默认模块
$config['MODULE_EMPTY']							='empty';					//空模块		
		
		//操作配置
$config['ACTION_DEFAULT']						='index';					//默认操作
$config['ACTION_EMPTY']							='_empty';					//空操作

		//静态页面缓存
$config['HTML_CACHE_ON']						=false;						//是否开启静态页面缓存，true开启.false关闭
$config['HTML_CACHE_PATH']						='./cache/html_cache/';	//静态页面缓存目录
$config['HTML_CACHE_SUFFIX']					='.html';				//静态页面缓存后缀
$config['HTML_CACHE_RULE']['index']['index']	=1000;	//缓存时间,单位：秒


//模板配置
$config['TPL_TEMPLATE_PATH']		='./templates/';			//模板目录
$config['TPL_TEMPLATE_SUFFIX']		='.html';					//模板后缀
$config['TPL_CACHE_ON']				=false;						//是否开启模板缓存，true开启,false不开启
$config['TPL_CACHE_PATH']			='./cache/tpl_cache/';		//模板缓存目录
$config['TPL_CACHE_SUFFIX']			='.php';					//模板缓存后缀,一般不需要修改

// smarty 配置
$config['SMARTY_DEBUGGING']         = false;              		//是否开启调试模式
$config['SMARTY_CACHING']           = FALSE;              		//是否开启缓存
$config['SMARTY_TEMPLATE_DIR']      = './templates/';      		//缓存时间
$config['SMARTY_CACHE_LIFETIME']    = 30;                 		//缓存时间
$config['SMARTY_COMPILE_DIR']       = './data/smarty/compile_dir'; //smarty模板编译文件存放的目录
$config['SMARTY_CACHE_DIR']         = './data/smarty/cache_dir';   //smarty模板缓存文件存放的目录
$config['SMARTY_LEFT_DELIMITER']    = '{';                         //左定界符
$config['SMARTY_RIGHT_DELIMITER']   = '}';                         //右定界符

//多语言配置 
$config['LANG_DEFAULT']				='zh-cn';       	//默认语言
$config['LANG_PACK_PATH']			='./lang/';      	//语言包目录
$config['LANG_PACK_SUFFIX']			='.lang.php';    	//语言包后缀 
$config['LANG_PACK_COMMON']			='common';   		//公用语言包，默认会自动加载

//MemCache配置
$config['MEM_SERVER']	     = array( array('192.168.0.189', 11211),  array('192.168.0.189', 11212) );
$config['MEM_GROUP']	     = 'PLATFORM_EXPAND';
$config['SAE_MEM_GROUP']	 = 'PLATFORM_EXPAND';
$config['MEM_EXPIRE']        = 60*5; //过期时间，设置为5分钟
$config['public_key']        = '1q2w3e4r';
$config['public_iv']         = '4r3e2w1q';

//DES加密配置
$config['DES']['SOAP_USER']				 ='soap_server';  //DES 用户名
$config['DES']['SOAP_PWD']				 ='soap_pwd';   //DES 密码
$config['DES']['SOAP_IV']				 ='12345678';   //DES 偏移量
//DES加密配置
$config['DES']['SOAP_USER']				 ='soap_server';  //DES 用户名
$config['DES']['SOAP_PWD']				 ='soap_pwd';   //DES 密码
$config['DES']['SOAP_IV']				 ='12345678';   //DES 偏移量
$config['DES']['Soap_Client']	   		 ='http://dev.dbowner.com/soap/manage?wsdl';   //SoapClient地址
$config['DES']['Soap_Header']			 ='http://dev.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Dev']	     ='http://dev.dbowner.com/soap/manage?wsdl';   //SoapClient地址
$config['DES']['Soap_Header_Dev']		 ='http://dev.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Expand']	 ='http://expand.dbowner.com/soap/extendSoap?wsdl';
$config['DES']['Soap_Header_Expand']	 ='http://expand.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Auth']	     ='http://soap.auth.dbowner.com/soap/userInfoSoap?wsdl';
$config['DES']['Soap_Header_Auth']	     ='http://soap.auth.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Plus']	     ='http://plus.dbowner.com/soap/plugInSoap?wsdl';
$config['DES']['Soap_Header_Plus']	     ='http://plus.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Pay']	     ='http://pay.dbowner.com/soap/paySoap?wsdl';
$config['DES']['Soap_Header_Pay']	     ='http://pay.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Ads']	     ='http://ad.dbowner.com/soap/adsSoap?wsdl';
$config['DES']['Soap_Header_Ads']	     ='http://ad.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Push']	     ='http://push.dbowner.com/soap/pushSoap?wsdl';
$config['DES']['Soap_Header_Push']	     ='http://push.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Analysis']	 ='http://analysis.dbowner.com/soap/analysisSoap?wsdl';
$config['DES']['Soap_Header_Analysis']	 ='http://analysis.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Self']	     ='http://exp.dbowner.com/Interface/ManageExtend.wsdl';
$config['DES']['Soap_Header_Self']		 ='http://exp.dbowner.com/soap';   //SoapHeader地址


//文件服务器
$config['FILE_SERVER_UP'] ='http://file.dbowner.com:80/index.php?act=up';  //保存文件
$config['FILE_SERVER_GET']='http://file.dbowner.com:80/index.php?act=get'; //读取文件

$config['DES']['SOAP_SERVER_CLIENTIP']	 = array('127.0.0.1','192.168.0.1','192.168.0.88','192.168.0.253','192.168.0.109');   //SoapHeader地址

$config['NoNeedLogin'] = array(
								'common','throwMessage','login','file','soap', //公用的
								 'apiAuth','api','verify','test'//私用的
						);

$config['DB']['IP'] = array('192.168.0.112');
$config['DB']['checkLogin']  =  array('admin'); //登录验证的模版
$config['DB']['PlugInTypeID']  =  array(1); //插件类型，为1时需要登录验证

$config['PLATFORM']['EXPAND']['API'] = 'expand_api_pwd';

$config['AUTH']['DB']['CURL'] = 'http://user.dbowner.com';

$config['PLATFORM']['Auth'] = 'https://auth.dbowner.com';  //应用扩展平台
$config['PLATFORM']['Plus'] = 'http://plus.dbowner.com'; //插件平台
?>