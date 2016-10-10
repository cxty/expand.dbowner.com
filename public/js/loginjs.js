function Tloginjs(){
	this.JS_LANG;
};
Tloginjs.prototype.init = function(){
	$('.login_box').DropInUp(2000);
	
	//用户名提示信息
	$('#username').val('').before(jQuery('<div class="def_txt">'+this.JS_LANG.UserName+'</div>').click(function(){
        $(this).hide();
        $('#username').focus();
    }));
   
	$('#username').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#username').focusout(function(){
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{
	        $(this).prevAll('.def_txt').hide();
	    }
	});
	
	//密码提示信息
	$('#userpwd').val('').before(jQuery('<div class="def_txt">'+this.JS_LANG.UserPass+'</div>').click(function(){
        $(this).hide();
        $('#userpwd').focus();
    }));
   
	$('#userpwd').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#userpwd').focusout(function(){
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{
	        $(this).prevAll('.def_txt').hide();
	    }
	});
	
	$('#btnLogin').click(function(){
		loginjs.checkLogin();
	});
};
Tloginjs.prototype.checkLogin = function(){
	$.fancybox.showLoading();
	$.get('/login/checkLogin',{username:$('#username').val(),userpwd:$('#userpwd').val(),rnd:Math.random()},function(data){
		$.fancybox.hideLoading();
		switch(parseInt(data)){
			case -1:
				Boxy.alert( loginjs.JS_LANG.Ex_NotAllow,
						function(){$('#username').val('').focus();$('#userpwd').val('').prevAll('.def_txt').show();},
						{title: loginjs.JS_LANG.Remind ,modal:true,unloadOnHide:true}
					  );
				break;
			case -2:
				Boxy.alert( loginjs.JS_LANG.Ex_AccountWrong,
						function(){$('#username').val('').focus();$('#userpwd').val('').prevAll('.def_txt').show();},
						{title: loginjs.JS_LANG.Remind ,modal:true,unloadOnHide:true}
					  );
				break;
			case 1:
				location = '/admin/index';
				break;
		}
	});
};