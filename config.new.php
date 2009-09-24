<?php
	// Title of the site
	$configs['site_title'] = 'My Site';

	// Database settings
	// $configs['db'][0]['user'] = '';	// Database username
	// $configs['db'][0]['pass'] = '';	// Database password
	// $configs['db'][0]['host'] = '';	// Database hostname (normally 'localhost', can also be an IP address or domain name)
	// $configs['db'][0]['base'] = '';	// Database name

	// Database driver type [None, MySQL, SQLite] (all-capitalized)
	// $configs['db'][0]['driver'] = NGenCore::SQL_NONE;	// Example: for MySQL use NGenCore::SQL_MYSQL

	// Document root (path to this file) with trailing forward slash
	$configs['document_root'] = '/example/site/';
	// Page driver type [None, Smarty, XPOP]
	$configs['page_driver'] = NGenCore::PAGE_SMARTY;
	// How long the cache lasts
	$configs['page_cache_lifetime'] = 86400;

	// Page cache
	//
	// 	0 Disabled
	//		No cache, whatsoever.
	// 	1 Enabled
	//		Enabled, all pages use the same $cache_time as defined in config.php
	// 	2 Enabled / Granular
	//		Use the $cache_time that was set when the page was cached. Allows for different $cache_time's amongst pages.

	$configs['cache'] = 2;

	// Name of the theme; /Styles/[theme]/
	$configs['theme'] = 'default';

	//
	// Default actions can be very useful, however for demonstration
	// purposes, it is disabled by default. Default actions can be
	// configured if this is true, see /Sections/.default/main.php
	// for configuration.
	//
	$configs['use_default_actions'] = false;

	// Default timezone
	// Valid timezones: http://us2.php.net/manual/en/timezones.php
	$configs['timezone'] = 'America/Chicago';
?>