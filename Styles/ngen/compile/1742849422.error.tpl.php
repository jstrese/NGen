<?php /*a:0:{}*/ ?>
<?php if(!defined('SMARTY_DIR')) exit('no direct access allowed'); ?>
<?php /* Smarty version Smarty3Alpha, created on 2009-04-22 19:25:55
         compiled from "./Styles/ngen/\error.tpl" */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Jason" />
	<title><?php echo $this->smarty->filter_handler->execute('variable', $_smarty_tpl->smarty->plugin_handler->capitalize(array($_smarty_tpl->getVariable('type')->value),'modifier'),null);?>
 Error</title>
	<style>
		h1 {
			font-weight: bold;
			color: maroon;
			padding: 0;
			margin: 0;
		}
		.box {
			border: 2px solid green;
			min-width: 200px;
			margin: 0px 25px 25px 25px;
			padding: 5px;
		}
	</style>
</head>
<body>
	<p>
		<h1>NGen &raquo; <?php echo $this->smarty->filter_handler->execute('variable', $_smarty_tpl->smarty->plugin_handler->capitalize(array($_smarty_tpl->getVariable('type')->value),'modifier'),null);?>
 Error</h1><br /><br />
		<p class="box">
			<strong>Exception Encountered</strong><br />
			<strong>Code</strong>: <?php echo $this->smarty->filter_handler->execute('variable', $_smarty_tpl->getVariable('code')->value,null);?>
<br />
			<strong>Line</strong>: <?php echo $this->smarty->filter_handler->execute('variable', $_smarty_tpl->getVariable('line')->value,null);?>
<br />
			<strong>Location</strong>: <?php echo $this->smarty->filter_handler->execute('variable', $_smarty_tpl->getVariable('file')->value,null);?>
<br />
			<em><?php echo $this->smarty->filter_handler->execute('variable', $_smarty_tpl->getVariable('message')->value,null);?>
</em>
		</p>
	</p>
</body>
</html>