<?php
	class Exception_NGen extends Exception
	{ 
		public function __construct($message, $line = 0, $file = null, $code = 0)
		{
			parent::__construct($message, $code);
			$this->line = $line;
			$this->file = $file;
		}
	}
?>