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
 * @method static int NONE()  unrestricted
 * @method static int LOW()  must have verified email on account
 * @method static int MEDIUM()  must be registered on Discord for longer than 5 minutes
 * @method static int HIGH()  must be a member of the server for longer than 10 minutes
 * @method static int VERY_HIGH()  must have a verified phone number
 */
final class VerificationLevel {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('NONE', 0);
		self::register('LOW', 1);
		self::register('MEDIUM', 2);
		self::register('HIGH', 3);
		self::register('VERY_HIGH', 4);
	}
}