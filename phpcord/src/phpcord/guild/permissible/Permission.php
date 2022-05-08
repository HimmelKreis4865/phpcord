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

namespace phpcord\guild\permissible;

final class Permission {
	
	public function __construct(private int $permissionBit = 0) { }
	
	public function set(int $permission): void {
		$this->permissionBit |= $permission;
	}
	
	public function has(int $permission): bool {
		return (($this->permissionBit & $permission) === $permission);
	}
	
	public function remove(int $permissionBit): void {
		$this->permissionBit &= ~$permissionBit;
	}
	
	/**
	 * @param int $permissionBit
	 */
	public function setPermissionBit(int $permissionBit): void {
		$this->permissionBit = $permissionBit;
	}
	
	/**
	 * @return int
	 */
	public function getPermissionBit(): int {
		return $this->permissionBit;
	}
}