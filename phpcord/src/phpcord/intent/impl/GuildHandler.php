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

use phpcord\Discord;
use phpcord\event\guild\EmojisUpdateEvent;
use phpcord\event\guild\GuildCreateEvent;
use phpcord\event\guild\GuildDeleteEvent;
use phpcord\guild\Guild;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use phpcord\utils\Collection;
use phpcord\utils\Factory;

class GuildHandler implements IntentHandler {
	
	/**
	 * @param IntentMessageBuffer $buffer
	 *
	 * @return void
	 */
	public function handle(IntentMessageBuffer $buffer): void {
		switch ($buffer->name()) {
			case Intents::GUILD_CREATE():
				$guild = Guild::fromArray($buffer->data());
				Discord::getInstance()->getClient()->getGuilds()->set($guild->getId(), $guild);
				(new GuildCreateEvent($guild))->call();
				break;
				
			case Intents::GUILD_DELETE():
				$guild = Discord::getInstance()->getClient()->getGuilds()->get($id = $buffer->data()['id']);
				(new GuildDeleteEvent($guild, $id))->call();
				Discord::getInstance()->getClient()->getGuilds()->unset($id);
				break;
				
			case Intents::GUILD_EMOJIS_UPDATE():
				$emojis = new Collection(Factory::createEmojiArray($id = ($buffer->data()['guild_id']), $buffer->data()['emojis']));
				($event = new EmojisUpdateEvent(Discord::getInstance()->getClient()->getGuilds()->get($id), $emojis))->call();
				$event->getGuild()->getEmojis()->clear();
				$event->getGuild()->getEmojis()->fill($emojis->asArray());
				break;
		}
	}
}