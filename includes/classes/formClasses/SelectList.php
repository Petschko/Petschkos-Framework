<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 14:38
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
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
	 * Contains the select count (Possible selects)
	 *
	 * @var int - select count
	 */
	private $selectCount = 1;

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
	// @Overwrite
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
	// @Overwrite
	public function __destruct() {
		unset($this->list);
		unset($this->selectCount);
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
	 * Build base HTML-Code for the selected list
	 *
	 * @return string - HTML Code with CSS/Ids etc (Base stuff)
	 */
	// @Overwrite
	protected function baseHTMLAttr() {
		$code = 'name="' . $this->getName() . (($this->getSelectCount() > 1) ? '[]' : '') .'"';
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
	// @Overwrite | @Implement
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
			if($this->getSelectCount() > 1) {
				$code .= 'multiple';
				if(self::isXhtml())
					$code .= '="multiple"';
			}
		}

		// Close Start-Tag
		$code .= '>';

		// Show List
		foreach($this->getList() as $listName => $listValue) {
			$code .= '<option value="' . $listValue . '"';

			if(is_array($this->getValue()) && $this->getSelectCount() > 1) {
				//todo search value array of multi select
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
		// TODO: Implement output() method.
	}
}
