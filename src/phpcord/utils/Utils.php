<?php

namespace phpcord\utils;

use const DIRECTORY_SEPARATOR;

class Utils {
	
	public static function addSeparator(string $string): string {
		if ($string[(strlen($string) - 1)] !== DIRECTORY_SEPARATOR) return $string . DIRECTORY_SEPARATOR;
		return $string;
	}
}