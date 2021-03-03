<?php

namespace phpcord\utils;

use phpcord\guild\Emoji;
use phpcord\guild\FollowWebhook;
use phpcord\guild\GuildBanEntry;
use phpcord\guild\GuildBanList;
use phpcord\guild\GuildEmoji;
use phpcord\guild\GuildInvite;
use phpcord\guild\GuildRole;
use phpcord\guild\GuildWelcomeScreen;
use phpcord\guild\GuildWelcomeScreenField;
use phpcord\guild\VoiceStateData;
use phpcord\guild\Webhook;
use function intval;
use function is_array;

class GuildSettingsInitializer {
	/**
	 * Creates a GuildBanList from data
	 *
	 * @internal
	 *
	 * @param string $guildId
	 * @param array $data
	 *
	 * @return GuildBanList
	 */
	public static function createBanList(string $guildId, array $data): GuildBanList {
		$entries = [];
		foreach ($data as $ban) {
			$user = MemberInitializer::createUser($ban["user"], $guildId);
			$entries[$user->getId()] = new GuildBanEntry($user, $ban["reason"]);
		}
		return new GuildBanList($entries);
	}
	
	/**
	 * Creates a webhook from data
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return Webhook|null
	 */
	public static function initWebhook(array $data): ?Webhook {
		$user = null;
		if (isset($data["user"]) and is_array($data["user"])) $user = MemberInitializer::createUser($data["user"], $data["guild_id"]);
		if (@$data["type"] === 2) return new FollowWebhook($data["guild_id"], $data["id"], $data["channel_id"], $data["source_guild"]["id"] ?? "", $data["source_guild"]["name"] ?? "", $data["source_guild"]["icon"] ?? "", $data["source_channel"]["id"] ?? "", $data["source_channel"]["name"] ?? "", @$data["name"], @$data["avatar"], @$data["token"], @$data["application_id"], $user);
		
		return new Webhook($data["guild_id"], $data["id"], $data["channel_id"], @$data["name"], @$data["avatar"], @$data["token"], @$data["application_id"], $user);
	}
	/**
	 * Creates a GuildInvite from data
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return GuildInvite
	 */
	public static function createInvitation(array $data): GuildInvite {
		$guild = null;
		if (isset($data["guild"])) $guild = ClientInitializer::createIncompleteGuild($data["guild"]);
		
		$inviter = null;
		if (isset($data["inviter"])) $inviter = MemberInitializer::createUser($data["inviter"], ($guild === null ? "0" : $guild->getId()));
		
		$target = null;
		if (isset($data["target_user"])) $target = MemberInitializer::createUser($data["target_user"], ($guild === null ? "0" : $guild->getId()));
		
		$channel = null;
		if (isset($data["channel"])) $channel = ChannelInitializer::createIncomplete($data["channel"], ($guild === null ? "0" : $guild->getId()));
		
		$metadata = [];
		if (isset($data["uses"]) and isset($data["max_uses"]) and isset($data["temporary"]) and isset($data["created_at"]) and isset($data["max_age"])) {
			$metadata = [
				"uses" => $data["uses"],
				"max_uses" => $data["max_uses"],
				"temporary" => $data["temporary"],
				"created_uses" => $data["created_at"],
				"duration" => $data["max_age"]
			];
		}
		
		return new GuildInvite($data["code"], $guild, $channel, $inviter, $target, $data["target_user_type"] ?? 0, @$data["approximate_presence_count"], @$data["approximate_member_count"], $metadata);
	}
	
	/**
	 * Initialises a GuildRole from data
	 *
	 * @internal
	 *
	 * @param string $guildId
	 * @param array $data
	 *
	 * @return GuildRole
	 */
	public static function initRole(string $guildId, array $data): GuildRole {
		return new GuildRole($guildId, $data["name"], $data["id"], intval($data["position"] ?? 0), $data["permissions"] ?? [], $data["color"] ?? 0, $data["mentionable"] ?? false, $data["managed"] ?? false);
	}
	
	public static function initWelcomeScreen(array $data): GuildWelcomeScreen {
		$fields = [];
		foreach ($data["welcome_channels"] ?? [] as $channel) {
			$fields[] = new GuildWelcomeScreenField($channel["channel_id"], $channel["description"], (isset($channel["emoji_name"]) ? new Emoji($channel["emoji_name"], @$channel["emoji_id"]) : null));
		}
		return new GuildWelcomeScreen($data["description"], $fields);
	}
	
	public static function createGuildEmoji(array $data, string $guildId): GuildEmoji {
		return new GuildEmoji($guildId, $data["name"], $data["id"], MemberInitializer::createUser($data["user"] ?? [], $guildId), $data["require_colons"] ?? true, $data["managed"] ?? true, $data["animated"] ?? false, $data["available"] ?? true);
	}
	
	public static function createVoiceState(array $data): VoiceStateData {
		return new VoiceStateData($data["user_id"], $data["self_mute"] ?? false, $data["self_deaf"] ?? false, $data["mute"] ?? false, $data["deaf"] ?? false, $data["self_stream"] ?? false, $data["self_video"] ?? false);
	}
}