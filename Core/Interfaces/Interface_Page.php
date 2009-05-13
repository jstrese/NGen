<?php
	interface Interface_Page
	{
		public function __construct($section, $action, $cache = self::CACHE_DISABLED, $cache_lifetime = 86400, $error = false);
		public function load();
	}
?>