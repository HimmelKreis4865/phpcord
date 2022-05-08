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

namespace phpcord\guild\components;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int INCOMING()
 * @method static int CHANNEL_FOLLOWER()
 * @method static int APPLICATION()
 */
final class WebhookTypes {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('INCOMING', 0);
		self::register('CHANNEL_FOLLOWER', 1);
		self::register('APPLICATION', 2);
	}
}