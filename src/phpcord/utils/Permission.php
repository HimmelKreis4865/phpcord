<?php

namespace phpcord\utils;

use function is_array;
use function is_numeric;
use function strval;

class Permission {
	
	protected $allow = 0;
	
	protected $deny = 0;
	
	public static function fromValues(int $allow, int $deny): string {
		$permission = 0;
		$permission |= $allow;
		$permission &= ~$deny;
		return strval($permission);
	}
	
	/**
	 * Permission constructor.
	 * @param int|array $allow
	 * @param int|array $deny
	 */
	public function __construct($allow = 0, $deny = 0) {
		if (is_array($allow)) {
			$tmp = 0;
			foreach ($allow as $item) {
				$tmp |= $item;
			}
			$allow = $tmp;
		}
		$this->allow = $allow;
		if (is_array($deny)) {
			$tmp = 0;
			foreach ($deny as $item) {
				$tmp |= $item;
			}
			$deny = $tmp;
		}
		$this->deny = $deny;
	}
	
	/**
	 * Adds an allowed permission to the instance
	 *
	 * @api
	 *
	 * @param array|int $permission
	 */
	public function addAllow($permission) {
		if (is_array($permission)) {
			foreach ($permission as $item) {
				$this->allow |= $item;
			}
		} else if (is_numeric($permission)) {
			$this->allow |= $permission;
		}
	}
	
	/**
	 * Adds a denied permission for the instance
	 *
	 * @api
	 *
	 * @param array|int $permission
	 */
	public function addDeny($permission) {
		if (is_array($permission)) {
			foreach ($permission as $item) {
				$this->deny |= $item;
			}
		} else if (is_numeric($permission)) {
			$this->deny |= $permission;
		}
	}
	
	/**
	 * Returns the int value of the instance
	 * @see toString()
	 *
	 * @api
	 *
	 * @return int
	 */
	public function toInt(): int {
		return intval($this->toString());
	}
	
	/**
	 * Returns the string value of the instance
	 * @see __toString()
	 *
	 * @api
	 *
	 * @return string
	 */
	public function toString(): string {
		return $this->__toString();
	}
	
	/**
	 * Combines allow and deny to a string permission that can be used for interacting with discord
	 *
	 * @api
	 *
	 * @return string
	 */
	public function __toString(): string {
		return self::fromValues($this->allow, $this->deny);
	}
	
	/**
	 * @return string
	 */
	public function getAllow(): string {
		return strval($this->allow);
	}
	
	/**
	 * @return string
	 */
	public function getDeny(): string {
		return strval($this->deny);
	}
}


