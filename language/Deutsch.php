<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 02.05.2016
 * Time: 23:14
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

/**
 * Class Deutsch
 */
class Deutsch extends LangBaseClass {

	/**
	 * @param $fieldName
	 * @return mixed
	 */
	public function getFormErrorEmptyReq($fieldName) {
		return 'Das Feld &quot;' . $fieldName . '&quot; darf nicht leer sein!';
	}

	/**
	 * @param $fieldName
	 * @return mixed
	 */
	public function getFormErrorDataT($fieldName) {
		return 'Das Feld &quot;' . $fieldName . '&quot; enthält ungültige Eingaben!';
	}

	/**
	 * @param $fieldName
	 * @param $minChars
	 * @return mixed
	 */
	public function getFormErrorMin($fieldName, $minChars) {
		return 'Der Inhalt des Feldes &quot;' . $fieldName . '&quot; muss mindestens <b>' . $minChars . '</b> Zeichen lang sein!';
	}

	/**
	 * @param $fieldName
	 * @param $maxChars
	 * @return mixed
	 */
	public function getFormErrorMax($fieldName, $maxChars) {
		return 'Der Inhalt des Feldes &quot;' . $fieldName . '&quot; ist zu lang. Er darf nur Maximal <b>' . $maxChars . '</b> Zeichen lang sein!';
	}

	/**
	 * @param $fieldName
	 * @param $minSelect
	 * @return mixed
	 */
	public function getFormErrorSelectLess($fieldName, $minSelect) {
		return 'Es müssen mindestens <b>' . $minSelect . '</b> Optionen im Feld &quot;' . $fieldName . '&quot; ausgewählt werden!';
	}

	/**
	 * @param $fieldName
	 * @param $maxSelect
	 * @return mixed
	 */
	public function getFormErrorSelectMuch($fieldName, $maxSelect) {
		return 'Im Feld &quot;' . $fieldName . '&quot; können nur maximal <b>' . $maxSelect . '</b> Optionen ausgewählt werden!';
	}

	/**
	 * @param $fieldName
	 * @param $value
	 * @return mixed
	 */
	public function getFormErrorSelectNotInList($fieldName, $value) {
		return 'Der Wert &quot;' . $value . '&quot; existiert nicht im Feld &quot;' . $fieldName . '&quot;!';
	}

	/**
	 * @return mixed
	 */
	public function getEmptySelect() {
		return 'Auswählen...';
	}

	/**
	 * @return mixed
	 */
	public function getHTMLLang() {
		return 'de';
	}

	/**
	 * @return mixed
	 */
	public function getPageTitle() {
		return 'Titel';
	}
}
