/**
 * 
 * 插件列表
 * 
 * @author wbqing405@sina.com
 * 
 */
function TplugIn(){
	this.JS_LANG = '';
}
TplugIn.prototype.init = function(){
	$(".tiptip_plus").tipTip({maxWidth: "160px", edgeOffset: 10});
	
	plugIn.loadImg();
};
TplugIn.prototype.loadImg = function(){
	var pObj = $('#plugIn_list img');
	pObj.each(function(ke,va){
		pObj.eq(ke).attr('src',pObj.eq(ke).attr('src-data'));
	});
};
TplugIn.prototype.addPlugIn = function(){
	location = '/plugIn/addPlugIn';
	return;
	$.fancybox({
        type: 'iframe',
        href: '/plugIn/addPlugIn',
        scrolling: 'auto',
        width: 760,
        height: 500,
        autoScale: false,
        centerOnScroll: true,
        hideOnOverlayClick: false,
        onClosed: function(){
        	window.location = location;
        }
    });
};
TplugIn.prototype.modifyPlugIn = function(AppPlugInID){
	location = '/plugIn/addPlugIn?AppPlugInID='+AppPlugInID;
	return;
	$.fancybox({
        type: 'iframe',
        href: '/plugIn/addPlugIn?AppPlugInID='+AppPlugInID,
        scrolling: 'auto',
        width: 760,
        height: 400,
        autoScale: false,
        centerOnScroll: true,
        hideOnOverlayClick: false,
        onClosed: function(){
        	window.location = location;
        }
    });
};
TplugIn.prototype.closeFancybox = function(){
	$.fancybox.close();
};