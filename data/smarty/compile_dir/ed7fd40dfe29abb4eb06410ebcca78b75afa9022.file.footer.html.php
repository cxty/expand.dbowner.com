<?php /* Smarty version Smarty-3.0.8, created on 2014-05-19 11:04:16
         compiled from "./templates/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:1707391618537974b0421bd2-46050887%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed7fd40dfe29abb4eb06410ebcca78b75afa9022' => 
    array (
      0 => './templates/footer.html',
      1 => 1400468654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1707391618537974b0421bd2-46050887',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

<div class="footer_box">
	<div class="footer_inner">
    	<div class="copyright">Copyright <?php echo date('Y');?>
 Yannyo Inc.,  </div>
        <div class="menu"><span id="lang_box"></span>&nbsp;<a href="#"><?php echo $_smarty_tpl->getVariable('Lang')->value['Log'];?>
</a>&nbsp;|&nbsp;<a href="#"><?php echo $_smarty_tpl->getVariable('Lang')->value['Workground'];?>
</a>&nbsp;|&nbsp;<a href="#"><?php echo $_smarty_tpl->getVariable('Lang')->value['Privacy'];?>
</a>&nbsp;|&nbsp;<a href="#"><?php echo $_smarty_tpl->getVariable('Lang')->value['ConnactUs'];?>
</a></div>
    </div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/common.js"></script>
<script language="javascript" type="text/javascript">
var Common = new TCommon();
//页面完全再入后初始化
$(document).ready(function(){
	Common.init();
});
//释放
$(window).unload(function(){
	Common = null;
});
</script>

</body>
</html>