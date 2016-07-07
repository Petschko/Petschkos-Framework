<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 11.04.2016
 * Time: 19:56
 * Update: 07.07.2016
 * Version: 1.0.3 (Added NULL-Operators)
 * 1.0.2 (Removed unnecessary finally statement und added missing doc)
 * 1.0.1 (Added name prop to destruct)
 * 1.0.0 (Added Name for Connection)
 * @package Petschkos Framework
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Create(s a) connection(s) to (a) Database(s)
 */

/**
 * Class DB
 */
class DB extends PDO {
	/**
	 * Used for SQL-Statements (Equals-Operator)
	 */
	const OPERATOR_EQUALS = '=';

	/**
	 * Used for SQL-Statements (Greater than-Operator)
	 */
	const OPERATOR_GREATER_THAN = '>';

	/**
	 * Used for SQL-Statements (Greater than or Equal-Operator)
	 */
	const OPERATOR_GREATER_EQUAL_THAN = '>=';

	/**
	 * Used for SQL-Statements (Less than-Operator)
	 */
	const OPERATOR_LESS_THAN = '<';

	/**
	 * Used for SQL-Statements (Less than or Equal-Operator)
	 */
	const OPERATOR_LESS_EQUAL_THAN = '<=';

	/**
	 * Used for SQL-Statements (NOT-Operator)
	 */
	const OPERATOR_NOT_EQUALS = 'NOT';

	/**
	 * Used for SQL-Statements (IS NULL-Operator)
	 */
	const OPERATOR_IS_NULL = 'IS NULL';

	/**
	 * Used for SQL-Statements (IS NOT NULL-Operator)
	 */
	const OPERATOR_NOT_NULL = 'IS NOT NULL';

	/**
	 * Var that holds all connection Objects
	 *
	 * @var array - Connection Objects
	 */
	private static $connection = array();

	/**
	 * Name of the DB-Connection
	 *
	 * @var string - Name of the Connection
	 */
	private $name;

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
		}

		parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
		parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		self::setConnection($name, $this);
		$this->setName($name);
	}

	/**
	 * Destruct method
	 */
	public function __destruct() {
		unset($this->name);
	}

	/**
	 * Closes a DB Connection
	 *
	 * @param string $name - Name of the connection to close
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
	public static function getConnection($name) {
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

	/**
	 * Auto-Detects the Data-Type for a Value
	 *
	 * @param mixed $value - Value to assign a type
	 * @return int - PDO-Type
	 */
	public static function getDataType($value) {
		switch(true) {
			case is_null($value):
				return parent::PARAM_NULL;
			case is_int($value):
				return parent::PARAM_INT;
			case is_bool($value):
				return parent::PARAM_BOOL;
			default:
				return parent::PARAM_STR;
		}
	}

	/**
	 * Some values can't escaped by PDO, so we using regex to remove all chars, that are not in the white list
	 *
	 * @param string $string - Subject
	 * @param string $whiteListPattern - White-List-Characters (Regex format)
	 * @return string - Escaped string
	 */
	public static function secureSQLRegEx($string, $whiteListPattern = '/[^0-9a-zA-Z_]/') {
		return preg_replace($whiteListPattern, '', $string);
	}

	/**
	 * Get the Name of the Connection
	 *
	 * @return string - Name of the Connection
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the Name of the Connection
	 *
	 * @param string $name - Name of the Connection
	 */
	private function setName($name) {
		$this->name = $name;
	}

	/**
	 * Creates a new Table
	 *
	 * @param string $tableName - Name of the table
	 * @param array $columns - Column of the table, use for each column an extra key within the array
	 * @param bool $overwrite - Overwrite table if existing? true = yes | false = no
	 * @return bool - true on success else false
	 */
	public function createTable($tableName, $columns = array('id INT(11) AUTO_INCREMENT PRIMARY KEY', 'col2 VARCHAR(20)'), $overwrite = false) {
		// Secure Columns
		$tmp = array();
		foreach($columns as $column)
			$tmp[] = self::secureSQLRegEx($column, '/[^0-9a-zA-Z _\(\)]/');

		// Drop table on overwrite
		if($overwrite && $this->tableExists($tableName))
			$this->dropTable($tableName);

		// create SQL
		$sql = 'CREATE TABLE ' . self::secureSQLRegEx($tableName) . '(' . PHP_EOL;
		$sql .= implode(',', $tmp); // Normal not used for User-Input... Fallback only!
		$sql .= PHP_EOL . ');';

		try {
			$this->exec($sql);

			return true;
		} catch(PDOException $ex) {
			SQLError::addError('Can\'t create Table... See more at the System-Log.');

			return false;
		}
	}

	/**
	 * Check if a table exists
	 *
	 * @param string $tableName - Name of the Table
	 * @return bool - true if table exists else false
	 */
	public function tableExists($tableName) {
		try {
			$sth = $this->query('SELECT 1 FROM ' . self::secureSQLRegEx($tableName) . ' LIMIT 1;');
			$sth->closeCursor();
		} catch(PDOException $ex) {
			return false;
		}

		return true;
	}

	/**
	 * Deletes a Table
	 *
	 * @param string $tableName - Name of the table, that you want delete
	 * @return bool - true on success else false
	 */
	public function dropTable($tableName) {
		try {
			$this->exec('DROP TABLE ' . self::secureSQLRegEx($tableName) . ';');
		} catch(PDOException $ex) {
			return false;
		}

		return true;
	}
}
