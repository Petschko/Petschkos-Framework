<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 27.04.2016
 * Time: 23:17
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

/**
 * Class English
 */
class English extends LangBaseClass {

	/**
	 * @param $fieldName
	 * @return mixed
	 */
	public function getFormErrorEmptyReq($fieldName) {
		return 'The Field &quot;' . $fieldName . '&quot; cannot be empty!';
	}

	/**
	 * @param $fieldName
	 * @return mixed
	 */
	public function getFormErrorDataT($fieldName) {
		return 'Invalid Data on the Field &quot;' . $fieldName . '&quot;!';
	}

	/**
	 * @param $fieldName
	 * @param $minChars
	 * @return mixed
	 */
	public function getFormErrorMin($fieldName, $minChars) {
		return 'Your Input on the Field &quot;' . $fieldName . '&quot; has to be min <b>' . $minChars . '</b> Characters!';
	}

	/**
	 * @param $fieldName
	 * @param $maxChars
	 * @return mixed
	 */
	public function getFormErrorMax($fieldName, $maxChars) {
		return 'Your Input on the Field &quot;' . $fieldName . '&quot; is to long! Maximum <b>' . $maxChars . '</b> Characters.';
	}

	/**
	 * @param $fieldName
	 * @param $minSelect
	 * @return mixed
	 */
	public function getFormErrorSelectLess($fieldName, $minSelect) {
		return 'You have to select at least <b>' . $minSelect . '</b> Options on the Field &quot;' . $fieldName . '&quot;';
	}

	/**
	 * @param $fieldName
	 * @param $maxSelect
	 * @return mixed
	 */
	public function getFormErrorSelectMuch($fieldName, $maxSelect) {
		return 'You can only select max <b>' . $maxSelect . '</b> Options on the Field &quot;' . $fieldName . '&quot;';
	}

	/**
	 * @param $fieldName
	 * @param $value
	 * @return mixed
	 */
	public function getFormErrorSelectNotInList($fieldName, $value) {
		return 'The Value &quot;' . $value . '&quot; does not exists in the Field &quot;' . $fieldName . '&quot;';
	}

	/**
	 * @return mixed
	 */
	public function getEmptySelect() {
		return 'Select...';
	}
}
