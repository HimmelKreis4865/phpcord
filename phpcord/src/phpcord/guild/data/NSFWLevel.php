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

namespace phpcord\guild\data;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int DEFAULT()
 * @method static int EXPLICIT()
 * @method static int SAFE()
 * @method static int AGE_RESTRICTED()
 */
final class NSFWLevel {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('DEFAULT', 0);
		self::register('EXPLICIT', 1);
		self::register('SAFE', 2);
		self::register('AGE_RESTRICTED', 3);
	}
}