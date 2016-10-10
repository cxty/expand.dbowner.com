
function TheaderJs(){
	
}
TheaderJs.prototype.init = function(){
	//分析脚本
	var anaScript= document.createElement("script");
	anaScript.type = "text/javascript";
	anaScript.src="http://dbo.so/1q";
    document.body.appendChild(anaScript);
    
	headerJs.checkLoginStatues();
};
TheaderJs.prototype.checkLoginStatues = function(){
	$.get('/login/checkLoginStatues', {rnd:Math.random()}, function(data){
		if(data == true){
			window.location = '/login/loginOut';
		}
	});
};