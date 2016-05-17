<?php /* Smarty version Smarty-3.1.12, created on 2016-05-17 14:51:41
         compiled from "application/views/error/error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:872114614573ab522187298-31471894%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c0b2190b4992f512f7bab6783a31b2d9ff87b41' => 
    array (
      0 => 'application/views/error/error.tpl',
      1 => 1463467897,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '872114614573ab522187298-31471894',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_573ab522187904_66982275',
  'variables' => 
  array (
    'exception' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_573ab522187904_66982275')) {function content_573ab522187904_66982275($_smarty_tpl) {?><div><?php echo $_smarty_tpl->tpl_vars['exception']->value->getMessage();?>
</div>
<?php }} ?>