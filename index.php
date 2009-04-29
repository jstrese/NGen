<?php
	error_reporting(E_ALL);
	
	ini_set('include_path', get_include_path() . PATH_SEPARATOR . './Core/' . PATH_SEPARATOR . './Core/Drivers/' . PATH_SEPARATOR . './Core/Exceptions/' . PATH_SEPARATOR . './Core/Interfaces/' . PATH_SEPARATOR . './Core/User Objects/' . PATH_SEPARATOR . './Core/Drivers/Page/' . PATH_SEPARATOR . './Core/Drivers/Database/');
		
	function __autoload($method)
	{
		include_once($method . '.php');
	}
		
	function exception_handler($exception)
	{
		Page::getInstance2()->load_error($exception, isset($exception->type) ? $exception->type : 'general');
	}
	
	set_exception_handler('exception_handler');
	
	function error_handler($errno, $errstr, $errfile, $errline)
	{
		Page::getInstance2()->load_error2($errno, $errstr, $errfile, $errline);
	}
	
	require_once('./config.php');
	
	set_error_handler('error_handler');
	
	date_default_timezone_set($configs['timezone']);
	
	NGenCore::$configs = $configs;
	
	NGenCore::$configs['__section'] = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
	NGenCore::$configs['__action'] = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';
	
	Page::getInstance()->load();
?>