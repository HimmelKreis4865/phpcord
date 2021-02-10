<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;

abstract class BaseIntentHandler {
	/**
	 * @return string[]
	 */
	abstract public function getIntents(): array;
	
	/**
	 * Handles one of the registered intetnt
	 *
	 * @api
	 *
	 * @param Discord $discord
	 * @param string $intent
	 * @param array $data
	 *
	 * @return mixed
	 */
	abstract public function handle(Discord $discord, string $intent, array $data);
}