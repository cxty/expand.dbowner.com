/**
 * 
 *插件
 * 
 * @author wbqing405@sina.com
 * 
 */
function TplusList(){
	this.JS_LANG = '';
}
TplusList.prototype.init = function(){

};
TplusList.prototype.addAPlus = function(){
	$.fancybox.showLoading();
	$.get('/admin/addAPlus',
		{
			PlusCode:$('#PlusCode').val(),
			pState:$('#pState').val(),
			rnd:Math.random()
		},
		function(data){
			$.fancybox.hideLoading();
			switch(parseInt(data)){
				case -1:
					Boxy.alert( plusList.JS_LANG.Ex_NotNullPermCode,
							function(){$('#PermCode').val('').focus();},
							{title: plusList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
						  );
					break;
				case -2:
					Boxy.alert( plusList.JS_LANG.Ex_NotNullAppState,
							function(){$('#pState').val('').focus();},
							{title: plusList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
						  );
					break;
				default:
					location = '/admin/appPlus?view=list';
					break;
			}
		}
	);
};
TplusList.prototype.updateAPlus = function(AppPlusID){
	$.fancybox.showLoading();
	$.get('/admin/updateAPlus',
			{	
				AppPlusID:AppPlusID,
				PlusCode:$('#PlusCode').val(),
				pState:$('#pState').val(),
				rnd:Math.random()
			},
			function(data){
				$.fancybox.hideLoading();
				switch(parseInt(data)){
					case -1:
						Boxy.alert( plusList.JS_LANG.Ex_SysParamsWrong,
								function(){},
								{title: plusList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
						break;
					case -2:
						Boxy.alert( plusList.JS_LANG.Ex_NotNullPlusCode,
								function(){$('#PlusCode').val('').focus();},
								{title: plusList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
						break;
					case -3:
						Boxy.alert( plusList.JS_LANG.Ex_NotNullPlusState,
								function(){$('#pState').val('').focus();},
								{title: plusList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
						break;
					default:
						location = '/admin/appPlus?view=list';
						break;
			}
	});
};
TplusList.prototype.delAPlus = function(key,AppPlusID){
	Boxy.confirm( plusList.JS_LANG.Ex_ComfirmDel,
			function(){
				$.fancybox.showLoading();
				$.get('/admin/delAPlus',{AppPlusID:AppPlusID,rnd:Math.random()},function(data){
					$.fancybox.hideLoading();
					if(data == -1){
						Boxy.alert( plusList.JS_LANG.Ex_SysParamsWrong,
								function(){},
								{title: plusList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
					}else{
						$('#list_'+key).remove();
					}
				});
			},
			{title: plusList.JS_LANG.RemindMsg }
	);
};