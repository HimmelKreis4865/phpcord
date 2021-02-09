<?php

namespace phpcord\utils;

use function is_numeric;

class ArrayUtils {
	public static function filterNullRecursive(array $array): array {
		$new_ar = [];
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$new_ar[$key] = self::filterNullRecursive($value);
			} else if (!is_null($value)) $new_ar[$key] = $value;
		}
		return $new_ar;
	}

	public static function convertStringArray(array &$array) {
		foreach ($array as $key => $value) {
			if (is_numeric($value)) {
				if (floor($value) == $value) {
					$array[$key] = intval($value);
				} else {
					$array[$key] = floatval($value);
				}
			}
		}
	}
}


