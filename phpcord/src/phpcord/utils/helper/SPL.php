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

use function is_object;
use function spl_object_hash;
use function spl_object_id;

final class SPL {
	
	public static function id(object|int $object): int {
		return (is_object($object) ? spl_object_id($object) : $object);
	}
	
	public static function hash(object $object): string {
		return spl_object_hash($object);
	}
}