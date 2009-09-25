<?php
	class Page
	{
		/**
		 * Template file name
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $template_file = '';
		/**
		 * The path to the action file, decided by RequestHandler
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $action_file   = '';
		/**
		 * The directory of the current style/theme
		 * (IE: ./Styles/default/)
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $style_dir   = '';
		/**
		 * Tells us whether or not we have an action object
		 * to work with.
		 * @staticvar
		 * @public
		 */
		static public $use_action = false;
		
		/**
		 * Withholds the current Page object
		 * @staticvar
		 * @protected
		 */
		static protected $instance = null;
		/**
		 * Contains variables that will be sent to the template
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $vars = array();
				
		/**
		 * Retrieves the current Page object. If it isn't created, it is created.
		 * @example Page::getInstance()->assign('foo', 'bar')
		 * @public
		 * @static
		 * @final
		 */
		final static public function getInstance()
		{
			if(self::$instance === null)
			{
				$configs = NGenCore::$configs;
				
				//
				// Populate our settings
				//
				if(self::$template_file === '')
				{
					self::preload();
				}
				
				switch($configs['page_driver'])
				{
					default:
					case NGenCore::PAGE_NONE:
						return null;
						break;
					case NGenCore::PAGE_SMARTY:
						self::$instance = new Page_Smarty(
							$configs['cache'],
							$configs['page_cache_lifetime']
						);
						break;
				}
			}
			
			return self::$instance;
		}
		
		/**
		 * Retrieves a Page object for the Exception handler.
		 * @public
		 * @static
		 * @final
		 */
		final static public function getInstance2()
		{
  			//
			// Populate our settings if we need to
			//
			if(self::$template_file === '')
			{
				self::preload();
			}
			
			switch(NGenCore::$configs['page_driver'])
			{
				default:
				case NGenCore::PAGE_NONE:
					return null;
					break;
				case NGenCore::PAGE_SMARTY:
					self::$instance = new Page_Smarty(0, 0, true);
					break;
			}
			
			return self::$instance;
		}
		
	 	/**
		 * If default actions are enabled (see: $use_default), we attempt to load the default
		 * action. Once loaded, we check for two things: a function that executes on every
		 * page for every section (DefaultAction::section_all()), and a function for
		 * section-specific usage (DefaultAction::section_<section>()). Both of these
		 * functions are optional, and are only called if they exist.
		 * @since 2.1
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
		static public function load_DefaultActions()
		{
			if(file_exists(RequestHandler::SECTION_DIR . RequestHandler::DEFAULT_ACTION . '.php'))
			{
				require_once(RequestHandler::SECTION_DIR . RequestHandler::DEFAULT_ACTION . '.php');
				
				// Make sure the file wasn't left blank
				if(!class_exists('DefaultAction', false))
				{
					return;
				}
				
				// Try to run DefaultAction::section_all() -- which affects all pages, in all sections
				if(method_exists('DefaultAction', 'section_all'))
				{
					try
					{
						DefaultAction::section_all();
					}catch(Exception $ex){ self::getInstance2()->display_error($ex); die(); }
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
					}catch(Exception $ex){ self::getInstance2()->display_error($ex); die(); }
				}
			}
		}
		
		static private function preload()
		{
			self::$action_file   = RequestHandler::GetActionPath();
			self::$template_file = RequestHandler::GetTemplateName();
			self::$style_dir     = './Styles/'.NGenCore::$configs['theme'].'/';
		}
		
		/**
		 * Includes the action file that RequestHandler came up with, then
		 * checks to see whether the Action class is available. This allows
		 * users to create blank action files for static pages with no need
		 * for action files.
		 * @since 2.1
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
		static public function load_Action()
		{
			// This file already exists, it was checked by the RequestHandler
			require_once(self::$action_file);
			
			// The reason we check is because, for static files, it's logical
			// to just have a blank action file. If it's blank, then there
			// won't be an Action class, else there will be.
			self::$use_action = class_exists('Action', false);
		}
		
		/**
		 * Redirects the user
		 * @example Page::redirect('home/index')
		 * @static
		 * @final
		 */
		final static public function redirect($location)
		{
			if($location[0] === '/')
			{
				$location[0] = '';
			}
			
			header('Location: ' . NGenCore::$configs['document_root'] . $location);
			exit;
		}
	}
?>