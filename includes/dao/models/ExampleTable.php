<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 *
 * Notes: Example file for DB-Table-Class
 */

defined('BASE_DIR') or die('Invalid File-Access');

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
	protected function setTableInfo(): void {
		// Set Table Name (Required)
		$this->setTableName('exampleTable');

		// Set Fields (Required)
		$this->setTableFields(['id', 'col2', 'col3']);

		// Set Primary-Key (Optional - If none don't set it)
		$this->setPrimaryKeyField('id');

		// Set Database-Connection (Very Optional - You can also use the constructor to set it)
		$this->setDb(DB::getConnection('myConnection'));
	}
}
