<?php
	class Exception_DatabaseDriver extends PDOException
	{ 
		public $type = 'database';
		public function __construct($message, $code = '00000')
		{
			parent::__construct($message, $code . 'kkk');
		}
	}
?>