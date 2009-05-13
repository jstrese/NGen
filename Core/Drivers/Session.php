<?php
	class Session implements Interface_Session
	{	
		/**
		 * References this specific instance, once constructed
		 * @static
		 */
		static protected $instance = null;
		
		/**
		 * Starts the session and verifies the owner of the session.
		 */
	 	public function __construct()
	 	{
	 		// Start the session
	 		session_start();
	 					
			// Prevent false sessions
			if(!isset($this->__identity))
			{
				// hash the IP address
				$this->__identity = $_SERVER['REMOTE_ADDR'];
			}
			elseif($this->__identity !== $_SERVER['REMOTE_ADDR'])
			{
				// Prevent the user from doing anything [E_FATAL]
				trigger_error('NGen::Session -> Invalid session', E_USER_ERROR);
			}
			
			// Prevention for session fixation
			session_regenerate_id(true);
		}
	 	
	 	/**
	 	 * Returns the constructed instance
	 	 * @example $session = Driver_Session::getInstance()
	 	 * @example print Driver_Session::getInstance()->foo
	 	 * @static
	 	 * @return Session Constructed session object
	 	 */
	 	static public function getInstance()
	 	{	 		
			if(self::$instance === null)
			{
				self::$instance = new self();
			}
			
			return self::$instance;
		}
	 	
	 	/**
	 	 * Deletes information from a session (can be overloaded)
	 	 * @example $session->del('foo', 'bar', 'baz')
	 	 * @static
	 	 */
		static public function del()
		{
			foreach(func_get_args() as $key)
			{
				unset($_SESSION[$key]);
			}
		}
		
		/**
		 * Deletes all information from the session
		 * @example $session->flush()
		 * @static
		 */
		static public function flush()
		{
			foreach($_SESSION as $key => $value)
			{
				unset($_SESSION[$key]);
			}
		}
		
		/**
		 * Magic method __get( $var )
		 * @example $session->foo
		 * @static
		 * @return mixed
		 */
		public function __get($key)
		{
			if(self::__isset($key))
			{
				return $_SESSION[$key];
			}
		}
		
		/**
		 * Magic method __set( $var, $val ); sets session information
		 * @example $session->foo = 'baz'
		 * @static
		 */
		public function __set($key, $value)
		{
			$_SESSION[$key] = $value;
		}
		
		/**
		 * Magic method __isset( $var )
		 * @example isset( $session->foo )
		 * @static
		 * @return boolean
		 */
		public function __isset($key)
		{
			return isset($_SESSION[$key]);
		}
		
		/**
		 * Magic method __toString()
		 * @example print $session
		 * @static
		 * @return string
		 */
		public function __toString()
		{
			print_r($_SESSION);
			return '';
		}
		
		/**
		 * Magic method __clone()
		 * @example clone $session
		 * @return array
		 */								
		public function __clone()
		{
			return $_SESSION;
		}
	}
?>