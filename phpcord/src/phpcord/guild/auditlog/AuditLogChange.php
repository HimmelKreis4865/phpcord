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

namespace phpcord\guild\auditlog;

use JetBrains\PhpStorm\Pure;

class AuditLogChange {
	
	public function __construct(private string $key, private mixed $oldValue, private mixed $newValue) { }
	
	/**
	 * @return string
	 */
	public function getKey(): string {
		return $this->key;
	}
	
	/**
	 * @return mixed
	 */
	public function getOldValue(): mixed {
		return $this->oldValue;
	}
	
	/**
	 * @return mixed
	 */
	public function getNewValue(): mixed {
		return $this->newValue;
	}
	
	#[Pure] public static function fromArray(array $array): AuditLogChange {
		return new AuditLogChange($array['key'], @$array['old_value'], @$array['new_value']);
	}
}