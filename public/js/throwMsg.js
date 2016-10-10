/**
 * 
 * 权限树脚本
 * 
 * @author wbqing405@sina.com
 * 
 */
function TthrowMsg(){
	this.JS_LANG = '';
};
TthrowMsg.prototype.init = function(){	 
	if($('#urlTurn').val() != ''){
		throwMsg.autoTure();	
	}	 
};
var theTime = 6;
TthrowMsg.prototype.autoTure = function(){	
	theTime=theTime-1;
	$('#time_out').text(this.JS_LANG.throwMsgSystem + theTime + this.JS_LANG.throwMsgTurn);
	if(theTime>1){
		setTimeout("throwMsg.autoTure();",1000);
	}else{
		window.location= $('#urlTurn').val();
	}	
};