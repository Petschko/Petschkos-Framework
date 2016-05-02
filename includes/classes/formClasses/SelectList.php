<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 22:38
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for easily creating Select-Lists
 */

/**
 * Class SelectList
 */
class SelectList extends FormField {
	/**
	 * Contains the Select-List
	 *
	 * @var array - Select-List
	 */
	private $list = array();

	/**
	 * Contains the select count (Possible selects - Max Selects) or 0 for no limit
	 *
	 * @var int - select count
	 */
	private $selectCount = 1;

	/**
	 * Contains how many options have to be min selected (Min selects)
	 *
	 * @var int - min select count
	 */
	private $minSelectCount = 1;

	/**
	 * Contains if the list is simple (Value and displayed value are the same)
	 *
	 * @var bool - Is the List simple
	 */
	private $simpleList = true;

	/**
	 * SelectList constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param array $list - Select-List
	 * @param bool $methodType - Submit method of the Form
	 * @param null|string $defaultSelectValue - Default selected value or null for none
	 * @param bool $simpleList - Is the value the same like the shown value
	 * @param bool $required - Is the Field required
	 * @param int $selectCount - Number of select options
	 * @param bool $disabled - Is the field disabled
	 * @param string $dataType - Allowed Data-Type of the Field
	 * @param null|string $otherHTMLAttr - Other HTML-Attributes
	 */
	public function __construct($name, $list, $methodType, $defaultSelectValue = null, $simpleList = true, $required = true,
								$selectCount = 1, $disabled = false, $dataType = self::TYPE_STRING, $otherHTMLAttr = null) {
		parent::__construct($name, 'select', $required, $dataType, $disabled, $otherHTMLAttr, null);
		$this->setList($list);
		$this->setSimpleList($simpleList);
		$this->setSelectCount($selectCount);

		// Detect Value
		$userValue = $this->getCurrentValue($methodType);
		if($userValue !== null)
			$this->setValue($userValue);
		else if($defaultSelectValue !== null)
			$this->setValue($defaultSelectValue);

	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->list);
		unset($this->selectCount);
		unset($this->minSelectCount);
		unset($this->simpleList);
		parent::__destruct();
	}

	/**
	 * Get the Select-List
	 *
	 * @return array - Select-List
	 */
	public function getList() {
		return $this->list;
	}

	/**
	 * Set the Select-List
	 *
	 * @param array $list - Select-List
	 */
	public function setList($list) {
		$this->list = $list;
	}

	/**
	 * Get the select count
	 *
	 * @return int - select count
	 */
	public function getSelectCount() {
		return $this->selectCount;
	}

	/**
	 * Set the select count
	 *
	 * @param int $selectCount - select count
	 */
	public function setSelectCount($selectCount) {
		$this->selectCount = $selectCount;
	}

	/**
	 * Get how many Options have min select if min 1 is selected or field is req
	 *
	 * @return int - Min select count
	 */
	public function getMinSelectCount() {
		return $this->minSelectCount;
	}

	/**
	 * Set how many options have to be selected min if min 1 is selected or field is req
	 *
	 * @param int $minSelectCount - min select count
	 */
	public function setMinSelectCount($minSelectCount) {
		$this->minSelectCount = $minSelectCount;
	}

	/**
	 * Get if the List is simple
	 *
	 * @return bool - Is the List simple
	 */
	private function isSimpleList() {
		return $this->simpleList;
	}

	/**
	 * Set if the List is simple
	 *
	 * @param bool $simpleList - Is the List simple
	 */
	private function setSimpleList($simpleList) {
		$this->simpleList = $simpleList;
	}

	/**
	 * Returns the current value of this object
	 *
	 * @return array|string|null - the current value of this object
	 */
	protected function getValue() {
		return $this->value;
	}

	/**
	 * Set the current value of this object
	 *
	 * @param array|string|null $value - the current value of this object
	 */
	public function setValue($value) {
		$this->value = trim($value);
	}

	/**
	 * Get the Value escaped (if not turned off)
	 *
	 * @param null|string|array $value
	 * @return string|array - Escaped Value
	 */
	public function getEscapedValue($value = null) {
		// Get the current object value if none is set as param
		if($value === null)
			$value = $this->getValue();

		// Return empty string if is empty
		if($value === null)
			return '';

		// If value is array do this function for every array part
		if(is_array($value)) {
			$tmp = array();
			foreach($value as $key => $item)
				$tmp[$key] = $this->getEscapedValue($item);

			return $tmp;
		}

		switch(self::getXssReplace()) {
			case self::XSS_NOT_REPLACE:
				return $value;
			case self::XSS_HTMLENTITIES:
				return htmlentities($value, ENT_QUOTES, 'UTF-8', true);
			case self::XSS_HTMLSPECIALCHARS:
			default:
				return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', true);
		}
	}

	/**
	 * Get the count of the current selected options of the user
	 *
	 * @return int - Selected options
	 */
	public function getCurrentSelectCount() {
		if(is_array($this->getValue()))
			return count($this->getValue());

		if($this->getValue())
			return 1;
		return 0;
	}

	/**
	 * Shows if the value is a possible select from the list (exists in select list)
	 *
	 * @param string $value - Value to check
	 * @return bool - Is value in select list
	 */
	public function isInList($value) {
		// If field is not required and its empty "show" that is in list
		if($value == '' && ! $this->isRequired())
			return true;

		return in_array($value, $this->getList());
	}

	/**
	 * Build base HTML-Code for the selected list
	 *
	 * @return string - HTML Code with CSS/Ids etc (Base stuff)
	 */
	protected function baseHTMLAttr() {
		$code = 'name="' . $this->getName() . (($this->getSelectCount() == 1) ? '' : '[]') .'"';
		$code .= $this->cssIdsHTML() . $this->cssClassesHTML();

		// Add size if its not the default size
		if($this->getSize() !== null && $this->getSize() != 1)
			$code .= ' size="' . $this->getSize() . '"';

		// Include other HTML attr
		if($this->getOtherHTMLAttr() !== null)
			$code .= ' ' . $this->getOtherHTMLAttr();

		// Is this field required AND is HTML5 allowed?
		if(self::isHtml5() && $this->isRequired()) {
			$code .= ' required';
			if(self::isXhtml())
				$code .= '="required"';
		}

		return $code;
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Select-Field as HTML Output
	 */
	public function output($show = true) {
		$code = '<select ' . $this->baseHTMLAttr() . '';

		if($this->getSize() !== null)
			$code .= ' size="' . $this->getSize() . '"';

		// Is disabled
		if($this->isDisabled()) {
			$code .= ' disabled';
			if(self::isXhtml())
				$code .= '="disabled"';
		} else {
			// Is multiple select
			if($this->getSelectCount() != 1) {
				$code .= 'multiple';
				if(self::isXhtml())
					$code .= '="multiple"';
			}
		}

		// Close Start-Tag
		$code .= '>';

		// If field is not required generate a empty field
		if(! $this->isRequired() && $this->getSelectCount() == 1)
			$code .= '<option value="">&lt;None&gt;</option>'; // todo lang

		// Show List
		foreach($this->getList() as $listName => $listValue) {
			$code .= '<option value="' . $listValue . '"';

			// Check if multi select
			if(is_array($this->getValue()) && $this->getSelectCount() != 1) {
				foreach($this->getValue() as $value) {
					if($value == $listValue) {
						$code .= ' selected';
						if(self::isXhtml())
							$code .= '="selected"';
						break;
					}
				}
			} else if($listValue == $this->getValue()) {
				$code .= ' selected';

				if(self::isXhtml())
					$code .= '="selected"';
			}

			$code .= '>' . (($this->isSimpleList()) ? $listValue : $listName) . '</option>';
		}

		// Close Select Field
		$code .= '</select>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
