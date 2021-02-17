<?php

namespace phpcord\intents\handlers;

use phpcord\channel\ChannelType;
use phpcord\Discord;
use phpcord\event\channel\ChannelCreateEvent;
use phpcord\event\channel\ChannelDeleteEvent;
use phpcord\event\channel\ChannelPinsUpdateEvent;
use phpcord\event\channel\ChannelUpdateEvent;
use phpcord\event\channel\DMChannelReceiveEvent;
use phpcord\utils\ChannelInitializer;
use function in_array;
class ChannelHandler extends BaseIntentHandler {
	public function getIntents(): array {
		return ["CHANNEL_CREATE", "CHANNEL_UPDATE", "CHANNEL_DELETE", "CHANNEL_PINS_UPDATE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "CHANNEL_CREATE":
				if (in_array($data["type"] ?? 1, [ChannelType::TYPE_DM, ChannelType::TYPE_GROUP_DM])) {
					$channel = ChannelInitializer::createDMChannel($data);
					$discord->getClient()->addDMChannel($channel);
					(new DMChannelReceiveEvent($channel))->call();
					return;
				}
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				$discord->client->getGuild($channel->guild_id)->addChannel($channel);
				(new ChannelCreateEvent($channel))->call();
				break;

			case "CHANNEL_UPDATE":
				if (in_array($data["type"] ?? 1, [ChannelType::TYPE_DM, ChannelType::TYPE_GROUP_DM])) return;
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				$discord->client->getGuild($channel->guild_id)->updateChannel($channel);
				(new ChannelUpdateEvent($channel))->call();
				break;

			case "CHANNEL_DELETE":
				if (in_array($data["type"] ?? 1, [ChannelType::TYPE_DM, ChannelType::TYPE_GROUP_DM])) return;
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				$discord->client->getGuild($channel->guild_id)->removeChannel($channel);
				(new ChannelDeleteEvent($channel))->call();
				break;

			case "CHANNEL_PINS_UPDATE":
				if (in_array($data["type"] ?? 1, [ChannelType::TYPE_DM, ChannelType::TYPE_GROUP_DM])) return;
				(new ChannelPinsUpdateEvent($data["last_pin_timestamp"] ?? "", $data["channel_id"], $data["guild_id"]))->call();
				break;
		}
	}
}