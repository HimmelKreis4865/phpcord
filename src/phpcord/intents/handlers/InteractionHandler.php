<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use function var_dump;

class InteractionHandler extends BaseIntentHandler {
	public function getIntents(): array {
		return ["INTERACTION_CREATE"];
	}
	
	public function handle(Discord $discord, string $intent, array $data) {
		var_dump($data);
	}
}