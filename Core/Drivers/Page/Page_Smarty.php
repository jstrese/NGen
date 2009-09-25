<?php
	// Smarty
	require_once('./Addons/Smarty/libs/Smarty.class.php');
	define('SMARTY_EXCEPTION_HANDLER', 0);
			
	class Page_Smarty extends Smarty implements Interface_Page
	{
		/**
		 * Cache method: CACHE_DISABLED
		 * No cache is used
		 * @link http://www.smarty.net/manual/en/variable.caching.php
		 */
		const CACHE_DISABLED = 0;
		/**
		 * Cache method: CACHE_ENABLED
		 * Enabled page cache for ALL, regardless of if you want
		 * them cached or not, pages with a 'global' lifetime
		 * @link http://www.smarty.net/manual/en/variable.caching.php
		 */
		const CACHE_ENABLED = 1;
		/**
		 * Cache method: CACHE_DYNAMIC
		 * Caching is page-dependant. The lifetime can be different
		 * for each page, also caching can be disabling on a per-page basis.
		 * @link http://www.smarty.net/manual/en/variable.caching.php
		 */
		const CACHE_DYNAMIC = 2;
				
		public function __construct($cache = self::CACHE_DISABLED, $cache_lifetime = 86400, $error = false)
		{
			if(!$error)
			{				
				Page::load_Action();
			}
			
			// Initialize Smarty
			parent::__construct();
						
			// Change the default template directories
			$this->template_dir = Page::$style_dir;
			$this->compile_dir  = $this->template_dir.'compile/';
			$this->config_dir   = $this->template_dir.'config/';
			$this->cache_dir    = $this->template_dir.'cache/';
			
			// Smarty3
			$this->auto_literal = true;

			// Change default caching behavior
			$this->caching      = $cache;
				
			$this->cache_lifetime = $cache_lifetime;
		}
														
		/**
		 * Performs the page action and displays the page [if not silenced]
		 * @public
		 * @since 2.0
		 * @return Null Does not return anything.
		 */
		public function load()
		{
			$vars    = Page::$vars;
			$configs = NGenCore::$configs;
						
			// Attempts to run the default action (if enabled)
			if($configs['use_default_actions'])
			{
				Page::load_DefaultActions();
			}

			// Variables for use in the page
			$vars['root_path']  = $configs['document_root'];
			$vars['style_path'] = $configs['document_root'].'Styles/'.$configs['theme'].'/';
			$vars['site_title'] = $configs['site_title'];
			$vars['page_desc']  = ucwords(implode(' &#187; ', RequestHandler::$requestParts));
			
			$this->assign($vars);

			if(Page::$use_action)
			{
				if(isset(Action::$caching))
				{
					$this->caching = Action::$caching;
				}
				
				if(isset(Action::$lifetime))
				{
					$this->cache_lifetime = Action::$lifetime;
				}
				
				if(isset(Action::$cache_uid))
				{
					$this->cache_id = Action::$cache_uid;
				}
				
				if($this->caching)
				{
					if(!$this->is_cached(Page::$template_file, $this->cache_id) && method_exists('Action', 'run'))
					{
						try
						{
							method_exists('Action', 'run') ? Action::run() : null;
						}catch(Exception $ex){ $this->display_error($ex); die(); }
					}
				}
				else
				{
					try
					{
						method_exists('Action', 'run') ? Action::run() : null;
					}catch(Exception $ex){ $this->display_error($ex); die(); }		
				}
				
				if(!isset(Action::$silence) || Action::$silence !== true)
				{
					$this->display(Page::$template_file, $this->cache_id);
				}
			}
			else
			{
				$this->display($this->tpl, $this->cache_id);
			}
		}
				
		/**
		 * Used by the exception handler to raise visual exceptions
		 * @public
		 * @since 2.1
		 * @return Null Does not return anything.
		 */
		public function display_error(exception $exception)
		{
			$this->caching = 0;
			$request       = RequestHandler::$request;
			
			$this->assign(array(
				'request' => isset($request[49]) ? substr($request, 0, 50).' ...' : $request,
				'request_raw' => $request,
				'request_qualified' => implode(' -> ', RequestHandler::$requestParts),
				'request_vars' => sizeof(RequestHandler::$variables) === 0 ? '(none)' : implode(', ', RequestHandler::$variables),
				'errLine' => $exception->getLine(),
				'errFile' => $exception->getFile(),
				'errMsg' => $exception->getMessage()
			));
			
			$this->display('debug.tpl');
		}
	}
?>