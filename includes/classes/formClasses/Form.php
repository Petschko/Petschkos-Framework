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
	 * @const string - Hidden-Field name
	 */
	const HIDDEN_FIELD = '_form_name';

	/**
	 * Contains the Name of the Form
	 *
	 * @var string - Name of the Form
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
	 * Contains if the Form was send
	 *
	 * @var bool - Was the Form send
	 */
	private $send = false;

	/**
	 * Form constructor.
	 *
	 * @param string $action - Form action URL
	 * @param string $method - Submit method GET/FORM/FILE
	 * @param string $name - Form Name
	 */
	public function __construct($action, $name, $method = self::METHOD_POST) {
		$this->setAction($action);
		$this->setMethod($method);
		$this->setName($name);

		$this->setField($name . self::HIDDEN_FIELD, new InputField($name . self::HIDDEN_FIELD, 'hidden', $method));
		if($this->getField($name . self::HIDDEN_FIELD)->getCurrentValue($method) == $name)
			$this->setSend(true);
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
	 * @return string - Name of the Form
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the Name of the Form or null for none
	 *
	 * @param string $name - Name of the Form
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
	 * Sets a Field at the Fields array (useful for add fields)
	 *
	 * @param string $field - Name of the Field
	 * @param object|FormField $value - Field Object
	 */
	public function setField($field, $value) {
		$this->fields[$field] = $value;
	}

	/**
	 * Get the Field with the specified name
	 *
	 * @param string $field - Field name
	 * @return object|FormField - Field Object
	 */
	public function getField($field) {
		return $this->fields[$field];
	}

	/**
	 * Show if the Form was send
	 *
	 * @return boolean - Was the Form send
	 */
	public function isSend() {
		return $this->send;
	}

	/**
	 * Set if the Form was send
	 *
	 * @param boolean $send - Was the Form send
	 */
	private function setSend($send) {
		$this->send = $send;
	}

	/**
	 * Generates an opening HTML-Tag for the Form with correct Parameters (Recommend to use this)
	 *
	 * @param bool $hiddenField - Include Hidden-field
	 * @return string - HTML-form open Tag
	 */
	public function formOpenHTML($hiddenField = true) {
		$code = '<form action="' . $this->getAction();

		if($this->getMethod() == self::METHOD_FILE || $this->getMethod() == self::METHOD_POST)
			$code .= ' method="POST"';

		if($this->getMethod() == self::METHOD_FILE)
			$code .= ' enctype="multipart/form-data"';
		$code .= '>' . PHP_EOL;

		// Display hidden field
		if($hiddenField)
			$code .= $this->getField($this->getName() . '_form_name')->output() . PHP_EOL;

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

	// todo class
}
