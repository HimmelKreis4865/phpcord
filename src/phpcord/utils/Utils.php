<?php

namespace phpcord\utils;

use ReflectionClass;
use function gettype;
use function is_string;
use function preg_replace;
use function str_replace;
use const DIRECTORY_SEPARATOR;

class Utils {
	
	public static function addSeparator(string $string): string {
		if ($string[(strlen($string) - 1)] !== DIRECTORY_SEPARATOR) return $string . DIRECTORY_SEPARATOR;
		return $string;
	}
	
	/**
	 * Returns a readable identifier for the class of the given object. Sanitizes class names for anonymous classes.
	 *
	 * @param object $obj
	 *
	 * @return string
	 */
	public static function getNiceClassName(object $obj) : string{
		$reflect = new ReflectionClass($obj);
		if($reflect->isAnonymous()){
			$filename = $reflect->getFileName();
			
			return "anonymous@" . ($filename !== false ?
					self::cleanPath($filename) . "#L" . $reflect->getStartLine() :
					"internal"
				);
		}
		
		return $reflect->getName();
	}
	
	public static function cleanPath(string $path): string {
		return str_replace([DIRECTORY_SEPARATOR, ".php", "phar://"], ["/", "", ""], $path);
	}
	
	/**
	 * Returns a string that can be printed, replaces non-printable characters
	 *
	 * @param mixed $str
	 *
	 * @return string
	 */
	public static function printable($str) : string{
		if(!is_string($str)){
			return gettype($str);
		}
		
		return preg_replace('#([^\x20-\x7E])#', '.', $str);
	}
}