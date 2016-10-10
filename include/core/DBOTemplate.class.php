<?php
class DBOTemplate
{
	public $tplFile;//模板文件
	public $cacheFile;//模板缓存文件
	public $vars=array();//存放变量信息
	public $config=array();//存放配置信息

	public function __construct($config=array())
	{
		//如果还没有加载配置文件，则加载配置文件
		 if(!defined('is_load_DBOConfig'))
		 	require_once(dirname(__FILE__).'/DBOConfig.class.php');
		$this->config=is_array($config)?array_merge(DBOConfig::get('TPL'),$config):DBOConfig::get('TPL');//参数配置		
		$this->_checkDir();//检查模板和模板缓存目录

	}
	//用于检查模板和模板缓存目录
	private function _checkDir()
	{
		//如果模板目录和模板缓存目录末尾不是以“/”,则加上"/"
        if(substr($this->config['TPL_TEMPLATE_PATH'], -1) != "/")    
		{
			$this->config['TPL_TEMPLATE_PATH'] .= "/";
		}
	    if(substr($this->config['TPL_CACHE_PATH'], -1) != "/")    
		{
			$this->config['TPL_CACHE_PATH'] .= "/";
		}
		//检查模板目录是否存在
		if(!is_dir($this->config['TPL_TEMPLATE_PATH'])) 
		{
			$this->error($this->config['TPL_TEMPLATE_PATH']."模板目录不存在");
		}
		
		//模板缓存开启，检查是否存在模板缓存目录，没有则创建
		if($this->config['TPL_CACHE_ON'])
		{
			if(!is_dir($this->config['TPL_CACHE_PATH']))
			{
				//模板缓存目录不存在，则尝试创建
				if (!@mkdir($this->config['TPL_CACHE_PATH'],0777))
				 {
					 $this->error($this->config['TPL_CACHE_PATH']."模板缓存目录不存在");
				 }
			}

			//模板缓存开启，检查模板缓存目录是否可写，不可写，则修改属性
			if(!is_writable($this->config['TPL_CACHE_PATH']))
			{
				//修改模板缓存属性，设置为可写
				if(!@chmod($this->config['TPL_CACHE_PATH'],0777))
				{
					$this->error($this->config['TPL_CACHE_PATH']."模板缓存目录不可写");
				}
			}
		}
	}
	
	//用来检测模板文件是否存在
	private function _checkTplFile($tpl)
	{
		$this->tplFile=$this->config['TPL_TEMPLATE_PATH'].$tpl.$this->config['TPL_TEMPLATE_SUFFIX'];
		if(!is_file($this->tplFile)) 
		{
			$this->error($this->tplFile."模板不存在");
		}
	}
	
	private function _init($tpl)
	{		
		$this->_checkTplFile($tpl);
		$cache_file=str_replace('/','-',$tpl).'-'.md5($this->config['TPL_CACHE_PATH'].$tpl);
		$this->cacheFile=$this->config['TPL_CACHE_PATH'].$cache_file.$this->config['TPL_CACHE_SUFFIX'];
		return true;
	}
	
	//模板编译核心
	private  function _compile()
	{
		$template = file_get_contents($this->tplFile);//读取模板内容

		//如果自定义模板标签解析函数tpl_parse_ext($template)存在，则执行
		if(function_exists('tpl_parse_ext'))		
		{
			$template=tpl_parse_ext($template);
		}
		
		//常量解析，__APP__,__ROOT__等常量是在core/cpApp.class.php中定义
		$template = preg_replace("/__[A-Z]+__/i", "<?php if(defined('$0')) echo $0; else echo '$0'; ?>", $template);//替换常量
		
		/*将变量{$name}替换成<?php  echo $name;?>*/
		$template = preg_replace("/{(\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)}/i", "<?php echo $1; ?>", $template);//替换变量
		
		//解析模板包含
	    $template= preg_replace("/{include\s*file=\"(.*)\"}/ie", "\$this->_getTemplate('$1')", $template);//递归解析模板包含
		
		return $template;
	}
	
	//获取模板，并编译
	private  function _getTemplate($tpl)
	{
		$this->_checkTplFile($tpl);
		return $this->_compile();
	}
	
	//模板赋值
	public function assign($name, $value)
	{
		$this->vars[$name] = $value;
	}

	//执行模板解析输出
	public function display($tpl='')
	{
		//如果没有设置模板，则调用当前模块的当前操作模板
		if(($tpl=="")&&(!empty($_GET['_module']))&&(!empty($_GET['_action'])))
		{
			$tpl=$_GET['_module']."/".$_GET['_action'];
		}
		//初始化模板和模板缓存文件
		$this->_init($tpl);
		//解析变量
		extract($this->vars, EXTR_OVERWRITE);
		define('CANPHP',true);
		
		//如果开启了模板缓存，则检查模板缓存
		if($this->config['TPL_CACHE_ON']&&is_file($this->cacheFile)) 
		{
			//缓存文件时间大于模板修改时间，即模板没有修改，则读取缓存，
			if(filemtime($this->cacheFile)>=filemtime($this->tplFile))
			{
				require($this->cacheFile);//存在模板缓存，且没有过期，则直接输出
				return true;
			}
		}
		
		$template= $this->_getTemplate($tpl);//获取经编译后的内容
		//如果开启缓存，则写入缓存，否则直接输出
		if($this->config['TPL_CACHE_ON'])
		{
			file_put_contents($this->cacheFile,"<?php if(!defined('CANPHP')) exit;?>".$template);//写入缓存
			require($this->cacheFile);//执行编译后模板输出
		}
		else
		{
			eval('?>'.$template);//直接执行编译后的模板输出
		}		
	}	
	/*
	清空模板缓存
	*/
    public function clear()
    {
        $path   =  $this->config['TPL_CACHE_PATH'];
        if ( $handle = opendir( $path ) )
        {
            while ( $file = readdir( $handle ) )
            {
                if ( is_file( $path . $file ) )
                    @unlink( $path . $file );
            }
            closedir( $handle );
            return true;
        }
		return false;
    }
    //输出错误信息
	public function error($str)
	{
		require_once(dirname(__FILE__).'/cpError.class.php');
		cpError::show($str);//输出错误信息
	}
}
?>