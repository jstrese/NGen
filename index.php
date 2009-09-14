<?php
	error_reporting(E_ALL);
	
	if(PATH_SEPARATOR === ';')
	{
		ini_set('include_path', get_include_path() . ';./Core/;./Core/Drivers/;./Core/Interfaces/;./Core/User Objects/;./Core/Drivers/Page/;./Core/Drivers/Database/');
	}
	else
	{
		ini_set('include_path', get_include_path() . ':./Core/:./Core/Drivers/:./Core/Interfaces/:./Core/User Objects/:./Core/Drivers/Page/:./Core/Drivers/Database/');
	}
		
	function __autoload($method)
	{
		require_once($method . '.php');
	}

	function exception_handler($exception)
	{
		try
		{
			Page::getInstance2()->display_error($exception);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}

	set_exception_handler('exception_handler');

	function error_handler($errno, $errstr, $errfile, $errline)
	{
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	require_once('./config.php');
	
	set_error_handler('error_handler');
	
	date_default_timezone_set($configs['timezone']);
	
	$configs['__section'] = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
	$configs['__action'] = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';
	NGenCore::$configs = $configs;

	Page::getInstance()->load();
?>