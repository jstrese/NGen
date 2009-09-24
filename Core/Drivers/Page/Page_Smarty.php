<?php
	// Smarty
	require_once('./Addons/Smarty/libs/Smarty.class.php');

	define('SMARTY_EXCEPTION_HANDLER', 0);
	define('TEMPLATE_DIR', './Styles/' . NGenCore::$configs['theme'] . '/');
	define('COMPILE_DIR', TEMPLATE_DIR . 'compile/');
	define('CONFIG_DIR', TEMPLATE_DIR . 'config/');
	define('CACHE_DIR', TEMPLATE_DIR . 'cache/');
	
	//
	//TODO: Edit error handler!
	//
	
	class Page_Smarty extends Smarty implements Interface_Page
	{
		/**
		 * Template file
		 */
		public $tpl = '';
		/**
		 * Action file
		 */
		public $action = '';
		/**
		 * Action Object
		 */
		private $actObj = false;
		/**
		 * Are we using the default action feature?
		 */
	 	private $use_default = true;
		/**
		 * Cache method: CACHE_DISABLED
		 * No cache is used
		 * @link http://www.smarty.net/manual/en/variable.caching.php
		 */
		const CACHE_DISABLED = 0;
		/**
		 * Cache method: CACHE_ENABLED
		 * Enabled page cache for ALL, regardless of if you want them cached or not, pages with a 'global' lifetime
		 * @link http://www.smarty.net/manual/en/variable.caching.php
		 */
		const CACHE_ENABLED = 1;
		/**
		 * Cache method: CACHE_DYNAMIC
		 * Caching is page-dependant. The lifetime can be different for each page, also caching can be disabling on a per-page basis.
		 * @link http://www.smarty.net/manual/en/variable.caching.php
		 */
		const CACHE_DYNAMIC = 2;
				
		public function __construct($cache = self::CACHE_DISABLED, $cache_lifetime = 86400, $use_default = true, $error = false)
		{
			if(!$error)
			{
				$this->tpl = RequestHandler::GetTemplateName();
				$this->action = RequestHandler::GetActionPath();
				
				$this->loadAction();
			}
			
			// Initialize Smarty
			parent::__construct();
							
			// Change the default template directories
			$this->template_dir = array(TEMPLATE_DIR);
			$this->compile_dir = COMPILE_DIR;
			$this->config_dir = CONFIG_DIR;
			$this->cache_dir = CACHE_DIR;
			
			// Smarty3
			$this->auto_literal = true;

			// Change default caching behavior
			$this->caching = $cache;
				
			$this->cache_lifetime = $cache_lifetime;
		}
				
				
		/**
		 * If default actions are enabled (see: $use_default), we attempt to load the default
		 * action. Once loaded, we check for two things: a function that executes on every
		 * page for every section (DefaultAction::section_all()), and a function for
		 * section-specific usage (DefaultAction::section_<section>()). Both of these
		 * functions are optional, and are only called if they exist.
		 * @private
		 * @since 2.1
		 * @return Null Does not return anything.
		 */
		private function defaultAction()
		{
			if(file_exists(RequestHandler::SECTION_DIR . RequestHandler::DEFAULT_ACTION . '.php'))
			{
				require_once(RequestHandler::SECTION_DIR . RequestHandler::DEFAULT_ACTION . '.php');
				
				// Try to run DefaultAction::section_all() -- which affects all pages, in all sections
				if(method_exists('DefaultAction', 'section_all'))
				{
					try
					{
						DefaultAction::section_all();
					}catch(Exception $ex){ $this->display_error($ex); die(); }
				}
				
				// Try to run section-specific default action (section_*)
				$section = RequestHandler::$requestParts;
				// shove the action element off the array
				array_pop($section);
				$section = implode('_', $section);
				
				if(method_exists('DefaultAction', 'section_'.$section))
				{
					try
					{
						call_user_func('DefaultAction::section_'.$section);
					}catch(Exception $ex){ $this->display_error($ex); die(); }
				}
			}
		}
		
		private function loadAction()
		{
			// This file already exists, it was checked by the RequestHandler
			include_once($this->action);
			
			// The reason we check is because, for static files, it's logical
			// to just have a blank action file. If it's blank, then there
			// won't be an Action class, else there will be.
			$this->actObj = class_exists('Action', false);
		}
														
		/**
		 * Performs the page action and displays the page [if not silenced]
		 * @public
		 * @since 2.0
		 * @return Null Does not return anything.
		 */
		public function load()
		{
			$vars = Page::$vars;
			$configs = NGenCore::$configs;
						
			// Attempts to run the default action (if enabled)
			if($this->use_default)
			{
				$this->defaultAction();
			}

			// Variables for use in the page
			$vars['base_url'] = $configs['document_root'];
			$vars['style_url'] = TEMPLATE_DIR;
			$vars['site_title'] = $configs['site_title'];
			//$vars['page_desc'] = ($this->actObj && isset(Action::$description)) ? (substr(Action::$description, -6) === '|more|' ? rtrim(Action::$description, '|more|').'- '.$this->section.($this->action !== DEFAULT_ACTION ? ' - '.$this->action:''): Action::$description) : '- '.$this->section.($this->action !== DEFAULT_ACTION ? ' - '.$this->action:'');
			
			$this->assign($vars);

			if($this->actObj)
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
					if(!$this->is_cached($this->tpl, $this->cache_id) && method_exists('Action', 'run'))
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
					$this->display($this->tpl, $this->cache_id);
				}
			}
			else
			{
				$this->display($this->tpl, $this->cache_id);
			}
		}
		
		/**
		 * Clears the cache for a select template
		 * @public
		 * @since 2.1
		 * @return Null Does not return anything.
		 */
	 	public function clear_cache($section, $action, $cache_id = null, $compile_id = null, $expire_time = null)
	 	{
	 		parent::clear_cache($section.'.'.$action.'.tpl', $cache_id, $compile_id, $expire_time);
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
			
			$this->assign(array(
				'section' => 'BLANK', // edit this
				'action' => 'BLANK', // edit this
				'errLine' => $exception->getLine(),
				'errFile' => $exception->getFile(),
				'errMsg' => $exception->getMessage()
			));
			
			$this->display('debug.tpl');
		}
	}
?>