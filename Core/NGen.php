<?php
	class NGen
	{
		/**
		 * Global configuration array; accessible through the whole framework
		 */
		static public $configs;

		/**
		 * Accepted types of database drivers [None, MySQL, SQLite]
		 */
		const SQL_NONE		= 0;
		const SQL_MYSQL		= 1;
		const SQL_PGSQL		= 2;
		const SQL_SQLITE	= 3;

		/**
		 * Available template systems
		 */
		const RENDERER_NONE		= 0;		// Not implemented
		const RENDERER_SMARTY	= 2;

		static public function __loader_drivers($class)
		{
			if($n = strpos($class, '_'))
			{
				$file = APP_PATH.'Core/Drivers/'.substr($class, 0, $n).'/'.$class.'.php';
				if(is_file($file))
				{
					require_once($file);
				}
			}
			else
			{
				$file = APP_PATH.'Core/Drivers/'.$class.'.php';
				if(is_file($file))
				{
					require_once($file);
				}
			}
		}

		static public function __loader_interfaces($interface)
		{
			$file = APP_PATH.'Core/Interfaces/'.$interface.'.php';
			if(is_file($file))
			{
				require_once($file);
			}
		}

		static public function __loader_user_objects($userobj)
		{
			if(strpos($userobj, '_'))
			{
				$file = APP_PATH.'Core/User Objects/'.str_replace('_', '/', $userobj).'.php';
				if(is_file($file))
				{
					require_once($file);
				}
			}
			else
			{
				$file = APP_PATH.'Core/User Objects/'.$userobj.'.php';
				if(is_file($file))
				{
					require_once($file);
				}
			}
		}
	}
?>