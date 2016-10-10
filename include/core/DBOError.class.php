<?php
//cp异常和错误处理

 //如果还没有加载配置文件，则加载配置文件
if(!defined('is_load_DBOConfig'))
{
	require_once(dirname(__FILE__).'/DBOConfig.class.php');
}
if(DBOConfig::get('ERROR_HANDLE'))
{	
	/**
	 * 默认异常处理函数
	 */
	function cp_exception_handler(Exception $e) {  
		throw new DBOError($e->getMessage(),$e->getCode(),$e->getFile(),$e->getLine());
	}
	/**
	 * 默认错误处理函数
	 */
	function cp_error_handler($errorCode,$errorMessage,$errorFile,$errorLine) {  
		throw new DBOError($errorMessage,$errorCode,$errorFile,$errorLine);
	}
	
	set_exception_handler('cp_exception_handler');//注册默认异常处理函数
	set_error_handler('cp_error_handler',E_ALL ^ E_NOTICE);//注册默认错误处理函数
}	



/**
 * DBOError.class
 * cp错误类
 */
class DBOError extends Exception{

    public $errorMessage='';
    public $errorFile='';
    public $errorLine=0;
    public $errorCode='';
	public $errorLevel='';
 	public $trace='';
    /**
     * 构造函数
     * @param string $errorMessage 提示信息
     * @param int $errorCode 提示代号
     * @param string $errorFile 出错的文件名
     * @param int $errorLine 出错的行号
     * @param array $errorArr 自定义出错信息数组
     * @param bool $errorType 是否使用自定义方式记录错误信息
     */
    public function __construct($errorMessage,$errorCode=0,$errorFile='',$errorLine=0,$errorArr=null,$errorType=false) 
	{
        parent::__construct($errorMessage,$errorCode);
        $this->errorMessage=$errorMessage;
		$this->errorCode=$errorCode==0?$this->getCode():$errorCode;
        $this->errorFile=$errorFile==''?$this->getFile():$errorFile;
        $this->errorLine=$errorLine==0?$this->getLine():$errorLine;
        $this->errorArr = $errorArr;
        $this->errorType = $errorType;
		
      	$this->errorLevel=$this->getLevel();
 	    $this->trace=$this->trace();
        $this->showError();
    }
	//获取trace信息
	public function trace()
    {
        $trace = $this->getTrace();

        $traceInfo='';
        $time = date("Y-m-d H:i:s");
        foreach($trace as $t) {
            $traceInfo .= '['.$time.'] '.$t['file'].' ('.$t['line'].') ';
            $traceInfo .= $t['class'].$t['type'].$t['function'].'(';
           // $traceInfo .= implode(', ', $t['args']);
            $traceInfo .=")<br />\r\n";

        }
		return $traceInfo ;
    }
	//错误等级
	public function getLevel()
	{
	  $Level_array=array(	1=>'致命错误(E_ERROR)',
			2 =>'警告(E_WARNING)',
			4 =>'语法解析错误(E_PARSE)',  
			8 =>'提示(E_NOTICE)',  
			16 =>'E_CORE_ERROR',  
			32 =>'E_CORE_WARNING',  
			64 =>'编译错误(E_COMPILE_ERROR)', 
			128 =>'编译警告(E_COMPILE_WARNING)',  
			256 =>'致命错误(E_USER_ERROR)',  
			512 =>'警告(E_USER_WARNING)', 
			1024 =>'提示(E_USER_NOTICE)',  
			2047 =>'E_ALL', 
			2048 =>'E_STRICT'
		 );
		return isset($Level_array[$this->errorCode])?$Level_array[$this->errorCode]:$this->errorCode;
	}
	
	//抛出错误信息，用于外部调用
     static public function show($message="",$errorType=false)
    {
		throw new DBOError($message,$errorCode=0,$errorFile='',$errorLine=0,$errorArr=NULL,$errorType);
    }
	
	//获取ip地址，记录出错信息的时候，记录下ip信息
	static public function getIp()
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		   $ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		   $ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		   $ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		   $ip = $_SERVER['REMOTE_ADDR'];
		else
		   $ip = "unknown";
		return($ip);
	}
	
	//记录错误信息
	static public function write($message)
	{		
		
		$log_path=DBOConfig::get('LOG_PATH');

		//检查日志记录目录是否存在
		 if(!is_dir($log_path)) 
		 {
			//创建日志记录目录
			@mkdir($log_path,0755);
		 }
		 $log_path= rtrim($log_path,"/")."/";
		 $time=date('Y-m-d H:i:s');
		 $ip=self::getIp();
		 $destination =$log_path .date("Y-m-d").".log";

		 //写入文件，记录错误信息
       	 @error_log("{$time} | {$ip} | {$_SERVER['PHP_SELF']} |{$message}\r\n", 3,$destination);
	}
	
	//输出错误信息
     public function showError()
    {
//     	if($this->errorType){
//     		echo '<pre>';print_r($this->errorArr);echo '</pre>';
//     	}else{
//     		echo '<pre>';print_r(error_get_last());echo '</pre>';
//     	}

		//如果开启了日志记录，则写入日志
		if(DBOConfig::get('LOG_ON'))
		{
			self::write($this->message);
		}
		$error_url=DBOConfig::get('ERROR_URL');
		//错误页面重定向
		if($error_url!='')
		{
		 echo '<script language="javascript">
			   if(self!=top)
			  {
				  parent.location.href="'.$error_url.'";
		      }
			  else
			  {
			 	 window.location.href="'.$error_url.'";
			  }
			  </script>';
			exit;
		}
		
		if(!defined('__APP__'))
		{	
			define('__APP__','/');
		}
		echo 
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统错误提示!</title>
</head>
<body>
	<div style="border:1px solid #9CF; margin:20px auto; width:800px;">
	<div style="border:1px solid #fff; padding:15px; background:#f0f6f9;">
	<div style="border-bottom:1px #9CC solid; font-size:26px;font-family: "Microsoft Yahei", Verdana, arial, sans-serif; line-height:40px; height:40px; font-weight:bold">系统错误提示!</div>
	<div style="height:20px; border-top:1px solid #fff"></div>
	<div style="border:1px dotted #F90; border-left:6px solid #F60; padding:15px; background:#FFC">
		出错信息：'.$this->message.'
	</div>';
	
	//开启调试模式之后，显示详细信息
	if($this->errorCode&&DBOConfig::get('DEBUG'))
   {
	 echo  '<div style="border:1px dotted #F90; border-left:6px solid #F60; padding:15px; background:#FFC">
			出错文件：'.$this->errorFile.'
		</div>
		<div style="border:1px dotted #F90; border-left:6px solid #F60; padding:15px; background:#FFC">
			错误行：'.$this->errorLine.'
		</div>
		<div style="border:1px dotted #F90; border-left:6px solid #F60; padding:15px; background:#FFC">
			错误级别：'.$this->errorLevel.'
		</div>
		<div style="border:1px dotted #F90; border-left:6px solid #F60; padding:15px; background:#FFC;line-height:20px;">
			Trace信息：<br>'.$this->trace.'
		</div>';
	}
	
echo '<div style="height:20px;"></div>
<div style=" font-size:15px;">您可以选择 &nbsp;&nbsp;<a href="'.$_SERVER['PHP_SELF'].'" title="重试">重试</a> &nbsp;&nbsp;<a href="javascript:history.back()" title="返回">返回</a>  或者  &nbsp;&nbsp;<a href="'.__APP__.'" title="回到首页">回到首页</a> </div>
</div>
</div>
</body>
</html>';
		exit;
    }
}
?>