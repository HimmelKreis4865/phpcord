<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\channel\ChannelCreateEvent;
use phpcord\event\channel\ChannelDeleteEvent;
use phpcord\event\channel\ChannelPinsUpdateEvent;
use phpcord\event\channel\ChannelUpdateEvent;
use phpcord\utils\ChannelInitializer;
use function var_dump;

class ChannelHandler extends BaseIntentHandler {
	public function getIntents(): array {
		return ["CHANNEL_CREATE", "CHANNEL_UPDATE", "CHANNEL_DELETE", "CHANNEL_PINS_UPDATE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "CHANNEL_CREATE":
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				var_dump("adding channel to guild");
				$discord->client->getGuild($channel->guild_id)->addChannel($channel);
				(new ChannelCreateEvent($channel))->call();
				break;

			case "CHANNEL_UPDATE":
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				$discord->client->getGuild($channel->guild_id)->updateChannel($channel);
				(new ChannelUpdateEvent($channel))->call();
				break;

			case "CHANNEL_DELETE":
				$channel = ChannelInitializer::createChannel($data, $data["guild_id"]);
				$discord->client->getGuild($channel->guild_id)->removeChannel($channel);
				(new ChannelDeleteEvent($channel))->call();
				break;

			case "CHANNEL_PINS_UPDATE":
				(new ChannelPinsUpdateEvent($data["last_pin_timestamp"] ?? "", $data["channel_id"], $data["guild_id"]))->call();
				break;
		}
	}
}


