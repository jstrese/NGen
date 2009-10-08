<?php
	class Controller
	{
		/**
		 * This sets a description for the page -- to be displayed after the
		 * site title (IE: N-Gen - Creativity at mind; where "N-Gen" is the
		 * title and "Creativity at mind" is the description).
		 * @staticvar
		 * @public
		 */
		static public $description    = null;

		/**
		 * $caching must be set to true for any caching to happen.
		 * @staticvar
		 * @public
		 */
		static public $caching        = null;

		/**
		 * $cache_lifetime is the time (in seconds) the cache is good for.
		 * @staticvar
		 * @public
		 */
		static public $cache_lifetime = null;

		/**
		 * $cache_uid gives the cache a unique id -- useful for caching the
		 * same page twice but with different/altered content.
		 * @staticvar
		 * @public
		 */
		static public $cache_uid      = null;

		/**
		 * $silence controls whether or not a page is displayed after
		 * the control is executed.
		 * @staticvar
		 * @public
		 */
		static public $silence        = null;

		/**
		 * $vars contains an array of template vars which are sent to
		 * the renderer and passed to the template file.
		 * @public
		 */
        public $vars = array();

  		/**
		 * Formats traverse information.
		 * (IE: Traverse > Traverse 2 >> Traverse 3)
		 * @param $first_separator The first kind of separator to use
		 * @param $separator The separator to show after the first element
		 * @static
		 * @public
		 * @return string The formatted traverse text
		 */
		static public function getTraverseText($first_separator = '&rsaquo;', $separator = '&#187;')
		{
			$request  = RequestHandler::$requestParts;
			$traverse = $request[0].' '.$first_separator.' ';
			unset($request[0]);
			return $traverse.implode(' '.$separator.' ', $request);
		}

		/**
		 * Outputs a valid link to a new control
		 * @param $location The location for the link to point to
		 * @static
		 * @public
		 * @return string The link
		 */
		static public function getURL($location)
		{
			if($location[0] === '/')
			{
				$location[0] = '';
			}

			$url  = isset($_SERVER['HTTPS']) || getenv('HTTPS') !== false ? 'https://' : 'http://';
			$url .= (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')).NGen::$configs['document_root'];
			$url .= $location;

			if(Session::$use_auth_key === true)
			{
				$url .= '/?authkey='.Session::getInstance()->__authed_key;
			}

			return $url;
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

			$redir  = 'Location: ' . (isset($_SERVER['HTTPS']) || getenv('HTTPS') !== false ? 'https://' : 'http://');
			$redir .= (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')).NGen::$configs['document_root'];
			$redir .= $location;

			if(Session::$use_auth_key === true)
			{
				$redir .= '/?authkey='.Session::getInstance()->__authed_key;
			}

			header($redir);
			exit;
		}
	}
?>