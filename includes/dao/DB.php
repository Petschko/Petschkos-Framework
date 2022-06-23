<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 11.04.2016
 * Time: 19:56
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Create(s a) connection(s) to (a) Database(s)
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class DB
 */
class DB extends PDO {
	/**
	 * Used for SQL-Statements (Equals-Operator)
	 */
	public const OPERATOR_EQUALS = '=';

	/**
	 * Used for SQL-Statements (Greater than-Operator)
	 */
	public const OPERATOR_GREATER_THAN = '>';

	/**
	 * Used for SQL-Statements (Greater than or Equal-Operator)
	 */
	public const OPERATOR_GREATER_EQUAL_THAN = '>=';

	/**
	 * Used for SQL-Statements (Less than-Operator)
	 */
	public const OPERATOR_LESS_THAN = '<';

	/**
	 * Used for SQL-Statements (Less than or Equal-Operator)
	 */
	public const OPERATOR_LESS_EQUAL_THAN = '<=';

	/**
	 * Used for SQL-Statements (NOT-Operator)
	 */
	public const OPERATOR_NOT_EQUALS = ' NOT ';

	/**
	 * Used for SQL-Statements (IS NULL-Operator)
	 */
	public const OPERATOR_IS_NULL = ' IS NULL';

	/**
	 * Used for SQL-Statements (IS NOT NULL-Operator)
	 */
	public const OPERATOR_NOT_NULL = ' IS NOT NULL';

	/**
	 * Var that holds all connection Objects
	 *
	 * @var self[] - Connection Objects
	 */
	private static array $connection = [];

	/**
	 * Name of the DB-Connection
	 *
	 * @var string - Name of the Connection
	 */
	private string $name;

	/**
	 * DB constructor.
	 *
	 * @param string $name - Name of the Database Object
	 * @param string $dsn - DSN-Connection String
	 * @param string $username - Database User
	 * @param string $password - Database User Password
	 * @param array|null $options - PDO Options
	 */
	public function __construct(string $name, string $dsn, string $username, string $password, ?array $options = null) {
		try {
			parent::__construct($dsn, $username, $password, $options);
		} catch(PDOException $e) {
			SQLError::addError($e->getMessage());
			return;
		}

		$this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		self::setConnection($name, $this);
		$this->setName($name);
	}

	/**
	 * Destruct method
	 */
	public function __destruct() {
		self::close($this->name);
	}

	/**
	 * Closes a DB Connection and removes it from the connection array
	 *
	 * @param string $name - Name of the connection to close
	 */
	public static function close(string $name): void {
		self::setConnection($name, null);
		unset(self::$connection[$name]);
	}

	/**
	 * Returns a DB Object
	 *
	 * @param string|int $name - Name of the Database Object
	 * @return DB - Database Object
	 */
	public static function getConnection($name): DB {
		return self::$connection[$name];
	}

	/**
	 * Sets a global DB Object
	 *
	 * @param string|int $name - Name of the Database Object
	 * @param DB $connection - Database Object
	 */
	private static function setConnection($name, DB $connection): void {
		self::$connection[$name] = $connection;
	}

	/**
	 * Check if the connection exists
	 *
	 * @param string $name - Name of the Database Object
	 * @return bool - Exists Database Object
	 */
	public static function existsConnection(string $name): bool {
		if(! array_key_exists($name, self::$connection)) {
			return false;
		}

		if(self::getConnection($name) === null) {
			return false;
		}

		return true;
	}

	/**
	 * Auto-Detects the Data-Type for a Value
	 *
	 * @param mixed $value - Value to assign a type
	 * @return int - PDO-Type
	 */
	public static function getDataType($value): int {
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
	 * Some values can't escaped by PDO, so were using a regex to remove all chars, that are not in the white list
	 *
	 * @param string $string - Subject
	 * @param string $whiteListPattern - White-List-Characters (Regex format)
	 * @return string - Escaped string
	 */
	public static function secureSQLRegEx(string $string, string $whiteListPattern = '/[^0-9a-zA-Z_]/'): string {
		return preg_replace($whiteListPattern, '', $string);
	}

	/**
	 * Get the Name of the Connection
	 *
	 * @return string - Name of the Connection
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Set the Name of the Connection
	 *
	 * @param string $name - Name of the Connection
	 */
	private function setName(string $name): void {
		$this->name = $name;
	}

	/**
	 * Creates a new Table
	 *
	 * @param string $tableName - Name of the table
	 * @param string[] $columns - Column of the table, use for each column an extra key within the array
	 * @param bool $overwrite - Overwrite table if existing? true = yes | false = no
	 * @return bool - true on success else false
	 */
	public function createTable(string $tableName, array $columns = ['id INT(11) AUTO_INCREMENT PRIMARY KEY', 'col2 VARCHAR(20)'], bool $overwrite = false): bool {
		// Secure Columns
		$tmp = [];
		foreach($columns as $column) {
			$tmp[] = self::secureSQLRegEx($column, '/[^0-9a-zA-Z _()]/');
		}

		// Drop table on overwrite
		if($overwrite && $this->tableExists($tableName)) {
			$this->dropTable($tableName);
		}

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
	public function tableExists(string $tableName): bool {
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
	public function dropTable(string $tableName): bool {
		try {
			$this->exec('DROP TABLE ' . self::secureSQLRegEx($tableName) . ';');
		} catch(PDOException $ex) {
			return false;
		}

		return true;
	}
}
