<?php

namespace phpcord\utils;

final class IntUtils {
	/**
	 * Returns whether a number is in range of min and max
	 *
	 * @api
	 *
	 * @param int $number
	 * @param int $min
	 * @param int $max
	 *
	 * @return bool
	 */
	public static function isInRange(int $number, int $min, int $max): bool {
		return ($min <= $number and $number <= $max);
	}
}