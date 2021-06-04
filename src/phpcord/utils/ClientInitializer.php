<?php

namespace phpcord\utils;

use phpcord\client\Application;
use phpcord\client\BotUser;
use phpcord\client\Client;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\guild\GuildChannel;
use phpcord\guild\GuildRole;
use phpcord\guild\IncompleteGuild;
use function array_map;
use function is_null;
use function var_dump;

class ClientInitializer {
	/**
	 * ClientInitializer constructor.
	 *
	 * Initialises a new guild to the client
	 *
	 * @internal
	 *
	 * @param Client $client
	 * @param array $data
	 *
	 * @return string the guild id
	 */
	public static function create(Client &$client, array $data): string {
		var_dump("were obv creating");
		$roles = [];
		$members = [];
		$channels = [];

		$guild_id = $data["id"];
		
		var_dump(1);
		if (CacheLevels::canCache(CacheLevels::TYPE_ROLES)) {
			foreach ($data["roles"] ?? [] as $role) {
				$roles[strval($role["id"])] = new GuildRole($guild_id, $role["name"], $role["id"], intval($role["position"] ?? 0), $role["permissions"] ?? [], $role["color"] ?? 0, $role["mentionable"] ?? false, $role["managed"] ?? false);
			}
		}
		var_dump(2);
		if (CacheLevels::canCache(CacheLevels::TYPE_MEMBERS)) {
			foreach ($data["members"] ?? [] as $member) {
				$members[strval($member["user"]["id"])] = MemberInitializer::createMember($member, $guild_id);
			}
		}
		var_dump(3);
		if (CacheLevels::canCache(CacheLevels::TYPE_CHANNEL)) {
			foreach ($data["channels"] ?? [] as $channel) {
				$channel = ChannelInitializer::createChannel($channel, $guild_id);
				if ($channel instanceof GuildChannel) $channels[$channel->id] = $channel;
			}
		}
		var_dump(4);
		$screen = null;
		if (isset($data["welcome_screen"]) and !is_null($data["welcome_screen"])) $screen = GuildSettingsInitializer::initWelcomeScreen($data["welcome_screen"]);
		
		var_dump(5);
		$emojis = array_map(function($data) use ($guild_id) {
			return GuildSettingsInitializer::createGuildEmoji($data, $guild_id);
		}, $data["emojis"] ?? []);
		var_dump(6);
		$client->guilds[$guild_id] = new Guild(
			$data["name"], $data["id"], $data["owner_id"], $data["icon"], @$data["banner"], @$data["afk_channel_id"],
			@$data["rules_channel_id"], $channels, $members, $roles, $data["description"] ?? "", intval($data["member_count"] ?? 2),
			@$data["preferred_locale"], @$data["region"], intval($data["default_message_notifications"]), intval($data["verification_level"]),
			intval($data["max_members"]), $emojis, @$data["vanity_url_code"], @$data["system_channel_id"],
			@$data["public_updates_channel_id"], intval($data["premium_subscription_count"] ?? 0), $data["features"] ?? [], $screen, $data["premium_tier"] ?? 0
		);
		var_dump(7);
		return $guild_id;
	}
	
	/**
	 * Creates an incomplete Guild used for invitations
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return IncompleteGuild
	 */
	public static function createIncompleteGuild(array $data): IncompleteGuild {
		return new IncompleteGuild($data["id"], $data["name"], @$data["splash"], @$data["banner"], @$data["description"], @$data["icon"], $data["features"] ?? [], $data["verification_level"] ?? 0, @$data["vanity_url"]);
	}
	
	/**
	 * Creates the BotUser (your bot)
	 *
	 * @internal
	 *
	 * @param array $data
	 *
	 * @return BotUser|null
	 */
	public static function createBotUser(array $data): ?BotUser {
		if (!isset($data["application"]) or !isset($data["user"])) return null;
		return new BotUser("-", $data["user"]["id"], $data["user"]["username"], $data["user"]["discriminator"], $data["user"]["flags"] ?? 0, @$data["avatar"], $data["version"] ?? Discord::VERSION, $data["user_settings"] ?? [], $data["user"]["verified"] ?? false, $data["user"]["mfa_enabled"] ?? false, @$data["user"]["email"], @$data["session_id"], $data["relationships"] ?? [], $data["private_channels"] ?? [], array_filter(array_map(function($key) {
			return @$key["id"];
		}, $data["guilds"] ?? []), function($key) {
			return !is_null($key);
		}), $data["guild_join_requests"] ?? [], $data["geo_ordered_rtc_regions"] ?? [], new Application($data["application"]["id"], $data["application"]["flags"] ?? 0));
	}
}