<?php
	class Renderer
	{
		/**
		 * Template file name
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $template_file = '';
		/**
		 * The path to the control file, decided by RequestHandler
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $control_file   = '';
		/**
		 * The directory of the current style/theme
		 * (IE: ./Styles/default/)
		 * @since 2.1
		 * @staticvar
		 * @public
		 */
		static public $style_dir   = '';
		/**
		 * Tells us whether or not we have a control object
		 * to work with.
		 * @staticvar
		 * @public
		 */
		static public $use_control = false;

		/**
		 * Withholds the current Renderer object
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
				$configs = NGen::$configs;

				//
				// Populate our settings
				//
				if(self::$template_file === '')
				{
					self::preload();
				}

				switch($configs['renderer_driver'])
				{
					default:
					case NGen::RENDERER_NONE:
						return null;
						break;
					case NGen::RENDERER_SMARTY:
						self::$instance = new Renderer_Smarty(
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

			switch(NGen::$configs['renderer_driver'])
			{
				default:
				case NGen::RENDERER_NONE:
					return null;
					break;
				case NGen::RENDERER_SMARTY:
					self::$instance = new Renderer_Smarty(0, 0, true);
					break;
			}

			return self::$instance;
		}

	 	/**
		 * If onloads are enabled (see: $use_onload), we attempt to load the onload.
		 * Once loaded, we check for two things: a function that executes on every
		 * page for every section (OnLoad::all()), and a function for section-specific
		 * usage (OnLoad::<section>()). Both of these functions are optional, and are
		 * only called if they exist.
		 * @since 2.1
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
		static public function OnLoad()
		{
			if(file_exists(RequestHandler::$control_dir . RequestHandler::DEFAULT_CONTROL . '.php'))
			{
				require_once(RequestHandler::$control_dir . RequestHandler::DEFAULT_CONTROL . '.php');

				// Make sure the file wasn't left blank
				if(!class_exists('OnLoad', false))
				{
					return;
				}

				// Try to run DefaultAction::section_all() -- which affects all pages, in all sections
				if(method_exists('OnLoad', 'all'))
				{
					try
					{
						OnLoad::all();
					}catch(Exception $ex){ self::getInstance2()->display_error($ex); die(); }
				}

				// Try to run section-specific default action (section_*)
				$section = RequestHandler::$requestParts;
				// shove the action element off the array
				array_pop($section);
				$section = implode('_', $section);

				if(method_exists('OnLoad', $section))
				{
					try
					{
						call_user_func('OnLoad::'.$section);
					}catch(Exception $ex){ self::getInstance2()->display_error($ex); die(); }
				}
			}
		}

		static private function preload()
		{
			self::$control_file   = RequestHandler::GetControlPath();
			self::$template_file  = RequestHandler::GetTemplateName();
			self::$style_dir      = APP_PATH . 'Styles/'.NGen::$configs['theme'].'/';
		}

		/**
		 * Includes the control file that RequestHandler came up with, then
		 * checks to see whether the Control class is available. This allows
		 * users to create blank control files for static pages with no need
		 * for control files.
		 * @since 2.1
		 * @static
		 * @public
		 * @return Null Does not return anything.
		 */
		static public function load_Control()
		{
			// This file already exists, it was checked by the RequestHandler
			require_once(self::$control_file);

			// The reason we check is because, for static files, it's logical
			// to just have a blank control file. If it's blank, then there
			// won't be a Control class, else there will be.
			self::$use_control = class_exists('Control', false);
		}

		/**
		 * Formats traverse information for use in the title.
		 * (IE: My Site - Traverse > Traverse2 >> Traverse 3)
		 * @param $first_separator The first kind of separator to use
		 * @param $separator The separator to show after the first element
		 * @static
		 * @public
		 * @return string The formatted traverse text for a page title
		 */
		static public function getTitleTraverseText($first_separator = '&rsaquo;', $separator = '&#187;')
		{
			$request  = RequestHandler::$requestParts;
			$traverse = $request[0].' '.$first_separator.' ';
			unset($request[0]);
			return $traverse.implode(' '.$separator.' ', $request);
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

			header('Location: ' . NGen::$configs['document_root'] . $location);
			exit;
		}
	}
?>