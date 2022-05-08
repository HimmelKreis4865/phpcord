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

use InvalidArgumentException;
use phpcord\async\completable\Completable;
use phpcord\guild\permissible\data\IPermissionData;

/**
 * @implements IPermissionData
 */
trait PermissionUpdateTrait {
	
	abstract public function getPermissionData(): IPermissionData;
	
	abstract protected function syncToDiscord(): Completable;
	
	public function hasPermission(int $permission): bool {
		return $this->getPermissionData()->hasPermission($permission);
	}
	
	/**
	 * @param int $permission
	 *
	 * @return Completable
	 */
	public function setPermission(int $permission): Completable {
		if (!$this->getPermissionData()->hasPermission($permission)) {
			$this->getPermissionData()->setPermission($permission);
			return $this->syncToDiscord();
		}
		return Completable::fail(new InvalidArgumentException('Permission ' . PermissionIds::__findValue($permission) . ' is already set.'));
	}
	
	/**
	 * @param int $permission
	 *
	 * @return Completable
	 */
	public function removePermission(int $permission): Completable {
		if ($this->getPermissionData()->hasPermission($permission)) {
			$this->getPermissionData()->setPermission($permission);
			return $this->syncToDiscord();
		}
		return Completable::fail(new InvalidArgumentException('Permission ' . PermissionIds::__findValue($permission) . ' is not set.'));
	}
}