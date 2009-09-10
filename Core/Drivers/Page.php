<?php
	class Page
	{
		static protected $instance = null;
		static private $errorRaised = false;
		static public $vars = array();
		
		final static public function getInstance()
		{
			if(self::$instance === null)
			{
				switch(NGenCore::$configs['page_driver'])
				{
					default:
					case NGenCore::PAGE_NONE:
						return null;
						break;
					case NGenCore::PAGE_SMARTY:
						self::$instance = new Page_Smarty(
							NGenCore::$configs['__section'],
							NGenCore::$configs['__action'],
							NGenCore::$configs['cache'],
							NGenCore::$configs['page_cache_lifetime'],
							(bool)NGenCore::$configs['use_default_actions']
						);
						break;
					/*case NGenCore::PAGE_XPOP:
						self::$instance = Page_XPOP::getInstance();
						break;*/
				}
			}
			
			return self::$instance;
		}
		
		final static public function getInstance2()
		{
			if(!self::$errorRaised)
			{
				switch(NGenCore::$configs['page_driver'])
				{
					default:
					case NGenCore::PAGE_NONE:
						return null;
						break;
					case NGenCore::PAGE_SMARTY:
						self::$instance = new Page_Smarty('', '', 0, 0, false, true);
						break;
					/*case NGenCore::PAGE_XPOP:
						self::$instance = Page_XPOP::getInstance2();
						break;*/
				}
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