<?php
	abstract class Database extends PDO
	{
		/**
		 * Witholds the current database connection object
		 * @staticvar
		 * @protected
		 */
		static protected $instance = null;

		/**
		 * Stores pooled database connections
		 * @staticvar
		 * @protected
		 */
		static protected $pool = array();

		/**
		 * Retrieves the current database connection object. If it isn't formed, it is created and connected.
		 * @example Database::getInstance()->query('SELECT * FROM table');
		 * @public
		 * @static
		 * @final
		 */
		final static public function getInstance($id = 0)
		{
			if(self::$instance === null)
			{
				switch(NGen::$configs['db'][$id]['driver'])
				{
					default:
					case NGen::SQL_NONE:
						return null;
						break;
					case NGen::SQL_MYSQL:
						self::$instance = new Database_MySQL(false, $id);
						break;
					case NGen::SQL_PGSQL:
						self::$instance = new Database_PgSQL(false, $id);
						break;
					/*case NGenCore::SQL_SQLITE:
						new Database_SQLite(false, $id);
						break;*/
				}
			}

			return self::$instance;
		}

		/**
		 * Retrieves a database connection object from the database connection pool (@var $pool). If it doesn't exist, it is created, connected, and added to the pool.
		 * @example Database::getPoolItem('localhost:database')->query('SELECT * FROM table');
		 * @final
		 * @static
		 * @public
		 */
		final static public function getPoolItem($poolid)
		{
			$conf = NGen::$configs['db'][$poolid];

			if(!isset(self::$pool[$conf['host'].':'.$conf['base']]))
			{
				switch($conf['driver'])
				{
					default:
					case NGen::SQL_NONE:
						return null;
						break;
					case NGen::SQL_MYSQL:
						new Database_MySQL(true, $poolid);
						break;
					case NGen::SQL_PGSQL:
						new Database_PgSQL(true, $poolid);
						break;
				}
			}

			return self::$pool[$conf['host'].':'.$conf['base']];
		}

		//TODO: Add toggle functionality
		final static public function poolToggle()
		{
		}
	}
?>