/**
 * 
 * soap测试方法
 * 
 * @author wbqing405@sina.com
 * 
 */
function TsoapTest(){
	this.JS_LANG = '';
}
TsoapTest.prototype.init = function(){
	soapTest.addParams(1);
	
	$('#selectMethod').change(function(){
		soapTest.addParams($(this).val());	
	});
};
TsoapTest.prototype.addParams = function(type){
	var html = '';
	switch(parseInt(type)){
		case 1:
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodCondition + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcCondition" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			html += '<div class="soap_li h_90">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodOrder + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcOrder" rows="3" cols="100" class="textarea"></textarea></div>';
			html += '</div>';			
			break;
		case 2:
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodCondition + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcCondition" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodParams + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcParams" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			break;
		case 3:
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodParams + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcParams" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			break;
		case 4:
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodCondition + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcCondition" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			break;
		case 5:
			html += '<div class="soap_li h_35">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodPageSize + '</div>';
			html += '<div class="soap_li_co"><input type="input" id="funcPageSize" size="135" class="input" /></div>';
			html += '</div>';
			html += '<div class="soap_li h_35">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodPage + '</div>';
			html += '<div class="soap_li_co"><input type="input" id="funcPage" size="135" class="input" /></div>';
			html += '</div>';		
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodCondition + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcCondition" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			html += '<div class="soap_li h_90">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodOrder + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcOrder" rows="3" cols="100" class="textarea"></textarea></div>';
			html += '</div>';	
			break;
		case 6:
			html += '<div class="soap_li h_35">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodID + '</div>';
			html += '<div class="soap_li_co"><input type="input" id="funcID" size="135" class="input" /></div>';
			html += '</div>';
			html += '<div class="soap_li h_140">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodCondition + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcCondition" rows="5" cols="100" class="textarea"></textarea></div>';
			html += '</div>';
			html += '<div class="soap_li h_90">';
			html += '<div class="soap_li_ti">' + this.JS_LANG.MethodOrder + '</div>';
			html += '<div class="soap_li_co"><textarea id="funcOrder" rows="3" cols="100" class="textarea"></textarea></div>';
			html += '</div>';	
			break;
	}
	$('#soap_list').html(html);
	html = null;
};
TsoapTest.prototype.doSearch = function(){
	$('#soap_iframe').html('');
	var url = '/private/doSearch?selectMethod='+$('#selectMethod').val()+'&funcName='+$('#funcName').val();
	switch(parseInt($('#selectMethod').val())){
		case 1:
			url += '&funcCondition='+$('#funcCondition').val()+'&funcOrder='+$('#funcOrder').val();
			break;
		case 2:
			url += '&funcCondition='+$('#funcCondition').val()+'&funcParams='+$('#funcParams').val();
			break;
		case 3:
			url += '&funcParams='+$('#funcParams').val();
			break;
		case 4:
			url += '&funcCondition='+$('#funcCondition').val();
			break;
		case 5:
			url += '&funcPageSize='+$('#funcPageSize').val()+'&funcPage='+$('#funcPage').val()+'&funcCondition='+$('#funcCondition').val()+'&funcOrder='+$('#funcOrder').val();
			break;
		case 6:url += '&funcID='+$('#funcID').val()+'&funcCondition='+$('#funcCondition').val()+'&funcOrder='+$('#funcOrder').val();
			break;
	}
	
	$('#soap_iframe').html('<iframe class="iframe" name="main" id="main" marginheight="0" marginwidth="0" frameborder="0" scrolling="auto"  style="width:960px;height:800px;" src="' + url + '"></iframe>');
	url = null;
};