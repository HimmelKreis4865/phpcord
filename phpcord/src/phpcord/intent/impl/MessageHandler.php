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

use phpcord\channel\Channel;
use phpcord\channel\TextChannel;
use phpcord\event\message\MessageDeleteEvent;
use phpcord\event\message\MessageSendEvent;
use phpcord\event\message\MessageEditEvent;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\message\Message;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use phpcord\utils\Utils;

class MessageHandler implements IntentHandler {
	
	public function handle(IntentMessageBuffer $buffer): void {
		switch ($buffer->name()) {
			case Intents::MESSAGE_CREATE():
				if ($message = Message::fromArray($buffer->data())) (new MessageSendEvent($message))->call();
				break;

			case Intents::MESSAGE_UPDATE():
				if ($message = Message::fromArray($buffer->data())) (new MessageEditEvent($message))->call();
				break;

			case Intents::MESSAGE_DELETE():
				if (Utils::contains(($d = $buffer->data()), 'id', 'channel_id')) {
					$event = new MessageDeleteEvent(@$d['guild_id'], $d['channel_id'], $d['id']);
					$event->getChannel()->then(fn(?TextChannel $channel) => $channel?->onMessageDelete($d['id']));
					$event->call();
				}
				break;
		}
	}
}