<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 13.04.2016
 * Time: 20:32
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Base abstract Model for SQL-Tables
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
	 * Contains the number of results of the last Query
	 *
	 * @var int - Number of results (last Query)
	 */
	protected $memoryCount = 0;

	/**
	 * BaseModel constructor.
	 *
	 * @param string $dbConName - Database PDO-Object Name | null if connection is assigned
	 * @throws Exception - Can't find connection to DB
	 * @throws Exception - Table Info not set correct
	 */
	final protected function __construct($dbConName = null) {
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
		unset($this->memoryIndex);
		unset($this->memoryCount);
	}

	/**
	 * Gets the Database PDO-Object
	 *
	 * @return DB - Database PDO-Object
	 */
	final protected static function getDb() {
		return self::$db;
	}

	/**
	 * Sets the Database PDO-Object
	 *
	 * @param DB $db - Database PDO-Object
	 */
	final protected static function setDb($db) {
		self::$db = $db;
	}

	/**
	 * Returns a PDO Statement Object
	 *
	 * @param string $statement - SQL-Statement
	 * @return PDOStatement - PDOStatement object
	 */
	final protected static function getSqlStatement($statement) {
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
	 * Get the Memory-Objects
	 *
	 * @return array|null - Get the Memory Objects or null if none is set
	 */
	final protected function getMemoryObjects() {
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
	 * Get the Memory Count (Number of results of the last Query)
	 *
	 * @return int - Memory Count
	 */
	final public function getMemoryCount() {
		return $this->memoryCount;
	}

	/**
	 * Set the Memory Count (Number of results of the last Query)
	 *
	 * @param int $memoryCount - Memory Count
	 */
	final protected function setMemoryCount($memoryCount) {
		$this->memoryCount = $memoryCount;
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
	 * Checks and may assign the input Field if its set to PK-Field and if the assigned Field exists at the Table
	 *
	 * @param string|null $field - Field Name or null if use PK
	 * @return null|string - Field Name if all is ok or null if Field doesn't exists at the Table
	 */
	final protected static function checkFieldInput($field) {
		// Get the default Field
		if($field === null)
			$field = self::getPrimaryKeyField();

		// Add error if field doesn't exists
		if(! self::existField($field)) {
			SQLError::addError('Field ' . $field . ' doesn\'t exists in Table ' . self::getTableName());
			return null;
		}

		return $field;
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
		// Remove the old memory objects
		$this->setMemoryObjects(null);

		// Reset Counter
		$this->setMemoryIndex(0);
		$this->setMemoryCount(0);
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
			$this->{$field} = $obj->{$field};

		// Remove the old object and increase counter
		unset($obj);
		$this->setMemoryIndex($this->getMemoryIndex() + 1);

		return true;
	}

	/**
	 * Save the result to the Model (1st Row) and the rest to the memory
	 *
	 * @param array $results - Query Results
	 */
	final protected function saveToMemory($results) {
		// Don't do anything if result is empty
		if($this->getMemoryCount() > 0) {

			// Set the first row to the model
			foreach(self::getTableFields() as $field)
				$this->{$field} = $results[0][$field];

			// Save all other row into the memory
			if($this->getMemoryCount() > 1) {
				$obj = array();
				$class = get_called_class();

				// Proceed every row
				for($i = 1; $i < $this->getMemoryCount(); $i++) {
					foreach(self::getTableFields() as $field) {
						$obj[$i - 1] = new $class();
						$obj[$i - 1]->{$field} = $results[$i][$field];
					}
				}
			}
		}
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

		// Get Field
		$byField = self::checkFieldInput($byField);
		if($byField === null)
			return;

		// Prepare SQL-Statement
		$sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE ' . $byField . $operator . ':value;';
		$sth = self::getSqlStatement($sql);
		$sth->bindValue('value', $value, DB::getDataType($value));

		// Execute
		try {
			$sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());
			return;
		}

		// Get the Result(s)
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$this->setMemoryCount(count($result));

		// Save query and close
		$this->saveToMemory($result);
		$sth->closeCursor();
	}

	/**
	 * Deletes one or more Data Rows
	 * You can choose by which term they get selected
	 *
	 * @param mixed $value - Value of the Field
	 * @param string|null $byField - Target Field | null uses Primary-Key
	 * @param string $operator - Compare Operator
	 * @return bool - true on success else false
	 */
	public function deleteBy($value, $byField = null, $operator = DB::OPERATOR_EQUALS) {
		// Clear the old query
		$this->clearMemory();

		// Get Field
		$byField = self::checkFieldInput($byField);
		if($byField === null)
			return false;

		// Prepare SQL-Statement
		$sql = 'DELETE FROM ' . self::getTableName() . ' WHERE ' . $byField . $operator . ':value;';
		$sth = self::getSqlStatement($sql);
		$sth->bindValue('value', $value, DB::getDataType($value));

		// Execute
		try {
			$success = $sth->execute();
		} catch(PDOException $e) {
			SQLError::addError($e->getMessage());
			return false;
		}

		$sth->closeCursor();
		if($success)
			return true;

		return false;
	}

	/**
	 * Update the current row at the Database with the Model-Data
	 *
	 * @param mixed $value - Value of the Field
	 * @param string|null $byField - Target Field | null uses Primary-Key
	 * @return bool - true on success else false
	 */
	public function updateBy($value, $byField = null) {
		// Clear the old query
		$this->clearMemory();

		// Get Field
		$byField = self::checkFieldInput($byField);
		if($byField === null)
			return false;

		// Prepare SQL-Statement
		$sqlFields = array();
		$setSQL = array();
		foreach($this->getTableFields() as $field) {
			if($byField != $field) {
				$setSQL[] = $field . '=:' . $field;
				$sqlFields[$field] = $this->{$field};
			}
		}
		$sql = 'UPDATE ' . self::getTableName() . ' SET ' . implode(', ', $setSQL) . ' WHERE ' . $byField . '=:value;';
		$sth = self::getSqlStatement($sql);

		// Bind Values
		$sth->bindValue('value', $value, DB::getDataType($value));
		foreach($sqlFields as $key => &$value)
			$sth->bindValue($key, $value, DB::getDataType($value));

		// Execute
		try {
			$success = $sth->execute();
		} catch(PDOException $e) {
			SQLError::addError($e->getMessage());
			return false;
		}

		$sth->closeCursor();
		if($success)
			return true;

		return false;
	}

	/**
	 * Get all Data rows
	 */
	public function getAll() {
		// Clear the old query
		$this->clearMemory();

		// Prepare SQL-Statement
		$sql = 'SELECT * FROM ' . self::getTableName() . ';';
		$sth = self::getSqlStatement($sql);

		// Execute
		try {
			$sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());
			return;
		}

		// Get the Result(s)
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$this->setMemoryCount(count($result));

		// Save query and close
		$this->saveToMemory($result);
		$sth->closeCursor();
	}

	/**
	 * Get one or more Data-Rows
	 *
	 * @param int $limit - Max Rows
	 * @param int|null $start - Start Row | null use the default start value
	 */
	public function get($limit = 1, $start = null) {
		// Clear the old query
		$this->clearMemory();

		if(! is_int($limit))
			SQLError::addError('LIMIT must be an Integer Value!');

		if($start !== null)
			if(! is_int($start))
				SQLError::addError('START must be null or an Integer Value!');

		if(SQLError::isError())
			return;

		// Prepare SQL-Statement
		$startEndSQL = (($start !== null) ? $start . ', ' : '') . $limit;
		$sql = 'SELECT * FROM ' . self::getTableName() . ' LIMIT ' . $startEndSQL . ';';
		$sth = self::getSqlStatement($sql);

		// Execute
		try {
			$sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());
			return;
		}

		// Get the Result(s)
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$this->setMemoryCount(count($result));

		// Save query and close
		$this->saveToMemory($result);
		$sth->closeCursor();
	}

	/**
	 * Saves the current values of the Model to the Database (New Row)
	 *
	 * @param bool $ignorePK - Ignore the Primary Key-Value (Example needed for auto-increment)
	 * @return bool - true on success else false
	 */
	public function save($ignorePK = true) {
		// Clear the old query
		$this->clearMemory();

		if(self::getPrimaryKeyField() === null)
			$ignorePK = false;

		$fields = self::getTableFields();

		if($ignorePK)
			$fields = array_slice($fields, array_search(self::getPrimaryKeyField(), $fields));

		// Prepare SQL-Statement
		$sql = 'INSERT INTO ' . self::getTableName() . '(' . implode(', ', $fields) . ') VALUES (:' . implode(', :', $fields) . ');';
		$sth = self::getSqlStatement($sql);

		// Bind Values
		foreach($fields as $field)
			$sth->bindValue($field, $this->{$field}, DB::getDataType($this->{$field}));

		// Execute
		try {
			$success = $sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());
			return false;
		}

		$sth->closeCursor();
		if($success)
			return true;

		return false;
	}

	/**
	 * Deletes the current Data-Row depending on the current Model values
	 * null uses the Primary-Key as Target
	 *
	 * @param null|string $byField - Target Field Name or null if use PK-Field as target
	 * @return bool - true on success else false
	 */
	public function delete($byField = null) {
		// Clear the old query
		$this->clearMemory();

		// Get Field
		$byField = self::checkFieldInput($byField);
		if($byField === null)
			return false;

		// Prepare SQL-Statement
		$sql = 'DELETE FROM ' . self::getTableName() . ' WHERE ' . $byField . '=:value;';
		$sth = self::getSqlStatement($sql);
		$sth->bindValue('value', $this->{$byField}, DB::getDataType($this->{$byField}));

		// Execute
		try {
			$success = $sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());
			return false;
		}

		$sth->closeCursor();
		if($success)
			return true;

		return false;
	}

	/**
	 * Update the current Data-Row with the Model values
	 * null uses the the Primary-Key as Target
	 *
	 * @param null|string $byField - Target Field Name or null if use PK-Field as target
	 * @return bool - true on success else false
	 */
	public function update($byField = null) {
		$fieldValues = array();

		// Clear the old query
		$this->clearMemory();

		// Get Field & prepare SQL-Statement
		$byField = self::checkFieldInput($byField);
		if($byField === null) {
			$whereSQL = array();
			//If PK doesn't exists and field is not set check ALL values if ALL are the same then update
			foreach(self::getTableFields() as $field) {
				$whereSQL[] = $field . '=:where' . $field;
				$fieldValues['where' . $field] = $this->{$field};
			}

			// Make String
			$whereSQL = implode(' AND ', $whereSQL);
		} else {
			$whereSQL = $byField . '=:where' . $byField;
			$fieldValues['where' . $byField] = $this->{$byField};
		}

		$setSQL = array();
		foreach(self::getTableFields() as $field) {
			$setSQL[] = $field . '=:set' . $field;
			$fieldValues['set' . $field] = $this->{$field};
		}

		$sql = 'UPDATE ' . self::getTableName() . ' SET ' . implode(', ', $setSQL) . ' WHERE ' . $whereSQL . ' LIMIT 1;';
		$sth = self::getSqlStatement($sql);

		// Bind Values
		foreach($fieldValues as $key => &$value)
			$sth->bindValue($key, $value, DB::getDataType($value));

		// Execute
		try {
			$success = $sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());
			return false;
		}

		$sth->closeCursor();
		if($success)
			return true;

		return false;
	}
}
