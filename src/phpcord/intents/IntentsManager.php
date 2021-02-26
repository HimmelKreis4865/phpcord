<?php

namespace phpcord\intents;

use ReflectionClass;
use function array_values;
use function in_array;
use function array_filter;
use function array_map;
use function is_numeric;

class IntentsManager implements IntentList {
	/**
	 * Return an Array of all listed intents
	 *
	 * @api
	 *
	 * @return int[]
	 */
	public static function getAllIntents(): array {
		return array_values((new ReflectionClass(__CLASS__))->getConstants());
	}

	/**
	 * Returns the sum of all intents "added", can be used to listen for all events
	 *
	 * @api
	 *
	 * @return int
	 */
	public static function allIntentsSum(): int {
		$sum = 0;
		foreach (array_map(function($key) {
			return intval($key);
		}, array_filter(self::getAllIntents(), function($key) {
			return is_numeric($key);
		})) as $intent) {
			$sum |= $intent;
		}
		return $sum;
	}
	
	/**
	 * Returns whether an intent is a valid one
	 *
	 * @api
	 *
	 * @param string $intent
	 *
	 * @return bool
	 */
	public static function isValidIntent(string $intent): bool {
		return in_array($intent, self::INTENTS);
	}
}