<?php
class DBOHtmlCache
{
	static public $config;//配置
	static private $module;//当前模块
	static private $action;//当前操作方法
	static private $cacheAble;//用来标志是否可以正常读写缓存
	static private $cacheFile;//缓存文件名
	
    static public function  init($config=array())
    {
		self::$config['HTML_CACHE_ON']=isset($config['HTML_CACHE_ON'])?$config['HTML_CACHE_ON']:false;//是否开启静态页面缓存，true开启
		self::$config['HTML_CACHE_PATH']=isset($config['HTML_CACHE_PATH'])?$config['HTML_CACHE_PATH']:'./data/html_cache/';//静态页面缓存目录
		self::$config['HTML_CACHE_SUFFIX']=isset($config['HTML_CACHE_SUFFIX'])?$config['HTML_CACHE_SUFFIX']:'.html';//静态页面缓存后缀
		self::$config['HTML_CACHE_RULE']=isset($config['HTML_CACHE_RULE'])?$config['HTML_CACHE_RULE']:'';//静态页面缓存规则
	}
		//读取静态缓存文件
	static public function read($module='',$action='')
	{	
		self::$cacheAble=false;//是否可正常读写静态页面缓存标记		
		self::$module=isset($module)?$module:$_GET['_module'];//获取当前模块
		self::$action=isset($action)?$action:$_GET['_action'];//获取当前操作
		//如果配置为空，缓存没有开启或缓存规则为空，则直接返回false
		if(empty(self::$config)||(self::$config['HTML_CACHE_ON']!=true)||empty(self::$config['HTML_CACHE_RULE']))
		{
			return false;
		}
		//如果不再静态规则范围内，则直接返回false
		if(false==self::_checkRule())
		{
			return false;
		}
		//如果静态缓存目录不存在或不可写，则直接返回false
		if(false==self::_checkDir())
		{
			return false;
		}
		self::$cacheAble=true;//标志静态缓存可正常使用
		
		//设定缓存文件名
		self::$cacheFile=self::$config['HTML_CACHE_PATH'].self::$module.'/'.self::$action.'/';
		self::$cacheFile.=md5($_SERVER['REQUEST_URI']).self::$config['HTML_CACHE_SUFFIX'];
		$expires=self::$config['HTML_CACHE_RULE'][self::$module][self::$action];

		//静态缓存文件存在，且没有过期，则直接读取
		if(file_exists(self::$cacheFile)&&(time()<(filemtime(self::$cacheFile)+$expires)))
		{
			readfile(self::$cacheFile);
			return true;
		}
		ob_start();//开启内容输出控制
		return false;
	}
	//写入静态缓存文件
	static  public function write()
	{	
		if(self::$cacheAble)
 		{
			 $contents=ob_get_contents();
			 if(strlen($contents)>0&&file_put_contents(self::$cacheFile,$contents))//生成静态缓存	
			 { 
				   ob_end_clean();
				 //为了可以用回调函数，修改生成的静态页面，生成静态页面之后再读取
				   self::read(self::$module,self::$action);
			 }
			 else
			 {  //没有输出内容，直接输出，不生成空的静态页面
				 ob_end_flush();
			 }
		}
		
	}
	
	//清空静态缓存文件，$dir为空时，删除全部缓存
	static  public function clear($path='')
	{	    
		 $path   =  empty($path)?self::$config['HTML_CACHE_PATH']:$path;
        if ( $handle = opendir( $path ) )
        {
            while ( $file = readdir( $handle ) )
            {
				if (is_dir( $path . $file)&&$file!='.'&&$file!='..' )
					self::clear($path . $file.'/');
				if ( is_file( $path . $file ))
                  	 @unlink( $path . $file );
            }
            closedir( $handle );
            return true;
        }
		 return false;
	}

	//检查规则，看是否满足生成静态页面的条件 
	static private function _checkRule()
	{
		//如果静态规则数组，不为空，检查模块是否在规则中
		if(isset(self::$config['HTML_CACHE_RULE'])&&!empty(self::$config['HTML_CACHE_RULE']))
		{
			foreach(self::$config['HTML_CACHE_RULE'] as $key =>$value)
			{
				//若模块在指定规则中，检查操作是否在指定的规则中
				if($key==self::$module&&!empty(self::$config['HTML_CACHE_RULE'][self::$module]))
				{
					if (array_key_exists(self::$action,self::$config['HTML_CACHE_RULE'][self::$module]))
					{
						return true;
					}			
				}
			}
		}
		return false;
	}
	
	//用于检查模板和模板缓存目录
	static private function _checkDir()
	{
		if(substr(self::$config['HTML_CACHE_PATH'], -1) != "/")    
		{
			self::$config['HTML_CACHE_PATH'] .= "/";
		}
		// 如果静态缓存根目录不存在或者不是目录，则创建目录
		$cache_path=self::$config['HTML_CACHE_PATH'];
		if(self::_mkdir($cache_path))
		{
			// 如果静态缓存模块目录不存在或者不是目录，则创建目录
				$cache_path=$cache_path.'/'.self::$module;
				if(self::_mkdir($cache_path))
				{
						// 如果静态缓存操作目录不存在或者不是目录，则创建目录
						$cache_path=$cache_path.'/'.self::$action;
						if(self::_mkdir($cache_path))
						{
							return true;
						}
				}
		}
		return false;
	}
	
	//创建目录
	static private function _mkdir($dir)
	{
		if((!file_exists($dir))||(!is_dir($dir))) 
		{
			//目录不存在，则创建目录
			if (!@mkdir($dir,0777))
			 {
				return false;
			 } 
		}
		//如果目录不可写，则修改属性
		if(!is_writable($dir))
		{
			//修改缓存目录属性，设置为可写
			if(!@chmod($dir,0777))
			{
				return false;
			}
		}
		return true;
	}
}
?>