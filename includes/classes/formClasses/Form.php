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
	 * Contains all Form-Errors
	 *
	 * @var null|array
	 */
	private $error = null;

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
		else
			$this->getField($name . self::HIDDEN_FIELD)->setValue($name);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->name);
		unset($this->action);
		unset($this->method);
		unset($this->send);

		foreach($this->fields as $field)
			unset($field);
		unset($this->fields);

		foreach($this->error as $error)
			unset($error);
		unset($this->error);
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
	 * @param bool $htmlOutput - Show new field direct as HTML
	 */
	public function setField($field, $value, $htmlOutput = true) {
		$this->fields[$field] = $value;

		if($htmlOutput)
			$this->getField($field)->output(true);
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
	 * Get the Fields Array
	 *
	 * @return array - Fields Array
	 */
	private function getFields() {
		return $this->fields;
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
	 * Sets the Error-Object array
	 *
	 * @param array|null $error - Error objects or null to reset
	 */
	private function setError($error) {
		$this->error = $error;
	}

	/**
	 * Get the Error-Objects or null if no error happens
	 *
	 * @return array|null - Error-Objects or null
	 */
	private function getError() {
		return $this->error;
	}

	/**
	 * Add a new Error to the Error-Object Array
	 *
	 * @param FormError $error - Error-Object
	 */
	private function addError($error) {
		$this->error[] = $error;
	}

	/**
	 * Checks all fields of the current form and generate if occur error objects
	 */
	public function checkData() {
		// Reset old Errors (if set)
		if(is_array($this->getError()))
			foreach($this->error as $error)
				unset($error);
		$this->setError(null);

		// Check all Form-Fields
		foreach($this->getFields() as $field) {
			/**
			 * @var FormField $field - FormField Object
			 */
			$value = $field->getEscapedValue();

			// Check if Required and Empty
			if($field->isRequired() && ! $value)
				$this->addError(new FormError(FormError::ERROR_EMPTY_REQUIRED, $field));
			else if($field->getType() == Form::F_SELECT_LIST) {
				// Do some special if the element is a select list
				/**
				 * @var SelectList $field - SelectList Object
				 * @var array|string $value - Select Value(s)
				 */
				$selectedOptions = $field->getCurrentSelectCount();

				// Check if to many options are selected
				if($selectedOptions > $field->getSelectCount() && $field->getSelectCount() != 0)
					$this->addError(new FormError(FormError::ERROR_SELECT_TO_MANY, $field));
					//is not in list

				// Check if multi options are enabled
				if($field->getSelectCount() != 1) {
					// Check if to less options are selected
					if($selectedOptions < $field->getMinSelectCount() && ($field->isRequired() || $selectedOptions > 0))
						$this->addError(new FormError(FormError::ERROR_SELECT_TO_LESS, $field));

					// Check all Values
					foreach($value as $item) {
						if(! $field->isInList($item))
							$this->addError(new FormError(FormError::ERROR_SELECT_NOT_IN_LIST, $field));
						$this->baseCheckValue($item, $field);
					}
				} else if(is_array($value)) // Value is never an array on 1 select
					$this->addError(new FormError(FormError::ERROR_ARRAY_IS_INVALID, $field));
				else {
					// Perform normal check to the string value
					if(! $field->isInList($value))
						$this->addError(new FormError(FormError::ERROR_SELECT_NOT_IN_LIST, $field));
					$this->baseCheckValue($value, $field);
				}

			} else
				$this->baseCheckValue($value, $field);
		}
	}

	/**
	 * Base-check all stuff on a value of a field
	 *
	 * @param string $value - Value to check
	 * @param FormField $field - Origin Field of the value
	 */
	private function baseCheckValue($value, $field) {
		$valLen = mb_strlen($value);

		// Check if value is to long and if limit is set
		if($valLen > $field->getMaxLen() && $field->getMaxLen() != 0)
			$this->addError(new FormError(FormError::ERROR_MAX_LEN, $field));

		// Check if its to short (will only returns an error if field is filled out or is req and min len is bigger than 0)
		if($valLen < $field->getMinLen() && $field->getMinLen() != 0 && ($field->isRequired() || $valLen > 0))
			$this->addError(new FormError(FormError::ERROR_MIN_LEN, $field));

		// Check DataType
		if(! $field->checkDataType() && $valLen > 0)
			$this->addError(new FormError(FormError::ERROR_DATA_TYPE, $field));
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

	public function showError() {
		// TODO: Implement showError() method
	}
}
