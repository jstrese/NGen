<?php
	interface Interface_Page
	{
		public function __construct($cache = self::CACHE_DISABLED, $cache_lifetime = 86400, $error = false);
		public function load();
	}
?>