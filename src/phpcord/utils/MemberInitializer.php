<?php

namespace phpcord\utils;

use phpcord\guild\GuildMember;
use phpcord\user\User;

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
	public static function createUser(array $data, string $guild_id): User {
		return new User($guild_id, $data["id"], $data["username"], strval($data["discriminator"]), $data["public_flags"] ?? 0, $data["avatar"] ?? "");
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
		return new GuildMember($guild_id, $member["user"]["id"], $member["user"]["username"], strval($member["user"]["discriminator"]), $member["roles"] ?? [], $member["user"]["bot"] ?? false,  ($member["nick"] ?? $member["user"]["username"]), ["user"]["public_flags"] ?? 0, $member["user"]["avatar"] ?? "", $member["joined_at"], @$member["premium_since"]);
	}
}