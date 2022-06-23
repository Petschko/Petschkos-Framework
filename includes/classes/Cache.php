<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 11.07.2017
 * Time: 15:15
 *
 * Notes: Contains the Cache-Class
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class Cache
 */
class Cache {
	/**
	 * @var string $pageName - Name of the Page (Used as identifier)
	 */
	private string $pageName;

	/**
	 * @var string $filename - Filename
	 */
	private string $fileName;

	/**
	 * @var string $cacheDir - Cache Directory
	 */
	private string $cacheDir;

	/**
	 * @var string $originScript - Origin Script which creates the File
	 */
	private string $originScript;

	/**
	 * @var null|int $createdTimeStamp - Created on Timestamp | null if not exists
	 */
	private ?int $createdTimeStamp = null;

	/**
	 * @var int $lifeTimeSec - Seconds before creating a new Cache-File
	 */
	private int $lifeTimeSec = 86400;

	/**
	 * @var string $extension - Extension of the Cache-File
	 */
	private string $extension = 'php';

	/**
	 * Cache constructor.
	 *
	 * @param string $pageName - Name of the current Page (Used as identifier)
	 * @param string $cacheDir - Path to the Directory where Cache-Files are saved
	 * @param string $originScript - Origin Script which creates this File (Will just called if need to reconstructed)
	 * @param int|null $lifeTimeSec - How long can a Cache-File exists before it is created new or null to use the default value
	 * @param string|null $cacheExtension - Specify the Cache-Extension or null for default (PHP) without starting "."
	 * @throws Exception - Missing Required Values
	 */
	public function __construct(string $pageName, string $cacheDir, string $originScript, ?int $lifeTimeSec = null, ?string $cacheExtension = null) {
		if(! $pageName || ! $cacheDir || ! $originScript) {
			throw new Exception(__CLASS__ . '->' . __FUNCTION__ . ': Please fill out all Values for the Cache!');
		}

		// Set Values
		$this->setPageName(urlencode($pageName));
		$this->setCacheDir($cacheDir);
		$this->setOriginScript($originScript);

		if($lifeTimeSec) {
			$this->setLifeTimeSec($lifeTimeSec);
		}
		if($cacheExtension) {
			$this->setExtension($cacheExtension);
		}

		// Check if it's a dir
		if(! is_dir($this->getCacheDir())) {
			throw new Exception(__CLASS__ . '->' . __FUNCTION__ . ': ' . $this->getCacheDir() . ' is not a Directory!');
		}

		// Check if OriginFile exists
		if(! file_exists($this->getOriginScript())) {
			throw new Exception(__CLASS__ . '->' . __FUNCTION__ . ': Source-File "' . $this->getOriginScript() . '" doesn\'t exists.');
		}

		// Check if dir is Writable
		if(! is_writable($this->getCacheDir())) {
			error_log('Cache Directory "' . $this->getCacheDir() . '" is not Writable. Ignoring Cache...');
			return;
		}

		$this->runCache();
	}

	/**
	 * @return null|string
	 */
	private function getPageName(): ?string {
		return $this->pageName;
	}

	/**
	 * @param string|null $pageName
	 */
	private function setPageName(?string $pageName): void {
		$this->pageName = $pageName;
	}

	/**
	 * @return string
	 */
	private function getFileName(): string {
		return $this->fileName;
	}

	/**
	 * @param string $fileName
	 */
	private function setFileName(string $fileName): void {
		$this->fileName = $fileName;
	}

	/**
	 * @return string
	 */
	private function getCacheDir(): string {
		return $this->cacheDir;
	}

	/**
	 * @param string $cacheDir
	 */
	private function setCacheDir(string $cacheDir): void {
		$this->cacheDir = $cacheDir;
	}

	/**
	 * @return string
	 */
	private function getOriginScript(): string {
		return $this->originScript;
	}

	/**
	 * @param string $originScript
	 */
	private function setOriginScript(string $originScript): void {
		$this->originScript = $originScript;
	}

	/**
	 * @return int|null
	 */
	private function getCreatedTimeStamp(): ?int {
		return $this->createdTimeStamp;
	}

	/**
	 * @param int|null $createdTimeStamp
	 */
	private function setCreatedTimeStamp(?int $createdTimeStamp): void {
		$this->createdTimeStamp = $createdTimeStamp;
	}

	/**
	 * @return int
	 */
	private function getLifeTimeSec(): int {
		return $this->lifeTimeSec;
	}

	/**
	 * @param int $lifeTimeSec
	 */
	private function setLifeTimeSec(int $lifeTimeSec): void {
		$this->lifeTimeSec = $lifeTimeSec;
	}

	/**
	 * @return string
	 */
	private function getExtension(): string {
		return $this->extension;
	}

	/**
	 * @param string $extension
	 */
	private function setExtension(string $extension): void {
		$this->extension = $extension;
	}

	/**
	 * Runs the Cache
	 */
	private function runCache(): void {
		$fileFound = $this->findFile();

		if($fileFound === null) {
			$this->deleteFile();
		} else if($fileFound && $this->getCreatedTimeStamp() === null) {
			$this->setCreatedTimeStamp($this->detectFileTime());
		}

		$cacheDisplayed = $this->displayFile();

		if(! $cacheDisplayed) {
			$this->createFile();
		}
	}

	/**
	 * Searches for a Cache-File of the current Page
	 *
	 * @return bool|null - File Found or null if not readable
	 */
	private function findFile(): ?bool {
		if(! is_readable($this->getCacheDir())) {
			return null;
		}

		$files = [];
		$handle = opendir($this->getCacheDir());
		if($handle !== false) {
			while(($file = readdir($handle)) !== false) {
				if(mb_substr($file, 0, mb_strlen($this->getPageName())) === $this->getPageName()) {
					$files[] = $file;
				}
			}
			closedir($handle);

			// Handle multiple files
			if(count($files) === 1) {
				$this->setFileName($files[0]);

				return true;
			}
			if(count($files) > 1) {
				return $this->findCorrectFileFromFiles($files);
			}
		}

		return false;
	}

	/**
	 * Search the Correct file from the File-Array and assign it; It also assign the Time
	 *
	 * @param string[] $files - File-Array
	 * @return true - File found
	 */
	private function findCorrectFileFromFiles(array $files): bool {
		error_log(__CLASS__ . '->' . __FUNCTION__ . ': There are Multiple Cache-Files for this Page (' . $this->getPageName() . '). Please delete the old ones for faster load time of the Cache...');

		$times = [];
		foreach($files as $file) {
			$times[] = $this->detectFileTime($file);
		}

		rsort($times);

		$this->setFileName($this->getPageName() . '_' . $times[0] . '.' . $this->getExtension());
		$this->setCreatedTimeStamp($times[0]);

		// Delete Old files or at least try it
		$fileCount = count($files);
		$success = 0;

		error_log(__CLASS__ . '->' . __FUNCTION__ . ': Try to delete ' . ($fileCount - 1) . ' Files for you...');
		for($i = 1; $i < $fileCount; $i++) {
			if($this->deleteFile($this->getPageName() . '_' . $times[$i] . '.' . $this->getExtension())) {
				$success++;
			}
		}
		error_log(__CLASS__ . '->' . __FUNCTION__ . ': Deleted ' . $success . '/' . ($fileCount - 1) . ' old Files for you!');

		return true;
	}

	/**
	 * Detects the Creation Time from the File-Name
	 *
	 * @param string|null $filename - Filename to get the Timestamp or null for using class File-Name
	 * @return int - Time of the File-Name
	 */
	private function detectFileTime(?string $filename = null): int {
		$filename = $filename ?? $this->getFileName();

		$separatorPos = mb_stripos($filename, '_', mb_strlen($this->getPageName()));

		$time = mb_substr($filename, $separatorPos + 1);

		// Remove extension
		$time = mb_substr($time, 0, mb_stripos($time, '.'));

		return (int) $time;
	}

	/**
	 * Displays the Cache-File if it is valid
	 *
	 * @return bool - Cache-File has been loaded
	 */
	private function displayFile(): bool {
		if(! $this->getFileName())
			return false;

		$file = $this->getCacheDir() . $this->getFileName();
		if(file_exists($file)) {
			if(! is_readable($file) || ! is_file($file)) {
				return false;
			}

			if($this->getCreatedTimeStamp() + $this->getLifeTimeSec() < time()) {
				return false;
			}

			require_once($file);
			return true;
		}

		return false;
	}

	/**
	 * Creates a new Cache-File with the Origin Script
	 */
	private function createFile(): void {
		// Delete Old File
		$this->deleteFile();

		$time = time();
		$newFilename = $this->getPageName() . '_' . $time . '.' . $this->getExtension();

		// Create Cache-Content
		ob_start();
		require_once($this->getOriginScript());
		echo '<!-- PHP Cache-File from ' . date('Y-m-d H:i:s', $time) . ' -->';
		$htmlContent = ob_get_contents();
		ob_end_flush();

		// Check if new File-Name is free
		if(file_exists($this->getCacheDir() . $newFilename)) {
			return;
		}

		// Save to File
		file_put_contents($this->getCacheDir() . $newFilename, $htmlContent);
	}

	/**
	 * Safely Deletes a File
	 *
	 * @param string|null $filename - Specific file in the Cache to delete or null for class File-Name
	 * @return bool|null - False if the File was not deleted else true and null if file does not exists or no name is specified
	 */
	private function deleteFile(?string $filename = null): ?bool {
		$filename = $filename ?? $this->getFileName();

		if(! $filename) {
			return null;
		}

		$file = $this->getCacheDir() . $filename;

		if(file_exists($file)) {
			if(is_writable($file) && is_file($file)) {
				if(! unlink($file)) {
					error_log(__CLASS__ . '->' . __FUNCTION__ . ': Can\'t delete Cache-File "' . $file . '"...');

					return false;
				}

				return true;
			}
		}

		return null;
	}
}
