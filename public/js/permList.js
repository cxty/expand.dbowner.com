/**
 * 
 * 权限
 * 
 * @author wbqing405@sina.com
 * 
 */
function TpermList(){
	this.JS_LANG = '';
}
TpermList.prototype.init = function(){
	
};
TpermList.prototype.addPerm = function(){
	$.fancybox.showLoading();
	$.get('/admin/addAPerm',
		{
			PermCode:$('#PermCode').val(),
			pState:$('#pState').val(),
			pRead:$('#pRead').val(),
			pWrite:$('#pWrite').val(),
			pDelete:$('#pDelete').val(),
			rnd:Math.random()
		},
		function(data){
			$.fancybox.hideLoading();
			switch(parseInt(data)){
				case -1:
					Boxy.alert( permList.JS_LANG.Ex_NotNullPermCode,
							function(){$('#PermCode').val('').focus();},
							{title: permList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
						  );
					break;
				case -2:
					Boxy.alert( permList.JS_LANG.Ex_NotNullAppState,
							function(){$('#pState').val('').focus();},
							{title: permList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
						  );
					break;
				default:
					location = '/admin/appPerm?view=list';
					break;
			}
		}
	);
};
TpermList.prototype.updateAPerm = function(AppPermID){
	$.fancybox.showLoading();
	$.get('/admin/updateAPerm',
			{	
				AppPermID:AppPermID,
				PermCode:$('#PermCode').val(),
				pState:$('#pState').val(),
				pRead:$('#pRead').val(),
				pWrite:$('#pWrite').val(),
				pDelete:$('#pDelete').val(),
				rnd:Math.random()
			},
			function(data){
				$.fancybox.hideLoading();
				switch(parseInt(data)){
					case -1:
						Boxy.alert( permList.JS_LANG.Ex_SysParamsWrong,
								function(){},
								{title: permList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
						break;
					case -2:
						Boxy.alert( permList.JS_LANG.Ex_NotNullPermCode,
								function(){$('#PermCode').val('').focus();},
								{title: permList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
						break;
					case -3:
						Boxy.alert( permList.JS_LANG.Ex_NotNullAppState,
								function(){$('#pState').val('').focus();},
								{title: permList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
						break;
					default:
						location = '/admin/appPerm?view=list';
						break;
			}
	});
};
TpermList.prototype.delAPerm = function(key,AppPermID){
	Boxy.confirm( permList.JS_LANG.Ex_ComfirmDel,
			function(){
				$.fancybox.showLoading();
				$.get('/admin/delAPerm',{AppPermID:AppPermID,rnd:Math.random()},function(data){
					$.fancybox.hideLoading();
					if(data == -1){
						Boxy.alert( permList.JS_LANG.Ex_SysParamsWrong,
								function(){},
								{title: permList.JS_LANG.Remind ,modal:true,unloadOnHide:true}
							  );
					}else{
						$('#list_'+key).remove();
					}
				});
			},
			{title: permList.JS_LANG.RemindMsg }
	);
};