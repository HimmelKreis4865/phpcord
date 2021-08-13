<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\member\InteractionCreateEvent;
use phpcord\interaction\Interaction;
use function array_keys;
use function var_dump;

class InteractionHandler extends BaseIntentHandler {
	public function getIntents(): array {
		return ["INTERACTION_CREATE"];
	}
	
	public function handle(Discord $discord, string $intent, array $data) {
		$interaction = Interaction::fromArray($data);
		
		(new InteractionCreateEvent($interaction))->call();
		
		if (!$interaction->fromDm()) $discord->getClient()->getGuild($interaction->guildId)?->getCommandMap()->executeHandler($interaction);
		$discord->getClient()->getCommandMap()->executeHandler($interaction);
		
		// todo: RestAPIHandler::getInstance()->sendInteractionReply($interaction->getToken(), $interaction->getId(), ...);
	}
}