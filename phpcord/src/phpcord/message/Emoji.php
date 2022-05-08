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

namespace phpcord\message;

use JetBrains\PhpStorm\Pure;
use phpcord\async\completable\Completable;
use phpcord\Discord;
use phpcord\guild\Guild;
use phpcord\http\RestAPI;

class Emoji extends PartialEmoji {
	
	/**
	 * @param int|null $id
	 * @param string|null $name
	 * @param array $roles
	 * @param bool $animated
	 * @param int $guildId
	 * @param bool $requireColons
	 */
	#[Pure] public function __construct(?int $id, ?string $name, private int $guildId, private array $roles = [], bool $animated = false, private bool $requireColons = false) {
		parent::__construct($name, $id, $animated);
	}
	
	/**
	 * @return int[]
	 */
	public function getRoles(): array {
		return $this->roles;
	}
	
	/**
	 * @return bool
	 */
	public function requiresColons(): bool {
		return $this->requireColons;
	}
	
	/**
	 * @return int
	 */
	public function getGuildId(): int {
		return $this->guildId;
	}
	
	public function getGuild(): Guild {
		return Discord::getInstance()->getClient()->getGuilds()->get($this->getGuildId());
	}
	
	protected function update(array $data): Completable {
		return RestAPI::getInstance()->updateEmoji($this->getGuildId(), $this->getId(), $data);
	}
	
	public function delete(): Completable {
		return RestAPI::getInstance()->deleteEmoji($this->getGuildId(), $this->getId());
	}
	
	public function setName(string $name): Completable {
		return $this->update([ 'name' => $name ]);
	}
	
	/**
	 * @param int[] $roles The new roles allowed to use the emoji
	 *
	 * @return Completable
	 */
	public function setRoles(array $roles): Completable {
		return $this->update([ 'roles' => $roles ]);
	}
	
	#[Pure] public static function fromArray(array $array): Emoji {
		return new Emoji(@$array['id'], @$array['name'], $array['guild_id'], ['roles'] ?? [], $array['animated'] ?? false, $array['requires_colon'] ?? false);
	}
}