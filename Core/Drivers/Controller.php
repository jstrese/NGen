<?php
	class Controller
	{
		static public $vars = array();

		//
		// This sets a description for the page -- to be displayed after the
		// site title (IE: N-Gen - Creativity at mind; where "N-Gen" is the
		// title and "Creativity at mind" is the description).
		//
		static public $description    = null;
		//
		// $caching must be set to true for any caching to happen
		//
		static public $caching        = null;
		//
		//  $cache_lifetime is the time (in seconds) the cache is good for
		//
		static public $cache_lifetime = null;
		//
		// $cache_uid gives the cache a unique id -- useful for caching the
		// same page twice but with different/altered content
		//
		static public $cache_uid      = null;


		//static public function render($view)
		//{
		//	Renderer::getInstance()->render($view.'.tpl');
		//}
	}
?>