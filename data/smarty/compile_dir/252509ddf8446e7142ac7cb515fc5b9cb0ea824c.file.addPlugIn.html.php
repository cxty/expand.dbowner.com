<?php /* Smarty version Smarty-3.0.8, created on 2014-04-15 14:31:50
         compiled from "./templates/plugIn/addPlugIn.html" */ ?>
<?php /*%%SmartyHeaderCode:1040368871534cd2565932d4-17370914%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '252509ddf8446e7142ac7cb515fc5b9cb0ea824c' => 
    array (
      0 => './templates/plugIn/addPlugIn.html',
      1 => 1397543489,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1040368871534cd2565932d4-17370914',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<?php $_template = new Smarty_Internal_Template('kindsoft.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.jUploader-1.0.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB_addPlugIn.js" ></script>

<div class="plugIn_box">
	<div class="plugIn_t_left"><a class="btn" href="javascript:void(0);" onclick="javascript:addPlugIn.backPlugIn();"><?php echo $_smarty_tpl->getVariable('Lang')->value['Back'];?>
</a></div>
	<div class="plugIn_t_right">
		<form id="addPlugInForm" method="post" action="/plugIn/savePlugIn">
			<div class="add_plugIn_box h_650 l_btm">		
				<input type="hidden" name="AppPlugInID" id="AppPlugInID" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['AppPlugInID'];?>
" />
				<input type="hidden" name="pIcoCode_512" id="loadPicture_0_va" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['pIcoCode_512'];?>
" />
				<input type="hidden" name="pIcoCode_256" id="loadPicture_1_va" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['pIcoCode_256'];?>
" />
				<input type="hidden" name="pIcoCode_128" id="loadPicture_2_va" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['pIcoCode_128'];?>
" />
				<input type="hidden" name="pIcoCode_64" id="loadPicture_3_va" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['pIcoCode_64'];?>
" />
				<input type="hidden" name="pPlugInState" id="PlugInState" />
				<div class="ap_title">1.<?php echo $_smarty_tpl->getVariable('Lang')->value['AddPlugInFirst'];?>
</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PluginUniqueCode'];?>
</div>
					<div class="ap_li_content"><input type="text" name="pUniqueCode" id="pUniqueCode" class="input w_475"  size="80" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['pUniqueCode'];?>
" readonly /></div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInName'];?>
</div>
					<div class="ap_li_content"><input type="text" name="PlugInName" id="PlugInName" class="input w_475"  size="80" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['PlugInName'];?>
" /></div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInCode'];?>
</div>
					<div class="ap_li_content"><input type="text" name="PlugInCode" id="PlugInCode" class="input w_475" size="80" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['PlugInCode'];?>
" /></div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInProperty'];?>
</div>
					<div class="ap_li_content">
						<input type="radio" name="PlugInProperty" value="0" checked /><?php echo $_smarty_tpl->getVariable('Lang')->value['ChooseMult'];?>

						<input type="radio" name="PlugInProperty" value="1" <?php if ($_smarty_tpl->getVariable('listInfo')->value['pProperty']==1){?>checked<?php }?>/><?php echo $_smarty_tpl->getVariable('Lang')->value['ChooseSigle'];?>

					</div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInType'];?>
</div>
					<div class="ap_li_content"><?php echo $_smarty_tpl->getVariable('PlugInClass')->value;?>
</div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInDB'];?>
</div>
					<div class="ap_li_content"><input type="text" name="pPoint" id="pPoint" class="input" size="4" value="<?php if ($_smarty_tpl->getVariable('listInfo')->value['pPoint']){?><?php echo $_smarty_tpl->getVariable('listInfo')->value['pPoint'];?>
<?php }else{ ?>0<?php }?>" />&nbsp;<?php echo $_smarty_tpl->getVariable('Lang')->value['DBByEveryDay'];?>
</div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugIcoCode'];?>
</div>
					<div class="ap_li_content p_h_70" id="loadPicture">
						<div class="pic_box pic_box_512" id="loadPicture_0" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['Pic_512'];?>
"></div>
						<div class="pic_box m_l_15 pic_box_256" id="loadPicture_1" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['Pic_256'];?>
"></div>
						<div class="pic_box m_l_15 pic_box_128" id="loadPicture_2" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['Pic_128'];?>
"></div>
						<div class="pic_box m_l_15 pic_box_64" id="loadPicture_3" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['Pic_64'];?>
"></div>
					</div>
				</div>
				<div class="ap_li">
					<div class="ap_li_title"><span class="f_red">*&nbsp;</span><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInState'];?>
</div>
					<div class="ap_li_content"><textarea id="editor" style="width:490px;height:200px;background:#fff"><?php echo $_smarty_tpl->getVariable('listInfo')->value['pPlugInState'];?>
</textarea></div>
				</div>
			</div>
			<div class="add_plugIn_box h_100 l_btm" id="plugInIframe">
				<div class="ap_title">2.<?php echo $_smarty_tpl->getVariable('Lang')->value['AddPlugInIframe'];?>
</div>
				<div class="ap_li">
					<div class="ap_li_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AddPlugInIframeUrl'];?>
</div>
					<div class="ap_li_content" id="pradio_wrap">
						<input type="radio" name="pUrlRadio" value="1" <?php if ($_smarty_tpl->getVariable('listInfo')->value['pUrl']!=''){?>checked<?php }?> /><?php echo $_smarty_tpl->getVariable('Lang')->value['ChooseAdd'];?>

						<input type="radio" name="pUrlRadio" value="2" <?php if ($_smarty_tpl->getVariable('listInfo')->value['pUrl']==''){?>checked<?php }?> /><?php echo $_smarty_tpl->getVariable('Lang')->value['ChooseNoAdd'];?>

					</div>
				</div>
				<div class="ap_li" id="purl_wrap" style="display:none;">
					<div class="ap_li_title">&nbsp;</div>
					<div class="ap_li_content"><input type="text" name="pUrl" class="input w_475"  size="80" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['pUrl'];?>
" /></div>
				</div>
			</div>
			<div class="add_plugIn_box l_btm">
				<div class="ap_title">3.<?php echo $_smarty_tpl->getVariable('Lang')->value['AddPlugInInterface'];?>
</div>
				<div id="pi_st_api_bf"></div>
				<div id="pi_st_add_bt"></div>
				<div id="pi_st_inter"></div>
			</div>
			<div class="add_plugIn_box">
				<div class="ap_btn">
					<a href="javascript:void(0);" onclick="javascript:addPlugIn.backPlugIn();" class="btn"><?php echo $_smarty_tpl->getVariable('Lang')->value['Back'];?>
</a>
					<a href="javascript:void(0);" onclick="javascript:addPlugIn.doSubmit();" class="btn m_l_20"><?php echo $_smarty_tpl->getVariable('Lang')->value['Submit'];?>
</a>
				</div>
			</div>
		</form>
	</div>
</div>

<script language="javascript" type="text/javascript">
var addPlugIn = new TaddPlugIn();
addPlugIn.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
//页面完全再入后初始化
$(document).ready(function(){
	addPlugIn.init();
});
//释放
$(window).unload(function(){
	addPlugIn = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>