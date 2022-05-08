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

namespace phpcord\message;

use phpcord\utils\enum\EnumTrait;

/**
 * @method static int CROSSPOSTED()
 * @method static int IS_CROSSPOST()
 * @method static int SUPPRESS_EMBEDS()
 * @method static int SOURCE_MESSAGE_DELETED()
 * @method static int URGENT()
 * @method static int HAS_THREAD()
 * @method static int EPHEMERAL()
 * @method static int LOADING()
 */
final class MessageFlags {
	use EnumTrait;
	
	protected static function make(): void {
		self::register('CROSSPOSTED',	1 << 0);
		self::register('IS_CROSSPOST', 1 << 1);
		self::register('SUPPRESS_EMBEDS',	1 << 2);
		self::register('SOURCE_MESSAGE_DELETED', 1 << 3);
		self::register('URGENT', 1 << 4);
		self::register('HAS_THREAD', 1 << 5);
		self::register('EPHEMERAL', 1 << 6);
		self::register('LOADING',	1 << 7);
	}
}