<?php

namespace phpcord\utils;

use phpcord\guild\GuildMember;
use phpcord\user\User;
use function strval;

class MemberInitializer {
	/**
	 * Creates a User instance
	 *
	 * @internal
	 *
	 * @param array $data
	 * @param string $guild_id
	 *
	 * @return User
	 */
	public static function createUser(array $data, string $guild_id): ?User {
		if (!isset($data["id"]) or !isset($data["username"]) or !isset($data["discriminator"])) return null;
		return new User($guild_id, $data["id"], $data["username"], strval($data["discriminator"]), $data["public_flags"] ?? 0, @$data["avatar"], $data["bot"] ?? false);
	}
	
	/**
	 * Creates a Member instance
	 *
	 * @internal
	 *
	 * @param array $member
	 * @param string $guild_id
	 *
	 * @return GuildMember
	 */
	public static function createMember(array $member, string $guild_id): GuildMember {
		return new GuildMember($guild_id, $member["user"]["id"], $member["user"]["username"], strval($member["user"]["discriminator"]), $member["roles"] ?? [], $member["user"]["bot"] ?? false,  ($member["nick"] ?? $member["user"]["username"]), ["user"]["public_flags"] ?? 0, @$member["user"]["avatar"], $member["joined_at"] ?? "", @$member["premium_since"]);
	}
	
	public static function createMentioned(array $member, string $guild_id): GuildMember {
		return new GuildMember($guild_id, $member["id"], $member["username"], strval($member["discriminator"]), $member["member"]["roles"] ?? [], $member["bot"] ?? false,  ($member["member"]["nick"] ?? $member["username"]), $member["public_flags"] ?? 0, @$member["avatar"], $member["member"]["joined_at"], @$member["member"]["premium_since"]);
	}
}