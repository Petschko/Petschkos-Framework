<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 13.04.2016
 * Time: 08:32
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

/**
 * Class BaseModel
 */
abstract class BaseModel {
	/**
	 * Contains SQL-Cached Queries
	 *
	 * @var array
	 */
	protected $sqlCache = array();

	/**
	 * Contains the DB Object of the Model
	 *
	 * @var DB - DB (PDO) Object
	 */
	protected $db;

	/**
	 * Contains the FieldName of the Primary Key
	 *
	 * @var string - Primary Key FieldName
	 */
	protected $primaryKeyField;

	/**
	 * BaseModel constructor.
	 *
	 * @param string $dbConName - Database PDO-Object Name
	 * @throws Exception - Can't find connection to DB
	 */
	final protected function __construct($dbConName) {
		if(! DB::existsConnection($dbConName)) {
			SQLError::addError('Can\'t establish link to the Connection: ' . $dbConName . '! (It doesn\'t exists)');
			throw new Exception();
		}

		// Sets the connection
		$this->setDb(DB::getConnection($dbConName));
	}

	/**
	 * Gets the Database PDO-Object
	 *
	 * @return DB - Database PDO-Object
	 */
	final protected function &getDb() {
		return $this->db;
	}

	/**
	 * Sets the Database PDO-Object
	 *
	 * @param DB $db - Database PDO-Object
	 */
	final protected function setDb(&$db) {
		$this->db =& $db;
	}

	/**
	 * @param string $statement - SQL-Statement
	 * @return PDOStatement - PDOStatement object
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
		$this->sqlCache[$statement] = $this->getDb()->prepare($statement);
	}

	/**
	 * Get one or more Data Rows
	 * You can choose by which term they get selected
	 * The result will stored into the model
	 *
	 * @param mixed $value - Value of the Field
	 * @param string|null $byField - Target Field | null uses Primary-Key
	 * @param string $operator - Operator which is used to compare the value and the Field
	 */
	abstract public function getBy($value, $byField = null, $operator = DB::OPERATOR_EQUALS);

	/**
	 * Deletes one or more Data Rows
	 * You can choose by which term they get selected
	 *
	 * @param mixed $value - Value of the Field
	 * @param string|null $byField - Target Field | null uses Primary-Key
	 * @param string $operator - Compare Operator
	 */
	abstract function deleteBy($value, $byField = null, $operator = DB::OPERATOR_EQUALS);

	/**
	 * Update the current row at the Database with the Model-Data
	 *
	 * @param mixed $value - Value of the Field
	 * @param string|null $byField - Target Field | null uses Primary-Key
	 * @param string $operator - Compare Operator
	 */
	abstract function updateBy($value, $byField = null, $operator = DB::OPERATOR_EQUALS);

	/**
	 * Get all Data rows
	 */
	abstract function getAll();

	/**
	 * Get one or more Data-Rows
	 *
	 * @param int $limit - Max Rows
	 * @param int|null $start - Start Row | null use the default start value
	 */
	abstract function get($limit = 1, $start = null);

	/**
	 * Saves the current values of the Model to the Database
	 */
	abstract function save();

	/**
	 * Deletes the current Data-Row
	 */
	abstract function delete();

	/**
	 * Update the current Data-Row
	 */
	abstract function update();
}
