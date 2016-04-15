<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 10.08.2015
 * Time: 12:27
 * Update: 12.04.2016
 * Version: 1.3.0 (Replaced mail() with mb_send_mail() - Added var for language control)
 * 1.2.1 (Added class vars Doc)
 * 1.2.0 (Changed Class-Name & Website - Added alias functions for getSender and setSender)
 * 1.1.2 (Reformat Code)
 * 1.1.1 (Add CC, BCC, Max, Line Length & Reply-To Address)
 *
 * Notes: Class for sending easy Mails via PHP
 */

/**
 * Class Email
 */
class Email {
	/**
	 * Holds the Character Set of the E-Mail
	 *
	 * @var string - Character Set
	 */
	private $charset;

	/**
	 * Holds the internal value for sending the mail views http://php.net/mb_language
	 * By default its unicode
	 *
	 * @var string - current Language
	 */
	private $mailLang = 'uni';

	/**
	 * Holds the receiver Address(es) if none is give this will be false
	 *
	 * @var bool|array - Receiver Address(es)
	 */
	private $to = false;

	/**
	 * Holds the CC receiver Address(es) if none is give this will be false
	 *
	 * @var bool|array - CC Receiver Address(es)
	 */
	private $cc = false;

	/**
	 * Holds the BCC receiver Address(es) if none is give this will be false
	 *
	 * @var bool|array - BCC Receiver Address(es)
	 */
	private $bcc = false;

	/**
	 * Show how many Chars per line are allowed before line break
	 * false mean there is no limit
	 *
	 * @var bool|int - Characters per Line
	 */
	private $maxLineLength = false;

	/**
	 * Contains the Sender-Address or false if none is given
	 *
	 * @var bool|string - Sender-Address
	 */
	private $sender = false;

	/**
	 * Contains the "Reply-To" Address or false if none is set
	 *
	 * @var bool|string - Reply-To Address
	 */
	private $replyTo = false;

	/**
	 * Contains the Subject or false if none is set
	 *
	 * @var bool|string - Subject
	 */
	private $subject = false;

	/**
	 * Contains the Content of the E-Mail or false if none is set
	 *
	 * @var bool|string - E-Mail Content
	 */
	private $msg = false;

	/**
	 * Creates a new instance
	 *
	 * @param string $charset - Encoding of this (Default: utf-8)
	 * @param string $lang
	 */
	public function __construct($charset = 'utf-8', $lang = 'uni') {
		$this->setMailLang($lang);
		$this->setCharset($charset);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->charset);
		unset($this->to);
		unset($this->cc);
		unset($this->bcc);
		unset($this->maxLineLength);
		unset($this->sender);
		unset($this->replyTo);
		unset($this->subject);
		unset($this->msg);
	}

	/**
	 * Get the current Encoding of this
	 *
	 * @return string - Encoding of this
	 */
	public function getCharset() {
		return $this->charset;
	}

	/**
	 * Set the Encoding of this
	 *
	 * @param string $charset - New Encoding of this
	 */
	private function setCharset($charset) {
		$this->charset = $charset;
	}

	/**
	 * Get the Mail-Language
	 *
	 * @return string - Mail-Language
	 */
	public function getMailLang() {
		return $this->mailLang;
	}

	/**
	 * Sets the Mail-Language if the Language is valid
	 *
	 * @param string $mailLang - Mail-Language
	 */
	public function setMailLang($mailLang) {
		if(mb_language($mailLang))
			$this->mailLang = $mailLang;
		else
			mb_language($this->mailLang); // Reset after failed test
	}

	/**
	 * Get the current receiver
	 *
	 * @return array|bool - The receiver or false if none is set
	 */
	private function getTo() {
		return $this->to;
	}

	/**
	 * Returns a List of all "To"-E-Mail Addresses as string
	 *
	 * @return string - E-Mail list of all "To" Receiver
	 */
	public function getToList() {
		return $this->createEMailList($this->getTo());
	}

	/**
	 * Set the Receiver
	 *
	 * @param array|bool $to - Receiver
	 */
	private function setTo($to) {
		$this->to = $to;
	}

	/**
	 * Get the the current CC(s)
	 *
	 * @return array|bool - The current Copy-To or false if none is set
	 */
	private function getCc() {
		return $this->cc;
	}

	/**
	 * Returns a List of all "CC"-E-Mail Addresses as string
	 *
	 * @return string - E-Mail list of all "CC" Receiver
	 */
	public function getCcList() {
		return $this->createEMailList($this->getCc());
	}

	/**
	 * Set CC
	 *
	 * @param array|bool $cc - CC(s) false if unset
	 */
	private function setCc($cc) {
		$this->cc = $cc;
	}

	/**
	 * Get the current BCC(s)
	 *
	 * @return array|bool - The current Blind-Copy-To or false if none is set
	 */
	private function getBcc() {
		return $this->bcc;
	}

	/**
	 * Returns a List of all "BCC"-E-Mail Addresses as string
	 *
	 * @return string - E-Mail list of all "BCC" Receiver
	 */
	public function getBccList() {
		return $this->createEMailList($this->getBcc());
	}

	/**
	 * Set the current BCC(s)
	 *
	 * @param array|bool $bcc - BCC(s) false if unset
	 */
	private function setBcc($bcc) {
		$this->bcc = $bcc;
	}

	/**
	 * Get the sender
	 *
	 * @return string - Sender-Address
	 */
	public function getSender() {
		return $this->sender;
	}

	/**
	 * Alias for getSender
	 *
	 * @return string - Sender-Address
	 */
	public function getFrom() {
		return $this->getSender();
	}

	/**
	 * Set the sender
	 *
	 * @param string $sender - Sender-Address
	 */
	public function setSender($sender) {
		self::checkValidEMail($sender);

		$this->sender = $sender;
	}

	/**
	 * Alias for setSender
	 *
	 * @param string $from - Sender Address
	 */
	public function setFrom($from) {
		$this->setSender($from);
	}

	/**
	 * Returns the Reply-To Address
	 *
	 * @return bool|string - Reply-To Address or false if none is set
	 */
	public function getReplyTo() {
		return $this->replyTo;
	}

	/**
	 * Sets the Reply-To Address
	 *
	 * @param bool|string $replyTo - Reply-To or false if none is set
	 */
	public function setReplyTo($replyTo) {
		self::checkValidEMail($replyTo);

		$this->replyTo = $replyTo;
	}

	/**
	 * Get the subject
	 *
	 * @return string - subject
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set the subject
	 *
	 * @param string $subject - The subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Get the Mail-Message
	 *
	 * @return string - Message
	 */
	public function getMsg() {
		return $this->msg;
	}

	/**
	 * Set the Mail-Message
	 *
	 * @param string $msg - Message
	 */
	public function setMsg($msg) {
		$this->msg = $msg;
	}

	/**
	 * Returns the max Chars that can be in each line
	 *
	 * @return bool|int - Characters per line or false if none limit is set
	 */
	public function getMaxLineLength() {
		return $this->maxLineLength;
	}

	/**
	 * Set the max Chars that can be in each line
	 *
	 * @param bool|int $maxLineLength - Characters per Line or false if no limit
	 */
	public function setMaxLineLength($maxLineLength) {
		$this->maxLineLength = $maxLineLength;
	}

	/**
	 * Adds an E-Mail Address to the receiver
	 *
	 * @param string $to - The E-Mail Address that you want to add
	 */
	public function addTo($to) {
		self::checkValidEMail($to);

		$this->to[] = $to;
	}

	/**
	 * Removes a Receiver from the to array
	 *
	 * @param string $to - The E-Mail Address that you want to remove
	 */
	public function removeTo($to) {
		$this->setTo(self::removeFromArray($to, $this->getTo()));
	}

	/**
	 * Adds an E-Mail Address to CC
	 *
	 * @param string $cc - The E-Mail Address that you want to add
	 */
	public function addCc($cc) {
		self::checkValidEMail($cc);

		$this->cc[] = $cc;
	}

	/**
	 * Removes an E-Mail Address from the CC array
	 *
	 * @param string $cc - The E-Mail Address that you want to remove
	 */
	public function removeCc($cc) {
		$this->setCc(self::removeFromArray($cc, $this->getCc()));
	}

	/**
	 * Adds an E-Mail Address to BCC
	 *
	 * @param string $bcc - The E-Mail Address that you want to add
	 */
	public function addBcc($bcc) {
		self::checkValidEMail($bcc);

		$this->bcc[] = $bcc;
	}

	/**
	 * Removes an E-Mail Address from the BCC array
	 *
	 * @param string $bcc - The E-Mail Address that you want to remove
	 */
	public function removeBcc($bcc) {
		$this->setBcc(self::removeFromArray($bcc, $this->getBcc()));
	}

	/**
	 * Send the Mail as HTML-Mail
	 *
	 * @return bool - true on success | false on error
	 */
	public function sendHTML() {
		if(! $this->getSender() || ! $this->getTo() || ! $this->getSubject() || ! $this->getMsg())
			return false;

		// Header
		$header = 'MIME-Version: 1.0' . PHP_EOL;
		$header .= 'Content-type: text/html; charset=' . $this->getCharset() . PHP_EOL;
		$header .= 'From: ' . $this->getSender() . PHP_EOL;
		if($this->getCc())
			$header .= 'Cc: ' . $this->getCcList() . PHP_EOL;
		if($this->getBcc())
			$header .= 'Bcc: ' . $this->getBccList() . PHP_EOL;
		if($this->getReplyTo())
			$header .= 'Reply-To: ' . $this->getReplyTo() . PHP_EOL;
		$header .= 'X-Mailer: PHP/' . phpversion() . PHP_EOL;

		$this->makeLineBreaksHTML();
		mb_language($this->getMailLang());

		return mb_send_mail($this->getToList(), mb_encode_mimeheader($this->getSubject(), $this->getCharset()), $this->getMsg(), $header);
	}

	/**
	 * Send the Mail
	 *
	 * @return bool - true on success | false on error
	 */
	public function send() {
		if(! $this->getSender() || ! $this->getTo() || ! $this->getSubject() || ! $this->getMsg())
			return false;

		// Header
		$header = 'Content-type: text/plain; charset=' . $this->getCharset() . PHP_EOL;
		$header .= 'From: ' . $this->getSender() . PHP_EOL;
		if($this->getCc())
			$header .= 'Cc: ' . $this->getCcList() . PHP_EOL;
		if($this->getBcc())
			$header .= 'Bcc: ' . $this->getBccList() . PHP_EOL;
		if($this->getReplyTo())
			$header .= 'Reply-To: ' . $this->getReplyTo() . PHP_EOL;
		$header .= 'X-Mailer: PHP/' . phpversion() . PHP_EOL;

		$this->makeLineBreaks();
		mb_language($this->getMailLang());

		return mb_send_mail($this->getToList(), mb_encode_mimeheader($this->getSubject(), $this->getCharset()), $this->getMsg(), $header);
	}

	/**
	 * Replaces Line-Breaks with HTML Line-Breaks
	 */
	private function makeLineBreaksHTML() {
		$this->setMsg(str_replace(array(PHP_EOL, '\r\n'), '<br />', $this->getMsg()));

		if($this->getMaxLineLength())
			$this->setMsg(wordwrap($this->getMsg(), $this->getMaxLineLength(), '<br />' . PHP_EOL));
	}

	/**
	 * Replaces HTML-Line-Breaks with normal Line-Breaks
	 */
	private function makeLineBreaks() {
		if($this->getMaxLineLength())
			$this->setMsg(wordwrap($this->getMsg(), $this->getMaxLineLength(), PHP_EOL));

		$this->setMsg(str_replace('<br />', PHP_EOL, str_replace('<br>', PHP_EOL, $this->getMsg())));
	}

	/**
	 * Creates an String of the E-Mail-Array
	 *
	 * @param array $emailArray - Array with E-Mail Addresses that you want to convert into a String
	 * @return string - E-Mail-String
	 */
	private function createEMailList($emailArray) {
		return implode(', ', $emailArray);
	}

	/**
	 * Removes a value from an array
	 *
	 * @param mixed $value - The value that you want to remove from the array
	 * @param array $array - The array from that the value should be removed
	 * @return array - The new array without the searched value
	 */
	private static function removeFromArray($value, $array) {
		$tmpNew = array();
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
	private static function checkValidEMail($email) {
		try {
			// Check E-Mail pattern
			$atPos = mb_strpos($email, '@');
			$lastPointPos = mb_strrpos($email, '.');

			if(! $atPos || ! $lastPointPos)
				throw new Exception('E-Mail-Address "' . $email . '" is invalid!');

			if(! ($atPos > 0 && $lastPointPos > ($atPos + 1) && mb_strlen($email) > ($lastPointPos + 1)))
				throw new Exception('E-Mail-Address "' . $email . '" is invalid!');
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
}
