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
 * Interface tableInterface
 */
interface tableInterface {
	/**
	 * Sets the Table-Info
	 */
	public static function setTableInfo();
}

/**
 * Class BaseDBTableModel
 */
abstract class BaseDBTableModel implements tableInterface {
	/**
	 * Contains the DB Object of the Model
	 *
	 * @var DB - DB (PDO) Object
	 */
	protected static $db;

	/**
	 * Contains the Name of the Table
	 *
	 * @var string - Table Name
	 */
	protected static $tableName;

	/**
	 * Contains the names of all Table-fields at the Table
	 *
	 * @var array - Table Fields
	 */
	protected static $tableFields;

	/**
	 * Contains the FieldName of the Primary Key
	 *
	 * @var string|null - Primary Key FieldName | null when no primary key exists
	 */
	protected static $primaryKeyField = null;

	/**
	 * Internal variable to check if the Table-Vars are already set
	 *
	 * @var bool - Is the Table setup done
	 */
	protected static $tableSetupDone = false;

	/**
	 * Contains SQL-Cached Queries
	 *
	 * @var array - SQL-Statements
	 */
	protected static $sqlCache = array();

	/**
	 * Contains Query Objects
	 *
	 * @var null|array - Memory Objects | null if there are no more rows or not set
	 */
	protected $memoryObjects = null;

	/**
	 * Contains the next index of the Memory Objects Array
	 *
	 * @var int - Index number
	 */
	protected $memoryIndex = 0;


	/**
	 * BaseModel constructor.
	 *
	 * @param string $dbConName - Database PDO-Object Name
	 * @throws Exception - Can't find connection to DB
	 * @throws Exception - Table Info not set correct
	 */
	final protected function __construct($dbConName) {
		// Check if Table-Data is already set
		if(! self::isTableSetupDone()) {
			// Set all info (Table-Name, fields etc)
			self::setTableInfo();

			// Check if the connection exists
			if(! DB::existsConnection($dbConName)) {
				$ex = 'Can\'t establish link to the Connection: ' . $dbConName . '! (It doesn\'t exists)';
				SQLError::addError($ex);
				throw new Exception($ex);
			}

			// Sets the connection
			self::setDb(DB::getConnection($dbConName));

			// Check if all is correct
			self::checkIssetTableInfo();

			// All done set it
			self::setTableSetupDone(true);
		}
	}

	/**
	 * Clears memory
	 */
	final protected function __destruct() {
		unset($this->memoryObjects);
	}

	/**
	 * Gets the Database PDO-Object
	 *
	 * @return DB - Database PDO-Object
	 */
	final protected static function &getDb() {
		return self::$db;
	}

	/**
	 * Sets the Database PDO-Object
	 *
	 * @param DB $db - Database PDO-Object
	 */
	final protected static function setDb(&$db) {
		self::$db =& $db;
	}

	/**
	 * Returns a Pointer to a Statement
	 *
	 * @param string $statement - SQL-Statement
	 * @return PDOStatement - PDOStatement object
	 */
	final protected static function &getSqlStatement($statement) {
		// Add to cache if not exists
		if(! isset(self::$sqlCache[$statement]))
			self::addSqlStatement($statement);

		return self::$sqlCache[$statement];
	}

	/**
	 * Add a new Statement to cache
	 *
	 * @param string $statement - Statement
	 */
	final protected static function addSqlStatement($statement) {
		self::$sqlCache[$statement] = self::getDb()->prepare($statement);
	}

	/**
	 * Get the Name of the Table
	 *
	 * @return string - Table Name
	 */
	final protected static function getTableName() {
		return self::$tableName;
	}

	/**
	 * Sets the Name of the Table
	 *
	 * @param string $tableName - Name of the Table
	 */
	final protected static function setTableName($tableName) {
		self::$tableName = $tableName;
	}

	/**
	 * Get the Table Fields
	 *
	 * @return array - Table Fields
	 */
	final protected static function getTableFields() {
		return self::$tableFields;
	}

	/**
	 * Sets the Table-Field Names
	 *
	 * @param array $tableFields - Field Names of the Table
	 */
	final protected static function setTableFields($tableFields) {
		self::$tableFields = $tableFields;
	}

	/**
	 * Get the Field Name of the Primary Key
	 *
	 * @return null|string - Field name of the Primary Key | null when no Key is set
	 */
	final protected static function getPrimaryKeyField() {
		return self::$primaryKeyField;
	}

	/**
	 * Sets the Field Name of the Primary-Key
	 *
	 * @param null|string $primaryKeyField - Field-Name of the Primary Key | null if none is set
	 */
	final protected static function setPrimaryKeyField($primaryKeyField) {
		self::$primaryKeyField = $primaryKeyField;
	}

	/**
	 * Check if the Table-Setup is done
	 *
	 * @return boolean - is the Table-Setup done
	 */
	final protected static function isTableSetupDone() {
		return self::$tableSetupDone;
	}

	/**
	 * Sets if the Table-Setup is done
	 *
	 * @param boolean $tableSetupDone - Is Table-Setup done
	 */
	final protected static function setTableSetupDone($tableSetupDone) {
		self::$tableSetupDone = $tableSetupDone;
	}

	/**
	 * Get a pointer to the Memory-Objects
	 *
	 * @return array|null - Get the Memory Objects or null if none is set
	 */
	final protected function &getMemoryObjects() {
		return $this->memoryObjects;
	}

	/**
	 * Sets the Memory Objects
	 *
	 * @param array|null $memoryObjects - Memory Objects or null if none
	 */
	final protected function setMemoryObjects($memoryObjects) {
		$this->memoryObjects = $memoryObjects;
	}

	/**
	 * Get the Memory Index
	 *
	 * @return int - Memory Index
	 */
	final protected function getMemoryIndex() {
		return $this->memoryIndex;
	}

	/**
	 * Set the Memory Index
	 *
	 * @param int $memoryIndex - Memory Index
	 */
	final protected function setMemoryIndex($memoryIndex) {
		$this->memoryIndex = $memoryIndex;
	}

	/**
	 * Check if Field Name exists
	 *
	 * @param string $field - Field Name
	 * @return bool - Exists field
	 */
	final protected static function existField($field) {
		if(in_array($field, self::getTableFields()))
			return true;

		return false;
	}

	/**
	 * Check the Table stuff
	 *
	 * @throws Exception - Table Info not set correct
	 */
	final protected static function checkIssetTableInfo() {
		if(! isset(self::$tableName) || ! isset(self::$tableFields))
			SQLError::addError('Table info is not set correctly!');

		if(! is_array(self::$tableFields) || ! is_string(self::$tableName))
			SQLError::addError('Table info var(s) has wrong data types!');

		// Check if primary key exists
		if(self::$primaryKeyField !== null) {
			if(! self::existField(self::$primaryKeyField))
				SQLError::addError('Primary-Key-Field doesn\'t exists');
		}

		// Check if table exists
		if(! self::getDb()->tableExists(self::$tableName))
			SQLError::addError('Table ' . self::$tableName . ' doesn\'t exists!');

		// Throw Exception on error
		if(SQLError::isError())
			throw new Exception('Table-Info is not correctly set! (See SQLError)');
	}

	/**
	 * Clears the Memory about the unused rows
	 */
	final protected function clearMemory() {
		$this->setMemoryObjects(null);
		$this->setMemoryIndex(0);
	}

	/**
	 * Sets the Object Table-Value to the next row
	 *
	 * @return bool - true if next row get | false if there isn't a next row
	 */
	final public function nextRow() {
		if($this->getMemoryObjects() === null)
			return false;

		if(! isset($this->getMemoryObjects()[$this->getMemoryIndex()])) {
			$this->clearMemory();
			return false;
		}

		$obj =& $this->getMemoryObjects()[$this->getMemoryIndex()];

		// Set the next row to this object
		foreach(self::getTableFields() as $field)
			$this->{$field} = $obj->get{ucfirst($field)}();

		// Remove the old object and increase counter
		$obj = null;
		$this->setMemoryIndex($this->getMemoryIndex() + 1);

		return true;
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
	public function getBy($value, $byField = null, $operator = DB::OPERATOR_EQUALS) {
		// Clear the old query
		$this->clearMemory();

		// Get the default Field
		if($byField === null)
			$byField = self::getPrimaryKeyField();

		// Exit if field doesn't exists
		if(! self::existField($byField)) {
			SQLError::addError('Field ' . $byField . ' doesn\'t exists in Table ' . self::getTableName());
			return;
		}

		$sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE ' . $byField . $operator . "':value';";

		$sth = self::getSqlStatement($sql);
		$sth->bindValue('value', $value, DB::getDataType($value));

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		//todo

		$sth->closeCursor();
	}

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
