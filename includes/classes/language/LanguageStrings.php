<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 19.04.2016
 * Time: 20:26
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Manages multi language support
 */

/**
 * Interface Language
 */
interface LanguageStrings {
	/**
	 * @param $fieldName
	 * @return mixed
	 */
	public function getFormErrorEmptyReq($fieldName);

	/**
	 * @param $fieldName
	 * @return mixed
	 */
	public function getFormErrorDataT($fieldName);

	/**
	 * @param $fieldName
	 * @param $minChars
	 * @return mixed
	 */
	public function getFormErrorMin($fieldName, $minChars);

	/**
	 * @param $fieldName
	 * @param $maxChars
	 * @return mixed
	 */
	public function getFormErrorMax($fieldName, $maxChars);

	/**
	 * @param $fieldName
	 * @param $minSelect
	 * @return mixed
	 */
	public function getFormErrorSelectLess($fieldName, $minSelect);

	/**
	 * @param $fieldName
	 * @param $maxSelect
	 * @return mixed
	 */
	public function getFormErrorSelectMuch($fieldName, $maxSelect);

	/**
	 * @param $fieldName
	 * @param $value
	 * @return mixed
	 */
	public function getFormErrorSelectNotInList($fieldName, $value);

	/**
	 * @return mixed
	 */
	public function getEmptySelect();


}
