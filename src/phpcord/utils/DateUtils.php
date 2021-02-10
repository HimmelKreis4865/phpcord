<?php

namespace phpcord\utils;

use function array_reverse;
use function explode;
use function is_numeric;

class DateUtils {
	public static function convertTimeToSeconds(string $time): int {
		$seconds = 0;
		$units = explode(":", $time);
		$units = array_reverse($units);
		foreach ($units as $key => $unit) {
			if (!is_numeric($unit)) continue;
			$seconds += self::convertStepToSeconds($key, intval($unit));
		}
		return $seconds;
	}
	
	protected static function convertStepToSeconds(int $step, int $number): int {
		$multiplicators = [
			0 => 1,
			1 => 60,
			2 => 60 * 60,
			3 => 24 * 60 * 60,
			4 => 30 * 24 * 60 * 60
		];
		if (isset($multiplicators[$step])) $number *= $multiplicators[$step];
		return $number;
	}
}