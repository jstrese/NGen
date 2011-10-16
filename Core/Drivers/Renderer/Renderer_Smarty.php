<?php
	// Smarty
	require_once(APP_PATH.'3rd Party/Smarty/libs/Smarty.class.php');
	define('SMARTY_EXCEPTION_HANDLER', 0);

    class Renderer_Smarty extends Smarty implements Interface_Renderer
    {
		private $control = null;

		public function __construct($cache = false, $cache_lifetime = 86400, $error = false)
		{
			// Initialize Smarty
			parent::__construct();
            
			// Change the default template directories
            $this->setTemplateDir(Renderer::$style_dir)
                 ->setCompileDir(Renderer::$style_dir.'compile')
                 ->setConfigDir(Renderer::$style_dir.'config')
                 ->setCacheDir(Renderer::$style_dir.'cache');

            // No setters/getters yet in Smarty 3.1.4 for the following
			$this->auto_literal = true;
            $this->compile_check = Smarty::COMPILECHECK_CACHEMISS;
			$this->caching        = $cache ? Smarty::CACHING_LIFETIME_SAVED : Smarty::CACHING_OFF;
			$this->cache_lifetime = $cache_lifetime;
		}

		/**
		 * Executes control and displays the page [if not silenced]
		 * @public
		 * @since 2.1
		 * @return Null Does not return anything.
		 */
		public function render()
		{
			$configs = NGen::$configs;

			// Variables for use in the page
			// These are merged with the Control variables (control->vars)
			$vars = array(
				'root_path'  => $configs['document_root'],
				'style_path' => $configs['document_root'].'Styles/'.$configs['theme'].'/',
				'site_title' => $configs['site_title']
			);

			// Attempts to run the onload function (if enabled)
			if($configs['use_onload'])
			{
				Renderer::OnLoad();
			}

			if(Renderer::$use_control)
			{
				if(isset(Control::$caching))
				{
					$this->caching = Control::$caching === true ? Smarty::CACHING_LIFETIME_SAVED : Smarty::CACHING_OFF;
				}

				if(isset(Control::$cache_lifetime))
				{
					$this->cache_lifetime = Control::$cache_lifetime;
				}

				if(isset(Control::$cache_uid))
				{
					$this->cache_id = Control::$cache_uid;
				}

				if($this->caching)
				{
					if(!$this->is_cached(Renderer::$template_file, $this->cache_id))
					{
						try
						{
							$this->control = new Control();
							if(method_exists($this->control, 'execute'))
							{
								$this->control->execute();
							}
						}catch(Exception $ex){ $this->display_error($ex); die(); }
					}
				}
				else
				{
					try
					{
						$this->control = new Control();
						if(method_exists($this->control, 'execute'))
						{
							$this->control->execute();
						}
					}catch(Exception $ex){ $this->display_error($ex); die(); }
				}
			}

			$this->assign('view', $this->control)
			     ->assign('vars', new ArrayObject($this->control === null ? $vars : array_merge($this->control->vars, $vars), ArrayObject::ARRAY_AS_PROPS))
			     ->display(Renderer::$template_file, $this->cache_id);
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
				'errMsg' => $exception->getMessage(),
				'root_path'  => NGen::$configs['document_root'],
				'style_path' => NGen::$configs['document_root'].'Styles/'.NGen::$configs['theme'].'/'
			));

			$this->display('debug.tpl');
		}
	}