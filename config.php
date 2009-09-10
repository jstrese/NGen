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

	// How are pages cached
	//
	// 	0 Disabled
	//		No cache, whatsoever.
	// 	1 Enabled
	//		Every page is cached with the same time-interval
	// 	2 Dynamic <SMARTY ONLY>
	//		Each page has its own independant time-interval and cache status (enabled/disabled); both settings can be, meaning optionally, defined in module command files

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