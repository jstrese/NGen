<?php
	//
	// Title of the site
	//
	$configs['site_title'] = 'My Site';

	//
	// Database settings
	//
	// $configs['db'][0]['user'] = '';	// Database username
	// $configs['db'][0]['pass'] = '';	// Database password
	// $configs['db'][0]['host'] = '';	// Database hostname (normally 'localhost', can also be an IP address or domain name)
	// $configs['db'][0]['base'] = '';	// Database name

	//
	// Database driver type [None, MySQL, SQLite] (all-capitalized)
	//
	// $configs['db'][0]['driver'] = NGen::SQL_NONE;	// Example: for MySQL use NGenCore::SQL_MYSQL

	//
	// Document root (path to this file) with trailing forward slash
	//
	$configs['document_root'] = '/example/site/';
	//
	// Page driver type [None, Smarty]
	//
	$configs['renderer_driver'] = NGen::RENDERER_SMARTY;

	//
	// Session driver
	// - The SESSION_GENERIC driver is the default secure
	// - session driver. Other drivers may be added to allow
	// - additional functionality and/or database access
	//
	$configs['session_driver'] = NGen::SESSION_GENERIC;

	//
	// Page cache
	//  Set to 'true' to enable caching, 'false' to disable
	// - Note: This can be individually configured for a per-page
	//         basis in the control files.
	//
	$configs['cache'] = true;
	//
	// How long the cache lasts
	//
	$configs['page_cache_lifetime'] = 86400;

	//
	// Name of the theme; /Styles/[theme]/
	//
	$configs['theme'] = 'default';

	//
	// OnLoad events can be very useful, however for demonstration
	// purposes, it is disabled by default. OnLoad events can be
	// configured if this is true, see /Controls/index.php
	// for configuration.
	//
	$configs['use_onload'] = false;

	//
	// Default timezone
	// Valid timezones: http://us2.php.net/manual/en/timezones.php
	//
	$configs['timezone'] = 'America/Chicago';