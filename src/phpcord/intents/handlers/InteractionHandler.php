<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\http\RestAPIHandler;
use phpcord\utils\Interaction;
use function var_dump;

class InteractionHandler extends BaseIntentHandler {
	public function getIntents(): array {
		return ["INTERACTION_CREATE"];
	}
	
	public function handle(Discord $discord, string $intent, array $data) {
		$interaction = Interaction::fromArray($data);
		var_dump($interaction);
		// todo: RestAPIHandler::getInstance()->sendInteractionReply($interaction->getToken(), $interaction->getId(), ...);
	}
}