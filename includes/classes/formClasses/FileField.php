<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 23:00
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for easily creating file fields
 */

/**
 * Class FileField
 */
class FileField extends InputField {
	/**
	 * Contains the accept type (MIME-Type) of the FileField
	 *
	 * @var null|string - FileType or null for all
	 */
	private $acceptType = null;

	/**
	 * FileField constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param string $type - Input type of the Field
	 * @param null|string $methodType - Submit method of the Form
	 * @param bool $required - Is the Field required
	 * @param bool|string $dataType - Allowed Data-Type of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param string|null $otherHTMLAttr - Other HTML-Attributes
	 * @param null|string $fileAccept - Accept HTML-Type of the FileField
	 */
	public function __construct($name, $type, $methodType, $required = true, $dataType = self::TYPE_STRING, $disabled = false, $otherHTMLAttr = null, $fileAccept = null) {
		parent::__construct($name, $type, $methodType, $required, $dataType, $disabled, $otherHTMLAttr, null);
		$this->setAcceptType($fileAccept);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->acceptType);
		parent::__destruct();
	}

	/**
	 * Get the Accept-Type (MIME-Type) of the FileField
	 *
	 * @return null|string - FileType or null for all
	 */
	public function getAcceptType() {
		return $this->acceptType;
	}

	/**
	 * Set the Accept-Type (MIME-Type) of the FileField
	 *
	 * @param null|string $acceptType - FileType or null for all
	 */
	public function setAcceptType($acceptType) {
		$this->acceptType = $acceptType;
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - File Field as HTML Output
	 */
	public function output($show = true) {
		$code = '<input type="file" ' . $this->baseHTMLAttr() . '';

		if($this->getSize() !== null)
			$code .= ' size="' . $this->getSize() . '"';

		if($this->getAcceptType() !== null)
			$code .= ' accept="' . $this->getAcceptType() . '"';

		if(self::isXhtml())
			$code .= ' /';
		$code .= '>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
