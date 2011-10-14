<?php
	interface Interface_Renderer
	{
		public function __construct($cache = false, $cache_lifetime = 86400, $error = false);
		public function render();
	}