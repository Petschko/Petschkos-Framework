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
	 * @var BaseDBTableModel|Object|null - Model or null if there is no model
	 */
	protected $model;

	/**
	 * BaseController constructor.
	 *
	 * @param Object|BaseDBTableModel|null $model - Controller Model or null if the controller has no model
	 */
	public function __construct(&$model = null) {
		$this->setModel($model);
	}

	/**
	 * Gets the Model
	 *
	 * @return BaseDBTableModel|Object|null - Model or null if there is no model
	 */
	protected function &getModel() {
		return $this->model;
	}

	/**
	 * Sets the Model
	 *
	 * @param BaseDBTableModel|Object|null $model - Model or null if there is no model
	 */
	protected function setModel(&$model) {
		$this->model =& $model;
	}
}
