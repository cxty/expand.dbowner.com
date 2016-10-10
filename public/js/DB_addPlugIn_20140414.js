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
	
	if($('#AppPlugInID').val() != ''){
		addPlugIn.initInfo($('#AppPlugInID').val());
	}
	
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
};
TaddPlugIn.prototype.IcoCode = function(){
	if($('#AppPlugInID').val() != ''){
		var _ico_url= 'http://file.dbowner.com/index.php?act=get&filecode=';
		$('#loadPicture div').eq(0).html('<img src="'+_ico_url+$('#loadPicture_0_va').val()+'&w512" />');
		$('#loadPicture div').eq(1).html('<img src="'+_ico_url+$('#loadPicture_1_va').val()+'&w512" />');
		$('#loadPicture div').eq(2).html('<img src="'+_ico_url+$('#loadPicture_2_va').val()+'&w512" />');
		$('#loadPicture div').eq(3).html('<img src="'+_ico_url+$('#loadPicture_3_va').val()+'&w512" />');
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
TaddPlugIn.prototype.initInfo = function(AppPlugInID){
	$.post('/plugIn/getApiUrlInfo',{AppPlugInID:AppPlugInID,rnd:Math.random()},function(data){
		for(var i=0;i<data.length;i++){
			var html = '';
			html += '<div class="ap_cont">';
			html += '<input type="text" class="input" name="apiUrl[]" size="88" value="'+data[i]['aUrl']+'" />';
			html += '<input type="hidden" name="ApiID[]" value="'+data[i]['ApiID']+'" />';
			html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delParamByID(\'aurl\','+data[i]['ApiID']+',this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
			html += '</div>';
			$('#pi_st_api_bf').append(
				$(html).find('input[type=text]').before(jQuery('<div class="def_txt">' + addPlugIn.JS_LANG.Ex_ValueApiUrl + '</div>').hide().click(function(){
			        $(this).hide();
			        $(this).next().focus();
			    })).focusin(function(){
					addPlugIn.plugInFocus(this);
				}).focusout(function(){
					addPlugIn.plugInBlur(this);
				}).parent()
			);
			html = null;
		}
	},'json');
	$.post('/plugIn/getParamsInfo',{AppPlugInID:AppPlugInID,rnd:Math.random()},function(data){
		for(var i=0;i<data['input'].length;i++){
			var html = '';
			html += '<div class="ap_cont">';
			html += '<input type="text" name="ipFieldName[]" class="input" size="20" value="'+data['input'][i]['pFieldName']+'" />';
			html += '&nbsp;<input type="text" name="ipFieldType[]" class="input" size="20" value="'+data['input'][i]['pFieldType']+'" />';
			html += '&nbsp;<input type="text" name="ipFieldState[]" class="input" size="40" value="'+data['input'][i]['pFieldState']+'" />';
			html += '<input type="hidden" name="ipParamsID[]" value="'+data['input'][i]['ParamsID']+'" />';
			html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delParamByID(\'input\','+data['input'][i]['ParamsID']+',this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
			html += '</div>';
			$('#pi_st_ip_bf').append(
				$(html).find('input[type=text]').each(function(ke){
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
					$(this).before(jQuery('<span class="def_txt">' + val + '</span>').hide().click(function(){
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
		}	
		for(var j=0;j<data['output'].length;j++){	
			var html = '';
			html += '<div class="ap_cont">';
			html += '<input type="text" name="opFieldName[]" class="input" size="20" value="'+data['output'][j]['pFieldName']+'" />';
			html += '&nbsp;<input type="text" name="opFieldType[]" class="input" size="20" value="'+data['output'][j]['pFieldType']+'" />';
			html += '&nbsp;<input type="text" name="opFieldState[]" class="input" size="40" value="'+data['output'][j]['pFieldState']+'" />';
			html += '<input type="hidden" name="opParamsID[]" value="'+data['output'][j]['ParamsID']+'" />';
			html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delParamByID(\'output\','+data['output'][j]['ParamsID']+',this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
			html += '</div>';
			$('#pi_st_op_bf').append(
				$(html).find('input[type=text]').each(function(ke){
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
					$(this).before(jQuery('<span class="def_txt">' + val + '</span>').hide().click(function(){
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
		}
	},'json');
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
				$(obj).parent().remove();
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
TaddPlugIn.prototype.addApi = function(){
	var html = '';
	html += '<div class="ap_cont">';
	html += '<input type="text" class="input" name="apiUrl[]" size="88" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delApi(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
	html += '</div>';
	$('#pi_st_api').append(
		$(html).find('input[type=text]').before(jQuery('<div class="def_txt">' + addPlugIn.JS_LANG.Ex_ValueApiUrl + '</div>').click(function(){
	        $(this).hide();
	        $(this).next().focus();
	    })).focusin(function(){
			addPlugIn.plugInFocus(this);
		}).focusout(function(){
			addPlugIn.plugInBlur(this);
		}).parent()
	);
	html = null;
};
TaddPlugIn.prototype.delApi = function(type){
	$(type).parent().remove();
};
TaddPlugIn.prototype.addInput = function(){
	var html = '';
	html += '<div class="ap_cont">';
	html += '<input type="text" name="ipFieldName[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="ipFieldType[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="ipFieldState[]" class="input" size="40" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInput(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
	html += '</div>';
	$('#pi_st_ip').append(
		$(html).find('input[type=text]').each(function(ke){
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
	$(type).parent().remove();
};
TaddPlugIn.prototype.addOutput = function(){
	var html = '';
	html += '<div class="ap_cont">';
	html += '<input type="text" name="opFieldName[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="opFieldType[]" class="input" size="20" />';
	html += '&nbsp;<input type="text" name="opFieldState[]" class="input" size="40" />';
	html += '&nbsp;<a href="javascript:void(0);" onclick="javascript:addPlugIn.delInput(this);" class="btn">' + addPlugIn.JS_LANG.Delete + '</a>';
	html += '</div>';
	$('#pi_st_op').append(
		$(html).find('input[type=text]').each(function(ke){
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
	$(type).parent().remove();
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