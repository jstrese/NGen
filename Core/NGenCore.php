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
		 * Available template systems [Smarty, XPOP]
		 */
		const PAGE_NONE		= 0;
		//const PAGE_XPOP		= 1;	// Experimental Page Operator; Generic -- not developed yet
		const PAGE_SMARTY	= 2;
	}
?>