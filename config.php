<?php
	// Title of the site
	$configs['site_title'] = 'N-Gen';

	// Database settings
	$configs['db'][0]['user'] = '';
	$configs['db'][0]['pass'] = '';
	$configs['db'][0]['host'] = '';
	$configs['db'][0]['base'] = '';

	// Database driver type [None, MySQL, SQLite] (all-capitalized)
	$configs['db'][0]['driver'] = NGenCore::SQL_NONE;

	// Document root (path to this file)
	$configs['document_root'] = '/NGen2.1/';
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
	$configs['theme'] = 'ngen';

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