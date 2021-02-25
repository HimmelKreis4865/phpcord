<?php

namespace phpcord\utils;

use phpcord\Discord;
use function file_exists;
use function file_put_contents;
use function fopen;
use const FILE_APPEND;

final class LogStore {
	
	public const CACHE_MESSAGE_LIMIT = 1;
	
	private static $logFile = null;
	
	private static $messageCache = [];
	
	public function __destruct() {
		self::saveLog();
	}
	
	public static function setLogFile(string $file) {
		if (!file_exists($file)) fopen($file, "w");
		self::$logFile = $file;
	}
	
	public static function addMessage(string $message) {
		if (!(Discord::getInstance()->options["save-log"] ?? false)) return;
		self::$messageCache[] = $message;
		if (count(self::$messageCache) >= self::CACHE_MESSAGE_LIMIT) self::saveLog();
	}
	
	public static function saveLog() {
		if (self::$logFile !== null) file_put_contents(self::$logFile, "\n" . implode("\n", self::$messageCache), FILE_APPEND);
		self::$messageCache = [];
	}
}