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

namespace phpcord\channel\helper;

use phpcord\async\completable\Completable;
use phpcord\http\RestAPI;

trait InvitableTrait {
	
	/**
	 * @param int $maxUses
	 * @param int $maxAge
	 * @param bool $temporary
	 * @param bool $unique
	 * @param string|null $reason
	 *
	 * @return Completable
	 */
	public function createInvitation(int $maxUses, int $maxAge, bool $temporary = false, bool $unique = false, string $reason = null): Completable {
		return RestAPI::getInstance()->createInvitation($this->getId(), [
			'max_uses' => $maxUses,
			'max_age' => $maxAge,
			'temporary' => $temporary,
			'unique' => $unique
		], $reason);
	}
	
	/**
	 * @return int
	 */
	abstract public function getId(): int;
}