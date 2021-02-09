<?php

namespace phpcord\intents;

class IntentsManager implements IntentList {
	/**
	 * Return an Array of all listed intents
	 *
	 * @api
	 *
	 * @return int[]
	 */
	public static function getAllIntents(): array {
		return array_values((new \ReflectionClass(__CLASS__))->getConstants());
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
		foreach (self::getAllIntents() as $intent) {
			$sum |= $intent;
		}
		return $sum;
	}

	public static function isValidIntent(string $intent): bool {
		return in_array($intent, self::INTENTS);
	}
}


