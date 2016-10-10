<?php
/**
 * 文件上传读取操作类
 * @author Cxty
 *
 */
class fileMod extends commonMod {
	
	public function __construct() {
		parent::__construct ();
	
	}
	
	public function index() {
		$this->display ();
	}
	/**
	 * 上传文件 curl方式
	 */
	public function Up(){
		//ComFun::pr($_FILES);//exit;
		if (! empty ( $_FILES )) {
				
			$files = array ();
	
			foreach ( $_FILES as $name => $file ) {
				if ($file ["error"] > 0) {
	
					echo json_encode ( array (
							'state' => false,
							'error' => $file ["error"]
					) );
	
				} else {
					$files = $file; // 表单对象
					
					//验证限制条件
					if(isset($_GET['order'])){
						$img_info = $this->doLimit($files['tmp_name'],$_GET);
						if($img_info !== true){
							echo json_encode(array("state"=>false,"msg"=>"Illegal File!",'error'=>$img_info));exit;
						}
					}
	
					$SERVER_URL = $this->config ['FILE_SERVER_UP'];
				    
					$fields['uploadfile'] = '@'.$files ['tmp_name'];
					$fields['filetype']   = $files['type'];
					$fields['filename']   = base64_encode ( $files ['name'] );// 存储原文件名		
					$fields['filemd5']    = md5_file ( $files ['tmp_name'] ); // 文件的md5值
	
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $SERVER_URL );
					curl_setopt($ch, CURLOPT_POST, 1 );
					curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );
						
					ob_start();
					curl_exec($ch);
					$body = ob_get_contents();
					ob_end_clean();
					curl_close($ch);
					
					$response_info = json_encode ( array (
								'state' => false,
								'error' => 'Nothing back data!'
						) );
			
					if($body){
						$response_info = json_decode($body, true);
					}	
					
					//上传插件图片
					if(isset($_GET['order'])){
						$response_info['attach'] ['order'] = $_GET['order'];
					}
					
					echo json_encode($response_info);
				}
			}
		} else {
			echo json_encode ( array (
					'state' => false,
					'error' => 'Nothing file data!'
			) );
		}
	}
	/**
	 * 上传 socket方式
	 */
	public function Up_bar() {
		//ComFun::pr($_FILES);exit;
		$SERVER_URL = $this->config ['FILE_SERVER_UP'];

		if (! empty ( $_FILES )) {
			
			$files = array ();
			
			foreach ( $_FILES as $name => $file ) {
				//ComFun::pr($file);
				if ($file ["error"] > 0) {
					echo 1;
					
					echo json_encode ( array (
							'state' => false,
							'error' => $file ["error"] 
					) );
				
				} else {
					$files = $file; // 表单对象
					
					//验证限制条件
					if(isset($_GET['order'])){
						$img_info = $this->doLimit($files['tmp_name'],$_GET);
						if($img_info !== true){
							echo json_encode(array("state"=>false,"msg"=>"Illegal File!",'error'=>$img_info));exit;
						}
					}
					
					$file_md5 = md5_file ( $files ['tmp_name'] ); // 文件的md5值
					$filename = base64_encode ( $files ['name'] );
					
					$SERVER_URL = $SERVER_URL . (strrpos ( $SERVER_URL, '?' ) > 0 ? '&' : '?') . 'filename=' . rawurlencode ( $filename ) . '&filemd5=' . $file_md5;
					
					$Server = parse_url ( $SERVER_URL );
					$host = $Server ['host'];
					$port = empty ( $Server ['port'] ) ? 80 : ( int ) $Server ['port'];
					
					if(function_exists('fsockopen')){
						$fp = @fsockopen ( $host, $port, $errno, $errstr, 30 );
					}else{
						$fp = @pfsockopen ( $host, $port, $errno, $errstr, 30 );
					}						
					
					srand ( ( double ) microtime () * 1000000 );
					$boundary = "---------------------------" . substr ( md5 ( rand ( 0, 32000 ) ), 0, 10 );
					$http_header = ""; // http协议信息头
					$http_header .= "POST $SERVER_URL  HTTP/1.0\r\n";
					$http_header .= "Host: $host\r\n";
					$http_header .= "Accept-Language: zh-cn,zh;q=0.5\r\n";
					$http_header .= "Accept-Encoding: deflate\r\n";
					$http_header .= "Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n";
					$http_header .= "Content-type: multipart/form-data,boundary=$boundary\r\n";
					
					$data .= "--$boundary\r\n";
					
					$new_file_name = $files ['name']; // 存储原文件名
					
					$content_file = join ( "", file ( $files ['tmp_name'] ) );
					$data .= "Content-Disposition:form-data;name=\"uploadfile\";filename=\"$new_file_name\" \r\n";
					$data .= "Content-Type: {$files['type']}\r\n\r\n";
					$data .= "$content_file\r\n";
					$data .= "--$boundary--\r\n";
					
					$http_header .= "Content-length: " . strlen ( $data ) . "\n";
					$http_header .= "Connection: close\n\n";
					$http_header .= "$data\n";

					fputs ( $fp, $http_header );
					
					stream_set_timeout ( $fp, 2000 );
	
					$resp = '';
					$start = microtime ( true );
					$len = - 1;
					
					while ( ($line = trim ( fgets ( $fp ) )) != "" ) {
						$header .= $line;
						if (strstr ( $line, "Content-Length:" )) {
							list ( $cl, $len ) = explode ( " ", $line );
						}
					}
					if ($len > 0) {
						$body = fread ( $fp, $len );
						if ($body) {
							$response_info = json_decode ( $body, true );
						}
					
					}
					
					//如果文件服务器未返回信息,则自动刷新页面重新提交
// 					if(!isset($response_info['state'])){
// 						echo '<script>window.location.reload();</script>';exit;
// 					}

					//上传插件图片
					if(isset($_GET['order'])){
						$response_info['attach'] ['order'] = $_GET['order'];
					}
					
					fclose ( $fp );
					unlink ( $files ['tmp_name'] );
	
					echo json_encode($response_info);
					
					/*
					 * fputs ( $fp, $http_header ); $response_info = ""; while (
					 * ! feof ( $fp ) ) { $response_info .= fgets ( $fp, 32000
					 * ); } fclose ( $fp ); if (strpos ( $response_info, '200' )
					 * !== false) { $response_info =
					 * substr($response_info,stripos($response_info,'{"state":'));
					 * if($response_info){ $response_info =
					 * json_decode($response_info,true); $urlPic =
					 * $response_info['data']['filecode'];
					 * include_once(dirname(dirname(__FILE__)).'/include/lib/ModifyProfile.class.php');
					 * $modifyProfile = new ModifyProfile($this->model);
					 * $modifyProfile->savePortrait($urlPic);
					 * $this->redirect('/main/index'); } //echo
					 * json_encode(array('state'=>true,'msg'=>$response_info));
					 * }
					 */
					break;
				}
			}
		} else {
			echo json_encode ( array (
					'state' => false,
					'error' => 'Nothing file data!' 
			) );
		}
	}
	private function upfile($file){
		$SERVER_URL = $this->config ['FILE_SERVER_UP'];
		$files = $file; // 表单对象
		//ComFun::pr($files);exit;
		//验证限制条件
		if(isset($_GET['order'])){
			// 			$img_info = $this->doLimit($files['tmp_name'],$_GET);
			// 			if($img_info !== true){
			// 				echo json_encode(array("state"=>false,"msg"=>"Illegal File!",'error'=>$img_info));exit;
			// 			}
			$files['order'] = $_GET['order'];
		}
			
		$file_md5 = md5_file ( $files ['tmp_name'] ); // 文件的md5值
		
		$filename = base64_encode ( $files ['name'] );
			
		$SERVER_URL = $SERVER_URL . (strrpos ( $SERVER_URL, '?' ) > 0 ? '&' : '?') . 'filename=' . rawurlencode ( $filename ) . '&filemd5=' . $file_md5;
		
		$Server = parse_url ( $SERVER_URL );
		$host = $Server ['host'];
		$port = empty ( $Server ['port'] ) ? 80 : ( int ) $Server ['port'];
			
		if(function_exists('fsockopen')){
			$fp = @fsockopen ( $host, $port, $errno, $errstr, 30 );
		}else{
			$fp = @pfsockopen ( $host, $port, $errno, $errstr, 30 );
		}
			
		srand ( ( double ) microtime () * 1000000 );
		$boundary = "---------------------------" . substr ( md5 ( rand ( 0, 32000 ) ), 0, 10 );
		$http_header = ""; // http协议信息头
		$http_header .= "POST $SERVER_URL  HTTP/1.0\r\n";
		$http_header .= "Host: $host\r\n";
		$http_header .= "Accept-Language: zh-cn,zh;q=0.5\r\n";
		$http_header .= "Accept-Encoding: deflate\r\n";
		$http_header .= "Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n";
		$http_header .= "Content-type: multipart/form-data,boundary=$boundary\r\n";
			
		$data .= "--$boundary\r\n";
			
		$new_file_name = $files ['name']; // 存储原文件名
			
		$content_file = join ( "", file ( $files ['tmp_name'] ) );
		$data .= "Content-Disposition:form-data;name=\"uploadfile\";filename=\"$new_file_name\" \r\n";
		$data .= "Content-Type: {$files['type']}\r\n\r\n";
		$data .= "$content_file\r\n";
		$data .= "--$boundary--\r\n";
			
		$http_header .= "Content-length: " . strlen ( $data ) . "\n";
		$http_header .= "Connection: close\n\n";
		$http_header .= "$data\n";
		
		fputs ( $fp, $http_header );
			
		stream_set_timeout ( $fp, 2000 );
		
		$resp = '';
		$start = microtime ( true );
		$len = - 1;
			
		while ( ($line = trim ( fgets ( $fp ) )) != "" ) {
			$header .= $line;
			if (strstr ( $line, "Content-Length:" )) {
				list ( $cl, $len ) = explode ( " ", $line );
			}
		}
		if ($len > 0) {
			$body = fread ( $fp, $len );
			if ($body) {
				$response_info = json_decode ( $body, true );
			}
		
		}
		
		//如果文件服务器未返回信息,则自动刷新页面重新提交
		if(isset($response_info['state'])){
			$ctn = isset($files['ctn']) ? $files['ctn'] : 1;
			if($ctn <= 5){
				$files['ctn'] = ++$ctn;
				self::upfile($files);
			}else{
				$response_info['state'] = false;
				$response_info['error'] == 'undefined';
			}
		}
		
		//上传插件图片
		if(isset($_GET['order'])){
			$response_info['attach'] ['order'] = $_GET['order'];
		}
			
		fclose ( $fp );
		unlink ( $files ['tmp_name'] );
		
		echo json_encode($response_info);exit;
			
		/*
		 * fputs ( $fp, $http_header ); $response_info = ""; while (
		 		* ! feof ( $fp ) ) { $response_info .= fgets ( $fp, 32000
		 				* ); } fclose ( $fp ); if (strpos ( $response_info, '200' )
		 						* !== false) { $response_info =
		* substr($response_info,stripos($response_info,'{"state":'));
		* if($response_info){ $response_info =
		* json_decode($response_info,true); $urlPic =
		* $response_info['data']['filecode'];
		* include_once(dirname(dirname(__FILE__)).'/include/lib/ModifyProfile.class.php');
		* $modifyProfile = new ModifyProfile($this->model);
		* $modifyProfile->savePortrait($urlPic);
		* $this->redirect('/main/index'); } //echo
		* json_encode(array('state'=>true,'msg'=>$response_info));
		* }
		*/
	}
	/**
	 * 图片限制
	 */
	private function doLimit($url,$params){
		if($url){
			$picInfo = getimagesize($url);
			
			if(isset($params['order'])){
				if($picInfo[0] != $picInfo[1]){
					return -2;
				}
				
				switch($params['order']){
					case 0:
						if($picInfo[1] != 512){
							return -3;
						}
						break;
					case 1:
						if($picInfo[1] != 256){
							return -3;
						}
						break;
					case 2:
						if($picInfo[1] != 128){
							return -3;
						}
						break;
					case 3:
						if($picInfo[1] != 64){
							return -3;
						}
						break;
				}
			}
			
			return true;
		}else{
			return -1;
		}
	}
	/**
	 * 读取
	 */
	public function Get() {
		$filecode = $_GET ['filecode'];
	}
	/**
	 * 取传来的参数值,防SQL注入
	 *
	 * @param unknown_type $key
	 * @param int $len
	 * @param unknown_type $def
	 */
	public function GetString($key, $len = 0, $def = null) {
		$_val = $_GET [$key] ? $_GET [$key] : $_POST [$key];
		if ($_val) {
			$_val = $this->_addslashes ( $_val );
			if ($len > 0) {
				return substr ( $_val, 0, $len );
			} else {
				return $_val;
			}
		} else if ($def) {
			return $def;
		} else {
			return null;
		}
	}
	
	public function b_fsockopen($host, $port, &$errno, &$errstr, $timeout) {
		$ip = gethostbyname ( $host );
		$s = socket_create ( AF_INET, SOCK_STREAM, 0 );
		if (socket_set_nonblock ( $s )) {
			$r = @socket_connect ( $s, $ip, $port );
			if ($r || socket_last_error () == EINPROGRESS) {
				$errno = EINPROGRESS;
				return $s;
			}
		}
		$errno = socket_last_error ( $s );
		$errstr = socket_strerror ( $errno );
		socket_close ( $s );
		return false;
	}
}

?>