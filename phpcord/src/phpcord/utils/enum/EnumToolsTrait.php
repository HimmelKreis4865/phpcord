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

namespace phpcord\utils\enum;

use JetBrains\PhpStorm\Pure;

/**
 * @internal
 */
trait EnumToolsTrait {
	use EnumTrait;
	
	public static function __getMemberRaw(string $name): mixed {
		return self::$members->get($name);
	}
	
	#[Pure] public static function __getMembers(): array {
		return self::$members->asArray();
	}
	
	/**
	 * @param mixed $value
	 *
	 * @return string|null the name of the member or null
	 */
	public static function __findValue(mixed $value): ?string {
		foreach (self::$members as $k => $v) if ($v === $value) return $k;
		return null;
	}
}