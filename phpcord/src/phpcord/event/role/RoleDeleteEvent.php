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

use phpcord\event\Event;
use phpcord\guild\permissible\Role;

class RoleDeleteEvent extends Event {
	
	/**
	 * @param Role|null $role
	 * @param int $roleId
	 * @param int $guildId
	 */
	public function __construct(private ?Role $role, private int $roleId, private int $guildId) { }
	
	/**
	 * May not be existent, since it returns the cached role
	 * Any modifications on it will fail
	 *
	 * @return Role|null
	 */
	public function getRole(): ?Role {
		return $this->role;
	}
	
	/**
	 * @return int
	 */
	public function getRoleId(): int {
		return $this->roleId;
	}
	
	/**
	 * @return int
	 */
	public function getGuildId(): int {
		return $this->guildId;
	}
}