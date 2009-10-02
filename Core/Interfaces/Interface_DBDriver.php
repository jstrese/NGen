<?php
	interface Interface_DBDriver
	{
		public function __construct();
		// Required to remove instance from instance pool
		public function __destruct();
	}
?>