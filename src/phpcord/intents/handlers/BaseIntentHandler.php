<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;

abstract class BaseIntentHandler {
	/**
	 * @return string[]
	 */
	abstract public function getIntents(): array;

	abstract public function handle(Discord $discord, string $intent, array $data);
}



