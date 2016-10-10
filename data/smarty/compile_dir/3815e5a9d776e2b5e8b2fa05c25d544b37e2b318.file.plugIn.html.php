<?php /* Smarty version Smarty-3.0.8, created on 2014-04-17 09:55:07
         compiled from "./templates/plugIn/plugIn.html" */ ?>
<?php /*%%SmartyHeaderCode:319090636534f347b9c0b22-50417533%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3815e5a9d776e2b5e8b2fa05c25d544b37e2b318' => 
    array (
      0 => './templates/plugIn/plugIn.html',
      1 => 1397699705,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '319090636534f347b9c0b22-50417533',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB_plugIn.js" ></script>

<div class="plugIn_box">
	<div class="plugIn_t_left"><a class="btn" href="javascript:void(0);" onclick="javascript:plugIn.addPlugIn();"><?php echo $_smarty_tpl->getVariable('Lang')->value['AddPlugIn'];?>
</a></div>
	<div class="plugIn_t_right">
		<div class="plugIn_list" id="plugIn_list">
			<?php if ($_smarty_tpl->getVariable('listInfo')->value){?>
				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listInfo')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
					<div class="plugIn_info">
						<a href="javascript:void(0);" onclick="javascript:plugIn.modifyPlugIn(<?php echo $_smarty_tpl->tpl_vars['item']->value['AppPlugInID'];?>
)" class="img_list tiptip_plus" title="<div class='wb'><span class='fw_b'><?php echo $_smarty_tpl->getVariable('Lang')->value['PlugInState'];?>
</span>:<?php echo $_smarty_tpl->tpl_vars['item']->value['pPlugInState'];?>
</div>"><img src-data="<?php echo $_smarty_tpl->tpl_vars['item']->value['pIcoCode_128'];?>
" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/loading_128.gif"></a>
						<div class="plugIn_state"><a href="javascript:void(0);" onclick="javascript:plugIn.modifyPlugIn(<?php echo $_smarty_tpl->tpl_vars['item']->value['AppPlugInID'];?>
)" class="tiptip_plus"><?php echo $_smarty_tpl->tpl_vars['item']->value['PlugInName'];?>
</a></div>
						<div class="plugIn_exp"><?php if ($_smarty_tpl->tpl_vars['item']->value['pStatues']==2){?><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/wait_audit.png"><?php }?></div>
					</div>
				<?php }} ?>
			<?php }?>
		</div>
		<div class="showpage"><?php echo $_smarty_tpl->getVariable('showpage')->value;?>
</div>
	</div>
</div>

<script language="javascript" type="text/javascript">
var plugIn = new TplugIn();
plugIn.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
//页面完全再入后初始化
$(document).ready(function(){
	plugIn.init();
});
//释放
$(window).unload(function(){
	plugIn = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>