<?php
	class NGenCore
	{
		/**
		 * Global configuration array; accessible through the whole framework
		 */
		static public $configs;

		/**
		 * Accepted types of database drivers [None, MySQL, SQLite]
		 */
		const SQL_NONE		= 0;
		const SQL_MYSQL		= 1;
		const SQL_PGSQL		= 2;
		const SQL_SQLITE	= 3;

		/**
		 * Available template systems
		 */
		const RENDERER_NONE		= 0;		// Not implemented
		const RENDERER_SMARTY	= 2;
	}
?>