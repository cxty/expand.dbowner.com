<?php
/**
 * 插件类型处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBPlugInClass{
	
	var $tbPlugInTypeInfo = 'tbPlugInTypeInfo'; //插件类型
	
	public function __construct($model){
		$this->model = $model;
		
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 取分类
	 */
	public function getPlugInClassList($fieldArr='', $order='pOrder asc,pAppendTime desc'){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			$condition['pStatues'] = 0;
			$condition['pPID']     = isset($fieldArr['pPID']) ? $fieldArr['pPID'] : 0;
			$field = 'PlugInTypeID,PlugInType,pDepth,pPID,pFID,pAppendTime';
			
			return $this->model->table($this->tbPlugInTypeInfo)->field($field)->where($condition)->order($order)->select();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 取分类选择
	 */
	public function getPlugInClass($value=0, $selectName="PlugInTypeID"){
		$html = '';
		$html .= '<select name="'.$selectName.'" id="'.$selectName.'">';
		$classList = $this->getPlugInClassList();
		if($classList){
			foreach($classList as $val){
				if(intval($value) == $val['PlugInTypeID']){
					$html .= '<option value="'.$val['PlugInTypeID'].'" selected="selected">'.$val['PlugInType'].'</option>';
				}else{
					$html .= '<option value="'.$val['PlugInTypeID'].'">'.$val['PlugInType'].'</option>';
				}
			}	
		}
		
		$html .= '</select>';
		
		return $html;
	} 
	/**
	 * 取插件选择框
	 */
	public function getPlugInSelect($value=0, $selectName="PlugInTypeID", $root=false){
		$html = '';
		$html .= '<select name="'.$selectName.'" id="'.$selectName.'" class="chzn-select">';
		if($root){
			$html .= '<option value="0">=root=</option>';
		}
		$tArr['pPID'] = 0;
		$html .= $this->getPlugInSelectLoop($value, $tArr);
		$html .= '</select>';
		
		return $html;
	}
	/**
	 * 循环操作
	 */
	private function getPlugInSelectLoop($value, $fieldArr){
		$classList = $this->getPlugInClassList($fieldArr);
		if($classList){
			$levelList = $this->getClassLevelList($classList[0]['pDepth']);
			foreach($classList as $val){			
				if(intval($value) == $val['PlugInTypeID']){
					$html .= '<option value="'.$val['PlugInTypeID'].'" selected="selected">'.$levelList.$val['PlugInType'].'</option>';
				}else{
					$html .= '<option value="'.$val['PlugInTypeID'].'">'.$levelList.$val['PlugInType'].'</option>';
				}
				$tArr['pPID'] = $val['PlugInTypeID'];
				$classSonList = $this->getPlugInClassList($tArr);
				if($classSonList){				
					$html .= $this->getPlugInSelectLoop($value, $tArr);
				}
			}
		}	
		return $html;
	}
	/**
	 * 取分级间的分割符号
	 */
	private function getClassLevelList($num){
		if($num){
			$html = '';
			for($i=1;$i<$num;$i++){
				$html .= '--';
			}
			return $html;
		}else{
			return '';
		}
	}
	/**
	 * 取分级导航
	 */
	public function getNavLevel($PlugInTypeID=null){
		if(isset($PlugInTypeID)){		
			$field = 'PlugInTypeID,pDepth,pPID';
			
			$tArr['PlugInTypeID'] = $PlugInTypeID;
			$re = $this->getNavLevelRecord($tArr, $field);
			
			if($re){
				$pDepth = $re[0]['pDepth'];
				$pPID   = $re[0]['pPID'];
				$rb[$pDepth]['pDepth'] = $pDepth;
				$rb[$pDepth]['pPID']   = $pPID;
				
				if($pPID){
					for($i=($pDepth-1);$i>0;$i--){
						$tArr['PlugInTypeID'] = $pPID;
						$sRe = $this->getNavLevelRecord($tArr, $field);
						if($sRe){
							$pPID = $sRe[0]['pPID'];
							$rb[$i]['pDepth'] = $sRe[0]['pDepth'];
							$rb[$i]['pPID']   = $pPID;						
						}
						 
					}
				}	
				ksort($rb);
				foreach($rb as $val){
					$kRe[] = $val;
				}
				return $kRe;
			}else{
				return null;
			}
		}else{
			return null;
		}
		
	}
	/**
	 * 取分级数据集
	 */
	private function getNavLevelRecord($fieldArr, $field='*', $order='pOrder asc,pAppendTime desc'){
		try{
			return $re = $this->model->table($this->tbPlugInTypeInfo)->field($field)->where($fieldArr)->order($order)->select();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 移动分类
	 */
	public function movePlugInTypeClass($PlugInTypeID=null, $pPID=null){
		if(isset($PlugInTypeID) && isset($pPID)){
			
			$field = 'pDepth,pFID';
			$tArr['PlugInTypeID'] = $PlugInTypeID;
			$re = $this->getNavLevelRecord($tArr, $field);
			if($re){
				unset($tArr);
				$condition['pDepth'] = $re[0]['pDepth'];
				$condition['pFID']   = $PlugInTypeID;
				
				$data['pFID'] = $re[0]['pFID'];
				
				$this->model->table($this->tbPlugInTypeInfo)->data($data)->where($condition)->update();
			}
			
			unset($re);
			unset($condition);
			unset($data);
			$field = 'PlugInTypeID,pDepth,pOrder';
			$order = 'pOrder desc';
			$tArr['pPID'] = $pPID;
			$re = $this->getNavLevelRecord($tArr, $field, $order);
			if($re){
				$condition['PlugInTypeID'] = $PlugInTypeID;

				$data['pPID']   = $pPID;
				$data['pDepth'] = $re[0]['pDepth'];
				$data['pFID']   = $re[0]['PlugInTypeID'];	
				$data['pOrder'] = $re[0]['pOrder']+1;

				$this->model->table($this->tbPlugInTypeInfo)->data($data)->where($condition)->update();
			}		
		}else{
			return null;
		}
	}
}