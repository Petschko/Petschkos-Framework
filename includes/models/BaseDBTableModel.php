<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 13.04.2016
 * Time: 20:32
 * Update: 15.07.2016
 * Version: 1.2.6 (Added Table Optimization)
 * 1.2.5 (Fixed bug with compare NULL values used correct OPERATOR now)
 * 1.2.4 (Added function to remove a row with the model values)
 * 1.2.3 (Added function to get PK if ignore on save and format code)
 * 1.2.2 (Optimized clearTable function)
 * 1.2.1 (Added clearTable function)
 * 1.2.0 (Added CountTotal-Row function)
 * 1.1.7 (Removed final from several function that might be okay to overwrite)
 * 1.1.6 (Removed final from constructor to allow overwrite on child classes)
 * 1.1.5 (Destructor unset all table fields now)
 * 1.1.4 (Removed final from destructor for overwrite on child classes)
 * 1.1.3 (BugFix - Every new class has now all fields filled out)
 * 1.1.2 (Save object - on more results - to prob)
 * 1.1.1 (Fixed bug where a object is given to new self-class expected string)
 * 1.1.0 (Removed statics to that multiple models will work parallel)
 * 1.0.1 (Added Check if database is set with table info instead of the constructor)
 * @package Petschkos Framework
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Base abstract Model for SQL-Tables
 */

/**
 * Class BaseDBTableModel
 */
abstract class BaseDBTableModel {
	/**
	 * Contains the DB Object of the Model
	 *
	 * @var DB - DB (PDO) Object
	 */
	protected $db;

	/**
	 * Contains the Name of the Table
	 *
	 * @var string - Table Name
	 */
	protected $tableName;

	/**
	 * Contains the names of all Table-fields at the Table
	 *
	 * @var array - Table Fields
	 */
	protected $tableFields;

	/**
	 * Contains the FieldName of the Primary Key
	 *
	 * @var string|null - Primary Key FieldName | null when no primary key exists
	 */
	protected $primaryKeyField = null;

	/**
	 * Internal variable to check if the Table-Vars are already set
	 *
	 * @var bool - Is the Table setup done
	 */
	protected $tableSetupDone = false;

	/**
	 * Contains SQL-Cached Queries
	 *
	 * @var array - SQL-Statements
	 */
	protected $sqlCache = array();

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
	public function __construct($dbConName = null) {
		// Set all info (Table-Name, fields etc)
		$this->setTableInfo();

		// Check if the connection exists and if it is set in table info
		if(! DB::existsConnection($dbConName) && $this->getDb() === null) {
			$ex = 'Can\'t establish link to the Connection: ' . $dbConName . '! (It doesn\'t exists)';
			SQLError::addError($ex);
			throw new Exception($ex);
		}

		// Sets the connection
		if($this->getDb() === null)
			$this->setDb(DB::getConnection($dbConName));

		// Check if all is correct
		$this->checkIssetTableInfo();

		// All done set it
		$this->setTableSetupDone(true);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		// Remove all Table-Fields
		foreach($this->tableFields as $field)
			unset($this->{$field});

		// Unset Members
		unset($this->db);
		unset($this->tableName);
		unset($this->tableFields);
		unset($this->primaryKeyField);
		unset($this->tableSetupDone);
		unset($this->sqlCache);
		unset($this->memoryObjects);
		unset($this->memoryIndex);
		unset($this->memoryCount);
	}

	/**
	 * Sets the Table-Info
	 */
	protected abstract function setTableInfo();

	/**
	 * Gets the Database PDO-Object
	 *
	 * @return DB - Database PDO-Object
	 */
	final protected function getDb() {
		return $this->db;
	}

	/**
	 * Sets the Database PDO-Object
	 *
	 * @param DB $db - Database PDO-Object
	 */
	final protected function setDb($db) {
		$this->db = $db;
	}

	/**
	 * Returns a PDO Statement Object
	 *
	 * @param string $statement - SQL-Statement
	 * @return PDOStatement - PDOStatement object
	 */
	final protected function getSqlStatement($statement) {
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
	 * Get the Name of the Table
	 *
	 * @return string - Table Name
	 */
	final protected function getTableName() {
		return $this->tableName;
	}

	/**
	 * Sets the Name of the Table
	 *
	 * @param string $tableName - Name of the Table
	 */
	final protected function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * Get the Table Fields
	 *
	 * @return array - Table Fields
	 */
	final protected function getTableFields() {
		return $this->tableFields;
	}

	/**
	 * Sets the Table-Field Names
	 *
	 * @param array $tableFields - Field Names of the Table
	 */
	final protected function setTableFields($tableFields) {
		$this->tableFields = $tableFields;
	}

	/**
	 * Get the Field Name of the Primary Key
	 *
	 * @return null|string - Field name of the Primary Key | null when no Key is set
	 */
	final protected function getPrimaryKeyField() {
		return $this->primaryKeyField;
	}

	/**
	 * Sets the Field Name of the Primary-Key
	 *
	 * @param null|string $primaryKeyField - Field-Name of the Primary Key | null if none is set
	 */
	final protected function setPrimaryKeyField($primaryKeyField) {
		$this->primaryKeyField = $primaryKeyField;
	}

	/**
	 * Check if the Table-Setup is done
	 *
	 * @return boolean - is the Table-Setup done
	 */
	final protected function isTableSetupDone() {
		return $this->tableSetupDone;
	}

	/**
	 * Sets if the Table-Setup is done
	 *
	 * @param boolean $tableSetupDone - Is Table-Setup done
	 */
	final protected function setTableSetupDone($tableSetupDone) {
		$this->tableSetupDone = $tableSetupDone;
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
	 * Delete a Memory-Object
	 *
	 * @param int $index - Index of the element
	 */
	final protected function deleteMemoryObject($index) {
		unset($this->memoryObjects[$index]);
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
	final protected function existField($field) {
		if(in_array($field, $this->getTableFields()))
			return true;

		return false;
	}

	/**
	 * Checks and may assign the input Field if its set to PK-Field and if the assigned Field exists at the Table
	 *
	 * @param string|null $field - Field Name or null if use PK
	 * @return null|string - Field Name if all is ok or null if Field doesn't exists at the Table
	 */
	final protected function checkFieldInput($field) {
		// Get the default Field
		if($field === null)
			$field = $this->getPrimaryKeyField();

		// Add error if field doesn't exists
		if(! $this->existField($field)) {
			SQLError::addError('Field ' . $field . ' doesn\'t exists in Table ' . $this->getTableName());

			return null;
		}

		return $field;
	}

	/**
	 * Check the Table stuff
	 *
	 * @throws Exception - Table Info not set correct
	 */
	final protected function checkIssetTableInfo() {
		if(! isset($this->tableName) || ! isset($this->tableFields))
			SQLError::addError('Table info is not set correctly!');

		if(! is_array($this->tableFields) || ! is_string($this->tableName))
			SQLError::addError('Table info var(s) has wrong data types!');

		// Check if primary key exists
		if($this->primaryKeyField !== null) {
			if(! $this->existField($this->primaryKeyField))
				SQLError::addError('Primary-Key-Field doesn\'t exists');
		}

		// Check if table exists
		if(! $this->getDb()->tableExists($this->tableName))
			SQLError::addError('Table ' . $this->tableName . ' doesn\'t exists!');

		// Throw Exception on error
		if(SQLError::isError())
			throw new Exception('Table-Info is not correctly set! (See SQLError): ' . SQLError::printError());
	}

	/**
	 * Clears the Memory about the unused rows
	 */
	protected function clearMemory() {
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
	public function nextRow() {
		if($this->getMemoryObjects() === null)
			return false;

		if(! isset($this->getMemoryObjects()[$this->getMemoryIndex()])) {
			$this->clearMemory();

			return false;
		}

		$obj = $this->getMemoryObjects()[$this->getMemoryIndex()];

		// Set the next row to this object
		foreach($this->getTableFields() as $field)
			$this->{$field} = $obj->{$field};

		// Remove the old object and increase counter
		$this->deleteMemoryObject($this->getMemoryIndex());
		$this->setMemoryIndex($this->getMemoryIndex() + 1);

		return true;
	}

	/**
	 * Save the result to the Model (1st Row) and the rest to the memory
	 *
	 * @param array $results - Query Results
	 */
	protected function saveToMemory($results) {
		// Don't do anything if result is empty
		if($this->getMemoryCount() > 0) {

			// Set the first row to the model
			foreach($this->getTableFields() as $field)
				$this->{$field} = $results[0][$field];

			// Save all other row into the memory
			if($this->getMemoryCount() > 1) {
				$obj = array();
				$class = get_called_class();

				// Proceed every row
				for($i = 1; $i < $this->getMemoryCount(); $i++) {
					$obj[$i - 1] = new $class($this->getDb()->getName());

					foreach($this->getTableFields() as $field)
						$obj[$i - 1]->{$field} = $results[$i][$field];
				}

				// Save to memory
				$this->setMemoryObjects($obj);
			}
		}
	}

	/**
	 * Counts the Total-Rows of this Table
	 *
	 * @return int - Number of Total-Records
	 */
	final public function countTotalRows() {
		// Clear the old query
		$this->clearMemory();

		// Prepare SQL-Statement
		$sql = 'SELECT count(*) FROM ' . $this->getTableName() . ';';
		$sth = $this->getSqlStatement($sql);

		// Execute
		try {
			$sth->execute();
		} catch (PDOException $e) {
			SQLError::addError($e->getMessage());

			return 0;
		}

		// Get the Result(s)
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		// Save query and close
		$sth->closeCursor();

		if(count($result) < 1)
			return 0;

		if(isset($result[0]['count(*)']))
			return (int) $result[0]['count(*)'];

		return 0;
	}

	/**
	 * Optimizes this Table
	 *
	 * @return bool - true on success else false
	 */
	final public function optimizeTable() {
		$sth = $this->getSqlStatement('OPTIMIZE TABLE ' . $this->getTableName() . ';');

		try {
			$status = $sth->execute();
		} catch(PDOException $e) {
			SQLError::addError($e->getMessage());

			return false;
		}

		$sth->closeCursor();

		return ($status) ? true : false;
	}

	/**
	 * Detects the PK-field-Value by using ALL other Values currently set in the model set the PK-field to the detected value
	 *
	 * On fail null
	 */
	final protected function getPkValueByFields() {
		// Exit if PK is not set
		if($this->getPrimaryKeyField() === null)
			return;

		// Create WHERE String
		$whereSQL = array();
		$fieldValues = array();

		foreach($this->getTableFields() as $field) {
			if($field != $this->getPrimaryKeyField()) {
				if($this->{$field} === null)
					$whereSQL[] = $field . ' IS NULL';
				else {
					$whereSQL[] = $field . '=:where' . $field;
					$fieldValues['where' . $field] = $this->{$field};
				}
			}
		}

		// Make String
		$whereSQL = implode(' AND ', $whereSQL);

		// Create Prepared Statement
		$sql = 'SELECT ' . $this->getPrimaryKeyField() . ' FROM ' . $this->getTableName() . ' WHERE ' . $whereSQL . ';';
		$sth = $this->getSqlStatement($sql);

		// Bind Values
		foreach($fieldValues as $key => &$value)
			$sth->bindValue($key, $value, DB::getDataType($value));

		// Execute
		try {
			$sth->execute();
		} catch (PDOException $e) {
			$this->{$this->getPrimaryKeyField()} = null;

			return;
		}

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		// Exit on no results
		if(count($result) < 1) {
			$this->{$this->getPrimaryKeyField()} = null;

			return;
		}

		// Use the latest result
		$this->{$this->getPrimaryKeyField()} = $result[count($result) - 1][$this->getPrimaryKeyField()];

		$sth->closeCursor();
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
		$byField = $this->checkFieldInput($byField);
		if($byField === null)
			return;

		// Prepare SQL-Statement
		$sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ' . $byField . $operator .
			(($operator == DB::OPERATOR_IS_NULL || $operator == DB::OPERATOR_NOT_NULL) ? ';' : ':value;');
		$sth = $this->getSqlStatement($sql);

		// Bind Value
		if($operator != DB::OPERATOR_IS_NULL && $operator != DB::OPERATOR_NOT_NULL)
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
		$byField = $this->checkFieldInput($byField);
		if($byField === null)
			return false;

		// Prepare SQL-Statement
		$sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE ' . $byField . $operator .
			(($operator == DB::OPERATOR_IS_NULL || $operator == DB::OPERATOR_NOT_NULL) ? ';' : ':value;');
		$sth = $this->getSqlStatement($sql);

		// Bind Value
		if($operator != DB::OPERATOR_IS_NULL && $operator != DB::OPERATOR_NOT_NULL)
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
	 * Deletes a row that matches these fields - useful if you don't have a PK
	 *
	 * @return bool - true on success else false
	 */
	public function deleteByThis() {
		// Clear old Memory
		$this->clearMemory();

		// Prepare SQL-Statement
		$whereSQL = array();
		$deleteFields = array();
		foreach($this->getTableFields() as $field) {
			if($this->{$field} === null)
				$whereSQL[] = $field . ' IS NULL';
			else {
				$whereSQL[] = $field . '=:' . $field;
				$deleteFields[$field] = $this->{$field};
			}
		}

		$sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE ' . implode(' AND ', $whereSQL) . ' LIMIT 1;';
		$sth = $this->getSqlStatement($sql);

		// Bind Values
		foreach($deleteFields as $key => &$value)
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
		$byField = $this->checkFieldInput($byField);
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

		$sql = 'UPDATE ' . $this->getTableName() . ' SET ' . implode(', ', $setSQL) . ' WHERE ' . $byField .
			(($value === null) ? ' IS NULL;' : '=:value;');
		$sth = $this->getSqlStatement($sql);

		// Bind Values
		if($value !== null)
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
		$sql = 'SELECT * FROM ' . $this->getTableName() . ';';
		$sth = $this->getSqlStatement($sql);

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
		$sql = 'SELECT * FROM ' . $this->getTableName() . ' LIMIT ' . $startEndSQL . ';';
		$sth = $this->getSqlStatement($sql);

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

		if($this->getPrimaryKeyField() === null)
			$ignorePK = false;

		$fields = $this->getTableFields();

		if($ignorePK)
			$fields = array_slice($fields, array_search($this->getPrimaryKeyField(), $fields));

		// Prepare SQL-Statement
		$sql = 'INSERT INTO ' . $this->getTableName() . '(' . implode(', ', $fields) . ') VALUES (:' . implode(', :', $fields) . ');';
		$sth = $this->getSqlStatement($sql);

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

		// Get last insert ID only if ignore PK
		if($ignorePK)
			$this->getPkValueByFields();

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
		$byField = $this->checkFieldInput($byField);
		if($byField === null)
			return false;

		// Prepare SQL-Statement
		$sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE ' . $byField .
			(($this->{$byField} === null) ? ' IS NULL;' : '=:value;');
		$sth = $this->getSqlStatement($sql);

		// Bind Value
		if($this->{$byField} !== null)
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
		$byField = $this->checkFieldInput($byField);
		if($byField === null) {
			$whereSQL = array();
			//If PK doesn't exists and field is not set check ALL values if ALL are the same then update
			foreach($this->getTableFields() as $field) {
				if($this->{$field} === null)
					$whereSQL[] = $field . ' IS NULL';
				else {
					$whereSQL[] = $field . '=:where' . $field;
					$fieldValues['where' . $field] = $this->{$field};
				}
			}

			// Make String
			$whereSQL = implode(' AND ', $whereSQL);
		} else {
			$whereSQL = $byField . '=:where' . $byField;
			$fieldValues['where' . $byField] = $this->{$byField};
		}

		$setSQL = array();
		foreach($this->getTableFields() as $field) {
			$setSQL[] = $field . '=:set' . $field;
			$fieldValues['set' . $field] = $this->{$field};
		}

		$sql = 'UPDATE ' . $this->getTableName() . ' SET ' . implode(', ', $setSQL) . ' WHERE ' . $whereSQL . ' LIMIT 1;';
		$sth = $this->getSqlStatement($sql);

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

	/**
	 * Clears the Table and resets PK
	 *
	 * @return bool - true on success else false
	 */
	public function clearTable() {
		// Clear old Memory
		$this->clearMemory();

		$sth = $this->getSqlStatement('TRUNCATE TABLE ' . $this->getTableName() . ';');

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
