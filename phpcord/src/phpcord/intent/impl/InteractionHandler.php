<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\intent\impl;

use phpcord\intent\IntentHandler;
use phpcord\event\interaction\InteractionEvent;
use phpcord\interaction\Interaction;
use phpcord\runtime\network\packet\IntentMessageBuffer;

class InteractionHandler implements IntentHandler {
	
	public function handle(IntentMessageBuffer $buffer): void {
		(new InteractionEvent(($interaction = Interaction::fromArray($buffer->data()))))->call();
		
		$interaction->getData()::handle($interaction);
	}
}