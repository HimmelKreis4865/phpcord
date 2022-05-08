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

namespace phpcord\event\role;

use JetBrains\PhpStorm\Pure;
use phpcord\guild\permissible\Role;

class RoleUpdateEvent extends RoleEvent {
	
	#[Pure] public function __construct(Role $role, private ?Role $old) {
		parent::__construct($role);
	}
	
	/**
	 * @return Role|null
	 */
	public function getOldRole(): ?Role {
		return $this->old;
	}
}