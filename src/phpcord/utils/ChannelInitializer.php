<?php

namespace phpcord\utils;

use phpcord\channel\CategoryChannel;
use phpcord\channel\ChannelType;
use phpcord\channel\DMChannel;
use phpcord\channel\NewsChannel;
use phpcord\channel\TextChannel;
use phpcord\channel\VoiceChannel;
use phpcord\guild\GuildChannel;
use phpcord\guild\GuildPermissionMemberOverwrite;
use phpcord\guild\GuildPermissionOverwrite;
use phpcord\guild\GuildPermissionRoleOverwrite;
use phpcord\guild\IncompleteChannel;
use function array_map;
use function is_null;

class ChannelInitializer {
	
	/**
	 * Tries to create a GuildChannel instance
	 *
	 * @internal
	 *
	 * @param array $data
	 * @param string $guild_id
	 *
	 * @return GuildChannel|null
	 */
	public static function createChannel(array $data, string $guild_id): ?GuildChannel {
		$permissions = array_filter(array_map(function(array $keys) {
			$type = ($keys["type"] === "role" ? GuildPermissionOverwrite::TYPE_ROLE : ($keys["type"] === "member" ? GuildPermissionOverwrite::TYPE_MEMBER : $keys["type"]));
			if ($type !== GuildPermissionOverwrite::TYPE_ROLE and $type !== GuildPermissionOverwrite::TYPE_MEMBER) return null;
			$class = ($type === GuildPermissionOverwrite::TYPE_ROLE ? GuildPermissionRoleOverwrite::class : GuildPermissionMemberOverwrite::class);
			return new $class($keys["id"], new Permission(intval($keys["allow"]), intval($keys["deny"])));
		}, $data["permission_overwrites"] ?? []), function($key) {
			return !is_null($key);
		});
		$channel = null;
		switch ($data["type"] ?? ChannelType::TYPE_TEXT) {
			case ChannelType::TYPE_TEXT:
				$channel = new TextChannel($guild_id, $data["id"], $data["name"], $data["position"] ?? 0, $permissions, $data["nsfw"] ?? false, @$data["last_message_id"], @$data["topic"], @$data["parent_id"], $data["rate_limit_per_user"] ?? 0);
				break;
			case ChannelType::TYPE_NEWS:
				$channel = new NewsChannel($guild_id, $data["id"], $data["name"], $data["position"] ?? 0, $permissions, $data["nsfw"] ?? false, @$data["last_message_id"], @$data["topic"], @$data["parent_id"]);
				break;
			case ChannelType::TYPE_VOICE:
				$channel = new VoiceChannel($guild_id, $data["id"], $data["name"], $data["position"] ?? 0, $permissions, @$data["parent_id"], $data["user_limit"] ?? 0, $data["bitrate"] ?? VoiceChannel::DEFAULT_BITRATE);
				break;
			case ChannelType::TYPE_CATEGORY:
				$channel = new CategoryChannel($guild_id, $data["id"], $data["name"], $data["position"] ?? 0, $permissions);
				break;
		}
		return $channel;
	}
	/**
	 * Tries to create a IncompleteChannel instance
	 *
	 * @internal
	 *
	 * @param array $data
	 * @param string $guildId
	 *
	 * @return IncompleteChannel|null
	 */
	public static function createIncomplete(array $data, string $guildId): ?IncompleteChannel {
		return new IncompleteChannel($guildId, $data["id"], $data["name"], $data["type"] ?? ChannelType::TYPE_TEXT);
	}
	
	public static function createDMChannel(array $data): DMChannel {
		return new DMChannel($data["id"], array_map(function($key) {
			return MemberInitializer::createUser($key, "-");
		}, $data["recipients"] ?? []), @$data["last_message_id"]);
	}
}