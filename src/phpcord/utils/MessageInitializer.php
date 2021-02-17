<?php

namespace phpcord\utils;

use phpcord\guild\GuildDeletedMessage;
use phpcord\guild\GuildMessage;
use phpcord\guild\GuildReceivedEmbed;
use phpcord\guild\GuildUpdatedMessage;
use phpcord\guild\store\GuildStoredMessage;
use function array_merge;
use function is_null;

class MessageInitializer {
	/**
	 * Creates a normal message
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return GuildMessage
	 */
	public static function create(array $data): GuildMessage {
		$reference = null;
		if (!is_null(@$data["referenced_message"])) {
			$reference = self::fromStore($data["guild_id"] ?? "-", $data["referenced_message"]);
		}
		return new GuildMessage($data["guild_id"] ?? "-", $data["id"], $data["channel_id"], $data["content"], MemberInitializer::createMember(array_merge(["user" => $data["author"]], $data["member"] ?? []), $data["guild_id"] ?? "-"), (isset($data["embed"]) ? self::initReceiveEmbed($data["embed"]) : null), $data["timestamp"] ?? null, $data["tts"] ?? false, $data["pinned"] ?? false, $reference, $data["attachments"] ?? [], @$data["edited_timestamp"], $data["type"] ?? 0, $data["flags"] ?? 0, $data["mention_everyone"] ?? false, $data["mentions"] ?? [], $data["mention_roles"] ?? [], $data["reactions"] ?? []);
	}
	
	/**
	 * Creates a deleted message (it only contains GuildID, ID and ChannelID)
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return GuildDeletedMessage
	 */
	public static function createDeleted(array $data): GuildDeletedMessage {
		return new GuildDeletedMessage($data["guild_id"] ?? "-", $data["id"], $data["channel_id"]);
	}
	
	/**
	 * Creates an Embed that you're receiving when fetching messages or in MessageUpdateEvent / MessageCreateEvent
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return GuildReceivedEmbed
	 */
	public static function initReceiveEmbed(array $data): GuildReceivedEmbed {
		return new GuildReceivedEmbed($data["guild_id"] ?? "-", $data["id"], @$data["title"], $data["fields"] ?? [], @$data["description"], @$data["url"], @$data["thumbnail"], $data["color"] ?? 0, @$data["timestamp"], @$data["footer"], @$data["image"], @$data["video"], @$data["provider"], @$data["author"]);
	}
	
	/**
	 * Creates an updated message that includes less content
	 *
	 * @todo add more information here
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return GuildUpdatedMessage
	 */
	public static function createUpdated(array $data): GuildUpdatedMessage {
		return new GuildUpdatedMessage($data["guild_id"] ?? "-", $data["id"], $data["channel_id"], (isset($data["embed"]) ? self::initReceiveEmbed($data["embed"]) : null));
	}
	
	/**
	 * Creates a message that was stored (includes other & less information)
	 *
	 * @internal
	 *
	 * @param string $guildId
	 * @param array $data
	 *
	 * @return GuildStoredMessage
	 */
	public static function fromStore(string $guildId, array $data): GuildStoredMessage {
		return new GuildStoredMessage($guildId, $data["id"], $data["channel_id"], $data["content"], MemberInitializer::createUser($data["author"], $guildId), (isset($data["embed"]) ? self::initReceiveEmbed($data["embed"]) : null), $data["timestamp"] ?? null, $data["tts"] ?? false, $data["pinned"] ?? false, @$data["referenced_message"], $data["attachments"] ?? [], @$data["edited_timestamp"], $data["type"] ?? 0, $data["flags"] ?? 0, $data["mention_everyone"] ?? false, $data["mentions"] ?? [], $data["mention_roles"] ?? []);
	}
}