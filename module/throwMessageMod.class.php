<?php
/**
 *
 * 错误处理
 *
 * @author wbqing405@sina.com
 *
 */
class throwMessageMod extends commonMod{
	/**
	 * 错误提示
	 */
	public function throwMsg(){
		$pack = '';
		if ( $_GET['t'] ) {
			$pack = 'throwMsg';
		}
		$msgkey = $_GET['msgkey'] ? $_GET['msgkey'] : 'Ex_UnknowError';
		$tt     = $_GET['tt'] ? $_GET['tt'] : '1'; //是否跳转
		
		$msgArr['appshow'] = false;
		if($tt == 2){
			$msgArr['urlTurn'] = '';
		}else{
			$msgArr['urlTurn'] = $_GET['urlTurn'];
		}
		$msgArr['url']     = '/index/index';
		$msgArr['retry']   = $_SERVER['REQUEST_URI'];
		$msgArr['msg']     = Lang::get($msgkey, $pack);
		
		$this->assign('msgArr',$msgArr);
		$this->display('throwMessage/message.html');
	}
	/**
	 * 错误信息提示
	 */
	public function remind () {
		header("Content-type: text/html; charset=utf-8"); 
		$msgkey = $_GET['msgkey'] ? $_GET['msgkey'] : $_GET[0] ? $_GET[0] : 'Ex_UnknowError';
		$this->assign('cdata',array(
				'msgkey' => Lang::get($msgkey, 'throwMsg')
				));
		
		$this->display('throwMessage/remind.html');
	}
}