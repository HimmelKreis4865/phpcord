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

use phpcord\channel\ChannelTypes;
use phpcord\channel\GuildChannel;
use phpcord\event\channel\ChannelCreateEvent;
use phpcord\event\channel\ChannelDeleteEvent;
use phpcord\event\channel\ChannelUpdateEvent;
use phpcord\event\channel\TypingStartEvent;
use phpcord\guild\GuildMember;
use phpcord\intent\IntentHandler;
use phpcord\intent\Intents;
use phpcord\runtime\network\packet\IntentMessageBuffer;
use phpcord\utils\Timestamp;

class ChannelHandler implements IntentHandler {
	
	/**
	 * @param IntentMessageBuffer $buffer
	 *
	 * @return void
	 */
	public function handle(IntentMessageBuffer $buffer): void {
		switch ($buffer->name()) {
			case Intents::CHANNEL_CREATE():
				/** @var GuildChannel $channel */
				if (($channel = ChannelTypes::createObject($buffer->data()['type'], $buffer->data())) instanceof GuildChannel) {
					(new ChannelCreateEvent($channel))->call();
					$channel->getGuild()->getChannels()->set($channel->getId(), $channel);
				}
				break;
				
			case Intents::CHANNEL_UPDATE():
				/** @var GuildChannel $channel */
				if (($channel = ChannelTypes::createObject($buffer->data()['type'], $buffer->data())) instanceof GuildChannel) {
					(new ChannelUpdateEvent($channel))->call();
					$channel->getGuild()->getChannels()->get($channel->getId())->replaceBy($channel);
				}
				break;
			
			case Intents::CHANNEL_DELETE():
				/** @var GuildChannel $channel */
				if (($channel = ChannelTypes::createObject($buffer->data()['type'], $buffer->data())) instanceof GuildChannel) {
					(new ChannelDeleteEvent($channel))->call();
					$channel->getGuild()->getChannels()->unset($channel->getId());
				}
				break;
				
			case Intents::TYPING_START():
				$data = $buffer->data();
				$member = (@$data['member'] ? GuildMember::fromArray(($data['member'] + ['guild_id' => $data['guild_id']])) : null);
				(new TypingStartEvent(@$data['guild_id'], $data['channel_id'], $data['user_id'], Timestamp::fromTimestamp($data['timestamp']), $member))->call();
				break;
		}
	}
}