<?php
	error_reporting(E_ALL);

	//
	// NOTES
	//   - Apache's document root is the realpath.
	//   - Nginx's document root is not a realpath,
	//     checks are in place to counter this.
	//
	//   realpath()'s performance differs from setup to
	//   setup. That said, we chose the lesser of two
	//   evils and decided to use the document_root
	//   whenever possible.
	//
	//   The current method excels by a fairly large
	//   margin on servers where realpath() is expensive.
	//   However, this method is MARGINALLY slower on
	//   setups where realpath() is viable (~5% margin).
	//
	if(!defined('APP_PATH'))
	{
		$dir = str_replace('\\', '/', dirname(__FILE__));
 		$doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : getenv('DOCUMENT_ROOT');
 		//
 		// If the document root is not found within the
 		// directory path, then the server is sending
 		// a non-realpath path as the document root.
 		// This can be observed when using Nginx.
 		//
	 	if(strstr($dir, $doc_root))
	 	{
			define('APP_PATH', $doc_root.str_replace($doc_root, '', $dir).'/');
	 	}
	 	else
	 	{
	 		define('APP_PATH', realpath($dir).'/');
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

	if($renderer = Renderer::getInstance())
	{
		$renderer->render();
	}
?>