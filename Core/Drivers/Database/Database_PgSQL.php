<?php
	class Database_PgSQL extends Database implements Interface_DBDriver
	{
		private $poolid = 0;

		public function __construct($pooled = false, $poolid = 0)
		{
				$config = NGen::$configs['db'][$poolid];

				parent::__construct('pgsql:host='.$config['host'].';dbname='.$config['base'], $config['user'], $config['pass'], array(PDO::ATTR_PERSISTENT => true, PDO::ERRMODE_EXCEPTION => true));

				if($pooled === true)
				{
					$this->poolid = $poolid;
					parent::$pool[$config['host'].':'.$config['base']] = &$this;
				}
		}

		final public function __destruct()
		{
			$configs = NGen::$configs['db'][$this->poolid];

			if(isset(parent::$pool[$configs['host'].':'.$configs['base']]))
			{
				unset(parent::$pool[$configs['host'].':'.$configs['base']]);
			}
		}
	}