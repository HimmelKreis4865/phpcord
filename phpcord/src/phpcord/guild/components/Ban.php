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

namespace phpcord\guild;

use phpcord\async\completable\Completable;
use phpcord\http\RestAPI;
use phpcord\user\User;
use phpcord\utils\Utils;

class Ban {
	
	/**
	 * @param User $user
	 * @param int $guildId
	 * @param string|null $reason
	 */
	public function __construct(private User $user, private int $guildId, private ?string $reason) { }
	
	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}
	
	/**
	 * @return string|null
	 */
	public function getReason(): ?string {
		return $this->reason;
	}
	
	/**
	 * @return Completable
	 */
	public function remove(): Completable {
		return RestAPI::getInstance()->removeBan($this->guildId, $this->getUser()->getId());
	}
	
	public static function fromArray(array $array): ?Ban {
		if (!Utils::contains($array, 'user', 'guild_id')) return null;
		return new Ban(User::fromArray($array['user']), $array['guild_id'], @$array['reason']);
	}
}