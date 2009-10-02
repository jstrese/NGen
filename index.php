<?php
	error_reporting(E_ALL);

	//
	// NOTES
	//   Rumor has it that DOCUMENT_ROOT isn't always
	//   viable. However, we still use it because we
	//	 find it viable for our userbase. If any troubles
	//   arise, we can revert this to:
	//     realpath(dirname(__FILE__))
	//
	//   -- although we would like to stay away from realpath()
	//   -- since it's an expensive call. DOCUMENT_ROOT should
	//   -- already be realpath'd to our knowledge.
	//
	if(!defined('APP_PATH'))
	{
		if(isset($_SERVER['DOCUMENT_ROOT']))
		{
			define('APP_PATH', $_SERVER['DOCUMENT_ROOT'] . str_replace(array('\\', $_SERVER['DOCUMENT_ROOT']), array('/', ''), dirname(__FILE__)).'/');
		}
		else
		{
			$env = getenv('DOCUMENT_ROOT');
			define('APP_PATH', $env . str_replace(array('\\', $env), array('/', ''), dirname(__FILE__)).'/');
		}
	}

	require_once(APP_PATH.'Core/NGen.php');
	spl_autoload_register('NGen::__loader_drivers');
	spl_autoload_register('NGen::__loader_interfaces');
	spl_autoload_register('NGen::__loader_user_objects');

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

	require_once(APP_PATH.'config.php');

	set_error_handler('error_handler');

	date_default_timezone_set($configs['timezone']);

	NGen::$configs = $configs;

	RequestHandler::HandleRequest();

	Renderer::getInstance()->render();
?>