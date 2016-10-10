<?php
/**
 *
 * 用户权限管理
 *
 * @author wbqing405@sina.com
 *
 */
class ShowPage{
	var $nextPage = '下一页';//下一页
	var $prePage = '上一页';//上一页
	var $firstPage = '首页';//首页
	var $lastPage = '尾页';//尾页
	
	var $pageBarNum = 10;//控制记录条的个数。
	var $totalPage = 0;//总页数
	var $nowIndex = 0;//当前页
	var $url = "";//url地址头
	var $requestUri = "";
	
	public function __construct(){
		
	}
	/**
	 * 处理分页页码
	 */
	public function doPage($url,$total,$pagesize,$pageBarNum){
		$pagesize = $pagesize ? $pagesize : 10;
		$this->url = $this->setUrl($url);
		$this->totalPage = ceil($total/$pagesize);	//计算总页数
		$this->nowIndex = isset($_GET['page']) ? $_GET['page'] : 1;
		$this->pageBarNum = $pageBarNum;	
	}
	/**
	 * 初始化url
	 */
	private function setUrl($url=''){
		if($url){
			$urlArr = explode('?', $url);
			
			if(count($urlArr) > 1){
				$params = explode('&', $urlArr[1]);
				foreach($params as $key=>$val){
					$pArr = explode('=', $val);
					$strArr[$pArr[0]] = $pArr[1];
				}
				if(isset($strArr['page'])){
					unset($strArr['page']);
				}
					
				$_url = $urlArr[0].'?'.http_build_query($strArr).'&page=';
			}else{
				$_url = $urlArr[0].'?page=';
			}
		}else{
			$_url = $this->_requestUri().'&page=';
		}	
		
		return $_url;
	}
	/**
	 * url为空的情况，自动获取url
	 */
	private function _requestUri(){
		if(isset($_SERVER['REQUEST_URI'])){
			$uri = $_SERVER['REQUEST_URI'];
		}else{
			if(isset($_SERVER['argv'])){
				$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
			}else{
				$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
			}
		}
		return 'http://'.$_SERVER['HTTP_HOST'].$uri;
	}
	/**
	 * 取链接地址
	 */
	private function getUrl($page){
		return $this->url.$page;
	}
	/**
	 * 取a标签
	 */
	private function getAhref($page,$pagename){
		return '<a href=\''.$this->getUrl($page).'\'>'.$pagename.'</a>';
	}
	/**
	 * 首页
	 */
	private function firstPage(){
		if($this->nowIndex != 1){
			return $this->getAhref(1,$this->firstPage);
		}
	}
	/**
	 * 尾页
	 *
	 */
	private function lastPage(){
		if($this->nowIndex != $this->totalPage){
			return $this->getAhref($this->totalPage,$this->lastPage);
		}
	}
	/**
	 * 下一页
	 */
	private function nextPage(){
		if($this->nowIndex<$this->totalPage){
			return $this->getAhref($this->nowIndex+1,$this->nextPage);
		}
	} 
	/**
	 * 上一页
	 */
	private function prePage(){
		if($this->nowIndex > 1){
			return $this->getAhref($this->nowIndex-1,$this->prePage);
		}
	}
	/**
	 * 获取显示跳转按钮的代码
	 */
	private function select(){
		if($this->totalPage>1){
			$return='<select onChange=\'window.location=this.options[this.selectedIndex].value\'>';
			for($i=1;$i<=$this->totalPage;$i++){
				if($i==$this->nowIndex){
					$return.='<option value=\''.$this->getUrl($i).'\' selected>'.$i.'</option>';
				}else{
					$return.='<option value=\''.$this->getUrl($i).'\'>'.$i.'</option>';
				}
			}
			$return.='</select>';
			return $return;
		}
	}
	/**
	 * 条目
	 */
	public function nowBar($nowIndex_style='current'){
		$plus = ceil($this->pageBarNum/2);
		
		if($this->pageBarNum-$plus+$this->nowIndex>$this->totalPage){
			$plus = ($this->pageBarNum-$this->totalPage+$this->nowIndex);
		}	
	
		$begin = $this->nowIndex-$plus+1;
		$begin = ($begin>=1)?$begin:1;
		$return = '';
		for($i=$begin;$i<$begin+$this->pageBarNum;$i++){
			if($i<=$this->totalPage){
				if($i!=$this->nowIndex){
					$return .= $this->getAhref($i,$i);
				}else{
					$return .= '<span class="'.$nowIndex_style.'">'.$i.'</span>';
				}
			}else{
				break;
			}
			$return.= '';
		}
		
		return $return;
	}
	/**
	 * 显示页面
	 * @param unknown_type $url
	 * @param unknown_type $total
	 * @param unknown_type $pagesize
	 * @param unknown_type $pagebarnum
	 * @param unknown_type $mode
	 */
	public function show($url,$total,$pagesize=10,$pageBarNum=5,$mode=1){
		if($total > $pagesize){
			$this->doPage($url,$total,$pagesize,$pageBarNum);
			switch($mode){
				case '1':
					return $this->prePage().$this->nowBar().$this->nextPage().$this->select();
					break;
			}
		}	
	}
}