/**
 * 
 * 框架
 * 
 * @author wbqing405@sina.com
 * 
 */
function Tframe(){
	this.JS_LANG = '';
};
Tframe.prototype.init = function(){
	frame.showLeftMenu('index','/admin/introduce?action=index&view=index');
};
Tframe.prototype.showLeftMenu = function(type,href){
	var tObj = '#left_sidebar ul';
	var lObj = '#nav_left ul li';
	$(tObj).each(function(ke,va){
		if($(this).hasClass(type)){
			$(lObj).removeClass('on');
			$(lObj).eq(ke).addClass('on');
			$(tObj).css({'display':'none'});
			$(tObj).eq(ke).css({'display':''});
			frame.showMainMenu(type,href);
		}
	});
};
Tframe.prototype.showMainMenu = function(type,href){
	$("#left_sidebar a").removeClass('on');
	$("#leftmenu_"+type).addClass('on');
	main.location = href;
};