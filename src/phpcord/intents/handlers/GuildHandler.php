<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\user\UserBanEvent;
use phpcord\event\user\UserUnbanEvent;
use phpcord\guild\GuildBanEntry;
use phpcord\utils\MemberInitializer;
use function var_dump;

class GuildHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["GUILD_BAN_ADD", "GUILD_BAN_REMOVE"];
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
		}
	}
}


