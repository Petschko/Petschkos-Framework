<?php
/**
 * @author Peter Dragicevic [peter@petschko.org]
 * @link https://petschko.org/
 * Date: 16.12.2020
 * Time: 10:56
 *
 * Notes: -
 *
 * @copyright 4Point (Paul-Hast GmbH)
 */

defined('BASE_DIR') or die('Invalid File-Access');

require_once(INCLUDE_DIR . DS . 'HoneypotField.php');
session_start();

/**
 * Class AntiSpam
 */
class AntiSpam {
	private const SESSION_NAME = 'anti_spam';

	private const PLUS = 0;
	private const MINUS = 1;
	private const MULTIPLE = 2;

	private static ?self $instance = null;
	private int $rndNumber1;
	private int $rndNumber2;
	private int $rndOperator;
	private int $result;
	private int $spamAttempts = 0;
	private string $currentForm;
	/**
	 * @var HoneypotField[] $honeypotFields
	 */
	private array $honeypotFields = [];
	/**
	 * @var string[] $registeredForms
	 */
	private array $registeredForms = [];

	/**
	 * AntiSpam constructor.
	 *
	 * @param string $formId - ID of the current <form> element
	 */
	private function __construct(string $formId) {
		$this->newMathQuestion();
		$this->currentForm = $formId;
	}

	/**
	 * @return int
	 */
	public function getSpamAttemptCount(): int {
		return $this->spamAttempts;
	}

	/**
	 * Returns the operator for the user
	 *
	 * @return string - Output
	 */
	public function getOperator(): string {
		switch($this->rndOperator) {
			case self::PLUS:
				return Config::ANTI_SPAM_USE_MATH_SYMBOLS ? '+' : Language::out()->getPlusName();
			case self::MINUS:
				return Config::ANTI_SPAM_USE_MATH_SYMBOLS ? '-' : Language::out()->getMinusName();
			case self::MULTIPLE:
			default:
				return Config::ANTI_SPAM_USE_MATH_SYMBOLS ? '*' : Language::out()->getMultiplyWithName();
		}
	}

	/**
	 * @return string
	 */
	public function getRndNumber1(): string {
		if(Config::ANTI_SPAM_NUMBERS_AS_TEXT) {
			return $this->getNumberToText($this->rndNumber1);
		}

		return (string) $this->rndNumber1;
	}

	/**
	 * @return string
	 */
	public function getRndNumber2(): string {
		if(Config::ANTI_SPAM_NUMBERS_AS_TEXT) {
			return $this->getNumberToText($this->rndNumber2);
		}

		return (string) $this->rndNumber2;
	}

	/**
	 * Converts a number to german text
	 *
	 * @param int $number - Number
	 * @return string - text or number if higher than 9 (or less than 0)
	 */
	private function getNumberToText(int $number): string {
		switch($number) {
			case 0:
				return Language::out()->get0Name();
			case 1:
				return Language::out()->get1Name();
			case 2:
				return Language::out()->get2Name();
			case 3:
				return Language::out()->get3Name();
			case 4:
				return Language::out()->get4Name();
			case 5:
				return Language::out()->get5Name();
			case 6:
				return Language::out()->get6Name();
			case 7:
				return Language::out()->get7Name();
			case 8:
				return Language::out()->get8Name();
			case 9:
				return Language::out()->get9Name();
			default:
				return (string) $number;
		}
	}

	/**
	 * Creates a new math question overwriting the old one (if exists)
	 *
	 * @throws Exception
	 */
	public function newMathQuestion(): void {
		$this->rndNumber1 = random_int(1, 10);
		$this->rndNumber2 = random_int(1, 10);
		$this->rndOperator = random_int(0, 2);
		$this->calcResult();
	}

	/**
	 * Calculates the math question result
	 */
	private function calcResult(): void {
		switch($this->rndOperator) {
			case self::PLUS:
				$this->result = $this->rndNumber1 + $this->rndNumber2;
				break;
			case self::MINUS:
				// Avoid negative results
				if($this->rndNumber1 < $this->rndNumber2) {
					$tmp = $this->rndNumber1;
					$this->rndNumber1 = $this->rndNumber2;
					$this->rndNumber2 = $tmp;
				}

				$this->result = $this->rndNumber1 - $this->rndNumber2;
				break;
			case self::MULTIPLE:
			default:
				$this->result = $this->rndNumber1 * $this->rndNumber2;
		}
	}

	/**
	 * Adds a new Honey-Put Field to the array
	 *
	 * @param string $formId - ID of the <form> element
	 * @param string $name - Name of the field
	 * @param string $class - Class of the field
	 * @param string $id - ID of the field
	 * @param string $type - Type of the field - default: text
	 * @param string|null $placeholder - Placeholder text or null for none
	 */
	public function addHoneypotField(string $formId, string $name, string $class, string $id, string $type = 'text', ?string $placeholder = null): void {
		// Only add once
		if(in_array($formId, $this->registeredForms)) {
			return;
		}

		$this->honeypotFields[] = new HoneypotField($formId, $name, $class, $id, $type, $placeholder);
	}

	/**
	 * Checks if the result of the user is true
	 *
	 * @param int $userResult - User-Input
	 * @return bool - User-Input is valid
	 */
	public function checkResult(int $userResult = 0): bool {
		return $userResult === $this->result;
	}

	/**
	 * @return HoneypotField[]
	 */
	public function getHoneypotFields(): array {
		return $this->honeypotFields;
	}

	/**
	 * Gets a honeypot field by name
	 *
	 * @param string $name - Honeypot field name
	 * @return HoneypotField|null - Honeypot field or null if not exists
	 */
	public function getHoneypotFieldByName(string $name): ?HoneypotField {
		foreach($this->honeypotFields as $field) {
			if($this->currentForm !== $field->formId) {
				continue;
			}

			if($name === $field->name) {
				return $field;
			}
		}

		return null;
	}

	/**
	 * Check if honey-pot fields are filled out
	 *
	 * @return bool - Honeypot is filled out (spam)
	 */
	public function checkHoneypotFields(): bool {
		foreach($this->honeypotFields as $field) {
			if($this->currentForm !== $field->formId) {
				continue;
			}

			if(! isset($_POST[$field->name])) {
				$_POST[$field->name] = '';
			}

			// ensure null (string) check, because JS acts strangly sometimes...
			if(! empty($_POST[$field->name]) && $_POST[$field->name] !== 'null') {
				$this->spamAttempts++;
				$this->saveSession();

				return true;
			}
		}

		return false;
	}

	/**
	 * Outputs the Math-Label
	 *
	 * @param string $labelForId - ID of the Math-Input Field
	 * @param string|null $id - ID of the element or null for none
	 * @param bool $numbersAsString - display as text instead of number
	 * @param bool $operatorSymbol - Use symbols instead of text
	 * @param bool $echo - Display output directly
	 * @return string - Output
	 */
	public function outputMathLabel(string $labelForId, ?string $id = null, bool $numbersAsString = false, bool $operatorSymbol = false, bool $echo = true): string {
		$string = '<label for="' . $labelForId . '"' . ($id ? ' id="' . $id . '"' : '') . '>' .
			$this->getInnerMathLabelContent() . ' <span class="math-req">*</span>' .
			'</label>' . PHP_EOL;

		if($echo)
			echo $string;
		return $string;
	}

	/**
	 * Outputs the Math-Question by itself
	 *
	 * @return string - Output
	 */
	public function getInnerMathLabelContent(): string {
		return sprintf(Language::out()->getAntiSpamMsgText(), $this->getRndNumber1(), $this->getOperator(), $this->getRndNumber2());
	}

	/**
	 * Outputs the Math-Input field
	 *
	 * @param string $name - Name of the Field
	 * @param string $id - ID of the Math-Field
	 * @param bool $echo - Display output directly
	 * @param string|null $class - Class of the input field or null for none
	 * @param string|null $placeholder - Placeholder of the input field or null for none
	 * @return string - Output
	 */
	public function outputMathField(string $name, string $id, bool $echo = true, ?string $class = null, ?string $placeholder = null): string {
		$string = '<input type="text" id="' . $id . '" name="' . $name . '"' .
			($class ? ' class="' . $class . '"' : '') .
			($placeholder ? ' placeholder="' . $placeholder . '"' : '') .
			' autocomplete="off" required>' . PHP_EOL;

		if($echo) {
			echo $string;
		}

		return $string;
	}

	/**
	 * Checks if the Form has changed
	 *
	 * @param string $newFormId - ID of the <form> element
	 */
	public function checkForm(string $newFormId): void {
		if($newFormId !== $this->currentForm) {
			$this->currentForm = $newFormId;

			$this->newMathQuestion();
		}
	}

	/**
	 * Registers a form
	 *
	 * @param string $formId - ID of the <form> element
	 */
	public function registerForm(string $formId): void {
		if(! in_array($formId, $this->registeredForms)) {
			$this->registeredForms[] = $formId;
		}
	}

	/**
	 * Saves this object to session
	 */
	public function saveSession(): void {
		$_SESSION[self::SESSION_NAME] = $this;
	}

	/**
	 * Deletes the session of Antispam
	 */
	public function deleteSession(): void {
		unset($_SESSION[self::SESSION_NAME]);
	}

	/**
	 * Gets the Instance
	 *
	 * @param string $formId - Id of the Form
	 * @return self - Instance
	 */
	final public static function getInstance(string $formId): self {
		if(! isset($_SESSION[self::SESSION_NAME]) || ! is_object($_SESSION[self::SESSION_NAME])) {
			self::$instance = new self($formId);
		}
		if(is_null(self::$instance)) {
			self::$instance = $_SESSION[self::SESSION_NAME];
		}

		// Check if form has changed
		self::$instance->checkForm($formId);

		return self::$instance;
	}
}
