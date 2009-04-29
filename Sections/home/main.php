<?php
	class Action implements Interface_StandardAction
	{
		//
		// $caching must be set to true for any caching to happen
		//
		// static public $caching = true;
		
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
		// run() is actually optional now
		// This way you can provide cache information without running anything
		// just comment the function out, or remove it from the source file if
		// you don't use it.
		//
		static public function run() {
			// ...
		}
	}
?>