<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 *
 * Notes: Example file for DB-Table-Class
 */

/**
 * Class ExampleTable
 */
class ExampleTable extends BaseDBTableModel {
	/**
	 * @vars: Set The Fields as public class vars with the same name as you have them in the table
	 */
	public $id;
	public $col2;
	public $col3;

	/**
	 * Sets the Table-Info
	 */
	public static function setTableInfo() {
		// Set Table Name (Required)
		self::setTableName('exampleTable');

		// Set Fields (Required)
		self::setTableFields(array('id', 'col2', 'col3'));

		// Set Primary-Key (Optional - If none don't set it)
		self::setPrimaryKeyField('id');

		// Set Database-Connection (Very Optional - You can also use the constructor to set it)
		self::setDb(DB::getConnection('myConnection'));
	}
}
