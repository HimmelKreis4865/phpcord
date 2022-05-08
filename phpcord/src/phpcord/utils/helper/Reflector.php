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

namespace phpcord\utils\helper;

use ReflectionException;
use ReflectionProperty;

/**
 * @internal
 */
final class Reflector {
	
	/**
	 * @param object $object
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return void
	 */
	public static function modifyProperty(object $object, string $name, mixed $value): void {
		try {
			($r = new ReflectionProperty($object, $name))->setAccessible(true);
			$r->setValue($object, $value);
		} catch (ReflectionException) { }
	}
}