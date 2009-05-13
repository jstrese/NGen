<?php
	// Smarty
	require_once('./Addons/Smarty/libs/Smarty.class.php');

	define('TEMPLATE_DIR', './Styles/' . NGenCore::$configs['theme'] . '/');
	define('COMPILE_DIR', TEMPLATE_DIR . 'compile/');
	define('CONFIG_DIR', TEMPLATE_DIR . 'config/');
	define('CACHE_DIR', TEMPLATE_DIR . 'cache/');

	// NGen
	define('SECTION_DIR', './Sections/');
	define('DEFAULT_SECTION', 'home');
	define('DEFAULT_ACTION', 'main');
	
	class Page_Smarty extends Smarty implements Interface_Page
	{
		/**
		 * Template file
		 */
		public $tpl = '';
		/**
		 * Section requested
		 */
		public $section = '';
		/**
		 * Action to perform (Section->Action)
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
				
		public function __construct($section, $action, $cache = self::CACHE_DISABLED, $caching_lifetime = 86400, $use_default = true, $error = false)
		{
			if(!$error)
			{
				// revert if conflicts occur
				$this->section = isset($section[0]) ? strtolower($section) : DEFAULT_SECTION;
				$this->action  = isset($action[0]) ? strtolower($action) : DEFAULT_ACTION;
							
				// Determine the route we will take (section, action)
				$this->route();
			}
			
			// Initialize Smarty
			parent::__construct();
							
			// Change the default template directories
			$this->template_dir = array(TEMPLATE_DIR);
			$this->compile_dir = COMPILE_DIR;
			$this->config_dir = CONFIG_DIR;
			$this->cache_dir = CACHE_DIR;

			// Change default caching behavior
			$this->caching = $cache;
				
			$this->caching_lifetime = $caching_lifetime;
		}
				
		/**
		 * Determines the route to the template file, and decides whether there's an action
		 * @return Exception Returns with an exception if the default page AND requested page cannot be found
		 * @return Null If successful, nothing is returned
		 */
		public function route()
		{
			if($this->tplExists($this->section, $this->action))
			{
				$this->actionExists($this->section, $this->action);
			}
			else
			{
				if($this->tplExists($this->section, DEFAULT_ACTION))
				{
					$this->actionExists($this->section, DEFAULT_ACTION);
					$this->action = DEFAULT_ACTION;
				}
				else
				{
					if($this->tplExists(DEFAULT_SECTION, DEFAULT_ACTION))
					{
						$this->actionExists(DEFAULT_SECTION, DEFAULT_ACTION);
						$this->section = DEFAULT_SECTION;
						$this->action  = DEFAULT_ACTION;
					}
					else
					{
						throw new Exception_NGen('The requested page could not be found. Additionally, the default page could not be found.');
					}					
				}
			}			
		}
		
		/**
		 * Checks whether or not the template file exists
		 * @return bool On return true it also sets $tpl to the template file
		 */
		public function tplExists($section, $action)
		{
			if(file_exists(TEMPLATE_DIR . $section . '.' . $action . '.tpl'))
			{
				$this->tpl = $section . '.' . $action . '.tpl';
				return true;
			}
			return false;
		}
		
		/**
		 * Checks for a valid Action object. There does not need to be an Action; if it's not found then it's just not executed.
		 * @return Null Does not return anything. However, if the action exists then $actObj gets set to true
		 */
		public function actionExists($section, $action)
		{
			if(file_exists(SECTION_DIR . $section . '/' . $action . '.php'))
			{
				require_once(SECTION_DIR . $section . '/' . $action . '.php');
				$this->actObj = true;
			}
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
			if(file_exists(SECTION_DIR . '.default/' . DEFAULT_ACTION . '.php'))
			{
				require_once(SECTION_DIR . '.default/' . DEFAULT_ACTION . '.php');
				
				// Try to run DefaultAction::section_all() -- which affects all pages, in all sections
				if(method_exists('DefaultAction', 'section_all'))
				{
					DefaultAction::section_all();
				}
				
				// Try to run section-specific default action (section_*)
				if(method_exists('DefaultAction', 'section_'.$this->section))
				{
					call_user_func('DefaultAction::section_'.$this->section);
				}
			}
		}
														
		/**
		 * Performs the page action and displays the page [if not silenced]
		 * @public
		 */
		public function load()
		{
			// Attempts to run the default action (if enabled)
			if($this->use_default)
			{
				$this->defaultAction();
			}

			// Variables for use in the page
			$this->assign(array(
				'section' => $this->section,
				'action' => $this->action,
				'base_url' => NGenCore::$configs['document_root'],
				'site_title' => NGenCore::$configs['site_title'],
				'page_desc' => ucwords(($this->actObj && isset(Action::$description)) ? (substr(Action::$description, -6) === '|more|' ? rtrim(Action::$description, '|more|').'- '.$this->section.($this->action !== DEFAULT_ACTION ? ' - '.$this->action:''): Action::$description) : '- '.$this->section.($this->action !== DEFAULT_ACTION ? ' - '.$this->action:''))
			));

			if($this->actObj)
			{
				if(isset(Action::$caching))
				{
					$this->caching = Action::$caching;
				}
				
				if(isset(Action::$lifetime))
				{
					$this->caching_lifetime = Action::$lifetime;
				}
				
				if(isset(Action::$cache_uid))
				{
					$this->cache_id = Action::$cache_uid;
				}
				
				if($this->caching)
				{
					if(!$this->is_cached($this->tpl, $this->cache_id) && method_exists('Action', 'run'))
					{
						Action::run();
					}
				}
				else
				{
					if(method_exists('Action', 'run'))
					{
						Action::run();
					}				
				}
				
				if(!isset(Action::$silence) || Action::$silence !== true)
				{
					$this->display($this->tpl, null, $this->cache_id);
				}
			}
			else
			{
				$this->display($this->tpl, null, $this->cache_id);
			}
		}
		
		/**
		 * Used by the exception handler to raise visual exceptions
		 */
		public function load_error(exception $exception, $type = 'general')
		{
			$this->caching = 0;
			
			$this->assign(array(
				'type' => $type,
				'code' => $exception->getCode(),
				'line' => $exception->getLine(),
				'file' => $exception->getFile(),
				'message' => $exception->getMessage()
			));
			
			$this->display('error.tpl');
		}
		
		/**
		 * Used by the error handler to raise visual errors
		 */
		public function load_error2($errno, $errstr, $errfile, $errline, $type = 'general')
		{
			$this->caching = 0;
			
			$this->assign(array(
				'type' => $type,
				'code' => $errno,
				'line' => $errline,
				'file' => $errfile,
				'message' => $errstr
			));
			
			$this->display('error.tpl');
		}
	}
?>