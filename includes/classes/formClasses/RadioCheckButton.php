<?php

/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 22:37
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for easily creating radio-Buttons and Checkboxes
 */

/**
 * Class RadioCheckButton
 */
class RadioCheckButton extends InputField {
	/**
	 * Contains if value is checked
	 *
	 * @var bool - Is value checked
	 */
	private $checked = false;

	/**
	 * Contains the Group-Name of the Elements (eq radio buttons have the same name)
	 *
	 * @var string - Group Name
	 */
	private $groupName;

	/**
	 * RadioCheckButton constructor.
	 *
	 * @param string $name - Name of the Object
	 * @param string $groupName - Name of the FieldGroup
	 * @param string $type - Input type of the Field
	 * @param string $methodType - Submit method of the Form
	 * @param string $value - Value of the Field
	 * @param bool $required - Is the Field required
	 * @param bool|string $dataType - Allowed Data-Type of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param null|string $otherHTMLAttr - Other HTML-Attributes
	 */
	public function __construct($name, $groupName, $type, $methodType, $value, $required = true, $dataType = self::TYPE_STRING, $disabled = false, $otherHTMLAttr = null) {
		// Do not autodetect user value because its a checkbox/radio button - compare the value and the user value
		parent::__construct($name, $type, null, $required, $dataType, $disabled, $otherHTMLAttr, $value);
		$this->setGroupName($groupName);

		// Check if the user has selected this value
		$userValue = $this->getCurrentValue($methodType);
		if($this->getValue() == $userValue)
			$this->setChecked(true);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->checked);
		unset($this->groupName);
		parent::__destruct();
	}

	/**
	 * Returns true if the object is checked else false
	 *
	 * @return boolean - is the object checked
	 */
	public function isChecked() {
		return $this->checked;
	}

	/**
	 * Set if the object is checked
	 *
	 * @param boolean $checked - is the object checked (true = checked | false = not checked)
	 */
	public function setChecked($checked) {
		$this->checked = $checked;
	}

	/**
	 * Get the Group name of the Field
	 *
	 * @return string - Group name
	 */
	public function getGroupName() {
		return $this->groupName;
	}

	/**
	 * Set the Group name of the Field
	 *
	 * @param string $groupName - Group name
	 */
	private function setGroupName($groupName) {
		$this->groupName = $groupName;
	}

	/**
	 * Get the value if the field is checked
	 *
	 * @return mixed|null - The Value if the Field is checked or null if not
	 */
	public function getValueIfChecked() {
		if($this->isChecked())
			return $this->getValue();

		return null;
	}

	/**
	 * Generate Base-HTML for Radio-buttons and check boxes
	 *
	 * @return string - HTML Code with CSS/Ids etc (Base stuff)
	 */
	protected function baseHTMLAttr() {
		$code = 'name="' . $this->getGroupName() . '"';
		$code .= $this->cssIdsHTML() . $this->cssClassesHTML();

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

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Button as HTML Output
	 */
	public function output($show = true) {
		$code = '<input type="' . $this->getType() . '" ' . $this->baseHTMLAttr() . ' value="' . $this->getEscapedValue() . '"';

		// Is checked
		if($this->isChecked()) {
			$code .= ' checked';
			if(self::isXhtml())
				$code .= '="checked"';
		}

		if(self::isXhtml())
			$code .= ' /';
		$code .= '>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
