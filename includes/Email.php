<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 10.08.2015
 * Time: 22:45
 * Update: 23.06.2022
 * Version: 1.4.0 (PHP 7.4)
 * 1.4.0 (PHP 7.4)
 * 1.3.1 (Added missing documentation)
 * 1.3.0 (Replaced mail() with mb_send_mail() - Added var for language control)
 * 1.2.1 (Added class vars Doc)
 * 1.2.0 (Changed Class-Name & Website - Added alias functions for getSender and setSender)
 * 1.1.2 (Reformat Code)
 * 1.1.1 (Add CC, BCC, Max, Line Length & Reply-To Address)
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for sending easy Mails via PHP
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class Email
 */
class Email {
	/**
	 * Holds the Character Set of the E-Mail
	 *
	 * @var string - Character Set
	 */
	private string $charset;

	/**
	 * Holds the internal value for sending the mail views http://php.net/mb_language
	 * By default its unicode
	 *
	 * @var string - current Language
	 */
	private string $mailLang = 'uni';

	/**
	 * Holds the receiver Address(es) if none is give this will be null
	 *
	 * @var null|string[] - Receiver Address(es)
	 */
	private ?array $to = null;

	/**
	 * Holds the CC receiver Address(es) if none is give this will be null
	 *
	 * @var null|string[] - CC Receiver Address(es)
	 */
	private ?array $cc = null;

	/**
	 * Holds the BCC receiver Address(es) if none is give this will be null
	 *
	 * @var null|string[] - BCC Receiver Address(es)
	 */
	private ?array $bcc = null;

	/**
	 * Show how many Chars per line are allowed before line break
	 * null/0 mean there is no limit
	 *
	 * @var null|int - Characters per Line
	 */
	private ?int $maxLineLength = null;

	/**
	 * Contains the Sender-Address or null if none is given
	 *
	 * @var null|string - Sender-Address
	 */
	private ?string $sender = null;

	/**
	 * Contains the "Reply-To" Address or null if none is set
	 *
	 * @var null|string - Reply-To Address
	 */
	private ?string $replyTo = null;

	/**
	 * Contains the Subject or null if none is set
	 *
	 * @var null|string - Subject
	 */
	private ?string $subject = null;

	/**
	 * Contains the Content of the E-Mail or null if none is set
	 *
	 * @var null|string - E-Mail Content
	 */
	private ?string $msg = null;

	/**
	 * Creates a new instance
	 *
	 * @param string $charset - Encoding of this (Default: utf-8)
	 * @param string $lang - Language Value used for Sending & Encoding mail see http://php.net/mb_language for more information
	 */
	public function __construct(string $charset = 'utf-8', string $lang = 'uni') {
		$this->setMailLang($lang);
		$this->setCharset($charset);
	}

	/**
	 * Get the current Encoding of this
	 *
	 * @return string - Encoding of this
	 */
	public function getCharset(): string {
		return $this->charset;
	}

	/**
	 * Set the Encoding of this
	 *
	 * @param string $charset - New Encoding of this
	 */
	private function setCharset(string $charset): void {
		$this->charset = $charset;
	}

	/**
	 * Get the Mail-Language
	 *
	 * @return string - Mail-Language
	 */
	public function getMailLang(): string {
		return $this->mailLang;
	}

	/**
	 * Sets the Mail-Language if the Language is valid
	 *
	 * @param string $mailLang - Mail-Language
	 */
	public function setMailLang(string $mailLang): void {
		if(mb_language($mailLang)) {
			$this->mailLang = $mailLang;
		} else {
			// Reset after failed test
			mb_language($this->mailLang);
		}
	}

	/**
	 * Get the current receiver
	 *
	 * @return string[]|null - The receiver or null if none is set
	 */
	private function getTo(): ?array {
		return $this->to;
	}

	/**
	 * Returns a List of all "To"-E-Mail Addresses as string
	 *
	 * @return string - E-Mail list of all "To" Receiver
	 */
	public function getToList(): string {
		return $this->createEMailList($this->getTo());
	}

	/**
	 * Set the Receiver
	 *
	 * @param string[]|null $to - Receiver
	 */
	private function setTo(?array $to): void {
		$this->to = $to;
	}

	/**
	 * Get the current CC(s)
	 *
	 * @return string[]|null - The current Copy-To or null if none is set
	 */
	private function getCc(): ?array {
		return $this->cc;
	}

	/**
	 * Returns a List of all "CC"-E-Mail Addresses as string
	 *
	 * @return string - E-Mail list of all "CC" Receiver
	 */
	public function getCcList(): string {
		return $this->createEMailList($this->getCc());
	}

	/**
	 * Set CC
	 *
	 * @param string[]|null $cc - CC(s) null if unset
	 */
	private function setCc(?array $cc): void {
		$this->cc = $cc;
	}

	/**
	 * Get the current BCC(s)
	 *
	 * @return string[]|null - The current Blind-Copy-To or null if none is set
	 */
	private function getBcc(): ?array {
		return $this->bcc;
	}

	/**
	 * Returns a List of all "BCC"-E-Mail Addresses as string
	 *
	 * @return string - E-Mail list of all "BCC" Receiver
	 */
	public function getBccList(): string {
		return $this->createEMailList($this->getBcc());
	}

	/**
	 * Set the current BCC(s)
	 *
	 * @param string[]|null $bcc - BCC(s) null if unset
	 */
	private function setBcc(?array $bcc): void {
		$this->bcc = $bcc;
	}

	/**
	 * Get the sender
	 *
	 * @return string - Sender-Address
	 */
	public function getSender(): string {
		return $this->sender;
	}

	/**
	 * Alias for getSender
	 *
	 * @return string - Sender-Address
	 */
	public function getFrom(): string {
		return $this->getSender();
	}

	/**
	 * Set the sender
	 *
	 * @param string $sender - Sender-Address
	 */
	public function setSender(string $sender): void {
		self::checkValidEMail($sender);

		$this->sender = $sender;
	}

	/**
	 * Alias for setSender
	 *
	 * @param string $from - Sender Address
	 */
	public function setFrom(string $from): void {
		$this->setSender($from);
	}

	/**
	 * Returns the Reply-To Address
	 *
	 * @return null|string - Reply-To Address or null if none is set
	 */
	public function getReplyTo(): ?string {
		return $this->replyTo;
	}

	/**
	 * Sets the Reply-To Address
	 *
	 * @param string|null $replyTo - Reply-To or null if none is set
	 */
	public function setReplyTo(?string $replyTo): void {
		self::checkValidEMail($replyTo);

		$this->replyTo = $replyTo;
	}

	/**
	 * Get the subject
	 *
	 * @return string - subject
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * Set the subject
	 *
	 * @param string $subject - The subject
	 */
	public function setSubject(string $subject): void {
		$this->subject = $subject;
	}

	/**
	 * Get the Mail-Message
	 *
	 * @return string - Message
	 */
	public function getMsg(): string {
		return $this->msg;
	}

	/**
	 * Set the Mail-Message
	 *
	 * @param string $msg - Message
	 */
	public function setMsg(string $msg): void {
		$this->msg = $msg;
	}

	/**
	 * Returns the max Chars that can be in each line
	 *
	 * @return null|int - Characters per line or null/0 if none limit is set
	 */
	public function getMaxLineLength(): ?int {
		return $this->maxLineLength;
	}

	/**
	 * Set the max Chars that can be in each line
	 *
	 * @param int|null $maxLineLength - Characters per Line or null/0 if no limit
	 */
	public function setMaxLineLength(?int $maxLineLength): void {
		$this->maxLineLength = $maxLineLength;
	}

	/**
	 * Adds an E-Mail Address to the receiver
	 *
	 * @param string $to - The E-Mail Address that you want to add
	 */
	public function addTo(string $to): void {
		self::checkValidEMail($to);

		$this->to[] = $to;
	}

	/**
	 * Removes a Receiver from the to array
	 *
	 * @param string $to - The E-Mail Address that you want to remove
	 */
	public function removeTo(string $to): void {
		$this->setTo(self::removeFromArray($to, $this->getTo()));
	}

	/**
	 * Adds an E-Mail Address to CC
	 *
	 * @param string $cc - The E-Mail Address that you want to add
	 */
	public function addCc(string $cc): void {
		self::checkValidEMail($cc);

		$this->cc[] = $cc;
	}

	/**
	 * Removes an E-Mail Address from the CC array
	 *
	 * @param string $cc - The E-Mail Address that you want to remove
	 */
	public function removeCc(string $cc): void {
		$this->setCc(self::removeFromArray($cc, $this->getCc()));
	}

	/**
	 * Adds an E-Mail Address to BCC
	 *
	 * @param string $bcc - The E-Mail Address that you want to add
	 */
	public function addBcc(string $bcc): void {
		self::checkValidEMail($bcc);

		$this->bcc[] = $bcc;
	}

	/**
	 * Removes an E-Mail Address from the BCC array
	 *
	 * @param string $bcc - The E-Mail Address that you want to remove
	 */
	public function removeBcc(string $bcc): void {
		$this->setBcc(self::removeFromArray($bcc, $this->getBcc()));
	}

	/**
	 * Send the Mail as HTML-Mail
	 *
	 * @return bool - true on success | false on error
	 */
	public function sendHTML(): bool {
		if(! $this->getSender() || ! $this->getTo() || ! $this->getSubject() || ! $this->getMsg()) {
			return false;
		}

		// Header
		$header = 'MIME-Version: 1.0' . PHP_EOL;
		$header .= 'Content-type: text/html; charset=' . $this->getCharset() . PHP_EOL;
		$header .= 'From: ' . $this->getSender() . PHP_EOL;
		if($this->getCc()) {
			$header .= 'Cc: ' . $this->getCcList() . PHP_EOL;
		}
		if($this->getBcc()) {
			$header .= 'Bcc: ' . $this->getBccList() . PHP_EOL;
		}
		if($this->getReplyTo()) {
			$header .= 'Reply-To: ' . $this->getReplyTo() . PHP_EOL;
		}
		$header .= 'X-Mailer: PHP/' . PHP_VERSION . PHP_EOL;

		$this->makeLineBreaksHTML();

		return mail(
			$this->getToList(),
			'=?' . mb_strtolower($this->getCharset()) . '?B?' . base64_encode($this->getSubject()) . '?=',
			$this->getMsg(),
			$header
		);
	}

	/**
	 * Send the Mail
	 *
	 * @return bool - true on success | false on error
	 */
	public function send(): bool {
		if(! $this->getSender() || ! $this->getTo() || ! $this->getSubject() || ! $this->getMsg()) {
			return false;
		}

		// Header
		$header = 'Content-type: text/plain; charset=' . $this->getCharset() . PHP_EOL;
		$header .= 'From: ' . $this->getSender() . PHP_EOL;
		if($this->getCc()) {
			$header .= 'Cc: ' . $this->getCcList() . PHP_EOL;
		}
		if($this->getBcc()) {
			$header .= 'Bcc: ' . $this->getBccList() . PHP_EOL;
		}
		if($this->getReplyTo()) {
			$header .= 'Reply-To: ' . $this->getReplyTo() . PHP_EOL;
		}
		$header .= 'X-Mailer: PHP/' . PHP_VERSION . PHP_EOL;

		$this->makeLineBreaks();

		return mail($this->getToList(),
			'=?' . mb_strtolower($this->getCharset()) . '?B?' . base64_encode($this->getSubject()) . '?=',
			$this->getMsg(),
			$header
		);
	}

	/**
	 * Replaces Line-Breaks with HTML Line-Breaks
	 */
	private function makeLineBreaksHTML(): void {
		$this->setMsg(str_replace([PHP_EOL, '\r\n'], '<br />', $this->getMsg()));

		if($this->getMaxLineLength()) {
			$this->setMsg(wordwrap($this->getMsg(), $this->getMaxLineLength(), '<br />' . PHP_EOL));
		}
	}

	/**
	 * Replaces HTML-Line-Breaks with normal Line-Breaks
	 */
	private function makeLineBreaks(): void {
		if($this->getMaxLineLength()) {
			$this->setMsg(wordwrap($this->getMsg(), $this->getMaxLineLength(), PHP_EOL));
		}

		$this->setMsg(str_replace('<br />', PHP_EOL, str_replace('<br>', PHP_EOL, $this->getMsg())));
	}

	/**
	 * Creates an String of the E-Mail-Array
	 *
	 * @param string[] $emailArray - Array with E-Mail Addresses that you want to convert into a String
	 * @return string - E-Mail-String
	 */
	private function createEMailList(array $emailArray): string {
		return implode(', ', $emailArray);
	}

	/**
	 * Removes a value from an array
	 *
	 * @param mixed $value - The value that you want to remove from the array
	 * @param array $array - The array from that the value should be removed
	 * @return array - The new array without the searched value
	 */
	private static function removeFromArray($value, array $array): array {
		$tmpNew = [];
		$i = 0;

		// Process for each array item. Construct new array without the searched value
		foreach($array as $itemValue) {
			if($value != $itemValue) {
				$tmpNew[$i] = $itemValue;
				$i++;
			}
		}

		return $tmpNew;
	}

	/**
	 * Check if the E-Mail Address is valid
	 *
	 * @param string $email - E-Mail Address to check
	 */
	private static function checkValidEMail(string $email): void {
		try {
			// Check E-Mail pattern
			$atPos = mb_strpos($email, '@');
			$lastPointPos = mb_strrpos($email, '.');

			if(! $atPos || ! $lastPointPos) {
				throw new Exception('E-Mail-Address "' . htmlspecialchars($email) . '" is invalid!');
			}

			if(! ($atPos > 0 && $lastPointPos > ($atPos + 1) && mb_strlen($email) > ($lastPointPos + 1))) {
				throw new Exception('E-Mail-Address "' . htmlspecialchars($email) . '" is invalid!');
			}
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
}
