<?php
	class Session_Generic extends ArrayObject
	{
		public function __construct()
		{
			parent::__construct($_SESSION, ArrayObject::ARRAY_AS_PROPS);

			// This should prevent session fixation attacks
		 	session_regenerate_id(true);
		}

		private function genAuthKey()
		{
			$this->__authed_key_sum       = md5($this->__authed_browser.$this->__authed_ip);
			$this->__authed_key_expire    = time()+180;
			$this->__authed_key_pad       = (bool)mt_rand(0, 1);
			///
			// 0/false: expire time padded to the left
			// 1/true: expire time padded to the right
			///
			$this->__authed_key = $this->__authed_key_pad ? $this->__authed_key_sum.$this->__authed_key_expire : $this->__authed_key_expire.$this->__authed_key_sum;
		}

		public function authenticate()
		{
			$user_browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');
			$user_ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : getenv('REMOTE_ADDR');

			//
			// Check the authenticated IP address
			//
			if(!isset($this->__authed_ip))
			{
				$this->__authed_ip = $user_ip;
			}
			elseif($this->__authed_ip !== $user_ip)
			{
				return false;
			}

			//
			// Check the authenticated browser
			// -- it's highly unlikely that requests will be made
			//    from different browsers.
			//
			if(!isset($this->__authed_browser))
			{
				$this->__authed_browser = $user_browser;
			}
			elseif($this->__authed_browser !== $user_browser)
			{
				return false;
			}

			if(Session::$use_auth_key === true)
			{
				if(!isset($this->__authed_key))
				{
					$this->genAuthKey();
					$skip_check = true;
				}
				else
				{
					// The 'authkey' MUST be specified in the URL
					if(!isset($_GET['authkey']))
					{
						return false;
					}

					if($this->__authed_key_pad === true)
					{
						$auth_key    = substr($this->__authed_key, 0, 32);
						$auth_expire = (int)substr($this->__authed_key, 32);
					}
					else
					{
						$auth_key    = substr($this->__authed_key, -32);
						$auth_expire = (int)substr($this->__authed_key, 0, -32);
					}

					if($auth_key !== $this->__authed_key || (int)$auth_expire !== (int)$this->__authed_expire)
					{
						return false;
					}
						// Do we need to renew the key?
					if($auth_expire <= time())
					{
						$this->genAuthKey();
					}
				}
			}

			// For all we know, this user belongs to this session.
			return true;
		}

		public function del()
		{
			foreach(func_get_args() as $key)
			{
				unset($this->$key);
			}
		}

		public function flush()
		{
			$this->exchangeArray(array());
		}

		public function convertToArray()
		{
			return (array)$this;
		}
	}
?>