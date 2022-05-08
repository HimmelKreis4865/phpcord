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

namespace phpcord\event\guild;

use JetBrains\PhpStorm\Pure;
use phpcord\guild\Guild;
use phpcord\user\User;

class GuildUserUnbanEvent extends GuildEvent {
	
	/**
	 * @param Guild $guild
	 * @param User $user
	 */
	#[Pure] public function __construct(Guild $guild, private User $user) {
		parent::__construct($guild);
	}
	
	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}
}