<?php
/**
 * SOAP调用例子
 *
 * @author wbqing405@sina.com
 */
class DBextendSoap {

	public function __construct($config){
		$this->SOAP_USER   = $config['DES']['SOAP_USER'];
		$this->DES_PWD     = $config['DES']['SOAP_PWD'];
		$this->DES_IV      = $config['DES']['SOAP_IV'];
		$this->Soap_Client = $config['DES']['User_Client'];
		$this->Soap_Header = $config['DES']['User_Header'];

		if(isset($config['DES']['ident'])){
			if($config['DES']['ident'] != 'private'){
				require(dirname(__FILE__)."/DES.class.php");
			}
		}else{
			require(dirname(__FILE__)."/DES.class.php");
		}
	}
	
	/**
	 * 处理SoapClient方法
	 * @param  $className 接口方法名
	 * @param  $conArr 数据数组
	 */
	private function manSoap($className,$conArr){	
		try{	
			$des = new DES($this->DES_PWD,$this->DES_IV);
			$client = new SoapClient($this->Soap_Client,array("trace"=>false, 'compression'=>true));
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);
			
			$val = $des->encrypt(json_encode($conArr));
			
			$tVal = array('data'=>json_encode(array('data'=>$val)));	
			
			//$re = $client->$className($tVal)->return;	
			//$_re =  json_decode($re);
			//$this->pr(array('data'=>json_encode(array('data'=>$val))));exit;
			
			$_re =  json_decode($client->$className(array('data'=>json_encode(array('data'=>$val))))->return);

			if(isset($_re->data) && $_re->data){
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
	public function setTableInfo($tableName,$condition=null){
		$conArr = $condition;
	
		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 取表信息
	 */
	public function getTableInfo($tableName,$condition=null,$order=null, $AppInfoID=null){
		$conArr = $condition;

		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}
		if($AppInfoID){
			$conArr['AppInfoID'] = $AppInfoID;
		}

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 取表信息
	 */
	public function SelectTableInfo($tableName,$condition=null,$order=null){
		$conArr = $condition;
		
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 更新表消息
	 */
	public function UpdateTableInfo($tableName,$udata){
		$conArr = $udata;

		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 删除用户信息
	 */
	public function DeleteTableInfo($tableName,$condition=null){
		$conArr = $condition;
		
		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 取用户列表
	 */
	public function GetTableList($tableName,$page=1,$rowNum=10,$condition=null,$order=null){
		$conArr['page']      = $page;
		$conArr['pagesize']  = $rowNum;
		
		if($condition){
			$conArr['condition'] = $condition;
		}else{
			$conArr['condition'] = '';
		}
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}

		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 增加用户基础信息
	 */
	public function InsertTableInfo($tableName,$data){
		$conArr = $data;

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 处理SoapClient方法
	 * @param  $className 接口方法名
	 * @param  $conArr 数据数组
	 */
	private function manSoap2($className,$conArr){
		try{
			$des = new DES($this->DES_PWD,$this->DES_IV);
			$client = new SoapClient($this->Soap_Client,array("trace"=>true));
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);
				
			$val = $des->encrypt(json_encode($conArr));
				
			$tVal = array('data'=>json_encode(array('data'=>$val)));
				
			//$re = $client->$className($tVal)->return;
			//$_re =  json_decode($re);
			//$this->pr(array('data'=>json_encode(array('data'=>$val))));exit;
				
			$_re =  json_decode($client->$className(array('data'=>json_encode(array('data'=>$val))))->return);
	
			if(isset($_re->data) && $_re->data){
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
	
	private function pr($arr=null){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
?>