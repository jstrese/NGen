<?php
	class Page
	{
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
				
				switch($configs['page_driver'])
				{
					default:
					case NGenCore::PAGE_NONE:
						return null;
						break;
					case NGenCore::PAGE_SMARTY:
						self::$instance = new Page_Smarty(
							$configs['cache'],
							$configs['page_cache_lifetime'],
							(bool)$configs['use_default_actions']
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
			switch(NGenCore::$configs['page_driver'])
			{
				default:
				case NGenCore::PAGE_NONE:
					return null;
					break;
				case NGenCore::PAGE_SMARTY:
					self::$instance = new Page_Smarty(0, 0, false, true);
					break;
			}
			
			return self::$instance;
		}
		
		/**
		 * Redirects the user
		 * @example Page::redirect('home/main')
		 * @static
		 * @final
		 */
		final static public function redirect($location)
		{
			if($location[0] === '/')
			{
				$location = substr($location, 1);
			}
			
			header('Location: ' . NGenCore::$configs['document_root'] . $location);
			exit;
		}
	}
?>