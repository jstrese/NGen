<?php
	class Control extends Controller
	{
		//
		// $caching must be set to true for any caching to happen
		//
		// static public $caching = false;
		//
		//  $lifetime is the time (in seconds) the cache is good for
		//
		// static public $lifetime = 5;

		//
		// $cache_uid gives the cache a unique id -- useful for caching the
		// same page twice but with different/altered content
		//
		// static public $cache_uid = 'foo';

		//
		// This sets a description for the page -- to be displayed after the
		// site title (IE: N-Gen - Creativity at mind; where "N-Gen" is the
		// title and "Creativity at mind" is the description).
		//
		// static public $description = '- Creativity at mind';

		//
		// execute() is actually optional now
		// This way you can provide cache information without running anything
		// just comment the function out, or remove it from the source file if
		// you don't use it.
		//
		//public function execute()
		//{
			// ...
		//}
	}
?>