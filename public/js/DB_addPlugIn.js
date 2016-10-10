/**
 * 
 * 增加插件
 * 
 * @author wbqing405@sina.com
 * 
 */

function TaddPlugIn(){
	this.JS_LANG = '';
}
TaddPlugIn.prototype.init = function(){
	//设置全局配置
	$.jUploader.setDefaults({
	    cancelable: true,
	    allowedExtensions: ['jpg', 'png', 'gif'],
	    messages: {
	        upload: addPlugIn.JS_LANG.Load,
	        cancel: addPlugIn.JS_LANG.Cancel,
	        emptyFile: addPlugIn.JS_LANG.EmptyFile,
	        invalidExtension: addPlugIn.JS_LANG.InvalidExtension,
	        onLeave: addPlugIn.JS_LANG.OnLeave
	    }
	});
	
	$(".tiptip_plus").tipTip({maxWidth: "auto", edgeOffset: 10});
	
	 $(".chzn-select").chosen(); 
	 //$(".chzn-select-deselect").chosen({allow_single_deselect:true});
	
	addPlugIn.editor();
	
	$('#PlugInName').focusout(function(){
		addPlugIn.checkValue('PlugInName',$('#PlugInName').val());
	});
	$('#PlugInCode').focusout(function(){
		addPlugIn.checkValue('PlugInCode',$('#PlugInCode').val());
	});
	
	$('#plugInSecond input[type=text]').before(jQuery('<span class="def_txt">' + addPlugIn.JS_LANG.Ex_ValueApiUrl + '</span>').click(function(){
        $(this).hide();
        $(this).next().focus();
    })).focusin(function(){		
    	addPlugIn.plugInFocus(this);
	}).focusout(function(){
		addPlugIn.plugInBlur(this);
	});
	
	$('#plugInThird input[type=text]').each(function(ke){
		switch(ke%3){
			case 0:
				val = addPlugIn.JS_LANG.Ex_ValueFieldName;
				break;
			case 1:
				val = addPlugIn.JS_LANG.Ex_ValueFieldType;
				break;
			case 2:
				val = addPlugIn.JS_LANG.Ex_ValueFieldState;
				break;
		}
		$('#plugInThird input[type=text]').eq(ke).before(jQuery('<span class="def_txt">' + val + '</span>').click(function(){
			$(this).hide();
			$(this).next().focus();
	    })).focusin(function(){
			addPlugIn.plugInFocus(this);    	
		}).focusout(function(){
			addPlugIn.plugInBlur(this);
		});
	});
	
	$('#plugInForth input[type=text]').each(function(ke){
		switch(ke){
			case 0:
				val = addPlugIn.JS_LANG.Ex_ValueFieldName;
				break;
			case 1:
				val = addPlugIn.JS_LANG.Ex_ValueFieldType;
				break;
			case 2:
				val = addPlugIn.JS_LANG.Ex_ValueFieldState;
				break;
		}
		$('#plugInForth input[type=text]').eq(ke).before(jQuery('<span class="def_txt">' + val + '</span>').click(function(){
			$(this).hide();
			$(this).next().focus();
	    })).focusin(function(){
			addPlugIn.plugInFocus(this);    	
		}).focusout(function(){
			addPlugIn.plugInBlur(this);
		});
	});
	
	//上传图片
	for(var i=0;i<4;i++){
		addPlugIn.upfile(i);
	}
	
	//初始化图片
	addPlugIn.IcoCode();

	//iframe地址初始化
	if ( $('#pradio_wrap input:radio[name="pUrlRadio"]:checked').val() == 1 ) {
		$('#purl_wrap').css('display','');
		$('#plugInIframe').css( 'height',  ($('#plugInIframe').height()+50) + 'px');
	} else {
		$('#purl_wrap').css('display','none');
	}
	$('#pradio_wrap input:radio').click(function(){
		if ( $(this).val() == 1 && $(this).attr('checked') == 'checked' ) {
			$('#purl_wrap').css('display','');
			$('#plugInIframe').css( 'height',  ($('#plugInIframe').height()+50) + 'px');
		} else {
			$('#purl_wrap').css('display','none');
			$('#plugInIframe').css( 'height',  ($('#plugInIframe').height()-50) + 'px');
		}
	});
	
	//初始化接口参数
	addPlugIn.initInfo($('#AppPlugInID').val());
};
TaddPlugIn.prototype.IcoCode = function(){
	if($('#AppPlugInID').val() != ''){
		var _ico_url= 'http://file.dbowner.com/index.php?act=get&filecode=';
		$('#loadPicture div').eq(0).css({'background':'url(' + _ico_url+$('#loadPicture_0_va').val() + '&w=64)'});
		$('#loadPicture div').eq(1).css({'background':'url(' + _ico_url+$('#loadPicture_1_va').val() + '&w=64)'});
		$('#loadPicture div').eq(2).css({'background':'url(' + _ico_url+$('#loadPicture_2_va').val() + '&w=64)'});
		$('#loadPicture div').eq(3).css({'background':'url(' + _ico_url+$('#loadPicture_3_va').val() + '&w=64)'});
	}
};
TaddPlugIn.prototype.upfile = function(order){
	$('#loadPicture_'+order).tipTip();
	$.jUploader({
        button: $('#loadPicture_'+order), // 这里设置按钮id
        action: '/file/up?order='+order, // 这里设置上传处理接口，这个加了参数test_cancel=1来测试取消
        css:{
        	width: '64px',
        	height: '64px'
        },
        // 上传完成事件
        onComplete: function (fileName, response) {
            if (response.state) {
            	var _ico_url= 'http://file.dbowner.com/index.php?act=get&filecode='+response.data.filecode+'&w=64'; 	
            	$('#loadPicture_'+response.attach.order+'_va').val(response.data.filecode);
            	//$('#loadPicture_'+response.attach.order).text('');
            	//$('#loadPicture a').eq(response.attach.order).html('<img src="'+_ico_url+'" />');
            	$('#loadPicture_'+response.attach.order).css({'background':'url(' + _ico_url + ')'});
            } else {
            	var remindMsg = '';
            	if(response.error == 'undefined'){
            		remindMsg = addPlugIn.JS_LANG.LoadFail;
            	}else{        		
            		switch(response.error){
            			case -1:
            				remindMsg = addPlugIn.JS_LANG.LoadFail;
            				break;
            			case -2:
            				remindMsg = addPlugIn.JS_LANG.Ex_PictureNotSquare;
            				break;
            			case -3:
            				remindMsg = addPlugIn.JS_LANG.Ex_PictureRule;
            				break;
            			default:
            				remindMsg = addPlugIn.JS_LANG.Ex_PicParamsError;
            				break;
            		}
            	}
            	Boxy.alert(
            			remindMsg, 
           			function(){
           				
           			}, 
           			{title: addPlugIn.JS_LANG.RemindMsg }
                );
            }
        }
    });
};
TaddPlugIn.prototype.initInfo = function ( AppPlugInID ) {
	$.fancybox.showLoading();
	$.get('/plugIn/getInterfaceInfo',{AppPlugInID:AppPlugInID,rnd:Math.random()},function(data){
		$.fancybox.hideLoading();
		
		var html = '';
		html += '<div class="ap_cont">';
		html += '<a href="javascript:void(0);" onclick="javascript:addPlugIn.addInterfalse();" class="btn">' + addPlugIn.JS_LANG.AddInterfaceButton + '</a>';		
		html += '</div>';
		$('#pi_st_add_bt').append(html);
		
		if ( data ) {
			for ( var i=0;i<data.length;i++ ) {
				var html = '';
				html += '<div class="ap_wrap">';
				html += '<input type="hidden" name="ApiID[]" value="' + data[i].ApiID + '" />';
				html += '<div class="ap_cont apiname">';
				html += '<input type="text" class="input ap_ipt_575" name="apiName[]" size="94" value="' + data[i].aApiName + '" />';
				html += '</div>';
				html += '<div class="ap_cont apiurl">';
				html += '<input type="text" class="input ap_ipt_575" name="apiUrl[]" size="94" value="' + data[i].aUrl + '" />';
				html += '</div>';
				
				html += '<div class="ap_cont">';
				html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.addInput(' + i + ');" class="btn">' + addPlugIn.JS_LANG.AddInputButton + '</a>';
				html += '</div>';
				html += '<div class="ap_cont inputfield"><input type="text" size="1" style="display:none;" /></div>';
				if ( data[i].input ) {
					for ( var j=0;j<data[i].input.length;j++ ) {
						html += '<div class="ap_cont inputfield">';
						html += '<input type="hidden" name="ipID' + i + '[]" value="' + data[i].input[j].ParamsID + '" />';
						html += '<input type="text" name="ipFieldName' + i + '[]" class="input" size="20" value="' + data[i].input[j].pFieldName + '" />';
						html += '&nbsp;<input type="text" name="ipFieldType' + i + '[]" class="input" size="20" value="' + data[i].input[j].pFieldType + '" />';
						html += '&nbsp;<input type="text" name="ipFieldState' + i + '[]" class="input" size="20" value="' + data[i].input[j].pFieldState + '" />';
						html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInput(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
						html += '</div>';
					}
				}
				html += '<div class="inputfield_wrap" id="pi_st_ip_' + i + '"></div>';
				
				html += '<div class="ap_cont">';
				html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.addOutput(' + i + ');" class="btn">' + addPlugIn.JS_LANG.AddOutputButton + '</a>';
				html += '</div>';
				html += '<div class="ap_cont outfield"><input type="text" size="1" style="display:none;" /></div>';
				if ( data[i].output ) {
					for ( var j=0; j<data[i].output.length; j++ ) {
						html += '<div class="ap_cont outfield">';
						html += '<input type="hidden" name="opID' + i + '[]" value="' + data[i].output[j].ParamsID + '" />';
						html += '<input type="text" name="opFieldName' + i + '[]" class="input" size="20" value="' + data[i].output[j].pFieldName + '" />';
						html += '&nbsp;<input type="text" name="opFieldType' + i + '[]" class="input" size="20" value="' + data[i].output[j].pFieldType + '" />';
						html += '&nbsp;<input type="text" name="opFieldState' + i + '[]" class="input" size="20" value="' + data[i].output[j].pFieldState + '" />';
						html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInput(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
						html += '</div>';
					}
				}
				html += '<div class="outfield_wrap" id="pi_st_op_' + i + '"></div>';
				
				html += '<div class="ap_cont">';
				html += '<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInterface(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
				html += '</div>';
				html += '</div>';
			
				addPlugIn.initInterface( html );
				
				$('#pi_st_inter .def_txt').hide();
			
				html = null;
			}
		}
	}
	,'json'
	);
};
var editor;
TaddPlugIn.prototype.editor = function(){
	KindEditor.ready(function(K) {
		editor = K.create('textarea[id="editor"]', {
			themeType : 'simple',
				items : [
					'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist']
		});
	});
};
TaddPlugIn.prototype.delParamByID = function(type, id, obj){
	Boxy.confirm(
		addPlugIn.JS_LANG.Ex_ConfirmDelRecord, 
		function(){
			$.get('/plugIn/delParamByID',{type:type,id:id,rnd:Math.random()},function(data){
				if ( type == 'aurl' ) {
					$(obj).parent().parent().remove();
				} else {
					$(obj).parent().remove();
				}
			});
		}, 
		{title: addPlugIn.JS_LANG.RemindMsg }
    );	
};
TaddPlugIn.prototype.backPlugIn = function(){
	location = '/plugIn';
};
TaddPlugIn.prototype.checkValue = function(type, value){
	if(value != ''){
		$.get('/plugIn/checkValue',{type:type,AppPlugInID:$('#AppPlugInID').val(),value:value,rnd:Math.random()},function(data){
			if(parseInt(data) == 1){
				Boxy.alert(
                		addPlugIn.JS_LANG.Ex_NotRepeat, 
           			function(){
           				if(type == 'PlugInName'){
           					$('#PlugInName').val('').focus();
           				}else{
           					$('#PlugInCode').val('').focus();
           				}
           			}, 
           			{title: addPlugIn.JS_LANG.RemindMsg }
                );
			}else if(parseInt(data) == -1){
				Boxy.alert(
                		addPlugIn.JS_LANG.Ex_NotPureEnglish, 
           			function(){
                		$('#PlugInCode').val('').focus();
           			}, 
           			{title: addPlugIn.JS_LANG.RemindMsg }
                );
			}
		});
	}
};
TaddPlugIn.prototype.initInterface = function ( html ) {
	$('#pi_st_inter').append(
		$(html).find('.apiname input[type=text]').before(jQuery('<div class="def_txt">' + addPlugIn.JS_LANG.Ex_ValueApiName + '</div>').click(function(){
	        $(this).hide();
	        $(this).next().focus();
	    })).focusin(function(){
			addPlugIn.plugInFocus(this);
		}).focusout(function(){
			addPlugIn.plugInBlur(this);
		}).parent().parent().find('.apiurl input[type=text]').before(jQuery('<div class="def_txt">' + addPlugIn.JS_LANG.Ex_ValueApiUrl + '</div>').click(function(){
	        $(this).hide();
	        $(this).next().focus();
	    })).focusin(function(){
			addPlugIn.plugInFocus(this);
		}).focusout(function(){
			addPlugIn.plugInBlur(this);
		}).parent().parent().find('.inputfield input[type=text]').each(function(ke){
			switch(ke){
				case 0:
					val = addPlugIn.JS_LANG.InputParamsName;
					break;
				case 1:
					val = addPlugIn.JS_LANG.Ex_ValueFieldType;
					break;
				case 2:
					val = addPlugIn.JS_LANG.Ex_ValueFieldState;
					break;
			}
			$(this).before(jQuery('<span class="def_txt">' + val + '</span>').click(function(){
				$(this).hide();
				$(this).next().focus();
		    })).focusin(function(){
				addPlugIn.plugInFocus(this);
			}).focusout(function(){
				addPlugIn.plugInBlur(this);
			});
		}).parent().parent().find('.outfield input[type=text]').each(function(ke){
			switch(ke){
				case 0:
					val = addPlugIn.JS_LANG.OutputParamsName;
					break;
				case 1:
					val = addPlugIn.JS_LANG.Ex_ValueFieldType;
					break;
				case 2:
					val = addPlugIn.JS_LANG.Ex_ValueFieldState;
					break;
			}
			$(this).before(jQuery('<span class="def_txt">' + val + '</span>').click(function(){
				$(this).hide();
				$(this).next().focus();
		    })).focusin(function(){
				addPlugIn.plugInFocus(this);
			}).focusout(function(){
				addPlugIn.plugInBlur(this);
			});
		}).parent().parent()
	);
};
TaddPlugIn.prototype.addInterfalse = function() {
	var len = $('input:text[name="apiUrl[]"]').length;
	
	var html = '';
	html += '<div class="ap_wrap">';
	html += '<div class="ap_cont apiname">';
	html += '<input type="text" class="input ap_ipt_575" name="apiName[]" size="94" />';
	html += '</div>';
	html += '<div class="ap_cont apiurl">';
	html += '<input type="text" class="input ap_ipt_575" name="apiUrl[]" size="94" />';
	html += '</div>';
	html += '<div class="ap_cont inputfield">';
	html += '<input type="text" name="ipFieldName' + len + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="ipFieldType' + len + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="ipFieldState' + len + '[]" class="input" size="20" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.addInput(' + len + ');" class="btn">' + addPlugIn.JS_LANG.AddInputButton + '</a>';
	html += '</div>';
	html += '<div class="inputfield_wrap" id="pi_st_ip_' + len + '"></div>';
	html += '<div class="ap_cont outfield">';
	html += '<input type="text" name="opFieldName' + len + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="opFieldType' + len + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="opFieldState' + len + '[]" class="input" size="20" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.addOutput(' + len + ');" class="btn">' + addPlugIn.JS_LANG.AddOutputButton + '</a>';
	html += '</div>';
	html += '<div class="outfield_wrap" id="pi_st_op_' + len + '"></div>'; 
	html += '<div class="ap_cont">';
	html += '<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInterface(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
	html += '</div>';
	html += '</div>';
	
	addPlugIn.initInterface( html );
	
	html = null;
};
TaddPlugIn.prototype.delInterface = function(type){
	var id = $(type).parent().parent().find('input:hidden[name="ApiID[]"]').val();
	if ( id != '' ) {
		addPlugIn.delParamByID('aurl', id, type);
	} else {
		$(type).parent().parent().remove();
	}
};
TaddPlugIn.prototype.addInput = function(num){
	var html = '';
	html += '<div class="ap_cont">';
	html += '<input type="text" name="ipFieldName' + num + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="ipFieldType' + num + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="ipFieldState' + num + '[]" class="input" size="20" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInput(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
	html += '</div>';
	$('#pi_st_ip_' + num).append(
		$(html).find('input[type=text]').each(function(ke){
			switch(ke){
				case 0:
					val = addPlugIn.JS_LANG.InputParamsName;
					break;
				case 1:
					val = addPlugIn.JS_LANG.Ex_ValueFieldType;
					break;
				case 2:
					val = addPlugIn.JS_LANG.Ex_ValueFieldState;
					break;
			}
			$(this).before(jQuery('<span class="def_txt">' + val + '</span>').click(function(){
				$(this).hide();
				$(this).next().focus();
		    })).focusin(function(){
				addPlugIn.plugInFocus(this);
			}).focusout(function(){
				addPlugIn.plugInBlur(this);
			});
		}).parent()
	);
	html = null;
};
TaddPlugIn.prototype.delInput = function(type){
	var id = $(type).parent().find('input:hidden').val();
	if ( id != '' ) {
		addPlugIn.delParamByID('input', id, type);
	} else {
		$(type).parent().remove();
	}
};
TaddPlugIn.prototype.addOutput = function(num){
	var html = '';
	html += '<div class="ap_cont">';
	html += '<input type="text" name="opFieldName' + num + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="opFieldType' + num + '[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="opFieldState' + num + '[]" class="input" size="20" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInput(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
	html += '</div>';
	$('#pi_st_op_' + num).append(
		$(html).find('input[type=text]').each(function(ke){
			switch(ke){
				case 0:
					val = addPlugIn.JS_LANG.OutputParamsName;
					break;
				case 1:
					val = addPlugIn.JS_LANG.Ex_ValueFieldType;
					break;
				case 2:
					val = addPlugIn.JS_LANG.Ex_ValueFieldState;
					break;
			}
			$(this).before(jQuery('<span class="def_txt">' + val + '</span>').click(function(){
				$(this).hide();
				$(this).next().focus();
		    })).focusin(function(){
				addPlugIn.plugInFocus(this);
			}).focusout(function(){
				addPlugIn.plugInBlur(this);
			});
		}).parent()
	);
	html = null;
};
TaddPlugIn.prototype.delOutput = function(type){
	var id = $(type).parent().find('input:hidden').val();
	if ( id != '' ) {
		addPlugIn.delParamByID('output', id, type);
	} else {
		$(type).parent().remove();
	}
};
TaddPlugIn.prototype.plugInFocus = function(type){
	$(type).prev('.def_txt').hide();
};
TaddPlugIn.prototype.plugInBlur = function(type){
	if($(type).val() == ''){
        $(type).prev('.def_txt').show().css({'display':''});
    }else{
        $(type).prev('.def_txt').hide();
    }
};
TaddPlugIn.prototype.doCancel = function(){
	window.parent.plugIn.closeFancybox();
};
TaddPlugIn.prototype.doSubmit = function(){
	var apiStr = ',';
	var apiName = '';
	var isexit = false;
	$('input:text[name="apiName[]"]').each(function(){
		apiName = $(this).val().toLowerCase();
		if ( apiStr != ',' ) {
			if ( apiStr.indexOf(',' + apiName + ',') !== -1 ) {
				isexit = true;
				Boxy.alert(
	            	addPlugIn.JS_LANG.Ex_ValueApiNameRepeat, 
	       			function(){}, 
	       			{title: addPlugIn.JS_LANG.RemindMsg }
	            );
			}
		}
		apiStr = apiStr + apiName + ',';
	});
	if ( isexit === true ) {
		return;
	}
	
	if($('#PlugInName').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullPlugInName, 
   			function(){
   				return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if($('#PlugInCode').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullPlugInCode, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if($('#pPoint').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullpPoint, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if($('#loadPicture_0_va').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullIcoCode_512, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if($('#loadPicture_1_va').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullIcoCode_256, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if($('#loadPicture_2_va').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullIcoCode_128, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if($('#loadPicture_3_va').val() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullIcoCode_64, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else if(editor.text() == ''){
		Boxy.alert(
        		addPlugIn.JS_LANG.Ex_NotNullInputState, 
   			function(){
        		return;
   			}, 
   			{title: addPlugIn.JS_LANG.RemindMsg }
        );
	}else{
		$('#PlugInState').val(editor.text());
		$('#addPlugInForm').submit();
	}
};