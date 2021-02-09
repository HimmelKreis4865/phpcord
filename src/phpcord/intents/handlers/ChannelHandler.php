<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\channel\ChannelCreateEvent;
use phpcord\event\channel\ChannelDeleteEvent;
use phpcord\event\channel\ChannelPinsUpdateEvent;
use phpcord\event\channel\ChannelUpdateEvent;
use phpcord\utils\ChannelInitializer;

class ChannelHandler extends BaseIntentHandler {
	public function getIntents(): array {
		return ["CHANNEL_CREATE", "CHANNEL_UPDATE", "CHANNEL_DELETE", "CHANNEL_PINS_UPDATE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "CHANNEL_CREATE":
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				(new ChannelCreateEvent($channel))->call();
				$discord->client->getGuild($channel->guild_id)->addChannel($channel);
				break;

			case "CHANNEL_UPDATE":
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				(new ChannelUpdateEvent($channel))->call();
				$discord->client->getGuild($channel->guild_id)->updateChannel($channel);
				break;

			case "CHANNEL_DELETE":
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				(new ChannelDeleteEvent($channel))->call();
				$discord->client->getGuild($channel->guild_id)->removeChannel($channel);
				break;

			case "CHANNEL_PINS_UPDATE":
				(new ChannelPinsUpdateEvent($data["last_pin_timestamp"] ?? "", $data["channel_id"], $data["guild_id"]))->call();
				break;
		}
	}
}


