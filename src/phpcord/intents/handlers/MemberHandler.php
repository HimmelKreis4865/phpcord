<?php

namespace phpcord\intents\handlers;

use phpcord\Discord;
use phpcord\event\member\MemberAddEvent;
use phpcord\event\member\MemberTypingStartEvent;
use phpcord\event\member\MemberUpdateEvent;
use phpcord\event\user\MemberRemoveEvent;
use phpcord\event\user\PresenceUpdateEvent;
use phpcord\guild\Guild;
use phpcord\utils\MemberInitializer;

class MemberHandler extends BaseIntentHandler {

	public function getIntents(): array {
		return ["GUILD_MEMBER_ADD", "GUILD_MEMBER_UPDATE", "GUILD_MEMBER_REMOVE", "TYPING_START", "PRESENCE_UPDATE"];
	}

	public function handle(Discord $discord, string $intent, array $data) {
		switch ($intent) {
			case "GUILD_MEMBER_ADD":
				$member = MemberInitializer::createMember($data, $data["guild_id"]);
				(new MemberAddEvent($member))->call();
				$guild = $discord->client->getGuild($member->getGuildId());
				if (!$guild instanceof Guild) return;
				$guild->addMember($member);
				$guild->member_count++;
				break;

			case "GUILD_MEMBER_UPDATE":
				$member = MemberInitializer::createMember($data, $data["guild_id"]);
				(new MemberUpdateEvent($member))->call();
				$guild = $discord->client->getGuild($member->getGuildId());
				if (!$guild instanceof Guild) return;
				$guild->updateMember($member);
				break;

			case "GUILD_MEMBER_REMOVE":
				$member = MemberInitializer::createUser($data["user"], $data["guild_id"]);
				(new MemberRemoveEvent($member))->call();
				$guild = $discord->client->getGuild($member->getGuildId());
				if (!$guild instanceof Guild) return;
				$guild->removeMember($member);
				
				$guild->member_count--;
				break;

			case "TYPING_START":
				(new MemberTypingStartEvent($data["user_id"], $data["timestamp"], $data["channel_id"]))->call();
				break;
			
			case "PRESENCE_UPDATE":
				(new PresenceUpdateEvent(@$data["user"]["id"], $data["status"] ?? "", $data["activities"] ?? []))->call();
				break;
		}
	}
}