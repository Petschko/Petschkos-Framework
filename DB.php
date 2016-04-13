<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 11.04.2016
 * Time: 14:56
 * Update: -
 * Version: 0.0.1
 *
 * Notes: Create(s a) connection(s) to (a) Database(s)
 */

/**
 * Class DB
 */
class DB extends PDO {
	/**
	 * Var that holds all connection Objects
	 *
	 * @var array - Connection Objects
	 */
	private static $connection = array();

	/**
	 * DB constructor.
	 *
	 * @param string $name - Name of the Database Object
	 * @param string $dsn - DSN-Connection String
	 * @param string $username - Database User
	 * @param string $password - Database User Password
	 * @param array|null $options - PDO Options
	 */
	public function __construct($name, $dsn, $username, $password, $options = null) {
		try {
			parent::__construct($dsn, $username, $password, $options);
		} catch(PDOException $e) {
			SQLError::addError($e->getMessage());
			return;
		} finally {
			parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			self::setConnection($name, $this);
		}
	}

	/**
	 * Destruct method
	 */
	public function __destruct() {
		// VOID
	}

	/**
	 * Closes a DB Connection
	 *
	 * @param $name - Name of the connection to close
	 */
	public static function close($name) {
		self::setConnection($name, null);
	}

	/**
	 * Returns a DB Object
	 *
	 * @param string|int $name - Name of the Database Object
	 * @return DB - Database Object
	 */
	public static function &getConnection($name) {
		return self::$connection[$name];
	}

	/**
	 * Sets a global DB Object
	 *
	 * @param string|int $name - Name of the Database Object
	 * @param DB $connection - Database Object
	 */
	private static function setConnection($name, $connection) {
		self::$connection[$name] = $connection;
	}

	/**
	 * Check if the connection exists
	 *
	 * @param string $name - Name of the Database Object
	 * @return bool - Exists Database Object
	 */
	public static function existsConnection($name) {
		if(! array_key_exists($name, self::$connection))
			return false;

		if(self::getConnection($name) === null)
			return false;

		return true;
	}
}
