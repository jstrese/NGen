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
		 * Stored reference
		 * @static
		 */
		static public $instance;
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
				
		public function __construct($section, $action, $cache = self::CACHE_DISABLED, $caching_lifetime = 86400, $error = false)
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
				
			// Instance
			self::$instance = &$this;
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
		 * @return Null Does not return anything. However, if the action exists then $actObj gets set to new instance of Action
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
		 * Returns the constructed page object
		 * @example Page_Smarty::getInstance()
		 * @return Page_Smarty
		 * @static
		 */
		static public function getInstance()
		{
			if(self::$instance === null)
			{
				new self(NGenCore::$configs['__section'], NGenCore::$configs['__action'], NGenCore::$configs['cache'], NGenCore::$configs['page_cache_lifetime'], false);
			}
			return self::$instance;
		}
		
		/**
		 * Returns a constructed Page object if one is not already constructed. This is used ONLY for Exception/Error handling!
		 * @example Page_Smarty::getInstance2()
		 * @return Page_Smarty
		 * @static
		 */
		static public function getInstance2()
		{
			if(self::$instance === null)
			{
				new self('', '', self::CACHE_DISABLED, 0, true);
			}
			return self::$instance;
		}
		
		/**
		 * Checks the integrity and length of a variable to ensure we are getting something
		 * Prior to 2.1, we checked with isset() -and- empty(), will revert if this change
		 * causes conflicts.
		 * @return boolean
		 * @deprecated
		 */
		private function isvalid($what)
		{
			return isset($what[0]);
		}
														
		/**
		 * Performs the page action and displays the page [if not silenced]
		 */
		public function load()
		{			
			// Before we run the desired page.. run the default script!
			require_once(SECTION_DIR . '.default/' . DEFAULT_ACTION . '.php');
			
			new DefaultAction();
			
			// Set the section & action (trakback)
			$this->assign(array(
				'section' => $this->section,
				'action' => $this->action
			));
			
			// Set the base url for links
			$this->assign('base_url', NGenCore::$configs['document_root']);

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
					$this->display($this->tpl);
				}
			}
			else
			{
				$this->display($this->tpl);
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