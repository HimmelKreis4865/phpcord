<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\member\MemberAddEvent;
use phpcord\event\member\MemberTypingStartEvent;
use phpcord\event\member\MemberUpdateEvent;
use phpcord\event\user\MemberRemoveEvent;
use phpcord\utils\MemberInitializer;

class MemberHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["GUILD_MEMBER_ADD", "GUILD_MEMBER_UPDATE", "GUILD_MEMBER_REMOVE", "TYPING_START"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "GUILD_MEMBER_ADD":
				$member = MemberInitializer::createMember($data, $data["guild_id"]);
				(new MemberAddEvent($member))->call();
				$discord->client->getGuild($member->getGuildId())->addMember($member);
				break;

			case "GUILD_MEMBER_UPDATE":
				$member = MemberInitializer::createMember($data, $data["guild_id"]);
				(new MemberUpdateEvent($member))->call();
				$discord->client->getGuild($member->getGuildId())->updateMember($member);
				break;

			case "GUILD_MEMBER_REMOVE":
				$member = MemberInitializer::createUser($data["user"], $data["guild_id"]);
				(new MemberRemoveEvent($member))->call();
				$discord->client->getGuild($member->getGuildId())->removeMember($member);
				break;

			case "TYPING_START":
				(new MemberTypingStartEvent($data["user_id"], $data["timestamp"], $data["channel_id"]))->call();
				break;
		}
	}
}