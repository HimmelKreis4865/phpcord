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

namespace phpcord\guild\permissible\data;

use JetBrains\PhpStorm\Pure;
use phpcord\guild\permissible\Permission;

class DualPermissionData implements IPermissionData {
	
	/** @var Permission $allow */
	private Permission $allow;
	
	/** @var Permission $deny */
	private Permission $deny;
	
	/**
	 * @param int $allow
	 * @param int $deny
	 */
	#[Pure] public function __construct(int $allow, int $deny) {
		$this->allow = new Permission($allow);
		$this->deny = new Permission($deny);
	}
	
	#[Pure] public function hasPermission(int $permission): bool {
		return $this->allow->has($permission);
	}
	
	public function setPermission(int $permission): void {
		if (!$this->allow->has($permission)) $this->allow->set($permission);
		if ($this->deny->has($permission)) $this->deny->remove($permission);
	}
	
	public function removePermission(int $permission): void {
		if ($this->allow->has($permission)) $this->allow->remove($permission);
		if (!$this->deny->has($permission)) $this->deny->set($permission);
	}
	
	/**
	 * @return Permission
	 */
	public function getAllow(): Permission {
		return $this->allow;
	}
	
	/**
	 * @return Permission
	 */
	public function getDeny(): Permission {
		return $this->deny;
	}
}