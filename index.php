<?php
	error_reporting(E_ALL);

	if(PATH_SEPARATOR === ';')
	{
		ini_set('include_path', get_include_path() . ';./Core/;./Core/Drivers/;./Core/Interfaces/;./Core/User Objects/;./Core/Drivers/Renderer/;./Core/Drivers/Database/');
	}
	else
	{
		ini_set('include_path', get_include_path() . ':./Core/:./Core/Drivers/:./Core/Interfaces/:./Core/User Objects/:./Core/Drivers/Renderer/:./Core/Drivers/Database/');
	}

	function __autoload($class)
	{
		require_once($class . '.php');
	}

	function exception_handler($exception)
	{
		try
		{
			Renderer::getInstance2()->display_error($exception);
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

	NGenCore::$configs = $configs;

	RequestHandler::HandleRequest();

	Renderer::getInstance()->render();
?>