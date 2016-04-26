<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 19.04.2016
 * Time: 16:45
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Base Class for every FormField
 */

// Include sub-classes
require_once('InputField.php');
require_once('SelectList.php');
require_once('TextArea.php');

/**
 * Class FormField
 */
abstract class FormField {
	/**
	 * @const String - Data Types
	 */
	const TYPE_BOOL = 'bool';
	const TYPE_STRING = 'string';
	const TYPE_INT = 'int';
	const TYPE_DOUBLE = 'double';
	const TYPE_NUMBER = 'number';
	const TYPE_PHONE = 'phone';
	const TYPE_EMAIL = 'email';
	const TYPE_TIME = 'time';
	const TYPE_DATE = 'date';
	const TYPE_DATETIME = 'datetime';
	const TYPE_URL = 'url';
	const TYPE_COLOR = 'color';
	const TYPE_TEXT = 'text';
	const TYPE_ZIP = 'zip';
	const TYPE_AUTODETECT = 'auto';

	/**
	 * @const int - XSS-Replace Methods
	 */
	const XSS_NOT_REPLACE = 0;
	const XSS_HTMLSPECIALCHARS = 1;
	const XSS_HTMLENTITIES = 2;

	/**
	 * Contains if XHTML is enabled
	 *
	 * @var bool - Is xHTML enabled
	 */
	protected static $xhtml = false;

	/**
	 * Contains if HTML5 is allowed
	 *
	 * @var bool - Is HTML5 allowed
	 */
	protected static $html5 = true;

	/**
	 * Contains the XSS-Replace Method
	 *
	 * @var int - XSS-Replace Method
	 */
	protected static $xssReplace = self::XSS_HTMLSPECIALCHARS;

	/**
	 * Contains the Name of the Field
	 *
	 * @var string - Name of the Field
	 */
	protected $name;

	/**
	 * Contains the current Value of the Field
	 *
	 * @var string|null - Value of the current Field
	 */
	protected $value;

	/**
	 * Contains the Input-Field-Type of the Field
	 *
	 * @var string - Type of the Field
	 */
	protected $type;

	/**
	 * Contains the allowed Data-Type
	 *
	 * @var string - Allowed Data-Type
	 */
	protected $dataType = self::TYPE_STRING;

	/**
	 * Contains if the Field is disabled
	 *
	 * @var bool - Is the Field disabled
	 */
	protected $disabled = false;

	/**
	 * Contains if the Field is required
	 *
	 * @var bool - Is the Field required
	 */
	protected $required = true;

	/**
	 * Contains if the Field is read only
	 *
	 * @var bool - Is read only
	 */
	protected $readOnly = false;

	/**
	 * Contains the CSS/HTML-Id(s)
	 *
	 * @var array - CSS/HTML-Id(s)
	 */
	protected $cssIds;

	/**
	 * Contains the CSS/HTML-Class(es)
	 *
	 * @var array - CSS/HTML-Class(es)
	 */
	protected $cssClasses;

	/**
	 * Contains all other HTML-Attributes
	 *
	 * @var string|null - Other HTML-Attributes
	 */
	protected $otherHTMLAttr;

	/**
	 * Contains the Minimum Length of this Field - 0 Means there is no minimum
	 *
	 * @var int - Minimum Length
	 */
	protected $minLen = 0;

	/**
	 * Contains the Maximum Length of this Field - 0 Means there is no maximum
	 *
	 * @var int - Maximum Length
	 */
	protected $maxLen = 0;

	/**
	 * Contains the size of the object - null is the default value
	 *
	 * @var null|int - Size of the Object
	 */
	protected $size = null;

	/**
	 * FormField constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param string $type - Input type of the Field
	 * @param bool $required - Is the Field required
	 * @param string $dataType - Allowed Data-Type of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param string|null $otherHTMLAttr - Other HTML-Attributes
	 * @param string|null $value - Value of the Field
	 */
	public function __construct($name, $type, $required = true, $dataType = self::TYPE_STRING, $disabled = false, $otherHTMLAttr = null, $value = null) {
		$this->setName($name);
		if(self::isHtml5())
			$this->setType($type);
		else
			$this->setType(self::removeHtml5Type($type));
		$this->setRequired($required);
		$this->setDataType($dataType);
		$this->setDisabled($disabled);
		$this->setOtherHTMLAttr($otherHTMLAttr);
		$this->setValue($value);

		// Auto assign data-type on special fields
		$this->autoSetDataType($type);
	}


	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->name);
		unset($this->value);
		unset($this->type);
		unset($this->dataType);
		unset($this->enabled);
		unset($this->required);
		unset($this->readOnly);
		unset($this->cssIds);
		unset($this->cssClasses);
		unset($this->otherHTMLAttr);
		unset($this->minLen);
		unset($this->maxLen);
		unset($this->size);
	}

	/**
	 * Returns true if you use XHTML
	 *
	 * @return boolean - is this XHTML code
	 */
	final protected static function isXhtml() {
		return self::$xhtml;
	}

	/**
	 * Set the value of XHTML use
	 *
	 * @param boolean $xhtml - enable/disable XHTML (true = enable | false = disable)
	 */
	final public static function setXhtml($xhtml) {
		self::$xhtml = $xhtml;
	}

	/**
	 * Returns true if HTML5 is enabled
	 *
	 * @return boolean - is HTML5 enabled (true = yes | false = no)
	 */
	final protected static function isHtml5() {
		return self::$html5;
	}

	/**
	 * Set HTML5 on/off
	 *
	 * @param boolean $html5 - enable/disable HTML5 (true = enable | false = disable)
	 */
	final public static function setHtml5($html5) {
		self::$html5 = $html5;
	}

	/**
	 * Get the XSS-Replace method
	 *
	 * @return int - XSS-Replace method
	 */
	final protected static function getXssReplace() {
		return self::$xssReplace;
	}

	/**
	 * Set the XSS-Replace method
	 *
	 * @param int $xssReplace - XSS-Replace method
	 */
	final public static function setXssReplace($xssReplace) {
		self::$xssReplace = $xssReplace;
	}

	/**
	 * Returns the allowed DataType of the value
	 *
	 * @return string - Allowed DataType
	 */
	final public function getDataType() {
		return $this->dataType;
	}

	/**
	 * Set the allowed DataType of the value
	 *
	 * @param string $dataType - Allowed DataType
	 */
	final public function setDataType($dataType) {
		$this->dataType = $dataType;
	}

	/**
	 * Returns true if the object is required
	 *
	 * @return boolean - is this field required
	 */
	final public function isRequired() {
		return $this->required;
	}

	/**
	 * Set if the object is required
	 *
	 * @param boolean $required - is this field required (true = yes | false = no)
	 */
	final public function setRequired($required) {
		$this->required = $required;
	}

	/**
	 * Returns the name of this object
	 *
	 * @return string - the name of this object
	 */
	final public function getName() {
		return $this->name;
	}

	/**
	 * Set the name of this object
	 *
	 * @param string $name - the name of this object
	 */
	final protected function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the current value of this object
	 *
	 * @return string|null - the current value of this object
	 */
	protected function getValue() {
		return $this->value;
	}

	/**
	 * Set the current value of this object
	 *
	 * @param string|null $value - the current value of this object
	 */
	public function setValue($value) {
		$this->value = trim($value);
	}

	/**
	 * Get the Value escaped (if not turned off)
	 *
	 * @return string - Escaped Value
	 */
	public function getEscapedValue() {
		// Return empty string if is empty
		if($this->getValue() === null)
			return '';

		switch(self::getXssReplace()) {
			case self::XSS_NOT_REPLACE:
				return $this->getValue();
			case self::XSS_HTMLENTITIES:
				return htmlentities($this->getValue(), ENT_QUOTES, 'UTF-8', true);
			case self::XSS_HTMLSPECIALCHARS:
			default:
				return htmlspecialchars($this->getValue(), ENT_QUOTES, 'UTF-8', true);
		}
	}

	/**
	 * Get the current type of this object
	 *
	 * @return string - the current type of this object
	 */
	final public function getType() {
		return $this->type;
	}

	/**
	 * Set the current type of this object
	 *
	 * @param string $type - the current type of this object
	 */
	final protected function setType($type) {
		$this->type = mb_strtolower($type);
	}

	/**
	 * Returns true if the object is disabled else false
	 *
	 * @return boolean - is this object disabled
	 */
	final public function isDisabled() {
		return $this->disabled;
	}

	/**
	 * Set if the object is disabled
	 *
	 * @param boolean $disabled - enable/disable this object (false = enabled | true = disabled)
	 */
	final public function setDisabled($disabled) {
		$this->disabled = $disabled;
	}

	/**
	 * Returns true if you can only read the field
	 *
	 * @return boolean - Can field only read? true = readOnly | false = write/read
	 */
	final public function isReadOnly() {
		return $this->readOnly;
	}

	/**
	 * Set if field can only read
	 *
	 * @param boolean $readOnly - Can field only read? true = readOnly | false = write/read
	 */
	final public function setReadOnly($readOnly) {
		$this->readOnly = $readOnly;
	}

	/**
	 * Returns the CSS-ID(s) or null
	 *
	 * @return array|null - CSS-ID(s) as array or null if there are none
	 */
	final protected function getCssIds() {
		return $this->cssIds;
	}

	/**
	 * Set the CSS-ID(s)
	 *
	 * @param array|null $cssIds - CSS-ID(s) as array or null if there are none
	 */
	final protected function setCssIds($cssIds) {
		$this->cssIds = $cssIds;
	}

	/**
	 * Add a CSS-ID to the object
	 *
	 * @param string $cssIdName - the name of a css ID, no "," needed!!!
	 */
	final public function addCssId($cssIdName) {
		$this->cssIds[] = $cssIdName;
	}

	/**
	 * Remove a CSS-ID from the object
	 *
	 * @param string $cssIdName - the name of a css ID
	 */
	final public function removeCssId($cssIdName) {
		// Create a new array and include all values to it except the remove css class
		$tmpNew = array();
		$i = 0;
		foreach($this->getCssIds() as $cssId) {
			if($cssIdName != $cssId) {
				$tmpNew[$i] = $cssId;
				$i++;
			}
		}

		$this->setCssIds($tmpNew);
	}

	/**
	 * Returns CSS-Class(es) or null
	 *
	 * @return array|null - CSS-Class(es) as array or null if there are none
	 */
	final protected function getCssClasses() {
		return $this->cssClasses;
	}

	/**
	 * Set CSS-Class(es)
	 *
	 * @param array|null $cssClasses - CSS-Class(es) as array or null if there are none
	 */
	final protected function setCssClasses($cssClasses) {
		$this->cssClasses = $cssClasses;
	}

	/**
	 * Add a CSS-Class to the object
	 *
	 * @param string $cssClassName - the name of a css class, no "," needed!!!
	 */
	final public function addCssClass($cssClassName) {
		$this->cssClasses[] = $cssClassName;
	}

	/**
	 * Remove a CSS-Class from the object
	 *
	 * @param string $cssClassName - the name of a css class
	 */
	final public function removeCssClass($cssClassName) {
		// Create a new array and include all values to it except the remove css class
		$tmpNew = array();
		$i = 0;
		foreach($this->getCssClasses() as $cssClass) {
			if($cssClassName != $cssClass) {
				$tmpNew[$i] = $cssClass;
				$i++;
			}
		}

		$this->setCssClasses($tmpNew);
	}

	/**
	 * Returns all other HTML-Stuff or null
	 *
	 * @return string|null - all other html attributes - null if there are none
	 */
	final public function getOtherHTMLAttr() {
		return $this->otherHTMLAttr;
	}

	/**
	 * Set all other CSS stuff
	 *
	 * @param string|null $otherHTMLAttr - all other html attributes - null if there are none
	 */
	final public function setOtherHTMLAttr($otherHTMLAttr) {
		$this->otherHTMLAttr = $otherHTMLAttr;
	}

	/**
	 * Returns the value of chars that this field must have at least
	 *
	 * @return int - min length of the field
	 */
	final public function getMinLen() {
		return $this->minLen;
	}

	/**
	 * Set the value of chars that this field must have at least
	 *
	 * @param int $minLen - min length of the field.
	 */
	final public function setMinLen($minLen) {
		$this->minLen = $minLen;
	}

	/**
	 * Returns the value of chars that this field can have max
	 *
	 * @return int - max length 0 means no limit
	 */
	final public function getMaxLen() {
		return $this->maxLen;
	}

	/**
	 * Set the value of chars that this field can have max
	 *
	 * @param int $maxLen - max length 0 means no limit
	 */
	final public function setMaxLen($maxLen) {
		$this->maxLen = $maxLen;
	}

	/**
	 * Returns the size of the object - null means no limit
	 *
	 * @return null|int - size of the object - null means no limit
	 */
	final public function getSize() {
		return $this->size;
	}

	/**
	 * Set the size of the object - null means no limit
	 *
	 * @param null|int $size - size of the object - null means no limit
	 */
	final public function setSize($size) {
		if(is_numeric($size) || $size === null)
			$this->size = $size;
	}

	/**
	 * Removes HTML5 types to input type text other non-HTML5 types will return normal
	 *
	 * @param string $type - Type to check
	 * @return string - New type (text) or the other non HTML5 Type
	 */
	final protected static function removeHtml5Type($type) {
		switch(mb_strtolower($type)) {
			case 'color':
			case 'date':
			case 'datetime':
			case 'datetime-local':
			case 'email':
			case 'month':
			case 'number':
			case 'range':
			case 'search':
			case 'tel':
			case 'time':
			case 'url':
			case 'week':
				$type = 'text';
		}

		return $type;
	}

	/**
	 * Returns the current user value of the Field
	 *
	 * @param string $postMethod - Method of submit eg POST GET
	 * @return mixed|null - User Value of the field or null if empty
	 */
	final public function getCurrentValue($postMethod) {
		if($postMethod == Form::METHOD_GET) {
			if(!isset($_GET[$this->getName()]))
				$_GET[$this->getName()] = null;

			return $_GET[$this->getName()];
		} else {
			if(!isset($_POST[$this->getName()]))
				$_POST[$this->getName()] = null;

			return $_POST[$this->getName()];
		}
	}

	/**
	 * Detect if a value is a number
	 *
	 * @param mixed $value - Value to check
	 * @param bool $updateValue - Update value if it was touched
	 * @return bool - true if its a number and false if not
	 */
	final protected function checkIsNumber($value, $updateValue = true) {
		if(is_numeric($value))
			return true;

		if(is_string($value)) {
			// Replace comma to dot
			$value = str_replace(',', '.', $value);

			// Check if value is numeric AND if locale settings pointing to dot
			if(is_numeric(1.1)) {
				if(! is_numeric($value))
					return false;
			} else {
				// If locale point to comma replace all dots to commas
				$value = str_replace('.', ',', $value);
				if(! is_numeric($value))
					return false;
			}
		} else
			return false;

		if($updateValue && $value != $this->getValue())
			$this->setValue($value);

		return true;
	}

	/**
	 * Returns CSS-ID(s) as HTML-string
	 *
	 * @return string - HTML-string
	 */
	final protected function cssIdsHTML() {
		if($this->getCssIds() === null)
			return '';

		$code = '';
		foreach($this->getCssIds() as $cssId) {
			$code .= ' ' . $cssId;
		}

		return ' id="' . trim($code) . '"';
	}

	/**
	 * Returns CSS-Class(es) as HTML-string
	 *
	 * @return string - HTML-String
	 */
	final protected function cssClassesHTML() {
		if($this->getCssClasses() === null)
			return '';

		$code = '';
		foreach($this->getCssClasses() as $cssClass) {
			$code .= ' ' . $cssClass;
		}

		return ' class="' . trim($code) . '"';
	}

	/**
	 * Check if the value dataType is the same as required on this field and convert them to the correct type
	 *
	 * @return bool - true on success else false
	 */
	final public function checkDataType() {
		$value = $this->getValue();

		// Go to the right dataType to check
		switch($this->getDataType()) {

			case self::TYPE_BOOL:
				// Convert value to bool
				if(is_string($value))
					$this->setValue((boolean) $value);

				if(! is_bool($this->getValue()))
					return false;
				break;

			case self::TYPE_INT:
				// Check if string contains only numbers
				if(is_string($value)) {
					if(! ctype_digit($value)) {

						// May its an negative value check it out
						if(! mb_substr($value, 0, 1) == '-') {
							if(! ctype_digit(mb_substr($value, 1)))
								return false;
						} else
							return false;
					}

					// Convert to int
					$this->setValue((int) $value);
				}

				if(! is_int($this->getValue()))
					return false;
				break;

			case self::TYPE_DOUBLE:
				// Check if string is numeric
				if(is_string($value)) {
					if(! $this->checkIsNumber($value))
						return false;

					// Convert to float
					$this->setValue((float) $value);
				}

				if(! (is_int($this->getValue()) || is_float($this->getValue())))
					return false;
				break;

			case self::TYPE_NUMBER:
				if(! $this->checkIsNumber($value))
					return false;
				break;

			case self::TYPE_PHONE:
				if(! is_string($value))
					return false;

				// Verify that its a number (with special chars) of typical numbers
				if(! preg_match('/([^0-9-+ \/\(\)])$/', $value))
					return false;
				break;

			case self::TYPE_STRING:
				// Check if its a string
				if(! is_string($value))
					return false;

				//todo regex remove htmlEntities
				break;

			case self::TYPE_EMAIL:
				if(! is_string($value))
					return false;

				// Check E-Mail pattern
				$atPos = mb_strpos($value, '@');
				$lastPointPos = mb_strrpos($value, '.');

				if(! $atPos || ! $lastPointPos)
					return false;

				if(! ($atPos > 0 && $lastPointPos > ($atPos + 1) && mb_strlen($value) > ($lastPointPos + 1)))
					return false;
				break;

			case self::TYPE_COLOR:
				//todo
			case self::TYPE_URL:
				if(! is_string($value))
					return false;

				// Only allow specific Characters to URLs
				if(! preg_match('/([^0-9a-zA-Z+_ #~&@=,;:\|\-\?\.\/])$/', $value)) // todo test
					return false;

				// Correct URL
				//$this->setValue(); //todo

				break;
			case self::TYPE_TIME:
				//todo
			case self::TYPE_DATE:
				//todo
			case self::TYPE_DATETIME:
				//todo
			case self::TYPE_ZIP:
				//todo

			case self::TYPE_TEXT:
			default:
				if(! is_string($value))
					return false;

		}

		return true;
	}

	/**
	 * Autodetect dataType for every FieldType and assign it (can changed later)
	 * Set also auto lengths can changed later too :)
	 *
	 * @param string|null $type - Current Field-Type
	 */
	final protected function autoSetDataType($type = null) {
		// Is a Type set? If not use object field type
		if($type === null)
			$type = $this->getType();

		switch(mb_strtolower($type)) {
			case 'color':
				$this->setDataType(self::TYPE_COLOR);
				$this->setMinLen(3);
				$this->setMaxLen(7);
				break;

			case 'time':
				$this->setDataType(self::TYPE_TIME);
				$this->setMinLen(3);
				$this->setMaxLen(5);
				break;

			case 'date':
				$this->setDataType(self::TYPE_DATE);
				$this->setMinLen(6);
				$this->setMaxLen(10);
				break;

			case 'datetime':
			case 'datetime-local':
				$this->setDataType(self::TYPE_DATETIME);
				$this->setMaxLen(16);
				break;

			case 'email':
				$this->setDataType(self::TYPE_EMAIL);
				$this->setMinLen(6);
				$this->setMaxLen(256);
				break;

			case 'tel':
				$this->setDataType(self::TYPE_PHONE);
				$this->setMaxLen(30);
				break;

			case 'url':
				$this->setDataType(self::TYPE_URL);
				$this->setMinLen(11);
				break;

			case 'range':
			case 'number':
				$this->setDataType(self::TYPE_NUMBER);
				break;
		}
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Form Field as HTML Output
	 */
	abstract public function output($show = true);

	/**
	 * Build base HTML-Code for the input fields
	 *
	 * @return string - HTML Code with CSS/Ids etc (Base stuff)
	 */
	protected function baseHTMLAttr() {
		$code = 'name="' . $this->getName() . '"';
		$code .= $this->cssIdsHTML() . $this->cssClassesHTML();

		// Add min length if is set
		if($this->getMinLen() > 0 && self::isHtml5())
			$code .= ' minlength="' . $this->getMinLen() . '"';

		// Add max length if is set
		if($this->getMaxLen() > 0 && self::isHtml5())
			$code .= ' maxlength="' . $this->getMaxLen() . '"';

		// Include other HTML attr
		if($this->getOtherHTMLAttr() !== null)
			$code .= ' ' . $this->getOtherHTMLAttr();

		// Is this field readOnly?
		if($this->isReadOnly()) {
			$code .= ' readonly';
			if(self::isXhtml())
				$code .= '="readonly"';
		}

		// Is this field required AND is HTML5 allowed?
		if(self::isHtml5() && $this->isRequired()) {
			$code .= ' required';
			if(self::isXhtml())
				$code .= '="required"';
		}

		// Is disabled
		if($this->isDisabled()) {
			$code .= ' disabled';
			if(self::isXhtml())
				$code .= '="disabled"';
		}

		return $code;
	}
}
