<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\channel\overwrite;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\channel\GuildChannel;
use phpcord\guild\GuildMember;
use phpcord\guild\permissible\Permission;
use phpcord\utils\Utils;

class MemberPermissionOverwrite extends PermissionOverwrite implements JsonSerializable {
	
	/**
	 * @param GuildChannel $channel
	 * @param int $allowBit
	 * @param int $denyBit
	 * @param int $id
	 */
	#[Pure] public function __construct(GuildChannel $channel, int $allowBit, int $denyBit, private int $id) {
		parent::__construct($channel, $allowBit, $denyBit);
	}
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	public function getMember(): ?GuildMember {
		return $this->getChannel()->getGuild()->getMembers()->get($this->id);
	}
	
	#[Pure] public static function fromArray(GuildChannel $channel, array $array): ?MemberPermissionOverwrite {
		if (!Utils::contains($array, 'allow', 'deny', 'id')) return null;
		return new MemberPermissionOverwrite($channel, $array['allow'], $array['deny'], $array['id']);
	}
	
	#[Pure] #[ArrayShape(['type' => "int", 'id' => "int", 'allow' => "int", 'deny' => "int"])]
	public static function build(int $userId, Permission|int $allow, Permission|int $deny = 0): array {
		return [
			'type' => 1,
			'id' => $userId,
			'allow' => ($allow instanceof Permission ? $allow->getPermissionBit() : $allow),
			'deny' => ($deny instanceof Permission ? $deny->getPermissionBit() : $deny),
		];
	}
	
	#[ArrayShape(['type' => "int", 'id' => "int", 'allow' => "int", 'deny' => "int"])] public function jsonSerialize(): array {
		return [
			'type' => 1,
			'id' => $this->id,
			'allow' => $this->getPermissionData()->getAllow()->getPermissionBit(),
			'deny' => $this->getPermissionData()->getDeny()->getPermissionBit(),
		];
	}
}