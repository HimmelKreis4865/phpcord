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

namespace phpcord\utils;

use JetBrains\PhpStorm\Pure;

final class RtcRegion {
	
	public function __construct(private string $id, private string $name, private bool $custom, private bool $deprecated, private bool $optimal) { }
	
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return bool
	 */
	public function isCustom(): bool {
		return $this->custom;
	}
	
	/**
	 * @return bool
	 */
	public function isDeprecated(): bool {
		return $this->deprecated;
	}
	
	/**
	 * @return bool
	 */
	public function isOptimal(): bool {
		return $this->optimal;
	}
	
	#[Pure] public static function fromArray(array $array): ?RtcRegion {
		if (!Utils::contains($array, 'id', 'name')) return null;
		return new RtcRegion($array['id'], $array['name'], $array['custom'] ?? false, $array['deprecated'] ?? false, $array['optimal'] ?? false);
	}
}