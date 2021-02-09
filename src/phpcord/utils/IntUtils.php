<?php

namespace phpcord\utils;

final class IntUtils {
	public static function isInRange(int $number, int $min, int $max): bool {
		return ($min <= $number and $number <= $max);
	}
}


