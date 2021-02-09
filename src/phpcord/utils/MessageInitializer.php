<?php

namespace phpcord\utils;

use phpcord\guild\GuildDeletedMessage;
use phpcord\guild\GuildMessage;
use phpcord\guild\GuildReceivedEmbed;
use phpcord\guild\GuildUpdatedMessage;
use phpcord\guild\store\GuildStoredMessage;
use function array_merge;
use function var_dump;

class MessageInitializer {
	public static function create(array $data): GuildMessage {
		return new GuildMessage($data["guild_id"], $data["id"], $data["channel_id"], $data["content"], MemberInitializer::createMember(array_merge(["user" => $data["author"]], $data["member"]), $data["guild_id"]), (isset($data["embed"]) ? self::initReceiveEmbed($data["embed"]) : null), $data["timestamp"] ?? null, $data["tts"] ?? false, $data["pinned"] ?? false, @$data["referenced_message"], $data["attachments"] ?? [], @$data["edited_timestamp"], $data["type"] ?? 0, $data["flags"] ?? 0, $data["mention_everyone"] ?? false, $data["mentions"] ?? [], $data["mention_roles"] ?? [], $data["reactions"] ?? []);
	}

	public static function createDeleted(array $data): GuildDeletedMessage {
		return new GuildDeletedMessage($data["guild_id"], $data["id"], $data["channel_id"]);
	}

	public static function initReceiveEmbed(array $data): GuildReceivedEmbed {
		return new GuildReceivedEmbed($data["guild_id"], $data["id"], @$data["title"], $data["fields"] ?? [], @$data["description"], @$data["url"], @$data["thumbnail"], $data["color"] ?? 0, @$data["timestamp"], @$data["footer"], @$data["image"], @$data["video"], @$data["provider"], @$data["author"]);
	}

	public static function createUpdated(array $data): GuildUpdatedMessage {
		return new GuildUpdatedMessage($data["guild_id"], $data["id"], $data["channel_id"], (isset($data["embed"]) ? self::initReceiveEmbed($data["embed"]) : null));
	}

	public static function fromStore(string $guildId, array $data): GuildStoredMessage {
		return new GuildStoredMessage($guildId, $data["id"], $data["channel_id"], $data["content"], MemberInitializer::createUser($data["author"], $guildId), (isset($data["embed"]) ? self::initReceiveEmbed($data["embed"]) : null), $data["timestamp"] ?? null, $data["tts"] ?? false, $data["pinned"] ?? false, @$data["referenced_message"], $data["attachments"] ?? [], @$data["edited_timestamp"], $data["type"] ?? 0, $data["flags"] ?? 0, $data["mention_everyone"] ?? false, $data["mentions"] ?? [], $data["mention_roles"] ?? []);
	}
}


