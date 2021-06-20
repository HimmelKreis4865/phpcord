<?php

namespace phpcord\utils;

use phpcord\Discord;
use ReflectionClass;
use Throwable;
use function error_reporting;
use function in_array;
use function ini_set;
use function set_error_handler;
use function set_exception_handler;
use function str_replace;
use function strtolower;
use function substr;
use const E_ERROR;

final class ErrorHandler {
	
	public static function init(): void {
		error_reporting(E_ERROR);
		set_error_handler([self::class, "error"]);
		set_exception_handler([self::class, "exception"]);
	}
	
	public static function error(int $error_level, string $error_message, string $error_file, int $error_line) {
		if ($error_level > 4) return;
		MainLogger::logError((strtolower(substr($error_message, 0, 2)) === "a " ? "" : (strtolower(substr($error_message, 0, 2)) === "an " ? "" : (in_array(strtolower($error_message[0]), ["a", "e", "i", "o", "u"]) ? "n " : " "))) . $error_message . " in $error_file:$error_line");
		exit();
	}
	
	public static function exception(Throwable $throwable) {
		MainLogger::logEmergency("Uncaught " . (new ReflectionClass($throwable))->getShortName() . ": " . $throwable->getMessage() . " in " . str_replace(Discord::PATH, "", $throwable->getFile()) . ":" . $throwable->getLine());
		exit();
	}
}