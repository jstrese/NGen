<?php
	class RequestHandler
	{
		/**
		 * Raw request (IE: "index.php/blog/view/1/Sample-Blog-Post")
		 * @since 2.1
		 * @staticvar
		 */
		static public $request      = '';
		/**
		 * Request (IE: array(0 => blog, 1 => view))
		 * @since 2.1
		 * @staticvar
		 */
		static public $requestParts = array();
		/**
		 * Variables from the request (IE: array(0 => 1, 1 => 'Sample-Blog-Post'))
		 * @since 2.1
		 * @staticvar
		 */
		static public $variables    = array();

		/**
		 * The section to default to
		 * @since 2.1
		 */
		const DEFAULT_SECTION = 'home';
		/**
		 * The control to default to
		 * @since 2.1
		 */
		const DEFAULT_CONTROL  = 'index';
		/**
		 * The directory in which to search for sections and controls
		 * @since 2.1
		 */
		const CONTROL_DIR     = './Controllers/';

		/**
		 * Reads the data from PATH_INFO. If nothing useful is
		 * found in PATH_INFO we result to ORIG_PATH_INFO, then
		 * finally we just default to our default values.
		 * -
		 * There's one slight hiccup: this will not work
		 * at all if both of these scenarios are true:
		 *   - variables_order INI setting doesn't include S (server)
		 *   - PHP is running under IIS
		 *
		 * Why? Since getenv() doesn't work on IIS and the
		 * super global array $_SERVER isn't available, we
		 * have no way of getting any info that we need.
		 * @since 2.1
		 * @static
		 * @public
		 * @return Null Returns nothing
		 */
		static public function HandleRequest()
		{
			//
			// Prevent unecessary calls to getenv(),
			// since we don't need to call getenv() if
			// the $_SERVER super global is available.
			//
			if(isset($_SERVER))
			{
				if(isset($_SERVER['PATH_INFO']))
				{
					if($_SERVER['PATH_INFO'][0] !== '/')
					{
						self::$request = strstr($_SERVER['PATH_INFO'], '/');
						self::SortRequest();
						return;
					}

					self::$request = $_SERVER['PATH_INFO'];
					self::SortRequest();
					return;
				}

				if(isset($_SERVER['ORIG_PATH_INFO']))
				{
					if($_SERVER['ORIG_PATH_INFO'][0] !== '/')
					{
						self::$request = strstr($_SERVER['ORIG_PATH_INFO'], '/');
						self::SortRequest();
						return;
					}

					self::$request = $_SERVER['ORIG_PATH_INFO'];
					self::SortRequest();
					return;
				}

				self::SortRequest();
				return;
			}

			//
			// Attempt to use getenv() to find PATH_INFO
			// This will fail on IIS, though I'm sure it
			// will just return 'false' instead of raising
			// and error.
			//
			if(($request = getenv('PATH_INFO')) !== false || ($request = getenv('ORIG_PATH_INFO')) !== false)
			{
				if($request[0] !== '/')
				{
					self::$request = strstr($request, '/');
					self::SortRequest();
					return;
				}

				self::$request = $request;
				self::SortRequest();
				return;
			}

			self::SortRequest();
			return;
		}

		/**
		 * Sorts out the control from the section, and isolates variables.
		 * This information is then stored.
		 * @since 2.1
		 * @static
		 * @private
		 * @return Null Returns nothing
		 */
		static private function SortRequest()
		{
			if(self::$request !== '')
			{
				$parts = explode('/', trim(strtolower(self::$request), '/'));
				//
				// If this section doesn't exist then we will treat the request
				// as if they are supplying variables to the default section & control
				//
				if(!is_dir(self::CONTROL_DIR . $parts[0]))
				{
					self::$request = self::DEFAULT_SECTION.'/'.self::DEFAULT_CONTROL.'/'.implode('/', $parts);
					self::$requestParts = array(self::DEFAULT_SECTION, self::DEFAULT_CONTROL);
					self::$variables = $parts;
					return;
				}

				$path = $parts[0];
				self::$requestParts[] = $parts[0];
				unset($parts[0]);

				//
				// A foreach loop cannot iterate through an empty array,
				// now can it? This is used when only 1 part was sent.
				// (IE: example.com/blog => example.com/blog/index)
				//
				if(sizeof($parts) === 0)
				{
					if(is_file(self::CONTROL_DIR.$path.'/'.self::DEFAULT_CONTROL.'.php'))
					{
						self::$request .= '/'.self::DEFAULT_CONTROL;
						self::$requestParts[] = self::DEFAULT_CONTROL;
						return;
					}
					else
					{
						throw new Exception('Could not find combination of sections and control.');
						return;
					}
				}

				foreach($parts as $key => $part)
				{
					$path .= '/';

					if(is_dir(self::CONTROL_DIR.$path.$part))
					{
						//
						// If a directory exists with the last item in the array,
						// it's apparent that we must autoload the index file.
						//
						if($key === sizeof($parts))
						{
							if(is_file(self::CONTROL_DIR.$path.$part.'/'.self::DEFAULT_CONTROL.'.php'))
							{
								self::$requestParts[] = $part;
								self::$requestParts[] = self::DEFAULT_CONTROL;
								unset($parts[$key]);
								self::$variables = array_merge($parts);

								return;
							} // No suitable action found, throw error
							else
							{
								throw new Exception('Could not find combination of sections and action.');
							}
						}

						$path .= $part;
						self::$requestParts[] = $part;
						unset($parts[$key]);

						continue;
					}

					// Checking for action, since no dir is available
					if(is_file(self::CONTROL_DIR.$path.$part.'.php'))
					{
						self::$requestParts[] = $part;
						unset($parts[$key]);
						self::$variables = array_merge($parts);

						return;
					} // Checking for default control
					elseif(is_file(self::CONTROL_DIR.$path.self::DEFAULT_CONTROL.'.php'))
					{
						self::$requestParts[] = self::DEFAULT_CONTROL;
						self::$variables = array_merge($parts);

						return;
					} // No suitable control found, throw error
					else
					{
						throw new Exception('Could not find combination of sections and control.');
						return;
					}
				}

				return;
			}

			//
			// This is used when NOTHING is supplied
			// (IE: example.com => example.com/home/index)
			//
			self::$request = self::DEFAULT_SECTION.'/'.self::DEFAULT_CONTROL;
			self::$requestParts = array(self::DEFAULT_SECTION, self::DEFAULT_CONTROL);
		}

		/**
		 * Forms a template name from the gathered combination
		 * of sections and control.
		 * @since 2.1
		 * @param $ext The extension to append (default: .tpl)
		 * @param $separator The separator to use when putting the parts together (default: .)
		 * @static
		 * @public
		 * @return string A string representing the template file name (IE: home.index.tpl)
		 */
		static public function GetTemplateName($ext = '.tpl', $separator = '.')
		{
			return implode($separator, self::$requestParts).$ext;
		}

		/**
		 * Forms a path to the control file that we need
		 * @since 2.1
		 * @static
		 * @public
		 * @return string A string representing the path to the control file (IE: home/index.php)
		 */
		static public function GetControlPath()
		{
			return self::CONTROL_DIR.implode('/', self::$requestParts).'.php';
		}
	}
?>