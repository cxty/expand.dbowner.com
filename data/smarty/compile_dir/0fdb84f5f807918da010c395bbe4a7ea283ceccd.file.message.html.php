<?php /* Smarty version Smarty-3.0.8, created on 2014-04-16 11:53:30
         compiled from "./templates/throwMessage/message.html" */ ?>
<?php /*%%SmartyHeaderCode:614935423534dfeba5eceb1-43108572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0fdb84f5f807918da010c395bbe4a7ea283ceccd' => 
    array (
      0 => './templates/throwMessage/message.html',
      1 => 1397543490,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '614935423534dfeba5eceb1-43108572',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/throwMsg.js"></script>

<div class="content_box">
    <div class="content_box_c">
         <div class="content">
          	<div class="msg_box">
          		<div class="msg_rmd"><?php echo $_smarty_tpl->getVariable('Lang')->value['WarmRemind'];?>
：</div>
          		<div class="msg_cont">
          			<?php echo $_smarty_tpl->getVariable('msgArr')->value['msg'];?>

          			<?php if ($_smarty_tpl->getVariable('msgArr')->value['urlTurn']){?>
	          			<div class="time_out" id="time_out"></div>          			
          			<?php }?>
          			<input type="hidden" id="urlTurn" value="<?php echo $_smarty_tpl->getVariable('msgArr')->value['urlTurn'];?>
" />
          		</div>
			   	<div class="msg_app">
		   			<?php echo $_smarty_tpl->getVariable('Lang')->value['YouChoose'];?>
&nbsp;<a href="<?php echo $_smarty_tpl->getVariable('msgArr')->value['retry'];?>
" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['Retry'];?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['Retry'];?>
</a>&nbsp;<a href="javascript:history.back()" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['Back'];?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['Back'];?>
</a>&nbsp;<?php echo $_smarty_tpl->getVariable('Lang')->value['Or'];?>
&nbsp;<a href="<?php echo $_smarty_tpl->getVariable('msgArr')->value['url'];?>
" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['BackIndex'];?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['BackIndex'];?>
</a>
			   	</div>
			</div>
         </div>
    </div>
</div>
           
<script type="text/javascript">
var throwMsg = new TthrowMsg();
throwMsg.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	throwMsg.init();
});
//释放
$(window).unload(function(){
	throwMsg = null;
});
</script>
<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>