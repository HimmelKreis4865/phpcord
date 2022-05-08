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

use phpcord\event\voice\VoiceStateUpdateEvent;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use phpcord\voice\VoiceRequestPool;
use phpcord\voice\VoiceState;

class VoiceHandler implements IntentHandler {
	
	public function handle(IntentMessageBuffer $buffer): void {
		switch ($buffer->name()) {
			case Intents::VOICE_STATE_UPDATE():
				if (!($state = VoiceState::fromArray($buffer->data()))) return;
				(new VoiceStateUpdateEvent($state))->call();
				$state->getGuild()->onVoiceStateUpdate($state);
				if ($state->getGuildId() and ($request = VoiceRequestPool::getInstance()->getRequest($state->getGuildId()))) {
					$request->sessionId = $state->getSessionId();
					$request->tryComplete();
				}
				break;
				
			case Intents::VOICE_SERVER_UPDATE():
				$d = $buffer->data();
				if (!isset($d['token']) or !isset($d['guild_id']) or !isset($d['endpoint'])) return;
				if ($request = VoiceRequestPool::getInstance()->getRequest($d['guild_id'])) {
					if (!$request->hasServerUpdate) {
						$request->hasServerUpdate = true;
						return;
					}
					$request->token = $d['token'];
					$request->endpoint = $d['endpoint'];
					$request->tryComplete();
				}
				break;
		}
	}
}