<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 19.04.2016
 * Time: 08:23
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Create easily Forms with this class
 */

require_once('FormField.php');

/**
 * Class Form
 */
class Form {
	/**
	 * @const int - Input-Types
	 */
	const F_INPUT = 0;
	const F_BUTTON = 1;
	const F_RADIO = 2;
	const F_SELECT_LIST = 3;
	const F_TEXTAREA = 4;

	/**
	 * @const string - Form-Method
	 */
	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';
	const METHOD_FILE = 'FILE';

	/**
	 * Contains the Name of the Form
	 *
	 * @var string|null - Name of the Form
	 */
	private $name;

	/**
	 * Contains the Action of the Form
	 *
	 * @var string - Action
	 */
	private $action;

	/**
	 * Contains the Method of the Form
	 *
	 * @var string - Method
	 */
	private $method;

	/**
	 * Contains all Fields of the Form
	 *
	 * @var array - Form-Fields
	 */
	private $fields = array();

	/**
	 * Form constructor.
	 *
	 * @param string $action - Form action URL
	 * @param string $method - Submit method GET/FORM/FILE
	 * @param string|null $name
	 */
	public function __construct($action, $method = self::METHOD_POST, $name = null) {
		$this->setAction($action);
		$this->setMethod($method);
		$this->setName($name);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->name);
		unset($this->action);
		unset($this->method);

		foreach($this->fields as $field)
			unset($field);
		unset($this->fields);
	}

	/**
	 * Get the Name of the Form or null if none is set
	 *
	 * @return string|null - Name of the Form
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the Name of the Form or null for none
	 *
	 * @param string|null $name - Name of the Form
	 */
	private function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get the Action of the Form
	 *
	 * @return string - Form Action
	 */
	private function getAction() {
		return $this->action;
	}

	/**
	 * Set the Form Action
	 *
	 * @param string $action - Form Action
	 */
	private function setAction($action) {
		$this->action = $action;
	}

	/**
	 * Get the Method of the Form
	 *
	 * @return string - Method
	 */
	private function getMethod() {
		return $this->method;
	}

	/**
	 * Set the Method of the Form
	 *
	 * @param string $method - Method
	 */
	private function setMethod($method) {
		$this->method = $method;
	}

	/**
	 * Get the Field of this Form
	 *
	 * @return array - Form fields
	 */
	private function getFields() {
		return $this->fields;
	}

	/**
	 * Set the Fields of this Form
	 *
	 * @param array $fields - Form Fields
	 */
	private function setFields($fields) {
		$this->fields = $fields;
	}

	/**
	 * Sets a Field at the Fields array (useful for add fields)
	 *
	 * @param string $field - Name of the Field
	 * @param object|FormField $value - Field Object
	 */
	private function setField($field, $value) {
		$this->fields[$field] = $value;
	}

	public function addInput($name, $inputType = self::F_INPUT, $type = 'text', $required = true, $allowedType = FormField::TYPE_STRING, $disabled = false, $otherHTMLAttr = null) {
		$this->setField($name, null);
	}

	/**
	 * Generates an opening HTML-Tag for the Form with correct Parameters (Recommend to use this)
	 *
	 * @return string - HTML-form open Tag
	 */
	public function formOpenHTML() {
		$code = '<form action="' . $this->getAction();

		if($this->getMethod() == self::METHOD_FILE || $this->getMethod() == self::METHOD_POST)
			$code .= ' method="POST"';

		if($this->getMethod() == self::METHOD_FILE)
			$code .= ' enctype="multipart/form-data"';
		$code .= '>';

		return $code;
	}

	/**
	 * Generates close HTML-Tag for the Form (You can also use plain HTML)
	 *
	 * @return string - HTML-form close Tag
	 */
	public function formCloseHTML() {
		return '</form>';
	}

	// todo
}
