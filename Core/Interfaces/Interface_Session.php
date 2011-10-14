<?php
	interface Interface_Session
	{
		public function __construct();
		static public function del();
		static public function flush();
		public function __get($what);
		public function __set($what, $value);
		public function __toString();
		public function __clone();
	}