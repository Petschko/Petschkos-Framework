<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 13.04.2016
 * Time: 11:03
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */


/**
 * Class BaseDAO
 */
abstract class BaseDAO {
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
	 * Contains the connection Object
	 *
	 * @var DB - Connection Object
	 */
	protected $connection;

	/**
	 * Contains the TableName of the current Table
	 *
	 * @var string - Table Name
	 */
	protected $tableName;

	/**
	 * Contains SQL-Cached Queries
	 *
	 * @var array
	 */
	protected $sqlCache = array();

	/**
	 * BaseDAO constructor.
	 *
	 * @param string $connectionName - Name of the Connection
	 * @param string $tableName - Name of the Table
	 * @throws Exception - Can't find connection to DB
	 */
	final protected function __construct($connectionName, $tableName) {
		if(! DB::existsConnection($connectionName)) {
			// Load the connection if it doesn't exists
			$this->loadDBConnections();

			// Trow exception if Link still doesn't exists
			if(! DB::existsConnection($connectionName)) {
				SQLError::addError("Can't establish link to the Connection: " . $connectionName . "! (It doesn't exists even after load)");
				throw new Exception();
			}
		}
		// Sets the connection
		$this->setConnection(DB::getConnection($connectionName));

		$this->setTableName($tableName);
	}

	/**
	 * A function that creates the connection to the Database - may call an other function to load them
	 */
	abstract protected function loadDBConnections();

	/**
	 * Points to the PDO-Connection-Object
	 *
	 * @return DB - DB-Object
	 */
	final protected function &getConnection() {
		return $this->connection;
	}

	/**
	 * Sets a Pointer to the PDO-Connection Object
	 *
	 * @param DB $connection - DB-Object
	 */
	final protected function setConnection(&$connection) {
		$this->connection =& $connection;
	}

	/**
	 * Returns the Name of the current Table
	 *
	 * @return string - Table Name
	 */
	final protected function getTableName() {
		return $this->tableName;
	}

	/**
	 * Sets the current Table Name
	 *
	 * @param string $tableName - Table Name
	 */
	final protected function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * @param $statement
	 * @return mixed
	 */
	final protected function &getSqlStatement($statement) {
		// Add to cache if not exists
		if(! isset($this->sqlCache[$statement]))
			$this->addSqlStatement($statement);

		return $this->sqlCache[$statement];
	}

	/**
	 * Add a new Statement to cache
	 *
	 * @param string $statement - Statement
	 */
	final protected function addSqlStatement($statement) {
		$this->sqlCache[$statement] = $this->getConnection()->prepare($statement);
	}

	/**
	 * Get one or more Data Rows
	 * You can choose by which term they get selected
	 *
	 * @param string $byField - By which field
	 * @param mixed $value - Value of the Field
	 * @param string $operator - Operator which is used to compare the value and the Field
	 * @return mixed todo
	 */
	abstract function getBy($byField, $value, $operator = self::OPERATOR_EQUALS);

	/**
	 * Get all Data rows
	 *
	 * @return mixed todo
	 */
	public function getAll() {
		$statement = 'SELECT * FROM ' . $this->getTableName() . ';';

		try {
			$sth = $this->getSqlStatement($statement);
		} catch(PDOException $e) {
			SQLError::addError($e->getMessage());
			return null;
		}


	}

	/**
	 * @param string $byField
	 * @param mixed $value
	 * @return mixed todo
	 */
	abstract function delete($byField, $value);

	/**
	 * @param Object $model
	 * @return mixed todo
	 */
	abstract function save($model);

	/**
	 * @param Object $model
	 * @return mixed todo
	 */
	abstract function update($model);
}
