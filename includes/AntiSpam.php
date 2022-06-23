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
				return Config::useMathSymbols ? '+' : 'plus';
			case self::MINUS:
				return Config::useMathSymbols ? '-' : 'minus';
			case self::MULTIPLE:
			default:
				return Config::useMathSymbols ? '*' : 'multipliziert mit';
		}
	}

	/**
	 * @return string
	 */
	public function getRndNumber1(): string {
		if(Config::numbersAsString)
			return $this->getNumberToText($this->rndNumber1);

		return (string) $this->rndNumber1;
	}

	/**
	 * @return string
	 */
	public function getRndNumber2(): string {
		if(Config::numbersAsString)
			return $this->getNumberToText($this->rndNumber2);

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
				return 'Null';
			case 1:
				return 'Eins';
			case 2:
				return 'Zwei';
			case 3:
				return 'Drei';
			case 4:
				return 'Vier';
			case 5:
				return 'Fünf';
			case 6:
				return 'Sechs';
			case 7:
				return 'Sieben';
			case 8:
				return 'Acht';
			case 9:
				return 'Neun';
			default:
				return (string) $number;
		}
	}

	/**
	 * Creates a new math question overwriting the old one (if exists)
	 *
	 * @throws Exception
	 */
	public function newMathQuestion() {
		$this->rndNumber1 = random_int(1, 10);
		$this->rndNumber2 = random_int(1, 10);
		$this->rndOperator = random_int(0, 2);
		$this->calcResult();
	}

	/**
	 * Calculates the math question result
	 */
	private function calcResult() {
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
	public function addHoneypotField(string $formId, string $name, string $class, string $id, string $type = 'text', ?string $placeholder = null) {
		// Only add once
		if(in_array($formId, $this->registeredForms))
			return;

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
			if($this->currentForm !== $field->formId)
				continue;

			if($name === $field->name)
				return $field;
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
			if($this->currentForm !== $field->formId)
				continue;

			if(! isset($_POST[$field->name]))
				$_POST[$field->name] = '';

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
	public function getInnerMathLabelContent() {
		return sprintf('Berechne %s %s %s: (Anti-Spam)', $this->getRndNumber1(), $this->getOperator(), $this->getRndNumber2());
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
	public function outputMathField(string $name, string $id, bool $echo = true, ?string $class = null, ?string $placeholder = null) {
		$string = '<input type="text" id="' . $id . '" name="' . $name . '"' .
			($class ? ' class="' . $class . '"' : '') .
			($placeholder ? ' placeholder="' . $placeholder . '"' : '') .
			' autocomplete="off" required>' . PHP_EOL;

		if($echo)
			echo $string;
		return $string;
	}

	/**
	 * Checks if the Form has changed
	 *
	 * @param string $newFormId - ID of the <form> element
	 */
	public function checkForm(string $newFormId) {
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
	public function registerForm(string $formId) {
		if(! in_array($formId, $this->registeredForms))
			$this->registeredForms[] = $formId;
	}

	/**
	 * Saves this object to session
	 */
	public function saveSession() {
		$_SESSION['anti_spam'] = $this;
	}

	/**
	 * Deletes the session of Antispam
	 */
	public function deleteSession() {
		unset($_SESSION['anti_spam']);
	}

	/**
	 * Gets the Instance
	 *
	 * @param string $formId - Id of the Form
	 * @return self - Instance
	 */
	public static final function getInstance(string $formId): self {
		if(! isset($_SESSION['anti_spam']) || ! is_object($_SESSION['anti_spam'])) {
			self::$instance = new self($formId);
		}
		if(is_null(self::$instance)) {
			self::$instance = $_SESSION['anti_spam'];
		}

		// Check if form has changed
		self::$instance->checkForm($formId);

		return self::$instance;
	}
}
