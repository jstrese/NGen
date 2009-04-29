<?php
	class Action implements Interface_StandardAction
	{
		//public $caching = false;
		//public $lifetime = 10;
		
		public function run()
		{
			/* Database example
			
			// $db = Database::getPoolItem(0); -- this is used for pooling, if you have only one connection, do not use this (pooling is meant to deal with multiple databases)
			$db = Database::getInstance(); // No pooling, use for single database setups
			
			$prep = $db->prepare('SELECT * FROM clients');
			
			// output the data
			if($prep->execute())
			{
				print_r($prep->fetchAll());
			}
			
			// destroy instance/disconnect from database/close connection .. whatever you want to call it.
			Database::$instance = null;
			*/
		}
	}
?>