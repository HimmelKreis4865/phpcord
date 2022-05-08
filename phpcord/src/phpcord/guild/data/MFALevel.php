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
 * @method static int NONE()  guild has no 2FA requirement for moderation actions
 * @method static int ELEVATED()  guild has a 2FA requirement for moderation actions
 *
 * MFA = MultiFactorAuthorization
 */
final class MFALevel {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('NONE', 0);
		self::register('ELEVATED', 1);
	}
}