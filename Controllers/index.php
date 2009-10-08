<?php
	class OnLoad
	{
		//
		// This function is optional
		//
		//public static function all()
		//{
			// This executes before every page render
		//}

		public static function testbed_usercp()
		{
			$session = Session::getInstance();

			if(!$session->authenticate() || !isset($session->logged) || $session->logged !== 1)
			{
				Renderer::redirect('testbed/login');
			}
		}

		//
		// The following function is an example of how to
		// make section-specific default actions.
		//
		//public static function home()
		//{
			// This executes before any page in the
			// 'home' section (IE: before home.index)
		//}
	}
?>