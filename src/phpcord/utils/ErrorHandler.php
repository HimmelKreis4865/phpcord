<?php

namespace phpcord\utils;

use ErrorException;
use InvalidArgumentException;
use phpcord\Discord;
use ReflectionClass;
use Throwable;
use function array_map;
use function count;
use function error_reporting;
use function gettype;
use function implode;
use function ini_set;
use function is_array;
use function is_bool;
use function is_object;
use function is_string;
use function set_error_handler;
use function spl_object_id;
use function str_replace;
use function strlen;
use function substr;
use function var_dump;
use const E_ERROR;
use const E_NOTICE;
use const E_PARSE;
use const E_WARNING;

final class ErrorHandler {
	
	public static function init(): void {
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		ini_set("error_reporting", E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		ini_set("display_errors", true);
		set_error_handler([self::class, "error"]);
		//set_exception_handler([self::class, "exception"]);
	}
	
	/**
	 * @param int $error_level
	 * @param string $error_message
	 * @param string $error_file
	 * @param int $error_line
	 *
	 * @throws ErrorException
	 */
	public static function error(int $error_level, string $error_message, string $error_file, int $error_line) {
		var_dump("error $error_level $error_message " . error_reporting());
		if (($error_level & error_reporting()) !== 0) {
			throw new ErrorException($error_message, 0, $error_level, $error_file, $error_line);
		}
	}
	
	public static function exception(Throwable $throwable) {
		var_dump("received throwable");
		MainLogger::logEmergency("Uncaught " . (new ReflectionClass($throwable))->getShortName() . ": " . $throwable->getMessage() . " in " . str_replace(Discord::PATH, "", $throwable->getFile()) . ":" . $throwable->getLine());
		foreach (self::printableTrace($throwable->getTrace()) as $line) {
			MainLogger::logError($line);
		}
	}
	
	/**
	 * @param mixed[][] $trace
	 * @phpstan-param list<array<string, mixed>> $trace
	 * @param int $maxStringLength
	 *
	 * @return string[]
	 */
	public static function printableTrace(array $trace, int $maxStringLength = 80) : array{
		$messages = [];
		for($i = 0; isset($trace[$i]); ++$i){
			$params = "";
			if(isset($trace[$i]["args"]) or isset($trace[$i]["params"])){
				if(isset($trace[$i]["args"])){
					$args = $trace[$i]["args"];
				}else{
					$args = $trace[$i]["params"];
				}
				
				$params = implode(", ", array_map(function($value) use($maxStringLength) : string{
					if(is_object($value)){
						return "object " . Utils::getNiceClassName($value) . "#" . spl_object_id($value);
					}
					if(is_array($value)){
						return "array[" . count($value) . "]";
					}
					if(is_string($value)){
						return "string[" . strlen($value) . "] " . substr(Utils::printable($value), 0, $maxStringLength);
					}
					if(is_bool($value)){
						return $value ? "true" : "false";
					}
					return gettype($value) . " " . Utils::printable((string) $value);
				}, $args));
			}
			$messages[] = "#$i " . (isset($trace[$i]["file"]) ? Utils::cleanPath($trace[$i]["file"]) : "") . "(" . (isset($trace[$i]["line"]) ? $trace[$i]["line"] : "") . "): " . (isset($trace[$i]["class"]) ? $trace[$i]["class"] . (($trace[$i]["type"] === "dynamic" or $trace[$i]["type"] === "->") ? "->" : "::") : "") . $trace[$i]["function"] . "(" . Utils::printable($params) . ")";
		}
		return $messages;
	}
	
	
}