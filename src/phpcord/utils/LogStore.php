<?php

namespace phpcord\utils;

use phpcord\Discord;
use Threaded;
use function file_exists;
use function file_put_contents;
use function fopen;
use const FILE_APPEND;

final class LogStore extends Threaded {
	
	public static $logFile = null;
	
	public static function setLogFile(string $file) {
		if (!file_exists($file)) fopen($file, "w");
		self::$logFile = $file;
	}
	
	public static function addMessage(string $message) {
		if (self::$logFile === null) return;
		file_put_contents(self::$logFile, "\n" . $message, FILE_APPEND);
	}
}