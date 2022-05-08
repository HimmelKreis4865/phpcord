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

class BasePermissionData implements IPermissionData {
	
	/** @var Permission $permission */
	private Permission $permission;
	
	#[Pure] public function __construct(int $permission = 0) {
		$this->permission = new Permission($permission);
	}
	
	#[Pure] public function hasPermission(int $permission): bool {
		return $this->permission->has($permission);
	}
	
	public function setPermission(int $permission): void {
		$this->permission->set($permission);
	}
	
	public function removePermission(int $permission): void {
		$this->permission->remove($permission);
	}
	
	/**
	 * @return Permission
	 */
	public function getPermission(): Permission {
		return $this->permission;
	}
}