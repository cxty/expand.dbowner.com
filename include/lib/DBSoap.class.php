<?php
/**
 * SOAP调用例子
 *
 * @author wbqing405@sina.com
 */
class DBSoap{

	public function __construct(){
		$this->SOAP_USER   = $GLOBALS['config']['DES']['SOAP_USER'];
		$this->DES_PWD     = $GLOBALS['config']['DES']['SOAP_PWD'];
		$this->DES_IV      = $GLOBALS['config']['DES']['SOAP_IV'];
		$this->Soap_Client = $GLOBALS['config']['DES']['User_Client'];
		$this->Soap_Header = $GLOBALS['config']['DES']['User_Header'];
	}
	/**
	 * 载入加密文件
	 */
	private function _load_des(){
		if($GLOBALS['DES_DBSoap']){
			return $GLOBALS['DES_DBSoap'];
		}else{
			require_once dirname(__FILE__).'/DES.class.php';
			$GLOBALS['DES_DBSoap'] = new DES($this->DES_PWD,$this->DES_IV);
				
			return $GLOBALS['DES_DBSoap'];
		}
	}
	/**
	 * 头部验证文件
	 */
	private function _soap($platform){
		switch(strtolower($platform)){
			case 'dev':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Dev'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Dev'];
				break;
			case 'auth':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Auth'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Auth'];
				break;
			case 'pay':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Pay'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_pay'];
				break;
			case 'ads':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Ads'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Ads'];
				break;
			case 'expand':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Expand'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Expand'];
				break;
			case 'push':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Push'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Push'];
				break;
			case 'analysis':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Analysis'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Analysis'];
				break;
			default:
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Self'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Self'];
				break;
		}
	}
	/**
	 * 处理SoapClient方法
	 * @param  $className 接口方法名
	 * @param  $conArr 数据数组
	 */
	private function manSoap($className,$cArr){	
// 		echo $className;
// 		echo '<br>';
// 		echo $this->Soap_Client;
// 		echo '<br>';
// 		exit;
		try{	
			$des = $this->_load_des();
			$client = new SoapClient($this->Soap_Client,array("trace"=>false, 'compression'=>true));
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);
			
			$val = $des->encrypt(json_encode($cArr));
			
			$_re =  json_decode($client->$className(array('data'=>json_encode(array('data'=>$val))))->return);
		
			if(isset($_re->data)){
				return json_decode($des->decrypt($_re->data),true);
			}else{
				return false;
			}
		}catch (Exception $e) {
			
			//echo $client->__getLastRequest();
			//echo $client->__getLastResponse();
			echo $e->getMessage();
			
			exit;
				
			
			printf("Message = %s",$e->__toString());
		}
	}
	/**
	 * 取表信息
	 */
	public function SelectTableInfo($platform, $tableName, $condition='', $order=''){
		$this->_soap($platform);
	
		$tArr = array();
		if ( $condition['condition'] ) {
			$tArr['condition'] = $condition;
		} elseif ( $condition ) {
			$tArr = $condition;
		}
		if($order){
			$tArr['order'] = $order;
		}

		return $this->manSoap($tableName,$tArr);
	}
	/**
	 * 取列表
	 */
	public function GetTableList($platform, $tableName, $page=1, $pagesize=10, $condition=null, $order=null){
		$this->_soap($platform);
	
		$tArr['page']      = $page;
		$tArr['pagesize']  = $pagesize;
	
		if($condition){
			$tArr['condition'] = $condition;
		}
		if($order){
			$tArr['order'] = $order;
		}
	
		return $this->manSoap($tableName,$tArr);
	}
	/**
	 * 增信息
	 */
	public function InsertTableInfo($platform, $tableName, $data){
		$this->_soap($platform);
	
		return $this->manSoap($tableName, $data);
	}
	/**
	 * 更新信息
	 */
	public function UpdateTableInfo($platform, $tableName, $udata){
		$this->_soap($platform);

		return $this->manSoap($tableName, $udata);
	}
	/**
	 * 删除信息
	 */
	public function DeleteTableInfo($platform, $tableName, $ddata){
		$this->_soap($platform);
		
		return $this->manSoap($tableName, $ddata);
	}
	/**
	 * 取表信息
	 */
	public function GetTableInfo($platform, $tableName, $condition='', $order=''){
		$this->_soap($platform);
	
		$tArr = array();
		$tArr = $condition;
		if($order){
			$tArr['order'] = $order;
		}
	
		return $this->manSoap($tableName,$tArr);
	}
	private function pr($arr=null){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
?>