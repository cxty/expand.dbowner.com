<?php 
/**
 * soap调用接口
 * @author wbqing405@sina.com
 *
 */
class privateMod extends commonMod{	
	/**
	 * soap方法测试
	 */
	public function testSoap(){
		$selectMethod = '<select name="selectMethod" id="selectMethod">';
		$selectMethod .= '<option value="1">'.Lang::get('SelectMethod').'</optioin>';
		$selectMethod .= '<option value="2">'.Lang::get('UpdateMethod').'</optioin>';
		$selectMethod .= '<option value="3">'.Lang::get('InsertMethod').'</optioin>';
		$selectMethod .= '<option value="4">'.Lang::get('DeleteMethod').'</optioin>';
		$selectMethod .= '<option value="5">'.Lang::get('GetListMethod').'</optioin>';
		$selectMethod .= '<option value="6">'.Lang::get('GetMethod').'</optioin>';
		$selectMethod .= '</select>';
		
		$this->assign('selectMethod', $selectMethod);
		
		$this->display('private/testSoap.html');
	}
	/**
	 * 方法显示
	 */
	public function doSearch(){
		if(isset($_GET['funcName']) && $_GET['funcName'] != 'undefined'){
			$funcName = $_GET['funcName'];
		}else{
			$funcName = '';
		}
		if(isset($_GET['funcCondition']) && $_GET['funcCondition'] != 'undefined'){
			$funcCondition = $_GET['funcCondition'];
		}else{
			$funcCondition = '';
		}
		if(isset($_GET['funcOrder']) && $_GET['funcOrder'] != 'undefined'){
			$funcOrder = $_GET['funcOrder'];
		}else{
			$funcOrder = '';
		}
		if(isset($_GET['funcParams']) && $_GET['funcParams'] != 'undefined'){
			$funcParams = $_GET['funcParams'];
		}else{
			$funcParams = '';
		}
		if(isset($_GET['funcPage']) && $_GET['funcPage'] != 'undefined'){
			$MethodPage = $_GET['funcPage'];
		}else{
			$MethodPage =1;
		}
		if(isset($_GET['funcPageSize']) && $_GET['funcPageSize'] != 'undefined'){
			$funcPageSize = $_GET['funcPageSize'];
		}else{
			$funcPageSize = 1;
		}
		if(isset($_GET['funcID']) && $_GET['funcID'] != 'undefined'){
			$funcID = $_GET['funcID'];
		}else{
			$funcID = '';
		}

		$dbextendSoap = $this->getClass('DBextendSoap');		
		switch(intval($_GET['selectMethod'])){
			case 1:
				$data['condition'] = $funcCondition;		
				$re = $dbextendSoap->SelectTableInfo('Select'.$funcName, $data, $funcOrder);				
				break;
			case 2:			
				$data = $this->structData($funcParams);
				$data['condition'] = $funcCondition;			
				$re = $dbextendSoap->UpdateTableInfo('Update'.$funcName, $data);
				break;
			case 3:
				$data = $this->structData($funcParams);
				$re = $dbextendSoap->InsertTableInfo('Insert'.$funcName, $data);
				break;
			case 4:
				$data['condition'] = $funcCondition;
				$re = $dbextendSoap->DeleteTableInfo('Delete'.$funcName, $data);
				break;
			case 5:
				$re = $dbextendSoap->GetTableList('Get'.$funcName.'List', $MethodPage, $funcPageSize, $funcCondition, $funcOrder);
				break;
			case 6:
				$data['condition'] = $funcCondition;
				$re = $dbextendSoap->GetTableInfo('Get'.$funcName, $data, $funcOrder, $funcID);
				break;
		}
		ComFun::pr($re);
	}
	/**
	 * 分解数组
	 */
	private function structData($str){
		if($str){
			$str = preg_replace( '/，/', ',', $str);
			$params = explode(',', $str);
			foreach($params as $key=>$val){
				if($val){
					$arr = explode('=', $val);
					$narr[trim($arr[0])] = trim($arr[1]);
				}	
			}
			return $narr;
		}else{
			return '';
		}
	}
}