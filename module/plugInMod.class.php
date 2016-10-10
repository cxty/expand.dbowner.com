<?php
/**
 *
 * 插件管理
 *
 * @author wbqing405@sina.com
 *
 */
//!DBOwner && header('location:/login/login?ident=manage');

class plugInMod extends commonMod{
	/**
	 * 插件列表
	 */
	public function index(){
		$this->assign('title', Lang::get('DBOwner'));
		
		$pagesize = 10;
		$dbPlugIn = $this->getClass('DBPlugIn');
		$tArr['UserID'] = ComFun::getCookies('UserID');
		$listInfo = $dbPlugIn->getPlugInPage($tArr,$order='pUpdateTime desc',$pagesize,$_GET['page']);

		if($listInfo['list']){
			foreach($listInfo['list'] as $key=>$val){
				if($val['pIcoCode']){
					$pIcoCodeArr = explode('|', $val['pIcoCode']);
					if(is_array($pIcoCodeArr)){
						foreach($pIcoCodeArr as $ke=>$va){						
							$pICArr = explode(',',$va);
							$listInfo['list'][$key]['pIcoCode_'.$pICArr[1]] = $this->config['FILE_SERVER_GET'].'&filecode='.$pICArr[0].'&w='.$pICArr[1];
						}
						
					}
					
				}
			}
		}
		$this->assign('listInfo',$listInfo['list']);
		$this->assign('showpage',$this->showpage('/plugIn/index',$listInfo['count'],$pagesize,5,1));
	
		$this->display('plugIn/plugIn.html');
	}
	/**
	 * 检查所添加的值是否存在
	 */
	public function checkValue(){
		$dbPlugIn = $this->getClass('DBPlugIn');
		
		$tArr['AppPlugInID'] = $_GET['AppPlugInID'];
		switch ($_GET['type']){
			case 'PlugInName':
				$tArr['PlugInName'] = $_GET['value'];
				echo $dbPlugIn->checkPlugInName($tArr);
				break;
			case 'PlugInCode':
				//先判断是否是纯英文
				if(preg_match("/^[a-zA-Z]*$/", $_GET['value'])){
					$tArr['PlugInCode'] = $_GET['value'];
					echo $dbPlugIn->checkPlugInCode($tArr);
				}else{
					echo -1;
				}		
				break;
		}
	}
	/**
	 * 增加插件
	 */
	public function addPlugIn(){	
		$this->assign('title', Lang::get('DBOwner'));
		
		$PlugInTypeID = 0;
		if($_GET['AppPlugInID']){
			$dbPlugIn = $this->getClass('DBPlugIn');
			$listInfo = $dbPlugIn->getPlugInByID($_GET['AppPlugInID']);
			
			if($listInfo){
				$PlugInTypeID = $listInfo[0]['PlugInTypeID'];
				
				if($listInfo){
					foreach($listInfo as $key=>$val){
						if($val['pIcoCode']){
							$pIcoCodeArr = explode('|', $val['pIcoCode']);
							if(is_array($pIcoCodeArr)){
								foreach($pIcoCodeArr as $ke=>$va){
									$pICArr = explode(',',$va);
									$listInfo[$key]['pIcoCode_'.$pICArr[1]] = $pICArr[0];
								}
				
							}
								
						}
					}
				}
				$list = $listInfo[0];
			}
		} else {
			$list['pUniqueCode'] = ComFun::getRandom(16);
		}
		
		$this->assign('listInfo', $list);
		$dbPlugInClass = $this->getClass('DBPlugInClass');
		$this->assign('PlugInClass',$dbPlugInClass->getPlugInSelect($PlugInTypeID,'PlugInTypeID'));
		$this->display('plugIn/addPlugIn.html');
	}
	
	/**
	 * 取插件接口信息
	 */
	public function getInterfaceInfo () {
		$AppPlugInID = $_GET['AppPlugInID'];
		
		if ( $AppPlugInID ) {
			$dbPlugIn = new DBPlugIn($this->model);
			
			$rb = $dbPlugIn->getApiUrlByID($AppPlugInID);
			$rb2 = $dbPlugIn->getParamsByID( $AppPlugInID );
			if ( $rb ) {
				foreach ( $rb as $k => $v ) {
					$list[$k]['ApiID']    = $v['ApiID'];
					$list[$k]['aApiName'] = $v['aApiName'];
					$list[$k]['aUrl']     = $v['aUrl'];
					if ( $rb2 ) {
						$i = $j = 0;
						foreach ( $rb2 as $k2 => $v2 ) {
							if ( $v2['ApiID'] == $v['ApiID'] ) {
								if ( $v2['pType'] == 1 ) {
									$list[$k]['input'][$i]['ParamsID']    = $v2['ParamsID'];
									$list[$k]['input'][$i]['pFieldName']  = $v2['pFieldName'];
									$list[$k]['input'][$i]['pFieldType']  = $v2['pFieldType'];
									$list[$k]['input'][$i]['pFieldState'] = $v2['pFieldState'];
									$i++;
								} else {
									$list[$k]['output'][$j]['ParamsID']    = $v2['ParamsID'];
									$list[$k]['output'][$j]['pFieldName']  = $v2['pFieldName'];
									$list[$k]['output'][$j]['pFieldType']  = $v2['pFieldType'];
									$list[$k]['output'][$j]['pFieldState'] = $v2['pFieldState'];
									$j++;
								}
							}
						}
					}
				}
			}
			
			echo json_encode($list);
		} else {
			echo '';	
		}
	}
	
	/**
	 * 删除记录
	 */
	public function delParamByID(){
		$dbPlugIn = $this->getClass('DBPlugIn');
		switch(strtolower($_GET['type'])){
			case 'aurl':
				$tArr['ApiID'] = $_GET['id'];
				
				$dbPlugIn->delApiUrlByID($tArr);
				$dbPlugIn->delParamByApiID($tArr);
				break;
			case 'input':
				$tArr['ParamsID'] = $_GET['id'];
				$dbPlugIn->delParamByByID($tArr);
				break;
			case 'output':
				$tArr['ParamsID'] = $_GET['id'];
				$dbPlugIn->delParamByByID($tArr);
				break;
		}
	}
	/**
	 * 保存插件
	 */
	public function savePlugIn(){
		$dbPlugIn = $this->getClass('DBPlugIn');
	
		$AppPlugInID = $_POST['AppPlugInID'];
		
		if ( $AppPlugInID ) {
			$dbPlugIn->updatePlugIn($_POST);
			 
			if ( $_POST['ApiID'] ) {
				foreach ( $_POST['ApiID'] as $k2 => $v2 ) {
					$tArr2['ApiID']    = $v2;
					$tArr2['aApiName'] = $_POST['apiName'][$k2];
					$tArr2['aUrl']     = $_POST['apiUrl'][$k2];
					
					$dbPlugIn->updateApiUrlInfo($tArr2);
					if ( $_POST['ipFieldName' . $k2] ) {
						foreach ( $_POST['ipFieldName' . $k2] as $k3 => $v3 ) {
							$tArr3['AppPlugInID'] = $AppPlugInID;
							$tArr3['ApiID']       = $v2;
							$tArr3['pFieldName']  = $v3;
							$tArr3['pFieldType']  = $_POST['ipFieldType' . $k2][$k3];
							$tArr3['pFieldState'] = $_POST['ipFieldState' . $k2][$k3];
							$tArr3['pType']  	  = 1;
			
							if ( $_POST['ipID' . $k2][$k3] ) {	
								$tArr3['ParamsID'] = $_POST['ipID' . $k2][$k3];
								$dbPlugIn->updateInputParamInfo($tArr3);
							} else {
								$dbPlugIn->addPlugInParams($tArr3);
							}
						}
					}
					if ( $_POST['opFieldName' . $k2] ) {
						foreach ( $_POST['opFieldName' . $k2] as $k3 => $v3 ) {
							$tArr3['AppPlugInID'] = $AppPlugInID;
							$tArr3['ApiID']       = $v2;
							$tArr3['pFieldName']  = $v3;
							$tArr3['pFieldType']  = $_POST['opFieldType' . $k2][$k3];
							$tArr3['pFieldState'] = $_POST['opFieldState' . $k2][$k3];
							$tArr3['pType']  	  = 2;
								
							if ( $_POST['opID' . $k2][$k3] ) {	
								$tArr3['ParamsID'] = $_POST['opID' . $k2][$k3];
								$dbPlugIn->updateInputParamInfo($tArr3);
							} else {
								$dbPlugIn->addPlugInParams($tArr3);
							}
						}
					}
				}
			}
			for ( $i=count($_POST['ApiID']);$i<count($_POST['apiName']);$i++ ) {
				$tArr22['AppPlugInID'] = $AppPlugInID;
				$tArr22['aApiName']    = $_POST['apiName'][$i];
				$tArr22['aUrl']        = $_POST['apiUrl'][$i];
			
				$ApiID = $dbPlugIn->addAppPlugInApiInfo($tArr22);
			
				if ( $_POST['ipFieldName' . $i] ) {
					foreach ( $_POST['ipFieldName' . $i] as $k3 => $v3 ) {
						$tArr33['AppPlugInID'] = $AppPlugInID;
						$tArr33['ApiID']       = $ApiID;
						$tArr33['pFieldName']  = $v3;
						$tArr33['pFieldType']  = $_POST['ipFieldType' . $i][$k3];
						$tArr33['pFieldState'] = $_POST['ipFieldState' . $i][$k3];
						$tArr33['pType']  	   = 1;
						$dbPlugIn->addPlugInParams($tArr33);
					}
				}
			
				if ( $_POST['opFieldName' . $i] ) {
					foreach ( $_POST['opFieldName' . $i] as $k3 => $v3 ) {
						$tArr33['AppPlugInID'] = $AppPlugInID;
						$tArr33['ApiID']       = $ApiID;
						$tArr33['pFieldName']  = $v3;
						$tArr33['pFieldType']  = $_POST['opFieldType' . $i][$k3];
						$tArr33['pFieldState'] = $_POST['opFieldState' . $i][$k3];
						$tArr33['pType']  	   = 2;
						$dbPlugIn->addPlugInParams($tArr33);
					}
				}
			}
		}else{
			$_POST['UserID'] = ComFun::getCookies('UserID');
			$_POST['UID']    = ComFun::getCookies('UID');
			$_POST['pUrl']   = $_POST['pUrlRadio'] == 1 ? $_POST['pUrl'] : '';
			$AppPlugInID = $dbPlugIn->addPlugIn($_POST);
			if($AppPlugInID){
				if ( $_POST['apiName'] ) {
					foreach ( $_POST['apiName'] as $k2 => $v2 ) {
						$tArr2['AppPlugInID'] = $AppPlugInID;
						$tArr2['aApiName']    = $v2;
						$tArr2['aUrl']        = $_POST['apiUrl'][$k2];
						
						$ApiID = $dbPlugIn->addAppPlugInApiInfo($tArr2);
						
						if ( $_POST['ipFieldName' . $k2] ) {
							 foreach ( $_POST['ipFieldName' . $k2] as $k3 => $v3 ) {
							 	$tArr3['AppPlugInID'] = $AppPlugInID;
							 	$tArr3['ApiID']       = $ApiID;
							 	$tArr3['pFieldName']  = $v3;
							 	$tArr3['pFieldType']  = $_POST['ipFieldType' . $k2][$k3];
							 	$tArr3['pFieldState'] = $_POST['ipFieldState' . $k2][$k3];
							 	$tArr3['pType']  	  = 1;
							 	$dbPlugIn->addPlugInParams($tArr3);
							 }
						}
						
						if ( $_POST['opFieldName' . $k2] ) {
							foreach ( $_POST['opFieldName' . $k2] as $k3 => $v3 ) {
								$tArr3['AppPlugInID'] = $AppPlugInID;
								$tArr3['ApiID']       = $ApiID;
								$tArr3['pFieldName']  = $v3;
								$tArr3['pFieldType']  = $_POST['opFieldType' . $k2][$k3];
								$tArr3['pFieldState'] = $_POST['opFieldState' . $k2][$k3];
								$tArr3['pType']  	  = 2;
								$dbPlugIn->addPlugInParams($tArr3);
							}
						}
					}
				}
			}
		}

		$this->redirect('/plugIn');
	}
	/**
	 * 测试
	 */
	public function test(){
		$this->display('plugIn/test.html');
	}
}