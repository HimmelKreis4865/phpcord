<?php

namespace phpcord\utils;

use phpcord\guild\GuildMember;
use phpcord\user\User;

class MemberInitializer {
	public static function createUser(array $data, string $guild_id): User {
		return new User($guild_id, $data["id"], $data["username"], strval($data["discriminator"]), $data["public_flags"] ?? 0, $data["avatar"] ?? "");
	}

	public static function createMember(array $member, string $guild_id): GuildMember {
		return new GuildMember($guild_id, $member["user"]["id"], $member["user"]["username"], strval($member["user"]["discriminator"]), $member["roles"] ?? [], $member["user"]["bot"] ?? false,  ($member["nick"] ?? $member["user"]["username"]), ["user"]["public_flags"] ?? 0, $member["user"]["avatar"] ?? "", $member["joined_at"], @$member["premium_since"]);
	}
}


