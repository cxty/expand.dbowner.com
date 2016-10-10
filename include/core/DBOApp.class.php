<?php
//应用控制类，完成网址解析，单一入口控制，静态页面缓存功能
class DBOApp
{
	static public $module;//模块名称		
	static public $action;//操作名称
	static public $config=array();//配置信息

	//构造函数，配置参数
    public function __construct($config=array())
	{
		//echo '<pre>';print_r($config);echo '</pre>';
		define('CP_VER','1.5.2011.1103');//框架版本号,后两段表示发布日期
		define('DBOApp_PATH',dirname(__FILE__));//当前文件所在的目录
		define('is_load_DBOConfig',true);//定义已经加载了默认配置文件
		
        require_once(DBOApp_PATH.'/DBOConfig.class.php');//加载默认配置
		self::$config=is_array($config)?array_merge(DBOConfig::get('APP'),$config):DBOConfig::get('APP');//参数配置
		DBOConfig::set('APP',self::$config);//设置参数，主要用于传递给其他类使用

		 if(self::$config['DEBUG'])
			 error_reporting(E_ALL ^ E_NOTICE);//除了notice提示，其他类型的错误都报告
		 else
			 error_reporting(0);//把错误报告，全部屏蔽
			 
		//加载错误处理类
		require_once(DBOApp_PATH.'/DBOError.class.php');
		
		//注册类的自动加载
		if(function_exists('spl_autoload_register'))
			spl_autoload_register('self::autoload');  
		
		//加载常用函数库
		if(is_file(DBOApp_PATH.'/../lib/common.function.php'))
			require(DBOApp_PATH.'/../lib/common.function.php');
			
		//加载扩展函数库
		if(is_file(DBOApp_PATH.'/../ext/extend.php'))
			require(DBOApp_PATH.'/../ext/extend.php');
			
		//如果开启静态页面缓存，则初始化静态缓存类
		if(self::$config['HTML_CACHE_ON'])
		{
			require(DBOApp_PATH.'/DBOHtmlCache.class.php');
			DBOHtmlCache::init(self::$config);
		}
		
		//检查模块目录
		$this->_checkDir();

	}
	
	//检查模块存放目录是否存在，不存在，则创建
	 private function _checkDir()
    {	
		if(substr(self::$config['MODULE_PATH'], -1) != "/")    
		{
			self::$config['MODULE_PATH'] .= "/";
		}
			
		// 如果模块存放目录不存在，则创建目录
		if(!is_dir(self::$config['MODULE_PATH'])) 
		{
			//创建模块目录
			if (!@mkdir(self::$config['MODULE_PATH'],0755))
			 {
				$this->error(self::$config['MODULE_PATH']."模块目录不存在，请手动创建...");
			 } 
			 else 
			 {
			 	//自动创建目录
				@mkdir('template');//创建模板目录
				@mkdir('public');//创建公共目录，存放图片，css，js
				@mkdir('data',0777);//创建数据目录,存放缓存数据
				//写一个hello world
				$module_suffix=explode('.',self::$config['MODULE_SUFFIX'],2);	
				$classname=self::$config['MODULE_DEFAULT'].$module_suffix[0];//模块名+后缀名得到完整类名
				$hello_world=  
"<?php
class $classname
{
	public function ".self::$config['ACTION_DEFAULT']."()
	{
		echo 'hello world';
	}
}
?>";
				$file=self::$config['MODULE_PATH'].self::$config['MODULE_DEFAULT'].self::$config['MODULE_SUFFIX'];
				file_put_contents($file,$hello_world);
			 }
		}
		
     }
	
	//网址解析
    private function _parseUrl()
	{
		$script_name=$_SERVER["SCRIPT_NAME"];//获取当前文件的路径
		$url= $_SERVER["REQUEST_URI"];//获取完整的路径，包含"?"之后的字符串
		
		//去除url包含的当前文件的路径信息
		if($url&&@strpos($url,$script_name,0)!==false)
		{
			 $url=substr($url,strlen($script_name));
		}
		else
		{
			$script_name=str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
			if($url&&@strpos($url,$script_name,0)!==false)
			{
			 $url=substr($url,strlen($script_name));
			}
		}
		//第一个字符是'/'，则去掉
		if($url[0]=='/')
		{
			$url=substr($url, 1);
		}		
		//去除问号后面的查询字符串
		if($url&&false!==($pos=@strrpos($url,'?')))
		{
			$url=substr($url,0,$pos);
		}
		//去除后缀
		if($url&&($pos=strrpos($url,self::$config['URL_HTML_SUFFIX']))>0)
		{
			$url=substr($url,0,$pos);
		}
		$flag=0;
		//获取模块名称
		if($url&&($pos=@strpos($url,self::$config['URL_MODULE_DEPR'],1))>0)
		{
			self::$module=substr($url,0,$pos);//模块
			$url=substr($url,$pos+1);//除去模块名称，剩下的url字符串
			$flag=1;//标志可以正常查找到模块
		}
		else
		{	//如果找不到模块分隔符，以当前网址为模块名
			self::$module=$url;
		}
		
		$flag2=0;//用来表示是否需要解析参数
		//获取操作方法名称
		if($url&&($pos=@strpos($url,self::$config['URL_ACTION_DEPR'],1))>0)
		{
			self::$action=substr($url,0,$pos);//模块
			$url=substr($url,$pos+1);
			$flag2=1;//表示需要解析参数
		}
		else
		{
			//只有可以正常查找到模块之后，才能把剩余的当作操作来处理
			//因为不能找不到模块，已经把剩下的网址当作模块处理了
			if($flag)
			{
				self::$action=$url;
			}
		}				
		//解析参数
		if($flag2)
		{
			$param=explode(self::$config['URL_PARAM_DEPR'],$url);
			$param_count=count($param);
			for($i=0; $i<$param_count;$i=$i+2)
			{			
				$_GET[$i]=$param[$i];
				if(isset($param[$i+1]))
				{
					if(!is_numeric($param[$i]))
					{
						$_GET[$param[$i]]=$param[$i+1];
					}
					$_GET[$i+1]=$param[$i+1];
				}
			}	
		}	
	}
		
	//常量定义
    private function _define()
	{					
		$root=str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$root=substr($root,0,-1);//去掉最后的斜杠		
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'){
			$http = 'HTTPS';
		}else{
			$http = 'HTTP';
		}
		$root= $http.'://'.$_SERVER['HTTP_HOST'].$root;//网址加上完整域名
		//__ROOT__和__PUBLIC__常用于图片，css，js定位
		//__APP__和__URL__常用于网址定位
		
		define('__ROOT__',$root);//当前入口所在的目录
		define('__PUBLIC__',$root.'/public');//当前入口所在的目录
		//如果开启了重写，则网址不包含入口文件，如index.php
		if(self::$config['URL_REWRITE_ON'])
		{
			define('__APP__',__ROOT__);
		}
		else
		{
			define('__APP__',__ROOT__.'/'.basename($_SERVER["SCRIPT_NAME"]));//当前入口文件
		}		
		define('__URL__',__APP__.'/'.self::$module);//当前模块
	}
	
	//执行模块，单一入口控制核心
    public function run()
	{
		if(function_exists('url_parse_ext'))
		{
			url_parse_ext();//自定义网址解析
		}
		else	
		{
			$this->_parseUrl();//解析模块和操作	
		}
		//在其他页面通过$_GET['_module']和$_GET['_action']获取得到当前的模块和操作名
		self::$module=empty(self::$module)?self::$config['MODULE_DEFAULT']:self::$module;
		self::$action=empty(self::$action)?self::$config['ACTION_DEFAULT']:self::$action;
		self::$module=str_replace(array("/","\\"),'',self::$module);//过滤一下模块名，防止模块包含漏洞
		self::$action=str_replace(array("/","\\"),'',self::$action);//过滤一下操作名
		$_GET['_module']=self::$module;
		$_GET['_action']=self::$action;	
		
		//如果存在初始程序，则先执行初始程序
		if(file_exists(self::$config['MODULE_PATH'].self::$config['MODULE_INIT']))
		{
				require(self::$config['MODULE_PATH'].self::$config['MODULE_INIT']);//加载初始程序
		}
		
		$this->_define();//常量定义
			
		//检查指定模块是否存在
		if($this->_checkModule(self::$module))
		{
			 $module=self::$module;
		}
		else if($this->_checkModule(self::$config['MODULE_EMPTY']))//如果指定模块不存在，但存在空模块，则执行空模块
		{
			$module=self::$config['MODULE_EMPTY'];
		}
		else
		{
			 $this->error(self::$module."模块不存在");//指定模块和空模块都不存在，则显示出错信息，并退出程序。
		}
		
		//如果开启静态页面缓存，则尝试读取静态缓存
		if(false==$this->_readHtmlCache($module,self::$action))
		{	
			$this->_execute($module);//静态缓存读取失败，执行模块
		}		
		//如果存在回调函数cp_app_end，则在即将结束前，调用回调函数
		if(function_exists('cp_app_end'))
		{
			cp_app_end();
		}		
	}
	
	//检查模块文件是否存在,存在返回true，不存在返回false
	private function _checkModule($module)
	{
		if(file_exists(self::$config['MODULE_PATH'].$module.self::$config['MODULE_SUFFIX']))
			return true;
		else
			return false;
	}
	
	//执行操作
    private function _execute($module)
	{	
		require_once(self::$config['MODULE_PATH'].$module.self::$config['MODULE_SUFFIX']);//加载模块文件
		//获取模块后缀
		$module_suffix=explode('.',self::$config['MODULE_SUFFIX'],2);	
		$classname=$module.$module_suffix[0];//模块名+后缀名得到完整类名
		
		if(class_exists($classname))
		{
			$object=new $classname();//实例化模块对象
			//类和方法同名，直接返回，因为跟类同名的方法会当成构造函数，已经被调用，不需要再次调用
			if($classname==self::$action)
			{
				return true;
			}
			$action="";
			if(method_exists($object,self::$action))
			{
				$action=self::$action;
			}
			else if(method_exists($object,self::$config['ACTION_EMPTY']))
			{
				$action=self::$config['ACTION_EMPTY'];
				//解决空操作的静态页面缓存读取
				if($this->_readHtmlCache($module,$action))
				{	
						return true;
				}
			}
			else
			{
				$this->error(self::$action."操作方法在".$module."模块中不存在");
			}
			//执行指定模块的指定操作	
			call_user_func(array(&$object,$action));//这里相当于$object->$action();
			//如果缓存开启，写入静态缓存，只有符合规则的，才会创建缓存
			$this->_writeHtmlCache();
		}
		else
		{
			$this->error($classname."类不存在");
		}	
	}
	
	//读取静态页面缓存
	private function _readHtmlCache($module='',$action='')
	{	//如果开启静态页面缓存，则尝试读取静态缓存
		if(self::$config['HTML_CACHE_ON']&&DBOHtmlCache::read($module,$action))
		{
				return true;
		}
		return false;
	}
	
	//写入静态页面缓存
	private function _writeHtmlCache()
	{	//如果缓存开启，写入静态缓存，只有符合规则的，才会创建缓存
		if(self::$config['HTML_CACHE_ON'])
		{
			DBOHtmlCache::write();
		}	
	}
	
	//实现类的自动加载
	static public function autoload($classname)   
	{   
		$class_array=array();
		$base_path=DBOApp_PATH;
		$class_array[]=self::$config['MODULE_PATH'].$classname.'.class.php';//加载模块文件
		$class_array[]=$base_path.'/'.$classname.'.class.php';//核心文件
		$class_array[]=$base_path.'/../lib/'.$classname.'.class.php';//官方扩展库
		$class_array[]=$base_path.'/../ext/'.$classname.'.class.php';//第三方扩展库
		$class_array[]=self::$config['MODEL_PATH'].$classname.'.class.php';//加载模型文件
		foreach($class_array as $file)
		{
			if(is_file($file))
			{   
			    require_once($file); 
				return true;
			} 
		}
		return false;
	}   
    //输出错误信息
	public function error($str)
	{
		//$errorArr['eType'] = 3;
		//$errorArr['eNode'] = $str;
		//$errorArr['eCodeData'] = $error;

		//require_once(dirname(__FILE__).'/DBOErrorRecord.class.php');
		//$errorRecord = new DBOErrorRecord();
		//$errorRecord->getRecord($errorArr);
		//$sql = 'select * from tbSysErrorLogInfo';
		//$info = $this->connect->query($sql);
		//echo '<pre>';print_r($this->config);echo '</pre>';
		DBOError::show($str);//输出错误信息
	}
}
?>