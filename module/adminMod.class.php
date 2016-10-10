<?php
/**
 *
 * 前台处理
 *
 * @author wbqing405@sina.com
 *
 */

//!DBOwner && header('location:/login/login?ident=manage');

//header('location:/plugIn');

class adminMod extends commonMod {
	/**
	 * index处理
	 */
	public function index() {
		$this->assign ( 'title', Lang::get('Frame').' - '.Lang::get('DBOwner') );
	
		$menu = include_once(dirname(dirname(__FILE__)).'/conf/menu.php');
	
		$this->assign('menu',$menu);
	
		$this->display ('frame.html'); //输出模板
	}
	/**
	 * 平台简介
	 */
	public function introduce(){
		ComFun::pr(ComFun::getCookies());
		$this->display ('admin/introduce.html');
	}
	/**
	 * 权限管理
	 */
	public function appPerm(){
		$view = $_GET['view'] ? $_GET['view'] : 'list';
		
		$dbAppPerm = $this->getClass('DBAppPerm');
		switch($view){
			case 'list':			
				$pagesize = 10;
				$permList = $dbAppPerm->getAppPermPageList('','',$pagesize,$_GET['page']);
				$this->assign('permList',$permList['list']);
				$this->assign('showpage',$this->showpage('/admin/appPerm?view=list',$permList['count'],$pagesize,5,1));
				break;
			case 'add':
				$this->assign('pRead', $dbAppPerm->getPermSelect(1,'pRead'));
				$this->assign('pWrite', $dbAppPerm->getPermSelect(0,'pWrite'));
				$this->assign('pDelete', $dbAppPerm->getPermSelect(0,'pDelete'));
				break;
			case 'modify':
				$permList = $dbAppPerm->getAppPermListByID($_GET['AppPermID']);
				if($permList){
					$this->assign('pRead', $dbAppPerm->getPermSelect($permList[0]['pRead'],'pRead'));
					$this->assign('pWrite', $dbAppPerm->getPermSelect($permList[0]['pWrite'],'pWrite'));
					$this->assign('pDelete', $dbAppPerm->getPermSelect($permList[0]['pDelete'],'pDelete'));
				}else{
					$this->assign('pRead', $dbAppPerm->getPermSelect(1,'pRead'));
					$this->assign('pWrite', $dbAppPerm->getPermSelect(0,'pWrite'));
					$this->assign('pDelete', $dbAppPerm->getPermSelect(0,'pDelete'));
				}
				$this->assign('permList',$permList);
				break;
		}
		
		$this->assign('view', $view);
		$this->display ('admin/appperm.html');
	}
	/**
	 * 增加权限代码
	 */
	public function addAPerm(){
		if(empty($_GET['PermCode'])){
			echo -1;exit;
		}
		if(empty($_GET['pState'])){
			echo -2;exit;
		}
		$dbAppPerm = $this->getClass('DBAppPerm');
		echo $dbAppPerm->addAppPerm($_GET);
	}
	/**
	 * 修改权限代码
	 */
	public function updateAPerm(){
		if(empty($_GET['AppPermID'])){
			echo -1;exit;
		}
		if(empty($_GET['PermCode'])){
			echo -2;exit;
		}
		if(empty($_GET['pState'])){
			echo -3;exit;
		}
		$dbAppPerm = $this->getClass('DBAppPerm');
		$dbAppPerm->updateAppPerm($_GET);
		echo 1;
	}
	/**
	 * 屏蔽权限代码
	 */
	public function delAPerm(){
		if(empty($_GET['AppPermID'])){
			echo -1;exit;
		}
		$dbAppPerm = $this->getClass('DBAppPerm');
		$dbAppPerm->delAppPerm($_GET['AppPermID']);
		echo 1;
	}
	/**
	 * 插件管理
	 */
	public function appPlus(){
		$view = $_GET['view'] ? $_GET['view'] : 'list';
		
		$dbAppPlus = $this->getClass('DBAppPlus');
		switch($view){
			case 'list':
				$pagesize = 10;
				$plusList = $dbAppPlus->getAppPlusPageList('','',$pagesize,$_GET['page']);
				$this->assign('plusList',$plusList['list']);
				$this->assign('showpage',$this->showpage('/admin/appPlus?view=list',$plusList['count'],$pagesize,5,1));
				break;
			case 'modify':
				$plusList = $dbAppPlus->getAppPlusListByID($_GET['AppPlusID']);

				$this->assign('plusList',$plusList);
				break;
		}
		
		$this->assign('view', $view);
		$this->display ('admin/appplus.html');
	}
	/**
	 * 增加插件
	 */
	public function addAPlus(){
		if(empty($_GET['PlusCode'])){
			echo -1;exit;
		}
		if(empty($_GET['pState'])){
			echo -2;exit;
		}
		$dbAppPlus = $this->getClass('DBAppPlus');
		echo $dbAppPlus->addAppPlus($_GET);
	}
	/**
	 * 更新插件
	 */
	public function updateAPlus(){
		if(empty($_GET['AppPlusID'])){
			echo -1;exit;
		}
		if(empty($_GET['PlusCode'])){
			echo -2;exit;
		}
		if(empty($_GET['pState'])){
			echo -3;exit;
		}
		$dbAppPlus = $this->getClass('DBAppPlus');
		$dbAppPlus->updateAppPlus($_GET);
		echo 1;
	}
	/**
	 * 删除插件
	 */
	public function delAPlus(){
		if(empty($_GET['AppPlusID'])){
			echo -1;exit;
		}
		$dbAppPlus = $this->getClass('DBAppPlus');
		$dbAppPlus->delAppPlus($_GET['AppPlusID']);
		echo 1;
	}
}