<?php /* Smarty version Smarty-3.0.8, created on 2014-04-15 14:31:47
         compiled from "./templates/header.html" */ ?>
<?php /*%%SmartyHeaderCode:426447828534cd253cebb81-59925795%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd701c050746a5ee258b58a5bc4dd80a198b99e51' => 
    array (
      0 => './templates/header.html',
      1 => 1397543490,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '426447828534cd253cebb81-59925795',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_smarty_tpl->getVariable('title')->value;?>
</title>
<?php $_template = new Smarty_Internal_Template('link.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

</head>
  
<body>
<div class="header_box">
	<div class="header_inner">
		<div class="h_img"><a href="/" ><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/ico_2.png"  height="35" /></a></div>
		<div class="h_left">
             | <a href="/plugIn"><?php echo $_smarty_tpl->getVariable('Lang')->value['AppExtend'];?>
</a>
        </div>
        <div class="h_right">
            <script src="http://auth.dbowner.com/provitejs/userbox?lang=zh" language="javascript" type="text/javascript"></script>
        </div>
	</div>
</div>

<script language="javascript" type="text/javascript">
var headerJs = new TheaderJs();
headerJs.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
//页面完全再入后初始化
$(document).ready(function(){
	headerJs.init();
});
//释放
$(window).unload(function(){
	headerJs = null;
});
</script>