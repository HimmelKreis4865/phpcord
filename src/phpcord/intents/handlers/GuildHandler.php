<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\guild\GuildDeleteEvent;
use phpcord\event\guild\GuildUpdateEvent;
use phpcord\event\user\UserBanEvent;
use phpcord\event\user\UserUnbanEvent;
use phpcord\guild\GuildBanEntry;
use phpcord\utils\ClientInitializer;
use phpcord\utils\MemberInitializer;
use function var_dump;

class GuildHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["GUILD_BAN_ADD", "GUILD_BAN_REMOVE", "GUILD_DELETE", "GUILD_UPDATE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "GUILD_BAN_ADD":
				$user = MemberInitializer::createUser($data["user"], $data["guild_id"]);
				(new UserBanEvent($user))->call();
				var_dump($data);
				$discord->getClient()->getGuild($user->getGuildId())->getBanList()->addBan(new GuildBanEntry($user, ""));
				break;

			case "GUILD_BAN_REMOVE":
				$user = MemberInitializer::createUser($data["user"], $data["guild_id"]);
				(new UserUnbanEvent($user))->call();
				$discord->getClient()->getGuild($user->getGuildId())->getBanList()->removeBan($user->getId());
				break;
				
			case "GUILD_UPDATE":
				$client = clone Discord::getInstance()->getClient();
				$guildId = ClientInitializer::create($client, $data);
				Discord::getInstance()->client = $client;
				
				(new GuildUpdateEvent($client->getGuild($guildId)))->call();
				break;
				
			case "GUILD_DELETE":
				(new GuildDeleteEvent($data["id"], !isset($data["unavailable"])))->call();
				break;
		}
	}
}