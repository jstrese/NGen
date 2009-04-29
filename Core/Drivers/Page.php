<?php

	class Page
	{
		static public $instance = null;
		
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
						self::$instance = Page_Smarty::getInstance();
						break;
					case NGenCore::PAGE_XPOP:
						self::$instance = Page_XPOP::getInstance();
						break;
				}
			}
			
			return self::$instance;
		}
		
		final static public function getInstance2()
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
						self::$instance = Page_Smarty::getInstance2();
						break;
					case NGenCore::PAGE_XPOP:
						self::$instance = Page_XPOP::getInstance2();
						break;
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