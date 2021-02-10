<?php

namespace phpcord\utils;

use function is_numeric;

class ArrayUtils {
	/**
	 * Filters null from an Array recursively
	 *
	 * @api
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function filterNullRecursive(array $array): array {
		$new_ar = [];
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$new_ar[$key] = self::filterNullRecursive($value);
			} else if (!is_null($value)) $new_ar[$key] = $value;
		}
		return $new_ar;
	}
	
	/**
	 * Converts an array with string keys to an array with int and float keys if there are numbers
	 *
	 * @api
	 *
	 * @param array $array
	 */
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