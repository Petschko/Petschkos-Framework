<?php
/**
 * @author Peter Dragicevic [peter@petschko.org]
 * @link https://petschko.org/
 * Date: 16.12.2020
 * Time: 11:17
 *
 * Notes: -
 *
 * @copyright 4Point (Paul-Hast GmbH)
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class HoneypotField
 */
class HoneypotField {
	public string $formId;
	public string $name;
	public string $type;
	public string $class;
	public string $id;
	public ?string $placeholder;

	/**
	 * HoneypotField constructor.
	 *
	 * @param string $formId - ID of the <form> element
	 * @param string $name - Name of the field
	 * @param string $class - Class of the field
	 * @param string $id - ID of the field
	 * @param string $type - Type of the field - default: text
	 * @param string|null $placeholder - Placeholder text or null for none
	 */
	public function __construct(string $formId, string $name, string $class, string $id, string $type = 'text', ?string $placeholder = null) {
		$this->formId = $formId;
		$this->name = $name;
		$this->type = $type;
		$this->class = $class;
		$this->id = $id;
		$this->placeholder = $placeholder;
	}

	/**
	 * Outputs the Label for this Field
	 *
	 * @param bool $echo - Output directly
	 * @return string - Output
	 */
	public function outputLabel(bool $echo = true): string {
		$string = '<label class="' . $this->class . '" for="' . $this->id . '"></label>';

		if($echo)
			echo $string;
		return $string;
	}

	/**
	 * Outputs the field
	 *
	 * @param bool $echo - Output directly
	 * @return string - Output
	 */
	public function outputField(bool $echo = true): string {
		if($this->type === 'textarea') {
			return $this->outputTextArea($echo);
		}

		$string = '<input class="' . $this->class .
			'" autocomplete="off" type="' . $this->type .
			'" id="' . $this->id . '" name="' . $this->name .
			'"' . (is_null($this->placeholder) ? '' : ' placeholder="' . $this->placeholder . '"') . '>';

		if($echo) {
			echo $string;
		}
		return $string;
	}

	/**
	 * Outputs the textarea field
	 *
	 * @param bool $echo - output directly
	 * @return string - Output
	 */
	private function outputTextArea(bool $echo): string {
		$string = '<textarea name="' . $this->name . '" id="' . $this->id . '" class="' . $this->class . '" rows="30"></textarea>';

		if($echo) {
			echo $string;
		}
		return $string;
	}
}
