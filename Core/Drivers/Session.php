<?php
	class Session
	{
		static protected $instance  = null;
		static public $use_auth_key = false;

		static public function getInstance($use_auth_key = false)
		{
			if(self::$instance === null)
			{
				session_start();
				register_shutdown_function('Session::destruct');

				self::$use_auth_key = $use_auth_key;

				switch(NGen::$configs['session_driver'])
				{
					default:
					case NGen::SESSION_GENERIC:
						self::$instance = new Session_Generic();
						break;
				}
			}

			return self::$instance;
		}

		static public function destruct()
		{
   			$_SESSION = self::$instance->convertToArray();
		}
	}
?>