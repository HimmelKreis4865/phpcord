<?php

namespace phpcord\intents\handlers;

use phpcord\client\Client;
use phpcord\Discord;
use phpcord\event\client\ClientReadyEvent;
use phpcord\event\guild\GuildCreateEvent;
use phpcord\event\guild\GuildDeleteEvent;
use phpcord\event\guild\GuildEnterEvent;
use phpcord\event\guild\GuildUpdateEvent;
use phpcord\event\user\UserBanEvent;
use phpcord\event\user\UserUnbanEvent;
use phpcord\guild\GuildBanEntry;
use phpcord\utils\ClientInitializer;
use phpcord\utils\MemberInitializer;

class GuildHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["GUILD_BAN_ADD", "GUILD_BAN_REMOVE", "GUILD_DELETE", "GUILD_UPDATE", "GUILD_CREATE", "READY"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "GUILD_BAN_ADD":
				$user = MemberInitializer::createUser($data["user"], $data["guild_id"]);
				(new UserBanEvent($user))->call();
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
				
			case "GUILD_CREATE":
				$client = Discord::getInstance()->getClient() ?? new Client();
				$guildId = ClientInitializer::create($client, $data);
				Discord::getInstance()->client = $client;
				(new GuildCreateEvent($client->getGuild($guildId)))->call();
				
				// WORKAROUND, todo: improve this
				// checks whether the guild was created due to join on a new guild / initialisation on startup
				if ($client->getPing() > -1) {
					(new GuildEnterEvent($client->getGuild($guildId)))->call();
				}
				break;
				
			case "READY":
				Discord::getInstance()->getClient()->user = ClientInitializer::createBotUser($data);
				(new ClientReadyEvent(Discord::getInstance()->getClient()))->call();
				break;
		}
	}
}