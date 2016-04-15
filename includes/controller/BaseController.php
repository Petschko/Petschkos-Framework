<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 15:37
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Notes: Base abstract Controller
 */

/**
 * Class BaseController
 */
abstract class BaseController {
	/**
	 * Contains the Model of the Controller
	 *
	 * @var BaseDBTableModel|Object
	 */
	private $model;

	/**
	 * BaseController constructor.
	 *
	 * @param Object|BaseDBTableModel $model
	 */
	public function __construct($model) {
		$this->model =& $model;
	}

	/**
	 * Gets the Model
	 *
	 * @return mixed
	 */
	private function &getModel() {
		return $this->model;
	}

	/**
	 * Sets the Model
	 *
	 * @param mixed $model
	 */
	private function setModel($model) {
		$this->model =& $model;
	}
}
