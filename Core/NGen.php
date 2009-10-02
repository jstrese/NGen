<?php
	class NGen
	{
		/**
		 * Global configuration array; accessible through the whole framework
		 * @staticvar
		 * @public
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
		const RENDERER_NONE		= 0; // Not implemented
		const RENDERER_SMARTY	= 2;

		/**
		 * First in queue for spl_autoload, attempts to load a driver
		 * @param $driver The driver to be loaded
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
		static public function __loader_drivers($driver)
		{
			if($n = strpos($driver, '_'))
			{
				$file = APP_PATH.'Core/Drivers/'.substr($driver, 0, $n).'/'.$driver.'.php';
				if(is_file($file))
				{
					require_once($file);
				}
			}
			else
			{
				$file = APP_PATH.'Core/Drivers/'.$driver.'.php';
				if(is_file($file))
				{
					require_once($file);
				}
			}
		}

		/**
		 * Second in queue for spl_autoload, attempts to load an interface
		 * @param $interface The interface to be loaded
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
		static public function __loader_interfaces($interface)
		{
			$file = APP_PATH.'Core/Interfaces/'.$interface.'.php';
			if(is_file($file))
			{
				require_once($file);
			}
		}

		/**
		 * Thurd in queue for spl_autoload, attempts to load a user object
		 * User object names can be organized via the underscore.
		 * For example, MyTestClass would be loaded from /User Objects/MyTestClass.php
		 * and MyTestClass_Extended would load from /User Objects/MyTestClass/MyTestClass_Extended.php
		 * @param $userobj The user object to be loaded
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
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